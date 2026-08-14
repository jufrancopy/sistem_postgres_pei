# SIPLAN — Hoja de Ruta de Desarrollo
*Última actualización: Mayo 2026*

---

## ✅ COMPLETADO — Resumen de lo implementado

### Módulo PEI (Plan Estratégico Institucional)
| Componente | Estado |
|---|---|
| Grupos y Eventos | ✅ |
| Perfiles FODA grupal/individual con IEA | ✅ |
| Cruce de Ambientes FO/FA/DO/DA consolidado | ✅ |
| PEI con árbol recursivo (NestedSet) | ✅ |
| Modelo de niveles dinámico por PEI | ✅ MECIP / IPS / Clásico / Personalizado |
| Estructura MECIP 2015 alineada | ✅ Objetivo Estratégico → Meta → Acción |
| Estrategias FODA vinculadas al Objetivo Estratégico | ✅ |
| RACI por acción (R/A/C/I) | ✅ |
| Semáforo Lead/Lag | ✅ |
| Alertas presupuestarias | ✅ |
| Dashboard de proceso (6 pasos) | ✅ |
| Tablero de monitoreo por PEI | ✅ |
| PDF del árbol del PEI | ✅ |

---

### Módulo SIESS (Sistema de Estadísticas e Información — Res. 266/2022)

#### Fase 1 — Infraestructura base ✅
| Componente | Estado |
|---|---|
| 6 tablas base en schema `estadistica` | ✅ |
| 9 módulos sembrados (AOP/PL/JU/RL/DI/DT/RH/INF/CAU) | ✅ |
| 22 indicadores sembrados | ✅ |
| 84 períodos generados (2020–2026 mensual + anual) | ✅ |
| Máquina de estados: borrador→pendiente→aprobado/objetado/silencio | ✅ |
| Job silencio administrativo (Art. 8 Res. 266/2022) — diario 7am | ✅ |
| Job alertas de vencimiento — diario 8am | ✅ |
| Comando `siess:generar-periodos` | ✅ |
| Dashboard SIESS con KPIs y gráficos Chart.js | ✅ |
| Gestión de extractos con DataTable y flujo de validación | ✅ |
| Selector de dirección responsable en 2 pasos (raíz → hija) | ✅ |

#### Fase 2 — Módulos de datos ✅
| Módulo | Tablas | Estado |
|---|---|---|
| AOP — Aportes y Trabajadores | aop_trabajadores, aop_empleadores, aop_recaudacion, aop_mora | ✅ |
| JU — Jubilaciones y Pensiones | ju_beneficiarios, ju_altas_solicitudes, pl_financiero | ✅ |
| DT — Tesorería y Contabilidad | dcp_presupuesto, dt_tesoreria | ✅ |
| RL — Subsidios y Riesgo Laboral | rl_subsidios | ✅ |
| DI — Inversiones | di_portafolio, di_prestamos_caja | ✅ |
| RH — Recursos Humanos | rh_nomina, rh_movimientos | ✅ |
| CAU — Atención al Usuario | cau_atencion, sal_suministros | ✅ |
| PL — Poblacional | pl_estructura, pl_historico | ✅ |

#### Fase 3 — Reportes Gerenciales ✅
| Componente | Estado |
|---|---|
| PDF Informe Gerencial consolidado (AOP + JU + DT) | ✅ |
| Exportación CSV para Tableau/BI (5 módulos) | ✅ |
| Vista de selección de reportes | ✅ |
| Seeder de datos de simulación | ✅ |

#### Notificaciones y Acceso ✅
| Componente | Estado |
|---|---|
| Tabla `siess_notificaciones` | ✅ |
| Notificaciones automáticas en cada cambio de estado | ✅ |
| Campana en navbar con badge y dropdown | ✅ |
| Vinculación usuario → organigrama → notificación | ✅ |
| Vista home para usuarios Participantes | ✅ |
| Sidebar diferenciado por rol | ✅ |
| Redirección post-login por rol | ✅ |
| Control de botones Aprobar/Objetar (no puede aprobar el propio extracto) | ✅ |

---

### Módulo Bioestadística Sanitaria (planillas SP1–SP14)

Motor **metadata-driven** de formularios e indicadores estadísticos (estilo REDCap / DHIS2),
en schema propio `bioestadistica`, aislado de PEI, SIESS y RIISS.
Las planillas SP no son tablas: son configuración almacenada en base de datos.

