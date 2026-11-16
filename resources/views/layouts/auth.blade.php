<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DENTAL CARE') · OdontoAgenda CRM</title>
    
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%2314b8a6'/%3E%3Cstop offset='100%25' stop-color='%231a3a5c'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='32' height='32' rx='8' fill='url(%23g)'/%3E%3Cpath d='M16 6c-3.8 0-5.8 2-5.8 5 0 3.2 1.4 5.6 2.2 8.6.6 2.2 1.6 4.4 3.1 4.4 1 0 1.4-1.6 1.8-3.4.3-1 .6-1 .9 0 .4 1.8.8 3.4 1.8 3.4 1.5 0 2.5-2.2 3.1-4.4.8-3 2.2-5.4 2.2-8.6 0-3-2-5-5.8-5z' fill='white'/%3E%3Cpath d='M13.2 13.5c1.4 1.6 4.2 1.6 5.6 0' stroke='%230d9488' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#effaf8', 100: '#d7f4ef', 200: '#b0e8de', 300: '#7fd7c8',
                            400: '#4ec0b0', 500: '#14b8a6', 600: '#0d9488', 700: '#0f766e',
                            800: '#115e59', 900: '#134e4a',
                        },
                        secondary: {
                            50: '#f0f5fa', 100: '#deebf4', 200: '#c3daea', 300: '#99bfdc',
                            400: '#679dc8', 500: '#3a7bb7', 600: '#2b6299', 700: '#234f7c',
                            800: '#1a3a5c', 900: '#0f2338',
                        }
                    },
                    fontFamily: {
                        heading: ['Bricolage Grotesque', 'system-ui', 'sans-serif'],
                        sans: ['Figtree', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;700;800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-primary-500 selection:text-white">
    <!-- Barra superior institucional -->
    <header class="bg-white border-b border-slate-200/80 py-3.5 px-6">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-secondary-800 flex items-center justify-center text-white shadow-sm">
                    <svg class="w-5 h-5" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 6c-3.8 0-5.8 2-5.8 5 0 3.2 1.4 5.6 2.2 8.6.6 2.2 1.6 4.4 3.1 4.4 1 0 1.4-1.6 1.8-3.4.3-1 .6-1 .9 0 .4 1.8.8 3.4 1.8 3.4 1.5 0 2.5-2.2 3.1-4.4.8-3 2.2-5.4 2.2-8.6 0-3-2-5-5.8-5z" fill="white"/>
                        <path d="M13.2 13.5c1.4 1.6 4.2 1.6 5.6 0" stroke="#0d9488" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <span class="font-heading font-extrabold text-base tracking-tight text-secondary-900 block leading-none">DENTAL<span class="text-primary-600">CARE</span></span>
                    <span class="text-[10px] text-slate-400 font-medium">Clínica Odontológica Especializada</span>
                </div>
            </a>
            <div class="text-xs">
                <a href="{{ route('home') }}" class="text-slate-600 hover:text-primary-700 font-semibold transition flex items-center gap-1">
                    &larr; <span>Volver a la clínica</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 md:p-8">
        <div class="w-full max-w-md">
            @if(session('success'))
                <div class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-4 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-900 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs space-y-1">
                    @foreach($errors->all() as $err)
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            <span>{{ $err }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-3xl p-7 sm:p-8 shadow-sm border border-slate-200/80">
                @yield('content')
            </div>
        </div>
    </main>

    <footer class="py-4 text-center text-xs text-slate-400 border-t border-slate-100">
        DentalCare © {{ date('Y') }} · Plataforma Segura OdontoAgenda CRM · Cali, Colombia
    </footer>
</body>
</html>
