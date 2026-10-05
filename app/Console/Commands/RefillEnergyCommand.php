<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LifeService;
use Illuminate\Console\Command;

class RefillEnergyCommand extends Command
{
    protected $signature = 'energy:refill';

    protected $description = 'Refill energy for all users up to max';

    public function handle()
    {
        $refilled = User::where('energy', '<', LifeService::MAX_ENERGY)
            ->update(['energy' => LifeService::MAX_ENERGY]);

        $this->info("Refilled energy for {$refilled} users.");

        return Command::SUCCESS;
    }
}
