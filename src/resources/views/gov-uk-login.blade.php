@section('heading')
    <x-govuk::notification-banner title="Cookies" colour="blue">
        <p class="govuk-body">We use some essential cookies to make this service work.</p>
        <p class="govuk-body">Your browser session will be stored once you sign in.</p>
    </x-govuk::notification-banner>
@endsection

@section('after-main')
    <x-govuk::details label="Forgotten your password?">
        <p class="govuk-body">{{ config('user-login.forgot_password.description') }}</p>
        <x-govuk::ul bulleted>
            @foreach(config('user-login.forgot_password.routes') as $lable => $name)
                <li>
                    <a class="govuk-link" href="{{ route($name) }}" target="_blank">{{ $lable }}</a>
                </li>
            @endforeach
        </x-govuk::ul>
    </x-govuk::details>
@endsection
