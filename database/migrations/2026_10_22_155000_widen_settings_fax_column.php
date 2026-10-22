<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WidenSettingsFaxColumn extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        $columns = DB::select("SHOW COLUMNS FROM settings LIKE 'fax'");
        if (!count($columns)) {
            return;
        }

        $type = strtolower((string) ($columns[0]->Type ?? ''));
        if (strpos($type, 'text') !== false) {
            return;
        }

        DB::statement('ALTER TABLE settings MODIFY fax VARCHAR(500) NULL');
    }

    public function down()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        $columns = DB::select("SHOW COLUMNS FROM settings LIKE 'fax'");
        if (!count($columns)) {
            return;
        }

        DB::statement('ALTER TABLE settings MODIFY fax VARCHAR(255) NULL');
    }
}
