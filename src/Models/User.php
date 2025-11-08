<?php

namespace Molitor\User\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends \App\Models\User
{
    public function userGroups(): BelongsToMany
    {
        return $this->belongsToMany(UserGroup::class, 'memberships', 'user_id', 'user_group_id', 'id');
    }
}
