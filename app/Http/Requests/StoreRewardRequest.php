<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRewardRequest extends FormRequest
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
            'employee_id' => 'required',
            'code' => 'required|unique:rewards',
            'sign_date' => 'required',
            'content' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Số id nhân viên không hợp lệ.',
            'code.required' => 'Bạn phải nhập số khen thưởng.',
            'code.unique' => 'Số khen thưởng đã tồn tại.',
            'sign_date.required' => 'Bạn phải nhập ngày ký.',
            'content.required' => 'Bạn phải nhập nội dung.',
        ];
    }
}
