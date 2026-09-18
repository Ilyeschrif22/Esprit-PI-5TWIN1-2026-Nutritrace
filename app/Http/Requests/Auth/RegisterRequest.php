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
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'cin' => ['required', 'digits:8', 'unique:users,cin'],
            'phone' => ['required', 'string', 'regex:/^(?:\+216|216)?[2-9]\d{7}$/'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'governorate' => ['required', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'terms' => ['accepted'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $secret = env('RECAPTCHA_SECRET_KEY');
            $token = $this->input('g-recaptcha-response');

            // reCAPTCHA est vérifié uniquement lorsqu'une clé secrète et un token sont fournis.
            if ($secret && $token) {
                $recaptcha = new ReCaptcha($secret);
                $response = $recaptcha->verify($token, $this->ip());

                if (!$response->isSuccess()) {
                    $validator->errors()->add('g-recaptcha-response', 'La vérification reCAPTCHA a échoué. Veuillez réessayer.');
                }
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
            'phone.regex' => 'Le numéro doit être un téléphone tunisien valide.',
            'terms.accepted' => 'Vous devez accepter les conditions d’utilisation.',
        ];
    }
}