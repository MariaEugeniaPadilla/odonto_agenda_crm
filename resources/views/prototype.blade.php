<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DENTAL CARE · Clínica Odontológica en Cali | Odonto Agenda</title>
    
    <!-- Favicon Oficial DENTAL CARE -->
    <link rel="icon" type="image/png" href="{{ asset('images/dentalcare-icon.png') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#effaf8',
                            100: '#d7f4ef',
                            200: '#b0e8de',
                            300: '#7fd7c8',
                            400: '#4ec0b0',
                            500: '#14b8a6', // Aqua turquesa clínico principal
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        },
                        secondary: {
                            50: '#f0f5fa',
                            100: '#deebf4',
                            200: '#c3daea',
                            300: '#99bfdc',
                            400: '#679dc8',
                            500: '#3a7bb7',
                            600: '#2b6299',
                            700: '#234f7c',
                            800: '#1a3a5c', // Azul profundo institucional
                            900: '#0f2338',
                        },
                        accent: {
                            50: '#f2fbf7',
                            100: '#dff6ec',
                            200: '#c0eddb',
                            300: '#92dec3',
                            400: '#5ec7a6',
                            500: '#34b38d', // Menta suave
                            600: '#249372',
                            700: '#1f755b',
                            800: '#1c5d4a',
                            900: '#184d3e',
                        }
                    },
                    fontFamily: {
                        heading: ['Poppins', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        sans: ['Inter', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft-xs': '0 1px 3px rgba(15, 76, 110, 0.04)',
                        'soft-sm': '0 2px 8px -2px rgba(15, 76, 110, 0.06), 0 1px 4px -1px rgba(15, 76, 110, 0.04)',
                        'soft-md': '0 6px 18px -4px rgba(15, 76, 110, 0.08), 0 2px 6px -2px rgba(15, 76, 110, 0.04)',
                        'soft-lg': '0 12px 28px -6px rgba(15, 76, 110, 0.10), 0 4px 12px -2px rgba(15, 76, 110, 0.05)',
                        'soft-xl': '0 20px 35px -8px rgba(15, 76, 110, 0.12), 0 6px 16px -4px rgba(15, 76, 110, 0.06)',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="css/prototype.css">
</head>
<body class="min-h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-primary-500 selection:text-white">

    <!-- ===================================================================== -->
    <!-- BARRA SUPERIOR DE PRUEBAS / SIMULADOR DE USUARIOS (DISCRETA Y ELEGANTE) -->
    <!-- ===================================================================== -->
    <aside class="bg-secondary-900 text-slate-200 text-xs py-1.5 px-4 sticky top-0 z-50 border-b border-secondary-800 shadow-sm" aria-label="Panel de demostración de roles">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2.5">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-accent-400 animate-pulse" aria-hidden="true"></span>
                <img src="{{ asset('images/odontoagenda-icon.png') }}" class="w-3.5 h-3.5 object-contain" alt="">
                <span class="font-heading font-semibold tracking-wide text-primary-300 uppercase text-[11px]">OdontoAgenda CRM</span>
                <span class="text-secondary-400 hidden md:inline text-[11px]">· Panel de Pruebas de Roles</span>
            </div>

            <!-- Selector de Roles Discreto -->
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-secondary-300 text-[11px] font-normal mr-1 hidden sm:inline">Simular usuario:</span>
                
                <button onclick="switchRoleDemo('paciente-normal')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-secondary-800/80 hover:bg-secondary-700 text-emerald-300 font-medium text-[11px] border border-secondary-700/60 transition" title="Paciente sin faltas con agendamiento libre">
                    <svg class="w-3 h-3 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Paciente Normal</span>
                </button>

                <button onclick="switchRoleDemo('paciente-bloqueado')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-secondary-800/80 hover:bg-secondary-700 text-rose-300 font-medium text-[11px] border border-secondary-700/60 transition" title="Demuestra la atención personalizada con 2 faltas">
                    <svg class="w-3 h-3 text-rose-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                    <span>Paciente (2 Faltas)</span>
                </button>

                <button onclick="switchRoleDemo('odontologo')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-secondary-800/80 hover:bg-secondary-700 text-amber-300 font-medium text-[11px] border border-secondary-700/60 transition">
                    <svg class="w-3 h-3 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/></svg>
                    <span>Dra. Camila</span>
                </button>

                <button onclick="switchRoleDemo('admin')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-secondary-800/80 hover:bg-secondary-700 text-purple-300 font-medium text-[11px] border border-secondary-700/60 transition">
                    <svg class="w-3 h-3 text-purple-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                    <span>Recepción</span>
                </button>

                <div id="current-user-badge" class="ml-1 sm:ml-2"></div>
            </div>
        </div>
    </aside>

    <!-- ===================================================================== -->
    <!-- CABECERA INSTITUCIONAL Y MENÚ PRINCIPAL (DENTAL CARE) -->
    <!-- ===================================================================== -->
    <header class="glass-nav border-b border-slate-200/80 sticky top-8 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-4">
            
            <!-- LOGO INSTITUCIONAL OFICIAL DE DENTAL CARE -->
            <div class="flex items-center cursor-pointer group py-1" onclick="navigateToModule('mod-landing')" role="button" aria-label="Ir a la página de inicio">
                <img src="{{ asset('images/dentalcare-clean.png') }}" alt="Dental Care - Sonrisas saludables" class="h-10 sm:h-12 w-auto object-contain transition transform group-hover:scale-[1.02]">
            </div>

            <!-- Botones de Navegación del Prototipo -->
            <nav class="flex flex-wrap items-center gap-1.5 md:gap-2" aria-label="Navegación principal">
                <button data-target="mod-landing" onclick="navigateToModule('mod-landing')" class="nav-tab-btn px-3.5 py-2 rounded-xl text-xs md:text-sm font-semibold bg-primary-600 text-white shadow-soft-sm flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    <span>Inicio / Servicios</span>
                </button>

                <button data-target="mod-login" onclick="navigateToModule('mod-login')" class="nav-tab-btn px-3.5 py-2 rounded-xl text-xs md:text-sm font-medium bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>Login / Registro</span>
                </button>

                <button data-target="mod-paciente" onclick="navigateToModule('mod-paciente')" class="nav-tab-btn px-3.5 py-2 rounded-xl text-xs md:text-sm font-medium bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Portal Paciente</span>
                </button>

                <button data-target="mod-odontologo" onclick="navigateToModule('mod-odontologo')" class="nav-tab-btn px-3.5 py-2 rounded-xl text-xs md:text-sm font-medium bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/></svg>
                    <span>Agenda Especialista</span>
                </button>

                <button data-target="mod-admin" onclick="navigateToModule('mod-admin'); switchAdminTab('operaciones');" class="nav-tab-btn px-3.5 py-2 rounded-xl text-xs md:text-sm font-medium bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                    <span>Admin & Ausentismo</span>
                </button>

                <button id="nav-btn-usuarios" data-target="mod-admin" onclick="navigateToModule('mod-admin'); switchAdminTab('usuarios');" class="nav-tab-btn px-3.5 py-2 rounded-xl text-xs md:text-sm font-medium bg-white hover:bg-slate-100 text-slate-700 border border-slate-200/80 flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Usuarios</span>
                </button>

                <!-- CTA PRINCIPAL: AGENDAR CITA CON ODONTO AGENDA -->
                <button onclick="openBookingWizard()" class="ml-1 sm:ml-2 px-4 py-2 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white text-xs md:text-sm font-semibold shadow-soft-sm hover:shadow-soft-md transition transform hover:-translate-y-0.5 flex items-center gap-1.5" aria-label="Abrir asistente para agendar cita">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    <span>Agendar Cita</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- ===================================================================== -->
    <!-- PANTALLA 1: INICIO / SERVICIOS (HERO & CATÁLOGO DENTAL CARE) -->
    <!-- ===================================================================== -->
    <main id="mod-landing" class="app-module flex-1">
        
        <!-- Hero Section con atmósfera luminosa y confiable -->
        <section class="relative overflow-hidden bg-gradient-to-b from-primary-50/70 via-white to-white py-16 md:py-24 border-b border-slate-100">
            <!-- Formas decorativas orgánicas suaves de fondo -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-100/50 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>
            <div class="absolute top-1/2 -right-24 w-96 h-96 bg-secondary-100/40 rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
                
                <!-- Columna Izquierda: Mensaje y Propuesta de Valor -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-white/90 backdrop-blur-md text-primary-900 text-xs font-semibold border border-primary-200/80 shadow-soft-xs">
                        <img src="{{ asset('images/dentalcare-icon.png') }}" class="w-5 h-5 object-contain flex-shrink-0" alt="Dental Care">
                        <span>Clínica Odontológica DENTAL CARE · Sonrisas Saludables</span>
                    </div>

                    <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-[34px] text-secondary-900 tracking-tight leading-snug">
                        Tu sonrisa en las mejores manos: atención odontológica moderna y sin filas innecesarias.
                    </h1>

                    <p class="text-slate-600 text-base md:text-lg leading-relaxed max-w-2xl">
                        En <strong>DENTAL CARE</strong> integramos especialistas en Ortodoncia, Endodoncia y Odontología Estética con la tecnología de <strong>Odonto Agenda</strong> para brindarte un servicio puntual, cálculo aditivo de tiempo de tratamiento y recordatorios preventivos en tiempo real.
                    </p>

                    <!-- Acciones principales -->
                    <div class="flex flex-wrap items-center gap-3.5 pt-2">
                        <button onclick="openBookingWizard()" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl bg-primary-600 hover:bg-primary-700 text-white font-semibold text-sm shadow-soft-md hover:shadow-soft-lg transition transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                            <span>Agendar Cita en Línea</span>
                        </button>
                        <button onclick="navigateToModule('mod-paciente')" class="inline-flex items-center gap-2.5 px-6 py-3.5 rounded-2xl border border-slate-200 hover:border-primary-400 bg-white hover:bg-slate-50 text-secondary-900 font-semibold text-sm shadow-soft-sm transition">
                            <svg class="w-4 h-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>Portal del Paciente</span>
                        </button>
                    </div>

                    <!-- Micro-métricas de Confianza Clínica -->
                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-200/80">
                        <div>
                            <strong class="font-heading text-2xl font-bold text-secondary-900 block">15 min</strong>
                            <span class="text-xs text-slate-500">Tolerancia de cortesía</span>
                        </div>
                        <div>
                            <strong class="font-heading text-2xl font-bold text-secondary-900 block">24 hrs</strong>
                            <span class="text-xs text-slate-500">Recordatorio oportuno</span>
                        </div>
                        <div>
                            <strong class="font-heading text-2xl font-bold text-primary-600 block">0%</strong>
                            <span class="text-xs text-slate-500">Cruces de horario</span>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Tarjeta de Reserva Rápida con Odonto Agenda -->
                <div class="lg:col-span-5">
                    <div class="dental-card p-6 sm:p-7 space-y-5 bg-white">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 gap-3">
                            <div>
                                <img src="{{ asset('images/odontoagenda-clean.png') }}" alt="OdontoAgenda CRM" class="h-6 sm:h-7 w-auto object-contain mb-1">
                                <h2 class="font-heading font-bold text-secondary-900 text-base">Reserva Rápida de Atención</h2>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full bg-accent-100 text-accent-800 font-semibold flex items-center gap-1.5 border border-accent-200">
                                <span class="w-2 h-2 rounded-full bg-accent-500 animate-pulse" aria-hidden="true"></span>
                                <span>Cupos Disponibles</span>
                            </span>
                        </div>

                        <!-- Pasos visuales claros -->
                        <div class="space-y-3">
                            <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200/70 text-xs flex items-start gap-3">
                                <div class="w-6 h-6 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-[11px] flex-shrink-0">1</div>
                                <div>
                                    <span class="text-slate-400 block font-medium text-[11px]">Tratamiento</span>
                                    <strong class="text-slate-800 font-semibold">Selección múltiple con cálculo aditivo de tiempo</strong>
                                </div>
                            </div>
                            <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200/70 text-xs flex items-start gap-3">
                                <div class="w-6 h-6 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-[11px] flex-shrink-0">2</div>
                                <div>
                                    <span class="text-slate-400 block font-medium text-[11px]">Especialista</span>
                                    <strong class="text-slate-800 font-semibold">Ortodoncia, Endodoncia o Estética Dental</strong>
                                </div>
                            </div>
                            <div class="p-3.5 bg-slate-50/80 rounded-xl border border-slate-200/70 text-xs flex items-start gap-3">
                                <div class="w-6 h-6 rounded-lg bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-[11px] flex-shrink-0">3</div>
                                <div>
                                    <span class="text-slate-400 block font-medium text-[11px]">Horario Seguro</span>
                                    <strong class="text-slate-800 font-semibold">Sin solapamientos ni esperas en sala</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Botón directo hacia el Wizard -->
                        <button onclick="openBookingWizard()" class="w-full py-3.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold text-xs uppercase tracking-wider shadow-soft-sm hover:shadow-soft-md transition">
                            Iniciar Agendamiento en 4 Pasos
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Portafolio Clínico de Servicios en DENTAL CARE -->
        <section class="py-16 md:py-20 bg-white" aria-labelledby="servicios-titulo">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="text-center max-w-2xl mx-auto mb-14">
                    <span class="text-xs font-semibold uppercase tracking-widest text-primary-600 mb-2 block">Portafolio Clínico · DENTAL CARE</span>
                    <h2 id="servicios-titulo" class="font-heading font-bold text-2xl sm:text-3xl text-secondary-900">Nuestros Servicios Odontológicos Especializados</h2>
                    <p class="text-slate-500 text-sm mt-2">Tarifas transparentes de referencia en Pesos Colombianos (COP) y tiempos de atención estimados para tu comodidad en Cali.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Tarjeta Servicio 1 -->
                    <div class="dental-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="w-9 h-9 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center font-bold text-xs">01</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg border border-slate-200/60">
                                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>30 min</span>
                                </span>
                            </div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Valoración General</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Diagnóstico completo, examen exhaustivo de la cavidad oral y formulación de plan de tratamiento personalizado.</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-heading text-lg font-bold text-secondary-900">$30.000 <span class="text-xs font-normal text-slate-400">COP</span></span>
                            <button onclick="openBookingWizard()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-800 transition">
                                <span>Agendar</span>
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta Servicio 2 -->
                    <div class="dental-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="w-9 h-9 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center font-bold text-xs">02</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg border border-slate-200/60">
                                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>45 min</span>
                                </span>
                            </div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Profilaxis (Limpieza Profunda)</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Limpieza ultrasónica, remoción de sarro supragingival, manchas superficiales y pulido coronario con flúor.</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-heading text-lg font-bold text-secondary-900">$100.000 <span class="text-xs font-normal text-slate-400">COP</span></span>
                            <button onclick="openBookingWizard()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-800 transition">
                                <span>Agendar</span>
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta Servicio 3 -->
                    <div class="dental-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="w-9 h-9 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center font-bold text-xs">03</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg border border-slate-200/60">
                                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>60 min</span>
                                </span>
                            </div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Blanqueamiento Dental</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Aclaramiento profesional en consultorio con gel de peróxido de alta pureza y fotoactivación controlada.</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-heading text-lg font-bold text-secondary-900">$500.000 <span class="text-xs font-normal text-slate-400">COP</span></span>
                            <button onclick="openBookingWizard()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-800 transition">
                                <span>Agendar</span>
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta Servicio 4 -->
                    <div class="dental-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="w-9 h-9 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center font-bold text-xs">04</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg border border-slate-200/60">
                                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>45 min</span>
                                </span>
                            </div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Resina Estética / Calza</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Restauración anatómica con resina nanohíbrida de fotocurado, logrando acabado natural y alta resistencia.</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-heading text-lg font-bold text-secondary-900">$80.000 <span class="text-xs font-normal text-slate-400">COP</span></span>
                            <button onclick="openBookingWizard()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-800 transition">
                                <span>Agendar</span>
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta Servicio 5 -->
                    <div class="dental-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="w-9 h-9 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center font-bold text-xs">05</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg border border-slate-200/60">
                                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>90 min</span>
                                </span>
                            </div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Endodoncia Especializada</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Tratamiento mecanizado de conductos radiculares para aliviar dolor pulpar y conservar la pieza dental.</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-heading text-lg font-bold text-secondary-900">$400.000 <span class="text-xs font-normal text-slate-400">COP</span></span>
                            <button onclick="openBookingWizard()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-800 transition">
                                <span>Agendar</span>
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta Servicio 6 & 7 -->
                    <div class="dental-card p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="w-9 h-9 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center font-bold text-xs">06/07</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg border border-slate-200/60">
                                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span>30 min c/u</span>
                                </span>
                            </div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Ortodoncia y Cirugía Simple</h3>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">Controles periódicos con cambio de arcos ($120.000) y exodoncias dentales bajo técnica atraumática ($150.000).</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="font-heading text-sm font-bold text-secondary-900">Desde $120.000 COP</span>
                            <button onclick="openBookingWizard()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-800 transition">
                                <span>Agendar</span>
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Equipo de Especialistas en DENTAL CARE -->
        <section class="py-16 bg-slate-50 border-t border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="text-center max-w-2xl mx-auto mb-12">
                    <span class="text-xs font-semibold uppercase tracking-widest text-primary-600 mb-2 block">Cuerpo Médico Especializado</span>
                    <h2 class="font-heading font-bold text-2xl sm:text-3xl text-secondary-900">Especialistas al Cuidado de tu Salud Oral</h2>
                    <p class="text-slate-500 text-sm mt-1">Profesionales certificados con amplia trayectoria clínica en la ciudad de Cali.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Doctor 1 -->
                    <div class="dental-card p-6 text-center space-y-4">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 text-primary-700 mx-auto flex items-center justify-center shadow-soft-xs">
                            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Dra. Camila Morales</h3>
                            <p class="text-xs text-primary-700 font-semibold mt-0.5">Ortodoncia y Ortopedia Maxilar</p>
                            <span class="text-[11px] text-slate-400 block mt-0.5">Reg. RM-ORT-4491 · Cali</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">Experta en alineación dental estética, brackets cerámicos y sistemas autoligados. Atención de Lunes a Sábado.</p>
                    </div>

                    <!-- Doctor 2 -->
                    <div class="dental-card p-6 text-center space-y-4">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 text-primary-700 mx-auto flex items-center justify-center shadow-soft-xs">
                            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Dr. Carlos Mendoza</h3>
                            <p class="text-xs text-primary-700 font-semibold mt-0.5">Endodoncia y Rehabilitación Oral</p>
                            <span class="text-[11px] text-slate-400 block mt-0.5">Reg. RM-END-8210 · Cali</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">Especialista en tratamiento microquirúrgico de conductos radiculares y prótesis fija. Atención de Lunes a Viernes.</p>
                    </div>

                    <!-- Doctor 3 -->
                    <div class="dental-card p-6 text-center space-y-4">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 text-primary-700 mx-auto flex items-center justify-center shadow-soft-xs">
                            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Dra. Valeria Gómez</h3>
                            <p class="text-xs text-primary-700 font-semibold mt-0.5">Odontología General y Estética</p>
                            <span class="text-[11px] text-slate-400 block mt-0.5">Reg. RM-ODG-3315 · Cali</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">Líder en diseño de sonrisa mínimamente invasivo, blanqueamiento y resinas de alta definición. Atención de Lunes a Sábado.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ===================================================================== -->
    <!-- PANTALLA 2: LOGIN / REGISTRO (AUTENTICACIÓN AMIGABLE DENTAL CARE) -->
    <!-- ===================================================================== -->
    <section id="mod-login" class="app-module flex-1 py-12 px-4 sm:px-6 hidden">
        <div class="max-w-md mx-auto dental-card p-8 bg-white space-y-6">
            
            <!-- Encabezado de Login con Logo Oficial -->
            <div class="text-center space-y-3">
                <img src="{{ asset('images/dentalcare-clean.png') }}" alt="Dental Care - Sonrisas saludables" class="h-14 mx-auto object-contain">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-[11px] text-slate-600 font-medium">
                    <img src="{{ asset('images/odontoagenda-icon.png') }}" class="w-3.5 h-3.5 object-contain" alt="">
                    <span>Plataforma OdontoAgenda CRM</span>
                </div>
                <div>
                    <h2 class="font-heading font-bold text-xl text-secondary-900">Ingreso al Sistema</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Accede a tu cuenta de paciente o equipo de DENTAL CARE</p>
                </div>
            </div>

            <!-- Formulario amigable conectado a la BD -->
            <form id="login-form" onsubmit="handleLoginFormSubmit(event);" class="space-y-4">
                <div>
                    <label for="login-email-input" class="block text-xs font-semibold text-slate-700 mb-1.5">Correo Electrónico</label>
                    <input type="email" id="login-email-input" value="juan.perez@gmail.com" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div>
                    <label for="login-pass-input" class="block text-xs font-semibold text-slate-700 mb-1.5">Contraseña</label>
                    <input type="password" id="login-pass-input" value="Password123!" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="login-remember-input" checked class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-slate-300">
                        <span class="text-slate-600 font-medium">Recordarme</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-primary-700 hover:text-primary-800 font-semibold hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold text-sm shadow-soft-sm hover:shadow-soft-md transition">
                    Iniciar Sesión
                </button>

                @if(config('app.registro_publico', true))
                    <div class="pt-2 text-center text-xs text-slate-500">
                        ¿Eres paciente nuevo?
                        <a href="{{ route('register') }}" class="text-primary-700 hover:text-primary-800 font-bold hover:underline ml-1">
                            Registrarse como Paciente &rarr;
                        </a>
                    </div>
                @endif
            </form>

            <!-- Atajos rápidos para demostración sin fricciones -->
            <div class="pt-5 border-t border-slate-100 text-center">
                <span class="text-[11px] text-slate-400 uppercase font-semibold tracking-wider block mb-2.5">Atajos para evaluación académica</span>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" onclick="switchRoleDemo('paciente-normal')" class="p-2 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 font-medium border border-slate-200/80 transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Paciente Libre</span>
                    </button>
                    <button type="button" onclick="switchRoleDemo('paciente-bloqueado')" class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-medium border border-rose-200/80 transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-rose-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                        <span>Paciente 2 Faltas</span>
                    </button>
                    <button type="button" onclick="switchRoleDemo('odontologo')" class="p-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 font-medium border border-amber-200/80 transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/></svg>
                        <span>Dra. Morales</span>
                    </button>
                    <button type="button" onclick="switchRoleDemo('admin')" class="p-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-800 font-medium border border-purple-200/80 transition flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-purple-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                        <span>Recepción</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- PANTALLA 3: PORTAL DEL PACIENTE (MIS CITAS & HISTORIAL CLÍNICO) -->
    <!-- ===================================================================== -->
    <section id="mod-paciente" class="app-module flex-1 py-8 px-4 sm:px-6 max-w-7xl mx-auto w-full hidden space-y-6">
        
        <!-- Saludo Personalizado y Acción de Agendamiento -->
        <div class="dental-card p-6 flex flex-wrap items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white border border-primary-100 p-2 flex items-center justify-center shadow-soft-xs flex-shrink-0">
                    <img src="{{ asset('images/dentalcare-icon.png') }}" alt="Dental Care" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-primary-700 uppercase tracking-wide">Portal del Paciente</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-primary-50 text-primary-700 border border-primary-200 font-medium">Dental Care</span>
                    </div>
                    <h2 class="font-heading font-bold text-xl text-secondary-900">Hola, <span id="pac-user-name">Juan Carlos Pérez</span></h2>
                    <p class="text-xs text-slate-500 mt-0.5">Bienvenido a tu espacio personal de salud oral en DENTAL CARE Cali.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button id="btn-start-booking" onclick="openBookingWizard()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold text-xs uppercase tracking-wider shadow-soft-sm hover:shadow-soft-md transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    <span>Agendar Nueva Cita</span>
                </button>
            </div>
        </div>

        <!-- Alerta Dinámica de Inasistencias (Tono Empático y Amable) -->
        <div id="pac-alert-box" class="empty:hidden"></div>

        <!-- Tarjeta de Próxima Cita Activa -->
        <div id="pac-next-appointment"></div>

        <!-- Tabla de Citas del Paciente -->
        <div class="dental-card p-6 space-y-4 bg-white">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    </div>
                    <h3 class="font-heading font-bold text-secondary-900 text-base">Mis Citas Agendadas y Estado</h3>
                </div>
                <span class="text-xs text-slate-400">Cancelaciones y cambios habilitados hasta 2h antes</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200/80 text-[11px] font-semibold uppercase text-slate-400 tracking-wider">
                            <th class="py-2.5 px-4">Cita ID</th>
                            <th class="py-2.5 px-4">Fecha y Franja</th>
                            <th class="py-2.5 px-4">Especialista</th>
                            <th class="py-2.5 px-4">Tratamientos</th>
                            <th class="py-2.5 px-4">Estado</th>
                            <th class="py-2.5 px-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="pac-citas-list"></tbody>
                </table>
            </div>
        </div>

        <!-- Historial de Atenciones Clínicas -->
        <div class="dental-card p-6 space-y-4 bg-white">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                    </div>
                    <h3 class="font-heading font-bold text-secondary-900 text-base">Historial Clínico de Procedimientos Realizados</h3>
                </div>
                <span class="text-xs text-slate-400">Trazabilidad oficial de historias clínicas</span>
            </div>
            <div id="pac-historial-list"></div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- PANTALLA 4: AGENDA ESPECIALISTA (DRA. CAMILA MORALES) -->
    <!-- ===================================================================== -->
    <section id="mod-odontologo" class="app-module flex-1 py-8 px-4 sm:px-6 max-w-7xl mx-auto w-full hidden space-y-6">
        
        <!-- Header del Odontólogo Especialista -->
        <div class="dental-card p-6 flex flex-wrap items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white border border-amber-200/80 p-2 flex items-center justify-center shadow-soft-xs flex-shrink-0">
                    <img src="{{ asset('images/dentalcare-icon.png') }}" alt="Dental Care" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-amber-700 uppercase tracking-wide">Consultorio Clínico</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 border border-amber-200 font-medium">Dental Care</span>
                    </div>
                    <h2 class="font-heading font-bold text-xl text-secondary-900"><span id="odo-doctor-name">Dra. Camila Morales</span></h2>
                    <p class="text-xs text-slate-500 mt-0.5">Especialista en Ortodoncia y Ortopedia Maxilar · Cali</p>
                </div>
            </div>

            <!-- Selector de Fecha de Atención y Marca CRM -->
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center px-3 py-1.5 bg-slate-50 rounded-xl border border-slate-200/70" title="Plataforma OdontoAgenda CRM">
                    <img src="{{ asset('images/odontoagenda-clean.png') }}" alt="OdontoAgenda CRM" class="h-5 w-auto object-contain">
                </div>
                <div class="flex items-center gap-2.5">
                    <label for="odo-agenda-date" class="text-xs font-semibold text-slate-600 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        <span>Fecha:</span>
                    </label>
                    <input type="date" id="odo-agenda-date" onchange="renderOdontologoPortal()" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
        </div>

        <!-- Indicadores Rápidos del Consultorio en la Fecha -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3.5">
            <div class="dental-card p-4 text-center bg-white">
                <span class="text-[11px] text-slate-400 uppercase font-semibold block">Total Citas</span>
                <strong id="odo-m-total" class="font-heading text-2xl font-bold text-secondary-900 mt-1 block">0</strong>
            </div>
            <div class="dental-card p-4 text-center bg-white">
                <span class="text-[11px] text-sky-600 uppercase font-semibold block">Pendientes</span>
                <strong id="odo-m-pendientes" class="font-heading text-2xl font-bold text-sky-700 mt-1 block">0</strong>
            </div>
            <div class="dental-card p-4 text-center bg-white">
                <span class="text-[11px] text-purple-600 uppercase font-semibold block">En Sillón</span>
                <strong id="odo-m-proceso" class="font-heading text-2xl font-bold text-purple-700 mt-1 block">0</strong>
            </div>
            <div class="dental-card p-4 text-center bg-white">
                <span class="text-[11px] text-emerald-600 uppercase font-semibold block">Atendidas</span>
                <strong id="odo-m-atendidas" class="font-heading text-2xl font-bold text-emerald-700 mt-1 block">0</strong>
            </div>
            <div class="dental-card p-4 text-center bg-white">
                <span class="text-[11px] text-amber-600 uppercase font-semibold block">No Asistió</span>
                <strong id="odo-m-noasistio" class="font-heading text-2xl font-bold text-amber-700 mt-1 block">0</strong>
            </div>
        </div>

        <!-- Cronograma Cronológico de Pacientes -->
        <div class="dental-card p-6 space-y-4 bg-white">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-heading font-bold text-secondary-900 text-base">Cronograma de Citas del Día</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Control de atención clínica, descuento de insumos y registro de notas médicas</p>
                </div>
                <span class="text-xs text-slate-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Tolerancia protocolaria: 15 minutos</span>
                </span>
            </div>
            <div id="odo-citas-timeline" class="space-y-4"></div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- PANTALLA 5: PANEL ADMINISTRATIVO, RECEPCIÓN & CONTROL DE AUSENTISMO -->
    <!-- ===================================================================== -->
    <section id="mod-admin" class="app-module flex-1 py-8 px-4 sm:px-6 max-w-7xl mx-auto w-full hidden space-y-6">
        
        <!-- Header Administrativo con Selector de Pestañas (Operaciones vs Usuarios) -->
        <div class="dental-card p-6 flex flex-wrap items-center justify-between gap-4 bg-white">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 p-2 flex items-center justify-center shadow-soft-xs flex-shrink-0">
                    <img src="{{ asset('images/odontoagenda-icon.png') }}" alt="OdontoAgenda CRM" class="w-10 h-10 object-contain">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-secondary-700 uppercase tracking-wide">Administración & Recepción</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 border border-slate-200 font-medium">OdontoAgenda</span>
                    </div>
                    <h2 class="font-heading font-bold text-xl text-secondary-900">Panel de Control Operativo · DENTAL CARE</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Supervisión integral de citas, insumos médicos y administración de usuarios</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="hidden lg:flex items-center px-3 py-2 bg-slate-50 rounded-xl border border-slate-200/80 mr-1" title="Plataforma OdontoAgenda CRM">
                    <img src="{{ asset('images/odontoagenda-clean.png') }}" alt="OdontoAgenda CRM" class="h-6 w-auto object-contain">
                </div>
                <!-- Selector de Pestañas del Panel Admin -->
                <div class="bg-slate-100 p-1 rounded-2xl flex items-center gap-1 text-xs font-semibold border border-slate-200/80">
                    <button type="button" id="admin-tab-btn-operaciones" onclick="switchAdminTab('operaciones')" class="px-3.5 py-1.5 rounded-xl bg-white text-secondary-900 shadow-soft-xs transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-primary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                        <span>Operaciones & Citas</span>
                    </button>
                    <button type="button" id="admin-tab-btn-usuarios" onclick="switchAdminTab('usuarios')" class="px-3.5 py-1.5 rounded-xl text-slate-600 hover:text-secondary-900 transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Gestión de Usuarios</span>
                    </button>
                </div>
                <button onclick="actualizarDatosAdminActual()" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-soft-xs transition">
                    <svg class="w-3.5 h-3.5 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                    <span>Actualizar</span>
                </button>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- SUB-VISTA 1: OPERACIONES, AGENDA, INSUMOS & REPORTES -->
        <!-- ================================================================= -->
        <div id="admin-subview-operaciones" class="space-y-6">
            <!-- Indicadores Gerenciales con Semáforo de Ausentismo -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5">
                <div class="dental-card p-5 bg-white">
                    <span class="text-[11px] text-slate-400 font-semibold uppercase block">Total Citas</span>
                    <strong id="adm-kpi-total" class="font-heading text-2xl font-bold text-secondary-900 mt-1 block">0</strong>
                    <span class="text-[11px] text-slate-400">En sistema</span>
                </div>
                <div class="dental-card p-5 bg-white">
                    <span class="text-[11px] text-emerald-600 font-semibold uppercase block">Atendidas</span>
                    <strong id="adm-kpi-atendidas" class="font-heading text-2xl font-bold text-emerald-700 mt-1 block">0</strong>
                    <span class="text-[11px] text-emerald-600 font-medium">Insumos descontados</span>
                </div>
                <div class="dental-card p-5 bg-white">
                    <span class="text-[11px] text-amber-600 font-semibold uppercase block">Inasistencias</span>
                    <strong id="adm-kpi-noasistio" class="font-heading text-2xl font-bold text-amber-700 mt-1 block">0</strong>
                    <span class="text-[11px] text-amber-700 font-medium">Insumos preservados</span>
                </div>
                <div class="dental-card p-5 bg-gradient-to-br from-amber-50/80 to-rose-50/80 border-amber-200">
                    <span class="text-[11px] text-amber-950 font-bold uppercase block">Tasa Ausentismo</span>
                    <strong id="adm-kpi-tasa" class="font-heading text-2xl font-black text-rose-700 mt-1 block">0%</strong>
                    <span class="text-[11px] text-amber-900 font-medium">Faltas / (Atend + Faltas)</span>
                </div>
                <div class="dental-card p-5 bg-white">
                    <span class="text-[11px] text-rose-600 font-semibold uppercase block">Alertas Stock</span>
                    <strong id="adm-kpi-stock-alert" class="font-heading text-2xl font-bold text-rose-700 mt-1 block">0</strong>
                    <span class="text-[11px] text-slate-400">Bajo umbral mínimo</span>
                </div>
            </div>

            <!-- Tabla General de Consultorios (Recepción) -->
            <div class="dental-card p-6 space-y-4 bg-white">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-secondary-50 text-secondary-800 flex items-center justify-center">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Agenda General de Consultorios</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Control de cupos, asignación de horas y verificación de asistencia</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200/80 text-[11px] font-semibold uppercase text-slate-400 tracking-wider">
                                <th class="py-2.5 px-3.5">ID</th>
                                <th class="py-2.5 px-3.5">Horario</th>
                                <th class="py-2.5 px-3.5">Paciente</th>
                                <th class="py-2.5 px-3.5">Especialista</th>
                                <th class="py-2.5 px-3.5">Tratamiento</th>
                                <th class="py-2.5 px-3.5">Estado</th>
                                <th class="py-2.5 px-3.5">Control Recepción</th>
                            </tr>
                        </thead>
                        <tbody id="adm-agenda-table-body"></tbody>
                    </table>
                </div>
            </div>

            <!-- Inventario de Insumos Médicos Odontológicos -->
            <div class="dental-card p-6 space-y-4 bg-white">
                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-700 flex items-center justify-center">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-secondary-900 text-base">Control de Inventario y Suministros Clínicos</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Regla de Negocio: Los insumos únicamente se descuentan tras finalizar la cita en estado 'Atendido'.</p>
                        </div>
                    </div>
                </div>
                <div id="adm-inventario-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5"></div>
            </div>

            <!-- Indicador Comparativo de Ausentismo por Odontólogo -->
            <div class="dental-card p-6 space-y-4 bg-white">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-secondary-50 text-secondary-800 flex items-center justify-center">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                        </div>
                        <h3 class="font-heading font-bold text-secondary-900 text-base">Tasa de Ausentismo por Especialista</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200/80 text-[11px] font-semibold uppercase text-slate-400 tracking-wider">
                                <th class="py-2.5 px-3.5">Especialista</th>
                                <th class="py-2.5 px-3.5">Área Clínica</th>
                                <th class="py-2.5 px-3.5 text-center">Atendidas</th>
                                <th class="py-2.5 px-3.5 text-center">Inasistencias</th>
                                <th class="py-2.5 px-3.5 text-center">Tasa Ausentismo</th>
                            </tr>
                        </thead>
                        <tbody id="adm-reporte-odontologos-tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- SUB-VISTA 2: GESTIÓN DE USUARIOS DEL SISTEMA (EXCLUSIVO ADMIN) -->
        <!-- ================================================================= -->
        <div id="admin-subview-usuarios" class="space-y-6 hidden">
            <!-- Barra Superior de Gestión de Usuarios -->
            <div class="dental-card p-6 bg-white space-y-4">
                <div class="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-secondary-900 text-lg">Directorio y Control de Usuarios</h3>
                            <p class="text-xs text-slate-500">Administra cuentas, asignación de roles definitivos, seguridad y credenciales</p>
                        </div>
                    </div>
                    <button type="button" onclick="abrirModalCrearUsuario()" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold text-xs shadow-soft-sm hover:shadow-soft-md transition flex items-center gap-2">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span>Nuevo Usuario</span>
                    </button>
                </div>

                <!-- Barra de Búsqueda y Filtros -->
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs">
                    <!-- Búsqueda en Vivo -->
                    <div class="sm:col-span-6 relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </span>
                        <input type="text" id="usuarios-filtro-buscar" oninput="debounceBuscarUsuarios()" placeholder="Buscar por nombre, correo o documento..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    </div>

                    <!-- Filtro por Rol Definitivo -->
                    <div class="sm:col-span-3">
                        <select id="usuarios-filtro-rol" onchange="renderAdminUsuarios(1)" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                            <option value="">Todos los Roles</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Odontólogo">Odontólogo</option>
                            <option value="Recepcionista">Recepcionista</option>
                            <option value="Paciente">Paciente</option>
                        </select>
                    </div>

                    <!-- Filtro por Estado Activo / Inactivo -->
                    <div class="sm:col-span-3">
                        <select id="usuarios-filtro-activo" onchange="renderAdminUsuarios(1)" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
                            <option value="">Todos los Estados</option>
                            <option value="1">Solo Activos</option>
                            <option value="0">Solo Inactivos</option>
                        </select>
                    </div>
                </div>

                <!-- Tabla Interactiva de Usuarios -->
                <div class="overflow-x-auto pt-2">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200/80 text-[11px] font-semibold uppercase text-slate-400 tracking-wider">
                                <th class="py-2.5 px-3.5">Usuario / Documento</th>
                                <th class="py-2.5 px-3.5">Contacto</th>
                                <th class="py-2.5 px-3.5">Rol Oficial</th>
                                <th class="py-2.5 px-3.5">Estado</th>
                                <th class="py-2.5 px-3.5">Auditoría</th>
                                <th class="py-2.5 px-3.5 text-right">Acciones de Seguridad</th>
                            </tr>
                        </thead>
                        <tbody id="adm-usuarios-tbody">
                            <!-- Filas cargadas dinámicamente desde /api/admin/usuarios -->
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div id="adm-usuarios-pagination" class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                    <!-- Dinámico -->
                </div>
            </div>
        </div>
    </section>

    <!-- ===================================================================== -->
    <!-- MODAL 1: CREAR USUARIO (CON ASIGNACIÓN DE CONTRASEÑA Y ENLACE SEGURO) -->
    <!-- ===================================================================== -->
    <div id="modal-crear-usuario" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-secondary-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-soft-xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh]">
            <div class="p-6 bg-gradient-to-r from-primary-600 to-secondary-800 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6"/><path d="M22 11h-6"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading text-base font-bold text-white">Crear Nuevo Usuario</h3>
                        <p class="text-xs text-primary-100">Asignación de rol oficial, datos y credenciales</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalCrearUsuario()" class="text-primary-100 hover:text-white text-2xl font-bold leading-none p-1 rounded-lg hover:bg-white/10 transition">&times;</button>
            </div>

            <form id="form-crear-usuario" onsubmit="handleCrearUsuarioSubmit(event)" class="p-6 overflow-y-auto space-y-4 text-xs flex-1">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nombre Completo *</label>
                    <input type="text" id="crear-user-nombre" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Ej. Dr. Andrés Restrepo">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Correo Electrónico *</label>
                        <input type="email" id="crear-user-correo" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="usuario@dentalcare.com">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Teléfono / Celular *</label>
                        <input type="text" id="crear-user-telefono" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="310 123 4567">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Documento de Identidad</label>
                        <input type="text" id="crear-user-doc" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="CC 114400...">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Rol en el Sistema *</label>
                        <select id="crear-user-rol" required onchange="onRolUsuarioChange(this, 'crear-odontologo-fields')" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500 font-semibold text-secondary-900 bg-white">
                            <option value="Paciente">Paciente</option>
                            <option value="Odontólogo">Odontólogo</option>
                            <option value="Recepcionista">Recepcionista</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>
                </div>

                <!-- Campos adicionales si el rol es Odontólogo -->
                <div id="crear-odontologo-fields" class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/80 space-y-3 hidden">
                    <span class="font-bold text-amber-950 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/></svg>
                        <span>Datos Profesionales del Especialista</span>
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div>
                            <label class="block font-medium text-amber-900 mb-1">Especialidad</label>
                            <input type="text" id="crear-user-especialidad" class="w-full px-3 py-2 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Ej. Ortodoncia">
                        </div>
                        <div>
                            <label class="block font-medium text-amber-900 mb-1">Registro Médico</label>
                            <input type="text" id="crear-user-registro" class="w-full px-3 py-2 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="RM-ORT-...">
                        </div>
                    </div>
                </div>

                <!-- Opciones de Contraseña (Requisito 1.c) -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3">
                    <span class="font-bold text-secondary-900 block">Asignación de Contraseña de Acceso</span>
                    
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input type="radio" name="crear_metodo_contrasena" value="enlace" checked onchange="onMetodoContrasenaChange('enlace')" class="mt-0.5 text-primary-600 focus:ring-primary-500">
                        <div>
                            <span class="font-semibold text-secondary-900">Enviar enlace seguro por correo (Recomendado)</span>
                            <p class="text-slate-500 text-[11px] mt-0.5">El usuario recibirá un correo con token temporal seguro para definir su propia contraseña mediante Password Broker.</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input type="radio" name="crear_metodo_contrasena" value="manual" onchange="onMetodoContrasenaChange('manual')" class="mt-0.5 text-primary-600 focus:ring-primary-500">
                        <div>
                            <span class="font-semibold text-secondary-900">Definir contraseña inicial manualmente</span>
                            <p class="text-slate-500 text-[11px] mt-0.5">Asignar una contraseña temporal. El usuario será marcado para cambiarla en su primer inicio de sesión.</p>
                        </div>
                    </label>

                    <div id="crear-pass-manual-container" class="pt-2 hidden">
                        <label class="block font-semibold text-slate-700 mb-1">Contraseña Inicial Manual (mínimo 8 caracteres) *</label>
                        <input type="password" id="crear-user-password" minlength="8" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="crear-user-activo" checked class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-slate-300">
                    <label for="crear-user-activo" class="font-semibold text-slate-700 cursor-pointer">Usuario activo inmediatamente</label>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="cerrarModalCrearUsuario()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition">
                        Cancelar
                    </button>
                    <button type="submit" id="crear-user-submit-btn" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white font-heading font-semibold shadow-soft-sm transition">
                        Crear y Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- MODAL 2: EDITAR USUARIO (DATOS, ROL, ESTADO Y RESPETO DE REGLAS ADMIN)-->
    <!-- ===================================================================== -->
    <div id="modal-editar-usuario" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-secondary-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-soft-xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh]">
            <div class="p-6 bg-secondary-900 text-white flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading text-base font-bold text-white">Editar Usuario</h3>
                        <p class="text-xs text-slate-300">Modificación de información, rol y estado</p>
                    </div>
                </div>
                <button type="button" onclick="cerrarModalEditarUsuario()" class="text-slate-300 hover:text-white text-2xl font-bold leading-none p-1 rounded-lg hover:bg-white/10 transition">&times;</button>
            </div>

            <form id="form-editar-usuario" onsubmit="handleEditarUsuarioSubmit(event)" class="p-6 overflow-y-auto space-y-4 text-xs flex-1">
                <input type="hidden" id="editar-user-id">

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nombre Completo *</label>
                    <input type="text" id="editar-user-nombre" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Correo Electrónico *</label>
                        <input type="email" id="editar-user-correo" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Teléfono / Celular *</label>
                        <input type="text" id="editar-user-telefono" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Documento de Identidad</label>
                        <input type="text" id="editar-user-doc" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 mb-1">Rol en el Sistema *</label>
                        <select id="editar-user-rol" required onchange="onRolUsuarioChange(this, 'editar-odontologo-fields')" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500 font-semibold text-secondary-900 bg-white">
                            <option value="Paciente">Paciente</option>
                            <option value="Odontólogo">Odontólogo</option>
                            <option value="Recepcionista">Recepcionista</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>
                </div>

                <div id="editar-rol-warning" class="p-2.5 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 text-[11px] hidden">
                    <strong>Aviso de seguridad:</strong> Como Administrador actual, no puedes removerte tu propio rol para garantizar la gobernanza del sistema.
                </div>

                <!-- Campos adicionales si el rol es Odontólogo -->
                <div id="editar-odontologo-fields" class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/80 space-y-3 hidden">
                    <span class="font-bold text-amber-950 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/></svg>
                        <span>Datos Profesionales del Especialista</span>
                    </span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div>
                            <label class="block font-medium text-amber-900 mb-1">Especialidad</label>
                            <input type="text" id="editar-user-especialidad" class="w-full px-3 py-2 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block font-medium text-amber-900 mb-1">Registro Médico</label>
                            <input type="text" id="editar-user-registro" class="w-full px-3 py-2 rounded-xl border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                </div>

                <!-- Cambio de Contraseña Opcional -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <label class="block font-semibold text-slate-700">Cambiar Contraseña (Opcional)</label>
                    <input type="password" id="editar-user-password" minlength="8" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="Dejar en blanco para mantener la contraseña actual">
                    <span class="text-[11px] text-slate-400 block">Si se ingresa un nuevo valor, se hasheará con Bcrypt y se obligará al cambio en el próximo ingreso.</span>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="editar-user-activo" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-slate-300">
                    <label for="editar-user-activo" class="font-semibold text-slate-700 cursor-pointer">Cuenta de usuario activa</label>
                </div>
                <div id="editar-activo-warning" class="p-2.5 rounded-xl bg-rose-50 text-rose-900 border border-rose-200 text-[11px] hidden">
                    <strong>Aviso de seguridad:</strong> No puedes desactivar tu propia cuenta mientras estés autenticado como Administrador.
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2.5">
                    <button type="button" onclick="cerrarModalEditarUsuario()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold transition">
                        Cancelar
                    </button>
                    <button type="submit" id="editar-user-submit-btn" class="px-5 py-2.5 rounded-xl bg-secondary-900 hover:bg-secondary-800 text-white font-heading font-semibold shadow-soft-sm transition">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- PANTALLA 6: MODAL WIZARD PARA AGENDAR CITA (ODONTO AGENDA EN 4 PASOS) -->
    <!-- ===================================================================== -->
    <div id="booking-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-secondary-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="wizard-step-title">
        <div class="bg-white w-full max-w-2xl rounded-3xl shadow-soft-xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh]">
            
            <!-- Encabezado del Wizard con Marca DENTAL CARE -->
            <div class="p-5 sm:p-6 bg-gradient-to-r from-primary-600 via-teal-600 to-secondary-800 text-white flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white p-1.5 flex items-center justify-center shadow-soft-xs flex-shrink-0">
                        <img src="{{ asset('images/dentalcare-icon.png') }}" class="w-8 h-8 object-contain" alt="Dental Care">
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span id="wizard-step-number" class="text-xs uppercase font-extrabold tracking-wider text-primary-200 block">Paso 1 de 4</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-white/20 text-white font-medium">Dental Care</span>
                        </div>
                        <h3 id="wizard-step-title" class="font-heading text-lg font-bold mt-0.5 text-white">Agendar Cita Odontológica</h3>
                    </div>
                </div>
                <button onclick="closeBookingWizard()" class="text-primary-100 hover:text-white text-2xl font-bold leading-none p-1 rounded-lg hover:bg-white/10 transition" aria-label="Cerrar asistente de agendamiento">&times;</button>
            </div>

            <!-- Contenedor de Alertas de Validación del Paso -->
            <div id="wizard-step-alert" class="px-6 pt-4 empty:hidden"></div>

            <!-- Cuerpo Dinámico del Paso Activo -->
            <div id="wizard-step-content" class="p-6 overflow-y-auto flex-1 space-y-4"></div>

            <!-- Pie del Wizard con Resumen y Navegación -->
            <div class="p-5 bg-slate-50 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-3">
                <div id="wizard-footer-summary"></div>
                <div class="flex items-center gap-2.5">
                    <button id="wizard-btn-prev" onclick="wizardPrev()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-100 transition hidden">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        <span>Anterior</span>
                    </button>
                    <button id="wizard-btn-next" onclick="wizardNext()" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 hover:from-primary-700 hover:to-primary-600 text-white text-xs font-heading font-semibold shadow-soft-sm transition">
                        <span>Continuar</span>
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- MODAL: FINALIZAR ATENCIÓN ODONTOLÓGICA (NOTAS CLÍNICAS) -->
    <!-- ===================================================================== -->
    <div id="doctor-finalizar-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-secondary-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="doctor-finalizar-titulo">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-soft-xl border border-slate-200 p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
                    </div>
                    <h3 id="doctor-finalizar-titulo" class="font-heading font-bold text-secondary-900 text-base">Finalizar Atención y Registrar Notas Clínicas</h3>
                </div>
                <button onclick="doctorCerrarFinalizarModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold p-1 rounded-lg" aria-label="Cerrar ventana">&times;</button>
            </div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Al confirmar, la cita pasará al estado <strong>'Atendido'</strong>, se insertará en el historial del paciente en DENTAL CARE y se descontarán automáticamente los insumos utilizados del inventario clínico.
            </p>

            <div id="doctor-finalizar-alert" class="empty:hidden"></div>

            <!-- Panel de Consulta de Historial Clínico DURANTE la Consulta -->
            <div class="border border-teal-200/90 rounded-2xl overflow-hidden bg-primary-50/40">
                <button type="button" onclick="toggleDoctorFinalizarHistorial()" class="w-full px-4 py-2.5 flex items-center justify-between text-left hover:bg-primary-100/50 transition">
                    <span class="inline-flex items-center gap-2 text-xs font-bold text-secondary-900">
                        <svg class="w-4 h-4 text-primary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                        <span>Historial Clínico Previo del Paciente</span>
                    </span>
                    <span id="doctor-finalizar-historial-toggle-btn" class="text-xs font-bold text-primary-700 flex items-center gap-1">
                        <span>Ver antecedentes</span>
                        <svg id="doctor-finalizar-historial-chevron" class="w-3.5 h-3.5 transform transition-transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                </button>
                <div id="doctor-finalizar-historial-box" class="hidden p-3.5 border-t border-teal-100 bg-white max-h-48 overflow-y-auto space-y-2 text-xs">
                    <!-- Se carga dinámicamente con los eventos clínicos y observaciones previas del paciente -->
                </div>
            </div>

            <div>
                <label for="doctor-notas-input" class="block text-xs font-semibold text-slate-700 mb-1.5">Observaciones Clínicas del Procedimiento</label>
                <textarea id="doctor-notas-input" rows="4" placeholder="Ej: Se completó limpieza ultrasónica y profilaxis coronal sin complicaciones. Se programa control en 6 meses." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
            </div>

            <div class="flex justify-end gap-2.5 pt-2">
                <button type="button" onclick="doctorCerrarFinalizarModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Cancelar
                </button>
                <button type="button" onclick="doctorConfirmarFinalizar()" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-xs font-heading font-semibold shadow-soft-sm transition">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Guardar y Finalizar Cita</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- MODAL DE HISTORIAL CLÍNICO DEL PACIENTE (PARA ODONTÓLOGOS) -->
    <!-- Permite ver antecedentes antes y durante la atención clínica -->
    <!-- ===================================================================== -->
    <div id="paciente-historial-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-secondary-900/60 backdrop-blur-sm hidden" role="dialog" aria-modal="true" aria-labelledby="paciente-historial-titulo">
        <div class="bg-white w-full max-w-2xl max-h-[90vh] rounded-3xl shadow-soft-xl border border-slate-200 flex flex-col overflow-hidden">
            <!-- Header del Modal -->
            <div class="p-6 pb-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-primary-100 text-primary-700 flex items-center justify-center shadow-soft-xs flex-shrink-0">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-primary-700 uppercase tracking-wide">Ficha Clínica · DENTAL CARE</span>
                        <h3 id="paciente-historial-titulo" class="font-heading font-bold text-secondary-900 text-lg">Historial Clínico del Paciente</h3>
                        <p id="paciente-historial-subtitulo" class="text-xs text-slate-500">Consultas previas, tratamientos realizados y evolución médica</p>
                    </div>
                </div>
                <button onclick="cerrarHistorialPacienteModal()" class="text-slate-400 hover:text-slate-600 text-2xl font-bold p-1 leading-none rounded-xl hover:bg-slate-100 transition" aria-label="Cerrar modal">&times;</button>
            </div>

            <!-- Contenido Desplazable -->
            <div class="p-6 overflow-y-auto space-y-5 flex-1 text-xs">
                <!-- Ficha Resumen del Paciente -->
                <div id="paciente-historial-info" class="p-4 rounded-2xl bg-gradient-to-br from-primary-50/60 to-slate-50 border border-primary-100/80 flex flex-wrap items-center justify-between gap-3">
                    <!-- Dinámico: Nombre, Documento, Teléfono, Faltas, Estado -->
                </div>

                <!-- Cabecera de Sección Cronológica -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <span class="font-heading font-bold text-secondary-900 text-sm">Registro Cronológico de Atenciones Clínicas</span>
                    <span id="paciente-historial-contador" class="text-slate-500 font-medium">0 eventos</span>
                </div>

                <!-- Timeline de Atenciones Clínicas y Observaciones -->
                <div id="paciente-historial-timeline" class="space-y-3">
                    <!-- Dinámico: Eventos clínicos pasados con notas del odontólogo -->
                </div>
            </div>

            <!-- Footer del Modal -->
            <div class="p-4 px-6 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between">
                <span class="text-[11px] text-slate-400 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-primary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                    <span>Información confidencial para uso exclusivo del profesional de salud en consulta</span>
                </span>
                <button type="button" onclick="cerrarHistorialPacienteModal()" class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold transition">
                    Cerrar Ficha
                </button>
            </div>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- CONTENEDOR FLOTANTE TOAST (CAPA SUPERIOR Z-[99999]) -->
    <!-- ===================================================================== -->
    <div id="toast-container" class="fixed top-5 right-5 z-[99999] flex flex-col gap-2.5 max-w-md w-full pointer-events-none px-4 sm:px-0"></div>

    <!-- ===================================================================== -->
    <!-- PIE DE PÁGINA CORPORATIVO INSTITUCIONAL -->
    <!-- ===================================================================== -->
    <footer class="bg-white border-t border-slate-200/80 py-10 text-xs text-slate-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 space-y-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-6 border-b border-slate-100">
                <!-- Marca Dental Care -->
                <div class="flex flex-col sm:flex-row items-center gap-3 text-center sm:text-left">
                    <img src="{{ asset('images/dentalcare-clean.png') }}" alt="Dental Care - Sonrisas saludables" class="h-11 w-auto object-contain">
                    <div class="border-t sm:border-t-0 sm:border-l border-slate-200 pt-2 sm:pt-0 sm:pl-3 text-slate-500 text-xs">
                        <span class="font-semibold block text-slate-700">Clínica Odontológica Especializada</span>
                        <span>Cali, Colombia · Atención ética, moderna y de calidad</span>
                    </div>
                </div>

                <!-- Marca OdontoAgenda CRM -->
                <div class="flex items-center gap-3 bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-200/80">
                    <span class="text-slate-400 text-[11px] font-medium hidden sm:inline">Tecnología de gestión:</span>
                    <img src="{{ asset('images/odontoagenda-clean.png') }}" alt="OdontoAgenda - CRM para Clínicas Dentales" class="h-7 w-auto object-contain">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-slate-400 text-[11px] text-center sm:text-left">
                <p>Dental Care © {{ date('Y') }} · Sonrisas saludables · Todos los derechos reservados.</p>
                <p>Plataforma impulsada por <strong>OdontoAgenda CRM</strong></p>
            </div>
        </div>
    </footer>

    <!-- Datos Iniciales Conectados a MySQL (odontoagenda_crm) -->
    <script>
        window.INITIAL_DB_STATE = {!! json_encode($initialState ?? null) !!};
        window.INITIAL_ACTIVE_MODULE = "{{ $activeModule ?? 'landing' }}";
    </script>

    <!-- Lógica Vanilla JS con Soporte y Renderizado de Iconos Vectoriales -->
    <script src="js/prototype.js"></script>
</body>
</html>