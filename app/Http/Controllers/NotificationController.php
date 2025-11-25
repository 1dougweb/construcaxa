<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Listar notificações do usuário autenticado
     */
    public function index(Request $request)
    {
        $query = auth()->user()->notifications()->orderBy('created_at', 'desc');

        // Filtro por status
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->read();
            }
        }

        $notifications = $query->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Contar notificações não lidas (API)
     */
    public function unread()
    {
        $count = auth()->user()->notifications()->unread()->count();
        
        return response()->json([
            'count' => $count,
        ]);
    }

    /**
     * Obter últimas notificações para dropdown (API)
     */
    public function recent()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at?->toIso8601String(),
                    'created_at' => $notification->created_at->toIso8601String(),
                    'time_ago' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => auth()->user()->notifications()->unread()->count(),
        ]);
    }

    /**
     * Listar arquivos de som disponíveis (API)
     */
    public function availableSounds()
    {
        $soundsPath = public_path('sounds');
        $sounds = ['default'];
        
        if (is_dir($soundsPath)) {
            $files = glob($soundsPath . '/*.mp3');
            foreach ($files as $file) {
                $sounds[] = basename($file);
            }
        }

        return response()->json([
            'sounds' => $sounds,
        ]);
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead(Notification $notification)
    {
        // Verificar se a notificação pertence ao usuário autenticado
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => auth()->user()->notifications()->unread()->count(),
        ]);
    }

    /**
     * Marcar todas as notificações como lidas
     */
    public function markAllAsRead()
    {
        auth()->user()->notifications()->unread()->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * Deletar notificação
     */
    public function destroy(Notification $notification)
    {
        // Verificar se a notificação pertence ao usuário autenticado
        if ($notification->user_id !== auth()->id()) {
            return back()->with('error', 'Não autorizado.');
        }

        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => auth()->user()->notifications()->unread()->count(),
            ]);
        }

        return back()->with('success', 'Notificação excluída com sucesso!');
    }

    /**
     * Enviar notificação de teste
     */
    public function sendTest()
    {
        try {
            $notification = \App\Services\NotificationService::create(
                auth()->id(),
                'test',
                '',
                '',
                [
                    'url' => route('notifications.index'),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Notificação de teste enviada.',
                'notification' => [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar notificação de teste: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar notificação: ' . $e->getMessage(),
            ], 500);
        }
    }
}
