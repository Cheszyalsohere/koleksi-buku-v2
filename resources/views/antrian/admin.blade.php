<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — Sistem Antrian</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --indigo: #6366f1; --indigo-d: #4f46e5;
            --green: #10b981; --amber: #f59e0b; --red: #ef4444;
            --slate: #0f172a; --slate2: #1e293b;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #1e293b; }

        .topbar {
            background: var(--slate); color: #fff;
            padding: 14px 22px; display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar .brand { display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 18px; }
        .topbar .brand .ico {
            width: 38px; height: 38px; border-radius: 11px;
            background: linear-gradient(135deg, var(--indigo), #a855f7);
            display: flex; align-items: center; justify-content: center; font-size: 18px;
        }
        .conn-badge {
            display: flex; align-items: center; gap: 7px;
            font-size: 12px; padding: 6px 12px; border-radius: 20px;
            background: rgba(255,255,255,0.1);
        }
        .conn-dot { width: 8px; height: 8px; border-radius: 50%; background: #64748b; }
        .conn-dot.live { background: var(--green); box-shadow: 0 0 8px var(--green); animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

        .container { max-width: 1200px; margin: 0 auto; padding: 24px 20px; }

        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; }
        .stat-card {
            background: #fff; border-radius: 16px; padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .stat-card .num { font-size: 34px; font-weight: 800; line-height: 1; }
        .stat-card .lbl { font-size: 13px; color: #64748b; margin-top: 4px; }
        .stat-menunggu .num { color: var(--indigo); }
        .stat-terlambat .num { color: var(--amber); }
        .stat-current .num { color: var(--green); }

        .grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: 20px; }
        @media (max-width: 880px) { .grid { grid-template-columns: 1fr; } .stats { grid-template-columns: 1fr; } }

        .panel { background: #fff; border-radius: 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.06); overflow: hidden; }
        .panel-head {
            padding: 16px 20px; font-weight: 700; font-size: 15px;
            border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px;
        }
        .panel-body { padding: 16px 20px; }

        /* CURRENT CALL CARD */
        .current-card {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 18px; padding: 24px; color: #fff; text-align: center;
            margin-bottom: 18px;
        }
        .current-card .c-label { font-size: 13px; opacity: .85; text-transform: uppercase; letter-spacing: 1px; }
        .current-card .c-nomor { font-size: 64px; font-weight: 800; line-height: 1.1; }
        .current-card .c-nama { font-size: 20px; font-weight: 600; }
        .current-card .c-empty { font-size: 16px; opacity: .8; padding: 16px 0; }

        .ruangan-input {
            width: 100%; padding: 12px 14px; border: 2px solid #e2e8f0; border-radius: 12px;
            font-size: 14px; font-family: inherit; margin-bottom: 12px; outline: none;
        }
        .ruangan-input:focus { border-color: var(--indigo); }

        .btn {
            border: none; border-radius: 12px; font-family: inherit;
            font-weight: 700; cursor: pointer; transition: all .15s;
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-call {
            width: 100%; padding: 18px; font-size: 18px; color: #fff;
            background: linear-gradient(135deg, var(--green), #059669);
            box-shadow: 0 8px 22px rgba(16,185,129,0.35);
        }
        .btn-call:hover { transform: translateY(-2px); }
        .btn-call:active { transform: translateY(0); }
        .btn-reset {
            padding: 8px 14px; font-size: 12px; background: #fee2e2; color: var(--red);
        }
        .btn-reset:hover { background: #fecaca; }

        /* LIST */
        .q-item {
            display: flex; align-items: center; gap: 14px;
            padding: 12px 14px; border-radius: 12px; margin-bottom: 8px;
            background: #f8fafc; border: 1px solid #f1f5f9;
            transition: background .15s;
        }
        .q-item:hover { background: #f1f5f9; }
        .q-num {
            width: 48px; height: 48px; flex-shrink: 0; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 800;
            background: #e0e7ff; color: var(--indigo-d);
        }
        .q-item.terlambat .q-num { background: #fef3c7; color: #b45309; }
        .q-info { flex: 1; min-width: 0; }
        .q-nama { font-weight: 700; font-size: 15px; }
        .q-meta { font-size: 12px; color: #94a3b8; }
        .q-actions { display: flex; gap: 6px; flex-shrink: 0; }
        .btn-mini {
            padding: 7px 11px; font-size: 12px; border-radius: 9px;
        }
        .btn-late   { background: #fef3c7; color: #b45309; }
        .btn-late:hover { background: #fde68a; }
        .btn-recall { background: #d1fae5; color: #047857; }
        .btn-recall:hover { background: #a7f3d0; }
        .btn-done   { background: #e0e7ff; color: var(--indigo-d); }
        .btn-done:hover { background: #c7d2fe; }

        .empty { text-align: center; color: #94a3b8; padding: 24px; font-size: 14px; }
        .terlambat-hint { font-size: 11px; color: #b45309; background: #fef9c3; padding: 6px 10px; border-radius: 8px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="brand">
            <div class="ico">📋</div>
            <span>Admin — Sistem Antrian</span>
        </div>
        <div class="conn-badge">
            <span class="conn-dot" id="connDot"></span>
            <span id="connText">Menghubungkan…</span>
        </div>
    </div>

    <div class="container">

        {{-- STATS --}}
        <div class="stats">
            <div class="stat-card stat-current">
                <div class="num" id="statCurrent">–</div>
                <div class="lbl">Sedang Dipanggil</div>
            </div>
            <div class="stat-card stat-menunggu">
                <div class="num" id="statMenunggu">0</div>
                <div class="lbl">Menunggu</div>
            </div>
            <div class="stat-card stat-terlambat">
                <div class="num" id="statTerlambat">0</div>
                <div class="lbl">Terlambat</div>
            </div>
        </div>

        <div class="grid">

            {{-- KIRI: Daftar Menunggu --}}
            <div class="panel">
                <div class="panel-head">
                    <i class="bi bi-people-fill"></i> Daftar Antrian Menunggu
                </div>
                <div class="panel-body">
                    <div id="waitingList">
                        <div class="empty">Belum ada antrian.</div>
                    </div>
                </div>
            </div>

            {{-- KANAN: Kontrol + Terlambat --}}
            <div>
                {{-- Current call --}}
                <div class="current-card">
                    <div class="c-label">Sedang Dipanggil</div>
                    <div id="currentDisplay">
                        <div class="c-empty">Belum ada yang dipanggil</div>
                    </div>
                </div>

                {{-- Kontrol --}}
                <div class="panel" style="margin-bottom: 18px;">
                    <div class="panel-body">
                        <input type="text" id="ruanganInput" class="ruangan-input"
                            placeholder="Ruangan (opsional, cth: Ruang Dokter Meta)">
                        <button class="btn btn-call" onclick="panggilBerikutnya()">
                            <i class="bi bi-megaphone-fill"></i> Panggil Berikutnya
                        </button>
                    </div>
                </div>

                {{-- Terlambat --}}
                <div class="panel">
                    <div class="panel-head" style="justify-content: space-between;">
                        <span><i class="bi bi-clock-history"></i> Daftar Terlambat</span>
                        <button class="btn btn-reset" onclick="resetAntrian()">
                            <i class="bi bi-trash"></i> Reset Semua
                        </button>
                    </div>
                    <div class="panel-body">
                        <div class="terlambat-hint">
                            💡 Klik 2× pada nama, atau tombol Panggil untuk memanggil ulang
                        </div>
                        <div id="terlambatList">
                            <div class="empty">Tidak ada yang terlambat.</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// ============================================================
// SSE CONNECTION
// ============================================================
let evtSource = null;
let lastUpdate = 0;   // waktu update terakhir diterima

function connectSSE() {
    evtSource = new EventSource('{{ route("antrian.stream") }}');

    evtSource.addEventListener('queue-update', (e) => {
        lastUpdate = Date.now();
        const data = JSON.parse(e.data);
        renderAdmin(data);
    });

    // onerror TIDAK dipakai untuk status — koneksi memang sengaja ditutup
    // tiap siklus (kirim-sekali + reconnect). Status dipantau via heartbeat.
}

function setConn(live) {
    document.getElementById('connDot').className = 'conn-dot' + (live ? ' live' : '');
    document.getElementById('connText').textContent = live ? 'Terhubung (Live)' : 'Terputus…';
}

// Heartbeat: dianggap Live selama ada update dalam 3 detik terakhir
setInterval(() => {
    setConn(Date.now() - lastUpdate < 3000);
}, 1000);

// ============================================================
// RENDER
// ============================================================
function renderAdmin(data) {
    // Stats
    document.getElementById('statMenunggu').textContent  = data.stats.menunggu;
    document.getElementById('statTerlambat').textContent = data.stats.terlambat;
    document.getElementById('statCurrent').textContent   =
        data.current ? pad(data.current.nomor) : '–';

    // Current call
    const cd = document.getElementById('currentDisplay');
    if (data.current) {
        cd.innerHTML = `
            <div class="c-nomor">${pad(data.current.nomor)}</div>
            <div class="c-nama">${esc(data.current.nama)}</div>
            ${data.current.ruangan ? `<div style="font-size:13px;opacity:.85;margin-top:4px;">📍 ${esc(data.current.ruangan)}</div>` : ''}`;
    } else {
        cd.innerHTML = `<div class="c-empty">Belum ada yang dipanggil</div>`;
    }

    // Waiting list
    const wl = document.getElementById('waitingList');
    if (data.waiting.length === 0) {
        wl.innerHTML = `<div class="empty">Belum ada antrian menunggu.</div>`;
    } else {
        wl.innerHTML = data.waiting.map(q => `
            <div class="q-item">
                <div class="q-num">${pad(q.nomor)}</div>
                <div class="q-info">
                    <div class="q-nama">${esc(q.nama)}</div>
                    <div class="q-meta">Menunggu dipanggil</div>
                </div>
                <div class="q-actions">
                    <button class="btn btn-mini btn-late" onclick="tandaiTerlambat(${q.id})" title="Tandai terlambat">
                        <i class="bi bi-clock"></i> Terlambat
                    </button>
                </div>
            </div>`).join('');
    }

    // Terlambat list
    const tl = document.getElementById('terlambatList');
    if (data.terlambat.length === 0) {
        tl.innerHTML = `<div class="empty">Tidak ada yang terlambat.</div>`;
    } else {
        tl.innerHTML = data.terlambat.map(q => `
            <div class="q-item terlambat" ondblclick="panggilUlang(${q.id})" title="Klik 2x untuk panggil ulang">
                <div class="q-num">${pad(q.nomor)}</div>
                <div class="q-info">
                    <div class="q-nama">${esc(q.nama)}</div>
                    <div class="q-meta">Tidak hadir saat dipanggil</div>
                </div>
                <div class="q-actions">
                    <button class="btn btn-mini btn-recall" onclick="panggilUlang(${q.id})" title="Panggil ulang">
                        <i class="bi bi-megaphone"></i> Panggil
                    </button>
                </div>
            </div>`).join('');
    }
}

// ============================================================
// ACTIONS
// ============================================================
function post(url, body = {}) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'X-Requested-With':'XMLHttpRequest' },
        body: JSON.stringify(body)
    }).then(r => r.json());
}

function panggilBerikutnya() {
    const ruangan = document.getElementById('ruanganInput').value.trim();
    post('{{ route("antrian.panggil") }}', { ruangan })
        .then(res => {
            if (res.status === 'empty') alert(res.message);
        })
        .catch(() => alert('Gagal memanggil'));
}

function panggilUlang(id) {
    const ruangan = document.getElementById('ruanganInput').value.trim();
    post('{{ url("antrian/panggil-ulang") }}/' + id, { ruangan });
}

function tandaiTerlambat(id) {
    post('{{ url("antrian/terlambat") }}/' + id);
}

function resetAntrian() {
    if (!confirm('Reset & hapus SEMUA data antrian? Tindakan ini tidak bisa dibatalkan.')) return;
    post('{{ route("antrian.reset") }}');
}

// ============================================================
// UTIL
// ============================================================
function pad(n) { return String(n).padStart(3, '0'); }
function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

connectSSE();
</script>
</body>
</html>
