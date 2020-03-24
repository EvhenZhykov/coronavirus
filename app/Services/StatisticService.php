<?php


namespace App\Services;
use App\Repositories\StatisticRepository;

class StatisticService
{
    /** @var StatisticRepository */
    protected $repository;

    /**
     *
     * @param StatisticRepository $repository
     */
    public function __construct( StatisticRepository $repository )
    {
        $this->repository = $repository;
    }


}
