@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Barang</h3>

    <a href="{{ route('barang.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <form id="formBarangUpdate" action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-2">
            <label>ID Barang</label>
            <input type="text" class="form-control" value="{{ $barang->id_barang }}" disabled>
        </div>

        <div class="form-group mb-2">
            <label>Nama Barang</label>
            <input type="text" name="nama" class="form-control" value="{{ $barang->nama }}" required maxlength="50">
        </div>

        <div class="form-group mb-2">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ $barang->harga }}" required>
        </div>
    </form>

    <button type="button" class="btn btn-success mt-3" id="btnBarangUpdate">
        <span class="btn-text">Update</span>
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    </button>

    <form action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" class="mt-2">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
    </form>
</div>

@section('script-page')
<script>
    $(document).ready(function() {
        $('#btnBarangUpdate').on('click', function() {
            const form = document.getElementById('formBarangUpdate');

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
