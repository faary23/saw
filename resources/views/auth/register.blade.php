@extends('layouts.applogin')

@section('content')

<div class="container-fluid">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <div class="card px-sm-6 px-0">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <img src="{{ asset('assets/img/logobem.png') }}" alt="Logo" style="height: 100px;">
                        <span class="h4 mb-0">Sistem Pendukung Keputusan Seleksi Anggota BEM Politeknik Negeri Banyuwangi</span>
                    </div>
                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <form action="{{ url('/register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" required autofocus />
                            @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <select class="form-control @error('jurusan') is-invalid @enderror" id="jurusan" name="jurusan" required>
                                <option value="" disabled selected>Pilih Jurusan</option>
                                <option value="D4-Teknologi Rekayasa Perangkat Lunak">D4-Teknologi Rekayasa Perangkat Lunak</option>
                                <option value="D4-Teknologi Rekayasa Komputer">D4-Teknologi Rekayasa Komputer</option>
                                <option value="D4-Bisnis Digital">D4-Bisnis Digital</option>
                                <option value="D3-Teknik Sipil">D3-Teknik Sipil</option>
                                <option value="D4-Teknologi Rekayasa Kontruksi Jalan & Jembatan">D4-Teknologi Rekayasa Kontruksi Jalan & Jembatan</option>
                                <option value="D4-Teknologi Rekayasa Kontruksi Bangunan & Gedung">D4-Teknologi Rekayasa Kontruksi Bangunan & Gedung</option>
                                <option value="D4-Manajemen Kontruksi">D4-Manajemen Kontruksi</option>
                                <option value="D4-Teknologi Rekayasa Manufaktur">D4-Teknologi Rekayasa Manufaktur</option>
                                <option value="D4-Teknologi Teknik Manufaktur Kapal">D4-Teknologi Teknik Manufaktur Kapal</option>
                                <option value="D4-Agrbisnis">D4-Agrbisnis</option>
                                <option value="D4-Teknologi Pengolahan Hasil Ternak">D4-Teknologi Pengolahan Hasil Ternak</option>
                                <option value="D4-Pengembangan Produk Agroindustri">D4-Pengembangan Produk Agroindustri</option>
                                <option value="D4-Teknologi Budi Daya Perikanan/Teknologi Akuakultur">D4-Teknologi Budi Daya Perikanan/Teknologi Akuakultur</option>
                                <option value="D4-Teknologi Produksi Tanaman Pangan">D4-Teknologi Produksi Tanaman Pangan</option>
                                <option value="D4-Teknologi Produksi Ternak">D4-Teknologi Produksi Ternak</option>
                                <option value="D4-Manajemen Bisnis Pariwisata">D4-Manajemen Bisnis Pariwisata</option>
                                <option value="D4-Destinasi Pariwisata">D4-Destinasi Pariwisata</option>
                                <option value="D4-Pengelolaan Perhotelan">D4-Pengelolaan Perhotelan</option>
                            </select>
                            @error('jurusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM</label>
                            <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim" name="nim" required />
                            @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required />
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" required />
                            @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Register</button>
                        <p class="text-center mt-4">Sudah punya akun? <a href="{{ url('/login') }}">Login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection