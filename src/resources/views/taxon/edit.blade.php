@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $taxon->name }}
@stop

@section('content')
{!! Form::model($taxon, ['url'  => route('vanilo.taxon.update', [$taxonomy, $taxon]), 'method' => 'PUT', 'class' => 'row']) !!}

    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-accent-secondary">

            <div class="card-header">
                {{ __(':category Data', ['category' => \Illuminate\Support\Str::singular($taxonomy->name)]) }}
            </div>

            <div class="card-block">
                @include('vanilo::taxon._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-primary">{{ __('Save') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>

        </div>
    </div>

{!! Form::close() !!}
@stop
