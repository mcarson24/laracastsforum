<?php

namespace Tests\Unit;

use App\Reply;
use Tests\DatabaseTest;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
    public function a_reply_knows_if_it_was_just_created_within_the_last_minute()
    {
        $reply = create(Reply::class);

        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at =  \Carbon\Carbon::now()->subMonth();

        $this->assertFalse($reply->wasJustPublished());
    }
}
