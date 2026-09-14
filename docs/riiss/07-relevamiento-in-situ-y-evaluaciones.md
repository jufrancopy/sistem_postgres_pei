# 07. Relevamiento In Situ y Motor de Gap Analysis — RIISS IPS

## 1. El Proceso de Inspección In Situ

Durante la visita técnica a un establecimiento, los auditores de la Dirección de Planificación ejecutan el relevamiento estructurado en tiempo real a través de tabletas o dispositivos portátiles:

1. **Datos de la Visita**: Fecha, equipo evaluador, directores y referentes locales presentes.
2. **Georreferenciación**: Captura de coordenadas GPS (`latitud`, `longitud`) integradas con mapas Leaflet.
3. **Cuestionario Dinámico por 5 Dimensiones**: Respuestas con opciones de `Sí`, `No` o `No Aplica`, acompañadas de observaciones puntuales.
4. **Evidencia Fotográfica con Lightbox**: Subida de fotografías de salas de espera, quirófanos, farmacia y áreas críticas con vista ampliada interactiva.

---

## 2. Motor de Gap Analysis (Análisis de Brechas)

El servicio `GapAnalysisService` evalúa automáticamente las respuestas registradas en comparación con el perfil estándar normativo para el grado de complejidad:

$$\text{Porcentaje de Cumplimiento} = \frac{\sum (\text{Respuestas 'Sí'} \times \text{Ponderación})}{\sum (\text{Preguntas Requeridas} \times \text{Ponderación})} \times 100$$

### Veredicto Técnico Automatizado:
* 🟢 **CUMPLE ($\ge 90\%$)**: El establecimiento satisface plenamente los requisitos de su cartera declarada y estándares de habilitación.
* 🟡 **CUMPLE PARCIALMENTE ($70\% - 89.9\%$)**: Presenta brechas no críticas; se emite recomendación de plan de mejora en un plazo de 90 días.
* 🔴 **NO CUMPLE ($< 70\%$)**: El establecimiento no alcanza los estándares mínimos para su grado declarado; amerita recategorización formal o intervención técnica prioritaria.
