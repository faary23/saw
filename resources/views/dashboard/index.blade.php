@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <!-- Card Jumlah Alternatif -->
        <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Jumlah Pendaftar Calon Anggota BEM</h5>
                    <p class="card-text display-4">{{ $totalAlternatives }}</p>
                </div>
            </div>
        </div>

        <!-- Card Jumlah Anggota Telah Pemberkasan -->
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Jumlah Anggota Telah Pemberkasan</h5>
                    <p class="card-text display-4">{{ $totalBerkas }}</p>
                </div>
            </div>
        </div>

         <!-- Card Jumlah Alternatif Perangkingan -->
         <div class="col-md-6">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Jumlah Calon Anggota Perangkingan</h5>
                    <p class="card-text display-4">{{ $totalRanked }}</p>
                </div>
            </div>
        </div>

         <!-- Card Jumlah Alternatif Diterima -->
         <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Jumlah Calon Anggota Diterima</h5>
                    <p class="card-text display-4">{{ $totalAccepted }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
