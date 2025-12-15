<?php

namespace App\Mail;

use App\Models\ProjectBudget;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BudgetApprovedByClientNotification extends Mailable
{
    use Queueable, SerializesModels;

    public ProjectBudget $budget;

    /**
     * Create a new message instance.
     */
    public function __construct(ProjectBudget $budget)
    {
        $this->budget = $budget->loadMissing(['client', 'items', 'project']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Orçamento Aprovado pelo Cliente - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.budgets.approved-by-client',
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
