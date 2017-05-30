<div class="panel-heading">
    {{ $reply->body }}
</div>
<div class="panel-body">
	<div class="level">
		<h5 class="flex">
		    by<a href="{{ action('ProfilesController@show', $reply->owner) }}"> {{ $reply->owner->name }}</a>
		    <span class="reply-time">{{ $reply->created_at->diffForHumans() }}</span>
		</h5>
	    <div>
	    	<form action="{{ action('FavoritesController@store', $reply) }}" method="POST">
    			{{ csrf_field() }}
				<button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
					{{ $reply->favorites_count }} {{ str_plural('favorite', $reply->favorites_count) }}
				</button>
	    	</form>
	    </div>
    </div>
</div>