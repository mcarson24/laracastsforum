@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')
                {{ $threads->render() }}
            </div>
            @if (count($trending))
		        <div class="col-md-4">
		        	<div class="panel panel-default">
		        		<div class="panel-heading">
		        			Trending Threads
		        		</div>
		        		<div class="panel-body">
		        			<div class="list-group">
			        			@foreach ($trending as $thread)
									<li class="list-group-item"><a href="{{ $thread->path }}">{{ $thread->title }}</a></li>
			        			@endforeach
		        			</div>
		        		</div>
		        	</div>
		        </div>
	        @endif
        </div>
    </div>
@endsection
