<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorPlanCategoriesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('floor_plan_categories')) {
            return;
        }

        Schema::create('floor_plan_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        $now = now();
        $names = [
            'Vinhomes Central Park',
            'Khu Central',
            'Khu Landmark',
            'Khu Park',
            'Vinhomes Golden River',
        ];
        foreach ($names as $index => $name) {
            \Illuminate\Support\Facades\DB::table('floor_plan_categories')->insert([
                'name' => $name,
                'slug' => to_slug($name),
                'sort' => $index + 1,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('floor_plan_categories');
    }
}
