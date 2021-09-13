<div class="form-group">
    <div class="input-group">
        <span class="input-group-prepend">
            <span class="input-group-text">
                {!! icon('payment-method') !!}
            </span>
        </span>
        {{ Form::text('name', null, [
                'class' => 'form-control form-control-lg' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => __('Name of payment method')
            ])
        }}
    </div>
    @if ($errors->has('name'))
        <input hidden class="form-control is-invalid" />
        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
    @endif
</div>

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Gateway') }}</label>
    <div class="col-md-10">
        {{ Form::select('gateway', $gateways, null, [
                'class' => 'form-control form-control-sm' . ($errors->has('gateway') ? ' is-invalid': ''),
                'placeholder' => __('--')
           ])
        }}
        @if ($errors->has('gateway'))
            <div class="invalid-feedback">{{ $errors->first('gateway') }}</div>
        @endif
    </div>
</div>

<hr>

<div class="form-group row{{ $errors->has('is_enabled') ? ' has-danger' : '' }}">
    <div class="col-md-10 offset-md-2">
        {{ Form::hidden('is_enabled', 0) }}

        <div class="custom-control custom-switch">
            {{ Form::checkbox('is_enabled', 1, null, ['class' => 'custom-control-input', 'id' => 'is-payment-method-enabled']) }}
            <label class="custom-control-label" for="is-payment-method-enabled">{{ __('Enabled') }}</label>
        </div>

        @if ($errors->has('is_enabled'))
            <div class="form-control-feedback">{{ $errors->first('is_enabled') }}</div>
        @endif
    </div>
</div>

<hr>

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Configuration') }}</label>
    <div class="col-md-10">
        {{ Form::textarea('configuration', null, [
            'class' => 'form-control form-control-sm' . ($errors->has('configuration') ? ' is-invalid' : ''),
            'placeholder' => __('Enter JSON config'),
            'rows' => 6
            ])
        }}
        @if ($errors->has('configuration'))
            <div class="invalid-feedback">{{ $errors->first('configuration') }}</div>
        @endif
    </div>
</div>

