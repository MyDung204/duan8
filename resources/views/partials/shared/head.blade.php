<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="{{ asset('image/favicon.png') }}" type="image/png">

{{-- Fonts are self-hosted via resources/css/fonts.css and Vite build --}}
@once
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endonce


{{-- Layouts sẽ tự quyết định @vite entries (public vs admin) --}}
@fluxAppearance
