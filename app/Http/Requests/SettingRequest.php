<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
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
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
            'title_ar' => 'required',
            'title_en' => 'required',

        ];
    }

    protected function update(): array
    {
        return [
            'logo' => 'nullable|image',
            'favicon' => 'nullable|image',
            'title_ar' => 'nullable',
            'title_en' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'logo.image' => trns('input_logo_must_be_image'),
            'favicon.image' => trns('input_logo_must_be_image'),
        ];
    }
}
