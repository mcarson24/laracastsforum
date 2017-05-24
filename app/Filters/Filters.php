<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
	protected $request, $builder;

	protected $filters = [];

	/**
	 * ThreadFilters Constructor
	 * 
	 * @param Request $request 
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * Apply the thread filters to the query builder.
	 * 
	 * @param  Illuminate\Database\Query\Builder 	$builder 
	 * @return Illuminate\Database\Query\Builder 	$builder         
	 */
	public function apply($builder)
	{
		$this->builder = $builder;

		foreach ($this->filters as $filter)
		{
			if ($this->hasFilter($filter))
			{
				$this->$filter($this->request->$filter);
			}
		}
		
		return $builder;
	}

	protected function hasFilter($filter)
	{
		return method_exists($this, $filter) && $this->request->has($filter);
	}
}