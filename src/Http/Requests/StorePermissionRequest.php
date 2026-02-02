<?php

namespace Molitor\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StorePermissionRequest",
    title: "Store Permission Request",
    description: "Data for creating a permission",
    required: ["name"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "user.delete"),
        new OA\Property(property: "description", type: "string", example: "Can delete users", nullable: true),
        new OA\Property(
            property: "user_groups",
            type: "array",
            items: new OA\Items(type: "integer"),
            example: [1]
        )
    ]
)]
class StorePermissionRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:permissions,name',
            'description' => 'nullable|string',
            'user_groups' => 'nullable|array',
            'user_groups.*' => 'exists:user_groups,id',
        ];
    }
}
