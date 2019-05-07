<?php

namespace Freez0n\SypexGeo;

use Illuminate\Support\Facades\Facade;

/**
 * Class SypexGeo
 *
 * @package Freez0n\SypexGeo
 *
 * @method static array get(string $ip = '')
 * @method static string getIP()
 */
class SypexGeo extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(){
        return 'sypexgeo';
    }

}