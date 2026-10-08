<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $b = $this->booking;

        $mail = (new MailMessage)
            ->subject("New booking request {$b->reference}: {$b->title()} on {$b->date->format('D j M')}")
            ->greeting("New request, {$b->reference}")
            ->line("**{$b->name}** wants **{$b->title()}** on **{$b->date->format('l j F Y')}**".($b->slotLabel() ? " ({$b->slotLabel()})" : '.'))
            ->line("{$b->adults} adults, {$b->children} children. Estimate TT$".number_format($b->estimate).'.')
            ->line('Phone: '.$b->phone.' (prefers '.$b->contact_via.')'.($b->email ? ' · Email: '.$b->email : ''));

        if ($b->pickup) {
            $mail->line('Pickup: '.$b->pickup);
        }

        if ($b->notes) {
            $mail->line('"'.$b->notes.'"');
        }

        return $mail->action('Open in bookings', route('admin.bookings.show', $b))->salutation('Sent by the All Things Tobago website.');
    }
}
