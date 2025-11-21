<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view projects') || $user->hasAnyRole(['manager', 'admin']);
    }

    public function view(User $user, Project $project): bool
    {
        if ($user->can('view projects')) return true;
        if ($user->can('view client-projects') && $project->client_id === $user->id) return true;
        if ($user->hasRole('employee')) {
            $employee = Employee::where('user_id', $user->id)->first();
            if ($employee && $project->employees()->where('employees.id', $employee->id)->exists()) return true;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('create projects') || $user->hasAnyRole(['manager', 'admin']);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->can('edit projects') || $user->hasAnyRole(['manager', 'admin']);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can('delete projects') || $user->hasAnyRole(['manager', 'admin']);
    }
}


