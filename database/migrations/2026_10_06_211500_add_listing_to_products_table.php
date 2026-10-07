<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListingToProductsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('products') || Schema::hasColumn('products', 'listing')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->text('listing')->nullable();
        });
    }

    public function down()
    {
        if (!Schema::hasTable('products') || !Schema::hasColumn('products', 'listing')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('listing');
        });
    }
}
