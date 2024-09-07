<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('orders');
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('tourist_name')->nullable();
            $table->string('tourist_email')->nullable();
            $table->string('tourist_phone')->nullable();
            $table->string('promocode')->nullable();
            $table->decimal('promocode_value')->nullable();
            $table->tinyInteger('payment_type')->nullable();
            $table->tinyInteger('payment_status')->default(0);
            $table->date('order_date')->nullable();
            $table->time('order_time')->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->longText('details')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->integer('members')->nullable();
            $table->integer('childrens')->nullable();
            $table->integer('adults')->nullable();
            $table->foreignId('trip_id')->references('id')->on('trips')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->softDeletes();
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
};
