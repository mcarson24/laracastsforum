<?php

namespace App\Http\Controllers;

use App\User;
use App\Thread;
use App\Channel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Filters\ThreadFilters;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of Threads.
     * 
     * @param  Channel       $channel 
     * @param  ThreadFilters $filters 
     * @return Illuminate\Http\Response                
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) return $threads;

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inspections\Spam  $spam
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'         => 'required|spamfree',
            'body'          => 'required|spamfree',
            'channel_id'    => 'required|exists:channels,id'
        ], [
            'channel_id.required' => 'Please select a channel for your new thread.'
        ]);

        $thread = Thread::create([
            'user_id'       => auth()->id(),
            'title'         => $request->title,
            'channel_id'    => $request->channel_id,
            'body'          => $request->body
        ]);

        return redirect($thread->path($thread->channel, $thread))
                ->with('flash', 'Your thread has been created!');   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread)
    {
        if (auth()->check()) 
        {
            auth()->user()->read($thread);
        }

        return view('threads.show', compact('thread'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson())
        {
            return response([], 204);
        }

        return redirect('threads');
    }

    /**
     * Get filtered threads by a given channel.
     * 
     * @param  Channel       $channel 
     * @param  ThreadFilters $filters 
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::with('creator')->latest()->filter($filters);

        if ($channel->exists)
        {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(15);
    }
}
