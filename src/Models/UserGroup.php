<?php

declare(strict_types=1);

namespace Molitor\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserGroup extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_default',
    ];

    public $timestamps = true;

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_group_permissions', 'user_group_id', 'permission_id', 'id');
    }
}
