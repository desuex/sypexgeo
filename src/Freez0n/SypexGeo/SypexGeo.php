<?php

namespace Freez0n\SypexGeo;

use Illuminate\Support\Facades\Facade;

/**
 * Class SypexGeo
 *
 * @package Freez0n\SypexGeo
 *
 * @method array get(string $ip = '')
 * @method string getIP()
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