@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1>Keputusan Akhir</h1>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="mb-3 d-flex justify-content-start">
                <a href="{{ route('ranking.exportPDFakhir') }}" class="btn btn-success">Ekspor PDF</a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Nama</th>
                            @foreach($criterias as $criteria)
                                <th>{{ $criteria->name }}</th>
                            @endforeach
                            <th>Total Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatives as $alternative)
                        @php
                        $ranking = $alternative->perangkingan;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $alternative->nama }}</td>
                            @foreach ($criterias as $criteria)
                            @php
                                $nilai = $alternative->weightedValues
                                            ->firstWhere('criteria_id', $criteria->id)
                                            ->weighted_value ?? 0;
                            @endphp
                            <td>{{ number_format($nilai, 2, '.', '') }}</td>
                            @endforeach
                            <td>
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
