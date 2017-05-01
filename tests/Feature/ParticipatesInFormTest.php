<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipatesInFormTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_may_participate_in_form_threads()
    {
        $this->signInAs($user = create(User::class));
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
        $this->post($thread->path() . '/replies', $reply->toArray());
    }
}
