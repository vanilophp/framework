@foreach($taxons as $taxon)
    <div class="card-body">

        @if ($taxon->children->isNotEmpty())
            <a href="#taxon-{{$taxon->id}}" aria-expanded="false"
               aria-controls="taxon-{{$taxon->id}}" data-toggle="collapse"
               class="collapse-toggler-heading">
                &nbsp;{!! icon('>') !!}
            </a>
        @else
            &nbsp;{!! icon('>', 'light') !!}
        @endif

        @can('edit taxons')
            <a href="{{ route('vanilo.taxon.edit', [$taxonomy, $taxon]) }}">{{ $taxon->name }}</a>
        @else
            {{ $taxon->name }}
        @endcan
            &nbsp;<span class="badge badge-pill badge-light text-muted">{{ $taxon->products()->count() }}</span>

        <div class="d-inline card-actionbar-show-on-hover">
            @can('create taxons')
                <a href="{{ route('vanilo.taxon.create', $taxonomy) }}?parent={{$taxon->id}}"
                   class="btn btn-outline-success btn-xs"
                   title="{{ __('Add Child :category', ['category' => \Illuminate\Support\Str::singular($taxonomy->name)]) }}">
                    {!! icon('+') !!}
                </a>
            @endcan
            @can('delete taxons')
                {{ Form::open([
                            'url' => route('vanilo.taxon.destroy', [$taxonomy, $taxon]),
                            'class' => 'form',
                            'style' => 'display: inline-flex',
                            'data-confirmation-text' => __('Delete :name?', ['name' => $taxon->name]),
                            'method' => 'DELETE'
                        ])
                }}
                <button type="submit"
                        class="btn btn-outline-danger btn-xs" title="{{ __('Delete') }}">{!! icon('delete') !!}</button>
                {{ Form::close() }}
            @endcan
        </div>
    </div>

    @if ($taxon->children->isNotEmpty())
        <div class="collapse multi-collapse" id="taxon-{{$taxon->id}}" data-toggle="collapse">
            <div class="card-body">
                <div class="card">
                    @include('vanilo::taxon._tree', ['taxons' => $taxon->children])
                </div>
            </div>
        </div>
    @endif

@endforeach
