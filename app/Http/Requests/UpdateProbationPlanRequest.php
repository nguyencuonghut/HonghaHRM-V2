<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProbationPlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return 'Admin' == Auth::user()->role->name || 'Trưởng đơn vị' == Auth::user()->role->name;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'work_title' => 'required',
            'work_requirement' => 'required',
            'work_deadline' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'work_title.required' => 'Bạn phải nhập nội dung công việc.',
            'work_requirement.required' => 'Bạn phải nhập yêu cầu.',
            'work_deadline.required' => 'Bạn phải nhập deadline.',
        ];
    }
}
