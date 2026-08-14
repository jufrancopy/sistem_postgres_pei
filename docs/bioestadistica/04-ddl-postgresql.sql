-- =====================================================================
-- Módulo Bioestadística Sanitaria — DDL de referencia
-- Schema: bioestadistica
-- PostgreSQL 14+
--
-- Documento de referencia de Fase 0. La implementación productiva se
-- realiza mediante migraciones Laravel en Fase 1 (mismo contenido).
--
-- Reglas:
--   * Cero tablas sp1..sp14: las planillas son configuración.
--   * Cero FK hacia public / estadistica / planificacion / proyecto.
--     Los user_id se guardan como bigint sin FK para no acoplar schemas.
--   * Jerarquía geo: departamentos -> distritos -> establecimientos.
-- =====================================================================

CREATE SCHEMA IF NOT EXISTS bioestadistica;
SET search_path TO bioestadistica, public;

-- =====================================================================
-- 1. GEOGRAFÍA Y CLASIFICACIÓN
-- =====================================================================

CREATE TABLE bioestadistica.departamentos (
    id          bigserial PRIMARY KEY,
    codigo      varchar(10)  NOT NULL,
    nombre      varchar(150) NOT NULL,
    activo      boolean      NOT NULL DEFAULT true,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp,
    CONSTRAINT departamentos_codigo_unique UNIQUE (codigo),
    CONSTRAINT departamentos_nombre_unique UNIQUE (nombre)
);
COMMENT ON TABLE  bioestadistica.departamentos IS 'Departamentos del país. codigo proviene de id_DEPTO del maestro Excel.';
COMMENT ON COLUMN bioestadistica.departamentos.codigo IS 'Asunción y Capital se unifican bajo el código 18.';

CREATE TABLE bioestadistica.distritos (
    id               bigserial PRIMARY KEY,
    departamento_id  bigint       NOT NULL,
    codigo           varchar(20),
    nombre           varchar(150) NOT NULL,
    activo           boolean      NOT NULL DEFAULT true,
    created_at       timestamp,
    updated_at       timestamp,
    deleted_at       timestamp,
    CONSTRAINT distritos_departamento_fk FOREIGN KEY (departamento_id)
        REFERENCES bioestadistica.departamentos (id) ON DELETE RESTRICT,
    CONSTRAINT distritos_depto_nombre_unique UNIQUE (departamento_id, nombre)
);
CREATE INDEX distritos_departamento_idx ON bioestadistica.distritos (departamento_id);
COMMENT ON TABLE bioestadistica.distritos IS 'Un distrito pertenece siempre a un departamento (Itapúa -> Hohenau, Itapúa -> Fram).';

CREATE TABLE bioestadistica.microredes (
    id          bigserial PRIMARY KEY,
    nombre      varchar(150) NOT NULL,
    activo      boolean      NOT NULL DEFAULT true,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp,
    CONSTRAINT microredes_nombre_unique UNIQUE (nombre)
);

CREATE TABLE bioestadistica.tipos_establecimiento (
    id          bigserial PRIMARY KEY,
    nombre      varchar(150) NOT NULL,
    descripcion text,
    activo      boolean      NOT NULL DEFAULT true,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp,
    CONSTRAINT tipos_establecimiento_nombre_unique UNIQUE (nombre)
);
COMMENT ON TABLE bioestadistica.tipos_establecimiento IS 'Unifica Tipo de Establecimiento y la antigua Tipología-Clasificación.';

CREATE TABLE bioestadistica.grados_complejidad (
    id          bigserial PRIMARY KEY,
    codigo      varchar(10)  NOT NULL,
    descripcion varchar(200) NOT NULL,
    activo      boolean      NOT NULL DEFAULT true,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp,
    CONSTRAINT grados_complejidad_codigo_unique UNIQUE (codigo)
);

CREATE TABLE bioestadistica.areas_gestion (
    id          bigserial PRIMARY KEY,
    nombre      varchar(150) NOT NULL,
    activo      boolean      NOT NULL DEFAULT true,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp,
    CONSTRAINT areas_gestion_nombre_unique UNIQUE (nombre)
);

