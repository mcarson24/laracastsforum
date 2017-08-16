<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * Set the route key name equal to the User's name.
     * 
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * A User can have many threads.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    /**
     * A user can have many different activities.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * Store in the cache when this user last read a thread.
     * 
     * @param  Thread $thread [description]
     */
    public function read(Thread $thread)
    {
        cache()->forever(
            sprintf("users.%s.visits.%s", $this->id, $thread->id),
            Carbon::now()
        );
    }

    /**
     * Return the Cache Key for a given thread.
     * 
     * @param  Thread $thread 
     * @return string         
     */
    public function visitedThreadCacheKey(Thread $thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }


    /**
     * Returns the last reply that a users has left.
     * 
     * @return Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    /**
     * Return a path to the user's avatar.
     * 
     * @return string
     */
    public function avatar()
    {
        return asset($this->avatar_path ?? 'avatars/default.jpg');
    }
}
