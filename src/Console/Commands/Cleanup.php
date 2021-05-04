<?php

namespace Soved\Laravel\Gdpr\Console\Commands;

use Illuminate\Console\Command;
use Soved\Laravel\Gdpr\Jobs\Cleanup\CleanupJob;

class Cleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gdpr:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup inactive users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = config('gdpr');

        $userModel = config('auth.providers.users.model');
        $users = $userModel::all();

        $strategy = app($config['cleanup']['strategy']);

        CleanupJob::dispatch($users, $strategy);

        $this->info('CleanupJob dispatched');
    }
}
