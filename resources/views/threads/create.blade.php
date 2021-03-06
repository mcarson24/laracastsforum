@extends('layouts.app')

@section('head-end')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

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
                                <label for="channel_id">Select a channel:</label>
                                <select name="channel_id" 
                                        id="channel_id" 
                                        class="form-control {{ $errors->has('channel_id') ? 'error' : '' }}"
                                        required>
                                    <option value="">Choose One...</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}" {{ old('channel_id') == $channel->id ? 'selected' : '' }}>{{ $channel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        	<div class="form-group">
                        		<label for="title">Title:</label>
                        		<input type="text" 
                                       class="form-control {{ $errors->has('title') ? 'error' : '' }}" 
                                       name="title" 
                                       value="{{ old('title') }}"
                                       required>
                        	</div>
                        	<div class="form-group">
	                        	<label for="body">Content:</label>
                        		<textarea name="body" 
                                          id="body" 
                                          class="form-control {{ $errors->has('body') ? 'error' : '' }}" 
                                          rows="10"
                                          required>{{ old('body') }}
                                </textarea>
                        	</div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6LfL9TwUAAAAALh6Fl8X74SO-3tV9uH_lxb0itHJ"></div>
                            </div>
                        	<div class="form-group">
                        		<button type="submit" class="btn btn-default btn-lg btn-block">Publish</button>
                        	</div>
                        </form>
                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
