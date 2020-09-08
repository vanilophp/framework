<div class="card mb-3">
    <div class="card-body">
        <h6 class="card-title">{{ __('Categorization') }}</h6>

        <table class="table">
            @foreach($taxonomies as $taxonomy)
                <tr>
                    <td>{{ $taxonomy->name }}</td>
                    <td>
                        @foreach($product->taxons()->byTaxonomy($taxonomy)->get() as $taxon)
                            <span class="badge badge-pill badge-dark">{{ $taxon->name }}</span>
                        @endforeach
                    </td>
                    <td class="text-right">
                        <button type="button" data-toggle="modal"
                                data-target="#taxon-assign-to-model-{{$taxonomy->id}}"
                                class="btn btn-outline-success btn-sm">{{ __('Manage') }}</button>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

@foreach($taxonomies as $taxonomy)
    @include('vanilo::taxon.assign._form', [
        'for' => 'product',
        'forId' => $product->id,
        'assignments' => $product->taxons()->byTaxonomy($taxonomy)->get()->keyBy('id'),
        'taxonomy' => $taxonomy
        ])
@endforeach
