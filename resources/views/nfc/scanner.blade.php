<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NFC Absensi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=JetBrains+Mono:wght@400;600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
:root {
    --bg:        #04080f;
    --bg2:       #080f1c;
    --surface:   rgba(255,255,255,0.04);
    --border:    rgba(0,212,255,0.12);
    --border-hi: rgba(0,212,255,0.4);
    --cyan:      #00d4ff;
    --cyan-dim:  rgba(0,212,255,0.15);
    --violet:    #7c3aed;
    --green:     #00ff87;
    --green-dim: rgba(0,255,135,0.12);
    --red:       #ff3860;
    --red-dim:   rgba(255,56,96,0.12);
    --amber:     #ffb800;
    --amber-dim: rgba(255,184,0,0.12);
    --text:      #e2eaf5;
    --text-muted:#5a7090;
    --font-head: 'Rajdhani', sans-serif;
    --font-mono: 'JetBrains Mono', monospace;
    --font-body: 'DM Sans', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
    background: var(--bg);
    color: var(--text);
    font-family: var(--font-body);
    min-height: 100vh;
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

/* ─── BACKGROUND MESH ─────────────────────────────── */
body::before {
    content: '';
    position: fixed; inset: 0;
    background:
        radial-gradient(ellipse 80% 50% at 20% -10%, rgba(0,212,255,0.08) 0%, transparent 60%),
        radial-gradient(ellipse 60% 40% at 80% 110%, rgba(124,58,237,0.07) 0%, transparent 60%),
        radial-gradient(ellipse 50% 30% at 50% 50%, rgba(0,255,135,0.03) 0%, transparent 70%);
    pointer-events: none; z-index: 0;
}

/* scanline texture */
body::after {
    content: '';
    position: fixed; inset: 0;
    background: repeating-linear-gradient(
        0deg,
        transparent,
        transparent 2px,
        rgba(0,0,0,0.03) 2px,
        rgba(0,0,0,0.03) 4px
    );
    pointer-events: none; z-index: 0;
}

/* ─── HEADER ──────────────────────────────────────── */
.header {
    position: relative; z-index: 10;
    padding: 18px 20px 16px;
    border-bottom: 1px solid var(--border);
    background: rgba(4,8,15,0.85);
    backdrop-filter: blur(20px);
    display: flex; align-items: center; justify-content: space-between;
}
.header-logo {
    display: flex; align-items: center; gap: 10px;
}
.logo-orb {
    width: 36px; height: 36px; border-radius: 10px;
    background: linear-gradient(135deg, var(--cyan), var(--violet));
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 16px rgba(0,212,255,0.4);
}
.logo-orb i { font-size: 18px; color: #fff; }
.header-title {
    font-family: var(--font-head);
    font-size: 20px; font-weight: 700; letter-spacing: 0.5px;
    line-height: 1.1;
    background: linear-gradient(90deg, var(--cyan), #a5f3ff);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}
.header-sub { font-size: 10px; color: var(--text-muted); letter-spacing: 1.5px; text-transform: uppercase; }
.header-time {
    font-family: var(--font-mono);
    font-size: 13px; color: var(--cyan); text-align: right;
    opacity: 0.8;
}
.header-date { font-size: 10px; color: var(--text-muted); letter-spacing: 0.5px; }

/* ─── TABS ────────────────────────────────────────── */
.tabs-wrap {
    position: relative; z-index: 10;
    padding: 14px 16px;
}
.tabs {
    display: flex; background: rgba(255,255,255,0.03);
    border: 1px solid var(--border); border-radius: 14px; padding: 4px; gap: 4px;
    position: relative;
}
.tab-slider {
    position: absolute;
    top: 4px; bottom: 4px;
    width: calc(50% - 4px);
    background: linear-gradient(135deg, rgba(0,212,255,0.2), rgba(124,58,237,0.2));
    border: 1px solid var(--border-hi);
    border-radius: 10px;
    transition: transform .3s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 0 20px rgba(0,212,255,0.1);
}
.tab-slider.right { transform: translateX(calc(100% + 4px)); }
.tab {
    flex: 1; padding: 10px 8px; border: none; background: transparent;
    color: var(--text-muted); font-family: var(--font-body);
    font-size: 13px; font-weight: 600; cursor: pointer;
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
    gap: 7px; transition: color .3s; position: relative; z-index: 1;
    letter-spacing: 0.2px;
}
.tab.active { color: var(--cyan); }
.tab i { font-size: 15px; }

/* ─── PANELS ──────────────────────────────────────── */
.panel { position: relative; z-index: 5; padding: 0 16px; }

/* ─── GLASS CARD ──────────────────────────────────── */
.g-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 18px;
    margin-bottom: 14px;
    backdrop-filter: blur(10px);
    position: relative; overflow: hidden;
}
.g-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,212,255,0.3), transparent);
}
.g-label {
    font-size: 10px; color: var(--text-muted);
    letter-spacing: 1.8px; text-transform: uppercase;
    font-weight: 600; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;
}
.g-label::before {
    content: ''; width: 3px; height: 3px; border-radius: 50%; background: var(--cyan);
    box-shadow: 0 0 6px var(--cyan);
}

