@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Tambah Buku</h4>

        <form id="formBukuCreate" action="{{ route('buku.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-2">
                <label>Kode</label>
                <input type="text" name="kode" class="form-control" required>
            </div>

            <div class="form-group mt-2">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" required>
            </div>

            <div class="form-group mt-2">
                <label>Pengarang</label>
                <input type="text" name="pengarang" class="form-control" required>
            </div>
        </form>

        <button type="button" class="btn btn-success mt-3" id="btnBukuCreate">
            <span class="btn-text">Simpan</span>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </div>
</div>

@section('script-page')
<script>
    $(document).ready(function() {
        $('#btnBukuCreate').on('click', function() {
            const form = document.getElementById('formBukuCreate');

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
