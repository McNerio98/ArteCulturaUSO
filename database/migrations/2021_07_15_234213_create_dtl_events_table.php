<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDtlEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtl_events', function (Blueprint $table) {
            $table->id();
            $table->dateTime('event_date',0);
            $table->enum('frequency',['unique','repeat']);
            $table->boolean('has_cost')->default(false);
            $table->decimal('cost',8,2);            
            $table->foreignId('event_id')->constrained("post_events")->onDelete('cascade');
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
        Schema::dropIfExists('dtl_events');
    }
}
