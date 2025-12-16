@php
    /** @var \App\Models\User $user */
    /** @var string $password */
@endphp

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo ao {{ config('app.name') }}</title>
</head>
<body>
    <h1>Bem-vindo(a), {{ $user->name }}!</h1>

    <p>Seu acesso ao sistema {{ config('app.name') }} foi criado.</p>

    <p><strong>Usuário (email):</strong> {{ $user->email }}</p>
    <p><strong>Senha provisória:</strong> {{ $password }}</p>

    <p>Recomendamos que você altere sua senha no primeiro acesso.</p>

    <p>Para acessar o sistema, utilize o link: <a href="{{ config('app.url') }}">{{ config('app.url') }}</a></p>

    <p>Atenciosamente,<br>{{ config('app.name') }}</p>
</body>
</html>





