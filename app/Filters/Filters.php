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

		$this->getFilters()
			 ->filter(function($value, $filter) {
			 	return method_exists($this, $filter);
			 })->each(function($value, $filter) {
			 	$this->$filter($value);
			 });
		
		return $builder;
	}

	/**
	 * Get all the accepted filters that were passed in the request.
	 * 
	 * @return $filters
	 */
	public function getFilters()
	{
		return collect($this->request->intersect($this->filters));
	}
}