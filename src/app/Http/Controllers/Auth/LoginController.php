<?php

namespace NetworkRailBusinessSystems\UserLogin\Http\Controllers\Auth;

use AnthonyEdmonds\GovukLaravel\Helpers\GovukPage;
use AnthonyEdmonds\GovukLaravel\Helpers\GovukQuestion;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use NetworkRailBusinessSystems\UserLogin\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function index(): View
    {
        $view = config('user-login.view');

        $action = route('login');

        $buttonLabel = 'Sign in';

        $questions = [
            [
                'name' => 'username',
                'label' => 'What is your username?',
                'hint' => 'The username you use to access your Windows device, such as jdoe3.',
            ],
            [
                'name' => 'password',
                'label' => 'What is your password?',
                'hint' => 'The password you use to access your Windows device.',
            ],
        ];

        return $view === 'gov-uk-login'
            ? GovukPage::questions(
                'Sign in',
                [
                    GovukQuestion::input($questions[0]['label'], $questions[0]['name'])
                        ->hint($questions[0]['hint'])
                        ->width(20),

                    GovukQuestion::input($questions[1]['label'], $questions[1]['name'])
                        ->hint($questions[1]['hint'])
                        ->width(20),
                ],
                $buttonLabel,
                $action,
                null,
                'post',
                "user-login::$view"
            )
            : view("user-login::$view", [
                'questions' => $questions,
                'action' => $action,
                'buttonLabel' => $buttonLabel,
            ]);
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
        $model = config('user-login.model');

        $ldapUser = LdapUser::query()
            ->where('samaccountname', '=', $username)
            ->first();

        if ($ldapUser === null) {
            return false;
        }

        $model::query()
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
        /** @var Model $user */
        $user = Auth::user();
        $user->touch();

        flash('You have successfully signed in')->success();

        return redirect()->intended();
    }
}
