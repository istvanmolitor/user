<?php

namespace Molitor\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StoreUserRequest",
    title: "Store User Request",
    description: "Data for creating a user",
    required: ["name", "email"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "Jane Doe"),
        new OA\Property(property: "email", type: "string", format: "email", example: "jane@example.com"),
        new OA\Property(property: "email_verified", type: "boolean", example: true),
        new OA\Property(
            property: "user_groups",
            type: "array",
            items: new OA\Items(type: "integer"),
            example: [1, 2]
        )
    ]
)]
class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'user_groups' => 'array',
            'user_groups.*' => 'exists:user_groups,id',
            'email_verified' => 'boolean',
        ];
    }
}
