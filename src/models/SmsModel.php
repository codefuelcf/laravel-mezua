<?php

/**
 * Laravel Sms Package - Mezua
 */

namespace Codefuelcf\Mezua\Models;

use Illuminate\Database\Eloquent\Model;

class SmsModel extends Model
{
	// Base table
	protected $table = 'sms';

	// Properties for columns
	protected $casts = [

		'response' => 'array'
		
	];

	// Mapping to SMS API's tables
	public function api()
	{

		return $this->belongsTo('Codefuelcf\Mezua\Models\SmsApiModel', 'sms_api_id', 'id');
		
	}
}
