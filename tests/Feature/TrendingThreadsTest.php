<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use Tests\TestCase;
use Tests\DatabaseTest;

class TrendingThreadsTest extends DatabaseTest
{
	public function setUp()
	{
		parent::setUp();

		$this->trending = new Trending;

		$this->trending->reset();
	}

	/** @test */
	public function it_increments_a_threads_score_each_time_it_is_viewed()
	{
		$this->assertCount(0, $this->trending->get());

	   	$thread = create(Thread::class);

	   	$this->get($thread->path());

	   	$this->assertCount(1, $this->trending->get());
	}
}
