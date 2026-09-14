# 02. Arquitectura de Software y Módulos — RIISS IPS

## 1. Arquitectura General del Sistema

El módulo RIISS está construido bajo la arquitectura **MVC (Modelo - Vista - Controlador)** de **Laravel**, integrando capas de servicios especializadas, renderizado de componentes Blade interactivos y consumo asíncrono vía AJAX con JSON.

```mermaid
graph TD
    subgraph Cliente_Frontend [Frontend & UX]
        UI_Center[Centro RIISS - index.blade.php]
        UI_Config[Ajustes & Configuración - configuracion.blade.php]
        UI_Portal[Portal Validador Descentralizado]
        UI_Public[Ficha Técnica Pública con QR]
        SortableJS[Sortable.js - Drag & Drop]
        DataTables[DataTables.js]
        LeafletJS[Leaflet Maps]
    end

    subgraph Backend_Laravel [Controladores & Servicios]
        RiissCenterController[RiissCenterController]
        EvaluacionController[EvaluacionController]
        FormularioController[FormularioController]
        ComplejidadTipoController[ComplejidadTipoController]
        ValidacionEspecialidadesController[ValidacionEspecialidadesController]
        ValidacionFarmaceuticaController[ValidacionFarmaceuticaController]
        
        FormularioDinamicoService[FormularioDinamicoService]
        GapAnalysisService[GapAnalysisService]
    end

    subgraph Base_Datos_PostgreSQL [Persistencia PostgreSQL]
        DB_Establecimientos[(establecimientos)]
        DB_Evaluaciones[(evaluaciones & respuestas)]
        DB_Formularios[(formulario_secciones & preguntas)]
        DB_Cartera[(cartera_servicios)]
        DB_Vademecum[(riiss_medicamentos_vademecum)]
        DB_Sesiones[(sesiones_validador)]
    end

    UI_Center --> RiissCenterController
    UI_Config --> FormularioController
    UI_Config --> ComplejidadTipoController
    UI_Portal --> ValidacionEspecialidadesController
    UI_Portal --> ValidacionFarmaceuticaController
    UI_Public --> EvaluacionController

    RiissCenterController --> FormularioDinamicoService
    EvaluacionController --> FormularioDinamicoService
    EvaluacionController --> GapAnalysisService
    FormularioController --> FormularioDinamicoService

    FormularioDinamicoService --> DB_Formularios
    FormularioDinamicoService --> DB_Evaluaciones
    GapAnalysisService --> DB_Cartera
    ValidacionFarmaceuticaController --> DB_Vademecum
```

---

## 2. Mapa de Controladores Principales

| Controlador | Namespace | Responsabilidad Principal |
|---|---|---|
| `RiissCenterController` | `App\Http\Controllers\Admin\Riiss` | Tablero central RIISS, KPIs generales, mapa geográfico, matriz de validación y modal de establecimientos. |
| `FormularioController` | `App\Http\Controllers\Admin\Riiss` | Constructor Drag & Drop de preguntas, reordenamiento por secciones, duplicación, banco universal y tipologías. |
| `ComplejidadTipoController` | `App\Http\Controllers\Admin\Riiss` | Matriz de grados de complejidad (1 al 6), edición vía modal AJAX y recálculo automático de criterios de establecimientos. |
| `EvaluacionController` | `App\Http\Controllers\Admin\Riiss` | Relevamiento in situ, guardado de respuestas, fotos, ejecución de Gap Analysis, actas en PDF y ficha pública. |
| `ValidacionEspecialidadesController` | `App\Http\Controllers\Admin\Riiss` | Generación de enlaces tokenizados para directores, verificación de PIN, validación masiva y exportación de matriz a Excel. |
| `ValidacionFarmaceuticaController` | `App\Http\Controllers\Admin\Riiss` | Portal de Regulación Farmacéutica, cruce de especialidades con el Vademécum IPS y emisión de dictamen farmacéutico. |

---

## 3. Seguridad, Roles y Middleware

El acceso está protegido por el middleware `auth` y controlado mediante permisos Spatie:

```php
// Roles reconocidos por el módulo RIISS
$rolesAutorizados = [
    'Administrador',
    'Super Admin',
    'Coordinación RIISS',
    'Coordinador RIISS',
    'Analista - RIISS',
    'Analista RIISS',
];
```

* **Rutas Privadas (`/riiss/...`)**: Exigen autenticación y rol de Coordinación o Analista.
* **Rutas Públicas Tokenizadas (`/riiss/portal-validador/{token}`)**: Acceso protegido por token criptográfico SHA-256 de 64 caracteres y verificación de PIN numérico de 4 dígitos.
* **Ficha Técnica Pública (`/riiss/evaluaciones/{id}/resumen-publico`)**: Acceso de consulta mediante lectura de Código QR impreso en el acta oficial.
