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
        Schema::create('booking_gifts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->enum('payment_way', ['cash','online'])->default('cash');
            $table->text('payment_id')->nullable();
            $table->enum('delivery_way',['myself','delivery'])->default('myself');
            $table->text('delivery_address')->nullable();
            $table->string('payment_status')->default('pendding');
            $table->integer('quntity')->default(1);
            $table->decimal('total', 10, 2)->default(0);
            $table->foreignId('gift_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('booking_gifts');
    }
};
