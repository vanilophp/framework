    <div class="card card-accent-secondary">
        <div class="card-header">{{ __('Properties') }}
            <span class="badge badge-pill badge-info">{{ $product->propertyValues->count() }}</span>
        </div>
        <div class="card-block">
            @if($errors->has('properties'))
                <div class="alert alert-danger">{{ $errors->first('properties') }}</div>
            @endif
            <table class="table table-condensed table-striped" id="product-properties-table">
                <tbody>
                @foreach($product->propertyValues as $propertyValue)
                    <tr>
                        <th>{{ $propertyValue->property->name }}</th>
                        <td>{{ $propertyValue->title }}</td>
                        <td>
                            <i class="zmdi zmdi-close"></i>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <select id="product-property-add-select">
                @foreach($properties->keyBy('id')->except($product->propertyValues->map(function ($propertyValue) {
                    return $propertyValue->property->id;
                })->all()) as $missingProperty)
                    <option>{{ $missingProperty->name }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-secondary btn-sm"id="product-property-add-btn">{{ __('Add property') }}</button>
        </div>
    </div>

@section('scripts')
@parent()
<script>
    var properties = [
    @foreach($properties as $property)
        {
            "id": "{{ $property->id }}",
            "name": "{{ $property->name }}",
            "values": [
                @foreach($property->values() as $value)
                {
                    "id": "{{ $value->id }}",
                    "title": "{{ $value->title }}",
                    "value": "{{ $value->value }}"
                },
                @endforeach
            ]
        },
    @endforeach
    ];
    $(document).ready(function() {
        $('#product-property-add-btn').on('click', function () {
            $('#product-properties-table tbody').append(
                '<tr>' +
                '<th>' + $('#product-property-add-select').find(":selected").text() + '</th>' +
                '<td></td>' +
                '<td><i class="zmdi zmdi-close"></i></td>' +
                '</tr>'
            )
        });
    });
</script>
@endsection
