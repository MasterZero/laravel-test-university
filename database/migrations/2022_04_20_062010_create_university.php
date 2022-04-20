<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200)->unique();
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('email', 200)->unique();
            $table->string('name', 200);
            $table->foreignId('class_id')->nullable()->references('id')->on('classes')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('lections', function (Blueprint $table) {
            $table->id();
            $table->string('subject', 200)->unique();
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('class_lections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->references('id')->on('classes');
            $table->foreignId('lection_id')->references('id')->on('lections');
            $table->timestamp('planned_at');
            $table->timestamps();
            $table->unique([
                'class_id',
                'lection_id',
            ]);
            $table->unique([
                'lection_id',
                'planned_at',
            ]);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_lections');
        Schema::dropIfExists('lections');
        Schema::dropIfExists('students');
        Schema::dropIfExists('classes');
    }
}
