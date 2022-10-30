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


    public function getNameAttribute($value)
    {
        if (isset($this->first_name) && isset($this->last_name)) {
            return $this->first_name . ' ' . $this->last_name;
        } else {
            return $value;
        }
    }

    protected $appends = ['name'];

    public $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
