<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TokenService;

class RefreshToken extends Command
{
    protected $signature = 'live:refresh-token';
    protected $description = 'Force refresh token Live2 and store to database';

    public function handle(TokenService $svc): int
    {
        $token = $svc->refreshToken();
        $this->info('Token refreshed: ' . substr($token, 0, 30) . '...');
        return self::SUCCESS;
    }
}
