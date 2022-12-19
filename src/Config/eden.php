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
     * Default Button Styles
     */
    'button_style' => 'inline-flex items-center gap-2 px-4 py-1.5 bg-slate-800 border border-transparent rounded-md text-slate-200 hover:bg-slate-700 active:bg-slate-900 tracking-wide focus:outline-none focus:border-slate-900 focus:ring focus:ring-slate-300 disabled:opacity-25 transition dark:bg-slate-700 dark:hover:bg-slate-500',

    /**
     * DataTable Action Button Styles
     */
    'button_style_table' => 'inline-flex items-center gap-2 rounded-md px-1 text-slate-700 tracking-wide focus:outline-none focus:border-slate-900 focus:ring focus:ring-slate-300 disabled:opacity-25 transition dark:text-slate-300',

    /**
     * Eden MiddleWare Group
     */
    'middleware' => [
        'web',
        'auth',
        //config('jetstream.auth_session'),
        'verified',
        BestSnipp\Eden\Middleware\EdenRequestHandler::class,
        'can:accessEden'
    ],
];
