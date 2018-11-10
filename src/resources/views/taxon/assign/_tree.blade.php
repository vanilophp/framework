@foreach($taxons as $taxon)
    <div>
    @if ($taxon->children->isNotEmpty())
        <a href="#taxon-{{$taxon->id}}" aria-expanded="false"
           aria-controls="taxon-{{$taxon->id}}" data-toggle="collapse"
           class="collapse-toggler-heading">
            &nbsp;<i class="zmdi zmdi-chevron-right"></i>
        </a>
    @else
        &nbsp;<i class="zmdi zmdi-chevron-right text-secondary"></i>
    @endif

    <input type="checkbox" id="taxon-checkbox-{{$taxon->id}}"
           name="taxons[{{$taxon->id}}]"
           @if($assignments->has($taxon->id))checked="checked" @endif
    />
    <label for="taxon-checkbox-{{$taxon->id}}"></label>{{ $taxon->name }}

    @if ($taxon->children->isNotEmpty())
        <div class="collapse multi-collapse" id="taxon-{{$taxon->id}}" data-toggle="collapse">
            <div style="padding-left: 1.35em">
            @include('vanilo::taxon.assign._tree', ['taxons' => $taxon->children])
            </div>
        </div>
    @endif
    </div>
@endforeach
