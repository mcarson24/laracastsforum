<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable;
    
	protected $fillable = ['body', 'user_id'];

    protected $with = ['owner', 'favorites'];

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

    /**
     * Favorite this reply for the authenticated user.
     * 
     * @return [type] [description]
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists())
        {
        	return $this->favorites()->create($attributes);
        }
    }

    /**
     * Determine if this reply currently has any favorites.
     * 
     * @return boolean 
     */
    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * Return the amount of times this reply has been favorited.
     * 
     * @return integer
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}
