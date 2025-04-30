@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1>Ranking Awal</h1>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <div class="mb-3 d-flex justify-content-between">
                <form action="{{ route('ranking.rank') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Rangking</button>
                </form>
                <a href="{{ route('ranking.exportPDFawal') }}" class="btn btn-success">Ekspor PDF</a>
            </div>

            <form method="POST" action="{{ route('ranking.setAcceptedCount') }}" class="mb-3">
                @csrf
                <label for="accepted_count">Jumlah diterima:</label>
                <input type="number" name="accepted_count" id="accepted_count" value="{{ session('accepted_count') }}" min="1" required>
                <button type="submit">Terapkan</button>
            </form>
            
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
                            {{-- <th>Keterangan</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alternatives as $alternative)
                        @php
                            $ranking = $alternative->perangkingan;
                            $acceptedCount = session('accepted_count', 0);
                            $rank = $ranking->rank ?? 999;
                            $isAccepted = $rank > 0 && $rank <= $acceptedCount;
                        @endphp
                        <tr @if($isAccepted) style="background-color: #d4edda;" @endif>
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
                            {{-- <td>
                                @if($ranking && $ranking->total_score)
                                    @if($ranking->status != 0)
                                        @if($ranking->keterangan)
                                            {{ $ranking->keterangan }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        <form action="{{ route('ranking.addDiterima', $alternative->id) }}" method="POST" class="d-inline diterima-form">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-diterima">Diterima</button>
                                        </form>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#keteranganModal{{ $alternative->id }}">
                                            Tolak
                                        </button>
                                    @endif
                                @else
                                    <span class="text-muted">Skor tidak tersedia</span>
                                @endif
                            </td> --}}
                        </tr>

                        <!-- Modal Penolakan -->
                        {{-- <div class="modal fade" id="keteranganModal{{ $alternative->id }}" tabindex="-1" aria-labelledby="keteranganModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="keteranganModalLabel">Alasan Penolakan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('ranking.tolak', $alternative->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="keterangan" class="form-label">Masukkan Alasan:</label>
                                                <textarea class="form-control" name="keterangan" id="keterangan" rows="3" required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <!-- End Modal -->

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-diterima').forEach((button) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Terima calon anggota ini?',
                text: "Pastikan Anda yakin sebelum melanjutkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                backdrop: false
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection --}}
