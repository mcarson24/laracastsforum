<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;

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
     * @param  integer              $channelId 
     * @param  Thread               $thread    
     * @param  CreatePostRequest    $form      
     * @return Symfony\Component\HttpFoundation\RedirectResponse                   
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
    	return $thread->addReply([
    		'body' 		=> request('body'),
    		'user_id'	=> auth()->id()
		])->load('owner');
    }

    /**
     * Update a reply.
     * 
     * @param  Reply  $reply 
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $this->validate(request(), [
            'body' => 'required|spamfree'
        ]);

        $reply->update(request(['body']));
        
        return response('Sorry, your reply could not be saved at this time.', 422); 
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
    }
}
