<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @author Rizky A
     * @return void
     */
    public function run()
    {
        $table = DB::table('facilities');
        if ($table->count() === 0)
        {
            $table->insert(['fc_nama' => 'Gedung Full AC', 'fc_desc' => null, 'created_by' => 'system']);
            $table->insert(['fc_nama' => 'Ruang rias pria dan wanita', 'fc_desc' => null, 'created_by' => 'system']);
            $table->insert(['fc_nama' => 'Kursi 50 buah', 'fc_desc' => null, 'created_by' => 'system']);
            $table->insert(['fc_nama' => 'Meja penerima tamu 2 buah (untuk acara perpisahan & halal bi halal)', 'fc_desc' => null, 'created_by' => 'system']);
            $table->insert(['fc_nama' => 'Sound system untuk akad nikah/halal bi halal/perpisahan/reuni', 'fc_desc' => null, 'created_by' => 'system']);
            $table->insert(['fc_nama' => 'Tempat parkir: 100 kendaraan roda empat, dan 300 kendaraan roda dua', 'fc_desc' => null, 'created_by' => 'system']);
            $table->insert(['fc_nama' => 'Listrik PLN 66.000 Watt (belum termasuk genset)', 'fc_desc' => null, 'created_by' => 'system']);
            $table->insert(['fc_nama' => 'Keamanan & kebersihan', 'fc_desc' => null, 'created_by' => 'system']);   
        }
    }
}
