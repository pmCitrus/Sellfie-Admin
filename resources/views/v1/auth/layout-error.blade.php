@extends('v1.auth.layout')
@section('title', $title )
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{ $panel_title }}</h3>
                    </div>
                    <div class="panel-body">
                        {{ $error_message }}
                        <a class="btn btn-link" href="{{ $url }}"> {{ $url_title }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection