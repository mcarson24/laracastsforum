<?php

namespace App\Http\Controllers;

use App\User;
use App\Thread;
use App\Channel;
use App\Trending;
use Carbon\Carbon;
use App\Rules\Recaptcha;
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
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads'  => $threads,
            'trending' => $trending->get()
        ]);
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
     * @param  \App\Inspections\Spam  $spam
     * @param \App\Rules\Recaptcha
     */
    public function store(Recaptcha $recaptcha)
    {
        request()->validate([
            'title'                 => 'required|spamfree',
            'body'                  => 'required|spamfree',
            'channel_id'            => 'required|exists:channels,id',
            'g-recaptcha-response'  => ['required', $recaptcha]
        ], [
            'channel_id.required'           => 'Please select a channel for your new thread.',
            'g-recaptcha-response.required' => 'Please prove that you are not a bot.'
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body'),
        ]);

        if (request()->wantsJson()) {
            return response($thread, 201);
        }

        return redirect($thread->path($thread->channel, $thread))
                ->with('flash', 'Your thread has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $thread->visits()->record();
        $trending->push($thread);

        return view('threads.show', compact('thread'));
    }

    /**
     * Update a thread.
     * 
     * @param  $channelId 
     * @param  Thread $thread    
     * @return \Illuminate\Http\RedirectResponse       
     */
    public function update($channelId, Thread $thread)
    {
        $this->authorize('update', $thread);

        request()->validate([
            'title' => 'required|spamfree',
            'body'  => 'required|spamfree'
        ]);

        $thread->update(request(['title', 'body']));

        return response($thread, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson()) {
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

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(15);
    }
}
