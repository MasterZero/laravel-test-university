<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Lections;
use App\Models\ClassLections;
use Illuminate\Support\Facades\Validator;

class ClassLectionRequest extends FormRequest
{

    public function lections() : array
    {
        return json_decode($this->table, 1);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'table' => [
                'required',
                'json',
                function ($attribute, $value, $fail) {
                    $lectionsArr = json_decode($value, 1);
                    $validationRules = [
                        'lection_id' => 'required|exists:lections,id',
                        'planned_at' => 'required|date',
                    ];
                    foreach($lectionsArr as $id => $lectionArr) {
                        if (!is_array($lectionArr)) {
                            $fail('The '.$attribute.' has invalid json entry.');
                        }
                        $validator = Validator::make($lectionArr, $validationRules);

                        foreach($validator->errors()->all() as $message) {
                            $fail($message);
                        }

                        if ($validator->fails()) {
                            return;
                        }

                        foreach($lectionsArr as $id2 => $lectionArr2) {
                            if ($id == $id2) {
                                continue;
                            }

                            if (Validator::make($lectionArr2, $validationRules)->fails()) {
                                continue;
                            }

                            if ($lectionArr['planned_at'] == $lectionArr2['planned_at']) {
                                $fail('double entry for planned_at ' . $lectionArr['planned_at']);
                            }

                            if ($lectionArr['lection_id'] == $lectionArr2['lection_id']) {
                                $fail('double entry for lection_id ' . $lectionArr['lection_id']);
                            }
                        }
                    }
                }
            ],
        ];
    }
}
