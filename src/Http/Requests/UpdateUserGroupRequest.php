<?php

namespace Molitor\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateUserGroupRequest",
    title: "Update User Group Request",
    description: "Data for updating a user group",
    required: ["name"],
    properties: [
        new OA\Property(property: "name", type: "string", example: "Moderators Updated"),
        new OA\Property(property: "description", type: "string", example: "Limited access (updated)", nullable: true),
        new OA\Property(property: "is_default", type: "boolean", example: true),
        new OA\Property(
            property: "permissions",
            type: "array",
            items: new OA\Items(type: "integer"),
            example: [1]
        )
    ]
)]
class UpdateUserGroupRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:user_groups,name,' . $this->route('user_group')->id,
            'description' => 'nullable|string',
            'is_default' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }
}

