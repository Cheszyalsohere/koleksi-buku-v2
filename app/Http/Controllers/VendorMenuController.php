<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorMenuController extends Controller
{
    private function vendor()
    {
        return Auth::guard('vendor')->user();
    }

    public function index()
    {
        $menus = $this->vendor()->menus()->latest()->get();
        return view('vendor.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('vendor.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|max:255',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable',
        ]);

        $this->vendor()->menus()->create([
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('vendor.menu.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $menu = $this->vendor()->menus()->findOrFail($id);

        // Pastikan menu milik vendor yang login
        if ($menu->vendor_id != $this->vendor()->id) {
            abort(403);
        }

        return view('vendor.menu.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required|max:255',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable',
        ]);

        $menu = $this->vendor()->menus()->findOrFail($id);

        if ($menu->vendor_id != $this->vendor()->id) {
            abort(403);
        }

        $menu->update([
            'nama_menu' => $request->nama_menu,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('vendor.menu.index')
            ->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy($id)
    {
        $menu = $this->vendor()->menus()->findOrFail($id);

        if ($menu->vendor_id != $this->vendor()->id) {
            abort(403);
        }

        $menu->delete();

        return redirect()->route('vendor.menu.index')
            ->with('success', 'Menu berhasil dihapus');
    }
}
