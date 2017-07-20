<?php

namespace App;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $fillable = ['user_id', 'title', 'body', 'channel_id'];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function($builder) {
        //     $builder->withCount('replies');
        // });

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
     * Subscribe to a thread.
     * 
     * @param  integer $userId
     * @return null
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' =>  $userId ?: auth()->id()
        ]);
    }

    /**
     * Unsubscribe from a thread.
     * 
     * @param  integer $userId 
     * @return null         
     */
    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
             ->where('user_id', $userId ?: auth()->id())
             ->delete();
    }

    /**
     * Get a listing of this thread's subscriptions.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscriptions::class);
    }

    /**
     * Determine if the thread subscribed to by the current.
     * 
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
                    ->where('user_id', auth()->id())
                    ->exists();
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
