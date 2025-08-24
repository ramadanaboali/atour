<?php

use GuzzleHttp\Psr7\DroppingStream;
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
        Schema::table('f_a_q_s', function (Blueprint $table) {
          $table->dropColumn('question');
          $table->dropColumn('answer');
        });
        Schema::create('f_a_q_translations', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('faq_id');
            $table->string('locale', 5); // en, ar, fr
            $table->text('question')->nullable();
            $table->longText('answer')->nullable();
            $table->timestamps();
            $table->unique(['faq_id', 'locale']); // كل لغة مرة واحدة فقط

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('f_a_q_translations');
    }
};
