    <div class="card card-accent-success">
        <div class="card-header">{{ __('Images') }}</div>
        <div class="card-body">
            @can('create media')
                <div class="card">
                    <div class="card-body p-0 d-flex align-items-center">
                        <div class="p-3 bg-secondary">
                            <div class="align-content-center text-center">
                                {!! icon('image') !!}
                            </div>
                        </div>
                        <div class="p-2">
                            {{ Form::file('images[]', ['multiple', 'class' => 'form-control-file']) }}
                        </div>
                    </div>
                </div>
            @endcan
            @if ($errors->has('images.*'))
                <div class="alert alert-danger mt-2">
                    @foreach($errors->get('images.*') as $fileErrors)
                        @foreach($fileErrors as $error)
                            {{ $error }}<br>
                        @endforeach
                    @endforeach
                </div>
            @endif
        </div>
    </div>
