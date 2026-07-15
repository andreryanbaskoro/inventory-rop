<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PemilikMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $pengguna = $request->user();
        if (! $pengguna || ! $pengguna->isPemilik()) {
            abort(403, 'Akses ditolak. Hanya Pemilik.');
        }

        return $next($request);
    }
}
