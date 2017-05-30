<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
	/**
	 * Set the route key name equal to the Channel's slug
	 * 
	 * @return string
	 */
    public function getRouteKeyName()
    {
    	return 'slug';
    }

    /**
     * A channel has many threads.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
    	return $this->hasMany(Thread::class);
    }
}
