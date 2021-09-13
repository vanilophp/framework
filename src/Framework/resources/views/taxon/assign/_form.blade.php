<div id="taxon-assign-to-model-{{ $taxonomy->id }}" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="taxon-assign-to-model-title" aria-hidden="true">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open([
                    'url'  => route('vanilo.taxonomy.sync', [$taxonomy]),
                    'method' => 'PUT'
                ])
            !!}

            <div class="modal-header">
                <h5 class="modal-title" id="taxon-assign-to-model-title">{{ __('Assign :name', ['name' => $taxonomy->name]) }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {{ Form::hidden('for', $for) }}
                {{ Form::hidden('forId', $forId) }}

                @include('vanilo::taxon.assign._tree', [
                    'taxons' => $taxonomy->rootLevelTaxons()
                ])
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('Close') }}</button>
                <button class="btn btn-primary">{{ __('Update assignments') }}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
