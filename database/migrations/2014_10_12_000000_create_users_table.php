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
            $table->string('name')->nullable();
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->string('image')->nullable();
            $table->string('code')->nullable();
            $table->date('birthdate')->nullable();
            $table->date('joining_date_from')->nullable();
            $table->date('joining_date_to')->nullable();
            $table->string('address')->nullable();
            $table->string('nationality')->nullable();
            $table->tinyInteger('type');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('reset_code')->nullable();
            $table->rememberToken();
            $table->boolean('active')->default(true);
            $table->enum('status', ['pendding','accepted','rejected'])->default('pendding');
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('users');
    }
}
