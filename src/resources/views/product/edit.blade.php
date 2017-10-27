@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $product->name }}
@stop

@section('content')

    <div class="row">

        <div class="col-xl-9">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Product Data') }}
                </div>
                <div class="card-block">

                    <?php $formClass = $errors->isEmpty() ? '' : 'was-walidated'; ?>
                    {!! Form::model($product, [
                            'route'  => ['vanilo.product.update', $product],
                            'method' => 'PUT',
                            'class' => $formClass
                            ]
                    ) !!}

                    @include('vanilo::product._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('Save') }}</button>
                        <a href="{{ route('vanilo.product.index') }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>

                    {!! Form::close() !!}
                </div>
                <div class="card-footer">
                    @can('delete products')
                        {!! Form::open(['route' => ['vanilo.product.destroy', $product], 'method' => 'DELETE']) !!}
                        <button class="btn btn-outline-danger float-right">
                            {{ __('Delete product') }}
                        </button>
                        {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>

    </div>


@stop