CREATE TABLE bioestadistica.establecimientos (
    id                       bigserial PRIMARY KEY,
    codigo                   varchar(30)  NOT NULL,
    nombre                   varchar(250) NOT NULL,
    distrito_id              bigint,
    microred_id              bigint,
    tipo_establecimiento_id  bigint,
    grado_complejidad_id     bigint,
    area_gestion_id          bigint,
    nivel_atencion           varchar(50),
    prestador                varchar(80),
    situacion_inmueble       varchar(120),
    sistema                  varchar(30),
    codigo_sih               varchar(30),
    latitud                  numeric(10,7),
    longitud                 numeric(10,7),
    observacion              text,
    created_at               timestamp,
    updated_at               timestamp,
    deleted_at               timestamp,
    CONSTRAINT establecimientos_codigo_unique UNIQUE (codigo),
    CONSTRAINT establecimientos_distrito_fk FOREIGN KEY (distrito_id)
        REFERENCES bioestadistica.distritos (id) ON DELETE RESTRICT,
    CONSTRAINT establecimientos_microred_fk FOREIGN KEY (microred_id)
        REFERENCES bioestadistica.microredes (id) ON DELETE SET NULL,
    CONSTRAINT establecimientos_tipo_fk FOREIGN KEY (tipo_establecimiento_id)
        REFERENCES bioestadistica.tipos_establecimiento (id) ON DELETE SET NULL,
    CONSTRAINT establecimientos_grado_fk FOREIGN KEY (grado_complejidad_id)
        REFERENCES bioestadistica.grados_complejidad (id) ON DELETE SET NULL,
    CONSTRAINT establecimientos_area_fk FOREIGN KEY (area_gestion_id)
        REFERENCES bioestadistica.areas_gestion (id) ON DELETE SET NULL
);
CREATE INDEX establecimientos_distrito_idx   ON bioestadistica.establecimientos (distrito_id);
CREATE INDEX establecimientos_microred_idx   ON bioestadistica.establecimientos (microred_id);
CREATE INDEX establecimientos_codigo_sih_idx ON bioestadistica.establecimientos (codigo_sih);

COMMENT ON TABLE  bioestadistica.establecimientos IS 'Maestro propio del módulo. Sin relación con public.establecimientos de RIISS.';
COMMENT ON COLUMN bioestadistica.establecimientos.distrito_id IS 'El departamento se deriva vía distrito; no se duplica aquí. Nullable solo durante el seed inicial.';
COMMENT ON COLUMN bioestadistica.establecimientos.nivel_atencion IS 'Campo, no tabla. NIVEL 1 a NIVEL 4.';
COMMENT ON COLUMN bioestadistica.establecimientos.prestador IS 'Campo, no tabla. IPS / CONVENIO / TERCERIZADO.';
COMMENT ON COLUMN bioestadistica.establecimientos.sistema IS 'Campo, no tabla. SIH / SAMIW.';

-- =====================================================================
-- 2. METADATA DE FORMULARIOS
-- =====================================================================

CREATE TABLE bioestadistica.catalogos (
    id          bigserial PRIMARY KEY,
    codigo      varchar(80)  NOT NULL,
    nombre      varchar(200) NOT NULL,
    descripcion text,
    activo      boolean      NOT NULL DEFAULT true,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp,
    CONSTRAINT catalogos_codigo_unique UNIQUE (codigo)
);
COMMENT ON TABLE bioestadistica.catalogos IS 'Catálogos reutilizables: especialidades, determinaciones, vacunas, CIE10, medicamentos, etc.';

CREATE TABLE bioestadistica.catalog_items (
    id            bigserial PRIMARY KEY,
    catalogo_id   bigint       NOT NULL,
    codigo        varchar(80),
    label         varchar(400) NOT NULL,
    orden         integer      NOT NULL DEFAULT 0,
    activo        boolean      NOT NULL DEFAULT true,
    domain_code   varchar(10),
    tipo_registro varchar(250),
    prestacion    varchar(400),
    meta          jsonb,
    created_at    timestamp,
    updated_at    timestamp,
    deleted_at    timestamp,
    CONSTRAINT catalog_items_catalogo_fk FOREIGN KEY (catalogo_id)
        REFERENCES bioestadistica.catalogos (id) ON DELETE CASCADE
);
CREATE INDEX catalog_items_catalogo_idx ON bioestadistica.catalog_items (catalogo_id, orden);
CREATE UNIQUE INDEX catalog_items_codigo_unique
    ON bioestadistica.catalog_items (catalogo_id, codigo) WHERE codigo IS NOT NULL;
