<?php

namespace App\Services;

use App\Models\ApiToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use RuntimeException;

class TokenService
{
    public function __construct(
        protected string $provider = '',
    ) {
        $this->provider = $this->provider ?: Config::get('services.live2.provider', 'live2');
    }

    /**
     * Ambil token valid; jika tidak ada/akan kadaluarsa -> refresh.
     */
    public function getValidToken(): string
    {
        $buffer = (int) Config::get('services.live2.expiry_buffer', 120);

        $row = ApiToken::where('provider', $this->provider)->first();

        $needsRefresh = true;
        if ($row && $row->token) {
            $exp = $row->expired_at;
            // kalau tidak ada expired_at (token bukan JWT atau gagal parse), paksa refresh
            $needsRefresh = !$exp || $exp->lte(now()->addSeconds($buffer));
        }

        if (!$needsRefresh) {
            return $row->token;
        }

        // Hindari race condition: pakai cache lock
        $lockKey = "token-refresh:{$this->provider}";
        return Cache::lock($lockKey, 10)->block(10, function () use ($buffer) {
            // cek lagi di dalam lock (double-check)
            $existing = ApiToken::where('provider', $this->provider)->first();
            if ($existing && $existing->token && $existing->expired_at && $existing->expired_at->gt(now()->addSeconds($buffer))) {
                return $existing->token;
            }
            $tokenData = $this->fetchTokenFromServer();
            return $this->saveToken($tokenData);
        });
    }

    /**
     * Paksa refresh token baru dari server (abaikan token lama).
     */
    public function refreshToken(): string
    {
        $tokenData = $this->fetchTokenFromServer();
        return $this->saveToken($tokenData);
    }

    /**
     * Panggil endpoint GetToken.
     */
    protected function fetchTokenFromServer(): array
    {
        $url = Config::get('services.live2.base_url');
        $username = Config::get('services.live2.username');
        $password = Config::get('services.live2.password');

        $payload = [
            'act' => 'GetToken',
            'username' => $username,
            'password' => $password,
        ];

        $res = Http::timeout(15)->retry(2, 500)->post($url, $payload);

        if (!$res->ok()) {
            throw new RuntimeException("Gagal menghubungi server token: HTTP " . $res->status());
        }

        $json = $res->json();

        if (!is_array($json) || ($json['error_code'] ?? 1) !== 0) {
            $desc = $json['error_desc'] ?? 'Unknown error';
            throw new RuntimeException("GetToken gagal: " . $desc);
        }

        $token = data_get($json, 'data.token');
        if (!$token) {
            throw new RuntimeException("Response tidak mengandung token.");
        }

        return [
            'token' => $token,
            'raw'   => $json,
        ];
    }

    /**
     * Simpan / update token di DB (upsert), hitung expired_at dari payload JWT.
     */
    protected function saveToken(array $tokenData): string
    {
        $token = $tokenData['token'];

        $expiredAt = $this->parseJwtExpiry($token); // bisa null bila bukan JWT

        ApiToken::updateOrCreate(
            ['provider' => $this->provider],
            [
                'token'        => $token,
                'expired_at'   => $expiredAt,
                'refreshed_at' => now(),
            ]
        );

        return $token;
    }

    /**
     * Ambil claim `exp` dari JWT tanpa verifikasi signature (cukup untuk estimasi expiry).
     */
    protected function parseJwtExpiry(?string $jwt): ?Carbon
    {
        if (!$jwt || !Str::contains($jwt, '.')) {
            return null;
        }
        $parts = explode('.', $jwt);
        if (count($parts) < 2) {
            return null;
        }
        $payload = $this->base64UrlDecode($parts[1]);
        if (!$payload) {
            return null;
        }
        $data = json_decode($payload, true);
        if (!is_array($data) || empty($data['exp'])) {
            return null;
        }
        // exp dalam detik epoch (UTC)
        return Carbon::createFromTimestampUTC((int) $data['exp']);
    }

    protected function base64UrlDecode(string $input): ?string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $input .= str_repeat('=', 4 - $remainder);
        }
        $decoded = base64_decode(strtr($input, '-_', '+/'));
        return $decoded === false ? null : $decoded;
    }
}
