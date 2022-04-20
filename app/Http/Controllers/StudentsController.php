<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;

class StudentsController extends Controller
{

    public function list()
    {
        $ret = Students::with('class')->get();

        return response()->json($ret);
    }

    public function info()
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