COMMENT ON COLUMN bioestadistica.catalog_items.domain_code IS 'Código de dominio 1-18 de variables salud (x = medicamentos).';

CREATE TABLE bioestadistica.variable_definitions (
    id             bigserial PRIMARY KEY,
    codigo_dominio varchar(10)  NOT NULL,
    dominio        varchar(200) NOT NULL,
    tipo_registro  varchar(250),
    prestacion     varchar(400),
    catalogo_id    bigint,
    form_codes     varchar(20)[],
    meta           jsonb,
    activo         boolean      NOT NULL DEFAULT true,
    created_at     timestamp,
    updated_at     timestamp,
    deleted_at     timestamp,
    CONSTRAINT variable_definitions_catalogo_fk FOREIGN KEY (catalogo_id)
        REFERENCES bioestadistica.catalogos (id) ON DELETE SET NULL
);
CREATE INDEX variable_definitions_dominio_idx ON bioestadistica.variable_definitions (codigo_dominio);
COMMENT ON TABLE bioestadistica.variable_definitions IS 'Diccionario maestro desde variables salud.xls: define QUÉ miden las planillas SP. 18 dominios, 94 tipos de registro, 553 prestaciones.';

CREATE TABLE bioestadistica.formularios (
    id           bigserial PRIMARY KEY,
    codigo       varchar(20)  NOT NULL,
    nombre       varchar(250) NOT NULL,
    descripcion  text,
    estado       varchar(20)  NOT NULL DEFAULT 'borrador',
    periodicidad varchar(20)  NOT NULL DEFAULT 'mensual',
    layout_type  varchar(20)  NOT NULL DEFAULT 'tabular',
    version      integer      NOT NULL DEFAULT 1,
    created_by   bigint,
    created_at   timestamp,
    updated_at   timestamp,
    deleted_at   timestamp,
    CONSTRAINT formularios_codigo_unique UNIQUE (codigo),
    CONSTRAINT formularios_estado_chk CHECK (estado IN ('borrador','activo','archivado')),
    CONSTRAINT formularios_periodicidad_chk CHECK (periodicidad IN ('diaria','semanal','mensual','trimestral','anual','ad_hoc')),
    CONSTRAINT formularios_layout_chk CHECK (layout_type IN ('tabular','nominativo','matriz'))
);
COMMENT ON TABLE bioestadistica.formularios IS 'SP1..SP14 son filas de esta tabla, nunca tablas propias.';

CREATE TABLE bioestadistica.form_secciones (
    id            bigserial PRIMARY KEY,
    formulario_id bigint       NOT NULL,
    titulo        varchar(250) NOT NULL,
    descripcion   text,
    orden         integer      NOT NULL DEFAULT 0,
    created_at    timestamp,
    updated_at    timestamp,
    deleted_at    timestamp,
    CONSTRAINT form_secciones_formulario_fk FOREIGN KEY (formulario_id)
        REFERENCES bioestadistica.formularios (id) ON DELETE CASCADE
);
CREATE INDEX form_secciones_formulario_idx ON bioestadistica.form_secciones (formulario_id, orden);

