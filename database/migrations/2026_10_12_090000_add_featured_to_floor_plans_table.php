<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddFeaturedToFloorPlansTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('floor_plans')) {
            return;
        }
        if (count(DB::select("SHOW COLUMNS FROM floor_plans LIKE 'featured'"))) {
            return;
        }

        DB::statement('ALTER TABLE floor_plans ADD featured TINYINT NOT NULL DEFAULT 0 AFTER status');
    }

    public function down()
    {
        if (!Schema::hasTable('floor_plans')) {
            return;
        }
        if (!count(DB::select("SHOW COLUMNS FROM floor_plans LIKE 'featured'"))) {
            return;
        }

        DB::statement('ALTER TABLE floor_plans DROP COLUMN featured');
    }
}
