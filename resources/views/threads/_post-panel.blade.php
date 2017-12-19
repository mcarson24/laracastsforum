{{-- Editing the post --}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}'s avatar" height="75" width="75" class="mr-1 br-5">
            <span class="flex">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="form.title" required>
                </div>
            </span>
        </div>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <textarea class="form-control" rows="10" v-model="form.body" required></textarea>
        </div>
    </div>
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-default btn-xs mr-1" @click="update">Update</button>
            <button class="btn btn-default btn-xs" @click="cancel">Cancel</button>
            @can ('update', $thread)
                <form action="{{ action('ThreadsController@destroy', [$thread->channel, $thread]) }}" method="POST" class="m-l-auto">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link">Delete</button>
                </form>
            @endcan
        </div>
    </div>
</div>

{{-- Viewing the post --}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}'s avatar" height="75" width="75" class="mr-1 br-5">
            <span class="flex">
                @{{ form.title }} posted by: <span class="creator-name"><a href="{{ action('ProfilesController@show', $thread->creator) }}">{{ $thread->creator->name }}</a></span>
            </span>
        </div>
    </div>
    <div class="panel-body" v-text="form.body"></div>
    @can ('update', $thread)
        <div class="panel-footer">
            <div class="level">
                <button class="btn btn-default btn-xs" @click="toggleEdit">Edit</button>
                <form action="{{ action('ThreadsController@destroy', [$thread->channel, $thread]) }}" method="POST" class="m-l-auto">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-link">Delete</button>
                </form>
            </div>
        </div>
    @endcan
</div>
