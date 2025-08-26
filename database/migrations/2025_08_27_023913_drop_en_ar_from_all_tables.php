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
        foreach ($tables as $tableObj) {
            // For MySQL, the property is 'Tables_in_' . dbname
            $tableName = null;
            foreach ($tableObj as $key => $value) {
                $tableName = $value;
                break;
            }
            if (!$tableName) {
                continue;
            }
            $columns = Schema::getColumnListing($tableName);
            foreach ($columns as $column) {
                if (str_ends_with($column, '_en') || str_ends_with($column, '_ar')) {
                    Schema::table($tableName, function (Blueprint $table) use ($column) {
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
