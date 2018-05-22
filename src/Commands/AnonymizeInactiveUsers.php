<?php

namespace Dialect\Gdpr\Commands;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AnonymizeInactiveUsers extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'gdpr:anonymizeInactiveUsers';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anonymize inactive users';

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
        $anonymizableUsers = User::where('last_activity', '!=', null)->where('last_activity', '<=', carbon::now()->subMonths(config('gdpr.settings.ttl')))->get();

        foreach ($anonymizableUsers as $user) {
            $user->anonymize();
        }
    }
}
