<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceIssuedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Invoice $invoice)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('請求書が発行されました: ' . $this->invoice->invoice_number)
            ->greeting($notifiable->name . ' 様')
            ->line('対象月: ' . $this->invoice->billing_month?->format('Y-m'))
            ->line('請求金額: ' . number_format((float) $this->invoice->amount, 0) . ' 円')
            ->line('請求書番号: ' . $this->invoice->invoice_number)
            ->action('請求書を確認', route('invoices.show', $this->invoice))
            ->line('このメールはシステムから自動送信されています。');
    }
}
