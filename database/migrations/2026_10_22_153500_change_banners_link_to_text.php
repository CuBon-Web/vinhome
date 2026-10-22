<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeBannersLinkToText extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('banners')) {
            return;
        }

        $columns = DB::select("SHOW COLUMNS FROM banners LIKE 'link'");
        if (!count($columns)) {
            return;
        }

        $type = strtolower((string) ($columns[0]->Type ?? ''));
        if (strpos($type, 'text') !== false) {
            return;
        }

        DB::statement('ALTER TABLE banners MODIFY link TEXT NULL');
    }

    public function down()
    {
        if (!Schema::hasTable('banners')) {
            return;
        }

        $columns = DB::select("SHOW COLUMNS FROM banners LIKE 'link'");
        if (!count($columns)) {
            return;
        }

        DB::statement('ALTER TABLE banners MODIFY link VARCHAR(255) NULL');
    }
}
