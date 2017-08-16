@forelse ($threads as $thread)
    <div class="panel panel-default">   
        <div class="panel-heading">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href="{{ action('ThreadsController@show', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">
                            @if ($thread->hasUpdatesForUser())
                                <strong>{{ $thread->title }}</strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>
                    <h5>by: <a href="{{ action('ProfilesController@show', $thread->creator) }}">{{ $thread->creator->name }}</a></h5>
                </div>
                <a href="{{ action('ThreadsController@show', [$thread->channel, $thread]) }}">
                    <strong>{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</strong>
                </a>
            </div>
        </div>
        <div class="panel-body">
            <article>
                
                <div class="body">{{ $thread->body }}</div>
            </article>
        </div>
    </div>
@empty
    <p>There is nothing here. Why not 
        <a href="{{ action('ThreadsController@create') }}">start a thread of your own</a>?
    </p>
@endforelse