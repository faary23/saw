<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ranking</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; } /* Ukuran font lebih kecil untuk keseluruhan body */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { 
            border: 1px solid black; 
            padding: 6px; /* Mengurangi padding agar tampilan lebih kompak */
            text-align: center; 
            font-size: 10px; /* Ukuran font lebih kecil untuk tabel */
        }
        th { 
            background-color: #f2f2f2; 
            font-size: 10px; /* Ukuran font header tabel sedikit lebih besar */
        }
        .header { text-align: center; margin-bottom: 20px; position: relative; }
        .logo-left { position: absolute; left: 50px; top: 10px; width: 80px; }
        .logo-right { position: absolute; right: 50px; top: 10px; width: 100px; }
        .header h2, .header h3, .header p { margin: 5px 0; }
        .line { border-top: 2px solid black; margin-top: 5px; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('assets/img/logopoliwangi.png') }}" alt="Logo Poliwangi" class="logo-left">
        <img src="{{ public_path('assets/img/logobem.png') }}" alt="Logo BEM" class="logo-right">
        
        <h2>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN,</h2>
        <h2>RISET, DAN TEKNOLOGI</h2>
        <h3>BEM POLITEKNIK NEGERI BANYUWANGI</h3>
        <p>Jl. Raya Jember kilometer 13 Labanasem, Kabat, Banyuwangi, 68461</p>
        <p>Telepon/Faks: (0333) 636780</p>
        <p>E-mail: poliwangi@poliwangi.ac.id | Website: <a href="http://www.poliwangi.ac.id">www.poliwangi.ac.id</a></p>
        
        <div class="line"></div>
    </div>

    <h1 align="center">Hasil Perangkingan</h1>
    <table>
        <thead>
            <tr>
                <th>Ranking</th>
                <th>Nama Alternatif</th>
                <th>Jurusan</th>
                @foreach($criterias as $criteria)
                    <th>{{ $criteria->name }}</th>
                @endforeach
                <th>Total Skor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alternatives as $alternative)
            @php
                $ranking = $alternative->perangkingan;
                $data_kriteria = $alternative->data_kriteria ?? [];
                $acceptedCount = session('accepted_count', 0);
                $rank = $ranking->rank ?? 999;
                $isAccepted = $rank > 0 && $rank <= $acceptedCount;
            @endphp
            <tr>
                <td>{{ $ranking->rank ?? '-' }}</td>
                <td>{{ $alternative->nama }}</td>
                <td>{{ $alternative->jurusan }}</td>
                @foreach($criterias as $criteria)
                    @php
                        $nilai = $alternative->weightedValues
                            ->firstWhere('criteria_id', $criteria->id)
                            ->weighted_value ?? 0;
                    @endphp
                    <td>{{ number_format($nilai, 2, '.', '') }}</td>
                @endforeach
                <td>{{ number_format($ranking->total_score ?? 0, 2, '.', '') }}</td>
                <td>
                    {{ $ranking->status == 1 ? 'Diterima' : 'Ditolak' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
