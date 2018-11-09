@extends('appshell::layouts.default')

@section('title')
    {{ __('Viewing') }} {{ $taxonomy->name }}
@stop

@section('content')

    <div class="card">
        <div class="card-block">
            <div class="card">
                @include('vanilo::taxon._tree', ['taxons' => $taxonomy->rootLevelTaxons()])

                @can('create taxons')
                    <div class="card-footer">
                        <a href="{{ route('vanilo.taxon.create', $taxonomy) }}"
                           class="btn btn-outline-success btn-sm">{{ __('Add :category', ['category' => str_singular($taxonomy->name)]) }}</a>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-block">
            @can('edit taxonomies')
                <a href="{{ route('vanilo.taxonomy.edit', $taxonomy) }}" class="btn btn-outline-primary">{{ __('Edit Category Tree') }}</a>
            @endcan

            @can('delete taxonomies')
                {!! Form::open(['route' => ['vanilo.taxonomy.destroy', $taxonomy], 'method' => 'DELETE', 'class' => "float-right"]) !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Category Tree') }}
                </button>
                {!! Form::close() !!}
            @endcan
        </div>
    </div>

@stop
