<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionedUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        // Given I have a user named JohnDoe,
        $john = create(User::class, ['name' => 'JohnDoe']);
        $this->signIn($john);
        // and another user JaneDoe
        $jane = create(User::class, ['name' => 'JaneDoe']);
        // If we have a thread
        $thread = create(Thread::class);
        // And JohnDoe replies and mentions JaneDoe
        $reply = create(Reply::class, ['body' => 'Mentioning @JaneDoe @BobbyDoe @DorisDoe! right here.']);
        
        $this->postJson($thread->path(). '/replies', $reply->toArray()); 
        // Then, Jane should be notified
        $this->assertCount(1, $jane->notifications);
    }
}
