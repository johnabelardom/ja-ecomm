<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('email');
            $table->text('firstname');
            $table->text('lastname');
            $table->text('line_1_address');
            $table->text('line_2_address')->nullable();
            $table->text('city')->nullable();
            $table->string('country')->default('United States')->nullable();
            $table->text('zipcode')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('new');
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
        Schema::dropIfExists('orders');
    }
}
