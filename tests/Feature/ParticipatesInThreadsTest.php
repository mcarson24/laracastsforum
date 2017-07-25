<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Tests\DatabaseTest;
use League\Flysystem\Exception;
use App\Inspections\SpamDetectionException;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipatesInThreadsTest extends DatabaseTest
{

    /** @test */
    public function an_authenticated_user_may_participate_in_form_threads()
    {
        $this->signIn();
        $thread = create(Thread::class);

        $reply = make(Reply::class);
        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', [
            'thread_id' => $thread->id,
            'body'      => $reply->body
        ]);
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
        
        $this->signIn();
        $this->delete("replies/{$reply->id}")
             ->assertStatus(403);
    }

    /** @test */
    public function authorized_users_can_delete_their_replies()
    {
        $reply = create(Reply::class);
        $this->signIn($reply->owner);

        $this->assertEquals(1, $reply->thread->replies_count);
        $this->delete("replies/{$reply->id}")
             ->assertStatus(302);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
        $this->assertDatabaseMissing('replies', [
                'id'    => $reply->id,
                'body'  => $reply->body
        ]);
    }

    /** @test */
    public function authorizes_users_can_update_replies()
    {
        $reply = create(Reply::class);
        $this->signIn($reply->owner);

        $this->patch("replies/{$reply->id}", [
            'body' => 'This is the new body.'
        ]);

        $this->assertDatabaseHas('replies' ,[
            'id'    => $reply->id,
            'body'  => 'This is the new body.'
        ]);
    }

    /** @test */
    public function an_unauthorized_user_cannot_edit_replies()
    {
        $reply = create(Reply::class);

        $this->withExceptionHandling()
             ->patch("replies/{$reply->id}")
             ->assertRedirect('login');

        // As an authenticated user, attempt to delete
        // another user's reply.
        
        $this->signIn();
        $this->patch("replies/{$reply->id}")
             ->assertStatus(403);
    }

    /** @test */
    public function replies_that_contain_spam_are_not_created()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(Reply::class, [
            'body' => 'Yahoo Customer Support'
        ]);

        try {
            $this->post("{$thread->path()}/replies", $reply->toArray());
        } catch (SpamDetectionException $e) {
            return;
        }
    }
}
