<?php

namespace App\Http\Requests;
use App\Rules\CpfValid;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user')->id;

        return [

            // Usuário
            'name'       => ['required', 'string', 'min:3', 'max:150'],
            'email'      => ['required', 'email', 'max:150', "unique:users,email,{$id}"],            
            'cpf' => [
                'required',
                'digits:11',
                Rule::unique('users', 'cpf')->ignore($this->user),
                new CpfValid
            ],
            'profile_id' => ['required', 'exists:profiles,id'],

            // Endereços
            'addresses' => ['required', 'array', 'min:1'],
            'addresses.*.street'       => ['required_with:addresses.*.city,addresses.*.state', 'string', 'max:255'],
            'addresses.*.number'       => ['nullable', 'string', 'max:20'],
            'addresses.*.complement'   => ['nullable', 'string', 'max:100'],
            'addresses.*.neighborhood' => ['required_with:addresses.*.street', 'string', 'max:100'],
            'addresses.*.city'         => ['required_with:addresses.*.street', 'string', 'max:150'],
            'addresses.*.state'        => ['required_with:addresses.*.street', 'string', 'size:2'],
            'addresses.*.zip'          => ['nullable', 'digits_between:5,9'],
            'addresses.*.country'      => ['required_with:addresses.*.state', 'string', 'max:100'],
        ];
    }

    public function prepareForValidation()
    {
        // Normaliza CPF
        if ($this->has('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/\D/', '', $this->cpf),
            ]);
        }

        // Normaliza CEP dos endereços
        if ($this->has('addresses')) {
            $addresses = $this->addresses;

            foreach ($addresses as $i => $addr) {
                if (!empty($addr['zip'])) {
                    $addresses[$i]['zip'] = preg_replace('/\D/', '', $addr['zip']);
                }
            }

            $this->merge(['addresses' => $addresses]);
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail informado não é válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.digits' => 'O CPF deve ter exatamente 11 dígitos.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'profile_id.required' => 'O perfil é obrigatório.',

            // Endereços
            'addresses.*.street.required_with' => 'A rua é obrigatória quando cidade ou estado forem informados.',
            'addresses.*.city.required_with'   => 'A cidade é obrigatória quando a rua for informada.',
            'addresses.*.state.required_with'  => 'O estado é obrigatório quando a rua for informada.',
            'addresses.*.state.size'           => 'O estado deve conter exatamente 2 letras (UF).',
            'addresses.*.zip.digits_between'   => 'O CEP deve possuir entre 5 e 9 dígitos.',
            'addresses.*.country.required_with'    => 'O País é obrigatório quando o Estado for informado.',
        ];
    }
}
