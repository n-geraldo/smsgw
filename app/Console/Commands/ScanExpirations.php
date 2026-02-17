<?php

namespace App\Console\Commands;

use App\Services\ExpirationScanner;
use Illuminate\Console\Command;

class ScanExpirations extends Command
{
    protected $signature = 'sms:scan-expirations';

    protected $description = 'Scan DMA for upcoming expirations and create SMS jobs.';

    public function handle(ExpirationScanner $scanner): int
    {
        $result = $scanner->scan();

        $this->info("SMS scan complete. Created: {$result['created']}, Skipped: {$result['skipped']}");

        return self::SUCCESS;
    }
}
