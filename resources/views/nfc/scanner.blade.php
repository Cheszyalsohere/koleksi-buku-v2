<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>NFC Absensi</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        * { box-sizing: border-box; }
        body { background: #0f172a; color: #f1f5f9; font-family: 'Segoe UI', sans-serif; min-height: 100vh; }

        .navbar-nfc { background: linear-gradient(135deg, #1e3a5f, #2563eb); padding: 14px 20px; }
        .navbar-nfc h6 { margin: 0; font-weight: 700; font-size: 16px; }
        .navbar-nfc small { color: #93c5fd; font-size: 12px; }

        .mode-tabs { display: flex; background: #1e293b; border-radius: 12px; padding: 4px; margin: 16px; }
        .mode-tab {
            flex: 1; padding: 10px; text-align: center; border-radius: 8px;
            font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s;
            border: none; background: transparent; color: #64748b;
        }
        .mode-tab.active { background: #2563eb; color: #fff; }

        .card-nfc {
            background: #1e293b; border-radius: 16px; margin: 0 16px 16px;
            padding: 20px; border: 1px solid #334155;
        }
        .card-nfc label { font-size: 13px; color: #94a3b8; margin-bottom: 6px; display: block; }

        .nfc-btn {
            width: 100%; padding: 16px; border-radius: 14px; border: none;
            font-size: 16px; font-weight: 700; cursor: pointer; transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .nfc-btn-activate { background: #2563eb; color: #fff; }
        .nfc-btn-activate:hover { background: #1d4ed8; }
        .nfc-btn-activate:disabled { background: #475569; cursor: not-allowed; }
        .nfc-btn-stop { background: #dc2626; color: #fff; }

        .status-bar {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 16px; border-radius: 10px; margin-top: 12px; font-size: 14px;
        }
        .status-waiting  { background: #1e3a5f; color: #93c5fd; }
        .status-scanning { background: #1e3a5f; color: #60a5fa; }

        .pulse { animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        /* Result cards */
        .result-card {
            border-radius: 14px; padding: 20px; margin-top: 12px; text-align: center;
        }
        .result-success  { background: #14532d; border: 1px solid #16a34a; }
        .result-duplicate{ background: #1e3a5f; border: 1px solid #3b82f6; }
        .result-error    { background: #450a0a; border: 1px solid #dc2626; }
        .result-notfound { background: #431407; border: 1px solid #ea580c; }

        .result-icon { font-size: 48px; margin-bottom: 8px; }
        .result-name { font-size: 22px; font-weight: 700; color: #fff; }
        .result-nim  { font-size: 14px; color: #94a3b8; margin-top: 2px; }
        .result-time { font-size: 13px; margin-top: 8px; color: #cbd5e1; }

        .badge-hadir     { background: #16a34a; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .badge-terlambat { background: #d97706; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }

        .form-control-dark {
            background: #0f172a; border: 1px solid #334155; color: #f1f5f9;
            border-radius: 10px; padding: 12px 14px; font-size: 15px; width: 100%;
        }
        .form-control-dark:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px #2563eb33; }
        .form-control-dark option { background: #1e293b; }

        .mhs-list {
            background: #0f172a; border: 1px solid #334155; border-radius: 10px;
            max-height: 220px; overflow-y: auto; margin-top: 8px;
        }
        .mhs-option {
            padding: 12px 14px; cursor: pointer; font-size: 14px;
            border-bottom: 1px solid #1e293b; display: flex; align-items: center; gap: 10px;
        }
        .mhs-option:last-child { border-bottom: none; }
        .mhs-option:active { background: #2563eb33; }
        .mhs-option.selected { background: #1e3a5f; }

        .serial-display {
            background: #0f172a; border: 1px dashed #3b82f6; border-radius: 10px;
            padding: 12px 16px; font-family: monospace; font-size: 14px; color: #60a5fa;
            margin-top: 12px; text-align: center; display: none;
        }

        .nfc-wave {
            width: 100px; height: 100px; border-radius: 50%;
            background: #2563eb22; border: 3px solid #2563eb;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; position: relative;
        }
        .nfc-wave::before, .nfc-wave::after {
            content: ''; position: absolute; border-radius: 50%;
            border: 2px solid #2563eb; animation: wave 2s infinite;
        }
        .nfc-wave::before { width: 130px; height: 130px; animation-delay: 0s; }
        .nfc-wave::after  { width: 160px; height: 160px; animation-delay: .5s; }
        @keyframes wave { 0%{opacity:1;transform:scale(.8)} 100%{opacity:0;transform:scale(1.2)} }
        .nfc-wave i { font-size: 36px; color: #2563eb; z-index: 1; }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <div class="navbar-nfc">
        <h6><i class="bi bi-wifi me-2"></i>NFC Absensi</h6>
        <small>Sistem Absensi Berbasis NFC</small>
    </div>

    {{-- Mode Tabs --}}
    <div class="mode-tabs">
        <button class="mode-tab active" id="tabAbsensi" onclick="switchMode('absensi')">
            <i class="bi bi-person-check me-1"></i> Absensi
        </button>
        <button class="mode-tab" id="tabDaftar" onclick="switchMode('daftar')">
            <i class="bi bi-credit-card me-1"></i> Daftarkan Kartu
        </button>
    </div>

    {{-- ===== MODE ABSENSI ===== --}}
    <div id="panelAbsensi">
        <div class="card-nfc">
            <label>Mata Kuliah</label>
            <input type="text" id="mataKuliah" class="form-control-dark"
                placeholder="contoh: Pemrograman Web" list="mkList" autocomplete="off">
            <datalist id="mkList">
                <option value="Workshop on Web Software Development">
                <option value="Pemrograman Web">
                <option value="Basis Data">
                <option value="Algoritma & Pemrograman">
                <option value="Jaringan Komputer">
                <option value="Sistem Operasi">
            </datalist>
        </div>

        <div class="card-nfc">
            {{-- Animasi gelombang NFC --}}
            <div id="nfcWave" style="display:none; padding: 20px 0;">
                <div class="nfc-wave"><i class="bi bi-wifi"></i></div>
                <p class="text-center" style="color:#60a5fa; font-size:14px;">Dekatkan kartu NFC ke HP...</p>
            </div>

            <button id="btnActivate" class="nfc-btn nfc-btn-activate" onclick="activateNfc('absensi')">
                <i class="bi bi-wifi"></i> Aktifkan NFC
            </button>
            <button id="btnStop" class="nfc-btn nfc-btn-stop mt-2" style="display:none;" onclick="stopNfc()">
                <i class="bi bi-stop-circle"></i> Stop NFC
            </button>
            <button id="btnScanLagi" class="nfc-btn nfc-btn-activate mt-2" style="display:none;" onclick="scanLagi()">
                <i class="bi bi-arrow-repeat"></i> Scan Lagi
            </button>

            {{-- Hasil absensi --}}
            <div id="resultAbsensi"></div>
        </div>
    </div>

    {{-- ===== MODE DAFTAR KARTU ===== --}}
    <div id="panelDaftar" style="display:none;">
        <div class="card-nfc">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="mb-0">Pilih Mahasiswa</label>
                <button onclick="loadMahasiswas()" style="background:transparent;border:none;color:#60a5fa;font-size:13px;padding:0;">
                    <i class="bi bi-arrow-clockwise"></i> Muat Ulang
                </button>
            </div>

            {{-- Search input --}}
            <input type="text" id="searchMhsInput" class="form-control-dark"
                placeholder="Ketik nama atau NIM untuk cari..." autocomplete="off"
                oninput="filterMhs(this.value)">

            {{-- Daftar mahasiswa --}}
            <div id="mhsList" class="mhs-list" style="display:none;"></div>

            <input type="hidden" id="selectedMhsId">

            {{-- Mahasiswa terpilih --}}
            <div id="selectedMhsInfo" style="display:none; margin-top:10px; padding:12px; background:#0f172a; border-radius:8px; font-size:14px;">
                <span style="color:#60a5fa;"><i class="bi bi-person-check me-1"></i></span>
                <span id="selectedMhsText"></span>
                <button onclick="clearMhsSelection()" style="background:transparent;border:none;color:#64748b;font-size:12px;float:right;">✕ Ganti</button>
            </div>

            <div id="mhsEmpty" style="display:none; text-align:center; padding:16px; color:#64748b; font-size:13px;">
                <i class="bi bi-person-x" style="font-size:24px; display:block; margin-bottom:6px;"></i>
                Belum ada mahasiswa.<br>
                <span style="font-size:12px;">Tambahkan lewat <strong>Admin → Data Mahasiswa</strong></span>
            </div>
        </div>

        <div class="card-nfc">
            <button id="btnActivateDaftar" class="nfc-btn nfc-btn-activate" onclick="activateNfc('daftar')">
                <i class="bi bi-wifi"></i> Scan Kartu NFC
            </button>
            <button id="btnStopDaftar" class="nfc-btn nfc-btn-stop mt-2" style="display:none;" onclick="stopNfc()">
                <i class="bi bi-stop-circle"></i> Stop NFC
            </button>

            <div id="serialDisplay" class="serial-display"></div>

            <button id="btnRegister" class="nfc-btn mt-3" style="background:#16a34a;color:#fff;display:none;" onclick="registerKartu()">
                <i class="bi bi-check-circle"></i> Daftarkan Kartu Ini
            </button>

            <div id="resultDaftar"></div>
        </div>
    </div>

    {{-- Footer --}}
    <div style="text-align:center; padding:20px; color:#475569; font-size:12px;">
        Android Chrome ≥ 89 | Web NFC API
    </div>

<script>
// =============================================
// BEEP
// =============================================
function playBeep(ok = true) {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain); gain.connect(ctx.destination);
        osc.type = 'square';
        osc.frequency.setValueAtTime(ok ? 940 : 300, ctx.currentTime);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
        osc.start(); osc.stop(ctx.currentTime + 0.15);
    } catch(e) {}
}

// =============================================
// STATE
// =============================================
const csrf = document.querySelector('meta[name="csrf-token"]').content;
let nfcReader = null;
let nfcAbortCtrl = null;
let scannedSerial = null;
let currentMode = 'absensi';
let allMahasiswas = [];

// =============================================
// SWITCH MODE
// =============================================
function switchMode(mode) {
    currentMode = mode;
    stopNfc();
    document.getElementById('panelAbsensi').style.display = mode === 'absensi' ? '' : 'none';
    document.getElementById('panelDaftar').style.display  = mode === 'daftar'  ? '' : 'none';
    document.getElementById('tabAbsensi').className = 'mode-tab' + (mode === 'absensi' ? ' active' : '');
    document.getElementById('tabDaftar').className  = 'mode-tab' + (mode === 'daftar'  ? ' active' : '');
}

// =============================================
// NFC
// =============================================
async function activateNfc(mode) {
    if (!('NDEFReader' in window)) {
        alert('Browser ini tidak mendukung Web NFC API.\nGunakan Android Chrome ≥ 89.');
        return;
    }

    // Validasi mata kuliah untuk mode absensi
    if (mode === 'absensi') {
        const mk = document.getElementById('mataKuliah').value.trim();
        if (!mk) {
            alert('Isi mata kuliah terlebih dahulu!');
            document.getElementById('mataKuliah').focus();
            return;
        }
    }

    // Validasi mahasiswa untuk mode daftar
    if (mode === 'daftar') {
        if (!document.getElementById('selectedMhsId').value) {
            alert('Pilih mahasiswa terlebih dahulu!');
            return;
        }
    }

    try {
        nfcReader = new NDEFReader();
        nfcAbortCtrl = new AbortController();

        // Tampilkan animasi
        if (mode === 'absensi') {
            document.getElementById('btnActivate').style.display = 'none';
            document.getElementById('btnStop').style.display = '';
            document.getElementById('nfcWave').style.display = '';
            document.getElementById('resultAbsensi').innerHTML = '';
        } else {
            document.getElementById('btnActivateDaftar').style.display = 'none';
            document.getElementById('btnStopDaftar').style.display = '';
            document.getElementById('serialDisplay').style.display = 'none';
            document.getElementById('btnRegister').style.display = 'none';
            document.getElementById('resultDaftar').innerHTML = '';
        }

        await nfcReader.scan({ signal: nfcAbortCtrl.signal });

        nfcReader.addEventListener('reading', ({ serialNumber, message }) => {
            // Hentikan scanner
            stopNfc(false);

            if (mode === 'absensi') {
                handleAbsensiScan(serialNumber);
            } else {
                handleDaftarScan(serialNumber);
            }
        });

        nfcReader.addEventListener('readingerror', () => {
            showError(mode, 'Gagal membaca kartu. Coba lagi.');
            stopNfc(false);
        });

    } catch (err) {
        stopNfc(false);
        let msg = err.message;
        if (err.name === 'NotAllowedError') msg = 'Izin NFC ditolak. Tap tombol dan izinkan akses NFC.';
        if (err.name === 'NotSupportedError') msg = 'NFC tidak aktif. Hidupkan NFC di Settings HP.';
        showError(mode, msg);
    }
}

function stopNfc(resetUI = true) {
    if (nfcAbortCtrl) { nfcAbortCtrl.abort(); nfcAbortCtrl = null; }
    nfcReader = null;
    if (resetUI) {
        document.getElementById('nfcWave').style.display = 'none';
        document.getElementById('btnActivate').style.display = '';
        document.getElementById('btnStop').style.display = 'none';
        document.getElementById('btnScanLagi').style.display = 'none';
        document.getElementById('btnActivateDaftar').style.display = '';
        document.getElementById('btnStopDaftar').style.display = 'none';
    }
}

function scanLagi() {
    document.getElementById('resultAbsensi').innerHTML = '';
    document.getElementById('nfcWave').style.display = 'none';
    document.getElementById('btnScanLagi').style.display = 'none';
    document.getElementById('btnActivate').style.display = '';
}

// =============================================
// ABSENSI SCAN
// =============================================
function handleAbsensiScan(serial) {
    document.getElementById('nfcWave').style.display = 'none';
    document.getElementById('btnScanLagi').style.display = '';

    const mk = document.getElementById('mataKuliah').value.trim();

    fetch('/nfc/scan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ serial_number: serial, mata_kuliah: mk })
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            playBeep(true);
            const d = res.data;
            const badgeCls = d.status === 'hadir' ? 'badge-hadir' : 'badge-terlambat';
            document.getElementById('resultAbsensi').innerHTML = `
                <div class="result-card result-success">
                    <div class="result-icon">✅</div>
                    <div class="result-name">${d.nama}</div>
                    <div class="result-nim">${d.nim}${d.prodi ? ' · ' + d.prodi : ''}</div>
                    <div class="result-time mt-2">
                        <span class="${badgeCls}">${d.status.toUpperCase()}</span>
                    </div>
                    <div class="result-time">Waktu: ${d.waktu_scan}</div>
                </div>`;
        } else if (res.status === 'duplicate') {
            playBeep(false);
            const d = res.data;
            document.getElementById('resultAbsensi').innerHTML = `
                <div class="result-card result-duplicate">
                    <div class="result-icon">ℹ️</div>
                    <div class="result-name">${d.nama}</div>
                    <div class="result-nim">${d.nim}</div>
                    <div class="result-time">Sudah absen pukul ${d.waktu_scan}</div>
                </div>`;
        } else if (res.status === 'not_found') {
            playBeep(false);
            document.getElementById('resultAbsensi').innerHTML = `
                <div class="result-card result-notfound">
                    <div class="result-icon">❓</div>
                    <div class="result-name">Kartu Tidak Terdaftar</div>
                    <div class="result-nim" style="font-family:monospace;">${serial}</div>
                    <div class="result-time">Daftarkan kartu ini di tab "Daftarkan Kartu"</div>
                </div>`;
        } else {
            playBeep(false);
            showError('absensi', res.message || 'Terjadi kesalahan');
        }
    })
    .catch(() => { playBeep(false); showError('absensi', 'Gagal menghubungi server'); });
}

// =============================================
// DAFTAR KARTU SCAN
// =============================================
function handleDaftarScan(serial) {
    playBeep(true);
    scannedSerial = serial;
    document.getElementById('serialDisplay').textContent = '📡 Serial: ' + serial;
    document.getElementById('serialDisplay').style.display = '';
    document.getElementById('btnRegister').style.display = '';
    document.getElementById('btnActivateDaftar').style.display = '';
    document.getElementById('btnStopDaftar').style.display = 'none';
}

function registerKartu() {
    const mhsId = document.getElementById('selectedMhsId').value;
    if (!mhsId || !scannedSerial) return;

    fetch('/nfc/register-kartu', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ serial_number: scannedSerial, mahasiswa_id: mhsId })
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            playBeep(true);
            const d = res.data;
            document.getElementById('resultDaftar').innerHTML = `
                <div class="result-card result-success" style="margin-top:12px;">
                    <div class="result-icon">✅</div>
                    <div class="result-name">${d.nama}</div>
                    <div class="result-nim">${d.nim}</div>
                    <div class="result-time">Kartu berhasil didaftarkan!</div>
                </div>`;
            document.getElementById('serialDisplay').style.display = 'none';
            document.getElementById('btnRegister').style.display = 'none';
            scannedSerial = null;
        } else {
            playBeep(false);
            document.getElementById('resultDaftar').innerHTML = `
                <div class="result-card result-error" style="margin-top:12px;">
                    <div class="result-icon">❌</div>
                    <div class="result-name">${res.message}</div>
                </div>`;
        }
    })
    .catch(() => showError('daftar', 'Gagal menghubungi server'));
}

function showError(mode, msg) {
    playBeep(false);
    const el = mode === 'absensi' ? 'resultAbsensi' : 'resultDaftar';
    document.getElementById(el).innerHTML = `
        <div class="result-card result-error">
            <div class="result-icon">❌</div>
            <div class="result-name">Error</div>
            <div class="result-time">${msg}</div>
        </div>`;
}

// =============================================
// LOAD & SEARCH MAHASISWAS
// =============================================
function loadMahasiswas() {
    const listEl  = document.getElementById('mhsList');
    const emptyEl = document.getElementById('mhsEmpty');

    listEl.innerHTML = '<div class="mhs-option" style="color:#64748b; justify-content:center;"><i class="bi bi-arrow-repeat pulse me-2"></i>Memuat...</div>';
    listEl.style.display = '';
    emptyEl.style.display = 'none';

    fetch('/nfc/get-mahasiswas', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(data => {
        allMahasiswas = data;
        filterMhs('');  // render semua
    })
    .catch(() => {
        listEl.innerHTML = '<div class="mhs-option" style="color:#dc2626; justify-content:center;">Gagal memuat data</div>';
    });
}

function filterMhs(q) {
    const listEl  = document.getElementById('mhsList');
    const emptyEl = document.getElementById('mhsEmpty');
    const selectedId = document.getElementById('selectedMhsId').value;

    // Kalau sudah ada yg dipilih, jangan tampilkan list lagi
    if (selectedId) return;

    if (allMahasiswas.length === 0) {
        listEl.style.display = 'none';
        emptyEl.style.display = '';
        return;
    }

    const filtered = q
        ? allMahasiswas.filter(m =>
            m.nama.toLowerCase().includes(q.toLowerCase()) ||
            m.nim.toLowerCase().includes(q.toLowerCase()))
        : allMahasiswas;

    if (filtered.length === 0) {
        listEl.innerHTML = '<div class="mhs-option" style="color:#64748b; justify-content:center;">Tidak ditemukan</div>';
    } else {
        listEl.innerHTML = filtered.map(m => `
            <div class="mhs-option" onclick="selectMhs(${m.id}, '${m.nim}', '${escHtml(m.nama)}')">
                <i class="bi bi-person" style="color:#60a5fa; flex-shrink:0;"></i>
                <div>
                    <div style="font-weight:600; color:#f1f5f9;">${escHtml(m.nama)}</div>
                    <div style="font-size:12px; color:#64748b; font-family:monospace;">${m.nim}${m.prodi ? ' · ' + escHtml(m.prodi) : ''}</div>
                </div>
            </div>`).join('');
    }

    emptyEl.style.display = 'none';
    listEl.style.display = '';
}

function selectMhs(id, nim, nama) {
    document.getElementById('selectedMhsId').value = id;
    document.getElementById('searchMhsInput').value = '';
    document.getElementById('mhsList').style.display = 'none';
    document.getElementById('selectedMhsText').innerHTML =
        `<strong>${nama}</strong> <span style="color:#64748b; font-family:monospace;">(${nim})</span>`;
    document.getElementById('selectedMhsInfo').style.display = '';
    document.getElementById('searchMhsInput').placeholder = 'Mahasiswa dipilih ↑';
}

function clearMhsSelection() {
    document.getElementById('selectedMhsId').value = '';
    document.getElementById('searchMhsInput').value = '';
    document.getElementById('searchMhsInput').placeholder = 'Ketik nama atau NIM untuk cari...';
    document.getElementById('selectedMhsInfo').style.display = 'none';
    filterMhs('');
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Tampilkan list saat input di-focus
document.getElementById('searchMhsInput').addEventListener('focus', () => {
    if (!document.getElementById('selectedMhsId').value) filterMhs('');
});

// Load mahasiswas saat halaman dibuka
loadMahasiswas();
</script>
</body>
</html>
