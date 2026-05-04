@extends('layouts.vendor')

@section('title', 'Daftar Menu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Daftar Menu</h4>
    <a href="{{ route('vendor.menu.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Menu
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th width="30%">Nama Menu</th>
                        <th width="20%">Harga</th>
                        <th width="30%">Deskripsi</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $i => $menu)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $menu->nama_menu }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td class="text-muted">{{ $menu->deskripsi ?? '-' }}</td>
                        <td>
                            <a href="{{ route('vendor.menu.edit', $menu->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            {{-- Hidden delete form (di luar tombol, pakai JS trigger) --}}
                            <form id="deleteForm{{ $menu->id }}" action="{{ route('vendor.menu.destroy', $menu->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                            <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Yakin hapus menu ini?')) document.getElementById('deleteForm{{ $menu->id }}').submit();">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada menu. Silakan tambah menu baru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
