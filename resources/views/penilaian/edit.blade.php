@extends('layouts.apppenilai')

@section('title', $isEdit ? 'Edit Penilaian' : 'Tambah Penilaian')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-body">
            <h1>{{ $isEdit ? 'Edit Penilaian' : 'Tambah Penilaian' }}</h1>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('penilaian.update', $alternative->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" value="{{ $alternative->nama }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control" value="{{ $alternative->nim }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jurusan</label>
                    <input type="text" class="form-control" value="{{ $alternative->jurusan }}" readonly>
                </div>
                @foreach ($criterias as $criteria)
                @if (strtoupper(session('role')) == strtoupper($criteria->kode))
                <div class="mb-3">
                    <label class="form-label">{{ $criteria->name }}</label>

                    @if (in_array(strtolower($criteria->name), ['attitude', 'komunikasi', 'problem solving']))
                    <input type="number"
                        name="data_kriteria[{{ $criteria->id }}]"
                        class="form-control"
                        value="{{ old('data_kriteria.' . $criteria->id, $alternative->nilai_manual[$criteria->id] ?? '') }}"
                        required>
                    @else
                    <select name="data_kriteria[{{ $criteria->id }}]" class="form-control" required>
                        <option value="">-- Pilih Sub Kriteria --</option>
                        @foreach ($criteria->subCriterias as $sub)
                        <option value="{{ $sub->id }}"
                            @selected(old('data_kriteria.' . $criteria->id, $alternative->data_kriteria[$criteria->id] ?? '') == $sub->id)>
                            {{ $sub->nama_sub }}
                        </option>
                        @endforeach
                    </select>
                    @endif
                </div>
                @endif
                @endforeach


                <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Tambah' }}</button>
            </form>
        </div>
    </div>
</div>
@endsection