CREATE TABLE bioestadistica.fields (
    id                     bigserial PRIMARY KEY,
    seccion_id             bigint       NOT NULL,
    code                   varchar(100) NOT NULL,
    label                  varchar(400) NOT NULL,
    type                   varchar(20)  NOT NULL,
    required               boolean      NOT NULL DEFAULT false,
    min_value              numeric(18,4),
    max_value              numeric(18,4),
    validation_regex       varchar(500),
    tooltip                varchar(400),
    help_text              text,
    catalogo_id            bigint,
    parent_field_id        bigint,
    variable_definition_id bigint,
    config                 jsonb,
    orden                  integer      NOT NULL DEFAULT 0,
    created_at             timestamp,
    updated_at             timestamp,
    deleted_at             timestamp,
    CONSTRAINT fields_seccion_fk FOREIGN KEY (seccion_id)
        REFERENCES bioestadistica.form_secciones (id) ON DELETE CASCADE,
    CONSTRAINT fields_catalogo_fk FOREIGN KEY (catalogo_id)
        REFERENCES bioestadistica.catalogos (id) ON DELETE SET NULL,
    CONSTRAINT fields_parent_fk FOREIGN KEY (parent_field_id)
        REFERENCES bioestadistica.fields (id) ON DELETE CASCADE,
    CONSTRAINT fields_variable_fk FOREIGN KEY (variable_definition_id)
        REFERENCES bioestadistica.variable_definitions (id) ON DELETE SET NULL,
    CONSTRAINT fields_type_chk CHECK (type IN (
        'text','textarea','integer','decimal','date','time','boolean',
        'select','multiselect','radio','tabla','subtabla','matriz'
    )),
    CONSTRAINT fields_seccion_code_unique UNIQUE (seccion_id, code)
);
CREATE INDEX fields_seccion_idx ON bioestadistica.fields (seccion_id, orden);
CREATE INDEX fields_parent_idx  ON bioestadistica.fields (parent_field_id);
COMMENT ON COLUMN bioestadistica.fields.config IS 'JSONB por tipo: tabla {row_catalog_id, columns[]}, matriz {rows[], col_count, totals}, select {allow_other}.';

-- =====================================================================
-- 3. CAPTURA (EAV)
-- =====================================================================

CREATE TABLE bioestadistica.records (
    id                 bigserial PRIMARY KEY,
    formulario_id      bigint      NOT NULL,
    establecimiento_id bigint      NOT NULL,
    periodo_anio       smallint    NOT NULL,
    periodo_mes        smallint    NOT NULL,
    estado             varchar(20) NOT NULL DEFAULT 'borrador',
    observacion        text,
    submitted_by       bigint,
    submitted_at       timestamp,
    approved_by        bigint,
    approved_at        timestamp,
    created_by         bigint,
    created_at         timestamp,
    updated_at         timestamp,
    deleted_at         timestamp,
    CONSTRAINT records_formulario_fk FOREIGN KEY (formulario_id)
        REFERENCES bioestadistica.formularios (id) ON DELETE RESTRICT,
    CONSTRAINT records_establecimiento_fk FOREIGN KEY (establecimiento_id)
        REFERENCES bioestadistica.establecimientos (id) ON DELETE RESTRICT,
    CONSTRAINT records_estado_chk CHECK (estado IN ('borrador','enviado','aprobado','objetado')),
    CONSTRAINT records_mes_chk CHECK (periodo_mes BETWEEN 1 AND 12),
    CONSTRAINT records_anio_chk CHECK (periodo_anio BETWEEN 1990 AND 2100),
    CONSTRAINT records_periodo_unique UNIQUE (formulario_id, establecimiento_id, periodo_anio, periodo_mes)
);
CREATE INDEX records_periodo_idx         ON bioestadistica.records (periodo_anio, periodo_mes);
CREATE INDEX records_establecimiento_idx ON bioestadistica.records (establecimiento_id, periodo_anio, periodo_mes);
CREATE INDEX records_estado_idx          ON bioestadistica.records (estado);

COMMENT ON COLUMN bioestadistica.records.periodo_anio IS 'Año DEL DATO reportado, no de la digitación.';
COMMENT ON COLUMN bioestadistica.records.periodo_mes  IS 'Mes DEL DATO reportado. En febrero se carga enero (periodo_mes=1).';
COMMENT ON COLUMN bioestadistica.records.submitted_at IS 'Momento de la carga; puede ser uno o más meses posterior al período.';

CREATE TABLE bioestadistica.record_values (
    id         bigserial PRIMARY KEY,
    record_id  bigint NOT NULL,
    field_id   bigint NOT NULL,
    value_text text,
    value_num  numeric(18,4),
    value_date date,
    value_bool boolean,
    value_json jsonb,
    created_at timestamp,
    updated_at timestamp,
    CONSTRAINT record_values_record_fk FOREIGN KEY (record_id)
        REFERENCES bioestadistica.records (id) ON DELETE CASCADE,
    CONSTRAINT record_values_field_fk FOREIGN KEY (field_id)
        REFERENCES bioestadistica.fields (id) ON DELETE CASCADE,
    CONSTRAINT record_values_unique UNIQUE (record_id, field_id)
);
CREATE INDEX record_values_field_idx ON bioestadistica.record_values (field_id);
CREATE INDEX record_values_num_idx   ON bioestadistica.record_values (field_id, value_num);
COMMENT ON COLUMN bioestadistica.record_values.value_json IS 'Multiselect, tabla, subtabla y matriz.';

