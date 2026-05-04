@extends('layouts.app')

@section('title', 'Tugas 4 - Select vs Select2')

@section('style-page')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">

    {{-- Card 1: Select Biasa --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Select</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Dropdown native browser — tanpa fitur search</p>

                {{-- Form Tambah Kota --}}
                <form id="formKota1">
                    <div class="mb-3">
                        <label class="form-label">Tambah Kota</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputKota1" placeholder="Masukkan nama kota" required>
                            <button type="button" class="btn btn-primary" id="btnTambahKota1">Tambah</button>
                        </div>
                    </div>
                </form>

                {{-- Select Biasa --}}
                <div class="mb-3">
                    <label class="form-label">Pilih Kota</label>
                    <select class="form-select" id="selectKota1">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Kota Terpilih --}}
                <div class="alert alert-info">
                    <strong>Kota Terpilih:</strong> <span id="kotaTerpilih1">-</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Select2 --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Select 2</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Dropdown dengan fitur search, styling modern</p>

                {{-- Form Tambah Kota --}}
                <form id="formKota2">
                    <div class="mb-3">
                        <label class="form-label">Tambah Kota</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputKota2" placeholder="Masukkan nama kota" required>
                            <button type="button" class="btn btn-success" id="btnTambahKota2">Tambah</button>
                        </div>
                    </div>
                </form>

                {{-- Select2 --}}
                <div class="mb-3">
                    <label class="form-label">Pilih Kota</label>
                    <select class="form-select" id="selectKota2" style="width:100%">
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                {{-- Kota Terpilih --}}
                <div class="alert alert-info">
                    <strong>Kota Terpilih:</strong> <span id="kotaTerpilih2">-</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script-page')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        // ============ INISIALISASI SELECT2 untuk Card 2 ============
        $('#selectKota2').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih Kota --',
            allowClear: true
        });

        // ============ CARD 1: SELECT BIASA ============

        // Tambah kota ke select biasa
        $('#btnTambahKota1').on('click', function() {
            const form = document.getElementById('formKota1');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const namaKota = $('#inputKota1').val().trim();

            // Tambahkan option baru
            const option = new Option(namaKota, namaKota);
            $('#selectKota1').append(option);

            // Reset input
            $('#inputKota1').val('').focus();
        });

        // Tampilkan kota terpilih
        $('#selectKota1').on('change', function() {
            const val = $(this).val();
            $('#kotaTerpilih1').text(val ? val : '-');
        });

        // ============ CARD 2: SELECT2 ============

        // Tambah kota ke Select2
        $('#btnTambahKota2').on('click', function() {
            const form = document.getElementById('formKota2');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const namaKota = $('#inputKota2').val().trim();

            // Tambah option baru + trigger change supaya Select2 refresh
            const option = new Option(namaKota, namaKota);
            $('#selectKota2').append(option);
            $('#selectKota2').trigger('change');

            // Reset input
            $('#inputKota2').val('').focus();
        });

        // Event change pada Select2
        $('#selectKota2').on('change', function() {
            const val = $(this).val();
            $('#kotaTerpilih2').text(val ? val : '-');
        });

    });
</script>
@endsection
