<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApprovedContentProvider
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        $user = auth()->user();

        if ($user->isApprovedContentProvider()) {
            return $next($request);
        }

        if ($user->is_content_provider && in_array($user->content_provider_status, ['pending', 'rejected'], true)) {
            return redirect()->route('provider.application-status');
        }

        return redirect()
            ->route('provider.register')
            ->with('info', 'للوصول إلى لوحة مزوّد المحتوى، سجّل أولاً كمزوّد محتوى معتمد.');
    }
}
