<?php

namespace Database\Seeders;

use App\Models\DateTime;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderVenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $waktu = ['Siang' => 0,'Malam' => 1];
        $keperluan = ['Resepsi' => 0,'Wisuda' => 1,'Reuni' => 2,'Acara Keagamaan' => 3,'Acara Adat' => 4,'Seminar' => 5];
        try {
            $faker = Factory::create();
            $gst_id = rand(intval(date('ymdhis')),intval(date('ymdhis')));
            $status_order = rand(0,4); // 0: Dalam Proses; 1: Terverifikasi; 2: Sudah Down Payment; 3: Selesai(Lunas); 4: Ditolak;
            DB::table('guests')->insert([
                'gst_id' => $gst_id,
                'gst_nama' => $faker->name(),
                'gst_alamat' => $faker->address,
                'gst_no_telp' => '+62'.rand(10000000,99999999),
                'gst_rencana_pemakaian' => date('Y-m-d'),
                'gst_waktu_pemakaian' => array_rand($waktu),
                'gst_keperluan_pemakaian' => array_rand($keperluan),
                'created_at' => DateTime::Now(),
                'updated_at' => DateTime::Now()
            ]);
            DB::table('order_venues')->insert([
                'ov_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'ov_cst_id' => 2536325632120215,
                'ov_gst_id' => $gst_id,
                'ov_vnu_id' => 891141429,
                'ov_vnu_nama' => 'Gedung Aula PKS',
                'ov_no_telp' => '021002003',
                'ov_sum_biaya' => 10000000.00,
                'ov_down_payment' => 0.00,
                'ov_remaining_payment' => 10000000.00,
                'ov_status_order' => $status_order,
                'ov_contact_customer' => $status_order == 0 ? 0 : 1,
                'created_at' => DateTime::Now(),
                'updated_at' => DateTime::Now()
            ]);
        } catch (\Throwable $th) {}
    }
}
