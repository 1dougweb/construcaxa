<?php

namespace App\Mail;

use App\Models\ProjectBudget;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BudgetClientRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    public ProjectBudget $budget;

    /**
     * Create a new message instance.
     */
    public function __construct(ProjectBudget $budget)
    {
        Log::info('[BUDGET EMAIL MAILABLE] Construindo mailable BudgetClientRequestNotification', [
            'budget_id' => $budget->id,
            'client_id' => $budget->client_id,
        ]);
        
        $this->budget = $budget->loadMissing(['client', 'items']);
        
        Log::info('[BUDGET EMAIL MAILABLE] Mailable construído com sucesso', [
            'budget_id' => $this->budget->id,
            'has_client' => $this->budget->client !== null,
            'has_items' => $this->budget->items !== null,
            'items_count' => $this->budget->items?->count() ?? 0,
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Novo Orçamento Disponível - ' . config('app.name');
        
        Log::info('[BUDGET EMAIL MAILABLE] Criando envelope do email', [
            'budget_id' => $this->budget->id,
            'subject' => $subject,
        ]);
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = 'emails.budgets.client-request';
        
        Log::info('[BUDGET EMAIL MAILABLE] Criando conteúdo do email', [
            'budget_id' => $this->budget->id,
            'view' => $view,
            'view_exists' => view()->exists($view),
        ]);
        
        return new Content(
            view: $view,
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


