<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoreFacilitiesTable extends Migration
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
        if (!Schema::hasTable('more_facilities'))
        {
            Schema::create('more_facilities', function (Blueprint $table) {
                $table->integer('mfc_id', true, true)->unique('mfc_id_UNIQUE');
                $table->string('mfc_nama');
                $table->string('mfc_satuan', 50);
                $table->decimal('mfc_harga',10,2,true);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('more_facilities');
    }
}
