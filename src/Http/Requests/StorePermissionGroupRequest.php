<?php

namespace Molitor\User\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StorePermissionGroupRequest',
    title: 'Store Permission Group Request',
    description: 'Data for creating a permission group',
    required: ['name'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'User management'),
    ]
)]
class StorePermissionGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:permission_groups,name',
        ];
    }
}
