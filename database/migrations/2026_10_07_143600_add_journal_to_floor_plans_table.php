<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddJournalToFloorPlansTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('floor_plans')) {
            return;
        }

        $exists = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM floor_plans LIKE 'journal'");
        if (count($exists)) {
            return;
        }

        \Illuminate\Support\Facades\DB::statement('ALTER TABLE floor_plans ADD journal LONGTEXT NULL AFTER content');
    }

    public function down()
    {
        if (!Schema::hasTable('floor_plans')) {
            return;
        }
        $exists = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM floor_plans LIKE 'journal'");
        if (!count($exists)) {
            return;
        }
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE floor_plans DROP COLUMN journal');
    }
}
