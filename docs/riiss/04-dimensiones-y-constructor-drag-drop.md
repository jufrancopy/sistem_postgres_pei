# 04. Dimensiones Estratégicas y Constructor Drag & Drop — RIISS IPS

## 1. Las 5 Dimensiones Estratégicas

Para organizar rigurosamente los formularios dinámicos y dar respuesta al **Estudio Consolidado de Cartera de Servicios 2026**, el sistema segmenta todas las secciones y preguntas en **5 Dimensiones Estratégicas**:

| Dimensión | Código | Icono | Color | Alcance Temático |
|---|---|---|---|---|
| **1. Cartera de Servicios** | `cartera_servicios` | `fa-stethoscope` | `#0284c7` (Azul Médico) | Consultas ambulatorias, especialidades médicas, procedimientos diagnósticos e intervenciones quirúrgicas. |
| **2. Infraestructura** | `infraestructura` | `fa-building` | `#10b981` (Verde Esmeralda) | Edificaciones, accesibilidad, rampas, quirófanos, áreas de internación, instalaciones hidrosanitarias y eléctricas. |
| **3. Talento Humano** | `talento_humano` | `fa-users` | `#f59e0b` (Ámbar) | Médicos especialistas, guardias médicas 24/7, enfermería, personal de blanco, regencias y administrativos. |
| **4. Medicamentos e Insumos** | `medicamentos_insumos` | `fa-pills` | `#8b5cf6` (Púrpura) | Farmacia interna, cadena de frío, stock de medicamentos del Vademécum IPS, reactivos de laboratorio y equipamiento biológico. |
| **5. Gobernanza y Procesos** | `gobernanza_procesos` | `fa-file-shield` | `#64748b` (Pizarra) | Resoluciones de habilitación MSPBS, manuales de bioseguridad, protocolos clínicos y organigrama institucional. |

---

## 2. Constructor Visual Drag & Drop (Sortable.js)

La pantalla de **Ajustes RIISS** (`/riiss/configuracion#tab-formularios`) incorpora un editor visual bidireccional:

```
┌───────────────────────────────────────┬────────────────────────────────────────────────────────┐
│   PANEL IZQUIERDO (BANCO UNIVERSAL)   │          PANEL DERECHO (CONSTRUCTOR ACTIVO)            │
├───────────────────────────────────────┼────────────────────────────────────────────────────────┤
│ [Todas las Dimensiones ▼] [Buscar...] │ [Filtro Dimensión] [Filtro Grado MSPBS ↔ IPS]          │
│                                       │                                                        │
│ 🗂️ Banco de Preguntas (3.200+)        │ 📂 Sección: Cardiología y Métodos Auxiliares           │
│ ┌───────────────────────────────────┐ │ ┌────────────────────────────────────────────────────┐ │
│ │ ⠿ ¿Dispone de electrocardiógrafo? │ │ │ ⠿ ¿Cuenta con consultorio exclusivo de cardiología?│ │
│ │   [+ Vincular] [Drag to right ➔]  │ │ │   [Grado 3+] [Obligatorio] [Editar] [Duplicar]     │ │
│ └───────────────────────────────────┘ │ └────────────────────────────────────────────────────┘ │
│ ┌───────────────────────────────────┐ │ ┌────────────────────────────────────────────────────┐ │
│ │ ⠿ ¿Posee rampa de acceso universal?│ │ │ ⠿ ¿Realiza ergometría y ecocardiograma Doppler?   │ │
│ │   [Infraestructura]               │ │ └────────────────────────────────────────────────────┘ │
│ └───────────────────────────────────┘ │ [+ Agregar Nueva Pregunta]                             │
└───────────────────────────────────────┴────────────────────────────────────────────────────────┘
```

### Características Clave:
1. **Selector de Dimensión Independiente**: El banco izquierdo permite buscar preguntas de cualquier dimensión (por ejemplo, tomar una pregunta de *Infraestructura* o *Equipamiento*) y vincularla o arrastrarla hacia una sección clínica en el panel derecho.
2. **Reutilización y Duplicación**: Botón `+ Vincular` para asociar una pregunta existente sin romper evaluaciones históricas.
3. **Reordenamiento en Tiempo Real**: Al soltar una pregunta (`onEnd` de Sortable.js), se ejecuta una petición AJAX a `POST /riiss/formularios/reordenar-preguntas`, actualizando los índices `orden` en PostgreSQL.
