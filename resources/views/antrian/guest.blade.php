<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Nomor Antrian</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e3a8a 100%);
            padding: 20px;
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(circle at 15% 20%, rgba(168,85,247,0.25), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(59,130,246,0.25), transparent 40%);
        }
        .card {
            position: relative; z-index: 1;
            background: rgba(255,255,255,0.97);
            border-radius: 28px;
            padding: 44px 38px;
            width: 100%; max-width: 420px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.4);
            text-align: center;
        }
        .icon-circle {
            width: 78px; height: 78px; border-radius: 22px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 12px 28px rgba(99,102,241,0.4);
            font-size: 38px;
        }
        h1 { font-size: 26px; font-weight: 800; color: #1e1b4b; margin-bottom: 6px; }
        .subtitle { color: #6b7280; font-size: 14px; margin-bottom: 30px; }
        label { display: block; text-align: left; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 8px; }
        input[type=text] {
            width: 100%; padding: 16px 18px;
            border: 2px solid #e5e7eb; border-radius: 14px;
            font-size: 16px; font-family: inherit;
            transition: border-color .2s, box-shadow .2s; outline: none;
        }
        input[type=text]:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.12); }
        .btn {
            width: 100%; margin-top: 22px; padding: 16px;
            border: none; border-radius: 14px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: #fff; font-size: 17px; font-weight: 700; font-family: inherit;
            cursor: pointer; transition: transform .15s, box-shadow .2s;
            box-shadow: 0 10px 26px rgba(99,102,241,0.35);
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 14px 32px rgba(99,102,241,0.45); }
        .btn:active { transform: translateY(0); }
        .err { color: #dc2626; font-size: 13px; margin-top: 8px; text-align: left; }
        .footer-note { margin-top: 24px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-circle">🎫</div>
        <h1>Ambil Nomor Antrian</h1>
        <p class="subtitle">Masukkan nama Anda untuk mendapatkan nomor antrian</p>

        <form action="{{ route('antrian.daftar') }}" method="POST" target="_blank">
            @csrf
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Budi Santoso"
                value="{{ old('nama') }}" autocomplete="off" autofocus required>
            @error('nama')<div class="err">{{ $message }}</div>@enderror

            <button type="submit" class="btn">Ambil Nomor →</button>
        </form>

        <p class="footer-note">Nomor antrian akan terbuka di tab baru</p>
    </div>
</body>
</html>
