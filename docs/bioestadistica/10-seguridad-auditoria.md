# 10 — Seguridad y Auditoría

## Roles

Se usa `spatie/laravel-permission`, ya instalado en SIPLAN.

| Rol | Estado | Alcance |
|---|---|---|
| Administrador | Existe | Acceso total |
| Analista de Bioestadística | Existe | Diseña formularios, catálogos, indicadores, reportes y dashboards institucionales; aprueba registros |
| Digitador Bioestadística | Nuevo | Carga y edita registros de sus establecimientos asignados |
| Consultor Bioestadística | Nuevo | Solo lectura y exportaciones agregadas |
| Auditor Bioestadística | Nuevo | Lectura general más consulta de `audit_log` |

Los roles y permisos `bio.*` se crean en `BioestadisticaRolesSeeder`. El permiso legado `bioestadistica-dashboard` se conserva como alias de `bio.dashboard.view` para no romper el scaffold ya sembrado.

## Permisos

| Grupo | Permisos |
|---|---|
| Formularios | `bio.form.view`, `bio.form.create`, `bio.form.update`, `bio.form.delete`, `bio.form.publish` |
| Catálogos | `bio.catalog.view`, `bio.catalog.create`, `bio.catalog.update`, `bio.catalog.delete` |
| Geografía | `bio.geo.view`, `bio.geo.create`, `bio.geo.update`, `bio.geo.delete` |
| Registros | `bio.record.view`, `bio.record.create`, `bio.record.update`, `bio.record.delete`, `bio.record.submit`, `bio.record.approve` |
| Indicadores | `bio.indicator.view`, `bio.indicator.manage`, `bio.indicator.evaluate` |
| Reportes | `bio.report.view`, `bio.report.manage`, `bio.report.export` |
| Dashboards | `bio.dashboard.view`, `bio.dashboard.manage`, `bio.dashboard.personalize` |
| Importación | `bio.import.view`, `bio.import.execute` |
| Hospitalización | `bio.hosp.view`, `bio.hosp.manage`, `bio.hosp.view_pii`, `bio.hosp.export` |
| Auditoría | `bio.audit.view` |

### Matriz rol / permiso

| Permiso | Admin | Analista | Digitador | Consultor | Auditor |
|---|:--:|:--:|:--:|:--:|:--:|
| `bio.form.view` | x | x | x | x | x |
| `bio.form.create` / `update` / `delete` / `publish` | x | x | | | |
| `bio.catalog.view` | x | x | x | x | x |
| `bio.catalog.create` / `update` / `delete` | x | x | | | |
| `bio.geo.view` | x | x | x | x | x |
| `bio.geo.create` / `update` / `delete` | x | x | | | |
| `bio.record.view` | x | x | x | x | x |
| `bio.record.create` / `update` | x | x | x | | |
| `bio.record.submit` | x | x | x | | |
| `bio.record.approve` | x | x | | | |
| `bio.record.delete` | x | x | | | |
| `bio.indicator.view` / `evaluate` | x | x | x | x | x |
| `bio.indicator.manage` | x | x | | | |
| `bio.report.view` / `export` | x | x | x | x | x |
| `bio.report.manage` | x | x | | | |
| `bio.dashboard.view` / `personalize` | x | x | x | x | x |
| `bio.dashboard.manage` | x | x | | | |
| `bio.import.execute` | x | x | | | |
| `bio.hosp.view` | x | x | x | x | x |
| `bio.hosp.manage` | x | x | x | | |
| `bio.hosp.view_pii` | x | x | x | | x |
| `bio.hosp.export` | x | x | | | |
| `bio.audit.view` | x | | | | x |

## Alcance por establecimiento

Un Digitador solo opera sobre los establecimientos que le fueron asignados.
La asignación se resuelve con una tabla puente propia del módulo y un scope global en los modelos
`Record` y `HospEpisodio`, que filtra por los establecimientos habilitados del usuario autenticado.
Administrador, Analista, Consultor y Auditor no tienen esa restricción.

## Protección de rutas

- Rutas web con `middleware(['auth', 'permission:...'])`.
- Rutas API bajo el mismo esquema, agrupadas por recurso.
- Autorización adicional en Policies para reglas contextuales: por ejemplo, un registro `aprobado`
  no se edita salvo que el usuario tenga `bio.record.approve`.

## Auditoría

### Qué se registra

| Entidad | Acciones |
|---|---|
| Formularios, secciones, campos | create, update, delete, publish, archive |
| Catálogos e ítems | create, update, delete |
| Variables | create, update, delete |
| Geografía y lookups | create, update, delete |
| Registros y valores | create, update, delete, submit, approve, reject |
| Episodios hospitalarios | create, update, delete, y **view** del detalle con cédula |
| Indicadores y fórmulas | create, update, delete |
| Reportes y dashboards | create, update, delete |
| Importaciones | execute, commit, cancel |

### Estructura del asiento

| Campo | Contenido |
|---|---|
| `user_id` | Usuario autenticado |
| `accion` | `create`, `update`, `delete`, `submit`, `approve`, `reject`, `publish`, `view`, `export`, `import` |
| `entity_type` | Clase del modelo |
| `entity_id` | Identificador de la entidad |
| `old_values` | Valores anteriores (JSONB, solo atributos modificados) |
| `new_values` | Valores nuevos (JSONB) |
| `ip` | Dirección de origen |
| `user_agent` | Cliente |
| `created_at` | Fecha y hora del evento |

### Implementación

Un trait `Auditable` con observer de Eloquent, aplicado a los modelos del módulo.
En `update` solo se guardan los atributos que efectivamente cambiaron (`getDirty` contra `getOriginal`),
para no inflar la tabla con copias completas.

Campos excluidos del asiento: `updated_at`, `remember_token` y cualquier campo marcado como sensible.

### Retención

`audit_log` es **append-only**: no se edita ni se borra desde la aplicación.
Los asientos de más de 24 meses se archivan por proceso administrativo, nunca por la UI.

### Consulta

Vista con DataTables filtrable por entidad, usuario, acción y rango de fechas.
El detalle muestra la comparación lado a lado entre valor anterior y valor nuevo.
Requiere `bio.audit.view`.

## Otros controles

| Control | Detalle |
|---|---|
| Validación de entrada | Form Request por caso de uso; nada llega crudo al modelo |
| Fórmulas | AST validado contra lista blanca de operadores; sin `eval` de PHP |
| Consultas | Query Builder con bindings; sin concatenación de SQL |
| Archivos subidos | Extensión y MIME validados; almacenados fuera de `public` |
| Exportaciones | Registradas en `audit_log` con los filtros aplicados |
| Datos personales | Cédula enmascarada sin `bio.hosp.view_pii` |

## Redirección post-login

Único cambio previsto fuera del módulo: en `LoginController` se agrega la redirección del rol
Analista de Bioestadística hacia `bioestadistica.dashboard`. Es una condición adicional que
no altera la lógica de redirección de los demás roles ni de los otros módulos.
