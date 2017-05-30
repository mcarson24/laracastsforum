<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Favoritable;

	protected $fillable = ['body', 'user_id'];

    protected $with = ['owner', 'favorites'];

	/**
	 * A reply was written by a single user.
	 * 
	 * @return Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }
}
