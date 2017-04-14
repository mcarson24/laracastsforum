<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class ViewThreadsTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $thread = factory(Thread::class)->create();

    	$response = $this->get('threads');

    	$response->assertStatus(200);
		$response->assertSee($thread->title);
    }

    /** @test */
    public function a_user_can_view_a_single_thread()
    {
        $thread = factory(Thread::class)->create();

        $response = $this->get('threads/' . $thread->id);

        $response->assertStatus(200);
        $response->assertSee($thread->title);
        $response->assertSee($thread->body);
    }
}
