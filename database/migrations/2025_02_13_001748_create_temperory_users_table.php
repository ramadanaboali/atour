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
        Schema::create('temperory_users', function (Blueprint $table) {

            $table->id();
            $table->string('name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('image')->nullable();
            $table->string('code', 50)->nullable();
            $table->date('birthdate')->nullable();
            $table->date('joining_date_from')->nullable();
            $table->date('joining_date_to')->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('type')->unsigned();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 60);
            $table->timestamps();
            $table->boolean('can_cancel')->default(false);
            $table->boolean('can_pay_later')->default(false);
            $table->boolean('ban_vendor')->default(false);
            $table->boolean('pay_on_deliver')->default(false);
            $table->boolean('tour_guid')->default(false);
            $table->longText('rerequest_reason')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->string('streat')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->longText('description')->nullable();
            $table->longText('short_description')->nullable();
            $table->string('url')->nullable();
            $table->boolean('profission_guide')->default(false);
            $table->string('job')->nullable();
            $table->string('experience_info')->nullable();
            $table->longText('languages')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('place_summary')->nullable();
            $table->string('place_content')->nullable();
            $table->string('expectations')->nullable();
            $table->string('general_name')->nullable();
            $table->string('nationality')->nullable();
            $table->string('licence_image')->nullable();
            $table->string('profile')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temperory_users');
    }
};
