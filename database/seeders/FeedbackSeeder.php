<?php

namespace Database\Seeders;

use App\Models\DateTime;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $ov_ids = [
            165656484 => 3,
            446545875 => 4,
            210717112439 => 4,
            210717112506 => 4,
            210717112508 => 4,
            210717112510 => 3,
            210717112520 => 4,
            210717112524 => 3,
            210717112526 => 3,
            210717112528 => 4,
            210717112530 => 4,
            210717112537 => 4,
            210717112709 => 3,
            210717112712 => 3,
            210717112714 => 4,
            210717112716 => 4,
            210717112725 => 3,
            210717112729 => 3,
            210718063343 => 4
        ];
        $op_ids = [
            210719070943 => 3,
            210719070947 => 3,
            210719070957 => 3,
            210719071000 => 3,
            210719071004 => 4,
            210719071029 => 3,
            210719071059 => 4
        ];
        $data_ov_id = array_rand($ov_ids);
        $data_op_id = array_rand($op_ids);
        // Create ov feedback
        DB::table('feedbacks')->insert([
            'fb_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
            'fb_ov_id' => $data_ov_id,
            'fb_order_status' => $ov_ids[$data_ov_id],
            'fb_text' => $faker->text(250),
            'created_at' => DateTime::Now(),
            'updated_at' => DateTime::Now()
        ]);
        sleep(1);
        // Create op feedback
        DB::table('feedbacks')->insert([
            'fb_id' => rand(intval(date('ymdhis')),intval(date('ymdhis'))),
            'fb_op_id' => $data_op_id,
            'fb_order_status' => $op_ids[$data_op_id],
            'fb_text' => $faker->text(250),
            'created_at' => DateTime::Now(),
            'updated_at' => DateTime::Now()
        ]);
    }
}
