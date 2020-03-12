<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\ApiController;
use App\Repositories\StatisticRepository;
use Illuminate\Http\JsonResponse;

/**
 * Class StatisticController
 * @package App\Http\Controllers\Api
 */
class StatisticController extends ApiController
{

    /**
     * Get statistic
     *
     * @param StatisticRepository $statisticRepository
     * @return JsonResponse
     */
    public function getAll(StatisticRepository $statisticRepository): JsonResponse
    {
        $statistic = $statisticRepository->getAll();

        return $this->buildResponse(200, ['data' => $statistic]);
    }

    /**
     * Get statistic
     *
     * @param StatisticRepository $statisticRepository
     * @return JsonResponse
     */
    public function getLast(StatisticRepository $statisticRepository): JsonResponse
    {
        $statistic = $statisticRepository->getLast();

        return $this->buildResponse(200, ['data' => $statistic]);
    }
}
