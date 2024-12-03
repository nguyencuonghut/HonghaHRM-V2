<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreContractRequest extends FormRequest
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
            'ct_position_id' => 'required',
            'contract_type_id' => 'required',
            'contract_s_date' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'employee_id.required' => 'Số Id nhân sự chưa có.',
            'ct_position_id.required' => 'Bạn cần chọn Vị trí.',
            'contract_type_id.required' => 'Bạn cần chọn loại hợp đồng.',
            'contract_s_date.required' => 'Bạn cần nhập ngày bắt đầu.',
        ];
    }
}
