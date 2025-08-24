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
        // Schema::create('currencies', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('code', 3)->unique(); // ISO 4217 code
        //     $table->string('name');              // English name
        //     $table->text('encyclopedia')->nullable(); // Description
        //     $table->timestamps();
        // });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
