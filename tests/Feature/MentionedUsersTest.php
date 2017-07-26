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
        $john = create(User::class, ['name' => 'JohnDoe']);
        $this->signIn($john);
        $jane = create(User::class, ['name' => 'JaneDoe']);
        
        $thread = create(Thread::class);
        $reply = create(Reply::class, ['body' => 'Mentioning @JaneDoe @BobbyDoe @DorisDoe! right here.']);
        
        $this->postJson($thread->path(). '/replies', $reply->toArray()); 
        
        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_given_characters()
    {
        $user1 = create(User::class, ['name' => 'Joey Doughnuts']);
        $user2 = create(User::class, ['name' => 'Billy Smiths']);

        $users = $this->getJson('/api/users?name=joe');
        $this->assertEquals(['Joey Doughnuts'], $users->json());
    }
}
