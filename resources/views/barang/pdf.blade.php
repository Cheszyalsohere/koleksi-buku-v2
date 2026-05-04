<!DOCTYPE html>
<html>
<head>
    <style>
        /* Sesuaikan margin ini dengan batas fisik printer kamu (kalibrasi) */
        @page { margin: 0.5cm; } 
        body  { font-family: sans-serif; margin: 0; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        
        td {
            width: 20%; /* 5 Kolom = 100% / 5 */
            height: 3.6cm; /* Tinggi rata-rata stiker TnJ 108 */
            text-align: center;
            vertical-align: middle;
            padding: 2px;
            overflow: hidden;
            /* UNTUK TESTING: Hapus tanda komentar di bawah ini agar garis kotaknya terlihat */
            /* border: 1px dashed #ccc; */ 
        }
        .nama-barang { font-weight: bold; font-size: 10px; display: block; margin-bottom: 2px; }
        .harga-barang { font-size: 12px; font-weight: bold; color: #000; }
        .barcode-img { display: block; margin: 2px auto; max-width: 90%; height: auto; }
        .id-barang { font-size: 7px; color: #555; }
    </style>
</head>
<body>
    <table>
        <tr>
        @foreach($labels as $index => $item)
            {{-- Jika sudah mencapai 5 kolom, tutup baris dan buat baris baru --}}
            @if($index > 0 && $index % 5 == 0)
                </tr><tr>
            @endif
            
            <td>
                {{-- Hanya cetak jika array tidak kosong (bukan hasil skip) --}}
                @if($item)
                    <span class="nama-barang">{{ substr($item->nama, 0, 20) }}</span>
                    <span class="harga-barang">Rp {{ number_format($item->harga, 0, ',', '.') }}</span><br>
                    @if(isset($barcodes[$item->id_barang]))
                        <img src="data:image/png;base64,{{ $barcodes[$item->id_barang] }}" class="barcode-img">
                    @endif
                    <span class="id-barang">{{ $item->id_barang }}</span>
                @endif
            </td>
        @endforeach
        
        {{-- Tutup elemen td yang tersisa agar tabel tidak hancur --}}
        @php
            $sisa = 5 - (count($labels) % 5);
            if($sisa > 0 && $sisa < 5) {
                for($i=0; $i<$sisa; $i++){ echo "<td></td>"; }
            }
        @endphp
        </tr>
    </table>
</body>
</html>