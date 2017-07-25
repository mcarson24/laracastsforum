<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Inspections\Spam;
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
	 */
    public function store($channelId, Thread $thread)
    {
        try {
        	$this->validateReply();

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
            $this->validateReply();

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

    private function validateReply()
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        resolve(Spam::class)->detect(request('body'));
    }
}
