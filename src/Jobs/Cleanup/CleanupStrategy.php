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
     * @return void
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Execute cleanup strategy.
     *
     * @return void
     */
    abstract public function execute(Collection $users);
}
