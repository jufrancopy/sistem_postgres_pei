# 05. Matriz de Equiparación MSPBS ↔ IPS — RIISS IPS

## 1. El Marco de Equiparación Oficial

Para superar la discrepancia histórica entre la nomenclatura normativa del Ministerio de Salud (MSPBS) y la estructura funcional del Instituto de Previsión Social (IPS), SIPLAN implementa una **Matriz de Equiparación Dual**:

| Grado | Nivel | Tipología Oficial MSPBS | Tipología Homologada IPS | Criterios Estructurales Exigidos |
|:---:|:---:|---|---|---|
| **Grado 1** | **Nivel I** | Puesto de Salud / Dispensario | Puesto Sanitario / Dispensario Médico | Consulta ambulatoria básica, enfermería, curaciones y vacunación. |
| **Grado 2** | **Nivel I** | Centro de Salud Tipo B / USF Ampliada | Clínica Periférica Básica / UBR | Consultas de especialidades básicas (Clínica, Pediatría, Gineco-Obstetricia), odontología y laboratorio básico. |
| **Grado 3** | **Nivel II** | Hospital Distrital / Centro Tipo A | Unidad Sanitaria / Hospital Distrital IPS | 🏥 Hosp, 🛏️ Internación general, 🚨 Urgencias 24h, 🔪 Cirugía menor/mediana, Rx y ecografía. |
| **Grado 4** | **Nivel III** | Hospital Regional / Integrado | Hospital Regional IPS | 🏥 Hosp, 🛏️ Internación polivalente, 🔪 Quirófano mayor, 🫀 UTI (Adultos/Ped.), 🚨 Urgencias 24h, Banco de sangre y tomografía. |
| **Grado 5** | **Nivel III** | Hospital General / Interregional | Hospital Interregional / Hospital de Área | 🏥 Hosp, 🛏️ Camas de alta complejidad, 🔪 Quirófanos de alta especialidad, 🫀 UTI polivalente, resonancia y hemodinamia. |
| **Grado 6** | **Nivel IV** | Hospital Especializado / Alta Complejidad | Hospital Central / HEQ | 🏥 Hosp, 🛏️ Cuidados críticos intensivos, 🔪 Cirugías de alta complejidad/trasplantes, 🫀 UTI Nivel IV, supra-especialidades. |

---

## 2. Gestión Interactiva en DataTables y Modales AJAX

En la Pestaña 2 de Ajustes (`/riiss/configuracion#tab-complejidad`), la matriz se gestiona a través de un **DataTable interactivo** con:
* **Badges visuales de estado** para cada criterio (`flag-si` / `flag-no`).
* **Conteo en tiempo real** de los establecimientos asignados a cada grado.
* **Modal AJAX**: La edición de colores, niveles y requisitos estructurales se guarda mediante `PUT /riiss/complejidad/{id}` actualizando el DOM y la tabla instantáneamente sin recargar la página.
