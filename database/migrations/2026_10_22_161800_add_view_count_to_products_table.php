<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddViewCountToProductsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        if (count(DB::select("SHOW COLUMNS FROM products LIKE 'view_count'"))) {
            return;
        }

        DB::statement('ALTER TABLE products ADD view_count INT UNSIGNED NOT NULL DEFAULT 0 AFTER home_status');
    }

    public function down()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        if (!count(DB::select("SHOW COLUMNS FROM products LIKE 'view_count'"))) {
            return;
        }

        DB::statement('ALTER TABLE products DROP COLUMN view_count');
    }
}
