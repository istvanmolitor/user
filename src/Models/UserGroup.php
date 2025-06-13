<?php

declare(strict_types=1);

namespace Molitor\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{

    protected $fillable = [
        'name',
        'description',
    ];

    public $timestamps = true;

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_group_permissions', 'user_group_id', 'permission_id', 'id');
    }
}
