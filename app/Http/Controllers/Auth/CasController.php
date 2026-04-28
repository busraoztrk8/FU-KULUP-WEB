<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use DDYO\CasAuthenticate\Facades\CasAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class CasController extends Controller
{
    /**
     * Handle CAS login.
     */
    public function login()
    {
        if (Auth::check()) {
            return redirect()->intended(route('home'));
        }

        try {
            // authenticateAndLogin automatically handles user creation/update based on email
            $attr = CasAuth::authenticateAndLogin();
            
            if ($attr) {
                $user = $attr['user'];
                
                // Sync name and email from CAS fields if they are empty
                if (empty($user->name)) {
                    $user->name = $user->userFullName ?? ($user->userFirstName . ' ' . $user->userLastName);
                }
                if (empty($user->email)) {
                    $user->email = $user->userEMailAddress;
                }

                // ── ROLE ASSIGNMENT LOGIC ──
                $adminRole = Role::where('name', 'admin')->first();
                $studentRole = Role::where('name', 'student')->first();
                $isAcademicPersonnel = mb_strtoupper(trim($user->userDescription ?? ''), 'UTF-8') === 'AKADEMİK PERSONEL';

                if ($isAcademicPersonnel) {
                    // Check if there is already an existing admin (other than this user)
                    $existingAdmin = $adminRole 
                        ? User::where('role_id', $adminRole->id)->where('id', '!=', $user->id)->first() 
                        : null;

                    if ($existingAdmin) {
                        // Another admin already exists — assign student role and warn
                        if (!$user->role_id || $user->role_id !== $adminRole->id) {
                            $user->role_id = $studentRole ? $studentRole->id : $user->role_id;
                        }
                        $user->save();
                        Auth::login($user);

                        return redirect()->route('home')->with('warning', __('site.admin_already_exists'));
                    } else {
                        // No admin exists yet — assign admin role
                        $user->role_id = $adminRole ? $adminRole->id : $user->role_id;
                        $user->save();
                        Auth::login($user);

                        return redirect()->intended(route('home'));
                    }
                } else {
                    // Not academic personnel — assign student role if not set
                    if (!$user->role_id) {
                        $user->role_id = $studentRole ? $studentRole->id : 2;
                    }
                    $user->save();
                    Auth::login($user);

                    return redirect()->intended(route('home'));
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'CAS Girişi Başarısız: ' . $e->getMessage());
        }

        return redirect()->route('home');
    }

    /**
     * Handle CAS logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return CasAuth::logout(["service" => route("home")]);
    }
}
