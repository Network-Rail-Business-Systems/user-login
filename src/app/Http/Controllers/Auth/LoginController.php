<?php

namespace NetworkRailBusinessSystems\UserLogin\Http\Controllers\Auth;

use AnthonyEdmonds\GovukLaravel\Helpers\GovukPage;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use NetworkRailBusinessSystems\UserLogin\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    public function index(): View
    {
        $view = config('user-login.view');

        $action = route('login');

        $buttonLabel = 'Sign in';

        $forgotPasswordDetails = config('user-login.forgot-password');

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
                    [
                        'label' => $questions[0]['label'],
                        'name' => $questions[0]['name'],
                        'hint' => $questions[0]['hint'],
                        'width' => 20,
                    ],
                    [
                        'label' => $questions[1]['label'],
                        'name' => $questions[1]['name'],
                        'hint' => $questions[1]['hint'],
                        'width' => 20,
                    ],
                ],
                $buttonLabel,
                $action,
                null,
                'post',
                "user-login::$view",
            )->with('forgotPasswordDetails', $forgotPasswordDetails)
            : view("user-login::$view", [
                'questions' => $questions,
                'action' => $action,
                'buttonLabel' => $buttonLabel,
                'forgotPasswordDetails' => $forgotPasswordDetails,
            ]);
    }

    public function signIn(LoginRequest $request): RedirectResponse
    {
        $usernameKey = config('user-login.auth-identifier');

        $details = [
            $usernameKey => strtolower($request->username),
            'password' => $request->password,
        ];

        if ($this->syncExistingUser($details[$usernameKey]) === false) {
            return $this->loginFailed($details[$usernameKey]);
        }

        return Auth::attempt($details, true) === true
            ? $this->loginSucceeded()
            : $this->loginFailed($details[$usernameKey]);
    }

    protected function syncExistingUser(string $username): bool
    {
        $model = config('user-login.local-model');
        $modelIdentifier = config('user-login.local-model-identifier');
        $modelUniqueIdentifier = config('user-login.local-unique-identifier');

        $guid = $model::uniqueIdentifier($username);

        if ($guid === null) {
            return false;
        }

        $model::query()
            ->where($modelUniqueIdentifier, '=', $guid)
            ->limit(1)
            ->update([
                $modelIdentifier => $username,
            ]);

        return true;
    }

    public function signOut(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }

    protected function loginFailed(string $username): RedirectResponse
    {
        $message = config('user-login.login-failed-message');

        flash()->error($message);

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

        $message = config('user-login.login-success-message');

        flash()->success($message);

        return redirect()->intended();
    }
}
