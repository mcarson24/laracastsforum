@extends('layouts.app')

@section('head-end')
    <link rel="stylesheet" href="{{ asset('css/vendor/atwho.css') }}">
@endsection

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template v-cloak>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    @include('threads._post-panel')
                </div>
                <div class="col-md-4">
                    <div class="row">
                        @include('threads._side-panel')
                    </div>
                </div>
                <div class="col-md-8">
                    @include('threads._replies-panel')
                </div>
            </div>
        </div>
    </thread-view>
@endsection
