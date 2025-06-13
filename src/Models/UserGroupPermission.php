<?php

declare(strict_types=1);

namespace Molitor\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroupPermission extends Model
{
    protected $fillable = [
        'user_group_id',
        'permission_id',
    ];

    public $timestamps = false;
}
