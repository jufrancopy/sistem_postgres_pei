# SIPLAN — Sistema de Planificación Estratégica Institucional

## 🎯 PROPÓSITO DEL SISTEMA
SIPLAN no es un simple gestor de tareas. Es una plataforma de inteligencia estratégica diseñada para el **Instituto de Previsión Social (IPS)**. Su objetivo es cerrar la brecha entre el diseño estratégico (Talleres FODA/PEI) y la ejecución operativa, eliminando la subjetividad mediante métricas cuantitativas.

---

## 🧠 REGLAS DE NEGOCIO CRÍTICAS (PARA AGENTES DE IA)
*Al analizar, sugerir cambios o generar código, respete estrictamente estas lógicas:*

### 1. Validación de Realidad (FODA-IEA)
- **Concepto:** No se aceptan "Fortalezas" por percepción.
- **Regla:** Un aspecto se valida mediante el **Índice de Eficiencia de Activos (IEA)**.
- **Fórmula:** `IEA = (Promedio Desempeño 6m) / (Inversión Histórica 6m)`.
- **Umbrales:** IEA < 0.4 es **Debilidad** (Inversión sin impacto). IEA > 0.8 es **Fortaleza**.
- **Excepción:** Si no hay mejora en el SIH (Sistema Integrado de Salud), se marca como Debilidad por "Obsolescencia Funcional".

### 2. Trazabilidad y Responsabilidad (RACI)
- **Regla de Oro:** Ninguna estrategia o acción existe sin un **Accountable (A)** único.
- **Participación:** El sistema debe capturar el `id_autor` y la dependencia del redactor original para evitar el desentendimiento institucional.
- **Conexión:** El FODA debe ser el insumo directo (parent_id) de las estrategias del PEI.

### 3. Monitoreo Multidimensional (Lead vs. Lag)
- **Lead Measures (Predicción):** Indicadores de esfuerzo (ej. "Nro. de capacitaciones"). **Obligatorios**.
- **Lag Measures (Resultado):** Indicadores históricos (ej. "Nro. de citas atendidas").
- **Semáforo:** 
    - 🟢 Verde: Lead y Lag cumpliéndose.
    - 🟡 Amarillo (Cuello de Botella): Lead al 100%, pero Lag estancado.
    - 🔴 Rojo: Incumplimiento crítico.

### 4. Alerta Presupuestaria
- Detectar subejecución: (Meta < 20% && Presupuesto > 80%).

---

## 🛠 STACK TÉCNICO
- **Backend:** Laravel 10+ / PHP 8.2 / Django (Servicios de IA).
- **Frontend:** React / Blade / DataTables.
- **Estructuras:** Árboles jerárquicos mediante `Nested Sets` (NodeTrait).
- **IA Integration:** Gemini API / OpenAI SDK para análisis de encuestas y redacción de estrategias.

---

## 📂 ESTRUCTURA DE DATOS CLAVE
- `enunciados_estrategicos`: Almacena el núcleo de la estrategia con metadatos de autoría y contexto.
- `metas`: Tabla relacional con campos para `tipo_indicador` (Lead/Lag) y umbrales de semaforeo.
- `raci_assignments`: Tabla pivote para roles R, A, C, I vinculados a usuarios.

---

## 📋 INSTRUCCIONES PARA LA IA
1. **Refactorización:** Al sugerir cambios en controladores de Planificación, asegúrate de que no rompan la validación de "Exactamente un Accountable".
2. **Consultas:** Siempre prioriza Eloquent y utiliza `withCount` o `withSum` para los cálculos del IEA para optimizar rendimiento.
3. **MECIP:** Toda lógica debe ser compatible con los estándares de control interno de Paraguay (MECIP).
4. **Contexto:** Si detectas que un activo no tiene KPIs asociados, genera una advertencia de "Estrategia Huérfana".

---
*Documento de referencia para el desarrollo del SIPLAN - v2.0*