/* ─── INPUTS ──────────────────────────────────────── */
.g-input {
    width: 100%; padding: 13px 16px;
    background: rgba(0,0,0,0.3); border: 1px solid rgba(0,212,255,0.15);
    border-radius: 12px; color: var(--text);
    font-family: var(--font-body); font-size: 15px;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
}
.g-input:focus {
    border-color: var(--cyan);
    box-shadow: 0 0 0 3px rgba(0,212,255,0.08), 0 0 16px rgba(0,212,255,0.1);
}
.g-input::placeholder { color: var(--text-muted); }
.g-input option { background: #0d1520; }

/* ─── NFC ORB ─────────────────────────────────────── */
.nfc-zone {
    display: flex; flex-direction: column; align-items: center;
    padding: 10px 0 20px;
}
.nfc-orb-wrap {
    position: relative; width: 140px; height: 140px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 16px;
}
.nfc-ring {
    position: absolute; border-radius: 50%;
    border: 1.5px solid var(--cyan);
    opacity: 0;
}
.nfc-ring-1 { width: 100%; height: 100%; }
.nfc-ring-2 { width: 130%; height: 130%; }
.nfc-ring-3 { width: 160%; height: 160%; }
.nfc-ring-4 { width: 190%; height: 190%; }

.nfc-orb-wrap.scanning .nfc-ring-1 { animation: orbRing 2.4s .0s ease-out infinite; }
.nfc-orb-wrap.scanning .nfc-ring-2 { animation: orbRing 2.4s .4s ease-out infinite; }
.nfc-orb-wrap.scanning .nfc-ring-3 { animation: orbRing 2.4s .8s ease-out infinite; }
.nfc-orb-wrap.scanning .nfc-ring-4 { animation: orbRing 2.4s 1.2s ease-out infinite; }

@keyframes orbRing {
    0%   { opacity: 0.7; transform: scale(.6); }
    100% { opacity: 0; transform: scale(1); }
}

.nfc-orb {
    width: 100px; height: 100px; border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, rgba(0,212,255,0.3), rgba(0,212,255,0.05));
    border: 2px solid rgba(0,212,255,0.4);
    display: flex; align-items: center; justify-content: center;
    position: relative; z-index: 1;
    transition: all .3s cubic-bezier(.4,0,.2,1);
    box-shadow: 0 0 30px rgba(0,212,255,0.15), inset 0 1px 0 rgba(255,255,255,0.1);
}
.nfc-orb-wrap.scanning .nfc-orb {
    box-shadow: 0 0 50px rgba(0,212,255,0.35), 0 0 80px rgba(0,212,255,0.15), inset 0 1px 0 rgba(255,255,255,0.15);
    border-color: rgba(0,212,255,0.7);
    animation: orbPulse 1.8s ease-in-out infinite;
}
@keyframes orbPulse {
    0%,100% { transform: scale(1); }
    50%      { transform: scale(1.04); }
}
.nfc-orb i { font-size: 38px; color: var(--cyan); filter: drop-shadow(0 0 8px var(--cyan)); }
.nfc-orb-wrap.scanning .nfc-orb i { animation: iconPulse 1.8s ease-in-out infinite; }
@keyframes iconPulse { 0%,100%{opacity:1} 50%{opacity:.6} }

.nfc-status {
    font-family: var(--font-mono); font-size: 12px;
    color: var(--cyan); letter-spacing: 1px; text-align: center;
    min-height: 20px; opacity: 0;
    transition: opacity .3s;
}
.nfc-status.visible { opacity: 1; }
.nfc-status.blinking { animation: blink 1.2s infinite; }
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

/* ─── BUTTONS ─────────────────────────────────────── */
.btn-nfc {
    width: 100%; padding: 15px;
    border: none; border-radius: 14px;
    font-family: var(--font-head); font-size: 17px; font-weight: 700;
    letter-spacing: 0.8px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    transition: all .2s cubic-bezier(.4,0,.2,1);
    position: relative; overflow: hidden;
}
.btn-nfc::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(180deg, rgba(255,255,255,0.08) 0%, transparent 100%);
    pointer-events: none;
}
.btn-nfc:active { transform: scale(.97); }

