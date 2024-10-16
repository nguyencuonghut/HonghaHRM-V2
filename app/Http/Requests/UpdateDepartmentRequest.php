<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

class UpdateDepartmentRequest extends FormRequest
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
            'name' => 'required|unique:departments,name,'.$this->department->id,
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Bạn phải nhập tên.',
            'name.unique' => 'Tên bị trùng',
        ];
    }
}
