@extends('appshell::layouts.private')

@section('title')
    {{ $property->name }} {{ __('property') }}
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h5>{{ __(':name Values', ['name' => $property->name]) }}</h5>
        </div>
        <div class="card-body">
            @include('vanilo::property-value._index', ['propertyValues' => $property->values()])
        </div>
    </div>

    <div class="card">
        <div class="card-body">
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
