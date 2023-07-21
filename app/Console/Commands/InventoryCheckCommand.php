<?php

namespace App\Console\Commands;

use App\Services\InventoryTrackerService;
use Illuminate\Console\Command;

class InventoryCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check inventory for low stock items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new InventoryTrackerService())->doChecks();
        $this->info('Inventory check completed');
    }
}