.btn-activate {
    background: linear-gradient(135deg, #0099bb, #005577);
    border: 1px solid rgba(0,212,255,0.4);
    color: #fff;
    box-shadow: 0 4px 20px rgba(0,212,255,0.2), 0 1px 0 rgba(255,255,255,0.1) inset;
}
.btn-activate:hover { box-shadow: 0 6px 28px rgba(0,212,255,0.35); }

.btn-stop {
    background: linear-gradient(135deg, #880022, #440011);
    border: 1px solid rgba(255,56,96,0.4);
    color: #fff;
    box-shadow: 0 4px 20px rgba(255,56,96,0.2);
}
.btn-green {
    background: linear-gradient(135deg, #007744, #003322);
    border: 1px solid rgba(0,255,135,0.35);
    color: #fff;
    box-shadow: 0 4px 20px rgba(0,255,135,0.15);
}
.btn-sm-ghost {
    background: transparent; border: none;
    color: var(--cyan); font-size: 12px; font-family: var(--font-body);
    cursor: pointer; padding: 0; display: flex; align-items: center; gap: 4px;
    opacity: 0.7; transition: opacity .2s;
}
.btn-sm-ghost:hover { opacity: 1; }

.mt-3 { margin-top: 12px; }

/* ─── RESULT CARDS ────────────────────────────────── */
.result-wrap {
    margin-top: 14px;
    animation: slideUp .35s cubic-bezier(.4,0,.2,1) both;
}
@keyframes slideUp {
    from { opacity:0; transform: translateY(14px); }
    to   { opacity:1; transform: translateY(0); }
}
.result-card {
    border-radius: 16px; padding: 22px 18px; text-align: center;
    position: relative; overflow: hidden;
}
.result-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 1px;
}
.r-success {
    background: linear-gradient(160deg, rgba(0,255,135,0.08), rgba(0,255,135,0.03));
    border: 1px solid rgba(0,255,135,0.3);
}
.r-success::before { background: linear-gradient(90deg,transparent,rgba(0,255,135,0.5),transparent); }
.r-duplicate {
    background: linear-gradient(160deg, rgba(0,212,255,0.08), rgba(0,212,255,0.03));
    border: 1px solid rgba(0,212,255,0.3);
}
.r-duplicate::before { background: linear-gradient(90deg,transparent,rgba(0,212,255,0.5),transparent); }
.r-notfound {
    background: linear-gradient(160deg, rgba(255,184,0,0.08), rgba(255,184,0,0.03));
    border: 1px solid rgba(255,184,0,0.3);
}
.r-notfound::before { background: linear-gradient(90deg,transparent,rgba(255,184,0,0.5),transparent); }
.r-error {
    background: linear-gradient(160deg, rgba(255,56,96,0.08), rgba(255,56,96,0.03));
    border: 1px solid rgba(255,56,96,0.3);
}
.r-error::before { background: linear-gradient(90deg,transparent,rgba(255,56,96,0.5),transparent); }

.r-icon { font-size: 42px; line-height: 1; margin-bottom: 10px; }
.r-name {
    font-family: var(--font-head); font-size: 24px; font-weight: 700;
    color: #fff; letter-spacing: 0.3px; margin-bottom: 3px;
}
.r-nim {
    font-family: var(--font-mono); font-size: 12px; color: var(--text-muted);
    letter-spacing: 0.5px;
}
.r-meta { margin-top: 12px; display: flex; flex-direction: column; gap: 5px; align-items: center; }
.badge-hadir {
    background: rgba(0,255,135,0.15); border: 1px solid rgba(0,255,135,0.4);
    color: var(--green); padding: 4px 14px; border-radius: 20px;
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    font-family: var(--font-mono); display: inline-block;
}
.badge-terlambat {
    background: rgba(255,184,0,0.15); border: 1px solid rgba(255,184,0,0.4);
    color: var(--amber); padding: 4px 14px; border-radius: 20px;
    font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
    font-family: var(--font-mono); display: inline-block;
}
.r-time {
    font-family: var(--font-mono); font-size: 12px; color: var(--text-muted);
}

/* ─── SERIAL DISPLAY ──────────────────────────────── */
.serial-box {
    background: rgba(0,0,0,0.4); border: 1px dashed rgba(0,212,255,0.35);
    border-radius: 12px; padding: 14px;
    font-family: var(--font-mono); font-size: 13px; color: var(--cyan);
    text-align: center; margin: 12px 0;
    word-break: break-all; letter-spacing: 0.5px;
    animation: slideUp .3s both;
}
.serial-label {
    font-size: 9px; letter-spacing: 2px; text-transform: uppercase;
    color: var(--text-muted); margin-bottom: 4px;
}

/* ─── MHS LIST ────────────────────────────────────── */
.mhs-list {
    background: rgba(0,0,0,0.3); border: 1px solid var(--border);
    border-radius: 12px; max-height: 230px; overflow-y: auto;
    margin-top: 8px;
}
.mhs-list::-webkit-scrollbar { width: 4px; }
.mhs-list::-webkit-scrollbar-track { background: transparent; }
.mhs-list::-webkit-scrollbar-thumb { background: rgba(0,212,255,0.2); border-radius: 2px; }
.mhs-item {
    padding: 12px 14px; display: flex; align-items: center; gap: 12px;
    cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.03);
    transition: background .15s;
}
.mhs-item:last-child { border-bottom: none; }
.mhs-item:active { background: rgba(0,212,255,0.06); }
.mhs-avatar {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    background: linear-gradient(135deg, rgba(0,212,255,0.2), rgba(124,58,237,0.2));
    border: 1px solid rgba(0,212,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-head); font-size: 14px; font-weight: 700; color: var(--cyan);
}
.mhs-name { font-size: 14px; font-weight: 500; color: var(--text); line-height: 1.2; }
.mhs-nim { font-family: var(--font-mono); font-size: 11px; color: var(--text-muted); }

.selected-box {
    background: rgba(0,212,255,0.05); border: 1px solid rgba(0,212,255,0.25);
    border-radius: 12px; padding: 12px 14px;
    display: flex; align-items: center; justify-content: space-between;
    margin-top: 10px; animation: slideUp .25s both;
}
.selected-info { display: flex; align-items: center; gap: 10px; }
.selected-name { font-size: 14px; font-weight: 600; color: var(--text); }
.selected-nim { font-family: var(--font-mono); font-size: 11px; color: var(--text-muted); }

.empty-state {
    text-align: center; padding: 20px; color: var(--text-muted);
    font-size: 13px;
}
.empty-state i { font-size: 28px; display: block; margin-bottom: 8px; opacity: .4; }

/* ─── DIVIDER ─────────────────────────────────────── */
.section-divider {
    display: flex; align-items: center; gap: 10px;
    margin: 6px 0 14px; color: var(--text-muted); font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase;
}
.section-divider::before, .section-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

/* ─── FOOTER ──────────────────────────────────────── */
.footer {
    text-align: center; padding: 20px; position: relative; z-index: 5;
    font-family: var(--font-mono); font-size: 10px; color: var(--text-muted);
    letter-spacing: 1px; text-transform: uppercase;
}
.footer span { color: var(--cyan); opacity: .5; }

/* ─── UTIL ────────────────────────────────────────── */
.pulse { animation: pulse 1.5s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.35} }

