@extends('layouts.vendor')

@section('title', 'Scan QR Code Customer')

@section('style-page')
<style>
    #reader {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
    }
    #reader > div:first-child { border: none !important; }

    .scan-overlay {
        background: #0f172a;
        border-radius: 12px;
        padding: 16px;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        letter-spacing: .5px;
    }
    .status-lunas   { background: #dcfce7; color: #16a34a; }
    .status-pending { background: #fef3c7; color: #d97706; }
    .status-gagal   { background: #fee2e2; color: #dc2626; }
    .scan-guide-box {
        border: 2px dashed #3b82f6;
        border-radius: 8px;
        padding: 12px 16px;
        background: #eff6ff;
        font-size: 13px;
        color: #1e40af;
    }
    .menu-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .menu-item-row:last-child { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="d-flex align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Scan QR Code Customer</h4>
        <small class="text-muted">Minta customer menunjukkan QR Code pesanan mereka</small>
    </div>
</div>

<div class="row g-4">

    {{-- ======== PANEL KIRI: KAMERA ======== --}}
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-dark text-white fw-semibold">
                <i class="bi bi-qr-code-scan me-2"></i> Scanner QR Code
            </div>
            <div class="card-body">

                <div class="scan-guide-box mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Minta customer membuka halaman <strong>Payment Success</strong> dan tunjukkan QR Code ke kamera.
                </div>

                <div class="scan-overlay mb-3">
                    <div id="reader"></div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button id="btnStart" class="btn btn-primary">
                        <i class="bi bi-play-fill me-1"></i> Mulai Scan
                    </button>
                    <button id="btnStop" class="btn btn-danger d-none">
                        <i class="bi bi-stop-fill me-1"></i> Stop
                    </button>
                    <button id="btnReset" class="btn btn-secondary d-none">
                        <i class="bi bi-arrow-repeat me-1"></i> Scan Lagi
                    </button>
                </div>

                <div id="scanStatus" class="mt-3 text-muted small d-none">
                    <span class="spinner-grow spinner-grow-sm text-primary me-1"></span>
                    Menunggu QR Code customer...
                </div>

            </div>
        </div>
    </div>

    {{-- ======== PANEL KANAN: HASIL ======== --}}
    <div class="col-lg-7">

        {{-- State: menunggu --}}
        <div id="cardWaiting" class="card h-100 d-flex align-items-center justify-content-center text-center" style="min-height:320px;">
            <div class="card-body">
                <i class="bi bi-qr-code" style="font-size:72px; color:#cbd5e1;"></i>
                <p class="text-muted mt-3 mb-1">Hasil scan akan tampil di sini</p>
                <small class="text-muted">Klik "Mulai Scan" lalu arahkan ke QR Code customer</small>
            </div>
        </div>

        {{-- State: loading --}}
        <div id="cardLoading" class="card h-100 d-flex align-items-center justify-content-center d-none" style="min-height:320px;">
            <div class="card-body text-center">
                <div class="spinner-border text-primary mb-3"></div>
                <p class="text-muted mb-0">Memuat detail pesanan...</p>
            </div>
        </div>

        {{-- State: error --}}
        <div id="cardError" class="card h-100 d-none" style="min-height:320px;">
            <div class="card-header fw-semibold text-white bg-danger">
                <i class="bi bi-x-circle me-2"></i> QR Code Tidak Valid
            </div>
            <div class="card-body d-flex flex-column justify-content-center text-center">
                <i class="bi bi-exclamation-triangle" style="font-size:52px; color:#f87171;"></i>
                <p class="mt-3 mb-1 fw-semibold" id="errMsg">-</p>
                <small class="text-muted">Kode QR: <span class="font-monospace" id="errQr">-</span></small>
            </div>
        </div>

        {{-- State: sukses --}}
        <div id="cardResult" class="card d-none">
            {{-- Header --}}
            <div class="card-header text-white fw-semibold" style="background:#1e3a5f;">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-receipt me-2"></i> Detail Pesanan</span>
                    <span id="resBadge" class="status-badge">-</span>
                </div>
            </div>
            <div class="card-body">

                {{-- Info pesanan --}}
                <table class="table table-borderless table-sm mb-3">
                    <tr>
                        <td class="text-muted" style="width:40%">Kode Pesanan</td>
                        <td class="fw-bold font-monospace" id="resKode">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Pemesan</td>
                        <td class="fw-semibold" id="resGuest">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Waktu Bayar</td>
                        <td class="fw-semibold" id="resPaidAt">-</td>
                    </tr>
                </table>

                <hr>

                {{-- Daftar menu --}}
                <h6 class="fw-bold mb-2">Menu yang Dipesan:</h6>
                <div id="resMenuList"></div>

                <hr>

                {{-- Total --}}
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Total</span>
                    <span class="fw-bold text-success fs-5" id="resTotal">-</span>
                </div>

            </div>
        </div>

    </div>
</div>

{{-- Riwayat Scan --}}
<div class="card mt-4 d-none" id="historyCard">
    <div class="card-header fw-semibold bg-light">
        <i class="bi bi-clock-history me-2"></i> Riwayat Scan Sesi Ini
        <button class="btn btn-sm btn-outline-danger float-end" id="btnClearHistory">Hapus</button>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Kode Pesanan</th>
                    <th>Pemesan</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Waktu Scan</th>
                </tr>
            </thead>
            <tbody id="historyBody"></tbody>
        </table>
    </div>
</div>

@endsection

@section('script-page')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
// ==========================================
// BEEP
// ==========================================
function playBeep(frequency = 940, duration = 120) {
    try {
        const ctx  = new (window.AudioContext || window.webkitAudioContext)();
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
    } catch (e) {}
}

// ==========================================
// STATE
// ==========================================
let html5QrCode = null;
let scannerRunning = false;
let scanHistory = [];
let scanCount = 0;
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ==========================================
// SHOW PANEL
// ==========================================
function showPanel(name) {
    ['cardWaiting','cardLoading','cardError','cardResult'].forEach(id => {
        document.getElementById(id).classList.add('d-none');
    });
    document.getElementById(name).classList.remove('d-none');
}

// ==========================================
// LOOKUP PESANAN
// ==========================================
function lookupPesanan(qrText) {
    showPanel('cardLoading');

    fetch('/vendor/scan-qr/find', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ qr_text: qrText }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            const d = res.data;

            // Status badge
            let badgeClass = 'status-pending', badgeText = 'MENUNGGU BAYAR';
            if (d.status_bayar === 'lunas') { badgeClass = 'status-lunas'; badgeText = 'LUNAS'; }
            else if (d.status_bayar === 'gagal') { badgeClass = 'status-gagal'; badgeText = 'GAGAL'; }

            document.getElementById('resBadge').className = 'status-badge ' + badgeClass;
            document.getElementById('resBadge').textContent = badgeText;
            document.getElementById('resKode').textContent   = d.kode_pesanan;
            document.getElementById('resGuest').textContent  = d.guest_name;
            document.getElementById('resPaidAt').textContent = d.paid_at || '-';
            document.getElementById('resTotal').textContent  = d.total_format;

            // Menu items
            const menuHtml = d.details.map(item =>
                `<div class="menu-item-row">
                    <div>
                        <span class="fw-semibold">${item.nama_menu}</span>
                        <br><small class="text-muted">${item.harga_format} × ${item.jumlah}</small>
                    </div>
                    <span class="fw-bold">${item.subtotal_format}</span>
                </div>`
            ).join('');
            document.getElementById('resMenuList').innerHTML = menuHtml || '<p class="text-muted">Tidak ada item</p>';

            showPanel('cardResult');

            // Riwayat
            addHistory(d);

        } else {
            document.getElementById('errMsg').textContent = res.message || 'Tidak ditemukan';
            document.getElementById('errQr').textContent  = qrText;
            showPanel('cardError');
        }
    })
    .catch(() => {
        document.getElementById('errMsg').textContent = 'Gagal menghubungi server';
        document.getElementById('errQr').textContent  = qrText;
        showPanel('cardError');
    });
}

// ==========================================
// RIWAYAT
// ==========================================
function addHistory(data) {
    scanCount++;
    const now = new Date().toLocaleTimeString('id-ID');
    scanHistory.unshift({
        no:    scanCount,
        kode:  data.kode_pesanan,
        guest: data.guest_name,
        status:data.status_bayar,
        total: data.total_format,
        waktu: now,
    });

    document.getElementById('historyCard').classList.remove('d-none');
    document.getElementById('historyBody').innerHTML = scanHistory.map(h => {
        const cls = h.status === 'lunas' ? 'success' : (h.status === 'gagal' ? 'danger' : 'warning');
        return `<tr>
            <td>${h.no}</td>
            <td class="font-monospace fw-bold">${h.kode}</td>
            <td>${h.guest}</td>
            <td><span class="badge bg-${cls}">${h.status.toUpperCase()}</span></td>
            <td>${h.total}</td>
            <td class="text-muted">${h.waktu}</td>
        </tr>`;
    }).join('');
}

// ==========================================
// START / STOP SCANNER
// ==========================================
function startScanner() {
    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    const boxSize = Math.min(Math.floor(document.getElementById('reader').clientWidth * 0.85), 260);

    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: boxSize, height: boxSize },
            formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
        },
        // ON SUCCESS
        function(decodedText) {
            // 1. BEEP
            playBeep();

            // 2. STOP
            stopScanner(false);

            // 3. Lookup
            lookupPesanan(decodedText);

            document.getElementById('btnStop').classList.add('d-none');
            document.getElementById('btnReset').classList.remove('d-none');
            document.getElementById('scanStatus').classList.add('d-none');
        },
        function() { /* silent */ }
    )
    .then(() => {
        scannerRunning = true;
        document.getElementById('btnStart').classList.add('d-none');
        document.getElementById('btnStop').classList.remove('d-none');
        document.getElementById('btnReset').classList.add('d-none');
        document.getElementById('scanStatus').classList.remove('d-none');
        showPanel('cardWaiting');
    })
    .catch(err => alert('Gagal mengakses kamera: ' + err));
}

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
