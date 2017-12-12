@extends('layouts.app')

@section('head-end')
    <link rel="stylesheet" href="{{ asset('css/vendor/atwho.css') }}">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="level">
                                <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}'s avatar" height="75" width="75" class="mr-1 br-5">
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
                            <replies @added="repliesCount++" 
                                     @removed="repliesCount--">
                            </replies>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p>This discussion was created {{ $thread->created_at->diffForHumans() }} by <a href="{{ action('ProfilesController@show', $thread->creator) }}">{{ $thread->creator->name }}</a>, it currently has <span v-text="repliesCount"></span> {{ str_plural('reply', $thread->replies_count) }}.</p>
                                <p>
                                    <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>

                                    <button class="button btn btn-default" v-if="authorize('isAdmin')" @click="toggleLock" v-text="locked ? 'Unlock': 'Lock'"></button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
