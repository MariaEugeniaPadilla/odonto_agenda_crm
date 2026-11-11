# OdontoAgenda CRM

> **Plataforma Web de Gestión Operativa, Agendamiento Inteligente, Atención al Paciente y Control de Inasistencias para la Clínica Odontológica DentalCare**  
> **Autora:** María Eugenia Padilla Reina  
> **Programa:** Tecnología en Desarrollo de Software – Misión Paz Corporación Universitaria  
> **Santiago de Cali, 2026**

---

## 1. Descripción del Proyecto

**OdontoAgenda CRM** es una solución integral desarrollada bajo el patrón arquitectónico **MVC (Laravel)** y tecnologías web modernas (**HTML5, Tailwind CSS, Vanilla JS y MySQL**) diseñada para automatizar el ciclo completo de servicio de una clínica odontológica. Resuelve de forma definitiva los problemas de cruces de horarios, tiempos muertos, descontrol de insumos y altas tasas de inasistencia (ausentismo) de pacientes sin previo aviso.

---

## 2. Cómo Ejecutar y Probar el Prototipo

El prototipo interactivo ha sido desarrollado de manera que puede ejecutarse y presentarse **de forma inmediata con 0 dependencias complejas**:

### Opción A: Directo en el Navegador (Recomendada para sustentación rápida)
Simplemente abra el archivo [`public/index.html`](file:///c:/laragon/www/odontoagenda/public/index.html) en cualquier navegador moderno (Google Chrome, Microsoft Edge, Mozilla Firefox o Safari).

### Opción B: A través del Servidor de Laragon
1. Abra **Laragon** y presione **Start All** (Apache y MySQL).
2. Abra en su navegador: `http://localhost/odontoagenda/public/` o `http://odontoagenda.test/`.

### Opción C: Servidor Integrado de PHP
Ejecute en la terminal dentro de esta carpeta:
```powershell
php -S localhost:8000 -t public
```
Luego abra en su navegador: `http://localhost:8000`

---

## 3. Esquema de la Base de Datos (`odontoagenda_crm`)

El script de la base de datos completa con estructura, triggers, procedimientos almacenados y datos iniciales está disponible en:
- [`database/odontoagenda_crm.sql`](file:///c:/laragon/www/odontoagenda/database/odontoagenda_crm.sql)
- [`database/schema.sql`](file:///c:/laragon/www/odontoagenda/database/schema.sql)

### Para importarlo en MySQL / MariaDB (Laragon HeidiSQL o consola):
```sql
CREATE DATABASE IF NOT EXISTS odontoagenda_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE odontoagenda_crm;
SOURCE c:/laragon/www/odontoagenda/database/odontoagenda_crm.sql;
```

### Tablas del Modelo Relacional:
1. `usuarios`: Pacientes, odontólogos, recepcionistas y administradores. Incluye `faltas_acumuladas` para control de inasistencias y restricción de agendamiento web.
2. `odontologos`: Especialidad, registro médico y estado profesional vinculado a la tabla usuarios.
3. `servicios`: Catálogo oficial de tratamientos con duración en minutos y precio de referencia en COP.
4. `horarios`: Franjas de disponibilidad semanal por especialista (Lunes a Sábado).
5. `citas`: Entidad central del servicio. Estados: `Solicitado`, `Asignado`, `En Proceso`, `Atendido`, `Cancelado`, `No Asistio`.
6. `cita_servicio`: Relación muchos a muchos para citas con cálculo aditivo de servicios.
7. `historial_atencion`: Trazabilidad formal que distingue `Atencion Realizada` de `Inasistencia Registrada`.
8. `insumos`: Inventario clínico con stock actual y stock mínimo de alerta.
9. `servicio_insumo`: Consumo de insumos por procedimiento.
10. `notificaciones`: Cola de correos transaccionales (confirmación, recordatorio 24h, alerta de inasistencia).

### Lógica y Triggers Avanzados de Base de Datos:
* **`trg_citas_before_insert` y `trg_citas_before_update`**: Valida que un odontólogo no tenga dos citas solapadas en la misma franja de tiempo, rechazando la inserción si hay colisión horaria.
* **`trg_citas_after_update`**:
  * Si la cita pasa a **`Atendido`**: Inserta en `historial_atencion` y **descuenta automáticamente los insumos** de la tabla `insumos`.
  * Si la cita pasa a **`No Asistio`**: Inserta la falta en `historial_atencion`, incrementa en 1 `faltas_acumuladas` en `usuarios` y **NO descuenta ningún insumo** (Regla 5).
* **Procedimiento Almacenado `sp_marcar_inasistencia`**: Valida que hayan transcurrido al menos 15 minutos de tolerancia antes de permitir cambiar el estado a 'No Asistió'.

---

## 4. Reglas de Negocio Implementadas

| Regla | Descripción | Implementación |
|---|---|---|
| **Regla 1 (No solapamiento)** | Un odontólogo no puede tener 2 citas en la misma franja de tiempo. | Validación en tiempo real en UI y Trigger SQL `BEFORE INSERT`. |
| **Regla 2 (Cálculo aditivo)** | La duración total y costo es la suma exacta de los servicios elegidos. | Calculador dinámico en JavaScript y procedimiento almacenado. |
| **Regla 3 (Cancelación >= 2h)** | Cancelación o reagendamiento voluntario con mínimo 2 horas de anticipación. | Validación en `PacienteController` y alertas modales en el prototipo. |
| **Regla 4 (Tolerancia 15 min)** | Ventana de espera de 15 minutos antes de marcar 'No Asistió'. | Control estricto en la vista del Odontólogo y `sp_marcar_inasistencia`. |
| **Regla 5 (Inviolabilidad de insumos)** | **NO** se descuentan insumos ante inasistencias o cancelaciones; solo en `Atendido`. | Trigger SQL `AFTER UPDATE` y lógica condicional en `OdontologoController`. |
| **Regla 6 (Inmutabilidad)** | Citas en `Atendido`, `Cancelado` o `No Asistió` son terminales y no se reabren. | Validación de estado inmutable. |
| **Regla 7 (Restricción por faltas)** | Si el paciente acumula 2 faltas consecutivas, se inhabilita el agendamiento web. | Validación en `Usuario::puedeAgendarWeb()` y banner de bloqueo en UI. |
| **Regla 8 (Notificación formal)** | Al registrar 'No Asistió', se notifica formalmente invitando a reprogramar. | Botón "Reprogramar Cita" en citas no asistidas y tabla `notificaciones`. |

---

## 5. Cuentas de Demostración Disponibles en el Prototipo

En la barra superior del prototipo encontrará botones rápidos para alternar entre roles:

1. **Paciente Normal (0 faltas):** `juan.perez@gmail.com` (Acceso completo para agendar, cancelar y ver citas).
2. **Paciente con 2 Faltas (Bloqueado):** `andres.murillo@gmail.com` (Demuestra el bloqueo web de la **Regla 7**).
3. **Odontólogo Especialista:** `dra.morales@dentalcare.com` (Agenda del día, Iniciar, Finalizar con observaciones, Marcar No Asistió tras 15 min).
4. **Administrador / Recepción:** `admin@dentalcare.com` (Métricas gerenciales, tasa de ausentismo, confirmación de citas, alertas de stock bajo e indicadores).

---

## 6. Estructura del Código

```text
odontoagenda/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── PublicController.php
│   │   ├── PacienteController.php
│   │   ├── OdontologoController.php
│   │   ├── AdminController.php
│   │   └── Controller.php
│   └── Models/
│       ├── Usuario.php
│       ├── Odontologo.php
│       ├── Servicio.php
│       ├── Horario.php
│       ├── Cita.php
│       ├── HistorialAtencion.php
│       ├── Insumo.php
│       └── Notificacion.php
├── database/
│   ├── migrations/
│   │   ├── 2026_11_05_000001_create_usuarios_table.php
│   │   ├── 2026_11_05_000002_create_odontologos_table.php
│   │   ├── 2026_11_05_000003_create_servicios_table.php
│   │   ├── 2026_11_05_000004_create_horarios_table.php
│   │   ├── 2026_11_05_000005_create_citas_table.php
│   │   ├── 2026_11_05_000006_create_cita_servicio_table.php
│   │   ├── 2026_11_05_000007_create_historial_atencion_table.php
│   │   ├── 2026_11_05_000008_create_insumos_table.php
│   │   ├── 2026_11_05_000009_create_servicio_insumo_table.php
│   │   └── 2026_11_05_000010_create_notificaciones_table.php
│   ├── seeders/
│   │   └── OdontoAgendaSeeder.php
│   ├── odontoagenda_crm.sql   <-- Script SQL Maestro
│   └── schema.sql
├── public/
│   ├── index.html            <-- Prototipo Interactivo Funcional
│   ├── js/
│   │   └── prototype.js      <-- Lógica Vanilla JS y simulación de reglas
│   └── css/
│       └── prototype.css
├── resources/views/
│   └── prototype.blade.php
├── routes/
│   └── web.php
├── composer.json
├── .env.example
├── .env
└── README.md
```
