@extends('appshell::layouts.private')

@section('title')
    {{ __('Product Properties') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create properties')
                    <a href="{{ route('vanilo.property.create') }}" class="btn btn-sm btn-outline-success float-right">
                        {!! icon('+') !!}
                        {{ __('New Property') }}
                    </a>
                @endcan
            </div>

        </div>

        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Slug') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Created') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($properties as $property)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                            @can('view properties')
                                <a href="{{ route('vanilo.property.show', $property) }}">{{ $property->name }}</a>
                            @else
                                {{ $property->name }}
                            @endcan
                            </span>
                        </td>
                        <td>{{ $property->slug }}</td>
                        <td>{{ $property->getType()->getName() }}</td>
                        <td><span title="{{ $property->created_at }}">{{ show_datetime($property->created_at) }}</span></td>
                        <td>
                            @can('edit properties')
                                <a href="{{ route('vanilo.property.edit', $property) }}"
                                   class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            @endcan

                            @can('delete properties')
                                {{ Form::open([
                                    'url' => route('vanilo.property.destroy', $property),
                                    'data-confirmation-text' => __('Delete this property: ":name"?', ['name' => $property->name]),
                                    'method' => 'DELETE'
                                ])}}
                                    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                {{ Form::close() }}
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop
