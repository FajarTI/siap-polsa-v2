<?php

namespace App\Services;

use App\Models\Semester;
use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class SemesterSyncService
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
                throw new \RuntimeException('Gagal mengambil data Semester setelah refresh token.');
            }
        }

        $count = 0;
        foreach ($items as $row) {
            $data = $this->mapSemesterPayload($row);
            if (empty($data['id_semester'])) {
                continue; // skip jika tidak ada id
            }

            Semester::updateOrCreate(
                ['id_semester' => $data['id_semester']],
                Arr::except($data, ['id_semester'])
            );
            $count++;
        }

        return $count;
    }

    /**
     * Panggil API GetSemester dengan filter aktif.
     * @return array [bool $success, array $items]
     */
    protected function tryFetch(string $token): array
    {
        try {
            $url = Config::get('services.live2.base_url');

            $payload = [
                'act'    => 'GetSemester',
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
                throw new \RuntimeException("GetSemester gagal: " . ($desc ?: 'Unknown error'));
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
    protected function mapSemesterPayload(array $r): array
    {
        return [
            'id_semester'      => $r['id_semester'] ?? null,
            'id_tahun_ajaran'    => $r['id_tahun_ajaran'] ?? null,
            'nama_semester'         => $r['nama_semester'] ?? null,
            'semester'         => $r['semester'] ?? null,
            'a_periode_aktif'         => $r['a_periode_aktif'] ?? null,
        ];
    }
}
