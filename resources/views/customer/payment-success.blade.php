<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Kantin Online</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        .navbar-customer { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .success-icon {
            width: 80px; height: 80px; border-radius: 50%;
            background: #f0fdf4; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
        }
        .success-icon i { font-size: 40px; color: #16a34a; }
        .badge-status { font-size: 14px; padding: 6px 14px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-customer mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('customer.order') }}">
                <i class="bi bi-shop"></i> Kantin Online
            </a>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body text-center py-5 px-4">
                        <div class="success-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>

                        <h4 class="fw-bold mb-1">Pesanan Dibuat!</h4>
                        <p class="text-muted mb-4">Terima kasih atas pesanan Anda</p>

                        {{-- Status badge (akan di-update via polling) --}}
                        <div class="mb-4">
                            <span id="badgeStatus" class="badge badge-status
                                @if($pesanan->status_bayar === 'lunas') bg-success
                                @elseif($pesanan->status_bayar === 'pending') bg-warning text-dark
                                @else bg-danger
                                @endif
                            ">
                                @if($pesanan->status_bayar === 'lunas')
                                    LUNAS
                                @elseif($pesanan->status_bayar === 'pending')
                                    MENUNGGU PEMBAYARAN
                                @else
                                    {{ strtoupper($pesanan->status_bayar) }}
                                @endif
                            </span>
                        </div>

                        {{-- QR Code (muncul setelah lunas) --}}
                        <div id="qrCodeSection" @if(!isset($qrCode) || !$qrCode) style="display:none;" @endif>
                            @if(isset($qrCode) && $qrCode)
                                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code Pesanan" style="width:180px; height:180px;" class="mb-2">
                            @endif
                            <p class="text-muted small mb-0">Tunjukkan QR Code ini ke vendor saat mengambil pesanan</p>
                        </div>
                    </div>

                    {{-- Detail pesanan --}}
                    <div class="card-body border-top px-4">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <td class="text-muted" width="40%">Kode Pesanan</td>
                                <td class="fw-semibold">{{ $pesanan->kode_pesanan }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Nama Guest</td>
                                <td class="fw-semibold">{{ $pesanan->guest_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Vendor</td>
                                <td class="fw-semibold">{{ $pesanan->vendor->nama_vendor }}</td>
                            </tr>
                            @if($pesanan->paid_at)
                            <tr>
                                <td class="text-muted">Dibayar pada</td>
                                <td class="fw-semibold" id="paidAtText">{{ $pesanan->paid_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @else
                            <tr id="rowPaidAt" style="display:none;">
                                <td class="text-muted">Dibayar pada</td>
                                <td class="fw-semibold" id="paidAtText">-</td>
                            </tr>
                            @endif
                        </table>
                    </div>

                    {{-- Item pesanan --}}
                    <div class="card-body border-top px-4">
                        <h6 class="fw-bold mb-3">Detail Item</h6>
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->details as $d)
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
                                    <th class="text-end">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Simpan Link QR Code --}}
                    <div class="card-body border-top">
                        <div class="alert alert-info mb-0 py-2 px-3 d-flex align-items-start gap-2" style="font-size:13px;">
                            <i class="bi bi-bookmark-heart-fill fs-5 text-primary flex-shrink-0 mt-1"></i>
                            <div>
                                <strong>Simpan halaman ini!</strong><br>
                                Kamu bisa akses QR Code kapanpun lewat link ini:<br>
                                <div class="input-group input-group-sm mt-1">
                                    <input type="text" class="form-control font-monospace" id="receiptUrl"
                                        value="{{ url('/order/payment-success/' . $pesanan->kode_pesanan) }}" readonly>
                                    <button class="btn btn-outline-primary" id="btnCopyLink" type="button">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body border-top text-center">
                        @if($pesanan->status_bayar === 'pending')
                            <button type="button" id="btnSimulate" class="btn btn-warning mb-2 w-100">
                                <i class="bi bi-lightning-charge me-1"></i> Simulate Bayar (Sandbox Testing)
                            </button>
                        @endif
                        <a href="{{ route('customer.order') }}" class="btn btn-primary">
                            <i class="bi bi-cart-plus me-1"></i> Pesan Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {

        // ============ COPY LINK ============
        $('#btnCopyLink').on('click', function() {
            const url = document.getElementById('receiptUrl');
            url.select();
            url.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(url.value).then(function() {
                $('#btnCopyLink').html('<i class="bi bi-check2"></i>').removeClass('btn-outline-primary').addClass('btn-success');
                setTimeout(() => {
                    $('#btnCopyLink').html('<i class="bi bi-clipboard"></i>').removeClass('btn-success').addClass('btn-outline-primary');
                }, 2000);
            }).catch(function() {
                document.execCommand('copy');
            });
        });
        const kodePesanan = '{{ $pesanan->kode_pesanan }}';
        let currentStatus = '{{ $pesanan->status_bayar }}';

        // ============ SIMULATE BAYAR (Sandbox only) ============
        $('#btnSimulate').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Memproses...');

            $.ajax({
                method: 'POST',
                url: '/order/simulate-pay/' + kodePesanan,
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.status === 'success') {
                        // Update badge langsung
                        $('#badgeStatus')
                            .removeClass('bg-warning text-dark')
                            .addClass('bg-success')
                            .text('LUNAS');

                        // Sembunyikan tombol simulate
                        $btn.remove();

                        // Tampilkan paid_at
                        const now = new Date();
                        const formatted = now.toLocaleDateString('id-ID', {
                            day: 'numeric', month: 'short', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        });
                        $('#paidAtText').text(formatted);
                        $('#rowPaidAt').show();

                        Swal.fire({
                            title: 'Pembayaran Berhasil!',
                            text: 'Status pesanan berhasil diubah menjadi LUNAS',
                            icon: 'success'
                        }).then(() => {
                            // Reload halaman supaya QR Code ter-render dari server
                            window.location.reload();
                        });

                        currentStatus = 'lunas';
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal simulate pembayaran', 'error');
                    $btn.prop('disabled', false).html('<i class="bi bi-lightning-charge me-1"></i> Simulate Bayar (Sandbox Testing)');
                }
            });
        });

        // Polling status setiap 3 detik sampai status berubah jadi 'lunas'
        if (currentStatus !== 'lunas') {
            const poller = setInterval(function() {
                $.ajax({
                    method: 'GET',
                    url: '/order/check-status/' + kodePesanan,
                    success: function(response) {
                        if (response.status === 'success') {
                            const statusBayar = response.data.status_bayar;

                            if (statusBayar === 'lunas') {
                                clearInterval(poller);
                                // Reload supaya QR Code muncul
                                window.location.reload();
                            } else if (statusBayar === 'gagal') {
                                clearInterval(poller);
                                $('#badgeStatus')
                                    .removeClass('bg-warning text-dark bg-success')
                                    .addClass('bg-danger')
                                    .text('GAGAL');
                            }
                        }
                    }
                });
            }, 3000);
        }
    });
    </script>
</body>
</html>
