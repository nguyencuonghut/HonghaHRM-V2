<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCandidateRequest extends FormRequest
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
            'name' => 'required',
            'phone' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'commune_id' => 'required',
            'addmore.*.school_id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn phải nhập tên.',
            'phone.required' => 'Bạn phải nhập số điện thoại.',
            'date_of_birth.required' => 'Bạn phải nhập ngày sinh.',
            'gender.required' => 'Bạn phải chọn giới tính.',
            'address.required' => 'Bạn phải nhập số nhà, thôn, xóm.',
            'commune_id.required' => 'Bạn phải chọn phường xã.',
            'addmore.*.school_id.required' => 'Bạn phải nhập tên trường.',
        ];
    }
}
