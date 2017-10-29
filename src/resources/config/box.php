<?php

return [
    'modules' => [
        Vanilo\Product\Providers\ModuleServiceProvider::class => []
    ],
    'views' => [
        'namespace' => 'vanilo'
    ],
    'routes' => [
        'prefix'     => 'vanilo',
        'as'         => 'vanilo.',
        'middleware' => ['web', 'auth', 'acl'],
        'files'      => ['admin']
    ],
    'breadcrumbs' => true
];
