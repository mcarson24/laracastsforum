<?php

namespace Tests\Feature;

use App\Activity;
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
    public function a_new_user_must_first_confirm_their_email_address_before_creating_threads()
    {
        $user = factory(User::class)->create();
        $this->signIn($user);
        $thread = create(Thread::class);

        $response = $this->post('threads', $thread->toArray());

        $response->assertRedirect('threads');
        $response->assertSessionHas('flash', 'You must confirm your email address before creating threads.');
    }

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $user = factory(User::class)->states('confirmed')->create();
        $this->signIn($user);
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
        ])->assertSessionHasErrors('title');
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
    public function a_thread_can_be_deleted_by_authorized_users()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    }

    /** @test */
    public function an_unauthenticated_user_cannot_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $this->delete($thread->path())
             ->assertRedirect('login');
        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

        $this->signIn();
        $this->delete($thread->path())
             ->assertStatus(403);
    }

    /** @test */
    public function a_threads_replies_are_also_deleted_when_a_thread_is_deleted()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function a_threads_activity_is_also_deleted_when_a_thread_is()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());
        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertEquals(0, Activity::count());
    }

    protected function publishThread($attributes = [])
    {
        $this->withExceptionHandling()
             ->signIn(factory(User::class)->states('confirmed')->create());

        $thread = make(Thread::class, $attributes);

        return $this->post('/threads', $thread->toArray());
    }
}
