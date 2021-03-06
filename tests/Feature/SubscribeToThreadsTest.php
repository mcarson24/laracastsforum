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

    	$this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $thread = create(Thread::class);

        $this->signIn();

        $thread->subscribe();

        $this->delete("{$thread->path()}/subscriptions");

        $this->assertCount(0, $thread->subscriptions);
        $this->assertFalse($thread->isSubscribedTo);
    }
}
