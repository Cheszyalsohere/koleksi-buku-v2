@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Barang</h3>

    <a href="{{ route('barang.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <form id="formBarangCreate" action="{{ route('barang.store') }}" method="POST">
        @csrf

        <div class="form-group mb-2">
            <label>Nama Barang</label>
            <input type="text" name="nama" class="form-control" required maxlength="50">
        </div>

        <div class="form-group mb-2">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
    </form>

    <button type="button" class="btn btn-success mt-3" id="btnBarangCreate">
        <span class="btn-text">Simpan</span>
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    </button>
</div>

@section('script-page')
<script>
    $(document).ready(function() {
        $('#btnBarangCreate').on('click', function() {
            const form = document.getElementById('formBarangCreate');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled', true);
            $btn.find('.btn-text').text('Memproses...');
            $btn.find('.spinner-border').removeClass('d-none');

            form.submit();
        });
    });
</script>
@endsection
@endsection
