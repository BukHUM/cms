<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Backend\PerformanceController;
use Illuminate\Http\Request;

class TestPerformanceFinal2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:performance-final2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Final test for Performance Controller 2';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Testing Performance Controller Final 2...');
            
            // Test controller instantiation
            $controller = new PerformanceController();
            $this->info('✓ Controller instantiated successfully');
            
            // Test index method
            $request = new Request();
            $response = $controller->index($request);
            $this->info('✓ Index method executed successfully');
            
            // Test response type
            $this->info('✓ Response type: ' . get_class($response));
            
            $this->info('All tests passed!');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile());
            $this->error('Line: ' . $e->getLine());
            $this->error('Trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}