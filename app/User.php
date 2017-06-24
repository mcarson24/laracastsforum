<?php

namespace App;

use App\Activity;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}
