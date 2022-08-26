<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncStores extends Command
{
    protected $signature = 'sync:stores';
    protected $description = 'Command to sync store products in Database';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        return $this->info = $this->description;
    }
}
