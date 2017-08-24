<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Tests\DatabaseTest;
use Illuminate\Support\Facades\Redis;

class TrendingThreadsTest extends DatabaseTest
{
   /** @test */
   public function it_increments_a_threads_score_each_time_it_is_viewed()
   {
   		$this->assertCount(0, Redis::zrevrange('trending_threads', 0, -1));

       	$thread = create(Thread::class);

       	$this->get($thread->path());

       	$this->assertCount(1, $trending = Redis::zrevrange('trending_threads', 0, -1));

       	dd($trending);
   }
}
