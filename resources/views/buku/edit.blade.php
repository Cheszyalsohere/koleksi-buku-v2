@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Edit Buku</h4>

        <form id="formBukuUpdate" action="{{ route('buku.update', $buku->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori_id" class="form-control" required>
                    @foreach($kategori as $k)
                    <option value="{{ $k->id }}"
                        {{ $buku->kategori_id == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mt-2">
                <label>Kode</label>
                <input type="text" name="kode"
                    value="{{ $buku->kode }}"
                    class="form-control" required>
            </div>

            <div class="form-group mt-2">
                <label>Judul</label>
                <input type="text" name="judul"
                    value="{{ $buku->judul }}"
                    class="form-control" required>
            </div>

            <div class="form-group mt-2">
                <label>Pengarang</label>
                <input type="text" name="pengarang"
                    value="{{ $buku->pengarang }}"
                    class="form-control" required>
            </div>
        </form>

        <button type="button" class="btn btn-success mt-3" id="btnBukuUpdate">
            <span class="btn-text">Update</span>
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        </button>
    </div>
</div>

@section('script-page')
<script>
    $(document).ready(function() {
        $('#btnBukuUpdate').on('click', function() {
            const form = document.getElementById('formBukuUpdate');

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
