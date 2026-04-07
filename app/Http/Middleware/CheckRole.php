<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Bu işlem için giriş yapmanız gerekmektedir.');
        }

        // Admin her şeye erişebilir
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Verilen rollerden birine sahip mi?
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Bu işlem için yetkiniz bulunmamaktadır.');
    }
}
