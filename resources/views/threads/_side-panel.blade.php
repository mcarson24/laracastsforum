<div class="panel panel-default">
    <div class="panel-body">
        <p>This discussion was created {{ $thread->created_at->diffForHumans() }} by <a href="{{ action('ProfilesController@show', $thread->creator) }}">{{ $thread->creator->name }}</a>, it currently has <span v-text="repliesCount"></span> {{ str_plural('reply', $thread->replies_count) }}.</p>
        <p>
            <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

            <button class="button btn btn-default" v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unlock': 'Lock'"></button>
        </p>
    </div>
</div>
