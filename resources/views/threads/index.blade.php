@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach ($threads as $thread)
                    <div class="panel panel-default">   
                        <div class="panel-heading">
                            <div class="level">
                                <h4 class="flex">
                                    <a href="{{ action('ThreadsController@show', ['channel' => $thread->channel->slug, 'thread' => $thread]) }}">{{ $thread->title }}</a>
                                </h4>
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
                    @endforeach
                    @if ($threads->isEmpty())
                        <p>There is nothing here. Why not start a thread of your own 
                            <a href="{{ action('ThreadsController@create') }}">here</a>?
                        </p>
                    @endif
            </div>
        </div>
    </div>
@endsection
