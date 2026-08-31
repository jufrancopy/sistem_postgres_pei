# 15 — Asignaciones de captura (formulario × establecimiento × usuario)

## Objetivo

Permitir que un **Digitador Bioestadística** cargue **solo** los formularios SP asignados en **los** establecimientos asignados, sin crear un rol nuevo ni cambiar las URLs de captura existentes.

## Actores y permisos

| Actor | Capacidad |
|---|---|
| Digitador Bioestadística | Captura, edición y envío restringidos a sus asignaciones |
| Analista de Bioestadística | CRUD de asignaciones (`bio.assignment.manage`) |
| Administrador | Igual que Analista + acceso total |
| Consultor / Auditor | Sin cambio: lectura global por establecimiento |

## Modelo de datos

Tabla `bioestadistica.usuario_captura_asignaciones`:

| Campo | Tipo | Descripción |
|---|---|---|
| `id` | bigint | PK |
| `user_id` | bigint | Usuario con rol Digitador |
| `formulario_id` | bigint nullable | Formulario SP; `NULL` = todos los formularios activos en ese establecimiento |
| `establecimiento_id` | FK | Establecimiento bioestadística |
| `activo` | boolean | Permite desactivar sin borrar historial |
| `asignado_por` | bigint nullable | Usuario que creó/modificó |
| `created_at` / `updated_at` | timestamp | Auditoría |

Índices únicos parciales (PostgreSQL):

- `(user_id, establecimiento_id)` WHERE `formulario_id IS NULL AND activo`
- `(user_id, formulario_id, establecimiento_id)` WHERE `formulario_id IS NOT NULL AND activo`

### Convivencia con `usuario_establecimientos`

| Situación del digitador | Comportamiento |
|---|---|
| Solo filas en `usuario_establecimientos` | **Legacy:** todos los formularios activos en esos establecimientos |
| Una o más filas activas en `usuario_captura_asignaciones` | **Granular:** solo los pares formulario+establecimiento definidos |
| Sin ninguna asignación | No puede capturar ni importar |

La tabla legacy **no se elimina**; sigue siendo la vía rápida “todos los SP en estos establecimientos”.

## Reglas de negocio

1. Sin asignaciones → digitador no puede crear registros (mensaje claro en UI).
2. Una fila activa = `usuario + formulario + establecimiento` (única).
3. `formulario_id = NULL` → todos los formularios activos en ese establecimiento para ese usuario.
4. SP10 / nominativo: misma regla de alcance que el resto.
5. Importación SP / genérica: rechaza si no tiene asignación para el par detectado.
6. Formulario archivado: la asignación sigue existiendo pero el formulario no aparece en selects (estado ≠ activo).
7. Varios digitadores pueden compartir el mismo par.
8. Admin, Analista, Consultor y Auditor **no** tienen restricción por asignación.

## Pantallas

### Configuraciones → pestaña **Asignaciones** (principal)

Ruta: `/bioestadistica/asignaciones`

- Tabla: Digitador | Formulario | Establecimiento | Estado | Acciones
- Filtros por digitador, formulario y establecimiento
- Modal **Nueva asignación**: usuario (Digitador), formulario (opción “Todos los formularios”), establecimiento
- Desactivar / eliminar fila

Permiso: `bio.assignment.manage`

### Acceso rápido desde Carga de datos

El botón **Asignar digitadores** en `/bioestadistica/captura` redirige a la pantalla de Configuraciones.

La pantalla antigua `/bioestadistica/captura-asignaciones` queda obsoleta (redirect).

## Experiencia del digitador

| Pantalla | Efecto |
|---|---|
| Nueva carga | Selects filtrados por asignación |
| Períodos pendientes | Solo cruces formulario×establecimiento permitidos |
| Listado / edición | `scopeForUser` incluye formulario cuando hay asignación granular |
| Importación | Validación de par formulario+establecimiento |

## Historias de usuario

- *Como analista, quiero asignar a María SP3 en Hospital Central para que no vea otros formularios.*
- *Como analista, quiero asignar a Pedro “todos los formularios” en CS Norte (modo equivalente al legacy por establecimiento).*
- *Como digitador, quiero ver solo mis formularios al crear una nueva carga.*
- *Como admin, quiero desactivar una asignación sin borrar registros ya cargados.*

## Criterios de aceptación

- [ ] Digitador con SP3 + Hosp. Central no ve SP5 en el select de nueva carga
- [ ] Digitador con solo `usuario_establecimientos` sigue viendo todos los SP activos en esos establecimientos
- [ ] Analista ve todo sin restricción
- [ ] Pendientes solo muestra periodos de pares asignados
- [ ] Importación rechaza formulario no asignado
- [ ] Pantalla en Configuraciones con pestaña visible para quien tenga `bio.assignment.manage`


- Vigencia por fechas
- Asignación por departamento/distrito/microred
- Notificaciones automáticas
- Auto-asignación al crear usuario

## Implementación técnica

| Componente | Ubicación |
|---|---|
| Servicio de alcance | `App\Application\Bioestadistica\Capture\CaptureScopeService` |
| Modelo | `App\Models\Bioestadistica\UsuarioCapturaAsignacion` |
| Controller | `App\Http\Controllers\Admin\Bioestadistica\AsignacionesCapturaController` |
| Vista | `resources/views/admin/bioestadistica/asignaciones/index.blade.php` |
| Pestaña Configuraciones | `BioestadisticaConfigNavigation` |
| Permiso | `bio.assignment.manage` en `BioestadisticaRolesSeeder` |

## Fases de despliegue

| Fase | Contenido |
|---|---|
| 1 | Migración + servicio + filtros en captura (create/list/store) |
| 2 | UI Configuraciones + permiso + redirect desde Carga de datos |
| 3 | Pendientes + importación + hospitalización |
| 4 | Documentación y capacitación analistas |
