@extends('appshell::layouts.private')

@section('title')
    {{ __('Editing') }} {{ $taxonomy->name }}
@stop

@section('content')
    <div class="row">

        <div class="col-12 col-md-6 col-lg-8 col-xl-9">
            {!! Form::model($taxonomy, [
                    'route'  => ['vanilo.taxonomy.update', $taxonomy],
                    'method' => 'PUT'
                ])
            !!}

            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Category Tree Data') }}
                </div>

                <div class="card-body">
                    @include('vanilo::taxonomy._form')
                </div>

                <div class="card-footer">
                    <button class="btn btn-primary">{{ __('Save') }}</button>
                    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>

            {!! Form::close() !!}
        </div>

        <div class="col-12 col-lg-4 col-xl-3">
            @include('vanilo::media._edit', ['model' => $taxonomy])
        </div>

    </div>
@stop
