<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
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
        if (!Schema::hasTable('feedbacks'))
        {
            Schema::create('feedbacks', function (Blueprint $table) {
                $table->bigInteger('fb_id')->unique('fb_id_UNIQUE');
                $table->integer('fb_vnu_id')->nullable();
                $table->integer('fb_pdct_id')->nullable();
                $table->string('fb_cst_nama');
                $table->string('fb_cst_email');
                $table->text('fb_text');
                $table->smallInteger('fb_rating', false, true);
                $table->timestamps();
                $table->primary('fb_id');
                $table->foreign('fb_vnu_id')
                        ->references('vnu_id')->on('venues')
                        ->onUpdate('CASCADE')
                        ->onDelete('RESTRICT');
                $table->foreign('fb_pdct_id')
                        ->references('pdct_id')->on('products')
                        ->onUpdate('CASCADE')
                        ->onDelete('RESTRICT');
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
        Schema::dropIfExists('feedbacks');
    }
}
