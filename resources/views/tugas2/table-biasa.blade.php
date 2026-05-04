@extends('layouts.app')

@section('title', 'Tugas 2 - Table Biasa')

@section('style-page')
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
                <p class="text-muted mb-3">Table HTML biasa (tanpa DataTables) — klik row untuk edit/hapus</p>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tabelBarang">
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

                <div id="emptyState" class="text-center text-muted py-4">
                    <p class="mb-0">Belum ada data. Silakan tambah barang melalui form di samping.</p>
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
<script>
    let counter = 1;

    $(document).ready(function() {
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

                const row = `<tr>
                    <td>${id}</td>
                    <td>${nama}</td>
                    <td>Rp ${parseInt(harga).toLocaleString('id-ID')}</td>
                    <td></td>
                </tr>`;
                $('#tabelBarang tbody').append(row);

                $('#emptyState').hide();

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

        // Klik row → buka modal & isi data (event delegation)
        $('#tabelBarang tbody').on('click', 'tr', function() {
            selectedRow = $(this);
            const cols = selectedRow.find('td');

            $('#editId').val(cols.eq(0).text());
            $('#editNama').val(cols.eq(1).text());
            const hargaText = cols.eq(2).text().replace('Rp ', '').replace(/\./g, '');
            $('#editHarga').val(hargaText);

            modalEdit.show();
        });

        // Tombol Ubah → validasi + spinner + update row
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
                const namaBaru = $('#editNama').val();
                const hargaBaru = $('#editHarga').val();

                selectedRow.find('td').eq(1).text(namaBaru);
                selectedRow.find('td').eq(2).text('Rp ' + parseInt(hargaBaru).toLocaleString('id-ID'));

                $btn.prop('disabled', false);
                $btn.html(originalText);
                modalEdit.hide();
            }, 500);
        });

        // Tombol Hapus → spinner + hapus row
        $('#btnHapus').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();
            $btn.prop('disabled', true);
            $btn.html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

            setTimeout(function() {
                selectedRow.remove();

                // Tampilkan empty state jika tabel kosong
                if ($('#tabelBarang tbody tr').length === 0) {
                    $('#emptyState').show();
                }

                $btn.prop('disabled', false);
                $btn.html(originalText);
                modalEdit.hide();
            }, 500);
        });
    });
</script>
@endsection
