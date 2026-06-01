<?php

declare(strict_types=1);

namespace Molitor\User\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'permission_group_id');
    }
}