# 05 — API REST

Prefijo: **`/api/bio/v1`**
Autenticación: sesión Laravel para la UI Blade; Sanctum para consumo externo.
Autorización: middleware `permission:` de Spatie por endpoint.

## Convención de respuestas

Éxito:

```json
{
  "data": { },
  "meta": { "total": 140, "per_page": 25, "current_page": 1 }
}
```

Error de validación (HTTP 422):

```json
{
  "errors": [
    { "field": "periodo_mes", "message": "El mes del período es obligatorio." }
  ]
}
```

Códigos: `200` OK, `201` creado, `204` sin contenido, `403` sin permiso, `404` no encontrado, `409` conflicto (registro duplicado de período), `422` validación.

## Geografía

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/departamentos` | Listado paginado |
| POST | `/departamentos` | Crear |
| GET/PUT/DELETE | `/departamentos/{id}` | Detalle, editar, eliminar |
| GET | `/distritos?departamento_id=` | Cascada por departamento |
| POST | `/distritos` | Crear |
| GET/PUT/DELETE | `/distritos/{id}` | |
| GET | `/establecimientos?distrito_id=&departamento_id=&q=` | Cascada y búsqueda |
| POST | `/establecimientos` | Crear |
| GET/PUT/DELETE | `/establecimientos/{id}` | |

Lookups clasificatorios (CRUD completo cada uno):
`/microredes`, `/tipos-establecimiento`, `/grados-complejidad`, `/areas-gestion`

Los selectores en cascada de la UI usan exactamente:

```
GET /distritos?departamento_id=7
GET /establecimientos?distrito_id=52
```

## Formularios

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/forms?estado=&periodicidad=` | Listado |
| POST | `/forms` | Crear formulario |
| GET/PUT/DELETE | `/forms/{id}` | |
| POST | `/forms/{id}/publish` | Pasa a `activo` e incrementa `version` |
| POST | `/forms/{id}/archive` | Pasa a `archivado` |
| POST | `/forms/{id}/duplicate` | Copia completa de la estructura |
| GET | `/forms/{id}/schema` | Estructura completa lista para renderizar |
| GET/POST | `/forms/{id}/sections` | Secciones |
| PUT/DELETE | `/sections/{id}` | |
| POST | `/sections/{id}/reorder` | Reordenar |
| GET/POST | `/sections/{id}/fields` | Campos |
| PUT/DELETE | `/fields/{id}` | |
| POST | `/fields/{id}/reorder` | Reordenar |

Ejemplo `POST /forms`:

```json
{
  "codigo": "SP15",
  "nombre": "Salud Mental",
  "descripcion": "Prestaciones de salud mental comunitaria",
  "periodicidad": "mensual",
  "layout_type": "tabular"
}
```

Ejemplo `POST /sections/{id}/fields` con campo tabla:

```json
{
  "code": "prestaciones",
  "label": "Prestaciones por especialidad",
  "type": "tabla",
  "required": true,
  "config": {
    "row_catalog_id": 12,
    "columns": [
      { "code": "pacientes_nuevos", "label": "Pacientes nuevos", "type": "integer", "min": 0 },
      { "code": "consultas",        "label": "Consultas",        "type": "integer", "min": 0 }
    ]
  }
}
```

## Catálogos y variables

| Método | Ruta | Descripción |
|---|---|---|
| GET/POST | `/catalogs` | Catálogos |
| GET/PUT/DELETE | `/catalogs/{id}` | |
| GET/POST | `/catalogs/{id}/items` | Ítems |
| PUT/DELETE | `/catalog-items/{id}` | |
| POST | `/catalogs/{id}/items/bulk` | Carga masiva |
| GET | `/variable-definitions?codigo_dominio=&q=` | Diccionario de variables |
| GET/POST/PUT/DELETE | `/variable-definitions[/{id}]` | |

