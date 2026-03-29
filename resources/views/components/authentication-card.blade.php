<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <!-- Logo -->
    <div class="h-16 flex items-center">
        <a href="/">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" class="h-14 w-auto" />
        </a>
    </div>

    <!-- Card de autenticação -->
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>