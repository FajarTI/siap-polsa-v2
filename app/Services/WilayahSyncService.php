<?php

namespace App\Services;

use App\Models\Wilayah;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class WilayahSyncService
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
                throw new \RuntimeException('Gagal mengambil data Wilayah setelah refresh token.');
            }
        }

        $count = 0;
        foreach ($items as $row) {
            $data = $this->mapWilayahPayload($row);
            if (empty($data['id_wilayah'])) {
                continue; // skip jika tidak ada id
            }

            Wilayah::updateOrCreate(
                ['id_wilayah' => $data['id_wilayah']],
                Arr::except($data, ['id_wilayah'])
            );
            $count++;
        }

        return $count;
    }

    /**
     * Panggil API GetWilayah dengan filter aktif.
     * @return array [bool $success, array $items]
     */
    protected function tryFetch(string $token): array
    {
        try {
            $url = Config::get('services.live2.base_url');

            $payload = [
                'act'    => 'GetWilayah',
                'filter' => 'id_induk_wilayah is not null',
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
                throw new \RuntimeException("GetWilayah gagal: " . ($desc ?: 'Unknown error'));
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
    protected function mapWilayahPayload(array $r): array
    {
        return [
            'id_wilayah' => trim($r['id_wilayah']) ?? null,
            'id_level_wilayah' => trim($r['id_level_wilayah']) ?? null,
            'id_negara' => trim($r['id_negara']) ?? null,
            'nama_wilayah' => trim($r['nama_wilayah']) ?? null,
            'id_induk_wilayah' => trim($r['id_induk_wilayah']) ?? null,
        ];
    }
}
