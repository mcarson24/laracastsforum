@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $thread->title }}</div>

                <div class="panel-body">
                    {{ $thread->body }}
                </div>

                @if ($thread->replies)
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="panel">
                                @foreach ($thread->replies as $reply)
                                    <div class="panel-body">
                                        {{ $reply->body }}
                                    </div>
                                    <div class="panel-footer">
                                        by {{ $reply->owner->name }}
                                        <span class="reply-time">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                @endforeach
                            </div>  
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
