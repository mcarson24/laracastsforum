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
                        		<input type="text" class="form-control {{ $errors->has('title') ? 'error' : '' }}" name="title" value="{{ old('title') }}">
                        	</div>
                        	<div class="form-group">
	                        	<label for="body">Content:</label>
                        		<textarea name="body" id="body" class="form-control {{ $errors->has('body') ? 'error' : '' }}" rows="10">{{ old('body') }}</textarea>
                        	</div>
                        	<div class="form-group">
                        		<button type="submit" class="btn btn-default btn-lg btn-block">Publish</button>
                        	</div>
                        </form>
                        @if (session()->has('errors'))
                            <ul class="error-box">
                                @foreach ($errors->all() as $error)
                                    <li class="error-item">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection