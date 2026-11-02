<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function build()
    {
        $labels = [
            'bug'     => 'Laporan Bug',
            'saran'   => 'Saran',
            'lainnya' => 'Lainnya',
        ];

        $typeLabel = $labels[$this->payload['type']] ?? 'Laporan';

        return $this
            ->subject('[Jelajah Wisata] ' . $typeLabel . ' — ' . $this->payload['subject'])
            ->view('emails.feedback')
            ->with([
                'payload'   => $this->payload,
                'typeLabel' => $typeLabel,
            ]);
    }
}