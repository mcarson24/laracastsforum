<?php

namespace Tests\Feature;

use App\User;
use App\Thread;
use Tests\DatabaseTest;

class UserProfileTest extends DatabaseTest
{
    /** @test */
    public function a_user_has_a_profile()
    {
    	$this->signIn();

        $this->get("profiles/" . auth()->user()->name)
        	 ->assertSee(auth()->user()->name);
    }

    /** @test */
    public function profiles_display_all_threads_by_the_user()
    {
        $this->signIn();
        
    	$thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->assertDatabaseHas('activities', [
            'subject_id'    => 1,
            'subject_type'  => 'App\Thread',
            'type'          => 'created_thread'
        ]);

    	$this->get('profiles/' . auth()->user()->name)
    		 ->assertSee($thread->title)
    		 ->assertSee($thread->body);
    }

    /** @test */
    public function a_profile_shows_the_correct_users_feed()
    {
        $user = create(User::class);

        $this->signIn($user);

        $threadToSee = create(Thread::class, ['user_id' => $user->id]);


        $this->signIn(create(User::class));

        $threadToNotSee = create(Thread::class, ['user_id' => auth()->id()]);

        $this->get("profiles/{$user->name}")
             ->assertSee($threadToSee->title)
             ->assertDontSee($threadToNotSee->title);
    }
}
