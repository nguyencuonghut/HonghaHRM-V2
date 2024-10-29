<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateEmployeeRequest extends FormRequest
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
            'code' => 'required|unique:employees,code,'.$this->employee->id,
            'name' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'relative_phone' => 'required',
            'date_of_birth' => 'required',
            'cccd' => 'required|unique:employees,cccd,'.$this->employee->id,
            'name' => 'required',
            'issued_date' => 'required',
            'issued_by' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'commune_id' => 'required',
            'addmore.*.school_id' => 'required',
            'experience' => 'required',
            'join_date' => 'required',
            'marriage_status' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Bạn phải nhập mã.',
            'code.unique' => 'Mã đã tồn tại.',
            'name.required' => 'Bạn phải nhập tên.',
            'phone.required' => 'Bạn phải nhập số điện thoại.',
            'relative_phone.required' => 'Bạn phải nhập số điện thoại người thân.',
            'date_of_birth.required' => 'Bạn phải nhập ngày sinh.',
            'cccd.required' => 'Bạn phải nhập số CCCD.',
            'cccd.unique' => 'Số CCCD đã tồn tại.',
            'issued_date.required' => 'Bạn phải nhập ngày cấp.',
            'issued_by.required' => 'Bạn phải nhập nơi cấp.',
            'gender.required' => 'Bạn phải chọn giới tính.',
            'address.required' => 'Bạn phải nhập số nhà, thôn, xóm.',
            'commune_id.required' => 'Bạn phải chọn Xã Phường.',
            'addmore.*.school_id.required' => 'Bạn phải nhập tên trường.',
            'experience.required' => 'Bạn phải nhập kinh nghiệm.',
            'join_date.required' => 'Bạn phải nhập ngày vào.',
            'marriage_status.required' => 'Bạn phải nhập tình trạng hôn nhân.',
        ];
    }
}
