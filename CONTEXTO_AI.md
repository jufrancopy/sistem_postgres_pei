# SIPLAN — Contexto para Agente de IA
*Última actualización: Julio 2026 — v2*

---

## 1. QUÉ ES EL SISTEMA

**SIPLAN** es una plataforma de planificación estratégica institucional para el **Instituto de Previsión Social (IPS) de Paraguay**.

- **Stack:** Laravel 10 / PHP 8.2 / PostgreSQL 14 / Bootstrap 4
- **BD local:** `planificacion` (desarrollo)
- **BD producción:** `siplan` en servidor `2.59.156.228` — `/var/www/html/sistem_postgres_pei`
- **Deploy:** `git push` + `php artisan migrate` en servidor
- **OPcache activo en producción** — siempre correr `php artisan cache:clear` tras deploy
- **Schemas PostgreSQL:** `public`, `planificacion`, `estadistica`, `proyecto`

---

## 2. MÓDULOS EXISTENTES (NO ROMPER)

| Módulo | Ubicación | Estado |
|---|---|---|
| PEI (Plan Estratégico) | `app/Admin/Planificacion/Pei/PeiProfile.php` | ✅ Producción |
| FODA con IEA | `app/Admin/Planificacion/Foda/` | ✅ Producción |
| SIESS (Estadísticas) | `app/Models/Estadistica/` | ✅ Producción |
| RIISS (Evaluaciones) | `app/Models/Riiss/` | ✅ Producción |
| Actividades/Tareas | `app/Admin/Globales/` | ✅ Producción |
| Plan Maestro | `app/Models/PlanMaestro/` | ✅ Producción |
| Proyectos Institucionales | `app/Models/Proyectos/` | ✅ Producción |
| RBAC | `spatie/laravel-permission` | ✅ Producción |

---

## 3. MODELO PEI — ESTRUCTURA ACTUAL

### Tabla: `planificacion.pei_profiles`
- Árbol recursivo con **NestedSet** (`kalnoy/nestedset`)
- UUID como PK
- Niveles: `master` → `axi` → `goal` → `action`
- Los nombres de niveles son **dinámicos** via `nivel_label` (JSON)
- Modelo MECIP: `master=PEI`, `axi=Objetivo Estratégico`, `goal=Meta`, `action=Acción`

### Columnas clave actuales:
```
id (uuid), name, level, type, group_id, dependency_id, user_id
mision, vision, values, period
numerator, operator, denominator, goal, progress, target
indicator, baseline, report_type, parameters
presupuesto_asignado, presupuesto_ejecutado
semaforo, tipo_indicador
nivel_label (JSON con etiquetas dinámicas)
_lft, _rgt, parent_id (NestedSet)
```

### Relaciones existentes en PeiProfile:
- `belongsToMany` → `FodaCruceAmbiente` (estrategias FODA) via `pei_profiles_has_strategies`
- `belongsToMany` → `User` (analistas) via `peis_profiles_has_analysts`
- `belongsToMany` → `Organigrama` (responsables RACI) via `peis_profiles_has_responsibles`
- `belongsTo` → `Group`
- `belongsTo` → `Organigrama` (dependency)

---

## 4. MARCOS REFERENCIALES — ✅ IMPLEMENTADO

### Objetivo
Vincular nodos `axi` del PEI a marcos externos configurables: **PND 2050, ODS 2030, BSC, MECIP, PGN, etc.**

### Estado: COMPLETO Y FUNCIONANDO

### Archivos implementados

| Archivo | Estado |
|---|---|
| `database/migrations/2026_07_04_000001_create_marcos_referenciales_tables.php` | ✅ migrado |
| `app/Models/Planificacion/MarcoReferencial.php` | ✅ |
| `app/Http/Controllers/Admin/Planificacion/MarcoReferencialController.php` | ✅ |
| `app/Admin/Planificacion/Pei/PeiProfile.php` | ✅ relación `marcos()` agregada |
| `resources/views/admin/planificacion/peis/peis/modals.blade.php` | ✅ campo `#axis_marcos` en `ajaxAxisModal` |
| `resources/views/admin/planificacion/peis/peis/show.blade.php` | ✅ JS completo |
| `resources/views/admin/planificacion/peis/peis/accordion.blade.php` | ✅ visualización |
| `database/seeders/MarcoReferencialSeeder.php` | ✅ 45 registros (PND/ODS/BSC/MECIP) |

