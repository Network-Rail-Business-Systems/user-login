<?php

namespace NetworkRailBusinessSystems\UserLogin\Http\Controllers;


use Illuminate\Routing\Controller;
use AnthonyEdmonds\GovukLaravel\Helpers\GovukPage;
use AnthonyEdmonds\GovukLaravel\Helpers\GovukQuestion;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use NetworkRailBusinessSystems\UserLogin\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function index(): View
    {
        $view = config('user-login.view');

        return $view === 'gov-uk-login'
            ? GovukPage::questions(
                'Sign in',
                [
                    GovukQuestion::input('What is your username?', 'username')
                        ->hint('The username you use to access your Windows device, such as jdoe3.')
                        ->width(20),

                    GovukQuestion::input('What is your password?', 'password', 'password')
                        ->hint('The password you use to access your Windows device.')
                        ->width(20),
                ],
                'Sign in',
                route('login'),
                null,
                'post',
                "user-login::$view"
            )
            : view("user-login::$view");

    }

    public function signIn(LoginRequest $request): RedirectResponse
    {
        $details = [
            'samaccountname' => strtolower($request->username),
            'password' => $request->password,
        ];

        if ($this->syncExistingUser($details['samaccountname']) === false) {
            return $this->loginFailed($details['samaccountname']);
        }

        return Auth::attempt($details, true) === true
            ? $this->loginSucceeded()
            : $this->loginFailed($details['samaccountname']);
    }

    public function signOut(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }

    protected function syncExistingUser(string $username): bool
    {
        $ldapUser = LdapUser::query()
            ->where('samaccountname', '=', $username)
            ->first();

        if ($ldapUser === null) {
            return false;
        }

        User::query()
            ->where('username', '=', $username)
            ->orWhere('email', '=', $ldapUser->getAttributeValue('mail'))
            ->limit(1)
            ->update([
                'username' => $username,
                'email' => $ldapUser->getAttributeValue('mail'),
            ]);

        return true;
    }

    protected function loginFailed(string $username): RedirectResponse
    {
        flash('Sign in failed; please check your username and password and try again')->error();

        return redirect()
            ->route('login')
            ->withInput([
                'username' => $username,
            ]);
    }

    protected function loginSucceeded(): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $user->touch();

        flash('You have successfully signed in')->success();

        return redirect()->intended();
    }
}