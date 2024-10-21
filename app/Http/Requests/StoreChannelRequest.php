<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreChannelRequest extends FormRequest
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
             'name' => 'required|unique:channels',
         ];
     }

     public function messages(): array
     {
         return [
             'name.required' => 'Bạn phải nhập tên.',
             'name.unique' => 'Phương tiện đã tồn tại.'
         ];
     }
}
