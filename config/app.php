<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'timezone' => 'America/Chicago',

    'log' => env('APP_LOG', 'single'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),

    'providers' => ServiceProvider::defaultProviders()->merge([
        NZTim\Mailchimp\MailchimpServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'Image' => Intervention\Image\Facades\Image::class,
        'Mailchimp' => NZTim\Mailchimp\MailchimpFacade::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
    ])->toArray(),

];
