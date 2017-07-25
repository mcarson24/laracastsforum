<?php

namespace App\Inspections;

use App\Inspections\SpamDetectionException;

class KeyHeldDown implements SpamFilter
{
	/**
	 * Detect this spam rule.
	 * 
	 * @param  string $body 
	 * @return bool       
	 */
	public function detect($body)
	{
		if (preg_match('/(.)\\1{4,}/', $body))
		{
			throw new SpamDetectionException('No spam please!');
		}
	}
}