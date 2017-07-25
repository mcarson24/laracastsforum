<?php

namespace App\Inspections;

use App\Inspections\InvalidKeywords;

class Spam
{
	protected $inspections = [
		InvalidKeywords::class,
		KeyHeldDown::class
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