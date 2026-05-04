<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
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
}
