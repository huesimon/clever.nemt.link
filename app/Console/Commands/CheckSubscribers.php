<?php

namespace App\Console\Commands;

use App\Models\LocationUser;
use Illuminate\Console\Command;

class CheckSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:subscribers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will loop over all subscribers and check if last_available has changed in relation with a locations chargers count';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking subscribers...');
        $this->handleSubscribers();

        return Command::SUCCESS;
    }

    private function handleSubscribers(): void
    {
        $subscribers = LocationUser::all();

        foreach ($subscribers as $subscriber) {
            $this->handleSubscriber($subscriber);
        }
    }

    private function handleSubscriber($subscriber)
    {
        $this->info('Checking subscriber...');
        $this->info('Subscriber: ' . $subscriber->user->name);
        $this->info('Location: ' . $subscriber->location->name);
        $this->info('Last available: ' . $subscriber->last_available);
        $this->info('Chargers count: ' . $subscriber->location->chargers->count());


        if ($subscriber->last_available != $subscriber->location->chargers->count()) {
            $this->info('Last available has changed!');
            $subscriber->update(['last_available' => $subscriber->location->chargers->count()]);
        } else {
            $this->info('Last available has not changed!');
        }
    }
}
