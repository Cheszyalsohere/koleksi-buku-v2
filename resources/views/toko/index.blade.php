@extends('layouts.app')

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kunjungan Toko</h4>
            <small class="text-muted">Geolocation — Verifikasi Kunjungan Sales</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABS --}}
    <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabDataToko" type="button">
                <i class="mdi mdi-store me-1"></i> Data Toko
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabKunjungan" type="button" id="tabKunjunganBtn">
                <i class="mdi mdi-map-marker-check me-1"></i> Kunjungan
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- ========== TAB 1: DATA TOKO ========== --}}
        <div class="tab-pane fade show active" id="tabDataToko">
            <div class="row g-4">

                {{-- FORM TAMBAH TOKO --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header fw-semibold bg-primary text-white">
                            <i class="mdi mdi-store-plus me-2"></i> Tambah Toko
                        </div>
                        <div class="card-body">
                            <form action="{{ route('toko.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Toko <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_toko"
                                        class="form-control @error('nama_toko') is-invalid @enderror"
                                        value="{{ old('nama_toko') }}" placeholder="contoh: Toko Maju Jaya">
                                    @error('nama_toko')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <hr class="my-2">
                                <p class="text-muted small mb-2">
                                    <i class="mdi mdi-information-outline me-1"></i>
                                    Klik tombol di bawah untuk ambil titik lokasi toko secara otomatis,
                                    atau isi koordinat secara manual dari Google Maps.
                                </p>

                                <button type="button" class="btn btn-outline-success w-100 mb-3"
                                    onclick="ambilLokasiToko()">
                                    <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi Toko
                                </button>

                                <div id="tokoGeolStatus" class="alert py-2 small mb-3" style="display:none;"></div>

                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Latitude</label>
                                        <input type="text" name="latitude" id="tokoLat"
                                            class="form-control form-control-sm font-monospace @error('latitude') is-invalid @enderror"
                                            value="{{ old('latitude') }}" placeholder="-7.2574720">
                                        @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Longitude</label>
                                        <input type="text" name="longitude" id="tokoLng"
                                            class="form-control form-control-sm font-monospace @error('longitude') is-invalid @enderror"
                                            value="{{ old('longitude') }}" placeholder="112.7520900">
                                        @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold">Accuracy (meter)</label>
                                        <input type="text" name="accuracy" id="tokoAcc"
                                            class="form-control form-control-sm @error('accuracy') is-invalid @enderror"
                                            value="{{ old('accuracy') }}" placeholder="contoh: 20">
                                        @error('accuracy')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 mt-3">
                                    <i class="mdi mdi-content-save me-1"></i> Simpan Toko
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- LIST TOKO --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                            <span><i class="mdi mdi-store me-2"></i> Daftar Toko ({{ $tokos->count() }})</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Barcode</th>
                                            <th>Nama Toko</th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                            <th class="text-center">Accuracy</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($tokos as $i => $t)
                                        <tr>
                                            <td class="text-muted small">{{ $i + 1 }}</td>
                                            <td><span class="badge bg-secondary font-monospace">{{ $t->barcode }}</span></td>
                                            <td class="fw-semibold">{{ $t->nama_toko }}</td>
                                            <td class="font-monospace small">{{ $t->latitude }}</td>
                                            <td class="font-monospace small">{{ $t->longitude }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $t->accuracy }}m</span>
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('toko.cetak', $t->id) }}" target="_blank"
                                                   class="btn btn-sm btn-outline-primary me-1" title="Cetak Barcode">
                                                    <i class="mdi mdi-barcode"></i>
                                                </a>
                                                <form action="{{ route('toko.destroy', $t->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Hapus toko {{ $t->nama_toko }}?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Belum ada toko. Tambahkan di form kiri.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ========== TAB 2: KUNJUNGAN ========== --}}
        <div class="tab-pane fade" id="tabKunjungan">
            <div class="row g-4">

                {{-- Kolom Kiri: Scanner + Info Toko --}}
                <div class="col-lg-6">

                    {{-- Barcode Scanner --}}
                    <div class="card">
                        <div class="card-header fw-semibold">
                            <i class="mdi mdi-barcode-scan me-2"></i> Scan Barcode Toko
                        </div>
                        <div class="card-body">
                            <div id="reader" style="width:100%;"></div>
                            <div class="d-flex gap-2 mt-2">
                                <button id="btnStartScan" class="btn btn-primary flex-fill" onclick="startScanner()">
                                    <i class="mdi mdi-play me-1"></i> Mulai Scan
                                </button>
                                <button id="btnStopScan" class="btn btn-danger flex-fill"
                                    style="display:none;" onclick="stopScanner()">
                                    <i class="mdi mdi-stop me-1"></i> Stop
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Info Toko setelah scan --}}
                    <div class="card mt-3" id="cardInfoToko" style="display:none;">
                        <div class="card-header fw-semibold bg-light">
                            <i class="mdi mdi-store me-2"></i> Data Toko (Hasil Scan)
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm mb-0" id="infoTokoTable">
                            </table>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Titik Kunjungan --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header fw-semibold">
                            <i class="mdi mdi-map-marker me-2"></i> Titik Kunjungan Sales
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">
                                    Threshold Jarak (meter)
                                    <span class="text-muted fw-normal">— jarak maksimum yang diizinkan</span>
                                </label>
                                <input type="number" id="threshold" class="form-control form-control-sm"
                                    value="300" min="1">
                            </div>

                            <button class="btn btn-success w-100 mb-3" onclick="ambilLokasiSales()"
                                id="btnAmbilLokasi" disabled>
                                <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi Saya
                            </button>

                            <div id="salesGeolStatus" class="alert py-2 small" style="display:none;"></div>

                            {{-- Hasil --}}
                            <div id="hasilKunjungan" style="display:none;"></div>

                        </div>
                    </div>

                    {{-- Penjelasan formula --}}
                    <div class="card mt-3 border-0 bg-light">
                        <div class="card-body py-2">
                            <p class="small text-muted mb-1 fw-semibold">
                                <i class="mdi mdi-information-outline me-1"></i> Formula Threshold Efektif
                            </p>
                            <code class="small">
                                threshold_efektif = threshold + accuracy_toko + accuracy_sales
                            </code>
                            <br>
                            <code class="small">
                                jarak ≤ threshold_efektif → DITERIMA
                            </code>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// =============================================
