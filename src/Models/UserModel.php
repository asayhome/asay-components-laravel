<?php

namespace AsayHome\AsayComponents\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends Model
{
    use HasFactory, SoftDeletes;

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
