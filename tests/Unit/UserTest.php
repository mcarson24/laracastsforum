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

    /** @test */
    public function a_user_knows_its_avatar_path()
    {
        $user = create(User::class, ['avatar_path' => 'avatars/me.jpg']);

        $this->assertEquals('http://forum.dev/avatars/me.jpg', $user->avatar());
    }

    /** @test */
    public function a_user_without_an_avatar_returns_a_placeholder_image()
    {
        $user = create(User::class);

        $this->assertEquals('http://forum.dev/avatars/default.png', $user->avatar());
    }
}
