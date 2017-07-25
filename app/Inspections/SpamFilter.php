<?php

namespace App\Inspections;

interface SpamFilter
{
	public function detect($body);
}