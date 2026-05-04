@extends('layouts.vendor')

@section('title', 'Pesanan Lunas')

@section('content')
<h4 class="fw-bold mb-4">Pesanan Lunas</h4>

@if($pesanans->isEmpty())
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            <i class="bi bi-inbox" style="font-size:48px;"></i>
            <p class="mt-3 mb-0">Belum ada pesanan yang lunas.</p>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Kode Pesanan</th>
                            <th>Guest</th>
                            <th class="text-end">Total</th>
                            <th>Dibayar</th>
                            <th width="10%">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanans as $i => $p)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $p->kode_pesanan }}</td>
                            <td>{{ $p->guest_name }}</td>
                            <td class="text-end">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                            <td>{{ $p->paid_at ? $p->paid_at->format('d M Y, H:i') : '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $p->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modals detail --}}
    @foreach($pesanans as $p)
    <div class="modal fade" id="modalDetail{{ $p->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Detail {{ $p->kode_pesanan }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless table-sm mb-3">
                        <tr>
                            <td class="text-muted" width="40%">Guest</td>
                            <td class="fw-semibold">{{ $p->guest_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibayar pada</td>
                            <td class="fw-semibold">{{ $p->paid_at ? $p->paid_at->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                    </table>

                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($p->details as $d)
                            <tr>
                                <td>{{ $d->nama_menu }}</td>
                                <td class="text-center">{{ $d->jumlah }}</td>
                                <td class="text-end">Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="3">Total</th>
                                <th class="text-end">Rp {{ number_format($p->total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