## Captura de registros

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/records?form_id=&establecimiento_id=&periodo_anio=&periodo_mes=&estado=` | Listado filtrado |
| POST | `/records` | Crear registro del período |
| GET/PUT/DELETE | `/records/{id}` | |
| POST | `/records/{id}/submit` | `borrador` → `enviado` |
| POST | `/records/{id}/approve` | `enviado` → `aprobado` |
| POST | `/records/{id}/reject` | `enviado` → `objetado` |
| GET | `/records/pending?establecimiento_id=` | Períodos sin cargar |

Ejemplo `POST /records`:

```json
{
  "formulario_id": 1,
  "establecimiento_id": 87,
  "periodo_anio": 2026,
  "periodo_mes": 1,
  "values": {
    "prestaciones": [
      { "row_code": "MED_GENERAL", "pacientes_nuevos": 120, "consultas": 340 },
      { "row_code": "PEDIATRIA",   "pacientes_nuevos": 80,  "consultas": 210 }
    ]
  }
}
```

`periodo_anio` y `periodo_mes` son el **período del dato**. La fecha de carga la asigna el servidor
en `submitted_at`. Cargar enero en febrero es el comportamiento esperado.

Conflicto de duplicado (HTTP 409):

```json
{ "errors": [ { "field": "periodo_mes", "message": "Ya existe un registro de SP1 para este establecimiento en 01/2026." } ] }
```

## Indicadores y estadística

| Método | Ruta | Descripción |
|---|---|---|
| GET/POST | `/indicators` | Indicadores |
| GET/PUT/DELETE | `/indicators/{id}` | |
| GET/POST | `/indicators/{id}/formulas` | Fórmulas AST versionadas |
| POST | `/indicators/{id}/evaluate` | Evaluar con filtros |
| POST | `/indicators/{id}/validate-formula` | Validar AST sin guardar |
| GET | `/statistics/field/{field_id}` | Estadística descriptiva de un campo |
| GET | `/statistics/trend` | Serie temporal |
| GET | `/statistics/compare` | Comparación entre períodos |

Ejemplo `POST /indicators/{id}/evaluate`:

```json
{
  "periodo_desde": { "anio": 2026, "mes": 1 },
  "periodo_hasta": { "anio": 2026, "mes": 6 },
  "group_by": "establecimiento",
  "filtros": { "departamento_id": 7, "distrito_id": 52 }
}
```

Respuesta:

```json
{
  "data": [
    { "grupo": "Hospital Hohenau", "establecimiento_id": 87, "valor": 82.45 },
    { "grupo": "Puesto Sanitario Fram", "establecimiento_id": 91, "valor": 61.20 }
  ],
  "meta": { "unidad": "%", "indicador": "Ocupación Hospitalaria", "periodos": 6 }
}
```

## Reportes

| Método | Ruta | Descripción |
|---|---|---|
| GET/POST | `/reports` | Definiciones de reporte |
| GET/PUT/DELETE | `/reports/{id}` | |
| POST | `/reports/run` | Ejecutar definición ad-hoc |
| POST | `/reports/{id}/run` | Ejecutar definición guardada |
| GET | `/reports/{id}/export.pdf` | Exportar PDF |
| GET | `/reports/{id}/export.xlsx` | Exportar Excel |
| GET | `/reports/{id}/export.csv` | Exportar CSV |

## Dashboards

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/dashboards` | Plantillas disponibles |
| GET/PUT | `/dashboards/me` | Dashboard personal del usuario |
| POST | `/dashboards/me/from-template/{id}` | Copiar plantilla como personal |
| GET/POST | `/dashboards/{id}/widgets` | Widgets |
| PUT/DELETE | `/widgets/{id}` | |
| POST | `/dashboards/{id}/layout` | Guardar posiciones |
| GET | `/widgets/{id}/data` | Serie lista para Chart.js |

## Importación Excel

| Método | Ruta | Descripción |
|---|---|---|
| POST | `/imports/excel` | Subir archivo, crea `import_job` |
| GET | `/imports/{id}` | Estado y análisis |
| GET | `/imports/{id}/preview` | Hojas, columnas y tipos sugeridos |
| POST | `/imports/{id}/map` | Guardar mapeo confirmado |
| POST | `/imports/{id}/commit` | Ejecutar importación |
| POST | `/imports/{id}/cancel` | Cancelar |

## Hospitalización SP10

| Método | Ruta | Descripción |
|---|---|---|
| GET/POST | `/hospitalization/episodes` | Episodios nominativos |
| GET/PUT/DELETE | `/hospitalization/episodes/{id}` | |
| GET | `/hospitalization/indicators` | Estancia media, mortalidad, cesáreas, cirugías |
| POST | `/hospitalization/aggregate` | Consolidar episodios al registro SP10 del período |

## Auditoría

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/audit-logs?entity_type=&entity_id=&user_id=&desde=&hasta=` | Traza filtrable |
| GET | `/audit-logs/{id}` | Detalle con valores anterior y nuevo |

## Permisos por recurso

| Grupo de rutas | Permiso |
|---|---|
| `/forms`, `/sections`, `/fields` | `bio.form.*` |
| `/catalogs`, `/catalog-items`, `/variable-definitions` | `bio.catalog.*` |
| `/departamentos`, `/distritos`, `/establecimientos`, lookups | `bio.geo.*` |
| `/records` | `bio.record.*` |
| `/indicators`, `/statistics` | `bio.indicator.*` |
| `/reports` | `bio.report.*` |
| `/dashboards`, `/widgets` | `bio.dashboard.*` |
| `/imports` | `bio.import.*` |
| `/hospitalization` | `bio.hosp.*` |
| `/audit-logs` | `bio.audit.view` |
