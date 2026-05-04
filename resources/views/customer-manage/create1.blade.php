@extends('layouts.app')

@section('title', 'Tambah Customer 1 - Foto Blob')

@section('content')
<div class="col-lg-8 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('customer-manage.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                    <i class="mdi mdi-arrow-left"></i>
                </a>
                <h4 class="card-title mb-0">Tambah Customer 1 — Foto sebagai Blob</h4>
            </div>
            <p class="text-muted mb-3">Foto diambil dari kamera lalu disimpan sebagai <strong>base64 blob</strong> di database.</p>

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                {{-- Kamera --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kamera</label>
                    <div class="position-relative bg-dark rounded overflow-hidden" style="aspect-ratio:4/3;">
                        <video id="video" autoplay playsinline style="width:100%; height:100%; object-fit:cover;"></video>
                    </div>
                    <button type="button" id="btnCapture" class="btn btn-primary btn-sm w-100 mt-2">
                        <i class="mdi mdi-camera"></i> Ambil Foto
                    </button>
                </div>

                {{-- Preview --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Preview</label>
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="aspect-ratio:4/3;">
                        <canvas id="canvas" style="width:100%; height:100%; display:none;"></canvas>
                        <span id="placeholderText" class="text-muted">Belum ada foto</span>
                    </div>
                    <button type="button" id="btnRetake" class="btn btn-warning btn-sm w-100 mt-2" style="display:none;">
                        <i class="mdi mdi-refresh"></i> Ambil Ulang
                    </button>
                </div>
            </div>

            <form id="formCustomer" action="{{ route('customer-manage.store1') }}" method="POST">
                @csrf
                <input type="hidden" name="foto_blob" id="fotoBlob">

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama customer" value="{{ old('nama') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="{{ old('email') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Telepon</label>
                        <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('telepon') }}">
                    </div>
                </div>
            </form>

            <button type="button" id="btnSimpan" class="btn btn-success">
                <i class="mdi mdi-check"></i> Simpan
            </button>
            <a href="{{ route('customer-manage.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </div>
</div>
@endsection

@section('script-page')
<script>
$(function(){
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    let stream = null;

    // Akses kamera
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
        .then(function(s) {
            stream = s;
            video.srcObject = stream;
        })
        .catch(function(err) {
            alert('Gagal mengakses kamera: ' + err.message);
        });

    // Capture foto
    $('#btnCapture').on('click', function(){
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0);
        $(canvas).show();
        $('#placeholderText').hide();
        $('#btnRetake').show();

        // Simpan base64 ke hidden input
        var dataUrl = canvas.toDataURL('image/png');
        $('#fotoBlob').val(dataUrl);
    });

    // Ambil ulang
    $('#btnRetake').on('click', function(){
        $(canvas).hide();
        $('#placeholderText').show();
        $('#btnRetake').hide();
        $('#fotoBlob').val('');
    });

    // Submit
    $('#btnSimpan').on('click', function(){
        var form = document.getElementById('formCustomer');
        if (!form.checkValidity()) { form.reportValidity(); return; }
        if (!$('#fotoBlob').val()) { alert('Silakan ambil foto terlebih dahulu!'); return; }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');
        setTimeout(function(){ form.submit(); }, 400);
    });
});
</script>
@endsection
