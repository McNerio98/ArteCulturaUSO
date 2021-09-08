<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesOnMemoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_on_memories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name",500);
            $table->enum('type_file',['image','video','docfile'])->default('image');
            $table->foreignId('memory_id')->constrained();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files_on_memories');
    }
}