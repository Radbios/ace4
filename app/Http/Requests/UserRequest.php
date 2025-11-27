<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $rules = [
            'cpf'          => ['required', 'string', 'between:11,14', Rule::unique('users', 'cpf')->ignore($this->user?->id)],
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'cep'          => ['nullable', 'string', 'max:20'],
            'city'         => ['nullable', 'string', 'max:100'],
            'state'        => ['nullable', 'string', 'max:100'],
            'country'      => ['nullable', 'string', 'max:100'],
            'is_activate'  => ['boolean'],
        ];

        if(in_array($this->method(), ['PUT', 'PATCH'])) {
            $rules['email'] = ['required', 'email', Rule::unique('users', 'email')->ignore($this->user->id)];
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'cpf'          => 'CPF',
            'name'         => 'nome',
            'email'        => 'e-mail',
            'password'     => 'senha',
            'phone'        => 'telefone',
            'cep'          => 'CEP',
            'city'         => 'cidade',
            'state'        => 'estado',
            'country'      => 'país',
            'is_activate'  => 'status de ativação',
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.required' => 'O :attribute é obrigatório',
            'cpf.unique'   => 'Este :attribute já está cadastrado',
            'cpf.size'     => 'O :attribute deve ter exatamente :size dígitos',

            'name.required' => 'O :attribute é obrigatório',

            'email.required' => 'O :attribute é obrigatório',
            'email.email'    => 'Informe um :attribute válido',
            'email.unique'   => 'Este :attribute já está cadastrado',

            'password.required' => 'A :attribute é obrigatória',
            'password.min'      => 'A :attribute deve ter pelo menos :min caracteres',
            'password.confirmed'      => 'As senhas não conferem',

            'scholarship.integer' => 'O campo :attribute deve ser um número inteiro',

            'phone.max'   => 'O :attribute pode ter no máximo :max caracteres',
            'cep.max'     => 'O :attribute pode ter no máximo :max caracteres',
            'city.max'    => 'A :attribute pode ter no máximo :max caracteres',
            'state.max'   => 'O :attribute pode ter no máximo :max caracteres',
            'country.max' => 'O :attribute pode ter no máximo :max caracteres',

            'is_activate.boolean' => 'O campo :attribute deve ser verdadeiro ou falso',
        ];
    }
}
