@extends('layout')
@section('title', 'Data Mahasiswa')
@section('content')

    <form class="forms-sample" action="{{ route('mahasiswa.store') }}" method="POST">
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
                        <x-form-input name="nama_mahasiswa" id="inputNamaLengkap" label="Nama Lengkap"
                            placeholder="Masukan nama lengkap" required />

                        {{-- Jenis Kelamin --}}
                        <x-form-select name="jenis_kelamin" label="Jenis Kelamin" :options="['L' => 'Laki - Laki', 'P' => 'Perempuan']"
                            placeholder="Pilih Jenis Kelamin" required />


                        {{-- Tanggal Lahir --}}
                        <x-form-input type="date" name="tanggal_lahir" id="inputTanggalLahir" label="Tanggal Lahir"
                            required />
                    </div>

                    <div class="col-md-6">
                        {{-- Tempat Lahir --}}
                        <x-form-input name="tempat_lahir" id="inputTempatLahir" label="Tempat Lahir"
                            placeholder="Masukan tempat lahir" required />

                        {{-- Nama Ibu --}}
                        <x-form-input name="nama_ibu_kandung" id="inputNamaIbu" label="Nama Lengkap Ibu"
                            placeholder="Masukan nama lengkap ibu" required />
                        {{-- Agama --}}
                        <x-form-select name="id_agama" label="Pilih Agama" :options="$list_agama->pluck('nama_agama', 'id_agama')->toArray()" placeholder="Pilih Agama"
                            required />

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
                                    placeholder="Pilih Kewarganegaraan" required :options="$list_negara->pluck('nama_negara', 'nama_negara')->toArray()" />

                                {{-- NISN --}}
                                <x-form-input name="nisn" id="inputNisn" label="NISN" placeholder="Masukan NISN" />

                                {{-- Jalan --}}
                                <x-form-input name="jalan" id="inputJalan" label="Jalan"
                                    placeholder="Masukan Jalan" />

                                <div class="row">
                                    <div class="col-8">
                                        <x-form-input name="dusun" id="inputDusun" label="Dusun"
                                            placeholder="Masukan Dusun" />
                                    </div>
                                    <div class="col-2">
                                        <x-form-input name="rt" id="inputRt" label="RT"
                                            placeholder="Masukan RT" />
                                    </div>
                                    <div class="col-2">
                                        <x-form-input name="rw" id="inputRw" label="RW"
                                            placeholder="Masukan RW" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-8">
                                        <x-form-input name="kelurahan" id="inputKelurahan" label="Kelurahan"
                                            placeholder="Masukan Kelurahan" required />
                                    </div>
                                    <div class="col-4">
                                        <x-form-input name="kode_pos" id="inputKodePos" label="Kode Pos"
                                            placeholder="Masukan Kode Pos" />
                                    </div>
                                </div>

                                <x-form-select name="id_wilayah" id="inputKecamatan" label="Kecamatan"
                                    placeholder="Pilih Kecamatan" required :options="$options_wilayah->toArray()" />

                                <x-form-select name="jenis_tinggal" id="inputJenisTinggal" label="Jenis Tinggal"
                                    placeholder="Pilih Jenis Tinggal" :options="$list_jenis_tinggal
                                        ->pluck('nama_jenis_tinggal', 'id_jenis_tinggal')
                                        ->toArray()" />
                            </div>
                            <div class="col-6">
                                {{-- NIK --}}
                                <x-form-input name="nik" id="inputNik" label="NIK" placeholder="Masukan NIK"
                                    required />

                                {{-- NPWP --}}
                                <x-form-input name="npwp" id="inputNpwp" label="NPWP" placeholder="Masukan NPWP" />

                                {{-- Telepon --}}
                                <x-form-input name="telepon" id="inputTelepon" label="Telepon"
                                    placeholder="Contoh: 08xxxx" />

                                {{-- HP --}}
                                <x-form-input name="hp" id="inputHp" label="HP"
                                    placeholder="Contoh: 08xxxx" />

                                {{-- Email --}}
                                <x-form-input name="email" id="inputEmail" label="Email"
                                    placeholder="Masukan alamat email" />

                                <x-form-select name="penerima_kps" id="inputPenerimaKps" label="Penerima KPS"
                                    placeholder="Apakah Penerima KPS?" required :options="['1' => 'Ya', '0' => 'Tidak']" />

                                <x-form-select name="alat_transportasi" id="inputAlatTransportasi"
                                    label="Alat Transportasi" placeholder="Pilih Alat Transportasi" :options="$list_alat_transportasi
                                        ->pluck('nama_alat_transportasi', 'id_alat_transportasi')
                                        ->toArray()" />
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="orang-tua" role="tabpanel" aria-labelledby="orang-tua-tab">
                        <div class="row mt-3">
                            <div class="col-6">
                                <x-form-input name="nama_ayah" id="inputNamaAyah" label="Nama Ayah"
                                    placeholder="" />

                                <x-form-input name="nik_ayah" id="inputNikAyah" label="NIK Ayah"
                                    placeholder="" />

                                <x-form-input name="tanggal_lahir_ayah" id="inputTanggalLahirAyah"
                                    label="Tanggal Lahir Ayah" placeholder="" type=date />

                                <x-form-select name="pekerjaan_ayah" id="inputPekerjaanAyah" label="Pekerjaan Ayah"
                                    placeholder="Pekerjaan" :options="$list_pekerjaan->pluck('nama_pekerjaan', 'id_pekerjaan')->toArray()" />

                                <x-form-select name="penghasilan_ayah" id="inputPenghasilanAyah" label="Penghasilan Ayah"
                                    placeholder="Penghasilan" :options="$list_penghasilan
                                        ->pluck('nama_penghasilan', 'id_penghasilan')
                                        ->toArray()" />

                                <x-form-select name="pendidikan_ayah" id="inputPendidikanAyah" label="Pendidikan Ayah"
                                    placeholder="Pendidikan" :options="[]" />

                            </div>
                            <div class="col-6">
                                <x-form-input name="nama_ibu_kandung" id="inputNamaibu"
                                    label="Nama Ibu" placeholder="" />

                                <x-form-input name="nik_ibu" id="inputNikibu" label="NIK Ibu"
                                    placeholder="" />

                                <x-form-input name="tanggal_lahir_ibu" id="inputTanggalLahirIbu"
                                    label="Tanggal Lahir Ibu" placeholder="" type=date />

                                <x-form-select name="pekerjaan_ibu" id="inputPekerjaanIbu" label="Pekerjaan Ibu"
                                    placeholder="Pekerjaan" :options="$list_pekerjaan->pluck('nama_pekerjaan', 'id_pekerjaan')->toArray()" />

                                <x-form-select name="penghasilan_ibu" id="inputPenghasilanIbu" label="Penghasilan Ibu"
                                    placeholder="Penghasilan" :options="$list_penghasilan
                                        ->pluck('nama_penghasilan', 'id_penghasilan')
                                        ->toArray()" />

                                <x-form-select name="pendidikan_ibu" id="inputPendidikanIbu" label="Pendidikan Ibu"
                                    placeholder="Pendidikan" :options="[]" />
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
@endsection
