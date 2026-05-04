<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Menu untuk Warung Bu Ani
        $ani = Vendor::where('email', 'ani@kantin.test')->first();
        $ani->menus()->createMany([
            ['nama_menu' => 'Nasi Goreng', 'harga' => 15000, 'deskripsi' => 'Nasi goreng spesial dengan telur dan kerupuk'],
            ['nama_menu' => 'Mie Ayam', 'harga' => 12000, 'deskripsi' => 'Mie ayam dengan topping ayam cincang'],
            ['nama_menu' => 'Es Teh', 'harga' => 5000, 'deskripsi' => 'Es teh manis segar'],
        ]);

        // Menu untuk Kedai Pak Budi
        $budi = Vendor::where('email', 'budi@kantin.test')->first();
        $budi->menus()->createMany([
            ['nama_menu' => 'Soto Ayam', 'harga' => 18000, 'deskripsi' => 'Soto ayam kuah bening dengan nasi'],
            ['nama_menu' => 'Nasi Uduk', 'harga' => 10000, 'deskripsi' => 'Nasi uduk komplit dengan lauk'],
            ['nama_menu' => 'Es Jeruk', 'harga' => 6000, 'deskripsi' => 'Es jeruk peras segar'],
        ]);
    }
}
