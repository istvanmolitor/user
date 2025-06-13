<?php

declare(strict_types=1);

namespace Molitor\User\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = true;

    public function userGroups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_permissions', 'permission_id', 'user_group_id', 'id');
    }
}
