@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $propertyValue->title}}
@stop

@section('content')
{!! Form::model($propertyValue, ['url'  => route('vanilo.property_value.update', [$property, $propertyValue]), 'method' => 'PUT', 'class' => 'row']) !!}

    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-accent-secondary">

            <div class="card-header">
                {{ __(':property Value', ['property' => $property->name]) }}
            </div>

            <div class="card-block">
                @include('vanilo::property-value._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-primary">{{ __('Save') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>

        </div>
    </div>

{!! Form::close() !!}
@stop
