<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('infected')->nullable();
            $table->bigInteger('tested')->nullable();
            $table->bigInteger('recovered')->nullable();
            $table->bigInteger('deceased')->nullable();
            $table->string('country');
            $table->string('source_url')->nullable();
            $table->dateTime('last_updated_at_source')->nullable();
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
        Schema::dropIfExists('current_data');
    }
}
