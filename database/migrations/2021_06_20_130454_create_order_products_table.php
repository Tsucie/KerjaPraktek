<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
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
        if (!Schema::hasTable('order_products'))
        {
            Schema::create('order_products', function (Blueprint $table) {
                $table->bigInteger('op_id')->unique('op_id_UNIQUE');
                $table->bigInteger('op_cst_id');
                $table->string('op_lokasi_pengiriman');
                $table->decimal('op_sum_harga_produk',20,2,true);
                $table->decimal('op_harga_ongkir')->nullable();
                $table->integer('op_persen_pajak')->nullable();
                $table->decimal('op_nominal_pajak')->nullable();
                $table->text('op_alamat_pengiriman')->nullable();
                $table->text('op_alamat_pemesanan')->nullable();
                $table->decimal('op_sum_biaya',20,2,true);
                $table->dateTime('op_tanggal_order');
                $table->smallInteger('op_status_order');
                $table->smallInteger('op_contact_customer');
                $table->string('op_note_to_customer')->nullable();
                $table->string('op_bukti_transfer_filename')->nullable();
                $table->binary('op_bukti_transfer_file')->nullable();
                $table->string('op_resi_filename')->nullable();
                $table->binary('op_resi_file')->nullable();
                $table->timestamps();
                $table->primary('op_id');
                $table->foreign('op_cst_id')
                        ->references('cst_id')->on('customers')
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
        Schema::dropIfExists('order_products');
    }
}
