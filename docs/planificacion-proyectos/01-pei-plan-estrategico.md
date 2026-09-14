# 01. Plan Estratégico Institucional (PEI) — Metodología y Arquitectura

## 1. Modelo de Cascada PEI (Master y Planes Derivados)

SIPLAN estructura el **Plan Estratégico Institucional** a través de un modelo jerárquico multinivel que conecta la visión del Consejo de Administración con las direcciones operativas:

```mermaid
graph TD
    PEI_Master[🎯 PEI Maestro Corporativo 2024-2028]
    PEI_Master --> Eje1[Eje 1: Cobertura y Calidad Asistencial]
    PEI_Master --> Eje2[Eje 2: Sostenibilidad Financiera y Actuarial]
    PEI_Master --> Eje3[Eje 3: Transformación Digital e Innovación]
    PEI_Master --> Eje4[Eje 4: Gestión del Talento Humano y Gobernanza]

    Eje1 --> ObjEst1[Objetivo 1.1: Expandir la Red Asistencial RIISS]
    ObjEst1 --> AccEst1[Acción 1.1.1: Estandarizar Carteras de Servicios]
    AccEst1 --> Meta1[Meta Física: 100% de Hospitales Caracterizados]
    Meta1 --> Ind1[Indicador: % Hospitales con Ficha Técnica Aprobada]

    PEI_Master -.->|Herencia / Descentralización| PEI_Dep[📌 Plan Operativo Anual por Dirección]
```

---

## 2. Motor de Semaforización Automática

La evaluación periódica contrasta la **Meta Programada** con el **Valor Logrado**:

$$\text{Eficacia (\%)} = \left( \frac{\text{Valor Logrado}}{\text{Meta Programada}} \right) \times 100$$

* 🟢 **Verde ($\ge 85\%$)**: Meta cumplida o en curso satisfactorio.
* 🟡 **Amarillo ($60\% - 84.9\%$)**: Alerta preventiva; requiere ajuste de cronograma o reasignación de recursos.
* 🔴 **Rojo ($< 60\%$)**: Desvío crítico; amerita informe de justificación y plan de contingencia ante la Dirección de Planificación.
