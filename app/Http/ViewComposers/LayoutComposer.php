<?php

namespace App\Http\ViewComposers;

use App\Channel;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class LayoutComposer 
{
	/**
	 * The channel categories for a thread.
	 * 
	 * @var Channel $channels
	 */
	protected $channels;

	/**
	 * Create a new layout view composer.
	 * 
	 * @return  void
	 */
	public function __construct()
	{
		$this->channels = Cache::remember('channels', 100, function () {
			return Channel::all();
		});
	}

	/**
	 * Bind the channels to the layouts.app view.
	 * 
	 * @param  View   $view 
	 * @return void
	 */
	public function compose(View $view)
	{
		$view->with('channels', $this->channels);
	}
}
