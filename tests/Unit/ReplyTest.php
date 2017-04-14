<?php

namespace Tests\Unit;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReplyTest extends TestCase
{
	use DatabaseMigrations;

    /** @test */
    public function it_has_an_owner()
    {
        $reply = factory(Reply::class)->make();

        $this->assertNotNull($reply->owner());
    }
}
