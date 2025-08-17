@extends('layout')
@section('title', 'Daftar Mahasiswa')
@section('content')

    {{-- <ul>
        @foreach ($list_mahasiswa as $mahasiswa)
        <li>
            {{ $mahasiswa->nim }} - {{ $mahasiswa->biodata->nama_mahasiswa }}
        </li>
        @endforeach
    </ul> --}}

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3 row justify-content-between">
                        <div class="col-3">
                            <h4 class="card-title">Daftar Mahasiswa</h4>
                        </div>
                        <!-- Form Pencarian -->
                        <div class="col-3">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" placeholder="Search"
                                    id="searchInput">
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-start mb-3">
                        <div class="col">
                            <button class="btn btn-secondary btn-sm" type="button">
                                <i class="mdi mdi-filter"></i> Filter/Sort
                            </button>
                            <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary btn-sm">
                                <i class="mdi mdi-plus"></i> Tambah
                            </a>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table mb-4">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIM</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>Program Studi</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Agama</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_mahasiswa as $mahasiswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $mahasiswa->riwayatPendidikan->nim }}</td>
                                        <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                                        <td>{{ $mahasiswa->riwayatPendidikan->programStudi->nama_program_studi ?? '-'}}</td>
                                        <td>{{ $mahasiswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $mahasiswa->agama->nama_agama ?? '-'}}</td>
                                        <td>Aktif</td>
                                        <td><a href="{{ route('mahasiswa.show', $mahasiswa) }}" class="btn btn-sm btn-info">Lihat</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $list_mahasiswa->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection