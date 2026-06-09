<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'password' => $request->validated('password'),
            'status' => UserStatus::Active,
        ]);

        $user->assignRole(RoleName::Customer->value);
        event(new Registered($user));
        Auth::login($user);

        if ($return = session()->pull('checkout_return')) {
            return redirect($return);
        }

        return redirect()->route('customer.dashboard');
    }
}
