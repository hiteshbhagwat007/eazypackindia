<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class PlainTextUserProvider implements UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        return User::find($identifier);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     */
    public function retrieveByToken($identifier, $token): ?Authenticatable
    {
        $user = User::find($identifier);
        
        if (!$user) {
            return null;
        }

        return $user->getRememberToken() && hash_equals($user->getRememberToken(), $token) ? $user : null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     */
    public function updateRememberToken(Authenticatable $user, $token): void
    {
        $user->setRememberToken($token);
        $user->save();
    }

    /**
     * Retrieve a user by the given credentials.
     */
    public function retrieveByCredentials(array $credentials): ?Authenticatable
    {
        if (!isset($credentials['email'])) {
            return null;
        }

        return User::where('email', $credentials['email'])->first();
    }

    /**
     * Validate a user against the given credentials.
     * This checks plain text password instead of hashed.
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        if (!isset($credentials['password'])) {
            return false;
        }

        // Compare plain text passwords
        return $user->getAuthPassword() === $credentials['password'];
    }

    /**
     * Rehash the user's password if required and supported.
     * Since we're using plain text passwords, we don't need to rehash.
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // No rehashing needed for plain text passwords
    }
}

