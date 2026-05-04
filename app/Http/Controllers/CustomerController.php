<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Data Customer — tabel semua customer
     */
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customer-manage.index', compact('customers'));
    }

    /**
     * Tambah Customer 1 — foto disimpan sebagai blob (base64) di database
     */
    public function create1()
    {
        return view('customer-manage.create1');
    }

    public function store1(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'email' => 'nullable|email',
            'telepon' => 'nullable|max:20',
            'foto_blob' => 'required', // base64 string dari kamera
        ]);

        Customer::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'foto_blob' => $request->foto_blob,
        ]);

        return redirect()->route('customer-manage.index')
            ->with('success', 'Customer berhasil ditambahkan (foto disimpan sebagai blob)');
    }

    /**
     * Tambah Customer 2 — foto disimpan sebagai file, path disimpan di database
     */
    public function create2()
    {
        return view('customer-manage.create2');
    }

    public function store2(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'email' => 'nullable|email',
            'telepon' => 'nullable|max:20',
            'foto_file' => 'required', // base64 string dari kamera
        ]);

        // Decode base64 dan simpan sebagai file gambar
        $base64 = $request->foto_file;
        // Hapus prefix data:image/png;base64, jika ada
        $base64Clean = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($base64Clean);

        // Generate filename unik
        $filename = 'customer_' . time() . '_' . uniqid() . '.png';
        $path = 'uploads/customers/' . $filename;

        // Buat folder kalau belum ada
        if (!file_exists(public_path('uploads/customers'))) {
            mkdir(public_path('uploads/customers'), 0755, true);
        }

        // Simpan file
        file_put_contents(public_path($path), $imageData);

        Customer::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'foto_path' => $path,
        ]);

        return redirect()->route('customer-manage.index')
            ->with('success', 'Customer berhasil ditambahkan (foto disimpan sebagai file)');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        // Hapus file foto kalau ada
        if ($customer->foto_path && file_exists(public_path($customer->foto_path))) {
            unlink(public_path($customer->foto_path));
        }

        $customer->delete();

        return redirect()->route('customer-manage.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
