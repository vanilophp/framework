@foreach($taxons as $taxon)
    <div class="card-block">

        @if ($taxon->children->isNotEmpty())
            <a href="#taxon-{{$taxon->id}}" aria-expanded="false"
               aria-controls="taxon-{{$taxon->id}}" data-toggle="collapse"
               class="collapse-toggler-heading">
                &nbsp;<i class="zmdi zmdi-chevron-right"></i>
            </a>
        @else
            &nbsp;<i class="zmdi zmdi-chevron-right text-secondary"></i>
        @endif

        @can('edit taxons')
            <a href="{{ route('vanilo.taxon.edit', [$taxonomy, $taxon]) }}">{{ $taxon->name }}</a>
        @else
            {{ $taxon->name }}
        @endcan
            &nbsp;<span class="badge badge-pill badge-light text-muted">{{ $taxon->products()->count() }}</span>

        <div class="card-actionbar card-actionbar-show-on-hover">
            @can('create taxons')
                <a href="{{ route('vanilo.taxon.create', $taxonomy) }}?parent={{$taxon->id}}"
                   class="btn btn-outline-success btn-xs float-right">{{ __('Add Child :category', ['category' => \Illuminate\Support\Str::singular($taxonomy->name)]) }}</a>
            @endcan
            @can('delete taxons')
                {{ Form::open([
                            'url' => route('vanilo.taxon.destroy', [$taxonomy, $taxon]),
                            'class' => 'form float-right',
                            'style' => 'display: inline-flex',
                            'data-confirmation-text' => __('Delete :name?', ['name' => $taxon->name]),
                            'method' => 'DELETE'
                        ])
                }}
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
