<?php

return [

    /**
     * Define Entry Path from Where Eden Will Start It's Magic
     */
    'entry' => '/eden',

    /**
     * Define Entry Component
     */
    'entry_component' => 'dashboard',

    /**
     * Toast Message Positions
     * top-right | top-left | bottom-right | bottom-left | top-center | bottom-center | center-center
     */
    'toast' => 'top-right',

    /**
     * Toast Message Duration
     */
    'toast_duration' => 5000,

    /**
     * Action Buttons Count
     */
    'action_buttons_count' => 5,

    /**
     * Eden MiddleWare Group
     */
    'middleware' => [
        'web',
        'auth',
        //config('jetstream.auth_session'),
        'verified',
        Dgharami\Eden\Middleware\EdenRequestHandler::class,
        'can:accessEden'
    ],
];
