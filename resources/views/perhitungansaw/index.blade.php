@extends('layouts.appuser')

@section('title', 'Sub Criteria List')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card mt-4">
    <div class="card-body">
      <h5 class="card-title mb-4">Detail Nilai</h5>

      @if($weightvalue->isEmpty() || !$rangking || $rangking->status == 3)
        <div class="alert alert-warning text-center">
          Data Kosong atau Calon Ditolak
        </div>
      @else
        <ul class="list-group">
          {{-- Ranking --}}
          @if($rangking->rank > 0)
            <li class="list-group-item d-flex align-items-center">
              <span class="fw-bold" style="min-width: 180px;">Ranking</span>
              <span class="mx-2">:</span>
              <span class="flex-grow-1 text-truncate">{{ $rangking->rank }}</span>
            </li>
          @endif

          {{-- Kriteria --}}
          @foreach($criterias as $index => $criteria)
            <li class="list-group-item d-flex align-items-center">
              <span class="fw-bold" style="min-width: 180px;">{{ $criteria->name }}</span>
              <span class="mx-2">:</span>
              <span class="flex-grow-1 text-truncate" style="max-width: 100%;">
                {{ $weightvalue[$index]->weighted_value ?? '-' }}
              </span>
            </li>
          @endforeach

          {{-- Total Skor --}}
          <li class="list-group-item d-flex align-items-center">
            <span class="fw-bold" style="min-width: 180px;">Total</span>
            <span class="mx-2">:</span>
            <span class="flex-grow-1 text-truncate">{{ $rangking->total_score ?? '-' }}</span>
          </li>

          {{-- Keputusan --}}
          <li class="list-group-item d-flex align-items-center">
            <span class="fw-bold" style="min-width: 180px;">Keputusan</span>
            <span class="mx-2">:</span>
            @if($rangking->status === 1)
              <span class="flex-grow-1 text-success fw-bold text-truncate">Diterima</span>
            @else
              <span class="flex-grow-1 text-danger fw-bold text-truncate">Ditolak</span>
            @endif
          </li>
        </ul>
      @endif

      {{-- Keterangan (jika ada) --}}
      @if($rangking && $rangking->keterangan)
        <div class="mt-4">
          <p class="mb-1 fw-bold">Keterangan</p>
          <div class="border border-danger rounded p-3 text-danger">
            {{ $rangking->keterangan }}
          </div>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection
