<?php

namespace Tests\Feature;

use App\User;
use App\Reply;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_thread_creator_can_mark_any_reply_as_the_best_answer()
    {
        $this->signIn(factory(User::class)->states('confirmed')->create());

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);
        
        $this->assertFalse($replies[1]->fresh()->isBest());

        $this->postJson(route('best-replies.store', $replies[1]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    public function only_a_threads_creator_may_mark_a_thread_as_best()
    {
        $this->withExceptionHandling();

        $this->signIn(factory(User::class)->states('confirmed')->create());

        $thread = create(Thread::class, ['user_id' => auth()->id()]);
        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->signIn(factory(User::class)->states('confirmed')->create());

        $response = $this->postJson(route('best-replies.store', $replies[1]));
        $response->assertStatus(403);
        $this->assertFalse($replies[1]->fresh()->isBest());
    }
}
