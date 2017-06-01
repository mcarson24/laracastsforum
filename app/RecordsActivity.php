<?php

namespace App;

use App\Activity;

trait RecordsActivity
{
	protected static function bootRecordsActivity()
	{
		if (auth()->guest()) return;
		static::created(function($model) {
			$model->recordActivity('created');
		});
	}

	protected function recordActivity($eventType)
    {
    	$this->activity()->create([
    		'type'              => $this->getActivityType($eventType),
            'user_id'           => auth()->id()
		]);
    }

    public function activity()
    {
    	return $this->morphMany(Activity::class, 'subject');
    }

    protected function getActivityType($eventType)
    {
        return $eventType . '_' . strtolower((new \ReflectionClass($this))->getShortName());
    }
}