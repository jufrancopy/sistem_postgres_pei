# Inventario tabular — Hospitales Área Central e Interior

Fuente: `.docs-bio/Organigrama.pdf` (IPS v03)  
Alcance: solo ramas **Dirección de Hospitales del Área Central** y **del Área Interior**.  
Reglas: establecimientos fuera del árbol; letras (A)/(B) omitidas en el nombre; Hospital 12 de Junio = misma base que Hospital de Luque.

Columnas: `tipo` | `nombre` | `padre` | `pagina` | `notas`

---

## 0. Ancla

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| gerencia | Gerencia de Salud | (raíz fase 1) | 39 | |
| direccion | Dirección de Hospitales del Área Central | Gerencia de Salud | 43 | |
| direccion | Dirección de Hospitales del Área Interior | Gerencia de Salud | 54 | |

---

## 1. Dir. Hospitales Área Central — staff propio (pág. 43)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| oficina_coordinacion | Oficina de Coordinación | Dirección de Hospitales del Área Central | 43 | |
| departamento | Departamento Apoyo Asistencial | Dirección de Hospitales del Área Central | 43 | |
| departamento | Departamento Administrativo | Dirección de Hospitales del Área Central | 43 | |
| seccion | Sección Administración de Personal | Departamento Administrativo (Área Central) | 43 | |
| seccion | Sección Servicios Generales | Departamento Administrativo (Área Central) | 43 | |
| seccion | Sección Apoyo Logístico | Departamento Administrativo (Área Central) | 43 | |

> Nota: si hay dos “Departamento Administrativo” (Central vs Interior), al cargar usar padre + nombre completo o código interno para no colisionar.

---

## 2. Direcciones de locales — Área Central (pág. 44)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Clínica Periférica Boquerón | Dirección de Hospitales del Área Central | 44 | estructura pág. 45 |
| direccion | Dirección Clínica Periférica Nanawa | Dirección de Hospitales del Área Central | 44 | estructura pág. 45 |
| direccion | Dirección Clínica Periférica Isla Poí | Dirección de Hospitales del Área Central | 44 | estructura pág. 45 |
| direccion | Dirección Clínica Periférica Campo Vía de Capiatá | Dirección de Hospitales del Área Central | 44 | estructura pág. 45 |
| direccion | Dirección Clínica Periférica Yrendague | Dirección de Hospitales del Área Central | 44 | estructura pág. 45 |
| direccion | Dirección Unidad Sanitaria Ypacaraí | Dirección de Hospitales del Área Central | 44 | estructura pág. 46 |
| direccion | Dirección Unidad Sanitaria Villeta | Dirección de Hospitales del Área Central | 44 | estructura pág. 46 |
| direccion | Dirección Unidad Sanitaria San Antonio | Dirección de Hospitales del Área Central | 44 | estructura pág. 46 |
| direccion | Dirección Puesto Sanitario Piquete Cué | Dirección de Hospitales del Área Central | 44 | estructura pág. 47 |
| direccion | Dirección Puesto Sanitario Itauguá | Dirección de Hospitales del Área Central | 44 | estructura pág. 47 |
| direccion | Dirección Puesto Sanitario Guarambaré | Dirección de Hospitales del Área Central | 44 | estructura pág. 47 |
| direccion | Dirección Hospital 12 de Junio | Dirección de Hospitales del Área Central | 44 | misma base que Luque (pág. 53) |
| direccion | Dirección Hospital Geriátrico Dr. Gerardo Buongermini | Dirección de Hospitales del Área Central | 44 | detalle pág. 52 |
| direccion | Dirección Hospital de Luque | Dirección de Hospitales del Área Central | 44 | detalle pág. 53 |
| direccion | Dirección Centro Odontológico | Dirección de Hospitales del Área Central | 44 | detalle págs. 50-51 |
| direccion | Dirección Centro de Salud Mental | Dirección de Hospitales del Área Central | 44 | detalle pág. 49 |
| direccion | Dirección Centro de Medicina Física y Rehabilitación | Dirección de Hospitales del Área Central | 44 | detalle pág. 48 |
| direccion | Dirección UBAS Itá | Dirección de Hospitales del Área Central | 44 | detalle pág. 51 |

