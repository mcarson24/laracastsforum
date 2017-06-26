<?php

namespace App;

use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable, RecordsActivity;

	protected $fillable = ['body', 'user_id'];

    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($reply) {
            $reply->thread->increment('replies_count');
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

    public function thread()
    {
    	return $this->belongsTo(Thread::class);
    }
}
