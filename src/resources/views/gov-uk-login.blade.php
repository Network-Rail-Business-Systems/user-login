@section('heading')
    <x-govuk::notification-banner title="Cookies" colour="blue">
        <p class="govuk-body">We use some essential cookies to make this service work.</p>
        <p class="govuk-body">Your browser session will be stored once you sign in.</p>
    </x-govuk::notification-banner>
@endsection

@section('after-main')
    <x-govuk::details label="Forgotten your password?">
        <p class="govuk-body">{{ config('user-login.forgot_password_details.body-text') }}</p>
        <x-govuk::ul bulleted>
            <li>
                <a class="govuk-link" href="{{ route(config('user-login.forgot_password.password-reset-route'))}}" target="_blank">Reset your password online (opens in a new tab)</a>
            </li>
            <li>
                <a class="govuk-link" href="{{ route(config('user-login.forgot_password.it-helpdesk-route')) }}" target="_blank">Contact the IT helpdesk (opens in a new tab)</a>
            </li>
        </x-govuk::ul>
    </x-govuk::details>
@endsection
