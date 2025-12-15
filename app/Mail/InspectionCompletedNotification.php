<?php

namespace App\Mail;

use App\Models\Inspection;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InspectionCompletedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Inspection $inspection;

    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
    }

    public function build(): self
    {
        return $this
            ->subject('Vistoria concluída - ' . config('app.name'))
            ->view('emails.inspections.completed');
    }
}


