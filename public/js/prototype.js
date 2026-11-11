/**
 * Odonto Agenda · DENTAL CARE (Cali, Colombia)
 * Sistema de Agendamiento de Citas Odontológicas en Línea
 * Iconografía vectorial profesional y accesible (Librería consistente estilo Lucide / Heroicons).
 * Vanilla JavaScript con simulación de reglas de negocio en tiempo real:
 * - Regla 1: No solapamiento de horarios
 * - Regla 2: Cálculo aditivo de duración y costo total de servicios
 * - Regla 3: Ventana de cancelación oportuna (>= 2 horas)
 * - Regla 4: Tiempo de tolerancia de 15 minutos para marcar No Asistió
 * - Regla 5: Inviolabilidad de insumos ante inasistencias (solo descuenta en 'Atendido')
 * - Regla 6: Inmutabilidad de citas terminales
 * - Regla 7: Restricción web con >= 2 faltas consecutivas
 * - Regla 8: Notificación formal de reprogramación
 */

// Catálogo Centralizado de Iconos Vectoriales SVG (Familia Lucide, Trazo 2px, currentColor)
const AppIcons = {
    tooth: (cls = "w-5 h-5") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 2C8.5 2 7 4.5 7 7c0 3 1.5 5 2 8 .5 3 1.5 7 3 7s2.5-4 3-7c.5-3 2-5 2-8 0-2.5-1.5-5-5-5z"/>
            <path d="M9 7c1 1.5 2 2 3 2s2-.5 3-2"/>
        </svg>`,
    calendar: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
        </svg>`,
    clock: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
        </svg>`,
    user: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
        </svg>`,
    doctor: (cls = "w-5 h-5") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>
        </svg>`,
    stethoscope: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/>
            <path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/>
        </svg>`,
    alertTriangle: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>`,
    alertOctagon: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>`,
    checkCircle: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
        </svg>`,
    check: (cls = "w-3.5 h-3.5") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="20 6 9 17 4 12"/>
        </svg>`,
    ban: (cls = "w-5 h-5") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/>
        </svg>`,
    bell: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
        </svg>`,
    clipboardList: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>
        </svg>`,
    fileText: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>
        </svg>`,
    fileEdit: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/>
        </svg>`,
    package: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
        </svg>`,
    trendingUp: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
        </svg>`,
    calendarX: (cls = "w-6 h-6") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m14 14-4 4"/><path d="m10 14 4 4"/>
        </svg>`,
    play: (cls = "w-3.5 h-3.5") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" stroke="none" aria-hidden="true">
            <polygon points="6 3 20 12 6 21 6 3"/>
        </svg>`,
    banknote: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/>
        </svg>`,
    info: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
        </svg>`,
    plus: (cls = "w-3 h-3") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M5 12h14"/><path d="M12 5v14"/>
        </svg>`,
    arrowRight: (cls = "w-3.5 h-3.5") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
        </svg>`,
    arrowLeft: (cls = "w-3.5 h-3.5") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
        </svg>`,
    mail: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
        </svg>`,
    send: (cls = "w-4 h-4") => `
        <svg class="${cls}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
        </svg>`
};

// Estado inicial del sistema
const OdontoState = {
    currentUser: {
        id: 6,
        nombre: 'Maria Padilla',
        correo: 'mariaepadilla@gmail.com',
        telefono: '3157894561',
        rol: 'paciente',
        faltas_acumuladas: 0,
        puedeAgendarWeb: true
    },
    servicios: [
        { id: 1, nombre: 'Valoración general', duracion: 30, precio: 30000, desc: 'Diagnóstico inicial completo y examen oral preventivo.' },
        { id: 2, nombre: 'Profilaxis (limpieza)', duracion: 45, precio: 100000, desc: 'Limpieza ultrasónica, profilaxis dental y pulido coronario.' },
        { id: 3, nombre: 'Blanqueamiento dental', duracion: 60, precio: 500000, desc: 'Aclaramiento dental profesional con peróxido de alta pureza.' },
        { id: 4, nombre: 'Resina / calza dental', duracion: 45, precio: 80000, desc: 'Restauración estética con resina nanohíbrida de fotocurado.' },
        { id: 5, nombre: 'Endodoncia', duracion: 90, precio: 400000, desc: 'Tratamiento de conductos radiculares para salvar la pieza dental.' },
        { id: 6, nombre: 'Extracción simple', duracion: 30, precio: 150000, desc: 'Exodoncia dental bajo anestesia local con técnica atraumática.' },
        { id: 7, nombre: 'Control de ortodoncia', duracion: 30, precio: 120000, desc: 'Revisión y cambio de arcos, ligaduras y control de avance.' }
    ],
    odontologos: [
        { id: 1, nombre: 'Dra. Camila Morales', especialidad: 'Ortodoncia y Ortopedia Maxilar', registro: 'RM-ORT-4491' },
        { id: 2, nombre: 'Dr. Carlos Mendoza', especialidad: 'Endodoncia y Rehabilitación Oral', registro: 'RM-END-8210' },
        { id: 3, nombre: 'Dra. Valeria Gómez', especialidad: 'Odontología General y Estética Dental', registro: 'RM-ODG-3315' }
    ],
    insumos: [
        { id: 1, nombre: 'Guantes de nitrilo descartables', stock: 450, minimo: 100, unidad: 'pares' },
        { id: 2, nombre: 'Eyectores de saliva descartables', stock: 320, minimo: 80, unidad: 'uds' },
        { id: 3, nombre: 'Baberos odontológicos impermeables', stock: 280, minimo: 50, unidad: 'uds' },
        { id: 4, nombre: 'Carpules Anestesia Lidocaína 2%', stock: 28, minimo: 30, unidad: 'amp' },
        { id: 5, nombre: 'Agujas cortas de anestesia 30G', stock: 140, minimo: 40, unidad: 'uds' },
        { id: 6, nombre: 'Jeringas de resina nanohíbrida', stock: 8, minimo: 10, unidad: 'tubos' },
        { id: 7, nombre: 'Kit gel blanqueador peróxido', stock: 14, minimo: 5, unidad: 'kits' },
        { id: 8, nombre: 'Pasta profiláctica abrasiva', stock: 35, minimo: 10, unidad: 'tubos' },
        { id: 9, nombre: 'Conos de gutapercha estériles', stock: 80, minimo: 20, unidad: 'cajas' },
        { id: 10, nombre: 'Ligaduras elastoméricas', stock: 500, minimo: 150, unidad: 'uds' }
    ],
    citas: [
        {
            id: 101,
            pacienteId: 6,
            pacienteNombre: 'Maria Padilla',
            pacienteTel: '3157894561',
            odontologoId: 1,
            odontologoNombre: 'Dra. Camila Morales',
            fecha: new Date().toISOString().split('T')[0],
            horaInicio: '09:00',
            horaFin: '09:30',
            servicios: ['Control de ortodoncia'],
            duracionMin: 30,
            costo: 120000,
            estado: 'Asignado',
            observaciones: 'Control mensual de brackets superiores.',
            motivoInasistencia: null
        },
        {
            id: 102,
            pacienteId: 7,
            pacienteNombre: 'Laura Sofía Restrepo',
            pacienteTel: '3012345678',
            odontologoId: 3,
            odontologoNombre: 'Dra. Valeria Gómez',
            fecha: new Date().toISOString().split('T')[0],
            horaInicio: '10:00',
            horaFin: '10:45',
            servicios: ['Profilaxis (limpieza)'],
            duracionMin: 45,
            costo: 100000,
            estado: 'En Proceso',
            observaciones: 'Limpieza dental semestral.',
            motivoInasistencia: null
        },
        {
            id: 103,
            pacienteId: 6,
            pacienteNombre: 'Maria Padilla',
            pacienteTel: '3157894561',
            odontologoId: 3,
            odontologoNombre: 'Dra. Valeria Gómez',
            fecha: '2026-10-28',
            horaInicio: '14:00',
            horaFin: '14:45',
            servicios: ['Resina / calza dental'],
            duracionMin: 45,
            costo: 80000,
            estado: 'Atendido',
            observaciones: 'Restauración en resina en pieza 24 con excelente resultado.',
            motivoInasistencia: null
        },
        {
            id: 104,
            pacienteId: 7,
            pacienteNombre: 'Laura Sofía Restrepo',
            pacienteTel: '3012345678',
            odontologoId: 2,
            odontologoNombre: 'Dr. Carlos Mendoza',
            fecha: '2026-10-31',
            horaInicio: '11:00',
            horaFin: '12:30',
            servicios: ['Endodoncia'],
            duracionMin: 90,
            costo: 400000,
            estado: 'No Asistio',
            observaciones: 'Tratamiento de conducto programado.',
            motivoInasistencia: 'Paciente no se presentó transcurridos 15 minutos de tolerancia.'
        },
        {
            id: 105,
            pacienteId: 8,
            pacienteNombre: 'Andrés Felipe Murillo',
            pacienteTel: '3023456789',
            odontologoId: 1,
            odontologoNombre: 'Dra. Camila Morales',
            fecha: '2026-10-25',
            horaInicio: '15:00',
            horaFin: '15:30',
            servicios: ['Valoración general'],
            duracionMin: 30,
            costo: 30000,
            estado: 'No Asistio',
            observaciones: 'Segunda inasistencia no justificada.',
            motivoInasistencia: 'Ausencia injustificada. Cuenta suspendida para agendamiento web.'
        },
        {
            id: 106,
            pacienteId: 6,
            pacienteNombre: 'Maria Padilla',
            pacienteTel: '3157894561',
            odontologoId: 2,
            odontologoNombre: 'Dr. Carlos Mendoza',
            fecha: '2026-11-10',
            horaInicio: '16:00',
            horaFin: '17:15',
            servicios: ['Valoración general', 'Resina / calza dental'],
            duracionMin: 75,
            costo: 110000,
            estado: 'Solicitado',
            observaciones: 'Evaluación y posible calza.',
            motivoInasistencia: null
        }
    ],
    historial: [
        {
            id: 1,
            pacienteId: 6,
            fecha: '2026-10-28',
            tipo: 'Atencion Realizada',
            servicio: 'Resina / calza dental',
            doctor: 'Dra. Valeria Gómez',
            resumen: 'Procedimiento finalizado exitosamente. Obs: Restauración en resina en pieza 24 con excelente resultado.'
        },
        {
            id: 2,
            pacienteId: 7,
            fecha: '2026-10-31',
            tipo: 'Inasistencia Registrada',
            servicio: 'Endodoncia',
            doctor: 'Dr. Carlos Mendoza',
            resumen: 'Paciente no se presentó transcurridos 15 minutos de tolerancia reglamentaria. Se notificó formalmente por correo.'
        },
        {
            id: 3,
            pacienteId: 8,
            fecha: '2026-10-25',
            tipo: 'Inasistencia Registrada',
            servicio: 'Valoración general',
            doctor: 'Dra. Camila Morales',
            resumen: 'Segunda inasistencia acumulada. Restricción lógica aplicada al agendamiento web autónomo.'
        }
    ]
};

// Wizard State para Agendamiento
let BookingWizard = {
    currentStep: 1,
    selectedServices: [],
    selectedDoctorId: null,
    selectedDate: '',
    selectedTime: '',
    notes: '',
    calculatedDuration: 0,
    calculatedCost: 0
};

// Utilidad para notificaciones Toast con diseño suave, accesible y z-index prioritario
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    const colors = {
        success: 'bg-emerald-50/95 text-emerald-900 border-emerald-200/90 shadow-soft-lg',
        error: 'bg-rose-50/95 text-rose-900 border-rose-200/90 shadow-soft-lg',
        warning: 'bg-amber-50/95 text-amber-950 border-amber-200/90 shadow-soft-lg',
        info: 'bg-sky-50/95 text-sky-950 border-sky-200/90 shadow-soft-lg'
    };

    toast.className = `pointer-events-auto flex items-center gap-3 px-4 py-3.5 rounded-2xl border transition-all transform duration-300 -translate-y-2 opacity-0 text-sm font-medium ${colors[type] || colors.info}`;
    
    let iconSvg = AppIcons.info('w-5 h-5 text-sky-600 flex-shrink-0');
    if (type === 'success') iconSvg = AppIcons.checkCircle('w-5 h-5 text-emerald-600 flex-shrink-0');
    if (type === 'error') iconSvg = AppIcons.alertOctagon('w-5 h-5 text-rose-600 flex-shrink-0');
    if (type === 'warning') iconSvg = AppIcons.bell('w-5 h-5 text-amber-600 flex-shrink-0');

    toast.innerHTML = `
        <span class="flex-shrink-0">${iconSvg}</span>
        <div class="flex-1 leading-snug">${message}</div>
        <button onclick="this.parentElement.remove()" class="text-xs text-slate-400 hover:text-slate-700 font-bold ml-2 p-1.5 leading-none rounded-lg hover:bg-slate-200/50 transition" aria-label="Cerrar notificación">&times;</button>
    `;
    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('-translate-y-2', 'opacity-0');
    });

    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('opacity-0', '-translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }
    }, 4500);
}

// Alertas visuales internas dentro de los modales (en caso de requerirse en el cuerpo del diálogo)
function showWizardStepAlert(message, type = 'warning') {
    const alertBox = document.getElementById('wizard-step-alert');
    if (!alertBox) return;
    const bgClass = type === 'warning' ? 'bg-amber-50/95 border-amber-200 text-amber-950' : 'bg-rose-50/95 border-rose-200 text-rose-950';
    const icon = type === 'warning' ? AppIcons.bell('w-4 h-4 text-amber-600 flex-shrink-0') : AppIcons.alertOctagon('w-4 h-4 text-rose-600 flex-shrink-0');

    alertBox.innerHTML = `
        <div class="flex items-center gap-2.5 p-3.5 rounded-2xl border text-xs font-semibold ${bgClass} shadow-soft-sm">
            ${icon}
            <span class="flex-1">${message}</span>
            <button onclick="clearWizardStepAlert()" class="text-slate-400 hover:text-slate-700 font-bold px-1.5 py-0.5 rounded" aria-label="Cerrar alerta">&times;</button>
        </div>
    `;
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function clearWizardStepAlert() {
    const alertBox = document.getElementById('wizard-step-alert');
    if (alertBox) alertBox.innerHTML = '';
}

function showDoctorFinalizarAlert(message, type = 'warning') {
    const alertBox = document.getElementById('doctor-finalizar-alert');
    if (!alertBox) return;
    const bgClass = type === 'warning' ? 'bg-amber-50/95 border-amber-200 text-amber-950' : 'bg-rose-50/95 border-rose-200 text-rose-950';
    const icon = type === 'warning' ? AppIcons.bell('w-4 h-4 text-amber-600 flex-shrink-0') : AppIcons.alertOctagon('w-4 h-4 text-rose-600 flex-shrink-0');

    alertBox.innerHTML = `
        <div class="flex items-center gap-2.5 p-3.5 rounded-2xl border text-xs font-semibold ${bgClass} shadow-soft-sm">
            ${icon}
            <span class="flex-1">${message}</span>
            <button onclick="clearDoctorFinalizarAlert()" class="text-slate-400 hover:text-slate-700 font-bold px-1.5 py-0.5 rounded" aria-label="Cerrar alerta">&times;</button>
        </div>
    `;
}

function clearDoctorFinalizarAlert() {
    const alertBox = document.getElementById('doctor-finalizar-alert');
    if (alertBox) alertBox.innerHTML = '';
}

// Navegación entre Módulos del Prototipo
function navigateToModule(moduleId) {
    const modules = document.querySelectorAll('.app-module');
    modules.forEach(m => m.classList.add('hidden'));

    const target = document.getElementById(moduleId);
    if (target) {
        target.classList.remove('hidden');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Actualizar botones de navegación
    document.querySelectorAll('.nav-tab-btn').forEach(btn => {
        if (btn.dataset.target === moduleId) {
            btn.className = 'nav-tab-btn px-3.5 py-2 rounded-xl bg-primary-600 text-white text-xs md:text-sm font-semibold shadow-soft-sm flex items-center gap-2 transition';
        } else {
            btn.className = 'nav-tab-btn px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200/80 text-xs md:text-sm font-medium flex items-center gap-2 transition';
        }
    });

    // Disparar renders específicos
    if (moduleId === 'mod-paciente') renderPacientePortal();
    if (moduleId === 'mod-odontologo') renderOdontologoPortal();
    if (moduleId === 'mod-admin') renderAdminPortal();
}

// Switch de roles para demostración
function switchRoleDemo(roleType) {
    if (roleType === 'paciente-normal') {
        OdontoState.currentUser = {
            id: 6,
            nombre: 'Maria Padilla',
            correo: 'mariaepadilla@gmail.com',
            telefono: '3157894561',
            rol: 'paciente',
            faltas_acumuladas: 0,
            puedeAgendarWeb: true
        };
        showToast('Sesión activa: Maria Padilla (mariaepadilla@gmail.com) · Rol Paciente.', 'success');
        navigateToModule('mod-paciente');
    } else if (roleType === 'paciente-bloqueado') {
        OdontoState.currentUser = {
            id: 8,
            nombre: 'Andrés Felipe Murillo',
            correo: 'andres.murillo@gmail.com',
            telefono: '3023456789',
            rol: 'paciente',
            faltas_acumuladas: 2,
            puedeAgendarWeb: false
        };
        showToast('Sesión de prueba: Paciente con 2 faltas. Demostración de atención y validación en recepción.', 'warning');
        navigateToModule('mod-paciente');
    } else if (roleType === 'odontologo') {
        OdontoState.currentUser = {
            id: 3,
            nombre: 'Dra. Camila Morales',
            correo: 'dra.morales@dentalcare.com',
            telefono: '3209876543',
            rol: 'odontologo',
            especialidad: 'Ortodoncia y Ortopedia Maxilar',
            odontologoId: 1
        };
        showToast('Sesión de prueba: Dra. Camila Morales (Odontóloga Especialista).', 'info');
        navigateToModule('mod-odontologo');
    } else if (roleType === 'admin') {
        OdontoState.currentUser = {
            id: 1,
            nombre: 'Administración y Recepción',
            correo: 'contacto@dentalcare.com',
            telefono: '3104567890',
            rol: 'admin'
        };
        showToast('Sesión de prueba: Panel Administrativo y Recepción DENTAL CARE.', 'info');
        navigateToModule('mod-admin');
    }
    updateUserBadge();
}

function updateUserBadge() {
    const badge = document.getElementById('current-user-badge');
    if (badge) {
        badge.innerHTML = `
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary-50 text-primary-800 border border-primary-200/80">
                <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                ${OdontoState.currentUser.nombre} <span class="text-primary-600 font-normal">(${OdontoState.currentUser.rol})</span>
            </span>
        `;
    }
}

// -------------------------------------------------------------
// PORTAL DEL PACIENTE
// -------------------------------------------------------------
function renderPacientePortal() {
    const user = OdontoState.currentUser;
    const nameEl = document.getElementById('pac-user-name');
    if (nameEl) nameEl.textContent = user.nombre;

    // Banner de advertencia si tiene faltas (tono empático, cordial y no agresivo)
    const alertBox = document.getElementById('pac-alert-box');
    if (alertBox) {
        if (user.faltas_acumuladas >= 2) {
            alertBox.innerHTML = `
                <div class="p-5 rounded-2xl bg-rose-50/90 border border-rose-200/90 text-rose-950 flex items-start gap-4 shadow-soft-sm">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                        ${AppIcons.info("w-5 h-5")}
                    </div>
                    <div class="space-y-1.5 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-200/70 text-rose-800 uppercase tracking-wide">Validación Asistida</span>
                            <h4 class="font-heading font-bold text-sm text-rose-950">Atención personalizada para agendar tu próxima cita</h4>
                        </div>
                        <p class="text-xs text-rose-800/90 leading-relaxed">
                            Estimado(a) <strong>${user.nombre}</strong>, para cuidar la continuidad de tu plan de tratamiento dental y la disponibilidad de los consultorios, tu cuenta registra <strong>${user.faltas_acumuladas} inasistencias previas</strong> sin cancelación oportuna. Con el mayor gusto te atenderemos presencialmente en nuestra sede de Cali o mediante nuestra línea directa <strong>(602) 555-0199</strong> para confirmar tu cupo con el especialista.
                        </p>
                    </div>
                </div>
            `;
            const bookBtn = document.getElementById('btn-start-booking');
            if (bookBtn) {
                bookBtn.classList.add('opacity-50', 'cursor-not-allowed');
                bookBtn.disabled = true;
                bookBtn.title = 'Agendamiento web requiere validación en recepción';
            }
        } else if (user.faltas_acumuladas === 1) {
            alertBox.innerHTML = `
                <div class="p-4 rounded-2xl bg-amber-50/90 border border-amber-200/90 text-amber-950 flex items-start gap-3.5 shadow-soft-sm">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                        ${AppIcons.bell("w-4 h-4")}
                    </div>
                    <div class="space-y-1 flex-1">
                        <h4 class="font-heading font-semibold text-xs text-amber-950">Recordatorio Preventivo de Asistencia</h4>
                        <p class="text-xs text-amber-900/90 leading-relaxed">
                            Cuentas con 1 inasistencia previa en tu registro. Recuerda que puedes cancelar o reagendar tu cita con al menos 2 horas de anticipación desde este portal sin ninguna penalidad.
                        </p>
                    </div>
                </div>
            `;
        } else {
            alertBox.innerHTML = '';
            const bookBtn = document.getElementById('btn-start-booking');
            if (bookBtn) {
                bookBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                bookBtn.disabled = false;
            }
        }
    }

    // Próxima cita del paciente
    const proximaCita = OdontoState.citas
        .filter(c => c.pacienteId === user.id && ['Solicitado', 'Asignado'].includes(c.estado))
        .sort((a, b) => new Date(a.fecha + 'T' + a.horaInicio) - new Date(b.fecha + 'T' + b.horaInicio))[0];

    const nextCard = document.getElementById('pac-next-appointment');
    if (nextCard) {
        if (proximaCita) {
            const estadoBadge = proximaCita.estado === 'Asignado' 
                ? '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">' + AppIcons.checkCircle("w-3.5 h-3.5 text-emerald-600") + 'Confirmada (Cupo Asignado)</span>'
                : '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-800 border border-sky-200">' + AppIcons.clock("w-3.5 h-3.5 text-sky-600") + 'Solicitada (En Revisión)</span>';

            nextCard.innerHTML = `
                <div class="bg-gradient-to-br from-primary-50/70 via-white to-secondary-50/50 border border-primary-200/80 rounded-2xl p-6 shadow-soft-sm space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary-100 text-primary-700 flex items-center justify-center flex-shrink-0">
                                ${AppIcons.calendar("w-5 h-5")}
                            </div>
                            <div>
                                <span class="text-xs font-semibold uppercase tracking-wider text-primary-700">Tu Próxima Atención</span>
                                <h3 class="font-heading font-bold text-secondary-900 text-lg leading-tight">Consulta Médica Programada</h3>
                            </div>
                        </div>
                        ${estadoBadge}
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-slate-700 bg-white/90 p-4 rounded-xl border border-primary-100/80 shadow-soft-sm">
                        <div>
                            <span class="text-[11px] text-slate-400 block uppercase font-medium">Fecha y Franja</span>
                            <strong class="text-sm font-semibold text-secondary-900 mt-0.5 block">${proximaCita.fecha} • ${proximaCita.horaInicio} - ${proximaCita.horaFin}</strong>
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 block uppercase font-medium">Especialista a Cargo</span>
                            <strong class="text-sm font-semibold text-secondary-900 mt-0.5 block">${proximaCita.odontologoNombre}</strong>
                        </div>
                        <div>
                            <span class="text-[11px] text-slate-400 block uppercase font-medium">Tratamiento Clínico</span>
                            <strong class="text-sm font-semibold text-primary-700 mt-0.5 block">${proximaCita.servicios.join(', ')}</strong>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                        <div class="text-xs text-slate-500 flex items-center gap-2">
                            ${AppIcons.clock("w-4 h-4 text-primary-600 flex-shrink-0")}
                            <span>Por favor presentarse con 10 min de anticipación. Tolerancia de cortesía: 15 min.</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button onclick="abrirModalNotificarCita(${proximaCita.id})" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-secondary-800 hover:bg-secondary-900 text-white text-xs font-semibold shadow-soft-xs transition" title="Enviar recordatorio por correo a mariaepadilla@gmail.com">
                                ${AppIcons.mail("w-3.5 h-3.5 text-primary-300")}
                                <span>Enviar Notificación</span>
                            </button>
                            <button onclick="handleCancelarCita(${proximaCita.id})" class="px-3.5 py-1.5 rounded-xl border border-slate-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold transition">
                                Cancelar
                            </button>
                            <button onclick="handleReagendarCita(${proximaCita.id})" class="px-4 py-1.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold shadow-soft-sm transition">
                                Reagendar Cita
                            </button>
                        </div>
                    </div>
                </div>
            `;
        } else {
            nextCard.innerHTML = `
                <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center text-slate-500 shadow-soft-sm">
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 text-primary-600 mx-auto flex items-center justify-center mb-3">
                        ${AppIcons.tooth("w-7 h-7")}
                    </div>
                    <h4 class="font-heading font-semibold text-slate-800 text-base">No tienes citas programadas actualmente</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">Selecciona tus tratamientos odontológicos y reserva en línea en tan solo 4 pasos con Odonto Agenda.</p>
                </div>
            `;
        }
    }

    // Renderizar Mis Citas
    renderMisCitasTable();
    // Renderizar Mi Historial
    renderMiHistorialList();
}

function renderMisCitasTable() {
    const listEl = document.getElementById('pac-citas-list');
    if (!listEl) return;

    const user = OdontoState.currentUser;
    const userCitas = OdontoState.citas
        .filter(c => c.pacienteId === user.id)
        .sort((a, b) => new Date(b.fecha) - new Date(a.fecha));

    if (userCitas.length === 0) {
        listEl.innerHTML = `<tr><td colspan="6" class="text-center py-8 text-slate-400 text-xs">No registras citas odontológicas en tu cuenta actualmente.</td></tr>`;
        return;
    }

    listEl.innerHTML = userCitas.map(c => {
        let badge = '';
        if (c.estado === 'Solicitado') badge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-800 border border-sky-200">' + AppIcons.clock("w-3 h-3 text-sky-600") + 'Solicitado</span>';
        else if (c.estado === 'Asignado') badge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">' + AppIcons.check("w-3 h-3 text-emerald-600") + 'Confirmada</span>';
        else if (c.estado === 'En Proceso') badge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-800 border border-purple-200 animate-pulse"><span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>En Sillón</span>';
        else if (c.estado === 'Atendido') badge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200">' + AppIcons.check("w-3 h-3 text-teal-600") + 'Atendido</span>';
        else if (c.estado === 'Cancelado') badge = '<span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">Cancelado</span>';
        else if (c.estado === 'No Asistio') badge = '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-300">' + AppIcons.alertTriangle("w-3 h-3 text-amber-700") + 'No Asistió</span>';

        let acciones = '';
        if (['Solicitado', 'Asignado'].includes(c.estado)) {
            acciones = `
                <div class="flex items-center gap-1.5">
                    <button onclick="abrirModalNotificarCita(${c.id})" class="inline-flex items-center gap-1 px-2.5 py-1 text-primary-700 bg-primary-50 hover:bg-primary-100 rounded-lg text-xs font-semibold border border-primary-200/60 transition" title="Enviar notificación y recordatorio a mariaepadilla@gmail.com">
                        ${AppIcons.mail("w-3 h-3 text-primary-600")}
                        <span>Notificar</span>
                    </button>
                    <button onclick="handleCancelarCita(${c.id})" class="px-2 py-1 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-semibold transition">Cancelar</button>
                    <button onclick="handleReagendarCita(${c.id})" class="px-2 py-1 text-secondary-700 hover:bg-secondary-50 rounded-lg text-xs font-semibold transition">Reagendar</button>
                </div>
            `;
        } else if (c.estado === 'No Asistio') {
            acciones = `
                <button onclick="handleReprogramarNoAsistida(${c.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 text-xs font-bold rounded-xl shadow-soft-sm transition">
                    ${AppIcons.calendar("w-3.5 h-3.5 text-amber-700")}
                    Reprogramar
                </button>
            `;
        } else {
            acciones = `<span class="text-slate-400 text-xs font-medium">Cerrada</span>`;
        }

        return `
            <tr class="border-b border-slate-100/80 hover:bg-slate-50/80 transition text-xs">
                <td class="py-3.5 px-4 font-semibold text-slate-900">#${c.id}</td>
                <td class="py-3.5 px-4 text-slate-700">
                    <div class="font-semibold text-slate-900">${c.fecha}</div>
                    <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                        ${AppIcons.clock("w-3 h-3 text-slate-400")}
                        ${c.horaInicio} - ${c.horaFin} (${c.duracionMin} min)
                    </div>
                </td>
                <td class="py-3.5 px-4 font-medium text-slate-800">${c.odontologoNombre}</td>
                <td class="py-3.5 px-4 text-slate-600">${c.servicios.join(', ')}</td>
                <td class="py-3.5 px-4">${badge}</td>
                <td class="py-3.5 px-4">${acciones}</td>
            </tr>
        `;
    }).join('');
}

function renderMiHistorialList() {
    const histEl = document.getElementById('pac-historial-list');
    if (!histEl) return;

    const user = OdontoState.currentUser;
    const items = OdontoState.historial.filter(h => h.pacienteId === user.id);

    if (items.length === 0) {
        histEl.innerHTML = `<div class="p-6 text-center text-slate-400 text-sm">No registra eventos históricos de atención ni inasistencias.</div>`;
        return;
    }

    histEl.innerHTML = items.map(h => {
        const isAtendido = h.tipo === 'Atencion Realizada';
        const iconSvg = isAtendido ? AppIcons.stethoscope("w-4 h-4 text-emerald-700") : AppIcons.alertTriangle("w-4 h-4 text-amber-700");
        const colorClass = isAtendido ? 'border-l-4 border-emerald-500 bg-emerald-50/30' : 'border-l-4 border-amber-500 bg-amber-50/30';
        const badge = isAtendido
            ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-800">' + AppIcons.check("w-3 h-3") + 'Atención Realizada</span>'
            : '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-900">' + AppIcons.alertTriangle("w-3 h-3") + 'Inasistencia Registrada</span>';

        return `
            <div class="p-4 rounded-xl border border-slate-200 ${colorClass} mb-3 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center">
                            ${iconSvg}
                        </div>
                        <strong class="text-slate-900 text-sm">${h.servicio}</strong>
                        <span class="text-xs text-slate-500">• ${h.doctor}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-slate-600 font-medium">${h.fecha}</span>
                        ${badge}
                    </div>
                </div>
                <p class="text-xs text-slate-700">${h.resumen}</p>
            </div>
        `;
    }).join('');
}

// -------------------------------------------------------------
// ASISTENTE DE AGENDAMIENTO EN 4 PASOS
// -------------------------------------------------------------
function openBookingWizard() {
    clearWizardStepAlert();
    if (!OdontoState.currentUser.puedeAgendarWeb) {
        showToast('Restricción activa: No puede agendar en línea debido a 2 inasistencias consecutivas (Regla 7).', 'error');
        return;
    }

    BookingWizard = {
        currentStep: 1,
        selectedServices: [],
        selectedDoctorId: null,
        selectedDate: new Date().toISOString().split('T')[0],
        selectedTime: '',
        notes: '',
        calculatedDuration: 0,
        calculatedCost: 0
    };

    renderWizardStep();
    const modal = document.getElementById('booking-modal');
    if (modal) modal.classList.remove('hidden');
}

function closeBookingWizard() {
    clearWizardStepAlert();
    const modal = document.getElementById('booking-modal');
    if (modal) modal.classList.add('hidden');
}

function renderWizardStep() {
    clearWizardStepAlert();
    const content = document.getElementById('wizard-step-content');
    const stepTitle = document.getElementById('wizard-step-title');
    const stepNumber = document.getElementById('wizard-step-number');
    const footerSummary = document.getElementById('wizard-footer-summary');
    const nextBtn = document.getElementById('wizard-btn-next');
    const prevBtn = document.getElementById('wizard-btn-prev');

    if (!content) return;

    if (stepNumber) stepNumber.textContent = `Paso ${BookingWizard.currentStep} de 4`;

    // Paso 1: Selección de Servicios
    if (BookingWizard.currentStep === 1) {
        stepTitle.textContent = 'Seleccione los Tratamientos Requeridos';
        prevBtn.classList.add('hidden');
        nextBtn.innerHTML = `Continuar a Odontólogo ${AppIcons.arrowRight("w-4 h-4 inline-block ml-1")}`;

        content.innerHTML = `
            <p class="text-xs text-slate-500 mb-4">Puede seleccionar uno o varios tratamientos. El sistema sumará la duración requerida de forma aditiva (Regla 2).</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-96 overflow-y-auto pr-1">
                ${OdontoState.servicios.map(s => {
                    const checked = BookingWizard.selectedServices.includes(s.id) ? 'checked' : '';
                    return `
                        <label class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-teal-500 hover:bg-teal-50/20 cursor-pointer transition">
                            <input type="checkbox" value="${s.id}" ${checked} onchange="toggleWizardService(${s.id})" class="mt-1 w-4 h-4 text-teal-600 rounded focus:ring-teal-500">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <strong class="text-sm text-slate-900">${s.nombre}</strong>
                                    <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded">$${s.precio.toLocaleString('es-CO')}</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">${s.desc}</p>
                                <span class="inline-flex items-center gap-1 mt-1 text-[11px] font-semibold text-slate-600">
                                    ${AppIcons.clock("w-3 h-3 text-slate-500")}
                                    Duración: ${s.duracion} min
                                </span>
                            </div>
                        </label>
                    `;
                }).join('')}
            </div>
        `;
    }

    // Paso 2: Selección de Odontólogo
    else if (BookingWizard.currentStep === 2) {
        stepTitle.textContent = 'Seleccione el Odontólogo Especialista';
        prevBtn.classList.remove('hidden');
        nextBtn.innerHTML = `Continuar a Fecha y Hora ${AppIcons.arrowRight("w-4 h-4 inline-block ml-1")}`;

        content.innerHTML = `
            <p class="text-xs text-slate-500 mb-4">Elija el profesional médico de su preferencia para la atención:</p>
            <div class="grid grid-cols-1 gap-3">
                ${OdontoState.odontologos.map(o => {
                    const isSelected = BookingWizard.selectedDoctorId === o.id;
                    const borderClass = isSelected ? 'border-teal-600 bg-teal-50/40 shadow-sm ring-2 ring-teal-500' : 'border-slate-200 hover:border-teal-400';
                    return `
                        <div onclick="selectWizardDoctor(${o.id})" class="flex items-center gap-4 p-4 rounded-xl border ${borderClass} cursor-pointer transition">
                            <div class="w-11 h-11 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center flex-shrink-0">
                                ${AppIcons.stethoscope("w-6 h-6")}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-slate-900 text-sm">${o.nombre}</h4>
                                <p class="text-xs text-teal-700 font-semibold">${o.especialidad}</p>
                                <span class="text-[11px] text-slate-400">Reg: ${o.registro} • Horario: Lunes a Sábado</span>
                            </div>
                            <span class="inline-flex items-center gap-1 text-xs font-bold ${isSelected ? 'text-teal-700' : 'text-slate-400'}">
                                ${isSelected ? AppIcons.check("w-3.5 h-3.5") + 'Seleccionado' : 'Elegir'}
                            </span>
                        </div>
                    `;
                }).join('')}
            </div>
        `;
    }

    // Paso 3: Selección de Fecha y Hora
    else if (BookingWizard.currentStep === 3) {
        stepTitle.textContent = 'Seleccione Fecha y Franja Horaria';
        prevBtn.classList.remove('hidden');
        nextBtn.innerHTML = `Revisar Resumen ${AppIcons.arrowRight("w-4 h-4 inline-block ml-1")}`;

        const doctor = OdontoState.odontologos.find(o => o.id === BookingWizard.selectedDoctorId);

        // Generar horas disponibles
        const horasPosibles = [
            '08:00', '08:45', '09:30', '10:15', '11:00',
            '14:00', '14:45', '15:30', '16:15', '17:00'
        ];

        content.innerHTML = `
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Fecha de la Consulta</label>
                <input type="date" value="${BookingWizard.selectedDate}" onchange="changeWizardDate(this.value)" min="${new Date().toISOString().split('T')[0]}" class="w-full px-3 py-2 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-teal-500">
            </div>

            <label class="block text-xs font-semibold text-slate-700 mb-2">
                Horarios Disponibles para ${doctor ? doctor.nombre : 'el Odontólogo'} 
                <span class="text-slate-400 font-normal">(Duración requerida: ${BookingWizard.calculatedDuration} min)</span>
            </label>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto p-1">
                ${horasPosibles.map(hora => {
                    // Validar solapamiento con citas existentes
                    const isOccupied = checkDoctorCollision(BookingWizard.selectedDoctorId, BookingWizard.selectedDate, hora, BookingWizard.calculatedDuration);
                    const isSelected = BookingWizard.selectedTime === hora;

                    if (isOccupied) {
                        return `
                            <button disabled class="p-2.5 rounded-xl border border-rose-200 bg-rose-50 text-rose-400 text-xs font-medium cursor-not-allowed text-center">
                                <span class="line-through">${hora}</span>
                                <span class="block text-[10px] text-rose-500">Ocupado</span>
                            </button>
                        `;
                    }

                    const activeClass = isSelected ? 'bg-teal-600 text-white font-bold shadow-md' : 'border border-slate-200 hover:border-teal-500 hover:bg-teal-50 text-slate-800';
                    return `
                        <button type="button" onclick="selectWizardTime('${hora}')" class="p-2.5 rounded-xl text-xs font-semibold text-center transition ${activeClass}">
                            <span>${hora}</span>
                            <span class="block text-[10px] ${isSelected ? 'text-teal-100' : 'text-emerald-600'}">Disponible</span>
                        </button>
                    `;
                }).join('')}
            </div>

            <div class="mt-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Observaciones / Motivo de la Cita (Opcional)</label>
                <textarea oninput="BookingWizard.notes = this.value" placeholder="Indique si siente dolor, molestia o detalles adicionales..." class="w-full px-3 py-2 border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-teal-500" rows="2">${BookingWizard.notes}</textarea>
            </div>
        `;
    }

    // Paso 4: Confirmación y Resumen
    else if (BookingWizard.currentStep === 4) {
        stepTitle.textContent = 'Confirmación de la Solicitud';
        prevBtn.classList.remove('hidden');
        nextBtn.innerHTML = `Confirmar y Agendar Cita ${AppIcons.check("w-4 h-4 inline-block ml-1")}`;

        const doctor = OdontoState.odontologos.find(o => o.id === BookingWizard.selectedDoctorId);
        const servNames = OdontoState.servicios.filter(s => BookingWizard.selectedServices.includes(s.id)).map(s => s.nombre);

        // Calcular hora fin
        const horaFin = calculateEndTime(BookingWizard.selectedTime, BookingWizard.calculatedDuration);

        content.innerHTML = `
            <div class="bg-gradient-to-br from-teal-50 to-emerald-50 p-5 rounded-2xl border border-teal-200 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                        ${AppIcons.clipboardList("w-5 h-5")}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-base">Resumen de su Cita Odontológica</h4>
                        <p class="text-xs text-slate-600">Por favor verifique la información antes de enviar la confirmación.</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-xl border border-teal-100 space-y-2.5 text-xs text-slate-700">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Paciente:</span>
                        <strong class="text-slate-900">${OdontoState.currentUser.nombre}</strong>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Odontólogo:</span>
                        <strong class="text-slate-900">${doctor.nombre} (${doctor.especialidad})</strong>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Fecha y Horario:</span>
                        <strong class="text-slate-900">${BookingWizard.selectedDate} • ${BookingWizard.selectedTime} a ${horaFin}</strong>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Duración Estimada Total:</span>
                        <strong class="text-teal-700">${BookingWizard.calculatedDuration} minutos (Cálculo Aditivo)</strong>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-500 font-medium">Tratamientos:</span>
                        <strong class="text-slate-900">${servNames.join(', ')}</strong>
                    </div>
                    <div class="flex justify-between py-1 pt-2">
                        <span class="text-slate-700 font-bold text-sm">Total Estimado:</span>
                        <strong class="text-teal-800 text-sm font-extrabold">$${BookingWizard.calculatedCost.toLocaleString('es-CO')} COP</strong>
                    </div>
                </div>

                <div class="bg-amber-50 p-3 rounded-xl border border-amber-200 text-amber-900 text-xs flex items-start gap-2.5">
                    <div class="p-1 rounded-md bg-amber-100 text-amber-800 flex-shrink-0 mt-0.5">
                        ${AppIcons.bell("w-4 h-4")}
                    </div>
                    <div>
                        <strong>Recordatorio de Asistencia y Tolerancia:</strong>
                        <p class="mt-0.5">Debe presentarse con 10 minutos de anticipación. Se otorga una tolerancia de espera de 15 minutos; superado este tiempo la cita se marcará como 'No Asistió' y sumará una falta.</p>
                    </div>
                </div>
            </div>
        `;
    }

    // Actualizar barra resumen inferior
    if (footerSummary) {
        footerSummary.innerHTML = `
            <div class="text-xs text-slate-600 flex items-center gap-3">
                <span class="inline-flex items-center gap-1">
                    ${AppIcons.clock("w-3.5 h-3.5 text-slate-500")}
                    Duración: <strong class="text-slate-900">${BookingWizard.calculatedDuration} min</strong>
                </span>
                <span class="text-slate-300">•</span>
                <span class="inline-flex items-center gap-1">
                    ${AppIcons.banknote("w-3.5 h-3.5 text-teal-700")}
                    Costo: <strong class="text-teal-800">$${BookingWizard.calculatedCost.toLocaleString('es-CO')}</strong>
                </span>
            </div>
        `;
    }
}

function toggleWizardService(servId) {
    const idx = BookingWizard.selectedServices.indexOf(servId);
    if (idx > -1) {
        BookingWizard.selectedServices.splice(idx, 1);
    } else {
        BookingWizard.selectedServices.push(servId);
    }

    // Recalcular aditivamente tiempo y costo
    const selected = OdontoState.servicios.filter(s => BookingWizard.selectedServices.includes(s.id));
    BookingWizard.calculatedDuration = selected.reduce((acc, curr) => acc + curr.duracion, 0);
    BookingWizard.calculatedCost = selected.reduce((acc, curr) => acc + curr.precio, 0);

    renderWizardStep();
}

function selectWizardDoctor(docId) {
    BookingWizard.selectedDoctorId = docId;
    renderWizardStep();
}

function changeWizardDate(newDate) {
    BookingWizard.selectedDate = newDate;
    BookingWizard.selectedTime = '';
    renderWizardStep();
}

function selectWizardTime(hora) {
    BookingWizard.selectedTime = hora;
    renderWizardStep();
}

function wizardNext() {
    clearWizardStepAlert();
    if (BookingWizard.currentStep === 1) {
        if (BookingWizard.selectedServices.length === 0) {
            const msg = 'Debe seleccionar al menos un servicio odontológico.';
            showToast(msg, 'warning');
            showWizardStepAlert(msg, 'warning');
            return;
        }
        BookingWizard.currentStep = 2;
    } else if (BookingWizard.currentStep === 2) {
        if (!BookingWizard.selectedDoctorId) {
            const msg = 'Por favor seleccione un odontólogo especialista.';
            showToast(msg, 'warning');
            showWizardStepAlert(msg, 'warning');
            return;
        }
        BookingWizard.currentStep = 3;
    } else if (BookingWizard.currentStep === 3) {
        if (!BookingWizard.selectedDate) {
            const msg = 'Seleccione la fecha de la cita.';
            showToast(msg, 'warning');
            showWizardStepAlert(msg, 'warning');
            return;
        }
        if (!BookingWizard.selectedTime) {
            const msg = 'Por favor seleccione una hora disponible.';
            showToast(msg, 'warning');
            showWizardStepAlert(msg, 'warning');
            return;
        }
        BookingWizard.currentStep = 4;
    } else if (BookingWizard.currentStep === 4) {
        // Enviar y Confirmar la Cita
        submitWizardBooking();
        return;
    }

    renderWizardStep();
}

function wizardPrev() {
    if (BookingWizard.currentStep > 1) {
        BookingWizard.currentStep--;
        renderWizardStep();
    }
}

function submitWizardBooking() {
    const doctor = OdontoState.odontologos.find(o => o.id === BookingWizard.selectedDoctorId);
    const servNames = OdontoState.servicios.filter(s => BookingWizard.selectedServices.includes(s.id)).map(s => s.nombre);
    const horaFin = calculateEndTime(BookingWizard.selectedTime, BookingWizard.calculatedDuration);

    const nuevaCita = {
        id: 100 + OdontoState.citas.length + 1,
        pacienteId: OdontoState.currentUser.id,
        pacienteNombre: OdontoState.currentUser.nombre,
        pacienteTel: OdontoState.currentUser.telefono,
        odontologoId: doctor.id,
        odontologoNombre: doctor.nombre,
        fecha: BookingWizard.selectedDate,
        horaInicio: BookingWizard.selectedTime,
        horaFin: horaFin,
        servicios: servNames,
        duracionMin: BookingWizard.calculatedDuration,
        costo: BookingWizard.calculatedCost,
        estado: 'Solicitado',
        observaciones: BookingWizard.notes || 'Solicitud generada vía portal web del paciente.',
        motivoInasistencia: null
    };

    OdontoState.citas.unshift(nuevaCita);

    closeBookingWizard();
    showToast(`¡Cita #${nuevaCita.id} solicitada con éxito! Se envió notificación de confirmación al correo ${OdontoState.currentUser.correo}.`, 'success');
    renderPacientePortal();
}

