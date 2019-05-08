<?php namespace Freez0n\SypexGeo\Commands;

use Illuminate\Console\Command;

class UpdateBaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sypexgeo:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Local `Sypex Geo City` Base';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $type = config('sypexgeo.type');
        if(is_null($type)){
            $this->error('Run `php artisan vendor:publish --provider="Freez0n\SypexGeo\SypexGeoServiceProvider"` before run this command!');
            return;
        }
        $settings = config('sypexgeo.types.'.$type);
        if(!is_array($settings)){
            $this->error("Bad settings: type `$type` dont exists!");
            return;
        }
        $driver = array_get($settings, 'driver');
        if($driver !== 'file'){
            $this->info('Sypexgeo type not `database`, see file `config/sypexgeo.php`');
            return;
        }
        $url = array_get($settings, 'download');
        if(is_null($url)){
            $this->error("Bad settings: set `sypexgeo.types.$type.download` before run command!");
            return;
        }
        $isQuiet = array_key_exists('quiet', $this->options());
        $full_path = array_get($settings, 'path');
        $dat_file_dir = dirname($full_path);

        $last_updated_file = $dat_file_dir . '/SxGeo.upd';

        chdir($dat_file_dir);
        $types = array(
            'Country' =>  'SxGeo.dat',
            'City' =>  'SxGeoCity.dat',
            'Max' =>  'SxGeoMax.dat',
        );

        preg_match("/(Country|City|Max)/", pathinfo($url, PATHINFO_BASENAME), $m);
        $type = $m[1];
        $dat_file = $types[$type];

        $fp = fopen($dat_file_dir .'/SxGeoTmp.zip', 'wb');
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_FILE => $fp,
            CURLOPT_HTTPHEADER => file_exists($last_updated_file) ? array("If-Modified-Since: " .file_get_contents($last_updated_file)) : array(),
        ));
        if(!curl_exec($ch)){
            $this->error("Download error");
            return;
        };
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);
        if ($code == 304) {
            @unlink($dat_file_dir . '/SxGeoTmp.zip');
            if($isQuiet){
                $this->info("File already updated");
            }
            return;
        }

        // Распаковываем архив
        $fp = fopen('zip://' . $dat_file_dir . '/SxGeoTmp.zip#' . $dat_file, 'rb');
        $fw = fopen($dat_file, 'wb');
        if (!$fp) {
            $this->error("File error");
            return;
        }
        stream_copy_to_stream($fp, $fw);
        fclose($fp);
        fclose($fw);

        if(filesize($dat_file) == 0){
            $this->error("Unpack error");
            return;
        }

        @unlink($dat_file_dir . '/SxGeoTmp.zip');
        rename($dat_file_dir . '/' . $dat_file, $full_path);

        file_put_contents($last_updated_file, gmdate('D, d M Y H:i:s') . ' GMT');
    }
}
