<?php

namespace Molitor\User\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Molitor\User\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('acl', 'user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'is_banned' => 'required|boolean',
            'email' => [
                'email',
                'required',
                'min:1',
                'max:255',
                Rule::unique(User::class)->ignore($this->route('user')),
            ],
            'name' => 'required|min:1|max:255',
            'user_group_ids' => 'nullable|array',
            'user_group_ids.*' => 'required|exists:Molitor\User\Models\UserGroup,id',
        ];
    }
}
