@extends('appshell::layouts.private')

<?php $taxonTypeName =  \Illuminate\Support\Str::singular($taxonomy->name) ?>

@section('title')
    {{ __('Create :category', ['category' => $taxonTypeName]) }}
@stop

@section('content')
{!! Form::model($taxon, ['url' => route('vanilo.taxon.store', $taxonomy), 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row']) !!}
    <div class="col-12 col-lg-8 col-xl-9 mb-4">
        <div class="card card-accent-success">
            <div class="card-header">
                {{ __(':category Details', ['category' => $taxonTypeName]) }}
            </div>

            <div class="card-body">
                @include('vanilo::taxon._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-success">{{ __('Create :category', ['category' => $taxonTypeName]) }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4 col-xl-3">
        @include('vanilo::media._create')
    </div>

{!! Form::close() !!}
@stop
