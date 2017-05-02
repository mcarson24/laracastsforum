<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\DatabaseTest;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends DatabaseTest
{

	/** @test */
	public function guests_cannot_create_threads()
	{
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);

	    $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());
	}

    /** @test */
    public function guests_cannot_see_new_forum_creation_page()
    {
        $this->withExceptionHandling();

        $this->get('threads/create')
             ->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->actingAs(create(User::class));

        $thread = make(Thread::class);

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }
}
