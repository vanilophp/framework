<div class="card-actionbar mr-3">
    <form method="GET" action="">
            <div class="input-group input-group-sm">
                {{ Form::text('query', null, [
                        'class' => 'form-control',
                        'placeholder' => __('Search query')
                    ])
                }}
                {{
                    Form::button('Search', [
                        'class' => 'btn btn-sm btn-outline-secondary',
                        'type' => 'submit',
                    ])
                }}
            </div>
    </form>
</div>
