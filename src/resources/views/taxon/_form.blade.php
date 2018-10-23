<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-folder"></i>
        </span>
        {{ Form::text('name', null,
                [
                    'class' => 'form-control form-control-lg' . ($errors->has('name') ? ' is-invalid' : ''),
                    'placeholder' => __('Name')
                ]
        ) }}
    </div>
    @if ($errors->has('name'))
        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
    @endif
</div>

<div class="form-group">
    {{ Form::select('parent_id', $taxons, null,
            [
                'class' => 'form-control form-control-sm' . ($errors->has('parent_id') ? ' is-invalid': ''),
                'placeholder' => __('Parent')
            ]
    ) }}

    @if ($errors->has('parent_id'))
        <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
    @endif
</div>

<hr>

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
    {{ Form::text('ext_title', null,
            [
                'class' => 'form-control form-control-sm' . ($errors->has('ext_title') ? ' is-invalid': ''),
                'placeholder' => __('Extra Title')
            ]
    ) }}

    @if ($errors->has('ext_title'))
        <div class="invalid-feedback">{{ $errors->first('ext_title') }}</div>
    @endif
</div>

<hr>

<div class="form-group">
    {{ Form::text('meta_keywords', null,
            [
                'class' => 'form-control form-control-sm' . ($errors->has('meta_keywords') ? ' is-invalid': ''),
                'placeholder' => __('Meta Keywords')
            ]
    ) }}

    @if ($errors->has('meta_keywords'))
        <div class="invalid-feedback">{{ $errors->first('meta_keywords') }}</div>
    @endif
</div>

<div class="form-group">
    {{ Form::text('meta_description', null,
            [
                'class' => 'form-control form-control-sm' . ($errors->has('meta_description') ? ' is-invalid': ''),
                'placeholder' => __('Meta Description')
            ]
    ) }}

    @if ($errors->has('meta_description'))
        <div class="invalid-feedback">{{ $errors->first('meta_description') }}</div>
    @endif
</div>
