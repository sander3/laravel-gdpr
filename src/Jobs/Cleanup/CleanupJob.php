<?php

namespace Soved\Laravel\Gdpr\Jobs\Cleanup;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\Collection;

class CleanupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $users;

    /**
     * @var \Soved\Laravel\Gdpr\Jobs\Cleanup\CleanupStrategy
     */
    public $strategy;

    /**
     * Create a new job instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $users
     * @param  \Soved\Laravel\Gdpr\Jobs\Cleanup\CleanupStrategy  $strategy
     * @return void
     */
    public function __construct(
        Collection $users,
        CleanupStrategy $strategy
    ) {
        $this->users = $users;
        $this->strategy = $strategy;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->strategy->execute($this->users);
    }
}
