<?php

namespace Tests\Browser;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UnfavoritingRepliesTest extends DuskTestCase
{
    /** @test /* */
    public function unfavoriting_a_reply_removes_the_favorited_activity()
    {
        $this->be($user = factory(User::class)->create(
            ['password' => bcrypt('password')]
        ));
        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->create([
            'thread_id' => $thread->id
        ]);

        $reply->favorite();
        
        $this->browse(function (Browser $browser) use ($thread, $user) {
            $browser->visit('login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/home')
                    ->visit('threads/' . $thread->channel->slug . '/' . $thread->id)
                    ->press('1')
                    ->visit('profiles/' . $user->name)
                    ->assertSee($user->name)
                    ->assertDontSee('Favorited ' . $user->name . '\'s reply to \'' . $thread->title . '\'!');
        });
    }

    /** @test */
    public function deleting_a_reply_that_has_been_favorited_will_remove_all_of_it_favorites()
    {
        $this->be($user = factory(User::class)->create([
            'password' => bcrypt('password')
        ]));

        $thread = factory(Thread::class)->create();

        $this->browse(function(Browser $browser) use ($user, $thread) {
           $browser->visit('login')
                   ->type('email', $user->email)
                   ->type('password', 'password')
                   ->press('Login')
                   ->assertPathIs('/home')
                   ->visit('threads/' . $thread->channel->slug . '/' . $thread->id)
                   ->type('body', 'Hello!')
                   ->press('Post')
                   ->press('0')
                   ->pause(5000)
                   ->press('Delete')
                   ->visit('profiles/' . $user->name)
                   ->pause(10000)
                   ->assertSee($user->name)
                   ->assertDontSee('Replied to ' . $thread->title); 
        });


    }
}
