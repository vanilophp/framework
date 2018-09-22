@extends('appshell::layouts.default')

@section('title')
    {{ __('Create Category Tree') }}
@stop

@section('content')

    {!! Form::open(['route' => 'vanilo.taxonomy.store', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row']) !!}

        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __('Category Tree Details') }}
                </div>
                <div class="card-block">

                    @include('vanilo::taxonomy._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-success">{{ __('Create category tree') }}</button>
                        <a href="{{ route('vanilo.taxonomy.index') }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>

    {!! Form::close() !!}

@stop
