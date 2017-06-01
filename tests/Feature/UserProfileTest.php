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
    	$user = create(User::class);

        $this->get("profiles/{$user->name}")
        	 ->assertSee($user->name);
    }

    /** @test */
    public function profiles_display_all_threads_by_the_user()
    {
    	$user = create(User::class);
        $this->signIn($user);
        
    	$thread = create(Thread::class, ['user_id' => $user->id]);

        $this->assertDatabaseHas('activities', [
            'subject_id'    => 1,
            'subject_type'  => 'App\Thread',
            'type'          => 'created_thread'
        ]);

    	$this->get("profiles/{$user->name}")
    		 ->assertSee($thread->title)
    		 ->assertSee($thread->body);
    }
}
