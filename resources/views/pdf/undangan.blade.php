<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Resmi - Seminar Kecerdasan Buatan</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Lato:wght@300;400;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f0ece4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px 20px;
            font-family: 'Lato', sans-serif;
        }

        .invitation-wrapper {
            background: #fff;
            max-width: 720px;
            width: 100%;
            border: 1px solid #d4c9a8;
            box-shadow: 0 8px 40px rgba(0,0,0,0.12);
            position: relative;
        }

        /* Ornamental border */
        .invitation-wrapper::before {
            content: '';
            position: absolute;
            inset: 10px;
            border: 1px solid #c8b87a;
            pointer-events: none;
            z-index: 1;
        }

        .invitation-inner {
            padding: 50px 60px;
            position: relative;
        }

        /* Corner ornaments */
        .corner {
            position: absolute;
            width: 40px;
            height: 40px;
            z-index: 2;
        }
        .corner svg { width: 100%; height: 100%; }
        .corner-tl { top: 18px; left: 18px; }
        .corner-tr { top: 18px; right: 18px; transform: scaleX(-1); }
        .corner-bl { bottom: 18px; left: 18px; transform: scaleY(-1); }
        .corner-br { bottom: 18px; right: 18px; transform: scale(-1); }

        /* Header */
        .header {
            text-align: center;
            padding-bottom: 28px;
            margin-bottom: 28px;
            border-bottom: 1px solid #c8b87a;
        }

        .header-logo {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #8a7240;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            color: #1a2d4e;
            margin-bottom: 4px;
            font-weight: 700;
        }

        .header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            color: #1a2d4e;
            font-weight: 400;
            font-style: italic;
        }

        .header address {
            font-style: normal;
            font-size: 12px;
            color: #888;
            margin-top: 6px;
            letter-spacing: 1px;
        }

        /* Divider ornament */
        .divider {
            text-align: center;
            margin: 24px 0;
            color: #c8b87a;
            font-size: 20px;
            letter-spacing: 8px;
        }

        /* Title block */
        .title-block {
            text-align: center;
            margin-bottom: 30px;
        }

        .badge {
            display: inline-block;
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #fff;
            background-color: #1a2d4e;
            padding: 6px 20px;
            margin-bottom: 16px;
        }

        .title-block h3 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #1a2d4e;
            margin-bottom: 6px;
        }

        .title-block h4 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #8a7240;
            font-weight: 400;
            font-style: italic;
        }

        /* Body text */
        .body-text {
            font-size: 14px;
            color: #444;
            line-height: 1.9;
            text-align: justify;
            margin-bottom: 28px;
        }

        /* Event details */
        .details-box {
            border: 1px solid #c8b87a;
            background: #fdf9f0;
            padding: 24px 30px;
            margin: 28px 0;
        }

        .details-title {
            text-align: center;
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #8a7240;
            margin-bottom: 18px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            text-align: center;
        }

        .detail-item label {
            display: block;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #8a7240;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .detail-item span {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 15px;
            color: #1a2d4e;
            font-weight: 700;
        }

        .detail-item small {
            display: block;
            font-size: 12px;
            color: #666;
            margin-top: 2px;
        }

        .detail-divider {
            width: 1px;
            background: #c8b87a;
            margin: 0 auto;
        }

        /* Closing */
        .closing {
            margin-top: 28px;
            text-align: center;
        }

        .closing p {
            font-size: 13px;
            color: #555;
            margin-bottom: 4px;
        }

        .closing .signature {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: #1a2d4e;
            margin-top: 12px;
            font-style: italic;
        }

        .closing .role {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #8a7240;
            margin-top: 4px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #c8b87a;
            font-size: 11px;
            color: #aaa;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<div class="invitation-wrapper">
    <!-- Corner ornaments -->
    <div class="corner corner-tl">
        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2 L2 20 M2 2 L20 2" stroke="#c8b87a" stroke-width="1.5"/>
            <circle cx="2" cy="2" r="2" fill="#c8b87a"/>
            <path d="M8 8 L8 16 M8 8 L16 8" stroke="#c8b87a" stroke-width="1"/>
        </svg>
    </div>
    <div class="corner corner-tr">
        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2 L2 20 M2 2 L20 2" stroke="#c8b87a" stroke-width="1.5"/>
            <circle cx="2" cy="2" r="2" fill="#c8b87a"/>
            <path d="M8 8 L8 16 M8 8 L16 8" stroke="#c8b87a" stroke-width="1"/>
        </svg>
    </div>
    <div class="corner corner-bl">
        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2 L2 20 M2 2 L20 2" stroke="#c8b87a" stroke-width="1.5"/>
            <circle cx="2" cy="2" r="2" fill="#c8b87a"/>
            <path d="M8 8 L8 16 M8 8 L16 8" stroke="#c8b87a" stroke-width="1"/>
        </svg>
    </div>
    <div class="corner corner-br">
        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2 L2 20 M2 2 L20 2" stroke="#c8b87a" stroke-width="1.5"/>
            <circle cx="2" cy="2" r="2" fill="#c8b87a"/>
            <path d="M8 8 L8 16 M8 8 L16 8" stroke="#c8b87a" stroke-width="1"/>
        </svg>
    </div>

    <div class="invitation-inner">

        <!-- Header Institusi -->
        <div class="header">
            <div class="header-logo">Universitas Airlangga</div>
            <h1>FAKULTAS VOKASI</h1>
            <h2>Surabaya, Indonesia</h2>
            <address>Jl. Kertajaya, Surabaya</address>
        </div>


        <!-- Judul Acara -->
        <div class="title-block">
            <div class="badge">Undangan Resmi</div>
            <h3>Seminar</h3>
            <h4>Kecerdasan Buatan</h4>
        </div>

        <!-- Teks Undangan -->
        <p class="body-text">
            Dengan hormat, kami dari Panitia Seminar Fakultas Vokasi Universitas Airlangga
            mengundang Saudara/i untuk hadir dan berpartisipasi dalam acara
            <strong>Seminar Kecerdasan Buatan</strong> yang kami selenggarakan.
            Kehadiran Saudara/i akan sangat berarti bagi kelancaran dan kesuksesan acara ini.
        </p>

        <!-- Detail Acara -->
        <div class="details-box">
            <div class="details-title">Pelaksanaan Acara</div>
            <div class="details-grid">
                <div class="detail-item">
                    <label>Tanggal</label>
                    <span>1 Maret</span>
                    <small>2026</small>
                </div>
                <div class="detail-item">
                    <label>Tempat</label>
                    <span>Airlangga Hall</span>
                    <small>Universitas Airlangga</small>
                </div>
                <div class="detail-item">
                    <label>Waktu</label>
                    <span>09.00 WIB</span>
                    <small>s.d. selesai</small>
                </div>
            </div>
        </div>

        <!-- Penutup -->
        <p class="body-text">
            Besar harapan kami atas kehadiran dan partisipasi Saudara/i dalam acara ini.
            Atas perhatian dan kerjasamanya, kami mengucapkan terima kasih.
        </p>

        <!-- Tanda Tangan -->
        <div class="closing">
            <p>Hormat Kami,</p>
            <div class="signature">Panitia Seminar</div>
            <div class="role">Fakultas Vokasi &bull; Universitas Airlangga</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Fakultas Vokasi &bull; Universitas Airlangga &bull; Jl. Kertajaya, Surabaya
        </div>

    </div>
</div>

</body>
</html>