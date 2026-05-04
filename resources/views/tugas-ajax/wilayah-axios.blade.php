@extends('layouts.app')

@section('title', 'Tugas AJAX - Wilayah Axios')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Cascading Dropdown Wilayah (Axios)</h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">Data diambil dari API publik emsifa menggunakan <strong>axios.get()</strong> dengan Promise (.then/.catch)</p>

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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    $(document).ready(function() {

        // ============ LOAD PROVINSI saat halaman dibuka ============
        axios({
            method: 'GET',
            url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json'
        })
        .then(function(response) {
            // Di Axios, data ada di response.data (BEDA dengan jQuery)
            response.data.forEach(function(item) {
                $('#provinsi').append(new Option(item.name, item.id));
            });
        })
        .catch(function(error) {
            console.log('Error load provinsi:', error);
            alert('Gagal memuat data provinsi');
        });

        // ============ EVENT: PROVINSI berubah ============
        $('#provinsi').on('change', function() {
            const idProvinsi = $(this).val();

            // Reset kota, kecamatan, kelurahan
            $('#kota').html('<option value="0">-- Pilih Kota --</option>');
            $('#kecamatan').html('<option value="0">-- Pilih Kecamatan --</option>');
            $('#kelurahan').html('<option value="0">-- Pilih Kelurahan --</option>');

            if (idProvinsi == 0) return;

            axios.get('https://www.emsifa.com/api-wilayah-indonesia/api/regencies/' + idProvinsi + '.json')
                .then(function(response) {
                    response.data.forEach(function(item) {
                        $('#kota').append(new Option(item.name, item.id));
                    });
                })
                .catch(function(error) {
                    console.log('Error load kota:', error);
                });
        });

        // ============ EVENT: KOTA berubah ============
        $('#kota').on('change', function() {
            const idKota = $(this).val();

            // Reset kecamatan & kelurahan
            $('#kecamatan').html('<option value="0">-- Pilih Kecamatan --</option>');
            $('#kelurahan').html('<option value="0">-- Pilih Kelurahan --</option>');

            if (idKota == 0) return;

            axios.get('https://www.emsifa.com/api-wilayah-indonesia/api/districts/' + idKota + '.json')
                .then(function(response) {
                    response.data.forEach(function(item) {
                        $('#kecamatan').append(new Option(item.name, item.id));
                    });
                })
                .catch(function(error) {
                    console.log('Error load kecamatan:', error);
                });
        });

        // ============ EVENT: KECAMATAN berubah ============
        $('#kecamatan').on('change', function() {
            const idKecamatan = $(this).val();

            // Reset kelurahan
            $('#kelurahan').html('<option value="0">-- Pilih Kelurahan --</option>');

            if (idKecamatan == 0) return;

            axios.get('https://www.emsifa.com/api-wilayah-indonesia/api/villages/' + idKecamatan + '.json')
                .then(function(response) {
                    response.data.forEach(function(item) {
                        $('#kelurahan').append(new Option(item.name, item.id));
                    });
                })
                .catch(function(error) {
                    console.log('Error load kelurahan:', error);
                });
        });

    });
</script>
@endsection
