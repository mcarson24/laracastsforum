<?php

namespace Tests\Unit;

use App\User;
use App\Thread;
use Tests\DatabaseTest;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;

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
    public function a_thread_has_a_path()
    {
        $thread = create(Thread::class);

        $this->assertEquals("http://forum.dev/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
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
    public function a_thread_notifies_all_registerd_subscribers_when_a_new_reply_is_added()
    {
        Notification::fake();

        $this->signIn();

        $this->thread->subscribe()
                     ->addReply([
                        'body' => 'Here ya go boo.',
                        'user_id' => create(User::class)->id
                    ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
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

    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $user = $this->signIn();
        $thread = create(Thread::class);

        $this->assertTrue($thread->hasUpdatesForUser());

        $user->read($thread);

        $this->assertFalse($thread->hasUpdatesForUser());
    }

    /** @test */
    public function a_thread_records_each_view()
    {
        $thread = make(Thread::class, ['id' => 1]);

        $thread->visits()->reset();

        $this->assertSame(0, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(1, $thread->visits()->count());

        $thread->visits()->record();

        $this->assertEquals(2, $thread->visits()->count());        
    }

    /** @test */
    public function a_thread_may_be_locked()
    {
        $this->assertFalse($this->thread->locked);

        $this->thread->update(['locked' => true]);

        $this->assertTrue($this->thread->locked);
    }
}
