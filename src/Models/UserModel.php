<?php

namespace AsayHome\AsayComponents\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class UserModel extends Model
{
    use HasFactory, SoftDeletes, HasRoles;

    public $table = 'users';


    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
