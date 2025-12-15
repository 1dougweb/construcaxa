<?php

namespace App\Services;

use App\Models\Notification;
use App\Events\NotificationCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Cria uma notificação de empréstimo de equipamento aprovado
     */
    public static function createEquipmentLoanNotification($equipmentRequest, $userId = null)
    {
        $userId = $userId ?? $equipmentRequest->employee->user_id;
        
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'equipment_loan',
            'title' => 'Empréstimo de Equipamento Aprovado',
            'message' => "Seu empréstimo de equipamento #{$equipmentRequest->number} foi aprovado.",
            'data' => [
                'equipment_request_id' => $equipmentRequest->id,
                'equipment_request_number' => $equipmentRequest->number,
                'url' => route('equipment-requests.show', $equipmentRequest),
            ],
        ]);

        broadcast(new NotificationCreated($notification));

        return $notification;
    }

    /**
     * Cria uma notificação de requisição de material criada
     */
    public static function createMaterialRequestNotification($materialRequest, $userId = null)
    {
        $userId = $userId ?? $materialRequest->user_id;
        
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'material_request',
            'title' => 'Nova Requisição de Material',
            'message' => "Nova requisição de material #{$materialRequest->number} foi criada.",
            'data' => [
                'material_request_id' => $materialRequest->id,
                'material_request_number' => $materialRequest->number,
                'url' => route('material-requests.show', $materialRequest),
            ],
        ]);

        broadcast(new NotificationCreated($notification));

        return $notification;
    }

    /**
     * Cria uma notificação de requisição de material completada
     */
    public static function createMaterialRequestCompletedNotification($materialRequest, $userId = null)
    {
        $userId = $userId ?? $materialRequest->user_id;
        
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'material_request',
            'title' => 'Requisição de Material Completada',
            'message' => "A requisição de material #{$materialRequest->number} foi completada e processada.",
            'data' => [
                'material_request_id' => $materialRequest->id,
                'material_request_number' => $materialRequest->number,
                'url' => route('material-requests.show', $materialRequest),
            ],
        ]);

        broadcast(new NotificationCreated($notification));

        return $notification;
    }

    /**
     * Cria uma notificação de aprovação de orçamento
     */
    public static function createBudgetApprovalNotification($budget, $userId = null)
    {
        $userId = $userId ?? $budget->client?->user_id;
        
        if (!$userId) {
            return null;
        }
        
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'budget_approval',
            'title' => 'Orçamento Aprovado',
            'message' => "Seu orçamento #{$budget->id} foi aprovado.",
            'data' => [
                'budget_id' => $budget->id,
                'project_id' => $budget->project_id,
                'url' => $budget->project_id 
                    ? route('projects.show', $budget->project_id)
                    : route('budgets.show', $budget),
            ],
        ]);

        broadcast(new NotificationCreated($notification));

        return $notification;
    }

    /**
     * Cria uma notificação de aprovação de proposta de funcionário
     */
    public static function createProposalApprovalNotification($proposal, $userId = null)
    {
        $userId = $userId ?? $proposal->employee->user_id;
        
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'proposal_approval',
            'title' => 'Proposta Aceita',
            'message' => "Sua proposta de trabalho foi aceita e você foi vinculado à obra.",
            'data' => [
                'proposal_id' => $proposal->id,
                'project_id' => $proposal->project_id,
                'url' => $proposal->project_id 
                    ? route('projects.show', $proposal->project_id)
                    : route('employees.proposals.show', ['employee' => $proposal->employee_id, 'proposal' => $proposal->id]),
            ],
        ]);

        broadcast(new NotificationCreated($notification));

        return $notification;
    }

    /**
     * Cria uma notificação quando um funcionário bate ponto
     */
    public static function createAttendanceNotification($attendance, $notifyAdmins = true)
    {
        $user = $attendance->user;
        $typeLabel = $attendance->type === 'entry' ? 'entrada' : 'saída';
        $time = $attendance->punched_at->format('H:i');
        
        // Notificar o próprio funcionário
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => 'attendance',
            'title' => "Ponto de {$typeLabel} registrado",
            'message' => "Seu ponto de {$typeLabel} foi registrado às {$time}.",
            'data' => [
                'attendance_id' => $attendance->id,
                'type' => $attendance->type,
                'punched_at' => $attendance->punched_at->toIso8601String(),
                'url' => route('attendance.index'),
            ],
        ]);

        broadcast(new NotificationCreated($notification));

        // Notificar admins e gerentes se solicitado
        if ($notifyAdmins) {
            try {
                // Tentar usar Spatie Permission se disponível
                $admins = \App\Models\User::role(['admin', 'manager'])->get();
            } catch (\Exception $e) {
                // Fallback para query direta se Spatie não estiver disponível
                $admins = \App\Models\User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['admin', 'manager']);
                })->get();
            }

            foreach ($admins as $admin) {
                // Não notificar o próprio admin se ele estiver batendo ponto
                if ($admin->id === $user->id) {
                    continue;
                }

                $adminNotification = Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'attendance',
                    'title' => "Funcionário bateu ponto",
                    'message' => "{$user->name} registrou ponto de {$typeLabel} às {$time}.",
                    'data' => [
                        'attendance_id' => $attendance->id,
                        'employee_id' => $user->id,
                        'employee_name' => $user->name,
                        'type' => $attendance->type,
                        'punched_at' => $attendance->punched_at->toIso8601String(),
                        'url' => route('attendance.manage'),
                    ],
                ]);

                try {
                    broadcast(new NotificationCreated($adminNotification));
                } catch (\Exception $e) {
                    \Log::error('Erro ao fazer broadcast de notificação de ponto para admin: ' . $e->getMessage());
                }
            }
        }

        return $notification;
    }

    /**
     * Cria uma notificação quando orçamento é enviado para o cliente
     */
    public static function createBudgetSentNotification($budget)
    {
        $clientUserId = $budget->client?->user_id;
        
        if (!$clientUserId) {
            return null;
        }
        
        $notification = Notification::create([
            'user_id' => $clientUserId,
            'type' => 'budget_sent',
            'title' => 'Novo Orçamento Disponível',
            'message' => "Um novo orçamento #{$budget->id} v{$budget->version} foi enviado para você. Valor total: R$ " . number_format($budget->total, 2, ',', '.'),
            'data' => [
                'budget_id' => $budget->id,
                'url' => route('client.budgets.show', $budget),
            ],
        ]);

        try {
            broadcast(new NotificationCreated($notification));
        } catch (\Exception $e) {
            \Log::error('Erro ao fazer broadcast de notificação de orçamento enviado: ' . $e->getMessage());
        }

        return $notification;
    }

    /**
     * Cria notificação quando cliente aprova orçamento (notifica admins/gerentes)
     */
    public static function createBudgetApprovedByClientNotification($budget)
    {
        try {
            $admins = \App\Models\User::role(['admin', 'manager'])->get();
        } catch (\Exception $e) {
            $admins = \App\Models\User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'manager']);
            })->get();
        }

        $notifications = [];
        foreach ($admins as $admin) {
            $notification = Notification::create([
                'user_id' => $admin->id,
                'type' => 'budget_approved_by_client',
                'title' => 'Orçamento Aprovado pelo Cliente',
                'message' => "O cliente {$budget->client?->name} aprovou o orçamento #{$budget->id} v{$budget->version}. Valor: R$ " . number_format($budget->total, 2, ',', '.'),
                'data' => [
                    'budget_id' => $budget->id,
                    'client_id' => $budget->client_id,
                    'url' => route('budgets.index', ['budget_id' => $budget->id]),
                ],
            ]);

            try {
                broadcast(new NotificationCreated($notification));
            } catch (\Exception $e) {
                \Log::error('Erro ao fazer broadcast de notificação de aprovação pelo cliente: ' . $e->getMessage());
            }

            $notifications[] = $notification;
        }

        return $notifications;
    }

    /**
     * Cria notificação quando cliente rejeita orçamento (notifica admins/gerentes)
     */
    public static function createBudgetRejectedByClientNotification($budget)
    {
        try {
            $admins = \App\Models\User::role(['admin', 'manager'])->get();
        } catch (\Exception $e) {
            $admins = \App\Models\User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'manager']);
            })->get();
        }

        $notifications = [];
        foreach ($admins as $admin) {
            $message = "O cliente {$budget->client?->name} rejeitou o orçamento #{$budget->id} v{$budget->version}.";
            if ($budget->rejection_reason) {
                $message .= " Motivo: " . Str::limit($budget->rejection_reason, 100);
            }

            $notification = Notification::create([
                'user_id' => $admin->id,
                'type' => 'budget_rejected_by_client',
                'title' => 'Orçamento Rejeitado pelo Cliente',
                'message' => $message,
                'data' => [
                    'budget_id' => $budget->id,
                    'client_id' => $budget->client_id,
                    'rejection_reason' => $budget->rejection_reason,
                    'url' => route('budgets.index', ['budget_id' => $budget->id]),
                ],
            ]);

            try {
                broadcast(new NotificationCreated($notification));
            } catch (\Exception $e) {
                \Log::error('Erro ao fazer broadcast de notificação de rejeição pelo cliente: ' . $e->getMessage());
            }

            $notifications[] = $notification;
        }

        return $notifications;
    }

    /**
     * Cria uma notificação genérica
     */
    public static function create($userId, $type, $title, $message, $data = [])
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        // Fazer broadcast da notificação
        try {
            broadcast(new NotificationCreated($notification));
        } catch (\Exception $e) {
            \Log::error('Erro ao fazer broadcast de notificação: ' . $e->getMessage());
            // Continuar mesmo se o broadcast falhar
        }

        return $notification;
    }
}

