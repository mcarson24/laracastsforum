<?php

namespace App;

use App\Activity;

trait RecordsActivity
{
	protected static function bootRecordsActivity()
	{
		if (auth()->guest()) return;

		foreach (static::getRecordableActivities() as $activity)
		{
			static::$activity(function($model) use ($activity) {
				$model->recordActivity($activity);
			});
		}
	}

	protected static function getRecordableActivities()
	{
		return ['created'];
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