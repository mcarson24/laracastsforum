@extends('layouts.app')

@section('content')
	<div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create A New Thread</div>

                    <div class="panel-body">
                        <form action="{{ action('ThreadsController@store') }}" method="POST">
                        	{{ csrf_field() }}
                        	<div class="form-group">
                        		<label for="title">Title:</label>
                        		<input type="text" class="form-control" name="title">
                        	</div>
                        	<div class="form-group">
	                        	<label for="body">Content:</label>
                        		<textarea name="body" id="body" class="form-control" rows="10"></textarea>
                        	</div>
                        	<div class="form-group">
                        		<button type="submit" class="btn btn-default btn-lg btn-block">Publish</button>
                        	</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection