<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PotentialCustomerUpdateRequest extends FormRequest
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
        $leadId = $this->route('lead');

        return [
            'nama' => ['required', 'string', 'max:255'],

            'no_wa' => ['required', 'string', 'max:30'],

            'email' => [
                'required',
                'email',
                Rule::unique('potential_customers', 'email')
                    ->ignore($leadId, 'id')
            ],

            'nama_lembaga' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi',
            'no_wa.required' => 'No WhatsApp wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah dipakai oleh calon customer lain',
            'nama_lembaga.required' => 'Nama lembaga wajib diisi',
        ];
    }
}
