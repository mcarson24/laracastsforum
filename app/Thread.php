<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $fillable = ['user_id', 'title', 'body'];

	/**
	 * A thread can have many replies
	 * 
	 * @return Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function replies()
    {
    	return $this->hasMany(Reply::class);
    }

    /**	
     * A thread is created by a single user;
     * 
     * @return Illuminate\Database\Eloquent\BelongsTo
     */
    public function creator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Return the url path of the thread.
     * 
     * @return string
     */
    public function path()
    {
    	return '/threads/' . $this->id;
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }
}