/* ─── HIDDEN ──────────────────────────────────────── */
[data-hidden] { display: none !important; }
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-logo">
        <div class="logo-orb"><i class="bi bi-wifi"></i></div>
        <div>
            <div class="header-title">NFC ABSENSI</div>
            <div class="header-sub">Web NFC System</div>
        </div>
    </div>
    <div class="header-time">
        <div id="clock">--:--:--</div>
        <div class="header-date" id="dateStr">---</div>
    </div>
</div>

<!-- TABS -->
<div class="tabs-wrap">
    <div class="tabs">
        <div class="tab-slider" id="tabSlider"></div>
        <button class="tab active" id="tabAbsensi" onclick="switchMode('absensi')">
            <i class="bi bi-person-badge"></i> Absensi
        </button>
        <button class="tab" id="tabDaftar" onclick="switchMode('daftar')">
            <i class="bi bi-credit-card-2-front"></i> Daftarkan Kartu
        </button>
    </div>
</div>

<!-- ═══════ PANEL ABSENSI ═══════ -->
<div class="panel" id="panelAbsensi">

    <!-- Mata Kuliah -->
    <div class="g-card">
        <div class="g-label">Mata Kuliah</div>
        <input type="text" id="mataKuliah" class="g-input"
            placeholder="Workshop on Web Software Development" list="mkList" autocomplete="off">
        <datalist id="mkList">
            <option value="Workshop on Web Software Development">
            <option value="Pemrograman Web">
            <option value="Basis Data">
            <option value="Algoritma &amp; Pemrograman">
            <option value="Jaringan Komputer">
            <option value="Sistem Operasi">
        </datalist>
    </div>

    <!-- Scanner Zone -->
    <div class="g-card">
        <div class="nfc-zone" id="nfcZone">
            <div class="nfc-orb-wrap" id="nfcOrbWrap">
                <div class="nfc-ring nfc-ring-1"></div>
                <div class="nfc-ring nfc-ring-2"></div>
                <div class="nfc-ring nfc-ring-3"></div>
                <div class="nfc-ring nfc-ring-4"></div>
                <div class="nfc-orb">
                    <i class="bi bi-wifi" id="nfcOrbIcon"></i>
                </div>
            </div>
            <div class="nfc-status" id="nfcStatusText"></div>
        </div>

        <button id="btnActivate" class="btn-nfc btn-activate" onclick="activateNfc('absensi')">
            <i class="bi bi-broadcast"></i> AKTIFKAN NFC
        </button>
        <button id="btnStop" class="btn-nfc btn-stop mt-3" data-hidden onclick="stopNfc()">
            <i class="bi bi-stop-circle"></i> HENTIKAN
        </button>
        <button id="btnScanLagi" class="btn-nfc btn-activate mt-3" data-hidden onclick="scanLagi()">
            <i class="bi bi-arrow-counterclockwise"></i> SCAN LAGI
        </button>

        <div id="resultAbsensi"></div>
    </div>

