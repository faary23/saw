@extends('layouts.apppenilai')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h2>Pemberkasan</h2>

            <!-- Form Search -->
            <form method="GET" action="{{ route('pemberkasanadmin.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama atau NIM" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Bukti Mahasiswa Aktif</th>
                            <th>KTM</th>
                            <th>CV</th>
                            <th>Formulir Pendaftaran</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatives as $alternative)
                        <tr>
                            <td>{{ $alternative->nama }}</td>
                            <td>{{ $alternative->nim }}</td>
                            <td>
                                @if($alternative->bukti_mahasiswa_aktif)
                                    <a href="{{ asset($alternative->bukti_mahasiswa_aktif) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if($alternative->ktm)
                                    <a href="{{ asset($alternative->ktm) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if($alternative->cv)
                                    <a href="{{ asset($alternative->cv) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if($alternative->formulir_pendaftaran)
                                    <a href="{{ asset($alternative->formulir_pendaftaran) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                            <td>
                                @if($alternative->foto)
                                    <a href="{{ asset($alternative->foto) }}" target="_blank" class="btn btn-primary btn-sm">Lihat</a>
                                @else
                                    <span class="text-danger">Belum diunggah</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-3">
                {{ $alternatives->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </nav>

        </div>
    </div>
</div>
@endsection
