<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lections extends Model
{
    use HasFactory;

    public $table = 'lections';

    public $fillable = [
        'subject',
        'description',
    ];

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_lections', 'class_id', 'lection_id')->withPivot('planned_at');
    }

    public function passClasses()
    {
        return $this->belongsToMany(Classes::class, 'class_lections', 'class_id', 'lection_id')
            ->withPivot('planned_at')
            ->where('planned_at', '<=', date('Y-m-d H:i:s'));
    }
}
