@extends('layouts.app')

@section('title', 'Edit Sub Criteria')

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
            <h1>Edit Sub Criteria</h1>
            <form action="{{ route('sub_criterias.update', $subCriteria->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="criteria_id">Kriteria</label>
                    <select name="criteria_id" id="criteria_id" class="form-control" required>
                        @foreach($criterias as $criteria)
                        <option value="{{ $criteria->id }}" {{ $subCriteria->criteria_id == $criteria->id ? 'selected' : '' }}>{{ $criteria->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="nama_sub">Sub Kriteria</label>
                    <input type="text" name="nama_sub" id="nama_sub" class="form-control" value="{{ $subCriteria->nama_sub }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="nilai">Nilai</label>
                    <input type="number" name="nilai" id="nilai" class="form-control" value="{{ $subCriteria->nilai }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection