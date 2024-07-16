@section('heading')
    <x-govuk::notification-banner title="Cookies" colour="blue">
        <p class="govuk-body">We use some essential cookies to make this service work.</p>
        <p class="govuk-body">Your browser session will be stored once you sign in.</p>
    </x-govuk::notification-banner>
@endsection

@section('after-main')
    <x-govuk::details label="Forgotten your password?">
        <p class="govuk-body">Windows device passwords are controlled by Network Rail IT. You may either:</p>
        <x-govuk::ul bulleted>
            <li>
                <a class="govuk-link" href="{{ route('password-reset') }}" target="_blank">Reset your password online (opens in a new tab)</a>
            </li>
            <li>
                <a class="govuk-link" href="{{ route('it-helpdesk') }}" target="_blank">Contact the IT helpdesk (opens in a new tab)</a>
            </li>
        </x-govuk::ul>
    </x-govuk::details>
@endsection