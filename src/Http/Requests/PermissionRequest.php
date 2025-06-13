<?php

namespace Molitor\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('acl', 'permission');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:255',
            'description',
            'user_group_ids' => 'nullable|array',
            'user_group_ids.*' => 'required|exists:Molitor\User\Models\UserGroup,id',
        ];
    }
}
