<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class NfcAbsensiController extends Controller
{
    // =============================================
    // SCANNER (no auth — diakses dari HP via ngrok)
    // =============================================

    public function scanner()
    {
        return view('nfc.scanner');
    }

    /**
     * AJAX: ambil daftar mahasiswa (untuk dropdown registrasi kartu)
     */
    public function getMahasiswas()
    {
        $mahasiswas = Mahasiswa::select('id', 'nim', 'nama')
            ->orderBy('nama')->get();
        return response()->json($mahasiswas);
    }

    /**
     * POST: catat absensi dari scan NFC
     */
    public function scan(Request $request)
    {
        $serial     = trim($request->input('serial_number', ''));
        $mataKuliah = trim($request->input('mata_kuliah', ''));

        if (empty($serial)) {
            return response()->json(['status' => 'error', 'message' => 'Serial number kosong'], 422);
        }
        if (empty($mataKuliah)) {
            return response()->json(['status' => 'error', 'message' => 'Pilih mata kuliah terlebih dahulu'], 422);
        }

        // Cari mahasiswa berdasarkan serial NFC
        $mahasiswa = Mahasiswa::where('nfc_serial', $serial)->first();
        if (!$mahasiswa) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Kartu NFC belum terdaftar',
                'serial'  => $serial,
            ], 404);
        }

        // Cek duplikat absensi hari ini untuk mata kuliah yang sama
        $today    = now()->toDateString();
        $existing = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->where('mata_kuliah', $mataKuliah)
            ->where('tanggal', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'status'  => 'duplicate',
                'message' => 'Mahasiswa sudah absen hari ini',
                'data'    => [
                    'nama'       => $mahasiswa->nama,
                    'nim'        => $mahasiswa->nim,
                    'waktu_scan' => $existing->waktu_scan,
                    'status'     => $existing->status,
                ]
            ]);
        }

        // Tentukan status (terlambat jika > 15 menit dari jam bulat terdekat)
        $menit  = (int) now()->format('i');
        $status = $menit > 15 ? 'terlambat' : 'hadir';

        $absensi = Absensi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'mata_kuliah'  => $mataKuliah,
            'tanggal'      => $today,
            'waktu_scan'   => now()->format('H:i:s'),
            'status'       => $status,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Absensi berhasil dicatat',
            'data'    => [
                'nama'       => $mahasiswa->nama,
                'nim'        => $mahasiswa->nim,
                'prodi'      => $mahasiswa->prodi,
                'waktu_scan' => $absensi->waktu_scan,
                'status'     => $absensi->status,
            ]
        ]);
    }

    /**
     * POST: daftarkan kartu NFC ke mahasiswa
     */
    public function registerKartu(Request $request)
    {
        $serial      = trim($request->input('serial_number', ''));
        $mahasiswaId = $request->input('mahasiswa_id');

        if (empty($serial)) {
            return response()->json(['status' => 'error', 'message' => 'Serial number kosong'], 422);
        }

        // Cek apakah serial sudah dipakai mahasiswa lain
        $existing = Mahasiswa::where('nfc_serial', $serial)
            ->where('id', '!=', $mahasiswaId)
            ->first();
        if ($existing) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kartu sudah terdaftar atas nama ' . $existing->nama,
            ], 409);
        }

        $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
        $mahasiswa->update(['nfc_serial' => $serial]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kartu berhasil didaftarkan',
            'data'    => [
                'nama'   => $mahasiswa->nama,
                'nim'    => $mahasiswa->nim,
                'serial' => $serial,
            ]
        ]);
    }

    // =============================================
    // ADMIN — Data Mahasiswa
    // =============================================

    public function mahasiswa()
    {
        $mahasiswas = Mahasiswa::withCount('absensis')->orderBy('nama')->get();
        return view('nfc.mahasiswa', compact('mahasiswas'));
    }

    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'nim'   => 'required|unique:mahasiswas,nim',
            'nama'  => 'required',
            'prodi' => 'nullable',
            'email' => 'nullable|email',
        ]);

        Mahasiswa::create($request->only('nim', 'nama', 'email', 'prodi'));

        return redirect()->route('nfc.mahasiswa')
            ->with('success', 'Mahasiswa berhasil ditambahkan');
    }

    public function destroyMahasiswa($id)
    {
        Mahasiswa::findOrFail($id)->delete();
        return redirect()->route('nfc.mahasiswa')
            ->with('success', 'Mahasiswa berhasil dihapus');
    }

    public function unlinkKartu($id)
    {
        Mahasiswa::findOrFail($id)->update(['nfc_serial' => null]);
        return redirect()->route('nfc.mahasiswa')
            ->with('success', 'Kartu NFC berhasil dilepas');
    }

    // =============================================
    // ADMIN — Riwayat Absensi
    // =============================================

    public function riwayat(Request $request)
    {
        $query = Absensi::with('mahasiswa')->latest('tanggal');

        if ($request->filled('mata_kuliah')) {
            $query->where('mata_kuliah', $request->mata_kuliah);
        }
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }
        if ($request->filled('nim')) {
            $query->whereHas('mahasiswa', fn($q) =>
                $q->where('nim', 'like', '%' . $request->nim . '%')
            );
        }

        $absensis       = $query->get();
        $mataKuliahList = Absensi::distinct()->orderBy('mata_kuliah')->pluck('mata_kuliah');

        return view('nfc.riwayat', compact('absensis', 'mataKuliahList'));
    }

    public function destroyAbsensi($id)
    {
        Absensi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data absensi dihapus');
    }
}
