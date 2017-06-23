<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Add a new favorite to the database.
	 * 
	 * @param  Reply  $reply 
     * @return \Illuminate\Http\Response
	 */
	public function store(Reply $reply)
	{
		$reply->favorite();

		return back();
	}

	/**
	 * Remove a favorite from the database.
	 * 
	 * @param  Reply  $reply 
     * @return \Illuminate\Http\Response
	 */
	public function destroy(Reply $reply)
	{
		$reply->unfavorite();
	}
}
