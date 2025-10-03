<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Schema;

class StudentPolicy
{
    use HandlesAuthorization;

    protected function isAdmin(User $user): bool
    {
        // Détection tolérante: is_admin bool, ou role/can columns
        try {
            $cols = Schema::getColumnListing('users');
            if (in_array('is_admin', $cols, true) && (bool) $user->is_admin) {
                return true;
            }
            if (in_array('role', $cols, true) && in_array((string) $user->role, ['admin','superadmin','super_admin'], true)) {
                return true;
            }
            if (in_array('is_super_admin', $cols, true) && (bool) $user->is_super_admin) {
                return true;
            }
        } catch (\Throwable $e) {
            // Ignore schema errors
        }
        return false;
    }

    public function update(User $user, Student $student): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }
        return $student->user_id === $user->id;
    }

    public function view(User $user, Student $student): bool
    {
        return $this->update($user, $student);
    }
}
