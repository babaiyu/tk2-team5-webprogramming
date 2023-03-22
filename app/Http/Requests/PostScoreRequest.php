<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostScoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required',
            'nim' => [
                'required',
                'min:10',
                'max:10',
            ],
            'score_quis' => [
                'required',
                'regex:/^(100(\.0+)?|\d{1,2}(\.\d+)?|0(\.\d+)?)$/',
            ],
            'score_tugas' => [
                'required',
                'regex:/^(100(\.0+)?|\d{1,2}(\.\d+)?|0(\.\d+)?)$/',
            ],
            'score_presensi' => [
                'required',
                'regex:/^(100(\.0+)?|\d{1,2}(\.\d+)?|0(\.\d+)?)$/',
            ],
            'score_praktek' => [
                'required',
                'regex:/^(100(\.0+)?|\d{1,2}(\.\d+)?|0(\.\d+)?)$/',
            ],
            'score_uas' => [
                'required',
                'regex:/^(100(\.0+)?|\d{1,2}(\.\d+)?|0(\.\d+)?)$/',
            ],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation Errors',
            'data' => $validator->errors(),
        ]));
    }

    public function messages()
    {
        return [
            'fullname.required' => 'Fullname is required!',
            'nim.required' => 'NIM is required!',
            'score_quis.required' => 'Quis is required!',
            'score_quis.regex' => 'Score Quis must be between 0 - 100',
            'score_tugas.required' => ' Tugas is required!',
            'score_tugas.regex' => 'Score Tugas must be between 0 - 100',
            'score_presensi.required' => 'Presensi is required!',
            'score_presensi.regex' => 'Score Presensi must be between 0 - 100',
            'score_praktek.required' => 'Praktek is required!',
            'score_praktek.regex' => 'Score Praktek must be between 0 - 100',
            'score_uas.required' => 'UAS is required!',
            'score_uas.regex' => 'Score UAS must be between 0 - 100',
        ];
    }

    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;
}
