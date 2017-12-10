<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LockThreadsTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function non_administrators_may_not_lock_threads()
	{
		$this->withExceptionHandling();
		
	    $this->signIn();

	    $thread = create(Thread::class, ['user_id' => auth()->id()]);

	    $this->assertFalse($thread->locked);

	    $response = $this->post(route('locked-threads.store', $thread));

	    $response->assertStatus(403);
	    $this->assertFalse(!!$thread->fresh()->locked);
	}

	/** @test */
	public function administrators_may_lock_threads()
	{
	    $this->signIn(
	    	factory(User::class)->states('administrator')->create()
	    );

	    $thread = create(Thread::class, ['user_id' => auth()->id()]);

		$response = $this->post(route('locked-threads.store', $thread));

		$response->assertStatus(200);
		$this->assertTrue(!!$thread->fresh()->locked);
	}

    /** @test */
    public function a_thread_that_has_been_locked_cannot_be_replied_to()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->lock();

        $response = $this->post($thread->path() . '/replies', [
        	'body' 		=> 'This is a test reply.',
        	'user_id'	=> auth()->id()
        ]);

        $response->assertStatus(422);
    }
}