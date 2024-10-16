<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ApproveRecruitmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ('Đã kiểm tra' == $this->recruitment->status
        || 'Đã duyệt' == $this->recruitment->status)
        && 'Ban lãnh đạo' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'approver_result' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'approver_result.required' => 'Bạn phải chọn kết quả.',
        ];
    }
}
