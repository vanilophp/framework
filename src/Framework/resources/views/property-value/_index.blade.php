@foreach($propertyValues as $propertyValue)
    <div class="btn-group btn-group-sm mb-1" role="group">
        @can('edit property values')
            <a href="{{ route('vanilo.property_value.edit', [$property, $propertyValue]) }}"
               class="btn btn-secondary">{{ $propertyValue->title }}</a>
        @else
            <button class="btn btn-secondary" type="button">{{ $propertyValue->title }}</button>
        @endcan
        <button type="button" class="btn btn-secondary" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">{!! icon('more-items') !!}
        </button>
        <div class="dropdown-menu">
            @can('delete property values')
                {{ Form::open([
                            'url' => route('vanilo.property_value.destroy', [$property, $propertyValue]),
                            'style' => 'display: inline',
                            'data-confirmation-text' => __('Delete :title?', ['title' => $propertyValue->title]),
                            'method' => 'DELETE'
                        ])
                }}
                <button class="dropdown-item" type="submit">
                    {!! icon('delete', 'danger') !!}
                    {{ __('Delete ":title"', ['title' => $propertyValue->title]) }}
                </button>
                {{ Form::close() }}

            @endcan
        </div>
    </div>
@endforeach
@can('create property values')
    <a href="{{ route('vanilo.property_value.create', $property) }}"
       class="btn btn-success btn-sm mb-1"
       title="{{ __('Add :property value', ['property' => $property->name]) }}">{!! icon('+') !!}</a>
@endcan
