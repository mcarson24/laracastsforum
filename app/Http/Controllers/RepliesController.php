<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
     */
    public function store($channelId, Thread $thread)
    {
        if (Gate::denies('create', new Reply))
        {
            return response('Woah! Slow done there a bit. One post per minute.', 429);
        }

        try {
        	$this->validate(request(), [
                'body' => 'required|spamfree'
            ]);

        	$reply = $thread->addReply([
        		'body' 		=> request('body'),
        		'user_id'	=> auth()->id()
    		]);
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422);
        }
        return $reply->load('owner');
    }

    /**
     * Update a reply.
     * 
     * @param  Reply  $reply 
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        try {
            $this->validate(request(), [
                'body' => 'required|spamfree'
            ]);

            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response('Sorry, your reply could not be saved at this time.', 422); 
        }
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
