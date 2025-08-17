<?php

namespace App\Http\Controllers;

use App\Models\Agama;
use App\Models\Negara;
use App\Models\Wilayah;
use App\Models\Pekerjaan;
use App\Models\Penghasilan;
use App\Models\JenisTinggal;
use Illuminate\Http\Request;
use App\Models\AlatTransportasi;
use App\Models\Mahasiswa as ModelsMahasiswa;
use App\Models\MahasiswaRegister;
use Illuminate\Support\Facades\DB;

class Mahasiswa extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_mahasiswa = ModelsMahasiswa::with('riwayatPendidikan.programStudi', 'agama')
            ->whereHas('riwayatPendidikan', function ($q) {
                $q->where('id_periode_masuk', '20231');
            })
            ->select([
                'id_mahasiswa',
                'id_agama',
                'nama_mahasiswa',
                'jenis_kelamin',
            ])
            ->paginate('25');

        return view('admin.mahasiswa.index', compact('list_mahasiswa'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $list_agama = Agama::select(['id_agama', 'nama_agama'])->get();

        $list_wilayah = Wilayah::select([
            'id_wilayah',
            'id_level_wilayah',
            'nama_wilayah',
            'id_induk_wilayah',
        ])
            ->get();

        $byId = $list_wilayah->mapWithKeys(function ($item) {
            $id = str_pad($item->id_wilayah, 6, '0', STR_PAD_LEFT);
            return [$id => $item];
        });

        $kecamatans = $list_wilayah->where('id_level_wilayah', 3);

        $options_wilayah = $kecamatans->map(function ($kec) use ($byId) {
            $kab = $byId[$kec->id_induk_wilayah];
            $prov = $byId[$kab->id_induk_wilayah];

            return [
                'id_wilayah' => str_pad($kec->id_wilayah, 6, '0', STR_PAD_LEFT),
                'label' => ($prov ? $prov->nama_wilayah : '') . ' - ' .
                    ($kab ? $kab->nama_wilayah : '') . ' - ' .
                    $kec->nama_wilayah,
                'provinsi' => $prov ? $prov->nama_wilayah : ''
            ];
        })
            ->sortBy('provinsi')
            ->mapWithKeys(function ($item) {
                return [$item['id_wilayah'] => $item['label']];
            });

        $list_negara = Negara::orderBy('nama_negara', 'asc')->get();
        $list_jenis_tinggal = JenisTinggal::orderBy('id_jenis_tinggal', 'asc')->get();
        $list_alat_transportasi = AlatTransportasi::orderBy('id_alat_transportasi', 'asc')->get();
        $list_penghasilan = Penghasilan::orderBy('id_penghasilan', 'asc')->get();
        $list_pekerjaan = Pekerjaan::orderBy('id_pekerjaan', 'asc')->get();

        return view('admin.mahasiswa.create', compact(
            'list_agama',
            'options_wilayah',
            'list_negara',
            'list_jenis_tinggal',
            'list_alat_transportasi',
            'list_penghasilan',
            'list_pekerjaan',
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mahasiswa' => 'required',
            'jenis_kelamin' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'id_agama' => 'required',
            'nik' => 'required',
            'kelurahan' => 'required',
            'id_wilayah' => 'required',
            'penerima_kps' => 'required',
            'nama_ibu_kandung' => 'required',
            'kewarganegaraan' => 'required',
        ]);

        // ModelsMahasiswa::create($request->all());

        DB::transaction(function () use ($validated) {
            $mahasiswa = ModelsMahasiswa::create($validated);
            $mahasiswa->riwayatPendidikan()->create(['id_mahasiswa' => $mahasiswa->id_mahasiswa]);
        });

        return redirect()->route('mahasiswa.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ModelsMahasiswa $mahasiswa)
    {
        $isEdit = true;

        $list_agama = Agama::select(['id_agama', 'nama_agama'])->get();

        $list_wilayah = Wilayah::select([
            'id_wilayah',
            'id_level_wilayah',
            'nama_wilayah',
            'id_induk_wilayah',
        ])
            ->get();

        $byId = $list_wilayah->mapWithKeys(function ($item) {
            $id = str_pad($item->id_wilayah, 6, '0', STR_PAD_LEFT);
            return [$id => $item];
        });

        $kecamatans = $list_wilayah->where('id_level_wilayah', 3);

        $options_wilayah = $kecamatans->map(function ($kec) use ($byId) {
            $kab = $byId[$kec->id_induk_wilayah];
            $prov = $byId[$kab->id_induk_wilayah];

            return [
                'id_wilayah' => str_pad($kec->id_wilayah, 6, '0', STR_PAD_LEFT),
                'label' => ($prov ? $prov->nama_wilayah : '') . ' - ' .
                    ($kab ? $kab->nama_wilayah : '') . ' - ' .
                    $kec->nama_wilayah,
                'provinsi' => $prov ? $prov->nama_wilayah : ''
            ];
        })
            ->sortBy('provinsi')
            ->mapWithKeys(function ($item) {
                return [$item['id_wilayah'] => $item['label']];
            });

        $list_negara = Negara::orderBy('nama_negara', 'asc')->get();
        $list_jenis_tinggal = JenisTinggal::orderBy('id_jenis_tinggal', 'asc')->get();
        $list_alat_transportasi = AlatTransportasi::orderBy('id_alat_transportasi', 'asc')->get();
        $list_penghasilan = Penghasilan::orderBy('id_penghasilan', 'asc')->get();
        $list_pekerjaan = Pekerjaan::orderBy('id_pekerjaan', 'asc')->get();

// dd($mahasiswa);

        return view('admin.mahasiswa.create', compact(
            'isEdit',
            'mahasiswa',
            'list_agama',
            'options_wilayah',
            'list_negara',
            'list_jenis_tinggal',
            'list_alat_transportasi',
            'list_penghasilan',
            'list_pekerjaan',
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
