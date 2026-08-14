# Módulo Bioestadística Sanitaria — Documentación Técnica

Motor **metadata-driven** de estadísticas sanitarias (estilo REDCap / DHIS2) para SIPLAN.
Las planillas **SP1 a SP14 no son tablas**: son configuración almacenada en base de datos.

## Principio rector

> Crear, modificar o eliminar un formulario estadístico **no requiere tocar código fuente ni crear migraciones**.

## Índice

| Doc | Contenido |
|---|---|
| [01-modelo-funcional.md](01-modelo-funcional.md) | Actores, roles, capacidades, casos de uso |
| [02-arquitectura.md](02-arquitectura.md) | Clean Architecture aplicada a Laravel 10 |
| [03-modelo-datos.md](03-modelo-datos.md) | Entidades, relaciones, reglas de integridad |
| [03-er.mmd](03-er.mmd) | Diagrama entidad-relación (Mermaid) |
| [04-ddl-postgresql.sql](04-ddl-postgresql.sql) | DDL de referencia del schema `bioestadistica` |
| [05-api-rest.md](05-api-rest.md) | Endpoints REST `/api/bio/v1` |
| [06-importacion-excel.md](06-importacion-excel.md) | Wizard de importación e importadores dedicados |
| [07-indicadores-y-estadistica.md](07-indicadores-y-estadistica.md) | Motor de indicadores (AST) y capa estadística |
| [08-reportes-y-dashboards.md](08-reportes-y-dashboards.md) | Diseñador de reportes y dashboards configurables |
| [09-hospitalizacion-sp10.md](09-hospitalizacion-sp10.md) | Módulo híbrido nominativo SP10 |
| [10-seguridad-auditoria.md](10-seguridad-auditoria.md) | Roles Spatie, permisos, `audit_log` |
| [11-roadmap.md](11-roadmap.md) | Fases F0 a F7 |
| [12-analisis-planillas-sp.md](12-analisis-planillas-sp.md) | Análisis de `Formularios SP.xls` y `variables salud.xls` |
| [13-maestro-establecimientos.md](13-maestro-establecimientos.md) | Análisis de `ESTABLECIMIENTO_CON_ID.xlsx` y jerarquía geográfica |
| [14-legado-access.md](14-legado-access.md) | Estructura de la base Access 2019 heredada |

## Decisiones fijas

| Tema | Decisión |
|---|---|
| Schema BD | `bioestadistica` (aislado de `public`, `planificacion`, `estadistica`, `proyecto`) |
| Stack | Laravel 10, PHP 8.2, PostgreSQL, Blade + Material Dashboard, DataTables |
| Permisos | `spatie/laravel-permission` (ya instalado) |
| Gráficos | **Chart.js 3.9** (mismo patrón que SIESS) |
| Excel | `maatwebsite/excel` (a agregar en F1/F5) |
| PDF / CSV | DomPDF y `fputcsv` (ya presentes) |
| Aislamiento | Cero FK, joins o dependencias hacia RIISS / SIESS / PEI |
| Geografía | Maestro propio: **Departamento → Distrito → Establecimiento** |

## Fuentes funcionales

Los archivos fuente viven en `.docs-bio/` (carpeta **gitignored**, no versionada):

- `Formularios SP.xls` — layout de las 14 planillas
- `variables salud.xls` — diccionario de dominios, tipos de registro y prestaciones
- `ESTABLECIMIENTO_CON_ID.xlsx` — dimensión de establecimientos
- Base Access 2019 de producción (referencia del sistema legado)

## Estado

**Fase 0 completada**: documentación, modelo de datos, DDL de referencia, contrato de API y análisis de fuentes.

**Fase 1 iniciada (14/08/2026)**: schema y 13 tablas aplicadas, modelos Eloquent, constructor de
formularios, catálogos, maestro geográfico, clasificaciones, roles/permisos y seeders idempotentes.
Carga inicial: 18 departamentos, **249 distritos**, 140 establecimientos (95 con distrito asignado
por coincidencia de nombre; 45 pendientes de CRUD), 93 catálogos, 551 variables únicas y
14 formularios base. Fuente de distritos: `.docs-bio/codigo distrito.xlsx`.
