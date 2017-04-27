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
            </div>
        </div>
    </div>
</div>
@endsection