</div>

<!-- ═══════ PANEL DAFTAR KARTU ═══════ -->
<div class="panel" id="panelDaftar" data-hidden>

    <!-- Pilih Mahasiswa -->
    <div class="g-card">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
            <div class="g-label" style="margin:0;">Pilih Mahasiswa</div>
            <button class="btn-sm-ghost" onclick="loadMahasiswas()">
                <i class="bi bi-arrow-clockwise"></i> Muat Ulang
            </button>
        </div>

        <input type="text" id="searchMhsInput" class="g-input"
            placeholder="Ketik nama atau NIM…" autocomplete="off"
            oninput="filterMhs(this.value)">

        <div id="mhsList" class="mhs-list" data-hidden></div>
        <input type="hidden" id="selectedMhsId">

        <div id="selectedMhsInfo" class="selected-box" data-hidden>
            <div class="selected-info">
                <div class="mhs-avatar" id="selectedAvatar">?</div>
                <div>
                    <div class="selected-name" id="selectedMhsText">–</div>
                    <div class="selected-nim" id="selectedMhsNim">–</div>
                </div>
            </div>
            <button class="btn-sm-ghost" onclick="clearMhsSelection()">
                <i class="bi bi-x-circle"></i>
            </button>
        </div>

        <div id="mhsEmpty" class="empty-state" data-hidden>
            <i class="bi bi-person-slash"></i>
            Belum ada mahasiswa terdaftar.<br>
            <span style="font-size:11px; margin-top:4px; display:block; color:#3a5070;">
                Tambah lewat Admin → Data Mahasiswa
            </span>
        </div>
    </div>

    <!-- Scan Kartu -->
    <div class="g-card">
        <div class="section-divider">Tempel Kartu NFC</div>

        <button id="btnActivateDaftar" class="btn-nfc btn-activate" onclick="activateNfc('daftar')">
            <i class="bi bi-credit-card-2-front"></i> SCAN KARTU NFC
        </button>
        <button id="btnStopDaftar" class="btn-nfc btn-stop mt-3" data-hidden onclick="stopNfc()">
            <i class="bi bi-stop-circle"></i> HENTIKAN
        </button>

        <div id="serialDisplay" data-hidden>
            <div class="serial-box">
                <div class="serial-label">Serial Number</div>
                <div id="serialVal">–</div>
            </div>
        </div>

        <button id="btnRegister" class="btn-nfc btn-green mt-3" data-hidden onclick="registerKartu()">
            <i class="bi bi-check2-circle"></i> DAFTARKAN KARTU INI
        </button>

        <div id="resultDaftar"></div>
    </div>

</div>

<div class="footer">Web NFC API &nbsp;·&nbsp; <span>Android Chrome ≥ 89</span></div>

<script>
// ═══════════════════════════════════════════════
// CLOCK
// ═══════════════════════════════════════════════
function updateClock() {
    const now = new Date();
    document.getElementById('clock').textContent =
        now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
    document.getElementById('dateStr').textContent =
        now.toLocaleDateString('id-ID', {weekday:'short',day:'numeric',month:'short',year:'numeric'});
}
updateClock(); setInterval(updateClock, 1000);

