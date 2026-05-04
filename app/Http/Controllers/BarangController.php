<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'required|integer'
        ]);

        DB::statement("INSERT INTO barangs (nama, harga) VALUES (?, ?)", [
            $request->nama,
            $request->harga,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'harga' => 'required|integer'
        ]);

        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
        ]);

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', 'Data berhasil dihapus');
    }


    public function cetak(Request $request)
    {
        $request->validate([
            'barang_ids' => 'required|array',
            'x' => 'required|integer|min:1|max:5',
            'y' => 'required|integer|min:1|max:8',
        ]);

        $barangs = Barang::whereIn('id_barang', $request->barang_ids)->get();

        $startIndex = (($request->y - 1) * 5) + ($request->x - 1);

        $labels = array_fill(0, 40, null);

        // Generate barcode untuk setiap barang
        $generator = new BarcodeGeneratorPNG();
        $barcodes = [];

        foreach ($barangs as $i => $barang) {
            if (($startIndex + $i) < 40) {
                $labels[$startIndex + $i] = $barang;

                // Generate barcode dari id_barang, simpan sebagai base64
                $barcodeData = $generator->getBarcode($barang->id_barang, $generator::TYPE_CODE_128, 2, 30);
                $barcodes[$barang->id_barang] = base64_encode($barcodeData);
            }
        }

        $pdf = Pdf::loadView('barang.pdf', compact('labels', 'barcodes'))
            ->setPaper([0, 0, 609.45, 836.22]); // Tom & Jerry 108: 21.5cm x 29.5cm

        return $pdf->stream('tag-harga.pdf');
    }
}