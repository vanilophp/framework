@extends('appshell::layouts.private')

@section('title')
    {{ __('Create Category Tree') }}
@stop

@section('content')
{!! Form::open(['route' => 'vanilo.taxonomy.store', 'autocomplete' => 'off','enctype'=>'multipart/form-data', 'class' => 'row']) !!}

    <div class="col-12 col-lg-8 col-xl-9 mb-4">
        <div class="card card-accent-success">

            <div class="card-header">
                {{ __('Category Tree Details') }}
            </div>

            <div class="card-body">
                @include('vanilo::taxonomy._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-success">{{ __('Create category tree') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
        @include('vanilo::media._create')
    </div>

{!! Form::close() !!}
@stop
