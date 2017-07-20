<?php

namespace Tests\Unit;

use App\Thread;
use Tests\DatabaseTest;

class ThreadTest extends DatabaseTest
{

    protected $thread;

	public function setUp()
	{
		parent::setUp();

		$this->thread = create(Thread::class);
	}

    /** @test */
    public function a_thread_can_have_replies()
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $thread = create(Thread::class);

        $this->assertInstanceOf('App\User', $thread->creator);
    }

    /** @test */
    public function a_thread_has_a_path_property()
    {
        $this->assertEquals("http://forum.dev/threads/{$this->thread->channel->slug}/{$this->thread->id}", $this->thread->path());
    }

    /** @test */
    public function a_thread_can_make_string_path()
    {
        $thread = create(Thread::class);

        $this->assertEquals("http://forum.dev/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'      => 'Foobar',
            'user_id'   => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $this->assertInstanceOf('App\Channel', $this->thread->channel);
    }

    /** @test */
    public function a_thread_can_be_subscribed_to_by_a_user()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed_from()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());

        $thread->unsubscribe($userId);

        $this->assertEquals(0, $thread->subscriptions()->count());
    }

    /** @test */
    public function a_thread_knows_if_a_user_is_subscribed_to_it()
    {
        $thread = create(Thread::class);

        $this->signIn();
        
        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }
}
