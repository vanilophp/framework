@extends('appshell::layouts.private')

@section('title')
    {{ __('Create Property') }}
@stop

@section('content')
{!! Form::open(['route' => 'vanilo.property.store', 'autocomplete' => 'off']) !!}

    <div class="card card-accent-success">

        <div class="card-header">
            {{ __('Property Details') }}
        </div>

        <div class="card-body">
            @include('vanilo::property._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create property') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

{!! Form::close() !!}
@stop
