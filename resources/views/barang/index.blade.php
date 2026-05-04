@extends('layouts.app')

@section('style-page')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
@endsection

@section('content')
<div class="container">

    <h3>Data Barang</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('barang.create') }}" class="btn btn-success mb-3">Tambah Barang</a>

    <form id="formCetak" method="POST" action="{{ route('barang.cetak') }}">
        @csrf

        <div class="mb-3">
            <label>X (1-5)</label>
            <input type="number" name="x" min="1" max="5" required>

            <label>Y (1-8)</label>
            <input type="number" name="y" min="1" max="8" required>

            <button type="button" class="btn btn-primary" id="btnCetak">

                <span id="textCetak">
                    Cetak Tag Harga
                </span>

                <span id="spinnerCetak"
                    class="spinner-border spinner-border-sm d-none">
                </span>

            </button>
        </div>

        <table id="table-barang" class="table table-bordered">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>ID Barang</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($barangs as $barang)
                <tr>
                    <td>
                        <input type="checkbox" name="barang_ids[]" value="{{ $barang->id_barang }}">
                    </td>
                    <td>{{ $barang->id_barang }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td>Rp {{ number_format($barang->harga, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('barang.edit', $barang->id_barang) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm"
                            onclick="if(confirm('Yakin hapus?')) document.getElementById('delete-{{ $barang->id_barang }}').submit();">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    {{-- Form delete di luar form cetak agar tidak nested --}}
    @foreach($barangs as $barang)
    <form id="delete-{{ $barang->id_barang }}" action="{{ route('barang.destroy', $barang->id_barang) }}" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
    @endforeach
</div>
@endsection

@section('script-page')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#table-barang').DataTable();

        $('#btnCetak').on('click', function() {
            const form = document.getElementById('formCetak');

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Cek minimal 1 checkbox tercentang
            if ($('input[name="barang_ids[]"]:checked').length === 0) {
                alert('Pilih minimal 1 barang untuk dicetak!');
                return;
            }

            const $btn = $(this);
            $btn.prop('disabled', true);
            $btn.find('#textCetak').text('Memproses...');
            $btn.find('#spinnerCetak').removeClass('d-none');

            form.submit();
        });
    });
</script>
@endsection