function checkDoctorCollision(doctorId, fecha, horaInicioStr, duracionMin) {
    if (!doctorId || !fecha || !horaInicioStr) return false;

    const startMin = timeToMinutes(horaInicioStr);
    const endMin = startMin + duracionMin;

    return OdontoState.citas.some(c => {
        if (c.odontologoId !== doctorId) return false;
        if (c.fecha !== fecha) return false;
        if (['Cancelado', 'No Asistio'].includes(c.estado)) return false;

        const cStart = timeToMinutes(c.horaInicio);
        const cEnd = timeToMinutes(c.horaFin);

        // Cruce de intervalos
        return (startMin < cEnd && endMin > cStart);
    });
}

function timeToMinutes(timeStr) {
    const [h, m] = timeStr.split(':').map(Number);
    return h * 60 + m;
}

function minutesToTime(mins) {
    const h = Math.floor(mins / 60).toString().padStart(2, '0');
    const m = (mins % 60).toString().padStart(2, '0');
    return `${h}:${m}`;
}

function calculateEndTime(startTimeStr, durationMin) {
    const total = timeToMinutes(startTimeStr) + durationMin;
    return minutesToTime(total);
}

// -------------------------------------------------------------
// REGLAS DE NEGOCIO: CANCELAR Y REPROGRAMAR
// -------------------------------------------------------------
function handleCancelarCita(citaId) {
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;

    // Regla 3: Mínimo 2 horas de anticipación
    const now = new Date();
    const citaDateTime = new Date(`${cita.fecha}T${cita.horaInicio}:00`);
    const diffHours = (citaDateTime - now) / (1000 * 60 * 60);

    // Si la cita es de hoy o futura
    if (diffHours < 2 && diffHours > -100) {
        const confirmar = confirm(`ALERTA DE POLÍTICA (Regla 3):\nFaltan menos de 2 horas para la cita programada (${diffHours.toFixed(1)} horas restantes).\n\nCancelar con menos de 2 horas genera un perjuicio operativo para la clínica.\n¿Desea solicitar la cancelación de emergencia de todas formas?`);
        if (!confirmar) return;
    } else {
        const confirmar = confirm(`¿Está seguro de cancelar la cita #${cita.id} con ${cita.odontologoNombre}?\nLa franja horaria será liberada inmediatamente.`);
        if (!confirmar) return;
    }

    cita.estado = 'Cancelado';
    cita.observaciones = (cita.observaciones ? cita.observaciones + ' | ' : '') + 'Cancelada voluntariamente por el paciente.';

    showToast(`Cita #${cita.id} cancelada. Notificación de cancelación enviada a ${OdontoState.currentUser.correo}.`, 'info');
    renderPacientePortal();
}

