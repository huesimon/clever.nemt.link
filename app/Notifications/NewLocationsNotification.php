<?php

namespace App\Notifications;

use App\Models\Charger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NewLocationsNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Collection $locations)
    {

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        Log::info("Sending mail to {$notifiable->email}");

        $message = (new MailMessage)
            ->subject('New Locations')
            ->line("There have been created {$this->locations->count()} new locations in your area.");
        $this->locations->each(function ($location) use ($message) {
            $address = $location->address;
            $message->line("{$location->name} at {$address->address} {$address->postalCode} {$address->city}")
            ->line("Total number of chargers: {$location->chargers->count()}")
            ->lineIf($location->chargers()->plugType(Charger::TYPE_2)->count(), "Type2: " . $location->chargers()->plugType(Charger::TYPE_2)->count())
            ->lineIf($location->chargers()->plugType(Charger::CCS)->count() ,"CCS: " . $location->chargers()->plugType(Charger::CCS)->count())
            ->lineIf($location->chargers()->plugType(Charger::CHADEMO)->count() ,"CHAdeMO: " . $location->chargers()->plugType(Charger::CHADEMO)->count());
        });

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
