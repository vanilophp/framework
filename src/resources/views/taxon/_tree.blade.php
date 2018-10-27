@foreach($taxons as $taxon)
    <div class="card-block">
        @can('edit taxons')
            <a href="{{ route('vanilo.taxon.edit', [$taxonomy, $taxon]) }}">{{ $taxon->name }}</a>
        @else
            {{ $taxon->name }}
        @endcan

        @if ($taxon->children->isNotEmpty())
                <a href="#taxon-{{$taxon->id}}" aria-expanded="false"
                   aria-controls="taxon-{{$taxon->id}}" data-toggle="collapse"
                   class="collapse-toggler-heading">
                    &nbsp;<i class="zmdi zmdi-chevron-right"></i>
                </a>
        @endif
        <div class="card-actionbar">
            @can('delete taxons')
                {{ Form::open(['url' => route('vanilo.taxon.destroy', [$taxonomy, $taxon]), 'class' => 'form', 'method' => 'DELETE']) }}
                <button type="submit"
                        class="btn btn-outline-danger btn-xs">{{ __('Delete') }}</button>
                {{ Form::close() }}
            @endcan
        </div>
    </div>

    @if ($taxon->children->isNotEmpty())
        <div class="collapse multi-collapse" id="taxon-{{$taxon->id}}" data-toggle="collapse">
            <div class="card-block">
                <div class="card">
                    @include('vanilo::taxon._tree', ['taxons' => $taxon->children])
                </div>
            </div>
        </div>
    @endif

@endforeach

@can('create taxons')
    <div class="card-footer">
        <?php $queryParam = $taxon->isRootLevel() ? '' : '?parent=' . $taxon->parent->id; ?>
        <a href="{{ route('vanilo.taxon.create', $taxonomy) }}{{ $queryParam }}"
           class="btn btn-outline-success btn-sm">{{ __('Add :category', ['category' => str_singular($taxonomy->name)]) }}</a>
    </div>
@endcan
