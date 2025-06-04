@extends('layouts.app')

@section('title', 'Add Criteria')

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
            <h1>Tambah Kriteria</h1>
            <form action="{{ route('criteria.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="kode" class="form-label">Kriteria</label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode') }}" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Keterangan</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Atribut</label>
                    <select name="type" id="type" class="form-control" required>
                      <!-- Placeholder awal yang tidak bisa dipilih sebagai nilai valid -->
                      <option value="" disabled selected hidden>Pilih Atribut</option>
                      <option value="Benefit" {{ old('type') == 'Benefit' ? 'selected' : '' }}>
                        Benefit
                      </option>
                      <option value="Cost" {{ old('type') == 'Cost' ? 'selected' : '' }}>
                        Cost
                      </option>
                    </select>
                  </div>
                <div class="mb-3">
                    <label for="weight" class="form-label">Bobot</label>
                    <input type="number" name="weight" class="form-control" value="{{ old('weight') }}" min="0" required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection