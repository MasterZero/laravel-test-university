<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLections extends Model
{
    use HasFactory;

    public $table = 'class_lections';


    public function lection()
    {
        return $this->belongsTo(Lections::class, 'lection_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

}
