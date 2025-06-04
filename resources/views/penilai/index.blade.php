@extends('layouts.app')

@section('title', 'Manajemen Akun Penilai')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-3">Manajemen Akun Penilai</h1>
            {{-- <a href="{{ route('penilai.create') }}" class="btn btn-primary mb-3">Tambah Penilai</a> --}}
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penilai as $index => $p)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->nim }}</td>
                            <td>{{ $p->role }}</td>
                            <td>
                                <a href="{{ route('penilai.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                {{-- <form action="{{ route('penilai.destroy', $p->id) }}" method="POST" class="d-inline" id="delete-form-{{ $p->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete btn-danger btn-sm" id="delete-btn-{{ $p->id }}">Hapus</button>
                                </form> --}}
                            </td>
                        </tr>
                        @endforeach
                        @if ($penilai->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data penilai</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.querySelectorAll('.btn-delete').forEach((button) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const formId = this.closest('form').id;
            const form = document.getElementById(formId);
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
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
@endsection
@endsection
