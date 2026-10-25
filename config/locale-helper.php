<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | The default locale code used when no locale is explicitly provided.
    |
    */
    'default_locale' => env('APP_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Default Timezone
    |--------------------------------------------------------------------------
    */
    'default_timezone' => env('APP_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Default Currency
    |--------------------------------------------------------------------------
    */
    'default_currency' => env('DEFAULT_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Default Date Format
    |--------------------------------------------------------------------------
    |
    | Must be a valid PHP date() format string.
    |
    */
    'default_date_format' => 'Y-m-d',

    /*
    |--------------------------------------------------------------------------
    | First Day of Week
    |--------------------------------------------------------------------------
    |
    | 0 = Sunday, 1 = Monday, ..., 6 = Saturday
    |
    */
    'first_day_of_week' => 1,

    /*
    |--------------------------------------------------------------------------
    | Country → first day of week overrides (ISO 3166-1 alpha-2)
    |--------------------------------------------------------------------------
    */
    'first_day_by_country' => [
        'US' => 0, 'CA' => 0, 'JP' => 0, 'BR' => 0, 'IL' => 0, // Sunday
        'GB' => 1, 'DE' => 1, 'FR' => 1, 'IT' => 1, 'ES' => 1,
        'NL' => 1, 'RU' => 1, 'CN' => 1, 'IN' => 0,
        'AE' => 6, 'IR' => 6, 'AF' => 6, // Saturday
    ],

    /*
    |--------------------------------------------------------------------------
    | Number Formatting Defaults
    |--------------------------------------------------------------------------
    */
    'number_format' => [
        'decimals' => 2,
        'decimal_separator' => '.',
        'thousands_separator' => ',',
    ],

    /*
    |--------------------------------------------------------------------------
    | Filters — Include / Exclude
    |--------------------------------------------------------------------------
    |
    | Use these arrays to restrict which items are exposed through the
    | public getters.
    |
    |   • If "include" is non-empty  → only those keys are returned.
    |   • Else if "exclude" non-empty → everything except those keys.
    |   • Else                        → all items are returned.
    |
    | Keys must match the array keys used by each data class
    | (ISO 639-1 for languages, ISO 4217 for currencies,
    |  PHP timezone identifier for timezones).
    |
    */

    'languages' => [
        'include' => [
            // 'en', 'fr', 'de', 'ar',
        ],
        'exclude' => [
            // 'la', 'sa', // e.g. exclude dead/rare languages
        ],
    ],

    'currencies' => [
        'include' => [
            // 'USD', 'EUR', 'GBP',
        ],
        'exclude' => [
            // 'VEF', 'IRR', // e.g. exclude sanctioned or obsolete ones
        ],
    ],

    'timezones' => [
        'include' => [
            // 'America/New_York', 'Europe/London', 'Asia/Dubai',
        ],
        'exclude' => [
            // 'Antarctica/...', // e.g. exclude polar stations
        ],
    ],

    'countries' => [
        'include' => [],
        'exclude' => [],
    ],

    'phones' => [
        'include' => [], // country codes whose phone data to expose
        'exclude' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Phone Defaults
    |--------------------------------------------------------------------------
    */
    'phone' => [
        'default_country' => env('DEFAULT_COUNTRY', 'US'),
        'strip_leading_plus' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Enabled Modules
    |--------------------------------------------------------------------------
    |
    | Toggle individual data modules on or off to save memory.
    |
    */
    'modules' => [
        'languages' => true,
        'timezones' => true,
        'currencies' => true,
        'date_formats' => true,
        'first_day_of_week' => true,
    ],

];
