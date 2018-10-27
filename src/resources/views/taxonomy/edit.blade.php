@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $taxonomy->name }}
@stop

@section('content')

    <div class="row">

        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Category Tree Data') }}
                </div>
                <div class="card-block">

                    <?php $formClass = $errors->isEmpty() ? '' : 'was-walidated'; ?>
                    {!! Form::model($taxonomy, [
                            'route'  => ['vanilo.taxonomy.update', $taxonomy],
                            'method' => 'PUT',
                            'class' => $formClass
                            ]
                    ) !!}

                    @include('vanilo::taxonomy._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('Save') }}</button>
                        <a href="{{ route('vanilo.taxonomy.index') }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>

                    {!! Form::close() !!}
                </div>
                <div class="card-footer">
                    @can('delete taxonomies')
                        {!! Form::open(['route' => ['vanilo.taxonomy.destroy', $taxonomy], 'method' => 'DELETE']) !!}
                        <button class="btn btn-outline-danger float-right">
                            {{ __('Delete Category Tree') }}
                        </button>
                        {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>

    </div>

@stop
