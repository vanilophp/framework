    <div class="card card-accent-success">
        <div class="card-header">{{ __('Images') }}</div>
        <div class="card-block">
            @can('create media')
                <div class="card">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="p-3 bg-secondary">
                            <div class="align-content-center text-center">
                                <i class="zmdi font-2xl zmdi-collection-image"></i>
                            </div>
                        </div>
                        <div class="p-2">
                            {{ Form::file('images[]', ['multiple', 'class' => 'form-control-file']) }}
                        </div>
                    </div>
                </div>
            @endcan
            @if ($errors->has('images'))
                <div class="sinvalid-feedback">{{ $errors->first('images') }}</div>
            @endif
        </div>
    </div>
