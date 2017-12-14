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

		foreach ($this->getFilters() as $filter => $value)
		{
			if (method_exists($this, $filter))
			{
				$this->$filter($value);
			}
		}
		
		return $builder;
	}

	/**
	 * Get all the accepted filters that were passed in the request.
	 * 
	 * @return $filters
	 */
	public function getFilters()
	{
		return array_filter($this->request->only($this->filters));
	}
}
