@extends('layouts.app')

@section('title', empty($alternative->data_kriteria) ? 'Tambah Alternative' : 'Edit Alternative')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1>{{ empty($alternative->data_kriteria) ? 'Tambah Nilai' : 'Edit Alternative' }}</h1>
            
            <form action="{{ route('alternatives.update', $alternative->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama" class="form-label">Name</label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ $alternative->nama }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="nim" class="form-label">NIM</label>
                    <input type="text" name="nim" id="nim" class="form-control" value="{{ $alternative->nim }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input type="text" name="jurusan" id="jurusan" class="form-control" value="{{ $alternative->jurusan }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password (Leave empty to keep current password)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>
                
                @foreach ($criterias as $criteria)
                <div class="mb-3">
                    <label class="form-label">{{ $criteria->name }}</label>
                    
                    @if(in_array(strtolower($criteria->name), ['attitude', 'komunikasi', 'problem solving']))
                        <input type="number" name="data_kriteria[{{ $criteria->id }}]" class="form-control"
                            value="{{ old('data_kriteria.' . $criteria->id, $alternative->nilai_manual[$criteria->id] ?? '') }}" required>
                    @else
                        <select name="data_kriteria[{{ $criteria->id }}]" class="form-control" required>
                            <option value="">-- Pilih Sub Kriteria --</option>
                            @foreach ($criteria->subCriterias as $sub)
                                <option value="{{ $sub->id }}"
                                    @if(old('data_kriteria.' . $criteria->id, $alternative->data_kriteria[$criteria->id] ?? '') == $sub->id) selected @endif>
                                    {{ $sub->nama_sub }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
                @endforeach
                                       
                <button type="submit" class="btn btn-primary">{{ empty($alternative->data_kriteria) ? 'Tambah' : 'Update' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
