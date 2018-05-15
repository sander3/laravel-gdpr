<?php
namespace Dialect\Gdpr\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
	/**
	 * Define the package's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		parent::schedule($schedule);

		$schedule->command('gdpr:anonymizeInactiveUsers')->daily();
	}
}