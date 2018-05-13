@if($product->hasImage())

    <div class="card card-accent-secondary">
        <div class="card-header">{{ __('Images') }} <span
                    class="badge badge-pill badge-info">{{ $product->getMedia()->count() }}</span>
        </div>
        <div class="card-block">
            @foreach($product->getMedia() as $media)
                <div class="card">
                    <div class="card-body p-0 d-flex align-items-center">
                        <img class="mr-3 w-25" src="{{ $media->getUrl('thumbnail') }}"
                             alt="{{ $media->name }}" title="{{ $media->name }}">
                        <div class="w-50">
                            <div class="text-sm-left text-info font-weight-bold">
                                <span title="{{ $media->getPath() }}">{{ $media->human_readable_size }}</span>
                            </div>
                            <div class="text-muted text-uppercase font-weight-bold small">
                                <a href="{{ $media->getUrl() }}" title="{{ $media->getUrl() }}"
                                   target="_blank"><i class="zmdi zmdi-link"></i></a>
                            </div>
                        </div>
                        <div class="w-25 p-2 b-l-1">
                            <div class="align-content-center text-center">
                                @can('delete media')
                                    {!! Form::open(['route' => ['vanilo.media.destroy', $media], 'method' => 'DELETE', 'class' => "float-right"]) !!}
                                    <button class="btn btn-sm btn-outline-danger" title="{{ __('Delete image') }}">
                                        <i class="zmdi zmdi-close"></i>
                                    </button>
                                    {!! Form::close() !!}
                                @endcan
                            </div>
                        </div>

                    </div>

                </div>
            @endforeach
        </div>
    </div>

@else
    <div class="col-12">
        <div class="alert alert-secondary">{{ __('No image') }}</div>
    </div>
@endif
