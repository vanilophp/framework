@extends('appshell::layouts.default')

@section('title')
    {{ __('Products') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create products')
                    <a href="{{ route('vanilo.product.create') }}" class="btn btn-sm btn-outline-success float-right">
                        <i class="zmdi zmdi-plus"></i>
                        {{ __('New Product') }}
                    </a>
                @endcan
            </div>

        </div>

        <div class="card-block">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('SKU') }}</th>
                    <th>{{ __('State') }}</th>
                    <th>{{ __('Last update') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>
                            @can('view products')
                                <a href="{{ route('vanilo.product.show', $product) }}">{{ $product->name }}</a>
                            @else
                                {{ $product->name }}
                            @endcan
                        </td>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->state->label() }}</td>
                        <td><span title="{{ $product->updated_at }}">{{ $product->updated_at->diffForHumans() }}</span></td>
                        <td>
                            @can('edit products')
                                <a href="{{ route('vanilo.product.edit', $product) }}"
                                   class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            @endcan

                            @can('delete products')
                                <a href="{{ route('vanilo.product.destroy', $product) }}"
                                   class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop