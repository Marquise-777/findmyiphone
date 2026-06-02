<?php

namespace App\Filament\Pages;


use App\Filament\Pages\Sales;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;

class CDashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';   // overrides the default
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    public function mount(): void
    {
        if (Auth::user()?->id !== 1) {
            $this->redirect(Sales::getUrl());
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()?->id === 1;
    }
}