### BD creada
```sql
planificacion.marcos_referenciales  (id, nombre, tipo, descripcion, activo, unique[nombre,tipo])
planificacion.pei_profile_marcos    (pei_profile_id UUID, marco_id BIGINT)
```

### Endpoints activos
```
GET  /pei/marcos/buscar?q=texto     → Select2 AJAX
POST /pei/marcos/crear              → firstOrCreate, retorna {id, text, tipo}
GET  /pei-profiles/{id}/marcos      → pre-carga edición
POST /pei-profiles/{id}/marcos/sync → sincroniza pivote, retorna {ok, marcos:[{nombre,tipo}]}
```

### Decisiones de diseño tomadas
- **Solo nivel `axi`** — metas y acciones heredan el marco del ancestro
- **Select2 tags:true** — si no existe se crea al vuelo con tipo `general`
- **IDs nuevos** usan prefijo `new::texto` en JS, se crean vía AJAX antes del sync
- **Validación**: `'marcos.*' => 'integer'` — NO usar `exists:planificacion.tabla` (Laravel lo interpreta como conexión, no schema)

### Visualización en accordion
- **Header cerrado**: píldora `btn btn-sm rounded-pill` con ícono + contador (azul si hay marcos, gris outline si no)
- **Header abierto**: badges por tipo agrupados en `#marcos-body-{axisId}` dentro del card-body
- **Colores por tipo**: pnd=danger, ods=success, bsc=primary, mecip=warning, pgn=dark, general=secondary
- **Popover al click**: muestra los badges de marcos vinculados
- **Estrategias FODA**: mismo patrón de píldora (amarillo/gris)

### Actualización DOM sin recargar
Tras el sync, `actualizarDomMarcos(axisId, marcos)` en `show.blade.php`:
- Actualiza contador en la píldora
- Cambia clase btn-info/btn-outline-secondary
- Reconstruye el popover con `popover('dispose').popover()`
- Reconstruye los badges en `#marcos-body-{axisId}`

### Gotcha crítico — CRLF en show.blade.php
`show.blade.php` tiene line endings `\r\n`. `fsReplace` falla. **Siempre usar Python** con `content.replace('\r\n', '\n')` para modificar ese archivo.

---

## 5. FASES PENDIENTES

### Fase 2 — Vinculación Presupuestaria
- Tabla `actividades_pgn` (actividades del PGN con montos Gs/USD)
- Tabla `solicitudes_vinculacion` (workflow de modificación)
- Reutilizar patrón de máquina de estados de `SiessExtracto`
- Roles: Analista propone → Director aprueba

### Fase 3 — FODA extendido con Patrón Mediático
- Agregar columnas nullable a `foda_analisis`:
  - `patron_mediatico` ENUM('alta_exposicion', 'baja_exposicion')
  - `naturaleza_problema` ENUM('estructural', 'coyuntural')
- Filtros en la vista de cruce de ambientes

### Fase 4 — BSC Dashboard
- Agregar `bsc_perspectiva` ENUM a `pei_profiles` (nullable)
- Extender dashboard existente (`pei-profiles/{id}/dashboard`)
- Agrupar métricas por perspectiva BSC
- Google Charts ya disponible en `public/assets/googleCharts/`

---

## 6. REGLAS DE NEGOCIO CRÍTICAS

### FODA-IEA
- `IEA = Promedio Desempeño 6m / Inversión Histórica 6m`
- IEA < 0.4 → Debilidad | IEA > 0.8 → Fortaleza

### RACI
- Exactamente **1 Accountable (A)** por estrategia/acción — validado en `syncRaci()`
- Roles: R (Responsible), A (Accountable), C (Consulted), I (Informed)

