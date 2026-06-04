<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode — {{ $toko->nama_toko }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 32px 40px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 420px;
            width: 100%;
        }
        .store-name {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .store-barcode-text {
            font-family: monospace;
            font-size: 13px;
            color: #64748b;
            margin-bottom: 16px;
        }
        .barcode-img {
            display: block;
            margin: 0 auto 8px;
            max-width: 100%;
        }
        .coords {
            font-family: monospace;
            font-size: 11px;
            color: #94a3b8;
            margin-top: 12px;
            line-height: 1.6;
        }
        .btn-print {
            margin-top: 20px;
            padding: 10px 28px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-print:hover { background: #1d4ed8; }
        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .card { box-shadow: none; border: 1px solid #e2e8f0; }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="store-name">{{ $toko->nama_toko }}</div>
    <div class="store-barcode-text">{{ $toko->barcode }}</div>

    <img class="barcode-img"
         src="data:image/png;base64,{{ $barcode }}"
         alt="Barcode {{ $toko->barcode }}">

    <div class="coords">
        Lat: {{ $toko->latitude }} | Lng: {{ $toko->longitude }}<br>
        Accuracy: {{ $toko->accuracy }} m
    </div>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print Barcode
        </button>
    </div>
</div>

</body>
</html>
