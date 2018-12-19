<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-format-indent-increase"></i>
        </span>
        {{ Form::text('name', null,
                [
                    'class' => 'form-control form-control-lg' . ($errors->has('name') ? ' is-invalid' : ''),
                    'placeholder' => __('Name')
                ]
        ) }}
    </div>
    @if ($errors->has('name'))
        <input hidden class="form-control is-invalid" />
        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
    @endif
</div>

<div class="form-group row">
    <label class="col-form-label col-md-2">{{ __('URL') }}</label>
    <div class="col-md-10">
        {{ Form::text('slug', null, [
                'class' => 'form-control' . ($errors->has('slug') ? ' is-invalid': ''),
                'placeholder' => __('Leave empty to auto generate from name')
           ])
        }}
        @if ($errors->has('slug'))
            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
        @endif
    </div>
</div>

<hr>

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Parent') }}</label>
    <div class="col-md-10">
        {{ Form::select('parent_id', $taxons, null, [
                'class' => 'form-control form-control-sm' . ($errors->has('parent_id') ? ' is-invalid': ''),
                'placeholder' => __('No parent')
           ])
        }}
        @if ($errors->has('parent_id'))
            <div class="invalid-feedback">{{ $errors->first('parent_id') }}</div>
        @endif
    </div>
</div>

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

<div class="form-group">
    <?php $seoHasErrors = any_key_exists($errors->toArray(), ['ext_title', 'meta_description', 'meta_keywords']) ?>
    <h5><a data-toggle="collapse" href="#taxon-form-seo" class="collapse-toggler-heading"
           @if ($seoHasErrors)
           aria-expanded="true"
                @endif
        ><i class="zmdi zmdi-chevron-right"></i> {{ __('SEO') }}</a></h5>

    <div id="taxon-form-seo" class="collapse{{ $seoHasErrors ? ' show' : '' }}">
        <div class="callout">

            @include('vanilo::taxon._form_seo')

        </div>
    </div>
</div>
