<table class="table table-sm table-striped">
    <tbody>
        @foreach($propertyValues as $propertyValue)
            <tr>
                <td>
                    <span class="font-lg mb-3 font-weight-bold">
                        @can('edit propertyvalues')
                            <a href="{{ route('vanilo.property-value.edit', $propertyValue) }}">{{ $propertyValue->title }}</a>
                        @else
                            {{ $propertyValue->title }}
                        @endcan
                    </span>
                    <div class="text-muted">
                        {{ $propertyValue->value }}
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