---

## 3. Estructura interna — cada Clínica Periférica Central (pág. 45)

Repetir el bloque bajo **cada** Dirección Clínica Periférica (Boquerón, Nanawa, Isla Poí, Campo Vía de Capiatá, Yrendague).  
En la tabla, `{DIR_CP}` = nombre de esa dirección.

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| area | Área Médica | {DIR_CP} | 45 | no jerárquica |
| area | Área de Diagnóstico | {DIR_CP} | 45 | no jerárquica |
| area | Área Apoyo Administrativo | {DIR_CP} | 45 | no jerárquica |
| servicio | Consulta Ambulatoria Médico Odontológica | Área Médica ({DIR_CP}) | 45 | |
| servicio | Urgencias y Enfermería | Área Médica ({DIR_CP}) | 45 | |
| servicio | Imágenes | Área de Diagnóstico ({DIR_CP}) | 45 | |
| servicio | Colposcopia y PAP | Área de Diagnóstico ({DIR_CP}) | 45 | |
| servicio | Laboratorio | Área de Diagnóstico ({DIR_CP}) | 45 | |
| servicio | Electrocardiograma (E.C.G.) | Área de Diagnóstico ({DIR_CP}) | 45 | |
| seccion | Farmacia | Área Apoyo Administrativo ({DIR_CP}) | 45 | |
| seccion | Admisión y Documentación Clínica | Área Apoyo Administrativo ({DIR_CP}) | 45 | |
| seccion | Nutrición | Área Apoyo Administrativo ({DIR_CP}) | 45 | |
| seccion | Servicios Generales | Área Apoyo Administrativo ({DIR_CP}) | 45 | |
| seccion | Administración del Personal | Área Apoyo Administrativo ({DIR_CP}) | 45 | |

---

## 4. Estructura interna — cada US Central (pág. 46)

`{DIR_US}` = Ypacaraí | Villeta | San Antonio

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| area | Área Médica | {DIR_US} | 46 | no jerárquica |
| area | Área de Apoyo | {DIR_US} | 46 | no jerárquica |
| area | Área Apoyo Administrativo | {DIR_US} | 46 | no jerárquica |
| servicio | Consultorio | Área Médica ({DIR_US}) | 46 | |
| servicio | Enfermería | Área Médica ({DIR_US}) | 46 | |
| servicio | Farmacia | Área de Apoyo ({DIR_US}) | 46 | |
| servicio | Laboratorio | Área de Apoyo ({DIR_US}) | 46 | |

---

## 5. Estructura interna — cada PS Central (pág. 47)

`{DIR_PS}` = Piquete Cué | Itauguá | Guarambaré

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| area | Área Médica | {DIR_PS} | 47 | no jerárquica |
| area | Área de Farmacia | {DIR_PS} | 47 | no jerárquica |
| area | Área de Enfermería | {DIR_PS} | 47 | no jerárquica |
| area | Área Apoyo Administrativo | {DIR_PS} | 47 | no jerárquica |

---

## 6. Centro de Medicina Física y Rehabilitación (pág. 48)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| servicio | Servicio de Medicina Física y Rehabilitación | Dirección Centro de Medicina Física y Rehabilitación | 48 | |
| servicio | Servicio de Rehabilitación del Hospital Central | Dirección Centro de Medicina Física y Rehabilitación | 48 | hijo del centro (confirmado) |
| supervision | Supervisión de Medicina Física y Rehabilitación Área Central e Interior | Dirección Centro de Medicina Física y Rehabilitación | 48 | |
| seccion | Sección de Apoyo Administrativo | Dirección Centro de Medicina Física y Rehabilitación | 48 | |

