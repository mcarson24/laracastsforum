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
        $this->assertEquals('/threads/' . $this->thread->id, $this->thread->path());
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
}
