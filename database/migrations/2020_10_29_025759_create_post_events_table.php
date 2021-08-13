<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_events', function (Blueprint $table) {
            $table->id();
            $table->string("title",100);
            $table->string('content',800);
            $table->enum('type_post',['post','event'])->default('post');
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('presentation_img')->nullable(); //sin restrincion 
            $table->timestamps(); //este es el datepost, es decir cuando se publico el post 
            $table->foreign('creator_id')->references('id')->on('users');
            $table->index("title"); //plain index for search
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_events');
    }
}
