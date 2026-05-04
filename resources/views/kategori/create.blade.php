@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Tambah Kategori</h4>

        <form id="formKategoriCreate" action="{{ route('kategori.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
        </form>

        <button type="button" class="btn btn-success mt-2" id="btnKategoriCreate">
            <span class="btn-text">Simpan</span>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </div>
</div>

@section('script-page')
<script>
    $(document).ready(function() {
        $('#btnKategoriCreate').on('click', function() {
            const form = document.getElementById('formKategoriCreate');

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
