<?php

namespace Tests\Feature;

use App\Thread;
use Tests\DatabaseTest;

class SubscribeToThreadsTest extends DatabaseTest
{
    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
    	// Given there is a thread and an authenticated user
        $thread = create(Thread::class);

        $this->signIn();

        // And the user subscribes to the thread
        $this->post("{$thread->path()}/subscriptions");

        // Then each time a new reply is left on that thread,
     //    $thread->addReply([
     //    	'user_id' => auth()->id(),
     //    	'body'    => 'Here is a reply'
    	// ]);
    	
        // then a notification is prepared for the user.
    	$this->assertCount(1, $thread->subscriptions);
    }
}
