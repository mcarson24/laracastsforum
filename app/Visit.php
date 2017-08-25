<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visit
{
	protected $relation;

	public function __construct($relation)
	{
		$this->relation = $relation;
	}

	public function reset()
	{
		Redis::del($this->getCacheKey());

		return $this;
	}

	public function count()
	{
        return Redis::get($this->getCacheKey()) ?? 0;
	}

	public function record()
	{
		Redis::incr($this->getCacheKey());

		return $this;
	}

	protected function getCacheKey()
	{
		return app()->environment() == 'testing' ? "testing_threads.{$this->relation->id}.visits" : "threads.{$this->relation->id}.visits";
	}
}