function handleReagendarCita(citaId) {
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;

    const nuevaFecha = prompt(`Reagendar Cita #${cita.id}\nIngrese la nueva fecha (AAAA-MM-DD):`, new Date().toISOString().split('T')[0]);
    if (!nuevaFecha) return;

    const nuevaHora = prompt(`Ingrese la nueva hora de inicio (HH:MM):`, '14:00');
    if (!nuevaHora) return;

    // Validar solapamiento
    const collision = checkDoctorCollision(cita.odontologoId, nuevaFecha, nuevaHora, cita.duracionMin);
    if (collision) {
        showToast('Error de solapamiento: El odontólogo ya tiene una cita reservada en ese horario.', 'error');
        return;
    }

    cita.fecha = nuevaFecha;
    cita.horaInicio = nuevaHora;
    cita.horaFin = calculateEndTime(nuevaHora, cita.duracionMin);
    cita.estado = 'Solicitado';

    showToast(`Cita #${cita.id} reprogramada con éxito. Notificación con nuevo horario enviada a ${OdontoState.currentUser.correo}.`, 'success');
    renderPacientePortal();
}

// -------------------------------------------------------------
// GESTIÓN DE NOTIFICACIONES DE CITAS (MARIA PADILLA)
// -------------------------------------------------------------
let activeNotifCitaId = null;