---

## 7. Centro de Salud Mental (pág. 49)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| servicio | Servicio Asistencial | Dirección Centro de Salud Mental | 49 | |
| servicio | Servicio de Farmacia | Dirección Centro de Salud Mental | 49 | |
| seccion | Sección de Apoyo Administrativo | Dirección Centro de Salud Mental | 49 | |

---

## 8. Centro Odontológico (págs. 50-51)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| servicio | Servicio Odontológico | Dirección Centro Odontológico | 50 | |
| servicio | Servicio de Apoyo y Suministros Odontológicos | Dirección Centro Odontológico | 50 | |
| supervision | Supervisión de Servicios Odontológicos | Dirección Centro Odontológico | 50 | |
| seccion | Sección Apoyo Administrativo | Dirección Centro Odontológico | 50 | |
| area | Área de Farmacia | Dirección Centro Odontológico | 51 | no jerárquica |
| area | Área de Admisión, Agendamiento e Informes | Dirección Centro Odontológico | 51 | no jerárquica |
| area | Área de Atención Odontológica | Dirección Centro Odontológico | 51 | no jerárquica |
| area | Área de Enfermería | Dirección Centro Odontológico | 51 | no jerárquica |
| area | Área de Cirugía Odontológica | Dirección Centro Odontológico | 51 | no jerárquica |
| area | Área de Imágenes | Dirección Centro Odontológico | 51 | no jerárquica |
| area | Área de Mantenimiento de Equipos y Logística | Dirección Centro Odontológico | 51 | no jerárquica |
| area | Área de Recursos Humanos | Dirección Centro Odontológico | 51 | no jerárquica |

---

## 9. UBAS Itá (pág. 51)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| area | Área Médica | Dirección UBAS Itá | 51 | no jerárquica |
| area | Área de Farmacia | Dirección UBAS Itá | 51 | no jerárquica |
| area | Área de Enfermería | Dirección UBAS Itá | 51 | no jerárquica |
| area | Área Apoyo Administrativo | Dirección UBAS Itá | 51 | no jerárquica |

---

## 10. Hospital Geriátrico (pág. 52)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| asistencia_tecnica | Asistencia Técnica | Dirección Hospital Geriátrico Dr. Gerardo Buongermini | 52 | |
| departamento | Departamento Médico Geriátrico | Dirección Hospital Geriátrico Dr. Gerardo Buongermini | 52 | |
| departamento | Departamento de Apoyo Médico | Dirección Hospital Geriátrico Dr. Gerardo Buongermini | 52 | |
| departamento | Departamento de Apoyo Administrativo | Dirección Hospital Geriátrico Dr. Gerardo Buongermini | 52 | |
| servicio | Servicio de Hospitalización y Urgencias | Departamento Médico Geriátrico | 52 | |
| servicio | Servicio de Atención Ambulatoria | Departamento Médico Geriátrico | 52 | |
| servicio | Servicio de Cuidados Intensivos | Departamento Médico Geriátrico | 52 | |
| servicio | Servicio de Farmacia | Departamento de Apoyo Médico (Geriátrico) | 52 | |
| servicio | Servicio de Estudios de Diagnóstico e Imágenes | Departamento de Apoyo Médico (Geriátrico) | 52 | |
| servicio | Servicio de Nutrición | Departamento de Apoyo Médico (Geriátrico) | 52 | |
| seccion | Sección Administración del Personal | Departamento de Apoyo Administrativo (Geriátrico) | 52 | |
| seccion | Sección Servicios Generales | Departamento de Apoyo Administrativo (Geriátrico) | 52 | |
| seccion | Sección Admisión Documentación e Informes | Departamento de Apoyo Administrativo (Geriátrico) | 52 | |

---

## 11. Hospital de Luque y Hospital 12 de Junio (pág. 53)

