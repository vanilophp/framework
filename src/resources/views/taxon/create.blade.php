@extends('appshell::layouts.default')

@section('title')
    {{ __('Create :category', ['category' => str_singular($taxonomy->name)]) }}
@stop

@section('content')
    {!! Form::model($taxon, ['url' => route('vanilo.taxon.store', $taxonomy), 'autocomplete' => 'off', 'class' => 'row']) !!}
        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __(':category Details', ['category' => str_singular($taxonomy->name)]) }}
                </div>
                <div class="card-block">

                    @include('vanilo::taxon._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-success">{{ __('Create :category', ['category' => str_singular($taxonomy->name)]) }}</button>
                        <a href="{{ route('vanilo.taxonomy.show', $taxonomy) }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}
@stop
