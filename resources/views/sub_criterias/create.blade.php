@extends('layouts.app')

@section('title', 'Add Sub Criteria')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <h1>Tambah Sub Kriteria</h1>
            <form action="{{ route('sub_criterias.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="criteria_id">Kriteria</label>
                    <select name="criteria_id" id="criteria_id" class="form-control" required>
                        <option value="">Pilih Kriteria</option>
                        @foreach($criterias as $criteria)
                        <option value="{{ $criteria->id }}">{{ $criteria->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="nama_sub">Sub Kriteria</label>
                    <input type="text" name="nama_sub" id="nama_sub" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label for="nilai">Nilai</label>
                    <input type="number" name="nilai" id="nilai" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection