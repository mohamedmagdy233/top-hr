<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (request()->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'user_id' => 'required',
            'check_in' => 'required',
            'check_out' => 'nullable',
            'lat' => 'required',
            'long' => 'required',
            'image' => 'required',
            'date' => 'required',
        ];
    }

    protected function update(): array
    {
        return [
            'user_id' => 'required',
            'check_in' => 'required',
            'check_out' => 'nullable',
            'lat' => 'required',
            'long' => 'required',
            'image' => 'required',
            'date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => trns('input_name_must_be_required'),
            'hours.required' => trns('input_hours_must_be_required'),
            'advances.required' => trns('input_advances_must_be_required'),
        ];
    }
}
