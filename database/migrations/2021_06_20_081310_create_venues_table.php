<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenuesTable extends Migration
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
        if (!Schema::hasTable('venues'))
        {
            Schema::create('venues', function (Blueprint $table) {
                $table->integer('vnu_id')->unique('vnu_id_UNIQUE');
                $table->string('vnu_nama');
                $table->string('vnu_desc')->nullable();
                $table->text('vnu_fasilitas');
                $table->decimal('vnu_harga',20,2,true);
                $table->string('vnu_jam_pemakaian_siang');
                $table->string('vnu_jam_pemakaian_malam');
                $table->text('vnu_ketentuan_sewa');
                $table->smallInteger('vnu_status_tersedia');
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
        Schema::dropIfExists('venues');
    }
}
