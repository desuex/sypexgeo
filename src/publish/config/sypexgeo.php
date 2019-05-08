<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Access type
    |--------------------------------------------------------------------------
    |
    | By default exists `database` and `web_service`, but you may add something
    | else.
    |
    */

    'type' => env('SYPEXGEO_TYPE','database'),

    /*
    |--------------------------------------------------------------------------
    | All access type settings
    |--------------------------------------------------------------------------
    |
    | Service specific settings.
    |
    */
    'types'       => [

        'database' => [
            'driver' => 'file',
            'path' => storage_path('sypexgeo/SxGeoCity.dat'),
            'download' => 'https://sypexgeo.net/files/SxGeoCityMax_utf8.zip'
        ],

        'web_service' => [
            'driver' => 'api',

            //license key sypexgeo.net
            'license_key' => env('SYPEXGEO_KEY', ''),

            //json or xml
            'view'        => 'json'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Location
    |--------------------------------------------------------------------------
    |
    | Return when a location is not found or ip not valid.
    |
    */

    'default_location' => [
        'city'    => [
            'id'      => 524901,
            'lat'     => 55.75222,
            'lon'     => 37.61556,
            'name_ru' => 'Москва',
            'name_en' => 'Moscow'
        ],
        'region'  => [
            'id'       => 524894,
            'name_ru'  => 'Москва',
            'name_en'  => 'Moskva',
            'iso'      => 'RU-MOW'
        ],
        'country' => [
            'id'        => 185,
            'iso'       => 'RU',
            'lat'       => 60,
            'lon'       => 100,
            'name_ru'   => 'Россия',
            'name_en'   => 'Russia',
        ],
    ],

];