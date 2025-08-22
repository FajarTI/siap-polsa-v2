<?php

namespace App\Http\Controllers\Api;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::latest()->paginate(5);
        return new MahasiswaResource(true, 'List Mahasiswa', $mahasiswas);
    }

    // Insert Data Mahasiswa
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_mahasiswa' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'id_agama' => 'required',
            'nik' => 'required',
            'kewarganegaraan' => 'required',
            'kelurahan' => 'required',
            'id_wilayah' => 'required',
            'penerima_kps' => 'required',
            'nama_ibu_kandung' => 'required',
            'id_kebutuhan_khusus_mahasiswa' => 'required',
            'id_kebutuhan_khusus_ayah' => 'required',
            'id_kebutuhan_khusus_ibu' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mahasiswa = Mahasiswa::create($request->all());

        return new MahasiswaResource(true, 'Data Mahasiswa Berhasil Ditambahkan!', $mahasiswa);
    }

    // Menampilkan Detail Data Mahasiswa
    public function show($id_mahasiswa)
    {
        $mahasiswa = Mahasiswa::find($id_mahasiswa);
        return new MahasiswaResource(true, 'Menampilkan Detail Mahasiswa!', $mahasiswa);
    }

    // Update Data Mahasiswa
    public function update(Request $request, $id_mahasiswa)
    {
        $validator = Validator::make($request->all(), [
            'nama_mahasiswa' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'id_agama' => 'required',
            'nik' => 'required',
            'kewarganegaraan' => 'required',
            'kelurahan' => 'required',
            'id_wilayah' => 'required',
            'penerima_kps' => 'required',
            'nama_ibu_kandung' => 'required',
            'id_kebutuhan_khusus_mahasiswa' => 'required',
            'id_kebutuhan_khusus_ayah' => 'required',
            'id_kebutuhan_khusus_ibu' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mahasiswa = Mahasiswa::find($id_mahasiswa);

        $mahasiswa->update($request->all());

        return new MahasiswaResource(true, 'Data Mahasiswa Berhasil Diubah!', $mahasiswa);
    }

    // Delete Data Mahasiswa
    public function destroy($id_mahasiswa)
    {
        $mahasiswa = Mahasiswa::find($id_mahasiswa);
        $mahasiswa->delete();
        return new MahasiswaResource(true, 'Data Mahasiswa Berhasil Dihapus!', null);

    }
}
