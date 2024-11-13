<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateDisciplineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role->name == 'Admin' || Auth::user()->role->name == 'Nhân sự';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|unique:disciplines,code,'.$this->discipline->id,
            'sign_date' => 'required',
            'content' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Bạn phải nhập số kỷ luật.',
            'code.unique' => 'Số kỷ luật đã tồn tại.',
            'sign_date.required' => 'Bạn phải nhập ngày ký.',
            'content.required' => 'Bạn phải nhập nội dung.',
        ];
    }
}