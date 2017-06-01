<?php

namespace Tests\Unit;

use App\Reply;
use App\Thread;
use App\Activity;
use Tests\DatabaseTest;

class ActivityTest extends DatabaseTest
{
    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
    	$this->signIn();

    	$thread = create(Thread::class);

    	$this->assertDatabaseHas('activities', [
    		'type' 			=> 'created_thread',
    		'user_id'		=> auth()->id(),
    		'subject_id'	=> $thread->id,
    		'subject_type'	=> 'App\Thread'	
		]);

		$activity = Activity::first();

		$this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    public function it_records_when_a_reply_is_created()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->assertEquals(2, Activity::count());
    }
}
