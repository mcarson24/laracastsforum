<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LockThreadsTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function a_thread_can_be_locked()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->lock();

        $response = $this->postJson($thread->path() . '/replies', [
        	'body' 		=> 'This is a test reply.',
        	'user_id'	=> auth()->id()
        ]);

        $response->assertStatus(422);
    }
}
