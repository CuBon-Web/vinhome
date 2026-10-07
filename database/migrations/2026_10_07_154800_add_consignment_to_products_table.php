<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddConsignmentToProductsTable extends Migration
{
    public function up()
    {
        $column = DB::select("SHOW COLUMNS FROM products LIKE 'consignment'");
        if (count($column)) {
            return;
        }

        DB::statement('ALTER TABLE products ADD consignment TINYINT UNSIGNED NOT NULL DEFAULT 0');
    }

    public function down()
    {
        $column = DB::select("SHOW COLUMNS FROM products LIKE 'consignment'");
        if (!count($column)) {
            return;
        }

        DB::statement('ALTER TABLE products DROP COLUMN consignment');
    }
}
