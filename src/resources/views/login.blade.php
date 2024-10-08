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
        <form class="login-form" method="POST" action="{{ $action }}">
            @csrf

            <div class="field" style="margin-bottom: 1.5rem;">
                <label class="label" for="{{ $questions[0]['name'] }}">{{ $questions[0]['label'] }}</label>

                <p style="margin-bottom: 0.5rem;">{{ $questions[0]['hint'] }}</p>

                <div class="control">
                    <input
                            class="input"
                            style="max-width: 15rem;"
                            id="{{ $questions[0]['name'] }}"
                            type="text"
                            name="{{ $questions[0]['name'] }}"
                            value="{{ old('username') }}"
                            required
                    />

                    @error($questions[0]['name'])
                    <p class="help is-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="field" style="margin-bottom: 1.5rem;">
                <label class="label" for="{{ $questions[1]['name'] }}">{{ $questions[1]['label'] }}</label>

                <p style="margin-bottom: 0.5rem;">{{ $questions[1]['hint'] }}</p>

                <div class="control">
                    <input
                        class="input"
                        style="max-width: 15rem;"
                        id="{{ $questions[1]['name'] }}"
                        type="password"
                        name="{{ $questions[1]['name'] }}"
                        required
                    />
                </div>
            </div>

            <div class="field">
                <div class="field-body">
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-primary">{{ $buttonLabel }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
