@extends('layouts.app')

@section('title', 'Manajemen Jurusan')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-3">Manajemen Prodi</h1>
            <a href="{{ route('jurusan.create') }}" class="btn btn-primary mb-3">Tambah Prodi</a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama Prodi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jurusans as $index => $jurusan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $jurusan->kode }}</td>
                            <td>{{ $jurusan->nama }}</td>
                            <td>
                                <a href="{{ route('jurusan.edit', $jurusan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('jurusan.destroy', $jurusan->id) }}" method="POST" class="d-inline" id="delete-form-{{ $jurusan->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete" id="delete-btn-{{ $jurusan->id }}">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data prodi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const formId = this.closest('form').id;
            const form = document.getElementById(formId);
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data jurusan akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                backdrop: false
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