function abrirModalNotificarCita(citaId) {
    activeNotifCitaId = citaId;
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;

    const modal = document.getElementById('notificacion-modal');
    if (!modal) return;

    const pacienteNombreEl = document.getElementById('notif-paciente-nombre');
    const pacienteCorreoEl = document.getElementById('notif-paciente-correo');
    if (pacienteNombreEl) pacienteNombreEl.textContent = OdontoState.currentUser.nombre;
    if (pacienteCorreoEl) pacienteCorreoEl.textContent = OdontoState.currentUser.correo;

    actualizarPreviewNotificacion(cita);
    modal.classList.remove('hidden');
}

function cerrarModalNotificarCita() {
    activeNotifCitaId = null;
    const modal = document.getElementById('notificacion-modal');
    if (modal) modal.classList.add('hidden');
}

function actualizarPreviewNotificacion(cita) {
    const previewBox = document.getElementById('notif-preview-box');
    if (!previewBox || !cita) return;

    const user = OdontoState.currentUser;
    previewBox.innerHTML = `
        <div class="border-b border-slate-200/80 pb-2 mb-2">
            <div><strong class="text-slate-800">De:</strong> DENTAL CARE Notificaciones &lt;citas@dentalcare.com.co&gt;</div>
            <div><strong class="text-slate-800">Para:</strong> ${user.nombre} &lt;${user.correo}&gt;</div>
            <div><strong class="text-slate-800">Asunto:</strong> [DENTAL CARE] Recordatorio de tu Cita Odontológica #${cita.id}</div>
        </div>
        <div class="space-y-1.5 leading-relaxed text-[11px] text-slate-700">
            <p>Estimada <strong>${user.nombre}</strong>,</p>
            <p>Te recordamos los datos de tu próxima cita odontológica en DENTAL CARE Cali:</p>
            <ul class="list-disc pl-4 space-y-0.5 text-slate-800">
                <li><strong>Fecha:</strong> ${cita.fecha} (${cita.horaInicio} - ${cita.horaFin})</li>
                <li><strong>Especialista:</strong> ${cita.odontologoNombre}</li>
                <li><strong>Tratamiento:</strong> ${cita.servicios.join(', ')}</li>
                <li><strong>Sede:</strong> DENTAL CARE · Calle 5 # 38-25, Cali, Colombia</li>
            </ul>
            <p class="text-[10px] text-slate-500 pt-1 border-t border-slate-200/60 mt-1">
                * Por favor presentarse con 10 minutos de anticipación. Tolerancia máxima de espera: 15 minutos. Cancelaciones permitidas hasta 2 horas antes desde tu portal.
            </p>
        </div>
    `;
}

