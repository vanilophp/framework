<div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-layers"></i>
        </span>
        {{ Form::text('name', null, ['class' => 'form-control form-control-lg', 'placeholder' => __('Product name')]) }}
    </div>
    @if ($errors->has('name'))
        <div class="form-control-feedback">{{ $errors->first('name') }}</div>
    @endif
</div>


<hr>


<div class="form-group{{ $errors->has('sku') ? ' has-danger' : '' }}">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-code-setting"></i>
        </span>
        {{ Form::text('sku', null, ['class' => 'form-control', 'placeholder' => __('SKU (product code)')]) }}
    </div>
    @if ($errors->has('sku'))
        <div class="form-control-feedback">{{ $errors->first('sku') }}</div>
    @endif
</div>


<hr>


<div class="form-group row{{ $errors->has('state') ? ' has-danger' : '' }}">
    <label class="form-control-label col-md-2">{{ __('State') }}</label>
    <div class="col-md-10">
        @foreach($states as $key => $value)
            <label class="radio-inline" for="state_{{ $key }}">
                {{ Form::radio('state', $key, $product->state == $value, ['id' => "state_$key"]) }}
                {{ $value }}
                &nbsp;
            </label>
        @endforeach

        @if ($errors->has('state'))
            <div class="form-control-feedback">{{ $errors->first('state') }}</div>
        @endif
    </div>
</div>

