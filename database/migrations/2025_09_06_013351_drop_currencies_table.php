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
        Schema::dropIfExists("user_prefered_settings");
        Schema::dropIfExists("currencies");
        Schema::create("currencies", function (Blueprint $table) {

            $table->id();
            $table->string('code')->unique(); // e.g. USD, EUR, EGP
            $table->string('symbol')->nullable(); // $, €, £
            $table->decimal('rate', 15, 8); // conversion rate against SAR
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
        Schema::dropIfExists("currencies");
        //
    }
};
