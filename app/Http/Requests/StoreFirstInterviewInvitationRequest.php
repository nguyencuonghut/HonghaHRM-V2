<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFirstInterviewInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Ban lãnh đạo' == Auth::user()->role->name || 'Trưởng đơn vị' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recruitment_candidate_id' => 'required',
            'interview_time' => 'required',
            'interview_location' => 'required',
            'contact' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'recruitment_candidate_id.required' => 'Số phiếu đề nghị tuyển dụng không hợp lệ.',
            'interview_time.required' => 'Bạn phải nhập thời gian.',
            'interview_location.required' => 'Bạn phải nhập địa điểm.',
            'contact.required' => 'Bạn phải nhập người liên hệ.',
        ];
    }
}
