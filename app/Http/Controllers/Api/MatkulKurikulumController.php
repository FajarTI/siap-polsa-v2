<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\MahasiswaResource;
use Illuminate\Http\Request;
use App\Models\MatkulKurikulum;
use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use Illuminate\Support\Facades\Validator;

class MatkulKurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matkul_kurikulums = Kurikulum::with('matkulKurikulum.matkul')->latest()->paginate(5);
        return new MahasiswaResource(
            true,
            'Berhasil menampilkan data Mata Kuliah Kurikulim',
            $matkul_kurikulums
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kurikulum' => 'required',
            'id_matkul' => 'required',
            'wajib' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $matkul_kurikulum = MatkulKurikulum::create($request->all());

        return new MahasiswaResource(
            true,
            'Berhasil menambahkan data Mata Kuliah Kurikulim',
            $matkul_kurikulum
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_kurikulum)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_kurikulum)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id_kurikulum)
    {
        $validated = $request->validate([
            'id_matkul' => ['required'],
        ]);

        $deleted = MatkulKurikulum::where('id_kurikulum', $id_kurikulum)
            ->where('id_matkul', $validated['id_matkul'])
            ->delete(); // langsung delete dari query, TIDAK pakai ->first()->delete()

        return new MahasiswaResource(
            true,
            $deleted ? 'Berhasil menghapus data Mata Kuliah Kurikulum' : 'Data tidak ditemukan',
            null
        );
    }
}
