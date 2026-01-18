<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'timezone' => 'America/Chicago',

    'log' => env('APP_LOG', 'single'),

    'log_level' => env('APP_LOG_LEVEL', 'debug'),


    'aliases' => Facade::defaultAliases()->merge([
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'Image' => Intervention\Image\Facades\Image::class,
        'Mailchimp' => NZTim\Mailchimp\MailchimpFacade::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
    ])->toArray(),

];
