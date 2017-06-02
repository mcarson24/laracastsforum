<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Store a new reply in the database.
	 * @param         $channelId 
	 * @param  Thread $thread       
	 */
    public function store($channelId, Thread $thread)
    {
    	$this->validate(request(), [
    		'body' => 'required'
		]);

    	$thread->addReply([
    		'body' 		=> request('body'),
    		'user_id'	=> auth()->id()
		]);

		return back()->with('flash', 'Your reply has been added.');
    }
}
