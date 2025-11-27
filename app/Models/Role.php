<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    const ADMIN = 1;
    const COORDINATOR = 2;
    const STUDENT = 3;
}
