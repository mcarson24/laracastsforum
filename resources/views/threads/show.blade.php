@extends('layouts.app')

@section('content')
    <thread-view :intial-replies-count="{{ $thread->replies->count() }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <span class="flex">
                                    {{ $thread->title }} posted by: <span class="creator-name"><a href="{{ action('ProfilesController@show', $thread->creator) }}">{{ $thread->creator->name }}</a></span>
                                </span>
                                @can ('update', $thread)
                                    <form action="{{ action('ThreadsController@destroy', [$thread->channel, $thread]) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="btn btn-link">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        <div class="panel-body">
                            {{ $thread->body }}
                            <hr>
                        </div>

                        @if ($thread->replies)
                            <replies :data="{{ $thread->replies }}" @removed="repliesCount--"></replies>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-1">
                                    {{ $replies->links() }}
                                </div>
                            </div> 
                        @endif
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1 mr-top-25">
                                @if (auth()->check())
                                    <form action="{{ action('RepliesController@store', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}" method="POST">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <textarea name="body" id="body" class="form-control" placeholder="Got something to say?"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-default btn-block">Post</button>
                                        </div>
                                    </form>
                                @else
                                    <p class="text-center"><a href="{{ route('register') }}">Register</a> to participate in this discussion.</p>
                                    <p class="text-center">Already a member? <a href="{{ route('login') }}">Sign in here</a>!</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p>This discussion was created {{ $thread->created_at->diffForHumans() }} by <a href="#">{{ $thread->creator->name }}</a>, it currently has <span v-text="repliesCount"></span> {{ str_plural('reply', $thread->replies_count) }}.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
