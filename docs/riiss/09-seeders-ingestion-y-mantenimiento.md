# 09. Guía de Seeders, Ingestión y Mantenimiento — RIISS IPS

## 1. Ingestión de Estudios y Formularios Oficiales

El sistema cuenta con un motor de importación e ingestión masiva desarrollado en PHP nativo (`PhpOffice\PhpSpreadsheet`) que permite cargar catálogos completos sin dependencias externas de Python ni riesgo de sobrescritura de datos existentes:

```bash
# Ingestar Estudio Consolidado (2.742+ prestaciones) y Banco de Preguntas MSPBS
php artisan db:seed --class=RiissEstudioConsolidadoSeeder

# Cargar Tipos de Complejidad (Grados 1 al 6)
php artisan db:seed --class=ComplejidadTiposSeeder

# Sincronizar Roles RIISS
php artisan db:seed --class=RolesTableSeeder
```

---

## 2. Archivos Oficiales de Respaldo

Los archivos fuente se encuentran versionados en el directorio `backups/RIISS/`:
* `Cartera de servicio estandar consolidado 2026 09 11.xlsx`: Estudio técnico completo con prestaciones, especialidades y ponderaciones.
* `Formulario para habilitacion Establecimiento sanitario MSPBS.xlsx`: Banco de preguntas de infraestructura, equipamiento, talento y gobernanza.
* `Vademecum Oficial IPS 2026.xlsx`: Catálogo corporativo de medicamentos autorizados.

---

## 3. Idempotencia y Seguridad en Producción

* **Principio de No Destrucción**: Todos los seeders utilizan `firstOrCreate` y `updateOrCreate`.
* **Preservación Histórica**: Nunca se ejecutan sentencias destructivas (`TRUNCATE` o `DROP TABLE`), asegurando que las evaluaciones en curso o cerradas, las firmas de directores y las evidencias fotográficas nunca se pierdan.
