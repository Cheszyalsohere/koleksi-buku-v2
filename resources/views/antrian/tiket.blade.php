<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomor Antrian Anda</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
            padding: 20px;
        }
        .ticket {
            background: #fff; border-radius: 24px;
            width: 100%; max-width: 380px; overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.35);
            position: relative;
        }
        .ticket-head {
            background: linear-gradient(135deg, #10b981, #059669);
            padding: 26px; text-align: center; color: #fff;
        }
        .ticket-head .label { font-size: 13px; opacity: .9; letter-spacing: 1px; text-transform: uppercase; }
        .ticket-head h2 { font-size: 18px; font-weight: 700; margin-top: 4px; }
        /* perforasi */
        .perf {
            height: 24px; position: relative; background: #fff;
        }
        .perf::before, .perf::after {
            content: ''; position: absolute; top: 50%; transform: translateY(-50%);
            width: 24px; height: 24px; border-radius: 50%;
            background: #065f46;
        }
        .perf::before { left: -12px; }
        .perf::after  { right: -12px; }
        .perf .dash {
            position: absolute; top: 50%; left: 16px; right: 16px;
            border-top: 2px dashed #d1d5db;
        }
        .ticket-body { padding: 10px 30px 36px; text-align: center; }
        .nomor-label { font-size: 13px; color: #6b7280; letter-spacing: 1px; text-transform: uppercase; }
        .nomor {
            font-size: 92px; font-weight: 800; line-height: 1;
            color: #059669; margin: 10px 0 16px;
        }
        .nama-box {
            background: #f0fdf4; border: 1px solid #bbf7d0;
            border-radius: 14px; padding: 14px;
        }
        .nama-box .nm-label { font-size: 12px; color: #6b7280; }
        .nama-box .nm-val { font-size: 20px; font-weight: 700; color: #1f2937; margin-top: 2px; }
        .info {
            margin-top: 22px; font-size: 13px; color: #6b7280; line-height: 1.6;
        }
        .info strong { color: #059669; }
        .btn-print {
            margin-top: 22px; padding: 13px 30px;
            border: 2px solid #10b981; background: #fff; color: #059669;
            border-radius: 12px; font-size: 14px; font-weight: 700; font-family: inherit;
            cursor: pointer; transition: all .2s;
        }
        .btn-print:hover { background: #10b981; color: #fff; }
        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .ticket { box-shadow: none; }
            .perf::before, .perf::after { background: #fff; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-head">
            <div class="label">Nomor Antrian</div>
            <h2>Sistem Antrian Digital</h2>
        </div>

        <div class="perf"><div class="dash"></div></div>

        <div class="ticket-body">
            <div class="nomor-label">Nomor Anda</div>
            <div class="nomor">{{ str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT) }}</div>

            <div class="nama-box">
                <div class="nm-label">Atas Nama</div>
                <div class="nm-val">{{ $antrian->nama }}</div>
            </div>

            <p class="info">
                Tanggal: <strong>{{ $antrian->created_at->format('d M Y, H:i') }}</strong><br>
                Silakan tunggu nomor Anda dipanggil.<br>
                Perhatikan papan antrian.
            </p>

            <button class="btn-print no-print" onclick="window.print()">🖨️ Cetak Tiket</button>
        </div>
    </div>
</body>
</html>
