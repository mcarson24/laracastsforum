<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Spam;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth')->except('index');
	}

    /**
     * View all replies.
     * 
     * @param  [type] $channelId 
     * @param  Thread $thread    
     * @return json            
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

	/**
	 * Store a new reply in the database.
     * 
	 * @param         $channelId 
	 * @param  Thread $thread      
     * @param  Spam   $spam
	 */
    public function store($channelId, Thread $thread, Spam $spam)
    {
    	$this->validate(request(), [
    		'body' => 'required'
		]);

        $spam->detect(request('body'));

    	$reply = $thread->addReply([
    		'body' 		=> request('body'),
    		'user_id'	=> auth()->id()
		]);

        if (request()->expectsJson())
        {
            return $reply->load('owner');
        }

		return back()->with('flash', 'Your reply has been added.');
    }

    /**
     * Update a reply in storage.
     * @param  Reply   $reply   [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Reply $reply, Request $request)
    {
        $this->authorize('update', $reply);

        $reply->update($request->only('body'));
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

        if (request()->expectsJson())
        {
            return response(['status', 'Reply has been deleted.']);
        }

    	return back();
    }
}
