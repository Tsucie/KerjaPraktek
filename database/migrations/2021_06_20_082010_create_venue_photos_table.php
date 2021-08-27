<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVenuePhotosTable extends Migration
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
        if (!Schema::hasTable('venue_photos'))
        {
            Schema::create('venue_photos', function (Blueprint $table) {
                $table->integer('vp_id')->unique('vp_id_UNIQUE');
                $table->integer('vp_vnu_id');
                $table->string('vp_filename', 150);
                $table->binary('vp_photo');
                $table->timestamps();
                $table->primary('vp_id');
                $table->foreign('vp_vnu_id', 'fk_venuePhotos_venues')
                        ->references('vnu_id')->on('venues')
                        ->onUpdate('CASCADE')
                        ->onDelete('CASCADE');
            });
            DB::statement("ALTER TABLE venue_photos ADD vp_photo MEDIUMBLOB NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venue_photos');
    }
}
