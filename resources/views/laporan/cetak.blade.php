<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $data['judul'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }
        h1 { font-size: 16px; margin: 0 0 4px; color: #1e3a5f; }
        h2 { font-size: 12px; margin: 0 0 12px; font-weight: normal; color: #555; }
        .meta { font-size: 9px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 5px 6px; text-align: left; }
        th { background: #1e3a5f; color: #fff; font-weight: bold; }
        tr:nth-child(even) { background: #f8f9fa; }
    </style>
</head>
<body>
    <h1>{{ $namaToko }}</h1>
    <h2>{{ $data['judul'] }}</h2>
    <p class="meta">
        @if ($periode)
            Periode: {{ $periode }} &nbsp;|&nbsp;
        @endif
        Dicetak: {{ $dicetakPada }} WIB
    </p>

    <table>
        <thead>
            <tr>
                @foreach ($data['kolom'] as $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($data['baris'] as $baris)
                <tr>
                    @foreach ($baris as $sel)
                        <td>{{ $sel }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($data['kolom']) }}">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
