<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    BladeUI\Heroicons\BladeHeroiconsServiceProvider::class,
    BladeUI\Icons\BladeIconsServiceProvider::class,
    Filament\Actions\ActionsServiceProvider::class,
    Filament\FilamentServiceProvider::class,
    Filament\Forms\FormsServiceProvider::class,
    Filament\Infolists\InfolistsServiceProvider::class,
    Filament\Notifications\NotificationsServiceProvider::class,
    Filament\Schemas\SchemasServiceProvider::class,
    Filament\Support\SupportServiceProvider::class,
    Filament\Tables\TablesServiceProvider::class,
    Filament\Widgets\WidgetsServiceProvider::class,
    Livewire\LivewireServiceProvider::class,
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
];
