<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLeadsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 16);
            $table->string('address', 255);
            $table->smallInteger('postal_code')->unsigned();
            $table->string('city', 255);
            $table->boolean('newsletter')->default(false);
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
        Schema::drop('leads');
    }

}
