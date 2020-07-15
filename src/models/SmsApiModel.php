<?php

/**
 * Laravel Sms Package - Mezua
 */

namespace Codefuelcf\Mezua\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsApiModel extends Model
{
    use SoftDeletes;

    // Base table
    protected $table = 'sms_api';

    // Properties for columns
    protected $casts = [

        'data' => 'array',
        'headers' => 'array'
        
    ];

    // Fillable properties
    protected $fillable = [
        'name',
        'url',
        'type',
        'data',
        'headers'
    ];
}
