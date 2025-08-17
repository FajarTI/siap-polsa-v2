<?php

namespace App\Services;

use Throwable;
use App\Models\MahasiswaRegister;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class MahasiswaRegisterSyncService
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
                throw new \RuntimeException('Gagal mengambil data Mahasiswa Register setelah refresh token.');
            }
        }

        // 4) simpan ke DB (upsert by id_mahasiswa)
        $count = 0;
        foreach ($items as $row) {
            $data = $this->mapMahasiswaRegisterPayload($row);

            if (empty($data['id_registrasi_mahasiswa'])) {
                continue;
            }

            if (!empty($data['nim']) && MahasiswaRegister::where('nim', $data['nim'])->exists()) {
                continue;
            }

            MahasiswaRegister::updateOrCreate(
                ['id_registrasi_mahasiswa' => $data['id_registrasi_mahasiswa']],
                Arr::except($data, ['id_registrasi_mahasiswa'])
            );

            $count++;
        }


        return $count;
    }

    /**
     * Panggil API DetailBiodataMahasiswaRegister dengan filter aktif.
     * @return array [bool $success, array $items]
     */
    protected function tryFetch(string $token): array
    {
        try {
            $url = Config::get('services.live2.base_url');

            $payload = [
                'act'    => 'GetListRiwayatPendidikanMahasiswa',
                'filter' => '',
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
                throw new \RuntimeException("DetailBiodataMahasiswaRegister gagal: " . ($desc ?: 'Unknown error'));
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
    protected function mapMahasiswaRegisterPayload(array $r): array
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
            'id_mahasiswa' => $r['id_mahasiswa'] ?? null,
            'id_registrasi_mahasiswa' => $r['id_registrasi_mahasiswa'] ?? null,
            'nim' => $r['nim'] ?? null,
            'id_jenis_daftar' => $r['id_jenis_daftar'] ?? null,
            'tanggal_daftar' => $toDate($r['tanggal_daftar'] ?? null),
            'id_jalur_daftar'  => $r['id_jalur_daftar'] ?? '',
            'id_periode_masuk' => $r['id_periode_masuk'] ?? null,
            'id_perguruan_tinggi' => $r['id_perguruan_tinggi'] ?? null,
            'id_prodi' => $r['id_prodi'] ?? null,
            'sks_diakui' => $r['sks_diakui'] ?? null,
            'id_perguruan_tinggi_asal' => $r['id_perguruan_tinggi_asal'] ?? null,
            'id_prodi_asal' => $r['id_prodi_asal'] ?? null,
            'id_pembiayaan' => $r['id_pembiayaan'] ?? null,
        ];
    }
}
