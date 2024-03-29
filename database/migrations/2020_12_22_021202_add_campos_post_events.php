<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposPostEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post_events', function (Blueprint $table) {
            $table->boolean('is_popular')->default(false);
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
        Schema::table('post_events', function (Blueprint $table) {
            $table->dropColumn('is_popular');
            $table->dropColumn('status');
        });
    }
}
