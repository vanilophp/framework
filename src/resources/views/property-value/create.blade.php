@extends('appshell::layouts.default')

@section('title')
    {{ __('Add :property value', ['property' => $property->name]) }}
@stop

@section('content')
{!! Form::model($propertyValue, ['url' => route('vanilo.property_value.store', $property), 'autocomplete' => 'off', 'class' => 'row']) !!}
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-accent-success">
            <div class="card-header">
                {{ __('Value Details') }}
            </div>

            <div class="card-block">
                @include('vanilo::property-value._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-success">{{ __('Create :property value', ['property' => $property->name]) }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>
        </div>
    </div>
{!! Form::close() !!}
@stop
