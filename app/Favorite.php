<?php

namespace App;

use App\User;
use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
	use RecordsActivity;

	protected $fillable = ['user_id', 'favorited_id', 'favorited_type'];

	/**
	 * Get the model that was favorited.
	 * 
	 * @return Illuminate\Database\Eloquent\Relations\MorphTo
	 */
	public function favorited()
	{
		return $this->morphTo();
	}
}
