<?php

namespace App\Http\Controllers;

use App\Reply;
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
     * 
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

    /**
     * Remove a reply from the database.
     * 
     * @param  Reply  $reply 
     * @return \Illuminate\Http\Response
     * 
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

    	$reply->delete();

    	return back();
    }
}
