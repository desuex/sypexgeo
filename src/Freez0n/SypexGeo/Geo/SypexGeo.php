<?php namespace Freez0n\SypexGeo\Geo;

use Freez0n\SypexGeo\Contracts\SypexGeoContract;
use Illuminate\Config\Repository;

class SypexGeo {
    /**
     * @var SypexGeoContract
     */
    private $_sypex = null;

    /**
     * Illuminate config repository instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @var string $ip remote client IP-address
     */
    public $ip = '';
    /**
     * @var int $ipAsLong remote client IP-address as integer value
     */
    public $ipAsLong = 0;
    /**
     * @var array $city geo information about city
     */
    public $city = [];
    /**
     * @var array $region geo information about region
     */
    public $region = [];
    /**
     * @var array $country geo information about country
     */
    public $country = [];

    /**
     * @param SypexGeoContract $object
     * @param Repository
     */
    public function __construct($object, $config){
        $this->config = $config;
        $this->_sypex = $object;
    }

    /**
     * @param string $ip
     * @return array
     */
    public function get($ip = ''){
        if(empty($ip)){
            $this->getIP();
        }elseif(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            return $this->config->get('sypexgeo.default_location', []);
        }else{
            $this->installIP($ip);
        }

        $data = $this->_sypex->getCityFull($this->ip, $this->config);
        if(isset($data['city'])){
            $this->city = $data['city'];
        }
        if(isset($data['region'])){
            $this->region = $data['region'];
        }
        if(isset($data['country'])){
            $this->country = $data['country'];
        }

        return empty($data) ? $this->config->get('sypexgeo.default_location', []) : $data;
    }

    /**
     * Detect client IP address
     *
     * @return string IP
     */
    public function getIP(){
        $ip = '';
        foreach(['HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR'] as $search){
            if(getenv($search)){
                $ip = getenv($search);
                break;
            }
        }

        return $this->installIP($ip);
    }

    private function installIP($ip){
        $this->ip = $ip;
        $this->ipAsLong = sprintf('%u', ip2long($ip));

        return $ip;
    }

    public function getCity($ip = ''){
        return $this->get($ip)['city'];
    }

    public function getRegion($ip = ''){
        return $this->get($ip)['region'];
    }

    public function getCountry($ip = ''){
        return $this->get($ip)['country'];
    }

    public function getLocationNames($ip = '', $lang = 'ru'){
        $location = $this->get($ip);
        if(!in_array($lang, ['ru', 'en'], true)){
            $lang = 'en';
        }
        return [
            'city' => $location['city']['name_'.$lang],
            'city_id' => $location['city']['id'],
            'region' => $location['region']['name_'.$lang],
            'region_id' => $location['region']['id'],
            'country' => $location['country']['name_'.$lang],
            'country_id' => $location['country']['id'],
            'iso' => $location['region']['iso'],
            'timezone' => $location['region']['timezone'],
        ];
    }
}

