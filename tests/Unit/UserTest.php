<?php

namespace Tests\Unit;

use App\User;
use App\Reply;
use Tests\DatabaseTest;

class UserTest extends DatabaseTest
{
    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        $user = create(User::class);
        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }
}
