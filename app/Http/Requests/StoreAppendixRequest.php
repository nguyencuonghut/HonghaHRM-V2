<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAppendixRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Nhân sự' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required',
            'contract_id' => 'required',
            'description' => 'required',
            'reason' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Số id nhân sự không hợp lệ.',
            'contract_id.required' => 'Số id hợp đồng không hợp lệ.',
            'description.required' => 'Bạn phải nhập mô tả.',
            'reason.required' => 'Bạn phải chọn lý do.',
        ];
    }
}
