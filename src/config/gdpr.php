<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Prefix URI
    |--------------------------------------------------------------------------
    |
    | This URI is used to prefix all GDPR routes. You may change this value as
    | required, but don't forget the update your forms accordingly.
    |
    */

    'uri' => 'gdpr',

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware are run during every request to the GDPR routes. Please
    | keep in mind to only allow authenticated users to access the routes.
    |
    */

    'middleware' => [
        'web',
        'auth',
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | This setting specifies the Time To Live in months, before the specified model
    | is anonymized automatically.
    |
    */

    'settings' => [
        'ttl' => 12,
    ],

    /*
    |--------------------------------------------------------------------------
    | String
    |--------------------------------------------------------------------------
    |
    | This is the default string the fields will change to
    |
    |d
    */

    'string' => [
        'default' => 'Anonymized',
    ],

];
