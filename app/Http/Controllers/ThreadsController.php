<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Inspections\Spam;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Show an index of all the threads.
     * 
     * @param  Channel $channel 
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
    public function store(Request $request, Spam $spam)
    {
        $this->validate($request, [
            'title'         => 'required',
            'body'          => 'required',
            'channel_id'    => 'required|exists:channels,id'
        ], [
            'channel_id.required' => 'Please select a channel for your new thread.'
        ]);

        $spam->detect(request('body'));

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
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

        return $threads->get();
    }
}