Misma estructura. `{DIR_HOSP}` = Dirección Hospital de Luque | Dirección Hospital 12 de Junio

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| departamento | Departamento Médico Asistencial | {DIR_HOSP} | 53 | |
| departamento | Departamento de Apoyo Médico | {DIR_HOSP} | 53 | |
| departamento | Departamento Administrativo | {DIR_HOSP} | 53 | |
| servicio | Servicio de Hospitalización y Urgencia | Departamento Médico Asistencial ({DIR_HOSP}) | 53 | |
| servicio | Servicio de Atención Ambulatoria Médico-Odontológico | Departamento Médico Asistencial ({DIR_HOSP}) | 53 | |
| servicio | Servicio de Cirugía | Departamento Médico Asistencial ({DIR_HOSP}) | 53 | |
| servicio | Servicio de Farmacia | Departamento de Apoyo Médico ({DIR_HOSP}) | 53 | |
| servicio | Servicio de Estudios de Diagnóstico e Imágenes | Departamento de Apoyo Médico ({DIR_HOSP}) | 53 | |
| servicio | Servicio de Laboratorio | Departamento de Apoyo Médico ({DIR_HOSP}) | 53 | |
| seccion | Sección Administración del Personal | Departamento Administrativo ({DIR_HOSP}) | 53 | |
| seccion | Sección Servicios Generales | Departamento Administrativo ({DIR_HOSP}) | 53 | |
| seccion | Sección Admisión Documentación e Informes | Departamento Administrativo ({DIR_HOSP}) | 53 | |

---

## 12. Dir. Hospitales Área Interior — staff propio (pág. 54)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| oficina_coordinacion | Oficina de Coordinación | Dirección de Hospitales del Área Interior | 54 | |
| departamento | Departamento Apoyo Asistencial | Dirección de Hospitales del Área Interior | 54 | |
| departamento | Departamento Administrativo | Dirección de Hospitales del Área Interior | 54 | |
| seccion | Sección Administración de Personal | Departamento Administrativo (Área Interior) | 54 | |
| seccion | Sección Servicios Generales | Departamento Administrativo (Área Interior) | 54 | |
| seccion | Sección Administración de Contratos y Convenios | Departamento Administrativo (Área Interior) | 54 | |

---

## 13. Zonas Interior (pág. 55)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| coordinacion_zonal | Coordinación Zonal 1ª — Concepción / Alto Paraguay | Dirección de Hospitales del Área Interior | 55 | |
| coordinacion_zonal | Coordinación Zonal 2ª — San Pedro | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 3ª — Cordillera | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 4ª — Guairá | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 5ª — Caaguazú | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 6ª — Caazapá | Dirección de Hospitales del Área Interior | 55 | |
| coordinacion_zonal | Coordinación Zonal 7ª — Itapúa | Dirección de Hospitales del Área Interior | 55 | |
| coordinacion_zonal | Coordinación Zonal 8ª — Misiones | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 9ª — Paraguarí | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 10ª — Alto Paraná | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 12ª — Ñeembucú | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 13ª — Amambay | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 14ª — Canindeyú | Dirección de Hospitales del Área Interior | 55 | |
| direccion_regional | Dirección Regional 15ª — Presidente Hayes / Boquerón | Dirección de Hospitales del Área Interior | 55 | |

---

## 14. Direcciones de locales — Interior (págs. 56-60)

Sin letras (A)/(B).

### 14.1 Zona 1ª — Concepción / Alto Paraguay

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional Concepción | Coordinación Zonal 1ª — Concepción / Alto Paraguay | 56 | |
| direccion | Dirección Unidad Sanitaria Vallemí | Coordinación Zonal 1ª — Concepción / Alto Paraguay | 56 | |
| direccion | Dirección Unidad Sanitaria Horqueta | Coordinación Zonal 1ª — Concepción / Alto Paraguay | 56 | |
| direccion | Dirección Unidad Sanitaria Bahía Negra | Coordinación Zonal 1ª — Concepción / Alto Paraguay | 56 | |
| direccion | Dirección Unidad Sanitaria Puerto Casado | Coordinación Zonal 1ª — Concepción / Alto Paraguay | 56 | |
| direccion | Dirección Puesto Sanitario Puerto Pinasco | Coordinación Zonal 1ª — Concepción / Alto Paraguay | 56 | |

