<?php

namespace App\Rules;

use App\Inspections\Spam;
use App\Inspections\SpamDetectionException;

class SpamFree
{
	public function passes($attribute, $value)
	{
		try {
			return ! resolve(Spam::class)->detect($value);
		} catch (SpamDetectionException $e) {
			return false;
		}
	}
}