// BARCODE SCANNER
// =============================================
let html5QrCode  = null;
let scannedToko  = null;

function startScanner() {
    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: { width: 320, height: 100 },
            formatsToSupport: [
                Html5QrcodeSupportedFormats.CODE_128,
                Html5QrcodeSupportedFormats.CODE_39,
                Html5QrcodeSupportedFormats.QR_CODE,
            ]
        },
        (decodedText) => {
            stopScanner();
            lookupBarcode(decodedText);
        }
    ).then(() => {
        document.getElementById('btnStartScan').style.display = 'none';
        document.getElementById('btnStopScan').style.display  = '';
    }).catch(err => alert('Gagal memulai scanner: ' + err));
}

function stopScanner() {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().then(() => {
            document.getElementById('btnStartScan').style.display = '';
            document.getElementById('btnStopScan').style.display  = 'none';
        });
    }
}

function lookupBarcode(barcode) {
    fetch('/toko/find-by-barcode', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ barcode })
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'found') {
            scannedToko = res.data;
            tampilkanInfoToko(res.data);
            document.getElementById('btnAmbilLokasi').disabled = false;
            document.getElementById('hasilKunjungan').style.display = 'none';
        } else {
            alert('Toko dengan barcode "' + barcode + '" tidak ditemukan!');
        }
    })
    .catch(() => alert('Gagal menghubungi server'));
}

function tampilkanInfoToko(t) {
    document.getElementById('cardInfoToko').style.display = '';
    document.getElementById('infoTokoTable').innerHTML = `
        <tr><th class="ps-3 py-2 text-muted small" style="width:120px;">Nama Toko</th>
            <td class="py-2 fw-semibold">${t.nama_toko}</td></tr>
        <tr><th class="ps-3 py-2 text-muted small">Barcode</th>
            <td class="py-2 font-monospace small">${t.barcode}</td></tr>
        <tr><th class="ps-3 py-2 text-muted small">Latitude</th>
            <td class="py-2 font-monospace small">${t.latitude}</td></tr>
        <tr><th class="ps-3 py-2 text-muted small">Longitude</th>
            <td class="py-2 font-monospace small">${t.longitude}</td></tr>
        <tr><th class="ps-3 py-2 text-muted small">Accuracy</th>
            <td class="py-2"><span class="badge bg-info">${t.accuracy} m</span></td></tr>`;
}

// =============================================
// GEOLOCATION — AMBIL LOKASI TOKO
// =============================================
async function ambilLokasiToko() {
    const status = document.getElementById('tokoGeolStatus');
    status.style.display = '';
    status.className = 'alert alert-info py-2 small mb-3';
    status.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Mencari lokasi terbaik (maks 20 detik)...';

    try {
        const pos = await getAccuratePosition(50);
        document.getElementById('tokoLat').value = pos.coords.latitude.toFixed(7);
        document.getElementById('tokoLng').value = pos.coords.longitude.toFixed(7);
        document.getElementById('tokoAcc').value = pos.coords.accuracy.toFixed(1);

        status.className = 'alert alert-success py-2 small mb-3';
        status.innerHTML = `<i class="mdi mdi-check-circle me-1"></i>
            Berhasil! Accuracy: <strong>${pos.coords.accuracy.toFixed(1)} m</strong>`;
    } catch(e) {
        status.className = 'alert alert-danger py-2 small mb-3';
        status.innerHTML = '<i class="mdi mdi-alert me-1"></i> ' + e.message;
    }
}

