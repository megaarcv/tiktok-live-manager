<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    /**
     * Redirect ke halaman login Google.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google setelah login.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }

        // Cari user berdasarkan google_id atau email
        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            // Cek apakah email sudah terdaftar (akun biasa)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Update google_id untuk akun yang sudah ada
                $user->update(['google_id' => $googleUser->getId()]);
            } else {
                // Buat akun baru
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password'  => bcrypt(Str::random(24)),
                ]);
            }
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
