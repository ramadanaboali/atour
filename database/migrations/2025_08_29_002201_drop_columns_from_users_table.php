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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['temperory_email', 'temperory_phone','joining_date_from','joining_date_to','can_cancel','can_pay_later','ban_vendor','pay_on_deliver','bank_account','bank_name','bank_iban','tax_number','admin_value_type','admin_value']);
            // $table->unsignedBigInteger('country_id')->nullable()->after('city_id');
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['tour_guid','profission_guide','experience_info','place_summary','place_content','expectations','languages','nationality','country_id','city_id']);
        });
        Schema::table('suppliers', function (Blueprint $table) {
            $table->boolean('can_pay_later')->default(false);
            $table->boolean('can_cancel')->default(false);
            $table->boolean('pay_on_deliver')->default(false);
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('licence_file')->nullable();
            $table->string('tax_file')->nullable();
            $table->string('commercial_register')->nullable();
            $table->string('other_files')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['country_id']);

        });
    }
};
