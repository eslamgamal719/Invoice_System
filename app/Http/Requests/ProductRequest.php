<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'product_name' => 'required|max:255',
            'description' => 'nullable',
            'section_id' => 'required|numeric',
        ];
    }


    public function messages()
    {
        return [
            'product_name.required' => 'الرجاء ادخال اسم المنتج',
            'section_id.required' => 'الرجاء اختيار القسم',
            'section_id.numeric' => 'عفوا هذا القسم غير صحيح',
        ];
    }
}
