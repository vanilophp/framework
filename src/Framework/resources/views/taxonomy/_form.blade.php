<div class="form-group">
    <div class="input-group">
        <span class="input-group-prepend">
            <span class="input-group-text">
                {!! icon('taxonomy') !!}
            </span>
        </span>
        {{ Form::text('name', null, [
                'class' => 'form-control form-control-lg' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => __('Name of the Category Tree')
            ])
        }}
    </div>
    @if ($errors->has('name'))
        <input hidden class="form-control is-invalid" />
        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
    @endif
</div>

<hr>

<div class="form-group">
    {{ Form::text('slug', null, [
            'class' => 'form-control form-control-sm' . ($errors->has('slug') ? ' is-invalid': ''),
            'placeholder' => __('URL (leave empty to auto-generate from name)')
        ])
    }}
    @if ($errors->has('slug'))
        <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
    @endif
</div>
