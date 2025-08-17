<?php

namespace App\Services;

use Throwable;
use App\Models\Dosen;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class DosenSyncService
{
    public function __construct(
        protected TokenService $tokenService
    ) {}

    /**
     * Sinkron dosen aktif (id_status_aktif = 1).
     * Return: jumlah baris yang tersimpan/ter-update.
     */
    public function syncActiveLecturers(): int
    {
        // 1) ambil token valid
        $token = $this->tokenService->getValidToken();

        // 2) coba fetch data
        [$ok, $items] = $this->tryFetch($token);

        // 3) kalau gagal karena token, refresh lalu retry sekali
        if (!$ok) {
            $token = $this->tokenService->refreshToken();
            [$ok, $items] = $this->tryFetch($token);
            if (!$ok) {
                throw new \RuntimeException('Gagal mengambil data Dosen setelah refresh token.');
            }
        }

        // 4) simpan ke DB (upsert by id_dosen)
        $count = 0;
        foreach ($items as $row) {
            $data = $this->mapDosenPayload($row);
            if (empty($data['id_dosen'])) {
                continue; // skip jika tidak ada id
            }

            Dosen::updateOrCreate(
                ['id_dosen' => $data['id_dosen']],
                Arr::except($data, ['id_dosen'])
            );
            $count++;
        }

        return $count;
    }

    /**
     * Panggil API DetailBiodataDosen dengan filter aktif.
     * @return array [bool $success, array $items]
     */
    protected function tryFetch(string $token): array
    {
        try {
            $url = Config::get('services.live2.base_url');

            $payload = [
                'act'    => 'DetailBiodataDosen',
                'filter' => 'id_status_aktif = 1',
                'token'  => $token, // API ini minta token di body
            ];

            $res = Http::timeout(30)
                ->acceptJson()
                ->asJson()
                ->post($url, $payload);

            if (!$res->ok()) {
                // beberapa API return 401 jika token invalid
                if ($res->status() === 401) {
                    return [false, []];
                }
                $res->throw();
            }

            $json = $res->json();

            // Pola response mengikuti contoh GetToken:
            // { "error_code": 0, "error_desc": "", "data": [...] }
            $err = (int) data_get($json, 'error_code', 1);
            if ($err !== 0) {
                $desc = (string) data_get($json, 'error_desc', '');
                // heuristik: bila pesan mengandung token invalid/expired, tandai untuk refresh
                if (Str::of(Str::lower($desc))->contains(['token', 'invalid', 'expire', 'expired', 'kadaluarsa'])) {
                    return [false, []];
                }
                throw new \RuntimeException("DetailBiodataDosen gagal: " . ($desc ?: 'Unknown error'));
            }

            $items = data_get($json, 'data', []);
            if (!is_array($items)) {
                $items = [];
            }

            return [true, $items];
        } catch (Throwable $e) {
            // jika exception HTTP 401/unauthorized, anggap token issue -> refresh
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
    protected function mapDosenPayload(array $r): array
    {
        // fungsi bantu untuk parse tanggal
        $toDate = function ($val) {
            if (empty($val)) return null;
            try {
                return \Carbon\Carbon::parse($val)->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        };

        return [
            'id_dosen'      => $r['id_dosen'] ?? null,
            'nama_dosen'    => $r['nama_dosen'] ?? null,
            'email'         => $r['email'] ?? null,
            'no_hp'         => $r['handphone'] ?? null, // pakai handphone dari API
            'agama'         => $r['nama_agama'] ?? null, // ambil nama agama
            'tempat_lahir'  => $r['tempat_lahir'] ?? null,
            'tanggal_lahir' => $toDate($r['tanggal_lahir'] ?? null),
            'jenis_kelamin' => $r['jenis_kelamin'] ?? null,
            'nip'           => $r['nip'] ?? null,
            'nuptk'         => $r['nuptk'] ?? null,
            'nidn'          => $r['nidn'] ?? null,
        ];
    }
}
