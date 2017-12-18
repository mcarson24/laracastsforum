<?php

namespace App\Inspections;

use App\Inspections\SpamFilter;
use App\Inspections\KeyHeldDown;
use App\Inspections\InvalidKeywords;
use App\Inspections\TooManyRequests;

class Spam implements SpamFilter
{
	protected $inspections = [
		InvalidKeywords::class,
		KeyHeldDown::class,
	];

	/**
	 * Detect spam.
	 * 
	 * @param  string $body 
	 * @return bool
	 */
	public function detect($body)
	{
		foreach ($this->inspections as $inspection)
		{
			app($inspection)->detect($body);
		}

		return false;
	}
}
