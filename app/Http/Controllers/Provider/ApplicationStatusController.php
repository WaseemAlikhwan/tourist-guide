<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApplicationStatusController extends Controller
{
    public function show(): RedirectResponse|View
    {
        $user = auth()->user();

        if ($user->isApprovedContentProvider()) {
            return redirect()->route('provider.dashboard');
        }

        if (! $user->is_content_provider) {
            return redirect()
                ->route('provider.register')
                ->with('info', 'لم يُسجَّل لديك طلب مزوّد محتوى بعد.');
        }

        return view('provider.application-status', compact('user'));
    }
}