function enviarNotificacionCita() {
    if (!activeNotifCitaId) return;
    const cita = OdontoState.citas.find(c => c.id === activeNotifCitaId);
    if (!cita) return;

    const user = OdontoState.currentUser;
    const sendBtn = document.getElementById('btn-enviar-notificacion');
    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.innerHTML = `
            <svg class="w-3.5 h-3.5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            <span>Enviando notificación...</span>
        `;
    }

    setTimeout(() => {
        if (sendBtn) {
            sendBtn.disabled = false;
            sendBtn.innerHTML = `
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                <span>Enviar Notificación a Maria Padilla</span>
            `;
        }
        cerrarModalNotificarCita();
        showToast(`¡Notificación enviada con éxito! Se despachó el recordatorio oficial de la cita #${cita.id} a ${user.correo}.`, 'success');
    }, 500);
}

function enviarNotificacionRapida(citaId) {
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;
    const user = OdontoState.currentUser;
    showToast(`Comprobante y recordatorio de la cita #${cita.id} despachado exitosamente a ${user.correo}.`, 'success');
}

function handleReprogramarNoAsistida(citaId) {
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;

    // Abre el wizard con los mismos tratamientos
    openBookingWizard();
    BookingWizard.selectedDoctorId = cita.odontologoId;
    showToast(`Iniciando reprogramación de la cita no asistida #${cita.id}.`, 'info');
}

