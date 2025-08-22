<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MahasiswaResource;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kurikulums = Kurikulum::latest()->paginate(5);
        return new MahasiswaResource(
            true,
            "Berhasil menampilkan semua data Kurikulum!",
            $kurikulums
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kurikulum' => 'required',
            'id_prodi' => 'required',
            'id_semester' => 'required',
            'jumlah_sks_wajib' => 'required',
            'jumlah_sks_pilihan' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $kurikulum = Kurikulum::create([
            'nama_kurikulum' => $request->nama_kurikulum,
            'id_prodi' => $request->id_prodi,
            'id_semester' => $request->id_semester,
            'jumlah_sks_wajib' => $request->jumlah_sks_wajib,
            'jumlah_sks_pilihan' => $request->jumlah_sks_pilihan,
            'jumlah_sks_lulus' => (($request->jumlah_sks_pilihan ?? 0) + ($request->jumlah_sks_wajib)),
        ]);

        return new MahasiswaResource(
            true,
            "Berhasil menambahkan data kurikulum!",
            $kurikulum
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_kurikulum)
    {
        $kurikulum = Kurikulum::find($id_kurikulum);
        return new MahasiswaResource(
            true,
            'Berhasil menampilkan data kurikulum!',
            $kurikulum
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_kurikulum)
    {
        $validator = Validator::make($request->all(), [
            'nama_kurikulum' => 'required',
            'id_prodi' => 'required',
            'id_semester' => 'required',
            'jumlah_sks_wajib' => 'required',
            'jumlah_sks_pilihan' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $kurikulum = Kurikulum::find($id_kurikulum);

        $kurikulum->update([
            'nama_kurikulum' => $request->nama_kurikulum,
            'id_prodi' => $request->id_prodi,
            'id_semester' => $request->id_semester,
            'jumlah_sks_wajib' => $request->jumlah_sks_wajib,
            'jumlah_sks_pilihan' => $request->jumlah_sks_pilihan,
            'jumlah_sks_lulus' => (($request->jumlah_sks_pilihan ?? 0) + ($request->jumlah_sks_wajib)),
        ]);

        return new MahasiswaResource(
            true,
            'Berhasil mengubah data kurikulum!',
            $kurikulum
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_kurikulum)
    {
        $kurikulum = Kurikulum::find($id_kurikulum);
        $kurikulum->delete();

        return new MahasiswaResource(
            true,
            'Berhasil mengubah data kurikulum!',
            null
        );
    }
}
