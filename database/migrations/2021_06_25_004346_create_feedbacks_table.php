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
                $table->bigInteger('fb_ov_id')->nullable();
                $table->bigInteger('fb_op_id')->nullable();
                $table->smallInteger('fb_order_status');
                $table->text('fb_text');
                $table->decimal('fb_rating',1,1,true);
                $table->timestamps();
                $table->primary('fb_id');
                $table->foreign('fb_ov_id')
                        ->references('ov_id')->on('order_venues')
                        ->onUpdate('CASCADE')
                        ->onDelete('CASCADE');
                $table->foreign('fb_op_id')
                        ->references('op_id')->on('order_products')
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
        Schema::dropIfExists('feedbacks');
    }
}
