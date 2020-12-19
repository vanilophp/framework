@extends('appshell::layouts.private')

@section('title')
    {{ __('Create new product') }}
@stop

@section('content')

    {!! Form::open(['route' => 'vanilo.product.store', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row']) !!}

        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __('Product Details') }}
                </div>
                <div class="card-body">
                    @include('vanilo::product._form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Create product') }}</button>
                    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xl-3">
            @include('vanilo::media._create')
        </div>

    {!! Form::close() !!}

@stop