// =============================================
// GEOLOCATION — KUNJUNGAN SALES
// =============================================
async function ambilLokasiSales() {
    if (!scannedToko) {
        alert('Scan barcode toko terlebih dahulu!');
        return;
    }

    const status = document.getElementById('salesGeolStatus');
    status.style.display = '';
    status.className = 'alert alert-info py-2 small';
    status.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Mencari lokasi terbaik (maks 20 detik)...';
    document.getElementById('hasilKunjungan').style.display = 'none';

    try {
        const pos = await getAccuratePosition(50);
        const salesLat = pos.coords.latitude;
        const salesLng = pos.coords.longitude;
        const salesAcc = pos.coords.accuracy;

        status.className = 'alert alert-success py-2 small';
        status.innerHTML = `<i class="mdi mdi-check-circle me-1"></i>
            Lokasi diambil! Accuracy: <strong>${salesAcc.toFixed(1)} m</strong>`;

        hitungKunjungan(salesLat, salesLng, salesAcc);
    } catch(e) {
        status.className = 'alert alert-danger py-2 small';
        status.innerHTML = '<i class="mdi mdi-alert me-1"></i> ' + e.message;
    }
}

// =============================================
// HITUNG JARAK & TAMPILKAN HASIL
// =============================================
function hitungKunjungan(salesLat, salesLng, salesAcc) {
    const threshold         = parseFloat(document.getElementById('threshold').value) || 300;
    const jarak             = haversine(scannedToko.latitude, scannedToko.longitude, salesLat, salesLng);
    const thresholdEfektif  = threshold + parseFloat(scannedToko.accuracy) + salesAcc;
    const diterima          = jarak <= thresholdEfektif;

    const el = document.getElementById('hasilKunjungan');
    el.style.display = '';
    el.innerHTML = `
        <div class="card border-${diterima ? 'success' : 'danger'} mt-3">
            <div class="card-body text-center">
                <div style="font-size:52px; line-height:1;">${diterima ? '✅' : '❌'}</div>
                <h4 class="fw-bold text-${diterima ? 'success' : 'danger'} mt-2 mb-3">
                    KUNJUNGAN ${diterima ? 'DITERIMA' : 'DITOLAK'}
                </h4>
                <table class="table table-sm text-start mb-2">
                    <tr class="table-light">
                        <th>Jarak Aktual</th>
                        <td class="fw-bold font-monospace">${jarak.toFixed(1)} m</td>
                    </tr>
                    <tr>
                        <th>Threshold</th>
                        <td class="font-monospace">${threshold} m</td>
                    </tr>
                    <tr>
                        <th>Accuracy Toko</th>
                        <td class="font-monospace">${parseFloat(scannedToko.accuracy).toFixed(1)} m</td>
                    </tr>
                    <tr>
                        <th>Accuracy Sales</th>
                        <td class="font-monospace">${salesAcc.toFixed(1)} m</td>
                    </tr>
                    <tr class="table-${diterima ? 'success' : 'danger'}">
                        <th>Threshold Efektif</th>
                        <td class="fw-bold font-monospace">${thresholdEfektif.toFixed(1)} m</td>
                    </tr>
                    <tr>
                        <th>Posisi Sales</th>
                        <td class="font-monospace small">${salesLat.toFixed(7)},<br>${salesLng.toFixed(7)}</td>
                    </tr>
                </table>
                <div class="alert alert-${diterima ? 'success' : 'danger'} py-2 mb-0 small">
                    <strong>${jarak.toFixed(1)}m ${diterima ? '≤' : '>'} ${thresholdEfektif.toFixed(1)}m
                    → ${diterima ? 'DITERIMA ✓' : 'DITOLAK ✗'}</strong>
                </div>
            </div>
        </div>`;
}

// =============================================
// HAVERSINE (Lampiran 2)
// =============================================
function haversine(lat1, lng1, lat2, lng2) {
    const R    = 6371000;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a    = Math.sin(dLat / 2) ** 2
               + Math.cos(lat1 * Math.PI / 180)
               * Math.cos(lat2 * Math.PI / 180)
               * Math.sin(dLng / 2) ** 2;
    const c    = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// =============================================
// GET ACCURATE POSITION (Lampiran 1)
// =============================================
function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
    return new Promise((resolve, reject) => {
        let bestResult  = null;
        const startTime = Date.now();

        const watchId = navigator.geolocation.watchPosition(
            (position) => {
                const acc = position.coords.accuracy;

                if (!bestResult || acc < bestResult.coords.accuracy) {
                    bestResult = position;
                }

                if (acc <= targetAccuracy) {
                    navigator.geolocation.clearWatch(watchId);
                    resolve(bestResult);
                }

                if (Date.now() - startTime >= maxWait) {
                    navigator.geolocation.clearWatch(watchId);
                    if (bestResult) resolve(bestResult);
                    else reject(new Error('Timeout — tidak dapat posisi GPS'));
                }
            },
            (error) => reject(error),
            { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
        );
    });
}
</script>
@endsection
