<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string("gender")->nullable();
            $table->string("image")->nullable();
            $table->string('breed')->nullable();
            $table->string('address')->nullable();
            $table->string('age')->nullable();
            $table->string('weight')->nullable();
            $table->string('medicalCondition')->nullable();
            $table->string('temperament')->nullable();
            $table->tinyInteger('neutered')->nullable();
            $table->string('identity_code')->nullable();
            $table->date('created');
            $table->date('updated')->nullable();
            $table->boolean('paid')->default(false);
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pets');
    }
}
