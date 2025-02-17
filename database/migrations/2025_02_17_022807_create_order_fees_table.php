<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('trip_id')->nullable();
            $table->unsignedBigInteger('gift_id')->nullable();
            $table->unsignedBigInteger('effectivenes_id')->nullable();
            $table->enum('order_type', ['trip','gift','effectivenes']);
            $table->decimal('tax_value', 8, 2)->default(0);
            $table->decimal('payment_way_value', 8, 2)->default(0);
            $table->decimal('admin_value', 8, 2)->default(0);
            $table->decimal('admin_fee_value', 8, 2)->default(0);
            $table->foreignId('vendor_id')->references('id')->on('users');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('order_fees');
    }
};
