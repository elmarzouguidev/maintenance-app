<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Cache\CacheManager;

class AppRepository
{
    protected $cache;

    protected function setCache(): CacheManager
    {
        if (! $this->cache) {
            $this->cache = new CacheManager(app());
        }

        return $this->cache;
    }

    /**
     * @return mixed
     */
    private function callConfig(string $key)
    {
        return config('app-config')['cache'][$key];
    }

    public function useCache(): bool
    {
        return $this->callConfig('use-cache');
    }

    protected function timeToLive(): Carbon
    {
        return Carbon::now()->addDays($this->callConfig('cache-live-time'));
    }
}
