# 08. Fichas Técnicas Públicas, Actas y Reportes — RIISS IPS

## 1. Ficha Técnica Pública con Código QR

Cada evaluación cerrada genera una **Ficha Técnica Digital** accesible públicamente mediante la ruta:
`/riiss/evaluaciones/{id}/resumen-publico`

```
┌────────────────────────────────────────────────────────────────────────┐
│  🏥 INSTITUTO DE PREVISIÓN SOCIAL — DIRECCIÓN DE PLANIFICACIÓN        │
│  FICHA TÉCNICA OFICIAL DE CARACTERIZACIÓN RIISS                        │
├────────────────────────────────────────────────────────────────────────┤
│  Establecimiento: Hospital Regional de Encarnación                     │
│  Tipología: Grado 4 (Nivel III) | Departamento: Itapúa                 │
│  Cumplimiento Global: [ 94.2% - CUMPLE ] | Habilitación: [ 98.0% ]     │
├────────────────────────────────────────────────────────────────────────┤
│  • Resumen de Especialidades Médicas Validadas                         │
│  • Evidencia Fotográfica y Galería de Inspección                       │
│  • Mapa de Ubicación Geográfica                                        │
│  • Firmas Digitales del Equipo Auditor y Dirección Médica              │
└────────────────────────────────────────────────────────────────────────┘
```

* **Diseño Responsive & Estándar de Impresión**: Optimizado con `@media print` para generar impresiones impecables sin cabeceras web innecesarias.
* **Código QR Dinámico**: Impreso en la esquina superior del acta física para verificación de autenticidad en terreno por auditores o autoridades nacionales.

---

## 2. Generación de Actas en PDF (DomPDF + Base64)

Para asegurar compatibilidad en entornos locales y servidores en producción sin fallas de certificados SSL o rutas de archivos temporales:
1. **Conversión Base64 de Imágenes**: Los logotipos institucionales y escudos oficiales se codifican a `data:image/png;base64,...` antes de ser inyectados a la vista PDF.
2. **Firmas y Trazabilidad**: El acta incluye fecha, hora, IP, nombres completos del director del hospital y del evaluador líder.
