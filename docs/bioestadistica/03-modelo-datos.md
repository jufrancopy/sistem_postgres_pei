# 03 — Modelo de Datos

Schema: **`bioestadistica`**. Diagrama en [03-er.mmd](03-er.mmd). DDL en [04-ddl-postgresql.sql](04-ddl-postgresql.sql).

**Regla absoluta**: no existen tablas `sp1`, `sp2`, … `sp14`. Las planillas viven como filas de configuración.

## Bloque 1 — Geografía y clasificación

| Tabla | Rol |
|---|---|
| `departamentos` | 18 departamentos. `codigo` proviene de `id_DEPTO`. Asunción y Capital unificados |
| `distritos` | Pertenece a un departamento. Nombre único por departamento |
| `microredes` | Agrupación de establecimientos |
| `tipos_establecimiento` | Puesto Sanitario, Hospital Regional, etc. Absorbe la antigua tipología-clasificación |
| `grados_complejidad` | Código 1 a 6 más descripción |
| `areas_gestion` | Área Central, Área Interior, etc. |
| `establecimientos` | Maestro propio del módulo |

Jerarquía canónica:

```
Departamento  →  Distrito  →  Establecimiento
   Itapúa      →  Hohenau   →  Hospital Hohenau
   Itapúa      →  Fram      →  Puesto Sanitario Fram
```

El establecimiento guarda solo `distrito_id`; el departamento se deriva por transitividad y **no se duplica**.

Campos de `establecimientos`:

| Campo | Tipo | Nota |
|---|---|---|
| `codigo` | varchar único | `ID_ESTABLECIMIENTO` |
| `nombre` | varchar | |
| `distrito_id` | FK nullable | Obligatorio en captura, nullable en el seed inicial |
| `microred_id`, `tipo_establecimiento_id`, `grado_complejidad_id`, `area_gestion_id` | FK nullable | Lookups |
| `nivel_atencion` | varchar | Campo, no tabla. NIVEL 1 a 4 |
| `prestador` | varchar | Campo, no tabla. IPS / CONVENIO / TERCERIZADO |
| `situacion_inmueble` | varchar | Campo, no tabla |
| `sistema` | varchar | Campo, no tabla. SIH / SAMIW |
| `codigo_sih` | varchar | Hoja `CODIGO SIH` |
| `latitud`, `longitud` | numeric | |
| `observacion` | text | |

No incluye `camas_operativas` ni `activo`: las camas operativas son un dato de planilla (SP10/SP11),
no un atributo del maestro.

## Bloque 2 — Metadata de formularios

| Tabla | Columnas clave |
|---|---|
| `formularios` | `codigo` único, `nombre`, `descripcion`, `estado`, `periodicidad`, `layout_type`, `version` |
| `form_secciones` | `formulario_id`, `titulo`, `orden`, `descripcion` |
| `fields` | `seccion_id`, `code`, `label`, `type`, `required`, `min_value`, `max_value`, `validation_regex`, `tooltip`, `help_text`, `catalogo_id`, `parent_field_id`, `variable_definition_id`, `config` JSONB, `orden` |
| `catalogos` | `codigo` único, `nombre`, `descripcion` |
| `catalog_items` | `catalogo_id`, `codigo`, `label`, `orden`, `activo`, `domain_code`, `tipo_registro`, `prestacion`, `meta` JSONB |
| `variable_definitions` | `codigo_dominio`, `dominio`, `tipo_registro`, `prestacion`, `catalogo_id`, `form_codes` (array), `meta` JSONB |

`config` JSONB por tipo de campo:

```json
// tabla
{ "row_catalog_id": 12, "columns": [ {"code":"pacientes","label":"Pacientes","type":"integer"} ] }

// matriz (SP11)
{ "rows": ["ingresos","egresos","pacientes_dia","camas_disponibles"],
  "cols": "dias_mes", "col_count": 31, "totals": true }

// select
{ "allow_other": false, "multiple": false }
```

## Bloque 3 — Captura

| Tabla | Rol |
|---|---|
| `records` | Una instancia de formulario. Único por `(formulario_id, establecimiento_id, periodo_anio, periodo_mes)` |
| `record_values` | Valor por campo. Único por `(record_id, field_id)` |

`records` incluye:

- `periodo_anio` (smallint), `periodo_mes` (smallint 1-12) — **período del dato**
- `estado`: `borrador` / `enviado` / `aprobado` / `objetado`
- `submitted_by`, `submitted_at`, `approved_by`, `approved_at` — **momento de la digitación**
- `observacion`

`record_values` usa columnas tipadas: `value_text`, `value_num`, `value_date`, `value_bool`, `value_json`.
El tipo del campo determina cuál se usa; `value_json` cubre multiselect, tabla, subtabla y matriz.

## Bloque 4 — Indicadores y estadística

| Tabla | Rol |
|---|---|
| `indicadores` | `codigo`, `nombre`, `descripcion`, `unidad`, `ambito`, `decimales`, `activo` |
| `indicador_formulas` | `indicador_id`, `expresion` JSONB (AST), `vigente_desde`, `vigente_hasta` |
| `indicador_cache` | Resultados por `(indicador_id, periodo_anio, periodo_mes, establecimiento_id)` |

## Bloque 5 — Reportes y dashboards

| Tabla | Rol |
|---|---|
| `reportes` | `codigo`, `nombre`, `definicion` JSONB (variables, filtros, group_by, order_by) |
| `dashboards` | `codigo`, `nombre`, `user_id` nullable (null = plantilla institucional) |
| `dashboard_widgets` | `dashboard_id`, `tipo`, `titulo`, `query_config` JSONB, `pos_x`, `pos_y`, `ancho`, `alto` |

## Bloque 6 — Hospitalización SP10

| Tabla | Rol |
|---|---|
| `hosp_episodios` | Registro nominativo: `cedula`, `sexo`, `seguro`, `fecha_ingreso`, `fecha_egreso`, `servicio`, `diagnostico`, `cie10`, `tipo_alta`, `cirugia`, `establecimiento_id`, `record_id` nullable |

## Bloque 7 — Auditoría e importación

| Tabla | Rol |
|---|---|
| `audit_log` | `user_id`, `accion`, `entity_type`, `entity_id`, `old_values` JSONB, `new_values` JSONB, `ip`, `user_agent`, `created_at` |
| `import_jobs` | `archivo`, `tipo_detectado`, `estado`, `mapping` JSONB, `formulario_id`, `resumen` JSONB, `user_id` |

## Índices principales

| Índice | Motivo |
|---|---|
| `UNIQUE (formulario_id, establecimiento_id, periodo_anio, periodo_mes)` en `records` | Un registro por formulario/establecimiento/período |
| `UNIQUE (record_id, field_id)` en `record_values` | Un valor por campo |
| `(periodo_anio, periodo_mes)` en `records` | Filtros de indicadores y reportes |
| `UNIQUE (departamento_id, nombre)` en `distritos` | Nombre de distrito único por departamento |
| GIN en `indicador_formulas.expresion`, `dashboard_widgets.query_config`, `reportes.definicion` | Búsqueda dentro de JSONB |
| `(entity_type, entity_id)` en `audit_log` | Traza por entidad |
| `(establecimiento_id, fecha_egreso)` en `hosp_episodios` | Indicadores hospitalarios |

## Integridad y borrado

- Metadata (`formularios`, `fields`, `catalogos`, `indicadores`, `reportes`, `dashboards`): `soft delete`.
- Lookups geográficos: restringir borrado si tienen dependientes.
- `record_values`: cascada al borrar el `record`.
- `audit_log`: sin borrado (append-only).
