<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'wati' => [
        'base_url' => env('WATI_BASE_URL'),
        'bearer_token' => env('WATI_BEARER_TOKEN'),
        'complete_survey_template_name' => env('WATI_COMPLETE_SURVEY_TEMPLATE_NAME'),
        'cancel_survey_template_name' => env('WATI_CANCEL_SURVEY_TEMPLATE_NAME'),
        'complete_survey_base_url' => env('WATI_COMPLETE_SURVEY_BASE_URL'),
        'cancel_survey_base_url' => env('WATI_CANCEL_SURVEY_BASE_URL'),
    ],

];
