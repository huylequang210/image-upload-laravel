<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        
        Fortify::loginView(function() {
            return view('auth.login');
        });

        Fortify::registerView(function() {
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function() {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function() {
            return view('auth.verify-email');
        });

        Fortify::authenticateUsing(function(Request $request) {
            $user = User::where('name', $request->name)->first();
            if(is_null($user)) 
                throw ValidationException::withMessages([
                    Fortify::username() => "User name is not found"
                ]);
            if($user && Hash::check($request->password, $user->password))
                return $user;
        });

    }
}
