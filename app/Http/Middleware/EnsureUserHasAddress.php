<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Adresse;

class EnsureUserHasAddress
{
    public function handle(Request $request, Closure $next)
    {
        if (! Adresse::where('user_id', Auth::id())->exists()) {
            return redirect()->route('adresses.create')
                ->with('message', 'Ajoutez une adresse pour continuer.');
        }
        return $next($request);
    }
}
