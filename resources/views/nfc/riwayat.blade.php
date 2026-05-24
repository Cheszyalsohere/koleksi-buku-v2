@extends('layouts.app')

@section('content')
<div class="container py-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Riwayat Absensi NFC</h4>
            <small class="text-muted">Total: {{ $absensis->count() }} record</small>
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

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('nfc.riwayat') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small">Mata Kuliah</label>
                    <select name="mata_kuliah" class="form-select form-select-sm">
                        <option value="">Semua Mata Kuliah</option>
                        @foreach($mataKuliahList as $mk)
                            <option value="{{ $mk }}" {{ request('mata_kuliah') == $mk ? 'selected' : '' }}>
                                {{ $mk }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control form-control-sm"
                        value="{{ request('tanggal') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">NIM Mahasiswa</label>
                    <input type="text" name="nim" class="form-control form-control-sm"
                        value="{{ request('nim') }}" placeholder="Cari NIM...">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="mdi mdi-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Mata Kuliah</th>
                            <th class="text-center">Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensis as $i => $a)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                            <td class="font-monospace">{{ $a->waktu_scan }}</td>
                            <td class="fw-semibold font-monospace">{{ $a->mahasiswa->nim ?? '-' }}</td>
                            <td>{{ $a->mahasiswa->nama ?? '-' }}</td>
                            <td>{{ $a->mata_kuliah }}</td>
                            <td class="text-center">
                                @if($a->status === 'hadir')
                                    <span class="badge bg-success">HADIR</span>
                                @else
                                    <span class="badge bg-warning text-dark">TERLAMBAT</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('nfc.absensi.destroy', $a->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus data absensi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Belum ada data absensi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
