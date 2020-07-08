<?php

/**
 * Laravel Sms Package - Mezua
 */

namespace Codefuelcf\Mezua;

// Models
use Codefuelcf\Mezua\Models\SmsApiModel;

/**
 * Laravel SmsAPI
 */
class SmsAPI {

	/**
	 * Show API
	 */
	public static function show($smsApiIdentifier)
	{
		return self::selectApi($smsApiIdentifier);
	}

	/**
	 * Create Sms API
	 */
	public static function create(string $name, string $slug = NULL, string $url, string $type, array $data, array $headers = NULL)
	{
		// Create new sms API object
		$smsApi = new SmsApiModel;
		$smsApi->name = $name;
		$smsApi->slug = self::createSlug($slug, $name);
		$smsApi->url = $url;
		$smsApi->type = $type;
		$smsApi->data = $data;
		$smsApi->headers = $headers;

		$smsApi->save();

		return $smsApi->id;
	}

	/**
	 * Update Sms API
	 */
	public static function update($smsApiIdentifier, array $attributes)
	{
		// Selecting the API to be updated
		$smsApiToUpdate = self::selectApi($smsApiIdentifier);

		// Check if the API exists
		if($smsApiToUpdate == FALSE)
		{
			return FALSE;
		}

		// Updating all the columns for the API
		$smsApiToUpdate->update(self::checkAttributes($attributes));

		return $smsApiToUpdate->id;
	}

	/**
	 * Delete Sms API
	 */
	public static function delete($smsApiIdentifier)
	{
		$smsApiToDelete = self::selectApi($smsApiIdentifier);

		if($smsApiToDelete == FALSE)
		{
			return FALSE;
		}

		return $smsApiToDelete->delete();
	}

	 /**
	 * Select a Sms API
	 */
	private static function selectApi($smsApiIdentifier)
	{
		// Check which column is being used (id or slug)
		is_integer($smsApiIdentifier) ? $column = 'id' : $column = 'slug';

		// If anything other than int or string is used, return
		if(! is_integer($column) && ! is_string($column))
		{
			return FALSE;
		}

		// Select the API
		$selectedSmsApi = SmsApiModel::where($column, $smsApiIdentifier)
									 ->first(); 

		// Check if the API exists
		if(is_null($selectedSmsApi))
		{
			return FALSE;
		}

		// Return the Sms API
		return $selectedSmsApi;
	}

	/**
	 * Check attributes for smsAPI table
	 */
	private static function checkAttributes(array $attributes)
	{
		// Sms API attributes available
		$attributesAvailable = ['name', 'url', 'type', 'data', 'headers'];

		// Array to store attributes after filtering out invalid attributres
		$trueAttributes = array_filter($attributes, function ($attribute) use ($attributesAvailable) {

			return in_array($attribute, $attributesAvailable);

		}, ARRAY_FILTER_USE_KEY);

		return $trueAttributes;
	}

	/**
	 * Creating slug for Sms API's
	 */
	private static function createSlug(string $slug, string $name)
	{
		// If the user hasn't provided with the slug then generate one
		if($slug == NULL)
		{
			$APIslug = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $name));

			// Check if the slug exists
			$slugExists = SmsApiModel::where('slug', $APIslug)
									->get();

			$slugExists->isEmpty() ? TRUE : $APIslug .= random_int(1, 99);

			return strval($APIslug);
		}

		// Create the slug from the name of the API
		$APIslug = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $slug));

		// Check if the slug exists
		$slugExists = SmsApiModel::where('slug', $APIslug)
								  ->get();

		$slugExists->isEmpty() ? TRUE : $APIslug .= random_int(1, 99);

		return strval($APIslug);
	}

}