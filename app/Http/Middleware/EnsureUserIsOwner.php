<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isOwner()) {
            abort(403, 'Aksi ini hanya dapat dilakukan oleh Owner. Admin hanya memiliki izin memantau.');
        }

        return $next($request);
    }
}
