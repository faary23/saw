@extends('layouts.app')

@section('title', 'Criteria List')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1>Kriteria</h1>
            <a href="{{ route('criteria.create') }}" class="btn btn-primary mb-3">Tambah Kriteria</a>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kriteria</th>
                            <th>Keterangan</th>
                            <th>Sifat</th>
                            <th>Bobot</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($criterias as $index => $criteria)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $criteria->kode }}</td>
                            <td>{{ $criteria->name }}</td>
                            <td>{{ $criteria->type }}</td>
                            <td>{{ $criteria->weight }}</td>
                            <td>
                                <a href="{{ route('criteria.edit', $criteria->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('criteria.destroy', $criteria->id) }}" method="POST" class="d-inline" id="delete-form-{{ $criteria->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete btn-danger btn-sm" id="delete-btn-{{ $criteria->id }}">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
@endsection