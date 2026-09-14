# 03. Gestión de Proyectos y Tareas Operativas

## 1. De la Estrategia a la Acción

El subsistema de **Actividades y Tareas** permite a los equipos de trabajo ejecutar las metas del PEI mediante tableros dinámicos y seguimiento de tareas:

```mermaid
stateDiagram-v2
    [*] --> Pendiente: Creación de Tarea / Hito
    Pendiente --> EnProceso: Asignación a Responsable (Inicio)
    EnProceso --> EnRevision: Carga de Evidencia / Entregable
    EnRevision --> Completada: Aprobación por Coordinador
    EnProceso --> Vencida: Supera Fecha Límite sin completar
    Vencida --> Completada: Regularización y cierre
    Completada --> [*]
```

### Funcionalidades:
* **Asignación Multidisciplinaria**: Asignación de colaboradores responsables y observadores.
* **Control de Plazos y Alertas**: Detección automática de tareas vencidas y avisos preventivos.
* **Gamificación & Reconocimiento (SIPLAN GO)**: Puntos y medallas que incentivan la productividad y la colaboración interdepartamental.
