@component('profiles.activities.activity')

	@slot('heading')
		Created a thread called 
		'<a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>'
	@endslot

	@slot('body')
		{{ $activity->subject->body }}
	@endslot

@endcomponent