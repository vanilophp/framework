<div class="form-group">
    {{ Form::text('slug', null,
            [
                'class' => 'form-control form-control-sm' . ($errors->has('slug') ? ' is-invalid': ''),
                'placeholder' => __('URL')
            ]
    ) }}

    @if ($errors->has('slug'))
        <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
    @endif
</div>

<div class="form-group">
    {{ Form::textarea('excerpt', null,
            [
                'class' => 'form-control form-control-sm' . ($errors->has('excerpt') ? ' is-invalid' : ''),
                'placeholder' => __('Short Description'),
                'rows' => 4
            ]
    ) }}

    @if ($errors->has('excerpt'))
        <div class="invalid-feedback">{{ $errors->first('excerpt') }}</div>
    @endif
</div>
