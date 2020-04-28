<?php


namespace App\Repositories;
use App\Models\Statistic;
use Illuminate\Support\Collection;

/**
 * Class StatisticRepository
 * @package App\Repositories
 */
class StatisticRepository
{
    /**
     * Get all Statistic
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        $statistics = Statistic::get();

        return $statistics->map(function(&$s) {
            $s->data        = json_decode($s->data);
            $s->generalData = json_decode($s->generalData);
            $s->apiData     = json_decode($s->apiData);
            return $s;
        });
    }

    /**
     * Get last Statistic
     *
     * @return Statistic
     */
    public function getLast(): Statistic
    {
        $statistic = Statistic::get()->last();
        $statistic->data        = json_decode($statistic->data);
        $statistic->generalData = json_decode($statistic->generalData);
        $statistic->apiData     = json_decode($statistic->apiData);
        return $statistic;
    }

    /**
     * Get last Statistic
     *
     */
    public function byCountry()
    {
        $requestData = request()->all();
        $statistic = Statistic::get()->last();
        $requestedCountry = trim(strtolower($requestData['country']));
        foreach (json_decode($statistic->data) as $data){
            if (strtolower($data->country) === 'us' &&
                $requestedCountry === 'us' || $requestedCountry === 'u.s.a' ||
                $requestedCountry === 'usa' || $requestedCountry === 'america') {
                return $data;
            }
            if(strtolower($data->country) == $requestedCountry) {
                return $data;
            }
        }
    }
}
