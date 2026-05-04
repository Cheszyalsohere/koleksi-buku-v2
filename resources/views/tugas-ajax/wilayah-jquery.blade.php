@extends('layouts.app')

@section('title', 'Tugas AJAX - Wilayah jQuery')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Cascading Dropdown Wilayah (Ajax jQuery)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Data diambil dari API publik emsifa menggunakan <strong>$.ajax()</strong> jQuery</p>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Provinsi :</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="provinsi">
                            <option value="0">-- Pilih Provinsi --</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Kota :</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="kota">
                            <option value="0">-- Pilih Kota --</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Kecamatan :</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="kecamatan">
                            <option value="0">-- Pilih Kecamatan --</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Kelurahan :</label>
                    <div class="col-sm-9">
                        <select class="form-select" id="kelurahan">
                            <option value="0">-- Pilih Kelurahan --</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-page')
<script>
    $(document).ready(function() {

        // ============ LOAD PROVINSI saat halaman dibuka ============
        $.ajax({
            method: "GET",
            url: "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json",
            success: function(response) {
                $.each(response, function(index, item) {
                    $('#provinsi').append(new Option(item.name, item.id));
                });
            },
            error: function(xhr) {
                console.log('Error load provinsi:', xhr);
                alert('Gagal memuat data provinsi');
            }
        });

        // ============ EVENT: PROVINSI berubah ============
        $('#provinsi').on('change', function() {
            const idProvinsi = $(this).val();

            // Reset kota, kecamatan, kelurahan
            $('#kota').html('<option value="0">-- Pilih Kota --</option>');
            $('#kecamatan').html('<option value="0">-- Pilih Kecamatan --</option>');
            $('#kelurahan').html('<option value="0">-- Pilih Kelurahan --</option>');

            if (idProvinsi == 0) return;

            $.ajax({
                method: "GET",
                url: "https://www.emsifa.com/api-wilayah-indonesia/api/regencies/" + idProvinsi + ".json",
                success: function(response) {
                    $.each(response, function(index, item) {
                        $('#kota').append(new Option(item.name, item.id));
                    });
                },
                error: function(xhr) {
                    console.log('Error load kota:', xhr);
                }
            });
        });

        // ============ EVENT: KOTA berubah ============
        $('#kota').on('change', function() {
            const idKota = $(this).val();

            // Reset kecamatan & kelurahan
            $('#kecamatan').html('<option value="0">-- Pilih Kecamatan --</option>');
            $('#kelurahan').html('<option value="0">-- Pilih Kelurahan --</option>');

            if (idKota == 0) return;

            $.ajax({
                method: "GET",
                url: "https://www.emsifa.com/api-wilayah-indonesia/api/districts/" + idKota + ".json",
                success: function(response) {
                    $.each(response, function(index, item) {
                        $('#kecamatan').append(new Option(item.name, item.id));
                    });
                },
                error: function(xhr) {
                    console.log('Error load kecamatan:', xhr);
                }
            });
        });

        // ============ EVENT: KECAMATAN berubah ============
        $('#kecamatan').on('change', function() {
            const idKecamatan = $(this).val();

            // Reset kelurahan
            $('#kelurahan').html('<option value="0">-- Pilih Kelurahan --</option>');

            if (idKecamatan == 0) return;

            $.ajax({
                method: "GET",
                url: "https://www.emsifa.com/api-wilayah-indonesia/api/villages/" + idKecamatan + ".json",
                success: function(response) {
                    $.each(response, function(index, item) {
                        $('#kelurahan').append(new Option(item.name, item.id));
                    });
                },
                error: function(xhr) {
                    console.log('Error load kelurahan:', xhr);
                }
            });
        });

    });
</script>
@endsection