### 14.2 Zona 2ª — San Pedro

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional San Pedro del Ycuamandyyú | Coordinación Zonal 2ª — San Pedro | 56 | |
| direccion | Dirección Unidad Sanitaria Puerto Rosario | Coordinación Zonal 2ª — San Pedro | 56 | |
| direccion | Dirección Unidad Sanitaria San Estanislao | Coordinación Zonal 2ª — San Pedro | 56 | |
| direccion | Dirección Puesto Sanitario Itacurubí del Rosario | Coordinación Zonal 2ª — San Pedro | 56 | |
| direccion | Dirección Puesto Sanitario Puerto Antequera | Coordinación Zonal 2ª — San Pedro | 56 | |
| direccion | Dirección Puesto Sanitario Capiibary | Coordinación Zonal 2ª — San Pedro | 56 | |
| direccion | Dirección Puesto Sanitario Choré | Coordinación Zonal 2ª — San Pedro | 56 | |

### 14.3 Zona 3ª — Cordillera

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Unidad Sanitaria Caacupé | Dirección Regional 3ª — Cordillera | 56 | |
| direccion | Dirección Unidad Sanitaria Eusebio Ayala | Dirección Regional 3ª — Cordillera | 56 | |
| direccion | Dirección Puesto Sanitario San Bernardino | Dirección Regional 3ª — Cordillera | 56 | |
| direccion | Dirección Puesto Sanitario Caraguatay | Dirección Regional 3ª — Cordillera | 56 | |
| direccion | Dirección Puesto Sanitario Tobatí | Dirección Regional 3ª — Cordillera | 56 | |
| direccion | Dirección Puesto Sanitario Arroyos y Esteros | Dirección Regional 3ª — Cordillera | 56 | |

### 14.4 Zona 4ª — Guairá

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional Villarrica | Dirección Regional 4ª — Guairá | 57 | |
| direccion | Dirección Unidad Sanitaria Colonia Independencia | Dirección Regional 4ª — Guairá | 57 | |
| direccion | Dirección Unidad Sanitaria Iturbe | Dirección Regional 4ª — Guairá | 57 | |
| direccion | Dirección Unidad Sanitaria Tebicuary | Dirección Regional 4ª — Guairá | 57 | |
| direccion | Dirección Puesto Sanitario Paso Yobay | Dirección Regional 4ª — Guairá | 57 | |
| direccion | Dirección Puesto Sanitario José Fassardi | Dirección Regional 4ª — Guairá | 57 | |

### 14.5 Zona 5ª — Caaguazú

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional Coronel Oviedo | Dirección Regional 5ª — Caaguazú | 57 | |
| direccion | Dirección Unidad Sanitaria Caaguazú | Dirección Regional 5ª — Caaguazú | 57 | |
| direccion | Dirección Puesto Sanitario Yhú | Dirección Regional 5ª — Caaguazú | 57 | |
| direccion | Dirección Puesto Sanitario San José de los Arroyos | Dirección Regional 5ª — Caaguazú | 57 | |
| direccion | Dirección Puesto Sanitario Dr. Juan Manuel Frutos | Dirección Regional 5ª — Caaguazú | 57 | |

### 14.6 Zona 6ª — Caazapá

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Unidad Sanitaria Caazapá | Dirección Regional 6ª — Caazapá | 57 | |
| direccion | Dirección Puesto Sanitario San Juan Nepomuceno | Dirección Regional 6ª — Caazapá | 57 | |
| direccion | Dirección Puesto Sanitario Yegros | Dirección Regional 6ª — Caazapá | 57 | |
| direccion | Dirección Puesto Sanitario Yuty | Dirección Regional 6ª — Caazapá | 57 | |

