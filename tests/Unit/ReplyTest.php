<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DatabaseTest;

class ReplyTest extends DatabaseTest
{

    /** @test */
    public function it_has_an_owner()
    {
        $reply = make(Reply::class);

        $this->assertInstanceOf('App\User', $reply->owner);
        $this->assertNotNull($reply->owner());
    }

    /** @test */
    public function it_knows_its_correct_path()
    {
        $thread = create(Thread::class);
        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $this->assertEquals($thread->path() . '#reply-1', $reply->path());
    }

    /** @test */
    public function a_reply_know_which_users_where_mentioned_in_its_body()
    {
        $reply = create(Reply::class, ['body' => '@JaneDoe @JohnDoe are cool people right @SteveDoe ?']);

        $this->assertEquals([
            'JaneDoe',
            'JohnDoe',
            'SteveDoe'
        ], $reply->mentionedUsers());
    }

    /** @test */
    public function a_reply_knows_if_it_was_just_created_within_the_last_minute()
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at =  \Carbon\Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }
}
