<?php

namespace BestSnipp\Eden\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdenMedia extends Model
{
    use HasFactory;

    protected $table = 'eden_media_records';

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'preview' => 'boolean'
    ];

}
