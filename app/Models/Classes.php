<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
