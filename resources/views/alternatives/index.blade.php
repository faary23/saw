@extends('layouts.app')

@section('title', 'Alternative List')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1>Alternative</h1>
            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Search Form -->
            <form method="GET" action="{{ route('alternatives.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name or NIM" value="{{ old('search', $search) }}">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>NIM</th>
                            <th>Jurusan</th>
                            <th>Criteria Data</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatives as $alternative)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $alternative->nama }}</td>
                            <td>{{ $alternative->nim }}</td>
                            <td>{{ $alternative->jurusan }}</td>
                            <td>
                                @foreach ($alternative->formatted_data_kriteria as $data)
                                <p><strong>{{ $data['criteria_name'] }}:</strong> {{ $data['sub_criteria_name'] }}</p>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('alternatives.edit', $alternative->id) }}" 
                                    class="btn btn-sm {{ !empty($alternative->formatted_data_kriteria) ? 'btn-warning' : 'btn-success' }}">
                                     {{ !empty($alternative->formatted_data_kriteria) ? 'Edit Nilai' : 'Tambah Nilai' }}
                                 </a>                                 
                                <form action="{{ route('alternatives.destroy', $alternative->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <nav aria-label="Page navigation" style="padding: 10px;">
                {{ $alternatives->appends(['search' => $search])->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach((button) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            Swal.fire({
                title: 'Apa anda yakin?',
                text: "Aksi ini tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus ini!',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                backdrop: false 
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });
    });
</script>
@endsection
@endsection
