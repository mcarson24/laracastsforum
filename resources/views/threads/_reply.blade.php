<reply :attributes="{{ $reply }}" inline-template v-cloak>
	<div>
		<div id="reply-{{ $reply->id }}" class="panel-heading">
			<div v-if="editing">
				<div class="form-group">
					<textarea class="form-control" v-model="body"></textarea>
				</div>
				<button class="btn btn-link btn-xs" @click="update">Update</button>
				<button class="btn btn-default btn-xs" @click="editing = false">Cancel</button>
			</div>
			<div v-else v-text="body"></div>
		</div>
		<div class="panel-body">
			<div class="level">
				<h5 class="flex">
				    by<a href="{{ action('ProfilesController@show', $reply->owner) }}"> {{ $reply->owner->name }}</a>
				    <span class="reply-time">{{ $reply->created_at->diffForHumans() }}</span>
				</h5>
			    	@if (auth()->check())
				    	<favorite :reply="{{ $reply }}"></favorite>
			    	@else
				    	<span class="glyphicon glyphicon-heart mr-quarter"></span>
			    		<span>{{ $reply->favoritesCount }}</span>
			    	@endif
		    </div>
		</div>
		@can ('update', $reply)
			<div class="panel-footer level">
				<button class="btn btn-default btn-xs mr-1" @click="editing = !editing">Edit</button>
				<button class="btn btn-default btn-xs" @click="destroy">Delete</button>
			</div>
		@endcan
	</div>
</reply>
