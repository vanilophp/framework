<div class="card">
    <div class="card-block">
        <h6 class="card-title">{{ __('Properties') }}</h6>

        <table class="table">
            @foreach($product->propertyValues as $propertyValue)
                <tr>
                    <td>{{ $propertyValue->property->name }}</td>
                    <td>
                        <span class="badge badge-pill badge-dark">{{ $propertyValue->title }}</span>
                    </td>
                    <td class="text-right">
                        <button type="button" data-toggle="modal"
                                data-target="#taxon-assign-to-model-{{$propertyValue->id}}"
                                class="btn btn-outline-success btn-sm">{{ __('Manage') }}</button>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

{{--@foreach($taxonomies as $taxonomy)--}}
    {{--@include('vanilo::taxon.assign._form', [--}}
        {{--'for' => 'product',--}}
        {{--'forId' => $product->id,--}}
        {{--'assignments' => $product->taxons()->byTaxonomy($taxonomy)->get()->keyBy('id'),--}}
        {{--'taxonomy' => $taxonomy--}}
        {{--])--}}
{{--@endforeach--}}
