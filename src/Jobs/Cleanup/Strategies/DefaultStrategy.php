<?php

namespace Soved\Laravel\Gdpr\Jobs\Cleanup\Strategies;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\Authenticatable;
use Soved\Laravel\Gdpr\Events\GdprInactiveUser;
use Soved\Laravel\Gdpr\Jobs\Cleanup\CleanupStrategy;
use Soved\Laravel\Gdpr\Events\GdprInactiveUserDeleted;

class DefaultStrategy extends CleanupStrategy
{
    /**
     * Execute cleanup strategy.
     *
     * @return void
     */
    public function execute(Collection $users)
    {
        $config = $this->config->get('gdpr.cleanup.defaultStrategy');

        // Users are considered inactive if their last activity is older than this timestamp
        $inactivity = Carbon::now()
            ->subMonths($config['keepInactiveUsersForMonths']);

        $this->notifyInactiveUsers(
            $inactivity,
            $config['notifyUsersDaysBeforeDeletion'],
            $users
        );

        $this->deleteInactiveUsers($inactivity, $users);
    }

    /**
     * Notify inactive users about their deletion.
     *
     * @return void
     */
    private function notifyInactiveUsers(
        Carbon $inactivity,
        int $notificationThreshold,
        Collection $users
    ) {
        $users->filter(
            function (Authenticatable $user) use ($inactivity, $notificationThreshold) {
                return $user->last_activity->diffInDays($inactivity)
                    === $notificationThreshold;
            }
        )->each(function (Authenticatable $user) {
            event(new GdprInactiveUser($user));
        });
    }

    /**
     * Delete inactive users.
     *
     * @return void
     */
    private function deleteInactiveUsers(
        Carbon $inactivity,
        Collection $users
    ) {
        $users->filter(function (Authenticatable $user) use ($inactivity) {
            return $user->last_activity < $inactivity;
        })->each(function (Authenticatable $user) {
            $user->delete();

            event(new GdprInactiveUserDeleted($user));
        });
    }
}
