<?php

namespace App\Console\Commands;

use App\Http\Controllers\Backend\MasterCurrencyController;
use Illuminate\Console\Command;

class UpdateKursCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kurs:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update currency exchange rates automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new MasterCurrencyController();
        // Memanggil updateKurs dengan actor 'cronjob'
        $controller->updateKurs('Cronjob');
        $this->info('Currency rates updated successfully by cronjob.');
        return 0;
    }
}
