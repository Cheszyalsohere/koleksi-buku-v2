@extends('layouts.app')

@section('title', 'Data Customer')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Data Customer</h4>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Metode Foto</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $i => $c)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                @if($c->foto_blob)
                                    <img src="{{ $c->foto_blob }}" alt="Foto" style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                                @elseif($c->foto_path)
                                    <img src="{{ asset($c->foto_path) }}" alt="Foto" style="width:50px; height:50px; object-fit:cover; border-radius:8px;">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $c->nama }}</td>
                            <td>{{ $c->email ?? '-' }}</td>
                            <td>{{ $c->telepon ?? '-' }}</td>
                            <td>
                                @if($c->foto_blob)
                                    <span class="badge bg-info">Blob (DB)</span>
                                @elseif($c->foto_path)
                                    <span class="badge bg-success">File (Path)</span>
                                @else
                                    <span class="badge bg-secondary">Tanpa Foto</span>
                                @endif
                            </td>
                            <td>
                                <form id="deleteForm{{ $c->id }}" action="{{ route('customer-manage.destroy', $c->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn btn-danger btn-sm" onclick="if(confirm('Yakin hapus customer ini?')) document.getElementById('deleteForm{{ $c->id }}').submit();">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data customer.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
