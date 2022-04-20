<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;


class ClassesController extends Controller
{

    public function list()
    {
        $ret = Classes::with([ /* 'students' */ 'lections'])->get();

        return response()->json($ret);
    }

    public function info()
    {
        return response()->json(['cat' => '(^◔ᴥ◔^)']);
    }

    public function lections_list()
    {
        return response()->json(['cat' => '(^◔ᴥ◔^)']);
    }

    public function lections_update()
    {
        return response()->json(['cat' => '(^◔ᴥ◔^)']);
    }

    public function create()
    {
        return response()->json(['cat' => '(^◔ᴥ◔^)']);
    }

    public function update()
    {
        return response()->json(['cat' => '(^◔ᴥ◔^)']);
    }

    public function delete()
    {
        return response()->json(['cat' => '(^◔ᴥ◔^)']);
    }
}
