@extends('appshell::layouts.default')

@section('title')
    {{ __('Viewing') }} {{ $taxonomy->name }}
@stop

@section('content')

    <div class="card">
        <div class="card-block">
            @can('edit taxonomies')
            <a href="{{ route('vanilo.taxonomy.edit', $taxonomy) }}" class="btn btn-outline-primary">{{ __('Edit Category Tree') }}</a>
            @endcan

            <a href="{{ route('vanilo.taxon.create', $taxonomy) }}" class="btn btn-link">{{ __('Add taxon') }}</a>
            @can('delete taxonomies')
                {!! Form::open(['route' => ['vanilo.taxonomy.destroy', $taxonomy], 'method' => 'DELETE', 'class' => "float-right"]) !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Category Tree') }}
                </button>
                {!! Form::close() !!}
            @endcan

        </div>
        <div class="card-block">
            @include('vanilo::taxon._tree', ['taxonomies' => $taxonomy->rootLevelTaxons()])
        </div>
    </div>

@stop
