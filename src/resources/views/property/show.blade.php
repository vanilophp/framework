@extends('appshell::layouts.default')

@section('title')
    {{ $property->name }} {{ __('property') }}
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            @can('create propertyvalues')
                <div class="card-footer">
                    <a href="{{ route('vanilo.property_value.create', $property) }}"
                       class="btn btn-outline-success btn-sm">{{ __('Add :property value', ['property' => $property->name]) }}</a>
                </div>
            @endcan
        </div>
        <div class="card-block">
            @include('vanilo::property-value._index', ['propertyValues' => $property->values()])
        </div>
    </div>

    <div class="card">
        <div class="card-block">
            @can('edit properties')
                <a href="{{ route('vanilo.property.edit', $property) }}" class="btn btn-outline-primary">{{ __('Edit Property') }}</a>
            @endcan

            @can('delete properties')
                {!! Form::open([
                        'route' => ['vanilo.property.destroy', $property],
                        'method' => 'DELETE',
                        'class' => 'float-right',
                        'data-confirmation-text' => __('Delete this property: ":name"?', ['name' => $property->name])
                    ])
                !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Property') }}
                </button>
                {!! Form::close() !!}
            @endcan
        </div>
    </div>

@stop
