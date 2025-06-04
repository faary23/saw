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
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <form id="formAuthentication" class="mb-6" action="{{ url('/login') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="nim" class="form-label">NIM</label>
                            <input
                                type="text"
                                class="form-control @error('nim') is-invalid @enderror"
                                id="nim"
                                name="nim"
                                placeholder="Masukkan NIM"
                                value="{{ old('nim') }}"
                                required autofocus />
                            @error('nim')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-group input-group-merge">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            <p class="text-center mt-4">Belum punya akun? <a href="{{ url('/register') }}">Register</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection