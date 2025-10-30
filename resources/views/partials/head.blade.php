<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="{{ asset('image/favicon.png') }}" type="image/png">

{{-- Fonts are self-hosted via resources/css/fonts.css and Vite build --}}

{{-- Layouts sẽ tự quyết định @vite entries (public vs admin) --}}
@fluxAppearance
