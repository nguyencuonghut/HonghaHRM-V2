<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRecruitmentCandidateRequest extends FormRequest
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
            'recruitment_id' => 'required',
            'candidate_id' => 'required',
            'channel_id' => 'required',
            'batch' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'recruitment_id.required' => 'Số phiếu đề nghị tuyển dụng không hợp lệ.',
            'candidate_id.required' => 'Bạn phải chọn tên ứng viên.',
            'channel_id.required' => 'Bạn phải chọn nguồn nhận CV.',
            'batch.required' => 'Bạn phải chọn đợt.',
        ];
    }
}
