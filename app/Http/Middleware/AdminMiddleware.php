<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $pengguna = $request->user();
        if (! $pengguna || ! $pengguna->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin.');
        }

        return $next($request);
    }
}
