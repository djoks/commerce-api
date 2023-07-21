<?php

namespace App\Console\Commands;

use App\Events\TestEvent;
use Illuminate\Console\Command;

class TriggerTestEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trigger:test-event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Triggers a test event';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Triggering test event...');
        event(new TestEvent());
        $this->info('Test event triggered.');
    }
}
