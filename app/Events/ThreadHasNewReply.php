<?php

namespace App\Events;

use App\Reply;
use App\Thread;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ThreadHasNewReply
{
    use Dispatchable, SerializesModels;

    public $thread;
    public $reply;

    /**
     * @param Thread $thread
     * @param Reply  $reply 
     */
    public function __construct(Thread $thread, Reply $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }
}
