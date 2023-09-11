<?php

namespace App\Http\Requests\Student;

use App\Enums\StudentStatusEnum;
use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
              'required',
              'string',
               'min:2',
               'max:50'
            ],
            'gender' => [
              'required',
              'boolean',
            ],
            'birthdate' => [
              'required',
              'date',
              'before:today',
            ],
            'status' => [
                'required',
                Rule::in(StudentStatusEnum::asArray()),
            ],
            'avatar' => [
                'nullable',
                'file',
                'image',
            ],
            'course_id' => [
              'required',
              Rule::exists(Course::class, 'id')
            ]
        ];
    }
}
