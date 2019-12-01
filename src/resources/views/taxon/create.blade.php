@extends('appshell::layouts.default')

<?php $taxonTypeName =  \Illuminate\Support\Str::singular($taxonomy->name) ?>

@section('title')
    {{ __('Create :category', ['category' => $taxonTypeName]) }}
@stop

@section('content')
{!! Form::model($taxon, ['url' => route('vanilo.taxon.store', $taxonomy), 'autocomplete' => 'off', 'class' => 'row']) !!}
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-accent-success">
            <div class="card-header">
                {{ __(':category Details', ['category' => $taxonTypeName]) }}
            </div>

            <div class="card-block">
                @include('vanilo::taxon._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-success">{{ __('Create :category', ['category' => $taxonTypeName]) }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>
        </div>
    </div>
{!! Form::close() !!}
@stop
