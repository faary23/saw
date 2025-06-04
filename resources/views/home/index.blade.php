@extends('layouts.applanding')

@section('content')

<div class="container" style="margin-top: 50px;">
    <div class="row align-items-center">
        <div class="col-4 text-start">
            <img src="{{ asset('assets/img/logobem.png') }}" alt="Logo" style="height: 100px;">
        </div>
        <div class="col-4 text-center">
            <h4 class="mb-0">BEM Politeknik Negeri Banyuwangi</h4>
        </div>
        <div class="col-4 text-end">
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        </div>
    </div>
</div>

<div class="container-xxl flex-grow-1 container-p-y mt-5">
    <div class="card">
        <div class="card-body">
            <h1 class="text-center mb-4">Hasil Pengumuman Lolos Seleksi BEM</h1>

            <!-- Search Form -->
            <form method="GET" action="{{ route('hasil.index') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama atau Jurusan" value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Rank</th>
                            <th class="text-center">Nama</th>
                            <th class="text-center">Jurusan</th>
                            @foreach($criterias as $criteria)
                                <th class="text-center">{{ $criteria->name }}</th>
                            @endforeach
                            <th class="text-center">Total Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatives as $alternative)
                            @php
                                $ranking = $alternative->perangkingan;
                                $status = $ranking->status ?? 0;
                                $bgColor = ($status == 1) ? '#d4edda' : 'transparent';
                            @endphp
                            <tr>
                                <td style="background-color: {{ $bgColor }}">{{ $ranking->rank ?? '-' }}</td>
                                <td style="background-color: {{ $bgColor }}">{{ $alternative->nama }}</td>
                                <td style="background-color: {{ $bgColor }}">{{ $alternative->jurusan }}</td>
                                @foreach($criterias as $criteria)
                                    @php
                                        $nilai = $alternative->weightedValues
                                            ->firstWhere('criteria_id', $criteria->id)
                                            ->weighted_value ?? 0;
                                    @endphp
                                    <td style="background-color: {{ $bgColor }}">{{ number_format($nilai, 2, '.', '') }}</td>
                                @endforeach
                                <td style="background-color: {{ $bgColor }}">{{ $ranking ? number_format($ranking->total_score, 2, '.', '') : '0.00' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <nav class="mt-3" aria-label="Page navigation">
                {{ $alternatives->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>

@endsection