// ═══════════════════════════════════════════════
// BEEP
// ═══════════════════════════════════════════════
function playBeep(ok = true) {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        if (ok) {
            [880, 1100].forEach((freq, i) => {
                const o = ctx.createOscillator(), g = ctx.createGain();
                o.connect(g); g.connect(ctx.destination);
                o.type = 'sine'; o.frequency.value = freq;
                g.gain.setValueAtTime(0.18, ctx.currentTime + i*0.07);
                g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i*0.07 + 0.12);
                o.start(ctx.currentTime + i*0.07);
                o.stop(ctx.currentTime + i*0.07 + 0.12);
            });
        } else {
            const o = ctx.createOscillator(), g = ctx.createGain();
            o.connect(g); g.connect(ctx.destination);
            o.type = 'sawtooth'; o.frequency.value = 200;
            g.gain.setValueAtTime(0.2, ctx.currentTime);
            g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);
            o.start(); o.stop(ctx.currentTime + 0.25);
        }
    } catch(e) {}
}

// ═══════════════════════════════════════════════
// HELPERS
// ═══════════════════════════════════════════════
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const show = id => { const el=document.getElementById(id); if(el){el.removeAttribute('data-hidden');} };
const hide = id => { const el=document.getElementById(id); if(el){el.setAttribute('data-hidden','');} };

function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function initials(name) {
    return name.trim().split(/\s+/).slice(0,2).map(w=>w[0]).join('').toUpperCase();
}

// ═══════════════════════════════════════════════
// STATE
// ═══════════════════════════════════════════════
let nfcReader = null, nfcAbortCtrl = null;
let scannedSerial = null, currentMode = 'absensi';
let allMahasiswas = [];

// ═══════════════════════════════════════════════
// TABS
// ═══════════════════════════════════════════════
function switchMode(mode) {
    currentMode = mode;
    stopNfc();

    const slider = document.getElementById('tabSlider');
    document.getElementById('tabAbsensi').className = 'tab' + (mode==='absensi' ? ' active':'');
    document.getElementById('tabDaftar').className  = 'tab' + (mode==='daftar'  ? ' active':'');

    if (mode === 'absensi') {
        slider.classList.remove('right');
        show('panelAbsensi'); hide('panelDaftar');
    } else {
        slider.classList.add('right');
        hide('panelAbsensi'); show('panelDaftar');
    }
}

// ═══════════════════════════════════════════════
// NFC ORB STATE
// ═══════════════════════════════════════════════
function setOrbScanning(scanning) {
    const wrap = document.getElementById('nfcOrbWrap');
    const stat = document.getElementById('nfcStatusText');
    if (scanning) {
        wrap.classList.add('scanning');
        stat.textContent = '● MENUNGGU KARTU NFC…';
        stat.className = 'nfc-status visible blinking';
    } else {
        wrap.classList.remove('scanning');
        stat.textContent = '';
        stat.className = 'nfc-status';
    }
}

// ═══════════════════════════════════════════════
// NFC ACTIVATE / STOP
// ═══════════════════════════════════════════════
async function activateNfc(mode) {
    if (!('NDEFReader' in window)) {
        alert('Browser ini tidak mendukung Web NFC API.\nGunakan Android Chrome ≥ 89 via HTTPS.');
        return;
    }

    if (mode === 'absensi') {
        const mk = document.getElementById('mataKuliah').value.trim();
        if (!mk) {
            document.getElementById('mataKuliah').focus();
            document.getElementById('mataKuliah').style.borderColor = 'rgba(255,56,96,0.6)';
            setTimeout(() => document.getElementById('mataKuliah').style.borderColor = '', 1500);
            return;
        }
    }
    if (mode === 'daftar') {
        if (!document.getElementById('selectedMhsId').value) {
            alert('Pilih mahasiswa terlebih dahulu!');
            return;
        }
    }

    try {
        nfcReader = new NDEFReader();
        nfcAbortCtrl = new AbortController();

        if (mode === 'absensi') {
            hide('btnActivate'); show('btnStop');
            hide('resultAbsensi');
            document.getElementById('resultAbsensi').innerHTML = '';
            setOrbScanning(true);
        } else {
            hide('btnActivateDaftar'); show('btnStopDaftar');
            hide('serialDisplay'); hide('btnRegister');
            document.getElementById('resultDaftar').innerHTML = '';
        }

        await nfcReader.scan({ signal: nfcAbortCtrl.signal });

        nfcReader.addEventListener('reading', ({ serialNumber }) => {
            stopNfc(false);
            if (mode === 'absensi') handleAbsensiScan(serialNumber);
            else                    handleDaftarScan(serialNumber);
        });

        nfcReader.addEventListener('readingerror', () => {
            showResultError(mode, 'Gagal membaca kartu. Coba lagi.');
            stopNfc(false);
        });

    } catch(err) {
        stopNfc(false);
        let msg = err.message;
        if (err.name === 'NotAllowedError') msg = 'Izin NFC ditolak. Izinkan akses NFC.';
        if (err.name === 'NotSupportedError') msg = 'NFC tidak aktif. Nyalakan NFC di Settings.';
        showResultError(mode, msg);
    }
}

