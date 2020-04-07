<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use DB;

/**
 * Class FillStatisticCommand
 * @package App\Console\Commands
 */
class FillStatisticCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:fill_statistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill statistic';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ch = curl_init('https://services9.arcgis.com/N9p5hsImWXAccRNI/arcgis/rest/services/Nc2JKvYFoAEOFCG5JSI6/FeatureServer/2/query?f=json&where=Recovered%3C%3E0&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&orderByFields=Recovered%20desc&resultOffset=0&resultRecordCount=250&cacheHint=true');
        // получать заголовки
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        // если ведется проверка HTTP User-agent, то передаем один из возможных допустимых вариантов:
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3');
        // елси проверятся откуда пришел пользователь, то указываем допустимый заголовок HTTP Referer:
        curl_setopt ($ch, CURLOPT_REFERER, 'https://services9.arcgis.com/N9p5hsImWXAccRNI/arcgis/rest/services/Nc2JKvYFoAEOFCG5JSI6/FeatureServer/2/query?f=json&where=Recovered%3C%3E0&returnGeometry=false&spatialRel=esriSpatialRelIntersects&outFields=*&orderByFields=Recovered%20desc&resultOffset=0&resultRecordCount=250&cacheHint=true');
        // использовать метод POST
        curl_setopt ($ch, CURLOPT_POST, 0);
        // возвращать результат работы
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        // не проверять SSL сертификат
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // не проверять Host SSL сертификата
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec ($ch);
        // закрыть сессию работы с cURL
        curl_close ($ch);
        $result = json_decode($result);

        $json = file_get_contents('./resources/json/data.json');
        $populationData = json_decode($json);

        $generalData = [
            'cases'      => 0,
            'deaths'     => 0,
            'recovered'  => 0,
            'population' => $populationData->generalPopulations,
        ];
        $data = [];
        $sortData = [];
        foreach ($result->features as $index=>$row){
            $generalData['cases'] += $row->attributes->Confirmed;
            $generalData['deaths'] += $row->attributes->Deaths;
            $generalData['recovered'] += $row->attributes->Recovered;
            $name = $row->attributes->Country_Region;
            $data[$index]['country'] = $name;
            $data[$index]['totalCases'] = $row->attributes->Confirmed;
            $data[$index]['totalDeaths'] = $row->attributes->Deaths;
            $data[$index]['totalRecovered'] = $row->attributes->Recovered;
            if(isset($populationData->data->$name)){
                $data[$index]['population'] = $populationData->data->$name;
            }
            $sortData[] = $row->attributes->Confirmed;
        }
        array_multisort($sortData, SORT_DESC, $data);

        DB::table('statistics')->insert(
            [
                'generalData' => json_encode($generalData),
                'data'        => json_encode($data),
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ]
        );
    }
}
