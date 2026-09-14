# 03. Modelo de Datos y Diagrama ER — RIISS IPS

## 1. Diagrama Entidad-Relación (Mermaid)

```mermaid
erDiagram
    COMPLEJIDAD_TIPOS ||--o{ ESTABLECIMIENTOS : "clasifica a"
    ESTABLECIMIENTOS ||--o{ EVALUACIONES : "recibe inspecciones"
    ESTABLECIMIENTOS ||--o{ VALIDACION_ESPECIALIDADES_REGISTRO : "valida especialidades"
    
    EVALUACIONES ||--o{ EVALUACION_RESPUESTAS : "contiene"
    EVALUACIONES ||--o{ GAP_ANALYSIS : "genera brechas"
    
    FORMULARIO_SECCIONES ||--o{ FORMULARIO_PREGUNTAS : "agrupa"
    FORMULARIO_PREGUNTAS ||--o{ EVALUACION_RESPUESTAS : "es respondida en"
    
    SESIONES_VALIDADOR ||--o{ VALIDACION_ESPECIALIDADES_REGISTRO : "agrupa validaciones"
    RIISS_ESPECIALIDADES ||--o{ VALIDACION_ESPECIALIDADES_REGISTRO : "es validada"
    RIISS_ESPECIALIDADES ||--o{ RIISS_ESPECIALIDAD_MEDICAMENTOS : "tiene medicamentos autorizados"
    RIISS_MEDICAMENTOS_VADEMECUM ||--o{ RIISS_ESPECIALIDAD_MEDICAMENTOS : "se relaciona con"

    COMPLEJIDAD_TIPOS {
        bigint id PK
        int grado
        string nombre
        int nivel_atencion
        string color
        boolean es_hospitalario
        boolean requiere_internacion
        boolean requiere_quirofano
        boolean requiere_uti
        boolean requiere_urgencias
    }

    ESTABLECIMIENTOS {
        string id_establecimiento PK
        string nombre_oficial
        string tipologia_clasificacion
        string tipo_ips_homologado
        int grado_complejidad_evaluado
        string departamento
        string distrito
        decimal latitude
        decimal longitude
    }

    FORMULARIO_SECCIONES {
        bigint id PK
        string seccion
        string sub_seccion
        string dimension
        string icono
        int orden
        boolean activa
    }

    FORMULARIO_PREGUNTAS {
        bigint id PK
        bigint formulario_seccion_id FK
        string dimension
        text pregunta
        string tipo_respuesta
        int grado_complejidad_min
        decimal peso_ponderacion
        boolean es_requerido
        boolean activa
        int orden
    }

    EVALUACIONES {
        bigint id PK
        string id_establecimiento FK
        date fecha_evaluacion
        string estado
        decimal porcentaje_cumplimiento
        decimal pct_habilitacion
        string clasificacion_resultado
        jsonb fotos
        jsonb evaluadores
    }

    EVALUACION_RESPUESTAS {
        bigint id PK
        bigint evaluacion_id FK
        bigint formulario_pregunta_id FK
        string dimension
        string respuesta
        text observacion
    }

    GAP_ANALYSIS {
        bigint id PK
        bigint evaluacion_id FK
        string dimension
        string servicio_nombre
        string estado
        int prioridad
    }

    SESIONES_VALIDADOR {
        bigint id PK
        string token UK
        string codigo_acceso
        string evaluador_nombre
        string evaluador_cargo
        string estado
        timestamp fecha_expiracion
    }
```

---

## 2. Diccionario de Tablas Principales

### `formulario_secciones`
Representa los bloques organizadores y agrupadores de preguntas.
* `dimension`: Enum (`cartera_servicios`, `infraestructura`, `talento_humano`, `medicamentos_insumos`, `gobernanza_procesos`).
* `seccion`: Nombre del grupo temático principal (VARCHAR 255).
* `sub_seccion`: Sub-agrupador o especialidad médica específica.
* `icono`: Clase FontAwesome para la representación visual en la UI (`fa-stethoscope`, `fa-building`, etc.).

### `formulario_preguntas`
Banco universal de preguntas evaluativas.
* `dimension`: Dimensión estratégica de pertenencia.
* `tipo_respuesta`: `si_no_na` (Sí/No/No Aplica), `si_no`, `texto`, `numero`, `checklist`.
* `grado_complejidad_min`: Grado mínimo normativo (1 al 6) requerido para exigir la pregunta.
* `peso_ponderacion`: Ponderación numérica (0.1 a 10.0) para el cálculo de porcentaje.

### `evaluaciones` y `evaluacion_respuestas`
Registro de la inspección técnica *in situ*.
* `porcentaje_cumplimiento`: Porcentaje global calculado sobre preguntas requeridas.
* `pct_habilitacion`: Porcentaje de requisitos estructurales indispensables.
* `clasificacion_resultado`: Veredicto técnico (`CUMPLE`, `CUMPLE_PARCIALMENTE`, `NO_CUMPLE`).
* `fotos`: JSON con URLs, títulos y descripciones de las evidencias fotográficas.
