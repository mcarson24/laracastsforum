<?php

namespace App;

use App\RecordsVisits;
use App\RecordsActivity;
use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity, RecordsVisits;

    protected $fillable = ['user_id', 'title', 'body', 'channel_id', 'slug', 'best_reply_id', 'locked'];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    protected $casts = [
        'locked'    => 'boolean'
    ];

    public static function boot()
    {
        parent::boot();

        // static::addGlobalScope('replyCount', function($builder) {
        //     $builder->withCount('replies');
        // });

        static::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
        });

        static::deleting(function ($thread) {
            $thread->replies->each(function ($reply) {
                $reply->delete();
            });
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
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
     * Lock the thread.
     */
    public function lock()
    {
        $this->update(['locked' => true]);
    }

    /**
     * Unlock the thread.
     */
    public function unlock()
    {
        $this->update(['locked' => false]);
    }

    /**
     * Subscribe to a thread.
     *
     * @param  integer|null $userId
     * @return $this
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' =>  $userId ?: auth()->id()
        ]);

        return $this;
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
     * Determine if the thread has been updated since
     * the user last visited the thread.
     * 
     * @return boolean
     */
    public function hasUpdatesForUser()
    {
        if (auth()->guest()) {
            return;
        }
        
        return $this->updated_at > cache(auth()->user()->visitedThreadCacheKey($this));
    }

    /**
     * Return the url path of the thread.
     *
     * @return string
     */
    public function path()
    {
        return url("/threads/{$this->channel->slug}/{$this->slug}");
    }

    /**
     * Add a new reply to the current thread.
     *
     * @param   array $reply
     * @return  Reply
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadHasNewReply($this, $reply));

        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Set the proper slug attribute.
     *
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);

        if (static::whereSlug($slug)->exists()) {
            $slug = "{$slug}-" . $this->id;
        }

        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
}
