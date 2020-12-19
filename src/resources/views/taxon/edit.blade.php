@extends('appshell::layouts.private')

@section('title')
    {{ __('Editing') }} {{ $taxon->name }}
@stop

@section('content')
<div class="row">
    <div class="col-12 col-lg-8 col-xl-9">
        {!! Form::model($taxon, ['url'  => route('vanilo.taxon.update', [$taxonomy, $taxon]), 'method' => 'PUT']) !!}
        <div class="card card-accent-secondary">

            <div class="card-header">
                {{ __(':category Data', ['category' => \Illuminate\Support\Str::singular($taxonomy->name)]) }}
            </div>

            <div class="card-body">
                @include('vanilo::taxon._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-primary">{{ __('Save') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>

        </div>
        {!! Form::close() !!}
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
        @include('vanilo::media._edit', ['model' => $taxon])
    </div>
</div>
@stop
