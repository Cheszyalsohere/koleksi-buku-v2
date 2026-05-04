@extends('layouts.app')

@section('title', 'Kategori')

@section('style-page')
<style>
    .kategori-title {
        font-weight: bold;
        color: #198754;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="kategori-title">Data Kategori</h4>

        <a href="{{ route('kategori.create') }}" class="btn btn-primary mb-3">
            Tambah Kategori
        </a>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama</th>
            </tr>
            @foreach($kategori as $key => $k)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $k->nama }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@section('script-page')
<script>
    console.log('Halaman Kategori Loaded');
</script>
@endsection

@endsection