📖 **Documentación técnica completa: [`docs/bioestadistica/`](docs/bioestadistica/README.md)**

| Fase | Alcance | Estado |
|---|---|---|
| F0 | Documentación, modelo de datos, DDL, contrato de API, análisis de fuentes | ✅ |
| F1 | Migraciones, geografía (depto→distrito→establecimiento), CRUD de metadata, seeders, roles | 🟡 En curso — core + 249 distritos cargados; 45 establecimientos sin distrito |
| F2 | Motor de captura `records`/`record_values` con período estadístico | 🔲 |
| F3 | Motor de indicadores (AST) y capa estadística | 🔲 |
| F4 | Dashboards configurables Chart.js y diseñador de reportes | 🔲 |
| F5 | Wizard de importación Excel | 🔲 |
| F6 | Módulo híbrido de hospitalización SP10 | 🔲 |
| F7 | Auditoría, permisos finos y hardening | 🔲 |

Ver el detalle de cada fase en [`docs/bioestadistica/11-roadmap.md`](docs/bioestadistica/11-roadmap.md).

---

## 🔲 PENDIENTE — Próximas fases

### Fase A — Observatorio Institucional (MECIP — impacto directo en puntuación)
- [ ] Vista pública de datos aprobados (Art. 5 y 6 Res. 266/2022)
- [ ] Filtros por módulo, período y tipo de indicador
- [ ] Sin autenticación requerida (acceso público)
- [ ] Exportación directa desde el observatorio

### Fase B — Módulo de Riesgos (MECIP)
- [ ] La tabla `risks` ya existe en BD — falta UI completa
- [ ] CRUD de riesgos con probabilidad, impacto y plan de mitigación
- [ ] Vinculación con objetivos del PEI
- [ ] Semáforo de riesgos (bajo/medio/alto/crítico)
- [ ] Dashboard de riesgos por dirección

### Fase C — Monitoreo por Dirección
- [ ] Vista de monitoreo para cada dirección (solo sus acciones RACI)
- [ ] Actualización inline de `progress` y `presupuesto_ejecutado`
- [ ] Historial de actualizaciones con auditoría
- [ ] Notificación cuando semáforo cambia a rojo

### Fase D — Mejoras SIESS
- [ ] Módulos INF (Infraestructura) con vista y gráficos
- [ ] Seeder extendido para RL, RH y CAU
- [ ] Integración con APIs externas (extracción automática Art. 5)
- [ ] Views materializadas para Tableau (schema `reporting`)
- [ ] Notificaciones por correo electrónico (Laravel Mail + Queue)

### Fase E — Control de acceso granular
- [ ] Roles específicos SIESS: `siess.planificacion`, `siess.validador`, `siess.gerencia`
- [ ] Middleware de nivel PEI (quién puede editar cada nivel del árbol)
- [ ] Auditoría completa de cambios

### Fase F — Integración IA (Gemini)
- [ ] Sugerencia automática de estrategias basada en cruce FODA
- [ ] Redacción asistida de Misión y Visión
- [ ] Análisis de coherencia entre objetivos y estrategias SIESS
- [ ] Generación automática de resumen ejecutivo para el PDF gerencial

---

## Orden de ejecución recomendado

```
Fase A (Observatorio)     ← impacto MECIP inmediato
    ↓
Fase B (Riesgos)          ← impacto MECIP inmediato
    ↓
Fase C (Monitoreo)        ← cierra el ciclo PEI
    ↓
Fase D (SIESS mejoras)    ← completitud del sistema
    ↓
Fase E (Acceso granular)  ← producción real
    ↓
Fase F (IA)               ← valor agregado
```

---

## Notas técnicas

- Stack: Laravel 10 / PHP 8.2 / PostgreSQL 14 / Bootstrap 4
- Árbol jerárquico: `kalnoy/nestedset` (NodeTrait)
- Permisos: `spatie/laravel-permission`
- PDF: `barryvdh/laravel-dompdf`
- Gráficos: Chart.js 3.9
- IA: `google-gemini-php/laravel`
- Schemas BD: `public`, `planificacion`, `estadistica`, `proyecto`, `bioestadistica`
- Jobs programados: silencio administrativo (7am), alertas vencimiento (8am), generación períodos (día 28)

---

*Este documento se actualiza a medida que se completan los ítems.*
