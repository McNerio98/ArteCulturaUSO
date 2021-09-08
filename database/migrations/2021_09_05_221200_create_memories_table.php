<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('type',['biography','memory'])->default('biography');
            $table->string("area",150);
            $table->string("name",100);
            $table->string("other_name",100);
            $table->date("birth_date");
            $table->date("death_date");
            $table->mediumText("content");
            $table->unsignedBigInteger('presentation_img')->nullable(); //sin restrincion 
            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->enum('status',['review','approved','deleted'])->default('review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memories');
    }
}
