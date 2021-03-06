<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
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
        return [
            'section_name' => 'required|max:255|unique:sections,section_name,' . $this->id,
            'description'  => 'nullable'
        ];
    }


    public function messages()
    {
        return [
            'section_name.required' => 'الرجاء ادخال اسم القسم',
            'section_name.unique'   => 'عفوا اسم القسم مسجل مسبقا',
        ];
    }
}
