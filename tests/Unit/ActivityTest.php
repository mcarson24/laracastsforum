<?php

namespace Tests\Unit;

use App\Activity;
use App\Reply;
use App\Thread;
use Carbon\Carbon;
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

    /** @test */
    public function it_records_when_a_reply_is_favorited()
    {
        $reply = create(Reply::class);

        $this->signIn();

        $reply->favorite();

        $this->assertDatabaseHas('favorites', [
            'user_id'           => auth()->id(),
            'favorited_id'      => $reply->id,
            'favorited_type'    => get_class($reply)
        ]);

        $this->assertEquals(1, Activity::count());
    }

    /** @test */
    public function it_can_get_an_activity_feed_for_a_user()
    {
        $this->signIn();

        create(Thread::class, ['user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user());

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
