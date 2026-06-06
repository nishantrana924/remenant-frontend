<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupWebhookLogs extends Command
{
    protected $signature = 'webhooks:cleanup';
    protected $description = 'Delete webhook logs older than 90 days';

    public function handle()
    {
        $deleted = DB::table('webhook_logs')
            ->where('created_at', '<', now()->subDays(90))
            ->delete();
            
        $this->info("Deleted {$deleted} old webhook logs.");
    }
}
