<?php

namespace App\Services;

use App\Models\Prodi;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class ProdiSyncService
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
                throw new \RuntimeException('Gagal mengambil data Prodi setelah refresh token.');
            }
        }

        $count = 0;
        foreach ($items as $row) {
            $data = $this->mapProdiPayload($row);
            if (empty($data['id_prodi'])) {
                continue; // skip jika tidak ada id
            }

            Prodi::updateOrCreate(
                ['id_prodi' => $data['id_prodi']],
                Arr::except($data, ['id_prodi'])
            );
            $count++;
        }

        return $count;
    }

    /**
     * Panggil API GetProdi dengan filter aktif.
     * @return array [bool $success, array $items]
     */
    protected function tryFetch(string $token): array
    {
        try {
            $url = Config::get('services.live2.base_url');

            $payload = [
                'act'    => 'GetProdi',
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
                throw new \RuntimeException("GetProdi gagal: " . ($desc ?: 'Unknown error'));
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
    protected function mapProdiPayload(array $r): array
    {
        return [
            'id_prodi'                  => $r['id_prodi'] ?? null,
            'kode_program_studi'        => $r['kode_program_studi'] ?? null,
            'nama_program_studi'        => $r['nama_program_studi'] ?? null,
            'status'                    => $r['status'] ?? null,
            'nama_jenjang_pendidikan'   => $r['nama_jenjang_pendidikan'] ?? null,
        ];
    }
}
