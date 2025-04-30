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
            <div class="table-responsive">
                <table class="table table-bordered" style="border-collapse: collapse;">
                    <thead>
                        <tr style="border: 1px solid #000;">
                            <th class="text-center" style="border: 1px solid #000;">Rank</th>
                            <th class="text-center" style="border: 1px solid #000;">Nama</th>
                            <th class="text-center" style="border: 1px solid #000;">Jurusan</th>
                            @foreach($criterias as $criteria)
                                <th class="text-center" style="border: 1px solid #000;">{{ $criteria->name }}</th>
                            @endforeach
                            <th class="text-center" style="border: 1px solid #000;">Total Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatives as $alternative)
                        @php
                            $ranking = $alternative->perangkingan;
                            $data_kriteria = $alternative->data_kriteria ?? [];
                            $acceptedCount = session('accepted_count', 0);
                            $rank = $ranking->rank ?? 999;
                            $isAccepted = $rank > 0 && $rank <= $acceptedCount;
                            $bgColor = $isAccepted ? '#d4edda' : 'transparent';
                        @endphp
                        <tr>
                            <td style="border: 1px solid #000; background-color: {{ $bgColor }};">{{ $ranking->rank ?? '-' }}</td>
                            <td style="border: 1px solid #000; background-color: {{ $bgColor }};">{{ $alternative->nama }}</td>
                            <td style="border: 1px solid #000; background-color: {{ $bgColor }};">{{ $alternative->jurusan }}</td>
                            @foreach ($criterias as $criteria)
                                @php
                                    $nilai = $alternative->weightedValues
                                        ->firstWhere('criteria_id', $criteria->id)
                                        ->weighted_value ?? 0;
                                @endphp
                                <td style="border: 1px solid #000; background-color: {{ $bgColor }};">
                                    {{ number_format($nilai, 2, '.', '') }}
                                </td>
                            @endforeach
                            <td style="border: 1px solid #000; background-color: {{ $bgColor }};">
                                {{ $ranking ? number_format($ranking->total_score, 2, '.', '') : '0.00' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
