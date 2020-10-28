@extends('appshell::layouts.private')

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
                        {!! icon('+') !!}
                        {{ __('New Product') }}
                    </a>
                @endcan
            </div>

        </div>

        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Sales') }}</th>
                    <th>{{ __('Categorization') }}</th>
                    <th>{{ __('State') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                <?php $defaultThumbnail = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC8AAAAjCAMAAAAzO6PlAAAAt1BMVEX///+tq6u2tLSXlZW0srKenJ2LiYqzsLGZmJiNi4yqqKmvra2cmpqRj5CmpKSPjY6koqKUk5O4traopqagnp+Ih4exr6/Mycq5t7fOzMy7ubmioKCGhIWTkZLEwsK9u7vCv8DV09PS0NDQzs7Jx8fHxcWBgIF/fX58envX1dXZ19eDgoNwb3C/vb3U0dH7+vt1dHXh4eHe29vb2dl4d3j39/dpaGrx8fHo5+djYmPt7e309PRbW1zggo2RAAAC0UlEQVQ4y22T23qCMBCEA6LWA1qlchIBBaViPdVabe37P1dnN4GU72su9OafnZkNER9FsYs6Y7v11H55dvqDruknaZx72WG53Zbl5Z3O5VJut8vDapWJ12ITzTrjRZNfe8FqWQkUnAWB54lis4tmPc27lh/umV8doICgrOF8vSZ+1unZFW+41pT4PMgyFiia4ThNBY1H/AnzI8Wn69yTAhyaDTqO030SCsJ7DV4V9oIAgkOD9k0RMb/QPBf27pkUAKckTE9NyxXAEV/zvKDtURzfSQBaDqfZVndgiBmNR93WsOaTu6BzDzyKklfDQfcdgfEyfs3HDyHPdempLKE/peHOaC6AN3lkqc9ZFa3xtuDxNe9YlEWfb6/G+87zvP0k+UXFx1fRPMfDPgzVdOAtgTg1Pyo1qDMlqOoafeDDlt3gje9/+NseazeQ5mXYWozBc37e5/wfg3PIe+9zGruj5hM/BO+kzQLXwOc0BpedjGfM0z4p0BwfaPf+dz1p6KvxHIf4vwWcbNA3VsdqN5ck3Jd+V/LUttfk0feR9o3kxvgjT8LgKm6Ja6jtLMDje1CBkGcJrDSMwZmL+gn9v1su7kry4w6+N31jw3b4QGjTMLxbYJkxfK45x+f8TxPwkRJMIEAk54zcHiy6FvW4hxbx3Jf22RMRPxjlQJ09YGd3YN0h3E5NrMcd0HVRoImN9ygdtMDCLd+CB35iX/PSYCI2m91OWti2FMxHF3mzpjnFE2SeDdCgJYqigAQe9bPBtcVXFMVLNnm+MmCBeMWBQj97+o6e3YuFl2+Bl4EqQVucPj4gKMhCvXskwsPBS3PBk0ElIIV4O51OUEgBSksD4mGgBRQJFiPxhqMEqoI2IB4C1YEV4vMTAlhAIBNpgwEaT32fLZTCEF9fULADJdIGzMPA91lRScCzQBtUgVQBPwxDLbHEz48W7CJeEQWSBZhPEkgq0S8ch3VdK2koBAAAAABJRU5ErkJggg=='; ?>
                @foreach($products as $product)
                    <tr>
                        <td>
                            @can('view products')<a href="{{ route('vanilo.product.show', $product) }}">@endcan
                                <img src="{{ $product->getThumbnailUrl() ?: $defaultThumbnail }}" class="mw-100" style="height: 2.5em;" />
                            @can('view products')</a>@endcan
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                @can('view products')<a href="{{ route('vanilo.product.show', $product) }}">@endcan
                                    {{ $product->name }}
                                @can('view products')</a>@endcan
                            </span>
                            <div class="text-muted" title="{{ __('SKU') }}">{{ $product->sku }}</div>
                        </td>
                        <td>
                            <span class="mb-3">
                                {{ $product->units_sold }} {{ __('units sold') }}
                            </span>
                            <div class="text-muted" title="{{ show_datetime($product->last_sale_at) }}">
                                {{ __('Last sale') }}
                                {{ show_datetime($product->last_sale_at, __('never')) }}
                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                @foreach($product->taxons as $taxon)
                                    <span class="badge badge-pill badge-secondary">
                                        {{ $taxon->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="mt-2">
                                <span class="badge badge-pill badge-{{$product->is_active ? 'success' : 'secondary'}}">{{ $product->state->label() }}</span>
                            </div>
                        </td>
                        <td>
                            @can('edit products')
                                <a href="{{ route('vanilo.product.edit', $product) }}"
                                   class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            @endcan
                            @can('delete products')
                                {!! Form::open(['route' => ['vanilo.product.destroy', $product],
                                        'method' => 'DELETE',
                                        'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => $product->name])
                                    ])
                                !!}
                                <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            @if($products->hasPages())
                <hr>
                <nav>
                    {{ $products->links() }}
                </nav>
            @endif

        </div>
    </div>

@stop