### 14.7 Zona 7ª — Itapúa

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional Encarnación | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Hospital Regional Ayolas | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Unidad Sanitaria Hohenau | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Unidad Sanitaria Carmen del Paraná | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Unidad Sanitaria Fram | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Unidad Sanitaria Natalio | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Puesto Sanitario Coronel Bogado | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Puesto Sanitario Carlos Antonio López | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Puesto Sanitario Edelira Km 28 | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Puesto Sanitario María Auxiliadora | Coordinación Zonal 7ª — Itapúa | 58 | |
| direccion | Dirección Puesto Sanitario Mayor Otaño | Coordinación Zonal 7ª — Itapúa | 58 | |

### 14.8 Zona 8ª — Misiones

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Unidad Sanitaria San Ignacio | Coordinación Zonal 8ª — Misiones | 58 | |
| direccion | Dirección Unidad Sanitaria San Juan Bautista de las Misiones | Coordinación Zonal 8ª — Misiones | 58 | |
| direccion | Dirección Puesto Sanitario Santa María de Fe | Coordinación Zonal 8ª — Misiones | 58 | |
| direccion | Dirección Puesto Sanitario Villa Florida | Coordinación Zonal 8ª — Misiones | 58 | |
| direccion | Dirección Puesto Sanitario Santa Rosa | Coordinación Zonal 8ª — Misiones | 58 | |
| direccion | Dirección Puesto Sanitario Yabebyry | Coordinación Zonal 8ª — Misiones | 58 | |
| direccion | Dirección Puesto Sanitario Santiago | Coordinación Zonal 8ª — Misiones | 58 | |

### 14.9 Zona 9ª — Paraguarí

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Unidad Sanitaria Paraguarí | Dirección Regional 9ª — Paraguarí | 58 | |
| direccion | Dirección Puesto Sanitario Carapeguá | Dirección Regional 9ª — Paraguarí | 58 | |
| direccion | Dirección Puesto Sanitario Caapucú | Dirección Regional 9ª — Paraguarí | 58 | |
| direccion | Dirección Puesto Sanitario La Colmena | Dirección Regional 9ª — Paraguarí | 58 | |
| direccion | Dirección Puesto Sanitario Quiindy | Dirección Regional 9ª — Paraguarí | 58 | |
| direccion | Dirección Puesto Sanitario Ybycuí | Dirección Regional 9ª — Paraguarí | 58 | |
| direccion | Dirección Puesto Sanitario Quyquyhó | Dirección Regional 9ª — Paraguarí | 58 | |
| direccion | Dirección Puesto Sanitario Mbuyapey | Dirección Regional 9ª — Paraguarí | 58 | |

### 14.10 Zona 10ª — Alto Paraná

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional Ciudad del Este | Dirección Regional 10ª — Alto Paraná | 59 | detalle págs. 61-62 |
| direccion | Dirección Unidad Sanitaria Hernandarias Dr. Ramón Genaro Agüero Sosa | Dirección Regional 10ª — Alto Paraná | 59 | |
| direccion | Dirección Unidad Sanitaria Puerto Presidente Franco | Dirección Regional 10ª — Alto Paraná | 59 | |
| direccion | Dirección Puesto Sanitario Itakyry | Dirección Regional 10ª — Alto Paraná | 59 | |
| direccion | Dirección Puesto Sanitario Santa Rita | Dirección Regional 10ª — Alto Paraná | 59 | |
| direccion | Dirección Puesto Sanitario Minga Guazú | Dirección Regional 10ª — Alto Paraná | 59 | |

### 14.11 Zona 12ª — Ñeembucú

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional Pilar | Dirección Regional 12ª — Ñeembucú | 59 | |
| direccion | Dirección Puesto Sanitario Alberdi | Dirección Regional 12ª — Ñeembucú | 59 | |

