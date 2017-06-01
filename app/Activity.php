<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function subject()
    {
    	return $this->morphTo();
    }

    public static function feed(User $user, $amount = 35)
    {
    	return static::where('user_id', auth()->id())
    				->latest()
    				->with('subject')
    				->take($amount)
    				->get()
    				->groupBy(function($activity) {
    					return $activity->created_at->format('Y-m-d');
    				});
    }
}
