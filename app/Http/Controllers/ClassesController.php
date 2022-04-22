<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Classes;
use App\Http\Requests\ClassesRequest;


class ClassesController extends Controller
{

    public function list()
    {
        return response()->json(Classes::all());
    }

    public function info($class_id)
    {
        return response()->json(
            Classes::with('students')->findOrFail($class_id)
        );
    }

    public function lections_list($class_id)
    {
        return response()->json(
            Classes::with('lections')->findOrFail($class_id)
        );
    }

    public function lections_update($class_id)
    {
        /**
         * @TODO: fill it
        */
        return response()->json(['cat' => '(^◔ᴥ◔^)']);
    }

    public function create(ClassesRequest $request)
    {
        $class = new Classes;
        $class->fill($request->all());
        $class->save();
        return response()->json($class);
    }

    public function update($class_id, ClassesRequest $request)
    {
        $class = Classes::findOrFail($class_id);
        $class->fill($request->all());
        $class->save();
        return response()->json($class);
    }

    public function delete($class_id)
    {
        Classes::findOrFail($class_id)->delete();
        return response()->json(['status' => 'OK', 'cat' => '(^◔ᴥ◔^)']);
    }
}
