<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-format-indent-increase"></i>
        </span>
        {{ Form::text('title', null,
                [
                    'class' => 'form-control form-control-lg' . ($errors->has('title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ]
        ) }}
    </div>
    @if ($errors->has('title'))
        <input hidden class="form-control is-invalid" />
        <div class="invalid-feedback">{{ $errors->first('title') }}</div>
    @endif
</div>

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Value') }}</label>
    <div class="col-md-10">
        {{ Form::text('value', null, [
                'class' => 'form-control form-control-sm' . ($errors->has('value') ? ' is-invalid': ''),
                'placeholder' => __('Leave empty to auto generate from title')
           ])
        }}
        @if ($errors->has('value'))
            <div class="invalid-feedback">{{ $errors->first('value') }}</div>
        @endif
    </div>
</div>

<hr>

@unless($hideProperties ?? false)
<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Property') }}</label>
    <div class="col-md-10">
        {{ Form::select('property_id', $properties, null, [
                'class' => 'form-control form-control-sm' . ($errors->has('property_id') ? ' is-invalid': ''),
                'placeholder' => __('Choose Property')
           ])
        }}
        @if ($errors->has('property_id'))
            <div class="invalid-feedback">{{ $errors->first('property_id') }}</div>
        @endif
    </div>
</div>
@endunless

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Priority') }}</label>
    <div class="col-md-10">
        {{ Form::text('priority', null, [
                'class' => 'form-control form-control-sm' . ($errors->has('priority') ? ' is-invalid': '')
           ])
        }}
        @if ($errors->has('priority'))
            <div class="invalid-feedback">{{ $errors->first('priority') }}</div>
        @endif
    </div>
</div>

<hr>
