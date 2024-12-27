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
        Schema::table('effectivenes', function (Blueprint $table) {
            $table->dropColumn('title_ar');
            $table->dropColumn('title_en');
            $table->dropColumn('description_en');
            $table->dropColumn('description_ar');
            $table->longText('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('effectivenes', function (Blueprint $table) {
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->longText('description_en')->nullable();
            $table->longText('description_ar')->nullable();
            $table->dropColumn('description');

        });
    }
};
