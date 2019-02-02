<div id="create-property-value" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="create-property-value-title" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open([
                    'route' => ['vanilo.property_value.store', $property],
                    'method' => 'PUT'
                ])
            !!}

            <div class="modal-header">
                <h5 class="modal-title" id="create-property-value-title">{{ __('Add Value') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                @include('vanilo::property-value._form', ['properties' => $properties->pluck('name', 'id')])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('Close') }}</button>
                <button class="btn btn-primary">{{ __('Save properties') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
