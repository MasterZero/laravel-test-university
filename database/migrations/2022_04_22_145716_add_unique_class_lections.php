<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueClassLections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_lections', function (Blueprint $table) {
            $table->unique([
                'class_id',
                'planned_at',
            ], 'unique_class_planned_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_lections', function (Blueprint $table) {
            $table->dropUnique('unique_class_planned_at');
        });
    }
}
