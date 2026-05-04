<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        Vendor::create([
            'nama_vendor' => 'Warung Bu Ani',
            'email' => 'ani@kantin.test',
            'password' => Hash::make('password'),
        ]);

        Vendor::create([
            'nama_vendor' => 'Kedai Pak Budi',
            'email' => 'budi@kantin.test',
            'password' => Hash::make('password'),
        ]);
    }
}
