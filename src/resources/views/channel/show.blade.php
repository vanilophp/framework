@extends('appshell::layouts.private')

@section('title')
    {{ $channel->name }} {{ __('channel') }}
@stop

@section('content')

    <div class="card">
        <div class="card-body">
            @can('edit channels')
                <a href="{{ route('vanilo.channel.edit', $channel) }}" class="btn btn-outline-primary">{{ __('Edit Channel') }}</a>
            @endcan

            @can('delete channels')
                {!! Form::open([
                        'route' => ['vanilo.channel.destroy', $channel],
                        'method' => 'DELETE',
                        'class' => 'float-right',
                        'data-confirmation-text' => __('Delete this channel: ":name"?', ['name' => $channel->name])
                    ])
                !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Channel') }}
                </button>
                {!! Form::close() !!}
            @endcan
        </div>
    </div>

@stop
