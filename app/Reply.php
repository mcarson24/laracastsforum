<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected $fillable = ['body', 'user_id'];

	/**
	 * A reply was written by a single user.
	 * 
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A reply can be favorited by many users.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()		
    {
    	return $this->morphMany(Favorite::class, 'favorited');
    }
}
