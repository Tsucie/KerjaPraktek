<?php

namespace Database\Seeders;

use App\Models\DateTime;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $faker = Factory::create();
            $op_id = rand(intval(date('ymdhis')),intval(date('ymdhis')));
            $pdct_harga = 1750000;
            $pdct_qty = rand(1,10);
            $status_order = rand(0,4); // 0: Dalam Proses; 1: Terverifikasi; 2: Sudah Down Payment; 3: Selesai(Lunas); 4: Ditolak;
            $ongkir = [30000 => 'JNE', 25000 => 'JNT', 20000 => 'TIKI', 35000 => 'SI CEPAT'];
            $pajak = 10; // persen
            DB::table('order_products')->insert([
                'op_id' => $op_id,
                'op_cst_id' => 2536325632120215,
                'op_lokasi_pengiriman' => $faker->address,
                'op_sum_harga_produk' => $pdct_harga * $pdct_qty,
                'op_harga_ongkir' => array_rand($ongkir),
                'op_persen_pajak' => $pajak,
                'op_nominal_pajak' => ($pdct_harga * $pdct_qty)*$pajak/100,
                'op_alamat_pengiriman' => $faker->address,
                'op_alamat_pemesanan' => $faker->address,
                'op_tanggal_order' => DateTime::Now(),
                'op_status_order' => $status_order,
                'op_contact_customer' => $status_order == 0 ? 0 : 1,
                'created_at' => DateTime::Now(),
                'updated_at' => DateTime::Now()
            ]);
            DB::table('order_detail_products')->insert([
                'odp_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
                'odp_op_id' => $op_id,
                'odp_pdct_id' => 1729797270,
                'odp_pdct_kode' => 'PD#20210717266850112',
                'odp_pdct_harga' => $pdct_harga,
                'odp_pdct_qty' => $pdct_qty,
                'created_at' => DateTime::Now(),
                'updated_at' => DateTime::Now()
            ]);
        } catch (\Throwable $th) { }
    }
}
