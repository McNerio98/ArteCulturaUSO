<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsEventsMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts_events_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_event_id');
            $table->string('key',45);
            $table->longText('value');
            $table->timestamps();
            $table->foreign('post_event_id')->references('id')->on('post_events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_events_metas');
    }
}