function stopNfc(resetUI = true) {
    if (nfcAbortCtrl) { nfcAbortCtrl.abort(); nfcAbortCtrl = null; }
    nfcReader = null;
    setOrbScanning(false);
    if (resetUI) {
        show('btnActivate'); hide('btnStop'); hide('btnScanLagi');
        show('btnActivateDaftar'); hide('btnStopDaftar');
    }
}

function scanLagi() {
    document.getElementById('resultAbsensi').innerHTML = '';
    hide('btnScanLagi');
    show('btnActivate');
    setOrbScanning(false);
}

// ═══════════════════════════════════════════════
// ABSENSI SCAN
// ═══════════════════════════════════════════════
function handleAbsensiScan(serial) {
    setOrbScanning(false);
    show('btnScanLagi');

    const mk = document.getElementById('mataKuliah').value.trim();

    fetch('/nfc/scan', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest' },
        body: JSON.stringify({ serial_number: serial, mata_kuliah: mk })
    })
    .then(r => r.json())
    .then(res => {
        const el = document.getElementById('resultAbsensi');
        if (res.status === 'success') {
            playBeep(true);
            const d = res.data;
            const badge = d.status === 'hadir'
                ? `<span class="badge-hadir">HADIR</span>`
                : `<span class="badge-terlambat">TERLAMBAT</span>`;
            el.innerHTML = `
                <div class="result-wrap">
                    <div class="result-card r-success">
                        <div class="r-icon">✅</div>
                        <div class="r-name">${escHtml(d.nama)}</div>
                        <div class="r-nim">${escHtml(d.nim)}${d.prodi ? ' · '+escHtml(d.prodi):''}</div>
                        <div class="r-meta">${badge}<div class="r-time">${d.waktu_scan}</div></div>
                    </div>
                </div>`;
        } else if (res.status === 'duplicate') {
            playBeep(false);
            const d = res.data;
            el.innerHTML = `
                <div class="result-wrap">
                    <div class="result-card r-duplicate">
                        <div class="r-icon">ℹ️</div>
                        <div class="r-name">${escHtml(d.nama)}</div>
                        <div class="r-nim">${escHtml(d.nim)}</div>
                        <div class="r-meta"><div class="r-time">Sudah absen pukul ${d.waktu_scan}</div></div>
                    </div>
                </div>`;
        } else if (res.status === 'not_found') {
            playBeep(false);
            el.innerHTML = `
                <div class="result-wrap">
                    <div class="result-card r-notfound">
                        <div class="r-icon">❓</div>
                        <div class="r-name">Kartu Tidak Terdaftar</div>
                        <div class="r-nim">${escHtml(serial)}</div>
                        <div class="r-meta"><div class="r-time">Daftarkan di tab "Daftarkan Kartu"</div></div>
                    </div>
                </div>`;
        } else {
            showResultError('absensi', res.message || 'Terjadi kesalahan');
        }
    })
    .catch(() => showResultError('absensi', 'Gagal menghubungi server'));
}

// ═══════════════════════════════════════════════
// DAFTAR KARTU
// ═══════════════════════════════════════════════
function handleDaftarScan(serial) {
    playBeep(true);
    scannedSerial = serial;
    document.getElementById('serialVal').textContent = serial;
    show('serialDisplay');
    show('btnRegister');
    show('btnActivateDaftar');
    hide('btnStopDaftar');
}

