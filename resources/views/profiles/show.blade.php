@extends('layouts.app')

@section('content')
	
	<div class="container">
		<div class="page-header">
			<h1>
				{{ $profileUser->name }}
				<small>joined us {{ $profileUser->created_at->diffForHumans() }}</small>
			</h1>
			
		</div>

		@foreach ($threads as $thread)
			<div class="panel panel-default">
                <div class="panel-heading">
					<div class="level">
						<span class="flex">
							{{ $thread->title }} 	
						</span>
	                	<span class="creator-name">
	                		{{ $thread->created_at->diffForHumans() }}
                		</span>
                	</div>
            	</div>

                <div class="panel-body">
                    {{ $thread->body }}
                    <hr>
                </div>

				<div class="container">
					<div class="row">
	                    <div class="col-md-8">
							{{ $threads->links() }}
	                    </div>
	                </div>
                </div>

            </div>
		@endforeach
	</div>

@endsection