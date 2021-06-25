<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @author Rizky A
     * @return void
     */
    public function up()
    {
        // Create table if not Exists
        if (!Schema::hasTable('order_detail_products'))
        {
            Schema::create('order_detail_products', function (Blueprint $table) {
                $table->bigInteger('odp_id')->unique('odp_id_UNIQUE');
                $table->bigInteger('odp_op_id');
                $table->integer('odp_pdct_id');
                $table->string('odp_pdct_kode',40);
                $table->decimal('odp_pdct_harga', 20, 2, true);
                $table->integer('odp_pdct_qty');
                $table->timestamps();
                $table->primary('odp_id');
                $table->foreign('odp_op_id')
                        ->references('op_id')->on('order_products')
                        ->onUpdate('CASCADE')
                        ->onDelete('CASCADE');
                $table->foreign('odp_pdct_id')
                        ->references('pdct_id')->on('products')
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
        Schema::dropIfExists('order_detail_products');
    }
}
