@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new product') }}
@stop

@section('content')

    <div class="row">

        <div class="col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __('Product Details') }}
                </div>
                <div class="card-block">

                    {!! Form::open(['route' => 'vanilo.product.store', 'autocomplete' => 'off']) !!}

                    @include('vanilo::product._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-success">{{ __('Create product') }}</button>
                        <a href="{{ route('vanilo.product.index') }}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>

@stop