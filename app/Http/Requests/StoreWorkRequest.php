<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreWorkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Ban lãnh đạo' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contract_code' => 'required',
            'employee_id' => 'required',
            'position_id' => 'required',
            'on_type_id' => 'required',
            's_date' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'contract_code.required' => 'Bạn phải nhập số hợp đồng.',
            'employee_id.required' => 'Số Id nhân sự chưa có.',
            'position_id.required' => 'Bạn cần chọn Vị trí.',
            'on_type_id.required' => 'Bạn cần chọn Phân loại tạo.',
            's_date.required' => 'Bạn cần nhập ngày bắt đầu.',
        ];
    }
}
