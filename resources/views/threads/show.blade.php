@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $thread->title }} posted by: <span class="creator-name"><a href="#">{{ $thread->creator->name }}</a></span></div>

                <div class="panel-body">
                    {{ $thread->body }}
                </div>

                @if ($thread->replies)
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="panel">
                                @foreach ($thread->replies as $reply)
                                    @include('threads._reply')
                                @endforeach
                            </div>  
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
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
    </div>
</div>
@endsection
