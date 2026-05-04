@extends('layouts.app')

@section('title', 'Buku')

@section('style-page')
<style>
    .page-title {
        font-weight: bold;
        color: #6f42c1;
    }
</style>
@endsection


@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="page-title">Data Buku</h4>

        <a href="{{ route('buku.create') }}" class="btn btn-primary mb-3">
            Tambah Buku
        </a>

        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Kode</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Aksi</th>

            </tr>

           @foreach($buku as $key => $b)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $b->kategori->nama }}</td>
                    <td>{{ $b->kode }}</td>
                    <td>{{ $b->judul }}</td>
                    <td>{{ $b->pengarang }}</td>
                    <td>
                        <a href="{{ route('buku.edit', $b->id) }}" 
                        class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('buku.destroy', $b->id) }}" 
                            method="POST" 
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </table>
    </div>
</div>

@section('script-page')
<script>
    console.log('Halaman Buku Loaded');
</script>
@endsection

@endsection
