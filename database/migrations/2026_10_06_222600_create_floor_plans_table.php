<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloorPlansTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('floor_plans')) {
            return;
        }

        Schema::create('floor_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('eyebrow')->nullable();
            $table->string('title')->nullable();
            $table->text('intro')->nullable();
            $table->string('building_name')->nullable();
            $table->string('footnote_title')->nullable();
            $table->text('footnote')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('floor_plans');
    }
}
