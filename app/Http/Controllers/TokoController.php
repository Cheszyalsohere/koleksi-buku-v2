<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class TokoController extends Controller
{
    public function index()
    {
        $tokos = Toko::latest()->get();
        return view('toko.index', compact('tokos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy'  => 'required|numeric|min:0',
        ]);

        // Simpan sementara, barcode diisi setelah dapat ID
        $toko = Toko::create([
            'barcode'   => 'TEMP-' . time(),
            'nama_toko' => $request->nama_toko,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy,
        ]);

        // Generate barcode dari ID
        $toko->barcode = 'TOKO-' . str_pad($toko->id, 4, '0', STR_PAD_LEFT);
        $toko->save();

        return redirect()->route('toko.index')
            ->with('success', 'Toko "' . $toko->nama_toko . '" berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $toko = Toko::findOrFail($id);
        $nama = $toko->nama_toko;
        $toko->delete();

        return redirect()->route('toko.index')
            ->with('success', 'Toko "' . $nama . '" berhasil dihapus!');
    }

    public function cetakBarcode($id)
    {
        $toko    = Toko::findOrFail($id);
        $gen     = new BarcodeGeneratorPNG();
        $barcode = base64_encode(
            $gen->getBarcode($toko->barcode, $gen::TYPE_CODE_128, 3, 80)
        );

        return view('toko.cetak-barcode', compact('toko', 'barcode'));
    }

    public function findByBarcode(Request $request)
    {
        $toko = Toko::where('barcode', $request->barcode)->first();

        if (!$toko) {
            return response()->json([
                'status'  => 'not_found',
                'message' => 'Toko tidak ditemukan',
            ]);
        }

        return response()->json([
            'status' => 'found',
            'data'   => [
                'id'        => $toko->id,
                'nama_toko' => $toko->nama_toko,
                'barcode'   => $toko->barcode,
                'latitude'  => (float) $toko->latitude,
                'longitude' => (float) $toko->longitude,
                'accuracy'  => (float) $toko->accuracy,
            ],
        ]);
    }
}
