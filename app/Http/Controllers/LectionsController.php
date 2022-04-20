<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lections;
use App\Http\Requests\LectionsRequest;

class LectionsController extends Controller
{
    public function list()
    {
        return response()->json(Lections::all());
    }

    public function info($id)
    {
        /**
         * @TODO: add classes and students
        */
        $ret = Lections::findOrFail($id);
        return response()->json($ret->toArray());
    }

    public function create(LectionsRequest $request)
    {
        $lection = new Lections;
        $lection->fill($request->all());
        $lection->save();
        return response()->json($lection->toArray());
    }

    public function update($id, LectionsRequest $request)
    {
        $lection = Lections::findOrFail($id);
        $lection->fill($request->all());
        $lection->save();
        return response()->json($lection->toArray());
    }

    public function delete($id)
    {
        Lections::findOrFail($id)->delete();
        return response()->json(['status' => 'OK']);
    }
}