### Semáforo Lead/Lag
- Lead: avance >= 100% → verde | >= 50% → amarillo | < 50% → rojo
- Lag: avance >= 85% → verde | >= 50% → amarillo | < 50% → rojo

### Alerta Presupuestaria
- `pct_meta < 20% && pct_presupuesto > 80%` → subejecución

### Migraciones en producción
- **Siempre nullable o con default** — hay datos existentes
- Nunca `->change()` sin verificar compatibilidad PostgreSQL

---

## 7. PATRONES DE CÓDIGO DEL PROYECTO

### Controladores
- Siempre `$this->middleware('auth')` en constructor
- Respuestas AJAX: `response()->json([...])`
- DataTables: `DataTables::of($data)->addColumn(...)->make(true)`

### Modelos
- Schema explícito: `protected $table = 'planificacion.pei_profiles'`
- Fillable siempre declarado
- Casts para booleanos y decimales

### Vistas
- Bootstrap 4 + Material Dashboard template
- Select2 para todos los selectores (ya cargado globalmente)
- SweetAlert2 para confirmaciones (en `public/assets/sweetAlert2/`)
- Toastr para notificaciones (en `public/assets/toastr/`)
- Google Charts en `public/assets/googleCharts/`

### Rutas
- Grupo principal: `Route::group(['middleware' => ['auth']], ...)`
- Prefijo RIISS: `Route::prefix('riiss')->name('riiss.')->middleware(['role:Administrador|Analista - RIISS'])`
- Prefijo Globales: `Route::group(['prefix' => 'admin/globales', 'as' => 'globales.'], ...)`

---

## 8. ARCHIVOS CLAVE DE REFERENCIA

```
app/Admin/Planificacion/Pei/PeiProfile.php          ← modelo principal PEI
app/Http/Controllers/Admin/Planificacion/Pei/PeiController.php  ← controller PEI
app/Admin/Planificacion/Foda/FodaAnalisis.php        ← modelo FODA
app/Services/GapAnalysisService.php                  ← patrón de servicio
app/Models/Estadistica/SiessExtracto.php             ← patrón workflow/estados
routes/web.php                                        ← todas las rutas
resources/views/admin/planificacion/peis/peis/modals.blade.php  ← modales PEI
resources/views/admin/planificacion/peis/peis/show.blade.php    ← vista principal PEI
database/migrations/2026_05_02_000001_create_siess_base_tables.php ← patrón migración
```

---

## 9. CONTEXTO RIISS (módulo paralelo activo)

- Gap Analysis con 2 dimensiones: `cartera_servicios` y `condiciones_habilitantes`
- 110 preguntas clasificadas: 38 cartera | 61 habilitantes | 11 metadata
- Evaluación 24 (CONCEPCIÓN HR): Cartera 55.56% | Habilitación 65%
- Tabla `gap_analysis_items` tiene columna `dimension`
- Tabla `evaluaciones` tiene `pct_habilitacion`, `clasificacion_habilitacion`

---

## 10. CRONOGRAMA/GANTT (trabajo en curso)

- Migración `fecha_inicio` en `activity_tasks` — ✅ creada
- Modelo `ActivityTask` — ✅ `fecha_inicio` en fillable y dates
- Controller `ActivityController::storeTarea()` — ✅ guarda `fecha_inicio`
- Modal `modal_tarea.blade.php` — ✅ campo fecha_inicio agregado
- **PENDIENTE:** Tab "Cronograma" en `show.blade.php` de actividades con Gantt de Google Charts

### Estructura de datos para el Gantt
```
activities: date_start, date_end (fechas del proyecto)
activity_tasks: etiqueta (departamento), color, fecha_inicio, fecha_vencimiento
```

### Formato Google Charts Gantt esperado
```javascript
['Task ID', 'Task Name', 'Resource', 'Start Date', 'End Date', 'Duration', 'Percent Complete', 'Dependencies']
[tarea.id, tarea.nombre, tarea.etiqueta, new Date(fecha_inicio), new Date(fecha_vencimiento), null, 0, null]
```
