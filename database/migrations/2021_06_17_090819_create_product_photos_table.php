<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPhotosTable extends Migration
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
        if (!Schema::hasTable('product_photos'))
        {
            Schema::create('product_photos', function (Blueprint $table) {
                $table->integer('pp_id')->unique('pp_id_UNIQUE');
                $table->integer('pp_pdct_id')->unique('pp_pdct_id_UNIQUE');
                $table->string('pp_filename', 150);
                $table->binary('pp_photo');
                $table->timestamps();
                $table->primary('pp_id');
                $table->foreign('pp_pdct_id','fk_productPhotos_products')
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
        Schema::dropIfExists('product_photos');
    }
}
