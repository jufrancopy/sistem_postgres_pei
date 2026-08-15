# 01 — Modelo Funcional

## Objetivo del módulo

Sustituir la carga de planillas estadísticas en Excel/Access por una plataforma web donde los formularios,
sus campos, catálogos, indicadores, reportes y dashboards se **configuran**, no se programan.

## Actores

| Rol | Origen | Capacidades |
|---|---|---|
| Administrador | Existe | Todo |
| Analista de Bioestadística | Existe | Diseña formularios, catálogos, indicadores, reportes y dashboards institucionales |
| Digitador Bioestadística | Nuevo | Carga y edita registros de los formularios asignados |
| Consultor Bioestadística | Nuevo | Solo lectura y exportaciones permitidas |
| Auditor Bioestadística | Nuevo | Lectura general + consulta de `audit_log` |

El rol *Estadígrafo* mencionado en el pedido original se implementa como **Analista de Bioestadística**,
que ya existe en el seeder de roles del sistema.

## Capacidades

### 1. Constructor de formularios

Cada formulario tiene:

| Atributo | Valores |
|---|---|
| `codigo` | Único (`SP1`, `SP2`, …, `SP15` a futuro) |
| `nombre` | Texto |
| `descripcion` | Texto largo |
| `estado` | `borrador` / `activo` / `archivado` |
| `periodicidad` | `diaria` / `semanal` / `mensual` / `trimestral` / `anual` / `ad_hoc` |
| `layout_type` | `tabular` / `nominativo` / `matriz` |
| `version` | Entero incremental al publicar |

### 2. Constructor de campos

Tipos soportados:

`text`, `textarea`, `integer`, `decimal`, `date`, `time`, `boolean`,
`select`, `multiselect`, `radio`, `tabla`, `subtabla`, `matriz`

Cada campo permite configurar:

- Obligatorio
- Valor mínimo y máximo
- Expresión de validación (regex)
- Tooltip
- Ayuda contextual
- Catálogo asociado (para `select` / `multiselect` / `radio` / filas de `tabla`)
- Campo padre (para `subtabla`)

### 3. Catálogos reutilizables

Un catálogo se define una vez y lo consumen todos los formularios que lo necesiten:

Especialidades médicas, determinaciones de laboratorio, vacunas, procedimientos,
diagnósticos CIE-10, medicamentos e insumos, servicios, programas de salud.

### 4. Captura de datos

Un **registro** (`record`) representa: *un formulario, de un establecimiento, de un período*.

```
record  =  formulario  ×  establecimiento  ×  (periodo_anio, periodo_mes)
```

Los valores se guardan en `record_values` (modelo EAV tipado).

**Regla de período**: la digitación ocurre en el mes siguiente o posterior al período reportado.
En febrero se cargan los datos de enero. El sistema distingue:

| Concepto | Campo | Ejemplo |
|---|---|---|
| Período del dato | `periodo_anio`, `periodo_mes` | 2026 / 1 (enero) |
| Momento de la carga | `created_at`, `submitted_at` | 2026-02-15 |

Estados del registro: `borrador` → `enviado` → `aprobado` (u `objetado`).

### 5. Motor de indicadores

Fórmulas declarativas sin programar: `SUM`, `AVG`, `COUNT`, `MAX`, `MIN`, porcentajes y operaciones aritméticas.

Ejemplo de ocupación hospitalaria:

```
(Pacientes Día / Camas Operativas) × 100
```

### 6. Capa estadística

Sobre cualquier campo numérico o indicador:

frecuencia absoluta, frecuencia relativa, porcentajes, media, mediana, moda,
desviación estándar, cuartiles, percentiles, tendencias y comparación entre períodos.

Cortes disponibles: año, mes, departamento, distrito, establecimiento, microred,
tipo de establecimiento, grado de complejidad.

### 7. Reportes

Diseñador donde el usuario selecciona variables, filtros, agrupamiento y orden.
Exportación a PDF, Excel y CSV.

### 8. Dashboards

Plantillas institucionales más copia personalizable por usuario.
Widgets: KPI, tabla, barras, líneas, pastel, heatmap e indicador.

### 9. Importación Excel

Asistente que sube un archivo, detecta hojas y columnas, sugiere tipos de datos
y crea el formulario dinámico correspondiente.

### 10. Auditoría

Registro de usuario, fecha, acción, valor anterior y valor nuevo sobre metadata y datos.

## Casos de uso principales

| Caso de uso | Actor | Resultado |
|---|---|---|
| Crear formulario SP15 | Analista | Formulario nuevo sin migración |
| Definir catálogo de vacunas | Analista | Catálogo reutilizable |
| Cargar SP1 de enero | Digitador | `record` con `periodo_anio=2026`, `periodo_mes=1` |
| Aprobar registro | Analista | Estado `aprobado`, dato disponible para indicadores |
| Definir ocupación hospitalaria | Analista | Indicador con fórmula AST |
| Consultar tablero | Consultor | Dashboard con Chart.js |
| Exportar reporte mensual | Consultor | PDF / Excel / CSV |
| Revisar cambios de un registro | Auditor | Traza en `audit_log` |
| Registrar egreso hospitalario | Digitador | Episodio nominativo SP10 |

## Reglas de negocio

1. Un establecimiento pertenece a un distrito; un distrito pertenece a un departamento.
2. Un registro es único por formulario, establecimiento y período.
3. Un formulario en estado `activo` no puede perder campos con datos cargados; se versiona.
4. Los indicadores se calculan sobre registros `aprobado` (configurable a `enviado`).
5. El período estadístico es independiente de la fecha de digitación.
6. Las prestaciones y dominios provienen del diccionario de variables, no del layout de la planilla.
