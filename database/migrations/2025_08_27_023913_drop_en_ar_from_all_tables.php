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
        // foreach all_tables any columns end with _en or _ar drop it
        $tables = Schema::getAllTables();
        foreach ($tables as $table) {
            $columns = Schema::getColumnListing($table);
            foreach ($columns as $column) {
                if (str_ends_with($column, '_en') || str_ends_with($column, '_ar')) {
                    Schema::table($table, function (Blueprint $table) use ($column) {
                        $table->dropColumn($column);
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
};
