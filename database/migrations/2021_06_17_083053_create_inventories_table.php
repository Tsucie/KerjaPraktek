<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
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
        if (!Schema::hasTable('inventories'))
        {
            Schema::create('inventories', function (Blueprint $table) {
                $table->integer('ivty_id')->unique('ivty_id_UNIQUE');
                $table->integer('ivty_pdct_id')->unique('ivty_pdct_id_UNIQUE');
                $table->string('ivty_pdct_nama', 200);
                $table->integer('ivty_pdct_stock', false, true);
                $table->string('ivty_cause')->nullable();
                $table->timestamps();
                $table->primary('ivty_id');
                $table->foreign('ivty_pdct_id','fk_inventories_products')->references('pdct_id')->on('products')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('inventories');
    }
}
