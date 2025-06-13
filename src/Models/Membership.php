<?php

declare(strict_types=1);

namespace Molitor\User\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'memberships';
    protected $fillable = [
        'user_group_id',
        'user_id',
    ];

    public $timestamps = false;
}
