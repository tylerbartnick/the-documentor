<?php

namespace App\Helpers\Auth;

use App\Models\User;

class AuthenticationHelper
{
    public function authenticate(string $email, string $password) : bool
    {
        $user = User::where('email', '=', $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        $this->login($user);
        return true;
    }

    public function login(User $user) : void
    {
        $_SESSION['USER_ID'] = $user->id;
        $_SESSION['USER_USERNAME'] = $user->username;
    }

    public function logout() : void
    {
        unset($_SESSION['USER_ID']);
        unset($_SESSION['USER_USERNAME']);
    }

    public function isLoggedIn() : bool
    {
        return (
            isset($_SESSION['USER_ID']) && 
            isset($_SESSION['USER_USERNAME'])
        );
    }

    public function getCurrentUser()
    {
        if ($this->isLoggedIn()) {
            return User::find($_SESSION['USER_ID']);
        }

        return null;
    }
}