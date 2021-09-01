<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @author Rizky A
     * @return void
     */
    public function up()
    {
        // Create Table if not Exits
        if (!Schema::hasTable('guests'))
        {
            Schema::create('guests', function (Blueprint $table) {
                $table->bigInteger('gst_id')->unique('gst_id_UNIQUE');
                $table->string('gst_nama');
                $table->string('gst_alamat');
                $table->string('gst_no_telp', 20);
                $table->date('gst_rencana_pemakaian');
                $table->string('gst_waktu_pemakaian',100);
                $table->string('gst_keperluan_pemakaian')->nullable();
                $table->timestamps();
                $table->primary('gst_id');
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
        Schema::dropIfExists('guests');
    }
}