function registerKartu() {
    const mhsId = document.getElementById('selectedMhsId').value;
    if (!mhsId || !scannedSerial) return;

    fetch('/nfc/register-kartu', {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest' },
        body: JSON.stringify({ serial_number: scannedSerial, mahasiswa_id: mhsId })
    })
    .then(r => r.json())
    .then(res => {
        const el = document.getElementById('resultDaftar');
        if (res.status === 'success') {
            playBeep(true);
            const d = res.data;
            el.innerHTML = `
                <div class="result-wrap">
                    <div class="result-card r-success">
                        <div class="r-icon">✅</div>
                        <div class="r-name">${escHtml(d.nama)}</div>
                        <div class="r-nim">${escHtml(d.nim)}</div>
                        <div class="r-meta"><div class="r-time">Kartu berhasil didaftarkan!</div></div>
                    </div>
                </div>`;
            hide('serialDisplay');
            hide('btnRegister');
            scannedSerial = null;
        } else {
            playBeep(false);
            el.innerHTML = `
                <div class="result-wrap">
                    <div class="result-card r-error">
                        <div class="r-icon">❌</div>
                        <div class="r-name">${escHtml(res.message)}</div>
                    </div>
                </div>`;
        }
    })
    .catch(() => showResultError('daftar', 'Gagal menghubungi server'));
}

function showResultError(mode, msg) {
    playBeep(false);
    const id = mode === 'absensi' ? 'resultAbsensi' : 'resultDaftar';
    document.getElementById(id).innerHTML = `
        <div class="result-wrap">
            <div class="result-card r-error">
                <div class="r-icon">❌</div>
                <div class="r-name">Error</div>
                <div class="r-nim">${escHtml(msg)}</div>
            </div>
        </div>`;
}

// ═══════════════════════════════════════════════
// MAHASISWA LIST
// ═══════════════════════════════════════════════
function loadMahasiswas() {
    const listEl  = document.getElementById('mhsList');
    const emptyEl = document.getElementById('mhsEmpty');

    listEl.innerHTML = `<div class="mhs-item" style="justify-content:center;color:var(--text-muted);">
        <i class="bi bi-arrow-repeat pulse"></i>&nbsp; Memuat...</div>`;
    show('mhsList');
    hide('mhsEmpty');

    fetch('/nfc/get-mahasiswas', { headers: { 'X-Requested-With':'XMLHttpRequest' } })
    .then(r => r.json())
    .then(data => { allMahasiswas = data; filterMhs(''); })
    .catch(() => {
        listEl.innerHTML = `<div class="mhs-item" style="justify-content:center;color:var(--red);">
            <i class="bi bi-exclamation-circle me-2"></i> Gagal memuat</div>`;
    });
}

function filterMhs(q) {
    const listEl  = document.getElementById('mhsList');
    const emptyEl = document.getElementById('mhsEmpty');
    if (document.getElementById('selectedMhsId').value) return;

    if (allMahasiswas.length === 0) {
        hide('mhsList'); show('mhsEmpty'); return;
    }

    const filtered = q
        ? allMahasiswas.filter(m =>
            m.nama.toLowerCase().includes(q.toLowerCase()) ||
            m.nim.toLowerCase().includes(q.toLowerCase()))
        : allMahasiswas;

    hide('mhsEmpty');
    show('mhsList');

    if (filtered.length === 0) {
        listEl.innerHTML = `<div class="mhs-item" style="justify-content:center;color:var(--text-muted);">
            Tidak ditemukan</div>`;
        return;
    }

    listEl.innerHTML = filtered.map(m => `
        <div class="mhs-item" onclick="selectMhs(${m.id},'${m.nim}','${escHtml(m.nama)}','${escHtml(m.prodi||'')}')">
            <div class="mhs-avatar">${initials(m.nama)}</div>
            <div>
                <div class="mhs-name">${escHtml(m.nama)}</div>
                <div class="mhs-nim">${m.nim}${m.prodi ? ' · '+escHtml(m.prodi):''}</div>
            </div>
        </div>`).join('');
}

function selectMhs(id, nim, nama, prodi) {
    document.getElementById('selectedMhsId').value = id;
    document.getElementById('searchMhsInput').value = '';
    document.getElementById('selectedMhsText').textContent = nama;
    document.getElementById('selectedMhsNim').textContent = nim + (prodi ? ' · '+prodi : '');
    document.getElementById('selectedAvatar').textContent = initials(nama);
    hide('mhsList');
    show('selectedMhsInfo');
    document.getElementById('searchMhsInput').placeholder = 'Cari mahasiswa lain…';
}

function clearMhsSelection() {
    document.getElementById('selectedMhsId').value = '';
    document.getElementById('searchMhsInput').value = '';
    document.getElementById('searchMhsInput').placeholder = 'Ketik nama atau NIM…';
    hide('selectedMhsInfo');
    filterMhs('');
}

document.getElementById('searchMhsInput').addEventListener('focus', () => {
    if (!document.getElementById('selectedMhsId').value) filterMhs('');
});

loadMahasiswas();
</script>
</body>
</html>
