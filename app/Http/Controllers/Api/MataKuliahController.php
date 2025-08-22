<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mata_kulias = MataKuliah::latest()->get();
        return new MahasiswaResource(true, "Berhasil menampilkan data!", $mata_kulias);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_prodi' => 'required',
            'kode_mata_kuliah' => 'required',
            'nama_mata_kuliah' => 'required',
            'id_jenis_matkul' => 'required',
            'id_kelompok_matkul' => 'nullable',
            'sks_matkul' => 'nullable',
            'sks_tatap_muka' => 'nullable',
            'sks_praktek' => 'nullable',
            'sks_praktek_lapangan' => 'nullable',
            'sks_simulasi' => 'nullable',
            'metode_kuliah' => 'nullable',
            'tanggal_mulai_efektif' => 'nullable',
            'tanggal_selesai_efektif' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mata_kuliah = MataKuliah::create([
            'id_prodi' => $request->id_prodi,
            'kode_mata_kuliah' => $request->kode_mata_kuliah,
            'nama_mata_kuliah' => $request->nama_mata_kuliah,
            'id_jenis_matkul' => $request->id_jenis_matkul,
            'id_kelompok_matkul' => $request->id_kelompok_matkul,
            'sks_matkul' => (($request->sks_tatap_muka ?? 0) + ($request->praktek ?? 0) + ($request->praktek_lapangan ?? 0) + ($request->simulasi ?? 0)),
            'sks_tatap_muka' => $request->sks_tatap_muka,
            'sks_praktek' => $request->praktek,
            'sks_praktek_lapangan' => $request->praktek_lapangan,
            'sks_simulasi' => $request->sks_simulasi,
            'metode_kuliah' => $request->metode_kuliah,
            'tanggal_mulai_efektif' => $request->tanggal_mulai_efektif,
            'tanggal_selesai_efektif' => $request->tanggal_selesai_efektif,
        ]);

        return new MahasiswaResource(true, "Berhasil menambahkan data mata kuliah", $mata_kuliah);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_matkul)
    {
        $mata_kuliah = MataKuliah::find($id_matkul);
        return new MahasiswaResource(true, "Berhasail menampilkan data mata kuliah", $mata_kuliah);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_matkul)
    {
        $validator = Validator::make($request->all(), [
            'id_prodi' => 'required',
            'kode_mata_kuliah' => 'required',
            'nama_mata_kuliah' => 'required',
            'id_jenis_matkul' => 'required',
            'id_kelompok_matkul' => 'nullable',
            'sks_matkul' => 'nullable',
            'sks_tatap_muka' => 'nullable',
            'sks_praktek' => 'nullable',
            'sks_praktek_lapangan' => 'nullable',
            'sks_simulasi' => 'nullable',
            'metode_kuliah' => 'nullable',
            'tanggal_mulai_efektif' => 'nullable',
            'tanggal_selesai_efektif' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $mata_kuliah = MataKuliah::find($id_matkul);

        $mata_kuliah->update([
            'id_prodi' => $request->id_prodi,
            'kode_mata_kuliah' => $request->kode_mata_kuliah,
            'nama_mata_kuliah' => $request->nama_mata_kuliah,
            'id_jenis_matkul' => $request->id_jenis_matkul,
            'id_kelompok_matkul' => $request->id_kelompok_matkul,
            'sks_matkul' => (($request->sks_tatap_muka ?? 0) + ($request->praktek ?? 0) + ($request->praktek_lapangan ?? 0) + ($request->simulasi ?? 0)),
            'sks_tatap_muka' => $request->sks_tatap_muka,
            'sks_praktek' => $request->praktek,
            'sks_praktek_lapangan' => $request->praktek_lapangan,
            'sks_simulasi' => $request->sks_simulasi,
            'metode_kuliah' => $request->metode_kuliah,
            'tanggal_mulai_efektif' => $request->tanggal_mulai_efektif,
            'tanggal_selesai_efektif' => $request->tanggal_selesai_efektif,
        ]);

        return new MahasiswaResource(true, "Berhasil mengubah data mata kuliah!", $mata_kuliah);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_matkul)
    {
        $mata_kuliah = MataKuliah::find($id_matkul);
        
        $mata_kuliah->delete();

        return new MahasiswaResource(true, "Berhasil menghapus data mata kuliah!", null);
    }
}
