<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\RunSchedule;
use App\Models\Schedules\Schedule;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class BotScheduler extends Command
{
    public const WAIT_SECONDS = 60;

    protected $signature = 'bot-scheduler:run';
    protected $description = 'Check schedules to run.';

    public function handle(): void
    {
        $runnedSchedules = 0;
        Schedule::query()->chunk(1000, static function (Collection $schedules) use (&$runnedSchedules) {
            foreach ($schedules as $schedule) {
                /** @var Schedule $schedule */
                if ($schedule->expression->isDue()) {
                    $runnedSchedules++;
                    dispatch(new RunSchedule($schedule));
                }
            }
        });

        $this->info('Runned schedules: ' . $runnedSchedules);
    }
}
