<?php

namespace App\View\Composers;

use App\Models\Finance\Estimate;
use Illuminate\Cache\CacheManager;
use Illuminate\View\View;

class EstimateComposer
{
    protected Estimate $estimate;

    protected CacheManager $cache;

    public function __construct(Estimate $estimate, CacheManager $cache)
    {
        $this->estimate = $estimate;

        $this->cache = $cache;
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('estimates_not_send', $this->estimate->estimatesNotsend());

        /*$view->with('categoriesMenu', $this->cache->remember('categoriesMenu', $this->timeToLive(), function () {
             return $this->categories->categoryInMenu();
         })); */
    }

    private function timeToLive()
    {
        return \Carbon\Carbon::now()->addDays(30);
    }
}
