<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PotentialCustomerStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:150'],
            'no_wa' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150', 'unique:potential_customers,email'],
            'nama_lembaga' => ['required', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi',
            'no_wa.required' => 'No WhatsApp wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'nama_lembaga.required' => 'Nama lembaga wajib diisi',
        ];
    }
}
