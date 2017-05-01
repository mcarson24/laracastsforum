<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ViewThreadsTest extends TestCase
{
	use DatabaseMigrations;

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
}
