<?php

namespace App;

trait Favoritable
{
	/**
     * This resource can be favorited by many users.
     * 
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()		
    {
    	return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * Favorite this resource for the authenticated user.
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
     * Determine if this resource currently has any favorites.
     * 
     * @return boolean 
     */
    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * Return the amount of times this resource has been favorited.
     * 
     * @return integer
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}