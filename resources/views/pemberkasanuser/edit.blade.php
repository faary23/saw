@extends('layouts.appuser')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
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

        <div class="card-body">
            @php
                $documents = [
                    'bukti_mahasiswa_aktif' => 'Bukti Mahasiswa Aktif',
                    'ktm' => 'Kartu Tanda Mahasiswa (KTM)',
                    'cv' => 'Curriculum Vitae (CV)',
                    'formulir_pendaftaran' => 'Formulir Pendaftaran',
                    'foto' => 'Foto'
                ];
                
                // Cek apakah semua file kosong
                $hasFiles = false;
                foreach ($documents as $key => $label) {
                    if (!empty($alternative->$key)) {
                        $hasFiles = true;
                        break;
                    }
                }
            @endphp

            <h2>{{ $hasFiles ? 'Edit Pemberkasan' : 'Unggah Berkas' }}</h2>

            <form action="{{ route('pemberkasanuser.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @foreach($documents as $key => $label)
                <div class="mb-3">
                    <label class="form-label">{{ $label }}</label>
                    <input type="file" class="form-control" name="{{ $key }}">
                    
                    @php
                    $filePath = $alternative->$key ?? null;
                    $fileName = $filePath ? pathinfo($filePath, PATHINFO_FILENAME) . '.' . pathinfo($filePath, PATHINFO_EXTENSION) : null;
                    @endphp

                    @if ($filePath)
                    <p class="mt-2">
                        <strong>File saat ini:</strong>
                        <a href="{{ asset($filePath) }}" target="_blank">{{ $fileName }}</a>
                    </p>
                    @endif

                    @if ($errors->has($key))
                    <small class="text-danger">{{ $errors->first($key) }}</small>
                    @endif
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('pemberkasanuser.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
