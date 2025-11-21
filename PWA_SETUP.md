# Configuração PWA - Stock Master

O suporte PWA foi implementado com sucesso! Para completar a configuração, você precisa gerar os ícones PWA.

## Ícones Necessários

Os ícones devem ser gerados nos seguintes tamanhos e salvos em `public/icons/`:

- icon-72x72.png
- icon-96x96.png
- icon-128x128.png
- icon-144x144.png
- icon-152x152.png
- icon-192x192.png
- icon-384x384.png
- icon-512x512.png

## Como Gerar os Ícones

### Opção 1: Usando Ferramentas Online (Recomendado - Mais Fácil)

1. Acesse https://realfavicongenerator.net/ ou https://www.pwabuilder.com/imageGenerator
2. Faça upload do arquivo `public/assets/images/logo.svg`
3. Configure as opções de PWA
4. Baixe e extraia os ícones gerados para `public/icons/`

### Opção 2: Usando ImageMagick (Linha de Comando)

Se você tiver ImageMagick instalado no sistema:

```bash
# Converter SVG para PNG em diferentes tamanhos
convert -background none -resize 72x72 public/assets/images/logo.svg public/icons/icon-72x72.png
convert -background none -resize 96x96 public/assets/images/logo.svg public/icons/icon-96x96.png
convert -background none -resize 128x128 public/assets/images/logo.svg public/icons/icon-128x128.png
convert -background none -resize 144x144 public/assets/images/logo.svg public/icons/icon-144x144.png
convert -background none -resize 152x152 public/assets/images/logo.svg public/icons/icon-152x152.png
convert -background none -resize 192x192 public/assets/images/logo.svg public/icons/icon-192x192.png
convert -background none -resize 384x384 public/assets/images/logo.svg public/icons/icon-384x384.png
convert -background none -resize 512x512 public/assets/images/logo.svg public/icons/icon-512x512.png
```

**Windows:** Baixe ImageMagick em https://imagemagick.org/script/download.php

### Opção 3: Usando Ferramentas de Design

1. Abra o logo SVG em Figma, Photoshop, GIMP ou outra ferramenta de design
2. Exporte o logo nos tamanhos listados acima como PNG
3. Salve os arquivos PNG em `public/icons/` com os nomes corretos

## Verificar Instalação

Após gerar os ícones, você pode verificar se estão todos presentes executando:

```bash
php artisan pwa:generate-icons
```

## Funcionalidades Implementadas

✅ Manifest.json configurado
✅ Service Worker para cache offline
✅ Script de registro PWA
✅ Meta tags para iOS/Android
✅ Cache de assets estáticos
✅ Cache de páginas visitadas (offline básico)
✅ Atualização automática do service worker

## Testando o PWA

1. Acesse o site em um dispositivo móvel ou Chrome Desktop
2. Abra as DevTools (F12) > Application > Service Workers
3. Verifique se o service worker está registrado
4. Teste o modo offline (DevTools > Network > Offline)
5. Tente instalar o app (Chrome mostrará um prompt de instalação)

## Notas

- O service worker cacheia páginas visitadas para funcionamento offline básico
- APIs e requisições Livewire não são cacheadas
- O cache é atualizado automaticamente quando uma nova versão é detectada

