@extends('layouts.vendor')

@section('title', 'Tambah Menu')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('vendor.menu.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="fw-bold mb-0">Tambah Menu Baru</h4>
        </div>

        <div class="card">
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formTambahMenu" action="{{ route('vendor.menu.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Menu <span class="text-danger">*</span></label>
                        <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Nasi Goreng Spesial" value="{{ old('nama_menu') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control" placeholder="15000" min="0" value="{{ old('harga') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi singkat menu (opsional)">{{ old('deskripsi') }}</textarea>
                    </div>
                </form>

                <div class="d-flex gap-2">
                    <button type="button" id="btnSimpan" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                    <a href="{{ route('vendor.menu.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-page')
<script>
$(function(){
    $('#btnSimpan').on('click', function(){
        var form = document.getElementById('formTambahMenu');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
        setTimeout(function(){ form.submit(); }, 400);
    });
});
</script>
@endsection
