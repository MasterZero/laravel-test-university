<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeClassLections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_lections', function (Blueprint $table) {
            $table->dropForeign('class_lections_class_id_foreign');
            $table->dropForeign('class_lections_lection_id_foreign');

            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('lection_id')->references('id')->on('lections')->cascadeOnUpdate()->cascadeOnDelete();
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
            $table->dropForeign('class_lections_class_id_foreign');
            $table->dropForeign('class_lections_lection_id_foreign');

            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('lection_id')->references('id')->on('lections');
        });
    }
}
