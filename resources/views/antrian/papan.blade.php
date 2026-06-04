<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh; overflow: hidden;
            background: radial-gradient(ellipse at top, #1e293b, #0f172a);
            color: #fff;
        }

        /* START OVERLAY (untuk unlock audio policy) */
        .start-overlay {
            position: fixed; inset: 0; z-index: 999;
            background: rgba(15,23,42,0.97);
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; padding: 20px;
        }
        .start-overlay h1 { font-size: 32px; margin-bottom: 10px; font-weight: 800; }
        .start-overlay p { color: #94a3b8; margin-bottom: 30px; max-width: 420px; line-height: 1.6; }
        .btn-start {
            padding: 18px 44px; font-size: 20px; font-weight: 700; font-family: inherit;
            border: none; border-radius: 16px; cursor: pointer; color: #fff;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            box-shadow: 0 14px 40px rgba(99,102,241,0.5);
            transition: transform .15s;
        }
        .btn-start:hover { transform: scale(1.05); }

        /* HEADER */
        .papan-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 40px; border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .papan-head .title { font-size: 26px; font-weight: 800; display: flex; align-items: center; gap: 12px; }
        .papan-head .title .ico {
            width: 44px; height: 44px; border-radius: 12px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            display: flex; align-items: center; justify-content: center; font-size: 22px;
        }
        .clock { font-size: 28px; font-weight: 700; font-variant-numeric: tabular-nums; color: #a5b4fc; }
        .conn-mini { font-size: 12px; color: #64748b; }

        /* MAIN GRID */
        .papan-main {
            display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px;
            padding: 30px 40px; height: calc(100vh - 86px);
        }
        @media (max-width: 900px) { .papan-main { grid-template-columns: 1fr; height: auto; } }

        /* NOW SERVING */
        .now-serving {
            background: linear-gradient(145deg, #4338ca, #7c3aed);
            border-radius: 32px; padding: 40px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; position: relative; overflow: hidden;
            box-shadow: 0 30px 80px rgba(99,102,241,0.3);
        }
        .now-serving::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(circle at 30% 20%, rgba(255,255,255,0.15), transparent 50%);
        }
        .ns-label {
            font-size: 22px; font-weight: 700; letter-spacing: 4px; text-transform: uppercase;
            opacity: .85; position: relative;
        }
        .ns-nomor {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(120px, 22vw, 280px); line-height: .9;
            font-weight: 400; letter-spacing: 4px; position: relative;
            text-shadow: 0 8px 30px rgba(0,0,0,0.3);
        }
        .ns-nama {
            font-size: clamp(28px, 4vw, 52px); font-weight: 700; position: relative;
            margin-top: 10px;
        }
        .ns-ruangan {
            margin-top: 18px; font-size: 22px; font-weight: 600; position: relative;
            background: rgba(255,255,255,0.15); padding: 10px 26px; border-radius: 30px;
        }
        .ns-empty { font-size: 34px; opacity: .7; position: relative; }

        .now-serving.flash { animation: flashCard 1s ease 3; }
        @keyframes flashCard {
            0%,100% { box-shadow: 0 30px 80px rgba(99,102,241,0.3); }
            50%     { box-shadow: 0 0 0 8px rgba(255,255,255,0.6), 0 30px 90px rgba(168,85,247,0.6); }
        }

        /* WAITING SIDE */
        .waiting-panel {
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            border-radius: 28px; padding: 26px; overflow: hidden;
            display: flex; flex-direction: column;
        }
        .wp-title {
            font-size: 18px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;
            color: #94a3b8; margin-bottom: 18px; display: flex; align-items: center; gap: 10px;
        }
        .wp-list { display: flex; flex-direction: column; gap: 12px; overflow-y: auto; }
        .wp-item {
            display: flex; align-items: center; gap: 16px;
            background: rgba(255,255,255,0.05); border-radius: 16px; padding: 14px 18px;
        }
        .wp-num {
            font-family: 'Bebas Neue', sans-serif; font-size: 34px;
            color: #a5b4fc; min-width: 64px; letter-spacing: 1px;
        }
        .wp-nama { font-size: 20px; font-weight: 600; }
        .wp-empty { color: #64748b; text-align: center; padding: 30px; font-size: 16px; }
    </style>
</head>
<body>

    {{-- START OVERLAY --}}
    <div class="start-overlay" id="startOverlay">
        <div style="font-size:64px; margin-bottom:16px;">📺</div>
        <h1>Papan Antrian</h1>
        <p>Klik tombol di bawah untuk memulai papan antrian. Ini diperlukan agar suara panggilan dapat diputar oleh browser.</p>
        <button class="btn-start" onclick="startPapan()">
            <i class="bi bi-play-fill"></i> Mulai Papan Antrian
        </button>
    </div>

    {{-- HEADER --}}
    <div class="papan-head">
        <div class="title">
            <div class="ico">📺</div>
            <div>
                Papan Antrian
                <div class="conn-mini" id="connMini">menghubungkan…</div>
            </div>
        </div>
        <div class="clock" id="clock">--:--:--</div>
    </div>

    {{-- MAIN --}}
    <div class="papan-main">
        {{-- NOW SERVING --}}
        <div class="now-serving" id="nowServing">
            <div class="ns-label">Nomor Dipanggil</div>
            <div id="nsContent">
                <div class="ns-empty">Menunggu panggilan…</div>
            </div>
        </div>

        {{-- WAITING --}}
        <div class="waiting-panel">
            <div class="wp-title"><i class="bi bi-hourglass-split"></i> Antrian Berikutnya</div>
            <div class="wp-list" id="waitingList">
                <div class="wp-empty">Belum ada antrian.</div>
            </div>
        </div>
    </div>

<script>
// ============================================================
// CLOCK
// ============================================================
function tick() {
    document.getElementById('clock').textContent =
        new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
tick(); setInterval(tick, 1000);

// ============================================================
// AUDIO: Ding-dong (Web Audio API) + Web Speech API
// ============================================================
let audioCtx = null;
let started = false;

function startPapan() {
    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    // unlock speech synthesis
    if ('speechSynthesis' in window) {
        const u = new SpeechSynthesisUtterance('');
        window.speechSynthesis.speak(u);
    }
    started = true;
    document.getElementById('startOverlay').style.display = 'none';
    connectSSE();
}

// Ding-dong dengan oscillator (tanpa file mp3)
function playDingDong() {
    if (!audioCtx) return;
    const now = audioCtx.currentTime;
    const notes = [
        { f: 880.0, t: 0.0 },   // ding (A5)
        { f: 659.3, t: 0.45 },  // dong (E5)
    ];
    notes.forEach(n => {
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.connect(gain); gain.connect(audioCtx.destination);
        osc.type = 'sine';
        osc.frequency.value = n.f;
        gain.gain.setValueAtTime(0.0001, now + n.t);
        gain.gain.exponentialRampToValueAtTime(0.5, now + n.t + 0.03);
        gain.gain.exponentialRampToValueAtTime(0.0001, now + n.t + 0.6);
        osc.start(now + n.t);
        osc.stop(now + n.t + 0.65);
    });
}

// Panggilan suara: ding-dong → "Nomor antrian X, Nama, silakan masuk..."
function announce(nomor, nama, ruangan) {
    playDingDong();

    if (!('speechSynthesis' in window)) return;
    window.speechSynthesis.cancel();

    let teks = `Nomor antrian ${nomor}. ${nama}.`;
    teks += ruangan ? ` Silakan masuk ke ${ruangan}.` : ' Silakan menuju loket.';

    const u = new SpeechSynthesisUtterance(teks);
    u.lang = 'id-ID';
    u.rate = 0.85;
    u.pitch = 1.0;
    u.volume = 1.0;

    // Tunggu ding-dong selesai (~1.1 detik) baru bicara
    setTimeout(() => window.speechSynthesis.speak(u), 1200);
}

// ============================================================
// SSE
// ============================================================
let evtSource = null;
let lastCallTs = null;   // timestamp panggilan terakhir (deteksi panggilan baru)
let firstLoad  = true;
let lastUpdate = 0;      // waktu update terakhir diterima

function connectSSE() {
    evtSource = new EventSource('{{ route("antrian.stream") }}');

    evtSource.addEventListener('queue-update', (e) => {
        lastUpdate = Date.now();
        const data = JSON.parse(e.data);
        render(data);
    });

    // onerror tidak dipakai untuk status — koneksi sengaja ditutup tiap siklus.
}

// Heartbeat: Live selama ada update dalam 3 detik terakhir
setInterval(() => {
    document.getElementById('connMini').textContent =
        (Date.now() - lastUpdate < 3000) ? 'live' : 'terputus…';
}, 1000);

function render(data) {
    // NOW SERVING
    const ns = document.getElementById('nsContent');
    const card = document.getElementById('nowServing');

    if (data.current) {
        ns.innerHTML = `
            <div class="ns-nomor">${pad(data.current.nomor)}</div>
            <div class="ns-nama">${esc(data.current.nama)}</div>
            ${data.current.ruangan ? `<div class="ns-ruangan">📍 ${esc(data.current.ruangan)}</div>` : ''}`;

        // Deteksi panggilan BARU (timestamp berubah) → bunyikan suara
        const ts = data.current.ts;
        if (ts !== lastCallTs) {
            lastCallTs = ts;
            if (!firstLoad) {
                card.classList.remove('flash'); void card.offsetWidth; // restart anim
                card.classList.add('flash');
                announce(data.current.nomor, data.current.nama, data.current.ruangan);
            }
        }
    } else {
        ns.innerHTML = `<div class="ns-empty">Menunggu panggilan…</div>`;
    }

    // WAITING (max 6)
    const wl = document.getElementById('waitingList');
    const list = data.waiting.slice(0, 6);
    if (list.length === 0) {
        wl.innerHTML = `<div class="wp-empty">Belum ada antrian berikutnya.</div>`;
    } else {
        wl.innerHTML = list.map(q => `
            <div class="wp-item">
                <div class="wp-num">${pad(q.nomor)}</div>
                <div class="wp-nama">${esc(q.nama)}</div>
            </div>`).join('');
    }

    firstLoad = false;
}

// ============================================================
// UTIL
// ============================================================
function pad(n) { return String(n).padStart(3, '0'); }
function esc(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
</body>
</html>
