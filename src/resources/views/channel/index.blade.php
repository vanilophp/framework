@extends('appshell::layouts.private')

@section('title')
    {{ __('Channels') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create channels')
                    <a href="{{ route('vanilo.channel.create') }}" class="btn btn-sm btn-outline-success float-right">
                        {!! icon('+') !!}
                        {{ __('Create Channel') }}
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
                @foreach($channels as $channel)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                            @can('view channels')
                                <a href="{{ route('vanilo.channel.show', $channel) }}">{{ $channel->name }}</a>
                            @else
                                {{ $channel->name }}
                            @endcan
                            </span>
                        </td>
                        <td>{{ $channel->slug }}</td>
                        <td><span title="{{ $channel->created_at }}">{{ show_datetime($channel->created_at) }}</span></td>
                        <td>
                            @can('edit channels')
                                <a href="{{ route('vanilo.channel.edit', $channel) }}"
                                   class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            @endcan

                            @can('delete channels')
                                {{ Form::open([
                                    'url' => route('vanilo.channel.destroy', $channel),
                                    'data-confirmation-text' => __('Delete this channel: ":name"?', ['name' => $channel->name]),
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
