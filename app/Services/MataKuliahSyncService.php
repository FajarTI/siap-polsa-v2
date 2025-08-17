<?php

namespace App\Services;

use Throwable;
use App\Models\MataKuliah;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class MataKuliahSyncService
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
                throw new \RuntimeException('Gagal mengambil data MataKuliah setelah refresh token.');
            }
        }

        // 4) simpan ke DB (upsert by id_matkul)
        $count = 0;
        foreach ($items as $row) {
            $data = $this->mapMataKuliahPayload($row);
            if (empty($data['id_matkul'])) {
                continue; // skip jika tidak ada id
            }

            MataKuliah::updateOrCreate(
                ['id_matkul' => $data['id_matkul']],
                Arr::except($data, ['id_matkul'])
            );
            $count++;
        }

        return $count;
    }

    /**
     * Panggil API DetailBiodataMataKuliah dengan filter aktif.
     * @return array [bool $success, array $items]
     */
    protected function tryFetch(string $token): array
    {
        try {
            $url = Config::get('services.live2.base_url');

            $payload = [
                'act'    => 'GetDetailMataKuliah',
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
                throw new \RuntimeException("DetailBiodataMataKuliah gagal: " . ($desc ?: 'Unknown error'));
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
    protected function mapMataKuliahPayload(array $r): array
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
            'id_matkul'      => $r['id_matkul'] ?? null,
            'kode_mata_kuliah'    => $r['kode_mata_kuliah'] ?? null,
            'id_prodi'         => $r['id_prodi'] ?? null,
            'nama_mata_kuliah'         => $r['nama_mata_kuliah'] ?? null, // pakai handphone dari API
            'id_jenis_matkul'         => $r['id_jenis_mata_kuliah'] ?? null, // ambil nama agama
            'id_kelompok_mata_kuliah'  => $r['id_kelompok_mata_kuliah'] ?? null,
            'tanggal_mulai_efektif' => $toDate($r['tanggal_mulai_efektif'] ?? null),
            'tanggal_selesai_efektif' => $toDate($r['tanggal_selesai_efektif'] ?? null),
            'sks_matkul' => $r['sks_mata_kuliah'] ?? null,
            'sks_tatap_muka'           => $r['sks_tatap_muka'] ?? null,
            'sks_praktek'         => $r['sks_praktek'] ?? null,
            'sks_praktek_lapangan'         => $r['sks_praktek_lapangan'] ?? null,
            'sks_simulasi'         => $r['sks_simulasi'] ?? null,
            'metode_kuliah'         => $r['metode_kuliah'] ?? null,
        ];
    }
}
