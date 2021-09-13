<div class="card bg-light">
    <div class="card-body">
        <h5 class="card-title">
            <a data-toggle="collapse" href="#product-filters" class="collapse-toggler-heading"
                @if ($hasActiveFilters ?? false)
                aria-expanded="true"
                @endif
            >{!! icon('>') !!} {{ __('Filters') }}</a>
        </h5>
        <form action="{{ route('vanilo.product.index') }}" id="product-filters" class="collapse{{ ($hasActiveFilters ?? false) ? ' show' : '' }}">

            <div class="row">
                <div class="form-group col-sm-6 col-md-4">
                    <label class="form-control-label">{{ __('Categories') }}</label>
                    {!! Form::select('taxons[]', [], null, ['class' => 'form-control form-control-sm', 'placeholder' => __('Any')]) !!}
                </div>
            </div>

            <hr>

            <button class="btn btn-primary" type="submit">{{ __('Filter') }}</button>
        </form>
    </div>
</div>
