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
    | Re-authentication
    |--------------------------------------------------------------------------
    |
    | Only authenticated users should be able to download their data.
    | Re-authentication is recommended to prevent information leakage.
    |
    */

    're-authenticate' => true,

    /*
    |--------------------------------------------------------------------------
    | Cleanup Strategy
    |--------------------------------------------------------------------------
    |
    | This strategy will be used to clean up inactive users. Do not forget to
    | mention these thresholds in your terms and conditions.
    |
    */

    'cleanup' => [

        'strategy' => 'Soved\Laravel\Gdpr\Jobs\Cleanup\Strategies\DefaultStrategy',

        'defaultStrategy' => [

            /*
             * The number of months for which inactive users must be kept.
             */
            'keepInactiveUsersForMonths' => 6,

            /*
             * The number of days before deletion at which inactive users will be notified.
             */
            'notifyUsersDaysBeforeDeletion' => 14,

        ],

    ],

];
