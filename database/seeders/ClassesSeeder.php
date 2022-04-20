<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;
use App\Models\Students;
use App\Models\Lections;
use App\Models\ClassLections;
use Faker;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lections = Lections::factory()->count(60)->create();


        $classes = Classes::factory()
            ->count(10)
            ->has(Students::factory()->count(20))
            ->create();

        
        $faker = Faker\Factory::create();

        foreach ($lections as $lection) {
            foreach($classes as $class) {
                $classLection = new ClassLections;
                $classLection->class_id = $class->id;
                $classLection->lection_id = $lection->id;
                $classLection->planned_at = $faker->dateTime() /*date('Y-m-d H:i:s') */;
                $classLection->save();
            }
        }
    }
}
