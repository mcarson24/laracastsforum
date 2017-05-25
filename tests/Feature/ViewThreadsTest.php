<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DatabaseTest;

class ViewThreadsTest extends DatabaseTest
{
	public function setUp()
	{
		parent::setUp();

		$this->thread = create(Thread::class);
	}

    /** @test */
    public function a_user_can_view_all_threads()
    {
    	$this->get('threads')

    		 ->assertStatus(200)
			 ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        $this->get($this->thread->path())

        	 ->assertStatus(200)
        	 ->assertSee($this->thread->title)
        	 ->assertSee($this->thread->body);
    }

    /** @test */
    public function a_user_can_read_replies_associated_with_a_thread()
    {
    	$reply = create(Reply::class, ['thread_id' => $this->thread->id]);

    	$this->get($this->thread->path())
    		 ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {
        $channel = create(Channel::class);

        $threadInChannel = create(Thread::class, [
            'channel_id' => $channel->id
        ]);

        $threadNotInChannel = create(Thread::class);

        $this->get('threads' . '/' . $channel->slug)
             ->assertSee($threadInChannel->title)
             ->assertDontSee($threadNotInChannel->title);
    }

    /** @test */
    public function a_user_can_find_threads_by_username()
    {
        $this->signIn(create(User::class, [
            'name' => 'JohnDoe'
        ]));

        $threadByJohnDoe = create(Thread::class, [
            'user_id' => auth()->id()
        ]);

        $threadNotByJohnDoe = create(Thread::class);

        $this->get('threads?by=JohnDoe')
             ->assertSee($threadByJohnDoe->title)
             ->assertDontSee($threadNotByJohnDoe->title);
    }

    /** @test */
    public function a_user_can_filter_threads_by_popularity()
    {
        // Given we have three threads
        // One with two replies, one with three, and one with zero
        $threadWithTwoReplies = create(Thread::class);
        create(Reply::class, [
            'thread_id' => $threadWithTwoReplies->id
        ], 2);

        $threadWithThreeReplies = create(Thread::class);
        create(Reply::class, [
            'thread_id' => $threadWithThreeReplies->id
        ], 3);

        $threadWithNoReplies = $this->thread;

        // When I filter threads by popularity
        $response = $this->getJson('threads?popular=1')->json();

        // Then they should be returned from mosr replied to to least replied to.
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }
}
