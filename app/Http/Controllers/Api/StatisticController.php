<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiController;

class StatisticController extends ApiController
{

    public function get()
    {
        return $this->buildResponse('200', []);
    }
}
