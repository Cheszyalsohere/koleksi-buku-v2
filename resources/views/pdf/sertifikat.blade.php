<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Resmi – Workshop Web Development</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=Cinzel:wght@400;600;700&family=EB+Garamond:ital@1&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold-deep:   #8B6914;
            --gold-mid:    #C9A84C;
            --gold-light:  #E8D08A;
            --gold-pale:   #F5EAC0;
            --navy:        #0F1E3C;
            --navy-soft:   #1A305A;
            --cream:       #FDFAF2;
            --cream-dark:  #F5EFD8;
            --ink:         #1C1408;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        body {
            background: #2a2218;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
                min-height: unset;
            }
            .cert-shell {
                box-shadow: none !important;
                animation: none !important;
            }
        }

        /* Certificate Shell ─ A4 landscape: 297mm × 210mm — at 96dpi ≈ 1122px × 794px */
        .cert-shell {
            position: relative;
            width: 1122px;
            height: 794px;
            max-width: 100%;
            background: var(--cream);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(201, 168, 76, 0.4);
            animation: fadeUp 1s ease both;
            overflow: hidden;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Outer Borders */
        .border-outer {
            position: absolute;
            inset: 12px;
            border: 1.5px solid var(--gold-mid);
            pointer-events: none;
            z-index: 2;
        }

        .border-inner {
            position: absolute;
            inset: 18px;
            border: 0.5px solid var(--gold-light);
            pointer-events: none;
            z-index: 2;
        }

        /* Background Watermark */
        .watermark {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.04;
            pointer-events: none;
            z-index: 0;
        }

        .watermark-text {
            font-family: 'Cinzel', serif;
            font-size: 160px;
            color: var(--gold-deep);
            letter-spacing: 0.1em;
            text-align: center;
            line-height: 0.9;
            transform: rotate(-20deg);
        }

        /* Corner Flourishes */
        .corner {
            position: absolute;
            width: 72px;
            height: 72px;
            z-index: 3;
        }

        .c-tl {
            top: 0;
            left: 0;
        }

        .c-tr {
            top: 0;
            right: 0;
            transform: scaleX(-1);
        }

        .c-bl {
            bottom: 0;
            left: 0;
            transform: scaleY(-1);
        }

        .c-br {
            bottom: 0;
            right: 0;
            transform: scale(-1, -1);
        }

        /* Content Area */
        .content {
            position: relative;
            z-index: 1;
            padding: 30px 72px 28px;
            text-align: center;
        }

        /* Header */
        .inst-logo {
            margin-bottom: 4px;
        }

        .inst-logo svg {
            width: 42px;
            height: 42px;
        }

        .inst-name {
            font-family: 'Cinzel', serif;
            font-size: 10px;
            letter-spacing: 4px;
            color: var(--gold-deep);
            text-transform: uppercase;
            margin-bottom: 1px;
        }

        .inst-sub {
            font-family: 'Cormorant Garamond', serif;
            font-size: 12px;
            color: #888;
            letter-spacing: 1.5px;
        }

        /* Top Divider */
        .divider-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 10px 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, var(--gold-mid), transparent);
        }

        .divider-gem {
            color: var(--gold-mid);
            font-size: 14px;
        }

        /* Certificate Title */
        .cert-label {
            font-family: 'Cinzel', serif;
            font-size: 9px;
            letter-spacing: 6px;
            color: var(--gold-deep);
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .cert-title {
            font-family: 'Cinzel', serif;
            font-size: 44px;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: 0.12em;
            line-height: 1;
            margin-bottom: 4px;
        }

        .cert-subtitle {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 13px;
            color: var(--gold-deep);
            letter-spacing: 2px;
        }

        /* Presented To */
        .presented-to {
            font-family: 'Cormorant Garamond', serif;
            font-size: 13px;
            color: #666;
            letter-spacing: 2px;
            margin-top: 14px;
            margin-bottom: 6px;
        }

        /* Recipient Name */
        .recipient-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 44px;
            font-weight: 300;
            font-style: italic;
            color: var(--navy);
            line-height: 1.1;
            margin-bottom: 4px;
            position: relative;
            display: inline-block;
        }

        .name-underline {
            display: block;
            height: 1.5px;
            background: linear-gradient(
                to right,
                transparent 0%,
                var(--gold-mid) 30%,
                var(--gold-mid) 70%,
                transparent 100%
            );
            margin-top: 6px;
        }

        /* Description */
        .award-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 15px;
            color: #555;
            margin-top: 10px;
            line-height: 1.6;
            max-width: 560px;
            margin-left: auto;
            margin-right: auto;
        }

        .award-event {
            font-family: 'Cinzel', serif;
            font-size: 14px;
            color: var(--navy);
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Middle Ornament Band */
        .band {
            background: linear-gradient(135deg, var(--navy) 0%, var(--navy-soft) 100%);
            margin: 14px -72px;
            padding: 10px 72px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
        }

        .band-line {
            flex: 1;
            height: 1px;
            background: rgba(201, 168, 76, 0.4);
        }

        .band-text {
            font-family: 'Cinzel', serif;
            font-size: 9px;
            letter-spacing: 4px;
            color: var(--gold-light);
            text-transform: uppercase;
        }

        /* Bottom / Signatures */
        .signatures {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: end;
            gap: 20px;
            margin-top: 16px;
        }

        .sig-block {
            text-align: center;
        }

        .sig-line {
            width: 140px;
            height: 1px;
            background: var(--gold-mid);
            margin: 0 auto 6px;
        }

        .sig-name {
            font-family: 'Cinzel', serif;
            font-size: 11px;
            color: var(--navy);
            font-weight: 600;
            letter-spacing: 1px;
        }

        .sig-role {
            font-family: 'Cormorant Garamond', serif;
            font-size: 12px;
            color: #888;
            font-style: italic;
            margin-top: 2px;
        }

        /* Seal */
        .seal {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .seal-ring {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 2px solid var(--gold-mid);
            background: radial-gradient(
                circle at 40% 35%,
                var(--gold-pale),
                var(--cream-dark)
            );
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 2px 12px rgba(139, 105, 20, 0.3),
                inset 0 1px 3px rgba(255, 255, 255, 0.6);
            position: relative;
        }

        .seal-ring::before {
            content: '';
            position: absolute;
            inset: 5px;
            border-radius: 50%;
            border: 1px dashed var(--gold-mid);
            opacity: 0.6;
        }

        .seal-initial {
            font-family: 'Cinzel', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--gold-deep);
            letter-spacing: 1px;
        }

        .seal-text {
            font-family: 'Cinzel', serif;
            font-size: 7.5px;
            letter-spacing: 2.5px;
            color: var(--gold-deep);
            text-transform: uppercase;
        }

        /* Footer */
        .cert-footer {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 0.5px solid var(--gold-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 11px;
            color: #aaa;
            letter-spacing: 1px;
        }

                .signatures {
            margin-top: 80px;
            text-align: center;
        }

        .sig-block {
            display: inline-block;
            width: 40%;
            vertical-align: top;
            margin: 0 20px;
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid black;
            margin-bottom: 5px;
        }

    </style>
</head>
<body>

    <div class="cert-shell">
        <!-- Borders -->
        <div class="border-outer"></div>
        <div class="border-inner"></div>

        <!-- Watermark -->
        <div class="watermark">
            <div class="watermark-text">FV</div>
        </div>

        <!-- Corner SVG Flourishes -->
        <svg class="corner c-tl" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 4 L4 36 Q4 4 36 4 Z" fill="none" stroke="#C9A84C" stroke-width="1"/>
            <path d="M2 2 L2 42" stroke="#C9A84C" stroke-width="1.2"/>
            <path d="M2 2 L42 2" stroke="#C9A84C" stroke-width="1.2"/>
            <path d="M8 8 L8 28" stroke="#E8D08A" stroke-width="0.6"/>
            <path d="M8 8 L28 8" stroke="#E8D08A" stroke-width="0.6"/>
            <circle cx="2" cy="2" r="2.5" fill="#C9A84C"/>
            <circle cx="8" cy="8" r="1.5" fill="#E8D08A"/>
            <path d="M14 2 Q8 8 2 14" stroke="#C9A84C" stroke-width="0.5" fill="none"/>
            <path d="M22 2 Q14 10 2 22" stroke="#C9A84C" stroke-width="0.4" fill="none" opacity="0.5"/>
        </svg>

        <svg class="corner c-tr" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2 L2 42" stroke="#C9A84C" stroke-width="1.2"/>
            <path d="M2 2 L42 2" stroke="#C9A84C" stroke-width="1.2"/>
            <path d="M8 8 L8 28" stroke="#E8D08A" stroke-width="0.6"/>
            <path d="M8 8 L28 8" stroke="#E8D08A" stroke-width="0.6"/>
            <circle cx="2" cy="2" r="2.5" fill="#C9A84C"/>
            <circle cx="8" cy="8" r="1.5" fill="#E8D08A"/>
            <path d="M14 2 Q8 8 2 14" stroke="#C9A84C" stroke-width="0.5" fill="none"/>
            <path d="M22 2 Q14 10 2 22" stroke="#C9A84C" stroke-width="0.4" fill="none" opacity="0.5"/>
        </svg>

        <svg class="corner c-bl" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2 L2 42" stroke="#C9A84C" stroke-width="1.2"/>
            <path d="M2 2 L42 2" stroke="#C9A84C" stroke-width="1.2"/>
            <path d="M8 8 L8 28" stroke="#E8D08A" stroke-width="0.6"/>
            <path d="M8 8 L28 8" stroke="#E8D08A" stroke-width="0.6"/>
            <circle cx="2" cy="2" r="2.5" fill="#C9A84C"/>
            <circle cx="8" cy="8" r="1.5" fill="#E8D08A"/>
            <path d="M14 2 Q8 8 2 14" stroke="#C9A84C" stroke-width="0.5" fill="none"/>
            <path d="M22 2 Q14 10 2 22" stroke="#C9A84C" stroke-width="0.4" fill="none" opacity="0.5"/>
        </svg>

        <svg class="corner c-br" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M2 2 L2 42" stroke="#C9A84C" stroke-width="1.2"/>
            <path d="M2 2 L42 2" stroke="#C9A84C" stroke-width="1.2"/>
            <path d="M8 8 L8 28" stroke="#E8D08A" stroke-width="0.6"/>
            <path d="M8 8 L28 8" stroke="#E8D08A" stroke-width="0.6"/>
            <circle cx="2" cy="2" r="2.5" fill="#C9A84C"/>
            <circle cx="8" cy="8" r="1.5" fill="#E8D08A"/>
            <path d="M14 2 Q8 8 2 14" stroke="#C9A84C" stroke-width="0.5" fill="none"/>
            <path d="M22 2 Q14 10 2 22" stroke="#C9A84C" stroke-width="0.4" fill="none" opacity="0.5"/>
        </svg>

        <div class="content">
            <!-- Institusi -->
            <div class="inst-logo">
                <svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="26" cy="26" r="24" stroke="#C9A84C" stroke-width="1.2"/>
                    <circle cx="26" cy="26" r="19" stroke="#C9A84C" stroke-width="0.6" stroke-dasharray="3 2"/>
                    <text x="26" y="31" text-anchor="middle" font-family="Cinzel,serif" font-size="11" font-weight="600" fill="#8B6914">FV</text>
                </svg>
            </div>
            <div class="inst-name">Universitas Airlangga</div>
            <div class="inst-sub">Fakultas Vokasi</div>

            <!-- Divider -->
            <div class="divider-row">
                <div class="divider-line"></div>
                <div class="divider-gem">.</div>
                <div class="divider-gem">.</div>
                <div class="divider-line"></div>
            </div>

            <!-- Title -->
            <div class="cert-label">Certificate of Participation</div>
            <div class="cert-title">SERTIFIKAT</div>
            <div class="cert-subtitle">Penghargaan atas Partisipasi</div>

        <!-- Recipient -->
        <div class="presented-to">Diberikan kepada</div>
        <div class="recipient-name">
            {{ $user->name }}
            <span class="name-underline"></span>
        </div>

        <!-- Award description -->
        <p class="award-text">
            atas partisipasi dan dedikasi yang penuh semangat dalam kegiatan<br>
            <span class="award-event">Workshop Web Development</span><br>
            yang diselenggarakan oleh Fakultas Vokasi, Universitas Airlangga
        </p>

        <!-- Band Ornament -->
        <div class="band">
            <div class="band-line"></div>
            <div class="band-text">Surabaya &bull; 2026</div>
            <div class="band-line"></div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-name">Javier Rakha Abiesta, M.T.</div>
                <div class="sig-role">Ketua Panitia</div>
            </div>



            <div class="sig-block">
                <div class="sig-line"></div>
                <div class="sig-name">Prof. Dr. Ir. Fulan</div>
                <div class="sig-role">Dekan Fakultas Vokasi</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="cert-footer">
            <span>No. Sertifikat: FV/WD/2026/001</span>
            <span>◆</span>
            <span>Diterbitkan: 1 Maret 2026</span>
        </div>

    </div>
</div>

</body>
</html>