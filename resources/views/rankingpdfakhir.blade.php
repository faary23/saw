<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ranking</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
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

    <h2 align="center">Hasil Keputusan Akhir</h2>
    <table>
        <thead>
            <tr>
                <th>Ranking</th>
                <th>Nama Alternatif</th>
                <th>Total Skor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alternatives as $alternative)
            <tr>
                <td>{{ $alternative->perangkingan->rank }}</td>
                <td>{{ $alternative->nama }}</td>
                <td>{{ $alternative->perangkingan->total_score }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
