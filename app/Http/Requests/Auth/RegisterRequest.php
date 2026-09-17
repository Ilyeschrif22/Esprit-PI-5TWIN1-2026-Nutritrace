<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use ReCaptcha\ReCaptcha;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'cin' => ['required', 'digits:8', 'unique:users,cin'],
            'telephone' => ['required', 'string', 'regex:/^(?:\+216|216)?[2-9]\d{7}$/'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'genre' => ['nullable', 'in:homme,femme'],
            'gouvernorat' => ['required', 'string', 'max:100'],
            'delegation' => ['nullable', 'string', 'max:100'],
            'ville' => ['nullable', 'string', 'max:100'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'code_postal' => ['nullable', 'digits:4'],
            'g-recaptcha-response' => ['required'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $recaptcha = new ReCaptcha(env('RECAPTCHA_SECRET_KEY'));
            $response = $recaptcha->verify($this->input('g-recaptcha-response'), $this->ip());

            if (!$response->isSuccess()) {
                $validator->errors()->add('g-recaptcha-response', 'La vérification reCAPTCHA a échoué. Veuillez réessayer.');
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cin.digits' => 'Le CIN doit contenir exactement 8 chiffres.',
            'telephone.regex' => 'Le numéro doit être un téléphone tunisien valide.',
            'code_postal.digits' => 'Le code postal tunisien doit contenir 4 chiffres.',
        ];
    }
}
