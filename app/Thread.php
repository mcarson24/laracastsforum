<?php

namespace App;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $fillable = ['user_id', 'title', 'body', 'channel_id'];

    protected $with = ['creator', 'channel'];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function($thread) {
            $thread->replies->each(function($reply) {
                $reply->delete();
            });
        });
    }

	/**
	 * A thread can have many replies.
	 * 
	 * @return Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function replies()
    {
    	return $this->hasMany(Reply::class);
    }

    /**	
     * A thread is created by a single user.
     * 
     * @return Illuminate\Database\Eloquent\BelongsTo
     */
    public function creator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread belongs to a single channel.
     * 
     * @return Illuminate\Database\Eloquent\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Return the url path of the thread.
     * 
     * @return string
     */
    public function path()
    {
        // return "/threads/{$this->channel->slug}/{$this->id}";
        return action('ThreadsController@show', [$this->channel, $this]);
    }

    /**
     * Add a new reply to the current thread.
     * 
     * @param   array $reply
     * @return  Reply
     */
    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
