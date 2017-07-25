<?php

namespace App\Inspections;

use App\Inspections\SpamDetectionException;

class InvalidKeywords implements SpamFilter
{
	protected $keywords = [
		'yahoo customer support'
	];

	/**
	 * Detect this spam rule.
	 * 
	 * @param  string $body 
	 * @return bool       
	 */
	public function detect($body)
	{
		foreach ($this->keywords as $keyword) 
		{
			if(stripos($body, $keyword) !== false)
			{
				throw new \Exception('No spam please!');
			}
		}
	}

}