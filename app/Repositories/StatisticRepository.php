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
     * Get all departments
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        $statistics = Statistic::get();

        $mapped = $statistics->map(function(&$s) {
            $s->generalData = json_decode($s->generalData);
            $s->totalData   = json_decode($s->totalData);
            return $s;
        });

        return $mapped;
    }

    /**
     * Get last departments
     *
     * @return Statistic
     */
    public function getLast(): Statistic
    {
        $statistic = Statistic::get()->first();
        $statistic->generalData = json_decode($statistic->generalData);
        $statistic->totalData = json_decode($statistic->totalData);
        return $statistic;
    }
}
