<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromosTable extends Migration
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
        if (!Schema::hasTable('promos'))
        {
            Schema::create('promos', function (Blueprint $table) {
                $table->integer('prm_id')->unique('prm_id_UNIQUE');
                $table->integer('prm_pdct_id')->nullable();
                $table->integer('prm_vnu_id')->nullable();
                $table->string('prm_nama');
                $table->integer('prm_disc_percent');
                $table->decimal('prm_harga_promo', 20, 2, true);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->timestamps();
                $table->primary('prm_id');
                $table->foreign('prm_pdct_id', 'fk_promos_products')
                        ->references('pdct_id')->on('products')
                        ->onUpdate('CASCADE')
                        ->onDelete('CASCADE');
                $table->foreign('prm_vnu_id', 'fk_promos_venues')
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
        Schema::dropIfExists('promos');
    }
}
