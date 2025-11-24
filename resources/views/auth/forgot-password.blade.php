<x-guest-layout>
    <x-authentication-card>
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Esqueceu sua senha? Sem problemas. Basta nos informar seu endereço de e-mail e enviaremos um link de redefinição de senha que permitirá que você escolha uma nova.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Voltar para login') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Enviar Link de Redefinição') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

