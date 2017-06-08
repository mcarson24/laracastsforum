<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Tests\DatabaseTest;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipatesInThreadsTest extends DatabaseTest
{

    /** @test */
    public function an_authenticated_user_may_participate_in_form_threads()
    {
        $this->signIn($user = create(User::class));
        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
        	 ->assertSee($reply->body);
    }

    /** @test */
    public function unauthenticated_users_may_not_add_replies()
    {
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);
        
        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post($thread->path($thread->channel, $thread) . '/replies', $reply->toArray());
    }

    /** @test */
    public function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()
             ->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, [
            'body' => null
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
             ->assertSessionHasErrors('body');
    }

    /** @test */
    public function an_unauthorized_user_cannot_delete_replies()
    {
        $reply = create(Reply::class);

        $this->withExceptionHandling()
             ->delete("replies/{$reply->id}")
             ->assertRedirect('login');

        // As an authenticated user, attempt to delete
        // another user's reply.
        
        $this->signIn()
             ->delete("replies/{$reply->id}")
             ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_their_replies()
    {
        $reply = create(Reply::class);
        $this->signIn($reply->owner);

        $response = $this->delete("replies/{$reply->id}");

        $response->assertStatus(302);

        $this->assertDatabaseMissing('replies', [
            'id'    => $reply->id,
            'body'  => $reply->body
        ]);
    }
}
