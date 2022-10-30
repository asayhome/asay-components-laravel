<?php

namespace AsayHome\AsayComponents\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsayReplies extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'asay_replies';

    protected $fillable = [
        'title',
        'content'
    ];
}
