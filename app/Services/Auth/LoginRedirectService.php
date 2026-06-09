<?php

namespace App\Services\Auth;

use App\Enums\RoleName;
use App\Models\User;

class LoginRedirectService
{
    /** @return list<string> */
    public function staffRoles(): array
    {
        return [
            RoleName::SuperAdmin->value,
            RoleName::Admin->value,
            RoleName::Sales->value,
            RoleName::TransactionAdmin->value,
            RoleName::Freelancer->value,
        ];
    }

    public function homeRoute(User $user): string
    {
        if ($user->hasAnyRole($this->staffRoles())) {
            return route('admin.dashboard');
        }

        return route('customer.dashboard');
    }

    public function canAccessPath(User $user, ?string $url): bool
    {
        if (! $url) {
            return false;
        }

        $path = '/'.ltrim(parse_url($url, PHP_URL_PATH) ?? '', '/');

        if (str_starts_with($path, '/admin')) {
            return $user->hasAnyRole($this->staffRoles());
        }

        if (str_starts_with($path, '/dashboard')) {
            return $user->hasRole(RoleName::Customer->value);
        }

        return true;
    }

    public function resolveAfterLogin(User $user): string
    {
        if ($checkoutReturn = session()->pull('checkout_return')) {
            return $checkoutReturn;
        }

        $intended = session()->pull('url.intended');

        if ($intended && $this->canAccessPath($user, $intended)) {
            return $intended;
        }

        return $this->homeRoute($user);
    }
}