### 14.12 Zona 13ª — Amambay

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional Pedro Juan Caballero | Dirección Regional 13ª — Amambay | 59 | |
| direccion | Dirección Unidad Sanitaria Capitán Bado | Dirección Regional 13ª — Amambay | 59 | |
| direccion | Dirección Puesto Sanitario Bella Vista Norte | Dirección Regional 13ª — Amambay | 59 | |

### 14.13 Zona 14ª — Canindeyú

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Unidad Sanitaria San Isidro del Curuguaty | Dirección Regional 14ª — Canindeyú | 60 | |
| direccion | Dirección Unidad Sanitaria Puente Kyjha | Dirección Regional 14ª — Canindeyú | 60 | |
| direccion | Dirección Puesto Sanitario Salto del Guairá | Dirección Regional 14ª — Canindeyú | 60 | |
| direccion | Dirección Puesto Sanitario La Paloma | Dirección Regional 14ª — Canindeyú | 60 | |

### 14.14 Zona 15ª — Presidente Hayes / Boquerón

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| direccion | Dirección Hospital Regional Benjamín Aceval | Dirección Regional 15ª — Presidente Hayes / Boquerón | 60 | |
| direccion | Dirección Puesto Sanitario Villa Hayes | Dirección Regional 15ª — Presidente Hayes / Boquerón | 60 | |

---

## 15. Estructura — Hospital Regional Ciudad del Este (págs. 61-62)

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| departamento | Departamento Médico | Dirección Hospital Regional Ciudad del Este | 61 | |
| departamento | Departamento de Apoyo Médico | Dirección Hospital Regional Ciudad del Este | 61 | |
| departamento | Departamento de Logística y Administración | Dirección Hospital Regional Ciudad del Este | 61 | |
| area | Área Consultorios Externos | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Hospital Día | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Cuidados Mínimos | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Cuidados Intermedios y Críticos | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Cirugía | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Gineco-Obstetricia | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Pediatría | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Emergencias Pediátricas | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Emergencias Adultos | Departamento Médico (CDE) | 61 | no jerárquica |
| area | Área Imágenes | Departamento de Apoyo Médico (CDE) | 61 | no jerárquica |
| area | Área Laboratorio | Departamento de Apoyo Médico (CDE) | 61 | no jerárquica |
| area | Área Farmacia | Departamento de Apoyo Médico (CDE) | 61 | no jerárquica |
| area | Área Nutrición | Departamento de Apoyo Médico (CDE) | 61 | no jerárquica |
| area | Área Fisioterapia | Departamento de Apoyo Médico (CDE) | 61 | no jerárquica |
| area | Área Esterilización | Departamento de Apoyo Médico (CDE) | 61 | no jerárquica |
| area | Gestión de Pacientes | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Comunicación Social | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Lavandería | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Limpieza | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Cocina | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Infraestructura Física | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Equipos Biomédicos | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Informática | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Jardinería | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Administración | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Residuos Hospitalarios | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Talento Humano | Departamento de Logística y Administración (CDE) | 61 | no jerárquica |
| area | Área Admisión y Egresos | Departamento de Logística y Administración (CDE) | 62 | no jerárquica |
| area | Área Coordinación Extrahospitalaria | Departamento de Logística y Administración (CDE) | 62 | no jerárquica |
| area | Área Agendamiento | Departamento de Logística y Administración (CDE) | 62 | no jerárquica |
| area | Área Atención al Usuario | Departamento de Logística y Administración (CDE) | 62 | no jerárquica |
| area | Área Archivo y Documentación Clínica | Departamento de Logística y Administración (CDE) | 62 | no jerárquica |
| area | Área Oficina de la Información para la Gestión | Departamento de Logística y Administración (CDE) | 62 | no jerárquica |
| area | Área Servicio Social | Departamento de Logística y Administración (CDE) | 62 | no jerárquica |

