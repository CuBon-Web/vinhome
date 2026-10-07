<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeProductPricesToDecimal extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        DB::statement('ALTER TABLE products MODIFY price DECIMAL(12,2) NULL');
        DB::statement('ALTER TABLE products MODIFY discount DECIMAL(12,2) NULL');
    }

    public function down()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        DB::statement('ALTER TABLE products MODIFY price BIGINT NULL');
        DB::statement('ALTER TABLE products MODIFY discount INT NULL');
    }
}
