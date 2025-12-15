<?php

namespace App\Mail;

use App\Models\ProjectBudget;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BudgetRejectedByClientNotification extends Mailable
{
    use Queueable, SerializesModels;

    public ProjectBudget $budget;

    /**
     * Create a new message instance.
     */
    public function __construct(ProjectBudget $budget)
    {
        $this->budget = $budget->loadMissing(['client', 'items', 'rejector']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Orçamento Rejeitado pelo Cliente - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.budgets.rejected-by-client',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
