<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\Request;

class ApproveKyc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kyc:approve {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Approve a KYC request directly from the server terminal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        $this->info("Attempting to approve KYC for ID: {$id}...");

        try {
            // Instantiate your controller container style to preserve dependency injection if needed
            $controller = app(DashboardController::class);

            // Build a blank request object in case your controller method expects it
            $request = new Request();

            // Call the exact method your route uses
            $controller->approveKyc($request, $id);

            $this->info("Successfully approved KYC for ID: {$id}");
        } catch (\Exception $e) {
            $this->error("An error occurred while executing the approval: " . $e->getMessage());
        }
    }
}
