<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Students;
use App\Http\Requests\StudentsRequest;


class StudentsController extends Controller
{

    public function list()
    {
        return response()->json(Students::all());
    }

    public function info($id)
    {
        return response()->json(
            Students::with('class.passLections')->findOrFail($id)
        );
    }

    public function create(StudentsRequest $request)
    {
        $model = new Students;
        $model->fill($request->all());
        $model->save();
        return response()->json($model);
    }

    public function update($id, StudentsRequest $request)
    {
        $model = Students::findOrFail($id);
        $model->fill($request->all());
        $model->save();
        return response()->json($model);
    }

    public function delete($id)
    {
        Students::findOrFail($id)->delete();
        return response()->json(['status' => 'OK', 'cat' => '(^◔ᴥ◔^)']);
    }
}
