@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $taxon->name }}
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Category Data') }}
                </div>
                <div class="card-block">

                    <?php $formClass = $errors->isEmpty() ? '' : 'was-walidated'; ?>
                    {!! Form::model($taxon, [
                            'url'  => route('vanilo.taxon.update', [$taxonomy, $taxon]),
                            'method' => 'PUT',
                            'class' => $formClass
                            ]
                    ) !!}

                    @include('vanilo::taxon._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('Save') }}</button>
                        <a href="{{ route('vanilo.taxonomy.show', $taxonomy) }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
