@extends('layout')
@section('title', 'Data Mahasiswa')
@section('content')

    <form class="forms-sample mb-3" action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="card-title">Detail Mahasiswa</h4>
                        <p class="card-description">Data Mahasiswa</p>
                    </div>
                    <div class="col d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-success me-2">Simpan</button>
                        <button type="reset" class="btn btn-sm btn-secondary">Reset</button>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        {{-- Nama Lengkap --}}
                        <x-form-input name="nama_mahasiswa" :value="$mahasiswa->nama_mahasiswa ?? null" id="inputNamaLengkap" label="Nama Lengkap"
                            placeholder="Masukan nama lengkap" required />

                        {{-- Jenis Kelamin --}}
                        <x-form-select name="jenis_kelamin" label="Jenis Kelamin" :options="['L' => 'Laki - Laki', 'P' => 'Perempuan']"
                            placeholder="Pilih Jenis Kelamin" :model="$mahasiswa" required />


                        {{-- Tanggal Lahir --}}
                        <x-form-input type="date" name="tanggal_lahir" :value="$mahasiswa->tanggal_lahir?->format('Y-m-d') ?? null" id="inputTanggalLahir"
                            label="Tanggal Lahir" required />
                    </div>

                    <div class="col-md-6">
                        {{-- Tempat Lahir --}}
                        <x-form-input name="tempat_lahir" :value="$mahasiswa->tempat_lahir ?? null" id="inputTempatLahir" label="Tempat Lahir"
                            placeholder="Masukan tempat lahir" required />

                        {{-- Nama Ibu --}}
                        <x-form-input name="nama_ibu_kandung" id="inputNamaIbu" label="Nama Lengkap Ibu"
                            placeholder="Masukan nama lengkap ibu" required :value="$mahasiswa->nama_ibu_kandung ?? null" />
                        {{-- Agama --}}
                        <x-form-select name="id_agama" label="Pilih Agama" :options="$list_agama->pluck('nama_agama', 'id_agama')->toArray()" placeholder="Pilih Agama"
                            required :model="$mahasiswa" />

                    </div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Detail Mahasiswa</h4>
                <div class="row">
                    <div class="col">
                        <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="alamat-tab" data-bs-toggle="tab"
                                    data-bs-target="#alamat" type="button" role="tab" aria-controls="alamat"
                                    aria-selected="true">Alamat</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="orang-tua-tab" data-bs-toggle="tab" data-bs-target="#orang-tua"
                                    type="button" role="tab" aria-controls="orang-tua" aria-selected="false">Orang
                                    Tua</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="wali-tab" data-bs-toggle="tab" data-bs-target="#wali"
                                    type="button" role="tab" aria-controls="wali" aria-selected="false">Wali</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="kebutuhan-khusus-tab" data-bs-toggle="tab"
                                    data-bs-target="#kebutuhan-khusus" type="button" role="tab"
                                    aria-controls="kebutuhan-khusus" aria-selected="false">Kebutuhan Khusus</button>
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="alamat" role="tabpanel" aria-labelledby="alamat-tab">
                        <div class="row mt-3">
                            <div class="col-6">
                                {{-- Kewarganegaraan --}}
                                <x-form-select name="kewarganegaraan" id="inputKewarganegaraan" label="Kewarganegaraan"
                                    placeholder="Pilih Kewarganegaraan" required :options="$list_negara->pluck('nama_negara', 'nama_negara')->toArray()" :model="$mahasiswa" />

                                {{-- NISN --}}
                                <x-form-input name="nisn" :value="$mahasiswa->nisn ?? null" id="inputNisn" label="NISN"
                                    placeholder="Masukan NISN" />

                                {{-- Jalan --}}
                                <x-form-input name="jalan" :value="$mahasiswa->jalan ?? null" id="inputJalan" label="Jalan"
                                    placeholder="Masukan Jalan" />

                                <div class="row">
                                    <div class="col-8">
                                        <x-form-input name="dusun" :value="$mahasiswa->dusun ?? null" id="inputDusun" label="Dusun"
                                            placeholder="Masukan Dusun" />
                                    </div>
                                    <div class="col-2">
                                        <x-form-input name="rt" :value="$mahasiswa->rt ?? null" id="inputRt" label="RT"
                                            placeholder="Masukan RT" />
                                    </div>
                                    <div class="col-2">
                                        <x-form-input name="rw" :value="$mahasiswa->rw ?? null" id="inputRw" label="RW"
                                            placeholder="Masukan RW" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                        <x-form-input name="kelurahan" :value="$mahasiswa->kelurahan ?? null" id="inputKelurahan"
                                            label="Kelurahan" placeholder="Masukan Kelurahan" required />
                                    </div>
                                    <div class="col-4">
                                        <x-form-input name="kode_pos" :value="$mahasiswa->kode_pos ?? null" id="inputKodePos"
                                            label="Kode Pos" placeholder="Masukan Kode Pos" />
                                    </div>
                                </div>

                                <x-form-select name="id_wilayah" :model="$mahasiswa" id="inputKecamatan" label="Kecamatan"
                                    placeholder="Pilih Kecamatan" required :options="$options_wilayah->toArray()" />

                                <x-form-select name="jenis_tinggal" :model="$mahasiswa" id="inputJenisTinggal"
                                    label="Jenis Tinggal" placeholder="Pilih Jenis Tinggal" :options="$list_jenis_tinggal
                                        ->pluck('nama_jenis_tinggal', 'id_jenis_tinggal')
                                        ->toArray()" />
                            </div>
                            <div class="col-6">
                                {{-- NIK --}}
                                <x-form-input name="nik" :value="$mahasiswa->nik ?? null" id="inputNik" label="NIK"
                                    placeholder="Masukan NIK" required />

                                {{-- NPWP --}}
                                <x-form-input name="npwp" :value="$mahasiswa->npwp ?? null" id="inputNpwp" label="NPWP"
                                    placeholder="Masukan NPWP" />

                                {{-- Telepon --}}
                                <x-form-input name="telepon" :value="$mahasiswa->telepon ?? null" id="inputTelepon" label="Telepon"
                                    placeholder="Contoh: 08xxxx" />

                                {{-- HP --}}
                                <x-form-input name="hp" :value="$mahasiswa->hp ?? null" id="inputHp" label="HP"
                                    placeholder="Contoh: 08xxxx" />

                                {{-- Email --}}
                                <x-form-input name="email" :value="$mahasiswa->email ?? null" id="inputEmail" label="Email"
                                    placeholder="Masukan alamat email" />

                                <x-form-select name="penerima_kps" :model="$mahasiswa" id="inputPenerimaKps"
                                    label="Penerima KPS" placeholder="Apakah Penerima KPS?" required :options="['1' => 'Ya', '0' => 'Tidak']" />

                                <x-form-select name="alat_transportasi" :model="$mahasiswa" id="inputAlatTransportasi"
                                    label="Alat Transportasi" placeholder="Pilih Alat Transportasi" :options="$list_alat_transportasi
                                        ->pluck('nama_alat_transportasi', 'id_alat_transportasi')
                                        ->toArray()" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="orang-tua" role="tabpanel" aria-labelledby="orang-tua-tab">
                        <div class="row mt-3">
                            <div class="col-6">
                                <x-form-input name="nama_ayah" :value="$mahasiswa->nama_ayah ?? null" id="inputNamaAyah" label="Nama Ayah"
                                    placeholder="" />

                                <x-form-input name="nik_ayah" :value="$mahasiswa->nik_ayah ?? null" id="inputNikAyah" label="NIK Ayah"
                                    placeholder="" />

                                <x-form-input name="tanggal_lahir_ayah" :value="$mahasiswa->tanggal_lahir_ayah ?? null" id="inputTanggalLahirAyah"
                                    label="Tanggal Lahir Ayah" placeholder="" type=date />

                                <x-form-select name="pekerjaan_ayah" :model="$mahasiswa" id="inputPekerjaanAyah"
                                    label="Pekerjaan Ayah" placeholder="Pekerjaan" :options="$list_pekerjaan->pluck('nama_pekerjaan', 'id_pekerjaan')->toArray()" />

                                <x-form-select name="penghasilan_ayah" :model="$mahasiswa" id="inputPenghasilanAyah"
                                    label="Penghasilan Ayah" placeholder="Penghasilan" :options="$list_penghasilan
                                        ->pluck('nama_penghasilan', 'id_penghasilan')
                                        ->toArray()" />

                                <x-form-select name="pendidikan_ayah" :model="$mahasiswa" id="inputPendidikanAyah"
                                    label="Pendidikan Ayah" placeholder="Pendidikan" :options="[]" />

                            </div>
                            <div class="col-6">
                                <x-form-input name="nama_ibu_kandung" :value="$mahasiswa->nama_ibu_kandung ?? null" id="inputNamaibu"
                                    label="Nama Ibu" placeholder="" />

                                <x-form-input name="nik_ibu" :value="$mahasiswa->nik_ibu ?? null" id="inputNikibu" label="NIK Ibu"
                                    placeholder="" />

                                <x-form-input name="tanggal_lahir_ibu" :value="$mahasiswa->tanggal_lahir_ibu?->format('Y-m-d') ?? null" id="inputTanggalLahirIbu"
                                    label="Tanggal Lahir Ibu" placeholder="" type=date />

                                <x-form-select name="pekerjaan_ibu" :model="$mahasiswa" id="inputPekerjaanIbu"
                                    label="Pekerjaan Ibu" placeholder="Pekerjaan" :options="$list_pekerjaan->pluck('nama_pekerjaan', 'id_pekerjaan')->toArray()" />

                                <x-form-select name="penghasilan_ibu" :model="$mahasiswa" id="inputPenghasilanIbu"
                                    label="Penghasilan Ibu" placeholder="Penghasilan" :options="$list_penghasilan
                                        ->pluck('nama_penghasilan', 'id_penghasilan')
                                        ->toArray()" />

                                <x-form-select name="pendidikan_ibu" :model="$mahasiswa" id="inputPendidikanIbu"
                                    label="Pendidikan Ibu" placeholder="Pendidikan" :options="[]" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="wali" role="tabpanel" aria-labelledby="wali-tab">
                        <div class="row justify-content-center mt-3">
                            <div class="col-6">
                                <x-form-input name="nama_wali" id="inputNamawali" label="Nama Wali" placeholder="" />

                                <x-form-input name="nik_wali" id="inputNikwali" label="NIK Wali" placeholder="" />

                                <x-form-input name="tanggal_lahir_wali" id="inputTanggalLahirWali"
                                    label="Tanggal Lahir Wali" placeholder="" type=date />
                            </div>
                            <div class="col-6">
                                <x-form-select name="pekerjaan_wali" id="inputPekerjaanWali" label="Pekerjaan Wali"
                                    placeholder="Pekerjaan" :options="$list_pekerjaan->pluck('nama_pekerjaan', 'id_pekerjaan')->toArray()" />

                                <x-form-select name="penghasilan_wali" id="inputPenghasilanWali" label="Penghasilan Wali"
                                    placeholder="Penghasilan" :options="$list_penghasilan
                                        ->pluck('nama_penghasilan', 'id_penghasilan')
                                        ->toArray()" />

                                <x-form-select name="pendidikan_wali" id="inputPendidikanWali" label="Pendidikan Wali"
                                    placeholder="Pendidikan" :options="[]" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kebutuhan-khusus" role="tabpanel"
                        aria-labelledby="kebutuhan-khusus-tab">
                        <div class="row mt-3">
                            <div class="col">
                                <x-form-select name="kebutuhan_khusus" id="inputKebutuhanKhusus"
                                    label="Apakah Berkebutuhan Khusus?" placeholder="Kebutuhan Khusus"
                                    :options="['1' => 'Ya', '0' => 'Tidak']" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('admin.mahasiswa.partials.riwayat_pendidikan')
@endsection
