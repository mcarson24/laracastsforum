<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
	/**
	 * Get top five most popular threads.
	 * 
	 * @return array
	 */
	public function get()
	{
		return array_map('json_decode', Redis::zrevrange($this->getCacheKey(), 0, 4));
	}

	/**
	 * Increment score for a thread by one.
	 * 
	 * @param  Thread $thread 
	 * @return void
	 */
	public function push(Thread $thread)
	{
		Redis::zincrby($this->getCacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path'  => $thread->path()
        ]));
	}

	/**
	 * Reset the trending threads cache.
	 * 
	 * @return void
	 */
	public function reset()
	{
		Redis::del($this->getCacheKey());
	}

	/**
	 * Get the cache key for the current environment.
	 * 
	 * @return string
	 */
	public function getCacheKey()
	{
		return app()->environment() == 'testing' ? 'trending_threads_test' : 'trending_threads';
	}
}
