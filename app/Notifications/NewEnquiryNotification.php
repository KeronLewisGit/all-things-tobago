<?php

namespace App\Notifications;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEnquiryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Enquiry $enquiry) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $e = $this->enquiry;

        return (new MailMessage)
            ->subject("New {$e->typeLabel()} enquiry {$e->reference} from {$e->name}")
            ->greeting("New enquiry, {$e->reference}")
            ->line("**{$e->name}** sent a {$e->typeLabel()} enquiry.")
            ->line(collect([$e->from ? 'From '.$e->from->format('j M Y') : null, $e->to ? 'to '.$e->to->format('j M Y') : null, $e->guests ? $e->guests.' guests' : null, $e->budget ? 'Budget '.$e->budget : null])->filter()->implode(' · '))
            ->line(($e->phone ? 'Phone: '.$e->phone.' ' : '').($e->email ? 'Email: '.$e->email : ''))
            ->line($e->message ? '"'.$e->message.'"' : '')
            ->action('Open in enquiries', route('admin.enquiries'))
            ->salutation('Sent by the All Things Tobago website.');
    }
}
