@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Data Mahasiswa NFC</h4>
            <small class="text-muted">Kelola data mahasiswa dan kartu NFC</small>
        </div>
        <a href="{{ route('nfc.scanner') }}" target="_blank" class="btn btn-primary btn-sm">
            <i class="mdi mdi-wifi me-1"></i> Buka Scanner NFC
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Form Tambah Mahasiswa --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header fw-semibold bg-primary text-white">
                    <i class="mdi mdi-account-plus me-2"></i> Tambah Mahasiswa
                </div>
                <div class="card-body">
                    <form action="{{ route('nfc.mahasiswa.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" class="form-control @error('nim') is-invalid @enderror"
                                value="{{ old('nim') }}" placeholder="contoh: 12345678">
                            @error('nim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                value="{{ old('nama') }}" placeholder="Nama lengkap">
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program Studi</label>
                            <input type="text" name="prodi" class="form-control" value="{{ old('prodi') }}"
                                placeholder="contoh: Teknik Informatika">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                placeholder="email@domain.com">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="mdi mdi-plus me-1"></i> Tambah
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabel Mahasiswa --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header fw-semibold bg-light d-flex justify-content-between align-items-center">
                    <span><i class="mdi mdi-account-group me-2"></i> Daftar Mahasiswa ({{ $mahasiswas->count() }})</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Prodi</th>
                                    <th class="text-center">Kartu NFC</th>
                                    <th class="text-center">Absensi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mahasiswas as $mhs)
                                <tr>
                                    <td class="fw-semibold font-monospace">{{ $mhs->nim }}</td>
                                    <td>{{ $mhs->nama }}</td>
                                    <td class="text-muted small">{{ $mhs->prodi ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($mhs->nfc_serial)
                                            <span class="badge bg-success">
                                                <i class="mdi mdi-check-circle me-1"></i> Terdaftar
                                            </span>
                                            <br>
                                            <small class="text-muted font-monospace" style="font-size:10px;">
                                                {{ Str::limit($mhs->nfc_serial, 20) }}
                                            </small>
                                            <br>
                                            <form action="{{ route('nfc.mahasiswa.unlink', $mhs->id) }}" method="POST" class="d-inline"
                                                onsubmit="return confirm('Lepas kartu NFC dari mahasiswa ini?')">
                                                @csrf @method('PATCH')
                                                <button class="btn btn-link btn-sm text-warning p-0" style="font-size:11px;">
                                                    Lepas kartu
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-secondary">Belum Ada</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('nfc.riwayat', ['nim' => $mhs->nim]) }}"
                                           class="badge bg-info text-decoration-none">
                                            {{ $mhs->absensis_count }} kali
                                        </a>
                                    </td>
                                    <td>
                                        <form action="{{ route('nfc.mahasiswa.destroy', $mhs->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus mahasiswa {{ $mhs->nama }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Belum ada mahasiswa. Tambahkan di form sebelah kiri.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