// -------------------------------------------------------------
// PORTAL DEL ODONTÓLOGO
// -------------------------------------------------------------
function renderOdontologoPortal() {
    const user = OdontoState.currentUser;
    const docNameEl = document.getElementById('odo-doctor-name');
    if (docNameEl) docNameEl.textContent = user.nombre;

    const dateInput = document.getElementById('odo-agenda-date');
    const selectedDate = dateInput ? dateInput.value || new Date().toISOString().split('T')[0] : new Date().toISOString().split('T')[0];
    if (dateInput && !dateInput.value) dateInput.value = selectedDate;

    // Buscar el id del odontólogo actual
    const currentDoctor = OdontoState.odontologos.find(o => o.nombre.includes(user.nombre.split(' ')[1] || 'Morales')) || OdontoState.odontologos[0];

    const citasDoctor = OdontoState.citas
        .filter(c => c.odontologoId === currentDoctor.id && c.fecha === selectedDate)
        .sort((a, b) => timeToMinutes(a.horaInicio) - timeToMinutes(b.horaInicio));

    // Métricas del día
    const mTotal = document.getElementById('odo-m-total');
    const mPend = document.getElementById('odo-m-pendientes');
    const mProc = document.getElementById('odo-m-proceso');
    const mAtend = document.getElementById('odo-m-atendidas');
    const mNoAsist = document.getElementById('odo-m-noasistio');

    if (mTotal) mTotal.textContent = citasDoctor.length;
    if (mPend) mPend.textContent = citasDoctor.filter(c => ['Solicitado', 'Asignado'].includes(c.estado)).length;
    if (mProc) mProc.textContent = citasDoctor.filter(c => c.estado === 'En Proceso').length;
    if (mAtend) mAtend.textContent = citasDoctor.filter(c => c.estado === 'Atendido').length;
    if (mNoAsist) mNoAsist.textContent = citasDoctor.filter(c => c.estado === 'No Asistio').length;

    // Renderizar lista cronológica
    const listEl = document.getElementById('odo-citas-timeline');
    if (!listEl) return;

    if (citasDoctor.length === 0) {
        listEl.innerHTML = `
            <div class="p-8 text-center bg-white rounded-2xl border border-slate-200 text-slate-400">
                <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 mx-auto flex items-center justify-center mb-2">
                    ${AppIcons.calendarX("w-6 h-6")}
                </div>
                <p class="font-medium text-slate-600">No hay citas programadas para esta fecha.</p>
                <p class="text-xs text-slate-400 mt-1">Seleccione otro día en el selector superior.</p>
            </div>
        `;
        return;
    }

    listEl.innerHTML = citasDoctor.map(c => {
        let statusBadge = '';
        let actionButtons = '';

        if (c.estado === 'Solicitado' || c.estado === 'Asignado') {
            statusBadge = `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold ${c.estado === 'Asignado' ? 'bg-emerald-100 text-emerald-800' : 'bg-sky-100 text-sky-800'}">${c.estado === 'Asignado' ? AppIcons.check("w-3 h-3") + 'Confirmada' : AppIcons.clock("w-3 h-3") + 'Solicitada'}</span>`;
            actionButtons = `
                <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-slate-100">
                    <button onclick="doctorIniciarAtencion(${c.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition">
                        ${AppIcons.play("w-3 h-3")}
                        Iniciar Atención
                    </button>
                    <button onclick="doctorMarcarNoAsistio(${c.id})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-amber-400 bg-amber-50 hover:bg-amber-100 text-amber-900 text-xs font-bold transition">
                        ${AppIcons.clock("w-3.5 h-3.5 text-amber-800")}
                        Registrar No Asistió (15 min)
                    </button>
                </div>
            `;
        } else if (c.estado === 'En Proceso') {
            statusBadge = '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 animate-pulse"><span class="w-2 h-2 rounded-full bg-purple-600"></span>En Sillón (Atención Activa)</span>';
            actionButtons = `
                <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-slate-100">
                    <button onclick="doctorAbrirFinalizar(${c.id})" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                        ${AppIcons.fileEdit("w-4 h-4")}
                        <span>Finalizar y Registrar Observaciones</span>
                    </button>
                </div>
            `;
        } else if (c.estado === 'Atendido') {
            statusBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">' + AppIcons.check("w-3 h-3") + 'Atendido</span>';
            actionButtons = `
                <div class="mt-2 text-xs text-slate-500 italic bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                    <strong>Notas clínicas:</strong> ${c.observaciones || 'Procedimiento completado sin contratiempos.'}
                </div>
            `;
        } else if (c.estado === 'No Asistio') {
            statusBadge = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">' + AppIcons.alertTriangle("w-3.5 h-3.5 text-amber-800") + 'No Asistió (Falta Registrada)</span>';
            actionButtons = `
                <div class="mt-2 text-xs text-amber-800 bg-amber-50 p-2.5 rounded-lg border border-amber-200">
                    <strong>Motivo de falta:</strong> ${c.motivoInasistencia || 'Paciente ausente tras superar los 15 minutos de tolerancia.'}
                </div>
            `;
        } else if (c.estado === 'Cancelado') {
            statusBadge = '<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-600">Cancelada</span>';
        }

        return `
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-2">
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-extrabold px-3 py-1 bg-slate-900 text-white rounded-lg flex items-center gap-1">
                            ${AppIcons.clock("w-3 h-3")}
                            ${c.horaInicio} - ${c.horaFin}
                        </span>
                        <h4 class="font-bold text-slate-900 text-base">${c.pacienteNombre}</h4>
                        <span class="text-xs text-slate-400 font-normal">Tel: ${c.pacienteTel}</span>
                    </div>
                    ${statusBadge}
                </div>
                <div class="text-xs text-slate-600 flex flex-wrap gap-4 mt-2">
                    <span><strong>Tratamiento:</strong> ${c.servicios.join(', ')}</span>
                    <span><strong>Duración:</strong> ${c.duracionMin} min</span>
                    <span><strong>Costo:</strong> $${c.costo.toLocaleString('es-CO')} COP</span>
                </div>
                ${actionButtons}
            </div>
        `;
    }).join('');
}

