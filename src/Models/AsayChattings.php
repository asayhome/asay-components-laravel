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
        'receivers',
        'message',
        'atachments'
    ];
}
