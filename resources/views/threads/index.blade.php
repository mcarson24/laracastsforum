@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Forum Threads</div>

                    <div class="panel-body">
                        @if ($threads->isEmpty())
                            <p>There is nothing here. Why not start a thread of your own 
                                <a href="{{ action('ThreadsController@create') }}">here</a>?
                            </p>
                        @endif
                        @foreach ($threads as $thread)
                            <article>
                                <div class="level">
                                    <h4 class="flex">
                                        <a href="{{ action('ThreadsController@show', [$thread->channel, $thread]) }}">{{ $thread->title }}</a>
                                    </h4>
                                    <a href="{{ action('ThreadsController@show', [$thread->channel, $thread]) }}">
                                        <strong>{{ $thread->replies_count }} {{ str_plural('reply', $thread->replies_count) }}</strong>
                                    </a>
                                </div>
                                <div class="body">{{ $thread->body }}</div>
                                <hr>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
