<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pemesanan - Kantin Online</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    {{-- Midtrans Snap JS --}}
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        .navbar-customer { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .card-header { border-radius: 12px 12px 0 0 !important; }
        .table th { font-size: 13px; }
        .table td { font-size: 13.5px; vertical-align: middle; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark navbar-customer mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('customer.order') }}">
                <i class="bi bi-shop"></i> Kantin Online
            </a>
            <span class="text-white-50 small">
                <i class="bi bi-cart3"></i> Pemesanan
            </span>
        </div>
    </nav>

    <div class="container pb-5">
        <h4 class="fw-bold mb-4">Pemesanan Kantin Online</h4>

        <div class="row g-4">
            {{-- KIRI: Form pilih vendor + menu --}}
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>Pilih Menu</h5>
                    </div>
                    <div class="card-body">

                        {{-- Pilih Vendor --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Vendor</label>
                            <select class="form-select" id="selectVendor" required>
                                <option value="0">-- Pilih Vendor --</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->nama_vendor }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pilih Menu (cascading dari vendor) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Menu</label>
                            <select class="form-select" id="selectMenu" required disabled>
                                <option value="0">-- Pilih Menu --</option>
                            </select>
                        </div>

                        <form id="formTambahItem">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Menu</label>
                                    <input type="text" class="form-control" id="namaMenu" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Harga</label>
                                    <input type="text" class="form-control" id="hargaMenu" readonly>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jumlah</label>
                                <input type="number" class="form-control" id="jumlahMenu" min="1" value="1" required>
                            </div>
                        </form>

                        <button type="button" class="btn btn-success w-100" id="btnTambahKeranjang" disabled>
                            <i class="bi bi-cart-plus me-1"></i> Tambahkan ke Keranjang
                        </button>
                    </div>
                </div>
            </div>

            {{-- KANAN: Keranjang --}}
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0"><i class="bi bi-cart3 me-2"></i>Keranjang</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0" id="tabelKeranjang">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                        <th width="40"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- diisi via JS --}}
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="2">Total</th>
                                        <th class="text-end" id="totalKeranjang">Rp 0</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <button type="button" class="btn btn-primary w-100 mt-3" id="btnCheckout" disabled>
                            <i class="bi bi-bag-check me-1"></i> Checkout & Bayar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {

        let keranjang = [];
        let currentVendorId = 0;

        function formatRupiah(angka) {
            return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
        }

        // ============ PILIH VENDOR → load menu ============
        $('#selectVendor').on('change', function() {
            const vendorId = $(this).val();

            $('#selectMenu').html('<option value="0">-- Pilih Menu --</option>').prop('disabled', true);
            resetFormMenu();

            if (keranjang.length > 0 && currentVendorId != vendorId) {
                Swal.fire({
                    title: 'Ganti Vendor?',
                    text: 'Mengganti vendor akan mengosongkan keranjang. Lanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, ganti',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        keranjang = [];
                        renderKeranjang();
                        currentVendorId = vendorId;
                        loadMenu(vendorId);
                    } else {
                        $('#selectVendor').val(currentVendorId);
                    }
                });
                return;
            }

            currentVendorId = vendorId;
            if (vendorId == 0) return;
            loadMenu(vendorId);
        });

        function loadMenu(vendorId) {
            $.ajax({
                method: 'GET',
                url: '/order/menus/' + vendorId,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#selectMenu').prop('disabled', false);
                        response.data.forEach(function(menu) {
                            $('#selectMenu').append(new Option(menu.nama_menu + ' — ' + formatRupiah(menu.harga), menu.id));
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Gagal memuat menu', 'error');
                }
            });
        }

        // ============ PILIH MENU → auto-fill ============
        $('#selectMenu').on('change', function() {
            const menuId = $(this).val();
            if (menuId == 0) { resetFormMenu(); return; }

            $.ajax({
                method: 'GET',
                url: '/order/menu-detail/' + menuId,
                success: function(response) {
                    if (response.status === 'success') {
                        $('#namaMenu').val(response.data.nama_menu);
                        $('#hargaMenu').val(formatRupiah(response.data.harga));
                        $('#hargaMenu').data('harga-asli', response.data.harga);
                        $('#jumlahMenu').val(1);
                        $('#btnTambahKeranjang').prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    console.log('Error load detail menu:', xhr);
                }
            });
        });

        function resetFormMenu() {
            $('#namaMenu').val('');
            $('#hargaMenu').val('').removeData('harga-asli');
            $('#jumlahMenu').val(1);
            $('#btnTambahKeranjang').prop('disabled', true);
        }

        // ============ TAMBAH KE KERANJANG ============
        $('#btnTambahKeranjang').on('click', function() {
            var form = document.getElementById('formTambahItem');
            if (!form.checkValidity()) { form.reportValidity(); return; }

            const menuId = parseInt($('#selectMenu').val());
            const namaMenu = $('#namaMenu').val();
            const harga = parseInt($('#hargaMenu').data('harga-asli'));
            const jumlah = parseInt($('#jumlahMenu').val());

            if (jumlah < 1) { Swal.fire('Perhatian', 'Jumlah minimal 1', 'warning'); return; }

            const existing = keranjang.find(item => item.menu_id === menuId);
            if (existing) {
                existing.jumlah += jumlah;
                existing.subtotal = existing.harga * existing.jumlah;
            } else {
                keranjang.push({
                    menu_id: menuId,
                    nama_menu: namaMenu,
                    harga: harga,
                    jumlah: jumlah,
                    subtotal: harga * jumlah
                });
            }

            renderKeranjang();
            $('#selectMenu').val(0);
            resetFormMenu();
        });

        // ============ RENDER KERANJANG ============
        function renderKeranjang() {
            const $tbody = $('#tabelKeranjang tbody');
            $tbody.empty();

            if (keranjang.length === 0) {
                $tbody.append('<tr><td colspan="4" class="text-center text-muted py-3">Keranjang kosong</td></tr>');
                $('#totalKeranjang').text('Rp 0');
                $('#btnCheckout').prop('disabled', true);
                return;
            }

            let total = 0;
            keranjang.forEach(function(item, index) {
                total += item.subtotal;
                $tbody.append(`
                    <tr>
                        <td>${item.nama_menu}</td>
                        <td class="text-center">${item.jumlah}</td>
                        <td class="text-end">${formatRupiah(item.subtotal)}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-hapus" data-index="${index}" title="Hapus">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </td>
                    </tr>
                `);
            });

            $('#totalKeranjang').text(formatRupiah(total));
            $('#btnCheckout').prop('disabled', false);
        }

        // Hapus item (event delegation)
        $('#tabelKeranjang tbody').on('click', '.btn-hapus', function() {
            keranjang.splice($(this).data('index'), 1);
            renderKeranjang();
        });

        // ============ CHECKOUT + MIDTRANS SNAP ============
        $('#btnCheckout').on('click', function() {
            if (keranjang.length === 0 || currentVendorId == 0) return;

            var $btn = $(this);
            var originalText = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

            $.ajax({
                method: 'POST',
                url: '/order/checkout',
                data: {
                    _token: '{{ csrf_token() }}',
                    vendor_id: currentVendorId,
                    items: keranjang.map(item => ({
                        menu_id: item.menu_id,
                        jumlah: item.jumlah
                    }))
                },
                success: function(response) {
                    if (response.status === 'success') {
                        const kodePesanan = response.data.kode_pesanan;
                        const snapToken = response.data.snap_token;

                        // Trigger Midtrans Snap popup
                        snap.pay(snapToken, {
                            onSuccess: function(result) {
                                console.log('Payment success:', result);
                                Swal.fire({
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Terima kasih, pesanan Anda akan segera diproses.',
                                    icon: 'success'
                                }).then(() => {
                                    window.location.href = '/order/payment-success/' + kodePesanan;
                                });
                            },
                            onPending: function(result) {
                                console.log('Payment pending:', result);
                                window.location.href = '/order/payment-success/' + kodePesanan;
                            },
                            onError: function(result) {
                                console.log('Payment error:', result);
                                Swal.fire('Error', 'Pembayaran gagal', 'error');
                                $btn.prop('disabled', false);
                                $btn.html(originalText);
                            },
                            onClose: function() {
                                // Redirect ke halaman payment-success (ada tombol simulate bayar)
                                window.location.href = '/order/payment-success/' + kodePesanan;
                                $btn.prop('disabled', false);
                                $btn.html(originalText);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    let msg = 'Gagal memproses pesanan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', msg, 'error');
                    $btn.prop('disabled', false);
                    $btn.html(originalText);
                }
            });
        });

        // Init
        renderKeranjang();
    });
    </script>
</body>
</html>
