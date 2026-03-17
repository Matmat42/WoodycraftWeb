<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Commande;
use App\Policies\CommandePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Commande::class => CommandePolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}