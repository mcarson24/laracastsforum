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
}
