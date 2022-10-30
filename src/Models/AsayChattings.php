<?php

namespace AsayHome\AsayComponents\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsayChattings extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'asay_chattings';

    protected $fillable = [
        'group',
        'group_id',
        'sender_id',
        'receiver_id',
        'message',
        'atachments'
    ];

    public function sender()
    {
        return $this->hasOne(UserModel::class, 'id', 'sender_id');
    }
}
