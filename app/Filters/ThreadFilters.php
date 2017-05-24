<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
	protected $filters = ['by'];

	/**
	 * Filter threads by an author's username.
	 * 
	 * @param  $username 
	 * @return Illuminate\Database\Query\Builder 	$builder           
	 */
	protected function by($username)
	{
		$user = User::where('name', $username)->firstOrFail();

		return $this->builder->where('user_id', $user->id);
	}
}