-- =====================================================================
-- 4. INDICADORES
-- =====================================================================

CREATE TABLE bioestadistica.indicadores (
    id          bigserial PRIMARY KEY,
    codigo      varchar(80)  NOT NULL,
    nombre      varchar(250) NOT NULL,
    descripcion text,
    unidad      varchar(50),
    ambito      varchar(50)  NOT NULL DEFAULT 'establecimiento',
    decimales   smallint     NOT NULL DEFAULT 2,
    activo      boolean      NOT NULL DEFAULT true,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp,
    CONSTRAINT indicadores_codigo_unique UNIQUE (codigo),
    CONSTRAINT indicadores_ambito_chk CHECK (ambito IN ('establecimiento','distrito','departamento','microred','pais'))
);

CREATE TABLE bioestadistica.indicador_formulas (
    id             bigserial PRIMARY KEY,
    indicador_id   bigint NOT NULL,
    expresion      jsonb  NOT NULL,
    vigente_desde  date,
    vigente_hasta  date,
    created_at     timestamp,
    updated_at     timestamp,
    CONSTRAINT indicador_formulas_indicador_fk FOREIGN KEY (indicador_id)
        REFERENCES bioestadistica.indicadores (id) ON DELETE CASCADE
);
CREATE INDEX indicador_formulas_expr_gin ON bioestadistica.indicador_formulas USING gin (expresion);
COMMENT ON COLUMN bioestadistica.indicador_formulas.expresion IS 'AST JSON: {"op":"pct","args":[{"op":"sum","field":"pacientes_dia"},{"op":"sum","field":"camas_operativas"}]}';

CREATE TABLE bioestadistica.indicador_cache (
    id                 bigserial PRIMARY KEY,
    indicador_id       bigint   NOT NULL,
    periodo_anio       smallint NOT NULL,
    periodo_mes        smallint,
    establecimiento_id bigint,
    valor              numeric(18,4),
    calculado_at       timestamp,
    CONSTRAINT indicador_cache_indicador_fk FOREIGN KEY (indicador_id)
        REFERENCES bioestadistica.indicadores (id) ON DELETE CASCADE,
    CONSTRAINT indicador_cache_establecimiento_fk FOREIGN KEY (establecimiento_id)
        REFERENCES bioestadistica.establecimientos (id) ON DELETE CASCADE
);
CREATE UNIQUE INDEX indicador_cache_unique
    ON bioestadistica.indicador_cache (indicador_id, periodo_anio, COALESCE(periodo_mes, 0), COALESCE(establecimiento_id, 0));

-- =====================================================================
-- 5. REPORTES Y DASHBOARDS
-- =====================================================================

CREATE TABLE bioestadistica.reportes (
    id            bigserial PRIMARY KEY,
    codigo        varchar(80)  NOT NULL,
    nombre        varchar(250) NOT NULL,
    descripcion   text,
    formulario_id bigint,
    definicion    jsonb        NOT NULL,
    publico       boolean      NOT NULL DEFAULT true,
    created_by    bigint,
    created_at    timestamp,
    updated_at    timestamp,
    deleted_at    timestamp,
    CONSTRAINT reportes_codigo_unique UNIQUE (codigo),
    CONSTRAINT reportes_formulario_fk FOREIGN KEY (formulario_id)
        REFERENCES bioestadistica.formularios (id) ON DELETE SET NULL
);
CREATE INDEX reportes_definicion_gin ON bioestadistica.reportes USING gin (definicion);

CREATE TABLE bioestadistica.dashboards (
    id          bigserial PRIMARY KEY,
    codigo      varchar(80)  NOT NULL,
    nombre      varchar(250) NOT NULL,
    descripcion text,
    user_id     bigint,
    es_default  boolean      NOT NULL DEFAULT false,
    created_at  timestamp,
    updated_at  timestamp,
    deleted_at  timestamp
);
CREATE UNIQUE INDEX dashboards_codigo_user_unique
    ON bioestadistica.dashboards (codigo, COALESCE(user_id, 0));
