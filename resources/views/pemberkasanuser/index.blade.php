@extends('layouts.appuser')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Pemberkasan Saya</h2>
                <a href="{{ route('download.file',['filename'=>'FORMULIR PENDAFTARAN STAFF MAGANG BEM KM POLIWANGI 2024.docx']) }}" class="btn btn-secondary btn-sm">Template Formulir Pemberkasan</a>
            </div>            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Bukti Mahasiswa Aktif</th>
                            <th>KTM</th>
                            <th>CV</th>
                            <th>Formulir Pendaftaran</th>
                            <th>Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->nama }}</td>
                            <td>
                                @if($user->bukti_mahasiswa_aktif)
                                    <a href="{{ asset($user->bukti_mahasiswa_aktif) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if($user->ktm)
                                    <a href="{{ asset($user->ktm) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if($user->cv)
                                    <a href="{{ asset($user->cv) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if($user->formulir_pendaftaran)
                                    <a href="{{ asset($user->formulir_pendaftaran) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if($user->foto)
                                    <a href="{{ asset($user->foto) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if(!$user->bukti_mahasiswa_aktif || !$user->ktm || !$user->cv || !$user->formulir_pendaftaran || !$user->foto)
                                    <a href="{{ route('pemberkasanuser.edit') }}" class="btn btn-success btn-sm">Upload Data</a>
                                @else
                                    <a href="{{ route('pemberkasanuser.edit') }}" class="btn btn-warning btn-sm">Edit</a>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
