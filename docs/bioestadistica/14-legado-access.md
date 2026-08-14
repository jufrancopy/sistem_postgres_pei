# 14 — Sistema Legado en Access

Base analizada: `BD-ESTADISTICA-PRODUCCION 2019_Backup.accdb`, aproximadamente 290 MB, 28 tablas de usuario.
Se leyó únicamente estructura y diccionarios; no se extrajeron datos operativos de las tablas de carga.

El propósito del análisis fue **validar el modelo propuesto** contra el sistema que efectivamente
se usa en producción, no migrar código ni replicar su diseño.

## Modelo que usa Access

```
VARIABLES RED  (dominio)
    └── COD-TIPO-REGISTRO  (tipo de registro; Campo1 = código de variable)
            └── COD-ESPEC-MEDICA  (prestación o detalle)

codigo depto-maspbs  (19 departamentos)
    └── codigo distrito-maspbs  (256 distritos)
            └── Establecimiento de salud  (Dpto_Region + Cod_Distr + Distrito)

AÑOS MESES  (período estadístico, formato "01-ENERO-2016")
    └── CARGA METRO*  (tablas de hechos)
            + Fecha de carga / Fecha de cierre  (distintas del período)
            + Estado de carga planilla mes
```

## Tablas relevantes

| Rol | Tablas Access | Volumen |
|---|---|---|
| Dominios de variable | `VARIABLES RED`, `VARIABLES RED 2019` | 16 dominios |
| Tipos de registro | `COD-TIPO-REGISTRO`, `COD-TIPO-REGISTRO 2019` | ~125 |
| Prestaciones y detalle | `COD-ESPEC-MEDICA`, `COD-ESPEC-MEDICA 2019` | ~1137 filas |
| Geografía | `codigo depto-maspbs`, `codigo distrito-maspbs` | 19 y 256 |
| Establecimientos | `Establecimiento de salud` y variantes por departamento | ~131 a 135 |
| Período | `AÑOS MESES` | 96 períodos |
| Hechos | `CARGA METRO 2016`, `CARGA METRO 20161`, tablas de totales | ~140.000 a 390.000 filas |
| Estado de carga | `Estado de carga planilla mes` | Nuevo, Facturado, otros |
| Medicamentos | `MEDIC_E_INSUMOS` | Catálogo aparte |
| Usuarios | `Usuarios` | Credenciales propias del sistema legado |

Advertencia de nomenclatura: las tablas `DPTO` y `SERVICIOS` **no son geografía**:
representan servicios hospitalarios internos.

## Hallazgos que confirman el diseño propuesto

| Hallazgo Access | Confirma |
|---|---|
| Jerarquía dominio → tipo de registro → prestación | La estructura de `variables salud.xls` y el diseño de `variable_definitions` |
| `Mm_Aa` como período estadístico y `Fecha de carga` como momento de digitación | La separación entre `periodo_anio` / `periodo_mes` y `submitted_at` |
| Distrito existe con `Cod_Distr` ligado a departamento | La jerarquía departamento → distrito → establecimiento (Hohenau, Fram) |
| La captura no es una tabla por planilla: son filas tipadas por tipo de registro más detalle y cantidades | El modelo EAV de `records` y `record_values`, en lugar de tablas `sp1` a `sp14` |
| Existe un estado de carga por planilla y mes | El flujo de estados borrador, enviado, aprobado, objetado |
| Catálogo de medicamentos e insumos separado | Los catálogos reutilizables independientes del formulario |

El punto más importante: **el sistema legado ya trabajaba con un modelo cercano a EAV**.
Las planillas SP nunca fueron tablas fijas en Access; eran combinaciones de tipo de registro,
detalle y cantidad. El motor metadata-driven formaliza y generaliza ese mismo enfoque.

## Diferencias deliberadas

| Access | Módulo nuevo | Razón |
|---|---|---|
| Diccionarios duplicados por año (`... 2019`) | Una sola tabla con vigencia y versionado de formulario | Evita divergencia entre años |
| Tablas de carga separadas por año (`CARGA METRO 2016`) | Una tabla `records` con `periodo_anio` | Consultas multianuales sin unir tablas |
| Tabla `Usuarios` con credenciales propias | Usuarios y roles de SIPLAN con Spatie | Una sola identidad y auditoría unificada |
| Códigos entre paréntesis en las planillas | Se ignoran | Eran identificadores internos de Access |
| Variantes de `Establecimiento de salud` por departamento | Una sola tabla `establecimientos` con FK a distrito | Normalización |
| Fórmulas de indicadores en consultas Access | AST en JSONB configurable desde la UI | Indicadores nuevos sin tocar código |

## Aprovechamiento posible

| Elemento | Uso |
|---|---|
| `codigo distrito-maspbs` (256 distritos) | Fuente candidata para el seeder de `distritos`, que el Excel no trae |
| `COD-ESPEC-MEDICA` (~1137 prestaciones) | Referencia de contraste contra las 553 prestaciones del Excel de variables |
| `MEDIC_E_INSUMOS` | Carga inicial del catálogo de medicamentos para SP14 |
| Histórico de `CARGA METRO` | Migración de datos históricos, fuera del alcance de las fases F0 a F7 |

La migración del histórico no está incluida en el roadmap actual. Si se decide hacerla,
requiere una fase adicional con mapeo de tipo de registro y detalle hacia `catalog_items`,
y conversión de `Mm_Aa` a `periodo_anio` y `periodo_mes`.

## Nota de acceso

El archivo permanece en un recurso de red compartido y estaba abierto en Access durante el análisis,
por lo que se leyó en modo compartido sin copiarlo localmente.
Si se necesita un análisis más profundo (relaciones y consultas guardadas), conviene cerrar Access
y dejar una copia sin datos sensibles en `.docs-bio/`.
