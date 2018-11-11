@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $taxonomy->name }}
@stop

@section('content')
{!! Form::model($taxonomy, [
        'route'  => ['vanilo.taxonomy.update', $taxonomy],
        'method' => 'PUT'
    ])
!!}

    <div class="card card-accent-secondary">
        <div class="card-header">
            {{ __('Category Tree Data') }}
        </div>

        <div class="card-block">
            @include('vanilo::taxonomy._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Save') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

{!! Form::close() !!}
@stop
