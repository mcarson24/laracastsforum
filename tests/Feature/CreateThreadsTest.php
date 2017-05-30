<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DatabaseTest;

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
        $this->signIn();

        $thread = make(Thread::class);

        $response = $this->post('/threads', $thread->toArray());

        $pathToThread = $response->headers->get('Location');

        $this->get($pathToThread)
             ->assertSee($thread->title)
             ->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread([
            'title' => null
        ])
             ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread([
            'body' => null
        ])
             ->assertSessionHasErrors('body');
    }
    /** @test */
    public function a_thread_requires_a_valid_channel_id()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread([
            'channel_id' => null
        ])
             ->assertSessionHasErrors('channel_id');

        $this->publishThread([
            'channel_id' => 99999
        ])
             ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_thread_can_be_deleted()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $response = $this->delete($thread->path());

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

        $response->assertRedirect('login');
    }

    /** @test */
    public function a_thread_replies_are_also_delteted_when_a_thread_is_deleted()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    protected function publishThread($attributes = [])
    {
        $this->withExceptionHandling()
             ->signIn();

        $thread = make(Thread::class, $attributes);

        return $this->post('/threads', $thread->toArray());
    }
}
