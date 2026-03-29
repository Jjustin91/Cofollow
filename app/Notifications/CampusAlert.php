<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CampusAlert extends Notification
{
    use Queueable;

    public $announcement;

    // Pass the announcement into the alert when we create it
    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    // Tell Laravel to store this in the database
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Define the data that gets saved and sent to Vue
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->announcement->title,
            'organization' => $this->announcement->organization->abbreviation,
            'message' => 'New announcement posted by ' . $this->announcement->user->name,
            'announcement_id' => $this->announcement->id
        ];
    }
}