COMMENT ON COLUMN bioestadistica.dashboards.user_id IS 'NULL = plantilla institucional; con valor = copia personal del usuario.';

CREATE TABLE bioestadistica.dashboard_widgets (
    id            bigserial PRIMARY KEY,
    dashboard_id  bigint       NOT NULL,
    tipo          varchar(30)  NOT NULL,
    titulo        varchar(250) NOT NULL,
    query_config  jsonb        NOT NULL,
    pos_x         integer      NOT NULL DEFAULT 0,
    pos_y         integer      NOT NULL DEFAULT 0,
    ancho         integer      NOT NULL DEFAULT 4,
    alto          integer      NOT NULL DEFAULT 3,
    created_at    timestamp,
    updated_at    timestamp,
    CONSTRAINT dashboard_widgets_dashboard_fk FOREIGN KEY (dashboard_id)
        REFERENCES bioestadistica.dashboards (id) ON DELETE CASCADE,
    CONSTRAINT dashboard_widgets_tipo_chk CHECK (tipo IN ('kpi','tabla','barras','lineas','pastel','heatmap','indicador'))
);
CREATE INDEX dashboard_widgets_dashboard_idx ON bioestadistica.dashboard_widgets (dashboard_id);
CREATE INDEX dashboard_widgets_query_gin     ON bioestadistica.dashboard_widgets USING gin (query_config);

-- =====================================================================
-- 6. HOSPITALIZACIÓN SP10 (nominativo)
-- =====================================================================

CREATE TABLE bioestadistica.hosp_episodios (
    id                 bigserial PRIMARY KEY,
    establecimiento_id bigint      NOT NULL,
    record_id          bigint,
    periodo_anio       smallint    NOT NULL,
    periodo_mes        smallint    NOT NULL,
    cedula             varchar(30),
    sexo               varchar(1),
    seguro             varchar(80),
    edad               integer,
    fecha_ingreso      date        NOT NULL,
    fecha_egreso       date,
    servicio           varchar(150),
    diagnostico        varchar(400),
    cie10              varchar(10),
    tipo_alta          varchar(50),
    cirugia            boolean     NOT NULL DEFAULT false,
    tipo_cirugia       varchar(150),
    recien_nacido      boolean     NOT NULL DEFAULT false,
    cesarea            boolean     NOT NULL DEFAULT false,
    created_by         bigint,
    created_at         timestamp,
    updated_at         timestamp,
    deleted_at         timestamp,
    CONSTRAINT hosp_episodios_establecimiento_fk FOREIGN KEY (establecimiento_id)
        REFERENCES bioestadistica.establecimientos (id) ON DELETE RESTRICT,
    CONSTRAINT hosp_episodios_record_fk FOREIGN KEY (record_id)
        REFERENCES bioestadistica.records (id) ON DELETE SET NULL,
    CONSTRAINT hosp_episodios_sexo_chk CHECK (sexo IN ('M','F') OR sexo IS NULL),
    CONSTRAINT hosp_episodios_mes_chk CHECK (periodo_mes BETWEEN 1 AND 12),
    CONSTRAINT hosp_episodios_fechas_chk CHECK (fecha_egreso IS NULL OR fecha_egreso >= fecha_ingreso)
);
CREATE INDEX hosp_episodios_establecimiento_idx ON bioestadistica.hosp_episodios (establecimiento_id, periodo_anio, periodo_mes);
CREATE INDEX hosp_episodios_egreso_idx          ON bioestadistica.hosp_episodios (fecha_egreso);
CREATE INDEX hosp_episodios_cie10_idx           ON bioestadistica.hosp_episodios (cie10);
COMMENT ON COLUMN bioestadistica.hosp_episodios.record_id IS 'Registro agregado SP10 al que este episodio contribuye.';

-- =====================================================================
-- 7. AUDITORÍA E IMPORTACIÓN
-- =====================================================================

