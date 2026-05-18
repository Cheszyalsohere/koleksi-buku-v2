@extends('layouts.app')

@section('style-page')
<style>
    #reader {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
    }
    /* Override html5-qrcode default border */
    #reader > div:first-child { border: none !important; }

    .scan-overlay {
        position: relative;
        background: #0f172a;
        border-radius: 12px;
        padding: 16px;
    }
    .scan-overlay #reader video {
        border-radius: 8px;
    }
    #resultCard {
        transition: all .3s ease;
    }
    .badge-lunas    { background: #16a34a; }
    .badge-pending  { background: #d97706; }
    .badge-gagal    { background: #dc2626; }
    .scan-hint {
        font-size: 12px;
        color: #94a3b8;
        text-align: center;
        margin-top: 8px;
    }
    .scan-guide-box {
        border: 2px dashed #3b82f6;
        border-radius: 6px;
        padding: 12px 16px;
        background: #eff6ff;
        font-size: 13px;
        color: #1e40af;
    }
</style>
@endsection

@section('content')
<div class="container py-3">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="mdi mdi-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-bold">Barcode Scanner</h4>
            <small class="text-muted">Scan barcode dari kertas label untuk melihat detail barang</small>
        </div>
    </div>

    <div class="row g-4">

        {{-- ======== PANEL KIRI: KAMERA ======== --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-dark text-white fw-semibold">
                    <i class="mdi mdi-barcode-scan me-2"></i> Kamera Scanner
                </div>
                <div class="card-body">

                    {{-- Panduan --}}
                    <div class="scan-guide-box mb-3">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Arahkan kamera ke <strong>barcode CODE-128</strong> pada kertas label. Pastikan barcode terbaca horizontal di dalam kotak merah.
                    </div>

                    {{-- Area kamera --}}
                    <div class="scan-overlay mb-3">
                        <div id="reader"></div>
                        <p class="scan-hint mt-2">
                            <i class="mdi mdi-camera me-1"></i>
                            Izinkan akses kamera saat diminta browser
                        </p>
                    </div>

                    {{-- Pilih Kamera --}}
                    <div class="mb-3" id="cameraSelectWrap">
                        <label class="form-label small text-muted mb-1">
                            <i class="mdi mdi-camera-switch me-1"></i> Pilih Kamera
                        </label>
                        <select id="cameraSelect" class="form-select form-select-sm">
                            <option value="">— Memuat daftar kamera... —</option>
                        </select>
                        <div class="form-text">Pilih <strong>OBS Virtual Camera</strong> jika pakai OBS</div>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex gap-2 flex-wrap">
                        <button id="btnStart" class="btn btn-primary">
                            <i class="mdi mdi-play me-1"></i> Mulai Scan
                        </button>
                        <button id="btnStop" class="btn btn-danger d-none">
                            <i class="mdi mdi-stop me-1"></i> Stop
                        </button>
                        <button id="btnReset" class="btn btn-secondary d-none">
                            <i class="mdi mdi-refresh me-1"></i> Scan Lagi
                        </button>
                    </div>

                    {{-- Status scanner --}}
                    <div id="scanStatus" class="mt-3 text-muted small d-none">
                        <span class="spinner-grow spinner-grow-sm text-primary me-1"></span>
                        Menunggu barcode...
                    </div>

                </div>
            </div>
        </div>

        {{-- ======== PANEL KANAN: HASIL SCAN ======== --}}
        <div class="col-lg-6">

            {{-- State: menunggu --}}
            <div id="cardWaiting" class="card h-100 d-flex align-items-center justify-content-center text-center" style="min-height:280px;">
                <div class="card-body">
                    <i class="mdi mdi-barcode" style="font-size:64px; color:#cbd5e1;"></i>
                    <p class="text-muted mt-3 mb-0">Hasil scan akan tampil di sini</p>
                    <small class="text-muted">Klik "Mulai Scan" lalu arahkan ke barcode</small>
                </div>
            </div>

            {{-- State: loading --}}
            <div id="cardLoading" class="card h-100 d-flex align-items-center justify-content-center d-none" style="min-height:280px;">
                <div class="card-body text-center">
                    <div class="spinner-border text-primary mb-3"></div>
                    <p class="text-muted mb-0">Mencari data barang...</p>
                </div>
            </div>

            {{-- State: error --}}
            <div id="cardError" class="card h-100 d-none border-danger" style="min-height:280px;">
                <div class="card-header bg-danger text-white fw-semibold">
                    <i class="mdi mdi-alert-circle me-2"></i> Barang Tidak Ditemukan
                </div>
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <i class="mdi mdi-help-circle-outline" style="font-size:48px; color:#f87171;"></i>
                    <p class="mt-3 mb-1 fw-semibold" id="errMsg">-</p>
                    <small class="text-muted">Kode barcode: <span id="errCode" class="font-monospace fw-bold">-</span></small>
                </div>
            </div>

            {{-- State: sukses --}}
            <div id="cardResult" class="card h-100 d-none border-success" style="min-height:280px;">
                <div class="card-header text-white fw-semibold" style="background:#16a34a;">
                    <i class="mdi mdi-check-circle me-2"></i> Barang Ditemukan
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div style="width:80px;height:80px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                            <i class="mdi mdi-cube-outline" style="font-size:40px;color:#16a34a;"></i>
                        </div>
                        <h5 id="resNama" class="fw-bold mb-0">-</h5>
                    </div>
                    <hr>
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width:45%">ID Barang</td>
                            <td class="fw-semibold font-monospace" id="resId">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Barang</td>
                            <td class="fw-semibold" id="resNama2">-</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Harga</td>
                            <td class="fw-bold text-success fs-5" id="resHarga">-</td>
                        </tr>
                    </table>
                    <hr>
                    <div class="text-muted small text-center">
                        <i class="mdi mdi-barcode me-1"></i>
                        Kode: <span class="font-monospace fw-bold" id="resBarcodeRaw">-</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Riwayat Scan --}}
    <div class="card mt-4 d-none" id="historyCard">
        <div class="card-header fw-semibold bg-light">
            <i class="mdi mdi-history me-2"></i> Riwayat Scan Sesi Ini
            <button class="btn btn-sm btn-outline-danger float-end" id="btnClearHistory">Hapus</button>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm table-hover mb-0" id="historyTable">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>ID Barang</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody id="historyBody"></tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('script-page')
{{-- Html5-qrcode library --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
// ==========================================
// BEEP via Web Audio API (tanpa file audio)
// ==========================================
function playBeep(frequency = 940, duration = 120) {
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc  = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.type = 'square';
        osc.frequency.setValueAtTime(frequency, ctx.currentTime);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration / 1000);
        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + duration / 1000);
    } catch (e) { /* browser tidak support */ }
}

// ==========================================
// STATE
// ==========================================
let html5QrCode = null;
let scannerRunning = false;
let scanHistory = [];
let scanCount = 0;
let availableCameras = [];

// ==========================================
// LOAD KAMERA (termasuk OBS Virtual Camera)
// ==========================================
Html5Qrcode.getCameras().then(cameras => {
    availableCameras = cameras;
    const sel = document.getElementById('cameraSelect');
    sel.innerHTML = '';

    if (cameras.length === 0) {
        sel.innerHTML = '<option value="">Tidak ada kamera ditemukan</option>';
        return;
    }

    cameras.forEach((cam, i) => {
        const opt = document.createElement('option');
        opt.value = cam.id;
        opt.textContent = cam.label || ('Kamera ' + (i + 1));
        // Auto-pilih OBS Virtual Camera jika ada
        if (cam.label && cam.label.toLowerCase().includes('obs')) {
            opt.selected = true;
        }
        sel.appendChild(opt);
    });
}).catch(err => {
    document.getElementById('cameraSelect').innerHTML =
        '<option value="">Gagal memuat kamera: ' + err + '</option>';
});

// ==========================================
// SHOW / HIDE PANELS
// ==========================================
function showPanel(name) {
    ['cardWaiting','cardLoading','cardError','cardResult'].forEach(id => {
        document.getElementById(id).classList.add('d-none');
    });
    document.getElementById(name).classList.remove('d-none');
}

// ==========================================
// LOOKUP BARANG VIA AJAX
// ==========================================
function lookupBarang(scannedId) {
    showPanel('cardLoading');

    fetch('/barang/find/' + encodeURIComponent(scannedId), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            const d = res.data;

            // Isi result card
            document.getElementById('resId').textContent     = d.id_barang;
            document.getElementById('resNama').textContent   = d.nama;
            document.getElementById('resNama2').textContent  = d.nama;
            document.getElementById('resHarga').textContent  = d.harga_format;
            document.getElementById('resBarcodeRaw').textContent = scannedId;

            showPanel('cardResult');

            // Tambah ke riwayat
            addHistory(d, scannedId);

        } else {
            document.getElementById('errMsg').textContent  = res.message || 'Tidak ditemukan';
            document.getElementById('errCode').textContent = scannedId;
            showPanel('cardError');
        }
    })
    .catch(() => {
        document.getElementById('errMsg').textContent  = 'Gagal menghubungi server';
        document.getElementById('errCode').textContent = scannedId;
        showPanel('cardError');
    });
}

