<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        $totalMenu = $vendor->menus()->count();
        $totalPesananPending = $vendor->pesanans()->where('status_bayar', 'pending')->count();
        $totalPesananLunas = $vendor->pesanans()->where('status_bayar', 'lunas')->count();

        return view('vendor.dashboard', compact('vendor', 'totalMenu', 'totalPesananPending', 'totalPesananLunas'));
    }

    public function pesananLunas()
    {
        $pesanans = Pesanan::with('details')
            ->where('vendor_id', Auth::guard('vendor')->id())
            ->where('status_bayar', 'lunas')
            ->orderBy('paid_at', 'desc')
            ->get();

        return view('vendor.pesanan-lunas', compact('pesanans'));
    }

    // =============================================
    // Week 8 - QR Code Scanner
    // =============================================

    /**
     * Halaman scan QR Code customer
     */
    public function scanQr()
    {
        return view('vendor.scan-qr');
    }

    /**
     * AJAX: cari pesanan berdasarkan kode_pesanan dari QR Code
     */
    public function findByQr(Request $request)
    {
        $qrText   = trim($request->input('qr_text', ''));
        $vendorId = Auth::guard('vendor')->id();

        if (empty($qrText)) {
            return response()->json(['status' => 'error', 'message' => 'QR text kosong'], 422);
        }

        // QR berisi kode_pesanan langsung (format: PSN-YYYYMMDD-XXXX)
        $pesanan = Pesanan::with('details')
            ->where('kode_pesanan', $qrText)
            ->where('vendor_id', $vendorId)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pesanan tidak ditemukan atau bukan milik vendor ini',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'kode_pesanan'  => $pesanan->kode_pesanan,
                'guest_name'    => $pesanan->guest_name,
                'status_bayar'  => $pesanan->status_bayar,
                'total_format'  => 'Rp ' . number_format($pesanan->total, 0, ',', '.'),
                'paid_at'       => $pesanan->paid_at
                    ? $pesanan->paid_at->format('d M Y, H:i')
                    : null,
                'details'       => $pesanan->details->map(function ($d) {
                    return [
                        'nama_menu'      => $d->nama_menu,
                        'jumlah'         => $d->jumlah,
                        'harga_format'   => 'Rp ' . number_format($d->harga, 0, ',', '.'),
                        'subtotal_format'=> 'Rp ' . number_format($d->subtotal, 0, ',', '.'),
                    ];
                }),
            ]
        ]);
    }
}
