<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @author Rizky A
     * @return void
     */
    public function up()
    {
        // Create Table if not Exists
        if (!Schema::hasTable('order_venues'))
        {
            Schema::create('order_venues', function (Blueprint $table) {
                $table->bigInteger('ov_id')->unique('ov_id_UNIQUE');
                $table->bigInteger('ov_cst_id')->nullable();
                $table->bigInteger('ov_gst_id')->nullable();
                $table->integer('ov_vnu_id');
                $table->string('ov_vnu_nama');
                $table->string('ov_nama_catering');
                $table->string('ov_no_telp');
                $table->decimal('ov_harga_sewa',20,2,true);
                $table->decimal('ov_biaya_lain',20,2,true)->nullable();
                $table->decimal('ov_fee_catering',20,2,true)->nullable();
                $table->decimal('ov_fee_pelaminan',20,2,true)->nullable();
                $table->text('ov_more_facilities')->nullable();
                $table->decimal('ov_lain_lain',20,2,true)->nullable();
                $table->decimal('ov_sum_lain_lain',20,2,true)->nullable();
                $table->decimal('ov_sum_biaya',20,2,true);
                $table->decimal('ov_down_payment',20,2,true);
                $table->decimal('ov_remaining_payment',20,2,true);
                $table->smallInteger('ov_status_order');
                $table->timestamps();
                $table->primary('ov_id');
                $table->foreign('ov_cst_id')
                        ->references('cst_id')->on('customers')
                        ->onUpdate('CASCADE')
                        ->onDelete('CASCADE');
                $table->foreign('ov_gst_id')
                        ->references('gst_id')->on('guests')
                        ->onUpdate('CASCADE')
                        ->onDelete('CASCADE');
                $table->foreign('ov_vnu_id')
                        ->references('vnu_id')->on('venues')
                        ->onUpdate('CASCADE')
                        ->onDelete('CASCADE');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_venues');
    }
}
