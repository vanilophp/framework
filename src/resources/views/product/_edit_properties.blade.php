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
                    <tr v-for="(assignedProperty, id) in assignedProperties" :id="id">
                        <th>@{{ assignedProperty.property.name }}</th>
                        <td>
                            <select name="propertyValues[]" :value="assignedProperty.id">
                                <option v-for="value in assignedProperty.values" :value="value.id">
                                    @{{ value.title }}
                                </option>
                            </select>
                        </td>
                        <td>
                            <i class="zmdi zmdi-close text-danger" style="cursor: pointer" @click="removePropertyValue(id)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>

            <select id="product-property-add-select" v-model="selected">
                <option v-for="(unassignedProperty, id) in unassignedProperties" :value="id">
                    @{{ unassignedProperty.property.name }}
                </option>
            </select>
                <button class="btn btn-secondary btn-sm"
                        type="button"
                        :disabled="selected == ''"
                        @click="addSelectedPropertyValue">{{ __('Add property') }}</button>
        </div>
    </div>

@section('scripts')
@parent()
<script>
    new Vue({
        el: '#app',
        data: {
            selected: '',
            assignedProperties: {
                @foreach($product->propertyValues as $propertyValue)
                "{{ $propertyValue->property->id }}": {
                    "value": "{{ $propertyValue->id }}",
                    "property": {
                        "id":  "{{ $propertyValue->property->id }}",
                        "name": "{{ $propertyValue->property->name }}"
                    },
                    "values": [
                        @foreach($propertyValue->property->values() as $value)
                        {
                            "id": "{{ $value->id }}",
                            "title": "{{ $value->title }}"
                        },
                        @endforeach
                    ]
                },
                @endforeach
            },
            unassignedProperties: {
                @foreach($properties->keyBy('id')->except($product->propertyValues->map(function ($propertyValue) {
                        return $propertyValue->property->id;
                })->all()) as $unassignedProperty)
                "{{ $unassignedProperty->id }}": {
                    "value": "",
                    "property": {
                        "id": "{{ $unassignedProperty->id }}",
                        "name": "{{ $unassignedProperty->name }}"
                    },
                    "values": [
                        @foreach($unassignedProperty->values() as $value)
                        {
                            "id": "{{ $value->id }}",
                            "title": "{{ $value->title }}"
                        },
                        @endforeach
                    ]
                },
                @endforeach
            }
        },
        methods: {
            addSelectedPropertyValue() {
                if (this.selected && '' !== this.selected) {
                    var property = this.unassignedProperties[this.selected];
                    if (property) {
                        this.assignedProperties[property.property.id] = property;
                        Vue.delete(this.unassignedProperties, property.property.id);
                    }
                }
            },
            removePropertyValue(propertyId) {
                var property = this.assignedProperties[propertyId];
                if (property) {
                    this.unassignedProperties[propertyId] = property;
                    Vue.delete(this.assignedProperties, propertyId)
                }
            }
        }
    });
</script>
@endsection
