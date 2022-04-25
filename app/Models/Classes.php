<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Classes extends Model
{
    use HasFactory;


    public $table = 'classes';

    public $fillable = [
        'name',
    ];

    public function students()
    {
        return $this->hasMany(Students::class, 'class_id', 'id');
    }

    public function classLections()
    {
        return $this->hasMany(ClassLections::class, 'class_id', 'id');
    }

    public function lections()
    {
        return $this->belongsToMany(Lections::class, 'class_lections', 'class_id', 'lection_id')->withPivot('planned_at');
    }

    public function passLections()
    {
        return $this->belongsToMany(Lections::class, 'class_lections', 'class_id', 'lection_id')
            ->withPivot('planned_at')
            ->where('planned_at', '<=', date('Y-m-d H:i:s'));
    }

    public function setLections(array $lectionsArr)
    {
        DB::beginTransaction();
        ClassLections::where('class_id', $this->id)->delete();
        foreach($lectionsArr as $lectionData) {
            $this->lections()->attach($lectionData['lection_id'], ['planned_at' => $lectionData['planned_at']]);
        }
        DB::commit();
        return $this;
    }
}
