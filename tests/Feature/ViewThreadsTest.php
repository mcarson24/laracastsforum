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
}
