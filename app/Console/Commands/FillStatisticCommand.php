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
        include_once('vendor/simplehtmldom/simplehtmldom/simple_html_dom.php');
        // Read JSON file
        $json = file_get_contents('./resources/json/data.json');

        $load = file_get_contents( 'https://www.worldometers.info/coronavirus/#countries' );
        $html= str_get_html( $load );
        $populationData = json_decode($json);

        $generalData = [
            'cases'      => (int)str_replace(",", "", $html->find('#maincounter-wrap span', 0)->innertext()),
            'deaths'     => (int)str_replace(",", "", $html->find('#maincounter-wrap span', 1)->innertext()),
            'recovered'  => (int)str_replace(",", "", $html->find('#maincounter-wrap span', 2)->innertext()),
            'population' => $populationData->generalPopulations,
        ];

        $data = [];

        $rows = $html
            ->find('.main_table_countries tbody tr');

        foreach ($rows as $index=>$row) {
            $rowHTML = $html->load($row->innertext);
            $name                           = trim(strip_tags($rowHTML->find('td', 0)->innertext));
            $data[$index]['country']        = $name;
            $data[$index]['totalCases']     = (int)str_replace(",", "", $rowHTML->find('td', 1)->innertext);
            $data[$index]['newCases']       = (int)str_replace(",", "", $rowHTML->find('td', 2)->innertext);
            $data[$index]['totalDeaths']    = (int)str_replace(",", "", $rowHTML->find('td', 3)->innertext);
            $data[$index]['newDeaths']      = (int)str_replace(",", "", $rowHTML->find('td', 4)->innertext);
            $data[$index]['totalRecovered'] = (int)str_replace(",", "", $rowHTML->find('td', 5)->innertext);
            $data[$index]['activeCases']    = (int)str_replace(",", "", $rowHTML->find('td', 6)->innertext);
            $data[$index]['serious']        = (int)str_replace(",", "", $rowHTML->find('td', 7)->innertext);
            $data[$index]['totCases']       = (int)str_replace(",", "", $rowHTML->find('td', 8)->innertext);
            $data[$index]['population']     = $populationData->data->$name;
        }

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
