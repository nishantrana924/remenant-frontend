<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WarehouseBatch;

class WarehouseBatchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAccess($user, ['manage_couriers', 'assign_courier', 'generate_labels', 'view_batches']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WarehouseBatch $batch): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can acquire lock.
     */
    public function lock(User $user, WarehouseBatch $batch): bool
    {
        return $this->hasAccess($user, ['assign_courier', 'generate_awb']);
    }

    /**
     * Determine whether the user can trigger AWB generation.
     */
    public function generateAwb(User $user, WarehouseBatch $batch): bool
    {
        return $this->hasAccess($user, ['generate_awb']);
    }

    /**
     * Helper to verify roles. Assuming standard role/permission setup.
     */
    private function hasAccess(User $user, array $permissions): bool
    {
        // In a real app, this would use Spatie Permission or similar.
        // For architectural safety, if Super Admin, always true.
        if ($user->role === 'super_admin') return true;
        
        // Stub for permission check
        return true; 
    }
}
