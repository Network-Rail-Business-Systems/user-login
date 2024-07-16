<div title="Login">
    <div class="column is-full">
        <h1 class="title is-marginless">Login</h1>
    </div>
    @if(session('status'))
        <div class="notification">
            {{ session('status') }}
        </div>
    @endif

    <div class="column is-full">
        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field" style="margin-bottom: 1.5rem;">
                <label class="label" for="username">What is your username?</label>

                <p style="margin-bottom: 0.5rem;">The username you use to access your Windows device, such as "jdoe3".</p>

                <div class="control">
                    <input
                            class="input"
                            style="max-width: 15rem;"
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            required
                    />

                    @error('username')
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="field" style="margin-bottom: 1.5rem;">
                <label for="password" class="label">What is your password?</label>

                <p style="margin-bottom: 0.5rem;">The password you use to access your Windows device.</p>

                <div class="control">
                    <input
                            class="input"
                            style="max-width: 15rem;"
                            id="password"
                            type="password"
                            name="password"
                            required
                    />
                </div>
            </div>

            <div class="field">
                <div class="field-body">
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-primary">Login</button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="content">
                <div class="field">
                    <article class="message is-small is-primary"
                             style="max-width: 35rem;"
                    >
                        <div class="message-header">
                            <p>Need help?</p>
                        </div>
                        <div class="message-body">
                            Contact RS Business Systems Support here - <a href="https://systems.hiav.networkrail.co.uk/enquiry/">https://systems.hiav.networkrail.co.uk/enquiry/</a>
                        </div>
                    </article>
                </div>

            </div>

            <div class="content">
                <details class="details">
                    <summary class="summary">
                    <span class="summary-text">
                      Forgotten your password?
                    </span>
                    </summary>
                    <div class="message-body">Windows device passwords are controlled by Network Rail IT. You may either:
                        <ul>
                            <li>
                                <a href="{{ route('password-reset') }}" target="_blank">Reset your password online (opens in a new tab)</a>
                            </li>
                            <li>
                                <a href="{{ route('it-helpdesk') }}" target="_blank">Contact the IT helpdesk (opens in a new tab)</a>
                            </li>
                        </ul>
                    </div>
                </details>
            </div>
        </form>
    </div>
    @push('scripts')
        <script>
            function browserIsInternetExplorer() {
                const user_agent = navigator.userAgent;

                return user_agent.indexOf('MSIE ') > -1 || user_agent.indexOf('Trident/') > -1;
            }

            if (browserIsInternetExplorer()) {
                alert('This system is not supported on IE11, therefore not all the features will work. For the best performance, please use Microsoft Edge, Google Chrome or Mozilla Firefox.');
            }
        </script>
    @endpush
</div>