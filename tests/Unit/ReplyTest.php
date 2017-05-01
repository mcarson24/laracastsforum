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
}