CREATE TABLE bioestadistica.audit_log (
    id          bigserial PRIMARY KEY,
    user_id     bigint,
    accion      varchar(30)  NOT NULL,
    entity_type varchar(120) NOT NULL,
    entity_id   bigint,
    old_values  jsonb,
    new_values  jsonb,
    ip          inet,
    user_agent  varchar(500),
    created_at  timestamp    NOT NULL DEFAULT now()
);
CREATE INDEX audit_log_entity_idx  ON bioestadistica.audit_log (entity_type, entity_id);
CREATE INDEX audit_log_user_idx    ON bioestadistica.audit_log (user_id, created_at);
CREATE INDEX audit_log_created_idx ON bioestadistica.audit_log (created_at);
COMMENT ON TABLE bioestadistica.audit_log IS 'Append-only. Registra usuario, fecha, acción, valor anterior y valor nuevo.';

CREATE TABLE bioestadistica.import_jobs (
    id              bigserial PRIMARY KEY,
    archivo         varchar(400) NOT NULL,
    archivo_path    varchar(600),
    tipo_detectado  varchar(40),
    estado          varchar(20)  NOT NULL DEFAULT 'subido',
    mapping         jsonb,
    resumen         jsonb,
    formulario_id   bigint,
    user_id         bigint,
    error_mensaje   text,
    created_at      timestamp,
    updated_at      timestamp,
    CONSTRAINT import_jobs_formulario_fk FOREIGN KEY (formulario_id)
        REFERENCES bioestadistica.formularios (id) ON DELETE SET NULL,
    CONSTRAINT import_jobs_estado_chk CHECK (estado IN ('subido','analizado','mapeado','confirmado','completado','error')),
    CONSTRAINT import_jobs_tipo_chk CHECK (tipo_detectado IN ('formularios_sp','variables_salud','establecimientos_dim','generico') OR tipo_detectado IS NULL)
);
CREATE INDEX import_jobs_estado_idx ON bioestadistica.import_jobs (estado, created_at);

-- =====================================================================
-- 8. VISTAS DE APOYO
-- =====================================================================

-- Establecimiento con su jerarquía geográfica resuelta (el departamento se deriva).
CREATE OR REPLACE VIEW bioestadistica.v_establecimientos_geo AS
SELECT e.id,
       e.codigo,
       e.nombre,
       d.id     AS distrito_id,
       d.nombre AS distrito,
       dep.id   AS departamento_id,
       dep.nombre AS departamento,
       m.nombre AS microred,
       t.nombre AS tipo_establecimiento,
       g.codigo AS grado_complejidad,
       g.descripcion AS grado_complejidad_desc,
       a.nombre AS area_gestion,
       e.nivel_atencion,
       e.prestador,
       e.situacion_inmueble,
       e.sistema,
       e.codigo_sih,
       e.latitud,
       e.longitud
FROM bioestadistica.establecimientos e
LEFT JOIN bioestadistica.distritos            d   ON d.id = e.distrito_id
LEFT JOIN bioestadistica.departamentos        dep ON dep.id = d.departamento_id
LEFT JOIN bioestadistica.microredes           m   ON m.id = e.microred_id
LEFT JOIN bioestadistica.tipos_establecimiento t  ON t.id = e.tipo_establecimiento_id
LEFT JOIN bioestadistica.grados_complejidad    g  ON g.id = e.grado_complejidad_id
LEFT JOIN bioestadistica.areas_gestion         a  ON a.id = e.area_gestion_id
WHERE e.deleted_at IS NULL;

-- Valores numéricos listos para indicadores y estadística, con período y geografía.
CREATE OR REPLACE VIEW bioestadistica.v_valores_numericos AS
SELECT r.id            AS record_id,
       r.formulario_id,
       f.codigo        AS formulario_codigo,
       r.establecimiento_id,
       r.periodo_anio,
       r.periodo_mes,
       r.estado,
       fl.id           AS field_id,
       fl.code         AS field_code,
       rv.value_num
FROM bioestadistica.record_values rv
JOIN bioestadistica.records     r  ON r.id = rv.record_id
JOIN bioestadistica.formularios f  ON f.id = r.formulario_id
JOIN bioestadistica.fields      fl ON fl.id = rv.field_id
WHERE rv.value_num IS NOT NULL
  AND r.deleted_at IS NULL;

-- =====================================================================
-- FIN
-- =====================================================================
