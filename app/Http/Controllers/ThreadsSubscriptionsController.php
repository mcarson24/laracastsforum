<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use Illuminate\Http\Request;

class ThreadsSubscriptionsController extends Controller
{
    public function store(Channel $channel, Thread $thread)
    {
    	$thread->subscribe();
    }

    public function destroy(Channel $channel, Thread $thread)
    {
    	$thread->unsubscribe();
    }
}
