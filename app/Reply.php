<?php

namespace App;

use App\RecordsActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

    protected $fillable = ['body', 'user_id'];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleting(function ($reply) {
            if ($reply->isBest())
            {
                $reply->thread->update(['best_reply_id' => null]);
            }
            $reply->thread->decrement('replies_count');
        });
    }

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
     * A reply belongs to a single thread.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Returns the reply's path.
     * 
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . '#reply-' . $this->id;
    }

    /**
     * Returns an array of all user's mentioned in a reply.
     
     * @return array
     */
    public function mentionedUsers()
    {
        preg_match_all('/\@([\w]+)/', $this->body, $matches);
        
        return $matches[1];
    }

    /**
     * Determine if the reply was just published.
     * 
     * @return boolean
     */
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    /**
     * Sets usernames in reply to links to the user's profile.
     * 
     * @param string
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/\@([\w\-]+)/', '<a href="/profiles/$1">$0</a>', $body);
    }

    /**
     * Has the reply been marked as the best reply of the thread?
     * 
     * @return boolean
     */
    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    /**
     * Return the reply's isBest attribute.
     * 
     * @return string
     */
    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
}
