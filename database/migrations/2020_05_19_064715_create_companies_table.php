<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('logo',64);
            $table->string('name',32);
            $table->string('email', 32);
            $table->text('description');
            $table->text('address');
            $table->string('phone', 16);
            $table->string('whatsapp', 16);
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('testimonial')->nullable();
            $table->string('va', 16)->nullable();
            $table->string('api_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
