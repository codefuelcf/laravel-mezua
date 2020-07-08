<?php

/**
 * Laravel Sms Package - Mezua
 */

namespace App\Http\Controllers;

use Codefuelcf\Mezua\Sms;
use App\Http\Controllers\Controller;

class SmsController extends Controller
{
	/**
	 * Send queued smses
	 */
	public function sendQueued()
	{
		return Sms::sendQueued();
	}
}
