<div class="form-group">
    <div class="input-group">
        <span class="input-group-prepend">
            <span class="input-group-text">
                {!! icon('property') !!}
            </span>
        </span>
        {{ Form::text('name', null, [
                'class' => 'form-control form-control-lg' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => __('Name of the property')
            ])
        }}
    </div>
    @if ($errors->has('name'))
        <input hidden class="form-control is-invalid" />
        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
    @endif
</div>

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('URL') }}</label>
    <div class="col-md-10">
        {{ Form::text('slug', null, [
                'class' => 'form-control form-control-sm' . ($errors->has('slug') ? ' is-invalid': ''),
                'placeholder' => __('Leave empty to autogenerate')
            ])
        }}
        @if ($errors->has('slug'))
            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
        @endif
    </div>
</div>

<hr>

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Type') }}</label>
    <div class="col-md-10">
        {{ Form::select('type', $types, null, [
                'class' => 'form-control form-control-sm' . ($errors->has('type') ? ' is-invalid': ''),
                'placeholder' => __('--')
           ])
        }}
        @if ($errors->has('type'))
            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
        @endif
    </div>
</div>

