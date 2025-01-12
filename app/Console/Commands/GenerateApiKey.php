<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:apikey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new static API key';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         // Generate a new API key
         $newApiKey = Str::random(32);

         $envPath = base_path('.env');
 
         // Check if the STATIC_API_KEY already exists in .env
         if (File::exists($envPath)) {
             $envContents = File::get($envPath);
 
             // Check if the key exists and replace it
             if (strpos($envContents, 'STATIC_API_KEY') !== false) {
                 $envContents = preg_replace(
                     '/^STATIC_API_KEY=.*/m',
                     'STATIC_API_KEY=' . $newApiKey,
                     $envContents
                 );
             } else {
                 // Add the key if it does not exist
                 $envContents .= "\nSTATIC_API_KEY={$newApiKey}\n";
             }
 
             // Save the updated .env file
             File::put($envPath, $envContents);
             $this->info("API Key generated and saved in .env file: {$newApiKey}");
         } else {
             $this->error('.env file does not exist.');
         }
    }
}
