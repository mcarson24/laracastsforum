<?php

namespace App;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function($model) {
            $model->favorites->each->delete();
        });
    }

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
     * Unfavorite the resource for the authenticated user.
     * 
     * @return [type] [description]
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributes)->get()->each->delete();
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
     * Return whether or not the favoritable object has 
     * been favorited by the current authenticated user.
     * 
     * @return boolean
     */
    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
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