---

## 16. Estructura tipo — demás Hospitales Regionales (págs. 62-63)

Aplicar bajo cada Dir. Hospital Regional **excepto** Ciudad del Este (ya detallada):  
Concepción, San Pedro del Ycuamandyyú, Villarrica, Coronel Oviedo, Encarnación, Ayolas, Pilar, Pedro Juan Caballero, Benjamín Aceval.

`{DIR_HR}` = esa dirección

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| asistencia_tecnica | Asistencia Técnica | {DIR_HR} | 62 | |
| departamento | Servicios de Apoyo y Diagnóstico | {DIR_HR} | 62 | |
| departamento | Servicios Médicos Odontológicos | {DIR_HR} | 62 | |
| seccion | Sección Apoyo Administrativo | {DIR_HR} | 62 | |
| servicio | Consulta Ambulatoria Médico Odontológica | Servicios Médicos Odontológicos ({DIR_HR}) | 62 | |
| servicio | Urgencias y Enfermería | Servicios Médicos Odontológicos ({DIR_HR}) | 62 | |
| servicio | Internados | Servicios Médicos Odontológicos ({DIR_HR}) | 62 | |
| servicio | Imágenes | Servicios de Apoyo y Diagnóstico ({DIR_HR}) | 62 | |
| servicio | Fisioterapia | Servicios de Apoyo y Diagnóstico ({DIR_HR}) | 62 | |
| servicio | Laboratorio | Servicios de Apoyo y Diagnóstico ({DIR_HR}) | 62 | |
| servicio | Electrocardiograma (E.C.G.) | Servicios de Apoyo y Diagnóstico ({DIR_HR}) | 62 | |
| servicio | Farmacia | Servicios de Apoyo y Diagnóstico ({DIR_HR}) | 62 | |
| seccion | Admisión y Documentación Clínica | Sección Apoyo Administrativo ({DIR_HR}) | 62 | |
| seccion | Nutrición | Sección Apoyo Administrativo ({DIR_HR}) | 62 | |
| seccion | Servicios Generales | Sección Apoyo Administrativo ({DIR_HR}) | 62 | |
| seccion | Administración del Personal | Sección Apoyo Administrativo ({DIR_HR}) | 62 | |

---

## 17. Estructura tipo — US Interior (pág. 63)

Bajo cada Dirección Unidad Sanitaria del Interior:

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| area | Área Médica | {DIR_US} | 63 | no jerárquica |
| area | Área de Apoyo | {DIR_US} | 63 | no jerárquica |
| area | Área Apoyo Administrativo | {DIR_US} | 63 | no jerárquica |
| servicio | Farmacia | Área de Apoyo ({DIR_US}) | 63 | |
| servicio | Laboratorio | Área de Apoyo ({DIR_US}) | 63 | |
| servicio | Consultorio | Área Médica ({DIR_US}) | 63 | |
| servicio | Enfermería | Área Médica ({DIR_US}) | 63 | |

---

## 18. Estructura tipo — PS Interior (pág. 64)

Bajo cada Dirección Puesto Sanitario del Interior:

| tipo | nombre | padre | pagina | notas |
|---|---|---|---|---|
| area | Área Médica | {DIR_PS} | 64 | no jerárquica |
| area | Área de Farmacia | {DIR_PS} | 64 | no jerárquica |
| area | Área de Enfermería | {DIR_PS} | 64 | no jerárquica |
| area | Área Apoyo Administrativo | {DIR_PS} | 64 | no jerárquica |

---

## Conteos orientativos (solo este archivo)

| Bloque | Órganos “cabecera” (direcciones/zonas) |
|---|---|
| Área Central — direcciones de local | 18 |
| Interior — zonas | 14 |
| Interior — direcciones de local | ~90 |

Las filas hijas (áreas/servicios/secciones) se expanden al instanciar los bloques `{DIR_*}`.
