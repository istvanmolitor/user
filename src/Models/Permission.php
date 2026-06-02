<?php

declare(strict_types=1);

namespace Molitor\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'permission_group_id',
        'name',
        'description',
    ];

    public $timestamps = true;

    public function userGroups(): BelongsToMany
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_permissions', 'permission_id', 'user_group_id', 'id');
    }

    public function permissionGroup(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }
}
