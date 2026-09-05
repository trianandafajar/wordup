<?php

namespace App\Filament\Pages\Auth;

use Filament\Notifications\Notification;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected static string $view = 'filament.admin.pages.auth.login';

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getSubHeading(): string|Htmlable
    {
        return '';
    }

    public function hasLogo(): bool
    {
        return false;
    }

    protected function throwFailureValidationException(): never
    {
        Notification::make()
            ->title('Email atau password salah')
            ->danger()
            ->send();

        throw ValidationException::withMessages([
            'data.email' => '',
        ]);
    }
}
