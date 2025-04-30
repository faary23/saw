@extends('layouts.app')

@section('title', 'Edit Criteria')

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
            <h1>Edit Criteria</h1>
            <form action="{{ route('criteria.update', $criteria->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="kode" class="form-label">Kriteria</label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode', $criteria->kode) }}" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Keterangan</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $criteria->name) }}" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Atribut</label>
                    <select name="type" class="form-control" required>
                        <option value="Benefit" {{ old('type', $criteria->type) == 'Benefit' ? 'selected' : '' }}>Benefit</option>
                        <option value="Cost" {{ old('type', $criteria->type) == 'Cost' ? 'selected' : '' }}>Cost</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="weight" class="form-label">Bobot</label>
                    <input type="number" name="weight" class="form-control" value="{{ old('weight', $criteria->weight) }}" min="0" required>
                </div>
                <button type="submit" class="btn btn-success">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection