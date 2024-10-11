<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;  
use Inertia\Inertia;
use Inertia\Response;

class PasswordResetLinkController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Customer/Auth/ForgotPassword', [
            'status' => session('status'),
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ]);
        
        // We will send the password reset link to this customer. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the customer. Finally, we'll send out a proper response.
        $status = Password::broker('customers')->sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);

    }
}