function doctorIniciarAtencion(citaId) {
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;

    cita.estado = 'En Proceso';
    showToast(`Atención clínica iniciada para el paciente ${cita.pacienteNombre}. El sillón se encuentra ocupado.`, 'info');
    renderOdontologoPortal();
}

let activeFinalizarCitaId = null;
function doctorAbrirFinalizar(citaId) {
    activeFinalizarCitaId = citaId;
    clearDoctorFinalizarAlert();
    const notasInput = document.getElementById('doctor-notas-input');
    if (notasInput) notasInput.value = '';
    const modal = document.getElementById('doctor-finalizar-modal');
    if (modal) modal.classList.remove('hidden');
}

function doctorCerrarFinalizarModal() {
    activeFinalizarCitaId = null;
    clearDoctorFinalizarAlert();
    const modal = document.getElementById('doctor-finalizar-modal');
    if (modal) modal.classList.add('hidden');
}

function doctorConfirmarFinalizar() {
    if (!activeFinalizarCitaId) return;
    const notas = document.getElementById('doctor-notas-input').value;
    if (!notas || notas.trim().length < 5) {
        const msg = 'Por favor ingrese las observaciones clínicas del procedimiento (mínimo 5 caracteres).';
        showToast(msg, 'warning');
        showDoctorFinalizarAlert(msg, 'warning');
        return;
    }

    const cita = OdontoState.citas.find(c => c.id === activeFinalizarCitaId);
    if (!cita) return;

    cita.estado = 'Atendido';
    cita.observaciones = notas.trim();

    // Regla 5: Descuento de insumos ÚNICAMENTE al finalizar la cita en Atendido
    OdontoState.insumos[0].stock -= 1; // Guantes
    OdontoState.insumos[1].stock -= 1; // Eyector
    OdontoState.insumos[2].stock -= 1; // Babero

    // Insertar en historial clínico
    OdontoState.historial.unshift({
        id: OdontoState.historial.length + 1,
        pacienteId: cita.pacienteId,
        fecha: cita.fecha,
        tipo: 'Atencion Realizada',
        servicio: cita.servicios.join(', '),
        doctor: cita.odontologoNombre,
        resumen: `Procedimiento finalizado exitosamente. Obs: ${notas.trim()}`
    });

    doctorCerrarFinalizarModal();
    showToast(`Atención finalizada con éxito. Se insertó en historial y se descontaron insumos del inventario (Regla 5).`, 'success');
    renderOdontologoPortal();
}

function doctorMarcarNoAsistio(citaId) {
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;

    // Regla 4: Ventana de tolerancia de 15 minutos
    const confirmMsg = `CONTROL DE INASISTENCIAS (Regla 4):\n¿Confirma que ya transcurrieron los quince (15) minutos de tolerancia reglamentaria posteriores a las ${cita.horaInicio} sin presentación del paciente ${cita.pacienteNombre}?\n\nAcciones automáticas:\n1. Se marcará como 'No Asistió'.\n2. Se liberará el consultorio.\n3. Se incrementará en 1 la falta del paciente.\n4. Se enviará correo formal invitando a reprogramar.\n5. NO se descontará ningún insumo del inventario (Regla 5).`;
    
    if (!confirm(confirmMsg)) return;

    const motivo = prompt('Ingrese el motivo o detalle de la falta:', 'Paciente no se presentó a la consulta tras superar los 15 minutos de tolerancia.');

    cita.estado = 'No Asistio';
    cita.motivoInasistencia = motivo || 'Ausencia tras 15 minutos de tolerancia.';

    // Actualizar faltas del paciente si coincide con el usuario actual o simulación
    if (OdontoState.currentUser.id === cita.pacienteId) {
        OdontoState.currentUser.faltas_acumuladas++;
        if (OdontoState.currentUser.faltas_acumuladas >= 2) {
            OdontoState.currentUser.puedeAgendarWeb = false;
        }
    }

    // Registrar en Historial
    OdontoState.historial.unshift({
        id: OdontoState.historial.length + 1,
        pacienteId: cita.pacienteId,
        fecha: cita.fecha,
        tipo: 'Inasistencia Registrada',
        servicio: cita.servicios.join(', '),
        doctor: cita.odontologoNombre,
        resumen: `Inasistencia registrada tras 15 min de tolerancia. Motivo: ${cita.motivoInasistencia}`
    });

    showToast(`Inasistencia registrada para ${cita.pacienteNombre}. Consultorio liberado y notificación despachada. Stock de insumos intacto.`, 'warning');
    renderOdontologoPortal();
}

