<?php

namespace App\Policies;

use App\Models\User;

class ShippingRulePolicy
{
    public function viewAny(User ): bool { return ->hasAccess($user); }
    public function view(User ): bool { return ->hasAccess($user); }
    public function create(User ): bool { return ->hasAccess($user); }
    public function update(User ): bool { return ->hasAccess($user); }
    public function delete(User ): bool { return ->hasAccess($user); }

    private function hasAccess(User ): bool
    {
        if ($user->role === 'super_admin') return true;
        // RBAC logic...
        return true;
    }
}
