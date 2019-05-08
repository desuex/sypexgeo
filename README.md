# GeoIP for Laravel 5.5 with auto discover

----------

The data comes from a database and from service http://sypexgeo.net


## Installation

- [SypexGeo for Laravel 5.5 on Packagist](https://packagist.org/packages/freezon/sypexgeo)
- [SypexGeo for Laravel 5.5 on GitHub](https://github.com/freezon/sypexgeo)

To get the latest version of SypexGeo simply require it in your `composer.json` file.

~~~
"freezon/sypexgeo": "^0.7.1"
~~~
OR
~~~
$ composer require freezon/sypexgeo
~~~

### Publish the configurations

Run this on the command line from the root of your project:

~~~
$ php artisan vendor:publish --provider="Freez0n\SypexGeo\SypexGeoServiceProvider"
~~~

A configuration file will be publish to `config/sypexgeo.php`


## Usage


Getting the location data for a given IP:

```php
$location = \SypexGeo::get('232.223.11.11');
```

OR 

```php
use Freez0n\SypexGeo\SypexGeo;

...

$location = SypexGeo::get('232.223.11.11');

// empty get for get info by user ip
$location = SypexGeo::get();

// for get city, region or country
$city = SypexGeo::getCity([$ip = '']);
$city = SypexGeo::getRegion([$ip = '']);
$city = SypexGeo::getCountry([$ip = '']);

//for get names only
$city = SypexGeo::getLocationNames([$ip = ''], [$lang = 'ru' or 'en']);
```

### Example Data

If data is received from the database - config/sypexgeo.php
('type'  => 'database')
```php
        [
            'city' => [
                'id' => 524901,
                'lat' => 55.75222,
                'lon' => 37.61556,
                'name_ru' => 'Москва',
                'name_en' => 'Moscow',
                'okato' => '45',
            ],
            'region' => [
                'id' => 524894,
                'lat' => 55.76,
                'lon' => 37.61,
                'name_ru' => 'Москва',
                'name_en' => 'Moskva',
                'iso' => 'RU-MOW',
                'timezone' => 'Europe/Moscow',
                'okato' => '45',
            ],
            'country' => [
                'id' => 185,
                'iso' => 'RU',
                'continent' => 'EU',
                'lat' => 60,
                'lon' => 100,
                'name_ru' => 'Россия',
                'name_en' => 'Russia',
                'timezone' => 'Europe/Moscow',
            ],
        ];
```
If data is received from the webservice - config/sypexgeo.php
    (   'type'  => 'web_service',
        'view'  => 'json'
    )
```php
        [
              "ip" => "77.37.136.11"
              "city" => array [
                     "id" => 524901
                     "lat" => 55.75222
                     "lon" => 37.61556
                     "name_ru" => "Москва"
                     "name_en" => "Moscow"
                     "okato" => "45"
                     "vk" => 1
                     "population" => 10381222
                  ]
              "region" => array [
                    "id" => 524894
                    "lat" => 55.76
                    "lon" => 37.61
                    "name_ru" => "Москва"
                    "name_en" => "Moskva"
                    "iso" => "RU-MOW"
                    "timezone" => "Europe/Moscow"
                    "okato" => "45"
                    "auto" => "77, 97, 99, 177, 197, 199, 777"
                    "vk" => 0
                    "utc" => 3
              ]
              "country" => array [
                    "id" => 185
                    "iso" => "RU"
                    "continent" => "EU"
                    "lat" => 60
                    "lon" => 100
                    "name_ru" => "Россия"
                    "name_en" => "Russia"
                    "timezone" => "Europe/Moscow"
                    "area" => 17100000
                    "population" => 140702000
                    "capital_id" => 524901
                    "capital_ru" => "Москва"
                    "capital_en" => "Moscow"
                    "cur_code" => "RUB"
                    "phone" => "7"
                    "neighbours" => "GE,CN,BY,UA,KZ,LV,PL,EE,LT,FI,MN,NO,AZ,KP"
                    "vk" => 1
                    "utc" => 3
              ]
              "error" => ""
              "request" => -2
              "created" => "2015.04.08"
              "timestamp" => 1428516249
        ];
```