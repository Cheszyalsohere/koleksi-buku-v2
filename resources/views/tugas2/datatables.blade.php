@extends('layouts.app')

@section('title', 'Tugas 2 - DataTables')

@section('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    #tabelBarang tbody tr {
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    #tabelBarang tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.08) !important;
    }
</style>
@endsection

@section('content')
<div class="row">

    {{-- Card Form Tambah Barang --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Form Tambah Barang</h4>
                <p class="text-muted mb-3">Data disimpan via JavaScript (tanpa database)</p>

                <form id="formBarang">
                    <div class="form-group mb-3">
                        <label class="mb-1">ID Barang</label>
                        <input type="text" id="idBarang" class="form-control" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label class="mb-1">Nama Barang</label>
                        <input type="text" id="namaBarang" class="form-control" placeholder="Masukkan nama barang" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="mb-1">Harga</label>
                        <input type="number" id="harga" class="form-control" placeholder="Masukkan harga" required min="0">
                    </div>
                </form>

                <button type="button" class="btn btn-success btn-block" id="btnTambah">
                    <span class="btn-text">Tambah</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Card Daftar Barang --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Barang</h4>
                <p class="text-muted mb-3">Dengan fitur DataTables (search, pagination, sorting) — klik row untuk edit/hapus</p>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tabelBarang" style="width:100%">
                        <thead class="table-dark">
                            <tr>
                                <th width="10%">ID</th>
                                <th width="40%">Nama</th>
                                <th width="30%">Harga</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data ditambah via JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal Edit / Hapus --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit / Hapus Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEdit">
                    <div class="mb-3">
                        <label class="form-label">ID Barang</label>
                        <input type="text" class="form-control" id="editId" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="editNama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" id="editHarga" required min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnHapus">Hapus</button>
                <button type="button" class="btn btn-primary" id="btnUbah">Ubah</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-page')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    let counter = 1;

    $(document).ready(function() {
        // Inisialisasi DataTables
        let dataTable = $('#tabelBarang').DataTable({
            language: {
                emptyTable: "Belum ada data. Silakan tambah barang melalui form di samping.",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            },
            columnDefs: [
                { orderable: false, targets: 3 }
            ]
        });

        // Set ID barang awal
        $('#idBarang').val(counter);

        // =====================
        // TAMBAH BARANG
        // =====================
        $('#btnTambah').on('click', function() {
            const form = document.getElementById('formBarang');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true);
            $btn.find('.btn-text').text('Memproses...');
            $btn.find('.spinner-border').removeClass('d-none');

            setTimeout(function() {
                const id = $('#idBarang').val();
                const nama = $('#namaBarang').val();
                const harga = $('#harga').val();

                dataTable.row.add([
                    id,
                    nama,
                    'Rp ' + parseInt(harga).toLocaleString('id-ID'),
                    ''
                ]).draw();

                $('#namaBarang').val('');
                $('#harga').val('');
                counter++;
                $('#idBarang').val(counter);

                $btn.prop('disabled', false);
                $btn.html(originalText);
                $('#namaBarang').focus();
            }, 500);
        });

        // =====================
        // EDIT & HAPUS VIA MODAL
        // =====================
        let selectedRow = null;
        const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));

        // Klik row → buka modal & isi data (event delegation via DataTables)
        $('#tabelBarang tbody').on('click', 'tr', function() {
            selectedRow = dataTable.row(this);
            const data = selectedRow.data();

            // Cegah klik row kosong (empty table message)
            if (!data) return;

            $('#editId').val(data[0]);
            $('#editNama').val(data[1]);
            const hargaText = String(data[2]).replace('Rp ', '').replace(/\./g, '');
            $('#editHarga').val(hargaText);

            modalEdit.show();
        });

        // Tombol Ubah → validasi + spinner + update via DataTables API
        $('#btnUbah').on('click', function() {
            const form = document.getElementById('formEdit');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

            setTimeout(function() {
                const id = $('#editId').val();
                const namaBaru = $('#editNama').val();
                const hargaBaru = 'Rp ' + parseInt($('#editHarga').val()).toLocaleString('id-ID');

                // Update via DataTables API
                selectedRow.data([id, namaBaru, hargaBaru, '']).draw();

                $btn.prop('disabled', false);
                $btn.html(originalText);
                modalEdit.hide();
            }, 500);
        });

        // Tombol Hapus → spinner + hapus via DataTables API
        $('#btnHapus').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

            setTimeout(function() {
                selectedRow.remove().draw();

                $btn.prop('disabled', false);
                $btn.html(originalText);
                modalEdit.hide();
            }, 500);
        });
    });
</script>
@endsection
