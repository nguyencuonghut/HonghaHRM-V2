<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReviewRecruitmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ('Mở' == $this->recruitment->status
                || 'Đã kiểm tra' == $this->recruitment->status)
                && 'Nhân sự' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reviewer_result' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'reviewer_result.required' => 'Bạn phải chọn kết quả.',
        ];
    }
}
