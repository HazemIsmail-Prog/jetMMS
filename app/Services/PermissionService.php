<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    private const CACHE_TTL = 86400; // 24 hours in seconds
    private const CACHE_PREFIX = 'user.permissions.';

    /**
     * Get user permissions from cache or load them
     */
    public function getUserPermissions(User $user): Collection
    {
        return Cache::remember(
            $this->getCacheKey($user),
            self::CACHE_TTL,
            fn () => $this->loadUserPermissions($user)
        );
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(User $user, string $permission): bool
    {
        return $this->getUserPermissions($user)->contains($permission);
    }

    /**
     * Clear user permissions cache
     */
    public function clearUserPermissionsCache(User $user): void
    {
        Cache::forget($this->getCacheKey($user));
    }

    /**
     * Load user permissions from database
     */
    private function loadUserPermissions(User $user): Collection
    {
        // get all direct permissions + roles permissions
        $directPermissions = $user->directPermissions()
            ->get()
            ->pluck('name')
            ->unique()
            ->values();

        $rolesPermissions = $user->roles()
            ->with('permissions:id,name')
            ->get()
            ->flatMap(fn ($role) => $role->permissions->pluck('name'))
            ->unique()
            ->values();

        return $directPermissions->merge($rolesPermissions)->unique();

    }

    /**
     * Get cache key for user
     */
    private function getCacheKey(User $user): string
    {
        return self::CACHE_PREFIX . $user->id;
    }
} 