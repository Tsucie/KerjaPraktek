<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
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
        if (!Schema::hasTable('customers'))
        {
            Schema::create('customers', function (Blueprint $table) {
                $table->bigInteger('cst_id')->unique('cst_id_UNIQUE');
                $table->string('cst_name');
                $table->string('cst_alamat')->nullable();
                $table->string('cst_no_telp',20)->nullable();
                $table->string('cst_email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('cst_password');
                $table->rememberToken();
                $table->timestamps();
                $table->primary('cst_id');
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
        Schema::dropIfExists('customers');
    }
}
