<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateContractRequest extends FormRequest
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
            'position_id' => 'required',
            'contract_type_id' => 'required',
            's_date' => 'required',
            'created_type' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'position_id.required' => 'Bạn cần chọn Vị trí.',
            'contract_type_id.required' => 'Bạn cần chọn loại hợp đồng.',
            's_date.required' => 'Bạn cần nhập ngày bắt đầu.',
            'created_type.required' => 'Bạn cần nhập loại tạo.',
        ];
    }
}
