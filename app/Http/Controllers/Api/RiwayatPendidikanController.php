<?php

namespace App\Http\Controllers\Api;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MahasiswaRegister;
use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use Illuminate\Support\Facades\Validator;

class RiwayatPendidikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mahasiswas = Mahasiswa::with('riwayatPendidikan')->latest()->paginate(5);
        return new MahasiswaResource(true, 'Menampilkan semua data mahasiswa registrasi', $mahasiswas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_mahasiswa'  => 'required',
            'nim' => 'required',
            'id_jenis_daftar' => 'required',
            'id_jalur_daftar' => 'nullable',
            'id_perguruan_tinggi' => 'required',
            'id_prodi' => 'required',
            'id_periode_masuk' => 'required',
            'id_prodi_asal' => 'nullable',
            'id_perguruan_tinggi_asal' => 'nullable',
            'id_pembiayaan' => 'nullable',
            'tanggal_daftar' => 'required',
            'sks_diakui' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mahasiswa = Mahasiswa::find($request->id_mahasiswa);
        $riwayat_pendidikan = $mahasiswa->riwayatPendidikan()->create($request->all());

        return new MahasiswaResource(true, 'Berhasil menambahkan data riyawat pendidikan!', $riwayat_pendidikan);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_registrasi_mahasiswa)
    {
        $mahasiswa = Mahasiswa::with('riwayatPendidikan')
            ->whereHas('riwayatPendidikan', function ($query) use ($id_registrasi_mahasiswa) {
                $query->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);
            })
            ->first();

        return new MahasiswaResource(true, 'Berhasil menampilkan data!', $mahasiswa);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_registrasi_mahasiswa)
    {
        $riwayat_pendidikan = MahasiswaRegister::find($id_registrasi_mahasiswa);

        $validator = Validator::make($request->all(), [
            'id_mahasiswa'  => 'required',
            'nim' => [
                'required',
                Rule::unique('riwayat_pendidikan', 'nim')
                    ->ignoreModel($riwayat_pendidikan),
            ],
            'id_jenis_daftar' => 'required',
            'id_jalur_daftar' => 'nullable',
            'id_perguruan_tinggi' => 'required',
            'id_prodi' => 'required',
            'id_periode_masuk' => 'required',
            'id_prodi_asal' => 'nullable',
            'id_perguruan_tinggi_asal' => 'nullable',
            'id_pembiayaan' => 'nullable',
            'tanggal_daftar' => 'required',
            'sks_diakui' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $riwayat_pendidikan->update($request->all());
        $riwayat_pendidikan->fresh();

        $mahasiswa = Mahasiswa::with('riwayatPendidikan')
            ->whereHas('riwayatPendidikan', function ($query) use ($id_registrasi_mahasiswa) {
                $query->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa);
            })
            ->first();

        return new MahasiswaResource(true, 'Berhasil mengubah data riyawat pendidikan!', $mahasiswa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_riwayat_pendidikan)
    {
        $riwayat_pendidikan = MahasiswaRegister::find($id_riwayat_pendidikan);
        $riwayat_pendidikan->delete();
        return new MahasiswaResource(true, 'Berhasil menghapus data riwayat pendidikan', null);
    }
}
