<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AntrianController extends Controller
{
    // ============================================================
    // HALAMAN
    // ============================================================
    public function guest()
    {
        return view('antrian.guest');
    }

    public function admin()
    {
        return view('antrian.admin');
    }

    public function papan()
    {
        return view('antrian.papan');
    }

    public function tiket($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.tiket', compact('antrian'));
    }

    // ============================================================
    // GUEST: DAFTAR ANTRIAN
    // ============================================================
    public function daftar(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        // Nomor urut berikutnya (lanjut dari nomor terakhir hari ini)
        $nomorTerakhir = Antrian::max('nomor') ?? 0;

        $antrian = Antrian::create([
            'nomor'  => $nomorTerakhir + 1,
            'nama'   => $request->nama,
            'status' => 'menunggu',
        ]);

        // Buka tab tiket
        return redirect()->route('antrian.tiket', $antrian->id);
    }

    // ============================================================
    // SSE STREAM — Server-Sent Events
    // ============================================================
    public function stream(Request $request): StreamedResponse
    {
        // Lepas session lock supaya request lain (POST panggil, tab lain) tidak ikut terblokir
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $response = new StreamedResponse(function () {
            // 'retry: 1000' = browser otomatis reconnect 1 detik setelah koneksi ditutup.
            // Pola "kirim sekali lalu tutup" ini KOMPATIBEL dengan `php artisan serve`
            // (single-thread) karena tiap request selesai cepat (~ms), tidak menahan
            // server selama berdetik-detik seperti loop while(true).
            echo "retry: 1000" . PHP_EOL;

            $payload = $this->buildPayload();
            echo "event: queue-update" . PHP_EOL;
            echo "data: " . json_encode($payload) . PHP_EOL;
            echo PHP_EOL;

            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
            // Koneksi ditutup di sini → EventSource akan reconnect setelah 1 detik.
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('X-Accel-Buffering', 'no'); // penting untuk Nginx
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }

    /**
     * Susun data antrian untuk dikirim via SSE.
     */
    private function buildPayload(): array
    {
        $current = Antrian::where('status', 'dipanggil')
            ->orderBy('called_at', 'desc')
            ->first();

        $waiting = Antrian::where('status', 'menunggu')
            ->orderBy('nomor')
            ->get(['id', 'nomor', 'nama']);

        $terlambat = Antrian::where('status', 'terlambat')
            ->orderBy('nomor')
            ->get(['id', 'nomor', 'nama']);

        return [
            'current' => $current ? [
                'id'      => $current->id,
                'nomor'   => $current->nomor,
                'nama'    => $current->nama,
                'ruangan' => $current->ruangan,
                'ts'      => optional($current->called_at)->timestamp,
            ] : null,
            'waiting'   => $waiting,
            'terlambat' => $terlambat,
            'stats'     => [
                'menunggu'  => $waiting->count(),
                'terlambat' => $terlambat->count(),
            ],
        ];
    }

    // ============================================================
    // ADMIN: PANGGIL BERIKUTNYA
    // ============================================================
    public function panggilBerikutnya(Request $request)
    {
        // Antrian "dipanggil" sebelumnya → selesai
        Antrian::where('status', 'dipanggil')->update(['status' => 'selesai']);

        $next = Antrian::where('status', 'menunggu')
            ->orderBy('nomor')
            ->first();

        if (!$next) {
            return response()->json([
                'status'  => 'empty',
                'message' => 'Tidak ada antrian menunggu.',
            ]);
        }

        $next->update([
            'status'    => 'dipanggil',
            'ruangan'   => $request->input('ruangan'),
            'called_at' => now(),
        ]);

        return response()->json(['status' => 'ok', 'data' => $next]);
    }

    // ============================================================
    // ADMIN: PANGGIL ULANG (nomor tertentu / yang terlambat)
    // ============================================================
    public function panggilUlang(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);

        // Yang sedang dipanggil → selesai
        Antrian::where('status', 'dipanggil')
            ->where('id', '!=', $id)
            ->update(['status' => 'selesai']);

        $antrian->update([
            'status'    => 'dipanggil',
            'ruangan'   => $request->input('ruangan', $antrian->ruangan),
            'called_at' => now(),
        ]);

        return response()->json(['status' => 'ok', 'data' => $antrian]);
    }

    // ============================================================
    // ADMIN: TANDAI TERLAMBAT (tamu tidak hadir)
    // ============================================================
    public function tandaiTerlambat($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update(['status' => 'terlambat']);

        return response()->json(['status' => 'ok']);
    }

    // ============================================================
    // ADMIN: TANDAI SELESAI
    // ============================================================
    public function selesai($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update(['status' => 'selesai']);

        return response()->json(['status' => 'ok']);
    }

    // ============================================================
    // ADMIN: RESET SEMUA ANTRIAN (untuk demo/testing)
    // ============================================================
    public function reset()
    {
        Antrian::truncate();
        return response()->json(['status' => 'ok']);
    }
}
