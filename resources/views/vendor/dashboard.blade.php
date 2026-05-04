@extends('layouts.vendor')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold">Dashboard</h4>
    <p class="text-muted mb-0">Selamat datang, <strong>{{ $vendor->nama_vendor }}</strong>!</p>
</div>

<div class="row g-3">
    {{-- Card: Total Menu --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:56px; height:56px; background:#eff6ff;">
                    <i class="bi bi-journal-text" style="font-size:24px; color:#2563eb;"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Menu</div>
                    <div class="fw-bold fs-4">{{ $totalMenu }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Pesanan Pending --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:56px; height:56px; background:#fefce8;">
                    <i class="bi bi-hourglass-split" style="font-size:24px; color:#ca8a04;"></i>
                </div>
                <div>
                    <div class="text-muted small">Pesanan Pending</div>
                    <div class="fw-bold fs-4">{{ $totalPesananPending }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card: Pesanan Lunas --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:56px; height:56px; background:#f0fdf4;">
                    <i class="bi bi-bag-check" style="font-size:24px; color:#16a34a;"></i>
                </div>
                <div>
                    <div class="text-muted small">Pesanan Lunas</div>
                    <div class="fw-bold fs-4">{{ $totalPesananLunas }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0">Daftar Menu Anda</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Menu</th>
                        <th width="20%">Harga</th>
                        <th width="30%">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendor->menus as $i => $menu)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td class="fw-semibold">{{ $menu->nama_menu }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td class="text-muted">{{ $menu->deskripsi ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada menu.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