// ==========================================
// RIWAYAT SCAN
// ==========================================
function addHistory(data, raw) {
    scanCount++;
    const now = new Date().toLocaleTimeString('id-ID');
    scanHistory.unshift({ no: scanCount, id: data.id_barang, nama: data.nama, harga: data.harga_format, waktu: now });

    const card = document.getElementById('historyCard');
    const tbody = document.getElementById('historyBody');
    card.classList.remove('d-none');

    tbody.innerHTML = scanHistory.map(h =>
        `<tr>
            <td>${h.no}</td>
            <td class="font-monospace fw-bold">${h.id}</td>
            <td>${h.nama}</td>
            <td class="text-success fw-semibold">${h.harga}</td>
            <td class="text-muted">${h.waktu}</td>
        </tr>`
    ).join('');
}

// ==========================================
// START SCANNER
// ==========================================
function startScanner() {
    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    // Gunakan deviceId dari dropdown (support OBS Virtual Camera)
    const selectedCameraId = document.getElementById('cameraSelect').value;
    const cameraConstraint = selectedCameraId
        ? { deviceId: { exact: selectedCameraId } }
        : { facingMode: "environment" };

    const config = {
        fps: 10,
        // Lebar qrbox untuk barcode 1D (CODE-128)
        qrbox: { width: Math.min(Math.floor(document.getElementById('reader').clientWidth * 0.9), 380), height: 100 },
        aspectRatio: 1.7,
        formatsToSupport: [
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.EAN_8,
            Html5QrcodeSupportedFormats.QR_CODE,
        ],
    };

    html5QrCode.start(
        cameraConstraint,
        config,
        // ON SUCCESS
        function(decodedText) {
            // 1. BEEP
            playBeep();

            // 2. STOP SCANNER
            stopScanner(false); // false = tidak reset tombol belum

            // 3. Cari data barang
            lookupBarang(decodedText);

            // Tampilkan tombol "Scan Lagi"
            document.getElementById('btnStop').classList.add('d-none');
            document.getElementById('btnReset').classList.remove('d-none');
            document.getElementById('scanStatus').classList.add('d-none');
        },
        // ON FAILURE (setiap frame tidak terbaca - abaikan)
        function(_errorMsg) { /* silent */ }
    )
    .then(() => {
        scannerRunning = true;
        document.getElementById('btnStart').classList.add('d-none');
        document.getElementById('btnStop').classList.remove('d-none');
        document.getElementById('btnReset').classList.add('d-none');
        document.getElementById('scanStatus').classList.remove('d-none');
        showPanel('cardWaiting');
    })
    .catch(err => {
        alert('Gagal mengakses kamera: ' + err);
    });
}

// ==========================================
// STOP SCANNER
// ==========================================
function stopScanner(resetButtons = true) {
    if (html5QrCode && scannerRunning) {
        html5QrCode.stop().catch(() => {});
        scannerRunning = false;
    }
    if (resetButtons) {
        document.getElementById('btnStart').classList.remove('d-none');
        document.getElementById('btnStop').classList.add('d-none');
        document.getElementById('btnReset').classList.add('d-none');
        document.getElementById('scanStatus').classList.add('d-none');
    }
}

// ==========================================
// EVENT LISTENERS
// ==========================================
document.getElementById('btnStart').addEventListener('click', startScanner);
document.getElementById('btnStop').addEventListener('click', () => stopScanner(true));
document.getElementById('btnReset').addEventListener('click', () => {
    showPanel('cardWaiting');
    startScanner();
});
document.getElementById('btnClearHistory').addEventListener('click', () => {
    scanHistory = [];
    scanCount = 0;
    document.getElementById('historyBody').innerHTML = '';
    document.getElementById('historyCard').classList.add('d-none');
});
</script>
@endsection
