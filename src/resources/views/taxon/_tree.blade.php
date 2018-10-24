@foreach($taxons as $taxon)
    <li class='list-group-item'>
        {{ Form::open(['url' => route('vanilo.taxon.destroy', [$taxonomy, $taxon]), 'class' => 'form', 'method' => 'DELETE']) }}
        <div class="form-group">
            {{ $taxon->name }}

            <span class="float-right">
                <a class="btn btn-outline-primary btn-xs" href="{{ route('vanilo.taxon.edit', [$taxonomy, $taxon]) }}">
                        {{ __('Edit')}}
                </a>
                <button type="submit" class="btn btn-outline-danger btn-xs">{{ __('Delete') }}</button>
            </span>
        </div>
        {{ Form::close() }}
        @if ($taxon->children->isNotEmpty())
            <ul class="list-group">
                @include('vanilo::taxon._tree', ['taxons' => $taxon->children])
            </ul>
        @endif
    </li>
@endforeach
