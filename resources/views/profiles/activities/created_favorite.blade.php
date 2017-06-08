@component('profiles.activities.activity')

	@slot('heading')
		Favorited <a href="{{ action('ProfilesController@show', $activity->subject->favorited->owner) }}">{{ $activity->subject->favorited->owner->name }}'s</a> reply to
		'<a href="{{ $activity->subject->favorited->thread->path() }}#reply-{{ $activity->subject->favorited->id }}">{{ $activity->subject->favorited->thread->title }}</a>'
	@endslot

	@slot('body')
		{{ $activity->subject->favorited->body }}
	@endslot

@endcomponent