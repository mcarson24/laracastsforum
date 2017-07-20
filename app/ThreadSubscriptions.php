<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class ThreadSubscriptions extends Model
{
    protected $guarded = [];

    /**
     * A ThreadSubscription belongs to a user.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    /**
     * A ThreadSubscription belongs to a thread.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Notify the subscribed user that a reply was posted to the subscribed thread.
     * 
     * @param  Reply  $reply [description]
     * @return null
     */
    public function notify(Reply $reply)
    {
        $this->user->notify(new ThreadWasUpdated($this->thread, $reply));
    }
}
