@extends('layouts.app')

@section('title', 'Sub Criteria List')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1>Sub Kriteria</h1>
            <a href="{{ route('sub_criterias.create') }}" class="btn btn-primary mb-3">Tambah Sub Kriteria</a>

            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('sub_criterias.index') }}" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" value="{{ old('search', $search) }}" class="form-control" placeholder="Search by name or criteria" />
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>

            @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th> <!-- Nomor urut -->
                            <th>Kriteria</th>
                            <th>Sub Kriteria</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subCriterias as $index => $subCriteria)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $subCriteria->criteria->name }}</td>
                            <td>{{ $subCriteria->nama_sub }}</td>
                            <td>{{ $subCriteria->nilai }}</td>
                            <td>
                                <a href="{{ route('sub_criterias.edit', $subCriteria->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('sub_criterias.destroy', $subCriteria->id) }}" method="POST" class="d-inline" id="delete-form-{{ $subCriteria->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete btn-danger btn-sm" id="delete-btn-{{ $subCriteria->id }}">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                <nav aria-label="Page navigation" style="padding: 10px;">
                    {{ $subCriterias->links('pagination::bootstrap-5') }}
                </nav>
            </div>
        </div>
    </div>
    @endsection

    @section('scripts')
    <script>
        // SweetAlert2 configuration for delete button
        document.querySelectorAll('.btn-delete').forEach((button) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const formId = this.closest('form').id;
                const form = document.getElementById(formId);
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