@extends('appshell::layouts.private')

@section('title')
    {{ __('Category Trees') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create taxonomies')
                    <a href="{{ route('vanilo.taxonomy.create') }}" class="btn btn-sm btn-outline-success float-right">
                        {!! icon('+') !!}
                        {{ __('New Category Tree') }}
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
                    <th>{{ __('Created') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($taxonomies as $taxonomy)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                            @can('view taxonomies')
                                <a href="{{ route('vanilo.taxonomy.show', $taxonomy) }}">{{ $taxonomy->name }}</a>
                            @else
                                {{ $taxonomy->name }}
                            @endcan
                            </span>
                        </td>
                        <td>{{ $taxonomy->slug }}</td>
                        <td><span title="{{ $taxonomy->created_at }}">{{ show_datetime($taxonomy->created_at) }}</span></td>
                        <td>
                            @can('edit taxonomies')
                                <a href="{{ route('vanilo.taxonomy.edit', $taxonomy) }}"
                                   class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            @endcan

                            @can('delete taxonomies')
                                {{ Form::open([
                                    'url' => route('vanilo.taxonomy.destroy', $taxonomy),
                                    'data-confirmation-text' => __('Delete this categorization: ":name"?', ['name' => $taxonomy->name]),
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
