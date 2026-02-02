<?php

namespace Molitor\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "ChangePasswordRequest",
    title: "Change Password Request",
    description: "Data for changing password",
    required: ["password", "password_confirmation"],
    properties: [
        new OA\Property(property: "password", type: "string", format: "password", example: "newpassword123"),
        new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "newpassword123")
    ]
)]
class ChangePasswordRequest extends FormRequest
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
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Az új jelszó megadása kötelező.',
            'password.confirmed' => 'Az új jelszó megerősítése nem egyezik.',
        ];
    }
}
