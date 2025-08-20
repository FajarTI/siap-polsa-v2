<?php

namespace App\Services;

use App\Models\JenjangPendidikan;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class JenjangPendidikanSyncService
{
    public function __construct(
        protected TokenService $tokenService
    ) {}

    /**
     * Return: jumlah baris yang tersimpan/ter-update.
     */
    public function syncActiveLecturers(): int
    {

        $token = $this->tokenService->getValidToken();

        [$ok, $items] = $this->tryFetch($token);

        if (!$ok) {
            $token = $this->tokenService->refreshToken();
            [$ok, $items] = $this->tryFetch($token);
            if (!$ok) {
                throw new \RuntimeException('Gagal mengambil data JenjangPendidikan setelah refresh token.');
            }
        }

        // dd($items);

        $count = 0;
        foreach ($items as $row) {
            $data = $this->mapJenjangPendidikanPayload($row);
            if (empty($data['id_jenjang_didik'])) {
                continue; // skip jika tidak ada id
            }

            JenjangPendidikan::updateOrCreate(
                ['id_jenjang_didik' => $data['id_jenjang_didik']],
                Arr::except($data, ['id_jenjang_didik'])
            );
            $count++;
        }

        

        return $count;
    }

    /**
     * Panggil API GetJenjangPendidikan dengan filter aktif.
     * @return array [bool $success, array $items]
     */
    protected function tryFetch(string $token): array
    {
        try {
            $url = Config::get('services.live2.base_url');

            $payload = [
                'act'    => 'GetJenjangPendidikan',
                'filter' => '',
                'token'  => $token,
            ];

            $res = Http::timeout(30)
                ->acceptJson()
                ->asJson()
                ->post($url, $payload);

            if (!$res->ok()) {
                if ($res->status() === 401) {
                    return [false, []];
                }
                $res->throw();
            }

            $json = $res->json();

            $err = (int) data_get($json, 'error_code', 1);
            if ($err !== 0) {
                $desc = (string) data_get($json, 'error_desc', '');
                if (Str::of(Str::lower($desc))->contains(['token', 'invalid', 'expire', 'expired', 'kadaluarsa'])) {
                    return [false, []];
                }
                throw new \RuntimeException("GetJenjangPendidikan gagal: " . ($desc ?: 'Unknown error'));
            }

            $items = data_get($json, 'data', []);
            if (!is_array($items)) {
                $items = [];
            }

            return [true, $items];
        } catch (Throwable $e) {
            if (Str::of(Str::lower($e->getMessage()))->contains(['401', 'unauthorized', 'token'])) {
                return [false, []];
            }
            throw $e;
        }
    }

    /**
     * Pemetaan field dari API ke kolom tabel kita.
     * Beberapa API Feeder pakai nama berbeda, sediakan alias umum.
     */
    protected function mapJenjangPendidikanPayload(array $r): array
    {
        return [
            'id_jenjang_didik' => $r['id_jenjang_didik'] ?? null,
            'nama_jenjang_didik' => $r['nama_jenjang_didik'] ?? null,
        ];
    }
}
