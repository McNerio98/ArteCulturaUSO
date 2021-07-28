<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username')->nullable()->unique();
            $table->string('password',255);
            $table->string('telephone',255);
            $table->unsignedBigInteger('img_profile_id')->nullable(); //sin restrincion 
            $table->boolean('active')->default(true);
            $table->json('rubros')->nullable();
            $table->boolean('is_admin')->default(false);
            $table->enum('status',['enabled','disabled','request'])->default('request');
            $table->string('api_token',60)->nullable()->unique();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
