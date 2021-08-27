<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
        if (!Schema::hasTable('products'))
        {
            Schema::create('products', function (Blueprint $table) {
                $table->integer('pdct_id')->unique('pdct_id_UNIQUE');
                $table->smallInteger('pdct_kategori_id');
                $table->string('pdct_kategori_nama',150);
                $table->string('pdct_kode',40);
                $table->string('pdct_nama', 200);
                $table->string('pdct_desc')->nullable();
                $table->decimal('pdct_harga', 20, 2, true);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->timestamps();
                $table->primary('pdct_id');
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
        Schema::dropIfExists('products');
    }
}
