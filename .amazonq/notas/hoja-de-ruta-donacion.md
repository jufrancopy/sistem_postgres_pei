# Hoja de Ruta — Donación Siplan al MEF Paraguay

> Autor: Julio Franco  
> Fecha: Julio 2025  
> Objetivo: Donar Siplan al Ministerio de Economía y Finanzas del Paraguay para uso institucional gratuito, protegiendo la autoría y evitando comercialización por terceros.

---

## Contexto

- El sistema vivirá en servidores del **MITIC** o **Ministerio de Hacienda**
- El código fuente será accesible para el equipo técnico del Estado
- Riesgo principal: clonación y comercialización por terceros sin autorización

---

## Hoja de Ruta

### Fase 1 — Protección Legal 🔴 (Prioritario)

- [ ] **Registrar Siplan en DINAPI** (Dirección Nacional de Propiedad Intelectual del Paraguay)
  - Documentar autoría, fecha de creación, versión a donar
  - Guardar certificado de registro
- [ ] **Redactar licencia personalizada** que reemplace la MIT actual, indicando:
  - Donación exclusiva al Estado Paraguayo
  - Prohibida la comercialización por terceros
  - Autoría: Julio Franco (jucfra23@gmail.com)
- [ ] **Agregar header de autoría** en archivos clave del proyecto
- [ ] **Firmar commits con GPG** en GitHub para trazabilidad de autoría

---

### Fase 2 — Control de Módulos con Clave HMAC 🟡 (Segundo paso)

- [ ] Definir lista de módulos a exponer/controlar:
  - Gestión de Usuarios y Roles
  - Planificación Estratégica (PEI)
  - Análisis FODA
  - Análisis de Estándares IPS
  - Análisis Organizacional
  - Proyectos Institucionales
- [ ] Crear tabla `modulos` en BD: `nombre`, `clave_activacion`, `habilitado`, `descripcion`
- [ ] Implementar sistema de **clave HMAC** firmada por el autor para activar módulos
  - La clave codifica: módulos habilitados + dominio + fecha de expiración
  - Sin internet, validación local
  - Sin la clave del autor, los módulos permanecen bloqueados
- [ ] Crear middleware `CheckModulo` que proteja rutas por módulo
- [ ] Panel `/superadmin/modulos` para visualizar estado de módulos

---

### Fase 3 — Ofuscación del Código 🟡 (Antes de entrega final)

- [ ] Evaluar herramientas: **phpBolt** o **Laravel Obfuscator**
- [ ] Ofuscar archivos críticos de lógica de negocio
- [ ] Verificar que el sistema funcione correctamente post-ofuscación
- [ ] Generar build final para entrega al MEF

---

### Fase 4 — Entrega Formal 🟢 (Cierre)

- [ ] Preparar documentación técnica (instalación, configuración, módulos)
- [ ] Preparar manual de usuario
- [ ] Acta de donación formal con MEF/MITIC
- [ ] Entrega de clave HMAC maestra al responsable técnico designado por el Estado
- [ ] Capacitación al equipo técnico

---

## Notas Importantes

- La **licencia personalizada + registro DINAPI** es el escudo legal más fuerte
- El **sistema HMAC** garantiza que aunque clonen el código, no puedan activar módulos sin autorización del autor
- La **ofuscación** es una capa adicional de disuasión, no una solución absoluta
- Mantener el repositorio GitHub como evidencia pública de autoría y fecha

---

## Contacto del Autor

- Email: jucfra23@gmail.com
- GitHub: https://github.com/jufrancopy/sistem_postgres_pei
