<div>
    <h1>Teste Simples</h1>
    <p>Arquivos: {{ count($files) }}</p>
    <p>Pastas: {{ count($folders) }}</p>
    
    @if(count($files) > 0)
        <ul>
            @foreach($files as $file)
                <li>{{ $file->original_name }}</li>
            @endforeach
        </ul>
    @else
        <p>Nenhum arquivo</p>
    @endif
</div>

