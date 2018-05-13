@if($product->hasImage())

    <div class="col-sm-12 col-md-9 col-xl-4">
        <div class="card">
            <div class="card-header">
                {{ __('Images') }}
            </div>
            <div class="card-block">

                <div id="product-images" class="carousel slide" data-ride="carousel" data-interval="false">

                    <ol class="carousel-indicators">
                        @foreach($product->getMedia() as $media)
                        <li data-target="#product-images" data-slide-to="{{ $loop->index }}"{{ $loop->first ? ' class="active"' : ''}}></li>
                        @endforeach
                    </ol>

                    <div class="carousel-inner">
                        @foreach($product->getMedia() as $media)
                            <div class="carousel-item{{ $loop->first ? ' active' : ''}}">
                                <img class="d-block w-100" src="{{ $media->getUrl() }}" alt="{{ $media->name }}">
                            </div>
                        @endforeach
                    </div>

                    <a class="carousel-control-prev" href="#product-images" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">{{ __('Previous') }}</span>
                    </a>
                    <a class="carousel-control-next" href="#product-images" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">{{ __('Next') }}</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

@else
    <div class="col-12">
        <div class="alert alert-secondary">{{ __('No image') }}</div>
    </div>
@endif
