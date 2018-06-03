<?php

namespace Soved\Laravel\Gdpr\Jobs\Cleanup;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Collection;

abstract class CleanupStrategy
{
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new cleanup strategy instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Execute cleanup strategy.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $users
     * @return void
     */
    abstract public function execute(Collection $users);
}
