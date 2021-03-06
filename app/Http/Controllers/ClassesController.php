<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Classes;
use App\Http\Requests\ClassesRequest;
use App\Http\Requests\ClassLectionRequest;



class ClassesController extends Controller
{

    public function list()
    {
        return response()->json(Classes::all());
    }

    public function info(int $class_id)
    {
        return response()->json(
            Classes::with('students')->findOrFail($class_id)
        );
    }

    public function lections_list(int $class_id)
    {
        return response()->json(
            Classes::with('lections')->findOrFail($class_id)
        );
    }

    public function lections_update(int $class_id, ClassLectionRequest $request)
    {
        $class = Classes::findOrFail($class_id);
        $class->setLections($request->lections());
        return response()->json(['status' => 'OK']);
    }

    public function create(ClassesRequest $request)
    {
        $class = new Classes;
        $class->fill($request->all());
        $class->save();
        return response()->json($class);
    }

    public function update(int $class_id, ClassesRequest $request)
    {
        $class = Classes::findOrFail($class_id);
        $class->fill($request->all());
        $class->save();
        return response()->json($class);
    }

    public function delete(int $class_id)
    {
        Classes::findOrFail($class_id)->delete();
        return response()->json(['status' => 'OK']);
    }
}
