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
        Schema::create('user_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('tax_type', ['const','percentage'])->default('percentage');
            $table->decimal('tax_value',8,2)->default(0);
            $table->enum('payment_way_type', ['const','percentage'])->default('percentage');
            $table->decimal('payment_way_value',8,2)->default(0);
            $table->enum('admin_type', ['const','percentage'])->default('percentage');
            $table->decimal('admin_value',8,2)->default(0);
            $table->enum('admin_fee_type', ['const','percentage'])->default('percentage');
            $table->decimal('admin_fee_value',8,2)->default(0);
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
        Schema::dropIfExists('user_fees');
    }
};
