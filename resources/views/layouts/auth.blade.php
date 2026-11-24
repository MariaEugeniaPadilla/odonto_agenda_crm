<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DENTAL CARE') · OdontoAgenda CRM</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/dentalcare-icon.png') }}">

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
    <header class="bg-white border-b border-slate-200/80 py-3 px-6">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group" title="Ir al inicio de DentalCare">
                <img src="{{ asset('images/dentalcare-clean.png') }}" alt="Dental Care - Sonrisas saludables" class="h-10 sm:h-11 w-auto object-contain transition transform group-hover:scale-[1.02]">
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

            @if(session('info'))
                <div class="mb-4 p-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-900 text-xs flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
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

    <footer class="py-5 text-center text-xs text-slate-400 border-t border-slate-100 bg-white">
        <div class="max-w-5xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 text-slate-500">
                <img src="{{ asset('images/dentalcare-icon.png') }}" class="w-4 h-4 object-contain inline" alt="">
                <span>Dental Care © {{ date('Y') }} · Sonrisas saludables · Cali, Colombia</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-[11px] text-slate-400">Plataforma Segura:</span>
                <img src="{{ asset('images/odontoagenda-clean.png') }}" alt="OdontoAgenda CRM" class="h-5 w-auto object-contain opacity-80">
            </div>
        </div>
    </footer>
</body>
</html>