// -------------------------------------------------------------
// PANEL ADMINISTRATIVO & RECEPCIÓN
// -------------------------------------------------------------
function renderAdminPortal() {
    // Cálculo de KPIs
    const total = OdontoState.citas.length;
    const atendidas = OdontoState.citas.filter(c => c.estado === 'Atendido').length;
    const noAsist = OdontoState.citas.filter(c => c.estado === 'No Asistio').length;
    const canceladas = OdontoState.citas.filter(c => c.estado === 'Cancelado').length;
    const solicitadas = OdontoState.citas.filter(c => c.estado === 'Solicitado').length;

    // Tasa de Ausentismo: Inasistencias / (Atendidas + Inasistencias) * 100
    const totalCompromiso = atendidas + noAsist;
    const tasaAusentismo = totalCompromiso > 0 ? ((noAsist / totalCompromiso) * 100).toFixed(1) : 0;

    // Insumos bajos
    const insumosCriticos = OdontoState.insumos.filter(i => i.stock <= i.minimo);

    document.getElementById('adm-kpi-total').textContent = total;
    document.getElementById('adm-kpi-atendidas').textContent = atendidas;
    document.getElementById('adm-kpi-noasistio').textContent = noAsist;
    document.getElementById('adm-kpi-tasa').textContent = `${tasaAusentismo}%`;
    document.getElementById('adm-kpi-stock-alert').textContent = insumosCriticos.length;

    // Renderizar Tablas
    renderAdminAgendaGeneral();
    renderAdminInventario();
    renderAdminReportes();
}

function renderAdminAgendaGeneral() {
    const tableBody = document.getElementById('adm-agenda-table-body');
    if (!tableBody) return;

    tableBody.innerHTML = OdontoState.citas.map(c => {
        let badgeClass = '';
        if (c.estado === 'Solicitado') badgeClass = 'bg-sky-50 text-sky-800 border border-sky-200';
        else if (c.estado === 'Asignado') badgeClass = 'bg-emerald-50 text-emerald-800 border border-emerald-200';
        else if (c.estado === 'En Proceso') badgeClass = 'bg-purple-50 text-purple-800 border border-purple-200';
        else if (c.estado === 'Atendido') badgeClass = 'bg-teal-50 text-teal-800 border border-teal-200';
        else if (c.estado === 'Cancelado') badgeClass = 'bg-slate-100 text-slate-600 border border-slate-200';
        else if (c.estado === 'No Asistio') badgeClass = 'bg-amber-50 text-amber-900 border border-amber-300 font-semibold';

        let actionOptions = '';
        if (c.estado === 'Solicitado') {
            actionOptions = `
                <button onclick="adminConfirmarCita(${c.id})" class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-soft-sm transition">
                    ${AppIcons.check("w-3 h-3")}
                    Confirmar Cupo
                </button>
            `;
        } else {
            actionOptions = `
                <select onchange="adminCambiarEstado(${c.id}, this.value)" class="text-xs border border-slate-300/80 rounded-xl px-2.5 py-1 bg-white focus:ring-2 focus:ring-primary-500 text-slate-700">
                    <option value="">Cambiar estado...</option>
                    <option value="Asignado" ${c.estado === 'Asignado' ? 'selected' : ''}>Asignado</option>
                    <option value="En Proceso" ${c.estado === 'En Proceso' ? 'selected' : ''}>En Proceso</option>
                    <option value="Atendido" ${c.estado === 'Atendido' ? 'selected' : ''}>Atendido</option>
                    <option value="Cancelado" ${c.estado === 'Cancelado' ? 'selected' : ''}>Cancelado</option>
                    <option value="No Asistio" ${c.estado === 'No Asistio' ? 'selected' : ''}>No Asistió</option>
                </select>
            `;
        }

        return `
            <tr class="border-b border-slate-100/80 hover:bg-slate-50/70 transition text-xs">
                <td class="py-3 px-3.5 font-bold text-slate-900">#${c.id}</td>
                <td class="py-3 px-3.5 text-slate-800 font-medium">${c.fecha} • ${c.horaInicio}</td>
                <td class="py-3 px-3.5 text-slate-900 font-semibold">${c.pacienteNombre}</td>
                <td class="py-3 px-3.5 text-slate-700">${c.odontologoNombre}</td>
                <td class="py-3 px-3.5 text-slate-600">${c.servicios.join(', ')}</td>
                <td class="py-3 px-3.5"><span class="px-2.5 py-0.5 rounded-full ${badgeClass}">${c.estado}</span></td>
                <td class="py-3 px-3.5">${actionOptions}</td>
            </tr>
        `;
    }).join('');
}

function adminConfirmarCita(citaId) {
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;

    cita.estado = 'Asignado';
    showToast(`Cita #${cita.id} confirmada y asignada. Franja médica reservada en DENTAL CARE.`, 'success');
    renderAdminPortal();
}

function adminCambiarEstado(citaId, nuevoEstado) {
    if (!nuevoEstado) return;
    const cita = OdontoState.citas.find(c => c.id === citaId);
    if (!cita) return;

    cita.estado = nuevoEstado;
    showToast(`Estado de la cita #${cita.id} actualizado a ${nuevoEstado}.`, 'info');
    renderAdminPortal();
}

function renderAdminInventario() {
    const container = document.getElementById('adm-inventario-cards');
    if (!container) return;

    container.innerHTML = OdontoState.insumos.map(ins => {
        const isBajo = ins.stock <= ins.minimo;
        const color = isBajo ? 'border-rose-200 bg-rose-50/50' : 'border-slate-200/80 bg-white';
        const percent = Math.min(100, Math.round((ins.stock / (ins.minimo * 3)) * 100));

        return `
            <div class="p-4 rounded-2xl border ${color} shadow-soft-sm space-y-2.5">
                <div class="flex items-start justify-between">
                    <div>
                        <h5 class="text-xs font-bold text-slate-900">${ins.nombre}</h5>
                        <span class="text-[11px] text-slate-400">Umbral mínimo: ${ins.minimo} ${ins.unidad}</span>
                    </div>
                    <span class="text-xs font-black ${isBajo ? 'text-rose-600' : 'text-slate-800'}">
                        ${ins.stock} ${ins.unidad}
                    </span>
                </div>
                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="h-full ${isBajo ? 'bg-rose-500' : 'bg-primary-500'}" style="width: ${percent}%"></div>
                </div>
                <div class="flex justify-between items-center pt-1 text-[11px]">
                    <span class="inline-flex items-center gap-1 ${isBajo ? 'text-rose-700 font-semibold' : 'text-slate-500'}">
                        ${isBajo ? AppIcons.alertTriangle("w-3 h-3 text-rose-600") + 'Stock Bajo Mínimo' : AppIcons.check("w-3 h-3 text-emerald-600") + 'Disponible'}
                    </span>
                    <button onclick="reabastecerInsumo(${ins.id})" class="inline-flex items-center gap-1 text-primary-700 hover:text-primary-900 font-bold">
                        ${AppIcons.plus("w-3 h-3")}
                        Reponer Stock
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

function reabastecerInsumo(insId) {
    const ins = OdontoState.insumos.find(i => i.id === insId);
    if (!ins) return;

    ins.stock += 50;
    showToast(`Se reabastecieron 50 ${ins.unidad} de ${ins.nombre}.`, 'success');
    renderAdminPortal();
}

function renderAdminReportes() {
    // Ausentismo por Odontólogo
    const tbody = document.getElementById('adm-reporte-odontologos-tbody');
    if (!tbody) return;

    tbody.innerHTML = OdontoState.odontologos.map(o => {
        const at = OdontoState.citas.filter(c => c.odontologoId === o.id && c.estado === 'Atendido').length;
        const faltas = OdontoState.citas.filter(c => c.odontologoId === o.id && c.estado === 'No Asistio').length;
        const tot = at + faltas;
        const rate = tot > 0 ? ((faltas / tot) * 100).toFixed(1) : '0.0';

        return `
            <tr class="border-b border-slate-100">
                <td class="py-2.5 px-3 text-xs font-bold text-slate-900">${o.nombre}</td>
                <td class="py-2.5 px-3 text-xs text-slate-600">${o.especialidad}</td>
                <td class="py-2.5 px-3 text-xs text-center font-semibold text-emerald-700">${at}</td>
                <td class="py-2.5 px-3 text-xs text-center font-semibold text-amber-700">${faltas}</td>
                <td class="py-2.5 px-3 text-xs text-center">
                    <span class="px-2 py-0.5 rounded text-xs font-black ${Number(rate) > 20 ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-800'}">
                        ${rate}%
                    </span>
                </td>
            </tr>
        `;
    }).join('');
}

// Inicialización general al cargar el DOM
document.addEventListener('DOMContentLoaded', () => {
    updateUserBadge();
    navigateToModule('mod-landing');
});
