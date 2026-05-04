<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama' => 'Pensil 2B', 'harga' => 3000],
            ['nama' => 'Penghapus', 'harga' => 2000],
            ['nama' => 'Buku Tulis', 'harga' => 5000],
            ['nama' => 'Penggaris 30cm', 'harga' => 4000],
            ['nama' => 'Pulpen Hitam', 'harga' => 3500],
            ['nama' => 'Spidol Merah', 'harga' => 7000],
            ['nama' => 'Tip-X', 'harga' => 6000],
            ['nama' => 'Lem Kertas', 'harga' => 4500],
            ['nama' => 'Gunting', 'harga' => 12000],
            ['nama' => 'Stapler', 'harga' => 15000],
        ];

        foreach ($items as $item) {
            DB::statement("INSERT INTO barangs (nama, harga) VALUES (?, ?)", [
                $item['nama'], $item['harga']
            ]);
        }
    }
}
