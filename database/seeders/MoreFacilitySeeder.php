<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MoreFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @author Rizky A
     * @return void
     */
    public function run()
    {
        $table = DB::table('more_facilities');
        if ($table->count() === 0)
        {
            $table->insert(['mfc_nama' => 'Kursi Lipat', 'mfc_satuan' => 'Buah', 'mfc_harga' => 5000.00, 'created_by' => 'system']);
            $table->insert(['mfc_nama' => 'Kursi Merk Futura', 'mfc_satuan' => 'Buah', 'mfc_harga' => 7000.00, 'created_by' => 'system']);
            $table->insert(['mfc_nama' => 'Meja', 'mfc_satuan' => 'Buah', 'mfc_harga' => 15000.00, 'created_by' => 'system']);
            $table->insert(['mfc_nama' => 'Tenda', 'mfc_satuan' => 'Meter', 'mfc_harga' => 40000.00, 'created_by' => 'system']);
            $table->insert(['mfc_nama' => 'Kipas angin humidifier untuk diluar gedung', 'mfc_satuan' => 'Buah', 'mfc_harga' => 300000.00, 'created_by' => 'system']);
            $table->insert(['mfc_nama' => 'Ruang Tamu VIP (selama jam pemakaian gedung)', 'mfc_satuan' => 'Jam', 'mfc_harga' => 500000.00, 'created_by' => 'system']);
            $table->insert(['mfc_nama' => 'Genset 60 KVA', 'mfc_satuan' => 'Hari', 'mfc_harga' => 2500000.00, 'created_by' => 'system']);
        }
    }
}
