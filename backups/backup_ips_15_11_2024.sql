--
-- PostgreSQL database dump
--

-- Dumped from database version 13.3
-- Dumped by pg_dump version 13.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: estadistica; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA estadistica;


ALTER SCHEMA estadistica OWNER TO postgres;

--
-- Name: planificacion; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA planificacion;


ALTER SCHEMA planificacion OWNER TO postgres;

--
-- Name: proyecto; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA proyecto;


ALTER SCHEMA proyecto OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: formulario_clasificadores; Type: TABLE; Schema: estadistica; Owner: postgres
--

CREATE TABLE estadistica.formulario_clasificadores (
    id bigint NOT NULL,
    clasificador character varying(191) NOT NULL,
    clasificador_id integer,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE estadistica.formulario_clasificadores OWNER TO postgres;

--
-- Name: formulario_clasificadores_id_seq; Type: SEQUENCE; Schema: estadistica; Owner: postgres
--

CREATE SEQUENCE estadistica.formulario_clasificadores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE estadistica.formulario_clasificadores_id_seq OWNER TO postgres;

--
-- Name: formulario_clasificadores_id_seq; Type: SEQUENCE OWNED BY; Schema: estadistica; Owner: postgres
--

ALTER SEQUENCE estadistica.formulario_clasificadores_id_seq OWNED BY estadistica.formulario_clasificadores.id;


--
-- Name: formulario_formulario_has_variables; Type: TABLE; Schema: estadistica; Owner: postgres
--

CREATE TABLE estadistica.formulario_formulario_has_variables (
    id bigint NOT NULL,
    formulario_id integer NOT NULL,
    variable_id integer NOT NULL,
    selected boolean DEFAULT false NOT NULL,
    selected_variable_id integer,
    value integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE estadistica.formulario_formulario_has_variables OWNER TO postgres;

--
-- Name: formulario_formulario_has_variables_id_seq; Type: SEQUENCE; Schema: estadistica; Owner: postgres
--

CREATE SEQUENCE estadistica.formulario_formulario_has_variables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE estadistica.formulario_formulario_has_variables_id_seq OWNER TO postgres;

--
-- Name: formulario_formulario_has_variables_id_seq; Type: SEQUENCE OWNED BY; Schema: estadistica; Owner: postgres
--

ALTER SEQUENCE estadistica.formulario_formulario_has_variables_id_seq OWNED BY estadistica.formulario_formulario_has_variables.id;


--
-- Name: formulario_formularios; Type: TABLE; Schema: estadistica; Owner: postgres
--

CREATE TABLE estadistica.formulario_formularios (
    id bigint NOT NULL,
    formulario character varying(191),
    status character varying(191),
    dependencia_emisor_id integer,
    dependencia_receptor_id integer,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE estadistica.formulario_formularios OWNER TO postgres;

--
-- Name: formulario_formularios_id_seq; Type: SEQUENCE; Schema: estadistica; Owner: postgres
--

CREATE SEQUENCE estadistica.formulario_formularios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE estadistica.formulario_formularios_id_seq OWNER TO postgres;

--
-- Name: formulario_formularios_id_seq; Type: SEQUENCE OWNED BY; Schema: estadistica; Owner: postgres
--

ALTER SEQUENCE estadistica.formulario_formularios_id_seq OWNED BY estadistica.formulario_formularios.id;


--
-- Name: formulario_items; Type: TABLE; Schema: estadistica; Owner: postgres
--

CREATE TABLE estadistica.formulario_items (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    questions text NOT NULL,
    variable_id integer,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE estadistica.formulario_items OWNER TO postgres;

--
-- Name: formulario_items_id_seq; Type: SEQUENCE; Schema: estadistica; Owner: postgres
--

CREATE SEQUENCE estadistica.formulario_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE estadistica.formulario_items_id_seq OWNER TO postgres;

--
-- Name: formulario_items_id_seq; Type: SEQUENCE OWNED BY; Schema: estadistica; Owner: postgres
--

ALTER SEQUENCE estadistica.formulario_items_id_seq OWNED BY estadistica.formulario_items.id;


--
-- Name: formulario_variables; Type: TABLE; Schema: estadistica; Owner: postgres
--

CREATE TABLE estadistica.formulario_variables (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE estadistica.formulario_variables OWNER TO postgres;

--
-- Name: formulario_variables_id_seq; Type: SEQUENCE; Schema: estadistica; Owner: postgres
--

CREATE SEQUENCE estadistica.formulario_variables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE estadistica.formulario_variables_id_seq OWNER TO postgres;

--
-- Name: formulario_variables_id_seq; Type: SEQUENCE OWNED BY; Schema: estadistica; Owner: postgres
--

ALTER SEQUENCE estadistica.formulario_variables_id_seq OWNED BY estadistica.formulario_variables.id;


--
-- Name: foda_analisis; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_analisis (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    perfil_id uuid NOT NULL,
    aspecto_id integer,
    tipo character varying(255) NOT NULL,
    ocurrencia numeric(8,2),
    impacto numeric(8,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT foda_analisis_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['Pendiente'::character varying, 'Fortaleza'::character varying, 'Oportunidad'::character varying, 'Debilidad'::character varying, 'Amenaza'::character varying])::text[])))
);


ALTER TABLE planificacion.foda_analisis OWNER TO postgres;

--
-- Name: foda_analisis_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.foda_analisis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.foda_analisis_id_seq OWNER TO postgres;

--
-- Name: foda_analisis_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.foda_analisis_id_seq OWNED BY planificacion.foda_analisis.id;


--
-- Name: foda_categorias_has_perfil; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_categorias_has_perfil (
    perfil_id uuid NOT NULL,
    category_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.foda_categorias_has_perfil OWNER TO postgres;

--
-- Name: foda_cruce_ambientes; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_cruce_ambientes (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    perfil_id uuid NOT NULL,
    estrategia text NOT NULL,
    tipo character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.foda_cruce_ambientes OWNER TO postgres;

--
-- Name: foda_cruce_ambientes_has_amenazas; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_cruce_ambientes_has_amenazas (
    cruce_id integer NOT NULL,
    amenaza_id integer NOT NULL
);


ALTER TABLE planificacion.foda_cruce_ambientes_has_amenazas OWNER TO postgres;

--
-- Name: foda_cruce_ambientes_has_debilidades; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_cruce_ambientes_has_debilidades (
    cruce_id integer NOT NULL,
    debilidad_id integer NOT NULL
);


ALTER TABLE planificacion.foda_cruce_ambientes_has_debilidades OWNER TO postgres;

--
-- Name: foda_cruce_ambientes_has_fortalezas; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_cruce_ambientes_has_fortalezas (
    cruce_id integer NOT NULL,
    fortaleza_id integer NOT NULL
);


ALTER TABLE planificacion.foda_cruce_ambientes_has_fortalezas OWNER TO postgres;

--
-- Name: foda_cruce_ambientes_has_oportunidades; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_cruce_ambientes_has_oportunidades (
    cruce_id integer NOT NULL,
    oportunidad_id integer NOT NULL
);


ALTER TABLE planificacion.foda_cruce_ambientes_has_oportunidades OWNER TO postgres;

--
-- Name: foda_cruce_ambientes_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.foda_cruce_ambientes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.foda_cruce_ambientes_id_seq OWNER TO postgres;

--
-- Name: foda_cruce_ambientes_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.foda_cruce_ambientes_id_seq OWNED BY planificacion.foda_cruce_ambientes.id;


--
-- Name: foda_groups_has_perfiles; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_groups_has_perfiles (
    id bigint NOT NULL,
    perfil_id uuid NOT NULL,
    group_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.foda_groups_has_perfiles OWNER TO postgres;

--
-- Name: foda_groups_has_perfiles_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.foda_groups_has_perfiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.foda_groups_has_perfiles_id_seq OWNER TO postgres;

--
-- Name: foda_groups_has_perfiles_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.foda_groups_has_perfiles_id_seq OWNED BY planificacion.foda_groups_has_perfiles.id;


--
-- Name: foda_models; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_models (
    id bigint NOT NULL,
    type character varying(191) NOT NULL,
    name character varying(191) NOT NULL,
    owner character varying(191) NOT NULL,
    environment character varying(191),
    description text,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.foda_models OWNER TO postgres;

--
-- Name: foda_models_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.foda_models_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.foda_models_id_seq OWNER TO postgres;

--
-- Name: foda_models_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.foda_models_id_seq OWNED BY planificacion.foda_models.id;


--
-- Name: foda_perfiles; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.foda_perfiles (
    id uuid NOT NULL,
    name character varying(191) NOT NULL,
    context character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    model_id integer NOT NULL,
    group_id integer,
    dependency_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.foda_perfiles OWNER TO postgres;

--
-- Name: pei_profiles; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.pei_profiles (
    id uuid NOT NULL,
    name text NOT NULL,
    year_start date,
    year_end date,
    type character varying(191),
    level character varying(191),
    mision text,
    vision text,
    "values" text,
    period character varying(191),
    numerator numeric(8,2),
    operator character varying(191),
    denominator numeric(8,2),
    goal numeric(8,2),
    progress numeric(8,2),
    order_item integer,
    "year_start " date,
    "year_end " date,
    action text,
    indicator text,
    baseline text,
    target text,
    group_id integer,
    dependency_id integer,
    user_id integer,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    parent_id uuid,
    parameters text,
    report_type text
);


ALTER TABLE planificacion.pei_profiles OWNER TO postgres;

--
-- Name: pei_profiles_has_dependencies; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.pei_profiles_has_dependencies (
    id bigint NOT NULL,
    pei_profile_id uuid NOT NULL,
    dependency_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.pei_profiles_has_dependencies OWNER TO postgres;

--
-- Name: pei_profiles_has_dependencies_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.pei_profiles_has_dependencies_id_seq OWNER TO postgres;

--
-- Name: pei_profiles_has_dependencies_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq OWNED BY planificacion.pei_profiles_has_dependencies.id;


--
-- Name: pei_profiles_has_strategies; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.pei_profiles_has_strategies (
    id bigint NOT NULL,
    profile_id uuid NOT NULL,
    strategy_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.pei_profiles_has_strategies OWNER TO postgres;

--
-- Name: pei_profiles_has_strategies_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.pei_profiles_has_strategies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.pei_profiles_has_strategies_id_seq OWNER TO postgres;

--
-- Name: pei_profiles_has_strategies_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.pei_profiles_has_strategies_id_seq OWNED BY planificacion.pei_profiles_has_strategies.id;


--
-- Name: peis_profiles_has_analysts; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.peis_profiles_has_analysts (
    id bigint NOT NULL,
    pei_profile_id uuid NOT NULL,
    analyst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.peis_profiles_has_analysts OWNER TO postgres;

--
-- Name: peis_profiles_has_analysts_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.peis_profiles_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.peis_profiles_has_analysts_id_seq OWNER TO postgres;

--
-- Name: peis_profiles_has_analysts_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.peis_profiles_has_analysts_id_seq OWNED BY planificacion.peis_profiles_has_analysts.id;


--
-- Name: peis_profiles_has_responsibles; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.peis_profiles_has_responsibles (
    id bigint NOT NULL,
    profile_id uuid NOT NULL,
    responsible_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.peis_profiles_has_responsibles OWNER TO postgres;

--
-- Name: peis_profiles_has_responsibles_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.peis_profiles_has_responsibles_id_seq OWNER TO postgres;

--
-- Name: peis_profiles_has_responsibles_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq OWNED BY planificacion.peis_profiles_has_responsibles.id;


--
-- Name: tasks; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.tasks (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    details character varying(191),
    status integer DEFAULT 0,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.tasks OWNER TO postgres;

--
-- Name: tasks_has_analysts; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.tasks_has_analysts (
    id bigint NOT NULL,
    task_id integer NOT NULL,
    analyst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.tasks_has_analysts OWNER TO postgres;

--
-- Name: tasks_has_analysts_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.tasks_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.tasks_has_analysts_id_seq OWNER TO postgres;

--
-- Name: tasks_has_analysts_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.tasks_has_analysts_id_seq OWNED BY planificacion.tasks_has_analysts.id;


--
-- Name: tasks_has_type_tasks; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.tasks_has_type_tasks (
    id bigint NOT NULL,
    task_id integer NOT NULL,
    typetaskable_id uuid,
    typetaskable_type character varying(191),
    status integer DEFAULT 0,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.tasks_has_type_tasks OWNER TO postgres;

--
-- Name: tasks_has_type_tasks_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.tasks_has_type_tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.tasks_has_type_tasks_id_seq OWNER TO postgres;

--
-- Name: tasks_has_type_tasks_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.tasks_has_type_tasks_id_seq OWNED BY planificacion.tasks_has_type_tasks.id;


--
-- Name: tasks_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.tasks_id_seq OWNER TO postgres;

--
-- Name: tasks_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.tasks_id_seq OWNED BY planificacion.tasks.id;


--
-- Name: typetasks; Type: TABLE; Schema: planificacion; Owner: postgres
--

CREATE TABLE planificacion.typetasks (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    typetaskable_id uuid NOT NULL,
    typetaskable_type character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE planificacion.typetasks OWNER TO postgres;

--
-- Name: typetasks_id_seq; Type: SEQUENCE; Schema: planificacion; Owner: postgres
--

CREATE SEQUENCE planificacion.typetasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE planificacion.typetasks_id_seq OWNER TO postgres;

--
-- Name: typetasks_id_seq; Type: SEQUENCE OWNED BY; Schema: planificacion; Owner: postgres
--

ALTER SEQUENCE planificacion.typetasks_id_seq OWNED BY planificacion.typetasks.id;


--
-- Name: e_p_c_equipamientos; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_equipamientos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_equipamientos OWNER TO postgres;

--
-- Name: e_p_c_equipamientos_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_equipamientos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_equipamientos_id_seq OWNER TO postgres;

--
-- Name: e_p_c_equipamientos_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_equipamientos_id_seq OWNED BY proyecto.e_p_c_equipamientos.id;


--
-- Name: e_p_c_equipamientos_servicios; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_equipamientos_servicios (
    servicio_id integer NOT NULL,
    equipamiento_id integer NOT NULL,
    cantidad integer NOT NULL
);


ALTER TABLE proyecto.e_p_c_equipamientos_servicios OWNER TO postgres;

--
-- Name: e_p_c_especialidads; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_especialidads (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_especialidads OWNER TO postgres;

--
-- Name: e_p_c_especialidads_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_especialidads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_especialidads_id_seq OWNER TO postgres;

--
-- Name: e_p_c_especialidads_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_especialidads_id_seq OWNED BY proyecto.e_p_c_especialidads.id;


--
-- Name: e_p_c_estandars; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_estandars (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_estandars OWNER TO postgres;

--
-- Name: e_p_c_estandars_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_estandars_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_estandars_id_seq OWNER TO postgres;

--
-- Name: e_p_c_estandars_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_estandars_id_seq OWNED BY proyecto.e_p_c_estandars.id;


--
-- Name: e_p_c_estandars_servicios; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_estandars_servicios (
    estandar_id integer NOT NULL,
    servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);


ALTER TABLE proyecto.e_p_c_estandars_servicios OWNER TO postgres;

--
-- Name: e_p_c_horarios; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_horarios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    start_time character varying(191) NOT NULL,
    end_time character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_horarios OWNER TO postgres;

--
-- Name: e_p_c_horarios_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_horarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_horarios_id_seq OWNER TO postgres;

--
-- Name: e_p_c_horarios_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_horarios_id_seq OWNED BY proyecto.e_p_c_horarios.id;


--
-- Name: e_p_c_infraestructura_servicio; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_infraestructura_servicio (
    servicio_id integer NOT NULL,
    infraestructura_id integer NOT NULL,
    cantidad integer NOT NULL
);


ALTER TABLE proyecto.e_p_c_infraestructura_servicio OWNER TO postgres;

--
-- Name: e_p_c_infraestructuras; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_infraestructuras (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    measurement double precision NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_infraestructuras OWNER TO postgres;

--
-- Name: e_p_c_infraestructuras_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_infraestructuras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_infraestructuras_id_seq OWNER TO postgres;

--
-- Name: e_p_c_infraestructuras_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_infraestructuras_id_seq OWNED BY proyecto.e_p_c_infraestructuras.id;


--
-- Name: e_p_c_medicamento_insumos; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_medicamento_insumos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_medicamento_insumos OWNER TO postgres;

--
-- Name: e_p_c_medicamento_insumos_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_medicamento_insumos_id_seq OWNER TO postgres;

--
-- Name: e_p_c_medicamento_insumos_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq OWNED BY proyecto.e_p_c_medicamento_insumos.id;


--
-- Name: e_p_c_otro_servicios; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_otro_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_otro_servicios OWNER TO postgres;

--
-- Name: e_p_c_otro_servicios_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_otro_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_otro_servicios_id_seq OWNER TO postgres;

--
-- Name: e_p_c_otro_servicios_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_otro_servicios_id_seq OWNED BY proyecto.e_p_c_otro_servicios.id;


--
-- Name: e_p_c_prestaciones; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_prestaciones (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_prestaciones OWNER TO postgres;

--
-- Name: e_p_c_prestaciones_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_prestaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_prestaciones_id_seq OWNER TO postgres;

--
-- Name: e_p_c_prestaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_prestaciones_id_seq OWNED BY proyecto.e_p_c_prestaciones.id;


--
-- Name: e_p_c_servicios; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_servicios OWNER TO postgres;

--
-- Name: e_p_c_servicios_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_servicios_id_seq OWNER TO postgres;

--
-- Name: e_p_c_servicios_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_servicios_id_seq OWNED BY proyecto.e_p_c_servicios.id;


--
-- Name: e_p_c_talento_humanos; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_talento_humanos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    hours character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_talento_humanos OWNER TO postgres;

--
-- Name: e_p_c_talento_humanos_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_talento_humanos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_talento_humanos_id_seq OWNER TO postgres;

--
-- Name: e_p_c_talento_humanos_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_talento_humanos_id_seq OWNED BY proyecto.e_p_c_talento_humanos.id;


--
-- Name: e_p_c_tthhs_servicios; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_tthhs_servicios (
    servicio_id integer NOT NULL,
    tthh_id integer NOT NULL,
    cantidad integer NOT NULL
);


ALTER TABLE proyecto.e_p_c_tthhs_servicios OWNER TO postgres;

--
-- Name: e_p_c_turnos; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.e_p_c_turnos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE proyecto.e_p_c_turnos OWNER TO postgres;

--
-- Name: e_p_c_turnos_id_seq; Type: SEQUENCE; Schema: proyecto; Owner: postgres
--

CREATE SEQUENCE proyecto.e_p_c_turnos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE proyecto.e_p_c_turnos_id_seq OWNER TO postgres;

--
-- Name: e_p_c_turnos_id_seq; Type: SEQUENCE OWNED BY; Schema: proyecto; Owner: postgres
--

ALTER SEQUENCE proyecto.e_p_c_turnos_id_seq OWNED BY proyecto.e_p_c_turnos.id;


--
-- Name: otroservicio_has_servicios; Type: TABLE; Schema: proyecto; Owner: postgres
--

CREATE TABLE proyecto.otroservicio_has_servicios (
    servicio_id integer NOT NULL,
    otro_servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);


ALTER TABLE proyecto.otroservicio_has_servicios OWNER TO postgres;

--
-- Name: activities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.activities (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.activities OWNER TO postgres;

--
-- Name: activities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.activities_id_seq OWNER TO postgres;

--
-- Name: activities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.activities_id_seq OWNED BY public.activities.id;


--
-- Name: answers; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.answers (
    id bigint NOT NULL,
    participant_id integer NOT NULL,
    survey_id uuid NOT NULL,
    question_id integer NOT NULL,
    answer jsonb NOT NULL,
    is_correct boolean,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.answers OWNER TO postgres;

--
-- Name: answers_has_questions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.answers_has_questions (
    id bigint NOT NULL,
    question_id integer NOT NULL,
    answers json
);


ALTER TABLE public.answers_has_questions OWNER TO postgres;

--
-- Name: answers_has_questions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.answers_has_questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.answers_has_questions_id_seq OWNER TO postgres;

--
-- Name: answers_has_questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.answers_has_questions_id_seq OWNED BY public.answers_has_questions.id;


--
-- Name: answers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.answers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.answers_id_seq OWNER TO postgres;

--
-- Name: answers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.answers_id_seq OWNED BY public.answers.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(128) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categories_id_seq OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: groups; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.groups (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.groups OWNER TO postgres;

--
-- Name: groups_has_members; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.groups_has_members (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.groups_has_members OWNER TO postgres;

--
-- Name: groups_has_members_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.groups_has_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.groups_has_members_id_seq OWNER TO postgres;

--
-- Name: groups_has_members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.groups_has_members_id_seq OWNED BY public.groups_has_members.id;


--
-- Name: groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.groups_id_seq OWNER TO postgres;

--
-- Name: groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.groups_id_seq OWNED BY public.groups.id;


--
-- Name: localities; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.localities (
    id bigint NOT NULL,
    cod_dpto integer NOT NULL,
    desc_dpto character varying(191) NOT NULL,
    cod_dist integer NOT NULL,
    desc_dist character varying(191) NOT NULL,
    area integer NOT NULL,
    cod_barrio_loc integer NOT NULL,
    desc_barrio_loc character varying(191) NOT NULL
);


ALTER TABLE public.localities OWNER TO postgres;

--
-- Name: localities_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.localities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.localities_id_seq OWNER TO postgres;

--
-- Name: localities_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.localities_id_seq OWNED BY public.localities.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(191) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_permissions (
    permission_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO postgres;

--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_roles (
    role_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO postgres;

--
-- Name: organigramas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.organigramas (
    id bigint NOT NULL,
    dependency character varying(191) NOT NULL,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    manager character varying(191) NOT NULL,
    phone integer NOT NULL,
    email character varying(191) NOT NULL,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.organigramas OWNER TO postgres;

--
-- Name: organigramas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.organigramas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.organigramas_id_seq OWNER TO postgres;

--
-- Name: organigramas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.organigramas_id_seq OWNED BY public.organigramas.id;


--
-- Name: participants_has_surveys; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.participants_has_surveys (
    id bigint NOT NULL,
    survey_id uuid NOT NULL,
    participant_id integer NOT NULL,
    completed boolean DEFAULT false NOT NULL
);


ALTER TABLE public.participants_has_surveys OWNER TO postgres;

--
-- Name: participants_has_surveys_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.participants_has_surveys_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.participants_has_surveys_id_seq OWNER TO postgres;

--
-- Name: participants_has_surveys_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.participants_has_surveys_id_seq OWNED BY public.participants_has_surveys.id;


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(191) NOT NULL,
    token character varying(191) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- Name: patrimonies; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.patrimonies (
    id bigint NOT NULL,
    type text,
    quantity_account_current character varying(191) NOT NULL,
    detail_location character varying(191),
    estate_quantity character varying(191),
    department character varying(191),
    city character varying(191),
    locality character varying(191),
    latitude double precision,
    longitude double precision,
    location_address character varying(191),
    infrastructure_type character varying(191) NOT NULL,
    description text,
    registry_number character varying(191),
    cadastral_current_account character varying(191),
    estate_status character varying(191),
    committed_investment character varying(191),
    transfer character varying(191),
    balance_for_transfer character varying(191),
    tenant text,
    rent_amount character varying(191),
    rent_amount_period character varying(191),
    contract_resolution character varying(191),
    contract_number character varying(191),
    current_period_start character varying(191),
    current_period_end character varying(191),
    status_documentation character varying(191),
    land_area_mt2 character varying(191),
    land_area_hectares character varying(191),
    land_sub_area character varying(191),
    built_area_m2 character varying(191),
    built_value_gs character varying(191),
    property_value_gs character varying(191),
    total_value_gs character varying(191),
    possession_rent_without_title character varying(191),
    main_photo_file character varying(128),
    main_photo_file_path character varying(128),
    evidence_file character varying(128),
    evidence_file_path character varying(128),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.patrimonies OWNER TO postgres;

--
-- Name: patrimonies_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.patrimonies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.patrimonies_id_seq OWNER TO postgres;

--
-- Name: patrimonies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.patrimonies_id_seq OWNED BY public.patrimonies.id;


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permissions (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO postgres;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permissions_id_seq OWNER TO postgres;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: questions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.questions (
    id bigint NOT NULL,
    survey_id uuid NOT NULL,
    question character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.questions OWNER TO postgres;

--
-- Name: questions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.questions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.questions_id_seq OWNER TO postgres;

--
-- Name: questions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.questions_id_seq OWNED BY public.questions.id;


--
-- Name: servicios; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.servicios (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.servicios OWNER TO postgres;

--
-- Name: servicios_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.servicios_id_seq OWNER TO postgres;

--
-- Name: servicios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.servicios_id_seq OWNED BY public.servicios.id;


--
-- Name: surveys; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.surveys (
    id uuid NOT NULL,
    name character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    group_id integer,
    dependency_id integer,
    description character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.surveys OWNER TO postgres;

--
-- Name: surveys_has_analysts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.surveys_has_analysts (
    id bigint NOT NULL,
    survey_id uuid NOT NULL,
    analyst_id bigint NOT NULL
);


ALTER TABLE public.surveys_has_analysts OWNER TO postgres;

--
-- Name: surveys_has_analysts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.surveys_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.surveys_has_analysts_id_seq OWNER TO postgres;

--
-- Name: surveys_has_analysts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.surveys_has_analysts_id_seq OWNED BY public.surveys_has_analysts.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    email character varying(191) NOT NULL,
    group_id integer,
    email_verified_at timestamp(0) without time zone,
    password character varying(191) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: formulario_clasificadores id; Type: DEFAULT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_clasificadores ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_clasificadores_id_seq'::regclass);


--
-- Name: formulario_formulario_has_variables id; Type: DEFAULT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formulario_has_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formulario_has_variables_id_seq'::regclass);


--
-- Name: formulario_formularios id; Type: DEFAULT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formularios ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formularios_id_seq'::regclass);


--
-- Name: formulario_items id; Type: DEFAULT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_items ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_items_id_seq'::regclass);


--
-- Name: formulario_variables id; Type: DEFAULT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_variables_id_seq'::regclass);


--
-- Name: foda_analisis id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_analisis ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_analisis_id_seq'::regclass);


--
-- Name: foda_cruce_ambientes id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_cruce_ambientes_id_seq'::regclass);


--
-- Name: foda_groups_has_perfiles id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_groups_has_perfiles ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_groups_has_perfiles_id_seq'::regclass);


--
-- Name: foda_models id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_models ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_models_id_seq'::regclass);


--
-- Name: pei_profiles_has_dependencies id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_dependencies_id_seq'::regclass);


--
-- Name: pei_profiles_has_strategies id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles_has_strategies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_strategies_id_seq'::regclass);


--
-- Name: peis_profiles_has_analysts id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.peis_profiles_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_analysts_id_seq'::regclass);


--
-- Name: peis_profiles_has_responsibles id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_responsibles_id_seq'::regclass);


--
-- Name: tasks id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_id_seq'::regclass);


--
-- Name: tasks_has_analysts id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_analysts_id_seq'::regclass);


--
-- Name: tasks_has_type_tasks id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks_has_type_tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_type_tasks_id_seq'::regclass);


--
-- Name: typetasks id; Type: DEFAULT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.typetasks ALTER COLUMN id SET DEFAULT nextval('planificacion.typetasks_id_seq'::regclass);


--
-- Name: e_p_c_equipamientos id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_equipamientos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_equipamientos_id_seq'::regclass);


--
-- Name: e_p_c_especialidads id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_especialidads ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_especialidads_id_seq'::regclass);


--
-- Name: e_p_c_estandars id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_estandars ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_estandars_id_seq'::regclass);


--
-- Name: e_p_c_horarios id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_horarios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_horarios_id_seq'::regclass);


--
-- Name: e_p_c_infraestructuras id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_infraestructuras ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_infraestructuras_id_seq'::regclass);


--
-- Name: e_p_c_medicamento_insumos id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_medicamento_insumos_id_seq'::regclass);


--
-- Name: e_p_c_otro_servicios id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_otro_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_otro_servicios_id_seq'::regclass);


--
-- Name: e_p_c_prestaciones id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_prestaciones ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_prestaciones_id_seq'::regclass);


--
-- Name: e_p_c_servicios id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_servicios_id_seq'::regclass);


--
-- Name: e_p_c_talento_humanos id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_talento_humanos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_talento_humanos_id_seq'::regclass);


--
-- Name: e_p_c_turnos id; Type: DEFAULT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_turnos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_turnos_id_seq'::regclass);


--
-- Name: activities id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activities ALTER COLUMN id SET DEFAULT nextval('public.activities_id_seq'::regclass);


--
-- Name: answers id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answers ALTER COLUMN id SET DEFAULT nextval('public.answers_id_seq'::regclass);


--
-- Name: answers_has_questions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answers_has_questions ALTER COLUMN id SET DEFAULT nextval('public.answers_has_questions_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: groups id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.groups ALTER COLUMN id SET DEFAULT nextval('public.groups_id_seq'::regclass);


--
-- Name: groups_has_members id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.groups_has_members ALTER COLUMN id SET DEFAULT nextval('public.groups_has_members_id_seq'::regclass);


--
-- Name: localities id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.localities ALTER COLUMN id SET DEFAULT nextval('public.localities_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: organigramas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organigramas ALTER COLUMN id SET DEFAULT nextval('public.organigramas_id_seq'::regclass);


--
-- Name: participants_has_surveys id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participants_has_surveys ALTER COLUMN id SET DEFAULT nextval('public.participants_has_surveys_id_seq'::regclass);


--
-- Name: patrimonies id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.patrimonies ALTER COLUMN id SET DEFAULT nextval('public.patrimonies_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: questions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.questions ALTER COLUMN id SET DEFAULT nextval('public.questions_id_seq'::regclass);


--
-- Name: servicios id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servicios ALTER COLUMN id SET DEFAULT nextval('public.servicios_id_seq'::regclass);


--
-- Name: surveys_has_analysts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.surveys_has_analysts ALTER COLUMN id SET DEFAULT nextval('public.surveys_has_analysts_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: formulario_clasificadores; Type: TABLE DATA; Schema: estadistica; Owner: postgres
--

COPY estadistica.formulario_clasificadores (id, clasificador, clasificador_id, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: formulario_formulario_has_variables; Type: TABLE DATA; Schema: estadistica; Owner: postgres
--

COPY estadistica.formulario_formulario_has_variables (id, formulario_id, variable_id, selected, selected_variable_id, value, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: formulario_formularios; Type: TABLE DATA; Schema: estadistica; Owner: postgres
--

COPY estadistica.formulario_formularios (id, formulario, status, dependencia_emisor_id, dependencia_receptor_id, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: formulario_items; Type: TABLE DATA; Schema: estadistica; Owner: postgres
--

COPY estadistica.formulario_items (id, item, questions, variable_id, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: formulario_variables; Type: TABLE DATA; Schema: estadistica; Owner: postgres
--

COPY estadistica.formulario_variables (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: foda_analisis; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_analisis (id, user_id, perfil_id, aspecto_id, tipo, ocurrencia, impacto, created_at, updated_at) FROM stdin;
72	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	68	Debilidad	0.90	0.80	2023-10-19 14:08:39	2023-10-19 14:10:01
7	9	9a670613-48f6-4af7-8680-57660453ee1b	27	Pendiente	0.00	0.00	2023-10-19 11:25:23	2023-10-19 11:25:23
8	9	9a670613-48f6-4af7-8680-57660453ee1b	26	Pendiente	0.00	0.00	2023-10-19 11:25:23	2023-10-19 11:25:23
9	9	9a670613-48f6-4af7-8680-57660453ee1b	35	Pendiente	0.00	0.00	2023-10-19 11:25:56	2023-10-19 11:25:56
10	9	9a670613-48f6-4af7-8680-57660453ee1b	37	Pendiente	0.00	0.00	2023-10-19 11:25:56	2023-10-19 11:25:56
11	9	9a670613-48f6-4af7-8680-57660453ee1b	36	Pendiente	0.00	0.00	2023-10-19 11:25:56	2023-10-19 11:25:56
12	1	9a672fc4-d900-4b24-9e9b-d71943b1470f	88	Pendiente	0.00	0.00	2023-10-19 13:31:36	2023-10-19 13:31:36
13	1	9a672fc4-d900-4b24-9e9b-d71943b1470f	89	Pendiente	0.00	0.00	2023-10-19 13:31:36	2023-10-19 13:31:36
14	1	9a672fc4-d900-4b24-9e9b-d71943b1470f	90	Pendiente	0.00	0.00	2023-10-19 13:31:36	2023-10-19 13:31:36
15	1	9a672fc4-d900-4b24-9e9b-d71943b1470f	87	Pendiente	0.00	0.00	2023-10-19 13:31:36	2023-10-19 13:31:36
31	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	84	Pendiente	0.00	0.00	2023-10-19 14:03:33	2023-10-19 14:03:33
32	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	82	Pendiente	0.00	0.00	2023-10-19 14:03:33	2023-10-19 14:03:33
33	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	83	Pendiente	0.00	0.00	2023-10-19 14:03:33	2023-10-19 14:03:33
34	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	74	Pendiente	0.00	0.00	2023-10-19 14:03:48	2023-10-19 14:03:48
35	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	75	Pendiente	0.00	0.00	2023-10-19 14:03:48	2023-10-19 14:03:48
36	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	73	Pendiente	0.00	0.00	2023-10-19 14:03:48	2023-10-19 14:03:48
38	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	47	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
39	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	60	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
40	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	49	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
41	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	62	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
42	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	64	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
43	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	39	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
44	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	54	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
45	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	41	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
46	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	50	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
47	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	52	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
48	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	51	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
49	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	53	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
50	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	56	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
51	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	43	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
52	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	45	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
53	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	40	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
54	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	55	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
55	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	44	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
56	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	42	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
57	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	57	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
58	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	46	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
59	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	59	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
60	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	48	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
61	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	61	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
62	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	63	Pendiente	0.00	0.00	2023-10-19 14:04:06	2023-10-19 14:04:06
37	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	58	Fortaleza	0.90	0.80	2023-10-19 14:04:06	2023-10-19 14:04:37
63	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	7	Pendiente	0.00	0.00	2023-10-19 14:05:26	2023-10-19 14:05:26
64	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	94	Pendiente	0.00	0.00	2023-10-19 14:05:26	2023-10-19 14:05:26
65	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	92	Pendiente	0.00	0.00	2023-10-19 14:05:26	2023-10-19 14:05:26
66	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	93	Pendiente	0.00	0.00	2023-10-19 14:05:26	2023-10-19 14:05:26
67	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	91	Pendiente	0.00	0.00	2023-10-19 14:05:26	2023-10-19 14:05:26
69	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	90	Pendiente	0.00	0.00	2023-10-19 14:07:22	2023-10-19 14:07:22
70	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	87	Pendiente	0.00	0.00	2023-10-19 14:07:22	2023-10-19 14:07:22
71	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	88	Pendiente	0.00	0.00	2023-10-19 14:07:22	2023-10-19 14:07:22
68	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	89	Fortaleza	0.90	0.80	2023-10-19 14:07:22	2023-10-19 14:08:11
73	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	66	Pendiente	0.00	0.00	2023-10-19 14:08:39	2023-10-19 14:08:39
74	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	67	Pendiente	0.00	0.00	2023-10-19 14:08:39	2023-10-19 14:08:39
75	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	69	Pendiente	0.00	0.00	2023-10-19 14:08:39	2023-10-19 14:08:39
76	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	70	Pendiente	0.00	0.00	2023-10-19 14:08:39	2023-10-19 14:08:39
77	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	71	Pendiente	0.00	0.00	2023-10-19 14:08:39	2023-10-19 14:08:39
79	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	94	Pendiente	0.00	0.00	2023-10-19 14:10:43	2023-10-19 14:10:43
80	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	92	Pendiente	0.00	0.00	2023-10-19 14:10:43	2023-10-19 14:10:43
81	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	93	Pendiente	0.00	0.00	2023-10-19 14:10:43	2023-10-19 14:10:43
82	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	91	Pendiente	0.00	0.00	2023-10-19 14:10:43	2023-10-19 14:10:43
78	8	9a670d46-01c4-4466-ad21-73d8f16bf0a8	7	Amenaza	0.90	0.80	2023-10-19 14:10:43	2023-10-19 14:11:17
84	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	90	Pendiente	0.00	0.00	2023-10-19 14:13:54	2023-10-19 14:13:54
85	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	87	Pendiente	0.00	0.00	2023-10-19 14:13:54	2023-10-19 14:13:54
86	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	88	Pendiente	0.00	0.00	2023-10-19 14:13:54	2023-10-19 14:13:54
83	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	89	Fortaleza	0.90	0.80	2023-10-19 14:13:54	2023-10-19 14:14:27
88	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	80	Pendiente	0.00	0.00	2023-10-19 14:15:11	2023-10-19 14:15:11
89	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	79	Pendiente	0.00	0.00	2023-10-19 14:15:11	2023-10-19 14:15:11
90	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	85	Pendiente	0.00	0.00	2023-10-19 14:15:11	2023-10-19 14:15:11
3	9	9a6706ec-e7b4-4906-9428-09e20268acbb	87	Debilidad	0.50	0.80	2023-10-19 01:45:01	2023-10-19 17:37:11
6	9	9a6706ec-e7b4-4906-9428-09e20268acbb	90	Debilidad	0.50	0.40	2023-10-19 01:45:01	2023-10-19 17:37:57
5	9	9a6706ec-e7b4-4906-9428-09e20268acbb	89	Fortaleza	0.70	0.80	2023-10-19 01:45:01	2023-10-19 17:47:36
17	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	90	Debilidad	0.90	0.80	2023-10-19 14:01:27	2023-10-19 17:47:58
4	9	9a6706ec-e7b4-4906-9428-09e20268acbb	88	Debilidad	0.70	0.80	2023-10-19 01:45:01	2023-10-19 17:48:16
18	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	87	Debilidad	0.90	0.80	2023-10-19 14:01:27	2023-10-19 17:51:43
19	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	88	Debilidad	0.90	0.80	2023-10-19 14:01:27	2023-10-19 17:55:27
21	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	66	Debilidad	0.90	0.80	2023-10-19 14:02:47	2023-10-19 18:00:23
22	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	67	Fortaleza	0.90	0.80	2023-10-19 14:02:47	2023-10-19 18:02:15
23	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	69	Debilidad	0.90	0.80	2023-10-19 14:02:47	2023-10-19 18:02:52
24	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	70	Fortaleza	0.90	0.80	2023-10-19 14:02:47	2023-10-19 18:03:51
25	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	71	Debilidad	0.90	0.80	2023-10-19 14:02:47	2023-10-19 18:05:09
26	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	78	Debilidad	0.90	0.80	2023-10-19 14:03:33	2023-10-19 18:09:23
27	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	80	Fortaleza	0.90	0.80	2023-10-19 14:03:33	2023-10-19 18:10:26
28	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	79	Debilidad	0.90	0.80	2023-10-19 14:03:33	2023-10-19 18:11:56
30	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	81	Fortaleza	0.90	0.80	2023-10-19 14:03:33	2023-10-19 18:13:15
91	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	81	Pendiente	0.00	0.00	2023-10-19 14:15:11	2023-10-19 14:15:11
92	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	84	Pendiente	0.00	0.00	2023-10-19 14:15:11	2023-10-19 14:15:11
93	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	82	Pendiente	0.00	0.00	2023-10-19 14:15:11	2023-10-19 14:15:11
94	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	83	Pendiente	0.00	0.00	2023-10-19 14:15:11	2023-10-19 14:15:11
87	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	78	Debilidad	0.90	0.80	2023-10-19 14:15:11	2023-10-19 14:16:06
96	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	75	Pendiente	0.00	0.00	2023-10-19 14:16:32	2023-10-19 14:16:32
97	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	73	Pendiente	0.00	0.00	2023-10-19 14:16:32	2023-10-19 14:16:32
95	2	9a670a4d-dc08-48b3-be07-fb2a830e199f	74	Fortaleza	0.90	0.80	2023-10-19 14:16:32	2023-10-19 14:17:33
115	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	89	Pendiente	0.00	0.00	2023-10-19 16:55:42	2023-10-19 16:55:42
116	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	90	Pendiente	0.00	0.00	2023-10-19 16:55:42	2023-10-19 16:55:42
117	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	87	Pendiente	0.00	0.00	2023-10-19 16:55:42	2023-10-19 16:55:42
118	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	88	Pendiente	0.00	0.00	2023-10-19 16:55:42	2023-10-19 16:55:42
167	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	90	Debilidad	0.90	0.80	2023-10-19 17:29:08	2023-10-19 17:34:09
155	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	74	Debilidad	0.70	0.80	2023-10-19 17:09:11	2023-10-19 17:34:59
156	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	75	Debilidad	0.90	0.80	2023-10-19 17:09:11	2023-10-19 17:36:01
174	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	89	Fortaleza	0.90	0.80	2023-10-19 17:30:52	2023-10-19 17:36:27
168	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	87	Debilidad	0.90	0.80	2023-10-19 17:29:08	2023-10-19 17:37:33
171	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	90	Debilidad	0.90	0.80	2023-10-19 17:29:52	2023-10-19 17:46:10
172	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	87	Fortaleza	0.90	0.80	2023-10-19 17:29:52	2023-10-19 17:47:00
173	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	88	Fortaleza	0.90	0.80	2023-10-19 17:29:52	2023-10-19 17:47:43
175	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	90	Debilidad	0.90	0.80	2023-10-19 17:30:52	2023-10-19 17:47:54
169	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	88	Debilidad	0.90	0.80	2023-10-19 17:29:08	2023-10-19 17:48:32
157	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	73	Debilidad	0.30	0.40	2023-10-19 17:09:12	2023-10-19 17:49:48
176	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	87	Debilidad	0.90	0.80	2023-10-19 17:30:52	2023-10-19 17:51:39
99	9	9a6706ec-e7b4-4906-9428-09e20268acbb	80	Debilidad	0.50	0.80	2023-10-19 16:54:26	2023-10-19 17:51:55
101	9	9a6706ec-e7b4-4906-9428-09e20268acbb	85	Debilidad	0.90	0.80	2023-10-19 16:54:26	2023-10-19 17:52:36
102	9	9a6706ec-e7b4-4906-9428-09e20268acbb	81	Fortaleza	0.90	0.80	2023-10-19 16:54:26	2023-10-19 17:53:16
100	9	9a6706ec-e7b4-4906-9428-09e20268acbb	79	Fortaleza	0.90	0.80	2023-10-19 16:54:26	2023-10-19 17:53:43
177	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	88	Debilidad	0.90	0.80	2023-10-19 17:30:52	2023-10-19 17:53:53
158	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	78	Debilidad	0.70	0.80	2023-10-19 17:09:55	2023-10-19 17:54:09
103	9	9a6706ec-e7b4-4906-9428-09e20268acbb	84	Debilidad	0.90	0.80	2023-10-19 16:54:26	2023-10-19 17:54:30
104	9	9a6706ec-e7b4-4906-9428-09e20268acbb	82	Debilidad	0.90	0.80	2023-10-19 16:54:26	2023-10-19 17:55:35
160	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	79	Debilidad	0.50	0.80	2023-10-19 17:09:55	2023-10-19 17:57:38
105	9	9a6706ec-e7b4-4906-9428-09e20268acbb	83	Debilidad	0.90	0.80	2023-10-19 16:54:26	2023-10-19 17:59:54
106	9	9a6706ec-e7b4-4906-9428-09e20268acbb	74	Fortaleza	0.90	0.80	2023-10-19 16:54:46	2023-10-19 18:00:45
161	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	85	Debilidad	0.70	0.80	2023-10-19 17:09:55	2023-10-19 18:02:24
108	9	9a6706ec-e7b4-4906-9428-09e20268acbb	73	Fortaleza	0.30	0.20	2023-10-19 16:54:46	2023-10-19 18:02:31
107	9	9a6706ec-e7b4-4906-9428-09e20268acbb	75	Debilidad	0.50	0.80	2023-10-19 16:54:46	2023-10-19 18:03:39
109	9	9a6706ec-e7b4-4906-9428-09e20268acbb	68	Debilidad	0.90	0.80	2023-10-19 16:55:36	2023-10-19 18:04:34
110	9	9a6706ec-e7b4-4906-9428-09e20268acbb	66	Fortaleza	0.70	0.80	2023-10-19 16:55:36	2023-10-19 18:04:56
112	9	9a6706ec-e7b4-4906-9428-09e20268acbb	69	Debilidad	0.90	0.80	2023-10-19 16:55:36	2023-10-19 18:05:08
114	9	9a6706ec-e7b4-4906-9428-09e20268acbb	71	Fortaleza	0.50	0.80	2023-10-19 16:55:36	2023-10-19 18:05:33
113	9	9a6706ec-e7b4-4906-9428-09e20268acbb	70	Fortaleza	0.50	0.80	2023-10-19 16:55:36	2023-10-19 18:05:49
111	9	9a6706ec-e7b4-4906-9428-09e20268acbb	67	Fortaleza	0.50	0.40	2023-10-19 16:55:36	2023-10-19 18:06:13
119	9	9a6706ec-e7b4-4906-9428-09e20268acbb	58	Fortaleza	0.50	0.40	2023-10-19 16:55:58	2023-10-19 18:07:05
120	9	9a6706ec-e7b4-4906-9428-09e20268acbb	47	Debilidad	0.70	\N	2023-10-19 16:55:58	2023-10-19 18:07:38
144	9	9a6706ec-e7b4-4906-9428-09e20268acbb	63	Debilidad	0.70	0.20	2023-10-19 16:55:58	2023-10-19 18:08:47
121	9	9a6706ec-e7b4-4906-9428-09e20268acbb	60	Debilidad	0.30	0.20	2023-10-19 16:55:58	2023-10-19 18:09:29
164	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	82	Debilidad	0.90	0.80	2023-10-19 17:09:55	2023-10-19 18:09:41
122	9	9a6706ec-e7b4-4906-9428-09e20268acbb	49	Debilidad	0.70	0.40	2023-10-19 16:55:58	2023-10-19 18:10:13
165	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	83	Debilidad	0.70	0.40	2023-10-19 17:09:55	2023-10-19 18:10:39
124	9	9a6706ec-e7b4-4906-9428-09e20268acbb	64	Fortaleza	0.50	0.40	2023-10-19 16:55:58	2023-10-19 18:11:19
125	9	9a6706ec-e7b4-4906-9428-09e20268acbb	39	Debilidad	0.50	0.40	2023-10-19 16:55:58	2023-10-19 18:11:41
129	9	9a6706ec-e7b4-4906-9428-09e20268acbb	52	Debilidad	0.70	0.80	2023-10-19 16:55:58	2023-10-19 18:12:52
141	9	9a6706ec-e7b4-4906-9428-09e20268acbb	59	Debilidad	0.70	0.80	2023-10-19 16:55:58	2023-10-19 18:13:31
130	9	9a6706ec-e7b4-4906-9428-09e20268acbb	51	Debilidad	0.70	0.40	2023-10-19 16:55:58	2023-10-19 18:14:59
131	9	9a6706ec-e7b4-4906-9428-09e20268acbb	53	Fortaleza	0.50	0.40	2023-10-19 16:55:58	2023-10-19 18:17:20
132	9	9a6706ec-e7b4-4906-9428-09e20268acbb	56	Debilidad	0.50	0.40	2023-10-19 16:55:58	2023-10-19 18:19:03
134	9	9a6706ec-e7b4-4906-9428-09e20268acbb	45	Fortaleza	0.70	0.80	2023-10-19 16:55:58	2023-10-19 18:19:39
138	9	9a6706ec-e7b4-4906-9428-09e20268acbb	42	Debilidad	0.10	0.20	2023-10-19 16:55:58	2023-10-19 18:20:09
136	9	9a6706ec-e7b4-4906-9428-09e20268acbb	55	Debilidad	0.50	0.80	2023-10-19 16:55:58	2023-10-19 18:21:12
135	9	9a6706ec-e7b4-4906-9428-09e20268acbb	40	Debilidad	0.30	0.20	2023-10-19 16:55:58	2023-10-19 18:22:10
143	9	9a6706ec-e7b4-4906-9428-09e20268acbb	61	Debilidad	0.30	0.40	2023-10-19 16:55:58	2023-10-19 18:23:51
140	9	9a6706ec-e7b4-4906-9428-09e20268acbb	46	Fortaleza	0.50	0.80	2023-10-19 16:55:58	2023-10-19 18:24:38
137	9	9a6706ec-e7b4-4906-9428-09e20268acbb	44	Fortaleza	0.30	0.20	2023-10-19 16:55:58	2023-10-19 18:26:06
128	9	9a6706ec-e7b4-4906-9428-09e20268acbb	50	Debilidad	0.30	0.20	2023-10-19 16:55:58	2023-10-19 18:26:40
133	9	9a6706ec-e7b4-4906-9428-09e20268acbb	43	Debilidad	0.50	0.80	2023-10-19 16:55:58	2023-10-19 18:27:13
127	9	9a6706ec-e7b4-4906-9428-09e20268acbb	41	Debilidad	0.50	0.80	2023-10-19 16:55:58	2023-10-19 18:27:53
123	9	9a6706ec-e7b4-4906-9428-09e20268acbb	62	Debilidad	0.50	0.80	2023-10-19 16:55:58	2023-10-19 18:28:26
145	9	9a6706ec-e7b4-4906-9428-09e20268acbb	27	Oportunidad	0.70	0.80	2023-10-19 16:56:13	2023-10-19 18:30:13
146	9	9a6706ec-e7b4-4906-9428-09e20268acbb	26	Oportunidad	0.70	0.80	2023-10-19 16:56:13	2023-10-19 18:32:10
147	9	9a6706ec-e7b4-4906-9428-09e20268acbb	4	Amenaza	0.30	0.40	2023-10-19 16:56:25	2023-10-19 18:33:07
149	9	9a6706ec-e7b4-4906-9428-09e20268acbb	3	Amenaza	0.50	0.40	2023-10-19 16:56:25	2023-10-19 18:33:49
148	9	9a6706ec-e7b4-4906-9428-09e20268acbb	15	Amenaza	0.70	0.80	2023-10-19 16:56:25	2023-10-19 18:34:05
152	9	9a6706ec-e7b4-4906-9428-09e20268acbb	35	Oportunidad	0.50	0.40	2023-10-19 16:56:55	2023-10-19 18:34:37
153	9	9a6706ec-e7b4-4906-9428-09e20268acbb	37	Oportunidad	0.70	0.40	2023-10-19 16:56:55	2023-10-19 18:35:05
150	9	9a6706ec-e7b4-4906-9428-09e20268acbb	33	Amenaza	0.50	0.40	2023-10-19 16:56:38	2023-10-19 18:44:51
151	9	9a6706ec-e7b4-4906-9428-09e20268acbb	32	Oportunidad	0.50	0.40	2023-10-19 16:56:38	2023-10-19 18:45:16
166	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	89	Fortaleza	0.90	0.80	2023-10-19 17:29:08	2023-10-19 17:31:23
178	1	9a684cfb-8d97-4115-ae88-855f59885d0c	89	Pendiente	0.00	0.00	2023-10-19 17:34:17	2023-10-19 17:34:17
170	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	89	Fortaleza	0.90	0.80	2023-10-19 17:29:52	2023-10-19 17:37:19
16	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	89	Fortaleza	0.90	0.80	2023-10-19 14:01:27	2023-10-19 17:37:27
187	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	78	Pendiente	0.00	0.00	2023-10-19 17:48:49	2023-10-19 17:48:49
188	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	80	Pendiente	0.00	0.00	2023-10-19 17:48:49	2023-10-19 17:48:49
189	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	79	Pendiente	0.00	0.00	2023-10-19 17:48:49	2023-10-19 17:48:49
190	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	85	Pendiente	0.00	0.00	2023-10-19 17:48:49	2023-10-19 17:48:49
191	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	81	Pendiente	0.00	0.00	2023-10-19 17:48:49	2023-10-19 17:48:49
192	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	84	Pendiente	0.00	0.00	2023-10-19 17:48:49	2023-10-19 17:48:49
193	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	82	Pendiente	0.00	0.00	2023-10-19 17:48:49	2023-10-19 17:48:49
194	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	83	Pendiente	0.00	0.00	2023-10-19 17:48:49	2023-10-19 17:48:49
98	9	9a6706ec-e7b4-4906-9428-09e20268acbb	78	Debilidad	0.70	0.40	2023-10-19 16:54:26	2023-10-19 17:50:21
179	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	78	Debilidad	0.90	0.80	2023-10-19 17:48:07	2023-10-19 17:50:22
195	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	78	Debilidad	0.90	0.80	2023-10-19 17:51:37	2023-10-19 17:52:14
196	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	80	Fortaleza	0.90	0.80	2023-10-19 17:51:37	2023-10-19 17:52:58
180	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	80	Debilidad	0.90	0.80	2023-10-19 17:48:07	2023-10-19 17:53:11
197	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	79	Debilidad	0.90	0.80	2023-10-19 17:51:37	2023-10-19 17:53:49
181	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	79	Debilidad	0.90	0.80	2023-10-19 17:48:07	2023-10-19 17:54:35
159	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	80	Debilidad	0.30	0.10	2023-10-19 17:09:55	2023-10-19 17:54:54
198	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	85	Debilidad	0.90	0.80	2023-10-19 17:51:37	2023-10-19 17:57:31
203	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	68	Debilidad	0.90	0.80	2023-10-19 17:54:17	2023-10-19 17:58:53
199	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	81	Debilidad	0.90	0.80	2023-10-19 17:51:37	2023-10-19 17:58:53
20	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	68	Debilidad	0.90	0.80	2023-10-19 14:02:47	2023-10-19 17:58:59
182	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	85	Debilidad	0.90	0.80	2023-10-19 17:48:07	2023-10-19 18:00:51
204	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	66	Debilidad	0.90	0.80	2023-10-19 17:54:17	2023-10-19 18:00:51
200	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	84	Debilidad	0.90	0.80	2023-10-19 17:51:37	2023-10-19 18:01:11
212	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	85	Debilidad	0.90	0.80	2023-10-19 18:05:04	2023-10-19 18:10:03
205	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	67	Fortaleza	0.90	0.80	2023-10-19 17:54:17	2023-10-19 18:01:55
183	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	81	Fortaleza	0.90	0.80	2023-10-19 17:48:07	2023-10-19 18:02:15
206	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	69	Debilidad	0.90	0.80	2023-10-19 17:54:17	2023-10-19 18:02:30
207	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	70	Fortaleza	0.90	0.80	2023-10-19 17:54:17	2023-10-19 18:03:22
184	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	84	Debilidad	0.90	0.80	2023-10-19 17:48:07	2023-10-19 18:04:19
162	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	81	Debilidad	0.90	0.80	2023-10-19 17:09:55	2023-10-19 18:04:31
208	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	71	Debilidad	0.90	0.80	2023-10-19 17:54:17	2023-10-19 18:04:51
202	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	83	Debilidad	0.90	0.80	2023-10-19 17:51:37	2023-10-19 18:06:50
209	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	78	Debilidad	0.90	0.80	2023-10-19 18:05:04	2023-10-19 18:07:05
163	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	84	Debilidad	0.90	0.80	2023-10-19 17:09:55	2023-10-19 18:07:28
186	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	83	Debilidad	0.90	0.80	2023-10-19 17:48:07	2023-10-19 18:07:51
210	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	80	Fortaleza	0.90	0.80	2023-10-19 18:05:04	2023-10-19 18:08:25
201	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	82	Debilidad	0.90	0.80	2023-10-19 17:51:37	2023-10-19 18:08:35
211	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	79	Debilidad	0.90	0.80	2023-10-19 18:05:04	2023-10-19 18:09:17
185	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	82	Debilidad	0.90	0.80	2023-10-19 17:48:07	2023-10-19 18:10:01
229	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	74	Pendiente	0.00	0.00	2023-10-19 18:11:28	2023-10-19 18:11:28
230	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	75	Pendiente	0.00	0.00	2023-10-19 18:11:28	2023-10-19 18:11:28
231	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	73	Pendiente	0.00	0.00	2023-10-19 18:11:28	2023-10-19 18:11:28
126	9	9a6706ec-e7b4-4906-9428-09e20268acbb	54	Debilidad	0.70	0.80	2023-10-19 16:55:58	2023-10-19 18:11:55
29	11	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	85	Debilidad	0.90	0.80	2023-10-19 14:03:33	2023-10-19 18:12:25
213	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	81	Fortaleza	0.90	0.80	2023-10-19 18:05:04	2023-10-19 18:13:20
223	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	68	Debilidad	0.90	0.40	2023-10-19 18:11:20	2023-10-19 18:13:47
220	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	74	Fortaleza	0.90	0.80	2023-10-19 18:10:38	2023-10-19 18:14:05
214	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	84	Debilidad	0.90	0.80	2023-10-19 18:05:04	2023-10-19 18:14:50
224	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	66	Debilidad	0.90	0.80	2023-10-19 18:11:20	2023-10-19 18:16:42
217	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	74	Fortaleza	0.90	0.40	2023-10-19 18:09:14	2023-10-19 18:16:59
222	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	73	Fortaleza	0.90	0.80	2023-10-19 18:10:38	2023-10-19 18:17:04
219	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	73	Fortaleza	0.90	0.40	2023-10-19 18:09:14	2023-10-19 18:17:45
225	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	67	Debilidad	0.90	0.40	2023-10-19 18:11:20	2023-10-19 18:17:49
218	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	75	Debilidad	0.90	0.80	2023-10-19 18:09:14	2023-10-19 18:18:29
215	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	82	Debilidad	0.90	0.80	2023-10-19 18:05:04	2023-10-19 18:18:33
226	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	69	Debilidad	0.50	0.80	2023-10-19 18:11:20	2023-10-19 18:18:48
221	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	75	Fortaleza	0.90	0.80	2023-10-19 18:10:38	2023-10-19 18:19:18
216	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	83	Fortaleza	0.90	0.80	2023-10-19 18:05:04	2023-10-19 18:19:40
139	9	9a6706ec-e7b4-4906-9428-09e20268acbb	57	Debilidad	0.30	0.20	2023-10-19 16:55:58	2023-10-19 18:20:26
241	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	68	Pendiente	0.00	0.00	2023-10-19 18:20:29	2023-10-19 18:20:29
242	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	66	Pendiente	0.00	0.00	2023-10-19 18:20:29	2023-10-19 18:20:29
243	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	67	Pendiente	0.00	0.00	2023-10-19 18:20:29	2023-10-19 18:20:29
244	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	69	Pendiente	0.00	0.00	2023-10-19 18:20:29	2023-10-19 18:20:29
245	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	70	Pendiente	0.00	0.00	2023-10-19 18:20:29	2023-10-19 18:20:29
246	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	71	Pendiente	0.00	0.00	2023-10-19 18:20:29	2023-10-19 18:20:29
232	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	68	Debilidad	0.70	0.80	2023-10-19 18:19:34	2023-10-19 18:20:40
233	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	66	Fortaleza	0.70	0.80	2023-10-19 18:19:34	2023-10-19 18:21:34
247	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	68	Debilidad	0.90	0.80	2023-10-19 18:21:05	2023-10-19 18:21:57
234	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	67	Fortaleza	0.70	0.80	2023-10-19 18:19:34	2023-10-19 18:22:42
235	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	69	Debilidad	0.90	0.80	2023-10-19 18:19:34	2023-10-19 18:23:24
228	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	71	Fortaleza	0.70	0.80	2023-10-19 18:11:20	2023-10-19 18:23:37
239	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	75	Fortaleza	0.90	0.80	2023-10-19 18:20:11	2023-10-19 18:24:35
240	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	73	Fortaleza	0.90	0.80	2023-10-19 18:20:11	2023-10-19 18:25:05
237	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	71	Fortaleza	0.90	0.80	2023-10-19 18:19:34	2023-10-19 18:25:20
236	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	70	Debilidad	0.90	0.40	2023-10-19 18:19:34	2023-10-19 18:26:26
248	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	66	Fortaleza	0.90	0.80	2023-10-19 18:21:05	2023-10-19 18:29:32
227	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	70	Debilidad	0.50	0.40	2023-10-19 18:11:20	2023-10-19 18:21:19
238	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	74	Debilidad	0.90	0.80	2023-10-19 18:20:11	2023-10-19 18:23:15
279	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	58	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:26:08
280	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	47	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:26:33
281	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	60	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:26:56
282	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	49	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:27:12
142	9	9a6706ec-e7b4-4906-9428-09e20268acbb	48	Fortaleza	0.50	0.80	2023-10-19 16:55:58	2023-10-19 18:27:37
283	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	62	Debilidad	0.70	0.80	2023-10-19 18:25:24	2023-10-19 18:28:16
253	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	58	Fortaleza	0.30	0.20	2023-10-19 18:23:57	2023-10-19 18:28:38
284	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	64	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:28:58
285	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	39	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:29:54
254	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	47	Fortaleza	0.50	0.20	2023-10-19 18:23:57	2023-10-19 18:30:04
249	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	67	Fortaleza	0.90	0.80	2023-10-19 18:21:05	2023-10-19 18:30:59
305	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	58	Debilidad	0.90	0.40	2023-10-19 18:30:11	2023-10-19 18:31:00
250	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	69	Debilidad	0.90	0.80	2023-10-19 18:21:05	2023-10-19 18:31:25
286	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	54	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:31:46
255	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	60	Debilidad	0.30	0.40	2023-10-19 18:23:57	2023-10-19 18:32:00
251	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	70	Fortaleza	0.90	0.80	2023-10-19 18:21:05	2023-10-19 18:32:03
306	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	47	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:32:19
287	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	41	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:32:48
307	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	60	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:32:53
256	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	49	Debilidad	0.50	0.80	2023-10-19 18:23:57	2023-10-19 18:33:18
288	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	50	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:33:31
289	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	52	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:33:48
308	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	49	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:34:08
290	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	51	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:34:32
291	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	53	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:34:52
252	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	71	Debilidad	0.90	0.80	2023-10-19 18:21:05	2023-10-19 18:34:59
292	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	56	Debilidad	0.50	0.80	2023-10-19 18:25:24	2023-10-19 18:35:46
309	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	62	Fortaleza	0.70	0.40	2023-10-19 18:30:11	2023-10-19 18:36:28
293	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	43	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:36:31
259	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	39	Debilidad	0.70	0.40	2023-10-19 18:23:57	2023-10-19 18:36:45
294	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	45	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:37:28
260	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	54	Debilidad	0.30	0.20	2023-10-19 18:23:57	2023-10-19 18:37:33
310	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	64	Debilidad	0.90	0.80	2023-10-19 18:30:11	2023-10-19 18:38:25
295	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	40	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:38:32
296	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	55	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:39:12
297	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	44	Fortaleza	0.50	0.20	2023-10-19 18:25:24	2023-10-19 18:39:49
264	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	51	Debilidad	0.90	0.80	2023-10-19 18:23:57	2023-10-19 18:40:15
298	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	42	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:40:29
265	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	53	Debilidad	0.50	0.40	2023-10-19 18:23:57	2023-10-19 18:41:27
311	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	39	Debilidad	0.90	0.80	2023-10-19 18:30:11	2023-10-19 18:41:09
312	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	54	Debilidad	0.90	0.80	2023-10-19 18:30:11	2023-10-19 18:41:29
299	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	57	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:41:54
269	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	40	Debilidad	0.90	0.40	2023-10-19 18:23:57	2023-10-19 18:42:02
313	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	41	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:42:07
300	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	46	Fortaleza	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:42:13
314	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	50	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:42:26
301	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	59	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:42:29
302	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	48	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:42:44
315	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	52	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:42:46
316	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	51	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:43:03
303	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	61	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:43:41
317	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	53	Debilidad	0.90	0.80	2023-10-19 18:30:11	2023-10-19 18:43:51
318	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	56	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:44:08
319	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	43	Fortaleza	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:44:27
320	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	45	Fortaleza	0.90	0.80	2023-10-19 18:30:11	2023-10-19 18:45:03
304	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	63	Debilidad	0.90	0.80	2023-10-19 18:25:24	2023-10-19 18:45:07
322	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	55	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:46:36
321	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	40	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:45:34
270	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	55	Fortaleza	0.50	0.40	2023-10-19 18:23:57	2023-10-19 18:46:15
276	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	48	Fortaleza	0.50	0.40	2023-10-19 18:23:57	2023-10-19 18:50:27
323	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	44	Debilidad	0.90	0.80	2023-10-19 18:30:11	2023-10-19 18:51:03
275	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	59	Debilidad	0.50	0.40	2023-10-19 18:23:57	2023-10-19 18:51:46
274	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	46	Debilidad	0.50	0.20	2023-10-19 18:23:57	2023-10-19 18:52:18
154	9	9a6706ec-e7b4-4906-9428-09e20268acbb	36	Oportunidad	0.50	0.40	2023-10-19 16:56:55	2023-10-19 18:34:53
359	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	58	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
360	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	47	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
361	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	60	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
362	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	49	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
363	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	62	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
364	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	64	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
365	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	39	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
366	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	54	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
367	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	41	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
368	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	50	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
369	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	52	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
370	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	51	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
371	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	53	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
372	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	56	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
373	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	43	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
374	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	45	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
375	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	40	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
376	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	55	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
377	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	44	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
378	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	42	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
379	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	57	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
380	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	46	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
381	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	59	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
382	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	48	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
383	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	61	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
384	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	63	Pendiente	0.00	0.00	2023-10-19 18:35:41	2023-10-19 18:35:41
357	9	9a6706ec-e7b4-4906-9428-09e20268acbb	30	Oportunidad	0.50	0.40	2023-10-19 18:35:41	2023-10-19 18:35:51
358	9	9a6706ec-e7b4-4906-9428-09e20268acbb	29	Amenaza	0.50	0.40	2023-10-19 18:35:41	2023-10-19 18:36:11
331	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	58	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:36:22
385	9	9a6706ec-e7b4-4906-9428-09e20268acbb	23	Amenaza	0.50	0.40	2023-10-19 18:36:33	2023-10-19 18:37:07
388	9	9a6706ec-e7b4-4906-9428-09e20268acbb	18	Amenaza	0.50	0.80	2023-10-19 18:36:33	2023-10-19 18:37:21
332	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	47	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:37:32
387	9	9a6706ec-e7b4-4906-9428-09e20268acbb	19	Amenaza	0.70	0.80	2023-10-19 18:36:33	2023-10-19 18:37:42
391	9	9a6706ec-e7b4-4906-9428-09e20268acbb	17	Amenaza	0.70	0.80	2023-10-19 18:36:33	2023-10-19 18:38:13
333	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	60	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:38:20
393	9	9a6706ec-e7b4-4906-9428-09e20268acbb	16	Amenaza	0.30	0.20	2023-10-19 18:36:33	2023-10-19 18:38:35
390	9	9a6706ec-e7b4-4906-9428-09e20268acbb	20	Amenaza	0.30	0.20	2023-10-19 18:36:33	2023-10-19 18:39:53
394	9	9a6706ec-e7b4-4906-9428-09e20268acbb	24	Amenaza	0.50	0.80	2023-10-19 18:36:33	2023-10-19 18:40:22
386	9	9a6706ec-e7b4-4906-9428-09e20268acbb	95	Amenaza	0.70	0.80	2023-10-19 18:36:33	2023-10-19 18:40:39
334	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	49	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:40:51
392	9	9a6706ec-e7b4-4906-9428-09e20268acbb	21	Amenaza	0.50	0.40	2023-10-19 18:36:33	2023-10-19 18:41:20
389	9	9a6706ec-e7b4-4906-9428-09e20268acbb	22	Amenaza	0.10	0.10	2023-10-19 18:36:33	2023-10-19 18:41:53
396	9	9a6706ec-e7b4-4906-9428-09e20268acbb	94	Amenaza	0.50	0.40	2023-10-19 18:42:13	2023-10-19 18:42:30
399	9	9a6706ec-e7b4-4906-9428-09e20268acbb	91	Amenaza	0.70	0.80	2023-10-19 18:42:13	2023-10-19 18:42:44
400	11	9a670fde-7520-4e65-8d55-46e9d730abfc	58	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
401	11	9a670fde-7520-4e65-8d55-46e9d730abfc	47	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
402	11	9a670fde-7520-4e65-8d55-46e9d730abfc	60	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
403	11	9a670fde-7520-4e65-8d55-46e9d730abfc	49	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
404	11	9a670fde-7520-4e65-8d55-46e9d730abfc	62	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
405	11	9a670fde-7520-4e65-8d55-46e9d730abfc	64	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
406	11	9a670fde-7520-4e65-8d55-46e9d730abfc	39	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
407	11	9a670fde-7520-4e65-8d55-46e9d730abfc	54	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
408	11	9a670fde-7520-4e65-8d55-46e9d730abfc	41	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
397	9	9a6706ec-e7b4-4906-9428-09e20268acbb	92	Amenaza	0.50	0.80	2023-10-19 18:42:13	2023-10-19 18:43:16
395	9	9a6706ec-e7b4-4906-9428-09e20268acbb	7	Amenaza	0.50	0.40	2023-10-19 18:42:13	2023-10-19 18:43:54
324	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	42	Fortaleza	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:51:42
325	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	57	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:52:21
336	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	64	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:54:05
326	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	46	Fortaleza	0.90	0.80	2023-10-19 18:30:11	2023-10-19 18:54:21
327	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	59	Debilidad	0.90	0.80	2023-10-19 18:30:11	2023-10-19 18:54:53
328	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	48	Fortaleza	0.70	0.40	2023-10-19 18:30:11	2023-10-19 18:55:43
330	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	63	Debilidad	0.90	0.40	2023-10-19 18:30:11	2023-10-19 18:56:59
337	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	39	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:56:15
338	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	54	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:57:11
339	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	41	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:58:31
340	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	50	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:59:29
341	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	52	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:00:19
342	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	51	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:00:44
343	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	53	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:01:42
344	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	56	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:02:11
346	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	45	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:03:30
347	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	40	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:05:31
348	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	55	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:05:49
349	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	44	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:06:05
350	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	42	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:06:28
351	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	57	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:07:01
352	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	46	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:07:33
354	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	48	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:08:51
355	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	61	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:09:30
356	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	63	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:09:59
409	11	9a670fde-7520-4e65-8d55-46e9d730abfc	50	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
410	11	9a670fde-7520-4e65-8d55-46e9d730abfc	52	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
411	11	9a670fde-7520-4e65-8d55-46e9d730abfc	51	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
412	11	9a670fde-7520-4e65-8d55-46e9d730abfc	53	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
413	11	9a670fde-7520-4e65-8d55-46e9d730abfc	56	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
414	11	9a670fde-7520-4e65-8d55-46e9d730abfc	43	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
415	11	9a670fde-7520-4e65-8d55-46e9d730abfc	45	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
416	11	9a670fde-7520-4e65-8d55-46e9d730abfc	40	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
417	11	9a670fde-7520-4e65-8d55-46e9d730abfc	55	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
418	11	9a670fde-7520-4e65-8d55-46e9d730abfc	44	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
419	11	9a670fde-7520-4e65-8d55-46e9d730abfc	42	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
420	11	9a670fde-7520-4e65-8d55-46e9d730abfc	57	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
421	11	9a670fde-7520-4e65-8d55-46e9d730abfc	46	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
422	11	9a670fde-7520-4e65-8d55-46e9d730abfc	59	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
423	11	9a670fde-7520-4e65-8d55-46e9d730abfc	48	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
424	11	9a670fde-7520-4e65-8d55-46e9d730abfc	61	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
425	11	9a670fde-7520-4e65-8d55-46e9d730abfc	63	Pendiente	0.00	0.00	2023-10-19 18:42:50	2023-10-19 18:42:50
398	9	9a6706ec-e7b4-4906-9428-09e20268acbb	93	Amenaza	0.50	0.80	2023-10-19 18:42:13	2023-10-19 18:42:57
426	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	7	Amenaza	0.90	0.80	2023-10-19 18:45:32	2023-10-19 18:46:05
427	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	94	Oportunidad	0.90	0.80	2023-10-19 18:45:32	2023-10-19 18:47:02
428	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	92	Amenaza	0.90	0.80	2023-10-19 18:45:32	2023-10-19 18:47:29
429	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	93	Amenaza	0.90	0.80	2023-10-19 18:45:32	2023-10-19 18:47:51
430	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	91	Amenaza	0.90	0.80	2023-10-19 18:45:32	2023-10-19 18:48:09
335	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	62	Debilidad	0.90	0.80	2023-10-19 18:35:33	2023-10-19 18:48:35
431	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	30	Oportunidad	0.90	0.80	2023-10-19 18:49:08	2023-10-19 18:49:31
432	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	29	Oportunidad	0.90	0.80	2023-10-19 18:49:08	2023-10-19 18:50:07
433	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	4	Amenaza	0.90	0.80	2023-10-19 18:50:39	2023-10-19 18:50:59
434	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	15	Amenaza	0.90	0.80	2023-10-19 18:50:39	2023-10-19 18:51:09
435	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	3	Amenaza	0.90	0.80	2023-10-19 18:50:39	2023-10-19 18:51:35
437	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	95	Pendiente	0.00	0.00	2023-10-19 18:52:02	2023-10-19 18:52:02
438	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	19	Pendiente	0.00	0.00	2023-10-19 18:52:02	2023-10-19 18:52:02
440	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	22	Pendiente	0.00	0.00	2023-10-19 18:52:02	2023-10-19 18:52:02
441	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	20	Pendiente	0.00	0.00	2023-10-19 18:52:02	2023-10-19 18:52:02
442	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	17	Pendiente	0.00	0.00	2023-10-19 18:52:02	2023-10-19 18:52:02
443	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	21	Pendiente	0.00	0.00	2023-10-19 18:52:02	2023-10-19 18:52:02
444	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	16	Pendiente	0.00	0.00	2023-10-19 18:52:02	2023-10-19 18:52:02
436	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	23	Oportunidad	0.90	0.80	2023-10-19 18:52:02	2023-10-19 18:52:50
445	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	24	Amenaza	0.90	0.80	2023-10-19 18:52:02	2023-10-19 18:53:20
439	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	18	Amenaza	0.90	0.80	2023-10-19 18:52:02	2023-10-19 18:54:07
446	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	89	Debilidad	0.70	0.40	2023-10-19 18:52:54	2023-10-19 18:54:20
450	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	35	Oportunidad	0.90	0.80	2023-10-19 18:54:21	2023-10-19 18:54:47
452	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	36	Oportunidad	0.90	0.80	2023-10-19 18:54:21	2023-10-19 18:55:04
451	8	9a670e44-aeed-4cfe-9c60-5d305913f7cb	37	Oportunidad	0.90	0.80	2023-10-19 18:54:21	2023-10-19 18:55:34
329	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	61	Debilidad	0.70	0.80	2023-10-19 18:30:11	2023-10-19 18:56:18
448	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	87	Fortaleza	0.50	0.40	2023-10-19 18:52:54	2023-10-19 18:56:30
453	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	27	Oportunidad	0.90	0.40	2023-10-19 18:57:56	2023-10-19 18:58:44
454	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	26	Amenaza	0.90	0.80	2023-10-19 18:57:56	2023-10-19 18:59:17
449	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	88	Fortaleza	0.50	0.20	2023-10-19 18:52:54	2023-10-19 19:00:06
455	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	4	Amenaza	0.90	0.80	2023-10-19 18:59:57	2023-10-19 19:00:39
457	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	3	Amenaza	0.90	0.80	2023-10-19 18:59:57	2023-10-19 19:01:53
456	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	15	Amenaza	0.90	0.80	2023-10-19 18:59:57	2023-10-19 19:02:14
345	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	43	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:02:43
458	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	33	Amenaza	0.70	0.80	2023-10-19 19:03:03	2023-10-19 19:03:28
459	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	32	Amenaza	0.70	0.80	2023-10-19 19:03:03	2023-10-19 19:03:56
460	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	35	Oportunidad	0.70	0.80	2023-10-19 19:04:26	2023-10-19 19:04:53
461	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	37	Oportunidad	0.90	0.80	2023-10-19 19:04:26	2023-10-19 19:05:26
462	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	36	Oportunidad	0.70	0.80	2023-10-19 19:04:26	2023-10-19 19:05:51
463	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	30	Amenaza	0.70	0.80	2023-10-19 19:06:19	2023-10-19 19:06:52
464	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	29	Amenaza	0.70	0.80	2023-10-19 19:06:19	2023-10-19 19:07:15
353	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	59	Fortaleza	0.90	0.80	2023-10-19 18:35:33	2023-10-19 19:08:17
467	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	92	Oportunidad	0.90	0.80	2023-10-19 19:07:51	2023-10-19 19:10:52
465	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	7	Oportunidad	0.70	0.80	2023-10-19 19:07:51	2023-10-19 19:09:13
469	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	91	Amenaza	0.70	0.80	2023-10-19 19:07:51	2023-10-19 19:09:38
468	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	93	Amenaza	0.70	0.80	2023-10-19 19:07:51	2023-10-19 19:10:05
466	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	94	Amenaza	0.70	0.80	2023-10-19 19:07:51	2023-10-19 19:11:36
470	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	27	Pendiente	0.00	0.00	2023-10-19 19:11:41	2023-10-19 19:11:41
471	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	26	Pendiente	0.00	0.00	2023-10-19 19:11:41	2023-10-19 19:11:41
472	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	27	Oportunidad	0.90	0.80	2023-10-19 19:11:44	2023-10-19 19:12:24
473	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	26	Amenaza	0.90	0.80	2023-10-19 19:11:44	2023-10-19 19:12:58
474	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	23	Amenaza	0.70	0.80	2023-10-19 19:13:01	2023-10-19 19:13:28
475	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	95	Amenaza	0.90	0.80	2023-10-19 19:13:01	2023-10-19 19:13:57
476	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	19	Amenaza	0.70	0.80	2023-10-19 19:13:01	2023-10-19 19:14:17
477	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	18	Amenaza	0.70	0.80	2023-10-19 19:13:01	2023-10-19 19:14:39
485	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	15	Amenaza	0.90	0.80	2023-10-19 19:13:28	2023-10-19 19:14:56
486	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	3	Amenaza	0.90	0.80	2023-10-19 19:13:28	2023-10-19 19:15:15
478	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	22	Amenaza	0.90	0.10	2023-10-19 19:13:01	2023-10-19 19:15:20
479	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	20	Amenaza	0.90	0.20	2023-10-19 19:13:01	2023-10-19 19:15:54
480	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	17	Amenaza	0.90	0.80	2023-10-19 19:13:01	2023-10-19 19:16:18
481	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	21	Amenaza	0.70	0.80	2023-10-19 19:13:01	2023-10-19 19:16:43
482	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	16	Amenaza	0.90	0.10	2023-10-19 19:13:01	2023-10-19 19:17:32
487	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	4	Pendiente	0.00	0.00	2023-10-19 19:14:20	2023-10-19 19:14:20
488	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	15	Pendiente	0.00	0.00	2023-10-19 19:14:20	2023-10-19 19:14:20
489	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	3	Pendiente	0.00	0.00	2023-10-19 19:14:20	2023-10-19 19:14:20
484	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	4	Amenaza	0.90	0.80	2023-10-19 19:13:28	2023-10-19 19:14:34
490	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	33	Amenaza	0.90	0.80	2023-10-19 19:15:40	2023-10-19 19:16:19
491	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	32	Amenaza	0.90	0.80	2023-10-19 19:15:40	2023-10-19 19:16:49
483	4	9a670976-68cf-4f5e-b4f5-4c56931aa175	24	Amenaza	0.70	0.80	2023-10-19 19:13:01	2023-10-19 19:17:08
492	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	35	Pendiente	0.00	0.00	2023-10-19 19:18:45	2023-10-19 19:18:45
493	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	37	Pendiente	0.00	0.00	2023-10-19 19:18:46	2023-10-19 19:18:46
494	6	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	36	Pendiente	0.00	0.00	2023-10-19 19:18:46	2023-10-19 19:18:46
495	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	36	Oportunidad	0.90	0.80	2023-10-19 19:23:02	2023-10-19 19:23:22
496	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	30	Oportunidad	0.90	0.80	2023-10-19 19:24:10	2023-10-19 19:24:29
497	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	23	Oportunidad	0.90	0.80	2023-10-19 19:24:53	2023-10-19 19:25:24
498	7	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	7	Oportunidad	0.90	0.80	2023-10-19 19:25:47	2023-10-19 19:26:26
499	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	33	Amenaza	0.50	0.40	2023-10-19 19:26:14	2023-10-19 19:26:35
500	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	32	Amenaza	0.70	0.40	2023-10-19 19:26:14	2023-10-19 19:26:54
501	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	23	Oportunidad	0.50	0.20	2023-10-19 19:28:01	2023-10-19 19:28:26
502	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	95	Amenaza	0.50	0.20	2023-10-19 19:28:01	2023-10-19 19:29:19
504	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	16	Amenaza	0.30	0.20	2023-10-19 19:28:01	2023-10-19 19:29:41
505	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	24	Amenaza	0.50	0.40	2023-10-19 19:28:01	2023-10-19 19:29:59
503	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	19	Amenaza	0.90	0.40	2023-10-19 19:28:01	2023-10-19 19:30:16
506	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	7	Amenaza	0.70	0.40	2023-10-19 19:30:59	2023-10-19 19:31:13
507	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	91	Amenaza	0.50	0.40	2023-10-19 19:30:59	2023-10-19 19:31:25
508	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	15	Amenaza	0.50	0.40	2023-10-19 19:32:29	2023-10-19 19:32:42
509	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	27	Oportunidad	0.30	0.20	2023-10-19 19:33:20	2023-10-19 19:33:35
510	3	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	29	Amenaza	0.70	0.20	2023-10-19 19:34:12	2023-10-19 19:34:26
2353	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	206	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2354	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	117	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2355	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	178	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2356	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	119	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2357	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	213	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2358	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	99	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2359	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	140	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2360	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	100	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2361	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	122	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2362	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	144	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2363	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	148	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2364	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	98	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2365	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	101	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2366	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	118	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2367	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	102	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2368	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	145	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2369	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	142	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2370	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	103	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2371	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	120	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2372	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	104	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2373	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	105	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2374	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	106	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2375	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	107	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2376	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	108	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2377	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	137	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2378	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	109	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2379	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	112	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2380	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	110	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2381	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	141	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2382	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	111	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2383	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	113	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2384	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	114	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2385	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	115	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2386	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	116	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2387	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	123	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2388	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	124	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2389	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	138	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2390	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	207	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2391	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	125	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2392	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	126	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2393	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	127	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2394	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	121	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2395	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	128	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2396	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	136	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2397	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	130	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2398	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	149	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2399	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	146	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2400	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	129	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2401	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	131	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2402	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	139	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2403	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	208	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2404	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	143	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2405	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	214	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2406	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	147	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2407	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	150	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2408	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	133	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2409	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	134	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2410	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	135	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2411	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	132	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2412	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	172	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2413	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	209	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2414	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	180	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2415	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	215	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2416	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	210	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2417	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	216	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2418	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	199	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2419	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	197	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2420	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	201	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2421	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	198	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2422	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	203	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2423	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	200	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2424	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	204	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2425	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	202	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2426	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	165	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2427	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	163	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2428	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	176	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2429	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	179	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2430	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	164	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2431	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	181	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2432	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	166	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2433	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	167	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2434	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	168	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2435	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	169	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2436	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	170	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2437	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	173	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2438	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	174	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2439	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	175	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2440	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	182	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2441	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	177	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2442	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	183	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2443	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	184	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2444	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	211	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2445	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	205	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2446	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	185	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2447	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	186	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2448	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	187	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2449	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	188	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2450	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	189	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2451	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	171	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2452	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	190	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2453	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	192	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2454	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	193	Pendiente	\N	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
2455	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	194	Pendiente	\N	\N	2024-04-25 17:45:09	2024-04-25 17:45:09
2456	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	191	Pendiente	\N	\N	2024-04-25 17:45:09	2024-04-25 17:45:09
2457	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	195	Pendiente	\N	\N	2024-04-25 17:45:09	2024-04-25 17:45:09
2458	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	196	Pendiente	\N	\N	2024-04-25 17:45:09	2024-04-25 17:45:09
2459	1	9be4bc1c-6178-4f10-a2eb-396841c7ee61	212	Pendiente	\N	\N	2024-04-25 17:45:09	2024-04-25 17:45:09
2460	1	9be4bc4a-6707-4cee-8c38-8093a936b729	206	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2461	1	9be4bc4a-6707-4cee-8c38-8093a936b729	117	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2462	1	9be4bc4a-6707-4cee-8c38-8093a936b729	178	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2463	1	9be4bc4a-6707-4cee-8c38-8093a936b729	119	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2464	1	9be4bc4a-6707-4cee-8c38-8093a936b729	213	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2465	1	9be4bc4a-6707-4cee-8c38-8093a936b729	99	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2466	1	9be4bc4a-6707-4cee-8c38-8093a936b729	140	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2467	1	9be4bc4a-6707-4cee-8c38-8093a936b729	100	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2468	1	9be4bc4a-6707-4cee-8c38-8093a936b729	122	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2469	1	9be4bc4a-6707-4cee-8c38-8093a936b729	144	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2470	1	9be4bc4a-6707-4cee-8c38-8093a936b729	148	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2471	1	9be4bc4a-6707-4cee-8c38-8093a936b729	98	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2472	1	9be4bc4a-6707-4cee-8c38-8093a936b729	101	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2473	1	9be4bc4a-6707-4cee-8c38-8093a936b729	118	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2474	1	9be4bc4a-6707-4cee-8c38-8093a936b729	102	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2475	1	9be4bc4a-6707-4cee-8c38-8093a936b729	145	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2476	1	9be4bc4a-6707-4cee-8c38-8093a936b729	142	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2477	1	9be4bc4a-6707-4cee-8c38-8093a936b729	103	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2478	1	9be4bc4a-6707-4cee-8c38-8093a936b729	120	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2479	1	9be4bc4a-6707-4cee-8c38-8093a936b729	104	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2480	1	9be4bc4a-6707-4cee-8c38-8093a936b729	105	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2481	1	9be4bc4a-6707-4cee-8c38-8093a936b729	106	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2482	1	9be4bc4a-6707-4cee-8c38-8093a936b729	107	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2483	1	9be4bc4a-6707-4cee-8c38-8093a936b729	108	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2484	1	9be4bc4a-6707-4cee-8c38-8093a936b729	137	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2485	1	9be4bc4a-6707-4cee-8c38-8093a936b729	109	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2486	1	9be4bc4a-6707-4cee-8c38-8093a936b729	112	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2487	1	9be4bc4a-6707-4cee-8c38-8093a936b729	110	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2488	1	9be4bc4a-6707-4cee-8c38-8093a936b729	141	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2489	1	9be4bc4a-6707-4cee-8c38-8093a936b729	111	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2490	1	9be4bc4a-6707-4cee-8c38-8093a936b729	113	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2491	1	9be4bc4a-6707-4cee-8c38-8093a936b729	114	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2492	1	9be4bc4a-6707-4cee-8c38-8093a936b729	115	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2493	1	9be4bc4a-6707-4cee-8c38-8093a936b729	116	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2494	1	9be4bc4a-6707-4cee-8c38-8093a936b729	123	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2495	1	9be4bc4a-6707-4cee-8c38-8093a936b729	124	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2496	1	9be4bc4a-6707-4cee-8c38-8093a936b729	138	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2497	1	9be4bc4a-6707-4cee-8c38-8093a936b729	207	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2498	1	9be4bc4a-6707-4cee-8c38-8093a936b729	125	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2499	1	9be4bc4a-6707-4cee-8c38-8093a936b729	126	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2500	1	9be4bc4a-6707-4cee-8c38-8093a936b729	127	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2501	1	9be4bc4a-6707-4cee-8c38-8093a936b729	121	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2502	1	9be4bc4a-6707-4cee-8c38-8093a936b729	128	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2503	1	9be4bc4a-6707-4cee-8c38-8093a936b729	136	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2504	1	9be4bc4a-6707-4cee-8c38-8093a936b729	130	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2505	1	9be4bc4a-6707-4cee-8c38-8093a936b729	149	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2506	1	9be4bc4a-6707-4cee-8c38-8093a936b729	146	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2507	1	9be4bc4a-6707-4cee-8c38-8093a936b729	129	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2508	1	9be4bc4a-6707-4cee-8c38-8093a936b729	131	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2509	1	9be4bc4a-6707-4cee-8c38-8093a936b729	139	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2510	1	9be4bc4a-6707-4cee-8c38-8093a936b729	208	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2511	1	9be4bc4a-6707-4cee-8c38-8093a936b729	143	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2512	1	9be4bc4a-6707-4cee-8c38-8093a936b729	214	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2513	1	9be4bc4a-6707-4cee-8c38-8093a936b729	147	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2514	1	9be4bc4a-6707-4cee-8c38-8093a936b729	150	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2515	1	9be4bc4a-6707-4cee-8c38-8093a936b729	133	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2516	1	9be4bc4a-6707-4cee-8c38-8093a936b729	134	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2517	1	9be4bc4a-6707-4cee-8c38-8093a936b729	135	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2518	1	9be4bc4a-6707-4cee-8c38-8093a936b729	132	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2519	1	9be4bc4a-6707-4cee-8c38-8093a936b729	172	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2520	1	9be4bc4a-6707-4cee-8c38-8093a936b729	209	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2521	1	9be4bc4a-6707-4cee-8c38-8093a936b729	180	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2522	1	9be4bc4a-6707-4cee-8c38-8093a936b729	215	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2523	1	9be4bc4a-6707-4cee-8c38-8093a936b729	210	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2524	1	9be4bc4a-6707-4cee-8c38-8093a936b729	216	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2525	1	9be4bc4a-6707-4cee-8c38-8093a936b729	199	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2526	1	9be4bc4a-6707-4cee-8c38-8093a936b729	197	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2527	1	9be4bc4a-6707-4cee-8c38-8093a936b729	201	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2528	1	9be4bc4a-6707-4cee-8c38-8093a936b729	198	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2529	1	9be4bc4a-6707-4cee-8c38-8093a936b729	203	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2530	1	9be4bc4a-6707-4cee-8c38-8093a936b729	200	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2531	1	9be4bc4a-6707-4cee-8c38-8093a936b729	204	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2532	1	9be4bc4a-6707-4cee-8c38-8093a936b729	202	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2533	1	9be4bc4a-6707-4cee-8c38-8093a936b729	165	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2534	1	9be4bc4a-6707-4cee-8c38-8093a936b729	163	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2535	1	9be4bc4a-6707-4cee-8c38-8093a936b729	176	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2536	1	9be4bc4a-6707-4cee-8c38-8093a936b729	179	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2537	1	9be4bc4a-6707-4cee-8c38-8093a936b729	164	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2538	1	9be4bc4a-6707-4cee-8c38-8093a936b729	181	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2539	1	9be4bc4a-6707-4cee-8c38-8093a936b729	166	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2540	1	9be4bc4a-6707-4cee-8c38-8093a936b729	167	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2541	1	9be4bc4a-6707-4cee-8c38-8093a936b729	168	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2542	1	9be4bc4a-6707-4cee-8c38-8093a936b729	169	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2543	1	9be4bc4a-6707-4cee-8c38-8093a936b729	170	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2544	1	9be4bc4a-6707-4cee-8c38-8093a936b729	173	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2545	1	9be4bc4a-6707-4cee-8c38-8093a936b729	174	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2546	1	9be4bc4a-6707-4cee-8c38-8093a936b729	175	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2547	1	9be4bc4a-6707-4cee-8c38-8093a936b729	182	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2548	1	9be4bc4a-6707-4cee-8c38-8093a936b729	177	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2549	1	9be4bc4a-6707-4cee-8c38-8093a936b729	183	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2550	1	9be4bc4a-6707-4cee-8c38-8093a936b729	184	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2551	1	9be4bc4a-6707-4cee-8c38-8093a936b729	211	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2552	1	9be4bc4a-6707-4cee-8c38-8093a936b729	205	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2553	1	9be4bc4a-6707-4cee-8c38-8093a936b729	185	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2554	1	9be4bc4a-6707-4cee-8c38-8093a936b729	186	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2555	1	9be4bc4a-6707-4cee-8c38-8093a936b729	187	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2556	1	9be4bc4a-6707-4cee-8c38-8093a936b729	188	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2557	1	9be4bc4a-6707-4cee-8c38-8093a936b729	189	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2558	1	9be4bc4a-6707-4cee-8c38-8093a936b729	171	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2559	1	9be4bc4a-6707-4cee-8c38-8093a936b729	190	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2560	1	9be4bc4a-6707-4cee-8c38-8093a936b729	192	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2561	1	9be4bc4a-6707-4cee-8c38-8093a936b729	193	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2562	1	9be4bc4a-6707-4cee-8c38-8093a936b729	194	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2563	1	9be4bc4a-6707-4cee-8c38-8093a936b729	191	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2564	1	9be4bc4a-6707-4cee-8c38-8093a936b729	195	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2565	1	9be4bc4a-6707-4cee-8c38-8093a936b729	196	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2566	1	9be4bc4a-6707-4cee-8c38-8093a936b729	212	Pendiente	\N	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
2567	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	206	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2568	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	117	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2569	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	178	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2570	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	119	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2571	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	213	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2572	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	99	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2573	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	140	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2574	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	100	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2575	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	122	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2576	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	144	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2577	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	148	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2578	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	98	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2579	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	101	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2580	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	118	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2581	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	102	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2582	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	145	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2583	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	142	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2584	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	103	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2585	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	120	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2586	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	104	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2587	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	105	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2588	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	106	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2589	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	107	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2590	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	108	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2591	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	137	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2592	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	109	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2593	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	112	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2594	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	110	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2595	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	141	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2596	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	111	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2597	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	113	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2598	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	114	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2599	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	115	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2600	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	116	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2601	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	123	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2602	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	124	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2603	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	138	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2604	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	207	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2605	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	125	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2606	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	126	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2607	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	127	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2608	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	121	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2609	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	128	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2610	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	136	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2611	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	130	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2612	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	149	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2613	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	146	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2614	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	129	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2615	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	131	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2616	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	139	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2617	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	208	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2618	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	143	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2619	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	214	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2620	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	147	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2621	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	150	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2622	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	133	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2623	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	134	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2624	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	135	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2625	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	132	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2626	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	172	Pendiente	\N	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
2627	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	209	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2628	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	180	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2629	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	215	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2630	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	210	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2631	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	216	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2632	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	199	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2633	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	197	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2634	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	201	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2635	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	198	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2636	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	203	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2637	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	200	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2638	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	204	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2639	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	202	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2640	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	165	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2641	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	163	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2642	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	176	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2643	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	179	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2644	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	164	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2645	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	181	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2646	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	166	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2647	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	167	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2648	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	168	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2649	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	169	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2650	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	170	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2651	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	173	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2652	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	174	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2653	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	175	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2654	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	182	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2655	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	177	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2656	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	183	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2657	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	184	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2658	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	211	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2659	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	205	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2660	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	185	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2661	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	186	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2662	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	187	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2663	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	188	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2664	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	189	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2665	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	171	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2666	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	190	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2667	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	192	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2668	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	193	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2669	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	194	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2670	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	191	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2671	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	195	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2672	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	196	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2673	1	9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	212	Pendiente	\N	\N	2024-04-25 17:46:02	2024-04-25 17:46:02
2674	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	206	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2675	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	117	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2676	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	178	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2677	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	119	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2678	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	213	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2679	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	99	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2680	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	140	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2681	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	100	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2682	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	122	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2683	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	144	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2684	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	148	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2685	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	98	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2686	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	101	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2687	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	118	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2688	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	102	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2689	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	145	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2690	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	142	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2691	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	103	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2692	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	120	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2693	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	104	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2694	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	105	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2695	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	106	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2696	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	107	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2697	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	108	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2698	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	137	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2699	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	109	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2700	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	112	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2701	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	110	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2702	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	141	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2703	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	111	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2704	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	113	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2705	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	114	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2706	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	115	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2707	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	116	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2708	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	123	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2709	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	124	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2710	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	138	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2711	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	207	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2712	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	125	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2713	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	126	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2714	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	127	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2715	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	121	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2716	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	128	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2717	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	136	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2718	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	130	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2719	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	149	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2720	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	146	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2721	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	129	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2722	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	131	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2723	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	139	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2724	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	208	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2725	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	143	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2726	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	214	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2727	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	147	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2728	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	150	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2729	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	133	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2730	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	134	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2731	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	135	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2732	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	132	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2733	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	172	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2734	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	209	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2735	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	180	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2736	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	215	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2737	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	210	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2738	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	216	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2739	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	199	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2740	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	197	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2741	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	201	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2742	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	198	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2743	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	203	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2744	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	200	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2745	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	204	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2746	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	202	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2747	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	165	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2748	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	163	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2749	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	176	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2750	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	179	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2751	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	164	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2752	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	181	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2753	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	166	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2754	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	167	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2755	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	168	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2756	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	169	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2757	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	170	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2758	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	173	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2759	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	174	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2760	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	175	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2761	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	182	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2762	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	177	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2763	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	183	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2764	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	184	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2765	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	211	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2766	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	205	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2767	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	185	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2768	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	186	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2769	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	187	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2770	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	188	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2771	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	189	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2772	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	171	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2773	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	190	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2774	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	192	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2775	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	193	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2776	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	194	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2777	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	191	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2778	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	195	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2779	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	196	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2780	1	9be4bc92-5758-487f-9eba-cfbc54a8e128	212	Pendiente	\N	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
2781	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	206	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2782	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	117	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2783	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	178	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2784	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	119	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2785	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	213	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2786	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	99	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2787	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	140	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2788	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	100	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2789	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	122	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2790	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	144	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2791	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	148	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2792	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	98	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2793	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	101	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2794	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	118	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2795	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	102	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2796	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	145	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2797	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	142	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2798	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	103	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2799	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	120	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2800	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	104	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2801	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	105	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2802	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	106	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2803	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	107	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2804	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	108	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2805	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	137	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2806	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	109	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2807	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	112	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2808	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	110	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2809	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	141	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2810	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	111	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2811	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	113	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2812	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	114	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2813	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	115	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2814	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	116	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2815	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	123	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2816	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	124	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2817	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	138	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2818	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	207	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2819	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	125	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2820	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	126	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2821	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	127	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2822	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	121	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2823	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	128	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2824	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	136	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2825	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	130	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2826	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	149	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2827	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	146	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2828	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	129	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2829	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	131	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2830	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	139	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2831	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	208	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2832	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	143	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2833	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	214	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2834	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	147	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2835	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	150	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2836	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	133	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2837	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	134	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2838	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	135	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2839	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	132	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2840	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	172	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2841	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	209	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2842	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	180	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2843	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	215	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2844	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	210	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2845	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	216	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2846	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	199	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2847	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	197	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2848	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	201	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2849	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	198	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2850	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	203	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2851	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	200	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2852	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	204	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2853	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	202	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2854	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	165	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2855	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	163	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2856	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	176	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2857	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	179	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2858	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	164	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2859	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	181	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2860	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	166	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2861	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	167	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2862	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	168	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2863	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	169	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2864	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	170	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2865	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	173	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2866	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	174	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2867	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	175	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2868	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	182	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2869	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	177	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2870	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	183	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2871	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	184	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2872	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	211	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2873	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	205	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2874	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	185	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2875	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	186	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2876	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	187	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2877	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	188	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2878	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	189	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2879	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	171	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2880	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	190	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2881	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	192	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2882	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	193	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2883	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	194	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2884	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	191	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2885	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	195	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2886	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	196	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2887	1	9be4bccd-4b41-48d2-b6c8-9a586e0bd165	212	Pendiente	\N	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
2888	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	206	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2889	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	117	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2890	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	178	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2891	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	119	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2892	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	213	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2893	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	99	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2894	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	140	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2895	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	100	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2896	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	122	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2897	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	144	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2898	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	148	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2899	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	98	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2900	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	101	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2901	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	118	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2902	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	102	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2903	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	145	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2904	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	142	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2905	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	103	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2906	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	120	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2907	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	104	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2908	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	105	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2909	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	106	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2910	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	107	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2911	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	108	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2912	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	137	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2913	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	109	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2914	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	112	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2915	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	110	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2916	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	141	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2917	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	111	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2918	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	113	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2919	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	114	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2920	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	115	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2921	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	116	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2922	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	123	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2923	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	124	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2924	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	138	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2925	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	207	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2926	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	125	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2927	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	126	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2928	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	127	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2929	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	121	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2930	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	128	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2931	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	136	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2932	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	130	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2933	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	149	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2934	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	146	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2935	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	129	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2936	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	131	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2937	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	139	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2938	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	208	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2939	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	143	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2940	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	214	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2941	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	147	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2942	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	150	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2943	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	133	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2944	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	134	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2945	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	135	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2946	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	132	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2947	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	172	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2948	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	209	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2949	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	180	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2950	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	215	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2951	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	210	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2952	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	216	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2953	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	199	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2954	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	197	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2955	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	201	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2956	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	198	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2957	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	203	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2958	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	200	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2959	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	204	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2960	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	202	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2961	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	165	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2962	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	163	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2963	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	176	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2964	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	179	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2965	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	164	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2966	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	181	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2967	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	166	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2968	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	167	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2969	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	168	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2970	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	169	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2971	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	170	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2972	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	173	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2973	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	174	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2974	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	175	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2975	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	182	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2976	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	177	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2977	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	183	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2978	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	184	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2979	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	211	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2980	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	205	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2981	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	185	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2982	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	186	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2983	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	187	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2984	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	188	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2985	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	189	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2986	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	171	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2987	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	190	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2988	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	192	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2989	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	193	Pendiente	\N	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
2990	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	194	Pendiente	\N	\N	2024-04-25 17:47:30	2024-04-25 17:47:30
2991	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	191	Pendiente	\N	\N	2024-04-25 17:47:30	2024-04-25 17:47:30
2992	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	195	Pendiente	\N	\N	2024-04-25 17:47:30	2024-04-25 17:47:30
2993	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	196	Pendiente	\N	\N	2024-04-25 17:47:30	2024-04-25 17:47:30
2994	1	9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	212	Pendiente	\N	\N	2024-04-25 17:47:30	2024-04-25 17:47:30
2995	1	9be4bd15-3026-42de-87d5-5ae1bb412470	206	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
2996	1	9be4bd15-3026-42de-87d5-5ae1bb412470	117	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
2997	1	9be4bd15-3026-42de-87d5-5ae1bb412470	178	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
2998	1	9be4bd15-3026-42de-87d5-5ae1bb412470	119	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
2999	1	9be4bd15-3026-42de-87d5-5ae1bb412470	213	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3000	1	9be4bd15-3026-42de-87d5-5ae1bb412470	99	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3001	1	9be4bd15-3026-42de-87d5-5ae1bb412470	140	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3002	1	9be4bd15-3026-42de-87d5-5ae1bb412470	100	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3003	1	9be4bd15-3026-42de-87d5-5ae1bb412470	122	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3004	1	9be4bd15-3026-42de-87d5-5ae1bb412470	144	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3005	1	9be4bd15-3026-42de-87d5-5ae1bb412470	148	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3006	1	9be4bd15-3026-42de-87d5-5ae1bb412470	98	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3007	1	9be4bd15-3026-42de-87d5-5ae1bb412470	101	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3008	1	9be4bd15-3026-42de-87d5-5ae1bb412470	118	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3009	1	9be4bd15-3026-42de-87d5-5ae1bb412470	102	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3010	1	9be4bd15-3026-42de-87d5-5ae1bb412470	145	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3011	1	9be4bd15-3026-42de-87d5-5ae1bb412470	142	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3012	1	9be4bd15-3026-42de-87d5-5ae1bb412470	103	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3013	1	9be4bd15-3026-42de-87d5-5ae1bb412470	120	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3014	1	9be4bd15-3026-42de-87d5-5ae1bb412470	104	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3015	1	9be4bd15-3026-42de-87d5-5ae1bb412470	105	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3016	1	9be4bd15-3026-42de-87d5-5ae1bb412470	106	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3017	1	9be4bd15-3026-42de-87d5-5ae1bb412470	107	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3018	1	9be4bd15-3026-42de-87d5-5ae1bb412470	108	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3019	1	9be4bd15-3026-42de-87d5-5ae1bb412470	137	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3020	1	9be4bd15-3026-42de-87d5-5ae1bb412470	109	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3021	1	9be4bd15-3026-42de-87d5-5ae1bb412470	112	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3022	1	9be4bd15-3026-42de-87d5-5ae1bb412470	110	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3023	1	9be4bd15-3026-42de-87d5-5ae1bb412470	141	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3024	1	9be4bd15-3026-42de-87d5-5ae1bb412470	111	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3025	1	9be4bd15-3026-42de-87d5-5ae1bb412470	113	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3026	1	9be4bd15-3026-42de-87d5-5ae1bb412470	114	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3027	1	9be4bd15-3026-42de-87d5-5ae1bb412470	115	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3028	1	9be4bd15-3026-42de-87d5-5ae1bb412470	116	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3029	1	9be4bd15-3026-42de-87d5-5ae1bb412470	123	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3030	1	9be4bd15-3026-42de-87d5-5ae1bb412470	124	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3031	1	9be4bd15-3026-42de-87d5-5ae1bb412470	138	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3032	1	9be4bd15-3026-42de-87d5-5ae1bb412470	207	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3033	1	9be4bd15-3026-42de-87d5-5ae1bb412470	125	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3034	1	9be4bd15-3026-42de-87d5-5ae1bb412470	126	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3035	1	9be4bd15-3026-42de-87d5-5ae1bb412470	127	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3036	1	9be4bd15-3026-42de-87d5-5ae1bb412470	121	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3037	1	9be4bd15-3026-42de-87d5-5ae1bb412470	128	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3038	1	9be4bd15-3026-42de-87d5-5ae1bb412470	136	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3039	1	9be4bd15-3026-42de-87d5-5ae1bb412470	130	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3040	1	9be4bd15-3026-42de-87d5-5ae1bb412470	149	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3041	1	9be4bd15-3026-42de-87d5-5ae1bb412470	146	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3042	1	9be4bd15-3026-42de-87d5-5ae1bb412470	129	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3043	1	9be4bd15-3026-42de-87d5-5ae1bb412470	131	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3044	1	9be4bd15-3026-42de-87d5-5ae1bb412470	139	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3045	1	9be4bd15-3026-42de-87d5-5ae1bb412470	208	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3046	1	9be4bd15-3026-42de-87d5-5ae1bb412470	143	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3047	1	9be4bd15-3026-42de-87d5-5ae1bb412470	214	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3048	1	9be4bd15-3026-42de-87d5-5ae1bb412470	147	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3049	1	9be4bd15-3026-42de-87d5-5ae1bb412470	150	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3050	1	9be4bd15-3026-42de-87d5-5ae1bb412470	133	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3051	1	9be4bd15-3026-42de-87d5-5ae1bb412470	134	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3052	1	9be4bd15-3026-42de-87d5-5ae1bb412470	135	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3053	1	9be4bd15-3026-42de-87d5-5ae1bb412470	132	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3054	1	9be4bd15-3026-42de-87d5-5ae1bb412470	172	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3055	1	9be4bd15-3026-42de-87d5-5ae1bb412470	209	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3056	1	9be4bd15-3026-42de-87d5-5ae1bb412470	180	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3057	1	9be4bd15-3026-42de-87d5-5ae1bb412470	215	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3058	1	9be4bd15-3026-42de-87d5-5ae1bb412470	210	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3059	1	9be4bd15-3026-42de-87d5-5ae1bb412470	216	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3060	1	9be4bd15-3026-42de-87d5-5ae1bb412470	199	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3061	1	9be4bd15-3026-42de-87d5-5ae1bb412470	197	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3062	1	9be4bd15-3026-42de-87d5-5ae1bb412470	201	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3063	1	9be4bd15-3026-42de-87d5-5ae1bb412470	198	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3064	1	9be4bd15-3026-42de-87d5-5ae1bb412470	203	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3065	1	9be4bd15-3026-42de-87d5-5ae1bb412470	200	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3066	1	9be4bd15-3026-42de-87d5-5ae1bb412470	204	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3067	1	9be4bd15-3026-42de-87d5-5ae1bb412470	202	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3068	1	9be4bd15-3026-42de-87d5-5ae1bb412470	165	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3069	1	9be4bd15-3026-42de-87d5-5ae1bb412470	163	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3070	1	9be4bd15-3026-42de-87d5-5ae1bb412470	176	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3071	1	9be4bd15-3026-42de-87d5-5ae1bb412470	179	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3072	1	9be4bd15-3026-42de-87d5-5ae1bb412470	164	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3073	1	9be4bd15-3026-42de-87d5-5ae1bb412470	181	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3074	1	9be4bd15-3026-42de-87d5-5ae1bb412470	166	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3075	1	9be4bd15-3026-42de-87d5-5ae1bb412470	167	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3076	1	9be4bd15-3026-42de-87d5-5ae1bb412470	168	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3077	1	9be4bd15-3026-42de-87d5-5ae1bb412470	169	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3078	1	9be4bd15-3026-42de-87d5-5ae1bb412470	170	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3079	1	9be4bd15-3026-42de-87d5-5ae1bb412470	173	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3080	1	9be4bd15-3026-42de-87d5-5ae1bb412470	174	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3081	1	9be4bd15-3026-42de-87d5-5ae1bb412470	175	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3082	1	9be4bd15-3026-42de-87d5-5ae1bb412470	182	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3083	1	9be4bd15-3026-42de-87d5-5ae1bb412470	177	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3084	1	9be4bd15-3026-42de-87d5-5ae1bb412470	183	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3085	1	9be4bd15-3026-42de-87d5-5ae1bb412470	184	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3086	1	9be4bd15-3026-42de-87d5-5ae1bb412470	211	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3087	1	9be4bd15-3026-42de-87d5-5ae1bb412470	205	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3088	1	9be4bd15-3026-42de-87d5-5ae1bb412470	185	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3089	1	9be4bd15-3026-42de-87d5-5ae1bb412470	186	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3090	1	9be4bd15-3026-42de-87d5-5ae1bb412470	187	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3091	1	9be4bd15-3026-42de-87d5-5ae1bb412470	188	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3092	1	9be4bd15-3026-42de-87d5-5ae1bb412470	189	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3093	1	9be4bd15-3026-42de-87d5-5ae1bb412470	171	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3094	1	9be4bd15-3026-42de-87d5-5ae1bb412470	190	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3095	1	9be4bd15-3026-42de-87d5-5ae1bb412470	192	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3096	1	9be4bd15-3026-42de-87d5-5ae1bb412470	193	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3097	1	9be4bd15-3026-42de-87d5-5ae1bb412470	194	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3098	1	9be4bd15-3026-42de-87d5-5ae1bb412470	191	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3099	1	9be4bd15-3026-42de-87d5-5ae1bb412470	195	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3100	1	9be4bd15-3026-42de-87d5-5ae1bb412470	196	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3101	1	9be4bd15-3026-42de-87d5-5ae1bb412470	212	Pendiente	\N	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
3102	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	206	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3103	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	117	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3104	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	178	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3105	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	119	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3106	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	213	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3107	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	99	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3108	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	140	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3109	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	100	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3110	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	122	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3111	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	144	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3112	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	148	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3113	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	98	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3114	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	101	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3115	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	118	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3116	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	102	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3117	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	145	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3118	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	142	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3119	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	103	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3120	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	120	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3121	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	104	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3122	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	105	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3123	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	106	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3124	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	107	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3125	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	108	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3126	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	137	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3127	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	109	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3128	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	112	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3129	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	110	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3130	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	141	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3131	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	111	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3132	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	113	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3133	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	114	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3134	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	115	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3135	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	116	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3136	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	123	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3137	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	124	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3138	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	138	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3139	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	207	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3140	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	125	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3141	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	126	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3142	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	127	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3143	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	121	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3144	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	128	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3145	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	136	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3146	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	130	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3147	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	149	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3148	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	146	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3149	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	129	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3150	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	131	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3151	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	139	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3152	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	208	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3153	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	143	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3154	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	214	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3155	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	147	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3156	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	150	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3157	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	133	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3158	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	134	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3159	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	135	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3160	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	132	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3161	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	172	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3162	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	209	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3163	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	180	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3164	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	215	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3165	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	210	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3166	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	216	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3167	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	199	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3168	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	197	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3169	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	201	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3170	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	198	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3171	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	203	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3172	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	200	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3173	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	204	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3174	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	202	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3175	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	165	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3176	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	163	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3177	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	176	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3178	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	179	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3179	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	164	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3180	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	181	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3181	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	166	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3182	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	167	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3183	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	168	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3184	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	169	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3185	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	170	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3186	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	173	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3187	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	174	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3188	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	175	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3189	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	182	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3190	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	177	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3191	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	183	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3192	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	184	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3193	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	211	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3194	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	205	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3195	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	185	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3196	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	186	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3197	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	187	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3198	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	188	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3199	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	189	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3200	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	171	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3201	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	190	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3202	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	192	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3203	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	193	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3204	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	194	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3205	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	191	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3206	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	195	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3207	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	196	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
3208	1	9be4bd41-6f2c-4692-bb29-7ee1ee190d92	212	Pendiente	\N	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
\.


--
-- Data for Name: foda_categorias_has_perfil; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_categorias_has_perfil (perfil_id, category_id, created_at, updated_at) FROM stdin;
9a670199-515d-45e0-9adf-da963f6cb5cd	9	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	2	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	31	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	86	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	77	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	72	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	65	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	38	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	34	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	28	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	8	\N	\N
9a670199-515d-45e0-9adf-da963f6cb5cd	5	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	9	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	2	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	31	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	86	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	77	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	72	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	65	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	38	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	34	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	28	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	8	\N	\N
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	5	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	9	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	2	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	31	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	86	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	77	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	72	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	65	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	38	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	34	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	28	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	8	\N	\N
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	5	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	9	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	2	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	31	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	86	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	77	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	72	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	65	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	38	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	34	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	28	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	8	\N	\N
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	5	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	9	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	2	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	31	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	86	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	77	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	72	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	65	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	38	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	34	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	28	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	8	\N	\N
9a670613-48f6-4af7-8680-57660453ee1b	5	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	9	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	2	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	31	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	86	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	77	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	72	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	65	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	38	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	34	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	28	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	8	\N	\N
9a6706ec-e7b4-4906-9428-09e20268acbb	5	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	9	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	2	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	31	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	86	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	77	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	72	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	65	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	38	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	34	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	28	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	8	\N	\N
9a670886-a987-4724-b5bd-8d84db396f9a	5	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	9	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	2	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	31	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	86	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	77	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	72	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	65	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	38	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	34	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	28	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	8	\N	\N
9a670976-68cf-4f5e-b4f5-4c56931aa175	5	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	9	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	2	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	31	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	86	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	77	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	72	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	65	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	38	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	34	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	28	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	8	\N	\N
9a670a4d-dc08-48b3-be07-fb2a830e199f	5	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	86	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	65	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	5	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	77	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	28	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	9	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	31	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	2	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	8	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	72	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	34	\N	\N
9a670c4c-1010-4ad0-97ad-0194abe0930d	38	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	86	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	65	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	5	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	77	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	28	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	9	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	31	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	2	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	8	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	72	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	34	\N	\N
9a670d46-01c4-4466-ad21-73d8f16bf0a8	38	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	86	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	65	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	5	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	77	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	28	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	9	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	31	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	2	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	8	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	72	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	34	\N	\N
9a670e44-aeed-4cfe-9c60-5d305913f7cb	38	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	86	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	65	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	5	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	77	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	28	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	9	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	31	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	2	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	8	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	72	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	34	\N	\N
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	38	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	86	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	65	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	5	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	77	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	28	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	9	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	31	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	2	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	8	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	72	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	34	\N	\N
9a670fde-7520-4e65-8d55-46e9d730abfc	38	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	86	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	65	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	5	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	77	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	28	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	9	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	31	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	2	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	8	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	72	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	34	\N	\N
9a6710ce-015e-422e-a288-b63cb70a72b2	38	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	86	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	77	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	72	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	65	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	38	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	34	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	31	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	28	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	9	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	8	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	5	\N	\N
9a672fc4-d900-4b24-9e9b-d71943b1470f	2	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	86	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	77	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	72	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	65	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	38	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	34	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	31	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	28	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	9	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	8	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	5	\N	\N
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	2	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	86	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	77	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	72	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	65	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	38	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	34	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	31	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	28	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	9	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	8	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	5	\N	\N
9a684a9e-0630-432c-8e2e-af213a33543a	2	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	86	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	77	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	72	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	65	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	38	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	34	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	31	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	28	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	9	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	8	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	5	\N	\N
9a684cfb-8d97-4115-ae88-855f59885d0c	2	\N	\N
\.


--
-- Data for Name: foda_cruce_ambientes; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_cruce_ambientes (id, user_id, perfil_id, estrategia, tipo, created_at, updated_at) FROM stdin;
10	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Difusión de los Programas Sociales por Redes (Hipertensión Diabetes)	FO	2023-10-26 14:31:13	2023-10-26 14:31:13
11	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Capacitación continua del Talento Humano en el uso de Herramientas Ofimáticas (DHAC)	FO	2023-10-26 14:33:03	2023-10-26 14:33:03
12	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Investigación continua e inversión para desarrollo de Programas basados en Tecnologías Diponibles (DHAC)	FO	2023-10-26 14:34:29	2023-10-26 14:34:44
13	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Mejoramiento de atención a través herramienta tecnológica APP - MEDICASA ((DHAC))	FO	2023-10-26 14:36:00	2023-10-26 14:36:00
14	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Fortalecimiento y uso de los Equipos Biomédicos disponibles con programa de mantenimiento continuo. ((DHAC))	FA	2023-10-26 14:38:25	2023-10-26 14:38:25
15	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Desentralización de Electromedicina para satisfacer la demanda de restauración de quipos(DHAC)	FA	2023-10-26 14:40:27	2023-10-26 14:40:27
16	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Fortalecimiento de Capacitaciones basadas en Medicina Preventiva (DHAC)	DO	2023-10-26 14:43:16	2023-10-26 14:43:16
19	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Capacitación y fortalecimiento del Talento Humano en Atención Directa a los Asegurados ((DHAC)) . Humanizar la atención. Rotación del Talento Humano.	DA	2023-10-26 14:48:57	2023-10-26 14:48:57
20	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Mejoramiento de los servicios tercerizados con enfoque de Fiscalización Rigurosa (Control Interno) - Control y cumplimiento de Servicios Tercerizados ((DHAC))	DO	2023-10-26 14:52:38	2023-10-26 14:55:17
21	1	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Mejoramiento el tiempo de culminación de las obras de infraestructura (DHAC)	FA	2023-10-26 15:02:40	2023-10-26 15:02:40
17	12	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Mejoramiento de Infraestructura Física disponible con enfoque inclusivo (DHAC)	DO	2023-10-26 14:44:22	2023-11-02 12:28:29
24	12	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Promoción de la aplicación del concepto de salud digital en las RIISS (DHAC)	FA	2023-11-01 13:11:30	2023-11-02 12:29:20
22	12	9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	Difusión de Programas Sociales (Asuriesgo, Diabetes, Hipertensión, Medicasa, AquaSerenity) con enfoque preventivo (DHAC)	DA	2023-10-26 15:04:35	2023-11-02 12:29:58
\.


--
-- Data for Name: foda_cruce_ambientes_has_amenazas; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_cruce_ambientes_has_amenazas (cruce_id, amenaza_id) FROM stdin;
14	7
14	26
14	32
15	7
15	33
15	21
19	7
19	32
19	21
21	7
21	26
21	32
21	21
22	7
22	32
22	21
24	7
24	26
24	33
24	32
24	95
24	21
\.


--
-- Data for Name: foda_cruce_ambientes_has_debilidades; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_cruce_ambientes_has_debilidades (cruce_id, debilidad_id) FROM stdin;
16	87
17	90
20	71
22	87
19	47
\.


--
-- Data for Name: foda_cruce_ambientes_has_fortalezas; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_cruce_ambientes_has_fortalezas (cruce_id, fortaleza_id) FROM stdin;
10	89
10	88
10	42
11	74
12	70
13	88
14	70
15	70
21	58
24	89
\.


--
-- Data for Name: foda_cruce_ambientes_has_oportunidades; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_cruce_ambientes_has_oportunidades (cruce_id, oportunidad_id) FROM stdin;
10	35
11	36
12	27
12	37
12	36
13	36
16	35
16	30
16	23
17	27
20	27
\.


--
-- Data for Name: foda_groups_has_perfiles; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_groups_has_perfiles (id, perfil_id, group_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: foda_models; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_models (id, type, name, owner, environment, description, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
96	aspect	Enfoque de Red de Servicios de Salud	IPS	Interno	<p>Articulación de los servicios de salud a través del modelo de redes integradas e integrales de servicios de salud (RIISS)</p>	173	174	86	2023-10-26 17:37:16	2023-10-26 17:37:16
7	aspect	Atención a los usuarios	IPS	Interno	<p>Percepcion de nuestros asegurados sobre calida de atencion</p>	3	4	5	2023-10-18 15:36:36	2023-10-18 15:36:36
23	aspect	Cantidad de Médicos especialistas	IPS	Externo	<p>Cantidad de Médicos especializados para casos compelejos</p>	37	38	8	2023-10-18 20:01:07	2023-10-18 20:01:07
4	aspect	Presiones externas para modificar proyectos	IPS	Externo	<p>Presiones externas para modificar proyectos</p>	15	16	2	2023-10-18 15:34:01	2023-10-18 19:53:27
94	aspect	Diversificación de Colocaciones	IPS	Externo	<p>Mercado Financiero reducido (Escasa entidades financieras para colocación de fondos)</p>	11	12	5	2023-10-19 13:51:17	2023-10-19 13:51:17
78	aspect	Ordenamiento del Gasto	IPS	Interno	<p>Ordenamiento del Gasto. Priorización. Estructura burocrática.</p>	161	162	77	2023-10-18 22:20:13	2023-10-19 13:44:59
80	aspect	Reglamento de cobertura en período de carencia	IPS	Interno	<p>Reglamento de cobertura en período de carencia. (Pandemia COVID-19).</p>	157	158	77	2023-10-18 22:20:48	2023-10-19 13:42:24
35	aspect	Redes Sociales	IPS	Externo	<p>Redes Sociales</p>	63	64	34	2023-10-18 20:12:32	2023-10-18 20:12:32
27	aspect	Alternativas de inversiones.	IPS	Externo	<p>Existen alternativas de inversión con instituciones multilaterales como el BID o el INVEST?</p>	47	48	9	2023-10-18 20:06:51	2023-10-18 20:06:51
58	aspect	Infraestructura Física	IPS	Interno	<p>Contamos con suficiente infraestructura física. Existe algun estudio que pueda validarlo? Es la infraestructura actual suficiente para las demandas de nuestros clientes.</p>	109	110	38	2023-10-18 21:52:08	2023-10-18 21:52:08
47	aspect	Atención a los asegurados del área médica	IPS	Interno	<p>Reporte CallCenter, hospital central y otros de referencia del IPS</p>	87	88	38	2023-10-18 21:44:13	2023-10-18 21:44:13
31	category	Imagen Institucional	IPS	Externo	<p>Percepción de nuestros asegurados y la ciudanía en general</p>	56	61	1	2023-10-18 20:10:07	2023-10-18 20:10:07
60	aspect	Mecanismo de control de las Prestaciones Tercerizadas	IPS	Interno	<p>Mecanismo de control de las Prestaciones Tercerizadas</p>	113	114	38	2023-10-18 21:53:53	2023-10-18 21:53:53
95	aspect	Crecimiento Demográfico	IPS	Externo	<p>Reducción de la Natalidad.</p>	41	42	8	2023-10-19 13:52:20	2023-10-19 13:52:21
92	aspect	Tasa de Interés	IPS	Externo	<p>Suba de tasas por inflación</p>	7	8	5	2023-10-19 13:49:37	2023-10-19 13:49:37
19	aspect	Patologías Crónicas	IPS	Externo	<p>% de aumento de pacientes con patologías crónicas</p>	29	30	8	2023-10-18 19:57:45	2023-10-18 19:57:45
18	aspect	Enfermedades Infecciosa	IPS	Externo	<p>Covid, Dengue (Salud advierte de Epidemia de dengue histórica)</p>	27	28	8	2023-10-18 19:56:51	2023-10-18 19:56:51
22	aspect	Demandas laborales	IPS	Externo	<p>Demandas laborales insatisfechas de los funcionarios</p>	35	36	8	2023-10-18 20:00:22	2023-10-18 20:00:22
33	aspect	Credibilidad	IPS	Externo	<p>Reducción de la credibilidad institucional ante la Opinión Pública, ciudadania débil y/o mal informada</p>	59	60	31	2023-10-18 20:11:37	2023-10-18 20:11:37
49	aspect	Cambios de Directivos	IPS	Interno	<p>Los cambios Autoridades y Directivos pueden afectar el seguimeintos de los procesos macro.&nbsp;</p>	91	92	38	2023-10-18 21:46:26	2023-10-18 21:46:26
8	category	Sociales	IPS	Externo	<p>Sociales</p>	22	43	1	2023-10-18 19:47:58	2023-10-18 19:47:58
62	aspect	Trazabilidad	IPS	Interno	<p>Trazabilidad</p>	117	118	38	2023-10-18 21:54:31	2023-10-18 21:54:31
64	aspect	Cruce de Datos interinstitucional	IPS	Interno	<p>Convenios para Cruce de Datos entre hacienda y los datos de AOP</p>	121	122	38	2023-10-18 21:55:19	2023-10-18 21:55:19
72	category	Talento Humano	IPS	Interno	<p>Talento Humano</p>	138	145	1	2023-10-18 22:16:06	2023-10-18 22:16:06
79	aspect	Cobertura para enfermedades catastróficas	IPS	Interno	<p>Cobertura para enfermedades catastróficas. Financiación del FONARESS</p>	159	160	77	2023-10-18 22:20:30	2023-10-19 13:42:53
89	aspect	Programas Sociales	IPS	Interno	<p>CREAM, ADULTO MAYOR, CIUDADANO DE ORO, RETIRO FELIZ</p>	167	168	86	2023-10-18 22:28:04	2023-10-18 22:28:23
90	aspect	Infraestructura Física Inclusiva	IPS	Interno	<p>Infraestructura Física Inclusiva</p>	169	170	86	2023-10-18 22:28:44	2023-10-18 22:28:44
87	aspect	Medicina Preventiva.	IPS	Interno	<p>Capacidad para la detección de enfermedades prevenibles.</p>	171	172	86	2023-10-18 22:26:19	2023-10-19 01:48:03
85	aspect	Stock de Medicamentos e Insumos	IPS	Interno	<p>Gestion de Stock de Medicamentos e Insumos en salud. Trazabilidad. Vademecum. Medicamentos de Alto Costo. Amparos judiciales. Obsolescencia o vencimiento.</p>	147	148	77	2023-10-18 22:24:46	2023-10-19 13:37:31
81	aspect	Estudios Actuariales.	IPS	Interno	<p>Estudios Actuariales de gastos en Salud y Jubilaciones. Proyecciones estimadas.&nbsp;</p>	155	156	77	2023-10-18 22:21:57	2023-10-19 13:41:39
26	aspect	Opinión Pública sobre las Inversiones de la Previsional	IPS	Externo	<p>Opinión Pública sobre las Inversiones de la Previsional</p>	45	46	9	2023-10-18 20:05:53	2023-10-18 20:05:53
74	aspect	Capacticación del Talento Humano	IPS	Interno	<p>Análisis del % de Capacticación del Talento Humano de último 2 años<br>Cantidad de Capacitaciones orientadas al cumplimento de la Misión.</p>	143	144	72	2023-10-18 22:17:45	2023-10-19 13:48:15
68	aspect	Hardware (Servidores)	IPS	Interno	<p>Potencia de hardware, red de alta velocidad, sistema operativo optimizado, configuración de seguridad sólida, balanceo de carga, monitorización constante, escalabilidad, etc</p><p><br>&nbsp;</p>	129	130	65	2023-10-18 22:07:06	2023-10-18 22:07:06
39	aspect	Imagen corporativa del área médica	IPS	Interno	<p>Señalética, Cartelerías y otros elementos para la comunicación corporativa.&nbsp;</p>	71	72	38	2023-10-18 21:31:27	2023-10-18 21:31:28
54	aspect	Tasas de Retorno de Inversiones	IPS	Interno	<p>Tasas de Retorno de Inversiones.&nbsp;</p>	101	102	38	2023-10-18 21:49:20	2023-10-18 21:49:20
41	aspect	Gestión de Recursos de Área Médica	IPS	Interno	<p>Provisión oportunda de Medicamentos e Insumos. Cuadro básico</p>	75	76	38	2023-10-18 21:37:17	2023-10-18 21:37:17
50	aspect	Riesgos Operativos	IPS	Interno	<p>Capacidad de análisi de Riesgos Corporativos (Formatos del MECIP sobre Riesgos)</p>	93	94	38	2023-10-18 21:47:09	2023-10-18 21:47:09
66	aspect	Software Área Médica	IPS	Interno	<p>Sistema Intgrado Hospitalario (SIH) . Sistema de Gestión de traslado de pacientes. Sistema de Inventario de Patrimonio.</p>	125	126	65	2023-10-18 22:00:25	2023-10-18 22:00:25
37	aspect	Inteligencia Artificial	IPS	Externo	<p>ChatGPT y otras tecnologias de Inteligencia Artificial</p>	67	68	34	2023-10-18 20:13:10	2023-10-18 20:13:10
52	aspect	Mecanismo de Fiscalización	IPS	Interno	<p>Mecanismo de Fiscalización en empresas. Evasión por fraude</p>	97	98	38	2023-10-18 21:48:25	2023-10-18 21:48:25
51	aspect	Marco Legal institucional	IPS	Interno	<p>El Marco Legal vigente</p>	95	96	38	2023-10-18 21:47:52	2023-10-18 21:47:52
28	category	Educación en Seguridad Social	IPS	Externo	<p>Grado de conocimiento de la población de los Derechos sobre la Seguridad Social</p>	50	55	1	2023-10-18 20:08:09	2023-10-18 20:08:09
67	aspect	Software Área Administrativa	IPS	Interno	<p>Más de 60 Sistemas segun catálogo. Licencias Office 365, Tableu, Herramientas de Inteligencia de Negocios. Linux, Servidores, licencias RedHat, otros…</p>	127	128	65	2023-10-18 22:02:53	2023-10-18 22:02:53
30	aspect	Difusiones sobre Seguridad Social	IPS	Externo	<p>Difusiones sobre Seguridad Social en Educación, en Empresas y en la ciudadanía en general</p>	53	54	28	2023-10-18 20:08:57	2023-10-18 20:08:57
1	root	Analisis FODA	IPS	\N	<p>Detalle</p>	1	176	\N	2023-10-18 15:17:39	2023-10-18 15:17:39
2	category	Políticos	IPS	Externo	<p>Políticos</p>	14	21	1	2023-10-18 15:18:07	2023-10-18 19:47:40
15	aspect	Amparos judiciales	IPS	Externo	<p>Cantidad de amparos judiciales de últimos 6 meses</p>	19	20	2	2023-10-18 19:54:52	2023-10-18 19:54:52
32	aspect	Percepción de la ciudadanía y los asegurados	IPS	Externo	<p>Percepción de la ciudadanía y los asegurados</p>	57	58	31	2023-10-18 20:10:59	2023-10-18 20:10:59
53	aspect	Gestión Jurídica	IPS	Interno	<p>Gestión Jurídica general y en particular para recuperación de inmuebles</p>	99	100	38	2023-10-18 21:48:55	2023-10-18 21:48:55
69	aspect	Hardware (PC)	IPS	Interno	<p>Disponibilidad de PC para gestionar las tareas diarias.</p>	131	132	65	2023-10-18 22:07:41	2023-10-18 22:07:41
56	aspect	Dispersión de los Trámites administrativos	IPS	Interno	<p>Dispersión de los Trámites administrativos</p>	105	106	38	2023-10-18 21:50:19	2023-10-18 21:50:19
43	aspect	Imagen Corporativa del área de prestaciones económicas.	IPS	Interno	<p>Imagen Corporativa del área de prestaciones económicas.</p>	79	80	38	2023-10-18 21:40:19	2023-10-18 21:40:19
70	aspect	Equipos Biomédicos	IPS	Interno	<p>Scaneres en general, Equipos de imagenes, Resonancia Magnética.&nbsp;</p>	133	134	65	2023-10-18 22:08:19	2023-10-18 22:14:31
29	aspect	Grado de conocimiento de la población de los Derechos sobre la Seguridad Social	IPS	Externo	<p>Grado de conocimiento de la población de los Derechos sobre la Seguridad Social</p>	51	52	28	2023-10-18 20:08:18	2023-10-18 20:08:18
45	aspect	Disponibilidad y Acceso a la Información	IPS	Interno	<p>Reclamos en Buzón de quejas de IPS TE ESCUCHA. Socialización de Resoluciones del Consejo. Transmisión Live</p>	83	84	38	2023-10-18 21:42:20	2023-10-18 21:42:20
5	category	Económicos	IPS	Externo	<p>Económicos</p>	2	13	1	2023-10-18 15:34:33	2023-10-18 19:47:35
93	aspect	Tipos de Cambios	IPS	Externo	<p>Suba del precio de divisas encareciendo compra de medicamentos e insumos</p>	9	10	5	2023-10-19 13:50:10	2023-10-19 13:50:10
20	aspect	Población Jóven con Riesgos	IPS	Externo	<p>Analizar si existe un tasa de crecimiento importante en la Población Jóven con riesgos en salud</p>	31	32	8	2023-10-18 19:58:54	2023-10-18 19:58:54
84	aspect	Presupuesto Salud	IPS	Interno	<p>Elaboración del presupuesto de salud y distribución del gasto. Financiamiento de Accidentes de Trabajo y Reposos, Maternidad.</p>	149	150	77	2023-10-18 22:24:20	2023-10-19 13:38:29
75	aspect	Política del Talento Humano	IPS	Interno	<p>Matriz Salarial, ambiente laboral, programas de recambio, Promoción y contratación del Talnto Humano, Capacitaciones en general</p>	141	142	72	2023-10-18 22:18:56	2023-10-18 22:18:56
36	aspect	Nuevas Tecnologías	IPS	Externo	<p>Nuevas Tecnologías</p>	65	66	34	2023-10-18 20:12:47	2023-10-18 20:12:47
34	category	Tecnológico (Externo)	IPS	Externo	<p>Tecnológico</p>	62	69	1	2023-10-18 20:12:18	2023-10-18 20:12:18
9	category	Inversiones	IPS	Externo	<p>Inversiones</p>	44	49	1	2023-10-18 19:48:22	2023-10-18 19:48:22
17	aspect	Accidente Viales	IPS	Externo	<p>Tasa de aumento de accidentes de motos y autos en general.&nbsp;</p>	25	26	8	2023-10-18 19:56:12	2023-10-18 19:56:12
3	aspect	Leyes Políticas (Nivel País)	IPS	Externo	<p>Leyes que afectan a la Institución</p>	17	18	2	2023-10-18 15:33:34	2023-10-18 19:54:08
21	aspect	Insatisfacción del asegurado	IPS	Externo	<p>Existe un aumento de las denuncias de los asegurados en medios masivos de comunicación sobre las prestaciones del seguro social del IPS?&nbsp;</p>	33	34	8	2023-10-18 19:59:53	2023-10-18 19:59:53
16	aspect	Huelgas	IPS	Externo	<p>Paros, Marchas y Huelgas realizadas por funcionarios de la institución&nbsp;</p>	23	24	8	2023-10-18 19:55:40	2023-10-18 19:55:40
86	category	Atención Asistencial	IPS	Interno	<p>Atención Asistencial</p>	164	175	1	2023-10-18 22:25:48	2023-10-18 22:25:48
24	aspect	Costo de Medicamentos e Insumos	IPS	Externo	<p>Costo de Medicamentos e Insumos. Tenemos un crecimiento exponencial en los precios? O los precios se mantienen</p>	39	40	8	2023-10-18 20:01:50	2023-10-18 20:02:01
40	aspect	Orden y Limpieza	IPS	Interno	<p>Estado general de los Baños públicos. Condición de los Servicios de Salud.&nbsp;</p>	73	74	38	2023-10-18 21:34:36	2023-10-18 21:34:54
91	aspect	Inflación	IPS	Externo	<p>Suba de Precios</p>	5	6	5	2023-10-19 13:49:10	2023-10-19 13:49:10
77	category	Financiero	IPS	Interno	<p>Financiero</p>	146	163	1	2023-10-18 22:19:58	2023-10-18 22:19:58
55	aspect	Portafolio de Inversiones	IPS	Interno	<p>Portafolio de Inversiones&nbsp;</p>	103	104	38	2023-10-18 21:49:49	2023-10-18 21:49:49
44	aspect	Reglamentos internos	IPS	Interno	<p>Reglamentos internos que permiten las actualizaciones del mercado</p>	81	82	38	2023-10-18 21:40:54	2023-10-18 21:40:54
65	category	Capacidad Tecnológica	IPS	Interno	<p>Capacidad Tecnológica Instalada</p>	124	137	1	2023-10-18 21:56:39	2023-10-18 21:56:39
71	aspect	Tercerización de Servicios Tecnológicos	IPS	Interno	<p>Tercerización de Servicios Tecnológicos</p>	135	136	65	2023-10-18 22:14:59	2023-10-18 22:14:59
42	aspect	Difusión y Capacitación	IPS	Interno	<p>Difusión y Capacitación en las empresas</p>	77	78	38	2023-10-18 21:39:49	2023-10-18 21:39:49
57	aspect	Trámites administrativos	IPS	Interno	<p>Trámites administrativos con cuello de botella, excesiva burocracia, fluidéz.</p>	107	108	38	2023-10-18 21:51:00	2023-10-18 21:51:00
73	aspect	Convenios	IPS	Interno	<p>Listado de convenios con Universidades e instituciones de capacitación (SINAFOCAL)</p>	139	140	72	2023-10-18 22:16:57	2023-10-18 22:16:57
46	aspect	Rendición de Cuentas	IPS	Interno	<p>Informes de Rendición de Cuentas</p>	85	86	38	2023-10-18 21:42:47	2023-10-18 21:42:47
59	aspect	Administración de Contratos	IPS	Interno	<p>Mejoras en la Administración de Contratos. Control interno.&nbsp;</p>	111	112	38	2023-10-18 21:52:56	2023-10-18 21:52:56
48	aspect	Estrategias de Prensa y Comunicación Institucional	IPS	Interno	<p>Como manejamos la Comunicación Corporativa. La institución cuenta con vocero oficial para canalizar los asuntos relevantes del IPS?</p>	89	90	38	2023-10-18 21:45:32	2023-10-18 21:45:32
61	aspect	Definición de las Demandas y costos	IPS	Interno	<p>Definición de las Demandas y costos</p>	115	116	38	2023-10-18 21:54:15	2023-10-18 21:54:15
63	aspect	Seguridad en los Sistemas de Información	IPS	Interno	<p>Seguridad en los Sistemas de Información&nbsp;</p>	119	120	38	2023-10-18 21:54:45	2023-10-18 21:54:45
38	category	Capacidad Directiva General	IPS	Interno	<p>Todo lo relacionado al área salud</p>	70	123	1	2023-10-18 21:29:56	2023-10-18 21:38:45
88	aspect	Guías y Protocolos Médicos	IPS	Interno	<p>Implementación de Guías y Protocolos Médicos. Inversión en Programas Preventivos en Salud y Riesgo Laboral.</p>	165	166	86	2023-10-18 22:26:57	2023-10-18 22:27:38
82	aspect	Inversión en Bienes y Servicios	IPS	Interno	<p>Realización de compras de bienes y servicios sin planificación adeacuada. Burocracia excesiva en compras públicas.</p>	153	154	77	2023-10-18 22:22:57	2023-10-19 13:40:53
83	aspect	Oferta y demanda de la cartera de servicios en salud	IPS	Interno	<p>Tratamientos de Alta Complejidad. Elevada prestaciones por monto reducido en aportes. Abuso en provisión de medicamentos (Pacientes que usan el seguro para retirar medicamentos.)</p>	151	152	77	2023-10-18 22:23:28	2023-10-19 13:40:13
206	aspect	Atención al Asegurado	Instituto de Previsión Social	Interno	<p>Se analiza la calidad y calidez en la atención brindada a los usuarios, asegurando un trato respetuoso y empático.</p>	369	370	205	2024-04-20 03:41:16	2024-04-20 03:41:16
117	aspect	Talento humano en el área médica	Instituto de Previsión Social	Interno	<p>La gestión médica define roles y responsabilidades claras para el personal médico y de atención de la salud, asegurando una distribución equitativa de tareas y recursos para satisfacer las necesidades de la población asignada. Esto implica la planificación de la fuerza laboral, el reclutamiento y la capacitación continua del personal médico y de enfermería.</p>	215	216	112	2024-04-16 16:57:38	2024-04-16 16:57:38
178	aspect	Infraestructura Física	Instituto de Previsión Social	Interno	<p>Se cuenta con una infraestructura física adecuada y bien equipada en todos los niveles de atención de la red para brindar servicios de salud de calidad.</p>	313	314	171	2024-04-16 17:48:27	2024-04-16 17:48:27
119	aspect	Sistema de Información y registro de servicios	Instituto de Previsión Social	Interno	<p>La gestión médica implementa sistemas de información y registros de servicios para registrar y monitorear la prestación de atención médica, incluidas las interconsultas, cirugías y otros procedimientos. Esto facilita la coordinación del cuidado, el seguimiento de pacientes y la generación de informes para la evaluación de la calidad y el desempeño del sistema de salud.</p>	219	220	112	2024-04-16 16:58:36	2024-04-16 16:58:36
213	aspect	Trato a los Asegurados	Instituto de Previsión Social	Interno	<p>Se asegura un trato adecuado y respetuoso hacia los asegurados, priorizando sus necesidades y brindando soluciones efectivas.</p>	383	384	205	2024-04-20 03:44:42	2024-04-20 03:44:42
99	aspect	Profesionales capacitados	Instituto de Previsión Social	Interno	<p>Calidad de atención del talento humano médico y administrativo ,motivación y compromiso</p>	179	180	98	2024-04-16 16:33:28	2024-04-16 16:33:28
140	aspect	Dotación de Tecnología y Hardware	Instituto de Previsión Social	Interno	<p>Considera la presencia y adecuación de equipos informáticos y de monitoreo para el apoyo administrativo y clínico.</p>	261	262	136	2024-04-16 17:21:01	2024-04-16 17:21:01
100	aspect	Motivación y compromiso	Instituto de Previsión Social	Interno	<p>La motivación y el compromiso del talento humano son pilares esenciales para el éxito organizacional. Al comprender las necesidades de los empleados, brindar un entorno de trabajo favorable y ofrecer oportunidades de desarrollo, las organizaciones pueden cultivar un ambiente laboral donde los empleados se sientan valorados, comprometidos y motivados para contribuir de manera significativa al logro de los objetivos corporativos. Este enfoque no solo impulsa la productividad y la calidad del trabajo, sino que también promueve la retención del talento y el crecimiento sostenible de la organización.</p>	181	182	98	2024-04-16 16:34:53	2024-04-16 16:34:53
122	aspect	Procesos administrativos	Instituto de Previsión Social	Interno	<p>La gestión de pacientes establece procesos administrativos eficientes para la admisión, registro, programación de citas, facturación y seguimiento de pacientes. Esto incluye la implementación de sistemas de gestión de registros médicos, protocolos de documentación y procedimientos estandarizados para garantizar la precisión y la integridad de la información del paciente.</p>	225	226	121	2024-04-16 17:03:31	2024-04-16 17:03:31
144	aspect	Mitigación de Riesgos ante Emergencias Sanitarias	Instituto de Previsión Social	Interno	<p>Considera la capacidad de respuesta del sistema de salud ante emergencias de salud pública, como pandemias o crisis sanitarias.</p>	269	270	136	2024-04-16 17:23:50	2024-04-16 17:23:50
148	aspect	Eficiencia Energética	Instituto de Previsión Social	Interno	<p>Se examina la optimización del consumo energético mediante la adopción de tecnologías y prácticas que reduzcan el uso de recursos no renovables y las emisiones de carbono. Esto incluye la implementación de sistemas de iluminación eficientes, la gestión de equipos médicos energéticamente eficientes y la utilización de fuentes de energía renovable.</p>	277	278	146	2024-04-16 17:29:02	2024-04-16 17:29:02
98	category	Talento Humano	Instituto de Previsión Social	Interno	<p><strong>Gestión, captación, capacitación y bienestar del talento humano</strong></p>	178	205	97	2024-04-16 16:32:07	2024-04-16 16:32:15
101	aspect	Investigación y desarrollo en el ambito médico	Instituto de Previsión Social	Interno	<p>La investigación y el desarrollo en el ámbito médico son fundamentales para avanzar en la comprensión y el tratamiento de enfermedades, mejorar la calidad de la atención médica y promover la salud pública. Al invertir en investigación médica, se pueden descubrir nuevas terapias, tecnologías médicas innovadoras y enfoques preventivos que beneficien a pacientes de todo el mundo. Además, la colaboración entre instituciones académicas, industria farmacéutica y entidades gubernamentales es clave para impulsar el progreso científico y traducir los hallazgos de investigación en soluciones prácticas que mejoren la salud y el bienestar de la sociedad.</p>	183	184	98	2024-04-16 16:35:49	2024-04-16 16:35:49
118	aspect	Mapas epidemiológicos	Instituto de Previsión Social	Interno	<p>La gestión médica utiliza datos epidemiológicos para identificar patrones de enfermedad, determinar factores de riesgo y priorizar intervenciones de salud pública. Esto implica la recopilación, análisis y visualización de datos de salud a nivel de población para informar la toma de decisiones y la asignación de recursos.</p>	217	218	112	2024-04-16 16:58:04	2024-04-16 16:58:04
102	aspect	Ambientes de trabajo armonizados	Instituto de Previsión Social	Interno	<p>Los ambientes de trabajo armonizados son aquellos donde se promueve un clima laboral positivo, basado en el respeto, la comunicación efectiva y la colaboración entre todos los miembros del equipo. Estos entornos fomentan el bienestar emocional y físico de los empleados, lo que se traduce en mayor satisfacción laboral, compromiso y productividad. Además, al crear un ambiente donde se valoren la diversidad, la inclusión y el equilibrio entre la vida personal y profesional, se fortalece el sentido de pertenencia y se impulsa la retención del talento humano. En definitiva, los ambientes de trabajo armonizados son clave para el éxito y el crecimiento sostenible de las organizaciones.</p>	185	186	98	2024-04-16 16:36:47	2024-04-16 16:36:47
145	aspect	Mantenimiento y Reposición de Equipamientos	Instituto de Previsión Social	Interno	<p>Analiza los procedimientos y programas para el mantenimiento y reemplazo de equipos médicos y tecnológicos para garantizar su funcionamiento óptimo.</p>	271	272	136	2024-04-16 17:24:13	2024-04-16 17:24:13
142	aspect	Cobertura de Software de Salud	Instituto de Previsión Social	Interno	<p>Analiza el alcance y la eficacia del sistema informático de salud en la gestión de datos y procesos médicos.</p>	265	266	136	2024-04-16 17:22:47	2024-04-16 17:22:47
103	aspect	Gestión eficiente de talento humano	Instituto de Previsión Social	Interno	<p>La gestión eficiente del talento humano implica un liderazgo inspirador y capacitado, capaz de guiar, motivar y empoderar a los colaboradores hacia el logro de objetivos organizacionales compartidos. Una comunicación interna clara y efectiva es fundamental para alinear expectativas, compartir información relevante y promover la participación activa de todos los miembros del equipo. Además, la gestión de calidad en los procesos de reclutamiento, desarrollo y retención del personal asegura que se cuente con los recursos humanos adecuados, con las habilidades y competencias necesarias, para impulsar la innovación, la excelencia y el éxito sostenible de la organización.</p>	187	188	98	2024-04-16 16:37:41	2024-04-16 16:37:41
120	aspect	Demanda de servicios y listas de espera	Instituto de Previsión Social	Interno	<p>La gestión médica monitorea la demanda de servicios de salud y gestiona las listas de espera para garantizar un acceso equitativo y oportuno a la atención médica. Esto implica la implementación de estrategias de gestión de la demanda, la optimización de la programación de citas y la identificación de cuellos de botella en la prestación de servicios.</p>	221	222	112	2024-04-16 16:58:48	2024-04-16 16:58:48
104	aspect	Implementación de políticas de talentos humanos	Instituto de Previsión Social	Interno	<p>La implementación de políticas de talento humano requiere un enfoque integral que abarque desde la atracción y retención de talento hasta el desarrollo y la gestión del desempeño. Estas políticas deben estar alineadas con la estrategia organizacional y diseñadas para promover un ambiente de trabajo inclusivo, motivador y centrado en el crecimiento profesional de los colaboradores. Es crucial establecer procesos transparentes y equitativos para la selección, evaluación y compensación del personal, así como fomentar una cultura de feedback continuo y oportunidades de desarrollo que impulsen el compromiso, la satisfacción laboral y el éxito a largo plazo tanto para la organización como para sus empleados.</p>	189	190	98	2024-04-16 16:38:13	2024-04-16 16:38:13
105	aspect	Programa de recambio	Instituto de Previsión Social	Interno	<p>Un programa de recambio eficaz es fundamental para asegurar la continuidad y la renovación del talento humano dentro de una organización. Este programa debe estar diseñado para identificar, desarrollar y retener a empleados con potencial para ocupar roles clave en el futuro. Para ello, es importante implementar estrategias de reclutamiento interno y externo, así como brindar oportunidades de capacitación y desarrollo profesional que preparen a los empleados para asumir nuevas responsabilidades. Además, es crucial establecer un proceso de mentoría y seguimiento para garantizar una transición exitosa entre los roles y promover la motivación y el compromiso del personal en todo momento.</p>	191	192	98	2024-04-16 16:38:45	2024-04-16 16:38:45
106	aspect	Salud Mental	Instituto de Previsión Social	Interno	<p>La salud mental en el ámbito médico es fundamental para el bienestar integral de los profesionales de la salud y, por extensión, para la calidad de la atención médica que brindan. Los desafíos inherentes a la profesión, como el estrés laboral, la carga emocional y la presión por el rendimiento, pueden impactar significativamente en la salud mental de los profesionales médicos. Por lo tanto, es crucial implementar programas y políticas que promuevan la salud mental dentro de los equipos médicos, incluyendo medidas preventivas, acceso a servicios de apoyo psicológico, fomento del autocuidado y creación de entornos laborales que favorezcan el bienestar emocional. Además, se debe trabajar en la eliminación del estigma asociado a los problemas de salud mental en el ámbito médico, promoviendo una cultura de apertura, comprensión y apoyo mutuo entre los profesionales de la salud. Esto no solo beneficiará la salud y el desempeño de los profesionales médicos, sino que también mejorará la calidad de la atención que brindan a los pacientes.</p>	193	194	98	2024-04-16 16:39:47	2024-04-16 16:39:47
107	aspect	Sistema de Promoción	Instituto de Previsión Social	Interno	<p>Un sistema de promoción efectivo es crucial para impulsar el crecimiento profesional y el compromiso de los empleados dentro de una organización. Debe ser transparente, basado en méritos y alineado con los objetivos estratégicos de la empresa. Esto implica establecer criterios claros de evaluación, proporcionar retroalimentación constructiva y ofrecer oportunidades de desarrollo personalizado. Además, es importante reconocer y recompensar el desempeño excepcional, fomentar la capacitación continua y brindar un camino claro para la progresión profesional. Un sistema de promoción bien diseñado no solo motiva a los empleados a alcanzar su máximo potencial, sino que también contribuye al éxito general de la organización al retener el talento clave y promover una cultura de excelencia y crecimiento.</p>	195	196	98	2024-04-16 16:40:21	2024-04-16 16:40:21
108	aspect	Convenio con Universidades.	Instituto de Previsión Social	Interno	<p>Establecer convenios con universidades para la capacitación ofrece una serie de beneficios tanto para las instituciones educativas como para las empresas. Por un lado, las universidades pueden acceder a casos de estudio reales, ampliar su oferta educativa y adaptarla a las necesidades del mercado laboral. Por otro lado, las empresas obtienen acceso a programas de formación especializados y actualizados, así como a talento joven y motivado que puede aportar nuevas ideas y perspectivas. Estos convenios también pueden facilitar la colaboración en proyectos de investigación y desarrollo, promoviendo la innovación y el avance en diversas áreas de interés mutuo.&nbsp;</p>	197	198	98	2024-04-16 16:41:16	2024-04-16 16:41:16
137	aspect	Disponibilidad de Centros de Atención Médica	Instituto de Previsión Social	Interno	<p>Evalúa la presencia y accesibilidad de instalaciones médicas para la atención de la población, considerando áreas geográficas y poblaciones atendidas.</p>	255	256	136	2024-04-16 17:19:10	2024-04-16 17:19:10
109	aspect	Detección de necesidades de talentos humanos	Instituto de Previsión Social	Interno	<p>La detección de necesidades de talento humano es un proceso fundamental para asegurar que una organización cuente con el personal adecuado y las habilidades necesarias para alcanzar sus objetivos estratégicos. Implica identificar las brechas entre las competencias actuales del personal y las requeridas para cumplir con las metas de la organización. Este proceso puede involucrar la realización de análisis de puestos, evaluaciones de desempeño, encuestas de satisfacción laboral, retroalimentación de los empleados y líderes, así como el seguimiento de tendencias en el mercado laboral y la industria. Al comprender las necesidades y expectativas del talento humano, las organizaciones pueden diseñar e implementar estrategias efectivas de reclutamiento, selección, capacitación y desarrollo que impulsen el crecimiento y el éxito a largo plazo.</p>	199	200	98	2024-04-16 16:41:58	2024-04-16 16:41:58
112	category	Gestión Médica	Instituto de Previsión Social	Interno	<p>La gestión médica garantiza que los servicios sanitarios ofrecidos sean completos y suficientes para cubrir las necesidades de la población asignada. Esto se logra mediante la planificación cuidadosa de recursos humanos, equipos médicos y suministros para asegurar la disponibilidad de atención médica oportuna y de calidad.</p>	206	223	97	2024-04-16 16:54:29	2024-04-16 16:54:29
110	aspect	Disponibilidad de talentos capacitados en el mercado	Instituto de Previsión Social	Interno	<p>La disponibilidad de talentos capacitados en el mercado es un factor crítico para el éxito de las organizaciones, ya que afecta directamente su capacidad para cumplir con sus objetivos y competir en el entorno empresarial. Para evaluar esta disponibilidad, es fundamental realizar un análisis del mercado laboral que incluya la identificación de las competencias y habilidades requeridas, así como la evaluación de la oferta de talento humano en términos de cantidad y calidad. Este análisis puede implicar la revisión de datos demográficos, tasas de empleo, niveles de educación y capacitación, así como la evaluación de tendencias del mercado laboral y proyecciones futuras. Con esta información, las organizaciones pueden diseñar estrategias efectivas de reclutamiento, retención y desarrollo de talento humano que les permitan asegurar el acceso a los profesionales capacitados que necesitan para alcanzar sus metas y objetivos.</p>	201	202	98	2024-04-16 16:42:38	2024-04-16 16:42:38
141	aspect	Software en general y Sistemas Informáticos en Salud	Instituto de Previsión Social	Interno	<p>Software Médico y Sistemas Informáticos. Evalúa la implementación y eficacia de sistemas como historia clínica electrónica, gestión de camas y otros programas de gestión médica.</p>	263	264	136	2024-04-16 17:22:22	2024-04-16 17:22:22
111	aspect	Plan de Capacitación Continua	Instituto de Previsión Social	Interno	<p>Un plan de capacitación continua es esencial para mantener actualizado y desarrollar el talento humano dentro de una organización. Este plan debe incluir la identificación de las necesidades de capacitación a través de evaluaciones de desempeño, retroalimentación de los empleados y análisis de brechas de habilidades. Además, se deben diseñar programas de capacitación adaptados a las necesidades específicas de cada área y nivel de la organización, utilizando una variedad de métodos de enseñanza, como cursos presenciales, seminarios web, tutoriales en línea y programas de mentoría. Es importante también contar con espacios físicos adecuados para la capacitación, que fomenten el aprendizaje colaborativo y la interacción entre los empleados. Estos espacios pueden incluir salas de conferencias equipadas con tecnología audiovisual, aulas de capacitación con acceso a recursos educativos y áreas comunes que promuevan el intercambio de conocimientos y experiencias. Un enfoque integral de capacitación continua contribuye al desarrollo profesional y personal de los empleados, mejorando su desempeño y aumentando su compromiso con la organización.</p>	203	204	98	2024-04-16 16:43:58	2024-04-16 16:43:58
113	aspect	Oferta de servicios	Instituto de Previsión Social	Interno	<p>La gestión médica garantiza que los servicios sanitarios ofrecidos sean completos y suficientes para cubrir las necesidades de la población asignada. Esto se logra mediante la planificación cuidadosa de recursos humanos, equipos médicos y suministros para asegurar la disponibilidad de atención médica oportuna y de calidad.</p>	207	208	112	2024-04-16 16:55:11	2024-04-16 16:55:11
114	aspect	Alianzas estratégicas y colaboraciones	Instituto de Previsión Social	Interno	<p>La gestión médica establece alianzas estratégicas y colaboraciones con otras instituciones médicas y proveedores de salud para mejorar la accesibilidad, eficiencia y calidad de los servicios. Estas colaboraciones pueden incluir acuerdos de referencia y contrarreferencia, intercambio de recursos y conocimientos, y programas de formación conjunta.</p>	209	210	112	2024-04-16 16:55:32	2024-04-16 16:55:32
115	aspect	Investigación y Desarrollo en el ámbito médico	Instituto de Previsión Social	Interno	<p>La gestión médica fomenta la investigación y el desarrollo en el ámbito médico para mejorar las terapias existentes, desarrollar nuevas opciones de tratamiento y estudiar la efectividad de las intervenciones médicas. Esto puede implicar la participación en ensayos clínicos, la colaboración con instituciones académicas y la adopción de tecnologías innovadoras.</p>	211	212	112	2024-04-16 16:56:32	2024-04-16 16:56:32
116	aspect	Integración tecnológica emergente	Instituto de Previsión Social	Interno	<p>La gestión médica adopta tecnologías emergentes, como la inteligencia artificial, el análisis de datos y la telemedicina, para mejorar la eficiencia operativa, la precisión diagnóstica y la atención al paciente. Esto incluye la implementación de sistemas de registro electrónico de salud, herramientas de análisis predictivo y plataformas de teleconsulta.</p>	213	214	112	2024-04-16 16:57:02	2024-04-16 16:57:02
123	aspect	Protocolos médicos implementados	Instituto de Previsión Social	Interno	<p>La gestión de pacientes implementa protocolos médicos estandarizados para el diagnóstico, tratamiento y seguimiento de enfermedades y condiciones médicas específicas. Esto asegura la consistencia en la atención médica, la adherencia a las mejores prácticas clínicas y la mejora de los resultados del paciente.</p>	227	228	121	2024-04-16 17:03:56	2024-04-16 17:03:56
124	aspect	Sistema Informático	Instituto de Previsión Social	Interno	<p>La gestión de pacientes utiliza sistemas informáticos integrados para gestionar la información del paciente, incluidos los registros médicos electrónicos, la programación de citas, la facturación y la comunicación entre los diferentes departamentos y profesionales de la salud. Esto mejora la coordinación del cuidado, la eficiencia operativa y la calidad de la atención.</p>	229	230	121	2024-04-16 17:04:15	2024-04-16 17:04:15
138	aspect	Accesibilidad y Cobertura	Instituto de Previsión Social	Interno	<p>Analiza la extensión de la cobertura de los servicios médicos, incluyendo la asignación de población y la atención a áreas geográficas específicas.</p>	257	258	136	2024-04-16 17:19:27	2024-04-16 17:19:27
207	aspect	Redes Sociales	Instituto de Previsión Social	Interno	<p>Se supervisa el manejo adecuado de las redes sociales, utilizando estas plataformas como herramientas de comunicación y promoción de servicios, manteniendo una imagen institucional positiva. Se mantiene una imagen corporativa basada en los standres oficiales</p>	371	372	205	2024-04-20 03:42:37	2024-04-20 03:42:37
125	aspect	Talentos Humanos capacitados	Instituto de Previsión Social	Interno	<p>La gestión de pacientes invierte en la capacitación y el desarrollo del personal médico y de enfermería para garantizar competencias adecuadas en la atención al paciente, el uso de tecnología médica y la comunicación efectiva. Esto incluye programas de formación continua, certificaciones profesionales y oportunidades de aprendizaje en el lugar de trabajo.</p>	231	232	121	2024-04-16 17:04:34	2024-04-16 17:04:34
126	aspect	Equipos Informáticos	Instituto de Previsión Social	Interno	<p>La gestión de pacientes proporciona equipos informáticos adecuados, como computadoras, tabletas y dispositivos móviles, para facilitar el acceso a los sistemas de información del paciente y apoyar las tareas clínicas y administrativas. Esto incluye la actualización regular de hardware y software para garantizar un rendimiento óptimo y la seguridad de los datos.</p>	233	234	121	2024-04-16 17:04:56	2024-04-16 17:04:56
127	aspect	Protocolos de traslado intrahospitalario	Instituto de Previsión Social	Interno	<p>La gestión de pacientes establece protocolos para el traslado seguro y eficiente de pacientes dentro del hospital, incluidas las transferencias entre departamentos, unidades de atención y salas de procedimientos. Esto garantiza una coordinación efectiva del cuidado, la seguridad del paciente y la optimización de los recursos hospitalarios.</p>	235	236	121	2024-04-16 17:05:18	2024-04-16 17:05:18
121	category	Gestión de Pacientes	Instituto de Previsión Social	Interno	<p>La gestión de pacientes implica establecer procesos administrativos eficientes, implementar protocolos médicos estandarizados, utilizar sistemas informáticos integrados, capacitar al personal médico, proporcionar equipos informáticos adecuados, establecer protocolos de traslado intrahospitalario y seguir de cerca las tendencias tecnológicas emergentes en el ámbito de la salud. Esto asegura la coordinación del cuidado, la eficiencia operativa y la mejora de la calidad de la atención médica para los pacientes.</p>	224	239	97	2024-04-16 17:02:45	2024-04-16 17:02:45
128	aspect	Tendencias tecnológicas	Instituto de Previsión Social	Interno	<p>La gestión de pacientes sigue de cerca las tendencias tecnológicas emergentes en el ámbito de la salud, como la telemedicina, la inteligencia artificial aplicada a la salud, la medicina de precisión y los dispositivos médicos avanzados. Esto permite la adopción oportuna de nuevas tecnologías para mejorar la calidad de la atención, la eficiencia operativa y la experiencia del paciente.</p>	237	238	121	2024-04-16 17:05:37	2024-04-16 17:05:37
136	category	Gestión de Recursos (Infraestructura y Tecnología)	Instituto de Previsión Social	Interno	<p>Se enfoca en asegurar la disponibilidad y funcionalidad de infraestructura y tecnología médica necesaria para brindar atención de calidad. Esto incluye la evaluación de centros de atención médica, cobertura geográfica, equipos biomédicos y tecnología de diagnóstico, así como la implementación y mantenimiento de sistemas informáticos de salud. Además, se considera la preparación para crisis de salud pública y la eficiencia en el mantenimiento y reposición de equipamientos para garantizar la continuidad de los servicios de salud.</p>	254	273	97	2024-04-16 17:18:20	2024-04-16 17:18:20
130	aspect	Diponibilidad de Recursos	Instituto de Previsión Social	Interno	<p>Enfrentar la restricción financiera, de personal y equipos, requiere una cuidadosa planificación y asignación de recursos para maximizar su eficiencia y efectividad.</p>	241	242	129	2024-04-16 17:12:48	2024-04-16 17:12:48
149	aspect	Políticas de Compra Sostenible	Instituto de Previsión Social	Interno	<p>3. **Políticas de Compra Sostenible:** Se analiza la reducción de residuos y la adquisición de productos y equipos médicos ecológicos. Esto implica el establecimiento de criterios de compra que favorezcan productos con menor impacto ambiental, así como la promoción de prácticas de reciclaje y reutilización.</p>	279	280	146	2024-04-16 17:29:20	2024-04-16 17:29:21
146	category	Gestión Ambiental y Sostenibilidad	Instituto de Previsión Social	Interno	<p>El análisis de Gestión Ambiental y Sostenibilidad se centra en varios aspectos cruciales para asegurar que las operaciones médicas sean responsables y respetuosas con el medio ambiente.</p>	274	283	97	2024-04-16 17:27:53	2024-04-16 17:27:53
129	category	Gestión Administrativa	Instituto de Previsión Social	Interno	<p>La gestión administrativa en el ámbito de la salud se enfoca en manejar eficientemente los recursos limitados, optimizando la asignación financiera, de personal y equipos. Esto implica establecer procesos administrativos eficientes, ordenar el gasto en salud priorizando enfermedades de alto costo, y mantener la disponibilidad financiera frente a recesiones económicas. La trazabilidad garantiza una gestión transparente, mientras que la seguridad humana, incluyendo la del paciente y del personal médico, se asegura mediante protocolos y medidas de emergencia.</p>	240	253	97	2024-04-16 17:11:28	2024-04-16 17:11:28
131	aspect	Eficiencia de Procesos	Instituto de Previsión Social	Interno	<p>Implementar procedimientos administrativos eficientes garantiza una gestión fluida y sin contratiempos en todas las áreas de la institución médica.</p>	243	244	129	2024-04-16 17:13:08	2024-04-16 17:13:08
139	aspect	Equipos Biomédicos y Tecnología de Diagnóstico	Instituto de Previsión Social	Interno	<p>Examina la disponibilidad y funcionalidad de equipos como rayos X, ecografías y resonancias magnéticas, esenciales para el diagnóstico y tratamiento médico.</p>	259	260	136	2024-04-16 17:19:52	2024-04-16 17:19:52
208	aspect	Imagen del Talento Humano en función a los Asegurados	Instituto de Previsión Social	Interno	<p>Se presta atención al manejo de la imagen personal de los funcionarios del IPS, asegurando una presentación profesional y acorde con los estándares de la institución.</p>	373	374	205	2024-04-20 03:42:50	2024-04-20 03:42:50
143	aspect	Conectividad e Interoperabilidad	Instituto de Previsión Social	Interno	<p>Evalúa la conectividad de los sistemas informáticos de salud y su capacidad para interoperar con otros sistemas médicos y administrativos</p>	267	268	136	2024-04-16 17:23:14	2024-04-16 17:23:14
214	aspect	Vínculo con la Población Usuaria	Instituto de Previsión Social	Interno	<p>Se busca fortalecer el vínculo con la población usuaria, promoviendo la participación activa y la retroalimentación constante para mejorar continuamente los servicios.</p>	385	386	205	2024-04-20 03:44:53	2024-04-20 03:44:53
147	aspect	Gestión de Residuos	Instituto de Previsión Social	Interno	<p>Se evalúa el manejo adecuado de la basura patológica, los medicamentos vencidos y los materiales reciclables para minimizar el impacto ambiental y proteger la salud pública. Se busca implementar sistemas de separación y disposición segura de estos residuos.</p>	275	276	146	2024-04-16 17:28:31	2024-04-16 17:28:31
97	root	Análisis FODA - RIISS	Instituto de Previsión Social	\N	<p>En el marco de la Implementación de la RIISS, la Dirección de Planificación realiza el análisis FODA por Establecimientos</p>	177	392	\N	2024-04-16 16:29:10	2024-04-16 16:29:10
150	aspect	Sistemas de Desagües	Instituto de Previsión Social	Interno	<p>Se evalúa la gestión adecuada de aguas residuales y pluviales para prevenir la contaminación del agua y proteger los ecosistemas locales. Esto incluye la implementación de sistemas de drenaje eficientes y la adopción de medidas para evitar vertidos contaminantes.</p>	281	282	146	2024-04-16 17:29:35	2024-04-16 17:29:35
133	aspect	Recesiones económicas	Instituto de Previsión Social	Interno	<p>&nbsp;Ante situaciones de recesión económica, es crucial mantener la disponibilidad financiera para la red de salud, identificando áreas de optimización y reducción de costos sin comprometer la calidad de la atención.</p>	245	246	129	2024-04-16 17:14:19	2024-04-16 17:14:19
134	aspect	Trazabilidad	Instituto de Previsión Social	Interno	<p>Establecer sistemas de trazabilidad garantiza la transparencia y el seguimiento adecuado de los recursos, desde su asignación hasta su utilización, permitiendo una gestión más efectiva y responsable.</p>	247	248	129	2024-04-16 17:14:33	2024-04-16 17:14:33
135	aspect	Seguridad Humana	Instituto de Previsión Social	Interno	<p>Priorizar la seguridad del paciente y del personal médico es primordial, implementando medidas de seguridad adecuadas, protocolos de emergencia y sistemas de custodia para proteger tanto a los pacientes como al personal médico en todo momento.</p>	249	250	129	2024-04-16 17:14:43	2024-04-16 17:14:43
132	aspect	Ordenamiento de gastos en salud	Instituto de Previsión Social	Interno	<p>Adoptar prácticas de economía circular y priorizar el gasto en enfermedades de alto costo asegura una distribución equitativa y efectiva de los recursos financieros disponibles.</p>	251	252	129	2024-04-16 17:13:26	2024-04-16 17:14:52
172	aspect	Conectividad	Instituto de Previsión Social	Interno	<p>Se garantiza una adecuada conectividad entre los diferentes centros de salud y hospitales que conforman la red, facilitando la comunicación y el intercambio de información entre ellos.</p>	301	302	171	2024-04-16 17:46:29	2024-04-16 17:46:29
209	aspect	Orden y Aseo	Instituto de Previsión Social	Interno	<p>Se valora el orden y aseo en las instalaciones, contribuyendo a una experiencia positiva para los usuarios.</p>	375	376	205	2024-04-20 03:43:04	2024-04-20 03:43:04
180	aspect	Logísitca	Instituto de Previsión Social	Interno	<p>Se establecen sistemas logísticos eficientes para garantizar la distribución oportuna de recursos y la prestación de servicios de salud de manera efectiva.</p>	317	318	171	2024-04-16 17:48:55	2024-04-16 17:48:55
215	aspect	Comunicación Interna y Externa	Instituto de Previsión Social	Interno	<p>Se verifica la efectividad de la comunicación tanto hacia el público en general como dentro de la institución, garantizando la transmisión clara y oportuna de información relevante. Voceros Oficiales acreditados por la Institución para dar respuestas de interés público</p>	387	388	205	2024-04-20 03:45:06	2024-04-20 03:45:06
210	aspect	Uso oportuno de Tecnología para respuestas efectivas	Instituto de Previsión Social	Interno	<p>Se fomenta el uso de la tecnología para mejorar la eficiencia y calidad de los servicios ofrecidos, adaptándose a las necesidades cambiantes de la población usuaria.</p>	377	378	205	2024-04-20 03:43:22	2024-04-20 03:43:22
216	aspect	Identidad Institucional y Reputación	Instituto de Previsión Social	Interno	<figure class="table"><table><tbody><tr><td>A</td><td>A</td><td>A</td><td>A</td><td>A</td><td>A</td><td>A</td><td>A</td><td>A</td></tr><tr><td>11</td><td>11</td><td>1</td><td>1</td><td>1</td><td>1</td><td>&nbsp;</td><td>1</td><td>1</td></tr><tr><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td><td>1</td></tr></tbody></table></figure><p>Se cuida la reputación y la identidad institucional, generando confianza y credibilidad entre los pacientes y la comunidad.</p>	389	390	205	2024-04-20 03:45:16	2024-04-22 14:46:23
199	category	Gestión Económica	Instituto de Previsión Social	Externo	<p>La Gestión Económica se enfoca en la administración eficiente de los recursos financieros en el ámbito de la salud.</p>	356	361	97	2024-04-16 21:34:42	2024-04-16 21:34:42
197	aspect	Avances tecnológicos y oportunidades de innovación en salud	Instituto de Previsión Social	Externo	<p>Este aspecto se refiere a la capacidad de aprovechar los avances tecnológicos, como la inteligencia artificial, el análisis de datos y la telemedicina, para mejorar la eficiencia y la calidad de la atención médica.</p>	351	352	196	2024-04-16 21:32:36	2024-04-16 21:32:36
201	aspect	Posibilidad de expansión geográfica y acceso a financiamiento externo	Instituto de Previsión Social	Externo	<p>Se refiere a la capacidad de la institución para expandir sus servicios a nuevas áreas geográficas, lo que puede generar nuevas fuentes de ingresos. Además, el acceso a financiamiento externo, como préstamos o fondos de inversión, puede proporcionar los recursos necesarios para proyectos de expansión y mejoras en la infraestructura y equipamiento médico.</p>	359	360	199	2024-04-16 21:36:41	2024-04-16 21:36:41
198	aspect	Convenios interinstitucionales para (Datos Abiertos)	Instituto de Previsión Social	Externo	<p>Implica establecer acuerdos con otras instituciones para compartir datos de salud de manera abierta y colaborativa, lo que puede fomentar la investigación, mejorar la toma de decisiones y promover la transparencia en el sector salud.</p>	353	354	196	2024-04-16 21:33:02	2024-04-16 21:33:02
203	aspect	Identificación y mitigación de riesgos en la prestación se servicios de salud	Instituto de Previsión Social	Externo	<p>Esta área implica la identificación proactiva de posibles riesgos en los procesos de atención médica, desde errores en la administración de medicamentos hasta fallas en equipos médicos, con el fin de implementar medidas preventivas y correctivas para minimizar dichos riesgos.</p>	363	364	202	2024-04-16 21:40:27	2024-04-16 21:40:27
200	aspect	Cambios demográficos favorables y concientización pública sobre la salud	Instituto de Previsión Social	Externo	<p>Esta área se refiere a la existencia de cambios demográficos que pueden impactar positivamente en la demanda de servicios de salud, así como a una mayor conciencia pública sobre la importancia de la salud, lo que puede traducirse en un aumento de la demanda de servicios y una mejor aceptación de políticas de salud.</p>	357	358	199	2024-04-16 21:35:42	2024-04-16 21:35:42
204	aspect	Implementación de protocolos y medidas de seguridad	Instituto de Previsión Social	Externo	<p>Consiste en establecer protocolos y procedimientos estandarizados para garantizar la seguridad de los pacientes y del personal médico. Esto incluye medidas como la capacitación del personal en prácticas seguras, el mantenimiento regular de equipos médicos, la gestión adecuada de medicamentos y la implementación de medidas de higiene y control de infecciones.</p>	365	366	202	2024-04-16 21:41:12	2024-04-16 21:41:12
202	category	Gestión del Riesgo	Instituto de Previsión Social	Externo	<p>La Gestión del Riesgo se centra en identificar y mitigar los riesgos asociados con la prestación de servicios de salud, garantizando la seguridad de los pacientes y del personal médico.</p>	362	367	97	2024-04-16 21:39:33	2024-04-16 21:41:39
165	aspect	Cultura de los Asegurados	Instituto de Previsión Social	Interno	<p>Se promueve una cultura de prevención entre los asegurados, incentivando hábitos saludables y el autocuidado.</p>	287	288	163	2024-04-16 17:41:19	2024-04-16 17:41:19
163	category	Gestión de la Prevención	Instituto de Previsión Social	Interno	<p>La prevención juega un papel crucial en el mantenimiento de la salud y el bienestar de la población asegurada del IPS.&nbsp;</p>	284	299	97	2024-04-16 17:40:19	2024-04-16 17:40:19
176	aspect	Equipos Biomédicos Disponibles	Instituto de Previsión Social	Interno	<p>Se asegura la disponibilidad y el mantenimiento adecuado de equipos biomédicos en todos los centros de salud y hospitales de la red.</p>	309	310	171	2024-04-16 17:47:51	2024-04-16 17:47:51
179	aspect	Legislación	Instituto de Previsión Social	Interno	<p>Se cumple con la legislación vigente y se aplican políticas gubernamentales que promueven y protegen el derecho a la salud de los asegurados.</p>	315	316	171	2024-04-16 17:48:43	2024-04-16 17:48:43
164	aspect	Capacitación en la Detección Precoz	Instituto de Previsión Social	Interno	<p>Se evalúa la capacidad del IPS para detectar de manera temprana posibles problemas de salud, permitiendo intervenciones preventivas eficaces.</p>	285	286	163	2024-04-16 17:41:01	2024-04-16 17:41:01
181	aspect	Niveles de Complejidad Estandarizados	Instituto de Previsión Social	Interno	<p>Se define y estandariza los niveles de atención en la red de salud, asegurando una atención jerarquizada y adecuada a las necesidades de los pacientes.</p>	319	320	171	2024-04-16 17:49:14	2024-04-16 17:49:14
166	aspect	Educación para una Vida Sana	Instituto de Previsión Social	Interno	<p>Se implementan programas educativos destinados a fomentar estilos de vida saludables, incluyendo la promoción de una alimentación balanceada, la actividad física regular y el manejo del estrés.</p>	289	290	163	2024-04-16 17:42:16	2024-04-16 17:42:16
167	aspect	Programas de Prevención en Salud	Instituto de Previsión Social	Interno	<p>Se diseñan y ejecutan programas específicos de prevención en salud, dirigidos a la población asegurada y adaptados a sus necesidades y riesgos particulares.</p>	291	292	163	2024-04-16 17:42:33	2024-04-16 17:42:33
168	aspect	Protocolos Médicos e Implementación	Instituto de Previsión Social	Interno	<p>Se establecen protocolos médicos actualizados y se asegura su correcta implementación para garantizar la efectividad de las medidas preventivas.</p>	293	294	163	2024-04-16 17:42:54	2024-04-16 17:42:54
169	aspect	Talentos Humanos de Salud Capactitados	Instituto de Previsión Social	Interno	<p>Se cuenta con un equipo de profesionales de la salud capacitados en prevención, quienes están preparados para brindar orientación y atención preventiva de calidad.</p>	295	296	163	2024-04-16 17:43:15	2024-04-16 17:43:15
170	aspect	Tratamiento Oportuno	Instituto de Previsión Social	Interno	<p>Además de la prevención, se garantiza el acceso oportuno a servicios de diagnóstico y tratamiento para aquellos asegurados que requieran atención médica, con el objetivo de evitar complicaciones y mejorar los resultados de salud.</p>	297	298	163	2024-04-16 17:43:28	2024-04-16 17:43:28
173	aspect	Convenios Existentes	Instituto de Previsión Social	Interno	<p>Se establecen convenios con otras instituciones médicas y proveedores de servicios de salud para ampliar la cobertura y mejorar el acceso a diferentes tipos de atención médica.</p>	303	304	171	2024-04-16 17:46:47	2024-04-16 17:46:47
174	aspect	Datos Demográficos de Asegurados	Instituto de Previsión Social	Interno	<p>Se recopilan y analizan datos demográficos de los asegurados para comprender mejor sus necesidades de salud y adaptar los servicios ofrecidos a sus características específicas.</p>	305	306	171	2024-04-16 17:47:10	2024-04-16 17:47:10
175	aspect	Datos Estadísticos - Demanda Real	Instituto de Previsión Social	Interno	<p>Se utilizan datos estadísticos para evaluar la demanda real de servicios de salud y planificar de manera efectiva la distribución de recursos en la red.</p>	307	308	171	2024-04-16 17:47:30	2024-04-16 17:47:30
182	aspect	Protocolos de Referencia y Contrarreferencia	Instituto de Previsión Social	Interno	<p>Se implementan protocolos claros y efectivos de referencia y contrarreferencia entre los diferentes niveles de atención de la red, garantizando una atención coordinada y continua.</p>	321	322	171	2024-04-16 17:49:41	2024-04-16 17:49:41
177	aspect	Influencia Política	Instituto de Previsión Social	Interno	<p>Se considera la influencia política en la gestión y el funcionamiento de la red de salud, asegurando que las decisiones políticas beneficien y fortalezcan el sistema de salud.</p>	311	312	171	2024-04-16 17:48:05	2024-04-16 17:48:05
183	aspect	Servicios Tercerizados	Instituto de Previsión Social	Interno	<p>Se gestionan servicios tercerizados cuando sea necesario para complementar la oferta de atención médica de la red.</p>	323	324	171	2024-04-16 17:49:54	2024-04-16 17:49:54
184	aspect	Sistemas de Traslados de Pacientes	Instituto de Previsión Social	Interno	<p>Se cuenta con un sistema de traslado de pacientes bien organizado, que garantiza el acceso oportuno a servicios de salud especializados cuando sea necesario.</p>	325	326	171	2024-04-16 17:50:09	2024-04-16 17:50:09
211	aspect	Conducta de los Colaboradores	Instituto de Previsión Social	Interno	<p>Se observa la conducta de los colaboradores durante la prestación del servicio, buscando mantener altos estándares de profesionalismo y ética.</p>	379	380	205	2024-04-20 03:44:21	2024-04-20 03:44:21
205	category	Gestión Imagen corporativa	Instituto de Previsión Social	Interno	<p>La imagen institucional es fundamental para la percepción pública de una organización de salud como el IPS. Se evalúa a través de varios aspectos:</p>	368	391	97	2024-04-20 03:37:33	2024-04-20 03:40:30
185	aspect	Sistema Informático	Instituto de Previsión Social	Interno	<p>Se utiliza un sistema informático integrado y actualizado para gestionar la información de los pacientes y mejorar la coordinación entre los diferentes actores de la red.</p><p>&nbsp;</p>	327	328	171	2024-04-16 17:50:23	2024-04-16 17:50:23
186	aspect	Talentos Humanos de Salud Disponibles	Instituto de Previsión Social	Interno	<p>Se asegura la disponibilidad de talentos humanos capacitados y motivados en todos los centros de salud y hospitales de la red.</p>	329	330	171	2024-04-16 17:50:42	2024-04-16 17:50:42
187	aspect	Telemedicina	Instituto de Previsión Social	Interno	<p>Se implementa la telemedicina como una herramienta para ampliar el acceso a la atención médica y mejorar la cobertura de servicios de salud, especialmente en áreas remotas.</p>	331	332	171	2024-04-16 17:50:55	2024-04-16 17:50:55
188	aspect	Evaluación del Funcionamiento de Red	Instituto de Previsión Social	Interno	<p>Se realizan evaluaciones periódicas del funcionamiento de la red de salud para identificar áreas de mejora y desarrollar planes de acción para optimizar su rendimiento.</p>	333	334	171	2024-04-16 17:51:23	2024-04-16 17:51:23
189	aspect	Existencia de Reuniones Periódicas de Coordinación de Red	Instituto de Previsión Social	Interno	<p>Se promueve el buen relacionamiento y la coordinación entre los diferentes actores de la red a través de reuniones periódicas de coordinación y trabajo en equipo.</p>	335	336	171	2024-04-16 17:51:47	2024-04-16 17:51:47
171	category	Gestión de la Red Sanitaria de Instituto de Previsión Social (IPS)	Instituto de Previsión Social	Interno	<p>La red de salud del IPS es un entramado complejo que engloba diversos aspectos cruciales para la atención integral de sus asegurados.</p>	300	339	97	2024-04-16 17:45:50	2024-04-16 17:45:50
190	aspect	Buen Relacionamiento para la Derivación de Pacientes	Instituto de Previsión Social	Interno	<p>Se establecen relaciones sólidas y colaborativas entre los diferentes centros de salud y hospitales de la red para facilitar la derivación de pacientes según sus necesidades de atención médica.</p>	337	338	171	2024-04-16 17:51:58	2024-04-16 17:51:58
192	aspect	Apoyo gubernamental y políticas públicas favorables	Instituto de Previsión Social	Externo	<p>Apoyo gubernamental y políticas públicas favorables:Se refiere al respaldo y respaldo proporcionado por las autoridades gubernamentales y a la existencia de políticas que promuevan el acceso y la calidad de los servicios de salud.</p>	341	342	191	2024-04-16 21:24:11	2024-04-16 21:24:11
193	aspect	Alianzas estratégicas y colaboraciones con otas instituciones de salud	Instituto de Previsión Social	Externo	<p>Implica establecer asociaciones con otras organizaciones de salud para compartir recursos, conocimientos y mejorar la eficacia de la atención médica.</p>	343	344	191	2024-04-16 21:24:46	2024-04-16 21:24:46
194	aspect	Cambios demográficos favorables y concienciación pública sobre salud	Instituto de Previsión Social	Externo	<p>Se relaciona con la comprensión y adaptación a las tendencias demográficas, así como con la educación y sensibilización de la población sobre temas de salud.</p>	345	346	191	2024-04-16 21:25:39	2024-04-16 21:25:39
191	category	Gestión Social y Política	Instituto de Previsión Social	Externo	<p>La Gestión Social y Política se centra en las interacciones con la comunidad y en las relaciones con entidades gubernamentales.</p>	340	349	97	2024-04-16 21:23:04	2024-04-16 21:23:04
195	aspect	Posibilidad de expansión geográfica y acceso a financiamiento externo	Instituto de Previsión Social	Externo	<p>Se refiere a la capacidad para ampliar la cobertura de los servicios de salud a nuevas áreas geográficas y acceder a recursos financieros externos para financiar proyectos de salud.</p>	347	348	191	2024-04-16 21:25:56	2024-04-16 21:25:56
196	category	Gestión Tecnológica	Instituto de Previsión Social	Externo	<p>La Gestión Tecnológica se centra en la implementación y utilización efectiva de tecnología en el ámbito de la salud.</p>	350	355	97	2024-04-16 21:32:02	2024-04-16 21:32:02
212	aspect	Humanización en la Atención	Instituto de Previsión Social	Interno	<p>Se promueve la humanización en la atención médica, considerando las necesidades emocionales y psicológicas de los pacientes.</p>	381	382	205	2024-04-20 03:44:32	2024-04-20 03:44:32
\.


--
-- Data for Name: foda_perfiles; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.foda_perfiles (id, name, context, type, model_id, group_id, dependency_id, created_at, updated_at) FROM stdin;
9a670199-515d-45e0-9adf-da963f6cb5cd	Río Paraguay (Presidencia)	Planificación Estratégica Institucional 2023-2028	grupal	1	6	\N	2023-10-18 23:33:11	2023-10-18 23:33:11
9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	Río Paraguay	Planificación Estratégica Institucional 2023-2028	grupal	1	15	\N	2023-10-18 23:36:28	2023-10-18 23:36:28
9a6703e4-3d3f-4400-8224-3be4d4c1cf80	Río Apa (Presidencia)	Planificación Estratégica Institucional 2023-2028	grupal	1	7	\N	2023-10-18 23:39:35	2023-10-18 23:39:35
9a6704e0-53f7-4eaf-9015-3fbcc0179e76	Río Apa	Planificación Estratégica Institucional 2023-2028	grupal	1	16	\N	2023-10-18 23:42:21	2023-10-18 23:42:21
9a670613-48f6-4af7-8680-57660453ee1b	Río Pilcomayo (Gerencia de Desarrollo y Tecnología)	Planificación Estratégica Institucional 2023-2028	grupal	1	8	\N	2023-10-18 23:45:42	2023-10-18 23:45:42
9a6706ec-e7b4-4906-9428-09e20268acbb	Río Pilcomayo	Planificación Estratégica Institucional 2023-2028	grupal	1	17	\N	2023-10-18 23:48:04	2023-10-18 23:48:04
9a670886-a987-4724-b5bd-8d84db396f9a	Río Paraná (Gerencia de Abastecimiento y Logística)	Planificación Estratégica Institucional 2023-2028	grupal	1	9	\N	2023-10-18 23:52:33	2023-10-18 23:52:33
9a670976-68cf-4f5e-b4f5-4c56931aa175	Río Paraná	Planificación Estratégica Institucional 2023-2028	grupal	1	18	\N	2023-10-18 23:55:10	2023-10-18 23:55:10
9a670a4d-dc08-48b3-be07-fb2a830e199f	Río Verde (Gerencia de Abastecimiento y Logística)	Planificación Estratégica Institucional 2023-2028	grupal	1	10	\N	2023-10-18 23:57:31	2023-10-18 23:57:31
9a670c4c-1010-4ad0-97ad-0194abe0930d	Río Verde	Planificación Estratégica Institucional 2023-2028	grupal	1	19	\N	2023-10-19 00:03:06	2023-10-19 00:03:06
9a670d46-01c4-4466-ad21-73d8f16bf0a8	Río Montelindo (Gerencia de Salud)	Planificación Estratégica Institucional 2023-2028	grupal	1	20	\N	2023-10-19 00:05:50	2023-10-19 00:05:50
9a670e44-aeed-4cfe-9c60-5d305913f7cb	Río Montelindo	Planificación Estratégica Institucional 2023-2028	grupal	1	20	\N	2023-10-19 00:08:36	2023-10-19 00:08:36
9a670f0e-8ab2-492c-be8d-b206cd96ad2e	Río Ypané (Gerencia de Salud)	Planificación Estratégica Institucional 2023-2028	grupal	1	12	\N	2023-10-19 00:10:49	2023-10-19 00:10:49
9a670fde-7520-4e65-8d55-46e9d730abfc	Río Ypané	Planificación Estratégica Institucional 2023-2028	grupal	1	21	\N	2023-10-19 00:13:05	2023-10-19 00:13:05
9a6710ce-015e-422e-a288-b63cb70a72b2	Río Piribebuy (Gerencia de Prestaciones Económicas)	Planificación Estratégica Institucional 2023-2028	grupal	1	14	\N	2023-10-19 00:15:42	2023-10-19 00:15:42
9a672fc4-d900-4b24-9e9b-d71943b1470f	Río Jejuí (Gerencia Administrativa y Financiera)	Planificación Estratégica Institucional 2023-2028	grupal	1	13	\N	2023-10-19 01:42:17	2023-10-19 01:42:17
9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	Río Jejuí	Planificación Estratégica Institucional 2023-2028	grupal	1	22	\N	2023-10-19 01:58:39	2023-10-19 01:58:39
9a684a9e-0630-432c-8e2e-af213a33543a	Río Montelindo (Gestión Médica)	Planificación Estratégica Institucional 2023-2028	grupal	1	24	\N	2023-10-19 14:53:11	2023-10-19 14:53:11
9a684cfb-8d97-4115-ae88-855f59885d0c	Río Montelindo (Lila)	Planificación Estratégica Institucional 2023-2028	grupal	1	25	\N	2023-10-19 14:59:48	2023-10-19 14:59:48
9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e	ANÁLISIS FODA IPS	PLANIFICACIÓN ESTRATÉGICA  2023-2028	consolidado	1	5	8	2023-10-25 11:49:38	2023-10-25 11:49:38
9be4bc1c-6178-4f10-a2eb-396841c7ee61	Tigre	Construcción RIISS - ESTE	grupal	97	35	\N	2024-04-25 17:45:08	2024-04-25 17:45:08
9be4bc4a-6707-4cee-8c38-8093a936b729	Leon	Construcción RIISS - ESTE	grupal	97	38	\N	2024-04-25 17:45:38	2024-04-25 17:45:38
9be4bc6e-140e-4970-a7fb-2a6c8aa03acf	Oso	Construcción RIISS - ESTE	grupal	97	39	\N	2024-04-25 17:46:01	2024-04-25 17:46:01
9be4bc92-5758-487f-9eba-cfbc54a8e128	Elefante	Construcción RIISS - ESTE	grupal	97	37	\N	2024-04-25 17:46:25	2024-04-25 17:46:25
9be4bccd-4b41-48d2-b6c8-9a586e0bd165	Pato	Construcción RIISS - ESTE	grupal	97	36	\N	2024-04-25 17:47:04	2024-04-25 17:47:04
9be4bcf3-5b4e-42c9-8d07-484209e4d8cd	Pescado	Construcción RIISS - ESTE	grupal	97	40	\N	2024-04-25 17:47:29	2024-04-25 17:47:29
9be4bd15-3026-42de-87d5-5ae1bb412470	Aguila	Construcción RIISS - ESTE	grupal	97	41	\N	2024-04-25 17:47:51	2024-04-25 17:47:51
9be4bd41-6f2c-4692-bb29-7ee1ee190d92	Perro	Construcción RIISS - ESTE	grupal	97	42	\N	2024-04-25 17:48:20	2024-04-25 17:48:20
\.


--
-- Data for Name: pei_profiles; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.pei_profiles (id, name, year_start, year_end, type, level, mision, vision, "values", period, numerator, operator, denominator, goal, progress, order_item, "year_start ", "year_end ", action, indicator, baseline, target, group_id, dependency_id, user_id, _lft, _rgt, deleted_at, created_at, updated_at, parent_id, parameters, report_type) FROM stdin;
9a82bb13-de55-4905-a1a3-458320628342	<p>Redistribución del personal para cubrir necesidades emergentes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Número de funcionarios por área para detección de necesidad de dotación de funcionarios en otras áreas	Cantidad de funcionarios mensual por área al año 2023	Cobertura del 60% de las necesidades de las áreas.	\N	8	1	356	357	\N	2023-11-01 18:19:10	2023-11-06 11:59:54	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a824a34-a899-406a-a8a6-4d6455f4ae50	<p>Análisis de la RIISS del IPS para la Categorización por Niveles de los establecimientos de salud.</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Porcentaje de Avance en el Análisis de la RIISS para Categorización por Niveles. Número de redes categorización analizados / Total de criterios de categorización) x 100	no se contaba con línea de base	Implementación del 100% de la categorización de niveles	\N	8	1	225	226	\N	2023-11-01 13:03:33	2023-11-06 11:54:41	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a8105a1-2019-47c1-aeef-acf480b8d29d	<p>Eje III - Innovación tecnológica y optimización en la gestión estratégica y administrativa.</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	56	59	2023-11-01 02:54:35	2023-10-31 21:55:59	2023-11-01 02:54:35	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a828fcd-a317-4475-8ca3-a538b57f05bd	<p>Renovación de equipos biomédicos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Cantidad de equipos biomédicos obsoletos	Llamados a licitación	5% anual	\N	8	1	161	162	\N	2023-11-01 16:18:10	2023-11-06 12:03:39	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a8253ff-2b24-45b2-bfe1-7f3eee7edc65	<p><strong>Fortalecimiento de las competencias de los funcionarios</strong></p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Cantidad de capacitaciones realizadas al personal	\N	\N	\N	8	\N	110	111	2023-11-01 13:35:06	2023-11-01 13:30:56	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a829e8a-b991-49c4-b292-9caedf836c33	<p>Actualizar y socializar los planes operativos según criterios epidemiologicos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	numeros de planes operativos existentes	Actualizar y socializar los planes operativos según criterios epidemiologicos	100% de los planes de accion actualizados	\N	8	8	293	294	2023-11-01 17:50:22	2023-11-01 16:59:23	2023-11-01 17:50:22	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	\N	\N
9a81faef-9fd1-43ca-9a0c-31fc9c3a7ac4	<p>Axis</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	66	67	2023-11-01 09:22:02	2023-11-01 09:21:54	2023-11-01 09:22:02	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a82b2e7-0fd0-4026-9c0b-b9545c6e3cef	<p>1- Ampliar la cobertura de la red del servicio de telemedicina</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	8	271	272	2023-11-03 00:46:06	2023-11-01 17:56:19	2023-11-03 00:46:06	9a82383c-d5d0-484f-943d-51b34d4dea8e	\N	\N
9a82383c-d5d0-484f-943d-51b34d4dea8e	<p>5- Promover la aplicación del concepto de salud digital en las RIISS</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	5	\N	\N	\N	\N	\N	\N	\N	8	1	270	285	\N	2023-11-01 12:13:19	2023-11-05 22:14:03	9a81a6f8-8f40-41d4-bfe1-8b73e5ec3ff4	\N	\N
9a8234fc-6991-4c0f-9342-62bf099a36de	<p>Concluir las obras adjudicadas al 2023</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Cantidad de obras adjudicadas	Porcentaje de avance de cada obra	100% para fines del año 2026	\N	8	1	163	164	\N	2023-11-01 12:04:13	2023-11-06 12:03:45	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82b504-8b14-4f9a-9f1a-d0dac9294553	<p>Capacitar al funcionariado del Instituto de Previsión Social en planificación estratégica, gestión por procesos y control interno</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	Cantidad de Funcionarios capacitados con el curso aprobado. Fórmula: número de funcionarios capacitados con el curso aprobado sobre el total de funcionarios que hayan rendido	Resoluciones Aprobadas Vigentes	Cobertura en promedio de mas del 80% de funcionarios capacitados que hayan aprobado.	\N	8	1	227	228	\N	2023-11-01 18:02:13	2023-11-06 11:54:57	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a81a733-7d22-4ae0-becf-fa1ec7fecf31	<p>EJE III - Innovación tecnológica y optimización en la gestión estratégica y administrativa. (Rino y Oscar Franco del Grupo D, Alejandro y Giuli del Grupo E, Andrea y Silvia del grupo F )</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	\N	\N	\N	\N	8	1	94	268	\N	2023-11-01 05:27:46	2023-11-05 21:11:18	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a824a5d-9f55-463c-ba5f-fad97cb224d9	<p>Ejecución adecuada del plan anual de contrataciones (PAC) previendo modificaciones de acuerdo a la urgencia del caso</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	PAC inicial con modificaciones menores al 20%	PAC inicial modificado en más del 50%	PAC inicial ejecutado al 80%	\N	8	1	253	254	\N	2023-11-01 13:04:00	2023-11-06 11:59:18	9a81a96d-d82e-4398-8b6c-9f8e7e9a177a	\N	\N
9a66ec8d-4cd8-496c-a0bd-f88bc880e6c9	Río Pilcomayo (Gerencia de Desarrollo y Tecnología)	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	\N	5	6	\N	2023-10-18 22:34:20	2023-10-18 22:34:20	\N	\N	\N
9a66f0cd-b29a-400a-867a-783058b92592	Río Paraguay	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	15	\N	\N	21	22	\N	2023-10-18 22:46:13	2023-10-18 22:46:13	\N	\N	\N
9a66f1bc-8f00-4e57-bf95-7e0cb307286b	Río Paraná	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	18	\N	\N	27	28	2023-10-18 22:49:17	2023-10-18 22:48:50	2023-10-18 22:49:17	\N	\N	\N
9a822ca1-dcd2-49eb-aeba-1583289eff3b	<p>prueba</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	\N	8	\N	129	130	2023-11-01 12:07:15	2023-11-01 11:40:52	2023-11-01 12:07:15	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a66f207-5b5a-43d3-bd5f-d4c413062753	Río Paraná	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	18	\N	\N	29	30	\N	2023-10-18 22:49:39	2023-10-18 22:49:39	\N	\N	\N
9a66f251-91bf-48ab-88a7-27acec09c3aa	Río Verde	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	19	\N	\N	31	32	\N	2023-10-18 22:50:27	2023-10-18 22:50:27	\N	\N	\N
9a66f286-c8d2-44af-9033-553cc6c0e932	Río Montelindo	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	20	\N	\N	33	34	\N	2023-10-18 22:51:02	2023-10-18 22:51:02	\N	\N	\N
9a66f325-b52d-4fcc-9492-427c47aeedd2	Río Apa	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	16	\N	\N	37	38	\N	2023-10-18 22:52:46	2023-10-18 22:52:46	\N	\N	\N
9a66eed4-fd56-4abc-9a66-b39dd42c782f	Río Jejuí (Gerencia Administrativa y Financiera)	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	13	\N	\N	17	18	2023-10-19 02:10:33	2023-10-18 22:40:42	2023-10-19 02:10:33	\N	\N	\N
9a828443-9867-4156-8425-15e2075959a7	<p>Renovación de contratos de servicios de limpieza de manera bianual</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	11	\N	\N	\N	Requerimiento de servicio de limpieza a nivel institucional	Cantidad de contratos adjudicados	100% bianual	\N	8	1	181	182	\N	2023-11-01 15:45:54	2023-11-06 12:04:46	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a8272a8-0605-4aee-93f4-6b1e5485c340	<p>Mantenimiento anual, preventivo y correctivo, de Sistemas Eléctricos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	14	\N	\N	\N	Cantidad de sistemas eléctricos disponibles	Número de contratos adjudicados	100% anual	\N	8	1	187	188	\N	2023-11-01 14:56:40	2023-11-06 12:05:13	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a827ec8-d220-47d7-b562-05e7140a7e5c	<p>Optimización de sistema de monitoreo</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	15	\N	\N	\N	Existencias y necesidades de sistema de monitoreo	Adjudicación de contratos	20% anual	\N	8	1	189	190	\N	2023-11-01 15:30:35	2023-11-06 12:05:19	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a8247ca-e8bb-41d2-b8f5-b152ac527c1e	<p>Capacitación para el uso correcto e integral de las herramientas tecnológicas</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Número de eventos de capacitación sobre herramientas tecnológicas sobre total de eventos de capacitación	No se cuenta	100% del total de eventos de capacitación sobre herramientas tecnológicas	\N	8	1	141	142	\N	2023-11-01 12:56:49	2023-11-06 12:02:04	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a824b0a-1777-4966-bfb1-9dffdeed2bbb	<figure class="table"><table><tbody><tr><td>Actualización de manuales de funciones</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Número de manuales de funciones actualizados	60 Manuales de Funciones vigentes	Completar a actualización del 50% de los manuales de funciones	\N	8	\N	108	109	2023-11-01 13:35:06	2023-11-01 13:05:53	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a825824-d83f-4b24-bf30-a835884ba779	<p>Mantenimiento anual de las obras civiles</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Cantidad de obras civiles concluídas	Número de contratos adjudicados al 2023	100% anual	\N	8	1	165	166	\N	2023-11-01 13:42:32	2023-11-06 12:03:50	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a828b46-4fb3-4045-afe8-e692e3122042	<p>Gestionar de optimización de convenio de provisión de combustible y lubricantes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	Necesidad de modificación del convenio vigente	Criterios del convenio vigente	20% anual	\N	8	1	167	168	\N	2023-11-01 16:05:30	2023-11-06 12:03:55	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82335a-3246-4381-b806-0afe20e5036d	<p>asd</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	133	134	2023-11-01 12:06:35	2023-11-01 11:59:39	2023-11-01 12:06:35	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a826a17-0a55-4c48-b492-acbf8fa5ad52	<p>Simplificación y desburocratización de los procesos internos de adquisición</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Tiempo promedio de gestión en los procesos	Cantidad de Procesos Aprobados	Monitoreo mediante un flujograma simplificado que permita visualizar la agilización de los procesos	\N	8	1	251	252	\N	2023-11-01 14:32:43	2023-11-06 11:59:03	9a81a96d-d82e-4398-8b6c-9f8e7e9a177a	\N	\N
9a82b361-b4af-4461-9520-466ebe579d57	<p>2- Asegurar la cobertura de vacunacion de los niños y adolecentes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	8	337	338	2023-11-06 11:47:14	2023-11-01 17:57:39	2023-11-06 11:47:14	9a81a818-2daa-4946-84c2-94c65f1ca599	\N	\N
9a81a9b0-4a41-4bd2-8f0d-d76b75938568	<p>5- Promover Calidad en todos los proyectos de infraestructura física, mantenimiento edilicio y de los equipamientos de la Institución actuales y futuros.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	5	\N	\N	\N	\N	\N	\N	\N	8	1	160	219	\N	2023-11-01 05:34:43	2023-11-06 04:24:53	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a826b02-1d29-4b93-8ffb-e8f2c04fb1ca	<p>Desarrollo e Implementación de la carrera administrativa Institucional</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	\N	\N	\N	Cantidad de funcionarios insertados en el plan de carrera sobre el total de funcionarios	No se cuenta con desarrollo de carrera administrativa	Incorporación del 50% de funcionarios en  3 años	\N	8	1	366	367	\N	2023-11-01 14:35:17	2023-11-06 12:00:47	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a81068d-19ea-44de-a713-66405894004f	<p>1- Estructurar la Red Integrada e Integral de Servicios Salud, con enfoque preventivo, incluyendo la promoción de la salud.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	57	58	2023-11-01 02:54:35	2023-10-31 21:58:33	2023-11-01 02:54:35	9a8105a1-2019-47c1-aeef-acf480b8d29d	\N	\N
9a8249ef-d149-4cf2-964c-19cb8194f971	<p>Ajustes de Procesos de Agendamientos de los Establecimientos de Salud del IPS (Fortalecimiento del Centro de Atención al Usuario - Call Center y Procesos de Agendamientos).</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	5	\N	\N	\N	Cantidad de turnos disponibles/cantidad de turnos requeridos	no se cuenta	100% de turnos disponibles en 1 año	\N	8	1	229	230	\N	2023-11-01 13:02:48	2023-11-06 11:55:17	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a82b1d6-d03e-4878-8ff7-8befad02fe48	<p>Elaboración, socialización y capacitación de los protocolos de atención en salud</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Numero de protocolos existentes	Cantidad de protocolos activos de IPS	Numero total de los protocolos de atención en salud, elaborados, socializados	\N	8	1	297	298	\N	2023-11-01 17:53:20	2023-11-06 11:41:19	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	\N	\N
9a81a818-2daa-4946-84c2-94c65f1ca599	<p>6- Promover la implementación de líneas de cuidado por ciclo de vida.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	\N	\N	\N	\N	\N	\N	\N	8	1	334	351	\N	2023-11-01 05:30:16	2023-11-06 11:51:28	9a81a6f8-8f40-41d4-bfe1-8b73e5ec3ff4	\N	\N
9a828806-161c-4fbd-965c-83f762fea4c5	<p>Optimizar el proceso de concesión de subsidios mediante la revisión y modificación de reglamentos vigentes&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Un reglamento actualizado para la Concesión de Subsidios	Numero de reglamentos vigentes	1 Reglamento para concesión de subsidios actualizado y aprobado	\N	8	1	319	320	\N	2023-11-01 15:56:25	2023-11-06 11:40:06	9a81a7ca-4ca2-474f-987e-f34815316acb	\N	\N
9a82a044-8d1d-4a5e-8636-d648ec98173b	<p>2- Ampliar los establecimientos que cuenten con el servicio de remisión digital de los resultados laboratoriales</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Número de establecimientos que cuenten con el servicio de remisión digital de los resultados laboratoriales	10 establecimientos donde se encuentra implementado el envió de los resultados laboratoriales de forma digital	50 establecimientos que implementen la remisión digital de los resultados de laboratorio	\N	8	1	283	284	\N	2023-11-01 17:04:12	2023-11-06 11:43:14	9a82383c-d5d0-484f-943d-51b34d4dea8e	\N	\N
9a8282c7-f6f6-4b44-85e2-92858ae0cdb0	<p>Capacitación para el uso correcto e integral de las herramientas tecnológicas</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Porcentaje de funcionarios capacitados en tecnología sobre total de funcionarios	0 % (sin datos)	100% del personal capacitado en uso herramientas tecnológicas	\N	8	1	263	264	\N	2023-11-01 15:41:45	2023-11-06 12:07:40	9a82823b-4bf6-4589-8197-66b5cde73f62	\N	\N
9a829e2e-f5f9-4a9a-9f88-8377b8e8a7cc	<p>2- Asegurar el registro adecuado de los insumos para la trazabilidad en la provisión de los mismos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Asegurar el registro adecuado de los insumos para la trazabilidad en la provisión de los mismos	Asegurar el registro adecuado de los insumos para la trazabilidad en la provisión de los mismos	Asegurar el registro adecuado de los insumos para la trazabilidad en la provisión de los mismos	\N	8	8	287	288	2023-11-01 17:50:40	2023-11-01 16:58:23	2023-11-01 17:50:40	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	\N	\N
9a81a020-a984-4632-8825-10a6e563e021	<p>Eje I - Fortalecimiento de la gestión de la Red integrada e integral de Salud.</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	64	65	2023-11-01 05:26:52	2023-11-01 05:07:59	2023-11-01 05:26:52	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a82622b-e5b6-433b-a38b-2bda8a2fbf86	<p>Elaborar la Política Institucional de Medicina Preventiva de Enfermedades Prevenibles No trasmisibles</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	Cantidad de Políticas Institucional de Medicina Preventiva de Enfermedades Prevenibles No trasmisibles aprobadas	1(uno) Disponemos de Política en materia de Medicina Preventiva de Diabetes	1 (uno) por año. Aprobación de la Política Política Institucional de Medicina Preventiva de Enfermedades Prevenibles No transmisibles	\N	8	11	325	326	\N	2023-11-01 14:10:34	2023-11-06 12:23:54	9a81a7ca-4ca2-474f-987e-f34815316acb	\N	\N
9a66ed48-c808-453f-bbc7-bccf46591679	Río Verde (Gerencia de Abastecimiento y Logística)	\N	\N	group	master	<p>Dirigir y Administrar el Seguro Social <strong>otorgando</strong> servicios de prestaciones sanitarias de calidad, jubilaciones y pensiones, a través de una gestión eficiente a nuestros asegurados.</p>	<p>Ser la Institución líder del Seguro Social eficiente, sostenible y transparente que otorgue el acceso a prestaciones sanitarias y económicas.</p>	<p>VALORES&nbsp;</p><ol><li>Honestidad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Actúo con honradez, verdad y justicia.</i></li><li>Integridad: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Soy coherente entre lo que hago y digo.</i></li><li>Responsabilidad: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Asumo los deberes y obligaciones.</i></li><li>Respeto: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Trato con dignidad<i> y tolerancia.</i></li><li>Solidaridad: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Ayudo y sirvo a los demás.</i></li><li>Servicio: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Tengo actitud de colaboración permanente.</i></li><li>Transparencia: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Hago pública la gestión realizada.</i></li><li>Eficiencia: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Logro los objetivos con calidad y mejor utilización de recursos.</i></li></ol><p>&nbsp;</p><p><strong>EJES ESTRATÉGICOS:&nbsp;</strong></p><p>1.Fortalecimiento de la gestión de la Red integrada de Salud.</p><p>2.Sostenibilidad del Fondo Común de Jubilaciones y Pensiones.</p><p>3. Innovación y mejora en la gestión estratégica y administrativa.</p><p>&nbsp;</p><p>Objetivos Propuestos por Ejes:</p><p><strong>Eje Estratégico: 3-</strong> Innovar la gestión administrativa.</p><p>3.1 Disponer de un sistema de abastecimiento institucional de bienes y servicios oportuno y eficiente, que apoye la prestación de servicios del IPS.</p><p>3.2 Desarrollar y mantener la infraestructura física y los equipamientos de manera que respondan a las necesidades de salud y administrativas actuales y futuras, con una organización en red, y evaluación constante de tecnologías de salud para la optimización de los recursos, con sostenibilidad ambiental.</p><p>3.3 Fortalecer e Integrar la gestión del Talento Humano, con orientación dirigida a la mejora de sus capacidades, sus condiciones de trabajo y accesibilidad; como un factor determinante en la consecución de una cultura de la calidad en la prestación de los servicios.</p><p>3.4 Incrementar la capacidad de la organización para la administración del riesgo en todos los procesos de la Institución.&nbsp;</p><p>&nbsp;</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	10	\N	\N	9	10	\N	2023-10-18 22:36:23	2023-10-20 12:29:14	\N	\N	\N
9a82689a-6d59-499f-a48f-93a3ab24da79	<p>Mantenimiento anual, preventivo y correctivo, de cámaras de refrigeración</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	\N	\N	\N	Cantidad de cámaras de refrigeración de la Institución	Número de contratos adjudicados	100% anual	\N	8	1	171	172	\N	2023-11-01 14:28:33	2023-11-06 12:04:14	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a828b0b-7300-4f2d-9bd9-3d889ef31d8e	<p>Renovación de servicio mantenimiento de áreas verdes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	5	\N	\N	\N	Cantidad de servicios por Lotes de mantenimientos de áreas verdes/ cantidad de Lotes adjudicados en el año T +1	Cantidad de servicios por Lotes de mantenimientos de áreas verdes/ cantidad de Lotes adjudicados en el año T	100% anual de ejecución de Contratos Adjudicados	\N	8	1	169	170	\N	2023-11-01 16:04:52	2023-11-06 12:04:07	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a824afc-1ae1-4acf-ae43-73f9726063d1	<p>4- Innovar las tecnologías de Informción, comunicación y ciberseguridad</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	119	120	2023-11-01 13:08:18	2023-11-01 13:05:44	2023-11-01 13:08:18	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a8247a8-9377-4e8a-a1b5-b7cf8cf315c6	<figure class="table"><table><tbody><tr><td>Provisión de medicamentos de uso en pacientes crónicos (hipertensos y diabéticos) para enfermedades prevalentes en toda la red:</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	137	138	2023-11-01 16:44:54	2023-11-01 12:56:26	2023-11-01 16:44:54	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a81a27a-4dc7-4ef3-a479-5a0b7ea8d8ec	<p>Eje II - Sostenibilidad del Fondo Común de Jubilaciones y Pensiones.</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	62	63	2023-11-01 05:26:54	2023-11-01 05:14:33	2023-11-01 05:26:54	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a82874e-6d6b-4cef-8c8c-47f1abf3cdc4	<p>Optimizar la gestión de las prestaciones económicas (junta medica) en localidades del IPS, mediante la adopción de TICs</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Cantidad  de localidades actuales / cantidad total de localidades donde se realizan junta médica	Localidades en las que se realiza Junta Médica: 1 (Asunción)	Aumentar al menos a 4 el N° de localidades en las que se realiza Junta Médica ( por vía telemática)	\N	8	1	317	318	\N	2023-11-01 15:54:24	2023-11-06 11:39:59	9a81a7ca-4ca2-474f-987e-f34815316acb	\N	\N
9a82b378-7913-4f61-a0f9-8259983c3a1a	<p>3- Promover la red de atencion integral de pacientes con Diabetes del IPS</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	8	339	340	2023-11-06 11:47:41	2023-11-01 17:57:54	2023-11-06 11:47:41	9a81a818-2daa-4946-84c2-94c65f1ca599	\N	\N
9a66ebbe-0cc5-4ba3-a1d7-83961d83cd53	Río Paraguay (Presidencia)	\N	\N	group	master	<p><strong>Propuesta N° 2.2:&nbsp;</strong>La Misión del IPS es garantizar el Seguro Social <strong>otorgando</strong> prestaciones oportunas mediante una gestión eficiente a nuestros asegurados.</p>	<p>Propuesta N°1: Ser un Instituto de Previsión Social, eficiente y con gobernanza transparente, que otorga el acceso a las prestaciones sanitarias y prestaciones económicas, garantizando la sostenibilidad del sistema desde sus tres enfoques: cobertura, solvencia financiera, prestaciones adecuadas.</p>	<ol><li>Integridad: Obrar con rectitud y honradez para garantizar la transparencia en los resultados propuestos.</li><li>Solidaridad: Garantizar protección a los asegurados en los momentos de mayor vulnerabilidad.</li><li>Responsabilidad: asumir los deberes y obligaciones necesarios para mejorar la seguridad en las prestaciones económicas y de salud.</li><li>Transparencia: Someter al conocimiento público, la información relativa a su gestión, manejo de los recursos, criterios que sustentan sus decisiones y conducta de sus funcionarios.</li><li>Respeto: Atender y escuchar a las personas y sus asuntos, reconociendo su dignidad como seres humanos, sin distinción de ninguna naturaleza.</li><li>Honestidad: Actuar con rectitud a partir de la razón; ser incapaz de engañar o defraudar a las personas.</li><li>Eficencia: Lograr los objetivos trazados con calidad y buen manejo de los recursos institucionales.</li><li>Servicio: Colaborar de manera permanente con el usuario interno y con los asegurados.</li></ol><p><strong>Propuestas de Ejes Estratégicos:</strong></p><ol><li><strong>Fortalecimiento de la gestión de la Red Integrada e Integral de Salud.</strong></li><li><strong>Sostenibilidad del Fondo Común de Jubilaciones y Pensiones</strong></li><li><strong>Mejora en la gestión estratégica y administrativa.</strong></li></ol><p><strong>EJES ESTRATÉGICOS</strong></p><p><strong>EJE 1</strong></p><ol><li><strong>Fortalecimiento de la gestión de la Red Integrada e Integral de Salud.</strong><ol><li><strong>Estructurar una red integrada e integral de servicios de salud</strong></li><li><strong>Garantizar la calidad y cobertura de los servicios de salud</strong></li><li><strong>Priorizar la prevención y la promoción de la salud</strong></li></ol></li></ol><p><strong>EJE 2</strong></p><ol><li><strong>Sostenibilidad del Fondo Común de Jubilaciones y Pensiones</strong><ol><li><strong>Impulsar reformas legales orientadas a mejorar la calidad del gasto, aumentar el rendimiento y seguridad de las inversiones, así como la libre disposición de los recursos del Instituto.</strong></li><li><strong>Gestionar el cobro de la deuda del Estado en concepto de Aporte.</strong></li><li><strong>Brindar prestaciones económicas y sociales oportunamente a la población asegurada de los regímenes de invalidez, vejez y muerte, mediante una gestión transparente y sostenible.</strong></li></ol></li></ol><p><strong>EJE 3</strong></p><ol><li><strong>Innovación tecnológica y optimización en la gestión estratégica y administrativa</strong><ol><li><strong>Fortalecer el direccionamiento estratégico institucional, en cumplimiento de los fines del Seguro Social.</strong></li><li><strong>Asegurar el uso eficiente de los recursos institucionales para la provisión de servicios de salud y de jubilaciones y pensiones, de acuerdo con el principio de sostenibilidad de los fondos.</strong></li><li><strong>Disponer de un sistema de abastecimiento institucional de bienes y servicios oportuno y eficiente, que apoye la prestación de servicios del IPS.</strong></li><li><strong>Desarrollar y mantener la infraestructura física y los equipamientos, de manera que respondan a las necesidades de salud y administrativas actuales y futuras, con una organización en red, con evaluación constante de tecnologías de salud para la optimización de los recursos y con sostenibilidad ambiental.</strong></li><li><strong>Brindar tecnologías de información y comunicación eficaces, de calidad y seguras, que respondan a las necesidades de continuidad y desarrollo requeridas en los servicios brindados a los usuarios internos y externos.</strong></li><li><strong>Fortalecer las políticas del talento humano vigentes aprobadas por la máxima autoridad a fin de garantizar la eficiencia, la eficacia en la gestión y el desarrollo de los talentos humanos, así como las competencias y habilidades para el cumplimiento de los planes institucionales.</strong></li></ol></li></ol><p>&nbsp;</p><p>&nbsp;</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	\N	\N	1	2	2023-11-01 00:02:52	2023-10-18 22:32:04	2023-10-20 12:18:32	\N	\N	\N
9a68542f-07f7-4b61-8769-561a034971b4	Río Montelindo (Gestión Médica)	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	24	\N	\N	45	46	\N	2023-10-19 15:19:56	2023-10-19 15:19:56	\N	\N	\N
9a685474-a0ed-48ee-985b-c908923da254	Río Montelindo (Lila)	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	25	\N	\N	47	48	\N	2023-10-19 15:20:42	2023-10-19 15:20:42	\N	\N	\N
9a66ece7-56d6-42ea-99ce-dcef14f1e42b	Río Paraná (Gerencia de Abastecimiento y Logística)	\N	\N	group	master	<p>La Misión del IPS es: Dirigir y Administrar el Seguro Social otorgando prestaciones: sanitarias de calidad, jubilaciones y pensiones en tiempo y forma, con recursos humanos calificados y comprometidos, mediante una gestión eficiente a nuestros asegurados</p>	<p>Ser la institución lider del Seguro Social, comprometida con una atención de calidad en Salud, ágil en la concesión de jubilaciones y pensiones, sostenible y solidaria a los asegurados.</p>	<ol><li>Honestidad: Actuar con rectitud a partir de la razón; ser incapaz de engañar o defraudar a las personas.</li><li>Integridad: Rectitud y <strong>coherencia</strong> en las actuaciones de la institución, para lograr transparencia en los resultados propuestos.</li><li><strong>Responsabilidad:</strong> Asumir los deberes y obligaciones necesarios para mejorar la seguridad en las prestaciones económicas y de salud y aceptar las consecuencias.</li><li>Respeto: Atender y escuchar a las personas y sus asuntos, reconociendo su dignidad como seres humanos, sin distincion de ninguna naturaleza.</li><li>Solidaridad: Garantía de protección a los asegurados en los momentos de mayor vunerabilidad.</li><li>Servicio: Colaboración permanente tanto con el usuario interno y con los asegurados.&nbsp;</li><li>Transparencia: Deber de la institución de someter al conocimiento público.</li><li>Eficiencia: Lograr los objetivos trazados de forma oportuna con calidad y buen manejo de los recursos institucionales.</li></ol><p>EJE 1 FORTALECIMIENTO EN LA GESTION DE LA RED INTEGRADA DE SALUD</p><ul><li>Estructurar una red integrada e integral de salud</li><li>Sostenibilidad sistema de salud que garantice la cobertura de servicios de salud con calidad de forma oportuna y eficiente</li><li>Priorizar la prevención de enfermedades y la promoción de salud</li></ul><p>EJE 2 SOSTENIBILIDAD DE LOS FONDOS DE JUBILACIONES Y PENSIONES &nbsp;Y SALUD</p><ul><li>Brindar las prestaciones economicas y sociales oportunamente a la población asegurada</li><li>Impulsar las reformas legales orientadas a mejorar a calidad del gasto, aumentar el rendimiento y seguridad de las inversiones.</li><li>Gestionar el cobro de la deuda del Estado en &nbsp;concepto de Aporte</li><li>Mejorar la rentabilidad de los inmuebles</li></ul><p>EJE 3 INNOVAR LA GESTIÓN ADMINISTRATIVA</p><p>GESTION DE ABASTECIMIENTO Y LOGISTICA:</p><ul><li>Lograr un sistema de abastecimiento de bienes y servicios oportuno y eficiente, mediante la ordenada planificación de las compras estableciendo las prioridades que apoye la prestaciones de servicios del IPS</li></ul><p>GESTION DE INFORMACIÓN Y COMUNICACIÓN</p><ul><li>Disponer de tecnologías de información en desarrollo continuo de soporte, capacitación, ciber seguridad, requerida en los servicios brindados a usuarios internos y externos.</li></ul><p>GESTION DE TALENTO HUMANO:</p><ul><li>Fortalecer e integrar la gestión de personas trabajadores del IPS, con una adecuada politica de aprovechamieno de capacitades del talento humano en funcion a las necesidades intitucionales con miras ana mejor calidad de prestaciones de &nbsp;servicios &nbsp;</li></ul><p>GESTION ADMINISTRATIVA Y FINANCIERA</p><ul><li>Asegurar el uso eficiente de los recursos institucionales</li><li>Reducir la morosidad y evasión en el cobro de AOP</li><li>Aumentar la rentabilidad del jubilaciones y pensiones con diversificación y serguridad de inversiones</li><li>Establecer pautas entre instituciones del estado relacionadas al cumplimiento de las obligaciones patronales de aportar al seguro social con sus dependientes.</li></ul><p>GESTION DE INFRAESTRUCTURA Y MANTENIEMINTO</p><ul><li>Desarrollar y mantener la infrestructura física y los equipamientos, de manera que responda a las necesidades de salud, prestaciones economicas y administrativas actuales y futuras.&nbsp;</li></ul>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	9	\N	\N	7	8	\N	2023-10-18 22:35:19	2023-10-20 12:53:08	\N	\N	\N
9a674341-5cff-440a-86a8-0e58a147a8b7	Río Jejuí (Gerencia Administrativa y Financiera)	\N	\N	group	master	<p>Administrar el Seguro Social, <strong>otorgando</strong> prestaciones sanitarias y&nbsp; jubilaciones y pensiones de calidad, con talento humano calificado mediante una gestión eficiente.</p>	<p>Ser la Institución líder del seguro social eficiente y transparente que otorga acceso a prestaciones sanitarias y económicas garantizando la sostenibilidad del sistema</p>	<ol><li>Servicio: colaborar en forma permanente con el usuario interno y el asegurados</li><li>Transparencia: dar a conocimiento público la información relativa a su gestión, manejo de los recursos, criterio que sustentan sus decisiones y la conducta de sus funcionarios</li><li>Eficiencia: Lograr los objetivos trazados con calidad y buen manejo de los recursos institucionales</li><li>Solidaridad. apoyar con empatía los momentos de mayor vulnerabilidad de los usuarios internos y externos.</li><li>Honestidad: Hablar y Actuar con sinceridad mostrando respeto hacia los demás y conciencia de sí mismo.</li><li>Integridad: Rectitud y honradez en las actuaciones individuales, para lograr transparencia en los resultados propuestos.</li><li>Responsabilidad: Cumplir con los deberes y obligaciones para mejorar la seguridad en las prestaciones económicas, de salud y en la toma de decisiones..&nbsp;</li><li>Respeto: Considerar a las personas y sus necesidades, reconociendo su dignidad como seres humanos, sin distinción de ninguna naturaleza.</li></ol><p><strong>EJES ESTRATEGICO</strong>S</p><p>1.Fortalecimiento de la gestión de la Red integrada de Salud.</p><p>2.Sostenibilidad de las prestaciones económicas y sociales.</p><p>3.Innovación y optimización de la gestión administrativa.</p><p><strong>GRUPO JEJUI&nbsp;</strong>&nbsp;</p><p><strong>Eje Estratégico: 2-</strong></p><p><strong>GESTIÓN DE JUBILACIONES Y PENSIONES</strong>:</p><p>2.1 Brindar prestaciones económicas y sociales oportunas mediante una gestión transparente y sostenible.</p><p>2.2 Impulsar reformas legales orientadas la diversificación de las inversiones financieras e inmobiliarias.</p><p>2.3 Realizar acciones para tendientes a incrementar los ingresos</p><p><strong>Eje Estratégico: 3-</strong></p><p><strong>GESTIÓN ESTRATÉGICA :</strong></p><p>1.Fortalecer el direccionamiento estratégico institucional, para el cumplimiento de los fines del Seguro Social.</p><p>2.Promover una estructura flexible y ágil</p><p>3.Fortalecer y continuar los estudios actuariales de los fondos institucionales</p><p><strong>GESTIÓN ADMINISTRATIVA Y FINANCIERA:</strong></p><p>1.Asegurar el uso eficiente de los recursos institucionales para la sostenibilidad de los fondos.</p><p>2.Realizar acciones tendientes a maximizar el cobro de los Aportes Obrero Patronal</p><p>3.Implementar acciones tecnológicas que permitan la integración de subsistemas.</p><p>&nbsp;</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	13	\N	\N	41	42	\N	2023-10-19 02:36:46	2023-10-20 13:14:12	\N	\N	\N
9a82b34c-1a9f-4406-a59d-0ac4aa7e5839	<p>1-Disminuir la mortalidad materna</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	8	335	336	2023-11-06 11:47:12	2023-11-01 17:57:25	2023-11-06 11:47:12	9a81a818-2daa-4946-84c2-94c65f1ca599	\N	\N
9a81a20f-e93c-4ae9-9af7-cce8d252346e	<p>Eje III - Innovación tecnológica y optimización en la gestión estratégica y administrativa.</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	60	61	2023-11-01 05:14:14	2023-11-01 05:13:24	2023-11-01 05:14:14	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a824380-981d-4447-b552-5ba55a6177e5	<p>Redes Quirúrgicas establecidas por zona ()</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Cantidad de medicamentos e insumos de baja o sin rotación	Medicamentos rotos, vencidos y deteriorados	DEFINIR	\N	8	\N	313	314	2023-11-01 13:41:18	2023-11-01 12:44:49	2023-11-01 13:41:18	9a81a7ca-4ca2-474f-987e-f34815316acb	\N	\N
9a66f182-dc9e-401f-8f46-7cd31fab3f60	Río Pilcomayo	\N	\N	group	master	<p>Garantizar, oportuna y eficientemente, las prestaciones del seguro social, con calidad y calidez.</p>	<p>Ser la Institución que administra el seguro social, con amplia cobertura, garantizando la sostenibilidad del sistema, en base a la mejora continua en la gestión.</p>	<ul><li>Integridad&nbsp;</li><li>Transparencia</li><li>Responsabilidad</li><li>Respeto</li><li>Compromiso (Trabajo sobre &nbsp;logros alcanzados y hacia las metas propuestas, creo en lo que &nbsp;hago y realizo con las normativas establecidas)</li><li>Solidaridad</li><li>Eficiencia</li><li>Empatía</li><li>innovación (Fomento la creatividad y la adopción de nuevas ideas para mantenerme actualizado)</li><li>Comunicación ( Comunico en forma permanente y abierta con los &nbsp;usuarios internos y externos)</li></ul><p>EJES ESTRATEGICOS&nbsp;</p><ol><li>DESARROLLO Y MANTENIMIENTO DE SERVICIOS DE CALIDAD ACCESIBLES Y EFICIENTES PARA SATISFACER LAS NECESIDADES DE LOS ASEGURADOS</li><li>SOSTENIBILIDAD DEL FONDO COMUN DE JUBILACIONES Y PENSIONES</li><li><strong>INNOVACION TECNOLOGICA &nbsp;Y OPTIMIZACION EN LA GESTION ESTRATEGICA Y ADMINISTRATIVA A TRAVEZ DE LA IMPLEMENTACION DE LA GESTION POR PROCESOS</strong></li></ol><p>&nbsp;</p><p><strong>LECCIONES APRENDIDAS DEL VIDEO</strong></p><ol><li><strong>DETECCION OPORTUNA DE LOS RIESGOS</strong></li><li>PLANIFICACION ADECUADA</li><li><strong>TRABAJAR EN EQUIPO</strong></li><li><strong>COMUNICACIÓN&nbsp;</strong></li><li><strong>ASUMIR RESPONSABILIDADES</strong></li><li><strong>SENTIDO DE PERTENENCIA</strong></li><li>Fortalecimiento de la gestión de la Red integrada e integral de Salud.</li></ol><p>OBJETIVOS ESTRATEGICOS POR EJES</p><p>&nbsp;</p><p>3. Innovación tecnológica y optimización en la gestión estratégica y administrativa.</p><p>&nbsp;3.1Fortalecer el direccionamiento estratégico institucional, en cumplimiento de los fines del Seguro Social.</p><p>3.2 Promover una estructura flexible y ágil basada en la gestión por procesos</p><p>3.3 Innovar y fortalecer las tecnologías de información y comunicación, de manera que respondan a las necesidades de continuidad y desarrollo requeridas en los servicios brindados a los usuarios internos y externos, de forma eficiente, confiable y segura.</p><p>&nbsp;</p><p>&nbsp;</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	17	\N	\N	25	26	\N	2023-10-18 22:48:12	2023-10-20 13:26:40	\N	\N	\N
9a685ab2-8813-4e35-9dea-7267678ecc01	Río Montelindo (Gestión Médica)	\N	\N	group	master	<p>Dirigir y administrar el Seguro Social el seguro social descentralizado asegurando servicios de prestación sanitaria, jubilación y pensiones con talentos humanos comprometidos a través de una gestión eficaz y eficiente a favor de los asegurados</p>	<p>Ser una Institución líder en seguro social descentralizado que asegure servicios de prestación sanitaria jubilaciones y pensiones con calidad y eficiencia de una manera ágil y solvente apoyados por los valores institucionales con el compromiso y entrega de talentos humanos de una manera sostenible y sustentable a favor de los asegurados</p>	<p>Honestidad</p><p>Transparencia</p><p>Eficiencia</p><p>Eficacia</p><p>Etica</p><p>Solidaridad</p><p>Solvencia</p><p>&nbsp;</p><p>OE 1- Fortalecimiento y sostenibilidad de la gestión de red integrada de salud</p><ul><li>Fortalecer&nbsp; la red integral de salud a fin de garantizar la calidad y cobertura de los servicios sanitarios</li></ul><p>OE 2- Sostenibilidad del fondo jubilatorio y pensiones</p><ul><li>Impulsar y gestionar el cobro de la deuda del Estado en concepto de aporte a fin de brindar prestaciones económicas y sociales económicas oportunas mediante una gestión transparente y sostenible</li></ul><p>OE 3- Marco estratégico y administrativo innovado</p><ul><li>Promover y fortalecer un direccionamiento estratégico institucional apegado a los fines del seguro social descentralizado, bajo un marco de gestión y administración innovados</li></ul><p>&nbsp;</p><p>&nbsp;</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	26	\N	\N	49	50	\N	2023-10-19 15:38:09	2023-10-19 16:44:33	\N	\N	\N
9a824ade-659c-4f5f-ad08-f9b395b13773	<figure class="table"><table><tbody><tr><td>Desarrollo de la carrera administrativa dentro de la estructura organizacional</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	102	103	2023-11-01 13:35:06	2023-11-01 13:05:25	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a8221a1-68ef-4371-8734-4f2bd2283d7b	<p>1- Brindar prestaciones económicas oportunamente a la población asegurada.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	69	70	2023-11-01 13:44:06	2023-11-01 11:10:06	2023-11-01 13:44:06	9a81a711-d45c-46c9-8464-aa48d40e0d8d	\N	\N
9a8289ae-6e0b-42ef-a217-f11a47042a6a	<p>Llamados a licitación de producciones varias, muebles y enseres de uso convencional</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	21	\N	\N	\N	Llamados necesarios según infraestructura	Adjudicación de contratos	100% de manera bianual	\N	8	1	201	202	\N	2023-11-01 16:01:03	2023-11-06 12:06:20	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82aff9-041e-4ee3-b05d-3a8744ecfacc	<p>Optimizar la gestión administrativa de los procesos de compras públicas</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Porcentaje de cumplimiento actual del PAC	Plan Anual de Contrataciones	Aumentar cumplimiento de manera anual	\N	8	1	261	262	\N	2023-11-01 17:48:07	2023-11-06 12:07:29	9a82823b-4bf6-4589-8197-66b5cde73f62	\N	\N
9a66ed93-e21b-4765-9027-f9cda2fc8f4a	Río Montelindo (Gerencia de Salud)	\N	\N	group	master	<p>Otorgar a los asegurados del IPS, las prestaciones sanitarias, jubilaciones y pensiones, con calidad y calidez humana</p>	<p>Ser la Institución lider del Seguro Social, comprometida con una atención de calidad en Salud, ágil en la concesión de jubilaciones y pensiones, sostenible y solidaria a los asegurados</p>	<ul><li>Honestidad: Actuar con rectitud a partir de la razón.</li><li>Integridad: Rectitud y honradez en las actuaciones de la Institución.</li><li>Responsabilidad: Asumir los deberes y obligaciones necesarias para mejorar la seguridad en las prestaciones económicas y de salud</li><li>Respeto: Atender y escuchar a las personas y sus asuntos reconociendo su dignidad como seres humanos</li><li>Solidaridad: Garantía de protección a los asegurados.</li><li>Servicio: Colaboración permanente.&nbsp;</li><li>Transparencia: someter al conocimiento público la información necesaria de la gestión en todos los ámbitos</li><li>Eficiencia: lograr los objetivos trazados con estándares de calidad</li></ul><p>&nbsp;</p><p><strong>EJES</strong></p><ol><li>Estructurar una Red integrada e integral de servicios de salud</li><li>Garantizar la calidad y cobertura de los servicios de salud</li><li>Priorizar la prevención de enfermedades y la promoción de la salud</li></ol><p>&nbsp;</p><p>Objetivos</p><ul><li>Crear, implementar y fortalecer, redes integrales de servicios de salud</li><li>Humanizar la calidad de la atención, garantizando la cobertura de los servicios sanitarios</li><li>Fortalecer continuamente las acciones de educación, promoción, prevención y vigilancia sanitaria</li></ul><p>&nbsp;</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	11	\N	\N	11	12	\N	2023-10-18 22:37:12	2023-10-20 12:22:46	\N	\N	\N
9a82b659-a700-4e51-af77-dcac86607aa7	<p>Implementacion de la firma digital en los procesos internos institucionales</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Cantidad de procesos que cuentan con firma digital	Procesos con firma digital al año 2023	Mas del 50% de cobertura de procesos con firma digital	\N	8	1	143	144	\N	2023-11-01 18:05:57	2023-11-06 12:02:12	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a82b8d2-5fbb-4532-9ea7-83f98bcd9fe7	<p>Institucionalización del Plan Estratégico 2023-2028&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Cantidad de funcionarios participantes en talleres sobre el Plan Estratégico Institucional	Aprobación de la Resolución del Plan Estratégico Institucional 2023-2028	En promedio 80% de participación del funcionariado en los talleres referentes al Plan Estratégico Institucional	\N	8	1	221	222	\N	2023-11-01 18:12:52	2023-11-06 11:54:02	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a824ac0-4a0f-46bb-9e65-5000cef131ae	<figure class="table"><table><tbody><tr><td>Potenciar el sistema de bienestar del personal</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	100	101	2023-11-01 13:35:06	2023-11-01 13:05:05	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a828a87-967d-4485-be1e-c6505c15a4c9	<p>Implementar un Plan de cargos y carreras</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Plan de Cargos y Carreras implementado sobre total de cargos y carreras vigente	Cargos y Carreras vigentes con el anexo de personal	100% de los cargos y carreras implementadas en 3 años	\N	8	1	360	361	\N	2023-11-01 16:03:25	2023-11-06 12:00:12	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a8226db-e160-4a0d-907a-a4063f988905	<p>Provisión de medicamentos de uso en pacientes crónicos (hipertensos y diabéticos) para enfermedades prevalentes en toda la red:</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	125	126	2023-11-01 17:49:28	2023-11-01 11:24:43	2023-11-01 17:49:28	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a8246b2-c8ca-4cfe-9cb7-428d2646c0de	<p>Intervenir en Políticas de enfermedades crónicas no transmisibles (Diabetes Hipertensión, Hipercolesterolemia)</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Inversion en enfermedades prevenibles	Inversion en enfermedades prevenibles	Inversion en enfermedades prevenibles	\N	8	\N	315	316	2023-11-03 13:08:30	2023-11-01 12:53:45	2023-11-03 13:08:30	9a81a7ca-4ca2-474f-987e-f34815316acb	\N	\N
9a8286b0-a66a-4c12-9057-52aca54ca78a	<p>Llamado a licitación para servicios de seguridad y vigilancia privada</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	\N	\N	Cantidad necesaria a cubrir	Número de contratos a ser adjudicados	100% de manera bianual	\N	8	1	175	176	\N	2023-11-01 15:52:41	2023-11-06 12:04:25	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82807c-b239-4c07-9c19-7eed2ab97e1b	<p>Mantenimiento y reparación de flota de vehículos de manera semestral</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	7	\N	\N	\N	Cantidad de vehículos de la flota	Número de contratos adjudicados	100% semestral	\N	8	1	173	174	\N	2023-11-01 15:35:20	2023-11-06 12:04:19	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a8241bd-e964-4720-a118-546bae99db69	<p>Actualización y aprobación de Licencias ambientales&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	9	\N	\N	\N	% de Licencias actualizadas y aprobadas/ 74 Licencias	26 vigentes	100% para fines del año 2026	\N	8	1	177	178	\N	2023-11-01 12:39:53	2023-11-06 12:04:31	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82823b-4bf6-4589-8197-66b5cde73f62	<p>6- Asegurar el uso eficiente de los recursos institucionales.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	\N	\N	\N	\N	\N	\N	\N	8	1	260	267	\N	2023-11-01 15:40:13	2023-11-06 04:25:43	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a82a17f-0216-4601-a7c9-490ffa54d43f	<p>Disminuir la muerte materna</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Cantidad de muerte maternas registradas  sobre cantidad de embarazadas totales que consultan en el IPS	3 muertes maternas registradas en el año 2022	Disminuir las muertes materna en el IPS	\N	8	1	343	344	\N	2023-11-01 17:07:38	2023-11-06 11:46:43	9a81a818-2daa-4946-84c2-94c65f1ca599	\N	\N
9a81aa47-dbee-4a90-b8c1-5ad32d23e7cf	<p>Acción I</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	11	11	11	\N	8	\N	303	304	2023-11-01 11:40:07	2023-11-01 05:36:22	2023-11-01 11:40:07	9a81a79d-46ba-4d42-b7c9-3ddcac54037b	\N	\N
9a66ef18-aba4-4145-ba8b-e04324a51430	Río Piribebuy (Gerencia de Prestaciones Económicas)	\N	\N	group	master	<p>Misión del IPS: Dirigir y Administrar el Seguro Social, brindando cobertura ante los riesgos sociales, garantizando asistencia médica de calidad y protección económica oportuna, mediante una gestión eficiente para nuestros asegurados.</p>	<p>Ser una Institución&nbsp; comprometida, eficiente, transparente y cercana a sus asegurados, que otorga atención médica de calidad y protección económica oportuna, garantizando la sostenibilidad del sistema</p>	<ol><li>Honestidad:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Actúo con honradez, verdad y justicia.</i></li><li>Integridad: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Soy coherente entre lo que hago y digo.</i></li><li>Responsabilidad: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Asumo las consecuencias de las acciones/omisiones.</i></li><li>Respeto: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<i>Actúo con rectitud y tolerancia.</i></li><li>Solidaridad: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Ayudo y sirvo a los demás.</i></li><li>Servicio: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;<i>Tengo actitud de colaboración permanente.</i></li><li>Transparencia: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Hago pública la gestión realizada.</i></li><li>Eficiencia: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Logro los objetivos con calidad y mejor utilización de los recursos.</i></li><li><i>Empatía:&nbsp; &nbsp;Ponerse en el lugar del otro.</i></li></ol><p>&nbsp;</p><p><i><strong>EJE ESTRATÉGICO:&nbsp;</strong></i></p><p><i><strong>2- Sostenibilidad del Fondo Común de Jubilaciones y Pensiones.</strong></i></p><p>2.1.&nbsp; Conceder prestaciones económicas, en forma eficiente y oportuna a la población asegurada.</p><p>2.2. Impulsar reformas legales orientadas a: 1. Financiar suficientemente todas &nbsp;las prestaciones económicas otorgadas. 2. Otorgar prestaciones coherentes entre el esfuerzo contributivo de los aportantes y los beneficios recibidos.</p><p>2.3.&nbsp; Maximizar el rendimiento y diversificar las inversiones.</p><p>2.4 &nbsp;Ampliar la protección a la población objetivo.</p><p>Eje 3 Innovación tecnológica y optimizan en la gestión estratégica y administrativa.</p><p>GESTIÓN DE PRESTACIONES ECONÓMICAS&nbsp;</p><ol><li>Agilizar la gestión &nbsp;para la concesión oportuna de las prestaciones económicas.</li><li>Implementar la accesibilidad digital de toda la gestión para la concesión de los beneficios.</li></ol><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	14	\N	\N	19	20	\N	2023-10-18 22:41:27	2023-10-20 13:03:56	\N	\N	\N
9a66ee6c-8d0e-4d88-a09e-f184f2e79d3a	Río Jejuí (Gerencia Administrativa y Financiera)	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	13	\N	\N	15	16	2023-10-19 02:10:42	2023-10-18 22:39:34	2023-10-19 02:10:42	\N	\N	\N
9a66f36a-7f9b-4fc9-81f0-1b424bd27704	Río Jejuí	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	22	\N	\N	39	40	2023-10-19 02:37:46	2023-10-18 22:53:31	2023-10-19 02:37:46	\N	\N	\N
9a66f13c-c671-4c40-a1b1-3069309364b3	Río Jejuí	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	22	\N	\N	23	24	2023-10-19 02:37:50	2023-10-18 22:47:26	2023-10-19 02:37:50	\N	\N	\N
9a6743bc-792d-4c14-9558-030201f8f8e0	Río Jejuí	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	22	\N	\N	43	44	\N	2023-10-19 02:38:07	2023-10-19 02:38:07	\N	\N	\N
9a8248e6-d9c0-4ae7-9143-2d6dee81ab49	<p>Incorporar el mantenimiento, tasación y custodia de los bienes de renta.</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	13	\N	\N	\N	Número de inmuebles de renta mantenidos, tasados y custodiados sobre el total de inmuebles de renta	890 inmuebles de renta sin mantenimiento, tasación y custodia	20% de inmuebles de renta mantenidos, tasados y custodiados	\N	8	1	185	186	\N	2023-11-01 12:59:55	2023-11-06 12:05:02	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a66ec39-3e36-4fe8-920b-87743c9f09f4	Río Apa (Presidencia)	\N	\N	group	master	<p>Mision. dirigir y administrar el seguro social brindando servicios de prestaciones sanitarias de calidad y con calidez, otorgando pensiones y jubilaciones sostenibles a nuestros asegurados, atraves de una gestion eficiente.</p>	<p>Visión. ser la institucion lider del seguro social, comprometida con una atencion de calidad &nbsp;en salud, agil en la concesion de pensiones y jubilaciones sostenibles y solidaria a los asegurados.</p>	<ul><li>Integridad: &nbsp;actuar de una persona honesta y respetuosa.</li><li>Solidaridad: actitud de colaboracion &nbsp;y servicio.</li><li>Responsabilidad. cumplir con los compromisos asumidos &nbsp;</li><li>Transparencia. &nbsp;expresar la verdad siendo objetivos.&nbsp;</li><li>Eficiencia. capacidad para cumplir adecuadamente la funcion</li></ul><p>&nbsp;</p><ul><li>Ejes Estrategicos</li><li>Innovación y mejora en la gestión estrategica y administrativa.</li></ul><p>Gestion Estratrgica.</p><p>1 Fortalecer el direccionamiento estrategico institucional basados en la planificación en la gestión por procesos y el control, para el cumplimiento de los fines del seguro social.</p><p>Gestion del Talento Humano</p><p>1 Fortalecer las politicas del talento humano vigentes, a fin de garantizar la eficiencia, la eficacia en la gestión y desarrollo de los talentos humanos, asi como las competencias y habilidades para el cumpliemiento de los planes institucionales.</p><p>Ideas</p><p>Nadie asumio su responsabilidad.</p><p>derivaron el problema a otras áreas&nbsp;</p><p>No trabajaron en equipo desde el inicio</p><p>No se tuvo en cuenta la opinión de una persona de menor rango</p><p>Esperaron a que el problema &nbsp;se agrave para tomar una decision en equipo</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	7	\N	\N	3	4	\N	2023-10-18 22:33:25	2023-10-20 12:31:34	\N	\N	\N
9a66f2d4-fb81-4bcf-a218-46ba5739aa37	Río Ypané	\N	\N	group	master	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	21	\N	\N	35	36	\N	2023-10-18 22:51:53	2023-10-19 14:00:20	\N	\N	\N
9a8275f9-8aa9-410c-b5fd-df1759355e65	<p>Potenciar el sistema de bienestar del personal</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	Número de prestaciones de bienestar del personal a cargo de la Gerencia de Salud sobre total de prestaciones al personal	Total de prestaciones de bienestar del personal a cargo de la Gerencia de Salud	Integrar las prestaciones de bienestar del personal en una sola dependencia	\N	8	1	362	363	\N	2023-11-01 15:05:57	2023-11-06 12:00:23	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a82b307-bd53-4382-8189-c4b89c609743	<p>Ampliar el acceso digital de los resultados de laboratorios, imágenes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	\N	\N	\N	\N	8	1	279	280	2023-11-06 11:44:15	2023-11-01 17:56:40	2023-11-06 11:44:15	9a82383c-d5d0-484f-943d-51b34d4dea8e	\N	\N
9a82bbb3-5a52-413d-8a93-c3c9a24137b3	<p>Proceso de Inducción al personal que se incorpora a la Institución</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Número de personas que realizaron inducción al ser vinculados a la Institución en relación al total de vinculados.	Políticas del Talento Humano vigentes	Llegar al 90% de los nuevos vinculados por año.	\N	8	1	358	359	\N	2023-11-01 18:20:55	2023-11-06 12:00:02	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a81a79d-46ba-4d42-b7c9-3ddcac54037b	<p>1- Estructurar la &nbsp;Red Integrada e Integral de Servicios Salud, con enfoque preventivo, incluyendo la promoción de la salud.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	\N	\N	\N	\N	8	1	302	311	\N	2023-11-01 05:28:55	2023-11-06 11:50:52	9a81a6f8-8f40-41d4-bfe1-8b73e5ec3ff4	\N	\N
9a81a96d-d82e-4398-8b6c-9f8e7e9a177a	<p>2- Lograr un sistema de abastecimiento de bienes y servicios oportuno y eficiente.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	\N	\N	\N	\N	8	1	250	259	\N	2023-11-01 05:34:00	2023-11-06 04:25:11	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a66edf3-8b29-49cf-98b6-e7b2e9c2ce65	Río Ypané (Gerencia de Salud)	\N	\N	group	master	<p>Decidimos la propuesta N° 1</p>	<p>La propuesta N° 1 Agregamos lider, solidario y con calidad, quedaría así: Ser una Institución líder del seguro social, eficiente, solidario y transparente, que otorga el acceso con calidad a prestaciones sanitarias y económicas, garantizando la sostenibilidad del sistema.</p>	<p>Sugerencia del Grupo es que se asiente con el verbo de actuar antes que agregar el significado. Ejemplo: En vez de ACTUAR debe de decir ACTUANDO, ASUMIENDO en vez de ASUMIR…</p><p>&nbsp;</p><p><strong>Ejes Estratégicos&nbsp;</strong></p><p><strong>1 Fortalecimiento de la descentralización y la gestión en red de la salud.</strong></p><p><strong>2 Sostenibilidad del Fondo Común de Jubilaciones y Pensiones.</strong></p><p><strong>3 Innovación y mejora en la gestión estratégica y administrativa.</strong></p><p>OBJETIVOS PROPUESTOS POR EJES</p><p><strong>Eje Estratégico: 1</strong></p><p>&nbsp;</p><p>1 – Estructurar y garantizar una red integral de servicios de salud con enfoques preventivos y fortaleciendo las prestaciones a nivel país.</p><p>&nbsp;</p><p>2 – Impulsar la descentralización de los Servicios de Salud.</p><p>&nbsp;</p>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	12	\N	\N	13	14	\N	2023-10-18 22:38:15	2023-10-20 12:35:52	\N	\N	\N
9a826a19-ded3-4456-86f3-513802cafe41	<p>Mantenimiento anual, preventivo y correctivo, de plantas generadoras de oxígeno medicinal</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	10	\N	\N	\N	Cantidad de plantas generadoras de oxígeno medicinal de la Institución	Número de contratos adjudicados	100% anual	\N	8	1	179	180	\N	2023-11-01 14:32:45	2023-11-06 12:04:38	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a8249ba-2c09-45ff-9c42-d634d31cd885	<p>Acompañamiento al Convenio con el MTESS, a través del CEDESS, con los 3 Programas de Promoción de la Seguridad Social mediante Capacitación Virtual, Dirigido a trabajadores, estudiantes y futuros Jubilados, (Tarea conjunta con la GAF-DAOP, tarea 52 y GPE-DAJ )&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Número de inscriptos para la participación del Programa de Promoción de la Seguridad Social	no se cuenta con la línea	Llegar a por lo menos 500 personas capacitadas	\N	8	1	223	224	\N	2023-11-01 13:02:13	2023-11-06 11:54:26	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a810540-d841-4dec-b735-d06ee2aaf6d0	<p>Eje I - Fortalecimiento de la gestión de la Red integrada e integral de Salud.</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	52	53	2023-11-01 02:54:38	2023-10-31 21:54:55	2023-11-01 02:54:38	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a829faf-caef-46ff-ac4f-a012acab589a	<p>Ampliar la cobertura de la red del servicio de telemedicina</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Número de establecimientos que cuentan con cobertura de telemedicina	28 establecimientos cuentan con el servicio de telemedicina actualmente	ampliar a 18 establecimientos mas que cuenten con el servicio de telemedicina en 5 años	\N	8	1	283	284	\N	2023-11-01 17:02:35	2023-11-06 11:42:36	9a82383c-d5d0-484f-943d-51b34d4dea8e	\N	\N
9a81a8d6-8858-4a7b-aedf-426208eaa0ad	<p>2- Maximizar los ingresos financieros e inmobiliarios del fondo.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	\N	\N	\N	\N	8	1	71	72	\N	2023-11-01 05:32:20	2023-11-06 11:52:48	9a81a711-d45c-46c9-8464-aa48d40e0d8d	\N	\N
9a822c39-bc4d-4667-9887-3ed0edf59b2f	<figure class="table"><table><tbody><tr><td>Provisión de medicamentos de uso en pacientes crónicos (hipertensos y diabéticos) para enfermedades prevalentes en toda la red:</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	127	128	2023-11-01 18:11:47	2023-11-01 11:39:44	2023-11-01 18:11:47	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a822f3a-774e-4cd0-b8b2-9c0e872259ae	<p>asdf</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	131	132	2023-11-01 12:07:29	2023-11-01 11:48:07	2023-11-01 12:07:29	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a81a998-56b1-4e9a-870c-85cd4941211d	<p>4- Innovar las tecnologías de información, comunicación y ciber-seguridad.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	\N	\N	\N	\N	8	1	124	159	\N	2023-11-01 05:34:27	2023-11-06 04:23:15	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a82697a-2ceb-46ff-82b9-9aacdfaff287	<p>3- Mejorar las políticas del talento humano vigentes.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	\N	\N	\N	\N	8	1	121	376	\N	2023-11-01 14:31:00	2023-11-06 04:23:29	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a824c15-e082-405b-98a3-e9ae7126a70c	<p>Capsulas de información, Generación de Podcast</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	Número de capsulas de información realizadas sobre total de informaciones realizadas	0 (No se contaba con el producto)	Participación de las capsulas de información del 50% del total de informaciones generadas	\N	8	1	147	148	\N	2023-11-01 13:08:49	2023-11-06 12:02:29	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a82a53a-7873-4e50-9ab4-ecc81f6e00dc	<p>Dotación de ingenieros y técnicos biomédicos, electrónica y electromecánica</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	12	\N	\N	\N	Cantidad necesaria de ingenieros y técnicos	Aumento de talento humano capacitado	15 por año	\N	8	1	183	184	\N	2023-11-01 17:18:04	2023-11-06 12:04:51	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a81055e-70ce-4c76-91a6-53ea32c69a6e	<p>Eje II - Sostenibilidad del Fondo Común de Jubilaciones y Pensiones.</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	54	55	2023-11-01 02:54:39	2023-10-31 21:55:15	2023-11-01 02:54:39	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a829d2c-82b3-4b30-a364-2909f9a11a8f	<p>1- Confección, capacitación y adherencia a los protocolos de atención</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Nro de protocolos de atención médica	1- Confección, capacitación y adherencia a los protocolos de atención	1- Confección, capacitación y adherencia a los protocolos de atención	\N	8	8	291	292	2023-11-01 17:50:45	2023-11-01 16:55:33	2023-11-01 17:50:45	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	\N	\N
9a82bd88-289d-4466-8823-aebb09f1a313	<p>Fortalecer la infraestructura de hardware y software</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Porcentaje de Cumplimiento de los controles criticos sobre ciberseguridad sobre las políticas del MITIC	5 %  de Cumplimiento de las Políticas del MITIC	Cumplir el 80% la política en un plazo de 5 años	\N	8	1	145	146	\N	2023-11-01 18:26:02	2023-11-06 12:02:18	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a828ac6-19bd-41dd-91e3-a508a0c0b507	<p>Medir el tiempo de gestión de los procesos de subsidios y junta medica</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Tiempo de gestión de los procesos de subsidios y junta medica	No se cuenta con datos	Registro de los tiempos de gestión de los procesos de subsidios y Junta Médica medido	\N	8	1	321	322	\N	2023-11-01 16:04:06	2023-11-06 11:40:12	9a81a7ca-4ca2-474f-987e-f34815316acb	\N	\N
9a81a7f1-a913-4fa0-8f09-27560468beda	<p>3- Promover la articulación intersectorial e interinstitucional para la optimización de los recursos, la docencia e investigación.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	\N	\N	\N	\N	8	1	330	333	\N	2023-11-01 05:29:50	2023-11-06 11:51:12	9a81a6f8-8f40-41d4-bfe1-8b73e5ec3ff4	\N	\N
9a824aac-8b3d-4ed0-bf8b-0463f557e521	<figure class="table"><table><tbody><tr><td>Planificar la disponibilidad de rubros para los ajustes de estructura organizacional en el anexo de personal con su línea presupuestaria</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	98	99	2023-11-01 13:35:06	2023-11-01 13:04:52	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a825440-948b-4380-9989-7a681411e8b8	<p><strong>Aplicación de &nbsp;la tecnología en la gestión del talento humano para la descentralización de los procesos</strong></p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Cantidad de procesos descentralizados	\N	\N	\N	8	\N	112	113	2023-11-01 13:35:06	2023-11-01 13:31:39	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a81a711-d45c-46c9-8464-aa48d40e0d8d	<p>Eje II - Sostenibilidad del Fondo Común de Jubilaciones y Pensiones. (Maureen Eisenhut)</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	\N	\N	\N	\N	8	1	68	93	\N	2023-11-01 05:27:24	2023-11-05 20:29:59	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a826b3b-4268-4d1e-9ce2-766567904bb8	<p>Mantenimiento anual, preventivo y correctivo, de Plantas de tratamiento de Efluentes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	18	\N	\N	\N	Cantidad de Plantas de tratamiento de Efluentes	Número de contratos adjudicados al 2023	100% anual	\N	8	1	195	196	\N	2023-11-01 14:35:54	2023-11-06 12:05:55	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a8264d7-6ac1-47c1-9c81-9e8542c41741	<p>Mantenimiento anual, preventivo y correctivo, de equipos electromecánicos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	17	\N	\N	\N	Número de contratos para los equipos electromecánicos	Número de contratos vigentes al 2023	100% anual	\N	8	1	193	194	\N	2023-11-01 14:18:02	2023-11-06 12:05:44	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82499b-fbc0-4c1d-be47-cbeb748913c6	<p>Análisis de documentos internos, organigramas,&nbsp; &nbsp;y procedimientos existentes en el IPS relacionados con el Área de Salud.</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	\N	\N	\N	PENDIENTE	PENDIENTE	PENDIENTE	\N	8	1	231	232	\N	2023-11-01 13:01:53	2023-11-06 11:55:41	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a824bff-3ab6-409b-a116-f5e6392387c5	<p>Promoción de las Transmisión online de Sesiones del Consejo en el portal web institucional https://www.ips.gov.py (YouTube, Instagram)</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	5	\N	\N	\N	Número de sesiones transmitidas sobre total de sesiones realizadas	No se transmitían las sesiones	100% de sesiones transmitidas	\N	8	1	149	150	\N	2023-11-01 13:08:34	2023-11-06 12:02:42	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a82b733-9d65-418f-8127-d6dff6d02e62	<p>Gestionar la certificación de calidad en los proyectos de infraestructura física y mantenimientos edilicios de la Institución</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	16	\N	\N	\N	PENDIENTE	PENDIENTE	PENDIENTE	\N	8	1	191	192	\N	2023-11-01 18:08:20	2023-11-06 12:05:37	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82a243-56e3-4006-b983-0b11d4d2d988	<p>Ampliar la Red Integral de Atención a la Diabetes (RIAD) (Ampliación de núcleos de atención en la Red de IPS)</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Número de personas con diabetes que tienen acceso a la RIAD, Número de núcleos de atención de la RIAD en la Red del IPS	72.000 pacientes tienen acceso a la RIAD	Aumentar al menos 60 mil personas con diabetes tenga acceso a la RIAD y a los núcleos de atención en toda la Red del IPS	\N	8	1	347	348	\N	2023-11-01 17:09:47	2023-11-06 11:47:56	9a81a818-2daa-4946-84c2-94c65f1ca599	\N	\N
9a82a09b-8e8b-4cda-9dab-95a94adcb767	<p>Universalizar las visualizaciones del expediente medico digital para servicios de salud en todos los establecimientos del IPS</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Numero de  establecimientos que cuentan con  acceso al expediente médico interno digital para el registro	No se encuentra implementado la visualización del expediente interno digital en todos los establecimientos del IPS	50% de los establecimientos cuenten con la visualización del expediente médico interno digital para servicios de salud	\N	8	1	281	282	\N	2023-11-01 17:05:09	2023-11-06 11:43:07	9a82383c-d5d0-484f-943d-51b34d4dea8e	\N	\N
9a82415f-49b5-4c2f-a722-0a0b55a3c58a	<p>Elaboración de proyectos &nbsp;de reformas legales para el debido financiamiento de prestaciones económicas&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Porcentaje de avance en la elaboración proyectos  de reformas legales para el debido financiamiento de prestaciones económicas	Se cuenta con Leyes Vigentes	Proyectos de reformas legales para el financiamiento de las prestaciones económicas presentadas y aprobadas por el Consejo de Administración	\N	8	\N	80	81	2023-11-01 14:32:34	2023-11-01 12:38:51	2023-11-01 14:32:34	9a81a8b3-a6da-4092-9a38-5166f6689b36	\N	\N
9a8269c3-236c-4b4c-a467-664206f0cdcd	<figure class="table"><table><tbody><tr><td><strong>Proceso de Inducción al personal que se incorpora a la Institución</strong></td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Cantidad de personas que realizaron inducción al ser vinculados a la Institución sobre el total de funcionarios incorporados	\N	\N	\N	8	\N	354	355	2023-11-01 15:01:04	2023-11-01 14:31:48	2023-11-01 15:01:04	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a823b4c-3cde-4080-a357-90e338c25f90	<p>Reducir los tiempos de concesión de Beneficios al Jubilado (retiro por vejez,derecho habientes y jubilación por invalides)</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Tiempo promedio de procesamiento de beneficios	Tiempo actual de concesión (Tiempo actual Promedio: Muerte   ( 260 días),    Vejez ( JO-33 días / JP- 69 días), Invalidez   (53 días))	Disminuir al 50 % los días para concesión de Beneficios de Largo Plazo mediante agilización de procesos internos	\N	8	1	82	83	\N	2023-11-01 12:21:52	2023-11-06 11:51:39	9a81a8b3-a6da-4092-9a38-5166f6689b36	\N	\N
9a82b307-56e9-48ec-97f4-d2bdd354138e	<p>Ampliar el acceso digital de los resultados de laboratorios, imágenes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	\N	\N	\N	\N	8	1	277	278	2023-11-06 11:44:11	2023-11-01 17:56:40	2023-11-06 11:44:11	9a82383c-d5d0-484f-943d-51b34d4dea8e	\N	\N
9a824c6d-c2e8-4945-a3c0-f0c57d7bbfb5	<p>Utilización del espacio en la nube para la proveer y compartir informaciones a organismos de control (share point) y validación de firma digital &nbsp;con el acuerdo de estos organismos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	\N	\N	\N	Número de publicaciones subidas al share point sobre total de publicaciones requeridas por los organismos de control	No se cuenta con el share point	Uso del 100% del share point en un año a efecto de reducir tiempo y papelería	\N	8	1	151	152	\N	2023-11-01 13:09:46	2023-11-06 12:02:52	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a823130-a372-42b4-a69f-b7c72612e3c9	<p>Implementar mecanismos de trabajo enfocados en la docencia e investigación&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Cantidad de establecimientos de salud que realizan la docencia e investigación	2 (1 Capital y 1 Interior)	Aumentar un establecimiento de salud por año que realiza la docencia e investigación	\N	8	1	331	332	\N	2023-11-01 11:53:36	2023-11-06 11:40:42	9a81a7f1-a913-4fa0-8f09-27560468beda	\N	\N
9a824af5-dd59-4ca1-b79e-bf58521d9454	<figure class="table"><table><tbody><tr><td>Implementar la inducción a los nuevos funcionarios para asumir nuevas responsabilidades</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	104	105	2023-11-01 13:35:06	2023-11-01 13:05:40	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a82566b-a2d4-4d6b-b871-5c87a9df6d83	<p>Optimizar los procesos de inclusión, exclusión y modificaciones técnicas en el Vademecum Institucional de medicamentos y Cuadro Básico de disposiciones medicas&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	5	\N	\N	\N	Vademecum Institucional de medicamentos y Cuadro Básico de disposiciones medicas actualizado (Porcentaje)	Cantidad actual de medicamentos e insumos de baja o sin rotación u obsoletos	1 (uno) Actualizar el Vademecum Institucional de medicamentos y Cuadro Básico de disposiciones medicas actualizado y aprobado	\N	8	11	327	328	\N	2023-11-01 13:37:43	2023-11-06 12:24:15	9a81a7ca-4ca2-474f-987e-f34815316acb	\N	\N
9a82b2a2-7bba-4966-9b96-e24285755137	<p>Actualizar y socializar los planes operativos según criterios epidemiologicos&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	numero de planes operativos existentes	planes de acción existentes	100% de los planes operativos actualizados	\N	8	1	299	300	\N	2023-11-01 17:55:34	2023-11-06 11:41:48	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	\N	\N
9a82495e-520d-4e38-acf6-c5f92567b37e	<p>Conformación de grupos de trabajo para acciones interinstitucionales: Acompañamiento para la elaboración del Convenio con la DIBEN</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	7	\N	\N	\N	Implementación del convenio	No se cuenta con contrato de convenio	Firma e Implementación del convenio con la DIBEN	\N	8	1	233	234	\N	2023-11-01 13:01:13	2023-11-06 11:55:57	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a82a1e2-fafb-4765-9921-f60c8b2b2a6f	<p>Asegurar la cobertura de vacunación de los niños y adolescentes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Numero de niños y niñas menores de 11 años con esquema completo para la edad	Cantidad de niños y niñas menores de 11 años con esquema completo para la edad	100% de los niños y niñas que acudan al consultorio, reciban sus vacunas acorde a la edad	\N	8	1	349	350	\N	2023-11-01 17:08:44	2023-11-06 11:48:18	9a81a818-2daa-4946-84c2-94c65f1ca599	\N	\N
9a82b38e-a7cc-41b9-88b7-cf4610fefcd1	<p>4- Realizar campañas de diagnostico y seguimiento a pacientes Hipertensos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	8	341	342	2023-11-06 11:48:59	2023-11-01 17:58:08	2023-11-06 11:48:59	9a81a818-2daa-4946-84c2-94c65f1ca599	\N	\N
9a824162-ecb2-4dc4-aa5c-9a796f6d2194	<p>Actualizar el VADEMECUM y el Cuadro Básico</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Cantidad de medicamentos e insumos de baja o sin rotación u obsoletos	Medicamentos e insumos de baja o sin rotación u obsoletos	Vademecum y el Cuadro Basico actualizado	\N	8	\N	305	306	2023-11-01 14:44:34	2023-11-01 12:38:54	2023-11-01 14:44:34	9a81a79d-46ba-4d42-b7c9-3ddcac54037b	\N	\N
9a8247d0-5930-4d4a-ade2-81a505f8b617	<p>Elaborar proyectos &nbsp;de reformas legales para el debido financiamiento de prestaciones económicas&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	Número de proyectos de leyes legales para el debido financiamiento de prestaciones económicas presentadas	Carta Orgánica vigente del IPS para la concesión de prestaciones económicas	Presentación de 5 proyectos de reformas legales para el financiamiento de las prestaciones económicas para aprobación por el Consejo de Administración	\N	8	1	74	75	\N	2023-11-01 12:56:52	2023-11-06 11:53:50	9a81a8f5-b443-4018-bef2-415be205dcb2	\N	\N
9a824c3f-98e1-484e-8ea1-6a30b897d2ba	<p>Puesta en marcha del Tablero Control Gerencial del IPS:</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	7	\N	\N	\N	Porcentaje de Implementación del  del tablero de control a la presidencia-consejeros y gerencias	no se cuenta con el tablero	Implementación del  tablero de control antes de fin de año de  2023	\N	8	1	153	154	\N	2023-11-01 13:09:16	2023-11-06 12:03:04	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a8248cf-01f2-4ff2-8cba-ad3489d1d5b3	<p>Creación de nuevas plazas de estacionamiento y ordenamiento del acceso y salida vehicular del HEQ Ingavi:</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	19	\N	\N	\N	Portón de salida sobre santa carolina habilitado	estacionamiento con 2 accesos de entrada y salida unico	Habilitar un nuevo portón de salida, usando el actual como entrada	\N	8	1	197	198	\N	2023-11-01 12:59:39	2023-11-06 12:06:08	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82b4e8-8687-4baf-84a5-0c99273ac6a7	<p>Ampliar espacios fisicos para archivos&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	20	\N	\N	\N	Aumento del espacio físico sobre el espacio físico actual en m2.	No se tiene línea (relevar datos)	Aumentar 50% los espacios disponibles para archivo	\N	8	1	199	200	\N	2023-11-01 18:01:55	2023-11-06 12:06:15	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82bc69-6104-42a0-b0ab-8ebaeb943980	<p>Potenciar la aplicación de la tecnología en la gestión del talento humano para la descentralización de los procesos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	5	\N	\N	\N	Cantidad de procesos descentralizados en relación a la totalidad de los procesos utilizados	Cantidad de procesos descentralizados al año 2023	Descentralización en promedio de 3 procesos por año	\N	8	1	364	365	\N	2023-11-01 18:22:54	2023-11-06 12:00:31	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a81a6f8-8f40-41d4-bfe1-8b73e5ec3ff4	<p>Eje I - Fortalecimiento de la gestión de la Red integrada e integral de Salud. (Javier - Ricardo del Grupo A / Marta Marín del Grupo B)&nbsp;</p>	\N	\N	corporative	axi	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	\N	\N	\N	\N	8	1	269	352	\N	2023-11-01 05:27:07	2023-11-05 21:11:32	9a809a7c-5b47-4638-84ce-66e75f3f017d	\N	\N
9a829ee3-07ca-42f2-a3c2-9e72fa98d9fb	<p>1- Ampliar la cobertura de la red del servicio de telemedicina</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Ampliar la cobertura de la red del servicio de telemedicina	Ampliar la cobertura de la red del servicio de telemedicina	Ampliar la cobertura de la red del servicio de telemedicina	\N	8	8	289	290	2023-11-01 17:50:48	2023-11-01 17:00:21	2023-11-01 17:50:48	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	\N	\N
9a824941-dfcc-4daa-ae9c-fda346b47903	<p>Conformación de grupos de trabajo para acciones interinstitucionales: Coordinación de la Elaboración del Plan Estratégico Institucional (PEI)&nbsp; 2023-2028:</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	\N	\N	Porcentaje de avance de elaboración del Plan Estratégico Institucional PEI 2023-2028	no se contaba con plan	Cumplimiento del 100% de las metas	\N	8	1	235	236	\N	2023-11-01 13:00:54	2023-11-06 11:56:11	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a826946-ca49-42d5-966d-30f627496d0c	<p>3. Mejorar las Políticas de Talento Humano Vigentes</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	122	123	2023-11-01 14:59:08	2023-11-01 14:30:26	2023-11-01 14:59:08	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a81a7ca-4ca2-474f-987e-f34815316acb	<p>2- Garantizar la sostenibilidad del sistema de salud para la cobertura de servicios con calidad de forma oportuna, eficiente y humanizada.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	\N	\N	\N	\N	8	1	312	329	\N	2023-11-01 05:29:25	2023-11-06 11:51:03	9a81a6f8-8f40-41d4-bfe1-8b73e5ec3ff4	\N	\N
9a82a5b3-e470-43b0-8e64-5dda2022dad3	<p>Llamados a licitación para mantenimiento de equipos biomédicos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	23	\N	\N	\N	Existencias de equipos biomédicos	Adjudicación de contratos	Bianual	\N	8	1	205	206	\N	2023-11-01 17:19:24	2023-11-06 12:06:35	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a81a955-fe95-49ea-b552-040c74abba5e	<p>1- Fortalecer el direccionamiento estratégico institucional basados en la planificación, la gestión por procesos y el ambiente de control interno.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	\N	\N	\N	\N	8	1	220	249	\N	2023-11-01 05:33:44	2023-11-06 04:25:02	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a82a4d7-19ef-433f-b244-5090ddb60326	<p>Realizar campañas de diagnostico y seguimiento a pacientes Hipertensos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	Cantidad de personas hipertensas que han sido diagnosticadas y reducido su riesgo de complicaciones a través del seguimiento	No contamos con registros (completar con la cantidad de pacientes hipertenos registrados en el IPS en el año 2022)	Llegar a 10.000 personas para diagnosticar hipertensión y que al menos 5.000 personas hipertensas reciban seguimiento para evitar complicaciones	\N	8	1	345	346	\N	2023-11-01 17:16:59	2023-11-06 11:47:27	9a81a818-2daa-4946-84c2-94c65f1ca599	\N	\N
9a828346-6294-49af-a9a6-11abb8ecc616	<p>Remate de flota de vehículos obsoletos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	22	\N	\N	\N	Cantidad de vehículos obsoletos	Procedimientos de remate	50% en un periodo de 2 años y medio	\N	8	1	203	204	\N	2023-11-01 15:43:08	2023-11-06 12:06:27	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a8248b4-b89c-4e9a-83bb-c81480b7ffd1	<p>Promover acciones de prevención para el adulto mayor entre ellos el CREAM.</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	24	\N	\N	\N	Número de actividades de prevención realizadas sobre total actividades previstas	Cream inhabiiltado	5 actividades de prevención al adulto mayor	\N	8	1	207	208	\N	2023-11-01 12:59:22	2023-11-06 12:06:47	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a82a430-b3e4-458f-bc4e-861af5617039	<p>Aumento de la cobertura de telemedicina</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	25	\N	\N	\N	Cobertura actual	Capacidad de aumento	5% anual	\N	8	1	209	210	\N	2023-11-01 17:15:10	2023-11-06 12:06:54	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a823568-2064-4bc2-8f76-c34047838d27	<p>rttttt</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	135	136	2023-11-01 12:06:41	2023-11-01 12:05:24	2023-11-01 12:06:41	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a828284-4bd8-499a-81c2-4be50791b713	<p>Optimizar la concesión de beneficios, a través de la revisión y modificación de reglamentos vigentes</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Porcentaje de avance del  nuevo reglamento  para la concesión de beneficios	Se cuenta con reglamentos vigentes aprobados	Reglamento para la concesión de beneficios modificado y aprobado	\N	8	1	86	87	\N	2023-11-01 15:41:01	2023-11-06 11:51:52	9a81a8b3-a6da-4092-9a38-5166f6689b36	\N	\N
9a8284e5-c7bc-4d96-a258-ac30782e7c61	<p>Automatizar los proceso de pensión a derecho habiente en caso de fallecimiento del jubilado&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Porcentaje de avance en la automatización del proceso de pensión a derecho habiente en caso de fallecimiento del jubilado	Se cuenta con 2 procesos automatizados Jubilación Ordinaria y Proporcional	Proceso de pensión a derecho habiente en caso de fallecimiento del jubilado automatizado en un 100%	\N	8	1	88	89	\N	2023-11-01 15:47:40	2023-11-06 11:52:08	9a81a8b3-a6da-4092-9a38-5166f6689b36	\N	\N
9a81a983-a84a-4d6b-9dd2-e6008278dd73	<p>3.Mejorar las políticas del talento humano vigentes.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	95	118	2023-11-01 13:35:06	2023-11-01 05:34:14	2023-11-01 13:35:06	9a81a733-7d22-4ae0-becf-fa1ec7fecf31	\N	\N
9a8244ba-b537-418c-bda3-1cccdd1c3b37	<p>Abordaje integral de enfermedades catastróficas&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	6	\N	\N	\N	% del gasto en pacientes con enfermedades catastróficas	Gasto Actual en pacientes con enfermedades catastróficas	PENDIENTE	\N	8	1	323	324	\N	2023-11-01 12:48:15	2023-11-06 11:40:34	9a81a7ca-4ca2-474f-987e-f34815316acb	\N	\N
9a809a7c-5b47-4638-84ce-66e75f3f017d	Construcción del Plan Estratégico Institucional - Instituto de Previsión Social	2023-10-31	2023-10-31	corporative	master	<p>Dirigir y administrar el Seguro Social, garantizando la sostenibilidad y calidad en las prestaciones sanitarias y económicas a los usuarios, a través de una gestión eficiente.</p>	<p>Ser la institución Líder del Seguro Social, innovadora, confiable, comprometida y transparente, con amplia cobertura Nacional.</p>	<ol><li><strong>Honestidad</strong>:&nbsp;Actúo con honradez, verdad y justicia.&nbsp;</li><li>Integridad: Soy coherente entre lo que hago y digo.&nbsp;</li><li>Responsabilidad: Asumo los deberes y obligaciones.</li><li>Respeto: Trato con dignidad y tolerancia.&nbsp;</li><li>Solidaridad:&nbsp;Ayudo y sirvo a los demás.&nbsp;</li><li>Servicio:&nbsp;Tengo actitud de colaboración permanente.</li><li>Transparencia:&nbsp;Hago pública la gestión realizada.&nbsp;</li><li>Empatía:&nbsp;Ponerse en el lugar del otro.&nbsp;</li></ol>	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	5	8	1	51	353	\N	2023-10-31 16:56:23	2023-11-06 13:05:06	\N	\N	\N
9a824b20-561f-465e-b7d9-77d921dc2acf	<figure class="table"><table><tbody><tr><td>Proponer el financimiento de nuevos rubros de acuerdo a disponibilidad y reglamentaciones vigentes a nivel nacional</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	106	107	2023-11-01 13:35:06	2023-11-01 13:06:08	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a82ab24-965b-4413-aac7-76b6b5afcd26	<p>Dotación de nuevos equipos biomédicos acorde a la cartera de servicios</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	26	\N	\N	\N	Cartera de servicios vigente y proyectada	Cantidad de llamados a licitación	0.2% anual	\N	8	1	211	212	\N	2023-11-01 17:34:37	2023-11-06 12:07:03	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a826b54-4cf4-4ea2-b41e-283b00932ed9	<p>Proponer el financimiento de nuevos rubros de acuerdo a disponibilidad y reglamentaciones vigentes a nivel nacional</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	\N	\N	Cantidad de rubros financiados sobre el total del anexo de personal	Anexo de Personal 2023	Mejorar los niveles de ingresos al 20% del total del anexo de personal	\N	8	1	368	369	\N	2023-11-01 14:36:11	2023-11-06 12:01:09	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a824c2b-28e8-43e7-8e62-056f76997d35	<p>Lanzamiento de la App "Medicasa" para la Optimización de la Atención del Adulto Mayor en Domicilio</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	\N	\N	Porcentaje de implementación de la APP MEDICASA sobre el total de servicios de MEDICASA	NO se contaba con el servicio	Continuar la implementación en toda la RIISS	\N	8	1	155	156	\N	2023-11-01 13:09:03	2023-11-06 12:03:18	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a826b3b-43e7-44b9-bb78-745c84bd211f	<p>Actualizar los manuales de funciones</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	7	\N	\N	\N	Número de manuales actualizados sobre total de manuales	40% de manuales actualizados sobre el total de dependencias	En 5 años el 80% de manuales actualizados	\N	8	1	195	196	\N	2023-11-01 14:35:54	2023-11-06 12:00:59	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a824a93-a613-4a78-97ef-d48edceb778d	<figure class="table"><table><tbody><tr><td>Implementar un Plan de cargos y carreras</td></tr></tbody></table></figure>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	\N	96	97	2023-11-01 13:35:06	2023-11-01 13:04:36	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a824e97-f8b0-44b5-a067-4654572ddb75	<p><strong>Construir alianzas estratégicas con empresas para facilitar la recopilación de información laboral de los trabajadores</strong></p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Número de Alianzas celebradas con Empresas que faciliten la recopilación de información laboral de los trabajadores	Se cuenta con 4 alianzas a través de Convenios con Empresas para la construcción de la historia laboral	Aumentar en 50% las alianzas con empresa para la construcción de la historia laboral y la Concesión de Beneficios de Largo Plazo	\N	8	1	84	85	\N	2023-11-01 13:15:50	2023-11-06 11:51:46	9a81a8b3-a6da-4092-9a38-5166f6689b36	\N	\N
9a828a39-cc82-44c0-9803-3b1d760fbc1e	<p>Renovación de servicio de fumigación general&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	27	\N	\N	\N	Cantidad de contratos necesarios y existentes	Adjudicación de contratos	100% de manera anual	\N	8	1	213	214	\N	2023-11-01 16:02:34	2023-11-06 12:07:10	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a824927-df9b-4947-9c18-80dbe60dc7c9	<p>Conformación de grupos de trabajo para acciones interinstitucionales: Seguimiento al cumplimiento de Metas del Plan 100 Días y más:</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	9	\N	\N	\N	Porcentaje de metas cumplidas del Plan 100 días/Número total de metas del Plan 100 días	no se cuenta con la línea	Cumplimiento del 100% de las metas	\N	8	1	237	238	\N	2023-11-01 13:00:37	2023-11-06 11:56:28	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a826b23-f034-477f-bce2-37cff9bf4948	<p>Implementar la inducción a los nuevos funcionarios para asumir nuevas responsabilidades</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	9	\N	\N	\N	Número de funcionarios inducidos sobre el total de funcionarios incorporados	No se dispone	100% de funcionarios inducidos del total de incorporados	\N	8	1	370	371	\N	2023-11-01 14:35:39	2023-11-06 12:01:20	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a824a79-b3a7-4568-8296-9394a75ee241	<p>Mejorar la planificación priorizando el uso de bienes y servicios al más bajo nivel de gastos.</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Cantidad de recursos priorizados en base a disponibilidad sobre el total de recursos disponibles	Priorización a cargo de la Alta Gerencia	Incorporación del PAC con el 80% de los requerimientos de las áreas	\N	8	1	255	256	\N	2023-11-01 13:04:19	2023-11-06 11:59:29	9a81a96d-d82e-4398-8b6c-9f8e7e9a177a	\N	\N
9a8269d8-0859-4668-92ab-08ea1fbba073	<p>Planificar la disponibilidad de rubros para los ajustes de estructura organizacional en el anexo de personal con su línea presupuestaria</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	10	\N	\N	\N	Cantidad de rubros ajustados sobre Cantidad total de rubros disponibles	Anexo de Personal año 2023	Integración de rubros del anexo de personal mejorando la remuneración por niveles	\N	8	1	372	373	\N	2023-11-01 14:32:01	2023-11-06 12:01:38	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a8247ee-13e7-44b4-a818-101e6d2fe739	<p>Reducción del uso de papelería. Oficina sin papeles</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Reducción del uso de papelería. Oficina sin papeles	Reducción del uso de papelería. Oficina sin papeles	PENDIENTE	\N	8	\N	139	140	2023-11-01 13:00:02	2023-11-01 12:57:12	2023-11-01 13:00:02	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a824981-923e-4eac-85ba-e0c1e39d2e45	<p>Implementación del Plan de las Normas de Requisitos Mínimos MECIP- del Sistema de Control Interno en la Institución (Análisis de la Estructura Organizacional, Procesos Administrativos, Control Interno, definición de indicadores, Medición de Riesgos, etc.)</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	10	\N	\N	\N	Seguimiento de la implementación de las NRM MECIP para el sistema de control interno	Calificación General del AGPE/CGR 1,9	Calificación General de la AGPE/CGR 2,5 en un año	\N	8	1	239	240	\N	2023-11-01 13:01:36	2023-11-06 11:56:46	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a824a1d-0af0-412b-be00-8223af351bf9	<p>Aplicación del Programa en Calidad y Calidez en Atención en&nbsp; el Área Ambulatoria (Calidad y seguridad del paciente)</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	11	\N	\N	\N	Porcentaje de pacientes encuestados que califican su experiencia de atención con calidad y seguridad como "satisfactoria" o "muy satisfactoria" en el área ambulatoria	PENDIENTE	PENDIENTE	\N	8	1	241	242	\N	2023-11-01 13:03:18	2023-11-06 11:57:16	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a8269ff-4ee7-4992-885f-9a31c0b2cf8c	<p>Fortalecimiento de las competencias de los talentos humanos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	11	\N	\N	\N	Cantidad de capacitaciones realizadas al personal sobre lo aprobado para el periodo y convocatorias	Plan Anual de Capacitaciones y Convocatorias	Más del 70% de lo aprobado para el periodo	\N	8	1	374	375	\N	2023-11-01 14:32:27	2023-11-06 12:01:51	9a82697a-2ceb-46ff-82b9-9aacdfaff287	\N	\N
9a81a8f5-b443-4018-bef2-415be205dcb2	<p>3- Impulsar reformas legales para el financiamiento de las prestaciones económicas otorgadas equilibradamente entre el esfuerzo contributivo de los aportantes y los beneficios recibidos.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	\N	\N	\N	\N	8	1	73	76	\N	2023-11-01 05:32:41	2023-11-06 11:53:02	9a81a711-d45c-46c9-8464-aa48d40e0d8d	\N	\N
9a81a92f-85e1-475c-afc4-553b10762785	<p>4- Promover el trabajo integrado, para la implementación de la Política Institucional del Adulto Mayor 2021/2030, en todas las dependencias del IPS.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	\N	\N	\N	\N	8	1	77	78	\N	2023-11-01 05:33:19	2023-11-06 13:15:23	9a81a711-d45c-46c9-8464-aa48d40e0d8d	\N	\N
9a8254bb-50ad-4518-a007-0053616092a5	<p><strong>Proceso de Inducción al personal que se incorpora a la Institución</strong></p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Cantidad de personas que realizaron inducción al ser vinculados a la Institución.	\N	\N	\N	8	\N	116	117	2023-11-01 13:35:06	2023-11-01 13:32:59	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a8249d8-ca9c-4f2d-ba30-30a197b0b741	<p>Lanzamiento del Plan de Gestión de la Calidad Institucional (Proceso de Agendamiento, procesos críticos con los círculos de calidad)&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	12	\N	\N	\N	(Número de procesos críticos identificados con círculos de calidad / Total de procesos críticos) x 100.	No se cuenta	Implementación del 50% de los procesos criticos identificados	\N	8	1	243	244	\N	2023-11-01 13:02:33	2023-11-06 11:57:33	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a81a8b3-a6da-4092-9a38-5166f6689b36	<p>1- Brindar prestaciones económicas oportunamente a la población asegurada.</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	\N	\N	\N	\N	\N	8	1	79	92	\N	2023-11-01 05:31:58	2023-11-06 13:15:34	9a81a711-d45c-46c9-8464-aa48d40e0d8d	\N	\N
9a82b284-f5a2-4e04-b719-ca715250c683	<p>Asegurar la trazabilidad de los insumos y medicamentos dentro del sistema informático</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Numero de insumos y/o medicamentos utilizados en un año	Consumo mensual de cada insumo y/o medicamento	80% de los de insumos y medicamentos cargados en el sistema	\N	8	1	299	300	\N	2023-11-01 17:55:14	2023-11-06 11:41:11	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	\N	\N
9a82480b-ddff-4bc2-86d2-9b821a299f09	<p>Dotación de medicamentos, insumos y dispositivos médicos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	Cartera de servicios	Llamados a licitación	80% anual	\N	8	1	257	258	\N	2023-11-01 12:57:31	2023-11-06 11:59:39	9a81a96d-d82e-4398-8b6c-9f8e7e9a177a	\N	\N
9a8237a0-a147-4a3b-9c27-6178d3b12b1e	<p>4- Promover la implementación efectiva de la planificación en salud, en el área asistencia, la logística y las áreas acto médica</p>	\N	\N	corporative	goal	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	\N	\N	\N	\N	8	1	286	301	\N	2023-11-01 12:11:36	2023-11-05 22:14:13	9a81a6f8-8f40-41d4-bfe1-8b73e5ec3ff4	\N	\N
9a828873-11c2-47c1-ab1f-ee103d4f4215	<p>Priorizar el cambio del sistema SAOP&nbsp;</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	13	\N	\N	\N	Actualización del sistema de AOP	Uso del sistema SAOP a pleno y software de base data de muchos años	Contar con la actualización del SAOP en 1 año	\N	8	1	245	246	\N	2023-11-01 15:57:36	2023-11-06 11:57:42	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a82816b-94e0-43e3-8fb8-8a82132a371b	<p>Fortalecer los vinculos interinstitucionales a fin de reducir la evasión patronal</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	14	\N	\N	\N	Aumento de la recaudación en promedio al 7% anual sobre total de recaudación	Promedio últimos doce meses	Llegar al 10% de recaudación promedio anual	\N	8	1	247	248	\N	2023-11-01 15:37:57	2023-11-06 11:57:54	9a81a955-fe95-49ea-b552-040c74abba5e	\N	\N
9a826cc7-59ca-4b41-a6e0-03d28279c3f9	<p>Mantenimiento anual, preventivo y correctivo, del sistema de prevención de incendios</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	28	\N	\N	\N	Existencias del sistema de prevención de incendios	Número de contratos adjudicados	100% anual	\N	8	1	215	216	\N	2023-11-01 14:40:14	2023-11-06 12:07:17	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a827f9c-c1d2-4b40-bf70-8c256001bae9	<p>Renovación de flota de vehículos</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	29	\N	\N	\N	Cantidades requeridas	Adjudicación de contratos	50% distribuido en dos períodos de 2 años y medio	\N	8	1	217	218	\N	2023-11-01 15:32:54	2023-11-06 12:07:23	9a81a9b0-4a41-4bd2-8f0d-d76b75938568	\N	\N
9a828348-cbce-4c5b-8ab3-1575ba999603	<p>Reducción del uso de papelería</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	3	\N	\N	\N	Reducción del 40% en la compra de papelería	NO se cuenta con línea de base	Reducir al 80% la compra de papelería	\N	8	1	265	266	\N	2023-11-01 15:43:10	2023-11-06 12:07:49	9a82823b-4bf6-4589-8197-66b5cde73f62	\N	\N
9a82b322-0143-4eb1-a86e-165c677f3bcb	<p>3- Universalizar el expediente interno digital</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	8	8	273	274	2023-11-06 11:44:41	2023-11-01 17:56:57	2023-11-06 11:44:41	9a82383c-d5d0-484f-943d-51b34d4dea8e	\N	\N
9a824b71-2210-4827-92ab-a891a3a0dded	<p>Garantizar que los jubilados y pensionados tengan acceso a información veraz, clara, concisa e igualitaria a través de las redes social (whatsapp)</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	4	\N	\N	\N	Número de asociaciones y gremios de jubilados y pensionados que reciben información directa, sin intermediarios, de forma veraz y equitativa	Se cuenta con registro de las asociaciones y gremios que reciben información de la institución a través de un grupo de whatsapp	Garantizar que el 80% de los jubilados y pensionados de asociación y gremios tengan acceso a información veraz y oportuna	\N	8	1	90	91	\N	2023-11-01 13:07:01	2023-11-06 11:52:13	9a81a8b3-a6da-4092-9a38-5166f6689b36	\N	\N
9a825479-17ef-4d6d-a726-cae632b59da4	<p><strong>Movilidad del personal para cubrir necesidades emergentes</strong></p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	Cantidad de funcionarios trasladados por Convocatoria Interna	\N	\N	\N	8	\N	114	115	2023-11-01 13:35:06	2023-11-01 13:32:16	2023-11-01 13:35:06	9a81a983-a84a-4d6b-9dd2-e6008278dd73	\N	\N
9a824c53-f955-49d1-8063-d3ed8bcfbef6	<p>Gestionar un sistema integrado de información para todo el IPS</p>	\N	\N	corporative	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	9	\N	\N	\N	Número de software integrados sobre total de software disponible	60 software disponibles integrados parcialmente	Integración del 100% de software especialmente con el área Contable	\N	8	1	157	158	\N	2023-11-01 13:09:29	2023-11-06 12:03:30	9a81a998-56b1-4e9a-870c-85cd4941211d	\N	\N
9a8665d9-5706-48b0-a19d-8a06b8a19c69	Brindar atención para la promoción y prevención de las enfermedades bucodentales materno infantil con énfasis en gestantes y bebes de 0 a 36 meses.	\N	\N	\N	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	2	\N	\N	\N	Numero de Madres gestantes atendidas en edad reproductivas	0	10 a 20 % por año	\N	8	1	307	308	\N	2023-11-03 14:04:10	2024-07-03 15:56:16	9a81a79d-46ba-4d42-b7c9-3ddcac54037b	\N	quantitative
9a827092-36dc-4f82-b7dc-9d92783e5c59	Implementar estrategias de Redes Temáticas Integradas e Integrales para la atención temprana de enfermedades crónicas	\N	\N	\N	action	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	\N	\N	Implementar estrategias de Redes Temáticas Integradas e Integrales para la atención temprana de enfermedades crónicas	Número de Redes temáticas aprobadas e implementada	Se cuenta con una Red Tematica aprobada e implementada por Resolución G.S. N° 154/2023 de fecha 02 de octubre	Implementar una Red Temática por año aprobada	\N	8	1	309	310	\N	2023-11-01 14:50:50	2024-07-03 18:57:28	9a81a79d-46ba-4d42-b7c9-3ddcac54037b	\N	quantitative
\.


--
-- Data for Name: pei_profiles_has_dependencies; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.pei_profiles_has_dependencies (id, pei_profile_id, dependency_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: pei_profiles_has_strategies; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.pei_profiles_has_strategies (id, profile_id, strategy_id, created_at, updated_at) FROM stdin;
1	9a81068d-19ea-44de-a713-66405894004f	22	\N	\N
2	9a81068d-19ea-44de-a713-66405894004f	10	\N	\N
3	9a81a79d-46ba-4d42-b7c9-3ddcac54037b	22	\N	\N
4	9a81a7ca-4ca2-474f-987e-f34815316acb	10	\N	\N
5	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	10	\N	\N
6	9a8237a0-a147-4a3b-9c27-6178d3b12b1e	14	\N	\N
7	9a82383c-d5d0-484f-943d-51b34d4dea8e	10	\N	\N
9	9a826946-ca49-42d5-966d-30f627496d0c	11	\N	\N
10	9a826946-ca49-42d5-966d-30f627496d0c	19	\N	\N
\.


--
-- Data for Name: peis_profiles_has_analysts; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.peis_profiles_has_analysts (id, pei_profile_id, analyst_id, created_at, updated_at) FROM stdin;
4	9a66ec8d-4cd8-496c-a0bd-f88bc880e6c9	9	\N	\N
9	9a66ee6c-8d0e-4d88-a09e-f184f2e79d3a	11	\N	\N
10	9a66eed4-fd56-4abc-9a66-b39dd42c782f	5	\N	\N
12	9a66f0cd-b29a-400a-867a-783058b92592	7	\N	\N
13	9a66f13c-c671-4c40-a1b1-3069309364b3	10	\N	\N
15	9a66f207-5b5a-43d3-bd5f-d4c413062753	4	\N	\N
16	9a66f251-91bf-48ab-88a7-27acec09c3aa	2	\N	\N
17	9a66f286-c8d2-44af-9033-553cc6c0e932	8	\N	\N
19	9a66f325-b52d-4fcc-9492-427c47aeedd2	10	\N	\N
20	9a66f36a-7f9b-4fc9-81f0-1b424bd27704	5	\N	\N
22	9a6743bc-792d-4c14-9558-030201f8f8e0	3	\N	\N
23	9a68542f-07f7-4b61-8769-561a034971b4	94	\N	\N
24	9a685474-a0ed-48ee-985b-c908923da254	94	\N	\N
26	9a809a7c-5b47-4638-84ce-66e75f3f017d	1	\N	\N
27	9a809a7c-5b47-4638-84ce-66e75f3f017d	2	\N	\N
28	9a809a7c-5b47-4638-84ce-66e75f3f017d	7	\N	\N
29	9a809a7c-5b47-4638-84ce-66e75f3f017d	5	\N	\N
30	9a809a7c-5b47-4638-84ce-66e75f3f017d	6	\N	\N
31	9a809a7c-5b47-4638-84ce-66e75f3f017d	11	\N	\N
32	9a809a7c-5b47-4638-84ce-66e75f3f017d	10	\N	\N
\.


--
-- Data for Name: peis_profiles_has_responsibles; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.peis_profiles_has_responsibles (id, profile_id, responsible_id, created_at, updated_at) FROM stdin;
1	9a81aa47-dbee-4a90-b8c1-5ad32d23e7cf	9	\N	\N
2	9a823130-a372-42b4-a69f-b7c72612e3c9	10	\N	\N
3	9a823b4c-3cde-4080-a357-90e338c25f90	13	\N	\N
4	9a8234fc-6991-4c0f-9342-62bf099a36de	11	\N	\N
5	9a82415f-49b5-4c2f-a722-0a0b55a3c58a	13	\N	\N
6	9a824162-ecb2-4dc4-aa5c-9a796f6d2194	10	\N	\N
7	9a8241bd-e964-4720-a118-546bae99db69	11	\N	\N
8	9a824380-981d-4447-b552-5ba55a6177e5	10	\N	\N
9	9a8244ba-b537-418c-bda3-1cccdd1c3b37	10	\N	\N
10	9a8246b2-c8ca-4cfe-9cb7-428d2646c0de	10	\N	\N
11	9a8246b2-c8ca-4cfe-9cb7-428d2646c0de	14	\N	\N
13	9a82480b-ddff-4bc2-86d2-9b821a299f09	11	\N	\N
14	9a8247ee-13e7-44b4-a818-101e6d2fe739	11	\N	\N
15	9a8247ee-13e7-44b4-a818-101e6d2fe739	14	\N	\N
16	9a824b71-2210-4827-92ab-a891a3a0dded	13	\N	\N
17	9a824e97-f8b0-44b5-a067-4654572ddb75	13	\N	\N
18	9a824b0a-1777-4966-bfb1-9dffdeed2bbb	12	\N	\N
19	9a824a5d-9f55-463c-ba5f-fad97cb224d9	11	\N	\N
20	9a824a79-b3a7-4568-8296-9394a75ee241	11	\N	\N
21	9a82566b-a2d4-4d6b-b871-5c87a9df6d83	10	\N	\N
22	9a82566b-a2d4-4d6b-b871-5c87a9df6d83	11	\N	\N
23	9a824981-923e-4eac-85ba-e0c1e39d2e45	12	\N	\N
24	9a8248e6-d9c0-4ae7-9143-2d6dee81ab49	14	\N	\N
25	9a825824-d83f-4b24-bf30-a835884ba779	11	\N	\N
26	9a8248cf-01f2-4ff2-8cba-ad3489d1d5b3	11	\N	\N
27	9a824bff-3ab6-409b-a116-f5e6392387c5	12	\N	\N
28	9a82622b-e5b6-433b-a38b-2bda8a2fbf86	10	\N	\N
29	9a82622b-e5b6-433b-a38b-2bda8a2fbf86	14	\N	\N
30	9a8248b4-b89c-4e9a-83bb-c81480b7ffd1	10	\N	\N
31	9a824c6d-c2e8-4945-a3c0-f0c57d7bbfb5	12	\N	\N
32	9a8264d7-6ac1-47c1-9c81-9e8542c41741	11	\N	\N
33	9a8247ca-e8bb-41d2-b8f5-b152ac527c1e	12	\N	\N
34	9a824c53-f955-49d1-8063-d3ed8bcfbef6	12	\N	\N
35	9a824c15-e082-405b-98a3-e9ae7126a70c	12	\N	\N
36	9a824c3f-98e1-484e-8ea1-6a30b897d2ba	12	\N	\N
37	9a82689a-6d59-499f-a48f-93a3ab24da79	11	\N	\N
38	9a826a19-ded3-4456-86f3-513802cafe41	11	\N	\N
39	9a826b3b-4268-4d1e-9ce2-766567904bb8	11	\N	\N
40	9a826cc7-59ca-4b41-a6e0-03d28279c3f9	11	\N	\N
41	9a8269d8-0859-4668-92ab-08ea1fbba073	14	\N	\N
42	9a826b54-4cf4-4ea2-b41e-283b00932ed9	14	\N	\N
43	9a826b23-f034-477f-bce2-37cff9bf4948	9	\N	\N
45	9a826b02-1d29-4b93-8ffb-e8f2c04fb1ca	9	\N	\N
46	9a8272a8-0605-4aee-93f4-6b1e5485c340	11	\N	\N
47	9a826b3b-43e7-44b9-bb78-745c84bd211f	12	\N	\N
49	9a8275f9-8aa9-410c-b5fd-df1759355e65	9	\N	\N
50	9a824c2b-28e8-43e7-8e62-056f76997d35	12	\N	\N
51	9a82495e-520d-4e38-acf6-c5f92567b37e	12	\N	\N
52	9a8249d8-ca9c-4f2d-ba30-30a197b0b741	12	\N	\N
53	9a8249ef-d149-4cf2-964c-19cb8194f971	12	\N	\N
54	9a8249ba-2c09-45ff-9c42-d634d31cd885	12	\N	\N
55	9a824927-df9b-4947-9c18-80dbe60dc7c9	12	\N	\N
56	9a824941-dfcc-4daa-ae9c-fda346b47903	12	\N	\N
57	9a827ec8-d220-47d7-b562-05e7140a7e5c	11	\N	\N
58	9a824a34-a899-406a-a8a6-4d6455f4ae50	12	\N	\N
59	9a827f9c-c1d2-4b40-bf70-8c256001bae9	11	\N	\N
60	9a82807c-b239-4c07-9c19-7eed2ab97e1b	11	\N	\N
61	9a82816b-94e0-43e3-8fb8-8a82132a371b	14	\N	\N
62	9a828284-4bd8-499a-81c2-4be50791b713	13	\N	\N
63	9a8282c7-f6f6-4b44-85e2-92858ae0cdb0	12	\N	\N
65	9a828346-6294-49af-a9a6-11abb8ecc616	11	\N	\N
66	9a828348-cbce-4c5b-8ab3-1575ba999603	12	\N	\N
67	9a8269ff-4ee7-4992-885f-9a31c0b2cf8c	9	\N	\N
68	9a828443-9867-4156-8425-15e2075959a7	11	\N	\N
69	9a8284e5-c7bc-4d96-a258-ac30782e7c61	13	\N	\N
70	9a8286b0-a66a-4c12-9057-52aca54ca78a	11	\N	\N
71	9a82874e-6d6b-4cef-8c8c-47f1abf3cdc4	10	\N	\N
72	9a82874e-6d6b-4cef-8c8c-47f1abf3cdc4	13	\N	\N
73	9a828806-161c-4fbd-965c-83f762fea4c5	12	\N	\N
74	9a828806-161c-4fbd-965c-83f762fea4c5	13	\N	\N
75	9a828873-11c2-47c1-ab1f-ee103d4f4215	12	\N	\N
76	9a8289ae-6e0b-42ef-a217-f11a47042a6a	11	\N	\N
77	9a828a39-cc82-44c0-9803-3b1d760fbc1e	11	\N	\N
78	9a828a87-967d-4485-be1e-c6505c15a4c9	9	\N	\N
79	9a828ac6-19bd-41dd-91e3-a508a0c0b507	13	\N	\N
80	9a828b0b-7300-4f2d-9bd9-3d889ef31d8e	11	\N	\N
81	9a828b46-4fb3-4045-afe8-e692e3122042	11	\N	\N
82	9a828fcd-a317-4475-8ca3-a538b57f05bd	11	\N	\N
83	9a829d2c-82b3-4b30-a364-2909f9a11a8f	10	\N	\N
84	9a829e2e-f5f9-4a9a-9f88-8377b8e8a7cc	10	\N	\N
85	9a829e8a-b991-49c4-b292-9caedf836c33	10	\N	\N
86	9a829ee3-07ca-42f2-a3c2-9e72fa98d9fb	10	\N	\N
87	9a829faf-caef-46ff-ac4f-a012acab589a	10	\N	\N
88	9a82a044-8d1d-4a5e-8636-d648ec98173b	10	\N	\N
89	9a82a09b-8e8b-4cda-9dab-95a94adcb767	10	\N	\N
90	9a82a17f-0216-4601-a7c9-490ffa54d43f	10	\N	\N
91	9a82a1e2-fafb-4765-9921-f60c8b2b2a6f	10	\N	\N
92	9a82a243-56e3-4006-b983-0b11d4d2d988	10	\N	\N
93	9a82a430-b3e4-458f-bc4e-861af5617039	11	\N	\N
94	9a82a4d7-19ef-433f-b244-5090ddb60326	10	\N	\N
95	9a82a53a-7873-4e50-9ab4-ecc81f6e00dc	11	\N	\N
96	9a82a5b3-e470-43b0-8e64-5dda2022dad3	11	\N	\N
97	9a82ab24-965b-4413-aac7-76b6b5afcd26	11	\N	\N
98	9a82aff9-041e-4ee3-b05d-3a8744ecfacc	11	\N	\N
99	9a826a17-0a55-4c48-b492-acbf8fa5ad52	11	\N	\N
100	9a82b4e8-8687-4baf-84a5-0c99273ac6a7	11	\N	\N
101	9a82b504-8b14-4f9a-9f1a-d0dac9294553	12	\N	\N
102	9a82b659-a700-4e51-af77-dcac86607aa7	12	\N	\N
103	9a82b1d6-d03e-4878-8ff7-8befad02fe48	10	\N	\N
104	9a82b284-f5a2-4e04-b719-ca715250c683	10	\N	\N
105	9a82b2a2-7bba-4966-9b96-e24285755137	10	\N	\N
106	9a82b8d2-5fbb-4532-9ea7-83f98bcd9fe7	12	\N	\N
107	9a82bb13-de55-4905-a1a3-458320628342	9	\N	\N
108	9a82bbb3-5a52-413d-8a93-c3c9a24137b3	9	\N	\N
109	9a82bc69-6104-42a0-b0ab-8ebaeb943980	9	\N	\N
110	9a82bd88-289d-4466-8823-aebb09f1a313	12	\N	\N
111	9a8247d0-5930-4d4a-ade2-81a505f8b617	13	\N	\N
112	9a82a09b-8e8b-4cda-9dab-95a94adcb767	12	\N	\N
113	9a829faf-caef-46ff-ac4f-a012acab589a	12	\N	\N
114	9a82a044-8d1d-4a5e-8636-d648ec98173b	12	\N	\N
115	9a8665d9-5706-48b0-a19d-8a06b8a19c69	10	\N	\N
120	9a827092-36dc-4f82-b7dc-9d92783e5c59	10	\N	\N
\.


--
-- Data for Name: tasks; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.tasks (id, group_id, details, status, created_at, updated_at) FROM stdin;
3	6	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 00:32:27	2023-10-19 00:32:27
4	15	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:04:45	2023-10-19 01:04:45
6	7	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:07:30	2023-10-19 01:07:30
7	16	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:07:52	2023-10-19 01:07:52
8	8	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:09:07	2023-10-19 01:09:07
9	17	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:10:00	2023-10-19 01:10:00
10	9	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:10:50	2023-10-19 01:10:50
12	10	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:12:45	2023-10-19 01:12:45
13	19	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:13:21	2023-10-19 01:13:21
14	11	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:14:05	2023-10-19 01:14:05
15	20	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:14:52	2023-10-19 01:14:52
16	12	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:15:39	2023-10-19 01:15:39
17	21	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 01:16:08	2023-10-19 01:16:08
27	13	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 02:46:09	2023-10-19 02:46:09
29	22	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 02:51:51	2023-10-19 02:51:51
30	18	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 02:54:13	2023-10-19 02:54:13
31	14	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 09:41:43	2023-10-19 09:41:43
33	7	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 12:11:44	2023-10-19 12:11:44
34	16	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 12:26:53	2023-10-19 12:26:53
35	26	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 15:45:37	2023-10-19 15:45:37
36	25	<p>Planificación Estratégica Institucional 2023-2028</p>	0	2023-10-19 15:53:12	2023-10-19 15:53:12
37	28	<p>PEI 2023</p>	0	2023-11-01 05:44:03	2023-11-01 05:44:03
38	29	<p>PEI 2023</p>	0	2023-11-01 05:44:56	2023-11-01 05:44:56
39	31	<p>PEI 2028</p>	0	2023-11-01 05:46:09	2023-11-01 05:46:09
40	30	<p>PEI 2028</p>	0	2023-11-01 05:46:53	2023-11-01 05:46:53
41	32	<p>PEI 2028</p>	0	2023-11-01 05:47:35	2023-11-01 05:47:35
42	33	<p>PEI 2028</p>	0	2023-11-01 05:48:30	2023-11-01 05:48:30
44	41	<p>A</p>	0	2024-04-25 18:08:47	2024-04-25 18:18:30
46	33	<p>A</p>	0	2024-10-17 15:55:36	2024-10-17 15:55:36
47	33	<p>A</p>	0	2024-10-17 15:55:45	2024-10-17 15:55:45
48	33	<p>a</p>	0	2024-10-17 18:08:48	2024-10-17 18:08:48
49	33	<p>a</p>	0	2024-10-17 18:08:57	2024-10-17 18:08:57
50	33	<p>a</p>	0	2024-10-17 18:09:46	2024-10-17 18:09:46
62	44	<p>Test</p>	0	2024-11-14 14:04:06	2024-11-14 14:04:06
\.


--
-- Data for Name: tasks_has_analysts; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.tasks_has_analysts (id, task_id, analyst_id, created_at, updated_at) FROM stdin;
3	3	7	\N	\N
4	4	7	\N	\N
6	6	10	\N	\N
7	7	10	\N	\N
8	8	9	\N	\N
9	9	9	\N	\N
10	10	4	\N	\N
12	12	2	\N	\N
13	13	2	\N	\N
14	14	8	\N	\N
15	15	8	\N	\N
16	16	11	\N	\N
17	17	11	\N	\N
27	27	3	\N	\N
29	29	3	\N	\N
30	30	4	\N	\N
31	31	5	\N	\N
33	33	6	\N	\N
34	34	6	\N	\N
35	35	94	\N	\N
36	36	94	\N	\N
37	37	8	\N	\N
38	37	11	\N	\N
39	38	94	\N	\N
40	39	9	\N	\N
41	39	3	\N	\N
42	40	5	\N	\N
43	41	2	\N	\N
44	41	10	\N	\N
45	42	7	\N	\N
46	42	6	\N	\N
48	44	4	\N	\N
50	46	4	\N	\N
51	47	4	\N	\N
52	48	4	\N	\N
53	49	4	\N	\N
54	50	4	\N	\N
68	62	228	\N	\N
\.


--
-- Data for Name: tasks_has_type_tasks; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.tasks_has_type_tasks (id, task_id, typetaskable_id, typetaskable_type, status, created_at, updated_at) FROM stdin;
8	4	9a66f0cd-b29a-400a-867a-783058b92592	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
67	34	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
13	7	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
68	34	9a66f325-b52d-4fcc-9492-427c47aeedd2	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
18	9	9a6706ec-e7b4-4906-9428-09e20268acbb	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
17	9	9a66f182-dc9e-401f-8f46-7cd31fab3f60	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
16	8	9a66f182-dc9e-401f-8f46-7cd31fab3f60	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
57	30	9a670976-68cf-4f5e-b4f5-4c56931aa175	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
58	30	9a66f207-5b5a-43d3-bd5f-d4c413062753	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
25	13	9a670c4c-1010-4ad0-97ad-0194abe0930d	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
26	13	9a66f251-91bf-48ab-88a7-27acec09c3aa	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
29	15	9a670e44-aeed-4cfe-9c60-5d305913f7cb	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
30	15	9a66f286-c8d2-44af-9033-553cc6c0e932	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
33	17	9a670fde-7520-4e65-8d55-46e9d730abfc	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
34	17	9a66f2d4-fb81-4bcf-a218-46ba5739aa37	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
5	3	9a670199-515d-45e0-9adf-da963f6cb5cd	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
6	3	9a66ebbe-0cc5-4ba3-a1d7-83961d83cd53	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
65	33	9a6703e4-3d3f-4400-8224-3be4d4c1cf80	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
11	6	9a6703e4-3d3f-4400-8224-3be4d4c1cf80	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
66	33	9a66ec39-3e36-4fe8-920b-87743c9f09f4	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
12	6	9a66ec39-3e36-4fe8-920b-87743c9f09f4	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
15	8	9a670613-48f6-4af7-8680-57660453ee1b	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
19	10	9a670886-a987-4724-b5bd-8d84db396f9a	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
20	10	9a66ece7-56d6-42ea-99ce-dcef14f1e42b	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
23	12	9a670a4d-dc08-48b3-be07-fb2a830e199f	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
24	12	9a66ed48-c808-453f-bbc7-bccf46591679	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
27	14	9a670d46-01c4-4466-ad21-73d8f16bf0a8	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
28	14	9a66ed93-e21b-4765-9027-f9cda2fc8f4a	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
31	16	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
32	16	9a66edf3-8b29-49cf-98b6-e7b2e9c2ce65	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
59	31	9a6710ce-015e-422e-a288-b63cb70a72b2	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
60	31	9a66ef18-aba4-4145-ba8b-e04324a51430	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
55	29	9a6743bc-792d-4c14-9558-030201f8f8e0	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
51	27	9a672fc4-d900-4b24-9e9b-d71943b1470f	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
56	29	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
52	27	9a674341-5cff-440a-86a8-0e58a147a8b7	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
69	35	9a684a9e-0630-432c-8e2e-af213a33543a	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
70	35	9a685ab2-8813-4e35-9dea-7267678ecc01	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
71	36	9a685474-a0ed-48ee-985b-c908923da254	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
72	36	9a684cfb-8d97-4115-ae88-855f59885d0c	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
78	42	9a809a7c-5b47-4638-84ce-66e75f3f017d	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
77	41	9a809a7c-5b47-4638-84ce-66e75f3f017d	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
76	40	9a809a7c-5b47-4638-84ce-66e75f3f017d	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
75	39	9a809a7c-5b47-4638-84ce-66e75f3f017d	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
74	38	9a809a7c-5b47-4638-84ce-66e75f3f017d	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
73	37	9a809a7c-5b47-4638-84ce-66e75f3f017d	App\\Admin\\Planificacion\\Pei\\PeiProfile	0	\N	\N
79	43	9bdb18e2-a2f9-43ab-99e7-df225db6146b	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	\N	\N
4	44	9be4bd15-3026-42de-87d5-5ae1bb412470	App\\Admin\\Planificacion\\Foda\\FodaPerfil	0	2024-04-25 18:18:30	2024-04-25 18:18:30
91	62	9d5efe4d-e8cd-41df-a45d-888fe41fa404	App\\Models\\Admin\\Globales\\Survey	0	2024-11-14 14:04:06	2024-11-14 14:04:06
\.


--
-- Data for Name: typetasks; Type: TABLE DATA; Schema: planificacion; Owner: postgres
--

COPY planificacion.typetasks (id, name, typetaskable_id, typetaskable_type, created_at, updated_at) FROM stdin;
5	Río Paraguay (FODA)	9a6702c6-af89-4dc4-b1d4-49ef7b556c6a	FODA	2023-10-19 00:17:41	2023-10-19 00:17:41
7	Río Paraguay (PEI)	9a66f0cd-b29a-400a-867a-783058b92592	PEI	2023-10-19 00:19:39	2023-10-19 00:19:39
9	Río Apa (FODA)	9a6704e0-53f7-4eaf-9015-3fbcc0179e76	FODA	2023-10-19 00:21:59	2023-10-19 00:21:59
11	Río Apa (PEI)	9a66f325-b52d-4fcc-9492-427c47aeedd2	PEI	2023-10-19 00:22:17	2023-10-19 00:22:17
13	Río Pilcomayo (FODA)	9a6706ec-e7b4-4906-9428-09e20268acbb	FODA	2023-10-19 00:23:04	2023-10-19 00:23:04
15	Río Pilcomayo (PEI)	9a66f182-dc9e-401f-8f46-7cd31fab3f60	PEI	2023-10-19 00:23:18	2023-10-19 00:23:18
17	Río Paraná (FODA)	9a670976-68cf-4f5e-b4f5-4c56931aa175	FODA	2023-10-19 00:23:43	2023-10-19 00:23:43
19	Río Paraná (PEI)	9a66f207-5b5a-43d3-bd5f-d4c413062753	PEI	2023-10-19 00:23:58	2023-10-19 00:23:58
21	Río Verde (FODA)	9a670c4c-1010-4ad0-97ad-0194abe0930d	FODA	2023-10-19 00:24:29	2023-10-19 00:24:29
23	Río Verde (PEI)	9a66f251-91bf-48ab-88a7-27acec09c3aa	PEI	2023-10-19 00:24:45	2023-10-19 00:24:45
25	Río Montelindo (FODA)	9a670e44-aeed-4cfe-9c60-5d305913f7cb	FODA	2023-10-19 00:25:10	2023-10-19 00:25:10
27	Río Montelindo (PEI)	9a66f286-c8d2-44af-9033-553cc6c0e932	PEI	2023-10-19 00:25:20	2023-10-19 00:25:20
29	Río Ypané (FODA)	9a670fde-7520-4e65-8d55-46e9d730abfc	FODA	2023-10-19 00:25:40	2023-10-19 00:25:40
31	Río Ypané (PEI)	9a66f2d4-fb81-4bcf-a218-46ba5739aa37	PEI	2023-10-19 00:25:53	2023-10-19 00:25:53
4	Río Paraguay (Presidencia) (FODA)	9a670199-515d-45e0-9adf-da963f6cb5cd	FODA	2023-10-19 00:17:15	2023-10-19 00:17:15
6	Río Paraguay (Presidencia) (PEI)	9a66ebbe-0cc5-4ba3-a1d7-83961d83cd53	PEI	2023-10-19 00:19:22	2023-10-19 00:19:22
8	Río Apa (Presidencia) (FODA)	9a6703e4-3d3f-4400-8224-3be4d4c1cf80	FODA	2023-10-19 00:20:59	2023-10-19 00:20:59
10	Río Apa (Presidencia) (PEI)	9a66ec39-3e36-4fe8-920b-87743c9f09f4	PEI	2023-10-19 00:22:07	2023-10-19 00:22:07
12	Río Pilcomayo (Gerencia de Desarrollo y Tecnología) (FODA)	9a670613-48f6-4af7-8680-57660453ee1b	FODA	2023-10-19 00:22:58	2023-10-19 00:22:58
14	Río Pilcomayo (Gerencia de Desarrollo y Tecnología) (PEI)	9a66ec8d-4cd8-496c-a0bd-f88bc880e6c9	PEI	2023-10-19 00:23:10	2023-10-19 00:23:10
16	Río Paraná (Gerencia de Abastecimiento y Logística) (FODA)	9a670886-a987-4724-b5bd-8d84db396f9a	FODA	2023-10-19 00:23:37	2023-10-19 00:23:37
18	Río Paraná (Gerencia de Abastecimiento y Logística) (PEI)	9a66ece7-56d6-42ea-99ce-dcef14f1e42b	PEI	2023-10-19 00:23:52	2023-10-19 00:23:52
20	Río Verde (Gerencia de Abastecimiento y Logística) (FODA)	9a670a4d-dc08-48b3-be07-fb2a830e199f	FODA	2023-10-19 00:24:25	2023-10-19 00:24:25
22	Río Verde (Gerencia de Abastecimiento y Logística) (PEI)	9a66ed48-c808-453f-bbc7-bccf46591679	PEI	2023-10-19 00:24:34	2023-10-19 00:24:34
24	Río Montelindo (Gerencia de Salud) (FODA)	9a670d46-01c4-4466-ad21-73d8f16bf0a8	FODA	2023-10-19 00:25:06	2023-10-19 00:25:06
26	Río Montelindo (Gerencia de Salud) (PEI)	9a66ed93-e21b-4765-9027-f9cda2fc8f4a	PEI	2023-10-19 00:25:16	2023-10-19 00:25:16
28	Río Ypané (Gerencia de Salud) (FODA)	9a670f0e-8ab2-492c-be8d-b206cd96ad2e	FODA	2023-10-19 00:25:35	2023-10-19 00:25:35
30	Río Ypané (Gerencia de Salud) (PEI)	9a66edf3-8b29-49cf-98b6-e7b2e9c2ce65	PEI	2023-10-19 00:25:47	2023-10-19 00:25:47
37	Río Piribebuy (Gerencia de Prestaciones Económicas) (FODA)	9a6710ce-015e-422e-a288-b63cb70a72b2	FODA	2023-10-19 00:28:21	2023-10-19 00:28:21
38	Río Piribebuy (Gerencia de Prestaciones Económicas) (PEI)	9a66ef18-aba4-4145-ba8b-e04324a51430	PEI	2023-10-19 00:28:28	2023-10-19 00:28:28
41	Río Jejuí (PEI)	9a6743bc-792d-4c14-9558-030201f8f8e0	PEI	2023-10-19 02:38:56	2023-10-19 02:38:56
39	Río Jejuí (Gerencia Administrativa y Financiera) (FODA)	9a672fc4-d900-4b24-9e9b-d71943b1470f	FODA	2023-10-19 01:59:51	2023-10-19 01:59:51
42	Río Jejuí (FODA)	9a67359f-0a16-4fb0-bff0-02cbf1ae28ac	FODA	2023-10-19 02:39:21	2023-10-19 02:39:21
43	Río Jejuí (Gerencia Administrativa y Financiera) (PEI)	9a674341-5cff-440a-86a8-0e58a147a8b7	PEI	2023-10-19 02:39:53	2023-10-19 02:39:53
45	Río Montelindo (PEI)	9a66f286-c8d2-44af-9033-553cc6c0e932	PEI	2023-10-19 15:03:19	2023-10-19 15:03:19
44	Río Montelindo (Gestión Médica) (FODA)	9a684a9e-0630-432c-8e2e-af213a33543a	FODA	2023-10-19 15:02:13	2023-10-19 15:02:13
46	Río Montelindo (Gestión Médica) (PEI)	9a685ab2-8813-4e35-9dea-7267678ecc01	PEI	2023-10-19 15:41:59	2023-10-19 15:41:59
47	Río Montelindo (Lila) (PEI)	9a685474-a0ed-48ee-985b-c908923da254	PEI	2023-10-19 15:47:46	2023-10-19 15:47:46
48	Río Montelindo (Lila) (FODA)	9a684cfb-8d97-4115-ae88-855f59885d0c	FODA	2023-10-19 15:50:58	2023-10-19 15:50:58
49	Construcción del Plan Estratégico Institucional - Instituto de Previsión Social (PEI)	9a809a7c-5b47-4638-84ce-66e75f3f017d	PEI	2023-10-31 18:16:26	2023-10-31 18:16:26
50	Análisis FODA en el Marco de la RIISS de IPS (FODA)	9bdb18e2-a2f9-43ab-99e7-df225db6146b	FODA	2024-04-20 22:50:17	2024-04-20 22:50:17
\.


--
-- Data for Name: e_p_c_equipamientos; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_equipamientos (id, item, type, cost, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_equipamientos_servicios; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_equipamientos_servicios (servicio_id, equipamiento_id, cantidad) FROM stdin;
\.


--
-- Data for Name: e_p_c_especialidads; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_especialidads (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_estandars; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_estandars (id, item, type, detail, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_estandars_servicios; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_estandars_servicios (estandar_id, servicio_id, cantidad) FROM stdin;
\.


--
-- Data for Name: e_p_c_horarios; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_horarios (id, item, start_time, end_time, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_infraestructura_servicio; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_infraestructura_servicio (servicio_id, infraestructura_id, cantidad) FROM stdin;
\.


--
-- Data for Name: e_p_c_infraestructuras; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_infraestructuras (id, item, type, measurement, cost, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_medicamento_insumos; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_medicamento_insumos (id, item, type, cost, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_otro_servicios; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_otro_servicios (id, item, type, cost, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_prestaciones; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_prestaciones (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_servicios; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_servicios (id, item, type, description, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_talento_humanos; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_talento_humanos (id, item, hours, type, cost, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: e_p_c_tthhs_servicios; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_tthhs_servicios (servicio_id, tthh_id, cantidad) FROM stdin;
\.


--
-- Data for Name: e_p_c_turnos; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.e_p_c_turnos (id, item, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: otroservicio_has_servicios; Type: TABLE DATA; Schema: proyecto; Owner: postgres
--

COPY proyecto.otroservicio_has_servicios (servicio_id, otro_servicio_id, cantidad) FROM stdin;
\.


--
-- Data for Name: activities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.activities (id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: answers; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.answers (id, participant_id, survey_id, question_id, answer, is_correct, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: answers_has_questions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.answers_has_questions (id, question_id, answers) FROM stdin;
1	1	[{"answer":"Olimpia","is_correct":1},{"answer":"Cerro","is_correct":0}]
2	2	[{"answer":"Colon","is_correct":0},{"answer":"Los Vikingos","is_correct":1}]
3	3	[{"answer":"A","is_correct":1},{"answer":"B","is_correct":0}]
8	8	[{"answer":"a","is_correct":1},{"answer":"b","is_correct":0}]
9	9	[{"answer":"a","is_correct":1},{"answer":"b","is_correct":0}]
11	11	[{"answer":null,"is_correct":1}]
12	12	[{"answer":"a","is_correct":1},{"answer":null,"is_correct":0}]
23	23	[{"answer":"Florence Nightingale","is_correct":1},{"answer":"Clara Barton","is_correct":0}]
24	24	[{"answer":"Siglo XIX","is_correct":1},{"answer":"Siglo XVII","is_correct":0}]
25	25	[{"answer":"Establecimiento de est\\u00e1ndares de higiene","is_correct":1},{"answer":"Invenci\\u00f3n de la m\\u00e1quina de rayos X","is_correct":0}]
26	26	[{"answer":"Henry Dunant","is_correct":1},{"answer":"Louis Pasteur","is_correct":0}]
27	27	[{"answer":"Escuela de Enfermer\\u00eda del Hospital Bellevue","is_correct":1},{"answer":"Escuela de Medicina de Harvard","is_correct":0}]
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categories (id, name, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: groups; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.groups (id, name, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
27	Talleres de Elaboración del Plan Estratégico Institucional (Segunda Semana)	45	58	\N	2023-10-31 17:32:12	2023-10-31 17:32:12
11	Río Montelindo (Gerencia de Salud)	8	9	4	2023-10-18 19:32:31	2023-10-18 19:32:31
12	Río Ypané (Gerencia de Salud)	10	11	4	2023-10-18 19:34:58	2023-10-18 19:34:58
13	Río Jejuí (Gerencia Administrativa y Financiera)	12	13	4	2023-10-18 19:38:03	2023-10-18 19:38:03
14	Río Piribebuy (Gerencia de Prestaciones Económicas)	14	15	4	2023-10-18 19:40:17	2023-10-18 19:40:17
6	Río Paraguay (Presidencia)	16	17	4	2023-10-18 19:22:46	2023-10-19 11:27:33
10	Río Verde (Gerencia de Abastecimiento y Logística)	18	19	4	2023-10-18 19:30:53	2023-10-19 14:22:11
33	EJE 3 - GRUPO F (ROJO)	56	57	27	2023-10-31 18:11:32	2023-10-31 18:15:19
7	Río Apa (Presidencia)	2	3	4	2023-10-18 19:24:51	2023-10-18 19:24:51
8	Río Pilcomayo (Gerencia de Desarrollo y Tecnología)	4	5	4	2023-10-18 19:26:53	2023-10-18 19:27:03
9	Río Paraná (Gerencia de Abastecimiento y Logística)	6	7	4	2023-10-18 19:28:49	2023-10-18 19:28:49
26	Río Montelindo (Gestión Médica)	20	21	4	2023-10-19 15:37:39	2023-10-19 15:37:39
21	Río Ypane (Rojo)	36	37	5	2023-10-18 19:45:15	2023-10-19 13:24:54
22	Río Jejuí (Amarillo)	38	39	5	2023-10-18 19:45:25	2023-10-19 13:26:47
24	Río Montelindo (Gestión Médica)	40	41	5	2023-10-19 14:46:32	2023-10-19 14:46:32
25	Río Montelindo (Lila)	42	43	5	2023-10-19 14:46:54	2023-10-19 14:46:54
15	Río Paraguay (Azul)	24	25	5	2023-10-18 19:43:58	2023-10-19 11:33:32
16	Río Apa (Azul)	26	27	5	2023-10-18 19:44:13	2023-10-19 11:37:01
17	Río Pilcomayo (Naranja)	28	29	5	2023-10-18 19:44:25	2023-10-19 11:41:02
18	Río Paraná (Verder)	30	31	5	2023-10-18 19:44:35	2023-10-19 11:47:13
19	Río Verde (Verde)	32	33	5	2023-10-18 19:44:46	2023-10-19 11:52:35
20	Río Montelindo (Rojo)	34	35	5	2023-10-18 19:45:04	2023-10-19 12:27:56
5	Talleres de Planificación - Grupales (Primera Semana)	23	44	\N	2023-10-18 19:17:29	2023-10-31 17:31:16
4	Talleres de Planificación - Gerenciales (Primera Semana)	1	22	\N	2023-10-18 17:44:35	2023-10-31 17:31:26
28	EJE 1- GRUPO A (LILA)	46	47	27	2023-10-31 17:46:13	2023-10-31 18:12:45
29	EJE 1 - GRUPO B (NARANJA)	48	49	27	2023-10-31 17:53:48	2023-10-31 18:13:31
30	EJE 2 - GRUPO C (VERDE)	50	51	27	2023-10-31 17:59:32	2023-10-31 18:14:14
31	EJE 3 - GRUPO D (AZUL)	52	53	27	2023-10-31 18:04:31	2023-10-31 18:14:43
32	EJE 3 - GRUPO E (AMARILLO)	54	55	27	2023-10-31 18:07:41	2023-10-31 18:15:03
35	Tigre	60	61	34	2024-04-16 14:51:03	2024-04-25 17:43:05
38	Leon	62	63	34	2024-04-16 14:51:44	2024-04-25 17:43:09
39	Oso	64	65	34	2024-04-16 14:51:56	2024-04-25 17:43:17
37	Elefante	66	67	34	2024-04-16 14:51:34	2024-04-25 17:43:22
36	Pato	68	69	34	2024-04-16 14:51:21	2024-04-25 17:43:28
40	Pescado	70	71	34	2024-04-25 17:43:35	2024-04-25 17:43:35
41	Aguila	72	73	34	2024-04-25 17:43:43	2024-04-25 17:43:43
34	Taller de Análisis FODA en el Marco de la RIISS de IPS	59	76	\N	2024-04-16 14:11:21	2024-04-16 14:11:21
42	Perro	74	75	34	2024-04-25 17:43:54	2024-04-25 17:43:54
43	Curso de Inglés	77	80	\N	2024-10-18 12:37:06	2024-10-18 12:37:06
44	Test	78	79	43	2024-10-18 12:47:03	2024-11-05 19:48:57
\.


--
-- Data for Name: groups_has_members; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.groups_has_members (id, group_id, user_id, created_at, updated_at) FROM stdin;
5	6	13	\N	\N
6	6	14	\N	\N
7	6	15	\N	\N
8	6	16	\N	\N
9	6	17	\N	\N
10	6	19	\N	\N
11	6	18	\N	\N
12	6	20	\N	\N
13	7	21	\N	\N
14	7	22	\N	\N
15	7	23	\N	\N
16	7	25	\N	\N
17	7	26	\N	\N
18	7	27	\N	\N
19	7	28	\N	\N
20	8	14	\N	\N
21	8	29	\N	\N
22	8	31	\N	\N
23	8	32	\N	\N
24	8	33	\N	\N
25	8	34	\N	\N
26	8	35	\N	\N
27	8	36	\N	\N
28	9	37	\N	\N
29	9	38	\N	\N
30	9	39	\N	\N
31	9	40	\N	\N
32	9	41	\N	\N
33	9	42	\N	\N
34	9	43	\N	\N
35	10	44	\N	\N
36	10	45	\N	\N
37	10	46	\N	\N
38	10	47	\N	\N
39	10	48	\N	\N
40	10	50	\N	\N
41	10	51	\N	\N
42	11	52	\N	\N
43	11	53	\N	\N
44	11	54	\N	\N
45	11	55	\N	\N
46	11	56	\N	\N
47	11	57	\N	\N
48	11	58	\N	\N
49	11	59	\N	\N
50	12	60	\N	\N
51	12	61	\N	\N
52	12	62	\N	\N
53	12	63	\N	\N
54	12	65	\N	\N
55	12	66	\N	\N
56	12	67	\N	\N
57	12	68	\N	\N
58	12	64	\N	\N
59	13	69	\N	\N
60	13	70	\N	\N
61	13	71	\N	\N
62	13	72	\N	\N
63	13	73	\N	\N
64	13	74	\N	\N
65	13	75	\N	\N
66	13	76	\N	\N
67	13	77	\N	\N
68	13	78	\N	\N
69	14	79	\N	\N
70	14	80	\N	\N
71	14	81	\N	\N
72	14	82	\N	\N
73	14	83	\N	\N
74	14	84	\N	\N
75	6	85	\N	\N
76	15	14	\N	\N
77	15	37	\N	\N
78	15	29	\N	\N
79	15	69	\N	\N
80	15	44	\N	\N
81	15	13	\N	\N
82	15	86	\N	\N
83	15	15	\N	\N
84	15	19	\N	\N
85	16	16	\N	\N
86	16	85	\N	\N
87	16	21	\N	\N
88	16	38	\N	\N
89	16	74	\N	\N
90	16	45	\N	\N
91	16	22	\N	\N
92	16	87	\N	\N
93	16	46	\N	\N
94	16	28	\N	\N
95	17	25	\N	\N
96	17	27	\N	\N
97	17	79	\N	\N
98	17	88	\N	\N
99	17	18	\N	\N
100	17	77	\N	\N
101	17	36	\N	\N
102	17	75	\N	\N
103	17	17	\N	\N
104	18	89	\N	\N
105	18	41	\N	\N
106	18	71	\N	\N
107	18	80	\N	\N
108	18	47	\N	\N
109	18	82	\N	\N
110	18	84	\N	\N
111	18	72	\N	\N
112	18	31	\N	\N
113	19	20	\N	\N
114	19	26	\N	\N
115	19	90	\N	\N
116	19	81	\N	\N
117	19	73	\N	\N
118	19	23	\N	\N
119	19	76	\N	\N
120	19	83	\N	\N
121	19	30	\N	\N
122	20	57	\N	\N
123	20	58	\N	\N
124	20	59	\N	\N
125	20	48	\N	\N
126	20	60	\N	\N
127	20	70	\N	\N
128	20	33	\N	\N
129	20	32	\N	\N
130	20	56	\N	\N
142	21	61	\N	\N
143	21	62	\N	\N
144	21	63	\N	\N
145	21	64	\N	\N
146	21	65	\N	\N
147	21	34	\N	\N
148	21	43	\N	\N
149	21	49	\N	\N
150	21	78	\N	\N
151	21	52	\N	\N
152	21	92	\N	\N
153	22	39	\N	\N
154	22	40	\N	\N
155	22	66	\N	\N
156	22	67	\N	\N
157	22	68	\N	\N
158	22	51	\N	\N
159	22	53	\N	\N
160	22	54	\N	\N
161	22	55	\N	\N
162	22	42	\N	\N
163	10	93	\N	\N
164	24	55	\N	\N
165	24	58	\N	\N
166	25	55	\N	\N
167	25	58	\N	\N
168	26	55	\N	\N
169	26	58	\N	\N
170	26	95	\N	\N
171	28	52	\N	\N
172	28	54	\N	\N
173	28	61	\N	\N
174	28	53	\N	\N
175	28	56	\N	\N
176	28	57	\N	\N
177	28	62	\N	\N
178	28	60	\N	\N
179	28	92	\N	\N
180	28	65	\N	\N
181	28	67	\N	\N
182	28	22	\N	\N
183	28	78	\N	\N
184	28	41	\N	\N
185	28	87	\N	\N
186	28	86	\N	\N
187	28	32	\N	\N
188	29	55	\N	\N
189	29	97	\N	\N
190	29	63	\N	\N
191	29	98	\N	\N
192	29	99	\N	\N
193	29	76	\N	\N
194	29	50	\N	\N
195	30	82	\N	\N
196	30	80	\N	\N
197	30	81	\N	\N
198	30	84	\N	\N
199	30	79	\N	\N
200	30	83	\N	\N
201	30	90	\N	\N
202	30	66	\N	\N
203	30	46	\N	\N
204	30	17	\N	\N
205	30	100	\N	\N
206	30	101	\N	\N
207	30	21	\N	\N
208	30	89	\N	\N
209	31	76	\N	\N
210	31	29	\N	\N
211	31	31	\N	\N
212	31	35	\N	\N
213	31	33	\N	\N
214	31	34	\N	\N
215	31	30	\N	\N
216	31	36	\N	\N
217	31	69	\N	\N
218	31	91	\N	\N
219	31	72	\N	\N
220	31	71	\N	\N
221	31	73	\N	\N
222	31	75	\N	\N
223	31	74	\N	\N
224	32	68	\N	\N
225	32	43	\N	\N
226	32	38	\N	\N
227	32	47	\N	\N
228	32	45	\N	\N
229	32	39	\N	\N
230	32	40	\N	\N
231	32	48	\N	\N
232	32	37	\N	\N
233	32	51	\N	\N
234	32	44	\N	\N
235	32	103	\N	\N
236	32	88	\N	\N
237	33	17	\N	\N
238	33	19	\N	\N
239	33	27	\N	\N
240	33	18	\N	\N
241	33	28	\N	\N
242	33	13	\N	\N
243	33	23	\N	\N
244	33	14	\N	\N
245	33	26	\N	\N
246	33	16	\N	\N
247	33	25	\N	\N
248	33	15	\N	\N
249	33	22	\N	\N
250	33	20	\N	\N
251	33	104	\N	\N
252	35	201	\N	\N
253	39	202	\N	\N
254	38	203	\N	\N
255	37	204	\N	\N
256	36	204	\N	\N
257	40	201	\N	\N
258	41	201	\N	\N
259	42	201	\N	\N
260	44	228	\N	\N
261	44	86	\N	\N
\.


--
-- Data for Name: localities; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.localities (id, cod_dpto, desc_dpto, cod_dist, desc_dist, area, cod_barrio_loc, desc_barrio_loc) FROM stdin;
1	0	ASUNCION	0	ASUNCION	1	1	SAJONIA
2	0	ASUNCION	0	ASUNCION	1	2	SAN ANTONIO
3	0	ASUNCION	0	ASUNCION	1	3	DR. GASPAR RODRIGUEZ DE FRANCIA
4	0	ASUNCION	0	ASUNCION	1	4	ITA PYTA PUNTA
5	0	ASUNCION	0	ASUNCION	1	5	LA ENCARNACION
6	0	ASUNCION	0	ASUNCION	1	6	TACUMBU
7	0	ASUNCION	0	ASUNCION	1	7	JUKYTY
8	0	ASUNCION	0	ASUNCION	1	8	LA CATEDRAL
9	0	ASUNCION	0	ASUNCION	1	9	GENERAL JOSE EDUVIGIS DIAZ
10	0	ASUNCION	0	ASUNCION	1	10	OBRERO INTENDENTE B. GUGGIARI
11	0	ASUNCION	0	ASUNCION	1	11	ROBERTO L. PETIT
12	0	ASUNCION	0	ASUNCION	1	12	RICARDO BRUGADA
13	0	ASUNCION	0	ASUNCION	1	13	SAN ROQUE
14	0	ASUNCION	0	ASUNCION	1	14	TTE. SILVIO PETTIROSSI
15	0	ASUNCION	0	ASUNCION	1	15	SAN VICENTE
16	0	ASUNCION	0	ASUNCION	1	16	PINOZA
17	0	ASUNCION	0	ASUNCION	1	17	VISTA ALEGRE
18	0	ASUNCION	0	ASUNCION	1	18	MBURICAO
19	0	ASUNCION	0	ASUNCION	1	19	GENERAL BERNARDINO CABALLERO
20	0	ASUNCION	0	ASUNCION	1	20	CIUDAD NUEVA
21	0	ASUNCION	0	ASUNCION	1	21	LAS MERCEDES
22	0	ASUNCION	0	ASUNCION	1	22	BANADO CARA CARA
23	0	ASUNCION	0	ASUNCION	1	23	JARA
24	0	ASUNCION	0	ASUNCION	1	24	MARISCAL FRANCISCO SOLANO LOPEZ
25	0	ASUNCION	0	ASUNCION	1	25	VIRGEN DEL HUERTO
26	0	ASUNCION	0	ASUNCION	1	26	BANCO SAN MIGUEL
27	0	ASUNCION	0	ASUNCION	1	27	SAN CAYETANO
28	0	ASUNCION	0	ASUNCION	1	28	REPUBLICANO
29	0	ASUNCION	0	ASUNCION	1	29	SANTA ANA
30	0	ASUNCION	0	ASUNCION	1	30	ITA ENRAMADA
31	0	ASUNCION	0	ASUNCION	1	31	SANTA LIBRADA
32	0	ASUNCION	0	ASUNCION	1	32	DE LA RESIDENTA
33	0	ASUNCION	0	ASUNCION	1	33	PRESIDENTE CARLOS ANTONIO LOPEZ
34	0	ASUNCION	0	ASUNCION	1	34	NAZARETH
35	0	ASUNCION	0	ASUNCION	1	35	TERMINAL
36	0	ASUNCION	0	ASUNCION	1	36	HIPODROMO
37	0	ASUNCION	0	ASUNCION	1	37	SAN PABLO
38	0	ASUNCION	0	ASUNCION	1	38	VILLA AURELIA
39	0	ASUNCION	0	ASUNCION	1	39	LOS LAURELES
40	0	ASUNCION	0	ASUNCION	1	40	TEMBETARY
41	0	ASUNCION	0	ASUNCION	1	41	RECOLETA
42	0	ASUNCION	0	ASUNCION	1	42	VILLA MORRA
43	0	ASUNCION	0	ASUNCION	1	43	MARISCAL JOSE FELIX ESTIGARRIBIA
44	0	ASUNCION	0	ASUNCION	1	44	SAN CRISTOBAL
45	0	ASUNCION	0	ASUNCION	1	45	YKUA SATI
46	0	ASUNCION	0	ASUNCION	1	46	LUIS ALBERTO DE HERRERA
47	0	ASUNCION	0	ASUNCION	1	47	SANTA MARIA
48	0	ASUNCION	0	ASUNCION	1	48	YTAY
49	0	ASUNCION	0	ASUNCION	1	49	SAN JORGE
50	0	ASUNCION	0	ASUNCION	1	50	TABLADA NUEVA
51	0	ASUNCION	0	ASUNCION	1	51	VIRGEN DE FATIMA
52	0	ASUNCION	0	ASUNCION	1	52	SANTA ROSA
53	0	ASUNCION	0	ASUNCION	1	53	VIRGEN DE LA ASUNCION
54	0	ASUNCION	0	ASUNCION	1	54	BELLA VISTA
55	0	ASUNCION	0	ASUNCION	1	55	SANTO DOMINGO
56	0	ASUNCION	0	ASUNCION	1	56	CANADA DEL YVYRAY
57	0	ASUNCION	0	ASUNCION	1	57	SANTISIMA TRINIDAD
58	0	ASUNCION	0	ASUNCION	1	58	MANORA
59	0	ASUNCION	0	ASUNCION	1	59	LAS LOMAS
60	0	ASUNCION	0	ASUNCION	1	60	MBURUCUYA
61	0	ASUNCION	0	ASUNCION	1	61	MADAME ELISA ALICIA LINCH
62	0	ASUNCION	0	ASUNCION	1	62	SALVADOR DEL MUNDO
63	0	ASUNCION	0	ASUNCION	1	63	MBOCAYATY
64	0	ASUNCION	0	ASUNCION	1	64	NU GUASU
65	0	ASUNCION	0	ASUNCION	1	65	LOMA PYTA
66	0	ASUNCION	0	ASUNCION	1	66	SAN BLAS
67	0	ASUNCION	0	ASUNCION	1	67	BOTANICO
68	0	ASUNCION	0	ASUNCION	1	68	ZEBALLOS KUE
101001	1	CONCEPCION	1	CONCEPCION	1	1	SAN JOSE OLERO
101002	1	CONCEPCION	1	CONCEPCION	1	2	DEL BANADO
101003	1	CONCEPCION	1	CONCEPCION	1	3	SAN AGUSTIN
101004	1	CONCEPCION	1	CONCEPCION	1	4	SAN LUIS
101005	1	CONCEPCION	1	CONCEPCION	1	5	VILLA ALTA
101006	1	CONCEPCION	1	CONCEPCION	1	6	INMACULADA
101007	1	CONCEPCION	1	CONCEPCION	1	7	VILLA ARMANDO
101009	1	CONCEPCION	1	CONCEPCION	1	9	ITACURUBI
101010	1	CONCEPCION	1	CONCEPCION	1	10	CENTRO
101011	1	CONCEPCION	1	CONCEPCION	1	11	SAN ANTONIO
101015	1	CONCEPCION	1	CONCEPCION	1	15	PRIMAVERA
101016	1	CONCEPCION	1	CONCEPCION	1	16	FATIMA
101017	1	CONCEPCION	1	CONCEPCION	1	17	SAN FRANCISCO
101018	1	CONCEPCION	1	CONCEPCION	1	18	SAN ANTONIO DE GUZMAN
101019	1	CONCEPCION	1	CONCEPCION	1	19	ASENT. SAN FRANCISCO
101020	1	CONCEPCION	1	CONCEPCION	1	20	SANTA RITA
101021	1	CONCEPCION	1	CONCEPCION	1	21	BOQUERON
101022	1	CONCEPCION	1	CONCEPCION	1	22	SAN ROQUE
101023	1	CONCEPCION	1	CONCEPCION	1	23	COM INDIG REDENCION
101024	1	CONCEPCION	1	CONCEPCION	1	24	VILLA REDENCION
101025	1	CONCEPCION	1	CONCEPCION	1	25	SAN CARLOS
101026	1	CONCEPCION	1	CONCEPCION	1	26	VILLA OLIVA
101027	1	CONCEPCION	1	CONCEPCION	1	27	SAN JORGE
101028	1	CONCEPCION	1	CONCEPCION	1	28	SANTO DOMINGO SAVIO
101029	1	CONCEPCION	1	CONCEPCION	1	29	INDUSTRIAL
101030	1	CONCEPCION	1	CONCEPCION	1	30	SAGRADA FAMILIA
101031	1	CONCEPCION	1	CONCEPCION	1	31	ASENT. SANTA MARIA
101100	1	CONCEPCION	1	CONCEPCION	6	100	CAACUPEMI
101110	1	CONCEPCION	1	CONCEPCION	6	110	CIUDAD NUEVA
101120	1	CONCEPCION	1	CONCEPCION	6	120	SALADILLO
101130	1	CONCEPCION	1	CONCEPCION	6	130	SAN CARLOS
101160	1	CONCEPCION	1	CONCEPCION	6	160	PANCHITO LOPEZ
101190	1	CONCEPCION	1	CONCEPCION	6	190	SAN BLAS
101200	1	CONCEPCION	1	CONCEPCION	6	200	MARIA AUXILIADORA
101210	1	CONCEPCION	1	CONCEPCION	6	210	SANTA ROSA 1
101220	1	CONCEPCION	1	CONCEPCION	6	220	SAN JUAN 2
101230	1	CONCEPCION	1	CONCEPCION	6	230	RINCON I
101240	1	CONCEPCION	1	CONCEPCION	6	240	POTRERO ROMERO
101250	1	CONCEPCION	1	CONCEPCION	6	250	KO'E PORA
101260	1	CONCEPCION	1	CONCEPCION	6	260	COSTA PUKU
101270	1	CONCEPCION	1	CONCEPCION	6	270	SAN RAMON
101290	1	CONCEPCION	1	CONCEPCION	6	290	SAN LUIS
101300	1	CONCEPCION	1	CONCEPCION	6	300	LAGUNA PLATO
101310	1	CONCEPCION	1	CONCEPCION	6	310	HUGUA GONZALEZ
101330	1	CONCEPCION	1	CONCEPCION	6	330	PASO HORQUETA
101390	1	CONCEPCION	1	CONCEPCION	6	390	COLONIA ROBERTO L PETIT
101400	1	CONCEPCION	1	CONCEPCION	6	400	PRIMAVERA
101620	1	CONCEPCION	1	CONCEPCION	6	620	BANCO CHACO'I
101645	1	CONCEPCION	1	CONCEPCION	6	645	CARLOS ANTONIO LOPEZ
101650	1	CONCEPCION	1	CONCEPCION	6	650	HUGUA IBANEZ
101655	1	CONCEPCION	1	CONCEPCION	6	655	SAN NICOLAS
101660	1	CONCEPCION	1	CONCEPCION	6	660	PIO X
101665	1	CONCEPCION	1	CONCEPCION	6	665	SAN FRANCISCO
101670	1	CONCEPCION	1	CONCEPCION	6	670	SAN JUAN 1
101675	1	CONCEPCION	1	CONCEPCION	6	675	COSTA FERREIRA
101680	1	CONCEPCION	1	CONCEPCION	6	680	SAGRADA FAMILIA
101685	1	CONCEPCION	1	CONCEPCION	6	685	KURUSU ISABEL
101690	1	CONCEPCION	1	CONCEPCION	6	690	DE LAS FLORES
101695	1	CONCEPCION	1	CONCEPCION	6	695	CALLEJON LAGUNA
101700	1	CONCEPCION	1	CONCEPCION	6	700	PASO ITA
101705	1	CONCEPCION	1	CONCEPCION	6	705	SAN ANTONIO
101710	1	CONCEPCION	1	CONCEPCION	6	710	KURUSU NU
101715	1	CONCEPCION	1	CONCEPCION	6	715	HUGUA TADEO
101720	1	CONCEPCION	1	CONCEPCION	6	720	SAN JOSE OBRERO
101725	1	CONCEPCION	1	CONCEPCION	6	725	BOQUERON
101730	1	CONCEPCION	1	CONCEPCION	6	730	SANTA LUCIA
101735	1	CONCEPCION	1	CONCEPCION	6	735	COLONIA RECONSTRUCCION
101745	1	CONCEPCION	1	CONCEPCION	6	745	SANTA ROSA 2
101750	1	CONCEPCION	1	CONCEPCION	6	750	HUGUA ZARZA
101755	1	CONCEPCION	1	CONCEPCION	6	755	SAN JOSE PIRITY
101815	1	CONCEPCION	1	CONCEPCION	6	815	PASO HORQUETA - SANTA LUCIA
101825	1	CONCEPCION	1	CONCEPCION	6	825	CANDIDO SILVA KILOMETRO 10
101830	1	CONCEPCION	1	CONCEPCION	6	830	PUEBLO RA
101835	1	CONCEPCION	1	CONCEPCION	6	835	COLONIA CORONEL MONGELOS
101840	1	CONCEPCION	1	CONCEPCION	6	840	MBOKAJATY
102001	1	CONCEPCION	2	BELEN	1	1	VIRGEN DE LA PAZ
102002	1	CONCEPCION	2	BELEN	1	2	SALVADOR DEL MUNDO
102003	1	CONCEPCION	2	BELEN	1	3	SANTO DOMINGO
102004	1	CONCEPCION	2	BELEN	1	4	SAN RAFAEL
102005	1	CONCEPCION	2	BELEN	1	5	SAGRADA FAMILIA
102006	1	CONCEPCION	2	BELEN	1	6	MARIA AUXILIADORA
102130	1	CONCEPCION	2	BELEN	6	130	SAN FELIPE
102150	1	CONCEPCION	2	BELEN	6	150	HIPICO
102160	1	CONCEPCION	2	BELEN	6	160	COLONIA SAN ANTONIO
102170	1	CONCEPCION	2	BELEN	6	170	SAN LUIS
102190	1	CONCEPCION	2	BELEN	6	190	REQUEJO
102200	1	CONCEPCION	2	BELEN	6	200	POTRERITO
102210	1	CONCEPCION	2	BELEN	6	210	SAN MIGUEL
102220	1	CONCEPCION	2	BELEN	6	220	SANTA ELENA
102250	1	CONCEPCION	2	BELEN	6	250	NINO SALVADOR
102260	1	CONCEPCION	2	BELEN	6	260	MBOREVI YGUA
102270	1	CONCEPCION	2	BELEN	6	270	SANTA LUCIA
102280	1	CONCEPCION	2	BELEN	6	280	SANTO TOMAS
102290	1	CONCEPCION	2	BELEN	6	290	SANTA CRUZ
102300	1	CONCEPCION	2	BELEN	6	300	PEGUAHO MI
102320	1	CONCEPCION	2	BELEN	6	320	SANTA LIBRADA
102330	1	CONCEPCION	2	BELEN	6	330	LEMOS
102340	1	CONCEPCION	2	BELEN	6	340	FATIMA
102350	1	CONCEPCION	2	BELEN	6	350	CAACUPEMI
102360	1	CONCEPCION	2	BELEN	6	360	COLONIA SAN MARTIN
102370	1	CONCEPCION	2	BELEN	6	370	PASO URUNDEY
102380	1	CONCEPCION	2	BELEN	6	380	SANTA CECILIA
102390	1	CONCEPCION	2	BELEN	6	390	SAN FRANCISCO
102400	1	CONCEPCION	2	BELEN	6	400	KILOMETRO 16
102410	1	CONCEPCION	2	BELEN	6	410	6 DE AGOSTO
102420	1	CONCEPCION	2	BELEN	6	420	ASENT. SANTA ROSA
102430	1	CONCEPCION	2	BELEN	6	430	8 DE DICIEMBRE
102440	1	CONCEPCION	2	BELEN	6	440	COM INDIG AYVU PORA
103001	1	CONCEPCION	3	HORQUETA	1	1	SAN ROQUE
103002	1	CONCEPCION	3	HORQUETA	1	2	INMACULADA
103003	1	CONCEPCION	3	HORQUETA	1	3	FATIMA
103004	1	CONCEPCION	3	HORQUETA	1	4	LAS MERCEDES
103005	1	CONCEPCION	3	HORQUETA	1	5	LAS PALMAS
103006	1	CONCEPCION	3	HORQUETA	1	6	SAN ANTONIO
103007	1	CONCEPCION	3	HORQUETA	1	7	MARIA AUXILIADORA
103100	1	CONCEPCION	3	HORQUETA	6	100	NARANJATY'I
103105	1	CONCEPCION	3	HORQUETA	6	105	SAGRADA FAMILIA 1
103110	1	CONCEPCION	3	HORQUETA	6	110	YKUA HOVY
103115	1	CONCEPCION	3	HORQUETA	6	115	SAN SEBASTIAN
103120	1	CONCEPCION	3	HORQUETA	6	120	SAN CARLOS PIRITY
103125	1	CONCEPCION	3	HORQUETA	6	125	SANTA TERESITA
103130	1	CONCEPCION	3	HORQUETA	6	130	HUGUA OCAMPOS
103135	1	CONCEPCION	3	HORQUETA	6	135	MARIA AUXILIADORA 1
103140	1	CONCEPCION	3	HORQUETA	6	140	SAN JORGE
103145	1	CONCEPCION	3	HORQUETA	6	145	NARANJATY GUASU
103170	1	CONCEPCION	3	HORQUETA	6	170	SALINAS KUE
103175	1	CONCEPCION	3	HORQUETA	6	175	ASENT. MARIA AUXILIADORA
103180	1	CONCEPCION	3	HORQUETA	6	180	COSTA CLAVEL
103185	1	CONCEPCION	3	HORQUETA	6	185	SAN PEDRO
103190	1	CONCEPCION	3	HORQUETA	6	190	BRASIL KUE
103195	1	CONCEPCION	3	HORQUETA	6	195	SAN ROQUE
103210	1	CONCEPCION	3	HORQUETA	6	210	EGUA
103215	1	CONCEPCION	3	HORQUETA	6	215	CARAGUATAY
103220	1	CONCEPCION	3	HORQUETA	6	220	BELEN KUE
103225	1	CONCEPCION	3	HORQUETA	6	225	SANTA ROSA
103230	1	CONCEPCION	3	HORQUETA	6	230	SANTO DOMINGO 1
103235	1	CONCEPCION	3	HORQUETA	6	235	SAN ANTONIO 1
103240	1	CONCEPCION	3	HORQUETA	6	240	MARIA AUXILIADORA 2
103245	1	CONCEPCION	3	HORQUETA	6	245	ALFONSO KUE
103250	1	CONCEPCION	3	HORQUETA	6	250	CRISTO REY
103255	1	CONCEPCION	3	HORQUETA	6	255	COLONIA EX COMBATIENTE
103300	1	CONCEPCION	3	HORQUETA	6	300	KILOMETRO 31 SAN MARCOS
103305	1	CONCEPCION	3	HORQUETA	6	305	SAN MIGUEL 2
103310	1	CONCEPCION	3	HORQUETA	6	310	KILOMETRO 34 SAN PEDRO
103315	1	CONCEPCION	3	HORQUETA	6	315	KURUPA'Y LOMA
103320	1	CONCEPCION	3	HORQUETA	6	320	PEGUAHO LOMA
103325	1	CONCEPCION	3	HORQUETA	6	325	SAN AGUSTIN
103340	1	CONCEPCION	3	HORQUETA	6	340	SAN JOSE
103345	1	CONCEPCION	3	HORQUETA	6	345	NUCLEO 6
103360	1	CONCEPCION	3	HORQUETA	6	360	PEGUAHO GUASU
103365	1	CONCEPCION	3	HORQUETA	6	365	15 DE AGOSTO
103370	1	CONCEPCION	3	HORQUETA	6	370	SAGRADA FAMILIA  2
103375	1	CONCEPCION	3	HORQUETA	6	375	SAN ANTONIO 2
103380	1	CONCEPCION	3	HORQUETA	6	380	YKUA PORA
103385	1	CONCEPCION	3	HORQUETA	6	385	NUCLEO 7
103390	1	CONCEPCION	3	HORQUETA	6	390	SAN MIGUEL 1
103395	1	CONCEPCION	3	HORQUETA	6	395	SAN IGNACIO
103410	1	CONCEPCION	3	HORQUETA	6	410	CAPITAN SOSA
103415	1	CONCEPCION	3	HORQUETA	3	415	CAPITAN SOSA SUB-URBANO
103420	1	CONCEPCION	3	HORQUETA	6	420	SANTO DOMINGO 2
103425	1	CONCEPCION	3	HORQUETA	6	425	ALEMAN KUE
103430	1	CONCEPCION	3	HORQUETA	6	430	PEGUAHO POTY
103435	1	CONCEPCION	3	HORQUETA	6	435	COM INDIG PASO ITA
103440	1	CONCEPCION	3	HORQUETA	6	440	PASO ITA
103445	1	CONCEPCION	3	HORQUETA	6	445	COM INDIG NANDE YVY PAVE
103450	1	CONCEPCION	3	HORQUETA	6	450	SANTA LIBRADA
103455	1	CONCEPCION	3	HORQUETA	6	455	SAN JUAN
103460	1	CONCEPCION	3	HORQUETA	6	460	LAS MERCEDES
103465	1	CONCEPCION	3	HORQUETA	6	465	COM INDIG KORAI PUNTA SUERTE
103490	1	CONCEPCION	3	HORQUETA	6	490	ESPAJIN
103500	1	CONCEPCION	3	HORQUETA	6	500	ARROYO PASITO
103505	1	CONCEPCION	3	HORQUETA	6	505	NARANJATY VALLE'I
103520	1	CONCEPCION	3	HORQUETA	6	520	SAN FELIPE
103530	1	CONCEPCION	3	HORQUETA	6	530	LAGUNA 7
103550	1	CONCEPCION	3	HORQUETA	6	550	COSTA ROMERO
103560	1	CONCEPCION	3	HORQUETA	6	560	SANTA ANA
103590	1	CONCEPCION	3	HORQUETA	6	590	CAPITAN GIMENEZ
103600	1	CONCEPCION	3	HORQUETA	6	600	TOLDO KUE
103620	1	CONCEPCION	3	HORQUETA	6	620	CEPINGO CANADA
103630	1	CONCEPCION	3	HORQUETA	6	630	TOTORA
103640	1	CONCEPCION	3	HORQUETA	6	640	CUARTELERO
103650	1	CONCEPCION	3	HORQUETA	6	650	PASO MBUTU
103660	1	CONCEPCION	3	HORQUETA	6	660	HUGUA PO'I
103670	1	CONCEPCION	3	HORQUETA	6	670	CALLE 10 ORO VERDE
103690	1	CONCEPCION	3	HORQUETA	6	690	25 DE ABRIL
103700	1	CONCEPCION	3	HORQUETA	6	700	CALLE 11 ALEMAN KUE
103710	1	CONCEPCION	3	HORQUETA	6	710	CUERO FRESCO
103720	1	CONCEPCION	3	HORQUETA	6	720	TACUARA
103730	1	CONCEPCION	3	HORQUETA	6	730	PRIMAVERA
103740	1	CONCEPCION	3	HORQUETA	6	740	ARROYO DE ORO
103780	1	CONCEPCION	3	HORQUETA	6	780	SAN RAFAEL
103790	1	CONCEPCION	3	HORQUETA	6	790	SAN BLAS
103830	1	CONCEPCION	3	HORQUETA	6	830	NUCLEO 4
103840	1	CONCEPCION	3	HORQUETA	6	840	NUCLEO 2
103850	1	CONCEPCION	3	HORQUETA	6	850	NUCLEO 3
103860	1	CONCEPCION	3	HORQUETA	6	860	NUCLEO 5
103880	1	CONCEPCION	3	HORQUETA	6	880	CHOFERES DEL CHACO
103890	1	CONCEPCION	3	HORQUETA	6	890	CANADA SAN JUAN
103900	1	CONCEPCION	3	HORQUETA	6	900	ARROYITO
103910	1	CONCEPCION	3	HORQUETA	6	910	COM INDIG ISLA SAKA Y AKA'I
103940	1	CONCEPCION	3	HORQUETA	6	940	PEGUAHO PO'I
104001	1	CONCEPCION	4	LORETO	1	1	FATIMA
104002	1	CONCEPCION	4	LORETO	1	2	SANTO DOMINGO
104003	1	CONCEPCION	4	LORETO	1	3	CENTRO
104004	1	CONCEPCION	4	LORETO	1	4	SAN FRANCISCO
104005	1	CONCEPCION	4	LORETO	1	5	NAZARETH
104100	1	CONCEPCION	4	LORETO	6	100	LAS PALMAS
104120	1	CONCEPCION	4	LORETO	6	120	SAN JOSEMI
104140	1	CONCEPCION	4	LORETO	6	140	YKUA HOVY
104150	1	CONCEPCION	4	LORETO	6	150	ZANJA KUE ROSARIO
104160	1	CONCEPCION	4	LORETO	6	160	VILLA DON BOSCO
104180	1	CONCEPCION	4	LORETO	6	180	SANTA LIBRADA
104190	1	CONCEPCION	4	LORETO	6	190	YKUA PORA
104200	1	CONCEPCION	4	LORETO	6	200	VIRGEN DEL CAMINO
104210	1	CONCEPCION	4	LORETO	6	210	HUGUA PO'I
104230	1	CONCEPCION	4	LORETO	6	230	HUGUA TORALES SAN ROQUE
104240	1	CONCEPCION	4	LORETO	6	240	SAN VICENTE
104260	1	CONCEPCION	4	LORETO	6	260	HUGUA GUASU
104270	1	CONCEPCION	4	LORETO	6	270	HUGUA BONETE
104280	1	CONCEPCION	4	LORETO	6	280	ISLERIA
104290	1	CONCEPCION	4	LORETO	6	290	SANTO DOMINGO 1
104300	1	CONCEPCION	4	LORETO	6	300	LAGUNA CRISTO REY
104310	1	CONCEPCION	4	LORETO	6	310	ANDERI
104320	1	CONCEPCION	4	LORETO	6	320	CANADA LA PAZ
104330	1	CONCEPCION	4	LORETO	6	330	COSTA FLORIDA
104340	1	CONCEPCION	4	LORETO	6	340	FATIMA
104350	1	CONCEPCION	4	LORETO	6	350	PERPETUO SOCORRO
104360	1	CONCEPCION	4	LORETO	6	360	CANADA LOURDES
104370	1	CONCEPCION	4	LORETO	6	370	SAN VICENTE-VIRGEN DEL CARMEN
104380	1	CONCEPCION	4	LORETO	6	380	LAGUNA MBOHAPY
104390	1	CONCEPCION	4	LORETO	6	390	HUGUA TORALES SAN MARCOS
104400	1	CONCEPCION	4	LORETO	6	400	HUGUA BONETE VIRGEN DEL CARMEN
104410	1	CONCEPCION	4	LORETO	6	410	HUGUA BONETE DE LA ASUNCION
104420	1	CONCEPCION	4	LORETO	6	420	ASENT. SANTO DOMINGO
104430	1	CONCEPCION	4	LORETO	6	430	CERRITO NARANJATY
104440	1	CONCEPCION	4	LORETO	6	440	ASENT. SANTO DOMINGO - 8 DE DICIEMBRE
104450	1	CONCEPCION	4	LORETO	6	450	ASENT. SANTO DOMINGO - DIVINO NINO JESUS
104460	1	CONCEPCION	4	LORETO	6	460	SAN MIGUEL
104470	1	CONCEPCION	4	LORETO	6	470	HUGUA PO'I SAN RAFAEL
104480	1	CONCEPCION	4	LORETO	6	480	PIRITY SAN CARLOS
104490	1	CONCEPCION	4	LORETO	6	490	CULANTRILLO
104500	1	CONCEPCION	4	LORETO	6	500	SAN ROQUE
104510	1	CONCEPCION	4	LORETO	6	510	LAGUNA CABECERA
104520	1	CONCEPCION	4	LORETO	6	520	SANTO DOMINGO 2
104530	1	CONCEPCION	4	LORETO	6	530	ZANJA KUE FATIMA
104540	1	CONCEPCION	4	LORETO	6	540	POTRERO TACUARA
104550	1	CONCEPCION	4	LORETO	6	550	DOMINGUEZ NIGO
104560	1	CONCEPCION	4	LORETO	6	560	PASO SENDA
105001	1	CONCEPCION	5	SAN CARLOS DEL APA	1	1	SAGRADA FAMILIA
105002	1	CONCEPCION	5	SAN CARLOS DEL APA	1	2	VISTA ALEGRE
105003	1	CONCEPCION	5	SAN CARLOS DEL APA	1	3	SAN ROQUE
105004	1	CONCEPCION	5	SAN CARLOS DEL APA	1	4	CENTRO
105100	1	CONCEPCION	5	SAN CARLOS DEL APA	6	100	ESTANCIA  ARRECIFE
105110	1	CONCEPCION	5	SAN CARLOS DEL APA	6	110	PASO BRAVO
105120	1	CONCEPCION	5	SAN CARLOS DEL APA	6	120	ZONA SAN CARLOS
105130	1	CONCEPCION	5	SAN CARLOS DEL APA	6	130	ZONA DE ESTANCIAS
106001	1	CONCEPCION	6	SAN LAZARO	1	1	SAN ANTONIO
106002	1	CONCEPCION	6	SAN LAZARO	1	2	VIRGEN DEL CARMEN
106100	1	CONCEPCION	6	SAN LAZARO	6	100	SANTO DOMINGO
106120	1	CONCEPCION	6	SAN LAZARO	6	120	TRES CERROS
106140	1	CONCEPCION	6	SAN LAZARO	6	140	JAGUARETE KUA
106150	1	CONCEPCION	6	SAN LAZARO	6	150	CERRO TIGRE
106230	1	CONCEPCION	6	SAN LAZARO	6	230	COM INDIG APA COSTA
106240	1	CONCEPCION	6	SAN LAZARO	6	240	ASENT. 1RO DE MAYO
106250	1	CONCEPCION	6	SAN LAZARO	6	250	CERRO MORADO
106260	1	CONCEPCION	6	SAN LAZARO	6	260	ZONA ESTANCIAS
106270	1	CONCEPCION	6	SAN LAZARO	6	270	SANTA ELENA
106300	1	CONCEPCION	6	SAN LAZARO	3	300	VALLEMI SAN ANTONIO SUB-URBANO
106305	1	CONCEPCION	6	SAN LAZARO	3	305	VALLEMI SANTA TERESITA SUB-URBANO
106310	1	CONCEPCION	6	SAN LAZARO	3	310	VALLEMI SAN RAMON SUB-URBANO
106315	1	CONCEPCION	6	SAN LAZARO	3	315	VALLEMI CAACUPEMI SUB-URBANO
106320	1	CONCEPCION	6	SAN LAZARO	3	320	VALLEMI SAN JUAN SUB URBANO
106325	1	CONCEPCION	6	SAN LAZARO	3	325	VALLEMI VIRGEN DE FATIMA SUB-URBANO
106330	1	CONCEPCION	6	SAN LAZARO	3	330	VALLEMI SAN JOSE SUB-URBANO
106335	1	CONCEPCION	6	SAN LAZARO	3	335	VALLEMI SAN CARLOS SUB-URBANO
106340	1	CONCEPCION	6	SAN LAZARO	3	340	VALLEMI SAN BLAS SUB-URBANO
106345	1	CONCEPCION	6	SAN LAZARO	3	345	VALLEMI SAN MARTIN SUB-URBANO
106350	1	CONCEPCION	6	SAN LAZARO	3	350	VALLEMI SAN GERARDO SUB-URBANO
106355	1	CONCEPCION	6	SAN LAZARO	3	355	TRES CERROS SUB-URBANO
107001	1	CONCEPCION	7	YBY YAU	1	1	SAN RAMON 1
107003	1	CONCEPCION	7	YBY YAU	1	3	VILLA REAL
107004	1	CONCEPCION	7	YBY YAU	1	4	SAN JUAN
107005	1	CONCEPCION	7	YBY YAU	1	5	VIRGEN DEL ROSARIO
107006	1	CONCEPCION	7	YBY YAU	1	6	MARIA  AUXILIADORA 1
107007	1	CONCEPCION	7	YBY YAU	1	7	VILLA DEL MAESTRO
107100	1	CONCEPCION	7	YBY YAU	6	100	SAN PEDRO
107120	1	CONCEPCION	7	YBY YAU	6	120	TAPYTANGUA
107130	1	CONCEPCION	7	YBY YAU	6	130	VILLALBA KUE
107140	1	CONCEPCION	7	YBY YAU	6	140	MARIA AUXILIADORA 2
107150	1	CONCEPCION	7	YBY YAU	6	150	NUEVA ESPERANZA
107170	1	CONCEPCION	7	YBY YAU	6	170	ASENT. JEPAY RA
107180	1	CONCEPCION	7	YBY YAU	6	180	CAGATA
107190	1	CONCEPCION	7	YBY YAU	6	190	PASINO
107200	1	CONCEPCION	7	YBY YAU	6	200	6 DE ENERO 1
107210	1	CONCEPCION	7	YBY YAU	6	210	CRISTO REY
107220	1	CONCEPCION	7	YBY YAU	6	220	SAN MIGUEL 1
107230	1	CONCEPCION	7	YBY YAU	6	230	EST SANTA RITA
107240	1	CONCEPCION	7	YBY YAU	6	240	KA'AGUY POTY RORY
107250	1	CONCEPCION	7	YBY YAU	6	250	LAGUNA SIETE
107280	1	CONCEPCION	7	YBY YAU	6	280	SAN ROQUE GONZALEZ DE SANTA CRUZ
107290	1	CONCEPCION	7	YBY YAU	6	290	MEDALLA MILAGROSA
107300	1	CONCEPCION	7	YBY YAU	6	300	SANTO DOMINGO
107310	1	CONCEPCION	7	YBY YAU	6	310	CRUCE BELLA VISTA
107320	1	CONCEPCION	7	YBY YAU	6	320	AQUIDABAN POTY
107330	1	CONCEPCION	7	YBY YAU	6	330	NARANJAY
107340	1	CONCEPCION	7	YBY YAU	6	340	CANADA AQUIDABAN
107350	1	CONCEPCION	7	YBY YAU	6	350	CIERVO POTRERO
107380	1	CONCEPCION	7	YBY YAU	6	380	COLONIA BERNARDINO CABALLERO
107390	1	CONCEPCION	7	YBY YAU	6	390	CERRO MEMBY
107400	1	CONCEPCION	7	YBY YAU	6	400	SANTA ANA 1
107410	1	CONCEPCION	7	YBY YAU	6	410	PUNTA PORA NU
107420	1	CONCEPCION	7	YBY YAU	6	420	SAPUCAI
107430	1	CONCEPCION	7	YBY YAU	6	430	PASO HU
107440	1	CONCEPCION	7	YBY YAU	6	440	SAGRADA FAMILIA
107450	1	CONCEPCION	7	YBY YAU	6	450	MBOKAJA'I
107460	1	CONCEPCION	7	YBY YAU	6	460	COM INDIG YRAPEY
107470	1	CONCEPCION	7	YBY YAU	6	470	COM INDIG YVY APU'A
107480	1	CONCEPCION	7	YBY YAU	6	480	SAN MIGUEL 2
107490	1	CONCEPCION	7	YBY YAU	6	490	COM INDIG MBERYVO JAGUARYMI
107500	1	CONCEPCION	7	YBY YAU	6	500	ASENT. AGUYJE
107510	1	CONCEPCION	7	YBY YAU	6	510	SANTA ANA 2
107520	1	CONCEPCION	7	YBY YAU	6	520	SANTA LIBRADA
107530	1	CONCEPCION	7	YBY YAU	6	530	SAN ROQUE
107540	1	CONCEPCION	7	YBY YAU	6	540	COM INDIG TAKUARYTIY
107550	1	CONCEPCION	7	YBY YAU	6	550	COM INDIG YPYJU TUKAMBIJU
107560	1	CONCEPCION	7	YBY YAU	6	560	AGUIJE PYAHU
107570	1	CONCEPCION	7	YBY YAU	6	570	6 DE ENERO 2
107580	1	CONCEPCION	7	YBY YAU	6	580	CERRO MOJON
107590	1	CONCEPCION	7	YBY YAU	6	590	NEPYTYVO
107600	1	CONCEPCION	7	YBY YAU	6	600	SAN RAMON 2
107610	1	CONCEPCION	7	YBY YAU	6	610	ASENT. SAN MIGUEL
107620	1	CONCEPCION	7	YBY YAU	6	620	COM INDIG YVYRAIJA
107630	1	CONCEPCION	7	YBY YAU	6	630	COM INDIG GUYRA KEHA
107640	1	CONCEPCION	7	YBY YAU	6	640	COM INDIG KA'AGUY POTY RORY
107650	1	CONCEPCION	7	YBY YAU	6	650	COM INDIG CERRO PUKA
107660	1	CONCEPCION	7	YBY YAU	6	660	COM INDIG TEKOHA KA'AGUY PORA
108001	1	CONCEPCION	8	AZOTE'Y	1	1	SANTA ANA
108002	1	CONCEPCION	8	AZOTE'Y	1	2	VIRGEN DE FATIMA
108003	1	CONCEPCION	8	AZOTE'Y	1	3	SAN ANTONIO
108100	1	CONCEPCION	8	AZOTE'Y	6	100	SANTA ANA
108110	1	CONCEPCION	8	AZOTE'Y	6	110	SAN BLAS
108120	1	CONCEPCION	8	AZOTE'Y	6	120	SANTA LIBRADA
108130	1	CONCEPCION	8	AZOTE'Y	6	130	SAN JORGE
108140	1	CONCEPCION	8	AZOTE'Y	6	140	ZANJA MOROTI
108150	1	CONCEPCION	8	AZOTE'Y	6	150	PASO TUYA
108160	1	CONCEPCION	8	AZOTE'Y	6	160	ZONA DE ESTANCIAS
108170	1	CONCEPCION	8	AZOTE'Y	6	170	CHINI
108180	1	CONCEPCION	8	AZOTE'Y	6	180	KURUSU DE HIERRO SAN FRANCISCO
108190	1	CONCEPCION	8	AZOTE'Y	6	190	KURUSU DE HIERRO SANTO DOMINGO
108200	1	CONCEPCION	8	AZOTE'Y	6	200	KURUSU DE HIERRO SAN ROQUE
108210	1	CONCEPCION	8	AZOTE'Y	6	210	KURUSU DE HIERRO CRISTO REY
108220	1	CONCEPCION	8	AZOTE'Y	6	220	COM INDIG VY'A PAVE
109001	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	1	1	PIRI POTY
109002	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	1	2	LA SUERTE
109003	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	1	3	PUENTESINO ESTE
109004	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	1	4	UNION
109005	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	1	5	SAN CLEMENTE
109006	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	1	6	LOMA PYTA
109007	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	1	7	PUENTESINO SUR
109100	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	100	29 DE JUNIO
109110	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	110	ASENT. 29 DE JUNIO
109120	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	120	ASENT. 6 DE ENERO
109130	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	130	ASENT. NORTE PYAJHU
109140	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	140	ASENT. BARRIO HERMOSA
109150	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	150	TOLDO KUE
109160	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	160	MARANEY
109170	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	170	ZONA DE ESTANCIAS
109180	1	CONCEPCION	9	SARGENTO JOSE FELIX LOPEZ	6	180	COM INDIG TAKUARITA
110001	1	CONCEPCION	10	SAN ALFREDO	1	1	CENTRO
110330	1	CONCEPCION	10	SAN ALFREDO	6	330	PASO HORQUETA
110520	1	CONCEPCION	10	SAN ALFREDO	6	520	PUERTO FONCIERE
110530	1	CONCEPCION	10	SAN ALFREDO	6	530	PUERTO ITAKUA
110560	1	CONCEPCION	10	SAN ALFREDO	6	560	PUERTO MAX
110625	1	CONCEPCION	10	SAN ALFREDO	6	625	ASENT. PAZ Y ALEGRIA
110630	1	CONCEPCION	10	SAN ALFREDO	6	630	ITAKUA SUB-URBANO
110635	1	CONCEPCION	10	SAN ALFREDO	6	635	ASENT. 8 DE NOVIEMBRE
110765	1	CONCEPCION	10	SAN ALFREDO	6	765	ASENT. SAN FRANCISCO
110770	1	CONCEPCION	10	SAN ALFREDO	6	770	COLONIA SAN ALFREDO - CHACO'I
110780	1	CONCEPCION	10	SAN ALFREDO	6	780	COLONIA SAN ALFREDO-MARIA AUXILIADORA
110790	1	CONCEPCION	10	SAN ALFREDO	6	790	COLONIA SAN ALFREDO
110800	1	CONCEPCION	10	SAN ALFREDO	6	800	CAMPITO
110805	1	CONCEPCION	10	SAN ALFREDO	6	805	ZONA DE ESTANCIAS 1
110810	1	CONCEPCION	10	SAN ALFREDO	6	810	GUYRATI
111001	1	CONCEPCION	11	PASO BARRETO	1	1	URBANO
111410	1	CONCEPCION	11	PASO BARRETO	6	410	PASO BARRETO
111420	1	CONCEPCION	11	PASO BARRETO	6	420	ISLA TUYU
111760	1	CONCEPCION	11	PASO BARRETO	6	760	COM INDIG VYA RENDA BOQUERON
111785	1	CONCEPCION	11	PASO BARRETO	6	785	JAGUARETE POTRERO
111820	1	CONCEPCION	11	PASO BARRETO	6	820	ALEGRIA
111845	1	CONCEPCION	11	PASO BARRETO	6	845	HUGUA NANDU
111850	1	CONCEPCION	11	PASO BARRETO	6	850	ZONA DE ESTANCIAS
111870	1	CONCEPCION	11	PASO BARRETO	6	870	COM INDIG JEGUAHATY
201001	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	1	SAN RAFAEL
201002	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	2	NUESTRA SENORA DE LA ASUNCION
201003	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	3	INMACULADA
201004	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	4	SAN MIGUEL
201005	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	5	SAN JOSE
201006	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	6	SAN ROQUE
201007	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	7	SANTA ANA
201008	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	8	FATIMA
201009	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	1	9	MARIA AUXILIADORA
201100	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	100	COSTA PUKU
201130	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	130	LOMA PYTA
201140	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	140	TAPE KA'AGUY
201150	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	150	ZOLABARRIETA
201160	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	160	CANADA
201170	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	170	PICADA FERNANDEZ
201180	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	180	ROSARIO LOMA
201190	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	190	NANDU KUA
201200	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	200	CURUPAYTY
201220	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	220	YBAROTY
201230	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	230	SAN JOSE 2
201240	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	240	CORREA RUGUA
201250	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	250	YATEBO
201260	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	260	SARGENTO LOMA
201290	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	290	JAKARE NE'E
201320	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	320	SAN JOSE 1
201330	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	330	NARANJATY
201350	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	350	GARRIGOZA
201360	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	360	AGUARAYMI
201390	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	390	PIRI PUKU
201420	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	420	HUGUA GUASU
201440	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	440	ASENT. CORPUS CHRISTI 15 DE MAYO
201450	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	450	AGUARAY
201460	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	460	HUGUA SANTA CATALINA
201480	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	480	SAN IGNACIO
201490	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	490	COLONIA BARBERO
201500	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	500	QUIINDY
201530	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	530	ASENT. VIRGEN DE FATIMA
201540	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	540	FONDO SAN BLAS
201560	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	560	NARANJO
201580	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	580	INMACULADA 2
201645	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	3	645	PUERTO YVAPOVO SUB-URBANO
201660	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	660	SAN ANTONIO 2
201680	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	680	COLONIA SAN JUAN
201690	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	690	YVAPOVO
201705	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	705	COM INDIG YVY POTY
201710	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	710	VILLA AURORA
201715	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	715	SANTA ROSA 2
201720	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	720	NUESTRA SENORA DE LA ASUNCION
201725	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	725	ZONA DE ESTANCIAS NORTE
201730	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	730	INMACULADA 1
201735	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	735	ASENT. MESQUITA
201740	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	740	ASENT. SAN JORGE
201745	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	745	ASENT. SAN FRANCISCO
201750	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	750	SANTA ANA
201760	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	760	PUERTO SAN ROQUE
201770	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	770	ANGELITA
201780	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	780	SANTO TOMAS
201790	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	790	YPAJERE
201800	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	800	MBOKAJATY
201810	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	810	ASENT. EX TAON
201820	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	820	SAN ISIDRO
201830	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	830	JUAN PABLO II
201840	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	840	ASENT. SANTA CATALINA
201850	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	850	SANTA ROSA 1
201860	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	860	MARIA AUXILIADORA
201870	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	870	SAN DIEGO LOMA
201880	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	880	ROJAS RUGUA
201890	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	890	SAN JUAN
201900	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	900	SANTA LUCIA
201910	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	910	SAN ANTONIO 1
201920	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	920	PANCHITO LOPEZ
201930	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	930	ASENT. CORPUS CHRISTI CALLE 3000
201940	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	940	SAN FRANCISCO
201950	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	950	ASENT. CORPUS CHRISTI CALLE 2000
201960	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	960	ASENT. CORPUS CHRISTI CALLE 1000
201970	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	970	MOREIRA
201980	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	980	LOS LAPACHOS
201990	2	SAN PEDRO	1	SAN PEDRO DEL YCUAMANDYYU	6	990	PAVON KUE
202001	2	SAN PEDRO	2	ANTEQUERA	1	1	CURUZU CHICA
202002	2	SAN PEDRO	2	ANTEQUERA	1	2	VIRGEN DE FATIMA
202003	2	SAN PEDRO	2	ANTEQUERA	1	3	SANTO DOMINGO
202004	2	SAN PEDRO	2	ANTEQUERA	1	4	SAN ROQUE
202005	2	SAN PEDRO	2	ANTEQUERA	1	5	SAGRADA FAMILIA
202100	2	SAN PEDRO	2	ANTEQUERA	6	100	ESTANCIA VIRADOLCE
202110	2	SAN PEDRO	2	ANTEQUERA	6	110	KILOMETRO 3
202120	2	SAN PEDRO	2	ANTEQUERA	6	120	1RA VISTA
202130	2	SAN PEDRO	2	ANTEQUERA	6	130	PICADA ANTEQUERA
202150	2	SAN PEDRO	2	ANTEQUERA	6	150	ESTANCIA PY PORE
202160	2	SAN PEDRO	2	ANTEQUERA	6	160	POROTO
202170	2	SAN PEDRO	2	ANTEQUERA	6	170	PUERTO BARRANQUERITA
202180	2	SAN PEDRO	2	ANTEQUERA	6	180	MONTE ALTO
203001	2	SAN PEDRO	3	CHORE	1	1	SANTISIMA TRINIDAD
203002	2	SAN PEDRO	3	CHORE	1	2	SANTA LUCIA
203003	2	SAN PEDRO	3	CHORE	1	3	SAN VICENTE
203004	2	SAN PEDRO	3	CHORE	1	4	SAN ROQUE
203005	2	SAN PEDRO	3	CHORE	1	5	ALBORADA
203100	2	SAN PEDRO	3	CHORE	6	100	NUCLEAR 2
203180	2	SAN PEDRO	3	CHORE	6	180	SANTA ANA
203270	2	SAN PEDRO	3	CHORE	6	270	SANTA LUCIA 1
203300	2	SAN PEDRO	3	CHORE	6	300	1RO DE MARZO
203350	2	SAN PEDRO	3	CHORE	6	350	SAN ROQUE
203360	2	SAN PEDRO	3	CHORE	6	360	COLONIA INDUSTRIAL KUE
203370	2	SAN PEDRO	3	CHORE	6	370	SAN AGUSTIN
203380	2	SAN PEDRO	3	CHORE	6	380	CHORE I
203390	2	SAN PEDRO	3	CHORE	6	390	CHORE MI
203400	2	SAN PEDRO	3	CHORE	6	400	PLACIDO
203410	2	SAN PEDRO	3	CHORE	6	410	NARANJA HAI
203440	2	SAN PEDRO	3	CHORE	6	440	MONSENOR AQUINO
203450	2	SAN PEDRO	3	CHORE	6	450	MARTILLO
203460	2	SAN PEDRO	3	CHORE	6	460	NUCLEAR 3
203470	2	SAN PEDRO	3	CHORE	6	470	NUCLEAR 1
203480	2	SAN PEDRO	3	CHORE	6	480	SANTO DOMINGO
203510	2	SAN PEDRO	3	CHORE	6	510	SAN LUIS
203520	2	SAN PEDRO	3	CHORE	6	520	HUGUA POTI
203530	2	SAN PEDRO	3	CHORE	6	530	25 DE MAYO
203550	2	SAN PEDRO	3	CHORE	6	550	KO'E POTY
203570	2	SAN PEDRO	3	CHORE	6	570	NACIENTE
203590	2	SAN PEDRO	3	CHORE	6	590	YKUA PORA 2DA LINEA
203600	2	SAN PEDRO	3	CHORE	6	600	YKUA PORA 3RA LINEA
203630	2	SAN PEDRO	3	CHORE	6	630	LA NINA
203640	2	SAN PEDRO	3	CHORE	6	640	15 DE AGOSTO
203650	2	SAN PEDRO	3	CHORE	6	650	LIBERACION SUR
203660	2	SAN PEDRO	3	CHORE	6	660	SAN FRANCISCO
203670	2	SAN PEDRO	3	CHORE	6	670	KOKUERA CALLE SANTA ROSA
203690	2	SAN PEDRO	3	CHORE	6	690	KOKUERA MARIA AUXILIADORA
203700	2	SAN PEDRO	3	CHORE	6	700	KOKUERA CALLE SANTA LUCIA
203710	2	SAN PEDRO	3	CHORE	6	710	KOKUERA CALLE SAN BLAS
203720	2	SAN PEDRO	3	CHORE	6	720	KOKUERA CALLE SAN ANTONIO
203730	2	SAN PEDRO	3	CHORE	6	730	SAGRADA FAMILIA
203740	2	SAN PEDRO	3	CHORE	6	740	SAN RAFAEL
203760	2	SAN PEDRO	3	CHORE	6	760	SAN ANTONIO
203770	2	SAN PEDRO	3	CHORE	6	770	ASENT. SAN FRANCISCO KOKUERA
203780	2	SAN PEDRO	3	CHORE	6	780	KOKUERA CALLE SAN FRANCISCO
203790	2	SAN PEDRO	3	CHORE	6	790	SAN PEDRO
203800	2	SAN PEDRO	3	CHORE	6	800	SANTA LUCIA 2
203810	2	SAN PEDRO	3	CHORE	6	810	SAN MIGUEL
203820	2	SAN PEDRO	3	CHORE	6	820	SAN BLAS 2
203830	2	SAN PEDRO	3	CHORE	6	830	SAN JOSE
203840	2	SAN PEDRO	3	CHORE	6	840	KOKUERA CALLE SAN JUAN
203850	2	SAN PEDRO	3	CHORE	6	850	CARAGUATAY
203860	2	SAN PEDRO	3	CHORE	6	860	4 BOCAS
203870	2	SAN PEDRO	3	CHORE	6	870	MARIA AUXILIADORA SUR
203880	2	SAN PEDRO	3	CHORE	6	880	MARIA AUXILIADORA NORTE
203890	2	SAN PEDRO	3	CHORE	6	890	6 DE ENERO
203900	2	SAN PEDRO	3	CHORE	6	900	VILLA ALEGRE
203910	2	SAN PEDRO	3	CHORE	6	910	ROSA MISTICA
203915	2	SAN PEDRO	3	CHORE	3	915	VILLA ELITA SUB-URBANO
203920	2	SAN PEDRO	3	CHORE	6	920	ROSARINO
203930	2	SAN PEDRO	3	CHORE	6	930	SAN BLAS 1
203940	2	SAN PEDRO	3	CHORE	6	940	LIBERACION NORTE
203950	2	SAN PEDRO	3	CHORE	6	950	DEFENSORES DEL CHACO
203960	2	SAN PEDRO	3	CHORE	6	960	SAN JOSE -YERUTI
203970	2	SAN PEDRO	3	CHORE	6	970	PINDOTY
203980	2	SAN PEDRO	3	CHORE	6	980	LINEA CARAGUATAY
203990	2	SAN PEDRO	3	CHORE	6	990	PANCHITO LOPEZ
204001	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	1	SANTA LIBRADA
204003	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	3	SAN JOSE
204005	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	5	SAN FRANCISCO
204006	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	6	CANADA
204007	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	7	FATIMA
204008	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	8	SANTA ISABEL
204009	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	9	SAN ANTONIO
204010	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	10	SAN PEDRO
204011	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	1	11	PROGRESO
204100	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	100	MANDYJU
204110	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	110	REDONDO
204130	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	130	CHAMORRO KUE
204140	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	140	NUMBUE
204150	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	150	1 DE MARZO
204160	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	160	SARGENTO CASTIGLIONI
204170	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	170	HUGUA PO'I
204180	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	180	SANTA LIBRADA
204190	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	190	PIRAY
204200	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	200	SANTA CLARA
204205	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	3	205	SANTA CLARA SUB-URBANO
204210	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	210	LLANTEN
204220	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	220	AGUILERA KUE
204230	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	230	YKUA PINDO
204240	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	240	CAMPO VIRGEN
204250	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	250	SAN RAMON
204260	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	260	SAN LUIS
204270	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	270	SAN VICENTE
204280	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	280	MARIA AUXILIADORA 1
204290	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	290	YKUA RUGUA
204320	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	320	SAN ISIDRO
204330	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	330	3 DE MAYO
204340	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	340	COMPANIA CORRALES
204350	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	350	JURUHEI
204360	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	360	NORTE POTY
204370	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	370	PINDOTY
204380	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	380	HUGUA GUASU 1RA LINEA
204390	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	390	HUGUA REY
204400	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	400	SAGRADA FAMILIA
204410	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	410	SARGENTO MOREL
204420	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	420	SANTA ROSA
204430	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	430	LOURDES
204440	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	440	NATIVIDAD
204450	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	450	8 DE DICIEMBRE 1
204460	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	460	COM INDIG MBOI CUA
204470	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	470	15 DE SETIEMBRE
204480	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	480	CALLE 15
204490	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	490	CARRILLO KUE
204500	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	500	CALLE 18
204510	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	510	15 DE MAYO
204520	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	520	ASENT. NUCLEAR 15 DE MAYO
204530	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	530	4 VIENTOS
204540	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	540	SAN FRANCISCO 1
204550	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	550	SANTO DOMINGO
204560	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	560	13 DE JUNIO
204570	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	570	SAN FELIPE
204580	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	580	ARASA POTY
204590	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	590	CRISTO REY
204600	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	600	SAN ANTONIO
204610	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	610	HUGUA REY SANTO TOMAS
204620	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	620	TOLEDO
204630	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	630	SAN BLAS 2
204640	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	640	FATIMA
204650	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	650	SAN MIGUEL
204660	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	660	MBOI KUA
204670	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	670	YKUA NINO
204680	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	680	COLONIA MBARETE
204690	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	690	TAKUARO
204700	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	700	SAN BLAS 1
204710	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	710	CALLE LUJAN
204720	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	720	8 DE DICIEMBRE 2
204730	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	730	HUGUA GUASU JETYTY
204740	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	740	PUNTA PORA
204750	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	750	SAN RAFAEL
204760	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	760	NINO JESUS
204770	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	770	SAN FRANCISCO 2
204780	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	780	SAN JOSE 1
204790	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	790	SAN JOSE 2
204800	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	800	MARIA AUXILIADORA 2
204810	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	810	HUGUA GUASU
204820	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	820	HUGUA MERCEDES
204830	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	830	SAN BLAS 3
204840	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	840	4 MIL MBARETE
204850	2	SAN PEDRO	4	GENERAL ELIZARDO AQUINO	6	850	POLENTO KUE
205001	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	1	1	SAN RAFAEL
205002	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	1	2	SAN MIGUEL
205003	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	1	3	SAN ANTONIO
205004	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	1	4	VIRGEN DEL ROSARIO
205005	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	1	5	SAN FRANCISCO
205006	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	1	6	SAN JOSE
205100	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	100	JATEVU
205110	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	110	PEGUAHO
205120	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	120	CAMPO VIRGEN
205130	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	130	SARGENTO MOREL
205140	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	140	HUGUA PORA
205150	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	150	SARGENTO CASTIGLIONI
205160	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	160	CAPILLA HUGUA
205170	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	170	AGUAPEY
205175	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	3	175	AGUAPEY SUB-URBANO
205190	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	190	HUGUA'I
205200	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	200	RAMOS KUE
205210	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	210	RIO RUGUA
205220	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	220	LAGUNA MOJON
205230	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	230	ISLA CARAYA
205240	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	240	CAROLINA
205270	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	270	COLONIA FRIESLAND
205275	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	3	275	COLONIA FRIESLAND SUB-URBANO
205280	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	280	COLONIA TUYANGO
205290	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	290	ALDEA 1 FRIESLAND
205300	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	300	KAVAJU RA'Y
205310	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	310	BARRERO
205320	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	320	GENERAL CACERES
205330	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	330	SAN ALFREDO
205340	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	340	MBOKAJATY
205350	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	350	JACINTO COLARTE
205360	2	SAN PEDRO	5	ITACURUBI DEL ROSARIO	6	360	ALDEA 2 FRIESLAND
206001	2	SAN PEDRO	6	LIMA	1	1	INMACULADA CONCEPCION
206002	2	SAN PEDRO	6	LIMA	1	2	SAN FRANCISCO
206003	2	SAN PEDRO	6	LIMA	1	3	FRAY PEDRO BARTOLOME
206004	2	SAN PEDRO	6	LIMA	1	4	SAN JOSE
206005	2	SAN PEDRO	6	LIMA	1	5	INDIO GUAIRAYU
206100	2	SAN PEDRO	6	LIMA	6	100	COSTA PUKU 2
206110	2	SAN PEDRO	6	LIMA	6	110	CEDRAN KUE
206130	2	SAN PEDRO	6	LIMA	6	130	PASO TUNA
206140	2	SAN PEDRO	6	LIMA	6	140	CARUMBEY 1
206150	2	SAN PEDRO	6	LIMA	6	150	CARUMBEY 2
206160	2	SAN PEDRO	6	LIMA	6	160	SARGENTO MONTANIA
206180	2	SAN PEDRO	6	LIMA	6	180	LOMA CLAVEL
206200	2	SAN PEDRO	6	LIMA	6	200	YVYPE
206210	2	SAN PEDRO	6	LIMA	6	210	NINO MARTIR
206240	2	SAN PEDRO	6	LIMA	6	240	ASENT. PRIMAVERA
206250	2	SAN PEDRO	6	LIMA	6	250	NARANJATY
206260	2	SAN PEDRO	6	LIMA	6	260	ASENT. SANGUINA KUE
206270	2	SAN PEDRO	6	LIMA	6	270	ASENT. SAN PABLO
206280	2	SAN PEDRO	6	LIMA	6	280	SAN FELIPE
206290	2	SAN PEDRO	6	LIMA	6	290	SAN ANTONIO
206300	2	SAN PEDRO	6	LIMA	6	300	VIRGEN DE FATIMA
206310	2	SAN PEDRO	6	LIMA	6	310	ASENT. NUEVA JERUSALEN
206320	2	SAN PEDRO	6	LIMA	6	320	ASENT. PIRO'Y
206330	2	SAN PEDRO	6	LIMA	6	330	ASENT. YVYRA PETEI
206340	2	SAN PEDRO	6	LIMA	6	340	ASENT. PRIMAVERA II
206350	2	SAN PEDRO	6	LIMA	6	350	PERPETUO SOCORRO
206360	2	SAN PEDRO	6	LIMA	6	360	ASENT. VILLA JUVENTUD
206370	2	SAN PEDRO	6	LIMA	6	370	VIRGEN DE LA ASUNCION
207001	2	SAN PEDRO	7	NUEVA GERMANIA	1	1	SAGRADA FAMILIA
207002	2	SAN PEDRO	7	NUEVA GERMANIA	1	2	SAN FELIPE
207003	2	SAN PEDRO	7	NUEVA GERMANIA	1	3	SAN ROQUE
207004	2	SAN PEDRO	7	NUEVA GERMANIA	1	4	VIRGEN DE FATIMA
207110	2	SAN PEDRO	7	NUEVA GERMANIA	6	110	COLONIA RIO VERDE
207120	2	SAN PEDRO	7	NUEVA GERMANIA	6	120	NUEVO MEXICO
207130	2	SAN PEDRO	7	NUEVA GERMANIA	6	130	COLONIA LA VICTORIA SAN ANTONIO
207150	2	SAN PEDRO	7	NUEVA GERMANIA	6	150	COSTA NORTE
207170	2	SAN PEDRO	7	NUEVA GERMANIA	6	170	ASENT. COLONIA
207180	2	SAN PEDRO	7	NUEVA GERMANIA	6	180	TAKURUTY
207190	2	SAN PEDRO	7	NUEVA GERMANIA	6	190	CHAMORRO KUE
207200	2	SAN PEDRO	7	NUEVA GERMANIA	6	200	CERRITO
207210	2	SAN PEDRO	7	NUEVA GERMANIA	6	210	CHACO'I
207220	2	SAN PEDRO	7	NUEVA GERMANIA	6	220	COSTA AZUL
207230	2	SAN PEDRO	7	NUEVA GERMANIA	6	230	ASENT. TIERRA PROMETIDA
207240	2	SAN PEDRO	7	NUEVA GERMANIA	6	240	ISLA SOLA
207250	2	SAN PEDRO	7	NUEVA GERMANIA	6	250	ASENT. GERMANINA
207260	2	SAN PEDRO	7	NUEVA GERMANIA	6	260	RINCON
207270	2	SAN PEDRO	7	NUEVA GERMANIA	6	270	COM INDIG YAPY POTY
208002	2	SAN PEDRO	8	SAN ESTANISLAO	1	2	TAPIRACUAI
208003	2	SAN PEDRO	8	SAN ESTANISLAO	1	3	SANTA BARBARA
208004	2	SAN PEDRO	8	SAN ESTANISLAO	1	4	SAN ANTONIO
208005	2	SAN PEDRO	8	SAN ESTANISLAO	1	5	MONGELOS
208006	2	SAN PEDRO	8	SAN ESTANISLAO	1	6	MONTE ALTO
208008	2	SAN PEDRO	8	SAN ESTANISLAO	1	8	CERRITO
208009	2	SAN PEDRO	8	SAN ESTANISLAO	1	9	ASENT. CERRITO
208010	2	SAN PEDRO	8	SAN ESTANISLAO	1	10	SAN JOSE
208011	2	SAN PEDRO	8	SAN ESTANISLAO	1	11	SAN MARTIN
208012	2	SAN PEDRO	8	SAN ESTANISLAO	1	12	SAN AGUSTIN
208013	2	SAN PEDRO	8	SAN ESTANISLAO	1	13	YKUA PA'I
208014	2	SAN PEDRO	8	SAN ESTANISLAO	1	14	CENTRO
208015	2	SAN PEDRO	8	SAN ESTANISLAO	1	15	YATAITY CORA
208016	2	SAN PEDRO	8	SAN ESTANISLAO	1	16	SIRATY
208017	2	SAN PEDRO	8	SAN ESTANISLAO	1	17	8 DE DICIEMBRE
208018	2	SAN PEDRO	8	SAN ESTANISLAO	1	18	ASENT. NINO JESUS
208019	2	SAN PEDRO	8	SAN ESTANISLAO	1	19	NINO JESUS
208020	2	SAN PEDRO	8	SAN ESTANISLAO	1	20	LOURDES
208021	2	SAN PEDRO	8	SAN ESTANISLAO	1	21	ASENT. LOURDES
208022	2	SAN PEDRO	8	SAN ESTANISLAO	1	22	ASENT. LA VICTORIA
208100	2	SAN PEDRO	8	SAN ESTANISLAO	6	100	COLONIA PRIMAVERA
208115	2	SAN PEDRO	8	SAN ESTANISLAO	6	115	1RO DE MAYO
208125	2	SAN PEDRO	8	SAN ESTANISLAO	6	125	ASENT.  JULIAN PORTILLO
208130	2	SAN PEDRO	8	SAN ESTANISLAO	6	130	BOLA KUA
208135	2	SAN PEDRO	8	SAN ESTANISLAO	6	135	ASENT.  SEBASTIAN LARROSA
208145	2	SAN PEDRO	8	SAN ESTANISLAO	6	145	ASENT. JULIAN JARA
208155	2	SAN PEDRO	8	SAN ESTANISLAO	6	155	ASENT. SANTA ROSA
208165	2	SAN PEDRO	8	SAN ESTANISLAO	6	165	ASENT. TACUARA
208175	2	SAN PEDRO	8	SAN ESTANISLAO	6	175	COM INDIG JAVIER KUE RUGUA
208185	2	SAN PEDRO	8	SAN ESTANISLAO	6	185	COM INDIG SAN ANTONIO
208195	2	SAN PEDRO	8	SAN ESTANISLAO	6	195	LOTE I
208200	2	SAN PEDRO	8	SAN ESTANISLAO	6	200	CALLE 12000 BERTONI
208220	2	SAN PEDRO	8	SAN ESTANISLAO	6	220	CALLE 10000 BERTONI
208240	2	SAN PEDRO	8	SAN ESTANISLAO	6	240	CALLE 8000 BERTONI
208290	2	SAN PEDRO	8	SAN ESTANISLAO	6	290	CALLE 6000 MARENGO
208300	2	SAN PEDRO	8	SAN ESTANISLAO	6	300	CALLE 8000 MARENGO
208310	2	SAN PEDRO	8	SAN ESTANISLAO	6	310	CALLE 10000 MARENGO
208340	2	SAN PEDRO	8	SAN ESTANISLAO	6	340	CALLE 6000 BERTONI
208360	2	SAN PEDRO	8	SAN ESTANISLAO	6	360	CALLE 4000 BERTONI
208370	2	SAN PEDRO	8	SAN ESTANISLAO	6	370	TAKURUTY
208380	2	SAN PEDRO	8	SAN ESTANISLAO	6	380	CALLE 2000 BERTONI
208390	2	SAN PEDRO	8	SAN ESTANISLAO	6	390	SANTA ROSA
208410	2	SAN PEDRO	8	SAN ESTANISLAO	6	410	HACHITA
208430	2	SAN PEDRO	8	SAN ESTANISLAO	6	430	ORIENTAL
208450	2	SAN PEDRO	8	SAN ESTANISLAO	6	450	CALLE 1000 BERTONI
208460	2	SAN PEDRO	8	SAN ESTANISLAO	6	460	VAKA HU
208465	2	SAN PEDRO	8	SAN ESTANISLAO	6	465	COSTA TAPIRACUAI
208470	2	SAN PEDRO	8	SAN ESTANISLAO	6	470	CALLE 6000 DEFENSORES DEL CHACO
208475	2	SAN PEDRO	8	SAN ESTANISLAO	6	475	SAN BLAS 2
208480	2	SAN PEDRO	8	SAN ESTANISLAO	6	480	KURURU CENTRO
208485	2	SAN PEDRO	8	SAN ESTANISLAO	6	485	PUNTA SUERTE
208490	2	SAN PEDRO	8	SAN ESTANISLAO	6	490	NOVIRETA
208495	2	SAN PEDRO	8	SAN ESTANISLAO	6	495	COSTA BARRETO
208500	2	SAN PEDRO	8	SAN ESTANISLAO	6	500	CRUCE BERTONI
208510	2	SAN PEDRO	8	SAN ESTANISLAO	6	510	CALLE 8000 DEFENSORES DEL CHACO
208520	2	SAN PEDRO	8	SAN ESTANISLAO	6	520	ARROYO GUASU
208525	2	SAN PEDRO	8	SAN ESTANISLAO	6	525	COM INDIG ARROYO MOROTI
208530	2	SAN PEDRO	8	SAN ESTANISLAO	6	530	YATAITY CORA
208535	2	SAN PEDRO	8	SAN ESTANISLAO	6	535	TAPIRACUAI
208560	2	SAN PEDRO	8	SAN ESTANISLAO	6	560	CALLE 10000 DEFENSORES DEL CHACO
208565	2	SAN PEDRO	8	SAN ESTANISLAO	6	565	SAN ISIDRO 2
208570	2	SAN PEDRO	8	SAN ESTANISLAO	6	570	SAN BLAS 1
208585	2	SAN PEDRO	8	SAN ESTANISLAO	6	585	YKUA TU'I
208590	2	SAN PEDRO	8	SAN ESTANISLAO	6	590	CALLE  12000 DEFENSORES DEL CHACO
208600	2	SAN PEDRO	8	SAN ESTANISLAO	6	600	ITAPE GUY
208610	2	SAN PEDRO	8	SAN ESTANISLAO	6	610	SIRATY
208620	2	SAN PEDRO	8	SAN ESTANISLAO	6	620	REPUBLICANO
208630	2	SAN PEDRO	8	SAN ESTANISLAO	6	630	TAKUARA
208640	2	SAN PEDRO	8	SAN ESTANISLAO	6	640	VIRGEN DE FATIMA
208645	2	SAN PEDRO	8	SAN ESTANISLAO	3	645	VIRGEN DE FATIMA SUB-URBANO CONAVI
208660	2	SAN PEDRO	8	SAN ESTANISLAO	6	660	NU PO'I
208680	2	SAN PEDRO	8	SAN ESTANISLAO	6	680	MONTE ALTO
208685	2	SAN PEDRO	8	SAN ESTANISLAO	6	685	SANTA BARBARA
208700	2	SAN PEDRO	8	SAN ESTANISLAO	6	700	COSTA PUKU
208720	2	SAN PEDRO	8	SAN ESTANISLAO	6	720	GUAICA
208725	2	SAN PEDRO	8	SAN ESTANISLAO	3	725	GUAICA SUB-URBANO
208750	2	SAN PEDRO	8	SAN ESTANISLAO	6	750	SAN ISIDRO 1
208755	2	SAN PEDRO	8	SAN ESTANISLAO	6	755	SAN JOSE
208760	2	SAN PEDRO	8	SAN ESTANISLAO	6	760	ARROYO MOROTI
208765	2	SAN PEDRO	8	SAN ESTANISLAO	6	765	ASENT. SAN FRANCISCO
208770	2	SAN PEDRO	8	SAN ESTANISLAO	6	770	SAN FRANCISCO
208780	2	SAN PEDRO	8	SAN ESTANISLAO	6	780	CALLE 20 DE ENERO
208790	2	SAN PEDRO	8	SAN ESTANISLAO	6	790	SANTA ANA
208880	2	SAN PEDRO	8	SAN ESTANISLAO	6	880	SAN ANTONIO 1
208885	2	SAN PEDRO	8	SAN ESTANISLAO	6	885	SANTA LIBRADA
208890	2	SAN PEDRO	8	SAN ESTANISLAO	6	890	SANTO DOMINGO
208895	2	SAN PEDRO	8	SAN ESTANISLAO	6	895	SAN JOSE DEL NORTE
208900	2	SAN PEDRO	8	SAN ESTANISLAO	6	900	SAN VALENTIN
208905	2	SAN PEDRO	8	SAN ESTANISLAO	6	905	SAN JOSE OBRERO
208910	2	SAN PEDRO	8	SAN ESTANISLAO	6	910	TAPIRACUAI LOMA
208915	2	SAN PEDRO	8	SAN ESTANISLAO	6	915	GUARANI KA'AGUY
208920	2	SAN PEDRO	8	SAN ESTANISLAO	6	920	YVYRAJU
208925	2	SAN PEDRO	8	SAN ESTANISLAO	6	925	SANTA CATALINA
208930	2	SAN PEDRO	8	SAN ESTANISLAO	6	930	ASENT. YVYRAJU
208940	2	SAN PEDRO	8	SAN ESTANISLAO	6	940	CRUCE GUAICA
208950	2	SAN PEDRO	8	SAN ESTANISLAO	6	950	SAN ANTONIO 2
208960	2	SAN PEDRO	8	SAN ESTANISLAO	6	960	KURURU 2DA LINEA
208970	2	SAN PEDRO	8	SAN ESTANISLAO	6	970	KURURU 3RA LINEA
208980	2	SAN PEDRO	8	SAN ESTANISLAO	6	980	MARENGO
208990	2	SAN PEDRO	8	SAN ESTANISLAO	6	990	CALLE 5000 MARENGO
209001	2	SAN PEDRO	9	SAN PABLO	1	1	SAN ANTONIO
209002	2	SAN PEDRO	9	SAN PABLO	1	2	FATIMA
209003	2	SAN PEDRO	9	SAN PABLO	1	3	MARIA AUXILIADORA
209004	2	SAN PEDRO	9	SAN PABLO	1	4	SAGRADA FAMILIA
209100	2	SAN PEDRO	9	SAN PABLO	6	100	TATARE NORTE
209110	2	SAN PEDRO	9	SAN PABLO	6	110	RUTARA
209120	2	SAN PEDRO	9	SAN PABLO	6	120	TATARE SUR
209130	2	SAN PEDRO	9	SAN PABLO	6	130	SAN RAMON
209140	2	SAN PEDRO	9	SAN PABLO	6	140	SAN ANTONIO I
209150	2	SAN PEDRO	9	SAN PABLO	6	150	ASENT. PYRENDA
209160	2	SAN PEDRO	9	SAN PABLO	6	160	SAN ISIDRO
209170	2	SAN PEDRO	9	SAN PABLO	6	170	FATIMA
209180	2	SAN PEDRO	9	SAN PABLO	6	180	KOKUERE
209190	2	SAN PEDRO	9	SAN PABLO	6	190	ASENT. KOKUERE
209200	2	SAN PEDRO	9	SAN PABLO	6	200	SAN ANTONIO II
209210	2	SAN PEDRO	9	SAN PABLO	6	210	CALLE SAN JUAN
209220	2	SAN PEDRO	9	SAN PABLO	6	220	CALLE ARROYO
209230	2	SAN PEDRO	9	SAN PABLO	6	230	POTRERO PUKU
209240	2	SAN PEDRO	9	SAN PABLO	6	240	SAGRADO CORAZON DE JESUS
210001	2	SAN PEDRO	10	TACUATI	1	1	VIRGEN DEL CARMEN
210002	2	SAN PEDRO	10	TACUATI	1	2	LAS MERCEDES
210003	2	SAN PEDRO	10	TACUATI	1	3	SANTA ROSA
210004	2	SAN PEDRO	10	TACUATI	1	4	SAN PEDRO
210005	2	SAN PEDRO	10	TACUATI	1	5	MARIA AUXILIADORA
210006	2	SAN PEDRO	10	TACUATI	1	6	NUESTRA SENORA DE LA ASUNCION
210100	2	SAN PEDRO	10	TACUATI	6	100	PLANTA 1
210105	2	SAN PEDRO	10	TACUATI	6	105	ASENT. 6 DE ENERO
210110	2	SAN PEDRO	10	TACUATI	6	110	SANTA MARIA
210115	2	SAN PEDRO	10	TACUATI	6	115	6 DE ENERO
210120	2	SAN PEDRO	10	TACUATI	6	120	SANTA CLARA
210130	2	SAN PEDRO	10	TACUATI	6	130	MBARAKA
210140	2	SAN PEDRO	10	TACUATI	6	140	COLONIA MANITOBA
210145	2	SAN PEDRO	10	TACUATI	6	145	COM INDIG JEROKY OKA
210150	2	SAN PEDRO	10	TACUATI	6	150	ESTANCIA PERONI
210155	2	SAN PEDRO	10	TACUATI	6	155	COM INDIG YVAMINDY
210160	2	SAN PEDRO	10	TACUATI	6	160	ESTANCIA ALEGRIA
210165	2	SAN PEDRO	10	TACUATI	6	165	ASENT. YVAMINDY
210170	2	SAN PEDRO	10	TACUATI	6	170	CRUCE TACUATI
210175	2	SAN PEDRO	10	TACUATI	6	175	COM INDIG NU RUGUA
210180	2	SAN PEDRO	10	TACUATI	6	180	POTRERO OCULTO
210185	2	SAN PEDRO	10	TACUATI	6	185	ASENT. 20 DE JULIO
210190	2	SAN PEDRO	10	TACUATI	6	190	LOMA PYTA
210195	2	SAN PEDRO	10	TACUATI	6	195	COM INDIG KAPI'ITINDY
210205	2	SAN PEDRO	10	TACUATI	6	205	CECILIO KUE
210210	2	SAN PEDRO	10	TACUATI	6	210	TORO NU
210215	2	SAN PEDRO	10	TACUATI	6	215	COLONIA PY'AGUAPY
210220	2	SAN PEDRO	10	TACUATI	6	220	3RA ZONA
210225	2	SAN PEDRO	10	TACUATI	6	225	PASO ITA
210230	2	SAN PEDRO	10	TACUATI	6	230	BARRIAL
210235	2	SAN PEDRO	10	TACUATI	6	235	ISLA GUASU
210240	2	SAN PEDRO	10	TACUATI	6	240	COLONIA ONONDIVEPA
210245	2	SAN PEDRO	10	TACUATI	6	245	ASENT. 8
210250	2	SAN PEDRO	10	TACUATI	6	250	SAN PEDRO POTY
210255	2	SAN PEDRO	10	TACUATI	6	255	ASENT. 12 DE JUNIO
210260	2	SAN PEDRO	10	TACUATI	6	260	SENDERO DEL NORTE
210265	2	SAN PEDRO	10	TACUATI	6	265	ASENT. TACUATI POTY
210270	2	SAN PEDRO	10	TACUATI	6	270	PLANTA 2
210275	2	SAN PEDRO	10	TACUATI	6	275	NARANJO MARIA AUXILIADORA
210280	2	SAN PEDRO	10	TACUATI	6	280	SANTA ROSA
210285	2	SAN PEDRO	10	TACUATI	6	285	ASENT. SENDERO DEL NORTE
210290	2	SAN PEDRO	10	TACUATI	6	290	ASENT. VIRGEN DEL CARMEN
210295	2	SAN PEDRO	10	TACUATI	6	295	ASENT. SAN JOSE
210300	2	SAN PEDRO	10	TACUATI	6	300	VIRGEN DEL CARMEN
210310	2	SAN PEDRO	10	TACUATI	6	310	ASENT. PLANTA 2
210320	2	SAN PEDRO	10	TACUATI	6	320	ASENT. SAN ANTONIO
210330	2	SAN PEDRO	10	TACUATI	6	330	ASENT. SAN JORGE
210340	2	SAN PEDRO	10	TACUATI	6	340	SAN FELIPE
210350	2	SAN PEDRO	10	TACUATI	6	350	ASENT. FATIMA
210360	2	SAN PEDRO	10	TACUATI	6	360	TACUATI POTY
210370	2	SAN PEDRO	10	TACUATI	6	370	ARROYO ATA
210380	2	SAN PEDRO	10	TACUATI	6	380	ASENT. 8 DE DICIEMBRE
210390	2	SAN PEDRO	10	TACUATI	6	390	POTRERO TACUATI
210400	2	SAN PEDRO	10	TACUATI	6	400	2DA ZONA
210410	2	SAN PEDRO	10	TACUATI	6	410	GENERAL DIAZ
210420	2	SAN PEDRO	10	TACUATI	6	420	CASTILLO KUE
210430	2	SAN PEDRO	10	TACUATI	6	430	COSTA PO'I
210440	2	SAN PEDRO	10	TACUATI	6	440	COLONIA ONONDIVEPA 2DA LINEA
210450	2	SAN PEDRO	10	TACUATI	6	450	ASENT. KO'E PYAHU
210460	2	SAN PEDRO	10	TACUATI	6	460	KAPI'ITINDY
210470	2	SAN PEDRO	10	TACUATI	6	470	COM INDIG ESPAJIN
211001	2	SAN PEDRO	11	UNION	1	1	MARIA GORETTI
211002	2	SAN PEDRO	11	UNION	1	2	SAN JOSE
211003	2	SAN PEDRO	11	UNION	1	3	SAN ROQUE
211004	2	SAN PEDRO	11	UNION	1	4	SANTA CATALINA
211005	2	SAN PEDRO	11	UNION	1	5	CENTRO
211110	2	SAN PEDRO	11	UNION	6	110	COBAS  KUE
211120	2	SAN PEDRO	11	UNION	6	120	CANADA SANTA MARIA
211130	2	SAN PEDRO	11	UNION	6	130	POTRERITO
211150	2	SAN PEDRO	11	UNION	6	150	SANTO DOMINGO
211160	2	SAN PEDRO	11	UNION	6	160	SANTA CATALINA
211170	2	SAN PEDRO	11	UNION	6	170	SAN ANTONIO
211180	2	SAN PEDRO	11	UNION	6	180	SAN BLAS
211190	2	SAN PEDRO	11	UNION	6	190	CANADA SAN JORGE
211210	2	SAN PEDRO	11	UNION	6	210	ASENT. SANTA LIBRADA
211220	2	SAN PEDRO	11	UNION	6	220	HUGUA MOJON
211230	2	SAN PEDRO	11	UNION	6	230	SAN ROQUE
211240	2	SAN PEDRO	11	UNION	6	240	CERRO MONTE ALTO
211250	2	SAN PEDRO	11	UNION	6	250	ASENT. ISLA NARANJA
212001	2	SAN PEDRO	12	25 DE DICIEMBRE	1	1	CENTRO
212100	2	SAN PEDRO	12	25 DE DICIEMBRE	6	100	SAN ISIDRO
212110	2	SAN PEDRO	12	25 DE DICIEMBRE	6	110	SAN JOSE 1
212120	2	SAN PEDRO	12	25 DE DICIEMBRE	6	120	ISLA HOVY
212130	2	SAN PEDRO	12	25 DE DICIEMBRE	6	130	CANADA LOURDES
212140	2	SAN PEDRO	12	25 DE DICIEMBRE	6	140	COLONIA NAVIDAD
212145	2	SAN PEDRO	12	25 DE DICIEMBRE	3	145	COLONIA NAVIDAD SUB URBANO
212160	2	SAN PEDRO	12	25 DE DICIEMBRE	6	160	SAN IGNACIO
212170	2	SAN PEDRO	12	25 DE DICIEMBRE	6	170	MBOIY
212180	2	SAN PEDRO	12	25 DE DICIEMBRE	6	180	POTRERO YVATE
212185	2	SAN PEDRO	12	25 DE DICIEMBRE	3	185	POTRERO YVATE SUB-URBANO
212190	2	SAN PEDRO	12	25 DE DICIEMBRE	6	190	TORIN KUE
212200	2	SAN PEDRO	12	25 DE DICIEMBRE	6	200	ASENT. VIRGEN DEL CARMEN
212210	2	SAN PEDRO	12	25 DE DICIEMBRE	6	210	CELADOR
212220	2	SAN PEDRO	12	25 DE DICIEMBRE	6	220	COSTA NUEVA
212240	2	SAN PEDRO	12	25 DE DICIEMBRE	6	240	SAN PABLO
212250	2	SAN PEDRO	12	25 DE DICIEMBRE	6	250	VIRGEN DEL ROSARIO
212260	2	SAN PEDRO	12	25 DE DICIEMBRE	6	260	SAN JUAN BOSCO
212270	2	SAN PEDRO	12	25 DE DICIEMBRE	6	270	SANTA ROSA
212280	2	SAN PEDRO	12	25 DE DICIEMBRE	6	280	CONAVI
212290	2	SAN PEDRO	12	25 DE DICIEMBRE	6	290	ASENT. KO'EJU
212300	2	SAN PEDRO	12	25 DE DICIEMBRE	6	300	SAN MIGUEL 1
212310	2	SAN PEDRO	12	25 DE DICIEMBRE	6	310	SAN MIGUEL 2
212320	2	SAN PEDRO	12	25 DE DICIEMBRE	6	320	ASENT. SAN AGUSTIN
212330	2	SAN PEDRO	12	25 DE DICIEMBRE	6	330	ASENT. SAN ANTONIO
212340	2	SAN PEDRO	12	25 DE DICIEMBRE	6	340	HUGUA GUASU
212350	2	SAN PEDRO	12	25 DE DICIEMBRE	6	350	KIRAYTY
212360	2	SAN PEDRO	12	25 DE DICIEMBRE	6	360	SAN BLAS
212370	2	SAN PEDRO	12	25 DE DICIEMBRE	6	370	SAN FRANCISCO
212380	2	SAN PEDRO	12	25 DE DICIEMBRE	6	380	SAN JOSE 2
212390	2	SAN PEDRO	12	25 DE DICIEMBRE	6	390	SANTA LIBRADA
212400	2	SAN PEDRO	12	25 DE DICIEMBRE	6	400	VIRGEN DE LA ASUNCION
212410	2	SAN PEDRO	12	25 DE DICIEMBRE	6	410	YKUA RUGUA
213001	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	1	SANTA TERESITA
213002	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	2	SAN BLAS
213003	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	3	INMACULADA
213004	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	4	MARIA GORETTI
213005	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	5	SAN LUIS
213006	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	6	SAN JOSE
213007	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	7	SAN ROQUE
213008	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	8	SAGRADO CORAZON DE JESUS
213009	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	9	SAN MIGUEL
213010	2	SAN PEDRO	13	VILLA DEL ROSARIO	1	10	MARIA AUXILIADORA
213100	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	100	COMPANIA ESCALERA
213110	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	110	PUERTO ROSARIO
213115	2	SAN PEDRO	13	VILLA DEL ROSARIO	3	115	PUERTO ROSARIO SUB-URBANO
213130	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	130	KERAMBU
213140	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	140	ESTANCIA LOMAS
213160	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	160	VALLEMI
213170	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	170	CAACUPE I
213190	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	190	COSTA PUKU
213200	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	200	SANTA ROSA 1
213210	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	210	SAN JOSE DEL ROSARIO
213215	2	SAN PEDRO	13	VILLA DEL ROSARIO	3	215	SAN JOSE DEL ROSARIO SUB URBANO
213230	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	230	MBOPI KUA
213270	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	270	COLONIA VOLENDAM
213280	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	280	ARASA POTY
213290	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	290	COM INDIG URUKUY LAS PALMAS
213300	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	300	COM INDIG BOQUERON
213310	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	310	TRES BOCAS
213320	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	320	VOLENDAM ALDEA 3 Y 4
213330	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	330	VOLENDAM ALDEA 12 Y 13
213340	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	340	VOLENDAM ALDEA 5
213360	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	360	SERRATI KUE
213370	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	370	SAN BLAS
213380	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	380	LAS PALMAS
213390	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	390	MBOKAJA
213400	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	400	SAN ISIDRO
213410	2	SAN PEDRO	13	VILLA DEL ROSARIO	6	410	SANTA ROSA 2
214001	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	1	1	BERNARDINO CABALLERO
214002	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	1	2	VIRGEN DE FATIMA
214003	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	1	3	8 DE DICIEMBRE
214120	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	120	ISLA ALTA
214140	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	140	SAN VICENTE - 8 DE DICIEMBRE
214145	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	3	145	SAN VICENTE SUB-URBANO-PANCHOLO
214170	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	170	NARANJITO CALLE 8 DE DICIEMBRE
214180	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	180	1RO DE MAYO
214190	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	190	COSTA RICA
214230	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	230	3 DE MAYO
214250	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	250	NARANJITO  CALLE MARIA AUXILIADORA
214255	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	3	255	NARANJITO SUB URBANO
214260	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	260	LA VICTORIA
214280	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	280	30 DE ABRIL
214290	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	290	CALLE PRIMERO DE MAYO
214300	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	300	SAN FRANCISCO
214350	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	350	KIRAY-6TA LINEA
214360	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	360	SAN JOSE
214425	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	425	NARANJITO CALLE SAN JUAN
214430	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	430	COM INDIG NARANJAY
214435	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	435	SAN VICENTE - CALLE SAN JUAN
214440	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	440	A. J. VIERCI
214445	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	445	SAN VICENTE - CALLE SAN MARCOS
214450	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	450	COM INDIG TAPYI KUE
214460	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	460	COM INDIG KO'E POTY
214470	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	470	SAN VICENTE CALLE 13 DE MAYO
214480	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	480	SAN VICENTE CALLE SANTA ROSA
214490	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	490	SAN VICENTE CALLE SAN RAMON KAATY-I
214500	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	500	SAN VICENTE CALLE SAN GABRIEL
214510	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	510	NARANJITO CALLE SAN MIGUEL
214520	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	520	COM INDIG TAHEKYI SAN LUIS
214530	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	530	NARANJITO CALLE SANTA LUCIA
214540	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	540	COM INDIG NARANJITO SANTA LUCIA
214550	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	550	NARANJITO CALLE SAN BLAS
214560	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	560	SAN VICENTE CALLE SANTA ANA
214570	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	570	ASENT. PABLO IBANEZ
214580	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	580	COM INDIG KA'APOTY
214590	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	590	NARANJITO CALLE SAN JORGE
214600	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	600	SAN JOSE DEL NORTE CALLE SANTA LIBRADA
214610	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	610	SAN JOSE DEL NORTE CALLE NUEVA ESPERANZA
214620	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	620	SAN JOSE DEL NORTE CALLE LIBERTAD
214630	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	630	NARANJITO CALLE 25 DE DICIEMBRE
214640	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	640	SAN JOSE DEL NORTE CALLE 6 DE ENERO
214650	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	650	SAN JOSE DEL NORTE CALLE SANTA CLARA
214660	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	660	NARANJITO CALLE SAN ANTONIO
214670	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	670	NARANJITO CALLE VIRGEN DE LAS MERCEDES
214680	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	680	SAN ANTONIO
214690	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	690	COM INDIG SANTA CAROLINA
214700	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	700	ESTANCIA KUAPE
214710	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	710	SANTA RITA
214720	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	720	PALOMA 1
214730	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	730	8 DE DICIEMBRE
214740	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	740	NARANJITO CALLE SAN MARTIN
214750	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	750	SAN LORENZO
214760	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	760	PALOMA 2
214770	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	770	COM INDIG YPOTYJU
214780	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	780	ESTRELLITA
214790	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	790	SAN VICENTE CALLE SAN PEDRO
214800	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	800	SAN VICENTE - CALLE SAN MIGUEL
214810	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	810	SAN VICENTE - 13 DE NOVIEMBRE
214820	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	820	SAN VICENTE - CALLE SAN FRANCISCO - PUERTO
214830	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	830	SAN VICENTE CALLE SAN BLAS
214840	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	840	SAN VICENTE CALLE SAN RAMON 1
214850	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	850	SAN VICENTE CALLE MARIA AUXILIADORA
214860	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	860	SAN VICENTE - CALLE 1RO. DE MARZO
214870	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	870	SAN VICENTE - CALLE SAN ISIDRO
214880	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	880	SAN VICENTE - CALLE GENERAL BERNARDINO CABALLERO
214890	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	890	ASENT. 26 DE ABRIL
214900	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	900	CALLE SAN JUAN-SAN JOSE NORTE
214910	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	910	CALLE 14 DE FEBRERO-SAN JOSE DEL NORTE
214920	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	920	CALLE SAN FRANCISCO-SAN JOSE DEL NORTE
214930	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	930	CALLE 25 DE DICIEMBRE - SAN JOSE NORTE
214940	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	940	SAN JOSE DEL NORTE
214950	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	950	CALLE SAN SALVADOR- SAN JOSE NORTE
214960	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	960	BARRIO SAN JOSE CENTRO URBANO
214970	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	970	KIRAY-5TA LINEA
214975	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	975	KIRAY-2DA LINEA
214980	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	980	KIRAY-4TA LINEA
214985	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	985	KIRAY-1RA LINEA
214990	2	SAN PEDRO	14	GENERAL FRANCISCO ISIDORO RESQUIN	6	990	KIRAY- 3RA LINEA
215001	2	SAN PEDRO	15	YATAITY DEL NORTE	1	1	SAGRADA FAMILIA
215002	2	SAN PEDRO	15	YATAITY DEL NORTE	1	2	CENTRO
215003	2	SAN PEDRO	15	YATAITY DEL NORTE	1	3	6 DE ENERO
215004	2	SAN PEDRO	15	YATAITY DEL NORTE	1	4	SAN FRANCISCO
215005	2	SAN PEDRO	15	YATAITY DEL NORTE	1	5	SAN JOSE
215100	2	SAN PEDRO	15	YATAITY DEL NORTE	6	100	ITA KUATIA
215120	2	SAN PEDRO	15	YATAITY DEL NORTE	6	120	SANTA LIBRADA
215130	2	SAN PEDRO	15	YATAITY DEL NORTE	6	130	8 DE DICIEMBRE
215150	2	SAN PEDRO	15	YATAITY DEL NORTE	6	150	CERRITO
215170	2	SAN PEDRO	15	YATAITY DEL NORTE	6	170	SANTA LUCIA 1
215180	2	SAN PEDRO	15	YATAITY DEL NORTE	6	180	MARIA AUXILIADORA
215200	2	SAN PEDRO	15	YATAITY DEL NORTE	6	200	GUAVIRA
215210	2	SAN PEDRO	15	YATAITY DEL NORTE	6	210	SAN JUAN
215220	2	SAN PEDRO	15	YATAITY DEL NORTE	6	220	SAN MIGUEL
215230	2	SAN PEDRO	15	YATAITY DEL NORTE	6	230	CRUCE UNION
215240	2	SAN PEDRO	15	YATAITY DEL NORTE	6	240	CHORRO
215250	2	SAN PEDRO	15	YATAITY DEL NORTE	6	250	YSAU
215260	2	SAN PEDRO	15	YATAITY DEL NORTE	6	260	BARRERO PYTA
215270	2	SAN PEDRO	15	YATAITY DEL NORTE	6	270	TUNA
215280	2	SAN PEDRO	15	YATAITY DEL NORTE	6	280	SAN FELIPE
215290	2	SAN PEDRO	15	YATAITY DEL NORTE	6	290	JAULA KUE
215310	2	SAN PEDRO	15	YATAITY DEL NORTE	6	310	SANTO DOMINGO
215330	2	SAN PEDRO	15	YATAITY DEL NORTE	6	330	CHACO'I
215360	2	SAN PEDRO	15	YATAITY DEL NORTE	6	360	SAN ISIDRO
215370	2	SAN PEDRO	15	YATAITY DEL NORTE	6	370	SAN JOSE YVYPE
215380	2	SAN PEDRO	15	YATAITY DEL NORTE	6	380	12 DE JUNIO
215400	2	SAN PEDRO	15	YATAITY DEL NORTE	6	400	16 DE JULIO
215410	2	SAN PEDRO	15	YATAITY DEL NORTE	6	410	GUASU RETA
215420	2	SAN PEDRO	15	YATAITY DEL NORTE	6	420	TACUARA PUNTA
215430	2	SAN PEDRO	15	YATAITY DEL NORTE	6	430	CERRO PYTA
215440	2	SAN PEDRO	15	YATAITY DEL NORTE	6	440	ASENT. CERRO PYTA
215450	2	SAN PEDRO	15	YATAITY DEL NORTE	6	450	SAN JOSE 1
215460	2	SAN PEDRO	15	YATAITY DEL NORTE	6	460	PUNTA SUERTE
215470	2	SAN PEDRO	15	YATAITY DEL NORTE	6	470	NU PYAHU
215480	2	SAN PEDRO	15	YATAITY DEL NORTE	6	480	MOLINO
215490	2	SAN PEDRO	15	YATAITY DEL NORTE	6	490	PINDOTY
215500	2	SAN PEDRO	15	YATAITY DEL NORTE	6	500	25 DE DICIEMBRE 2
215510	2	SAN PEDRO	15	YATAITY DEL NORTE	6	510	LA BARRERENA
215520	2	SAN PEDRO	15	YATAITY DEL NORTE	6	520	INDEPENDIENTE
215530	2	SAN PEDRO	15	YATAITY DEL NORTE	6	530	SAN ROQUE
215540	2	SAN PEDRO	15	YATAITY DEL NORTE	6	540	SAN JORGE
215550	2	SAN PEDRO	15	YATAITY DEL NORTE	6	550	SANTA TERESA
215560	2	SAN PEDRO	15	YATAITY DEL NORTE	6	560	SAN BLAS 1
215570	2	SAN PEDRO	15	YATAITY DEL NORTE	6	570	SANTA LUCIA 2
215580	2	SAN PEDRO	15	YATAITY DEL NORTE	6	580	SAN JOSE 2
215590	2	SAN PEDRO	15	YATAITY DEL NORTE	6	590	SAN BLAS 2
215600	2	SAN PEDRO	15	YATAITY DEL NORTE	6	600	NUEVA ALIANZA
215610	2	SAN PEDRO	15	YATAITY DEL NORTE	6	610	SAGRADA FAMILIA
215620	2	SAN PEDRO	15	YATAITY DEL NORTE	6	620	25 DE DICIEMBRE 1
215630	2	SAN PEDRO	15	YATAITY DEL NORTE	6	630	SAN ANTONIO
216001	2	SAN PEDRO	16	GUAJAYVI	1	1	SAN BLAS
216002	2	SAN PEDRO	16	GUAJAYVI	1	2	MARIA AUXILIADORA
216003	2	SAN PEDRO	16	GUAJAYVI	1	3	ASENT. MARIA AUXILIADORA
216004	2	SAN PEDRO	16	GUAJAYVI	1	4	SANTA ROSA
216005	2	SAN PEDRO	16	GUAJAYVI	1	5	NUESTRA SENORA DE LOURDES
216120	2	SAN PEDRO	16	GUAJAYVI	6	120	LUZ BELLA SAN FRANCISCO
216130	2	SAN PEDRO	16	GUAJAYVI	6	130	SAN RAMON
216140	2	SAN PEDRO	16	GUAJAYVI	6	140	ASENT. 7 DE ABRIL LUZ BELLA
216230	2	SAN PEDRO	16	GUAJAYVI	6	230	LUZ BELLA NATIVIDAD DE LAS MERCEDES
216300	2	SAN PEDRO	16	GUAJAYVI	6	300	LUZ BELLA
216330	2	SAN PEDRO	16	GUAJAYVI	6	330	MARIA AUXILIADORA
216340	2	SAN PEDRO	16	GUAJAYVI	6	340	SAN JOSE 2
216350	2	SAN PEDRO	16	GUAJAYVI	6	350	TORO PIRU
216360	2	SAN PEDRO	16	GUAJAYVI	6	360	AMISTAD
216370	2	SAN PEDRO	16	GUAJAYVI	6	370	COM INDIG NU APU'A
216380	2	SAN PEDRO	16	GUAJAYVI	6	380	GUAVIRA
216400	2	SAN PEDRO	16	GUAJAYVI	6	400	BARRIO SAN PEDRO
216410	2	SAN PEDRO	16	GUAJAYVI	6	410	8 DE DICIEMBRE
216440	2	SAN PEDRO	16	GUAJAYVI	6	440	ASENT. PRIMAVERA REAL
216450	2	SAN PEDRO	16	GUAJAYVI	6	450	ASUNCION
216470	2	SAN PEDRO	16	GUAJAYVI	6	470	ALMEIDA
216490	2	SAN PEDRO	16	GUAJAYVI	6	490	NARANJA HAI
216495	2	SAN PEDRO	16	GUAJAYVI	6	495	COM INDIG SAN JOSE KUPA'Y
216500	2	SAN PEDRO	16	GUAJAYVI	6	500	BERTONI 12000
216510	2	SAN PEDRO	16	GUAJAYVI	6	510	PRIMERA LINEA CHACHI
216520	2	SAN PEDRO	16	GUAJAYVI	6	520	SEGUNDA LINEA CHACHI
216540	2	SAN PEDRO	16	GUAJAYVI	6	540	SANTO DOMINGO 1
216550	2	SAN PEDRO	16	GUAJAYVI	6	550	BERTONI 10000
216580	2	SAN PEDRO	16	GUAJAYVI	6	580	TAVA'I 1000
216590	2	SAN PEDRO	16	GUAJAYVI	6	590	SAN FRANCISCO
216600	2	SAN PEDRO	16	GUAJAYVI	6	600	CALLE 2000
216610	2	SAN PEDRO	16	GUAJAYVI	6	610	CALLE 4000
216620	2	SAN PEDRO	16	GUAJAYVI	6	620	CALLE 6000
216630	2	SAN PEDRO	16	GUAJAYVI	6	630	YKUA PORA
216640	2	SAN PEDRO	16	GUAJAYVI	6	640	FATIMA
216650	2	SAN PEDRO	16	GUAJAYVI	6	650	LOURDES
216660	2	SAN PEDRO	16	GUAJAYVI	6	660	12 DE JUNIO
216670	2	SAN PEDRO	16	GUAJAYVI	6	670	TUPASY
216680	2	SAN PEDRO	16	GUAJAYVI	6	680	SAN CAYETANO
216690	2	SAN PEDRO	16	GUAJAYVI	6	690	SAN JOSE 1
216700	2	SAN PEDRO	16	GUAJAYVI	6	700	BERTONI 6000
216710	2	SAN PEDRO	16	GUAJAYVI	6	710	BERTONI 8000
216720	2	SAN PEDRO	16	GUAJAYVI	6	720	ASENT. BARRIO  SAN PEDRO 1
216730	2	SAN PEDRO	16	GUAJAYVI	6	730	ASENT. BARRIO SAN PEDRO 2
216740	2	SAN PEDRO	16	GUAJAYVI	6	740	ASENT. TORO PIRU 1
216750	2	SAN PEDRO	16	GUAJAYVI	6	750	MOROMBI
216760	2	SAN PEDRO	16	GUAJAYVI	6	760	SAN JUAN 1
216770	2	SAN PEDRO	16	GUAJAYVI	6	770	NAVIDAD
216780	2	SAN PEDRO	16	GUAJAYVI	6	780	ASENT. 19 DE AGOSTO
216790	2	SAN PEDRO	16	GUAJAYVI	6	790	PLANCHADA EVA
216800	2	SAN PEDRO	16	GUAJAYVI	6	800	SANTO DOMINGO 2
216810	2	SAN PEDRO	16	GUAJAYVI	6	810	ASENT. 15 DE AGOSTO
216820	2	SAN PEDRO	16	GUAJAYVI	6	820	ASENT. 3 DE FEBRERO
216830	2	SAN PEDRO	16	GUAJAYVI	6	830	ASENT. 11 DE JUNIO
216840	2	SAN PEDRO	16	GUAJAYVI	6	840	ASENT. JARDIN PUNTA DEL ESTE
216850	2	SAN PEDRO	16	GUAJAYVI	6	850	LUZ BELLA VILLA JARDIN
216860	2	SAN PEDRO	16	GUAJAYVI	6	860	LUZ BELLA SAN LUIS
216870	2	SAN PEDRO	16	GUAJAYVI	6	870	ASENT. JUSTO VILLANUEVA LUZ BELLA
216880	2	SAN PEDRO	16	GUAJAYVI	6	880	LUZ BELLA JUSTO VILLANUEVA
216890	2	SAN PEDRO	16	GUAJAYVI	6	890	LUZ BELLA SAN ISIDRO
216900	2	SAN PEDRO	16	GUAJAYVI	6	900	LUZ BELLA 8 DE DICIEMBRE
216910	2	SAN PEDRO	16	GUAJAYVI	6	910	ASENT. ECO URBANISTICO
216920	2	SAN PEDRO	16	GUAJAYVI	6	920	ASENT. TORO PIRU 2
216930	2	SAN PEDRO	16	GUAJAYVI	6	930	CAREAGA KUE
216940	2	SAN PEDRO	16	GUAJAYVI	6	940	2DO. LOTE
216950	2	SAN PEDRO	16	GUAJAYVI	6	950	4000 TACUAPI
217001	2	SAN PEDRO	17	CAPIIBARY	1	1	4 DE MAYO
217003	2	SAN PEDRO	17	CAPIIBARY	1	3	VIRGEN DE FATIMA
217004	2	SAN PEDRO	17	CAPIIBARY	1	4	SAN MIGUEL
217005	2	SAN PEDRO	17	CAPIIBARY	1	5	ASENT. SAN JOSE
217006	2	SAN PEDRO	17	CAPIIBARY	1	6	SAN JOSE
217007	2	SAN PEDRO	17	CAPIIBARY	1	7	CENTRO
217008	2	SAN PEDRO	17	CAPIIBARY	1	8	MARIA AUXILIADORA
217009	2	SAN PEDRO	17	CAPIIBARY	1	9	LAS RESIDENTAS
217010	2	SAN PEDRO	17	CAPIIBARY	1	10	SAN ISIDRO
217011	2	SAN PEDRO	17	CAPIIBARY	1	11	SAN FRANCISCO
217012	2	SAN PEDRO	17	CAPIIBARY	1	12	PRIMAVERA
217013	2	SAN PEDRO	17	CAPIIBARY	1	13	3 DE NOVIEMBRE
217014	2	SAN PEDRO	17	CAPIIBARY	1	14	1RO DE MARZO
217100	2	SAN PEDRO	17	CAPIIBARY	6	100	19 DE MARZO
217105	2	SAN PEDRO	17	CAPIIBARY	6	105	COM INDIG NU POTY
217110	2	SAN PEDRO	17	CAPIIBARY	6	110	TAJY KARE
217115	2	SAN PEDRO	17	CAPIIBARY	6	115	GUYRA CAMPANA
217125	2	SAN PEDRO	17	CAPIIBARY	6	125	COM INDIG RIO CORRIENTE MI
217135	2	SAN PEDRO	17	CAPIIBARY	6	135	COM INDIG KA'AGUY PYAHU 1
217136	2	SAN PEDRO	17	CAPIIBARY	6	136	COM INDIG KA'AGUY PYAHU 2
217145	2	SAN PEDRO	17	CAPIIBARY	6	145	COM INDIG PARAKAU KEHA
217170	2	SAN PEDRO	17	CAPIIBARY	6	170	ARA PYAHU
217200	2	SAN PEDRO	17	CAPIIBARY	6	200	CALLE ZABALA
217205	2	SAN PEDRO	17	CAPIIBARY	6	205	ASENT. ARA PYAHU
217210	2	SAN PEDRO	17	CAPIIBARY	6	210	BOQUERON
217230	2	SAN PEDRO	17	CAPIIBARY	6	230	COM INDIG KA'ATY MIRI  SAN FRANCISCO
217250	2	SAN PEDRO	17	CAPIIBARY	6	250	1RO DE MAYO
217260	2	SAN PEDRO	17	CAPIIBARY	6	260	CANETE KUE
217265	2	SAN PEDRO	17	CAPIIBARY	6	265	PABLO KUE
217275	2	SAN PEDRO	17	CAPIIBARY	6	275	15 DE AGOSTO
217280	2	SAN PEDRO	17	CAPIIBARY	6	280	26 DE FEBRERO
217285	2	SAN PEDRO	17	CAPIIBARY	6	285	ASENT. 25 DE FEBRERO
217290	2	SAN PEDRO	17	CAPIIBARY	6	290	1RO DE MARZO
217295	2	SAN PEDRO	17	CAPIIBARY	6	295	ASENT. 1RO DE MARZO
217300	2	SAN PEDRO	17	CAPIIBARY	6	300	1RO DE NOVIEMBRE
217305	2	SAN PEDRO	17	CAPIIBARY	6	305	ASENT. SAN NICOLAS
217310	2	SAN PEDRO	17	CAPIIBARY	6	310	25 DE DICIEMBRE
217315	2	SAN PEDRO	17	CAPIIBARY	6	315	ASENT. 4 DE MAYO
217320	2	SAN PEDRO	17	CAPIIBARY	6	320	4 DE MAYO
217325	2	SAN PEDRO	17	CAPIIBARY	6	325	4 DE MAYO 2
217330	2	SAN PEDRO	17	CAPIIBARY	6	330	3 DE NOVIEMBRE
217335	2	SAN PEDRO	17	CAPIIBARY	6	335	ASENT. 3 DE NOVIEMBRE
217340	2	SAN PEDRO	17	CAPIIBARY	6	340	POTRERITO
217345	2	SAN PEDRO	17	CAPIIBARY	6	345	ASENT. PIO KUE
217350	2	SAN PEDRO	17	CAPIIBARY	6	350	SAN ISIDRO
217355	2	SAN PEDRO	17	CAPIIBARY	6	355	ASENT. 25 DE DICIEMBRE
217360	2	SAN PEDRO	17	CAPIIBARY	6	360	12 DE JUNIO
217365	2	SAN PEDRO	17	CAPIIBARY	6	365	ASENT. 12 DE JUNIO
217370	2	SAN PEDRO	17	CAPIIBARY	6	370	CABO KUE
217375	2	SAN PEDRO	17	CAPIIBARY	6	375	ASENT. 10 DE JUNIO
217380	2	SAN PEDRO	17	CAPIIBARY	6	380	20 DE JULIO NORTE
217385	2	SAN PEDRO	17	CAPIIBARY	6	385	20 DE JULIO SUR
217400	2	SAN PEDRO	17	CAPIIBARY	6	400	SAN BLAS
217405	2	SAN PEDRO	17	CAPIIBARY	6	405	ASENT. SAN CARLOS
217410	2	SAN PEDRO	17	CAPIIBARY	6	410	SANTO DOMINGO
217415	2	SAN PEDRO	17	CAPIIBARY	6	415	SAN CARLOS
217420	2	SAN PEDRO	17	CAPIIBARY	6	420	ANA RETANGUE
217440	2	SAN PEDRO	17	CAPIIBARY	6	440	ASENT. 30 DE AGOSTO
217450	2	SAN PEDRO	17	CAPIIBARY	6	450	8 DE DICIEMBRE
217460	2	SAN PEDRO	17	CAPIIBARY	6	460	ASENT. TAPIRACUAI POTY
217465	2	SAN PEDRO	17	CAPIIBARY	6	465	TAPIRACUAI POTY
217470	2	SAN PEDRO	17	CAPIIBARY	6	470	SIDEPAR 45
217480	2	SAN PEDRO	17	CAPIIBARY	6	480	SIDEPAR 5TA LINEA
217490	2	SAN PEDRO	17	CAPIIBARY	6	490	SIDEPAR 4TA LINEA
217500	2	SAN PEDRO	17	CAPIIBARY	6	500	SIDEPAR 3RA LINEA
217505	2	SAN PEDRO	17	CAPIIBARY	6	505	SIDEPAR PLANTA URBANA
217510	2	SAN PEDRO	17	CAPIIBARY	6	510	SIDEPAR 2DA LINEA
217520	2	SAN PEDRO	17	CAPIIBARY	6	520	SIDEPAR SAN ALBERTO CURUZU
217530	2	SAN PEDRO	17	CAPIIBARY	6	530	SIDEPAR 1RA LINEA
217540	2	SAN PEDRO	17	CAPIIBARY	6	540	PIQUETE I
217545	2	SAN PEDRO	17	CAPIIBARY	6	545	MARISCAL LOPEZ 1RA LINEA
217550	2	SAN PEDRO	17	CAPIIBARY	6	550	MARISCAL LOPEZ 2DA LINEA
217560	2	SAN PEDRO	17	CAPIIBARY	6	560	MARISCAL LOPEZ 3RA LINEA
217570	2	SAN PEDRO	17	CAPIIBARY	6	570	MARISCAL LOPEZ - CHACHI  1RA LINEA
217580	2	SAN PEDRO	17	CAPIIBARY	6	580	MARISCAL LOPEZ - CHACHI  2DA LINEA
217590	2	SAN PEDRO	17	CAPIIBARY	6	590	MARISCAL LOPEZ 4TA LINEA
217600	2	SAN PEDRO	17	CAPIIBARY	6	600	MARISCAL LOPEZ 6TA LINEA
217610	2	SAN PEDRO	17	CAPIIBARY	6	610	ASENT. JAHAPE
217620	2	SAN PEDRO	17	CAPIIBARY	6	620	ASENT. KA'AGUY POTY
217630	2	SAN PEDRO	17	CAPIIBARY	6	630	COM INDIG YPACHI
217640	2	SAN PEDRO	17	CAPIIBARY	6	640	COM INDIG SAN JOSE
217650	2	SAN PEDRO	17	CAPIIBARY	6	650	COM INDIG RIO VERDE
218001	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	1	1	SANTO DOMINGO
218002	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	1	2	FATIMA
218003	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	1	3	ASENT. SAN RAMON
218004	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	1	4	JARDIN DEL NORTE
218005	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	1	5	ASENT. JARDIN DEL NORTE
218006	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	1	6	SAN JOSE 1
218100	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	100	ISLA SOLA
218110	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	110	SAN BLAS
218120	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	120	PROSPERIDAD
218140	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	140	SANTA ANA
218160	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	160	LA VICTORIA
218180	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	180	SAN MIGUEL
218185	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	185	SAN FRANCISCO
218190	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	190	SAN ISIDRO 3
218200	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	200	LOMA PUKU
218220	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	220	CAMBA YKUA
218230	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	230	COLONIA MEXICO
218240	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	240	MARIA AUXILIADORA
218245	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	245	SAN ISIDRO 2
218250	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	250	YVA HAI
218260	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	260	LOPEZ SALINAS
218310	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	310	SANTA LUCIA 2
218330	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	330	ASENT. KORORO'I
218340	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	340	RIO VERDE ZONA 1 AL 9
218351	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	351	SANTA BARBARA CALLE SAN JUAN
218352	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	352	SANTA BARBARA CALLE  VIRGEN DE FATIMA
218353	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	353	SANTA BARBARA CALLE  SAN PEDRO
218354	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	354	SANTA BARBARA CALLE SAN ANTONIO
218365	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	365	ASENT. 3 DE FEBRERO
218370	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	370	ASENT. TAVA GUARANI
218380	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	380	ASENT. KARAPA'I
218390	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	390	ASENT. KURUPAYTY
218420	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	420	ASENT. JAGUARETE SAN ISIDRO 15 DE MAYO
218430	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	430	ASENT. JAGUARETE  9 DE AGOSTO
218440	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	440	ASENT. JAGUARETE CALLE 1
218450	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	450	ASENT. JAGUARETE CALLE 4
218460	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	460	ASENT. JAGUARETE  SAN JUAN
218470	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	470	ASENT. JAGUARETE  CALLE 2
218480	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	480	ASENT. JAGUARETE CALLE 3
218490	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	490	ASENT. JAGUARETE CALLE 5
218500	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	500	SANTA BARBARA CALLE SAN JOSE
218510	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	510	ASENT. NUEVA ESPERANZA
218520	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	520	SANTA BARBARA BASE KORORO'I
218530	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	530	SAN ROQUE 1
218540	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	540	CORAZON DE JESUS
218550	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	550	SANTA LUCIA 1
218560	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	560	11 DE OCTUBRE
218570	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	570	ASENT. SAN MIGUEL
218580	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	580	ASENT. JAGUARETE 10 DE AGOSTO
218590	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	590	ASENT. AGUERITO
218595	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	595	ASENT. JAGUARETE FELIPE OSORIO NUCLEO 3
218600	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	600	ASENT. JAGUARETE FELIPE OSORIO NUCLEO 4
218605	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	605	ASENT. JAGUARETE FELIPE OSORIO NUCLEO 2
218610	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	610	ASENT. JAGUARETE FELIPE OSORIO NUCLEO 1
218640	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	640	SANTA BARBARA CALLE SAN RAMON
218650	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	650	SANTA BARBARA CALLE SAN ISIDRO
218660	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	660	ASENT. SANTA BARBARA
218670	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	670	ASENT. MARIA AUXILIADORA
218680	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	680	ASENT. AGUARAYMI
218690	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	690	ASENT. LOMA PUKU
218700	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	700	ASENT. BASE 8 DE DICIEMBRE
218710	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	710	ASENT. 7 DE SETIEMBRE
218720	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	720	ASENT. BASE SAN JORGE 1
218730	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	730	ASENT. BASE SAN BLAS
218740	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	740	ASENT. BASE PROSPERIDAD
218750	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	750	ZONA 11
218760	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	760	ZONA 12
218770	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	770	ZONA 13
218780	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	780	ZONA 14
218790	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	790	ZONA 103
218800	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	800	8 DE SETIEMBRE
218810	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	810	SAN ROQUE 2
218820	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	820	ASENT. SANTA LIBRADA
218830	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	830	KORORO'I
218840	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	840	SANTA LIBRADA
218850	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	850	ASENT. SAN JORGE
218860	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	860	ASENT. BASE SAN JORGE 2
218870	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	870	SAN JORGE
218880	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	880	SAN MIGUEL DEL  NORTE
218890	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	890	CRISTO REY
218900	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	900	STELLA MARIS
218910	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	910	VILLA DEL MAESTRO
218920	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	920	SAN VALENTIN
218930	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	930	SAN RAMON
218940	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	940	PICAFLOR
218950	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	950	ASENT. JAGUARETE  PLANTA URBANA
218960	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	960	ZONA 101
218970	2	SAN PEDRO	18	SANTA ROSA DEL AGUARAY	6	970	SAN JOSE
219001	2	SAN PEDRO	19	YRYBUCUA	1	1	CENTRO
219100	2	SAN PEDRO	19	YRYBUCUA	6	100	SAN ROQUE
219110	2	SAN PEDRO	19	YRYBUCUA	6	110	ASENT. YVU PORA
219120	2	SAN PEDRO	19	YRYBUCUA	6	120	YVU PORA
219130	2	SAN PEDRO	19	YRYBUCUA	6	130	SAN ISIDRO
219140	2	SAN PEDRO	19	YRYBUCUA	6	140	SANGUINA KUE
219150	2	SAN PEDRO	19	YRYBUCUA	6	150	ASENT. SAN ISIDRO DEL NORTE
219160	2	SAN PEDRO	19	YRYBUCUA	6	160	ASENT. 1RA LINEA SANGUINA KUE
219170	2	SAN PEDRO	19	YRYBUCUA	6	170	2 DE MAYO
219180	2	SAN PEDRO	19	YRYBUCUA	6	180	SAN MIGUEL
219190	2	SAN PEDRO	19	YRYBUCUA	6	190	COM INDIG Y APY MBURUCUYA
219200	2	SAN PEDRO	19	YRYBUCUA	6	200	COM INDIG Y APY ARROYO SA'YJU
219210	2	SAN PEDRO	19	YRYBUCUA	6	210	ASENT.  PALOMITA
219220	2	SAN PEDRO	19	YRYBUCUA	6	220	ASENT. ALEMAN KUE
219230	2	SAN PEDRO	19	YRYBUCUA	6	230	10 DE AGOSTO
219240	2	SAN PEDRO	19	YRYBUCUA	6	240	VY'A RENDA
219250	2	SAN PEDRO	19	YRYBUCUA	6	250	COM INDIG Y APY SANTA ISABEL
219260	2	SAN PEDRO	19	YRYBUCUA	6	260	SAN AGUSTIN
219270	2	SAN PEDRO	19	YRYBUCUA	6	270	COM INDIG Y APY ARROYO CORA
219280	2	SAN PEDRO	19	YRYBUCUA	6	280	ASENT. CERRO VERDE
219290	2	SAN PEDRO	19	YRYBUCUA	6	290	ASENT. NARANJATY
219300	2	SAN PEDRO	19	YRYBUCUA	6	300	NARANJATY
219310	2	SAN PEDRO	19	YRYBUCUA	6	310	ASENT. NUEVO HORIZONTE
219320	2	SAN PEDRO	19	YRYBUCUA	6	320	ASENT. SANTA CATALINA
219330	2	SAN PEDRO	19	YRYBUCUA	6	330	ASENT. JUSTO VILLANUEVA
219340	2	SAN PEDRO	19	YRYBUCUA	6	340	ASENT. OGA LATA
219350	2	SAN PEDRO	19	YRYBUCUA	6	350	ALEMAN KUE
219360	2	SAN PEDRO	19	YRYBUCUA	6	360	YKUA PORA
219370	2	SAN PEDRO	19	YRYBUCUA	6	370	CERRO VERDE
219380	2	SAN PEDRO	19	YRYBUCUA	6	380	SAN JUAN BOSCO
219390	2	SAN PEDRO	19	YRYBUCUA	6	390	COM INDIG PALOMITA - 2
219395	2	SAN PEDRO	19	YRYBUCUA	6	395	COM INDIG PALOMITA - 1
219400	2	SAN PEDRO	19	YRYBUCUA	6	400	ASENT. NUEVA ALIANZA
219410	2	SAN PEDRO	19	YRYBUCUA	6	410	ASENT. ARA PYAHU
219420	2	SAN PEDRO	19	YRYBUCUA	6	420	CRUCE CERRO VERDE
220001	2	SAN PEDRO	20	LIBERACION	1	1	SAN JORGE
220002	2	SAN PEDRO	20	LIBERACION	1	2	SANTO DOMINGO
220003	2	SAN PEDRO	20	LIBERACION	1	3	CRUCE LIBERACION
220004	2	SAN PEDRO	20	LIBERACION	1	4	SAN AGUSTIN
220005	2	SAN PEDRO	20	LIBERACION	1	5	SANTA ANA
220100	2	SAN PEDRO	20	LIBERACION	6	100	1RO DE MAYO
220110	2	SAN PEDRO	20	LIBERACION	6	110	ARROYO MOROTI
220120	2	SAN PEDRO	20	LIBERACION	6	120	SAN RAMON
220130	2	SAN PEDRO	20	LIBERACION	6	130	8 DE DICIEMBRE
220140	2	SAN PEDRO	20	LIBERACION	6	140	COLONIA FELICIDAD
220150	2	SAN PEDRO	20	LIBERACION	6	150	JEJUI POTY
220160	2	SAN PEDRO	20	LIBERACION	6	160	SANTA CATALINA
220170	2	SAN PEDRO	20	LIBERACION	6	170	SAN ISIDRO 2
220180	2	SAN PEDRO	20	LIBERACION	6	180	SAN ANTONIO
220190	2	SAN PEDRO	20	LIBERACION	6	190	SAN JOSE 2
220200	2	SAN PEDRO	20	LIBERACION	6	200	MALVINAS
220210	2	SAN PEDRO	20	LIBERACION	6	210	SAN JORGE ESTE
220220	2	SAN PEDRO	20	LIBERACION	6	220	ASENT. MBOKAJATY
220230	2	SAN PEDRO	20	LIBERACION	6	230	CURUPAYTY
220240	2	SAN PEDRO	20	LIBERACION	6	240	SANTA ELENA
220250	2	SAN PEDRO	20	LIBERACION	6	250	SAN MIGUEL DEL ESTE
220260	2	SAN PEDRO	20	LIBERACION	6	260	BARRIO SAN VICENTE- KO'E POTY
220270	2	SAN PEDRO	20	LIBERACION	6	270	SANTA LIBRADA
220280	2	SAN PEDRO	20	LIBERACION	6	280	SAN MIGUEL OESTE
220290	2	SAN PEDRO	20	LIBERACION	6	290	FATIMA
220300	2	SAN PEDRO	20	LIBERACION	6	300	SAN RAFAEL
220310	2	SAN PEDRO	20	LIBERACION	6	310	SAN ROQUE
220320	2	SAN PEDRO	20	LIBERACION	6	320	ASENT. 1RO DE MAYO
220330	2	SAN PEDRO	20	LIBERACION	6	330	KO'E PYTA
220340	2	SAN PEDRO	20	LIBERACION	6	340	JEJUI
220350	2	SAN PEDRO	20	LIBERACION	6	350	OVETENSE
220360	2	SAN PEDRO	20	LIBERACION	6	360	NINO JESUS
220370	2	SAN PEDRO	20	LIBERACION	6	370	SAN BLAS
220380	2	SAN PEDRO	20	LIBERACION	6	380	SAN PABLO
220390	2	SAN PEDRO	20	LIBERACION	6	390	SANTA LUCIA
220400	2	SAN PEDRO	20	LIBERACION	6	400	SAN ISIDRO 1
220410	2	SAN PEDRO	20	LIBERACION	6	410	SAN JOSE 1
220420	2	SAN PEDRO	20	LIBERACION	6	420	COLONIA 14 DE MAYO
220430	2	SAN PEDRO	20	LIBERACION	6	430	NAVIDAD
220440	2	SAN PEDRO	20	LIBERACION	6	440	SANTA CLARA
220450	2	SAN PEDRO	20	LIBERACION	6	450	CALLE SAN RAMON
220460	2	SAN PEDRO	20	LIBERACION	6	460	25 DE DICIEMBRE
220470	2	SAN PEDRO	20	LIBERACION	6	470	CALLE SAN ANTONIO
220480	2	SAN PEDRO	20	LIBERACION	6	480	SANTA ROSA
220490	2	SAN PEDRO	20	LIBERACION	6	490	SAN JORGE
301001	3	CORDILLERA	1	CAACUPE	1	1	LOMA GUASU
301002	3	CORDILLERA	1	CAACUPE	1	2	GENERAL DIAZ
301003	3	CORDILLERA	1	CAACUPE	1	3	LOMA
301004	3	CORDILLERA	1	CAACUPE	1	4	TUPASY YKUA
301005	3	CORDILLERA	1	CAACUPE	1	5	ALEGRE
301007	3	CORDILLERA	1	CAACUPE	1	7	KENNEDY
301008	3	CORDILLERA	1	CAACUPE	1	8	CENTRO
301009	3	CORDILLERA	1	CAACUPE	1	9	BUENA VISTA
301010	3	CORDILLERA	1	CAACUPE	1	10	SAN MIGUEL
301011	3	CORDILLERA	1	CAACUPE	1	11	YVU
301012	3	CORDILLERA	1	CAACUPE	1	12	YVOTY
301013	3	CORDILLERA	1	CAACUPE	1	13	INDUSTRIAL
301014	3	CORDILLERA	1	CAACUPE	1	14	SAN BLAS
301015	3	CORDILLERA	1	CAACUPE	1	15	SANTA MARIA
301016	3	CORDILLERA	1	CAACUPE	1	16	DANIEL ESCURRA
301017	3	CORDILLERA	1	CAACUPE	1	17	SAN PABLO
301018	3	CORDILLERA	1	CAACUPE	1	18	SAN CAYETANO
301019	3	CORDILLERA	1	CAACUPE	1	19	SAN FELIPE
301020	3	CORDILLERA	1	CAACUPE	1	20	SAN FRANCISCO
301021	3	CORDILLERA	1	CAACUPE	1	21	SAN RAFAEL
301022	3	CORDILLERA	1	CAACUPE	1	22	SEMINARIO
301023	3	CORDILLERA	1	CAACUPE	1	23	SANTA ANA
301024	3	CORDILLERA	1	CAACUPE	1	24	SAN JUAN BAUTISTA
301100	3	CORDILLERA	1	CAACUPE	6	100	ALMADA
301110	3	CORDILLERA	1	CAACUPE	6	110	YPUKU
301120	3	CORDILLERA	1	CAACUPE	6	120	LOMA GUASU
301130	3	CORDILLERA	1	CAACUPE	6	130	YTU GUASU
301140	3	CORDILLERA	1	CAACUPE	6	140	COSTA PUKU
301150	3	CORDILLERA	1	CAACUPE	6	150	CABANAS
301160	3	CORDILLERA	1	CAACUPE	6	160	YTU MI
301170	3	CORDILLERA	1	CAACUPE	6	170	CERRO REAL
301180	3	CORDILLERA	1	CAACUPE	6	180	POTRERO POI
301190	3	CORDILLERA	1	CAACUPE	6	190	AQUINO CANADA
301200	3	CORDILLERA	1	CAACUPE	6	200	AZCURRA
301210	3	CORDILLERA	1	CAACUPE	6	210	ITA YVU MI
301220	3	CORDILLERA	1	CAACUPE	6	220	YHAKA ROYSA
301230	3	CORDILLERA	1	CAACUPE	6	230	CORONEL MARTINEZ
301240	3	CORDILLERA	1	CAACUPE	6	240	ITA YVU GUASU
302001	3	CORDILLERA	2	ALTOS	1	1	VIRGEN DEL ROSARIO
302002	3	CORDILLERA	2	ALTOS	1	2	CORAZON DE JESUS
302003	3	CORDILLERA	2	ALTOS	1	3	MARIA AUXILIADORA
302004	3	CORDILLERA	2	ALTOS	1	4	SAN BLAS
302005	3	CORDILLERA	2	ALTOS	1	5	SAN MIGUEL
302100	3	CORDILLERA	2	ALTOS	6	100	ACUNA
302110	3	CORDILLERA	2	ALTOS	6	110	PORARU
302120	3	CORDILLERA	2	ALTOS	6	120	ITAGASA
302130	3	CORDILLERA	2	ALTOS	6	130	CHOCHI
302140	3	CORDILLERA	2	ALTOS	6	140	LOTE NUEVO
302150	3	CORDILLERA	2	ALTOS	6	150	PASO ITAPE
302160	3	CORDILLERA	2	ALTOS	6	160	PASO PELOTA
302170	3	CORDILLERA	2	ALTOS	6	170	YACARE
302180	3	CORDILLERA	2	ALTOS	6	180	ITA GUASU
302190	3	CORDILLERA	2	ALTOS	6	190	TUCANGUA CANADA
302200	3	CORDILLERA	2	ALTOS	6	200	YVU
302210	3	CORDILLERA	2	ALTOS	6	210	SAN MIGUEL
302220	3	CORDILLERA	2	ALTOS	6	220	TAJY CANADA
302230	3	CORDILLERA	2	ALTOS	6	230	TUCANGUA CORDILLERA
302240	3	CORDILLERA	2	ALTOS	6	240	SANTA LIBRADA
302250	3	CORDILLERA	2	ALTOS	6	250	ASENT. SANTA LIBRADA
302260	3	CORDILLERA	2	ALTOS	6	260	ASENT. SAN LORENZO
303001	3	CORDILLERA	3	ARROYOS Y ESTEROS	1	1	GENERAL AQUINO
303002	3	CORDILLERA	3	ARROYOS Y ESTEROS	1	2	CENTRO
303003	3	CORDILLERA	3	ARROYOS Y ESTEROS	1	3	GENERAL DIAZ
303100	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	100	BANCO'I
303110	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	110	PIRAPO MI
303120	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	120	ITA PIRU
303130	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	130	EL CARMEN
303140	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	140	SAN ANTONIO
303150	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	150	ISLA GUASU
303160	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	160	EST. SANTA LIBRADA
303170	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	170	CERRITO
303180	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	180	TAKUARINDY
303190	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	190	COSTA PUKU
303200	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	200	GENERAL DIAZ
303210	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	210	CANADA
303220	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	220	CURUPAYTY
303230	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	230	CANADA DOMINGUEZ
303240	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	240	URUNDEY
303250	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	250	MAINUMBY
303260	3	CORDILLERA	3	ARROYOS Y ESTEROS	6	260	ACEVEDO
304001	3	CORDILLERA	4	ATYRA	1	1	MARIA AUXILIADORA
304002	3	CORDILLERA	4	ATYRA	1	2	LAS MERCEDES
304003	3	CORDILLERA	4	ATYRA	1	3	SAN ANTONIO
304004	3	CORDILLERA	4	ATYRA	1	4	SAN BLAS
304005	3	CORDILLERA	4	ATYRA	1	5	VILLA CONAVI
304006	3	CORDILLERA	4	ATYRA	1	6	BARRIO ROSA MISTICA
304100	3	CORDILLERA	4	ATYRA	6	100	BERNARDINO CABALLERO
304110	3	CORDILLERA	4	ATYRA	6	110	CARUMBEY
304120	3	CORDILLERA	4	ATYRA	6	120	LOTE NUEVO
304130	3	CORDILLERA	4	ATYRA	6	130	POTRERO
304140	3	CORDILLERA	4	ATYRA	6	140	MONTE ALTO
304150	3	CORDILLERA	4	ATYRA	6	150	MBURURU
304160	3	CORDILLERA	4	ATYRA	6	160	COMANDANTE OJEDA
304170	3	CORDILLERA	4	ATYRA	6	170	CAACUPEMI
304180	3	CORDILLERA	4	ATYRA	6	180	CAUGUA
304190	3	CORDILLERA	4	ATYRA	6	190	CANDIA
304200	3	CORDILLERA	4	ATYRA	6	200	ZANJA HU
304210	3	CORDILLERA	4	ATYRA	6	210	SAN VICENTE
305001	3	CORDILLERA	5	CARAGUATAY	1	1	SAN MIGUEL
305002	3	CORDILLERA	5	CARAGUATAY	1	2	CRISTO REY
305003	3	CORDILLERA	5	CARAGUATAY	1	3	CENTRO
305004	3	CORDILLERA	5	CARAGUATAY	1	4	SAN JUAN
305005	3	CORDILLERA	5	CARAGUATAY	1	5	SAN ROQUE GONZALEZ
305006	3	CORDILLERA	5	CARAGUATAY	1	6	SAN JORGE
305007	3	CORDILLERA	5	CARAGUATAY	1	7	VIRGEN PEREGRINO
305008	3	CORDILLERA	5	CARAGUATAY	1	8	SAN JOSE
305009	3	CORDILLERA	5	CARAGUATAY	1	9	SANTA LIBRADA
305110	3	CORDILLERA	5	CARAGUATAY	6	110	VERA ACOSTA
305120	3	CORDILLERA	5	CARAGUATAY	6	120	ISLA PUKU
305130	3	CORDILLERA	5	CARAGUATAY	6	130	GENERAL GENES
305140	3	CORDILLERA	5	CARAGUATAY	6	140	ALFONSO LOMA
305150	3	CORDILLERA	5	CARAGUATAY	6	150	HUGUA PO'I
305160	3	CORDILLERA	5	CARAGUATAY	6	160	ROLON
305170	3	CORDILLERA	5	CARAGUATAY	6	170	INMACULADA
305180	3	CORDILLERA	5	CARAGUATAY	6	180	MARIA  AUXILIADORA
305190	3	CORDILLERA	5	CARAGUATAY	6	190	COSTA YBATE
305200	3	CORDILLERA	5	CARAGUATAY	6	200	SAN MIGUEL
305210	3	CORDILLERA	5	CARAGUATAY	6	210	HUGUA GUAZU
305220	3	CORDILLERA	5	CARAGUATAY	6	220	BOQUERON
305240	3	CORDILLERA	5	CARAGUATAY	6	240	FULGENCIO YEGROS
305250	3	CORDILLERA	5	CARAGUATAY	6	250	TENIENTE GONZALEZ
305260	3	CORDILLERA	5	CARAGUATAY	6	260	CAPELLANIA
305270	3	CORDILLERA	5	CARAGUATAY	6	270	TACUARY
305280	3	CORDILLERA	5	CARAGUATAY	6	280	VALLE'I
306001	3	CORDILLERA	6	EMBOSCADA	1	1	SALVADOR DEL MUNDO
306002	3	CORDILLERA	6	EMBOSCADA	1	2	VIRGEN DE LA APARECIDA
306003	3	CORDILLERA	6	EMBOSCADA	1	3	VIRGEN DE FATIMA
306004	3	CORDILLERA	6	EMBOSCADA	1	4	SOL NACIENTE
306005	3	CORDILLERA	6	EMBOSCADA	1	5	SANTO TOMAS
306006	3	CORDILLERA	6	EMBOSCADA	1	6	RAFAEL DE LA MONEDA
306007	3	CORDILLERA	6	EMBOSCADA	1	7	CATOLICO
306008	3	CORDILLERA	6	EMBOSCADA	1	8	CARAGUATAY
306009	3	CORDILLERA	6	EMBOSCADA	1	9	NAZARETH
306010	3	CORDILLERA	6	EMBOSCADA	1	10	SAN CAYETANO
306100	3	CORDILLERA	6	EMBOSCADA	6	100	KOKUE GUASU
306110	3	CORDILLERA	6	EMBOSCADA	6	110	MINAS
306130	3	CORDILLERA	6	EMBOSCADA	6	130	GUAJAYVITY
306140	3	CORDILLERA	6	EMBOSCADA	6	140	ISLA JOVAI
306150	3	CORDILLERA	6	EMBOSCADA	6	150	CORDILLERA GUY
306160	3	CORDILLERA	6	EMBOSCADA	6	160	ISLA ALTA
306170	3	CORDILLERA	6	EMBOSCADA	6	170	PASO PE
307001	3	CORDILLERA	7	EUSEBIO AYALA	1	1	INDUSTRIAL
307002	3	CORDILLERA	7	EUSEBIO AYALA	1	2	SAN BLAS
307003	3	CORDILLERA	7	EUSEBIO AYALA	1	3	SAN ROQUE
307004	3	CORDILLERA	7	EUSEBIO AYALA	1	4	SANTA ROSA
307005	3	CORDILLERA	7	EUSEBIO AYALA	1	5	SAN RAFAEL
307006	3	CORDILLERA	7	EUSEBIO AYALA	1	6	SAGRADO CORAZON DE JESUS
307007	3	CORDILLERA	7	EUSEBIO AYALA	1	7	INMACULADA
307008	3	CORDILLERA	7	EUSEBIO AYALA	1	8	GRAL. BERNARDINO CABALLERO
307009	3	CORDILLERA	7	EUSEBIO AYALA	1	9	SAN JUAN BAUTISTA
307010	3	CORDILLERA	7	EUSEBIO AYALA	1	10	6 DE ENERO
307011	3	CORDILLERA	7	EUSEBIO AYALA	1	11	CARMENCITA
307012	3	CORDILLERA	7	EUSEBIO AYALA	1	12	ASENTAMIENTO SAN PEDRO Y SAN PABLO
307013	3	CORDILLERA	7	EUSEBIO AYALA	1	13	ASENTAMIENTO SAN ANTONIO
307100	3	CORDILLERA	7	EUSEBIO AYALA	6	100	ACOSTA NU
307110	3	CORDILLERA	7	EUSEBIO AYALA	6	110	HU YVATY
307120	3	CORDILLERA	7	EUSEBIO AYALA	6	120	ISLA
307130	3	CORDILLERA	7	EUSEBIO AYALA	6	130	JACAREY
307140	3	CORDILLERA	7	EUSEBIO AYALA	6	140	AGUAITY
307150	3	CORDILLERA	7	EUSEBIO AYALA	6	150	PUNTA
307160	3	CORDILLERA	7	EUSEBIO AYALA	6	160	SANTA TERESITA
307170	3	CORDILLERA	7	EUSEBIO AYALA	6	170	COSTA
307180	3	CORDILLERA	7	EUSEBIO AYALA	6	180	JAULA KUE
307190	3	CORDILLERA	7	EUSEBIO AYALA	6	190	TUJUKUA
307200	3	CORDILLERA	7	EUSEBIO AYALA	6	200	POTRERO SAN JOSE
307210	3	CORDILLERA	7	EUSEBIO AYALA	6	210	KAPI'IPE
307220	3	CORDILLERA	7	EUSEBIO AYALA	6	220	RUBIO NU
307230	3	CORDILLERA	7	EUSEBIO AYALA	6	230	LAS INDIAS
307240	3	CORDILLERA	7	EUSEBIO AYALA	6	240	TAVA'I
307250	3	CORDILLERA	7	EUSEBIO AYALA	6	250	CURUPAYTY
307260	3	CORDILLERA	7	EUSEBIO AYALA	6	260	BOQUERON 1
307270	3	CORDILLERA	7	EUSEBIO AYALA	6	270	CAPILLA LOMA 1
307280	3	CORDILLERA	7	EUSEBIO AYALA	6	280	CANADA DEL CARMEN
307290	3	CORDILLERA	7	EUSEBIO AYALA	6	290	CAPILLA LOMA 2
307300	3	CORDILLERA	7	EUSEBIO AYALA	6	300	BOQUERON 2
307310	3	CORDILLERA	7	EUSEBIO AYALA	6	310	KAUNDY
307320	3	CORDILLERA	7	EUSEBIO AYALA	6	320	CERRO PORTENO
307330	3	CORDILLERA	7	EUSEBIO AYALA	6	330	MARISCAL FRANCISCO SOLANO LOPEZZ
307340	3	CORDILLERA	7	EUSEBIO AYALA	6	340	CABANAS KUE
307350	3	CORDILLERA	7	EUSEBIO AYALA	6	350	ASENT. 4 DE OCTUBRE
307360	3	CORDILLERA	7	EUSEBIO AYALA	6	360	ASENT. LOMA TUYUTI
307370	3	CORDILLERA	7	EUSEBIO AYALA	6	370	ASENT. CRISTO REDENTOR
308001	3	CORDILLERA	8	ISLA PUCU	1	1	SAN FRANCISCO
308002	3	CORDILLERA	8	ISLA PUCU	1	2	CENTRO
308003	3	CORDILLERA	8	ISLA PUCU	1	3	MARIA AUXILIADORA
308004	3	CORDILLERA	8	ISLA PUCU	1	4	SAN ANTONIO
308005	3	CORDILLERA	8	ISLA PUCU	1	5	LOMA
308006	3	CORDILLERA	8	ISLA PUCU	1	6	SAN ROQUE
308007	3	CORDILLERA	8	ISLA PUCU	1	7	SAN JOSE OBRERO
308100	3	CORDILLERA	8	ISLA PUCU	6	100	SAN JUAN
308110	3	CORDILLERA	8	ISLA PUCU	6	110	SAN JUAN BARRIO MARIA AUXILIADORA
308120	3	CORDILLERA	8	ISLA PUCU	6	120	PINDOTY BARRIO SANTA MARIA
308130	3	CORDILLERA	8	ISLA PUCU	6	130	SAN JUAN BARRIO SANTA ROSA
308140	3	CORDILLERA	8	ISLA PUCU	6	140	PINDOTY BARRIO INMACULADA
308150	3	CORDILLERA	8	ISLA PUCU	6	150	PINDOTY BARRIO SANTO DOMINGO
308160	3	CORDILLERA	8	ISLA PUCU	6	160	29 DE SETIEMBRE
308170	3	CORDILLERA	8	ISLA PUCU	6	170	PINDOTY
308200	3	CORDILLERA	8	ISLA PUCU	6	200	TAPE GUASU
308210	3	CORDILLERA	8	ISLA PUCU	6	210	LOMA MARIA AUXILIADORA
308220	3	CORDILLERA	8	ISLA PUCU	6	220	ARROYO PORA
308240	3	CORDILLERA	8	ISLA PUCU	6	240	AGUARAY
308280	3	CORDILLERA	8	ISLA PUCU	6	280	ITA YVATE
308290	3	CORDILLERA	8	ISLA PUCU	6	290	ASENT. 4 DE OCTUBRE
309001	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	1	SOL NACIENTE
309002	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	2	VIRGEN DEL ROSARIO
309003	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	3	SAN ANTONIO
309004	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	4	3 DE MAYO
309005	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	5	SANTA LUCIA
309006	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	6	SAN BLAS
309007	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	7	SAN CARLOS
309008	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	8	SAN JOSE
309009	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	9	SANTO DOMINGO
309010	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	1	10	PIRAYU'I
309100	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	6	100	TAKUARA
309120	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	6	120	RUBIO NU
309130	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	6	130	HUGUA PO'I
309140	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	6	140	LOMA MEDINA
309150	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	6	150	POTRERO ANGELITO
309180	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	6	180	PIRAYU'I
309190	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	6	190	CARYI LOMA
309200	3	CORDILLERA	9	ITACURUBI DE LA CORDILLERA	6	200	MINAS KUE
310001	3	CORDILLERA	10	JUAN DE MENA	1	1	CENTRO
310002	3	CORDILLERA	10	JUAN DE MENA	1	2	MARIA AUXILIADORA
310003	3	CORDILLERA	10	JUAN DE MENA	1	3	FIDEL MAIZ
310004	3	CORDILLERA	10	JUAN DE MENA	1	4	SAN MIGUEL
310005	3	CORDILLERA	10	JUAN DE MENA	1	5	SAN JUAN
310100	3	CORDILLERA	10	JUAN DE MENA	6	100	ZONA DE ESTANCIAS
310110	3	CORDILLERA	10	JUAN DE MENA	6	110	SAN RAFAEL NUPY
310120	3	CORDILLERA	10	JUAN DE MENA	6	120	SANTA LUCIA
310130	3	CORDILLERA	10	JUAN DE MENA	6	130	NUEVA ASUNCION
310180	3	CORDILLERA	10	JUAN DE MENA	6	180	COLONIA REGINA MARECO
310190	3	CORDILLERA	10	JUAN DE MENA	6	190	WENGREEN
310240	3	CORDILLERA	10	JUAN DE MENA	6	240	8 DE DICIEMBRE
310260	3	CORDILLERA	10	JUAN DE MENA	6	260	UNION PARAGUAYA
310270	3	CORDILLERA	10	JUAN DE MENA	6	270	LA UNION
310280	3	CORDILLERA	10	JUAN DE MENA	6	280	SAN ANTONIO
310290	3	CORDILLERA	10	JUAN DE MENA	6	290	SANTO DOMINGO
311001	3	CORDILLERA	11	LOMA GRANDE	1	1	SAN FRANCISCO
311002	3	CORDILLERA	11	LOMA GRANDE	1	2	MARIA AUXILIADORA
311003	3	CORDILLERA	11	LOMA GRANDE	1	3	MARISCAL ESTIGARRIBIA
311004	3	CORDILLERA	11	LOMA GRANDE	1	4	SAN MIGUEL
311100	3	CORDILLERA	11	LOMA GRANDE	6	100	SANDIATY
311110	3	CORDILLERA	11	LOMA GRANDE	6	110	JAGUARETE KUA
311120	3	CORDILLERA	11	LOMA GRANDE	6	120	VILLA FLOR
311130	3	CORDILLERA	11	LOMA GRANDE	6	130	SAN ANTONIO
311140	3	CORDILLERA	11	LOMA GRANDE	6	140	SAN FRANCISCO
311150	3	CORDILLERA	11	LOMA GRANDE	6	150	SAN MIGUEL
311160	3	CORDILLERA	11	LOMA GRANDE	6	160	MARISCAL ESTIGARRIBIA
311170	3	CORDILLERA	11	LOMA GRANDE	6	170	BOQUERON
311180	3	CORDILLERA	11	LOMA GRANDE	6	180	AGUA-Y
312001	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	1	1	CENTRO
312100	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	100	RIO NEGRO SANTA LUCIA
312110	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	110	SAN MARCOS
312120	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	120	RIO NEGRO POTRERO
312130	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	130	RIO NEGRO ENSENADA
312140	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	140	RIO NEGRO LLANES
312150	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	150	LAGUNA
312160	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	160	ISLA
312170	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	170	PUNTA SAKA
312180	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	180	SAN ANTONIO
312190	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	190	INMACULADA
312200	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	200	VIRGEN DEL CARMEN
312210	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	210	COLONIA ESPERANZA
312220	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	220	SAN BLAS
312230	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	230	PIRAY
312240	3	CORDILLERA	12	MBOCAYATY DEL YHAGUY	6	240	VIRGEN DE FATIMA
313001	3	CORDILLERA	13	NUEVA COLOMBIA	1	1	SAN BLAS
313002	3	CORDILLERA	13	NUEVA COLOMBIA	1	2	SAN MIGUEL
313003	3	CORDILLERA	13	NUEVA COLOMBIA	1	3	VIRGEN DEL CARMEN
313004	3	CORDILLERA	13	NUEVA COLOMBIA	1	4	SANTA LUCIA
313005	3	CORDILLERA	13	NUEVA COLOMBIA	1	5	SAN CAYETANO
313100	3	CORDILLERA	13	NUEVA COLOMBIA	6	100	SIRATY
313110	3	CORDILLERA	13	NUEVA COLOMBIA	6	110	KIRAYTY
313120	3	CORDILLERA	13	NUEVA COLOMBIA	6	120	ISLA ALTA
313130	3	CORDILLERA	13	NUEVA COLOMBIA	6	130	BOQUERON CAACUPEMI
313140	3	CORDILLERA	13	NUEVA COLOMBIA	6	140	BOQUERON TRES REYES
313150	3	CORDILLERA	13	NUEVA COLOMBIA	6	150	INGLES KUE
314001	3	CORDILLERA	14	PIRIBEBUY	1	1	SAN BLAS
314002	3	CORDILLERA	14	PIRIBEBUY	1	2	BARRIO FATIMA
314003	3	CORDILLERA	14	PIRIBEBUY	1	3	SANTA  ANA
314004	3	CORDILLERA	14	PIRIBEBUY	1	4	CENTRO
314005	3	CORDILLERA	14	PIRIBEBUY	1	5	MARIA AUXILIADORA
314006	3	CORDILLERA	14	PIRIBEBUY	1	6	VIRGEN DEL ROSARIO
314100	3	CORDILLERA	14	PIRIBEBUY	6	100	GUASU ROKAI
314110	3	CORDILLERA	14	PIRIBEBUY	6	110	JAKAREY
314120	3	CORDILLERA	14	PIRIBEBUY	6	120	ITA YVU
314130	3	CORDILLERA	14	PIRIBEBUY	6	130	ITA GUYRA
314140	3	CORDILLERA	14	PIRIBEBUY	6	140	MARISCAL LOPEZ
314150	3	CORDILLERA	14	PIRIBEBUY	6	150	CANADA
314160	3	CORDILLERA	14	PIRIBEBUY	6	160	NU GUASU
314170	3	CORDILLERA	14	PIRIBEBUY	6	170	PASO HU
314180	3	CORDILLERA	14	PIRIBEBUY	6	180	TAPE GUASU
314190	3	CORDILLERA	14	PIRIBEBUY	6	190	CORDILLERA
314200	3	CORDILLERA	14	PIRIBEBUY	6	200	YATAITY
314210	3	CORDILLERA	14	PIRIBEBUY	6	210	COLONIA PIRARETA
314220	3	CORDILLERA	14	PIRIBEBUY	6	220	4 DE JULIO
314230	3	CORDILLERA	14	PIRIBEBUY	6	230	YHAGUY MI
314240	3	CORDILLERA	14	PIRIBEBUY	6	240	PRESIDENTE FRANCO
314250	3	CORDILLERA	14	PIRIBEBUY	6	250	PASITO
314260	3	CORDILLERA	14	PIRIBEBUY	6	260	YKUA PORA
314270	3	CORDILLERA	14	PIRIBEBUY	6	270	NARANJO
314280	3	CORDILLERA	14	PIRIBEBUY	6	280	ITA MOROTI
314290	3	CORDILLERA	14	PIRIBEBUY	6	290	CHOLOLO GUASU
314300	3	CORDILLERA	14	PIRIBEBUY	6	300	YHAGUY GUASU
314310	3	CORDILLERA	14	PIRIBEBUY	6	310	OJOPOI
314320	3	CORDILLERA	14	PIRIBEBUY	6	320	ITA MOROTI 2
314330	3	CORDILLERA	14	PIRIBEBUY	6	330	ITA MOROTI GUASU
314340	3	CORDILLERA	14	PIRIBEBUY	6	340	YPA'U
314350	3	CORDILLERA	14	PIRIBEBUY	6	350	GENERAL AQUINO
314360	3	CORDILLERA	14	PIRIBEBUY	6	360	CHOLOLO
314370	3	CORDILLERA	14	PIRIBEBUY	6	370	CAPILLA KUE
315001	3	CORDILLERA	15	PRIMERO DE MARZO	1	1	VIRGEN DEL ROSARIO
315002	3	CORDILLERA	15	PRIMERO DE MARZO	1	2	VIRGEN DE LOS REMEDIOS
315003	3	CORDILLERA	15	PRIMERO DE MARZO	1	3	SAN JUAN
315004	3	CORDILLERA	15	PRIMERO DE MARZO	1	4	SAN ROQUE
315005	3	CORDILLERA	15	PRIMERO DE MARZO	1	5	SANTA MARIA
315100	3	CORDILLERA	15	PRIMERO DE MARZO	6	100	KAAGUY KUPE
315110	3	CORDILLERA	15	PRIMERO DE MARZO	6	110	GARAYO
315120	3	CORDILLERA	15	PRIMERO DE MARZO	6	120	ROJAS SILVA
315130	3	CORDILLERA	15	PRIMERO DE MARZO	6	130	POTRERITO
315140	3	CORDILLERA	15	PRIMERO DE MARZO	6	140	SARGENTO BAEZ
315150	3	CORDILLERA	15	PRIMERO DE MARZO	6	150	MARISCAL ESTIGARRIBIA
315160	3	CORDILLERA	15	PRIMERO DE MARZO	6	160	GENERAL DIAZ
315170	3	CORDILLERA	15	PRIMERO DE MARZO	6	170	CANADA DEL CARMEN
315180	3	CORDILLERA	15	PRIMERO DE MARZO	6	180	SAN BLAS
315190	3	CORDILLERA	15	PRIMERO DE MARZO	6	190	SARGENTO CABALLERO
315200	3	CORDILLERA	15	PRIMERO DE MARZO	6	200	SAN ANTONIO
315210	3	CORDILLERA	15	PRIMERO DE MARZO	6	210	BOQUERON
315220	3	CORDILLERA	15	PRIMERO DE MARZO	6	220	8 DE DICIEMBRE
316001	3	CORDILLERA	16	SAN BERNARDINO	1	1	CRISTOBAL COLON
316002	3	CORDILLERA	16	SAN BERNARDINO	1	2	CASCO HISTORICO
316003	3	CORDILLERA	16	SAN BERNARDINO	1	3	SANTA CLARA URBANIZACION
316004	3	CORDILLERA	16	SAN BERNARDINO	1	4	SANTA ROSALINA
316005	3	CORDILLERA	16	SAN BERNARDINO	1	5	BARRIO 5
316006	3	CORDILLERA	16	SAN BERNARDINO	1	6	SAN MIGUEL
316007	3	CORDILLERA	16	SAN BERNARDINO	1	7	NUESTRA SENORA DE LA ASUNCION
316008	3	CORDILLERA	16	SAN BERNARDINO	1	8	YBY ANGUY PRIMERA
316009	3	CORDILLERA	16	SAN BERNARDINO	1	9	YBY ANGUY SEGUNDA
316010	3	CORDILLERA	16	SAN BERNARDINO	1	10	VILLA DELFINA
316011	3	CORDILLERA	16	SAN BERNARDINO	1	11	SANTO DOMINGO
316012	3	CORDILLERA	16	SAN BERNARDINO	1	12	JARDIN
316013	3	CORDILLERA	16	SAN BERNARDINO	1	13	COUNTRY CLUB SOL DE VERANO
316100	3	CORDILLERA	16	SAN BERNARDINO	6	100	HERIBERTA MATIAUDA
316110	3	CORDILLERA	16	SAN BERNARDINO	6	110	CIERVO KUA
316120	3	CORDILLERA	16	SAN BERNARDINO	6	120	YVU
316140	3	CORDILLERA	16	SAN BERNARDINO	6	140	YVY ANGUY SEGUNDA
316150	3	CORDILLERA	16	SAN BERNARDINO	6	150	PIRAYU'I
316160	3	CORDILLERA	16	SAN BERNARDINO	6	160	VILLA REAL
317001	3	CORDILLERA	17	SANTA ELENA	1	1	SANTA ELENA
317002	3	CORDILLERA	17	SANTA ELENA	1	2	MERCADO
317003	3	CORDILLERA	17	SANTA ELENA	1	3	SAN ANTONIO
317004	3	CORDILLERA	17	SANTA ELENA	1	4	LOMA CLAVEL
317100	3	CORDILLERA	17	SANTA ELENA	6	100	SAN ROQUE
317110	3	CORDILLERA	17	SANTA ELENA	6	110	SANTA CATALINA
317130	3	CORDILLERA	17	SANTA ELENA	6	130	PASO TRANQUERA
317150	3	CORDILLERA	17	SANTA ELENA	6	150	MARIA AUXILIADORA
317160	3	CORDILLERA	17	SANTA ELENA	6	160	COSTA ELENA
317170	3	CORDILLERA	17	SANTA ELENA	6	170	PASO CABRAL
317180	3	CORDILLERA	17	SANTA ELENA	6	180	TOROPY LOMA
317190	3	CORDILLERA	17	SANTA ELENA	6	190	SAN ANTONIO
317200	3	CORDILLERA	17	SANTA ELENA	6	200	LOMA CLAVEL
317210	3	CORDILLERA	17	SANTA ELENA	6	210	TOROPY RUGUA
317220	3	CORDILLERA	17	SANTA ELENA	6	220	YCUA PORA
318001	3	CORDILLERA	18	TOBATI	1	1	SAN ROQUE
318002	3	CORDILLERA	18	TOBATI	1	2	VIRGEN DE FATIMA
318003	3	CORDILLERA	18	TOBATI	1	3	SAN FRANCISCO
318004	3	CORDILLERA	18	TOBATI	1	4	SANTA TERESITA
318005	3	CORDILLERA	18	TOBATI	1	5	INMACULADA CONCEPCION
318006	3	CORDILLERA	18	TOBATI	1	6	MARIA AUXILIADORA
318007	3	CORDILLERA	18	TOBATI	1	7	SAN JOSE
318008	3	CORDILLERA	18	TOBATI	1	8	VIRGEN DE LOS REMEDIOS
318009	3	CORDILLERA	18	TOBATI	1	9	SAN PEDRO
318010	3	CORDILLERA	18	TOBATI	1	10	CENTRO
318011	3	CORDILLERA	18	TOBATI	1	11	SANTA LUCIA
318012	3	CORDILLERA	18	TOBATI	1	12	SAN BLAS
318013	3	CORDILLERA	18	TOBATI	1	13	SAN ANTONIO
318014	3	CORDILLERA	18	TOBATI	1	14	CRISTO REY
318015	3	CORDILLERA	18	TOBATI	1	15	SAN CAYETANO
318100	3	CORDILLERA	18	TOBATI	6	100	PEDRO JUAN CABALLERO
318110	3	CORDILLERA	18	TOBATI	6	110	APARYPY
318120	3	CORDILLERA	18	TOBATI	6	120	ISLA GUASU
318130	3	CORDILLERA	18	TOBATI	6	130	ISLA FLORIDA
318140	3	CORDILLERA	18	TOBATI	6	140	ENSENADA
318150	3	CORDILLERA	18	TOBATI	6	150	COSTA ALEGRE
318160	3	CORDILLERA	18	TOBATI	6	160	SANTA ROSA
318170	3	CORDILLERA	18	TOBATI	6	170	VILLA LAS MERCEDES
318180	3	CORDILLERA	18	TOBATI	6	180	MOMPOX
318190	3	CORDILLERA	18	TOBATI	6	190	HU YVATY
318200	3	CORDILLERA	18	TOBATI	6	200	POTRERO
318210	3	CORDILLERA	18	TOBATI	6	210	21 DE JULIO
318220	3	CORDILLERA	18	TOBATI	6	220	ROSADO
318230	3	CORDILLERA	18	TOBATI	6	230	LOMA VERDE
318240	3	CORDILLERA	18	TOBATI	6	240	ASENT. SAN CAYETANO
318250	3	CORDILLERA	18	TOBATI	6	250	ASENT. SANTA CLARA
318260	3	CORDILLERA	18	TOBATI	6	260	ASENT. CORONILLO
319001	3	CORDILLERA	19	VALENZUELA	1	1	CENTRO
319002	3	CORDILLERA	19	VALENZUELA	1	2	SAN FRANCISCO
319003	3	CORDILLERA	19	VALENZUELA	1	3	SAN ROQUE
319004	3	CORDILLERA	19	VALENZUELA	1	4	SAGRADA FAMILIA
319005	3	CORDILLERA	19	VALENZUELA	1	5	SAN BLAS
319006	3	CORDILLERA	19	VALENZUELA	1	6	SAN JOSE
319007	3	CORDILLERA	19	VALENZUELA	1	7	VIRGEN DEL CARMEN
319100	3	CORDILLERA	19	VALENZUELA	6	100	TACUATI
319110	3	CORDILLERA	19	VALENZUELA	6	110	COLONIA PIRARETA
319120	3	CORDILLERA	19	VALENZUELA	6	120	NU GUASU
319140	3	CORDILLERA	19	VALENZUELA	6	140	JUAN CANCIO FLECHA
319160	3	CORDILLERA	19	VALENZUELA	6	160	MARISCAL LOPEZ
319170	3	CORDILLERA	19	VALENZUELA	6	170	GUASU KUA
319180	3	CORDILLERA	19	VALENZUELA	6	180	SAN FRANCISCO
319190	3	CORDILLERA	19	VALENZUELA	6	190	CURUPAYTY
319200	3	CORDILLERA	19	VALENZUELA	6	200	POTRERO JAKAREY
319210	3	CORDILLERA	19	VALENZUELA	6	210	POTRERITO
319220	3	CORDILLERA	19	VALENZUELA	6	220	POTRERO PUKU
319230	3	CORDILLERA	19	VALENZUELA	6	230	CERRO PERO
319240	3	CORDILLERA	19	VALENZUELA	6	240	ITA MOROTI
319250	3	CORDILLERA	19	VALENZUELA	6	250	OJOPOI
319260	3	CORDILLERA	19	VALENZUELA	6	260	GENERAL DIAZ
319270	3	CORDILLERA	19	VALENZUELA	6	270	ROSADO YVATE
320001	3	CORDILLERA	20	SAN JOSE OBRERO	1	1	LIBERTAD
320100	3	CORDILLERA	20	SAN JOSE OBRERO	6	100	GASORY
320110	3	CORDILLERA	20	SAN JOSE OBRERO	6	110	ALFONSO CENTRAL
320120	3	CORDILLERA	20	SAN JOSE OBRERO	6	120	ALFONSO TRANQUERA
320140	3	CORDILLERA	20	SAN JOSE OBRERO	6	140	SAN BLAS
320150	3	CORDILLERA	20	SAN JOSE OBRERO	6	150	SAN ANTONIO
320160	3	CORDILLERA	20	SAN JOSE OBRERO	6	160	SAN PEDRO
320170	3	CORDILLERA	20	SAN JOSE OBRERO	6	170	SAN JUAN BAUTISTA
320180	3	CORDILLERA	20	SAN JOSE OBRERO	6	180	LAS MERCEDES
401001	4	GUAIRA	1	VILLARRICA	1	1	TUYUTIMI
401002	4	GUAIRA	1	VILLARRICA	1	2	YVAROTY
401003	4	GUAIRA	1	VILLARRICA	1	3	SAN MIGUEL
401004	4	GUAIRA	1	VILLARRICA	1	4	LOMAS VALENTINAS
401005	4	GUAIRA	1	VILLARRICA	1	5	CENTRO
401006	4	GUAIRA	1	VILLARRICA	1	6	ESTACION
401007	4	GUAIRA	1	VILLARRICA	1	7	SANTA LIBRADA
401008	4	GUAIRA	1	VILLARRICA	1	8	SANTA LUCIA
401009	4	GUAIRA	1	VILLARRICA	1	9	YVYRA ROVY
401010	4	GUAIRA	1	VILLARRICA	1	10	SAN BLAS
401011	4	GUAIRA	1	VILLARRICA	1	11	SALESIANO
401012	4	GUAIRA	1	VILLARRICA	1	12	CRISTO REY
401013	4	GUAIRA	1	VILLARRICA	1	13	KURUSU FRANCISCO
401014	4	GUAIRA	1	VILLARRICA	1	14	URBANO
401015	4	GUAIRA	1	VILLARRICA	1	15	ASENT. SINAI 1, 2 y 3
401100	4	GUAIRA	1	VILLARRICA	6	100	TUYUTIMI
401110	4	GUAIRA	1	VILLARRICA	6	110	CAROVENI
401120	4	GUAIRA	1	VILLARRICA	6	120	POTRERO ISLA
401130	4	GUAIRA	1	VILLARRICA	6	130	POTRERO BAEZ
401160	4	GUAIRA	1	VILLARRICA	6	160	RINCON
401170	4	GUAIRA	1	VILLARRICA	6	170	PISADERA
401180	4	GUAIRA	1	VILLARRICA	6	180	MBOPI KUA
401190	4	GUAIRA	1	VILLARRICA	6	190	YTORORO
401200	4	GUAIRA	1	VILLARRICA	6	200	POTRERITO
401210	4	GUAIRA	1	VILLARRICA	6	210	DONA JUANA
401220	4	GUAIRA	1	VILLARRICA	6	220	LEMOS
401230	4	GUAIRA	1	VILLARRICA	6	230	14 DE MAYO
401250	4	GUAIRA	1	VILLARRICA	6	250	ROSADO
401260	4	GUAIRA	1	VILLARRICA	6	260	CAAZAPAMI
401280	4	GUAIRA	1	VILLARRICA	6	280	PUNTA KUPE
401290	4	GUAIRA	1	VILLARRICA	6	290	COSTA ESPINILLO
401310	4	GUAIRA	1	VILLARRICA	6	310	YBAROTY
401320	4	GUAIRA	1	VILLARRICA	6	320	POTRERO SAN FRANCISCO
401330	4	GUAIRA	1	VILLARRICA	6	330	CANADA MI
401340	4	GUAIRA	1	VILLARRICA	6	340	CANADA TAPE KA'AGUY
401350	4	GUAIRA	1	VILLARRICA	6	350	CANADA SAN JUAN
401360	4	GUAIRA	1	VILLARRICA	6	360	LOMA SAN FRANCISCO
401370	4	GUAIRA	1	VILLARRICA	6	370	ITA YVU
401375	4	GUAIRA	1	VILLARRICA	3	375	ITA YVU SUB-URBANO
401380	4	GUAIRA	1	VILLARRICA	6	380	SANTA ROSA
402001	4	GUAIRA	2	BORJA	1	1	URBANO
402110	4	GUAIRA	2	BORJA	6	110	BOQUERON
402120	4	GUAIRA	2	BORJA	6	120	COLONIA 20 DE JUNIO
402130	4	GUAIRA	2	BORJA	6	130	ISLA ALTA
402140	4	GUAIRA	2	BORJA	6	140	MACARRO
402150	4	GUAIRA	2	BORJA	6	150	AGUSTIN MOLAS
402170	4	GUAIRA	2	BORJA	6	170	LOMA'I
402180	4	GUAIRA	2	BORJA	6	180	COLONIA TAKUARE'E
402190	4	GUAIRA	2	BORJA	6	190	SAN PEDRO
402200	4	GUAIRA	2	BORJA	6	200	VALLE PE
402205	4	GUAIRA	2	BORJA	3	205	VALLE PE SUB - URBANO
402210	4	GUAIRA	2	BORJA	6	210	TTE. ROJAS SILVA
402220	4	GUAIRA	2	BORJA	6	220	RINCON
402230	4	GUAIRA	2	BORJA	6	230	CORDILLERA
402240	4	GUAIRA	2	BORJA	6	240	COSTEADA
402250	4	GUAIRA	2	BORJA	6	250	PASO KUE
402260	4	GUAIRA	2	BORJA	6	260	YHAKA GUASU
402270	4	GUAIRA	2	BORJA	6	270	SAN ANTONIO
402280	4	GUAIRA	2	BORJA	6	280	COSTA HU
402290	4	GUAIRA	2	BORJA	6	290	CUATRO BOCAS
402300	4	GUAIRA	2	BORJA	6	300	SAN ISIDRO
402310	4	GUAIRA	2	BORJA	6	310	NU ROKY
402320	4	GUAIRA	2	BORJA	6	320	MALVINAS
402330	4	GUAIRA	2	BORJA	6	330	ROJAS RUGUA
403002	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	1	2	VIRGEN DEL HUERTO
403003	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	1	3	SAN MIGUEL
403004	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	1	4	CENTRO
403005	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	1	5	SANTA ROSA
403006	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	1	6	SAN BLAS
403007	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	1	7	LOMA CLAVEL
403008	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	1	8	ALEGRE
403100	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	100	ITACURUBI
403110	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	110	GUAJAKI
403120	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	120	ALEGRE
403130	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	130	CHACORE
403140	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	140	NUMI
403150	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	150	SAN MIGUEL
403170	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	170	YHOVY
403180	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	180	ASENT. NUEVA ESPERANZA
403190	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	190	CERRO PUNTA
403200	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	200	LOMA CLAVEL
403210	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	210	COSTA CABALLERO
403220	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	220	KORA GUASU
403240	4	GUAIRA	3	CAPITAN MAURICIO JOSE TROCHE	6	240	SANTA LIBRADA
404001	4	GUAIRA	4	CORONEL MARTINEZ	1	1	SAN RAMON
404002	4	GUAIRA	4	CORONEL MARTINEZ	1	2	SAN MIGUEL
404003	4	GUAIRA	4	CORONEL MARTINEZ	1	3	SAN PEDRO
404004	4	GUAIRA	4	CORONEL MARTINEZ	1	4	SAGRADO CORAZON DE JESUS
404005	4	GUAIRA	4	CORONEL MARTINEZ	1	5	INMACULADA CONCEPCION
404006	4	GUAIRA	4	CORONEL MARTINEZ	1	6	VIRGEN DE FATIMA
404160	4	GUAIRA	4	CORONEL MARTINEZ	6	160	POTRERO VILLAR
404170	4	GUAIRA	4	CORONEL MARTINEZ	6	170	MONGES RUGUA
404180	4	GUAIRA	4	CORONEL MARTINEZ	6	180	MONGES PASO
404190	4	GUAIRA	4	CORONEL MARTINEZ	6	190	COSTA BARRIOS
404200	4	GUAIRA	4	CORONEL MARTINEZ	6	200	ARROYITO
404210	4	GUAIRA	4	CORONEL MARTINEZ	6	210	TENIENTE BOGADO
404220	4	GUAIRA	4	CORONEL MARTINEZ	6	220	SAN PEDRO
404230	4	GUAIRA	4	CORONEL MARTINEZ	6	230	CAPELLAN CARDOZO
404240	4	GUAIRA	4	CORONEL MARTINEZ	6	240	FERREIRA
405001	4	GUAIRA	5	FELIX PEREZ CARDOZO	1	1	CENTRO
405002	4	GUAIRA	5	FELIX PEREZ CARDOZO	1	2	ESTACION
405003	4	GUAIRA	5	FELIX PEREZ CARDOZO	1	3	SANTA ELENA
405100	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	100	POTRERO BENITEZ
405110	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	110	TAVA'I
405120	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	120	TORO KUA
405130	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	130	AQUINO COSTA
405140	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	140	BOMBILLA
405150	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	150	POTRERO MELGAREJO
405160	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	160	COSTA MERCADO
405170	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	170	KAUNDY
405180	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	180	CERRITO
405190	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	190	KOKUERE GUASU
405200	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	200	ESTACION COSTA
405210	4	GUAIRA	5	FELIX PEREZ CARDOZO	6	210	SAN PEDRO
406001	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	1	1	SAN ISIDRO
406002	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	1	2	SAGRADO CORAZON DE JESUS
406003	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	1	3	SAN BLAS
406004	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	1	4	SAN ANTONIO
406005	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	1	5	KILOMETRO 29
406006	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	1	6	SAN MIGUEL
406100	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	100	CIERVO KUA
406110	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	3	110	SAN ROQUE GONZALEZ DE SANTA CRUZ SUB-URBANO
406120	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	120	NANDU KUA KILOMETRO 22
406130	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	130	KILOMETRO  25
406140	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	140	SAN BENITO
406160	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	160	SAN BLAS
406170	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	170	MBOKAJA
406180	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	180	SAN ROQUE
406190	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	190	FLORIDO
406210	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	210	KIRAYTY
406220	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	220	SANTA CATALINA
406230	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	230	NU PYAHU
406240	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	240	CERRO POLILLA SAN PEDRO
406250	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	250	JOVERE
406260	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	260	11 DE SETIEMBRE
406270	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	270	INTER PUEBLO
406280	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	280	PALMITO
406290	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	290	POTRERO
406300	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	3	300	NUESTRA SENORA DE LA ASUNCION SUB-URBANO
406310	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	310	SANTO DOMINGO
406320	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	320	VIRGEN DE FATIMA
406330	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	330	SAN RAMON
406340	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	340	JATE'I SAN JORGE
406350	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	350	SAN PATRICIO
406360	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	360	SAN PABLO
406370	4	GUAIRA	6	GRAL. EUGENIO A. GARAY	6	370	NU PYAHUMI
407001	4	GUAIRA	7	INDEPENDENCIA	1	1	VIRGEN DE FATIMA
407002	4	GUAIRA	7	INDEPENDENCIA	1	2	LAS MERCEDES
407003	4	GUAIRA	7	INDEPENDENCIA	1	3	SAN VICENTE
407004	4	GUAIRA	7	INDEPENDENCIA	1	4	LA VICTORIA
407005	4	GUAIRA	7	INDEPENDENCIA	1	5	MELGAREJO
407006	4	GUAIRA	7	INDEPENDENCIA	1	6	AZUCENA
407007	4	GUAIRA	7	INDEPENDENCIA	1	7	VISTA ALEGRE
407100	4	GUAIRA	7	INDEPENDENCIA	6	100	CALLE ALTA
407110	4	GUAIRA	7	INDEPENDENCIA	6	110	PANETEY
407120	4	GUAIRA	7	INDEPENDENCIA	6	120	KURUSU PE
407140	4	GUAIRA	7	INDEPENDENCIA	6	140	CRISTO REY
407150	4	GUAIRA	7	INDEPENDENCIA	6	150	PLANTA URBANA
407160	4	GUAIRA	7	INDEPENDENCIA	6	160	PRIMAVERA DE JESUS
407170	4	GUAIRA	7	INDEPENDENCIA	6	170	CARLOS PFANNL
407180	4	GUAIRA	7	INDEPENDENCIA	6	180	POTRERO DEL CARMEN
407190	4	GUAIRA	7	INDEPENDENCIA	6	190	YROYSA LINEA 1
407200	4	GUAIRA	7	INDEPENDENCIA	6	200	SAN ANTONIO
407210	4	GUAIRA	7	INDEPENDENCIA	6	210	PIRECA BAJA
407215	4	GUAIRA	7	INDEPENDENCIA	3	215	PIRECA ALTA SUB-URBANO
407220	4	GUAIRA	7	INDEPENDENCIA	6	220	SANTA ROSA
407230	4	GUAIRA	7	INDEPENDENCIA	6	230	SAN PEDRO
407240	4	GUAIRA	7	INDEPENDENCIA	6	240	YROYSA LINEA 2
407250	4	GUAIRA	7	INDEPENDENCIA	6	250	YROYSA LINEA 3
407260	4	GUAIRA	7	INDEPENDENCIA	6	260	SANTO DOMINGO
407270	4	GUAIRA	7	INDEPENDENCIA	6	270	YROYSA LINEA 4
407280	4	GUAIRA	7	INDEPENDENCIA	6	280	YROYSA LINEA 5
407290	4	GUAIRA	7	INDEPENDENCIA	6	290	YROYSA LINEA 6
407300	4	GUAIRA	7	INDEPENDENCIA	6	300	YROYSA LINEA 7
407310	4	GUAIRA	7	INDEPENDENCIA	6	310	YROYSA LINEA 8
407320	4	GUAIRA	7	INDEPENDENCIA	6	320	YROYSA LINEA 9
407330	4	GUAIRA	7	INDEPENDENCIA	6	330	YROYSA LINEA 10
407340	4	GUAIRA	7	INDEPENDENCIA	6	340	YROYSA LINEA 11
407350	4	GUAIRA	7	INDEPENDENCIA	6	350	YROYSA LINEA 12
407360	4	GUAIRA	7	INDEPENDENCIA	6	360	YROYSA LINEA 13
407370	4	GUAIRA	7	INDEPENDENCIA	6	370	PIRECA ALTA LINEA 14
407380	4	GUAIRA	7	INDEPENDENCIA	6	380	ZORRILLA KUE
407390	4	GUAIRA	7	INDEPENDENCIA	6	390	CALLE FLORIDA
407400	4	GUAIRA	7	INDEPENDENCIA	6	400	VISTA  ALEGRE
407410	4	GUAIRA	7	INDEPENDENCIA	6	410	CERRITO
407420	4	GUAIRA	7	INDEPENDENCIA	6	420	TERCERA FRACCION
407430	4	GUAIRA	7	INDEPENDENCIA	6	430	SAN GERVACIO
407440	4	GUAIRA	7	INDEPENDENCIA	6	440	RANCHO CUATRO
407450	4	GUAIRA	7	INDEPENDENCIA	6	450	CERRO CORA
407460	4	GUAIRA	7	INDEPENDENCIA	6	460	CAMPITO
407470	4	GUAIRA	7	INDEPENDENCIA	6	470	MARIA AUXILIADORA
407480	4	GUAIRA	7	INDEPENDENCIA	3	480	SANTA CECILIA SUB-URBANO
407490	4	GUAIRA	7	INDEPENDENCIA	6	490	ITA AZUL
407500	4	GUAIRA	7	INDEPENDENCIA	6	500	MAYOR KUE
407520	4	GUAIRA	7	INDEPENDENCIA	6	520	CERRO LEON
407530	4	GUAIRA	7	INDEPENDENCIA	6	530	MELGAREJO
407540	4	GUAIRA	7	INDEPENDENCIA	6	540	SAN BONIFACIO
407550	4	GUAIRA	7	INDEPENDENCIA	6	550	VIRGEN SERRANA
407560	4	GUAIRA	7	INDEPENDENCIA	6	560	NINO JESUS
407570	4	GUAIRA	7	INDEPENDENCIA	6	570	YROYSA CAPI'I
407580	4	GUAIRA	7	INDEPENDENCIA	6	580	COM INDIG VEGA KUE
408001	4	GUAIRA	8	ITAPE	1	1	DULCE NOMBRE DE JESUS
408002	4	GUAIRA	8	ITAPE	1	2	SAN ISIDRO LABRADOR
408003	4	GUAIRA	8	ITAPE	1	3	INMACULADA CONCEPCION
408004	4	GUAIRA	8	ITAPE	1	4	CORAZON DE JESUS
408100	4	GUAIRA	8	ITAPE	6	100	KA'AGUY GUASU
408110	4	GUAIRA	8	ITAPE	6	110	POTRERO SOSA
408120	4	GUAIRA	8	ITAPE	6	120	ISLA VEGA
408130	4	GUAIRA	8	ITAPE	6	130	CAROVENI NUEVO
408150	4	GUAIRA	8	ITAPE	6	150	ITAPE HUGUA
408160	4	GUAIRA	8	ITAPE	6	160	POTRERO REDUCCION
408170	4	GUAIRA	8	ITAPE	6	170	COSTA HU
408180	4	GUAIRA	8	ITAPE	6	180	CERRITO
408190	4	GUAIRA	8	ITAPE	6	190	CERRO GUY
408200	4	GUAIRA	8	ITAPE	6	200	POTRERO RAMIREZ
408210	4	GUAIRA	8	ITAPE	6	210	LOMA HOVY
408220	4	GUAIRA	8	ITAPE	6	220	CORAZON DE JESUS
408230	4	GUAIRA	8	ITAPE	6	230	CAROVENI VIEJO
409001	4	GUAIRA	9	ITURBE	1	1	SAN FRANCISCO
409002	4	GUAIRA	9	ITURBE	1	2	SAN JUAN
409003	4	GUAIRA	9	ITURBE	1	3	SAN LUIS
409004	4	GUAIRA	9	ITURBE	1	4	SAN ANTONIO
409005	4	GUAIRA	9	ITURBE	1	5	SAN ROQUE
409006	4	GUAIRA	9	ITURBE	1	6	FATIMA
409100	4	GUAIRA	9	ITURBE	6	100	CAPITAN BRIZUELA
409110	4	GUAIRA	9	ITURBE	6	110	ITACURUBI
409120	4	GUAIRA	9	ITURBE	6	120	CANDEA MI
409140	4	GUAIRA	9	ITURBE	6	140	CANDEA GUASU
409150	4	GUAIRA	9	ITURBE	6	150	CONCEPCION MI
409160	4	GUAIRA	9	ITURBE	6	160	KA'ATY MI
409170	4	GUAIRA	9	ITURBE	6	170	COSTA ALEGRE
409210	4	GUAIRA	9	ITURBE	6	210	POTRERO
409220	4	GUAIRA	9	ITURBE	6	220	SAN ROQUE
409230	4	GUAIRA	9	ITURBE	6	230	SAN IGNACIO
409240	4	GUAIRA	9	ITURBE	6	240	PASO JOVAI
409250	4	GUAIRA	9	ITURBE	6	250	YVYRA KAIGUE
410002	4	GUAIRA	10	JOSE FASSARDI	1	2	NINO JESUS
410003	4	GUAIRA	10	JOSE FASSARDI	1	3	FATIMA
410110	4	GUAIRA	10	JOSE FASSARDI	6	110	PIRECA
410120	4	GUAIRA	10	JOSE FASSARDI	6	120	COLONIA GUARANI
410160	4	GUAIRA	10	JOSE FASSARDI	6	160	SANTA CATALINA
410170	4	GUAIRA	10	JOSE FASSARDI	6	170	SAN JUAN
410180	4	GUAIRA	10	JOSE FASSARDI	6	180	SANTA TERESITA
410190	4	GUAIRA	10	JOSE FASSARDI	6	190	SAN ROQUE
410200	4	GUAIRA	10	JOSE FASSARDI	6	200	SAN MIGUEL
410210	4	GUAIRA	10	JOSE FASSARDI	6	210	SANTO DOMINGO
410220	4	GUAIRA	10	JOSE FASSARDI	6	220	SANTA ROSA
410230	4	GUAIRA	10	JOSE FASSARDI	6	230	SAN ANTONIO
410240	4	GUAIRA	10	JOSE FASSARDI	6	240	SAN PABLO
410250	4	GUAIRA	10	JOSE FASSARDI	6	250	SAN FRANCISCO
410260	4	GUAIRA	10	JOSE FASSARDI	6	260	SANTA ANA
410270	4	GUAIRA	10	JOSE FASSARDI	6	270	KAGUARE'I
410280	4	GUAIRA	10	JOSE FASSARDI	6	280	KAGUARE'I 14
410290	4	GUAIRA	10	JOSE FASSARDI	6	290	KAGUARE'I 15
410300	4	GUAIRA	10	JOSE FASSARDI	6	300	KAGUARE'I 16
410310	4	GUAIRA	10	JOSE FASSARDI	6	310	KAGUARE'I 17
410320	4	GUAIRA	10	JOSE FASSARDI	6	320	KAGUARE'I 18
410330	4	GUAIRA	10	JOSE FASSARDI	6	330	SAN ISIDRO
410340	4	GUAIRA	10	JOSE FASSARDI	6	340	SAN LUIS
410350	4	GUAIRA	10	JOSE FASSARDI	6	350	CRISTO REY
411001	4	GUAIRA	11	MBOCAYATY	1	1	VIRGEN DE LA ASUNCION
411002	4	GUAIRA	11	MBOCAYATY	1	2	VIRGEN DEL ROSARIO
411003	4	GUAIRA	11	MBOCAYATY	1	3	MARIA AUXILIADORA
411004	4	GUAIRA	11	MBOCAYATY	1	4	SANTA LIBRADA
411100	4	GUAIRA	11	MBOCAYATY	6	100	KARANDAYTY
411110	4	GUAIRA	11	MBOCAYATY	6	110	SANTA BARBARA
411120	4	GUAIRA	11	MBOCAYATY	6	120	LOMA BARRETO
411140	4	GUAIRA	11	MBOCAYATY	6	140	JORGE NAVILLE
411145	4	GUAIRA	11	MBOCAYATY	3	145	JORGE NAVILLE SUB-URBANO
411150	4	GUAIRA	11	MBOCAYATY	6	150	PIRITY
411160	4	GUAIRA	11	MBOCAYATY	6	160	CAPITAN JOSE SAMUDIO
411170	4	GUAIRA	11	MBOCAYATY	6	170	COSTA MBOKAJATY
411180	4	GUAIRA	11	MBOCAYATY	6	180	TACUARITA
411190	4	GUAIRA	11	MBOCAYATY	6	190	COSTA PISADERA
411200	4	GUAIRA	11	MBOCAYATY	6	200	ITATI
411210	4	GUAIRA	11	MBOCAYATY	6	210	MANUEL GONDRA
411220	4	GUAIRA	11	MBOCAYATY	6	220	ITAKYRY
411230	4	GUAIRA	11	MBOCAYATY	6	230	NARANJO
412001	4	GUAIRA	12	NATALICIO TALAVERA	1	1	IGLESIA
412002	4	GUAIRA	12	NATALICIO TALAVERA	1	2	VIRGEN DEL ROSARIO
412003	4	GUAIRA	12	NATALICIO TALAVERA	1	3	ESCUELA
412004	4	GUAIRA	12	NATALICIO TALAVERA	1	4	SAN PEDRO
412005	4	GUAIRA	12	NATALICIO TALAVERA	1	5	SAN MIGUEL
412006	4	GUAIRA	12	NATALICIO TALAVERA	1	6	SANTA ROSA
412100	4	GUAIRA	12	NATALICIO TALAVERA	6	100	ISLA 1
412110	4	GUAIRA	12	NATALICIO TALAVERA	6	110	ARROYO COSTA
412120	4	GUAIRA	12	NATALICIO TALAVERA	6	120	SAN ISIDRO
412130	4	GUAIRA	12	NATALICIO TALAVERA	6	130	PRIMERA LINEA
412140	4	GUAIRA	12	NATALICIO TALAVERA	6	140	SEGUNDA LINEA
412160	4	GUAIRA	12	NATALICIO TALAVERA	6	160	PANETEY
412180	4	GUAIRA	12	NATALICIO TALAVERA	6	180	PASO ITA
412190	4	GUAIRA	12	NATALICIO TALAVERA	6	190	ZANJA PYTA
412200	4	GUAIRA	12	NATALICIO TALAVERA	6	200	POTRERITO
412230	4	GUAIRA	12	NATALICIO TALAVERA	6	230	APERE'A TY
412240	4	GUAIRA	12	NATALICIO TALAVERA	6	240	SAN MIGUEL
412250	4	GUAIRA	12	NATALICIO TALAVERA	6	250	ISLA 2
413001	4	GUAIRA	13	NUMI	1	1	ARROYITO
413002	4	GUAIRA	13	NUMI	1	2	CENTRAL
413004	4	GUAIRA	13	NUMI	1	4	CRUZ DEL SUR
413005	4	GUAIRA	13	NUMI	1	5	SAN FRANCISCO
413120	4	GUAIRA	13	NUMI	6	120	SANTA ELENA
413140	4	GUAIRA	13	NUMI	6	140	SAN LUIS
413150	4	GUAIRA	13	NUMI	6	150	CERRO CORA
413170	4	GUAIRA	13	NUMI	6	170	SANTA ROSA
413180	4	GUAIRA	13	NUMI	6	180	PERULERO
413190	4	GUAIRA	13	NUMI	6	190	SAN AGUSTIN
413200	4	GUAIRA	13	NUMI	6	200	MBARIGUI
414002	4	GUAIRA	14	SAN SALVADOR	1	2	FATIMA
414003	4	GUAIRA	14	SAN SALVADOR	1	3	SAN MIGUEL
414004	4	GUAIRA	14	SAN SALVADOR	1	4	SAN FRANCISCO
414005	4	GUAIRA	14	SAN SALVADOR	1	5	NTRA. SRA. DE LA ASUNCION
414006	4	GUAIRA	14	SAN SALVADOR	1	6	SAN ANTONIO
414007	4	GUAIRA	14	SAN SALVADOR	1	7	SAN CAYETANO
414008	4	GUAIRA	14	SAN SALVADOR	1	8	SAN JOSE
414009	4	GUAIRA	14	SAN SALVADOR	1	9	SAN ROQUE
414110	4	GUAIRA	14	SAN SALVADOR	6	110	YHAKA MI
414120	4	GUAIRA	14	SAN SALVADOR	6	120	FATIMA
414150	4	GUAIRA	14	SAN SALVADOR	6	150	ITACURUBI
414160	4	GUAIRA	14	SAN SALVADOR	6	160	JUKERI
414170	4	GUAIRA	14	SAN SALVADOR	6	170	ISLA VALLE
414200	4	GUAIRA	14	SAN SALVADOR	6	200	TRANQUERA SAN RAFAEL
414210	4	GUAIRA	14	SAN SALVADOR	6	210	VIRGEN DE CAACUPE
414220	4	GUAIRA	14	SAN SALVADOR	6	220	POTRERO ROBLEDO
414230	4	GUAIRA	14	SAN SALVADOR	6	230	VOLCAN KUE
415001	4	GUAIRA	15	YATAITY	1	1	TAJAMAR
415002	4	GUAIRA	15	YATAITY	1	2	ALEGRE
415005	4	GUAIRA	15	YATAITY	1	5	COSMOS
415006	4	GUAIRA	15	YATAITY	1	6	14 DE SETIEMBRE
415100	4	GUAIRA	15	YATAITY	6	100	POTRERO BENEGAS
415105	4	GUAIRA	15	YATAITY	6	105	ASENT. SAN BLAS - POTRERO BENEGAS
415110	4	GUAIRA	15	YATAITY	6	110	TEBICUARY COSTA
415120	4	GUAIRA	15	YATAITY	6	120	PUESTO KA'AGUY
415140	4	GUAIRA	15	YATAITY	6	140	TAJAMAR
415150	4	GUAIRA	15	YATAITY	6	150	CAPILLITA
415160	4	GUAIRA	15	YATAITY	6	160	VALLE PYTA
415170	4	GUAIRA	15	YATAITY	6	170	SANTA RITA
415180	4	GUAIRA	15	YATAITY	6	180	LOMA BARRETO
415190	4	GUAIRA	15	YATAITY	6	190	13 DE JUNIO
415200	4	GUAIRA	15	YATAITY	6	200	KA'AGUY KUPE
415210	4	GUAIRA	15	YATAITY	6	210	CAPILLA KUE
416001	4	GUAIRA	16	DOCTOR BOTTRELL	1	1	CENTRO
416130	4	GUAIRA	16	DOCTOR BOTTRELL	6	130	TAKUAPITY
416140	4	GUAIRA	16	DOCTOR BOTTRELL	6	140	ITACURUBI
416150	4	GUAIRA	16	DOCTOR BOTTRELL	6	150	CALLE 40
416160	4	GUAIRA	16	DOCTOR BOTTRELL	6	160	ZONA DE ESTANCIAS DR. BOTTRELL
416170	4	GUAIRA	16	DOCTOR BOTTRELL	6	170	13 DE JUNIO
416180	4	GUAIRA	16	DOCTOR BOTTRELL	6	180	SANTA LIBRADA
416190	4	GUAIRA	16	DOCTOR BOTTRELL	6	190	NUMI - VIRGEN DE FATIMA
416200	4	GUAIRA	16	DOCTOR BOTTRELL	6	200	NUMI - SAN JOSE
416210	4	GUAIRA	16	DOCTOR BOTTRELL	6	210	ALEGRE
417001	4	GUAIRA	17	PASO YOBAI	1	1	8 DE DICIEMBRE
417002	4	GUAIRA	17	PASO YOBAI	1	2	CENTRO
417003	4	GUAIRA	17	PASO YOBAI	1	3	SAN COSME
417100	4	GUAIRA	17	PASO YOBAI	6	100	SAN ISIDRO
417110	4	GUAIRA	17	PASO YOBAI	6	110	PLANCHADA
417115	4	GUAIRA	17	PASO YOBAI	3	115	PLANCHADA SUB URBANO
417120	4	GUAIRA	17	PASO YOBAI	6	120	ARROYO MOROTI
417130	4	GUAIRA	17	PASO YOBAI	6	130	COLONIA NANSSEN
417140	4	GUAIRA	17	PASO YOBAI	6	140	SAN JOSE
417150	4	GUAIRA	17	PASO YOBAI	6	150	3 DE NOVIEMBRE
417160	4	GUAIRA	17	PASO YOBAI	6	160	COLONIA BERGTHAL
417170	4	GUAIRA	17	PASO YOBAI	3	170	SAN AGUSTIN SUB-URBANO
417180	4	GUAIRA	17	PASO YOBAI	6	180	CIERVO KUA
417210	4	GUAIRA	17	PASO YOBAI	6	210	PALMETA
417220	4	GUAIRA	17	PASO YOBAI	6	220	SAN MARCOS
417225	4	GUAIRA	17	PASO YOBAI	3	225	SAN MARCOS SUB URBANO
417230	4	GUAIRA	17	PASO YOBAI	6	230	PIKYRY
417240	4	GUAIRA	17	PASO YOBAI	6	240	MANGRULLO
417245	4	GUAIRA	17	PASO YOBAI	3	245	MANGRULLO SUB - URBANO
417280	4	GUAIRA	17	PASO YOBAI	6	280	ZANJA PYTA
417285	4	GUAIRA	17	PASO YOBAI	6	285	ASENT. ZANJA PYTA
417290	4	GUAIRA	17	PASO YOBAI	6	290	SANTA MARIA
417300	4	GUAIRA	17	PASO YOBAI	6	300	ASENT. 8 DE DICIEMBRE
417310	4	GUAIRA	17	PASO YOBAI	6	310	SAN ANTONIO
417320	4	GUAIRA	17	PASO YOBAI	6	320	TAJY'I
417330	4	GUAIRA	17	PASO YOBAI	6	330	TORRES KUE
417340	4	GUAIRA	17	PASO YOBAI	6	340	3 MOJONES
417350	4	GUAIRA	17	PASO YOBAI	6	350	COLONIAS UNIDAS
417360	4	GUAIRA	17	PASO YOBAI	6	360	CORONEL CUBAS
417370	4	GUAIRA	17	PASO YOBAI	6	370	COLONIA SUDETIA
417380	4	GUAIRA	17	PASO YOBAI	6	380	YCUA PORA
417390	4	GUAIRA	17	PASO YOBAI	6	390	RINCON ALEGRE
417400	4	GUAIRA	17	PASO YOBAI	6	400	NUEVA GUAIRA
417410	4	GUAIRA	17	PASO YOBAI	6	410	COM INDIG ARROYO HU
417420	4	GUAIRA	17	PASO YOBAI	6	420	COM INDIG YRYVU KUA  NARANJITO
417430	4	GUAIRA	17	PASO YOBAI	6	430	NARANJITO
417440	4	GUAIRA	17	PASO YOBAI	6	440	ASENT. ONONDIVEPA
417450	4	GUAIRA	17	PASO YOBAI	6	450	COM INDIG SANTA TERESITA
417460	4	GUAIRA	17	PASO YOBAI	6	460	EL TRIUNFO
417470	4	GUAIRA	17	PASO YOBAI	6	470	KAPI'I
417480	4	GUAIRA	17	PASO YOBAI	6	480	ASENT. KAPI'I
417490	4	GUAIRA	17	PASO YOBAI	6	490	KAVAJU RY
417500	4	GUAIRA	17	PASO YOBAI	6	500	LA VICTORIA
417510	4	GUAIRA	17	PASO YOBAI	6	510	ASENT. SILVERA CUE
417520	4	GUAIRA	17	PASO YOBAI	6	520	ASENT. KAVAJU RY
417530	4	GUAIRA	17	PASO YOBAI	6	530	COSTA ALEGRE
417540	4	GUAIRA	17	PASO YOBAI	6	540	ASENT. COSTA ALEGRE
417550	4	GUAIRA	17	PASO YOBAI	6	550	SAN FRANCISCO I
417560	4	GUAIRA	17	PASO YOBAI	6	560	2DA LINEA 3 DE NOVIEMBRE
417570	4	GUAIRA	17	PASO YOBAI	6	570	KURUSU
417575	4	GUAIRA	17	PASO YOBAI	3	575	KURUSU SUB - URBANO
417580	4	GUAIRA	17	PASO YOBAI	6	580	KURUPA'Y
417590	4	GUAIRA	17	PASO YOBAI	6	590	COM INDIG OVENIA SAN FRANCISCO
417600	4	GUAIRA	17	PASO YOBAI	6	600	COM INDIG ISLA HU
417610	4	GUAIRA	17	PASO YOBAI	6	610	COLONIA NAVIDAD
417620	4	GUAIRA	17	PASO YOBAI	6	620	SAN FRANCISCO II
417630	4	GUAIRA	17	PASO YOBAI	6	630	COM INDIG 11 DE DICIEMBRE
417640	4	GUAIRA	17	PASO YOBAI	6	640	COM INDIG NANSSEN
417650	4	GUAIRA	17	PASO YOBAI	6	650	ASENT. NUEVA ALBORADA
417660	4	GUAIRA	17	PASO YOBAI	6	660	COM INDIG VEGA KUE
417670	4	GUAIRA	17	PASO YOBAI	6	670	NU VERA
417680	4	GUAIRA	17	PASO YOBAI	6	680	ASENT. TORRES KUE
417690	4	GUAIRA	17	PASO YOBAI	6	690	14 LINEA
417700	4	GUAIRA	17	PASO YOBAI	6	700	ALBORADA
417710	4	GUAIRA	17	PASO YOBAI	6	710	SAN ROQUE
418001	4	GUAIRA	18	TEBICUARY	1	1	ZONA INDUSTRIAL
418002	4	GUAIRA	18	TEBICUARY	1	2	CENTRO
418003	4	GUAIRA	18	TEBICUARY	1	3	SANTA LUCIA
418004	4	GUAIRA	18	TEBICUARY	1	4	SANTA CECILIA
418100	4	GUAIRA	18	TEBICUARY	6	100	CHACRA NORTE
418110	4	GUAIRA	18	TEBICUARY	6	110	CHACRA SUR
418120	4	GUAIRA	18	TEBICUARY	6	120	LOMA PINDO
501001	5	CAAGUAZU	1	CORONEL OVIEDO	1	1	CERRITO RUGUA
501002	5	CAAGUAZU	1	CORONEL OVIEDO	1	2	PRIMERO DE MARZO
501003	5	CAAGUAZU	1	CORONEL OVIEDO	1	3	SAN MIGUEL
501004	5	CAAGUAZU	1	CORONEL OVIEDO	1	4	12 DE JUNIO
501005	5	CAAGUAZU	1	CORONEL OVIEDO	1	5	CENTRO
501006	5	CAAGUAZU	1	CORONEL OVIEDO	1	6	CAPITAN ROA
501007	5	CAAGUAZU	1	CORONEL OVIEDO	1	7	COSTA ALEGRE
501008	5	CAAGUAZU	1	CORONEL OVIEDO	1	8	AZUCENA
501009	5	CAAGUAZU	1	CORONEL OVIEDO	1	9	GENERAL BERNARDINO CABALLERO
501010	5	CAAGUAZU	1	CORONEL OVIEDO	1	10	BOQUERON
501011	5	CAAGUAZU	1	CORONEL OVIEDO	1	11	JOSE ALFONSO GODOY
501012	5	CAAGUAZU	1	CORONEL OVIEDO	1	12	SAN ISIDRO
501013	5	CAAGUAZU	1	CORONEL OVIEDO	1	13	ASENT. SANTA LUCIA
501014	5	CAAGUAZU	1	CORONEL OVIEDO	1	14	POTRERITO - SAN ROQUE
501015	5	CAAGUAZU	1	CORONEL OVIEDO	1	15	PRIMERO DE MAYO
501016	5	CAAGUAZU	1	CORONEL OVIEDO	1	16	ASENT. 3 DE NOVIEMBRE
501017	5	CAAGUAZU	1	CORONEL OVIEDO	1	17	ASENT. LA GLORIA
501018	5	CAAGUAZU	1	CORONEL OVIEDO	1	18	ASENT. SOL NACIENTE
501019	5	CAAGUAZU	1	CORONEL OVIEDO	1	19	ASENT. LA GLORIA I
501020	5	CAAGUAZU	1	CORONEL OVIEDO	1	20	ASENT. BONANZA
501021	5	CAAGUAZU	1	CORONEL OVIEDO	1	21	BALCON OVETENSE
501022	5	CAAGUAZU	1	CORONEL OVIEDO	1	22	ASENT. 6 DE ENERO
501023	5	CAAGUAZU	1	CORONEL OVIEDO	1	23	VILLA MOREIRA
501024	5	CAAGUAZU	1	CORONEL OVIEDO	1	24	ASENT. VIRGEN DEL CARMEN
501025	5	CAAGUAZU	1	CORONEL OVIEDO	1	25	VILLA DEL MAESTRO
501026	5	CAAGUAZU	1	CORONEL OVIEDO	1	26	ASENT. SIEMPRE VERDE II
501027	5	CAAGUAZU	1	CORONEL OVIEDO	1	27	VILLA AZUCENA
501028	5	CAAGUAZU	1	CORONEL OVIEDO	1	28	ASENT. SIEMPRE VERDE I
501029	5	CAAGUAZU	1	CORONEL OVIEDO	1	29	NINO JESUS
501030	5	CAAGUAZU	1	CORONEL OVIEDO	1	30	LA VICTORIA
501031	5	CAAGUAZU	1	CORONEL OVIEDO	1	31	MARISTAS II
501032	5	CAAGUAZU	1	CORONEL OVIEDO	1	32	ASENT. PARAISO
501033	5	CAAGUAZU	1	CORONEL OVIEDO	1	33	EL PARAISO
501034	5	CAAGUAZU	1	CORONEL OVIEDO	1	34	CALLE HOVY
501035	5	CAAGUAZU	1	CORONEL OVIEDO	1	35	LA FLORESTA
501036	5	CAAGUAZU	1	CORONEL OVIEDO	1	36	MARISTAS I
501037	5	CAAGUAZU	1	CORONEL OVIEDO	1	37	AMANECER
501038	5	CAAGUAZU	1	CORONEL OVIEDO	1	38	CORONEL POTY
501039	5	CAAGUAZU	1	CORONEL OVIEDO	1	39	FRACCION CIUDADELA
501040	5	CAAGUAZU	1	CORONEL OVIEDO	1	40	FRACCION MANDYJU
501041	5	CAAGUAZU	1	CORONEL OVIEDO	1	41	REGINA
501042	5	CAAGUAZU	1	CORONEL OVIEDO	1	42	SAN RAFAEL
501043	5	CAAGUAZU	1	CORONEL OVIEDO	1	43	SAN LUIS
501044	5	CAAGUAZU	1	CORONEL OVIEDO	1	44	JUAN LATIN
501045	5	CAAGUAZU	1	CORONEL OVIEDO	1	45	UNIVERSITARIO
501046	5	CAAGUAZU	1	CORONEL OVIEDO	1	46	SAGRADO CORAZON DE JESUS
501047	5	CAAGUAZU	1	CORONEL OVIEDO	1	47	ESPERANZA
501048	5	CAAGUAZU	1	CORONEL OVIEDO	1	48	SAN ROQUE
501049	5	CAAGUAZU	1	CORONEL OVIEDO	1	49	KAAGUY RORY
501050	5	CAAGUAZU	1	CORONEL OVIEDO	1	50	ASENT. LAS MERCEDES 1
501051	5	CAAGUAZU	1	CORONEL OVIEDO	1	51	ASENT. LAS MERCEDES 2
501052	5	CAAGUAZU	1	CORONEL OVIEDO	1	52	ASENT. 31 DE JULIO
501100	5	CAAGUAZU	1	CORONEL OVIEDO	6	100	JUKYTY
501110	5	CAAGUAZU	1	CORONEL OVIEDO	6	110	CHASE KUE
501120	5	CAAGUAZU	1	CORONEL OVIEDO	6	120	CHIRKATY
501130	5	CAAGUAZU	1	CORONEL OVIEDO	6	130	GENARO ROMERO
501150	5	CAAGUAZU	1	CORONEL OVIEDO	6	150	PIQUETE KUE
501160	5	CAAGUAZU	1	CORONEL OVIEDO	6	160	CALLE TAKURUTY
501170	5	CAAGUAZU	1	CORONEL OVIEDO	6	170	CALLE BORDENAVE
501180	5	CAAGUAZU	1	CORONEL OVIEDO	6	180	VOLCAN KUE
501190	5	CAAGUAZU	1	CORONEL OVIEDO	6	190	COSTA VARELA
501200	5	CAAGUAZU	1	CORONEL OVIEDO	6	200	KARANDAYTY
501210	5	CAAGUAZU	1	CORONEL OVIEDO	6	210	LEIVA'I
501220	5	CAAGUAZU	1	CORONEL OVIEDO	6	220	CALLE ARENA
501240	5	CAAGUAZU	1	CORONEL OVIEDO	6	240	NU RUGUA
501250	5	CAAGUAZU	1	CORONEL OVIEDO	6	250	PLACIDO
501270	5	CAAGUAZU	1	CORONEL OVIEDO	6	270	CALLE CANGAL
501280	5	CAAGUAZU	1	CORONEL OVIEDO	6	280	CALLE ITACURUBI
501290	5	CAAGUAZU	1	CORONEL OVIEDO	6	290	OLEGARIO
501300	5	CAAGUAZU	1	CORONEL OVIEDO	6	300	CALLE ARROZ
501310	5	CAAGUAZU	1	CORONEL OVIEDO	6	310	SANTA LIBRADA
501320	5	CAAGUAZU	1	CORONEL OVIEDO	6	320	POTRERITO
501330	5	CAAGUAZU	1	CORONEL OVIEDO	6	330	AGUAPEY
501340	5	CAAGUAZU	1	CORONEL OVIEDO	6	340	POTRERO SAN ROQUE
501360	5	CAAGUAZU	1	CORONEL OVIEDO	6	360	CALLE SANTO DOMINGO
501370	5	CAAGUAZU	1	CORONEL OVIEDO	6	370	COLONIA MONTANARO
501380	5	CAAGUAZU	1	CORONEL OVIEDO	6	380	CALLE SAN ISIDRO
501390	5	CAAGUAZU	1	CORONEL OVIEDO	6	390	SAN ANTONIO
501400	5	CAAGUAZU	1	CORONEL OVIEDO	6	400	CALLE SAN ROQUE
501410	5	CAAGUAZU	1	CORONEL OVIEDO	6	410	PINDOTY
501440	5	CAAGUAZU	1	CORONEL OVIEDO	6	440	KAITA
501450	5	CAAGUAZU	1	CORONEL OVIEDO	6	450	SAN LUIS
501460	5	CAAGUAZU	1	CORONEL OVIEDO	6	460	CALLE MOREIRA
501470	5	CAAGUAZU	1	CORONEL OVIEDO	6	470	CALLE SAN FRANCISCO
501480	5	CAAGUAZU	1	CORONEL OVIEDO	6	480	CALLE DON BOSCO
501510	5	CAAGUAZU	1	CORONEL OVIEDO	6	510	CALLE HOVY
501520	5	CAAGUAZU	1	CORONEL OVIEDO	6	520	SANTA MARIA
501530	5	CAAGUAZU	1	CORONEL OVIEDO	6	530	POTRERO KUE
501550	5	CAAGUAZU	1	CORONEL OVIEDO	6	550	ESPINILLO
501560	5	CAAGUAZU	1	CORONEL OVIEDO	6	560	COSTA SAN ANTONIO
501580	5	CAAGUAZU	1	CORONEL OVIEDO	6	580	CALLE SAN PEDRO
501590	5	CAAGUAZU	1	CORONEL OVIEDO	6	590	HUGUA GUASU
501600	5	CAAGUAZU	1	CORONEL OVIEDO	6	600	TUJU PUKU PUNTA
501610	5	CAAGUAZU	1	CORONEL OVIEDO	6	610	KA'AGUY KUPE
501620	5	CAAGUAZU	1	CORONEL OVIEDO	6	620	TUJU PUKU
501630	5	CAAGUAZU	1	CORONEL OVIEDO	6	630	CARAGUATAY MI
501640	5	CAAGUAZU	1	CORONEL OVIEDO	3	640	BLAS GARAY -SUB URBANO
501650	5	CAAGUAZU	1	CORONEL OVIEDO	6	650	ZARO CARO
501660	5	CAAGUAZU	1	CORONEL OVIEDO	6	660	BLAS GARAY I
501680	5	CAAGUAZU	1	CORONEL OVIEDO	6	680	BLAS GARAY II
501690	5	CAAGUAZU	1	CORONEL OVIEDO	6	690	AGUAPETY
501700	5	CAAGUAZU	1	CORONEL OVIEDO	6	700	ISLA PA'U
501710	5	CAAGUAZU	1	CORONEL OVIEDO	6	710	POTRERO OCULTO
501720	5	CAAGUAZU	1	CORONEL OVIEDO	6	720	JOSE ALFONSO GODOY
501730	5	CAAGUAZU	1	CORONEL OVIEDO	3	730	NUEVO HORIZONTE- SUB URBANO
501740	5	CAAGUAZU	1	CORONEL OVIEDO	6	740	CIUDAD NUEVA
501750	5	CAAGUAZU	1	CORONEL OVIEDO	6	750	LA VICTORIA
501760	5	CAAGUAZU	1	CORONEL OVIEDO	6	760	ARROYO MOROTI
501770	5	CAAGUAZU	1	CORONEL OVIEDO	6	770	CALLE UNO
501780	5	CAAGUAZU	1	CORONEL OVIEDO	6	780	VILLA SAN FRANCISCO
501790	5	CAAGUAZU	1	CORONEL OVIEDO	6	790	ASENT. FORTUNA II
501800	5	CAAGUAZU	1	CORONEL OVIEDO	6	800	ABUELITA
501810	5	CAAGUAZU	1	CORONEL OVIEDO	6	810	CALLE GIMENEZ
501820	5	CAAGUAZU	1	CORONEL OVIEDO	6	820	POZO AZUL
501830	5	CAAGUAZU	1	CORONEL OVIEDO	6	830	OVANDO
501840	5	CAAGUAZU	1	CORONEL OVIEDO	6	840	GENERAL DIAZ
501850	5	CAAGUAZU	1	CORONEL OVIEDO	6	850	ASENT. SANTA ELENA
501860	5	CAAGUAZU	1	CORONEL OVIEDO	6	860	KAYGUA KOKUE
501870	5	CAAGUAZU	1	CORONEL OVIEDO	6	870	CALLE DUARTE
501880	5	CAAGUAZU	1	CORONEL OVIEDO	6	880	CALLE SEGUNDA
501890	5	CAAGUAZU	1	CORONEL OVIEDO	6	890	YKUA PORA
501900	5	CAAGUAZU	1	CORONEL OVIEDO	6	900	POTRERO UBALDINA
501910	5	CAAGUAZU	1	CORONEL OVIEDO	6	910	COM INDIG ARROYO GUASU SAN ISIDRO
501920	5	CAAGUAZU	1	CORONEL OVIEDO	6	920	COM INDIG ESPINILLO
501930	5	CAAGUAZU	1	CORONEL OVIEDO	3	930	ESPINILLO -SUB-URBANO
501940	5	CAAGUAZU	1	CORONEL OVIEDO	3	940	AGUAPETY - SUB URBANO
501950	5	CAAGUAZU	1	CORONEL OVIEDO	3	950	CHIRKATY - SUB-URBANO
501960	5	CAAGUAZU	1	CORONEL OVIEDO	6	960	POTRERO CERCADO
501970	5	CAAGUAZU	1	CORONEL OVIEDO	6	970	JUKYTY DON BOSCO
501980	5	CAAGUAZU	1	CORONEL OVIEDO	6	980	CURUCAU
501990	5	CAAGUAZU	1	CORONEL OVIEDO	6	990	ACOSTA NU
502001	5	CAAGUAZU	2	CAAGUAZU	1	1	IPVU
502002	5	CAAGUAZU	2	CAAGUAZU	1	2	SANTA ISABEL
502003	5	CAAGUAZU	2	CAAGUAZU	1	3	VILLA MARGARITA
502004	5	CAAGUAZU	2	CAAGUAZU	1	4	TAKURU
502005	5	CAAGUAZU	2	CAAGUAZU	1	5	TORO BLANCO
502006	5	CAAGUAZU	2	CAAGUAZU	1	6	CENTENARIO
502007	5	CAAGUAZU	2	CAAGUAZU	1	7	CENTRO
502008	5	CAAGUAZU	2	CAAGUAZU	1	8	SAN LORENZO
502009	5	CAAGUAZU	2	CAAGUAZU	1	9	EMPALADO
502010	5	CAAGUAZU	2	CAAGUAZU	1	10	INMACULADA CONCEPCION
502011	5	CAAGUAZU	2	CAAGUAZU	1	11	SAN ROQUE
502012	5	CAAGUAZU	2	CAAGUAZU	1	12	FLORIDA
502013	5	CAAGUAZU	2	CAAGUAZU	1	13	GENERAL BERNARDINO CABALLERO
502014	5	CAAGUAZU	2	CAAGUAZU	1	14	SAN RAFAEL
502015	5	CAAGUAZU	2	CAAGUAZU	1	15	TAKURU - FRACCION DON TOMAS
502016	5	CAAGUAZU	2	CAAGUAZU	1	16	TAKURU SAN MIGUEL
502017	5	CAAGUAZU	2	CAAGUAZU	1	17	VILLA INDUSTRIAL
502018	5	CAAGUAZU	2	CAAGUAZU	1	18	ASENT. TORO BLANCO
502019	5	CAAGUAZU	2	CAAGUAZU	1	19	ASENT. SAN PEDRO
502020	5	CAAGUAZU	2	CAAGUAZU	1	20	EMPALADO MI
502021	5	CAAGUAZU	2	CAAGUAZU	1	21	SAN LUIS 1
502022	5	CAAGUAZU	2	CAAGUAZU	1	22	NARANJAL
502023	5	CAAGUAZU	2	CAAGUAZU	1	23	COSTA ROSADO
502024	5	CAAGUAZU	2	CAAGUAZU	1	24	ASENT. MBOKAJATY
502025	5	CAAGUAZU	2	CAAGUAZU	1	25	ASENT. VILLA TRIUNFO
502026	5	CAAGUAZU	2	CAAGUAZU	1	26	VILLA TRIUNFO
502027	5	CAAGUAZU	2	CAAGUAZU	1	27	SAN LUIS 2
502028	5	CAAGUAZU	2	CAAGUAZU	1	28	ARROYO HU
502029	5	CAAGUAZU	2	CAAGUAZU	1	29	ASENT. CONSTITUCION
502030	5	CAAGUAZU	2	CAAGUAZU	1	30	VILLA CONSTITUCION
502031	5	CAAGUAZU	2	CAAGUAZU	1	31	FRACCION YVYRATY
502032	5	CAAGUAZU	2	CAAGUAZU	1	32	ASENT. SAN FERNANDO - WALTER INSFRAN
502033	5	CAAGUAZU	2	CAAGUAZU	1	33	ASENT. FAMILIA UNIDA
502034	5	CAAGUAZU	2	CAAGUAZU	1	34	ASENT. NUEVA ESPERANZA
502035	5	CAAGUAZU	2	CAAGUAZU	1	35	WALTER INSFRAN
502036	5	CAAGUAZU	2	CAAGUAZU	1	36	PARAGUAY PYAHU
502037	5	CAAGUAZU	2	CAAGUAZU	1	37	SAN RAMON
502038	5	CAAGUAZU	2	CAAGUAZU	1	38	ASENT. EL OLIVO
502039	5	CAAGUAZU	2	CAAGUAZU	1	39	ASENT. SANTA ISABEL
502040	5	CAAGUAZU	2	CAAGUAZU	1	40	MBOKAJATY
502041	5	CAAGUAZU	2	CAAGUAZU	1	41	SAN MIGUEL
502042	5	CAAGUAZU	2	CAAGUAZU	1	42	ASENT. VILLA JARDIN
502043	5	CAAGUAZU	2	CAAGUAZU	1	43	ASENT. SAN MIGUEL
502044	5	CAAGUAZU	2	CAAGUAZU	1	44	ASENT. VILLA SAN JUAN
502100	5	CAAGUAZU	2	CAAGUAZU	6	100	JUKYRY CENTRAL
502110	5	CAAGUAZU	2	CAAGUAZU	6	110	GUAJAKI KUA
502111	5	CAAGUAZU	2	CAAGUAZU	6	111	COM INDIG KA'AGUY PA'U
502112	5	CAAGUAZU	2	CAAGUAZU	6	112	COM INDIG SAN MARTIN
502113	5	CAAGUAZU	2	CAAGUAZU	6	113	COM INDIG ESCALERA
502114	5	CAAGUAZU	2	CAAGUAZU	6	114	ASENT. KERA YBOTY
502115	5	CAAGUAZU	2	CAAGUAZU	6	115	MBURURU
502116	5	CAAGUAZU	2	CAAGUAZU	6	116	COM INDIG YKUA PORA
502120	5	CAAGUAZU	2	CAAGUAZU	6	120	YPYTA
502130	5	CAAGUAZU	2	CAAGUAZU	6	130	BAEZ NU
502140	5	CAAGUAZU	2	CAAGUAZU	6	140	JAKARE I
502150	5	CAAGUAZU	2	CAAGUAZU	6	150	GUYRAUNGUA'I
502155	5	CAAGUAZU	2	CAAGUAZU	6	155	ASENT. 4 DE ENERO
502160	5	CAAGUAZU	2	CAAGUAZU	6	160	TAYAO
502170	5	CAAGUAZU	2	CAAGUAZU	6	170	6TA LINEA AGUA
502180	5	CAAGUAZU	2	CAAGUAZU	6	180	5TA LINEA AGUA
502190	5	CAAGUAZU	2	CAAGUAZU	6	190	TERCERA LINEA AGUA
502200	5	CAAGUAZU	2	CAAGUAZU	6	200	MBOKAJA'I
502210	5	CAAGUAZU	2	CAAGUAZU	6	210	4TA LINEA AGUA
502220	5	CAAGUAZU	2	CAAGUAZU	6	220	JOYVY
502221	5	CAAGUAZU	2	CAAGUAZU	6	221	COM INDIG JOYVY ISLA SAKA
502222	5	CAAGUAZU	2	CAAGUAZU	6	222	COM INDIG SAN JORGE
502223	5	CAAGUAZU	2	CAAGUAZU	6	223	COM INDIG 20 DE JULIO
502224	5	CAAGUAZU	2	CAAGUAZU	6	224	COM INDIG GUAVIRAMINDY
502230	5	CAAGUAZU	2	CAAGUAZU	6	230	7MA LINEA GUAIRA
502240	5	CAAGUAZU	2	CAAGUAZU	6	240	VY'APA GUASU
502250	5	CAAGUAZU	2	CAAGUAZU	6	250	TAKURU
502255	5	CAAGUAZU	2	CAAGUAZU	6	255	ASENT. TAKURU SAN MIGUEL
502260	5	CAAGUAZU	2	CAAGUAZU	6	260	VILLA SAN JUAN
502270	5	CAAGUAZU	2	CAAGUAZU	6	270	INFANTIL
502280	5	CAAGUAZU	2	CAAGUAZU	6	280	2DA LINEA GUAIRA
502290	5	CAAGUAZU	2	CAAGUAZU	6	290	FINANGRAY
502295	5	CAAGUAZU	2	CAAGUAZU	6	295	JUANGUI
502300	5	CAAGUAZU	2	CAAGUAZU	6	300	1RA LINEA GUAIRA
502310	5	CAAGUAZU	2	CAAGUAZU	6	310	SEGUNDA LINEA AGUA
502320	5	CAAGUAZU	2	CAAGUAZU	6	320	YTU
502330	5	CAAGUAZU	2	CAAGUAZU	6	330	1RO DE MAYO
502340	5	CAAGUAZU	2	CAAGUAZU	6	340	SAN MIGUEL
502350	5	CAAGUAZU	2	CAAGUAZU	6	350	ARROYO TERERE
502360	5	CAAGUAZU	2	CAAGUAZU	6	360	CAPITAN KUE
502370	5	CAAGUAZU	2	CAAGUAZU	6	370	ARROYO MOROTI
502380	5	CAAGUAZU	2	CAAGUAZU	6	380	CALLE PALMA
502390	5	CAAGUAZU	2	CAAGUAZU	6	390	GUAJAKI
502400	5	CAAGUAZU	2	CAAGUAZU	6	400	TORO BLANCO
502420	5	CAAGUAZU	2	CAAGUAZU	6	420	WALTER INSFRAN
502425	5	CAAGUAZU	2	CAAGUAZU	6	425	COM INDIG MANDU'A RA
502430	5	CAAGUAZU	2	CAAGUAZU	6	430	POTRERO GUAJAKI
502435	5	CAAGUAZU	2	CAAGUAZU	3	435	POTRERO GUAJAKI SUB URBANO
502440	5	CAAGUAZU	2	CAAGUAZU	6	440	JURUMI
502445	5	CAAGUAZU	2	CAAGUAZU	6	445	ASENT. JURUMI
502450	5	CAAGUAZU	2	CAAGUAZU	6	450	EMPALADO MI
502460	5	CAAGUAZU	2	CAAGUAZU	6	460	ARROYO GUASU
502465	5	CAAGUAZU	2	CAAGUAZU	6	465	COM INDIG ARROYO GUAZU SAN ISIDRO
502470	5	CAAGUAZU	2	CAAGUAZU	6	470	PARAJE GUASU
502490	5	CAAGUAZU	2	CAAGUAZU	6	490	LA FABRIL
502495	5	CAAGUAZU	2	CAAGUAZU	6	495	SAN  ISIDRO I
502500	5	CAAGUAZU	2	CAAGUAZU	6	500	CALLE POTRERO GUAJAKI
502520	5	CAAGUAZU	2	CAAGUAZU	6	520	8 DE DICIEMBRE
502530	5	CAAGUAZU	2	CAAGUAZU	6	530	CANTERA BOCA
502540	5	CAAGUAZU	2	CAAGUAZU	6	540	2DA LINEA BALANZA
502545	5	CAAGUAZU	2	CAAGUAZU	6	545	SANTA CATALINA
502560	5	CAAGUAZU	2	CAAGUAZU	6	560	BLAS GARAY
502570	5	CAAGUAZU	2	CAAGUAZU	6	570	POTRERO BOCA
502580	5	CAAGUAZU	2	CAAGUAZU	6	580	3RA LINEA BALANZA
502590	5	CAAGUAZU	2	CAAGUAZU	6	590	JAGUA KAI
502592	5	CAAGUAZU	2	CAAGUAZU	6	592	COM INDIG 6 DE ENERO
502595	5	CAAGUAZU	2	CAAGUAZU	6	595	ASENT. SANTA LIBRADA
502596	5	CAAGUAZU	2	CAAGUAZU	6	596	CANADA
502600	5	CAAGUAZU	2	CAAGUAZU	6	600	COSTA ROSADO
502610	5	CAAGUAZU	2	CAAGUAZU	6	610	1RA LINEA BALANZA
502620	5	CAAGUAZU	2	CAAGUAZU	6	620	CURUZU ARAUJO
502630	5	CAAGUAZU	2	CAAGUAZU	6	630	5TA LINEA BALANZA
502640	5	CAAGUAZU	2	CAAGUAZU	6	640	SANTA LIBRADA I
502650	5	CAAGUAZU	2	CAAGUAZU	6	650	4TA LINEA BALANZA
502660	5	CAAGUAZU	2	CAAGUAZU	6	660	SAN FRANCISCO
502665	5	CAAGUAZU	2	CAAGUAZU	6	665	SAN ISIDRO II
502670	5	CAAGUAZU	2	CAAGUAZU	6	670	8VA LINEA WALTER INSFRAN
502680	5	CAAGUAZU	2	CAAGUAZU	6	680	BRASILERO KUE
502690	5	CAAGUAZU	2	CAAGUAZU	6	690	ITA PLANCHON
502695	5	CAAGUAZU	2	CAAGUAZU	6	695	CRISTO REY
502700	5	CAAGUAZU	2	CAAGUAZU	6	700	TEBICUARYMI
502705	5	CAAGUAZU	2	CAAGUAZU	6	705	CALLE 9 SAN PEDRO
502710	5	CAAGUAZU	2	CAAGUAZU	6	710	OVENA
502720	5	CAAGUAZU	2	CAAGUAZU	6	720	3RA LINEA BALANZA II
502740	5	CAAGUAZU	2	CAAGUAZU	6	740	13 LINEA WALTER INSFRAN
502750	5	CAAGUAZU	2	CAAGUAZU	6	750	NANE MAITEI
502755	5	CAAGUAZU	2	CAAGUAZU	6	755	COM INDIG KAMBAY
502760	5	CAAGUAZU	2	CAAGUAZU	6	760	MBOI KA'E GUASU
502770	5	CAAGUAZU	2	CAAGUAZU	6	770	GUYRAUNGUA
502771	5	CAAGUAZU	2	CAAGUAZU	6	771	COM INDIG TAKUAPI'I SANTA LIBRADA
502775	5	CAAGUAZU	2	CAAGUAZU	6	775	SAN BLAS
503001	5	CAAGUAZU	3	CARAYAO	1	1	CENTRO
503002	5	CAAGUAZU	3	CARAYAO	1	2	SAN FRANCISCO
503003	5	CAAGUAZU	3	CARAYAO	1	3	SAN JUAN DE LAS MERCEDES
503004	5	CAAGUAZU	3	CARAYAO	1	4	LAS MERCEDES
503100	5	CAAGUAZU	3	CARAYAO	6	100	CLETO ROMERO
503110	5	CAAGUAZU	3	CARAYAO	6	110	PEGUAHO
503120	5	CAAGUAZU	3	CARAYAO	6	120	COMISARIA KUE
503130	5	CAAGUAZU	3	CARAYAO	6	130	CAMPO REDONDO
503140	5	CAAGUAZU	3	CARAYAO	6	140	ARROYO HONDO
503150	5	CAAGUAZU	3	CARAYAO	6	150	SANTA CATALINA
503155	5	CAAGUAZU	3	CARAYAO	3	155	SANTA CATALINA SUB-URBANO
503160	5	CAAGUAZU	3	CARAYAO	6	160	PARANA KUE
503170	5	CAAGUAZU	3	CARAYAO	6	170	ARROYO GUASU
503180	5	CAAGUAZU	3	CARAYAO	6	180	PARA'I
503185	5	CAAGUAZU	3	CARAYAO	6	185	PARA'I NORTE
503190	5	CAAGUAZU	3	CARAYAO	6	190	CALLE 24 MIL
503200	5	CAAGUAZU	3	CARAYAO	6	200	PARA GUASU
503210	5	CAAGUAZU	3	CARAYAO	6	210	COLONIA 12 MIL
503230	5	CAAGUAZU	3	CARAYAO	6	230	VIRGEN DE FATIMA
503240	5	CAAGUAZU	3	CARAYAO	6	240	CRISTO REY
503270	5	CAAGUAZU	3	CARAYAO	6	270	CERRO CORA - MARIA AUXILIADORA
503280	5	CAAGUAZU	3	CARAYAO	6	280	TENIENTE MORALES - ALEMAN KUE
503290	5	CAAGUAZU	3	CARAYAO	6	290	ASENT. 3 DE MAYO
503300	5	CAAGUAZU	3	CARAYAO	6	300	ASENT. SAN MIGUEL
503310	5	CAAGUAZU	3	CARAYAO	6	310	LOMA HOVY
503320	5	CAAGUAZU	3	CARAYAO	6	320	ITANARA
503330	5	CAAGUAZU	3	CARAYAO	6	330	ARROYO HOVY
503340	5	CAAGUAZU	3	CARAYAO	6	340	CRUCE MAINUMBY
503350	5	CAAGUAZU	3	CARAYAO	6	350	SANTA MAGDALENA
503360	5	CAAGUAZU	3	CARAYAO	6	360	ASENT. MARIANO DIAZ
503370	5	CAAGUAZU	3	CARAYAO	6	370	CALLE 13 MIL
503380	5	CAAGUAZU	3	CARAYAO	6	380	CALLE 14 MIL
503390	5	CAAGUAZU	3	CARAYAO	6	390	POZO 5
503400	5	CAAGUAZU	3	CARAYAO	6	400	COLONIA NICOLAS BO
503410	5	CAAGUAZU	3	CARAYAO	6	410	GUIDO ALMADA
504001	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	1	MARIA AUXILIADORA
504002	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	2	SAN JOSE
504003	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	3	SAN MIGUEL
504004	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	4	SAN LUIS
504005	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	5	CAACUPE
504006	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	6	FATIMA
504007	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	7	CORAZON DE JESUS
504008	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	8	PIRAY
504009	5	CAAGUAZU	4	DR. CECILIO BAEZ	1	9	TORORO
504110	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	110	ASENTAMIENTO CANDIDO BENITEZ
504120	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	120	ASENTAMIENTO SAN BLAS
504140	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	140	HUGUA PO'I
504150	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	150	VIRGEN DEL ROSARIO
504160	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	160	EMPALADO
504170	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	170	TORORO
504180	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	180	PIRAY
504190	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	190	PASO ITA
504200	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	200	SAN MIGUEL
504210	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	210	SAN ANTONIO
504220	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	220	CAATYMI
504230	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	230	1RO DE MAYO
504240	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	240	CANADA
504250	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	250	POTRERO
504260	5	CAAGUAZU	4	DR. CECILIO BAEZ	6	260	ASENT LA CANDELARIA
505001	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	1	1	URBANO
505110	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	110	CRUCE MBUTUY
505120	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	120	NATI'URY GUASU
505130	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	130	MONDORI
505140	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	140	INVERNADA
505150	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	150	GUARDIA KUE
505160	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	160	COSTA ALEGRE
505170	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	170	SAN ISIDRO
505190	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	190	CALLE 40
505200	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	200	NATI'URY MI
505210	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	210	MBUTUY I
505220	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	220	UMBU KUA
505230	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	230	SAN MIGUEL
505240	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	240	SANTO DOMINGO I
505250	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	250	SAN FELIPE
505260	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	260	SANTO DOMINGO II
505270	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	270	CALLE 20
505280	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	280	INDUSTRIAL
505290	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	290	SANTA ROSA
505300	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	300	SAN AGUSTIN
505310	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	310	SANTA LUCIA
505320	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	320	POTRERITO NUEVO
505330	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	330	COSTA QUINONEZ
505340	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	340	VIRGEN DE FATIMA
505350	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	350	MBUTUY
505360	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	360	NINO JESUS
505370	5	CAAGUAZU	5	SANTA ROSA DEL MBUTUY	6	370	ALEGRE
506001	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	1	CRISTO REY
506002	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	2	SANTA MARIA
506003	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	3	MBO'EHARA
506004	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	4	SAN FRANCISCO
506005	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	5	SANTA CLARA
506006	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	6	CENTRO
506007	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	7	SAN LUIS
506008	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	8	MARIA AUXILIADORA
506009	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	9	SEMINARIO
506010	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	1	10	GUAVIRA MI
506100	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	100	JUKYRY SAN JUAN
506110	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	110	MARIA AUXILIADORA
506120	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	120	TEMBETARY
506130	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	130	COM INDIG PUNTA PORA
506140	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	140	LOPEZ'I
506150	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	150	SAN MIGUEL
506160	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	160	SAN JORGE
506170	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	170	CAIBO
506180	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	180	SAN JOSE
506190	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	190	COLONIA SANTO DOMINGO DE GUZMAN
506200	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	200	SEGUNDA LINEA YTU
506210	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	210	PRIMERA LINEA YTU
506220	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	220	YKUA PORA
506230	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	230	SAN ISIDRO 2DA LINEA
506240	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	240	SAN ISIDRO 1RA LINEA
506250	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	250	PUENTE SECO
506260	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	260	GUAVIJU
506270	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	270	SAN FRANCISCO
506280	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	280	TERCERA LINEA YTU
506290	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	290	8 DE DICIEMBRE
506300	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	300	PUENTECITO
506310	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	310	YVU
506320	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	320	YVYRA POKA
506340	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	340	NUEVA ITALIA
506360	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	360	PATINO
506370	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	370	CALLE 5 MARIA AUXILIADORA
506380	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	380	SANTA MARIA
506390	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	390	ZANJA PE
506400	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	400	CALLE 6 ZANJA PE
506410	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	410	ISLA VERA
506450	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	450	CALLE 7 RAMONITA
506460	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	460	CRUCE PASTOREO
506470	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	470	SANTA ROSA
506490	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	490	SAN ANTONIO GUASU
506520	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	520	GUYRAUNGUA
506530	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	530	CHACO'I
506540	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	540	COLONIA SOMMERFELD
506550	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	550	CAMPO 1
506560	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	560	SAN ANTONIO-MI
506570	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	570	SEGUNDA LINEA SANTO DOMINGO
506580	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	580	CALLE 5 ZANJA PYTA
506590	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	590	JURUNDIAY
506600	5	CAAGUAZU	6	DR. JUAN MANUEL FRUTOS	6	600	COM INDIG NUEVA ESPERANZA VY'A RENDA
507001	5	CAAGUAZU	7	REPATRIACION	1	1	SAN FRANCISCO
507002	5	CAAGUAZU	7	REPATRIACION	1	2	CENTRO
507003	5	CAAGUAZU	7	REPATRIACION	1	3	SAN BLAS
507004	5	CAAGUAZU	7	REPATRIACION	1	4	CANADA
507005	5	CAAGUAZU	7	REPATRIACION	1	5	PLANTA URBANA
507100	5	CAAGUAZU	7	REPATRIACION	6	100	YPEGUA
507110	5	CAAGUAZU	7	REPATRIACION	6	110	1RA LINEA CHACORE
507120	5	CAAGUAZU	7	REPATRIACION	6	120	2DA LINEA CHACORE
507140	5	CAAGUAZU	7	REPATRIACION	6	140	COM INDIG PINDO'I CULANTRILLO
507150	5	CAAGUAZU	7	REPATRIACION	6	150	SANTORY
507160	5	CAAGUAZU	7	REPATRIACION	6	160	CANADA
507170	5	CAAGUAZU	7	REPATRIACION	6	170	PINDO'I CULANTRILLO
507171	5	CAAGUAZU	7	REPATRIACION	6	171	PINDO'I
507172	5	CAAGUAZU	7	REPATRIACION	6	172	CULANTRILLO
507180	5	CAAGUAZU	7	REPATRIACION	6	180	SAN ANTONIO
507190	5	CAAGUAZU	7	REPATRIACION	6	190	1RA LINEA EUGENIO A GARAY
507200	5	CAAGUAZU	7	REPATRIACION	6	200	CALLE PIRIBEBUY
507210	5	CAAGUAZU	7	REPATRIACION	6	210	4TA LINEA CHACORE
507220	5	CAAGUAZU	7	REPATRIACION	6	220	1RA LINEA IRRAZABAL
507230	5	CAAGUAZU	7	REPATRIACION	6	230	2DA LINEA IRRAZABAL NORTE
507250	5	CAAGUAZU	7	REPATRIACION	6	250	CABALLERO ALVAREZ
507280	5	CAAGUAZU	7	REPATRIACION	6	280	EUGENIO A GARAY
507290	5	CAAGUAZU	7	REPATRIACION	6	290	2DA LINEA TACURU PYTA
507300	5	CAAGUAZU	7	REPATRIACION	6	300	2DA LINEA IRRAZABAL SUR
507310	5	CAAGUAZU	7	REPATRIACION	6	310	4TA LINEA EUGENIO A. GARAY
507320	5	CAAGUAZU	7	REPATRIACION	6	320	3RA LINEA KA'AMINDY
507325	5	CAAGUAZU	7	REPATRIACION	6	325	ASENT. SAN MIGUEL KA'AMINDY
507330	5	CAAGUAZU	7	REPATRIACION	6	330	2DA LINEA SAN MIGUEL
507350	5	CAAGUAZU	7	REPATRIACION	6	350	MANZANA E
507360	5	CAAGUAZU	7	REPATRIACION	6	360	3RA LINEA ANGARA
507370	5	CAAGUAZU	7	REPATRIACION	6	370	BARRIENTOS KUE
507380	5	CAAGUAZU	7	REPATRIACION	6	380	6TA LINEA GENERAL DELGADO
507385	5	CAAGUAZU	7	REPATRIACION	6	385	GENERAL DELGADO
507390	5	CAAGUAZU	7	REPATRIACION	6	390	4TA LINEA CABALLERO ALVAREZ
507400	5	CAAGUAZU	7	REPATRIACION	6	400	MANDU'ARA
507410	5	CAAGUAZU	7	REPATRIACION	6	410	2DA LINEA GENERAL DELGADO
507420	5	CAAGUAZU	7	REPATRIACION	6	420	5TA LINEA GENERAL DELGADO
507440	5	CAAGUAZU	7	REPATRIACION	6	440	AGUILA NEGRA
507460	5	CAAGUAZU	7	REPATRIACION	6	460	COM INDIG NU HOVY
507470	5	CAAGUAZU	7	REPATRIACION	6	470	EL TRIUNFO
507480	5	CAAGUAZU	7	REPATRIACION	6	480	COM INDIG KAATYMI
507500	5	CAAGUAZU	7	REPATRIACION	6	500	COM INDIG TAKUARO - 3 DE FEBRERO
507510	5	CAAGUAZU	7	REPATRIACION	6	510	3 DE NOVIEMBRE
507511	5	CAAGUAZU	7	REPATRIACION	6	511	3 DE NOVIEMBRE BRASIL KUE
507512	5	CAAGUAZU	7	REPATRIACION	6	512	3 DE NOVIEMBRE SAN ISIDRO
507513	5	CAAGUAZU	7	REPATRIACION	6	513	3 DE NOVIEMBRE SAN JORGE
507514	5	CAAGUAZU	7	REPATRIACION	6	514	3 DE NOVIEMBRE SAN VICENTE
507515	5	CAAGUAZU	7	REPATRIACION	6	515	3 DE NOVIEMBRE 2DA LINEA
507516	5	CAAGUAZU	7	REPATRIACION	6	516	3 DE NOVIEMBRE 3RA LINEA
507517	5	CAAGUAZU	7	REPATRIACION	6	517	3 DE NOVIEMBRE 5TA LINEA
507518	5	CAAGUAZU	7	REPATRIACION	6	518	3 DE NOVIEMBRE CERRITO
507519	5	CAAGUAZU	7	REPATRIACION	6	519	3 DE NOVIEMBRE ESPIRITU SANTO
507520	5	CAAGUAZU	7	REPATRIACION	6	520	3RA LINEA GENERAL DELGADO
507521	5	CAAGUAZU	7	REPATRIACION	6	521	3 DE NOVIEMBRE PLANTA URBANA
507530	5	CAAGUAZU	7	REPATRIACION	6	530	SAN FRANCISCO 5TA LINEA
507535	5	CAAGUAZU	7	REPATRIACION	6	535	5TA LINEA ITURBE
507540	5	CAAGUAZU	7	REPATRIACION	6	540	TEJU
507560	5	CAAGUAZU	7	REPATRIACION	6	560	ARROYITO CHACORE
507570	5	CAAGUAZU	7	REPATRIACION	6	570	ASENT. CHIRKATY
507580	5	CAAGUAZU	7	REPATRIACION	6	580	ASENT. JULIANA FLEITAS
507590	5	CAAGUAZU	7	REPATRIACION	6	590	IRRAZABAL
507600	5	CAAGUAZU	7	REPATRIACION	6	600	CAMPITOS
507610	5	CAAGUAZU	7	REPATRIACION	6	610	SANTA LUCIA
507620	5	CAAGUAZU	7	REPATRIACION	6	620	MARIA AUXILIADORA
507630	5	CAAGUAZU	7	REPATRIACION	6	630	SANTA ROSA
507640	5	CAAGUAZU	7	REPATRIACION	6	640	2DA LINEA CABALLERO ALVAREZ
507650	5	CAAGUAZU	7	REPATRIACION	6	650	TAKURU PYTA
507660	5	CAAGUAZU	7	REPATRIACION	6	660	1RA LINEA SAN MIGUEL
507670	5	CAAGUAZU	7	REPATRIACION	6	670	NINO SALVADOR
507680	5	CAAGUAZU	7	REPATRIACION	6	680	3RA LINEA CABALLERO ALVAREZ
507690	5	CAAGUAZU	7	REPATRIACION	6	690	VISTA ALEGRE
507700	5	CAAGUAZU	7	REPATRIACION	6	700	1RA LINEA ITA KARU
507710	5	CAAGUAZU	7	REPATRIACION	6	710	TRIUNFO 2DA LINEA
507720	5	CAAGUAZU	7	REPATRIACION	6	720	TRIUNFO 1RA LINEA
507730	5	CAAGUAZU	7	REPATRIACION	6	730	1RA LINEA PLANTA URBANA
507740	5	CAAGUAZU	7	REPATRIACION	6	740	CERRITO
507750	5	CAAGUAZU	7	REPATRIACION	6	750	CAPIIBARY
507760	5	CAAGUAZU	7	REPATRIACION	6	760	COM INDIG YPA'U SENORITA
507770	5	CAAGUAZU	7	REPATRIACION	6	770	COM INDIG ARROYOPE
507780	5	CAAGUAZU	7	REPATRIACION	6	780	COM INDIG ISLA JOVAI TEJU
507800	5	CAAGUAZU	7	REPATRIACION	6	800	SAN ISIDRO
507810	5	CAAGUAZU	7	REPATRIACION	6	810	SAN BLAS
507820	5	CAAGUAZU	7	REPATRIACION	3	820	PUEBLO DE DIOS SUB-URBANO
507830	5	CAAGUAZU	7	REPATRIACION	6	830	1RA LINEA CABALLERO
507840	5	CAAGUAZU	7	REPATRIACION	6	840	PINDO'I II
507850	5	CAAGUAZU	7	REPATRIACION	6	850	COM INDIG 23 DE JUNIO
507860	5	CAAGUAZU	7	REPATRIACION	6	860	COM INDIG SAN JORGE SANTA ROSA
507880	5	CAAGUAZU	7	REPATRIACION	6	880	COM INDIG YHOVY'I
508001	5	CAAGUAZU	8	NUEVA LONDRES	1	1	CENTRO
508002	5	CAAGUAZU	8	NUEVA LONDRES	1	2	SANTA ISABEL
508100	5	CAAGUAZU	8	NUEVA LONDRES	6	100	KARACHI
508110	5	CAAGUAZU	8	NUEVA LONDRES	6	110	GUAVIRA
508120	5	CAAGUAZU	8	NUEVA LONDRES	6	120	LEON KUE
508130	5	CAAGUAZU	8	NUEVA LONDRES	6	130	LAVO'I
508140	5	CAAGUAZU	8	NUEVA LONDRES	6	140	POTRERO TUJA
508150	5	CAAGUAZU	8	NUEVA LONDRES	6	150	PUNTA ITACURUBI
508160	5	CAAGUAZU	8	NUEVA LONDRES	6	160	TUJU RUGUA
508170	5	CAAGUAZU	8	NUEVA LONDRES	6	170	LA NOVIA
508180	5	CAAGUAZU	8	NUEVA LONDRES	6	180	HUGUA JERE
508190	5	CAAGUAZU	8	NUEVA LONDRES	6	190	ITAU
508200	5	CAAGUAZU	8	NUEVA LONDRES	6	200	RAMAL NUEVA LONDRES
508210	5	CAAGUAZU	8	NUEVA LONDRES	6	210	NUEVA AUSTRALIA
508220	5	CAAGUAZU	8	NUEVA LONDRES	6	220	MBOREVI RUGUA
508230	5	CAAGUAZU	8	NUEVA LONDRES	6	230	BOQUERON
508240	5	CAAGUAZU	8	NUEVA LONDRES	6	240	LOMA RUGUA
508250	5	CAAGUAZU	8	NUEVA LONDRES	6	250	CANADA
509001	5	CAAGUAZU	9	SAN JOAQUIN	1	1	CENTRO
509002	5	CAAGUAZU	9	SAN JOAQUIN	1	2	SAN JOAQUIN
509003	5	CAAGUAZU	9	SAN JOAQUIN	1	3	YVU
509100	5	CAAGUAZU	9	SAN JOAQUIN	6	100	YUKYRY
509110	5	CAAGUAZU	9	SAN JOAQUIN	6	110	SAN ROQUE
509120	5	CAAGUAZU	9	SAN JOAQUIN	6	120	CELANO
509121	5	CAAGUAZU	9	SAN JOAQUIN	6	121	COM INDIG MODAY MI
509122	5	CAAGUAZU	9	SAN JOAQUIN	6	122	COM INDIG TEKOHA PORA CAMPITO
509130	5	CAAGUAZU	9	SAN JOAQUIN	3	130	JUAN SINFORIANO BOGARIN SUB-URBANO
509131	5	CAAGUAZU	9	SAN JOAQUIN	6	131	JUAN SINFORIANO BOGARIN - SAN FRANCISCO
509132	5	CAAGUAZU	9	SAN JOAQUIN	6	132	JUAN SINFORIANO BOGARIN - VIRGEN DEL ROSARIO
509140	5	CAAGUAZU	9	SAN JOAQUIN	6	140	CARPA KUE
509145	5	CAAGUAZU	9	SAN JOAQUIN	6	145	COM INDIG HUGUA RORY
509150	5	CAAGUAZU	9	SAN JOAQUIN	6	150	TARUMA
509155	5	CAAGUAZU	9	SAN JOAQUIN	6	155	COM INDIG TEKOHA MIRI
509160	5	CAAGUAZU	9	SAN JOAQUIN	6	160	SANTO DOMINGO
509170	5	CAAGUAZU	9	SAN JOAQUIN	6	170	TEJAS KUE
509180	5	CAAGUAZU	9	SAN JOAQUIN	6	180	PIRI POTY
509190	5	CAAGUAZU	9	SAN JOAQUIN	6	190	R I 6 BOQUERON
509200	5	CAAGUAZU	9	SAN JOAQUIN	6	200	VIRGEN DEL CARMEN
509210	5	CAAGUAZU	9	SAN JOAQUIN	6	210	NOGUEIRA
509220	5	CAAGUAZU	9	SAN JOAQUIN	6	220	NUA'I
509230	5	CAAGUAZU	9	SAN JOAQUIN	6	230	COM INDIG CERRO MOROTI
509250	5	CAAGUAZU	9	SAN JOAQUIN	6	250	QUINTA I
509260	5	CAAGUAZU	9	SAN JOAQUIN	6	260	MARTILLO
509270	5	CAAGUAZU	9	SAN JOAQUIN	6	270	MALVINAS
509280	5	CAAGUAZU	9	SAN JOAQUIN	6	280	NACIENTE
509290	5	CAAGUAZU	9	SAN JOAQUIN	6	290	TACUAPI'I
509300	5	CAAGUAZU	9	SAN JOAQUIN	6	300	ESPORTIVO
509310	5	CAAGUAZU	9	SAN JOAQUIN	6	310	SAN MIGUEL
509330	5	CAAGUAZU	9	SAN JOAQUIN	6	330	LAGUNA PYTA
509340	5	CAAGUAZU	9	SAN JOAQUIN	6	340	JAHAPE
509360	5	CAAGUAZU	9	SAN JOAQUIN	6	360	GUAHO
509365	5	CAAGUAZU	9	SAN JOAQUIN	6	365	ASENT. 8 DE DICIEMBRE
509370	5	CAAGUAZU	9	SAN JOAQUIN	6	370	TAKUAPI GUASU
509380	5	CAAGUAZU	9	SAN JOAQUIN	6	380	POTRERO
509390	5	CAAGUAZU	9	SAN JOAQUIN	6	390	TORO AKA
509400	5	CAAGUAZU	9	SAN JOAQUIN	6	400	PEJUPA
509405	5	CAAGUAZU	9	SAN JOAQUIN	3	405	PEJUPA SUB-URBANO
509410	5	CAAGUAZU	9	SAN JOAQUIN	6	410	GUA'A KUA
509420	5	CAAGUAZU	9	SAN JOAQUIN	6	420	OLLA RUGUA
509425	5	CAAGUAZU	9	SAN JOAQUIN	3	425	OLLA RUGUA SUB-URBANO
509430	5	CAAGUAZU	9	SAN JOAQUIN	6	430	CUARTA
509440	5	CAAGUAZU	9	SAN JOAQUIN	6	440	ASENT. ARSENIO VAZQUEZ
509450	5	CAAGUAZU	9	SAN JOAQUIN	6	450	SANTA ANA
510001	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	1	1	VIRGEN DEL CARMEN
510002	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	1	2	SANTA RITA
510003	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	1	3	SAN JUAN
510004	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	1	4	SAN BLAS
510110	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	110	YKUA RUGUA
510120	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	120	COSTA PUKU
510130	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	130	SAN IGNACIO DE LOYOLA
510140	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	140	Y'AKU BARRERO
510150	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	150	SAN LUIS (MBOITY)
510160	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	160	PRESIDENTE FRANCO
510165	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	3	165	PTE FRANCO SUB URBANO
510180	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	180	SAN JUAN BAUTISTA
510190	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	190	ISLA KARAPA
510200	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	200	MANDIHO
510210	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	210	POTRERO IRALA
510220	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	220	ISLA ROJA
510230	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	230	SAN PATRICIO
510240	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	240	SERAFINI
510250	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	250	CHACHINDY
510260	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	260	ARASAPE
510270	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	270	MONTE ALTO
510280	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	280	LAGUNA VERDE
510290	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	290	HUGUA GUASU
510320	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	320	CASTANO
510330	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	330	YHAKA
510340	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	340	CANADAS
510350	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	350	KAAGUY KUPE
510360	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	360	CARIY POTRERO
510370	5	CAAGUAZU	10	SAN JOSE DE LOS ARROYOS	6	370	TACUATY
511001	5	CAAGUAZU	11	YHU	1	1	SANTA LIBRADA
511002	5	CAAGUAZU	11	YHU	1	2	VIRGEN DEL ROSARIO
511003	5	CAAGUAZU	11	YHU	1	3	BARRIO MUNICIPALIDAD
511004	5	CAAGUAZU	11	YHU	1	4	SAN MIGUEL
511005	5	CAAGUAZU	11	YHU	1	5	TERMINAL
511006	5	CAAGUAZU	11	YHU	1	6	CENTRO
511007	5	CAAGUAZU	11	YHU	1	7	YHU
511110	5	CAAGUAZU	11	YHU	6	110	BELLA VISTA
511115	5	CAAGUAZU	11	YHU	3	115	BELLA VISTA SUB URBANO
511120	5	CAAGUAZU	11	YHU	6	120	VIRGEN DEL ROSARIO
511121	5	CAAGUAZU	11	YHU	6	121	TAVAPY
511122	5	CAAGUAZU	11	YHU	6	122	PINDO
511123	5	CAAGUAZU	11	YHU	6	123	CALLE SAN PEDRO
511124	5	CAAGUAZU	11	YHU	6	124	KO'E POTY
511125	5	CAAGUAZU	11	YHU	3	125	VIRGEN DEL ROSARIO SUB URBANO
511126	5	CAAGUAZU	11	YHU	6	126	COM INDIG KO'E POTY
511210	5	CAAGUAZU	11	YHU	6	210	SIDEPAR 2DA LINEA
511215	5	CAAGUAZU	11	YHU	6	215	COM INDIG YPACHI
511250	5	CAAGUAZU	11	YHU	6	250	3RA LINEA SAN ISDRO
511260	5	CAAGUAZU	11	YHU	6	260	PATRIMONIO
511270	5	CAAGUAZU	11	YHU	6	270	4TA LINEA MCAL LOPEZ
511280	5	CAAGUAZU	11	YHU	6	280	6TA MARISCAL LOPEZ
511285	5	CAAGUAZU	11	YHU	6	285	ASENT. VIRGEN DEL CARMEN
511290	5	CAAGUAZU	11	YHU	6	290	VIRGEN DEL CARMEN
511320	5	CAAGUAZU	11	YHU	6	320	COLONIA MARISCAL LOPEZ
511340	5	CAAGUAZU	11	YHU	6	340	YVY PYTA
511360	5	CAAGUAZU	11	YHU	6	360	DEPOSITO KUE 2DA LINEA
511365	5	CAAGUAZU	11	YHU	6	365	2DA LINEA MARACANA
511370	5	CAAGUAZU	11	YHU	6	370	SAN ALBERTO
511380	5	CAAGUAZU	11	YHU	6	380	MARISCAL LOPEZ
511390	5	CAAGUAZU	11	YHU	6	390	CAMBILO KUE
511395	5	CAAGUAZU	11	YHU	6	395	COM INDIG PARAJE PUKU
511400	5	CAAGUAZU	11	YHU	6	400	Y'ATA 12
511410	5	CAAGUAZU	11	YHU	6	410	3RA  LINEA GUAJAYVI
511415	5	CAAGUAZU	11	YHU	6	415	4TA LINEA STA CATALINA
511430	5	CAAGUAZU	11	YHU	6	430	COM INDIG ARROYO GUAZU
511431	5	CAAGUAZU	11	YHU	6	431	COM INDIG NUEVA ESTRELLA
511440	5	CAAGUAZU	11	YHU	6	440	DEPOSITO KUE
511445	5	CAAGUAZU	11	YHU	6	445	DEPOSITO KUE 1RA LINEA
511460	5	CAAGUAZU	11	YHU	6	460	SAN MIGUEL QUINTA
511470	5	CAAGUAZU	11	YHU	6	470	1RA LINEA - KA'IHO
511480	5	CAAGUAZU	11	YHU	6	480	2DA LINEA - KA'IHO
511485	5	CAAGUAZU	11	YHU	6	485	3ERA LINEA - KA'IHO SAN FRANCISCO
511490	5	CAAGUAZU	11	YHU	6	490	SANTA CATALINA I
511491	5	CAAGUAZU	11	YHU	6	491	SAN ISIDRO
511492	5	CAAGUAZU	11	YHU	6	492	SANTA ELENA
511500	5	CAAGUAZU	11	YHU	6	500	MARIA AUXILIADORA
511510	5	CAAGUAZU	11	YHU	6	510	SAN RAFAEL
511520	5	CAAGUAZU	11	YHU	6	520	POTRERO ALVAREZ
511530	5	CAAGUAZU	11	YHU	6	530	SAN ANTONIO I
511540	5	CAAGUAZU	11	YHU	6	540	LAGUNA VERA
511550	5	CAAGUAZU	11	YHU	6	550	ASENT. MARIA CRISTINA
511580	5	CAAGUAZU	11	YHU	6	580	YVYRA KATU
511590	5	CAAGUAZU	11	YHU	6	590	YATAITY
511620	5	CAAGUAZU	11	YHU	6	620	SANTA LUCIA
511640	5	CAAGUAZU	11	YHU	6	640	VILLAREAL ZAPATTINI KUE
511645	5	CAAGUAZU	11	YHU	6	645	SAN ANTONIO II
511650	5	CAAGUAZU	11	YHU	6	650	SANTA LIBRADA
511655	5	CAAGUAZU	11	YHU	6	655	SAN JUAN
511660	5	CAAGUAZU	11	YHU	6	660	LIMA
511670	5	CAAGUAZU	11	YHU	6	670	AGUADA
511680	5	CAAGUAZU	11	YHU	6	680	SAN RAMON
511690	5	CAAGUAZU	11	YHU	6	690	SAN MIGUEL I
511695	5	CAAGUAZU	11	YHU	6	695	ASENT 30 DE OCTUBRE
511700	5	CAAGUAZU	11	YHU	6	700	ARAPAY
511710	5	CAAGUAZU	11	YHU	6	710	SAN FRANCISCO
511720	5	CAAGUAZU	11	YHU	6	720	CALLE MBYKY
511730	5	CAAGUAZU	11	YHU	6	730	ZAVALA
511740	5	CAAGUAZU	11	YHU	6	740	CORRENTINA
511750	5	CAAGUAZU	11	YHU	6	750	SANTA  CATALINA
511760	5	CAAGUAZU	11	YHU	6	760	TORO CANGUE
511765	5	CAAGUAZU	11	YHU	6	765	COM INDIG TORO CANGUE
511780	5	CAAGUAZU	11	YHU	6	780	CANADITA
511785	5	CAAGUAZU	11	YHU	6	785	JUKERI
511790	5	CAAGUAZU	11	YHU	6	790	TARUMA
511795	5	CAAGUAZU	11	YHU	6	795	SAN LORENZO
511800	5	CAAGUAZU	11	YHU	6	800	JUAN SINFORIANO BOGARIN
511805	5	CAAGUAZU	11	YHU	6	805	LA AMISTAD
511820	5	CAAGUAZU	11	YHU	6	820	CANDIA KUE
511825	5	CAAGUAZU	11	YHU	6	825	DESVIO SAN CARLOS
511830	5	CAAGUAZU	11	YHU	6	830	SANTA LIBRADA I
511840	5	CAAGUAZU	11	YHU	6	840	SAN ANTONIO III
511850	5	CAAGUAZU	11	YHU	6	850	SAN MIGUEL II
512001	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	1	MARIA AUXILIADORA
512002	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	2	SAN BLAS
512003	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	3	SAN JORGE
512004	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	4	VIRGEN SERRANA
512005	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	5	LA FORTUNA
512006	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	6	SANTA LIBRADA
512007	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	7	SANTA CATALINA
512008	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	8	SAN FRANCISCO
512009	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	1	9	BUENA VISTA
512100	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	100	TORIN
512110	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	110	ASENT. CRISTOBAL ESPINOLA
512120	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	120	ZAPALLO
512130	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	130	CALLE 3
512150	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	150	COM INDIG CHEIRO ARAPOTY
512160	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	160	COM INDIG MBARIGUI 14
512170	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	170	FLORIDO
512180	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	180	MARACANA
512190	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	190	PRIMERA LINEA
512200	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	200	LUZ Y ESPERANZA
512210	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	210	COLONIA SOMMERFELD
512220	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	220	COM INDIG SAN JUAN CHEIRO ARA POTY YHOVY
512230	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	230	COLONIA BERGTHAL
512240	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	240	COM INDIG MBOCAJA YGUASU
512250	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	250	COM INDIG NUEVA ESPERANZA
512260	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	260	COM INDIG KOE PYAHU
512270	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	270	COM INDIG JAGUARY
512280	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	280	PA'I HA
512290	5	CAAGUAZU	12	DR. J. EULOGIO ESTIGARRIBIA	6	290	KO'E RORY
513001	5	CAAGUAZU	13	R.I. 3 CORRALES	1	1	CENTRO
513100	5	CAAGUAZU	13	R.I. 3 CORRALES	6	100	AMPLIACION YPYTA
513110	5	CAAGUAZU	13	R.I. 3 CORRALES	6	110	CALLE 25
513120	5	CAAGUAZU	13	R.I. 3 CORRALES	6	120	CALLE 22
513130	5	CAAGUAZU	13	R.I. 3 CORRALES	6	130	CALLE 20 TAYAO
513140	5	CAAGUAZU	13	R.I. 3 CORRALES	6	140	CALLE 18 TAYAO
513150	5	CAAGUAZU	13	R.I. 3 CORRALES	6	150	CALLE 17 TAYAO
513160	5	CAAGUAZU	13	R.I. 3 CORRALES	6	160	CALLE 16 TAYAO
513170	5	CAAGUAZU	13	R.I. 3 CORRALES	6	170	TACUARY
513180	5	CAAGUAZU	13	R.I. 3 CORRALES	6	180	CALLE 10
513190	5	CAAGUAZU	13	R.I. 3 CORRALES	6	190	CALLE 14 TAYAO
513200	5	CAAGUAZU	13	R.I. 3 CORRALES	6	200	TAKUAKORA
513210	5	CAAGUAZU	13	R.I. 3 CORRALES	6	210	CALLE 6 TAKUARY
513220	5	CAAGUAZU	13	R.I. 3 CORRALES	6	220	CAPILLITA TAKUAKORA
513230	5	CAAGUAZU	13	R.I. 3 CORRALES	6	230	TOVATIRY
513240	5	CAAGUAZU	13	R.I. 3 CORRALES	6	240	CALLE 12
513250	5	CAAGUAZU	13	R.I. 3 CORRALES	6	250	CALLE 24
513260	5	CAAGUAZU	13	R.I. 3 CORRALES	6	260	PFANNEL
513270	5	CAAGUAZU	13	R.I. 3 CORRALES	6	270	CALLE 23
513280	5	CAAGUAZU	13	R.I. 3 CORRALES	6	280	CALLE 21
513290	5	CAAGUAZU	13	R.I. 3 CORRALES	6	290	COM INDIG TOVATIRY
513300	5	CAAGUAZU	13	R.I. 3 CORRALES	6	300	COM INDIG YVY PORA
514001	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	1	1	URBANO
514120	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	120	SAN JUAN
514130	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	130	CEDROTY
514150	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	150	11 DE SETIEMBRE
514160	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	160	PANAMBI
514170	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	170	TRES PALMAS
514180	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	180	COM INDIG CHEIRO  ARA POTY - PANAMBI
514185	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	185	COM INDIG Y'AKA RETA
514190	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	190	COM INDIG TAJY POTY
514200	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	200	COM INDIG YVYRYVATE
514210	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	210	COM INDIG SANTA TERESA
514220	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	220	CASILLA 2
514225	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	3	225	CASILLA 2 - SUB URBANO
514230	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	230	LUCERO
514250	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	250	SAN ISIDRO
514260	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	260	SYRYKA
514270	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	270	1 DE MAYO
514280	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	280	CAMPANARIO
514290	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	290	COLONIA UNIDA
514300	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	300	ASENT. CHEMENDA
514320	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	320	SANCHEZ KUE
514340	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	340	YHOVY
514350	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	350	4 DE OCTUBRE
514360	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	360	RANCHO FLORES
514370	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	370	YKUA PORA
514390	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	390	SATI
514400	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	400	MARIA AUXILIADORA
514420	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	420	SAN RAMON
514430	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	430	3 CORRALES
514450	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	450	BUENA VISTA
514460	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	460	SAN PEDRO
514470	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	470	PUENTE VAVA
514500	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	500	CARRERIA'I
514510	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	510	CORDILLERA
514520	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	520	SAN ANTONIO
514530	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	530	SANTA CATALINA
514540	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	540	COM INDIG LOMA PIROY
514550	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	550	MONTENEGRO
514560	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	560	KO'E RORY
514570	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	570	SAN FRANCISCO
514650	5	CAAGUAZU	14	RAUL ARSENIO OVIEDO	6	650	COM INDIG NEMBIARA
515001	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	1	1	CENTRO
515002	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	1	2	FRACCION BELLA VISTA
515003	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	1	3	ASENT. JOVENES UNIDOS
515100	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	100	MARIA AUXILIADORA
515110	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	110	CALABRIA
515120	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	120	SAN AGUSTIN
515140	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	140	SAN ISIDRO NORTE
515150	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	150	ASENT. ARSENIO BAEZ
515160	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	160	SAN ROQUE
515170	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	170	VIRGEN DEL CARMEN
515180	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	180	SAN FRANCISCO
515190	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	190	SAN BLAS
515210	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	210	3 DE MAYO
515220	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	220	LA VIRGINIA
515230	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	230	NORTE AMERICA
515240	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	240	ALEGRE
515250	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	250	SAN ANTONIO
515260	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	260	SAN JOSE 1RA LINEA
515270	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	270	ZAPALLO
515280	5	CAAGUAZU	15	JOSE DOMINGO OCAMPOS	6	280	ASENT. KO'ETI JAVE
516001	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	1	1	URBANO
516110	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	110	RESERVA PARAGUAYA
516120	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	120	CAACUPEMI
516130	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	130	ISLA CAMINERA
516140	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	140	SAN MARCOS
516150	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	150	SAN CARLOS
516160	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	160	SAN LORENZO
516170	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	170	SANTA TERESA
516180	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	180	PALMITAL
516200	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	200	JAKARE KAI
516210	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	210	SANTA ANA
516220	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	220	CAMPO FLORIDO
516240	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	240	SAN ANTONIO
516270	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	270	REPRESA ACARAY
516280	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	280	LATERZA
516290	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	290	SAN PEDRO
516300	5	CAAGUAZU	16	MARISCAL FRANCISCO SOLANO LOPEZ	6	300	COM INDIG KAAGUY POTY ROMERO KUE
517001	5	CAAGUAZU	17	LA PASTORA	1	1	URBANO
517100	5	CAAGUAZU	17	LA PASTORA	6	100	SAN JOSE OBRERO
517110	5	CAAGUAZU	17	LA PASTORA	6	110	SANTA LUCIA
517120	5	CAAGUAZU	17	LA PASTORA	6	120	SAN MIGUEL
517130	5	CAAGUAZU	17	LA PASTORA	6	130	SAN FRANCISCO
517140	5	CAAGUAZU	17	LA PASTORA	6	140	SAN JUAN
517150	5	CAAGUAZU	17	LA PASTORA	6	150	ZANJA KORA
517160	5	CAAGUAZU	17	LA PASTORA	6	160	SANTA ROSA
517170	5	CAAGUAZU	17	LA PASTORA	6	170	SANTO DOMINGO
517180	5	CAAGUAZU	17	LA PASTORA	6	180	SAN VICENTE
517190	5	CAAGUAZU	17	LA PASTORA	6	190	SAN ANTONIO
517200	5	CAAGUAZU	17	LA PASTORA	6	200	SANTA LIBRADA
517210	5	CAAGUAZU	17	LA PASTORA	6	210	MARIA AUXILIADORA
517220	5	CAAGUAZU	17	LA PASTORA	6	220	SAN LUIS
517230	5	CAAGUAZU	17	LA PASTORA	6	230	SAN ISIDRO
517240	5	CAAGUAZU	17	LA PASTORA	6	240	SAN RAFAEL
517250	5	CAAGUAZU	17	LA PASTORA	6	250	CALLE PYAHU
517260	5	CAAGUAZU	17	LA PASTORA	6	260	LEIVA'I
517270	5	CAAGUAZU	17	LA PASTORA	6	270	SAN BLAS
518001	5	CAAGUAZU	18	3 DE FEBRERO	1	1	URBANO
518100	5	CAAGUAZU	18	3 DE FEBRERO	6	100	CANDIA KUE
518110	5	CAAGUAZU	18	3 DE FEBRERO	6	110	SAN CARLOS
518120	5	CAAGUAZU	18	3 DE FEBRERO	6	120	REMANSO
518130	5	CAAGUAZU	18	3 DE FEBRERO	6	130	ZAPALLO
518140	5	CAAGUAZU	18	3 DE FEBRERO	6	140	VIRGEN DEL PILAR
518150	5	CAAGUAZU	18	3 DE FEBRERO	6	150	JUKERI
518160	5	CAAGUAZU	18	3 DE FEBRERO	6	160	SANTA INES
518170	5	CAAGUAZU	18	3 DE FEBRERO	6	170	SAN LUIS
518180	5	CAAGUAZU	18	3 DE FEBRERO	6	180	CORRENTINA
518190	5	CAAGUAZU	18	3 DE FEBRERO	6	190	SAN RAFAEL
518200	5	CAAGUAZU	18	3 DE FEBRERO	6	200	SAN JOSE
518220	5	CAAGUAZU	18	3 DE FEBRERO	6	220	SEXTA LINEA 3 DE FEBRERO
518230	5	CAAGUAZU	18	3 DE FEBRERO	6	230	SANTA ROSA
518250	5	CAAGUAZU	18	3 DE FEBRERO	6	250	SANTA LIBRADA
518260	5	CAAGUAZU	18	3 DE FEBRERO	6	260	SAN MIGUEL
518270	5	CAAGUAZU	18	3 DE FEBRERO	6	270	SAN PEDRO
518280	5	CAAGUAZU	18	3 DE FEBRERO	6	280	SAN LORENZO
518290	5	CAAGUAZU	18	3 DE FEBRERO	6	290	GUAJAYVI
518300	5	CAAGUAZU	18	3 DE FEBRERO	6	300	PASO ITA
518310	5	CAAGUAZU	18	3 DE FEBRERO	6	310	KO'E RORY
518320	5	CAAGUAZU	18	3 DE FEBRERO	6	320	TERCERA LINEA 3 DE FEBRERO
518330	5	CAAGUAZU	18	3 DE FEBRERO	6	330	MARIA AUXILIADORA
518340	5	CAAGUAZU	18	3 DE FEBRERO	6	340	PINDO'I
519001	5	CAAGUAZU	19	SIMON BOLIVAR	1	1	URBANO
519002	5	CAAGUAZU	19	SIMON BOLIVAR	1	2	POTRERITO
519003	5	CAAGUAZU	19	SIMON BOLIVAR	1	3	ASENT SAN MIGUEL
519004	5	CAAGUAZU	19	SIMON BOLIVAR	1	4	ASENT SAN FRANCISCO
519100	5	CAAGUAZU	19	SIMON BOLIVAR	6	100	TORIN KUE
519110	5	CAAGUAZU	19	SIMON BOLIVAR	6	110	COSTA VILLALBA
519120	5	CAAGUAZU	19	SIMON BOLIVAR	6	120	NU PY
519130	5	CAAGUAZU	19	SIMON BOLIVAR	6	130	SAN DAMIAN
519140	5	CAAGUAZU	19	SIMON BOLIVAR	6	140	SANTA ANA
519150	5	CAAGUAZU	19	SIMON BOLIVAR	6	150	POTRERO ACEVAL
519160	5	CAAGUAZU	19	SIMON BOLIVAR	6	160	ASENT. SAN LUIS
519165	5	CAAGUAZU	19	SIMON BOLIVAR	6	165	ASENT. CHOKOKUE RETA
519170	5	CAAGUAZU	19	SIMON BOLIVAR	6	170	ARROYO PORA
519180	5	CAAGUAZU	19	SIMON BOLIVAR	6	180	EMPALADO
519190	5	CAAGUAZU	19	SIMON BOLIVAR	6	190	ASENT. SAN ANTONIO I
519200	5	CAAGUAZU	19	SIMON BOLIVAR	6	200	SAN ANTONIO
519210	5	CAAGUAZU	19	SIMON BOLIVAR	6	210	ASENT. SAN ANTONIO II
519220	5	CAAGUAZU	19	SIMON BOLIVAR	6	220	ASENT SAN JOSE
519230	5	CAAGUAZU	19	SIMON BOLIVAR	6	230	ASENTAMIENTO SAN AGUSTIN
519270	5	CAAGUAZU	19	SIMON BOLIVAR	6	270	POTRERO OCULTO
520001	5	CAAGUAZU	20	VAQUERIA	1	1	MARIA ELVA
520002	5	CAAGUAZU	20	VAQUERIA	1	2	30 DE JULIO
520003	5	CAAGUAZU	20	VAQUERIA	1	3	BARRIO CENTRO
520004	5	CAAGUAZU	20	VAQUERIA	1	4	TERMINAL
520005	5	CAAGUAZU	20	VAQUERIA	1	5	SAN ANTONIO
520006	5	CAAGUAZU	20	VAQUERIA	1	6	6 DE ENERO
520007	5	CAAGUAZU	20	VAQUERIA	1	7	SAN BLAS
520008	5	CAAGUAZU	20	VAQUERIA	1	8	SAN JORGE
520100	5	CAAGUAZU	20	VAQUERIA	6	100	NARANJITO
520120	5	CAAGUAZU	20	VAQUERIA	6	120	YERUTI
520130	5	CAAGUAZU	20	VAQUERIA	6	130	PIRA VERA
520140	5	CAAGUAZU	20	VAQUERIA	6	140	PALOMARES
520170	5	CAAGUAZU	20	VAQUERIA	6	170	TEKOJOJA 3RA LINEA
520180	5	CAAGUAZU	20	VAQUERIA	6	180	POROMBO
520190	5	CAAGUAZU	20	VAQUERIA	6	190	SANTO DOMINGO
520210	5	CAAGUAZU	20	VAQUERIA	6	210	TEKOJOJA 2DA LINEA
520220	5	CAAGUAZU	20	VAQUERIA	6	220	TEKOJOJA 1RA LINEA
520230	5	CAAGUAZU	20	VAQUERIA	6	230	KURUSU'I
520250	5	CAAGUAZU	20	VAQUERIA	6	250	SANTA ELENA
520260	5	CAAGUAZU	20	VAQUERIA	6	260	VIRGEN DE FATIMA
520270	5	CAAGUAZU	20	VAQUERIA	6	270	SAN JOSE
520280	5	CAAGUAZU	20	VAQUERIA	6	280	SAN BLAS
520290	5	CAAGUAZU	20	VAQUERIA	6	290	NUEVA BRASILIA
520300	5	CAAGUAZU	20	VAQUERIA	6	300	KAPI'I VEVE
520310	5	CAAGUAZU	20	VAQUERIA	6	310	MBOKAJA'I
520320	5	CAAGUAZU	20	VAQUERIA	6	320	SAN ANTONIO
520330	5	CAAGUAZU	20	VAQUERIA	6	330	8 DE DICIEMBRE
520340	5	CAAGUAZU	20	VAQUERIA	6	340	1RA LINEA MARACANA
520350	5	CAAGUAZU	20	VAQUERIA	6	350	SAN JUAN
520360	5	CAAGUAZU	20	VAQUERIA	6	360	SAN PEDRO
520370	5	CAAGUAZU	20	VAQUERIA	6	370	CANDELARIA 1RA FRACCION
520380	5	CAAGUAZU	20	VAQUERIA	6	380	CANDELARIA 2DA LINEA
520390	5	CAAGUAZU	20	VAQUERIA	6	390	ARROYO GUASU
520400	5	CAAGUAZU	20	VAQUERIA	6	400	COM INDIG MBOKAJA'I
520410	5	CAAGUAZU	20	VAQUERIA	6	410	COM INDIG YVYKUI JOVAI
520420	5	CAAGUAZU	20	VAQUERIA	6	420	SANTA CLARA
520430	5	CAAGUAZU	20	VAQUERIA	6	430	CRUCE MBURUVICHA
520440	5	CAAGUAZU	20	VAQUERIA	6	440	PARIRI
520450	5	CAAGUAZU	20	VAQUERIA	6	450	SAN JORGE
521001	5	CAAGUAZU	21	TEMBIAPORA	1	1	URBANO
521100	5	CAAGUAZU	21	TEMBIAPORA	6	100	KAPIATI
521110	5	CAAGUAZU	21	TEMBIAPORA	6	110	CABALLERIA
521120	5	CAAGUAZU	21	TEMBIAPORA	6	120	PINDO
521130	5	CAAGUAZU	21	TEMBIAPORA	6	130	SANTA LIBRADA
521140	5	CAAGUAZU	21	TEMBIAPORA	6	140	GUAHORY
521150	5	CAAGUAZU	21	TEMBIAPORA	6	150	BANDERITA 4 BOCAS
521160	5	CAAGUAZU	21	TEMBIAPORA	6	160	BANDERITA 8 DE DICIEMBRE
521170	5	CAAGUAZU	21	TEMBIAPORA	6	170	LA PALOMA
521180	5	CAAGUAZU	21	TEMBIAPORA	6	180	MARTILLO
521190	5	CAAGUAZU	21	TEMBIAPORA	6	190	SAN JORGE
521200	5	CAAGUAZU	21	TEMBIAPORA	6	200	GUAVIRA
521210	5	CAAGUAZU	21	TEMBIAPORA	6	210	TEMBIAPORA 2
521220	5	CAAGUAZU	21	TEMBIAPORA	6	220	Y HAI
521230	5	CAAGUAZU	21	TEMBIAPORA	6	230	YKUA PYTA
521240	5	CAAGUAZU	21	TEMBIAPORA	6	240	TEMBIAPORA
521250	5	CAAGUAZU	21	TEMBIAPORA	6	250	MONCHO KUE
522001	5	CAAGUAZU	22	NUEVA TOLEDO	1	1	URBANO
522100	5	CAAGUAZU	22	NUEVA TOLEDO	6	100	PALOMARES
522110	5	CAAGUAZU	22	NUEVA TOLEDO	6	110	COLONIA MARGARITA
522120	5	CAAGUAZU	22	NUEVA TOLEDO	6	120	ASENT. MIL PALOS
522130	5	CAAGUAZU	22	NUEVA TOLEDO	6	130	ASENT. GUYRA KEHA
522140	5	CAAGUAZU	22	NUEVA TOLEDO	6	140	COM INDIG YVY MOROTI
522150	5	CAAGUAZU	22	NUEVA TOLEDO	6	150	MONDAY
522160	5	CAAGUAZU	22	NUEVA TOLEDO	6	160	TOLEDO
522170	5	CAAGUAZU	22	NUEVA TOLEDO	6	170	CEDROTY
522180	5	CAAGUAZU	22	NUEVA TOLEDO	6	180	LAGUNA GUASU
522190	5	CAAGUAZU	22	NUEVA TOLEDO	6	190	COM INDIG YVU SANTA RITA
522200	5	CAAGUAZU	22	NUEVA TOLEDO	6	200	SAN JUAN
522210	5	CAAGUAZU	22	NUEVA TOLEDO	6	210	COM INDIG TEKOHA PORA
522220	5	CAAGUAZU	22	NUEVA TOLEDO	6	220	COM INDIG NU AVIJU
522230	5	CAAGUAZU	22	NUEVA TOLEDO	6	230	COM INDIG YPA'U TOLEDO
601001	6	CAAZAPA	1	CAAZAPA	1	1	SAN ANTONIO
601002	6	CAAZAPA	1	CAAZAPA	1	2	SANTA TERESITA
601003	6	CAAZAPA	1	CAAZAPA	1	3	SAN BLAS
601004	6	CAAZAPA	1	CAAZAPA	1	4	SAN ROQUE
601005	6	CAAZAPA	1	CAAZAPA	1	5	BRISAS
601006	6	CAAZAPA	1	CAAZAPA	1	6	SAN LUIS
601007	6	CAAZAPA	1	CAAZAPA	1	7	MARIA AUXILIADORA
601008	6	CAAZAPA	1	CAAZAPA	1	8	GALEANO KUE
601009	6	CAAZAPA	1	CAAZAPA	1	9	ASENT. ADOLFO ZARACHO
601010	6	CAAZAPA	1	CAAZAPA	1	10	ASENT. SAN RAFAEL
601011	6	CAAZAPA	1	CAAZAPA	1	11	CONAVI
601012	6	CAAZAPA	1	CAAZAPA	1	12	SAN RAMON
601100	6	CAAZAPA	1	CAAZAPA	6	100	PASO PINDO
601110	6	CAAZAPA	1	CAAZAPA	6	110	SANTA TERESITA
601120	6	CAAZAPA	1	CAAZAPA	6	120	SAN PEDRO
601130	6	CAAZAPA	1	CAAZAPA	6	130	ARASATY
601140	6	CAAZAPA	1	CAAZAPA	6	140	ROSARIO GUAVIRA
601160	6	CAAZAPA	1	CAAZAPA	6	160	JAULA KUE
601170	6	CAAZAPA	1	CAAZAPA	6	170	ROSARIO SARANDY
601180	6	CAAZAPA	1	CAAZAPA	6	180	ROSARIO TAKIRY
601210	6	CAAZAPA	1	CAAZAPA	6	210	SAN MIGUEL ONCE ESTRELLAS
601220	6	CAAZAPA	1	CAAZAPA	6	220	KAVAJU RETA
601230	6	CAAZAPA	1	CAAZAPA	6	230	GALEANO KUE
601240	6	CAAZAPA	1	CAAZAPA	6	240	JAHAPETY
601250	6	CAAZAPA	1	CAAZAPA	6	250	LOMA CLAVEL
601260	6	CAAZAPA	1	CAAZAPA	6	260	TAJY
601280	6	CAAZAPA	1	CAAZAPA	6	280	VISCAINO KUE
601290	6	CAAZAPA	1	CAAZAPA	6	290	COLONIA SAN COSME
601300	6	CAAZAPA	1	CAAZAPA	6	300	POTRERO GUASU
601310	6	CAAZAPA	1	CAAZAPA	6	310	SANTA CATALINA
601330	6	CAAZAPA	1	CAAZAPA	6	330	MANDUKUA
601340	6	CAAZAPA	1	CAAZAPA	6	340	ISLA GUASU
601350	6	CAAZAPA	1	CAAZAPA	6	350	NAUMBY
601360	6	CAAZAPA	1	CAAZAPA	6	360	NU PUAHUMI
601370	6	CAAZAPA	1	CAAZAPA	6	370	HUGUA GUASU
601380	6	CAAZAPA	1	CAAZAPA	6	380	ARROYO PORA
601390	6	CAAZAPA	1	CAAZAPA	6	390	SAN ANTONIO
601400	6	CAAZAPA	1	CAAZAPA	6	400	ALBADON
601410	6	CAAZAPA	1	CAAZAPA	6	410	KERA'Y HUGUA'I
601420	6	CAAZAPA	1	CAAZAPA	6	420	POTRERO SAN MARCOS
601430	6	CAAZAPA	1	CAAZAPA	6	430	POTRERO YVATE
601440	6	CAAZAPA	1	CAAZAPA	6	440	FATIMA
601450	6	CAAZAPA	1	CAAZAPA	6	450	NU PYAHU GUASU
601460	6	CAAZAPA	1	CAAZAPA	6	460	BOQUERON
601465	6	CAAZAPA	1	CAAZAPA	3	465	BOQUERON SUB-URBANO
601470	6	CAAZAPA	1	CAAZAPA	6	470	SAN IGNACIO
601480	6	CAAZAPA	1	CAAZAPA	6	480	SAN SALVADOR
601490	6	CAAZAPA	1	CAAZAPA	6	490	20 DE JULIO I
601500	6	CAAZAPA	1	CAAZAPA	6	500	TEREVO
601510	6	CAAZAPA	1	CAAZAPA	6	510	SAN FRANCISCO DE ASIS
601520	6	CAAZAPA	1	CAAZAPA	6	520	20 DE JULIO II
601530	6	CAAZAPA	1	CAAZAPA	6	530	TTE ROJAS SILVA
601540	6	CAAZAPA	1	CAAZAPA	6	540	ARROYO GUASU
602001	6	CAAZAPA	2	ABAI	1	1	SAN VALENTIN
602002	6	CAAZAPA	2	ABAI	1	2	SANTA ROSA
602003	6	CAAZAPA	2	ABAI	1	3	CENTRO
602004	6	CAAZAPA	2	ABAI	1	4	CORAZON DE JESUS
602100	6	CAAZAPA	2	ABAI	6	100	GOLONDRINA
602110	6	CAAZAPA	2	ABAI	6	110	SANTA TERESA
602120	6	CAAZAPA	2	ABAI	6	120	SAN ISIDRO MBYA
602130	6	CAAZAPA	2	ABAI	6	130	KUATI I
602140	6	CAAZAPA	2	ABAI	6	140	LIMA
602150	6	CAAZAPA	2	ABAI	6	150	MBOKAJA
602155	6	CAAZAPA	2	ABAI	6	155	TACUARA I
602160	6	CAAZAPA	2	ABAI	6	160	TUNA
602165	6	CAAZAPA	2	ABAI	3	165	TUNA SUB - URBANO
602180	6	CAAZAPA	2	ABAI	6	180	PLANTACION
602190	6	CAAZAPA	2	ABAI	6	190	KOKUE POTY
602200	6	CAAZAPA	2	ABAI	6	200	VILLA PASTOREO
602210	6	CAAZAPA	2	ABAI	6	210	ROSARIO PASTOREO
602230	6	CAAZAPA	2	ABAI	6	230	TARUMA
602240	6	CAAZAPA	2	ABAI	6	240	8 DE DICIEMBRE - TARUMA
602250	6	CAAZAPA	2	ABAI	6	250	LINEA KUNATAI
602260	6	CAAZAPA	2	ABAI	6	260	ORO KU'I
602265	6	CAAZAPA	2	ABAI	6	265	ASENT. ORO KU'I
602270	6	CAAZAPA	2	ABAI	6	270	CAMPO AZUL
602275	6	CAAZAPA	2	ABAI	6	275	RESTANTE
602280	6	CAAZAPA	2	ABAI	6	280	COM INDIG TAKUARUSU
602285	6	CAAZAPA	2	ABAI	6	285	COM INDIG KA'ATYMI
602290	6	CAAZAPA	2	ABAI	6	290	TUPARENDA
602295	6	CAAZAPA	2	ABAI	3	295	TUPARENDA SUB URBANO
602310	6	CAAZAPA	2	ABAI	6	310	LINEA 12
602320	6	CAAZAPA	2	ABAI	6	320	PAULISTA
602330	6	CAAZAPA	2	ABAI	6	330	ENTRE RIOS
602340	6	CAAZAPA	2	ABAI	6	340	EMILIANO RE
602350	6	CAAZAPA	2	ABAI	6	350	SAN JOSE CRISTAL
602355	6	CAAZAPA	2	ABAI	6	355	ASENT. KOEJU RORY
602360	6	CAAZAPA	2	ABAI	6	360	ARATIKU
602370	6	CAAZAPA	2	ABAI	6	370	CANTINA CUE
602380	6	CAAZAPA	2	ABAI	6	380	PIRAY
602390	6	CAAZAPA	2	ABAI	6	390	BORDA
602400	6	CAAZAPA	2	ABAI	6	400	2DA LINEA CRISTAL
602440	6	CAAZAPA	2	ABAI	6	440	SAN ROQUE
602450	6	CAAZAPA	2	ABAI	6	450	AMAMBAY
602455	6	CAAZAPA	2	ABAI	3	455	AMAMBAY SUB - URBANO
602470	6	CAAZAPA	2	ABAI	6	470	SAN JORGE
602475	6	CAAZAPA	2	ABAI	6	475	SAN ANTONIO
602480	6	CAAZAPA	2	ABAI	6	480	SANTA MARGARITA
602490	6	CAAZAPA	2	ABAI	6	490	TACUARATY
602500	6	CAAZAPA	2	ABAI	6	500	KM 7 GASPAR CUE
602510	6	CAAZAPA	2	ABAI	6	510	SANTA CATALINA
602520	6	CAAZAPA	2	ABAI	6	520	KAPI'ITINDY
602525	6	CAAZAPA	2	ABAI	3	525	KAPI'ITINDY SUB - URBANO
602530	6	CAAZAPA	2	ABAI	6	530	KM 10
602540	6	CAAZAPA	2	ABAI	6	540	SAN LORENZO
602550	6	CAAZAPA	2	ABAI	6	550	PRIMERA LINEA TARUMA
602560	6	CAAZAPA	2	ABAI	6	560	SAN MARCOS
602570	6	CAAZAPA	2	ABAI	6	570	MARIA AUXILIADORA
602580	6	CAAZAPA	2	ABAI	6	580	TORIN SAN ANTONIO
602585	6	CAAZAPA	2	ABAI	6	585	TORIN SAN PABLO
602590	6	CAAZAPA	2	ABAI	6	590	SAN VALENTIN
602600	6	CAAZAPA	2	ABAI	6	600	SANTA CRUZ
602610	6	CAAZAPA	2	ABAI	6	610	SAN MIGUEL
602620	6	CAAZAPA	2	ABAI	6	620	COLONIA MICHIMI
602630	6	CAAZAPA	2	ABAI	6	630	CAMPITO
602650	6	CAAZAPA	2	ABAI	6	650	SAN JUAN
602660	6	CAAZAPA	2	ABAI	6	660	SAN JOSE FARINA
602670	6	CAAZAPA	2	ABAI	6	670	KM 16 SAN BLAS
602680	6	CAAZAPA	2	ABAI	6	680	ARROYO MOROTI
602690	6	CAAZAPA	2	ABAI	6	690	COM INDIG YPETIMI
602700	6	CAAZAPA	2	ABAI	6	700	COM INDIG YVYTYMI
602720	6	CAAZAPA	2	ABAI	6	720	COM INDIG CERRITO
602730	6	CAAZAPA	2	ABAI	6	730	MBOIY
602740	6	CAAZAPA	2	ABAI	6	740	COM INDIG YPET?� - TAJY
602750	6	CAAZAPA	2	ABAI	6	750	COM INDIG YTU
602760	6	CAAZAPA	2	ABAI	6	760	COM INDIG NU APU'A
602770	6	CAAZAPA	2	ABAI	6	770	DURAZNO
602780	6	CAAZAPA	2	ABAI	6	780	SAN VICENTE KM 9
602790	6	CAAZAPA	2	ABAI	6	790	POTRERO SAN JUAN
602800	6	CAAZAPA	2	ABAI	6	800	COM INDIG YKUA PORA
602810	6	CAAZAPA	2	ABAI	6	810	COM INDIG CAMPITO KURUKAU
602820	6	CAAZAPA	2	ABAI	6	820	COM INDIG YPET?� - NARA'I
602830	6	CAAZAPA	2	ABAI	6	830	COM INDIG CECINA TEKOHA PYAHU
603001	6	CAAZAPA	3	BUENA VISTA	1	1	SAN ANTONIO
603002	6	CAAZAPA	3	BUENA VISTA	1	2	SAN LUIS
603003	6	CAAZAPA	3	BUENA VISTA	1	3	CRISTO REY
603004	6	CAAZAPA	3	BUENA VISTA	1	4	CORAZON DE JESUS
603005	6	CAAZAPA	3	BUENA VISTA	1	5	SAN ISIDRO
603006	6	CAAZAPA	3	BUENA VISTA	1	6	ASENT. DON VICTORIANO
603100	6	CAAZAPA	3	BUENA VISTA	6	100	SANTA ROSA
603110	6	CAAZAPA	3	BUENA VISTA	6	110	SAN RAMON
603120	6	CAAZAPA	3	BUENA VISTA	6	120	SAN ANTONIO
603130	6	CAAZAPA	3	BUENA VISTA	6	130	SAN AGUSTIN
603140	6	CAAZAPA	3	BUENA VISTA	6	140	TORRES KUE
603150	6	CAAZAPA	3	BUENA VISTA	6	150	JEROVIA
603170	6	CAAZAPA	3	BUENA VISTA	6	170	MIRANDA KUE
603200	6	CAAZAPA	3	BUENA VISTA	6	200	RECEPTOR KUE
603210	6	CAAZAPA	3	BUENA VISTA	6	210	YAPEPO
603220	6	CAAZAPA	3	BUENA VISTA	6	220	ASENT. MIRANDA KUE
604001	6	CAAZAPA	4	DR. MOISES S. BERTONI	1	1	URBANO
604100	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	100	CORRALITO SAN MIGUEL
604105	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	105	ASENT. CORRALITO SAN MIGUEL
604110	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	110	CORRALITO SAN RAMON
604120	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	120	MOISES BERTONI
604130	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	130	ROGELIO BENITEZ
604140	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	140	SANTA CECILIA
604150	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	150	SAN CARLOS
604160	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	160	SANTA TERESA
604180	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	180	LOMA'I
604190	6	CAAZAPA	4	DR. MOISES S. BERTONI	6	190	COM INDIG SOSA
605001	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	1	1	SAN ANTONIO
605002	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	1	2	SAN JOSE
605003	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	1	3	SAN MARCOS
605100	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	100	SAN ANTONIO
605130	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	130	GUYRA KEHA
605150	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	150	COSTA FLORIDA
605170	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	170	SANTA MARIA
605180	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	180	PINDOJU
605190	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	190	SAN ESTANISLAO
605200	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	200	SAN RAMON
605210	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	210	DURAZNO
605230	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	230	PATINO
605240	6	CAAZAPA	5	GRAL. HIGINIO MORINIGO	6	240	8 DE DICIEMBRE
606001	6	CAAZAPA	6	MACIEL	1	1	URBANO
606100	6	CAAZAPA	6	MACIEL	6	100	VALOIS RIVAROLA
606110	6	CAAZAPA	6	MACIEL	6	110	ZANJA PYTA
606120	6	CAAZAPA	6	MACIEL	6	120	KARAKARA'I
606130	6	CAAZAPA	6	MACIEL	6	130	SAN FRANCISCO MI
606140	6	CAAZAPA	6	MACIEL	6	140	SAN MIGUEL
606160	6	CAAZAPA	6	MACIEL	6	160	SOLALINDE
606180	6	CAAZAPA	6	MACIEL	6	180	COSTA DULCE
606190	6	CAAZAPA	6	MACIEL	6	190	POTRERO
606200	6	CAAZAPA	6	MACIEL	6	200	KURUSU ISABEL
607001	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	1	SAN LUIS
607002	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	2	SAN VICENTE
607003	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	3	CENTRAL
607004	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	4	SAN JOSE
607005	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	5	SAGRADO CORAZON DE JESUS
607006	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	6	SAN AGUSTIN
607007	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	7	ASENT. JORGE DE LA CUEVA
607008	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	8	SAN CAYETANO
607009	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	9	ASENT. 8 DE DICIEMBRE
607010	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	10	ASENT. TAVA JOAJU RENDA
607011	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	11	ASENT. ARTEMIO ROJAS
607012	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	1	12	VIRGEN DE FATIMA
607100	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	100	SAN FRANCISCO
607105	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	3	105	SAN FRANCISCO- SUB URBANO
607110	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	110	CARAGUATA
607150	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	150	ZANJA PYTA
607160	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	160	NU PYAHU
607165	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	165	ASENT. MARIA AUXILIADORA
607180	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	180	KAVAJU AKANGUE
607185	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	185	ASENT. BARRERO PYTA
607190	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	190	ASENT. SANTA LIBRADA
607200	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	200	SAN CRISTOBAL
607210	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	210	POTRERO
607220	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	220	HUGUA PUKU
607230	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	230	NURUNDAY
607240	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	240	ISLA YOBAI
607250	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	250	CARIDAD
607260	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	260	SAN ROQUE
607270	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	270	PINDO
607300	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	300	CIERVO KUA
607305	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	305	COM INDIG TAKUARO
607310	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	310	NUMI
607330	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	330	KAUNDYGUE
607340	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	340	POTRERO SAN MIGUEL
607350	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	350	SANTA LIBRADA
607360	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	360	VIJU
607370	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	370	LORITO
607380	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	380	ASENT. MANDU'ARA
607390	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	390	YPANE
607400	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	400	SAN RAFAEL
607410	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	410	ARAKANGUY
607420	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	420	TEBICUARYMI
607430	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	430	SAN ISIDRO
607440	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	440	SAN BLAS
607450	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	450	SAN CARLOS
607455	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	3	455	SAN CARLOS-SUB URBANO
607460	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	460	TAPYTA
607470	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	470	EMPALADOS
607490	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	490	NAHU
607500	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	500	POTRERO SANTIAGO
607510	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	510	YVU
607520	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	520	SAN IGNACIO
607530	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	530	MARIA AUXILIADORA
607540	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	540	HAKUVO
607550	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	550	SAN GERARDO
607560	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	560	SAN RAMON
607570	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	570	SAN MIGUEL
607580	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	580	KILOMETRO 14
607590	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	590	SAN MARCOS
607600	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	600	ISLA JOVAIMI
607610	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	610	CERRITO
607620	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	620	ASENT. 11 DE MAYO
607630	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	630	SAN LORENZO
607640	6	CAAZAPA	7	SAN JUAN NEPOMUCENO	6	640	COM INDIG POTRERO 26 DE JUNIO
608001	6	CAAZAPA	8	TAVAI	1	1	SAN ANTONIO
608002	6	CAAZAPA	8	TAVAI	1	2	SAN ROQUE
608003	6	CAAZAPA	8	TAVAI	1	3	LAS MERCEDES
608100	6	CAAZAPA	8	TAVAI	6	100	MARIA AUXILIADORA
608120	6	CAAZAPA	8	TAVAI	6	120	APEPU
608130	6	CAAZAPA	8	TAVAI	6	130	SANTA ANA
608140	6	CAAZAPA	8	TAVAI	6	140	SAN FRANCISCO
608150	6	CAAZAPA	8	TAVAI	6	150	SAN AGUSTIN
608160	6	CAAZAPA	8	TAVAI	6	160	PINDO TAVAI
608170	6	CAAZAPA	8	TAVAI	6	170	ENRAMADITA
608180	6	CAAZAPA	8	TAVAI	6	180	ATONGUE
608200	6	CAAZAPA	8	TAVAI	6	200	VALLE'I
608210	6	CAAZAPA	8	TAVAI	6	210	VIRGEN DEL CARMEN
608220	6	CAAZAPA	8	TAVAI	6	220	SAN GABRIEL
608230	6	CAAZAPA	8	TAVAI	6	230	RIVAS KUE
608240	6	CAAZAPA	8	TAVAI	6	240	SAN RAMON
608250	6	CAAZAPA	8	TAVAI	6	250	YVYHATY
608260	6	CAAZAPA	8	TAVAI	6	260	NU KANY
608270	6	CAAZAPA	8	TAVAI	6	270	ITA ANGU'A
608280	6	CAAZAPA	8	TAVAI	6	280	CASTOR KUE
608290	6	CAAZAPA	8	TAVAI	6	290	TAVA PORA
608310	6	CAAZAPA	8	TAVAI	6	310	CAAZAPAMI
608320	6	CAAZAPA	8	TAVAI	6	320	YVYTY KORA
608330	6	CAAZAPA	8	TAVAI	6	330	SAN ROQUE
608340	6	CAAZAPA	8	TAVAI	6	340	ORO VERDE
608360	6	CAAZAPA	8	TAVAI	6	360	NU PYAHU
608380	6	CAAZAPA	8	TAVAI	6	380	VILLA UNIDA
608390	6	CAAZAPA	8	TAVAI	6	390	MBOIRY
608400	6	CAAZAPA	8	TAVAI	6	400	MARIA NUEVA ESPERANZA
608410	6	CAAZAPA	8	TAVAI	6	410	TEMBIAPO RENDA
608420	6	CAAZAPA	8	TAVAI	6	420	CERRO CORA
608440	6	CAAZAPA	8	TAVAI	6	440	TORO BLANCO
608450	6	CAAZAPA	8	TAVAI	6	450	TITO FIRPO
608460	6	CAAZAPA	8	TAVAI	6	460	COM INDIG KOKUERE GUAZU
608465	6	CAAZAPA	8	TAVAI	6	465	COM INDIG KOKUERE GUAZU- SEXTA LINEA
608470	6	CAAZAPA	8	TAVAI	6	470	ASENT. PACURI 2
608480	6	CAAZAPA	8	TAVAI	6	480	LA AMISTAD
608490	6	CAAZAPA	8	TAVAI	6	490	ARROYO CLARO
608500	6	CAAZAPA	8	TAVAI	6	500	8 DE DICIEMBRE
608510	6	CAAZAPA	8	TAVAI	6	510	TORANZO
608520	6	CAAZAPA	8	TAVAI	6	520	COM INDIG TUNA ARROYO GUASU
608530	6	CAAZAPA	8	TAVAI	6	530	COM INDIG TAJAY PAKURI
608540	6	CAAZAPA	8	TAVAI	6	540	COM INDIG PACURI  CASTOR CUE
608550	6	CAAZAPA	8	TAVAI	6	550	YVY ATY
608560	6	CAAZAPA	8	TAVAI	6	560	COM INDIG KARUMBEY
608570	6	CAAZAPA	8	TAVAI	3	570	ENRAMADITA SUB - URBANO
608580	6	CAAZAPA	8	TAVAI	6	580	COM INDIG JUKERI - CERRO SEIS
608585	6	CAAZAPA	8	TAVAI	6	585	COM INDIG JUKERI
608590	6	CAAZAPA	8	TAVAI	6	590	CRUCE PAKURI
608600	6	CAAZAPA	8	TAVAI	6	600	COM INDIG JUKERI - TUNA'I
608610	6	CAAZAPA	8	TAVAI	6	610	SAN JORGE
608620	6	CAAZAPA	8	TAVAI	6	620	ASENT. 3 DE JUNIO TRANSLUMBRE
608640	6	CAAZAPA	8	TAVAI	6	640	SAN JOSE YVYVO
608650	6	CAAZAPA	8	TAVAI	6	650	PRIMERO DE MARZO
608660	6	CAAZAPA	8	TAVAI	6	660	COM INDIG JUKERY-ARROZ TYGUE
608680	6	CAAZAPA	8	TAVAI	6	680	ASENT. 7 DE DICIEMBRE
608690	6	CAAZAPA	8	TAVAI	6	690	COM INDIG KA'AGUY PA'U
608700	6	CAAZAPA	8	TAVAI	3	700	TEMBIAPO RENDA SUB URBANO
608710	6	CAAZAPA	8	TAVAI	3	710	TITO FIRPO-SUB URBANO
608720	6	CAAZAPA	8	TAVAI	6	720	LOS CEDROS
608730	6	CAAZAPA	8	TAVAI	6	730	COM INDIG RIVAS CUE
608740	6	CAAZAPA	8	TAVAI	6	740	COM INDIG JUKERI - KARANDA
608750	6	CAAZAPA	8	TAVAI	6	750	COM INDIG VIJU
608760	6	CAAZAPA	8	TAVAI	6	760	CERRO GUY
608770	6	CAAZAPA	8	TAVAI	6	770	COM INDIG VY'A RENDA
608780	6	CAAZAPA	8	TAVAI	6	780	COM INDIG YVY PYTA
608790	6	CAAZAPA	8	TAVAI	6	790	COM INDIG KA'AMINDY
608800	6	CAAZAPA	8	TAVAI	6	800	COM INDIG CERRO PE JUKERI
608810	6	CAAZAPA	8	TAVAI	6	810	COM INDIG GUYRA HUGUA JUKERI
608820	6	CAAZAPA	8	TAVAI	6	820	COM INDIG KA'AGUY PORA JUKERI
609001	6	CAAZAPA	9	YEGROS	1	1	SAN SINFORIANO
609002	6	CAAZAPA	9	YEGROS	1	2	SAN LUIS
609003	6	CAAZAPA	9	YEGROS	1	3	CAACUPE
609004	6	CAAZAPA	9	YEGROS	1	4	FATIMA
609100	6	CAAZAPA	9	YEGROS	6	100	COSTA LIMA
609110	6	CAAZAPA	9	YEGROS	6	110	SAN RAFAEL
609120	6	CAAZAPA	9	YEGROS	6	120	PUNTA GUASU
609130	6	CAAZAPA	9	YEGROS	6	130	PINDOJU
609140	6	CAAZAPA	9	YEGROS	6	140	YVYRA KATU
609150	6	CAAZAPA	9	YEGROS	6	150	ISLA MBOHAPY
609160	6	CAAZAPA	9	YEGROS	6	160	MBARIGUI
609170	6	CAAZAPA	9	YEGROS	6	170	ISLA SAKA
609200	6	CAAZAPA	9	YEGROS	6	200	PUESTO NARANJO
609210	6	CAAZAPA	9	YEGROS	6	210	GENERAL COLMAN
609220	6	CAAZAPA	9	YEGROS	6	220	PASO PIRAPO
609230	6	CAAZAPA	9	YEGROS	6	230	COM INDIG ISLA MBOREVI
610001	6	CAAZAPA	10	YUTY	1	1	MARIA GORETTI
610002	6	CAAZAPA	10	YUTY	1	2	SANTO DOMINGO
610003	6	CAAZAPA	10	YUTY	1	3	SAN LUIS
610004	6	CAAZAPA	10	YUTY	1	4	SANTA INES
610005	6	CAAZAPA	10	YUTY	1	5	SAN ROQUE
610150	6	CAAZAPA	10	YUTY	6	150	AVAY
610160	6	CAAZAPA	10	YUTY	6	160	KURUPI
610170	6	CAAZAPA	10	YUTY	6	170	TAKUAREMBOY
610180	6	CAAZAPA	10	YUTY	6	180	SAN GERONIMO
610400	6	CAAZAPA	10	YUTY	6	400	CANADA SAN JOSE
610410	6	CAAZAPA	10	YUTY	6	410	TYPYCHATY
610420	6	CAAZAPA	10	YUTY	6	420	JAKURA'A
610430	6	CAAZAPA	10	YUTY	6	430	AZAME KUE
610440	6	CAAZAPA	10	YUTY	6	440	ARA RUPE
610450	6	CAAZAPA	10	YUTY	6	450	AGUARAY GUASU
610460	6	CAAZAPA	10	YUTY	6	460	JAGUARETE KORA
610470	6	CAAZAPA	10	YUTY	6	470	SAN MIGUEL
610480	6	CAAZAPA	10	YUTY	6	480	JARATI'I
610490	6	CAAZAPA	10	YUTY	6	490	SAN ANTONIO
610500	6	CAAZAPA	10	YUTY	6	500	GUASU KAI
610510	6	CAAZAPA	10	YUTY	6	510	CAPILLA LOMA
610520	6	CAAZAPA	10	YUTY	6	520	TIRIRI SAN ANTONIO
610530	6	CAAZAPA	10	YUTY	6	530	ITA ANGU'A
610540	6	CAAZAPA	10	YUTY	6	540	SAN JUAN
610545	6	CAAZAPA	10	YUTY	6	545	SAN GABRIEL
610550	6	CAAZAPA	10	YUTY	6	550	SANTA BARBARA
610560	6	CAAZAPA	10	YUTY	6	560	SANTA ROSA DE LIMA
610570	6	CAAZAPA	10	YUTY	6	570	ESTACION SAN LORENZO
610580	6	CAAZAPA	10	YUTY	6	580	ESTACION YUTY
610590	6	CAAZAPA	10	YUTY	6	590	SAN ISIDRO
610610	6	CAAZAPA	10	YUTY	6	610	SAN JUAN LOMA
610620	6	CAAZAPA	10	YUTY	6	620	POTRERO
610630	6	CAAZAPA	10	YUTY	6	630	ASENT. 26 DE DICIEMBRE
610660	6	CAAZAPA	10	YUTY	6	660	VAZQUEZ
610680	6	CAAZAPA	10	YUTY	6	680	SAN VICENTE
610690	6	CAAZAPA	10	YUTY	6	690	COM INDIG MONTE ALTO
610700	6	CAAZAPA	10	YUTY	6	700	COM INDIG YKUA POTY
611001	6	CAAZAPA	11	3 DE MAYO	1	1	URBANO
611100	6	CAAZAPA	11	3 DE MAYO	6	100	JEROVIA
611110	6	CAAZAPA	11	3 DE MAYO	6	110	SAN FRANCISCO
611120	6	CAAZAPA	11	3 DE MAYO	6	120	VERA KUE
611130	6	CAAZAPA	11	3 DE MAYO	6	130	YATAITY
611150	6	CAAZAPA	11	3 DE MAYO	6	150	NUEVA COLONIA
611160	6	CAAZAPA	11	3 DE MAYO	6	160	CERRITO
611170	6	CAAZAPA	11	3 DE MAYO	6	170	SANTA URSULA
611180	6	CAAZAPA	11	3 DE MAYO	6	180	CERRO YVU
611190	6	CAAZAPA	11	3 DE MAYO	6	190	CANADA SAN JOSE
611200	6	CAAZAPA	11	3 DE MAYO	6	200	RINCON
611210	6	CAAZAPA	11	3 DE MAYO	6	210	POTRERO ANTEOJO
611220	6	CAAZAPA	11	3 DE MAYO	6	220	KA'A KARAPA
611230	6	CAAZAPA	11	3 DE MAYO	6	230	ASENT. SANTA RITA
611240	6	CAAZAPA	11	3 DE MAYO	6	240	ASENT. SAN ANTONIO
611250	6	CAAZAPA	11	3 DE MAYO	6	250	KUARAHY RESE
611260	6	CAAZAPA	11	3 DE MAYO	6	260	BUENA VISTA
611270	6	CAAZAPA	11	3 DE MAYO	6	270	TUPILANDIA
611280	6	CAAZAPA	11	3 DE MAYO	6	280	KAPI'ITINDY
611290	6	CAAZAPA	11	3 DE MAYO	6	290	TATUKUA
611300	6	CAAZAPA	11	3 DE MAYO	6	300	POTRERO YVATE
611310	6	CAAZAPA	11	3 DE MAYO	6	310	GASORY
611320	6	CAAZAPA	11	3 DE MAYO	6	320	3 DE MAYO
611330	6	CAAZAPA	11	3 DE MAYO	6	330	AYALA KUE
611340	6	CAAZAPA	11	3 DE MAYO	6	340	LOMITA
611350	6	CAAZAPA	11	3 DE MAYO	6	350	YTORORO
611360	6	CAAZAPA	11	3 DE MAYO	6	360	ARGANA KUE
611370	6	CAAZAPA	11	3 DE MAYO	6	370	UNION AGRICOLA
611375	6	CAAZAPA	11	3 DE MAYO	3	375	UNION AGRICOLA SUB-URBANO
611380	6	CAAZAPA	11	3 DE MAYO	6	380	SARGENTO POTRERO
611390	6	CAAZAPA	11	3 DE MAYO	6	390	DOLORES
611400	6	CAAZAPA	11	3 DE MAYO	6	400	LIMA
701001	7	ITAPUA	1	ENCARNACION	1	1	BOQUERON
701002	7	ITAPUA	1	ENCARNACION	1	2	INMACULADA CONCEPCION
701003	7	ITAPUA	1	ENCARNACION	1	3	ZONA ALTA
701004	7	ITAPUA	1	ENCARNACION	1	4	LA VICTORIA
701005	7	ITAPUA	1	ENCARNACION	1	5	OBRERO
701006	7	ITAPUA	1	ENCARNACION	1	6	SAN ROQUE GONZALEZ
701007	7	ITAPUA	1	ENCARNACION	1	7	PACU KUA
701008	7	ITAPUA	1	ENCARNACION	1	8	CARLOS ANTONIO LOPEZ
701009	7	ITAPUA	1	ENCARNACION	1	9	LA CATEDRAL
701010	7	ITAPUA	1	ENCARNACION	1	10	SAN BLAS
701011	7	ITAPUA	1	ENCARNACION	1	11	HOSPITAL
701012	7	ITAPUA	1	ENCARNACION	1	12	CIUDAD NUEVA
701013	7	ITAPUA	1	ENCARNACION	1	13	SANTA ROSA
701014	7	ITAPUA	1	ENCARNACION	1	14	PADRE BOLIK
701015	7	ITAPUA	1	ENCARNACION	1	15	GENERAL BERNARDINO CABALLERO
701016	7	ITAPUA	1	ENCARNACION	1	16	LA PAZ
701017	7	ITAPUA	1	ENCARNACION	1	17	ASENT. NUEVA ESPERANZA
701018	7	ITAPUA	1	ENCARNACION	1	18	VILLA CANDIDA
701019	7	ITAPUA	1	ENCARNACION	1	19	POTIY
701020	7	ITAPUA	1	ENCARNACION	1	20	BUENA VISTA
701021	7	ITAPUA	1	ENCARNACION	1	21	MBOI KA'E
701022	7	ITAPUA	1	ENCARNACION	1	22	KENNEDY
701023	7	ITAPUA	1	ENCARNACION	1	23	MARIA AUXILIADORA
701024	7	ITAPUA	1	ENCARNACION	1	24	KA'AGUY RORY
701025	7	ITAPUA	1	ENCARNACION	1	25	QUITERIA
701026	7	ITAPUA	1	ENCARNACION	1	26	SAN PEDRO
701027	7	ITAPUA	1	ENCARNACION	1	27	SAN ISIDRO 1
701028	7	ITAPUA	1	ENCARNACION	1	28	NUEVA ESPERANZA
701029	7	ITAPUA	1	ENCARNACION	1	29	SAGRADA FAMILIA
701030	7	ITAPUA	1	ENCARNACION	1	30	FATIMA
701031	7	ITAPUA	1	ENCARNACION	1	31	SAN ISIDRO ETAPA 2
701032	7	ITAPUA	1	ENCARNACION	1	32	SAN ISIDRO ETAPA 3
701033	7	ITAPUA	1	ENCARNACION	1	33	SAN ISIDRO ETAPA 5
701034	7	ITAPUA	1	ENCARNACION	1	34	SAN ISIDRO ETAPA 1
701035	7	ITAPUA	1	ENCARNACION	1	35	SAN ISIDRO ETAPA 6
701036	7	ITAPUA	1	ENCARNACION	1	36	SAN ISIDRO ETAPA 8
701037	7	ITAPUA	1	ENCARNACION	1	37	SAN ISIDRO 3
701038	7	ITAPUA	1	ENCARNACION	1	38	SAN ISIDRO ETAPA 7
701039	7	ITAPUA	1	ENCARNACION	1	39	SAN PEDRO KURUPAYTY
701040	7	ITAPUA	1	ENCARNACION	1	40	CURUPAYTY
701041	7	ITAPUA	1	ENCARNACION	1	41	SAN ANTONIO
701042	7	ITAPUA	1	ENCARNACION	1	42	CHAIPE
701043	7	ITAPUA	1	ENCARNACION	1	43	JUAN LEON MALLORQUIN
701120	7	ITAPUA	1	ENCARNACION	6	120	CHAIPE
701130	7	ITAPUA	1	ENCARNACION	6	130	SANTA MARIA SANTILLAN
701140	7	ITAPUA	1	ENCARNACION	6	140	ITA ANGU'A
701145	7	ITAPUA	1	ENCARNACION	3	145	ITA ANGU'A SUB-URBANO
701150	7	ITAPUA	1	ENCARNACION	6	150	SAN JOSE OBRERO
701160	7	ITAPUA	1	ENCARNACION	6	160	CERRITO
701170	7	ITAPUA	1	ENCARNACION	3	170	ITA PASO SUB-URBANO
701180	7	ITAPUA	1	ENCARNACION	6	180	SANTO DOMINGO
701185	7	ITAPUA	1	ENCARNACION	3	185	SANTO DOMINGO SUB-URBANO
701190	7	ITAPUA	1	ENCARNACION	6	190	SAN LUIS
701200	7	ITAPUA	1	ENCARNACION	6	200	URU SAPUKAI
701210	7	ITAPUA	1	ENCARNACION	6	210	SAN ANTONIO
701220	7	ITAPUA	1	ENCARNACION	6	220	4 POTRERO
701230	7	ITAPUA	1	ENCARNACION	6	230	SAN ISIDRO
701240	7	ITAPUA	1	ENCARNACION	6	240	NUEVA UCRANIA
701250	7	ITAPUA	1	ENCARNACION	6	250	ARROYO PORA
701260	7	ITAPUA	1	ENCARNACION	6	260	CURUPAYTY
701270	7	ITAPUA	1	ENCARNACION	6	270	SANTA ELENA
701280	7	ITAPUA	1	ENCARNACION	3	280	ARROYO PORA SUB-URBANO
701300	7	ITAPUA	1	ENCARNACION	6	300	SAN ISIDRO 2
701320	7	ITAPUA	1	ENCARNACION	6	320	LOS ARRAVALES
701325	7	ITAPUA	1	ENCARNACION	3	325	LOS ARRAVALES  SUB-URBANO
701330	7	ITAPUA	1	ENCARNACION	6	330	8 DE DICIEMBRE
701335	7	ITAPUA	1	ENCARNACION	3	335	8 DE DICIEMBRE SUB-URBANO
701340	7	ITAPUA	1	ENCARNACION	6	340	ASENT. SAN ANTONIO
701350	7	ITAPUA	1	ENCARNACION	6	350	COM INDIG MAKA(ITA PASO)
701360	7	ITAPUA	1	ENCARNACION	6	360	PARAISO
701365	7	ITAPUA	1	ENCARNACION	3	365	PARAISO SUB-URBANO
701370	7	ITAPUA	1	ENCARNACION	3	370	SANTA  CRUZ SUB-URBANO
701385	7	ITAPUA	1	ENCARNACION	3	385	CONAVI KILOMETRO 10  SUB-URBANO
701390	7	ITAPUA	1	ENCARNACION	6	390	LAS DELICIAS
701395	7	ITAPUA	1	ENCARNACION	3	395	LAS DELICIAS SUB-URBANO
701400	7	ITAPUA	1	ENCARNACION	6	400	SAN RAFAEL
701415	7	ITAPUA	1	ENCARNACION	3	415	CONAVI SANTO DOMINGO SUB-URBANO
702001	7	ITAPUA	2	BELLA VISTA	1	1	8 DE DICIEMBRE
702002	7	ITAPUA	2	BELLA VISTA	1	2	NINO JESUS
702004	7	ITAPUA	2	BELLA VISTA	1	4	OBRERO
702005	7	ITAPUA	2	BELLA VISTA	1	5	SIGFRIDO TISCHLER
702006	7	ITAPUA	2	BELLA VISTA	1	6	SAN PABLO
702007	7	ITAPUA	2	BELLA VISTA	1	7	INMIGRANTES
702008	7	ITAPUA	2	BELLA VISTA	1	8	BUENA VISTA
702009	7	ITAPUA	2	BELLA VISTA	1	9	UNION
702010	7	ITAPUA	2	BELLA VISTA	1	10	EMILIANO R FERNANDEZ
702011	7	ITAPUA	2	BELLA VISTA	1	11	SAN BLAS
702012	7	ITAPUA	2	BELLA VISTA	1	12	SAN JUAN
702013	7	ITAPUA	2	BELLA VISTA	1	13	15 DE MAYO
702014	7	ITAPUA	2	BELLA VISTA	1	14	NUEVA ESPERANZA
702015	7	ITAPUA	2	BELLA VISTA	1	15	PARQUE
702100	7	ITAPUA	2	BELLA VISTA	6	100	ZONA VACAY 40
702110	7	ITAPUA	2	BELLA VISTA	6	110	ZONA VACAY 33
702120	7	ITAPUA	2	BELLA VISTA	6	120	ZONA VACAY 30
702130	7	ITAPUA	2	BELLA VISTA	6	130	ZONA VACAY 20
702140	7	ITAPUA	2	BELLA VISTA	6	140	ZONA VACAY 15
702150	7	ITAPUA	2	BELLA VISTA	6	150	ZONA VACAY 10 Y 13
702160	7	ITAPUA	2	BELLA VISTA	6	160	ZONA AKA KARAJA
702170	7	ITAPUA	2	BELLA VISTA	6	170	ZONA SANTA CLARA, VACAY 2 Y 4
702180	7	ITAPUA	2	BELLA VISTA	6	180	ZONA UNION Y FORDI 2
702190	7	ITAPUA	2	BELLA VISTA	6	190	ZONA BELLA VISTA 1
702200	7	ITAPUA	2	BELLA VISTA	6	200	ZONA FORDI 5 Y 8
702210	7	ITAPUA	2	BELLA VISTA	6	210	ZONA LA TRINIDAD Y FORDI 15
702215	7	ITAPUA	2	BELLA VISTA	3	215	MARIA AUXILIADORA SUB URBANO
702220	7	ITAPUA	2	BELLA VISTA	6	220	ZONA FORDI 18
702230	7	ITAPUA	2	BELLA VISTA	6	230	ZONA BELLA VISTA 3
703001	7	ITAPUA	3	CAMBYRETA	1	1	CAMBYRETA CENTRO
703002	7	ITAPUA	3	CAMBYRETA	1	2	SAN MIGUEL KURUSU
703003	7	ITAPUA	3	CAMBYRETA	1	3	SAN BLAS
703004	7	ITAPUA	3	CAMBYRETA	1	4	COSTAS DEL POTIY
703005	7	ITAPUA	3	CAMBYRETA	1	5	VALDEZ
703006	7	ITAPUA	3	CAMBYRETA	1	6	ASENT. SAN EUGENIO
703007	7	ITAPUA	3	CAMBYRETA	1	7	PARQUE INDEPENDENCIA
703008	7	ITAPUA	3	CAMBYRETA	1	8	SAN FRANCISCO
703009	7	ITAPUA	3	CAMBYRETA	1	9	SANTA LUCIA
703010	7	ITAPUA	3	CAMBYRETA	1	10	JARDIN
703011	7	ITAPUA	3	CAMBYRETA	1	11	SAN MIGUEL
703012	7	ITAPUA	3	CAMBYRETA	1	12	LAS MERCEDES
703013	7	ITAPUA	3	CAMBYRETA	1	13	SAN JUAN
703014	7	ITAPUA	3	CAMBYRETA	1	14	CARMELITAS
703015	7	ITAPUA	3	CAMBYRETA	1	15	ASENT. NUEVO AMANECER II
703016	7	ITAPUA	3	CAMBYRETA	1	16	SAN JOSE
703017	7	ITAPUA	3	CAMBYRETA	1	17	BARRIO ALEGRE
703018	7	ITAPUA	3	CAMBYRETA	1	18	LA AMISTAD
703019	7	ITAPUA	3	CAMBYRETA	1	19	SILVIO PETTIROSSI
703020	7	ITAPUA	3	CAMBYRETA	1	20	ESPIRITU SANTO
703021	7	ITAPUA	3	CAMBYRETA	1	21	LOS MAESTROS
703022	7	ITAPUA	3	CAMBYRETA	1	22	SAN RAFAEL
703220	7	ITAPUA	3	CAMBYRETA	6	220	SANTA LIBRADA
703240	7	ITAPUA	3	CAMBYRETA	6	240	SAN BLAS INDEPENDENCIA
703250	7	ITAPUA	3	CAMBYRETA	3	250	ARROYO PORA  SUB-URBANO
703260	7	ITAPUA	3	CAMBYRETA	6	260	BARRERO GUASU
703270	7	ITAPUA	3	CAMBYRETA	6	270	CAMPICHUELO
703280	7	ITAPUA	3	CAMBYRETA	6	280	COLONIA PARANA
703290	7	ITAPUA	3	CAMBYRETA	6	290	COLONIA CAMBYRETA
703300	7	ITAPUA	3	CAMBYRETA	6	300	ARROYO VERDE
703330	7	ITAPUA	3	CAMBYRETA	6	330	PADRE CARLOS WINKEL
703340	7	ITAPUA	3	CAMBYRETA	6	340	MBURIKA
703350	7	ITAPUA	3	CAMBYRETA	6	350	SAN RAMON
703360	7	ITAPUA	3	CAMBYRETA	6	360	ASENT. AMANECER III
704001	7	ITAPUA	4	CAPITAN MEZA	1	1	CAPITAN MEZA KILOMETRO 16 A
704002	7	ITAPUA	4	CAPITAN MEZA	1	2	SAN PABLO
704003	7	ITAPUA	4	CAPITAN MEZA	1	3	CAPITAN MEZA KILOMETRO 16 B
704004	7	ITAPUA	4	CAPITAN MEZA	1	4	ITAKUA
704005	7	ITAPUA	4	CAPITAN MEZA	1	5	NINO JESUS
704006	7	ITAPUA	4	CAPITAN MEZA	1	6	SAN MIGUEL
704007	7	ITAPUA	4	CAPITAN MEZA	1	7	CRISTO REY
704100	7	ITAPUA	4	CAPITAN MEZA	6	100	EDELIRA 1
704130	7	ITAPUA	4	CAPITAN MEZA	6	130	CAPITAN MEZA KILOMETRO 24
704140	7	ITAPUA	4	CAPITAN MEZA	6	140	CAPITAN MEZA KILOMETRO 32
704150	7	ITAPUA	4	CAPITAN MEZA	6	150	CAPITAN MEZA KILOMETRO 28 AL 36
704160	7	ITAPUA	4	CAPITAN MEZA	6	160	CAPITAN MEZA KILOMETRO 17 AL 21
704170	7	ITAPUA	4	CAPITAN MEZA	6	170	CAPITAN MEZA KILOMETRO 13 AL 15
704180	7	ITAPUA	4	CAPITAN MEZA	6	180	COLONIA NUEVA
704190	7	ITAPUA	4	CAPITAN MEZA	6	190	EDELIRA KILOMETRO 13 AL 20
704200	7	ITAPUA	4	CAPITAN MEZA	6	200	SANTA ROSA
704210	7	ITAPUA	4	CAPITAN MEZA	6	210	EDELIRA KILOMETRO 10 AL 12
704220	7	ITAPUA	4	CAPITAN MEZA	6	220	EDELIRA KILOMETRO 6 AL 9
704230	7	ITAPUA	4	CAPITAN MEZA	3	230	CAPITAN MEZA PUERTO-SUB URBANO
704240	7	ITAPUA	4	CAPITAN MEZA	6	240	CAPITAN MEZA KILOMETRO 1 AL 6
704250	7	ITAPUA	4	CAPITAN MEZA	6	250	CAPITAN MEZA KILOMETRO 7 AL 12
704260	7	ITAPUA	4	CAPITAN MEZA	6	260	EDELIRA KILOMETRO 1 AL 5
704270	7	ITAPUA	4	CAPITAN MEZA	6	270	COM INDIG ARROYO KORA
705001	7	ITAPUA	5	CAPITAN MIRANDA	1	1	FRACCION SOZONIC
705002	7	ITAPUA	5	CAPITAN MIRANDA	1	2	CENTRO
705003	7	ITAPUA	5	CAPITAN MIRANDA	1	3	BARRIO SAN JOSE
705100	7	ITAPUA	5	CAPITAN MIRANDA	6	100	FEDERICO CHAVEZ
705120	7	ITAPUA	5	CAPITAN MIRANDA	6	120	CALLE B NORTE
705130	7	ITAPUA	5	CAPITAN MIRANDA	6	130	CALLE A NORTE
705140	7	ITAPUA	5	CAPITAN MIRANDA	6	140	PICADA BOCA
705150	7	ITAPUA	5	CAPITAN MIRANDA	6	150	CALLE D NORTE
705160	7	ITAPUA	5	CAPITAN MIRANDA	6	160	CALLE C NORTE
705170	7	ITAPUA	5	CAPITAN MIRANDA	6	170	CALLE D SUR
705180	7	ITAPUA	5	CAPITAN MIRANDA	6	180	CALLE C SUR
705190	7	ITAPUA	5	CAPITAN MIRANDA	6	190	LOS ROSALES
705200	7	ITAPUA	5	CAPITAN MIRANDA	6	200	CALLE B SUR
705210	7	ITAPUA	5	CAPITAN MIRANDA	6	210	INMACULADA CONCEPCION
705220	7	ITAPUA	5	CAPITAN MIRANDA	6	220	CALLE A SUR
705230	7	ITAPUA	5	CAPITAN MIRANDA	6	230	YTORORO
705235	7	ITAPUA	5	CAPITAN MIRANDA	3	235	YTORORO-SUB URBANO
705240	7	ITAPUA	5	CAPITAN MIRANDA	6	240	ALBORADA 2
705250	7	ITAPUA	5	CAPITAN MIRANDA	6	250	MBURUCUYA
705260	7	ITAPUA	5	CAPITAN MIRANDA	6	260	EL PARAISO
706001	7	ITAPUA	6	NUEVA ALBORADA	1	1	URBANO
706110	7	ITAPUA	6	NUEVA ALBORADA	6	110	PUERTO  ITA CAJON
706120	7	ITAPUA	6	NUEVA ALBORADA	6	120	PUERTO CANTERA
706130	7	ITAPUA	6	NUEVA ALBORADA	6	130	VILLA ALBORADA
706140	7	ITAPUA	6	NUEVA ALBORADA	6	140	PUERTO PARAISO
706150	7	ITAPUA	6	NUEVA ALBORADA	6	150	PUERTO SAMU'U
706160	7	ITAPUA	6	NUEVA ALBORADA	6	160	PUERTO TRINIDAD
706190	7	ITAPUA	6	NUEVA ALBORADA	6	190	ALBORADA NORTE
706200	7	ITAPUA	6	NUEVA ALBORADA	6	200	CALLE F
706210	7	ITAPUA	6	NUEVA ALBORADA	6	210	SAN ISIDRO
706220	7	ITAPUA	6	NUEVA ALBORADA	6	220	CALLE E
706230	7	ITAPUA	6	NUEVA ALBORADA	6	230	TEJU KUARE
706240	7	ITAPUA	6	NUEVA ALBORADA	6	240	CALLE D
706250	7	ITAPUA	6	NUEVA ALBORADA	6	250	CALLE A
706260	7	ITAPUA	6	NUEVA ALBORADA	6	260	CALLE B
707001	7	ITAPUA	7	CARMEN DEL PARANA	1	1	SAN MIGUEL
707002	7	ITAPUA	7	CARMEN DEL PARANA	1	2	LOMA CLAVEL
707003	7	ITAPUA	7	CARMEN DEL PARANA	1	3	SAN ISIDRO
707004	7	ITAPUA	7	CARMEN DEL PARANA	1	4	SAN ROQUE
707005	7	ITAPUA	7	CARMEN DEL PARANA	1	5	SAN BLAS
707006	7	ITAPUA	7	CARMEN DEL PARANA	1	6	OBRERO
707007	7	ITAPUA	7	CARMEN DEL PARANA	1	7	GRANEROS
707008	7	ITAPUA	7	CARMEN DEL PARANA	1	8	SAN FRANCISCO DEL SUR
707009	7	ITAPUA	7	CARMEN DEL PARANA	1	9	CONJUNTO HABITACIONAL
707110	7	ITAPUA	7	CARMEN DEL PARANA	6	110	KA'ATYMI
707120	7	ITAPUA	7	CARMEN DEL PARANA	6	120	CERRITO
707130	7	ITAPUA	7	CARMEN DEL PARANA	6	130	JAKAREY
707140	7	ITAPUA	7	CARMEN DEL PARANA	6	140	YVYRAITY
707150	7	ITAPUA	7	CARMEN DEL PARANA	6	150	HUGUA KARE
707160	7	ITAPUA	7	CARMEN DEL PARANA	6	160	KARAGUATA
707220	7	ITAPUA	7	CARMEN DEL PARANA	6	220	CAMBAY
707240	7	ITAPUA	7	CARMEN DEL PARANA	6	240	SAN ISIDRO
707250	7	ITAPUA	7	CARMEN DEL PARANA	6	250	SAN MARTIN
707260	7	ITAPUA	7	CARMEN DEL PARANA	6	260	TUPASY POTRERO
708001	7	ITAPUA	8	CORONEL BOGADO	1	1	SANTA CLARA
708002	7	ITAPUA	8	CORONEL BOGADO	1	2	SANTA ROSA
708003	7	ITAPUA	8	CORONEL BOGADO	1	3	SAN BLAS
708004	7	ITAPUA	8	CORONEL BOGADO	1	4	SANTA LIBRADA
708005	7	ITAPUA	8	CORONEL BOGADO	1	5	SAN ANTONIO
708006	7	ITAPUA	8	CORONEL BOGADO	1	6	CONAVI
708100	7	ITAPUA	8	CORONEL BOGADO	6	100	CRISTO REY
708110	7	ITAPUA	8	CORONEL BOGADO	6	110	SAN FRANCISCO
708120	7	ITAPUA	8	CORONEL BOGADO	6	120	NACIONAL
708140	7	ITAPUA	8	CORONEL BOGADO	6	140	SANTA CLARA
708150	7	ITAPUA	8	CORONEL BOGADO	6	150	KURUNAI
708160	7	ITAPUA	8	CORONEL BOGADO	6	160	SAN ISIDRO
708170	7	ITAPUA	8	CORONEL BOGADO	6	170	SAN RAFAEL
708190	7	ITAPUA	8	CORONEL BOGADO	6	190	ANTEQUERA
708210	7	ITAPUA	8	CORONEL BOGADO	6	210	SAN JUAN
708220	7	ITAPUA	8	CORONEL BOGADO	6	220	CAUCASIA
708230	7	ITAPUA	8	CORONEL BOGADO	6	230	YPYTA
708240	7	ITAPUA	8	CORONEL BOGADO	6	240	DOMINGO BADO
708250	7	ITAPUA	8	CORONEL BOGADO	6	250	SAN MIGUEL POTRERO
708260	7	ITAPUA	8	CORONEL BOGADO	6	260	MBUTUY
708270	7	ITAPUA	8	CORONEL BOGADO	6	270	RESQUIN KUE
708280	7	ITAPUA	8	CORONEL BOGADO	6	280	TAKUARY
708300	7	ITAPUA	8	CORONEL BOGADO	6	300	SAN JUAN HUGUA'I
708310	7	ITAPUA	8	CORONEL BOGADO	6	310	AGUARARE
708320	7	ITAPUA	8	CORONEL BOGADO	6	320	CERRITO
708330	7	ITAPUA	8	CORONEL BOGADO	6	330	SAN ANTONIO
708350	7	ITAPUA	8	CORONEL BOGADO	6	350	TYMAKA
708360	7	ITAPUA	8	CORONEL BOGADO	6	360	SIBERIA
708370	7	ITAPUA	8	CORONEL BOGADO	6	370	FLORIDO
708390	7	ITAPUA	8	CORONEL BOGADO	6	390	TACUATY
708400	7	ITAPUA	8	CORONEL BOGADO	6	400	SAN JORGE
708410	7	ITAPUA	8	CORONEL BOGADO	6	410	TELLEZ KUE
708420	7	ITAPUA	8	CORONEL BOGADO	6	420	POTRERITO
708430	7	ITAPUA	8	CORONEL BOGADO	6	430	ASENT. BUENA VISTA
708440	7	ITAPUA	8	CORONEL BOGADO	6	440	CAMBA RUGUA
709001	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	1	1	SAN ANTONIO DE PADUA
709002	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	1	2	RESIDENCIAL
709003	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	1	3	DEFENSORES DEL CHACO
709004	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	1	4	SANTA LIBRADA
709120	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	120	KRESSBURGO
709125	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	3	125	KRESSBURGO SUB-URBANO
709130	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	130	MAESTRO FERMIN LOPEZ
709135	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	3	135	MAESTRO FERMIN LOPEZ SUB-URBANO
709140	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	140	TIROL
709150	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	150	SAN LORENZO
709155	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	3	155	SAN LORENZO-SUB URBANO
709160	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	160	7 DE AGOSTO
709170	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	170	VIRGEN DEL CARMEN
709180	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	180	SAN ISIDRO
709190	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	190	CAACUPEMI
709200	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	200	CRUCE KIMEX
709210	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	210	ASENT. PALMITAL
709220	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	220	SAN FRANCISCO
709230	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	230	NINO JESUS
709240	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	240	SAN PEDRO
709250	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	250	PUERTO LOPEZ
709260	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	260	SAN ROQUE
709270	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	270	SAN ISIDRO 2
709280	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	280	ASENT. GUARAPAY
709290	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	290	COM INDIG MACUTINGA
709300	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	300	ASENT. SANTA CATALINA 7 DE AGOSTO
709310	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	310	COM INDIG Y'AKA MARANGATU
709320	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	320	COM INDIG ARASA POTY
709330	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	330	ALEGRE
709340	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	340	EL PROGRESO
709350	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	350	SAN VALENTIN
709360	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	360	ASENT. PRIMAVERA
709370	7	ITAPUA	9	CARLOS ANTONIO LOPEZ	6	370	COM INDIG KRESSBURGO
710001	7	ITAPUA	10	NATALIO	1	1	SAN JOSE
710002	7	ITAPUA	10	NATALIO	1	2	OBRERO
710003	7	ITAPUA	10	NATALIO	1	3	SANTA CECILIA
710004	7	ITAPUA	10	NATALIO	1	4	UNIDO
710005	7	ITAPUA	10	NATALIO	1	5	SANTA RITA
710006	7	ITAPUA	10	NATALIO	1	6	SAN MIGUEL
710007	7	ITAPUA	10	NATALIO	1	7	ESPIRITU SANTO
710008	7	ITAPUA	10	NATALIO	1	8	SAN JUAN
710009	7	ITAPUA	10	NATALIO	1	9	SAN ROQUE
710010	7	ITAPUA	10	NATALIO	1	10	SAN FRANCISCO
710011	7	ITAPUA	10	NATALIO	1	11	FLORIDA
710012	7	ITAPUA	10	NATALIO	1	12	KO'ETI RORY
710013	7	ITAPUA	10	NATALIO	1	13	UNION
710100	7	ITAPUA	10	NATALIO	6	100	PUERTO TRIUNFO NINO JESUS
710110	7	ITAPUA	10	NATALIO	6	110	PUERTO TRIUNFO
710115	7	ITAPUA	10	NATALIO	3	115	PUERTO TRIUNFO-SUB URBANO
710120	7	ITAPUA	10	NATALIO	6	120	PUERTO TRIUNFO SAN JOSE
710130	7	ITAPUA	10	NATALIO	6	130	SAN MIGUEL
710140	7	ITAPUA	10	NATALIO	6	140	CRISTO REY
710150	7	ITAPUA	10	NATALIO	6	150	ITAPE
710160	7	ITAPUA	10	NATALIO	6	160	MARIA AUXILIADORA 2
710170	7	ITAPUA	10	NATALIO	6	170	JAKARE KUA
710180	7	ITAPUA	10	NATALIO	6	180	SANTA ROSA KILOMETRO 7
710190	7	ITAPUA	10	NATALIO	6	190	VIRGEN DEL CARMEN 1
710200	7	ITAPUA	10	NATALIO	6	200	SAN ISIDRO 1
710210	7	ITAPUA	10	NATALIO	6	210	SAN PEDRO 1
710220	7	ITAPUA	10	NATALIO	6	220	FATIMA
710230	7	ITAPUA	10	NATALIO	6	230	CORAZON DE MARIA
710240	7	ITAPUA	10	NATALIO	6	240	SAN ANTONIO-KILOMETRO 25-2DA LINEA
710250	7	ITAPUA	10	NATALIO	6	250	SANTA LIBRADA 1
710260	7	ITAPUA	10	NATALIO	6	260	SANTO DOMINGO 1RA. LINEA
710270	7	ITAPUA	10	NATALIO	6	270	MARIA AUXILIADORA
710280	7	ITAPUA	10	NATALIO	6	280	SANTO DOMINGO 2DA. LINEA
710290	7	ITAPUA	10	NATALIO	6	290	VIRGEN DE LA ASUNCION
710300	7	ITAPUA	10	NATALIO	6	300	SANTA LIBRADA 2
710310	7	ITAPUA	10	NATALIO	6	310	PA'I KURUSU
710320	7	ITAPUA	10	NATALIO	6	320	VIRGEN DE FATIMA
710330	7	ITAPUA	10	NATALIO	6	330	SAGRADO CORAZON KILOMETRO 14
710340	7	ITAPUA	10	NATALIO	6	340	TRIUNFO KILOMETRO 9
710350	7	ITAPUA	10	NATALIO	6	350	SAN JOSE
710360	7	ITAPUA	10	NATALIO	6	360	SANTO DOMINGO
710370	7	ITAPUA	10	NATALIO	6	370	VIRGEN DE LUJAN
710380	7	ITAPUA	10	NATALIO	6	380	SAN LUIS
710390	7	ITAPUA	10	NATALIO	6	390	SAN JUAN DEL PARANA
710400	7	ITAPUA	10	NATALIO	6	400	SAN MARCOS
710410	7	ITAPUA	10	NATALIO	6	410	SAN AGUSTIN-PALOMA KILOMETRO 3
710420	7	ITAPUA	10	NATALIO	6	420	SAN ISIDRO 2
710430	7	ITAPUA	10	NATALIO	6	430	PERPETUO SOCORRO PALOMA 6
710440	7	ITAPUA	10	NATALIO	6	440	SAN LORENZO-PALOMA 7
710450	7	ITAPUA	10	NATALIO	6	450	COOPERATIVA LA PALOMA
710460	7	ITAPUA	10	NATALIO	6	460	SAN ANTONIO 1RA. LINEA
710470	7	ITAPUA	10	NATALIO	6	470	SAN ANTONIO - 4 BOCAS
710480	7	ITAPUA	10	NATALIO	6	480	SAN PEDRO 2
710490	7	ITAPUA	10	NATALIO	6	490	SAN MIGUEL - PIRAYU'I
710500	7	ITAPUA	10	NATALIO	6	500	VIRGEN DEL CARMEN 2
710510	7	ITAPUA	10	NATALIO	6	510	SAN ANTONIO - 2DA LINEA
710520	7	ITAPUA	10	NATALIO	6	520	SAN BLAS
710530	7	ITAPUA	10	NATALIO	6	530	SAN ROQUE
710540	7	ITAPUA	10	NATALIO	6	540	LA ESPERANZA
710550	7	ITAPUA	10	NATALIO	6	550	NATALIO KILOMETRO 15 - EL PROGRESO
710560	7	ITAPUA	10	NATALIO	6	560	SAN JOSE OBRERO KILOMETRO 17
710570	7	ITAPUA	10	NATALIO	6	570	22 DE ABRIL
710580	7	ITAPUA	10	NATALIO	6	580	SANTA ANA
710590	7	ITAPUA	10	NATALIO	6	590	VIRGEN DE LOURDES
710600	7	ITAPUA	10	NATALIO	6	600	SANTA CATALINA
710610	7	ITAPUA	10	NATALIO	6	610	SAN FRANCISCO DE ASIS
710620	7	ITAPUA	10	NATALIO	6	620	SAN JORGE
710630	7	ITAPUA	10	NATALIO	6	630	20 DE JULIO
711001	7	ITAPUA	11	FRAM	1	1	SAN ANTONIO
711002	7	ITAPUA	11	FRAM	1	2	SAN FRANCISCO
711005	7	ITAPUA	11	FRAM	1	5	ITAPE
711006	7	ITAPUA	11	FRAM	1	6	SAN CRISTOBAL
711008	7	ITAPUA	11	FRAM	1	8	8 DE DICIEMBRE
711009	7	ITAPUA	11	FRAM	1	9	BARRIO OBRERO
711010	7	ITAPUA	11	FRAM	1	10	INDUSTRIAL
711011	7	ITAPUA	11	FRAM	1	11	VIRGEN DE LA ASUNCION
711012	7	ITAPUA	11	FRAM	1	12	ESPIRITU SANTO
711100	7	ITAPUA	11	FRAM	6	100	PASO NARANJO
711110	7	ITAPUA	11	FRAM	6	110	SAN BORJITA
711130	7	ITAPUA	11	FRAM	6	130	POTRERO YVATE
711140	7	ITAPUA	11	FRAM	6	140	CASO'I
711150	7	ITAPUA	11	FRAM	6	150	TAINDY
711160	7	ITAPUA	11	FRAM	6	160	KURUPA'Y
711170	7	ITAPUA	11	FRAM	6	170	POTRERO NOVILLO
711180	7	ITAPUA	11	FRAM	6	180	SAN JUAN
711190	7	ITAPUA	11	FRAM	6	190	FUJI
711200	7	ITAPUA	11	FRAM	6	200	BARRERO NU
711210	7	ITAPUA	11	FRAM	6	210	SAN ISIDRO
711220	7	ITAPUA	11	FRAM	6	220	SAN ANTONIO
711230	7	ITAPUA	11	FRAM	6	230	SAN BORJA
711240	7	ITAPUA	11	FRAM	6	240	CALLE E-F-G
712001	7	ITAPUA	12	GENERAL ARTIGAS	1	1	BARRIO 1
712002	7	ITAPUA	12	GENERAL ARTIGAS	1	2	BARRIO 2
712003	7	ITAPUA	12	GENERAL ARTIGAS	1	3	BARRIO 3
712004	7	ITAPUA	12	GENERAL ARTIGAS	1	4	BARRIO 4
712100	7	ITAPUA	12	GENERAL ARTIGAS	6	100	AREKITA
712120	7	ITAPUA	12	GENERAL ARTIGAS	6	120	BOBI KARAPE
712130	7	ITAPUA	12	GENERAL ARTIGAS	6	130	SAN PEDRO NU
712150	7	ITAPUA	12	GENERAL ARTIGAS	6	150	BOBI PUKU
712180	7	ITAPUA	12	GENERAL ARTIGAS	6	180	SAN BLAS
712220	7	ITAPUA	12	GENERAL ARTIGAS	6	220	POTRERITO
712230	7	ITAPUA	12	GENERAL ARTIGAS	6	230	INDEPENDENCIA
712240	7	ITAPUA	12	GENERAL ARTIGAS	6	240	LOMA CLAVEL
712250	7	ITAPUA	12	GENERAL ARTIGAS	6	250	TAROPE
712260	7	ITAPUA	12	GENERAL ARTIGAS	6	260	YPAJERE
712280	7	ITAPUA	12	GENERAL ARTIGAS	6	280	PICADA PYTA
712290	7	ITAPUA	12	GENERAL ARTIGAS	6	290	ISLA ALTA
712300	7	ITAPUA	12	GENERAL ARTIGAS	6	300	BUENA VISTA
712310	7	ITAPUA	12	GENERAL ARTIGAS	6	310	SAN MIGUEL
712340	7	ITAPUA	12	GENERAL ARTIGAS	6	340	NU PYAHU
712350	7	ITAPUA	12	GENERAL ARTIGAS	6	350	CURUPAYTY
712360	7	ITAPUA	12	GENERAL ARTIGAS	6	360	COLONIA URUGUAYA
712380	7	ITAPUA	12	GENERAL ARTIGAS	6	380	CAMBAY
712390	7	ITAPUA	12	GENERAL ARTIGAS	6	390	POTRERO KOKUERE
712400	7	ITAPUA	12	GENERAL ARTIGAS	6	400	NU GUASU
712410	7	ITAPUA	12	GENERAL ARTIGAS	6	410	YUKYRAY
712420	7	ITAPUA	12	GENERAL ARTIGAS	6	420	SYRYCA
712430	7	ITAPUA	12	GENERAL ARTIGAS	6	430	HUGUA PO'I 2
712440	7	ITAPUA	12	GENERAL ARTIGAS	6	440	POTRERO NEMBOTY
712450	7	ITAPUA	12	GENERAL ARTIGAS	6	450	YATA'I
712460	7	ITAPUA	12	GENERAL ARTIGAS	6	460	POTRERO KA'A
712470	7	ITAPUA	12	GENERAL ARTIGAS	6	470	POTRERO DUARTE
712480	7	ITAPUA	12	GENERAL ARTIGAS	6	480	ZANJA HONDA
713001	7	ITAPUA	13	GENERAL DELGADO	1	1	NORTE
713002	7	ITAPUA	13	GENERAL DELGADO	1	2	CAACUPE
713003	7	ITAPUA	13	GENERAL DELGADO	1	3	JAZMIN
713004	7	ITAPUA	13	GENERAL DELGADO	1	4	SAN ROQUE
713120	7	ITAPUA	13	GENERAL DELGADO	6	120	SAN PEDRITO
713140	7	ITAPUA	13	GENERAL DELGADO	6	140	SAN ESTANISLAO
713150	7	ITAPUA	13	GENERAL DELGADO	6	150	VILLA DEL ROSARIO
713160	7	ITAPUA	13	GENERAL DELGADO	6	160	SAN ISIDRO
713170	7	ITAPUA	13	GENERAL DELGADO	6	170	KA'A
713180	7	ITAPUA	13	GENERAL DELGADO	6	180	PUNTA PORA
713190	7	ITAPUA	13	GENERAL DELGADO	6	190	SAN DIONISIO
713200	7	ITAPUA	13	GENERAL DELGADO	6	200	SANTA LIBRADA
713210	7	ITAPUA	13	GENERAL DELGADO	6	210	SAN JOSE
713220	7	ITAPUA	13	GENERAL DELGADO	6	220	HUGUA GUASU
713230	7	ITAPUA	13	GENERAL DELGADO	6	230	SAN BLAS
713240	7	ITAPUA	13	GENERAL DELGADO	6	240	SANTA ROSA
713250	7	ITAPUA	13	GENERAL DELGADO	6	250	PASO LAUREL
713270	7	ITAPUA	13	GENERAL DELGADO	6	270	POSTA KUE
713280	7	ITAPUA	13	GENERAL DELGADO	6	280	SAN ANTONIO
713290	7	ITAPUA	13	GENERAL DELGADO	6	290	PUESTA DEL SOL
713300	7	ITAPUA	13	GENERAL DELGADO	6	300	TAVA'I
714002	7	ITAPUA	14	HOHENAU	1	2	UNIVERSITARIO
714003	7	ITAPUA	14	HOHENAU	1	3	PARQUE
714004	7	ITAPUA	14	HOHENAU	1	4	LUIS ALBERTO DEL PARANA
714005	7	ITAPUA	14	HOHENAU	1	5	SAN ROQUE
714006	7	ITAPUA	14	HOHENAU	1	6	ARMONIA
714007	7	ITAPUA	14	HOHENAU	1	7	COLINAS DEL SUR
714008	7	ITAPUA	14	HOHENAU	1	8	CENTRO
714009	7	ITAPUA	14	HOHENAU	1	9	EL MIRADOR
714010	7	ITAPUA	14	HOHENAU	1	10	ALTO JARDIN
714011	7	ITAPUA	14	HOHENAU	1	11	LOS COLONOS
714012	7	ITAPUA	14	HOHENAU	1	12	LOS PIONEROS
714013	7	ITAPUA	14	HOHENAU	1	13	CERRO CORA
714014	7	ITAPUA	14	HOHENAU	1	14	SAN BLAS
714015	7	ITAPUA	14	HOHENAU	1	15	PRADERA ALTA
714016	7	ITAPUA	14	HOHENAU	1	16	OBRERO
714017	7	ITAPUA	14	HOHENAU	1	17	SANTA CECILIA
714018	7	ITAPUA	14	HOHENAU	1	18	SAN JOSE
714100	7	ITAPUA	14	HOHENAU	6	100	HOHENAU 5
714110	7	ITAPUA	14	HOHENAU	6	110	HOHENAU 4
714130	7	ITAPUA	14	HOHENAU	6	130	HOHENAU 3
714150	7	ITAPUA	14	HOHENAU	6	150	HOHENAU 2
714160	7	ITAPUA	14	HOHENAU	6	160	HOHENAU 1
714170	7	ITAPUA	14	HOHENAU	6	170	SAN JOSE
714190	7	ITAPUA	14	HOHENAU	6	190	SANTA ROSA
714220	7	ITAPUA	14	HOHENAU	6	220	SAN MIGUEL
714260	7	ITAPUA	14	HOHENAU	6	260	SANTA MARIA
714270	7	ITAPUA	14	HOHENAU	6	270	ITAPES?�I
715001	7	ITAPUA	15	JESUS	1	1	SAN ISIDRO
715002	7	ITAPUA	15	JESUS	1	2	SAN RAMON
715003	7	ITAPUA	15	JESUS	1	3	INMACULADA
715004	7	ITAPUA	15	JESUS	1	4	SANTA LIBRADA
715005	7	ITAPUA	15	JESUS	1	5	SAN JUAN
715006	7	ITAPUA	15	JESUS	1	6	CENTRO
715007	7	ITAPUA	15	JESUS	1	7	SAN MIGUEL
715110	7	ITAPUA	15	JESUS	6	110	SAN ROQUE
715120	7	ITAPUA	15	JESUS	6	120	CAAGUAZU
715130	7	ITAPUA	15	JESUS	6	130	KARUMBEY
715140	7	ITAPUA	15	JESUS	6	140	COLONIA GUARANI
715150	7	ITAPUA	15	JESUS	6	150	SANTA TERESA
715160	7	ITAPUA	15	JESUS	6	160	CAMBAY
715170	7	ITAPUA	15	JESUS	6	170	COM INDIG KAMBAY
715180	7	ITAPUA	15	JESUS	6	180	SAN MIGUEL
715190	7	ITAPUA	15	JESUS	6	190	SAN ANTONIO
715200	7	ITAPUA	15	JESUS	6	200	SAN JUAN
715210	7	ITAPUA	15	JESUS	6	210	8 DE DICIEMBRE
716001	7	ITAPUA	16	JOSE LEANDRO OVIEDO	1	1	CENTRO
716100	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	100	GAONA KUE
716120	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	120	LOMA HOVY
716130	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	130	SALITRE KUE
716140	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	140	CANADA TEBICUARY
716150	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	150	SAN SOLANO MI
716160	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	160	CAMPO FLORIDO
716180	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	180	MBOKA PIRAY
716190	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	190	POTRERO YAPEPO
716200	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	200	COLONIA CAPITAN LEGUIZAMON
716210	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	210	CANADITA
716220	7	ITAPUA	16	JOSE LEANDRO OVIEDO	6	220	CANADA SAN RAMON
717001	7	ITAPUA	17	OBLIGADO	1	1	PANAMBI
717002	7	ITAPUA	17	OBLIGADO	1	2	CRISTO REY
717003	7	ITAPUA	17	OBLIGADO	1	3	IDROSA
717004	7	ITAPUA	17	OBLIGADO	1	4	MARIA AUXILIADORA
717005	7	ITAPUA	17	OBLIGADO	1	5	SANTA RITA
717006	7	ITAPUA	17	OBLIGADO	1	6	LA VICTORIA
717007	7	ITAPUA	17	OBLIGADO	1	7	ASENT. MILAGROS 1
717008	7	ITAPUA	17	OBLIGADO	1	8	BERNARDINO CABALLERO
717009	7	ITAPUA	17	OBLIGADO	1	9	EL PORTAL
717010	7	ITAPUA	17	OBLIGADO	1	10	VILLA GRACIELA
717011	7	ITAPUA	17	OBLIGADO	1	11	CENTRO
717012	7	ITAPUA	17	OBLIGADO	1	12	SAN BLAS
717013	7	ITAPUA	17	OBLIGADO	1	13	ASENT. MILAGROS 2
717014	7	ITAPUA	17	OBLIGADO	1	14	UNIVERSITARIO
717015	7	ITAPUA	17	OBLIGADO	1	15	PAISAJES DEL SUR
717016	7	ITAPUA	17	OBLIGADO	1	16	KO'EJU
717017	7	ITAPUA	17	OBLIGADO	1	17	SAN JUAN
717100	7	ITAPUA	17	OBLIGADO	6	100	COLONIA OBLIGADO KILOMETRO 17
717130	7	ITAPUA	17	OBLIGADO	6	130	COLONIA PASTOREO
717140	7	ITAPUA	17	OBLIGADO	6	140	COLONIA LAPACHAL
717150	7	ITAPUA	17	OBLIGADO	6	150	COLONIA PALMITO
717160	7	ITAPUA	17	OBLIGADO	6	160	COLONIA MORENA'I
717170	7	ITAPUA	17	OBLIGADO	6	170	ASENT. PALMITO
717180	7	ITAPUA	17	OBLIGADO	6	180	COLONIA 8 DE DICIEMBRE
717190	7	ITAPUA	17	OBLIGADO	6	190	ASENT. SAN ROQUE
717200	7	ITAPUA	17	OBLIGADO	6	200	COM INDIG PASTOREO
717210	7	ITAPUA	17	OBLIGADO	6	210	COLONIA CANTERA
717220	7	ITAPUA	17	OBLIGADO	6	220	ASENT. 20 DE JUNIO
717230	7	ITAPUA	17	OBLIGADO	6	230	COLONIA POROMOCO
717240	7	ITAPUA	17	OBLIGADO	6	240	COLONIA  ARROYO GUASU
717250	7	ITAPUA	17	OBLIGADO	6	250	COLONIA CAMPO ANGELES
717260	7	ITAPUA	17	OBLIGADO	6	260	COLONIA OBLIGADO
717270	7	ITAPUA	17	OBLIGADO	6	270	COM INDIG LOMA HOVY
718001	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	1	1	UNIVERSITARIO
718002	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	1	2	REPUBLICANO
718003	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	1	3	8 DE DICIEMBRE
718004	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	1	4	DEFENSORES DEL CHACO
718100	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	100	1RA LINEA
718150	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	150	SAN MIGUEL DEL NORTE
718170	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	170	PAREJHA
718180	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	180	5TA LINEA
718210	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	210	PAREJHA 1RA LINEA
718220	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	220	KILOMETRO 24
718240	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	240	CAPITAN URBINA
718250	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	250	COM INDIG TEKOHA PORA
718260	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	260	REPATRIACION  1RA LINEA
718270	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	270	REPATRIACION 3RA LINEA
718280	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	280	SANTA LIBRADA 4TA LINEA
718290	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	290	SANTO DOMINGO
718300	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	300	YACUY GUASU
718310	7	ITAPUA	18	MAYOR JULIO DIONISIO OTANO	6	310	YACUY MINI
719001	7	ITAPUA	19	SAN COSME Y DAMIAN	1	1	KA'AGUY RORY
719002	7	ITAPUA	19	SAN COSME Y DAMIAN	1	2	SAN MIGUEL
719003	7	ITAPUA	19	SAN COSME Y DAMIAN	1	3	CENTRO
719004	7	ITAPUA	19	SAN COSME Y DAMIAN	1	4	CAACUPE
719005	7	ITAPUA	19	SAN COSME Y DAMIAN	1	5	VILLA PERMANENTE
719006	7	ITAPUA	19	SAN COSME Y DAMIAN	1	6	SAN FRANCISCO
719007	7	ITAPUA	19	SAN COSME Y DAMIAN	1	7	RIBERAS DEL PARANA
719008	7	ITAPUA	19	SAN COSME Y DAMIAN	1	8	AGUAPEY
719009	7	ITAPUA	19	SAN COSME Y DAMIAN	1	9	SANTA LIBRADA
719130	7	ITAPUA	19	SAN COSME Y DAMIAN	6	130	POTRERO CARDOZO
719140	7	ITAPUA	19	SAN COSME Y DAMIAN	6	140	CAMBYRETA
719150	7	ITAPUA	19	SAN COSME Y DAMIAN	6	150	COM INDIG PINDO
719160	7	ITAPUA	19	SAN COSME Y DAMIAN	6	160	NUA U POTRERO
719170	7	ITAPUA	19	SAN COSME Y DAMIAN	6	170	SAN MAURICIO
719200	7	ITAPUA	19	SAN COSME Y DAMIAN	6	200	TAMBURA
719210	7	ITAPUA	19	SAN COSME Y DAMIAN	6	210	PIRITY
719220	7	ITAPUA	19	SAN COSME Y DAMIAN	6	220	LOMAS VALENTINAS
719230	7	ITAPUA	19	SAN COSME Y DAMIAN	6	230	ATINGUY
719240	7	ITAPUA	19	SAN COSME Y DAMIAN	6	240	TIBURCIO BOGADO
719250	7	ITAPUA	19	SAN COSME Y DAMIAN	6	250	SAN COSME
719260	7	ITAPUA	19	SAN COSME Y DAMIAN	6	260	SAN LORENZO
719270	7	ITAPUA	19	SAN COSME Y DAMIAN	6	270	SANTA MARIA
719280	7	ITAPUA	19	SAN COSME Y DAMIAN	6	280	ISLA YACYRETA
720001	7	ITAPUA	20	SAN PEDRO DEL PARANA	1	1	SAN FRANCISCO
720002	7	ITAPUA	20	SAN PEDRO DEL PARANA	1	2	SAN JOSE
720003	7	ITAPUA	20	SAN PEDRO DEL PARANA	1	3	SAN ANTONIO
720004	7	ITAPUA	20	SAN PEDRO DEL PARANA	1	4	SAN MIGUEL
720005	7	ITAPUA	20	SAN PEDRO DEL PARANA	1	5	SANTA ROSA
720006	7	ITAPUA	20	SAN PEDRO DEL PARANA	1	6	CENTRAL
720007	7	ITAPUA	20	SAN PEDRO DEL PARANA	1	7	SANTA CATALINA
720008	7	ITAPUA	20	SAN PEDRO DEL PARANA	1	8	FATIMA
720100	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	100	PIKY
720120	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	120	POTRERITO
720140	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	140	SAN ANTONIO MI
720160	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	160	COSTA RUIZ
720180	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	180	POTRERO DUARTE
720190	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	190	IBARRA KUE
720200	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	200	SAN CAYETANO
720210	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	210	TARUMA
720230	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	230	DESGRACIA KUE
720240	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	240	SAN SOLANO
720250	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	250	SAN JOSE PICADA
720260	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	260	MBOKAJA
720270	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	270	SAN JUAN PO'I
720280	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	280	SANTA BRIGIDA
720290	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	290	SAN VICENTE
720300	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	300	PUNTA RATY
720310	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	310	CARAGUATAY
720320	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	320	PASITO
720330	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	330	COLONIAS UNIDAS
720340	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	340	MBURUKUJA
720390	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	390	GUASU YGUA
720410	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	410	NU PYAHU
720430	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	430	SANTIAGO KUE
720440	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	440	SAN ROQUE 2
720450	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	450	SANTO DOMINGO
720460	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	460	KARAGUATA
720470	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	470	RINCON DE LUNA
720480	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	480	SANTA CRUZ
720500	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	500	KURUPIKA'Y
720510	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	510	MISIONES
720520	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	520	PINDOYU
720540	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	540	TIMBO'I
720550	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	550	SAN RAFAEL
720560	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	560	MONTEGRANDE
720570	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	570	SAN ISIDRO
720590	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	590	SAN LORENZO
720600	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	600	PUENTE KUE
720620	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	620	PIRITY
720625	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	625	ASENT. PAZ Y PROGRESO
720630	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	630	FLEITAS KUE
720635	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	635	ASENT. SAN RAMON
720640	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	640	JAGUA KUA
720650	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	650	MBYJA KO'E
720670	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	670	MBATOVI
720675	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	675	ASENT. 8 DE DICIEMBRE
720680	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	680	POTRERO BENITEZ
720685	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	685	ASENT. KO'E PYAHU
720690	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	690	POTRERO YVATE
720700	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	700	SAN JOSE POTRERO
720710	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	710	SAN JUAN GUASU
720720	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	720	PARIRI
720740	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	740	ARROYO FRAZADA
720750	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	750	POTRERITO 2
720760	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	760	ITA ANGU'A
720770	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	770	YABEBYRY
720780	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	780	ERATY
720790	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	790	ASENT. 12 DE JULIO - MBOPIKUE
720800	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	800	SAN NICOLAS
720820	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	820	SAN ROQUE 1
720830	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	830	ARROYO ITA
720840	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	840	VILLA ADELA
720850	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	850	ASENT. 15 DE MAYO
720855	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	855	ASENT. SAN ROQUE
720860	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	860	ASENT. KUARAHY RESE
720880	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	880	CAPITAN LEGUIZAMON
720890	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	890	TACUARA
720900	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	900	ASENT. JAGUA KUA
720910	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	910	SAN AGUSTIN
720915	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	915	ASENT. TAVA PORA
720920	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	920	ASENT. NUEVA ESPERANZA
720925	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	925	ASENT. SAN VALENTIN
720930	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	930	CARINO
720940	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	940	BOGADO KUE
720950	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	950	POTRERO NEMBOTY
720970	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	970	LAS MERCEDES
720980	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	980	CAAZAPAMI
720990	7	ITAPUA	20	SAN PEDRO DEL PARANA	6	990	YSYPOJU
721001	7	ITAPUA	21	SAN RAFAEL DEL PARANA	1	1	URBANO
721100	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	100	APE AIME
721110	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	110	ALBORADA
721120	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	120	JACUTINGA
721130	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	130	YANQUI KUE
721140	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	140	SAN RAFAEL PORVENIR 2DA LINEA
721150	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	150	SAN RAFAEL PORVENIR 1RA LINEA
721160	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	160	8 DE DICIEMBRE
721170	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	170	COLONIA SAN RAFAEL
721200	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	200	NARANJITO
721205	7	ITAPUA	21	SAN RAFAEL DEL PARANA	3	205	NARANJITO SUB-URBANO
721220	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	220	NUEVA AURORA
721230	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	230	COLONIA ROSAURA
721240	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	240	LINEA PETRIL
721250	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	250	PROGRESO
721260	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	260	ASENT. CRUCE KIMEX
721270	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	270	CRUCE KIMEX
721280	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	280	SAN RAFAEL II
721290	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	290	COM INDIG PYCASU YGUA
721300	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	300	SAN RAFAEL I
721305	7	ITAPUA	21	SAN RAFAEL DEL PARANA	3	305	SAN RAFAEL SUB - URBANO
721310	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	310	TEMBEY POTY
721320	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	320	FATIMA
721330	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	330	OBRERO
721340	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	340	SAN JOSE OBRERO
721350	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	350	LA PAZ
721360	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	360	SAN GABRIEL
721370	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	370	PORVENIR 3 DE MAYO
721380	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	380	SAN ISIDRO
721390	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	390	SAN PEDRO
721400	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	400	3 DE FEBRERO
721410	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	410	SAN MARCOS
721420	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	420	SAN JUAN
721430	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	430	BARRIO UNIDO
721440	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	440	SAN MIGUEL
721450	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	450	SAN BLAS
721460	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	460	ASENT. SAN JOSE
721470	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	470	3ER ASENT. COLONIA LIBERTAD
721480	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	480	2DO ASENT. COLONIA LIBERTAD
721490	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	490	4TO ASENT. COLONIA LIBERTAD
721500	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	500	1ER ASENT. COLONIA LIBERTAD
721510	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	510	COLONIA LIBERTAD
721520	7	ITAPUA	21	SAN RAFAEL DEL PARANA	3	520	PUERTO SAN RAFAEL SUB URBANO
721530	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	530	SAN LORENZO
721540	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	540	VIRGEN MILAGROSA
721550	7	ITAPUA	21	SAN RAFAEL DEL PARANA	6	550	COM INDIG MACUTINGA
722001	7	ITAPUA	22	TRINIDAD	1	1	SAN MIGUEL
722002	7	ITAPUA	22	TRINIDAD	1	2	JARDIN
722003	7	ITAPUA	22	TRINIDAD	1	3	PRIMAVERA
722004	7	ITAPUA	22	TRINIDAD	1	4	VILLA NUEVA
722005	7	ITAPUA	22	TRINIDAD	1	5	SAN LORENZO
722100	7	ITAPUA	22	TRINIDAD	6	100	VILLA SANTA MARIA
722105	7	ITAPUA	22	TRINIDAD	3	105	VILLA SANTA MARIA-SUB URBANO
722110	7	ITAPUA	22	TRINIDAD	6	110	NARANJAL
722120	7	ITAPUA	22	TRINIDAD	6	120	ITA VERA
722130	7	ITAPUA	22	TRINIDAD	6	130	PASO GUEMBE
722140	7	ITAPUA	22	TRINIDAD	6	140	SAN PEDRO
722160	7	ITAPUA	22	TRINIDAD	6	160	PICADA BOCA
722170	7	ITAPUA	22	TRINIDAD	6	170	SAN ANTONIO
722180	7	ITAPUA	22	TRINIDAD	6	180	CAMBAY
722190	7	ITAPUA	22	TRINIDAD	6	190	COM INDIG NU POTY
722200	7	ITAPUA	22	TRINIDAD	6	200	CHINO
722210	7	ITAPUA	22	TRINIDAD	6	210	SAN MIGUEL
722220	7	ITAPUA	22	TRINIDAD	6	220	COM INDIG GUAVIRA MI
723001	7	ITAPUA	23	EDELIRA	1	1	URBANO
723100	7	ITAPUA	23	EDELIRA	6	100	PIRAPEY  50 AL 65
723110	7	ITAPUA	23	EDELIRA	6	110	EDELIRA 21 PASO CARRETA
723120	7	ITAPUA	23	EDELIRA	6	120	EDELIRA 28
723170	7	ITAPUA	23	EDELIRA	6	170	EDELIRA 70 ZONA BASE
723180	7	ITAPUA	23	EDELIRA	6	180	EDELIRA 49
723190	7	ITAPUA	23	EDELIRA	6	190	EDELIRA 59 AL 69
723200	7	ITAPUA	23	EDELIRA	6	200	EDELIRA 51 AL 58
723220	7	ITAPUA	23	EDELIRA	6	220	ASENT. SAN JORGE PIRAPEY 72
723230	7	ITAPUA	23	EDELIRA	6	230	CLAVEL
723240	7	ITAPUA	23	EDELIRA	6	240	COM INDIG KA'AGUY POTY
723250	7	ITAPUA	23	EDELIRA	6	250	PASO ITA
723260	7	ITAPUA	23	EDELIRA	6	260	SAN ISIDRO
723270	7	ITAPUA	23	EDELIRA	6	270	ASENT. 1RO DE MARZO
723280	7	ITAPUA	23	EDELIRA	6	280	ASENT. 1RO DE MAYO
723290	7	ITAPUA	23	EDELIRA	6	290	ASENT. SANTA LIBRADA PIRAPEY 72
723300	7	ITAPUA	23	EDELIRA	6	300	ASENT. ACRA
723310	7	ITAPUA	23	EDELIRA	6	310	LAS MERCEDES
723320	7	ITAPUA	23	EDELIRA	6	320	EDELIRA 72
723330	7	ITAPUA	23	EDELIRA	6	330	EDELIRA 75 ZONA BASE
723340	7	ITAPUA	23	EDELIRA	6	340	18 DE AGOSTO
723350	7	ITAPUA	23	EDELIRA	6	350	EDELIRA 24 AL 27
723355	7	ITAPUA	23	EDELIRA	3	355	EDELIRA 28 SUB - URBANO
723360	7	ITAPUA	23	EDELIRA	6	360	EDELIRA 28 SAN ROQUE
723365	7	ITAPUA	23	EDELIRA	3	365	PIRAPEY KILOMETRO 30 SUB - URBANO
723370	7	ITAPUA	23	EDELIRA	6	370	PIRAPEY 30 AL 35
723380	7	ITAPUA	23	EDELIRA	6	380	EDELIRA 31 AL 36
723390	7	ITAPUA	23	EDELIRA	6	390	EDELIRA 41 AL 43
723400	7	ITAPUA	23	EDELIRA	6	400	PIRAPEY 45
723405	7	ITAPUA	23	EDELIRA	3	405	PIRAPEY 45 SUB - URBANO
723410	7	ITAPUA	23	EDELIRA	6	410	PIRAPEY 39 AL 43
723420	7	ITAPUA	23	EDELIRA	6	420	PIRAYU'I
724001	7	ITAPUA	24	TOMAS ROMERO PEREIRA	1	1	VIRGEN DE FATIMA
724002	7	ITAPUA	24	TOMAS ROMERO PEREIRA	1	2	SAN JOSE OBRERO
724003	7	ITAPUA	24	TOMAS ROMERO PEREIRA	1	3	CAACUPE
724004	7	ITAPUA	24	TOMAS ROMERO PEREIRA	1	4	SAN JUAN
724005	7	ITAPUA	24	TOMAS ROMERO PEREIRA	1	5	ASENT. SAN JUAN
724100	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	100	LAS MERCEDES
724110	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	110	ITA PIRANGA
724120	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	120	PERPETUO SOCORRO 2
724130	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	130	SAN MIGUEL 2
724140	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	140	SAN JORGE 2
724150	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	150	LINEA JARDIN
724160	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	160	PONDEROSA
724170	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	170	SAN CAYETANO
724180	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	180	SAN RAMON 2
724190	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	190	SAN ISIDRO 2
724200	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	200	SAN BALTAZAR
724210	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	210	SAN ANTONIO 2
724220	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	220	VALLE PORA
724230	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	230	BARRIO UNIDO
724240	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	240	ASENT. 8 DE DICIEMBRE
724250	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	250	SAN JOSE OBRERO
724260	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	260	SAN IGNACIO
724270	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	270	CRUCE AYALA
724280	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	280	VIRGEN DE FATIMA
724290	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	290	SAN JORGE 1
724300	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	300	8 DE DICIEMBRE
724310	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	310	SAN JUAN 3
724320	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	320	SAN ANTONIO 4
724330	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	330	VIRGEN DEL ROSARIO
724335	7	ITAPUA	24	TOMAS ROMERO PEREIRA	3	335	VIRGEN DEL ROSARIO SUB-URBANO
724340	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	340	SAN MIGUEL 1
724350	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	350	1RO DE MAYO  2
724360	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	360	ASENT. 1RO DE MAYO 2
724370	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	370	SAN ISIDRO 3
724380	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	380	SAN JUAN 2
724390	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	390	SANTA CATALINA
724400	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	400	SAGRADO CORAZON DE JESUS 2
724410	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	410	SANTA RITA
724420	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	420	GUARANI
724430	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	430	1RO DE MAYO 1
724440	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	440	SAN PEDRO 2
724450	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	450	PERPETUO SOCORRO 1
724460	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	460	SAN PABLO
724470	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	470	MARIA AUXILIADORA
724480	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	480	SAN PEDRO 1
724490	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	490	SAN ANTONIO 1
724500	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	500	CIUDAD NUEVA
724510	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	510	PRADO VERDE
724520	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	520	SAN BLAS
724530	7	ITAPUA	24	TOMAS ROMERO PEREIRA	3	530	SAN BLAS SUB-URBANO
724540	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	540	SANTA MARIA
724550	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	550	CAACUPE
724560	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	560	SAN ROQUE 1
724570	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	570	OBRERO 1
724580	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	580	SAGRADO CORAZON DE JESUS 1
724590	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	590	ASENT. VIRGEN DEL ROSARIO
724600	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	600	SANTA LUCIA
724610	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	610	13 DE JUNIO
724620	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	620	SAN ISIDRO 1
724630	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	630	OBRERO 2
724640	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	640	SAN JUAN 1
724650	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	650	SAN FRANCISCO KILOMETRO 50 AL KM 52
724660	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	660	SANTA LIBRADA
724670	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	670	SAN ANTONIO 3
724680	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	680	SAN PEDRO 3
724690	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	690	ASENT. 1RO DE MAYO 1
724700	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	700	VIRGEN DEL CARMEN
724710	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	710	ASENT.  NATALIO
724720	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	720	SAN ROQUE 2
724730	7	ITAPUA	24	TOMAS ROMERO PEREIRA	6	730	SAN RAMON 1
725001	7	ITAPUA	25	ALTO VERA	1	1	CENTRO
725002	7	ITAPUA	25	ALTO VERA	1	2	BARRIO NUEVO
725100	7	ITAPUA	25	ALTO VERA	6	100	LIBERTAD DEL SUR
725110	7	ITAPUA	25	ALTO VERA	6	110	ASENT. TAGUATO
725120	7	ITAPUA	25	ALTO VERA	6	120	PERLITA
725130	7	ITAPUA	25	ALTO VERA	6	130	ASENT. LA AMISTAD
725150	7	ITAPUA	25	ALTO VERA	6	150	MARGARITA
725160	7	ITAPUA	25	ALTO VERA	6	160	YNAMBU
725170	7	ITAPUA	25	ALTO VERA	6	170	KARAGUATA
725180	7	ITAPUA	25	ALTO VERA	6	180	PONCHO
725190	7	ITAPUA	25	ALTO VERA	6	190	SANTA LIBRADA
725200	7	ITAPUA	25	ALTO VERA	6	200	SAN ROQUE GONZALEZ
725220	7	ITAPUA	25	ALTO VERA	6	220	PARADEMA
725230	7	ITAPUA	25	ALTO VERA	6	230	VIALIDAD 1RA A 6TA LINEA
725240	7	ITAPUA	25	ALTO VERA	6	240	TARUMA
725260	7	ITAPUA	25	ALTO VERA	6	260	CARONAY
725280	7	ITAPUA	25	ALTO VERA	6	280	COM INDIG KARUMBEY MBARAKAYU
725300	7	ITAPUA	25	ALTO VERA	6	300	ASENT. 3 DE JUNIO TRANSLUMBRE
725320	7	ITAPUA	25	ALTO VERA	6	320	COM INDIG TUNA GUASU
725330	7	ITAPUA	25	ALTO VERA	6	330	ASENT. 13 DE MAYO
725340	7	ITAPUA	25	ALTO VERA	6	340	POTRERO KANGUE KUA
725350	7	ITAPUA	25	ALTO VERA	6	350	ASENT. TAGUATOI
725360	7	ITAPUA	25	ALTO VERA	6	360	COM INDIG GUAPO'Y
725370	7	ITAPUA	25	ALTO VERA	6	370	COM INDIG ARROYO MOROTI
725380	7	ITAPUA	25	ALTO VERA	6	380	4 PUENTES
725390	7	ITAPUA	25	ALTO VERA	6	390	ASENT. BONANZA 2
725400	7	ITAPUA	25	ALTO VERA	6	400	COM INDIG TAGUATO SAUCO
725410	7	ITAPUA	25	ALTO VERA	6	410	JOVERE
725420	7	ITAPUA	25	ALTO VERA	6	420	COM INDIG PINDO I
725430	7	ITAPUA	25	ALTO VERA	6	430	MBATOVI
725440	7	ITAPUA	25	ALTO VERA	6	440	OGA ITA
725450	7	ITAPUA	25	ALTO VERA	6	450	TORO KARE
725460	7	ITAPUA	25	ALTO VERA	6	460	YATA'I
725470	7	ITAPUA	25	ALTO VERA	6	470	ASENT. VY'A RAITY
725480	7	ITAPUA	25	ALTO VERA	6	480	COM INDIG PINDOTY
725490	7	ITAPUA	25	ALTO VERA	6	490	SAN JORGE
725500	7	ITAPUA	25	ALTO VERA	6	500	ASENT. 25 DE DICIEMBRE
725510	7	ITAPUA	25	ALTO VERA	6	510	COM INDIG JATYTAY'I
725520	7	ITAPUA	25	ALTO VERA	6	520	ASENT. 6 DE ENERO
725530	7	ITAPUA	25	ALTO VERA	6	530	PARADEMA 6TA LINEA
725540	7	ITAPUA	25	ALTO VERA	6	540	COM INDIG MBERU - PIRAPO I
725550	7	ITAPUA	25	ALTO VERA	6	550	COM INDIG MBOI KA'E
725560	7	ITAPUA	25	ALTO VERA	6	560	ASENT. LIBERTAD DEL SUR
725570	7	ITAPUA	25	ALTO VERA	6	570	SAN PASCUAL
725580	7	ITAPUA	25	ALTO VERA	6	580	COM INDIG KO'EJU
725600	7	ITAPUA	25	ALTO VERA	6	600	CERRITO
725610	7	ITAPUA	25	ALTO VERA	6	610	ASENT. ALTO VERA
725620	7	ITAPUA	25	ALTO VERA	6	620	ALTO VERA KILOMETRO 4
725630	7	ITAPUA	25	ALTO VERA	6	630	ASENT. 8 DE DICIEMBRE
725640	7	ITAPUA	25	ALTO VERA	6	640	COM INDIG YSAPY'Y
725650	7	ITAPUA	25	ALTO VERA	6	650	COM INDIG CERRITO
726001	7	ITAPUA	26	LA PAZ	1	1	VIRGEN DE FATIMA
726002	7	ITAPUA	26	LA PAZ	1	2	CENTRO
726003	7	ITAPUA	26	LA PAZ	1	3	SANTA MARIA
726100	7	ITAPUA	26	LA PAZ	6	100	SANTA ROSA
726110	7	ITAPUA	26	LA PAZ	6	110	SUELO KUE
726130	7	ITAPUA	26	LA PAZ	6	130	SAN CARLOS
726140	7	ITAPUA	26	LA PAZ	6	140	ITAPES?�I
726200	7	ITAPUA	26	LA PAZ	6	200	CALLE Q
726220	7	ITAPUA	26	LA PAZ	6	220	CALLE P
726230	7	ITAPUA	26	LA PAZ	6	230	FUJI
726240	7	ITAPUA	26	LA PAZ	6	240	SAN FRANCISCO
727001	7	ITAPUA	27	YATYTAY	1	1	SAN JOSE OBRERO
727002	7	ITAPUA	27	YATYTAY	1	2	SANTA ROSA
727003	7	ITAPUA	27	YATYTAY	1	3	URBANO
727004	7	ITAPUA	27	YATYTAY	1	4	SAN MIGUEL
727005	7	ITAPUA	27	YATYTAY	1	5	SAN CAYETANO
727006	7	ITAPUA	27	YATYTAY	1	6	CENTRAL
727007	7	ITAPUA	27	YATYTAY	1	7	SAGRADO CORAZON DE JESUS
727100	7	ITAPUA	27	YATYTAY	6	100	VIRGEN DE FATIMA 2
727110	7	ITAPUA	27	YATYTAY	6	110	YATYTAY KILOMETRO 16
727120	7	ITAPUA	27	YATYTAY	6	120	VIRGEN DE FATIMA 1
727130	7	ITAPUA	27	YATYTAY	6	130	SANTA ROSA
727140	7	ITAPUA	27	YATYTAY	6	140	SAN FRANCISCO
727150	7	ITAPUA	27	YATYTAY	6	150	ASENT. SAN GERONIMO
727160	7	ITAPUA	27	YATYTAY	6	160	BONANZA 1RA LINEA
727170	7	ITAPUA	27	YATYTAY	6	170	BONANZA 2DA LINEA
727180	7	ITAPUA	27	YATYTAY	6	180	BONANZA 3RA LINEA
727190	7	ITAPUA	27	YATYTAY	6	190	BONANZA 4TA LINEA
727200	7	ITAPUA	27	YATYTAY	6	200	YATYTAY KILOMETRO 8
727210	7	ITAPUA	27	YATYTAY	6	210	YATYTAY KILOMETRO 13
727220	7	ITAPUA	27	YATYTAY	6	220	OBRERO GUASUY
727230	7	ITAPUA	27	YATYTAY	6	230	SAN JORGE
727240	7	ITAPUA	27	YATYTAY	6	240	BONANZA SANTA RITA
727250	7	ITAPUA	27	YATYTAY	6	250	BONANZA SAN ANTONIO
727260	7	ITAPUA	27	YATYTAY	6	260	BONANZA 3 DE MAYO
727270	7	ITAPUA	27	YATYTAY	6	270	SAN RAMON
727280	7	ITAPUA	27	YATYTAY	6	280	SAN MARCOS
727290	7	ITAPUA	27	YATYTAY	6	290	SAN JOSE OBRERO 1
727300	7	ITAPUA	27	YATYTAY	6	300	SAN ANTONIO
727310	7	ITAPUA	27	YATYTAY	6	310	SAN JOSE OBRERO 2
727320	7	ITAPUA	27	YATYTAY	6	320	SAN CAYETANO
727330	7	ITAPUA	27	YATYTAY	6	330	SAN BLAS
727340	7	ITAPUA	27	YATYTAY	6	340	SAN MIGUEL
727350	7	ITAPUA	27	YATYTAY	6	350	SANTA LIBRADA
727360	7	ITAPUA	27	YATYTAY	6	360	SAN ROQUE GONZALEZ
727370	7	ITAPUA	27	YATYTAY	6	370	SAN GERONIMO
728001	7	ITAPUA	28	SAN JUAN DEL PARANA	1	1	SAN ANTONIO
728002	7	ITAPUA	28	SAN JUAN DEL PARANA	1	2	SAN JUAN CENTRO
728003	7	ITAPUA	28	SAN JUAN DEL PARANA	1	3	SANTA RITA
728004	7	ITAPUA	28	SAN JUAN DEL PARANA	1	4	EX-CIVIL
728005	7	ITAPUA	28	SAN JUAN DEL PARANA	1	5	LOMAS DEL SUR
728100	7	ITAPUA	28	SAN JUAN DEL PARANA	6	100	FATIMA
728130	7	ITAPUA	28	SAN JUAN DEL PARANA	6	130	SANTA ROSA DEL GUAVIJU
728160	7	ITAPUA	28	SAN JUAN DEL PARANA	6	160	CRISTO REY
728170	7	ITAPUA	28	SAN JUAN DEL PARANA	6	170	POTRERO
728180	7	ITAPUA	28	SAN JUAN DEL PARANA	6	180	GUARANI
728200	7	ITAPUA	28	SAN JUAN DEL PARANA	3	200	SANTA LIBRADA SUB- URBANO
728210	7	ITAPUA	28	SAN JUAN DEL PARANA	6	210	SAN LUIS
728220	7	ITAPUA	28	SAN JUAN DEL PARANA	6	220	ESTACION KUE
728230	7	ITAPUA	28	SAN JUAN DEL PARANA	6	230	8 DE DICIEMBRE
728240	7	ITAPUA	28	SAN JUAN DEL PARANA	6	240	24 DE JUNIO
728250	7	ITAPUA	28	SAN JUAN DEL PARANA	6	250	TERRAZA SAN JUAN
728260	7	ITAPUA	28	SAN JUAN DEL PARANA	6	260	SAN JOSE OBRERO
728270	7	ITAPUA	28	SAN JUAN DEL PARANA	6	270	PARAISO
728280	7	ITAPUA	28	SAN JUAN DEL PARANA	6	280	SAN NICOLAS
728290	7	ITAPUA	28	SAN JUAN DEL PARANA	6	290	LA AZOTEA
728300	7	ITAPUA	28	SAN JUAN DEL PARANA	6	300	CAMPOS VERDES
729001	7	ITAPUA	29	PIRAPO	1	1	SAN BLAS
729002	7	ITAPUA	29	PIRAPO	1	2	SAN FRANCISCO
729003	7	ITAPUA	29	PIRAPO	1	3	CENTRO
729004	7	ITAPUA	29	PIRAPO	1	4	SAN JOSE OBRERO
729005	7	ITAPUA	29	PIRAPO	1	5	SAN MIGUEL
729006	7	ITAPUA	29	PIRAPO	1	6	SAN IGNACIO
729007	7	ITAPUA	29	PIRAPO	1	7	8 DE DICIEMBRE
729008	7	ITAPUA	29	PIRAPO	1	8	SAN PEDRO PIRAPO
729100	7	ITAPUA	29	PIRAPO	6	100	JAGUA RASAPA
729110	7	ITAPUA	29	PIRAPO	6	110	PUERTO PIRAPO
729120	7	ITAPUA	29	PIRAPO	6	120	AKA KARAJA
729130	7	ITAPUA	29	PIRAPO	6	130	MANDUVIJU
729140	7	ITAPUA	29	PIRAPO	6	140	KA'ARENDY
729150	7	ITAPUA	29	PIRAPO	6	150	CALLE 2
729160	7	ITAPUA	29	PIRAPO	6	160	PIRAPO KILOMETRO  23
729170	7	ITAPUA	29	PIRAPO	6	170	COLONIA PIRAPO KILOMETRO  25
729180	7	ITAPUA	29	PIRAPO	6	180	CAMPO GUARANI
729190	7	ITAPUA	29	PIRAPO	6	190	COLONIA PIRAPO
729200	7	ITAPUA	29	PIRAPO	6	200	COM INDIG KA'ATY MI
729210	7	ITAPUA	29	PIRAPO	6	210	COM INDIG PARAISO
729220	7	ITAPUA	29	PIRAPO	6	220	COM INDIG MANDUVIYU
729230	7	ITAPUA	29	PIRAPO	6	230	COM INDIG SALTO RENDA
729240	7	ITAPUA	29	PIRAPO	6	240	ASENT. SAN ISIDRO
729250	7	ITAPUA	29	PIRAPO	6	250	COM INDIG NU HOVY JAGUARASAPA
729260	7	ITAPUA	29	PIRAPO	6	260	COM INDIG POTRERO GUARANI
730001	7	ITAPUA	30	ITAPUA POTY	1	1	URBANO
730100	7	ITAPUA	30	ITAPUA POTY	6	100	EDELIRA 60
730110	7	ITAPUA	30	ITAPUA POTY	6	110	PIRO'Y
730130	7	ITAPUA	30	ITAPUA POTY	6	130	PIRAPO 3RA LINEA
730140	7	ITAPUA	30	ITAPUA POTY	6	140	PIRAPO 1RA LINEA
730150	7	ITAPUA	30	ITAPUA POTY	6	150	PIRAPO 2DA LINEA
730180	7	ITAPUA	30	ITAPUA POTY	6	180	TAKUAPI
730190	7	ITAPUA	30	ITAPUA POTY	6	190	KATUPYRY 70
730200	7	ITAPUA	30	ITAPUA POTY	6	200	SAN BUENAVENTURA
730205	7	ITAPUA	30	ITAPUA POTY	3	205	SAN BUENAVENTURA SUB-URBANO
730210	7	ITAPUA	30	ITAPUA POTY	6	210	COLONIA ITAPUA POTY
730230	7	ITAPUA	30	ITAPUA POTY	6	230	ITAPUA POTY-BARANA
730240	7	ITAPUA	30	ITAPUA POTY	6	240	ARROYO CLARO
730250	7	ITAPUA	30	ITAPUA POTY	6	250	COM INDIG TAPYSAVY
730260	7	ITAPUA	30	ITAPUA POTY	6	260	ASENT. TAKUAPI
730270	7	ITAPUA	30	ITAPUA POTY	6	270	TAGUATO
730280	7	ITAPUA	30	ITAPUA POTY	6	280	ITAPUA POTY KILOMETRO 51 AL KM 57
730290	7	ITAPUA	30	ITAPUA POTY	6	290	ITAPUA POTY KILOMETRO 49
730300	7	ITAPUA	30	ITAPUA POTY	6	300	SAN JORGE
730301	7	ITAPUA	30	ITAPUA POTY	6	301	SAN LUIS
730320	7	ITAPUA	30	ITAPUA POTY	6	320	COM INDIG JUKERI
801001	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	1	SAN FRANCISCO
801002	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	2	SANTA ROSA DE LIMA
801003	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	3	SAN MIGUEL
801004	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	4	OBRERO
801005	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	5	GENERAL CABALLERO
801006	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	6	UNIVERSITARIO
801007	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	7	CONCEPCION
801008	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	8	BOQUERON
801009	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	9	NTRA. SRA. DE LA ASUNCION
801010	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	10	GENERAL DIAZ
801011	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	11	CENTRO
801012	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	12	SANTA MARIA DE LOURDES
801013	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	13	SAN ANTONIO
801014	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	14	CHINO
801015	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	15	VILLA BONITA
801016	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	16	TRIBUNAL DE JUSTICIA
801017	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	17	CONAVI 1
801018	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	1	18	CONAVI 2
801100	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	100	AREA DE ESTANCIAS
801110	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	110	ITA JURU
801120	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	120	CERRO PERO
801130	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	130	SAN CRISTOBAL
801140	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	140	ISLA TOBATI
801150	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	150	JATA'I
801160	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	160	TAPE GUASU
801170	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	170	YNAMBUVY
801180	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	180	CONCEPCION
801190	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	190	LOMA
801210	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	210	TRISTAN ZALAZAR
801220	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	220	COLONIA SAN JUAN
801240	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	240	MBURIKA RETA
801250	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	250	KOKUERE
801260	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	260	COLONIA IBANEZ ROJAS
801270	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	270	KA'AGUY KUPE
801280	8	MISIONES	1	SAN JUAN BAUTISTA DE LAS MISIONES	6	280	PASO NARANJA
802001	8	MISIONES	2	AYOLAS	1	1	NUCLEO 02
802004	8	MISIONES	2	AYOLAS	1	4	VILLA PERMANENTE
802005	8	MISIONES	2	AYOLAS	1	5	SAN JOSE OBRERO
802006	8	MISIONES	2	AYOLAS	1	6	MARIA GRACIELA
802007	8	MISIONES	2	AYOLAS	1	7	SAN ANTONIO
802008	8	MISIONES	2	AYOLAS	1	8	SAN JOSE MI
802009	8	MISIONES	2	AYOLAS	1	9	SANTA ROSA DE LIMA
802010	8	MISIONES	2	AYOLAS	1	10	YATAITY
802011	8	MISIONES	2	AYOLAS	1	11	VIRGEN DEL PILAR
802012	8	MISIONES	2	AYOLAS	1	12	MIL VIVIENDAS
802013	8	MISIONES	2	AYOLAS	1	13	SIRENA
802100	8	MISIONES	2	AYOLAS	6	100	CANADA DE CASTILLA
802110	8	MISIONES	2	AYOLAS	6	110	COSTA YABEBYRY
802120	8	MISIONES	2	AYOLAS	6	120	ESTERO BELLACO
802130	8	MISIONES	2	AYOLAS	6	130	HUGUA'I
802140	8	MISIONES	2	AYOLAS	6	140	SANTA LIBRADA
802180	8	MISIONES	2	AYOLAS	6	180	KO'EJU
802190	8	MISIONES	2	AYOLAS	6	190	BOQUERON
802200	8	MISIONES	2	AYOLAS	6	200	MBOKAJA POTY
802220	8	MISIONES	2	AYOLAS	6	220	PUESTO 6
802230	8	MISIONES	2	AYOLAS	6	230	LAS MERCEDES
802250	8	MISIONES	2	AYOLAS	3	250	CORATEI SUB-URBANO
802260	8	MISIONES	2	AYOLAS	6	260	ASENT. MISIONES POTY
802270	8	MISIONES	2	AYOLAS	6	270	KILOMETRO 15
802280	8	MISIONES	2	AYOLAS	6	280	MARIA AUXILIADORA
802290	8	MISIONES	2	AYOLAS	6	290	ASENT. MBOKAJATY POTY
802300	8	MISIONES	2	AYOLAS	6	300	ASENT. ATINGUY
803001	8	MISIONES	3	SAN IGNACIO	1	1	LOMA CLAVEL
803003	8	MISIONES	3	SAN IGNACIO	1	3	SAN FRANCISCO
803004	8	MISIONES	3	SAN IGNACIO	1	4	SAN JOSE
803005	8	MISIONES	3	SAN IGNACIO	1	5	SAN SALVADOR
803006	8	MISIONES	3	SAN IGNACIO	1	6	SAN ROQUE
803007	8	MISIONES	3	SAN IGNACIO	1	7	MARIA AUXILIADORA
803008	8	MISIONES	3	SAN IGNACIO	1	8	SAN VICENTE
803009	8	MISIONES	3	SAN IGNACIO	1	9	SANTO ANGEL
803011	8	MISIONES	3	SAN IGNACIO	1	11	LOS COCOS
803012	8	MISIONES	3	SAN IGNACIO	1	12	SAN ISIDRO
803013	8	MISIONES	3	SAN IGNACIO	1	13	LOURDES
803014	8	MISIONES	3	SAN IGNACIO	1	14	VILLA UNIVERSITARIA
803015	8	MISIONES	3	SAN IGNACIO	1	15	SANTA LIBRADA
803017	8	MISIONES	3	SAN IGNACIO	1	17	CONAVI VILLA PRIMAVERA
803018	8	MISIONES	3	SAN IGNACIO	1	18	ASENT. SAN RAFAEL
803120	8	MISIONES	3	SAN IGNACIO	6	120	COLONIA URUGUAYA
803130	8	MISIONES	3	SAN IGNACIO	6	130	POTRERO SAN ANTONIO
803140	8	MISIONES	3	SAN IGNACIO	6	140	ARROYO VERDE
803150	8	MISIONES	3	SAN IGNACIO	6	150	TAHYI TY
803160	8	MISIONES	3	SAN IGNACIO	6	160	KA'AGUY YVATE
803170	8	MISIONES	3	SAN IGNACIO	6	170	SAN MIGUEL
803180	8	MISIONES	3	SAN IGNACIO	6	180	HECTOR KUE
803210	8	MISIONES	3	SAN IGNACIO	6	210	TANARANDY
803230	8	MISIONES	3	SAN IGNACIO	6	230	SAN BLAS
803240	8	MISIONES	3	SAN IGNACIO	6	240	SAN JAVIER
803245	8	MISIONES	3	SAN IGNACIO	3	245	SAN JAVIER SUB - URBANO
803250	8	MISIONES	3	SAN IGNACIO	6	250	SAN JUAN POTRERO
803270	8	MISIONES	3	SAN IGNACIO	6	270	COSTA PIRU
803290	8	MISIONES	3	SAN IGNACIO	6	290	SAN PABLO
803295	8	MISIONES	3	SAN IGNACIO	3	295	SAN PABLO SUB - URBANO
803300	8	MISIONES	3	SAN IGNACIO	6	300	NEMI
803310	8	MISIONES	3	SAN IGNACIO	6	310	SANTA RITA
803315	8	MISIONES	3	SAN IGNACIO	3	315	SANTA RITA SUB - URBANO
803320	8	MISIONES	3	SAN IGNACIO	6	320	ASENT. MARTIN ROLON
803340	8	MISIONES	3	SAN IGNACIO	6	340	JAGUARY
803350	8	MISIONES	3	SAN IGNACIO	6	350	COSTA PUKU
803370	8	MISIONES	3	SAN IGNACIO	6	370	GUASU RAPE
803380	8	MISIONES	3	SAN IGNACIO	6	380	SAN ISIDRO
803390	8	MISIONES	3	SAN IGNACIO	6	390	SANTO DOMINGO
803420	8	MISIONES	3	SAN IGNACIO	6	420	NANGAPE
803430	8	MISIONES	3	SAN IGNACIO	6	430	ABAY
803440	8	MISIONES	3	SAN IGNACIO	6	440	CAJES KUE
803460	8	MISIONES	3	SAN IGNACIO	6	460	LAGUNA PYTA
803470	8	MISIONES	3	SAN IGNACIO	6	470	KA'A JOHA
803480	8	MISIONES	3	SAN IGNACIO	6	480	PIRA KA'AGUY
803490	8	MISIONES	3	SAN IGNACIO	6	490	COSTA BRASIL
803500	8	MISIONES	3	SAN IGNACIO	6	500	ASENT. GUAYAKI
803510	8	MISIONES	3	SAN IGNACIO	6	510	SAN BENITO
803520	8	MISIONES	3	SAN IGNACIO	6	520	SAN JOSE
803530	8	MISIONES	3	SAN IGNACIO	6	530	VIRGEN DEL PILAR
803540	8	MISIONES	3	SAN IGNACIO	6	540	ASENT. CHE JAZMIN
803550	8	MISIONES	3	SAN IGNACIO	6	550	SANTA LIBRADA
803560	8	MISIONES	3	SAN IGNACIO	6	560	COLONIA SAN JORGE
803570	8	MISIONES	3	SAN IGNACIO	6	570	SAPUKAI
803580	8	MISIONES	3	SAN IGNACIO	6	580	ASENT. SANTA TERESA
803590	8	MISIONES	3	SAN IGNACIO	6	590	CRISTO REY
803600	8	MISIONES	3	SAN IGNACIO	6	600	POTRERITO
803610	8	MISIONES	3	SAN IGNACIO	6	610	ASENT. ARAPYSANDU
804005	8	MISIONES	4	SAN MIGUEL	1	5	CERRO CORA
804006	8	MISIONES	4	SAN MIGUEL	1	6	CIUDAD DEL ESTE
804007	8	MISIONES	4	SAN MIGUEL	1	7	BOQUERON
804008	8	MISIONES	4	SAN MIGUEL	1	8	SAN ROQUE GONZALEZ DE SANTA CRUZ
804009	8	MISIONES	4	SAN MIGUEL	1	9	GUARANI
804010	8	MISIONES	4	SAN MIGUEL	1	10	DON LAUREANO ROMERO ORTIZ
804011	8	MISIONES	4	SAN MIGUEL	1	11	COSTA HU
804012	8	MISIONES	4	SAN MIGUEL	1	12	CIUDAD NUEVA
804100	8	MISIONES	4	SAN MIGUEL	6	100	ZONA DE ESTANCIAS
804120	8	MISIONES	4	SAN MIGUEL	6	120	ARASAPE
804125	8	MISIONES	4	SAN MIGUEL	3	125	ARASAPE SUB - URBANO
804130	8	MISIONES	4	SAN MIGUEL	6	130	COSTA HU
804140	8	MISIONES	4	SAN MIGUEL	6	140	ITA JURU
804160	8	MISIONES	4	SAN MIGUEL	6	160	ISLA TAKUARA
804170	8	MISIONES	4	SAN MIGUEL	6	170	SAN MAURICIO
804180	8	MISIONES	4	SAN MIGUEL	6	180	YSYPO
804190	8	MISIONES	4	SAN MIGUEL	6	190	SANTA LIBRADA
804200	8	MISIONES	4	SAN MIGUEL	6	200	SAN PEDRO 2
804210	8	MISIONES	4	SAN MIGUEL	6	210	YSYPO POTRERO
804220	8	MISIONES	4	SAN MIGUEL	6	220	SAN PEDRO 1
804230	8	MISIONES	4	SAN MIGUEL	6	230	LOTE
805002	8	MISIONES	5	SAN PATRICIO	1	2	SANTA TERESA
805003	8	MISIONES	5	SAN PATRICIO	1	3	LA ESPERANZA
805004	8	MISIONES	5	SAN PATRICIO	1	4	TRES REYES
805005	8	MISIONES	5	SAN PATRICIO	1	5	VIRGEN DE LOS DOLORES
805006	8	MISIONES	5	SAN PATRICIO	1	6	SAGRADA FAMILIA
805007	8	MISIONES	5	SAN PATRICIO	1	7	PURA LIMPIA CONCEPCION
805100	8	MISIONES	5	SAN PATRICIO	6	100	SANTO ANGEL
805120	8	MISIONES	5	SAN PATRICIO	6	120	CONCEPCION
805130	8	MISIONES	5	SAN PATRICIO	6	130	POTRERITO
805140	8	MISIONES	5	SAN PATRICIO	6	140	YVYRA PIRU
805150	8	MISIONES	5	SAN PATRICIO	6	150	SAN MARTIN
806002	8	MISIONES	6	SANTA MARIA	1	2	CAACUPE
806003	8	MISIONES	6	SANTA MARIA	1	3	LAS MERCEDES
806004	8	MISIONES	6	SANTA MARIA	1	4	LOURDES
806005	8	MISIONES	6	SANTA MARIA	1	5	CONAVI
806006	8	MISIONES	6	SANTA MARIA	1	6	CENTRO
806007	8	MISIONES	6	SANTA MARIA	1	7	FATIMA
806120	8	MISIONES	6	SANTA MARIA	6	120	SAN ANTONIO
806130	8	MISIONES	6	SANTA MARIA	6	130	ITACURUBI
806140	8	MISIONES	6	SANTA MARIA	6	140	ZANJA KORA
806150	8	MISIONES	6	SANTA MARIA	6	150	SAN FERNANDO
806160	8	MISIONES	6	SANTA MARIA	6	160	TRINIDAD KUE
806170	8	MISIONES	6	SANTA MARIA	6	170	CERRO COSTA
806190	8	MISIONES	6	SANTA MARIA	6	190	SAN GERONIMO
806210	8	MISIONES	6	SANTA MARIA	6	210	LOURDES
806220	8	MISIONES	6	SANTA MARIA	6	220	SAN JUAN BERCHMANS
806240	8	MISIONES	6	SANTA MARIA	6	240	TAVA'I
806250	8	MISIONES	6	SANTA MARIA	6	250	CURUPAYTY
806260	8	MISIONES	6	SANTA MARIA	6	260	ARROYO KARE
806270	8	MISIONES	6	SANTA MARIA	6	270	ASENT. SAN FERNANDO
806280	8	MISIONES	6	SANTA MARIA	6	280	COSTA PO'I
807002	8	MISIONES	7	SANTA ROSA	1	2	PABLO VI
807003	8	MISIONES	7	SANTA ROSA	1	3	SAN ISIDRO
807004	8	MISIONES	7	SANTA ROSA	1	4	CRISTO REY
807005	8	MISIONES	7	SANTA ROSA	1	5	SAN MIGUEL
807006	8	MISIONES	7	SANTA ROSA	1	6	VIRGEN DEL PILAR
807007	8	MISIONES	7	SANTA ROSA	1	7	SAN JOSE
807008	8	MISIONES	7	SANTA ROSA	1	8	CENTRO
807100	8	MISIONES	7	SANTA ROSA	6	100	AREA DE ESTANCIAS
807110	8	MISIONES	7	SANTA ROSA	6	110	SAN FRANCISCO KUE
807120	8	MISIONES	7	SANTA ROSA	6	120	SAN GABRIEL
807130	8	MISIONES	7	SANTA ROSA	6	130	COLONIA ACEVEDO
807140	8	MISIONES	7	SANTA ROSA	6	140	SAN JOSE 19
807150	8	MISIONES	7	SANTA ROSA	6	150	SAN JOSE MOROTI
807160	8	MISIONES	7	SANTA ROSA	6	160	ITA HUGUA
807170	8	MISIONES	7	SANTA ROSA	6	170	ARROYO GONZALEZ
807180	8	MISIONES	7	SANTA ROSA	6	180	SAN ANTONIO
807190	8	MISIONES	7	SANTA ROSA	6	190	CERRO COSTA
807200	8	MISIONES	7	SANTA ROSA	6	200	GABINO ROJAS
807220	8	MISIONES	7	SANTA ROSA	6	220	SAN JOAQUIN
807230	8	MISIONES	7	SANTA ROSA	6	230	SAN FRANCISCO
807240	8	MISIONES	7	SANTA ROSA	6	240	SAN RAFAEL
807250	8	MISIONES	7	SANTA ROSA	6	250	SAN SOLANO
807255	8	MISIONES	7	SANTA ROSA	3	255	SAN SOLANO SUB-URBANO
807260	8	MISIONES	7	SANTA ROSA	6	260	YPUKU
807270	8	MISIONES	7	SANTA ROSA	6	270	JACAREY
807280	8	MISIONES	7	SANTA ROSA	6	280	POTRERO GUASU
807290	8	MISIONES	7	SANTA ROSA	6	290	SANTA ELENA
807300	8	MISIONES	7	SANTA ROSA	6	300	3 DE MAYO
807310	8	MISIONES	7	SANTA ROSA	6	310	YKUA SATI
807320	8	MISIONES	7	SANTA ROSA	6	320	ALCARAZ KUE
807330	8	MISIONES	7	SANTA ROSA	6	330	POTRERO ALTO
807340	8	MISIONES	7	SANTA ROSA	6	340	ZAPATERO KUE
807350	8	MISIONES	7	SANTA ROSA	6	350	FATIMA
807360	8	MISIONES	7	SANTA ROSA	6	360	SANTA TERESA
807370	8	MISIONES	7	SANTA ROSA	6	370	SAN JOSE
807380	8	MISIONES	7	SANTA ROSA	6	380	YATAI
807390	8	MISIONES	7	SANTA ROSA	6	390	SAN PEDRO
808002	8	MISIONES	8	SANTIAGO	1	2	FATIMA
808003	8	MISIONES	8	SANTIAGO	1	3	SAN MIGUEL
808004	8	MISIONES	8	SANTIAGO	1	4	CENTRO SAN JUAN
808005	8	MISIONES	8	SANTIAGO	1	5	CAACUPE
808100	8	MISIONES	8	SANTIAGO	6	100	SAN RAMON
808110	8	MISIONES	8	SANTIAGO	6	110	KA'AGUY GUASU
808120	8	MISIONES	8	SANTIAGO	6	120	TAMBORY
808130	8	MISIONES	8	SANTIAGO	6	130	SAN ROQUE
808140	8	MISIONES	8	SANTIAGO	6	140	ESTERO BELLACO
808150	8	MISIONES	8	SANTIAGO	6	150	SAN ANTONIO
808160	8	MISIONES	8	SANTIAGO	6	160	SANTA RITA
808170	8	MISIONES	8	SANTIAGO	6	170	SAN BLAS
808190	8	MISIONES	8	SANTIAGO	6	190	JACUTY
808200	8	MISIONES	8	SANTIAGO	6	200	SAN FELIPE
808210	8	MISIONES	8	SANTIAGO	6	210	CAAGUAZU-MI
808220	8	MISIONES	8	SANTIAGO	6	220	AREA DE ESTANCIAS
808230	8	MISIONES	8	SANTIAGO	6	230	SAN JUAN
808240	8	MISIONES	8	SANTIAGO	6	240	SAN MIGUEL
808250	8	MISIONES	8	SANTIAGO	6	250	KA'AGUY PO'I
808260	8	MISIONES	8	SANTIAGO	6	260	FATIMA
808270	8	MISIONES	8	SANTIAGO	6	270	MBOKAJATY
809001	8	MISIONES	9	VILLA FLORIDA	1	1	MANGA YVYRA
809002	8	MISIONES	9	VILLA FLORIDA	1	2	SAN MIGUEL
809003	8	MISIONES	9	VILLA FLORIDA	1	3	SAN ISIDRO
809004	8	MISIONES	9	VILLA FLORIDA	1	4	MANGA ITA
809005	8	MISIONES	9	VILLA FLORIDA	1	5	CENTRO
809006	8	MISIONES	9	VILLA FLORIDA	1	6	SAN FRANCISCO
809130	8	MISIONES	9	VILLA FLORIDA	6	130	SAN FRANCISCO
809140	8	MISIONES	9	VILLA FLORIDA	6	140	ZONA DE ESTANCIAS
810002	8	MISIONES	10	YABEBYRY	1	2	CENTRAL
810003	8	MISIONES	10	YABEBYRY	1	3	MARISCAL LOPEZ
810004	8	MISIONES	10	YABEBYRY	1	4	KUARAHY RESE
810120	8	MISIONES	10	YABEBYRY	6	120	PANCHITO LOPEZ
810130	8	MISIONES	10	YABEBYRY	6	130	LAGUNA PORA
810150	8	MISIONES	10	YABEBYRY	6	150	GALEANO KUE
810160	8	MISIONES	10	YABEBYRY	6	160	JUANITA PESOA
810170	8	MISIONES	10	YABEBYRY	6	170	MARISCAL LOPEZ
810180	8	MISIONES	10	YABEBYRY	6	180	PEGUAHO
810190	8	MISIONES	10	YABEBYRY	6	190	MBURUCUYA
810200	8	MISIONES	10	YABEBYRY	6	200	OBRERO
810210	8	MISIONES	10	YABEBYRY	6	210	LA FLOR
810220	8	MISIONES	10	YABEBYRY	6	220	SAN FRANCISCO
810230	8	MISIONES	10	YABEBYRY	6	230	KUARAHY RESE
901004	9	PARAGUARI	1	PARAGUARI	1	4	SANTO TOMAS
901005	9	PARAGUARI	1	PARAGUARI	1	5	PA'I GOMEZ
901006	9	PARAGUARI	1	PARAGUARI	1	6	CENTRO
901007	9	PARAGUARI	1	PARAGUARI	1	7	VIRGEN DE CAACUPE
901008	9	PARAGUARI	1	PARAGUARI	1	8	ASENT. SANTO DOMINGO
901009	9	PARAGUARI	1	PARAGUARI	1	9	SAN BLAS
901010	9	PARAGUARI	1	PARAGUARI	1	10	CIUDAD NUEVA
901011	9	PARAGUARI	1	PARAGUARI	1	11	SAN BLAS II
901012	9	PARAGUARI	1	PARAGUARI	1	12	SANTA CATALINA
901013	9	PARAGUARI	1	PARAGUARI	1	13	SUB MARIN
901014	9	PARAGUARI	1	PARAGUARI	1	14	NACIONAL
901015	9	PARAGUARI	1	PARAGUARI	1	15	SAN FRANCISCO
901016	9	PARAGUARI	1	PARAGUARI	1	16	URBANIZACION LA NEGRITA
901017	9	PARAGUARI	1	PARAGUARI	1	17	URBANIZACION VIRGEN DE GUADALUPE
901018	9	PARAGUARI	1	PARAGUARI	1	18	CALLE
901019	9	PARAGUARI	1	PARAGUARI	1	19	URBANIZACION ARASA POTY
901020	9	PARAGUARI	1	PARAGUARI	1	20	PISCINA
901021	9	PARAGUARI	1	PARAGUARI	1	21	FATIMA
901022	9	PARAGUARI	1	PARAGUARI	1	22	ESTACION
901023	9	PARAGUARI	1	PARAGUARI	1	23	SAN MIGUEL
901024	9	PARAGUARI	1	PARAGUARI	1	24	COMANDO DE ARTILLERIA VILLA MILITAR
901025	9	PARAGUARI	1	PARAGUARI	1	25	ALBORADA
901026	9	PARAGUARI	1	PARAGUARI	1	26	SANTO DOMINGO
901027	9	PARAGUARI	1	PARAGUARI	1	27	URBANIZACION LAS PALMERAS
901028	9	PARAGUARI	1	PARAGUARI	1	28	URBANIZACION MARIA DEL CARMEN
901029	9	PARAGUARI	1	PARAGUARI	1	29	URBANIZACION LOS GUAYABOS
901110	9	PARAGUARI	1	PARAGUARI	6	110	MBATOVI
901130	9	PARAGUARI	1	PARAGUARI	6	130	CERRO LEON
901170	9	PARAGUARI	1	PARAGUARI	6	170	COSTA SEGUNDA
901190	9	PARAGUARI	1	PARAGUARI	6	190	SOTO RUGUA
901200	9	PARAGUARI	1	PARAGUARI	6	200	COSTA PRIMERA
901206	9	PARAGUARI	1	PARAGUARI	6	206	MBATOVI SANTO TOMAS
901260	9	PARAGUARI	1	PARAGUARI	6	260	NUATI
901270	9	PARAGUARI	1	PARAGUARI	6	270	ESTANCIA ALFONSO
901290	9	PARAGUARI	1	PARAGUARI	6	290	AGROMONTE
901310	9	PARAGUARI	1	PARAGUARI	6	310	MBATOVI VIRGEN DEL ROSARIO
901320	9	PARAGUARI	1	PARAGUARI	6	320	CHOLOLO
901330	9	PARAGUARI	1	PARAGUARI	6	330	RAMAL A CARAPEGUA
902001	9	PARAGUARI	2	ACAHAY	1	1	MONSENOR BOGARIN
902002	9	PARAGUARI	2	ACAHAY	1	2	SAN BLAS
902003	9	PARAGUARI	2	ACAHAY	1	3	CENTRAL
902004	9	PARAGUARI	2	ACAHAY	1	4	15 DE AGOSTO
902005	9	PARAGUARI	2	ACAHAY	1	5	SAN ALFONSO
902006	9	PARAGUARI	2	ACAHAY	1	6	MONSENOR CARDENAS
902007	9	PARAGUARI	2	ACAHAY	1	7	SANTA LUCIA
902008	9	PARAGUARI	2	ACAHAY	1	8	SAN RAMON
902100	9	PARAGUARI	2	ACAHAY	6	100	YEGUARISO
902110	9	PARAGUARI	2	ACAHAY	6	110	VALOIS RIVAROLA
902120	9	PARAGUARI	2	ACAHAY	6	120	TAPYTANGUA
902130	9	PARAGUARI	2	ACAHAY	6	130	COSTA PENA
902140	9	PARAGUARI	2	ACAHAY	6	140	POTRERO ARCE
902150	9	PARAGUARI	2	ACAHAY	6	150	CERRO CORA
902160	9	PARAGUARI	2	ACAHAY	6	160	CERRO ICE
902170	9	PARAGUARI	2	ACAHAY	6	170	ISLA BAEZ
902180	9	PARAGUARI	2	ACAHAY	6	180	PINTOS
902190	9	PARAGUARI	2	ACAHAY	6	190	YVYRAITY
902200	9	PARAGUARI	2	ACAHAY	6	200	CERRO GUY
902210	9	PARAGUARI	2	ACAHAY	6	210	NU AHAI
902220	9	PARAGUARI	2	ACAHAY	6	220	ISLERIA
902230	9	PARAGUARI	2	ACAHAY	6	230	RECOLETA I
902240	9	PARAGUARI	2	ACAHAY	6	240	ARROYO VERDE
902250	9	PARAGUARI	2	ACAHAY	6	250	RINCON
902260	9	PARAGUARI	2	ACAHAY	6	260	COSTA BAEZ - JUKYTY
902270	9	PARAGUARI	2	ACAHAY	6	270	CARAGUATAY GUASU
902280	9	PARAGUARI	2	ACAHAY	6	280	CARAGUATAY MI
902290	9	PARAGUARI	2	ACAHAY	6	290	LAGUNA PYTA
902300	9	PARAGUARI	2	ACAHAY	6	300	COSTA BAEZ
902310	9	PARAGUARI	2	ACAHAY	6	310	ZANJITA
902320	9	PARAGUARI	2	ACAHAY	6	320	SAN RAMON
902330	9	PARAGUARI	2	ACAHAY	6	330	ITAKYTY
902340	9	PARAGUARI	2	ACAHAY	6	340	TAPE GUASU
902350	9	PARAGUARI	2	ACAHAY	6	350	POTRERITO
902360	9	PARAGUARI	2	ACAHAY	6	360	SAN ISIDRO
902370	9	PARAGUARI	2	ACAHAY	6	370	RIVAROLA KUE
903001	9	PARAGUARI	3	CAAPUCU	1	1	SAN SALVADOR
903002	9	PARAGUARI	3	CAAPUCU	1	2	SAN ROQUE
903003	9	PARAGUARI	3	CAAPUCU	1	3	SANTA LIBRADA
903004	9	PARAGUARI	3	CAAPUCU	1	4	SAGRADO CORAZON DE JESUS
903005	9	PARAGUARI	3	CAAPUCU	1	5	CENTRAL
903006	9	PARAGUARI	3	CAAPUCU	1	6	ESPIRITU SANTO
903100	9	PARAGUARI	3	CAAPUCU	6	100	MBOI KUATIA
903110	9	PARAGUARI	3	CAAPUCU	6	110	ITAPE
903120	9	PARAGUARI	3	CAAPUCU	6	120	TAPE GUASU
903130	9	PARAGUARI	3	CAAPUCU	6	130	YPUKU
903140	9	PARAGUARI	3	CAAPUCU	6	140	CAPILLITA
903150	9	PARAGUARI	3	CAAPUCU	6	150	CHARARA
903160	9	PARAGUARI	3	CAAPUCU	6	160	CAPILLA TUYA
903170	9	PARAGUARI	3	CAAPUCU	6	170	JAGUARETE KUA
903180	9	PARAGUARI	3	CAAPUCU	6	180	MONTIEL POTRERO
903190	9	PARAGUARI	3	CAAPUCU	6	190	COLONIA CNEL. JOSE V. MONGELOS
903200	9	PARAGUARI	3	CAAPUCU	6	200	YERE
903210	9	PARAGUARI	3	CAAPUCU	6	210	COLONIA TTE ROJAS
903220	9	PARAGUARI	3	CAAPUCU	6	220	SAN BLAS
903230	9	PARAGUARI	3	CAAPUCU	6	230	SANTA LIBRADA
904001	9	PARAGUARI	4	CABALLERO	1	1	MARIA AUXILIADORA
904002	9	PARAGUARI	4	CABALLERO	1	2	SANTA ROSA
904003	9	PARAGUARI	4	CABALLERO	1	3	VIRGEN DE FATIMA
904004	9	PARAGUARI	4	CABALLERO	1	4	SAN LUIS
904005	9	PARAGUARI	4	CABALLERO	1	5	8 DE DICIEMBRE
904006	9	PARAGUARI	4	CABALLERO	1	6	SAN JOSE
904100	9	PARAGUARI	4	CABALLERO	6	100	LINDERO
904110	9	PARAGUARI	4	CABALLERO	6	110	GUAVIRA
904120	9	PARAGUARI	4	CABALLERO	6	120	HORQUETA
904140	9	PARAGUARI	4	CABALLERO	6	140	ZORRILLA KUE
904150	9	PARAGUARI	4	CABALLERO	6	150	TENIENTE MARTINEZ
904160	9	PARAGUARI	4	CABALLERO	6	160	PIRAJUVY
904170	9	PARAGUARI	4	CABALLERO	6	170	CAMPICHUELO
904180	9	PARAGUARI	4	CABALLERO	6	180	CATALAN
904190	9	PARAGUARI	4	CABALLERO	6	190	POTRERO PUKU
904200	9	PARAGUARI	4	CABALLERO	6	200	ISLA SEGURA
904210	9	PARAGUARI	4	CABALLERO	6	210	POTRERO NARANJATY
904230	9	PARAGUARI	4	CABALLERO	6	230	CERRO NU
904240	9	PARAGUARI	4	CABALLERO	6	240	FRANCO NU
904250	9	PARAGUARI	4	CABALLERO	6	250	LOMA PYTA
904260	9	PARAGUARI	4	CABALLERO	6	260	POTRERO YVATE
904270	9	PARAGUARI	4	CABALLERO	6	270	FRANCO I
904280	9	PARAGUARI	4	CABALLERO	6	280	IRIARTE PRIMERO
904290	9	PARAGUARI	4	CABALLERO	6	290	KIRITO
904300	9	PARAGUARI	4	CABALLERO	6	300	CHAURIA
904310	9	PARAGUARI	4	CABALLERO	6	310	IRIARTE SEGUNDO
904320	9	PARAGUARI	4	CABALLERO	6	320	IRIARTE TERCERO
904330	9	PARAGUARI	4	CABALLERO	6	330	COSTA PUKU
904340	9	PARAGUARI	4	CABALLERO	6	340	CERRO HORQUETA
904350	9	PARAGUARI	4	CABALLERO	6	350	FRANCO NU OCULTO
905001	9	PARAGUARI	5	CARAPEGUA	1	1	SANTO DOMINGO
905002	9	PARAGUARI	5	CARAPEGUA	1	2	SAN MIGUEL
905003	9	PARAGUARI	5	CARAPEGUA	1	3	SAN FRANCISCO
905004	9	PARAGUARI	5	CARAPEGUA	1	4	VIRGEN DEL CARMEN
905005	9	PARAGUARI	5	CARAPEGUA	1	5	SAN JOSE
905006	9	PARAGUARI	5	CARAPEGUA	1	6	SAN BLAS
905007	9	PARAGUARI	5	CARAPEGUA	1	7	CENTRAL
905008	9	PARAGUARI	5	CARAPEGUA	1	8	MARIA AUXILIADORA
905009	9	PARAGUARI	5	CARAPEGUA	1	9	SAN ROQUE
905010	9	PARAGUARI	5	CARAPEGUA	1	10	SAN CAYETANO
905011	9	PARAGUARI	5	CARAPEGUA	1	11	ASENT. NUEVA ESPERANZA
905012	9	PARAGUARI	5	CARAPEGUA	1	12	FATIMA
905013	9	PARAGUARI	5	CARAPEGUA	1	13	SAGRADO CORAZON DE JESUS
905100	9	PARAGUARI	5	CARAPEGUA	6	100	ISLA YVATE
905110	9	PARAGUARI	5	CARAPEGUA	6	110	CERRITO
905120	9	PARAGUARI	5	CARAPEGUA	6	120	ESPARTILLAR
905130	9	PARAGUARI	5	CARAPEGUA	6	130	PACHECO
905140	9	PARAGUARI	5	CARAPEGUA	6	140	POTRERO
905150	9	PARAGUARI	5	CARAPEGUA	6	150	NDAVARU
905160	9	PARAGUARI	5	CARAPEGUA	6	160	TAJY LOMA
905170	9	PARAGUARI	5	CARAPEGUA	6	170	CALIXTRO
905180	9	PARAGUARI	5	CARAPEGUA	6	180	AGUAIY
905190	9	PARAGUARI	5	CARAPEGUA	6	190	BENI LOMA
905220	9	PARAGUARI	5	CARAPEGUA	6	220	CAAZAPA
905230	9	PARAGUARI	5	CARAPEGUA	6	230	CANETE KUE
905260	9	PARAGUARI	5	CARAPEGUA	6	260	FRANCO ISLA
905270	9	PARAGUARI	5	CARAPEGUA	6	270	CAAPUCUMI
905290	9	PARAGUARI	5	CARAPEGUA	6	290	EL PORTAL SAN VICENTE
905330	9	PARAGUARI	5	CARAPEGUA	6	330	TAJY PUNTA
905340	9	PARAGUARI	5	CARAPEGUA	6	340	SANTA MARGARITA
905350	9	PARAGUARI	5	CARAPEGUA	6	350	MARIA AUXILIADORA
905360	9	PARAGUARI	5	CARAPEGUA	6	360	CERRITO 2
905370	9	PARAGUARI	5	CARAPEGUA	6	370	VIRGEN DEL CARMEN
905380	9	PARAGUARI	5	CARAPEGUA	6	380	SANTO DOMINGO
906001	9	PARAGUARI	6	ESCOBAR	1	1	CENTRO
906180	9	PARAGUARI	6	ESCOBAR	6	180	GENERAL AQUINO
906190	9	PARAGUARI	6	ESCOBAR	6	190	YVYRAITY
906200	9	PARAGUARI	6	ESCOBAR	6	200	MBOKAJATY
906210	9	PARAGUARI	6	ESCOBAR	6	210	MBOPI KUA
906230	9	PARAGUARI	6	ESCOBAR	6	230	ARROYO PORA
906240	9	PARAGUARI	6	ESCOBAR	6	240	LOMA
906250	9	PARAGUARI	6	ESCOBAR	6	250	ESTANCIA ABELENDA
906260	9	PARAGUARI	6	ESCOBAR	6	260	POTRERO JARA
906270	9	PARAGUARI	6	ESCOBAR	6	270	CHIRCAL
906280	9	PARAGUARI	6	ESCOBAR	6	280	GUASU KUA
906290	9	PARAGUARI	6	ESCOBAR	6	290	1 DE JUNIO
906300	9	PARAGUARI	6	ESCOBAR	6	300	CERRO CORA CANADA
907001	9	PARAGUARI	7	LA COLMENA	1	1	SANTA CATALINA
907002	9	PARAGUARI	7	LA COLMENA	1	2	VIRGEN DEL CARMEN
907003	9	PARAGUARI	7	LA COLMENA	1	3	VIRGEN DE FATIMA
907004	9	PARAGUARI	7	LA COLMENA	1	4	SAN JOSE
907005	9	PARAGUARI	7	LA COLMENA	1	5	SAN FRANCISCO
907006	9	PARAGUARI	7	LA COLMENA	1	6	SAN RAFAEL
907120	9	PARAGUARI	7	LA COLMENA	6	120	RORY
907130	9	PARAGUARI	7	LA COLMENA	6	130	SAN RAMON
907140	9	PARAGUARI	7	LA COLMENA	6	140	YVAROTY
907150	9	PARAGUARI	7	LA COLMENA	6	150	YAHAPETY
907160	9	PARAGUARI	7	LA COLMENA	6	160	BARRERO AZUL
907170	9	PARAGUARI	7	LA COLMENA	6	170	KA'ATYMI
907180	9	PARAGUARI	7	LA COLMENA	6	180	COMPANIA FATIMA
907190	9	PARAGUARI	7	LA COLMENA	6	190	MBOKAJATY
907210	9	PARAGUARI	7	LA COLMENA	6	210	POTRERO ALTO
907220	9	PARAGUARI	7	LA COLMENA	6	220	PINDOTY
908001	9	PARAGUARI	8	MBUYAPEY	1	1	VIRGEN DE ITATI 2
908002	9	PARAGUARI	8	MBUYAPEY	1	2	VIRGEN DE LOS DOLORES
908003	9	PARAGUARI	8	MBUYAPEY	1	3	PERPETUO SOCORRO
908004	9	PARAGUARI	8	MBUYAPEY	1	4	VIRGEN DE LOURDES
908005	9	PARAGUARI	8	MBUYAPEY	1	5	SANTA TERESITA
908006	9	PARAGUARI	8	MBUYAPEY	1	6	VIRGEN DEL ROSARIO
908007	9	PARAGUARI	8	MBUYAPEY	1	7	VIRGEN DE FATIMA
908008	9	PARAGUARI	8	MBUYAPEY	1	8	MARIA AUXILIADORA
908009	9	PARAGUARI	8	MBUYAPEY	1	9	VIRGEN DEL CARMEN
908010	9	PARAGUARI	8	MBUYAPEY	1	10	INMACULADA CONCEPCION
908011	9	PARAGUARI	8	MBUYAPEY	1	11	VIRGEN DE ITATI 1
908100	9	PARAGUARI	8	MBUYAPEY	6	100	NUA'I
908110	9	PARAGUARI	8	MBUYAPEY	6	110	RECTA
908120	9	PARAGUARI	8	MBUYAPEY	6	120	CULANTRILLO
908130	9	PARAGUARI	8	MBUYAPEY	6	130	ISLA NARANJA
908140	9	PARAGUARI	8	MBUYAPEY	6	140	NU APU'AMI
908150	9	PARAGUARI	8	MBUYAPEY	6	150	NU APU'A
908190	9	PARAGUARI	8	MBUYAPEY	6	190	BOQUERON
908200	9	PARAGUARI	8	MBUYAPEY	6	200	COSTA PUKU
908210	9	PARAGUARI	8	MBUYAPEY	6	210	LOMA GUASU
908220	9	PARAGUARI	8	MBUYAPEY	6	220	CERRITO
908240	9	PARAGUARI	8	MBUYAPEY	6	240	LABARU
908250	9	PARAGUARI	8	MBUYAPEY	6	250	COSTA CAPILLA KUE
908260	9	PARAGUARI	8	MBUYAPEY	6	260	ISLA ALTA
908270	9	PARAGUARI	8	MBUYAPEY	6	270	NANDU RUGUA
908280	9	PARAGUARI	8	MBUYAPEY	6	280	ROA RUGUA
908290	9	PARAGUARI	8	MBUYAPEY	6	290	LOMITA
908300	9	PARAGUARI	8	MBUYAPEY	6	300	COLONIA MARIA ANTONIA
908310	9	PARAGUARI	8	MBUYAPEY	6	310	LOMA'I
908320	9	PARAGUARI	8	MBUYAPEY	6	320	KA'AGUY POTY
908330	9	PARAGUARI	8	MBUYAPEY	6	330	LIZ Y FRANCISCO
908340	9	PARAGUARI	8	MBUYAPEY	6	340	PIRAY
908350	9	PARAGUARI	8	MBUYAPEY	6	350	TUNA
908360	9	PARAGUARI	8	MBUYAPEY	6	360	LA ROSA
908370	9	PARAGUARI	8	MBUYAPEY	6	370	SAN FRANCISCO
908380	9	PARAGUARI	8	MBUYAPEY	6	380	SAN JUAN
908390	9	PARAGUARI	8	MBUYAPEY	6	390	RIVAROLA
909001	9	PARAGUARI	9	PIRAYU	1	1	BARRIO 1
909002	9	PARAGUARI	9	PIRAYU	1	2	BARRIO 2
909003	9	PARAGUARI	9	PIRAYU	1	3	BARRIO 3
909004	9	PARAGUARI	9	PIRAYU	1	4	BARRIO 4
909100	9	PARAGUARI	9	PIRAYU	6	100	AZCURRA
909110	9	PARAGUARI	9	PIRAYU	6	110	COSTA HU
909120	9	PARAGUARI	9	PIRAYU	6	120	TUJUKUA
909130	9	PARAGUARI	9	PIRAYU	6	130	TAVA'I
909140	9	PARAGUARI	9	PIRAYU	6	140	CERRO VERA
909150	9	PARAGUARI	9	PIRAYU	6	150	PASO ESPERANZA
909160	9	PARAGUARI	9	PIRAYU	6	160	POTRERO AVENDANO
909170	9	PARAGUARI	9	PIRAYU	6	170	TUJUKUA KOKUE
909180	9	PARAGUARI	9	PIRAYU	6	180	YKUA  KA'U
909200	9	PARAGUARI	9	PIRAYU	6	200	CERRO LEON
909210	9	PARAGUARI	9	PIRAYU	6	210	YAGUARON JURU
909220	9	PARAGUARI	9	PIRAYU	6	220	ARROYO SERVIN
909230	9	PARAGUARI	9	PIRAYU	6	230	POTRERO GUASU
909240	9	PARAGUARI	9	PIRAYU	6	240	KA'AGUY POTY
909250	9	PARAGUARI	9	PIRAYU	6	250	ZONA ALTA
910001	9	PARAGUARI	10	QUIINDY	1	1	SAGRADA FAMILIA
910002	9	PARAGUARI	10	QUIINDY	1	2	GENERAL BERNARDINO CABALLERO
910003	9	PARAGUARI	10	QUIINDY	1	3	SANTA MARIA
910004	9	PARAGUARI	10	QUIINDY	1	4	SAN LORENZO
910005	9	PARAGUARI	10	QUIINDY	1	5	SAGRADO CORAZON DE JESUS
910006	9	PARAGUARI	10	QUIINDY	1	6	SAN ANTONIO
910007	9	PARAGUARI	10	QUIINDY	1	7	NINO JESUS
910008	9	PARAGUARI	10	QUIINDY	1	8	BARRIO AMISTAD
910120	9	PARAGUARI	10	QUIINDY	6	120	ISLA ALTA
910130	9	PARAGUARI	10	QUIINDY	6	130	COMANDANTE CARMELO PERALTA
910140	9	PARAGUARI	10	QUIINDY	6	140	ACHOTEI
910160	9	PARAGUARI	10	QUIINDY	6	160	ITA CAJON
910170	9	PARAGUARI	10	QUIINDY	6	170	TOVATINGUA
910180	9	PARAGUARI	10	QUIINDY	6	180	MENDIETA KUE
910230	9	PARAGUARI	10	QUIINDY	6	230	LOMA PYTA
910240	9	PARAGUARI	10	QUIINDY	6	240	COSTA IRALA
910260	9	PARAGUARI	10	QUIINDY	6	260	CAPILLA TUJA
910300	9	PARAGUARI	10	QUIINDY	6	300	COSTA HU'U
910320	9	PARAGUARI	10	QUIINDY	6	320	VALLE APU'A
910330	9	PARAGUARI	10	QUIINDY	6	330	LAURELTY
910340	9	PARAGUARI	10	QUIINDY	6	340	NINO TAKUARY
910350	9	PARAGUARI	10	QUIINDY	6	350	VILLA JARDIN
910360	9	PARAGUARI	10	QUIINDY	6	360	HUGUA PO'I
910370	9	PARAGUARI	10	QUIINDY	6	370	COSTA GAONA
910380	9	PARAGUARI	10	QUIINDY	6	380	3 DE MAYO
911001	9	PARAGUARI	11	QUYQUYHO	1	1	SAN MIGUEL
911002	9	PARAGUARI	11	QUYQUYHO	1	2	SAN PEDRO Y SAN PABLO
911003	9	PARAGUARI	11	QUYQUYHO	1	3	CENTRAL
911120	9	PARAGUARI	11	QUYQUYHO	6	120	ISLA VALLE
911130	9	PARAGUARI	11	QUYQUYHO	6	130	KURUSU LACU
911140	9	PARAGUARI	11	QUYQUYHO	6	140	SAN LUIS
911150	9	PARAGUARI	11	QUYQUYHO	6	150	GUASU KORA
911160	9	PARAGUARI	11	QUYQUYHO	6	160	COSTA OLAZAR
911180	9	PARAGUARI	11	QUYQUYHO	6	180	CERRO FRENTE
911190	9	PARAGUARI	11	QUYQUYHO	6	190	CERRO GUY
911210	9	PARAGUARI	11	QUYQUYHO	6	210	MBOI KA'E
911220	9	PARAGUARI	11	QUYQUYHO	6	220	ESPINILLAR
911230	9	PARAGUARI	11	QUYQUYHO	6	230	LOMA GUASU
911240	9	PARAGUARI	11	QUYQUYHO	6	240	SAN JOSE OBRERO
911250	9	PARAGUARI	11	QUYQUYHO	6	250	COSTA ALEGRE
911260	9	PARAGUARI	11	QUYQUYHO	6	260	SAN MIGUEL
911270	9	PARAGUARI	11	QUYQUYHO	6	270	JAGUARY MBOPYRI
911280	9	PARAGUARI	11	QUYQUYHO	6	280	CERRO GUY SAN JUAN
911290	9	PARAGUARI	11	QUYQUYHO	6	290	SAN FRANCISCO
911300	9	PARAGUARI	11	QUYQUYHO	6	300	LAS MERCEDES
911310	9	PARAGUARI	11	QUYQUYHO	6	310	SAN JORGE
911320	9	PARAGUARI	11	QUYQUYHO	6	320	MBOKAJATY
912001	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	1	1	INMACULADA CONCEPCION
912002	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	1	2	SAGRADO CORAZON DE JESUS
912003	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	1	3	SAN VICENTE
912004	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	1	4	VIRGEN DEL ROSARIO
912100	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	100	MATACHI
912110	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	110	RINCON
912120	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	120	ARASATY
912130	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	130	CERRITO
912140	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	140	MOQUETE
912150	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	150	MBOKAJATY
912160	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	160	CANADA
912170	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	170	POTRERO
912180	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	180	SIMBROM
912190	9	PARAGUARI	12	ROQUE GONZALEZ DE SANTA CRUZ	6	190	VALLE PUKU
913001	9	PARAGUARI	13	SAPUCAI	1	1	PLANTA URBANA
913002	9	PARAGUARI	13	SAPUCAI	1	2	SANTA TERESITA
913004	9	PARAGUARI	13	SAPUCAI	1	4	MARIA AUXILIADORA
913005	9	PARAGUARI	13	SAPUCAI	1	5	VIRGEN DEL CARMEN
913006	9	PARAGUARI	13	SAPUCAI	1	6	TIERRA NEGRA
913007	9	PARAGUARI	13	SAPUCAI	1	7	ADRIANO IRALA
913008	9	PARAGUARI	13	SAPUCAI	1	8	SAN BLAS
913100	9	PARAGUARI	13	SAPUCAI	6	100	YTORORO
913120	9	PARAGUARI	13	SAPUCAI	6	120	COSTA IRALA
913130	9	PARAGUARI	13	SAPUCAI	6	130	CERRO ROKE
913140	9	PARAGUARI	13	SAPUCAI	6	140	YVYRAITY
913150	9	PARAGUARI	13	SAPUCAI	6	150	ARROYO PORA
913160	9	PARAGUARI	13	SAPUCAI	6	160	ADRIANO IRALA
913170	9	PARAGUARI	13	SAPUCAI	6	170	MBOKAJA
913180	9	PARAGUARI	13	SAPUCAI	6	180	CERRO VERDE
913190	9	PARAGUARI	13	SAPUCAI	6	190	POTRERO YVATE
913200	9	PARAGUARI	13	SAPUCAI	6	200	POTRERO VILLALBA
913220	9	PARAGUARI	13	SAPUCAI	6	220	MBOPI KUA
913230	9	PARAGUARI	13	SAPUCAI	6	230	BOLAS KUA
913240	9	PARAGUARI	13	SAPUCAI	6	240	COLONIA SANTA ISABEL
913250	9	PARAGUARI	13	SAPUCAI	6	250	PASO PE
913260	9	PARAGUARI	13	SAPUCAI	6	260	JARIGUA'AMI
913270	9	PARAGUARI	13	SAPUCAI	6	270	LOMA GUASU
913280	9	PARAGUARI	13	SAPUCAI	6	280	COSTA PO'I
913290	9	PARAGUARI	13	SAPUCAI	3	290	ARROYO PORA - SUB URBANO
913300	9	PARAGUARI	13	SAPUCAI	6	300	CERRO CORA
914001	9	PARAGUARI	14	TEBICUARY-MI	1	1	CENTRO
914100	9	PARAGUARI	14	TEBICUARY-MI	6	100	CAOEBI
914110	9	PARAGUARI	14	TEBICUARY-MI	6	110	POTRERITO
914120	9	PARAGUARI	14	TEBICUARY-MI	6	120	PAREDES COSTA
914130	9	PARAGUARI	14	TEBICUARY-MI	6	130	RECOLETA
914140	9	PARAGUARI	14	TEBICUARY-MI	6	140	CERRITO
914160	9	PARAGUARI	14	TEBICUARY-MI	6	160	PUNTA GUASU
914170	9	PARAGUARI	14	TEBICUARY-MI	6	170	COLONIA CESPEDES
914180	9	PARAGUARI	14	TEBICUARY-MI	6	180	SAN BLAS
914190	9	PARAGUARI	14	TEBICUARY-MI	6	190	ALONSO KUE
914200	9	PARAGUARI	14	TEBICUARY-MI	6	200	NUA HU
914210	9	PARAGUARI	14	TEBICUARY-MI	6	210	HUGUA'I
914220	9	PARAGUARI	14	TEBICUARY-MI	6	220	SYRYKA
915001	9	PARAGUARI	15	YAGUARON	1	1	SAN FRANCISCO
915002	9	PARAGUARI	15	YAGUARON	1	2	SAN ROQUE
915003	9	PARAGUARI	15	YAGUARON	1	3	SANTA LIBRADA
915004	9	PARAGUARI	15	YAGUARON	1	4	SAN MIGUEL
915005	9	PARAGUARI	15	YAGUARON	1	5	SAN JOSE
915006	9	PARAGUARI	15	YAGUARON	1	6	MBARITU
915007	9	PARAGUARI	15	YAGUARON	1	7	ASENT. 1RO DE MAYO
915008	9	PARAGUARI	15	YAGUARON	1	8	KARUNGUA
915009	9	PARAGUARI	15	YAGUARON	1	9	YSATY
915100	9	PARAGUARI	15	YAGUARON	6	100	ITA POTRERO
915110	9	PARAGUARI	15	YAGUARON	6	110	KA'AGUY POTI
915120	9	PARAGUARI	15	YAGUARON	6	120	SAGUASU
915130	9	PARAGUARI	15	YAGUARON	6	130	SAN BONINI
915140	9	PARAGUARI	15	YAGUARON	6	140	PIRAYU CALLE
915150	9	PARAGUARI	15	YAGUARON	6	150	GUAJAYVITY
915160	9	PARAGUARI	15	YAGUARON	6	160	ZAYAS
915170	9	PARAGUARI	15	YAGUARON	6	170	PORORO
915180	9	PARAGUARI	15	YAGUARON	6	180	MBARITU
915190	9	PARAGUARI	15	YAGUARON	6	190	NANDU'A
915200	9	PARAGUARI	15	YAGUARON	6	200	GUARAPI
915210	9	PARAGUARI	15	YAGUARON	6	210	TAKUARINDY
915220	9	PARAGUARI	15	YAGUARON	6	220	KARUNGUA
915230	9	PARAGUARI	15	YAGUARON	6	230	TAKUMBU
915240	9	PARAGUARI	15	YAGUARON	6	240	PEGUAHO
915250	9	PARAGUARI	15	YAGUARON	6	250	CURUPAYTY
915260	9	PARAGUARI	15	YAGUARON	6	260	CERRO GUY
915270	9	PARAGUARI	15	YAGUARON	6	270	CALLE PO'I
915280	9	PARAGUARI	15	YAGUARON	6	280	NUATI CALLE
915290	9	PARAGUARI	15	YAGUARON	6	290	POTRERITO
915300	9	PARAGUARI	15	YAGUARON	6	300	NUATI GUASU
915310	9	PARAGUARI	15	YAGUARON	6	310	HUGUA PO'I - NUATI
916001	9	PARAGUARI	16	YBYCUI	1	1	VIRGEN DE FATIMA
916002	9	PARAGUARI	16	YBYCUI	1	2	SAN FRANCISCO
916003	9	PARAGUARI	16	YBYCUI	1	3	SAN JOSE
916004	9	PARAGUARI	16	YBYCUI	1	4	SANTA ROSA
916005	9	PARAGUARI	16	YBYCUI	1	5	SANTA TERESA
916006	9	PARAGUARI	16	YBYCUI	1	6	CHE PUEBLO PORA 2
916007	9	PARAGUARI	16	YBYCUI	1	7	SAN MIGUEL
916008	9	PARAGUARI	16	YBYCUI	1	8	SAGRADO CORAZON DE JESUS
916009	9	PARAGUARI	16	YBYCUI	1	9	CHE PUEBLO PORA 1
916010	9	PARAGUARI	16	YBYCUI	1	10	SANTA TERESITA
916100	9	PARAGUARI	16	YBYCUI	6	100	CERRO ACHON
916110	9	PARAGUARI	16	YBYCUI	6	110	CORDILLERITA (APYRAGUA)
916120	9	PARAGUARI	16	YBYCUI	6	120	ISLA ALTA
916130	9	PARAGUARI	16	YBYCUI	6	130	CERRO KARAPE
916140	9	PARAGUARI	16	YBYCUI	6	140	CABALLERO PUNTA
916150	9	PARAGUARI	16	YBYCUI	6	150	CESAR BARRIENTOS
916160	9	PARAGUARI	16	YBYCUI	6	160	VARGAS LOMA
916170	9	PARAGUARI	16	YBYCUI	6	170	ISLA PA'U
916180	9	PARAGUARI	16	YBYCUI	6	180	BOLICHO KUE
916200	9	PARAGUARI	16	YBYCUI	6	200	PASO PARED
916220	9	PARAGUARI	16	YBYCUI	6	220	CORDILLERA
916230	9	PARAGUARI	16	YBYCUI	6	230	ARASATY
916240	9	PARAGUARI	16	YBYCUI	6	240	MBOPI KUA
916260	9	PARAGUARI	16	YBYCUI	6	260	ISLA KA'A
916280	9	PARAGUARI	16	YBYCUI	6	280	PASO PINDO
916290	9	PARAGUARI	16	YBYCUI	6	290	ISLA YVATE
916300	9	PARAGUARI	16	YBYCUI	6	300	KARAGUATA
916320	9	PARAGUARI	16	YBYCUI	6	320	YBYCUI PUNTA
916330	9	PARAGUARI	16	YBYCUI	6	330	YATAITY
916350	9	PARAGUARI	16	YBYCUI	6	350	CERRO SAUCE
916360	9	PARAGUARI	16	YBYCUI	6	360	MBOKAJA PUKU
916370	9	PARAGUARI	16	YBYCUI	6	370	ENTRE RIOS
916380	9	PARAGUARI	16	YBYCUI	6	380	RINCON GUASU
916390	9	PARAGUARI	16	YBYCUI	6	390	PALACIO KUE
916400	9	PARAGUARI	16	YBYCUI	6	400	CARBON KUE
916410	9	PARAGUARI	16	YBYCUI	6	410	TACUARY
916420	9	PARAGUARI	16	YBYCUI	6	420	MINAS KUE
916430	9	PARAGUARI	16	YBYCUI	6	430	KA'AGUY KUPE
916450	9	PARAGUARI	16	YBYCUI	6	450	SANTA ANGELA
916455	9	PARAGUARI	16	YBYCUI	3	455	SANTA ANGELA - SUB-URBANO
916460	9	PARAGUARI	16	YBYCUI	6	460	PEREIRA KUE
916470	9	PARAGUARI	16	YBYCUI	6	470	SANTA TERESITA
916480	9	PARAGUARI	16	YBYCUI	6	480	AGUSTIN GOIBURU
916490	9	PARAGUARI	16	YBYCUI	6	490	SAN MIGUEL
916500	9	PARAGUARI	16	YBYCUI	6	500	RINCON'I MOLINAS
916510	9	PARAGUARI	16	YBYCUI	6	510	CAPILLITA
916520	9	PARAGUARI	16	YBYCUI	6	520	CANADA
916530	9	PARAGUARI	16	YBYCUI	6	530	SAN JOSE BOQUERON
916540	9	PARAGUARI	16	YBYCUI	6	540	CERRO CORA
916550	9	PARAGUARI	16	YBYCUI	6	550	RINCON FLORIDO
916560	9	PARAGUARI	16	YBYCUI	6	560	RINCON NUAI
916570	9	PARAGUARI	16	YBYCUI	3	570	CESAR BARRIENTOS - SUB URBANO
916580	9	PARAGUARI	16	YBYCUI	3	580	AGUSTIN GOIBURU - SUB URBANO
917001	9	PARAGUARI	17	YBYTYMI	1	1	SAGRADO CORAZON DE JESUS
917002	9	PARAGUARI	17	YBYTYMI	1	2	SAN ROQUE
917003	9	PARAGUARI	17	YBYTYMI	1	3	VIRGEN DEL ROSARIO
917004	9	PARAGUARI	17	YBYTYMI	1	4	FATIMA
917005	9	PARAGUARI	17	YBYTYMI	1	5	SAN LUIS
917120	9	PARAGUARI	17	YBYTYMI	6	120	ISLA
917130	9	PARAGUARI	17	YBYTYMI	6	130	ANAZCO
917170	9	PARAGUARI	17	YBYTYMI	6	170	RINCON
917180	9	PARAGUARI	17	YBYTYMI	6	180	CERRO GUY
917190	9	PARAGUARI	17	YBYTYMI	6	190	HECTOR L. VERA
917210	9	PARAGUARI	17	YBYTYMI	6	210	CERRO SAN ANTONIO
917220	9	PARAGUARI	17	YBYTYMI	6	220	CANADA
917240	9	PARAGUARI	17	YBYTYMI	6	240	CHINI
917250	9	PARAGUARI	17	YBYTYMI	6	250	VARGAS KUE
917260	9	PARAGUARI	17	YBYTYMI	6	260	POTRERO GARAY
917270	9	PARAGUARI	17	YBYTYMI	6	270	HUGUA GUASU
917280	9	PARAGUARI	17	YBYTYMI	6	280	MARTINEZ KUE
917290	9	PARAGUARI	17	YBYTYMI	6	290	TUCUMAN
917300	9	PARAGUARI	17	YBYTYMI	6	300	SOLANO ESCOBAR
917310	9	PARAGUARI	17	YBYTYMI	6	310	RIVAROLA KUE
917320	9	PARAGUARI	17	YBYTYMI	6	320	KA'ATYMI LA COLMENA
917330	9	PARAGUARI	17	YBYTYMI	6	330	PASO KUPE
1001001	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	1	11 A 13 ACARAY
1001003	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	3	SAN JUAN - 7 A 9 ACARAY
1001004	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	4	DON BOSCO
1001008	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	8	PABLO ROJAS
1001009	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	9	SAN BLAS
1001010	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	10	MICROCENTRO
1001011	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	11	JUAN E O'LEARY
1001012	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	12	BOQUERON
1001013	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	13	AREA 1
1001014	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	14	SAN JOSE
1001015	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	15	VILLA 23 DE OCTUBRE
1001017	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	17	AREA 8
1001019	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	19	SAN MIGUEL - AREA 2
1001020	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	20	REMANSITO
1001023	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	23	SANTA ANA
1001024	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	24	CIUDAD NUEVA
1001025	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	25	8 A 9 MONDAY
1001026	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	26	11 A 13 MONDAY
1001027	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	27	GENERAL BERNARDINO CABALLERO
1001028	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	28	CHE LA REINA
1001029	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	29	LA BLANCA
1001030	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	30	CAROLINA - LAS MERCEDES - MBURUCUYA
1001031	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	31	SAN JUAN - JARDIN DEL ESTE - 7 A 9 ACARAY
1001032	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	32	9 A 11 MONDAY
1001033	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	33	SAN ALFREDO
1001034	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	34	VILLA FANNY
1001035	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	35	SAN ISIDRO
1001036	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	36	SAN LUCAS
1001037	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	37	AREA 4
1001038	10	ALTO PARANA	1	CIUDAD DEL ESTE	1	38	AREA 3
1002001	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	1	SAN ANTONIO
1002002	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	2	SAN PABLO - SAN JUAN
1002003	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	3	FATIMA 1
1002004	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	4	LAS MERCEDES
1002005	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	5	SAN RAFAEL
1002006	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	6	AREA 5
1002007	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	7	SAN SEBASTIAN
1002008	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	8	SANTO TOMAS
1002009	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	9	SAN LORENZO
1002010	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	10	SAN MIGUEL
1002011	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	11	MARIA AUXILIADORA
1002012	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	12	SAN MIGUEL - VILLA BAJA
1002013	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	13	SAN ROQUE
1002014	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	14	SAN FRANCISCO
1002015	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	15	SANTA ROSA
1002016	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	16	SAN JOSE OBRERO
1002017	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	17	FRAY LUIS DE BOLANOS
1002018	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	18	CONAVI SANTA CLARA
1002019	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	19	SANTA CLARA
1002020	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	20	CALLE 7
1002021	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	21	CONAVI SAN ISIDRO
1002022	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	22	FATIMA 2
1002023	10	ALTO PARANA	2	PRESIDENTE FRANCO	1	23	SAGRADO CORAZON DE JESUS
1002110	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	110	KILOMETRO 10 MONDAY
1002120	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	120	SANTO DOMINGO
1002140	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	140	SAN JORGE
1002150	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	150	SAN ISIDRO
1002160	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	160	PUERTO FLORES
1002170	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	170	COLONIA ALFREDO PLA
1002180	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	180	PENINSULA
1002210	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	210	COM INDIG PUERTO BERTONI
1002220	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	220	COM INDIG YVYRA MOA - PUERTO GIMENEZ
1002230	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	230	COM INDIG PUESTO CUE - MEDIO MUNDO
1002240	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	240	ASENT. SANTA CLARA
1002250	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	250	ASENT. LIBERTAD
1002260	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	260	KILOMETRO 9 MONDAY
1002270	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	270	PUEBLO DE DIOS SANTO DOMINGO
1002280	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	280	CONAVI SANTO DOMINGO
1002290	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	290	ASENT. SANTO DOMINGO
1002300	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	300	ASENT. REDENCION
1002310	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	310	ASENT. SAN ISIDRO
1002320	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	320	COM INDIG PUERTO BARRETO
1002330	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	330	COM INDIG 8 DE DICIEMBRE
1002350	10	ALTO PARANA	2	PRESIDENTE FRANCO	6	350	COM INDIG JOYVY MIRI POTY
1003001	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	1	1	URBANO
1003002	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	1	2	VILLA ALTA
1003003	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	1	3	PRIMAVERA
1003004	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	1	4	SAN PEDRO
1003100	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	100	ITALIANO-KUE
1003110	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	110	ITUTI
1003120	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	3	120	COLONIA JEPOPYHY - SAN ISDRO SUB-URBANO
1003130	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	130	COLONIA JEPOPYHY
1003135	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	3	135	COLONIA JEPOPYHY  SUB-URBANO
1003160	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	160	PUERTO TABUCAY
1003170	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	170	COLONIA PIRA PYTA
1003180	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	180	360 HECTAREAS
1003190	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	190	SARITA
1003200	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	200	HACIENDA IVP
1003210	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	210	PIRA PYTAMI
1003220	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	220	CAPILLA
1003230	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	230	BOQUERON
1003240	10	ALTO PARANA	3	DOMINGO MARTINEZ DE IRALA	6	240	ITA VERA
1004001	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	1	1	SAN ANTONIO
1004002	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	1	2	SAN FRANCISCO
1004003	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	1	3	SANTA ROSA
1004004	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	1	4	INMACULADA
1004005	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	1	5	SANTA LIBRADA
1004130	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	130	POTRERO JARDIN NORTE
1004160	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	160	PAZ DEL CHACO
1004170	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	170	POTRERO JARDIN SUR
1004180	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	180	KA'A RENDY GUASU
1004200	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	200	LOMA PIROY
1004220	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	220	VENECIA'I
1004240	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	240	YHOVY
1004260	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	260	LOMA TAJY
1004270	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	270	VENECIA GUASU
1004280	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	280	SANTA CATALINA
1004290	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	290	SAN ISIDRO
1004300	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	300	LA VICTORIA 2
1004310	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	310	VILLA SAN JUAN
1004320	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	320	SAN ANTONIO
1004330	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	330	SANTA LIBRADA
1004340	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	340	8 DE DICIEMBRE
1004350	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	350	KA'ARENDY GUASU SAN CRISTOBAL
1004360	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	360	SAN BLAS
1004370	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	370	CRISTO REY 4000
1004380	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	380	SAN MIGUEL
1004390	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	390	LA VICTORIA 1
1004400	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	400	SANTA ANA
1004420	10	ALTO PARANA	4	DR. JUAN LEON MALLORQUIN	6	420	ASENT. LOMA  PIRO'Y
1005001	10	ALTO PARANA	5	HERNANDARIAS	1	1	SAN ANTONIO
1005002	10	ALTO PARANA	5	HERNANDARIAS	1	2	SAN FRANCISCO
1005003	10	ALTO PARANA	5	HERNANDARIAS	1	3	MARISCAL LOPEZ
1005004	10	ALTO PARANA	5	HERNANDARIAS	1	4	SAN RAMON
1005005	10	ALTO PARANA	5	HERNANDARIAS	1	5	SAN JOSE
1005006	10	ALTO PARANA	5	HERNANDARIAS	1	6	SAN CARLOS
1005007	10	ALTO PARANA	5	HERNANDARIAS	1	7	NUESTRA SRA DE LA ASUNCION
1005008	10	ALTO PARANA	5	HERNANDARIAS	1	8	AREA 6
1005009	10	ALTO PARANA	5	HERNANDARIAS	1	9	SAN LORENZO
1005010	10	ALTO PARANA	5	HERNANDARIAS	1	10	SAN PABLO
1005011	10	ALTO PARANA	5	HERNANDARIAS	1	11	SANTO DOMINGO
1005012	10	ALTO PARANA	5	HERNANDARIAS	1	12	FATIMA
1005013	10	ALTO PARANA	5	HERNANDARIAS	1	13	BELLA VISTA
1005014	10	ALTO PARANA	5	HERNANDARIAS	1	14	AURORA
1005015	10	ALTO PARANA	5	HERNANDARIAS	1	15	CAACUPEMI
1005016	10	ALTO PARANA	5	HERNANDARIAS	1	16	SAN MIGUEL
1005017	10	ALTO PARANA	5	HERNANDARIAS	1	17	NUEVA ESPERANZA
1005018	10	ALTO PARANA	5	HERNANDARIAS	1	18	MARIA MAGDALENA
1005019	10	ALTO PARANA	5	HERNANDARIAS	1	19	ASENT. NINO JESUS
1005020	10	ALTO PARANA	5	HERNANDARIAS	1	20	VILLA DEPORTIVA
1005021	10	ALTO PARANA	5	HERNANDARIAS	1	21	TRIGAL 3
1005022	10	ALTO PARANA	5	HERNANDARIAS	1	22	ITAIPU
1005023	10	ALTO PARANA	5	HERNANDARIAS	1	23	PARANA COUNTRY CLUB
1005130	10	ALTO PARANA	5	HERNANDARIAS	6	130	COLONIA NUEVA ESPERANZA
1005150	10	ALTO PARANA	5	HERNANDARIAS	6	150	NUEVA FORTUNA
1005230	10	ALTO PARANA	5	HERNANDARIAS	6	230	MARACAMOA
1005270	10	ALTO PARANA	5	HERNANDARIAS	6	270	ACARAYMI
1005280	10	ALTO PARANA	5	HERNANDARIAS	6	280	COLONIA LAURA
1005300	10	ALTO PARANA	5	HERNANDARIAS	6	300	COM INDIG ACARAYMI - ANGELA ANTONIA
1005301	10	ALTO PARANA	5	HERNANDARIAS	6	301	COM INDIG ACARAYMI - SAN MIGUEL
1005302	10	ALTO PARANA	5	HERNANDARIAS	6	302	COM INDIG ACARAYMI - CENTRO
1005320	10	ALTO PARANA	5	HERNANDARIAS	6	320	COLONIA TORYVETE
1005330	10	ALTO PARANA	5	HERNANDARIAS	6	330	PASO ITA
1005340	10	ALTO PARANA	5	HERNANDARIAS	6	340	ORLANDO KUE
1005350	10	ALTO PARANA	5	HERNANDARIAS	6	350	TACURU PUKU
1005360	10	ALTO PARANA	5	HERNANDARIAS	6	360	TATI JUPI
1005370	10	ALTO PARANA	5	HERNANDARIAS	6	370	SAN ROQUE
1005380	10	ALTO PARANA	5	HERNANDARIAS	6	380	COLONIA ACARAY
1005390	10	ALTO PARANA	5	HERNANDARIAS	6	390	SAN MIGUEL
1005420	10	ALTO PARANA	5	HERNANDARIAS	6	420	FELIX DE AZARA
1005450	10	ALTO PARANA	5	HERNANDARIAS	6	450	COM INDIG INDEPENDIENTE
1005460	10	ALTO PARANA	5	HERNANDARIAS	6	460	LA FORTUNA UNIDA
1005470	10	ALTO PARANA	5	HERNANDARIAS	6	470	MANDU'ARA
1005480	10	ALTO PARANA	5	HERNANDARIAS	6	480	SANTA ROSA
1005485	10	ALTO PARANA	5	HERNANDARIAS	3	485	SANTA ROSA SUB-URBANO
1005490	10	ALTO PARANA	5	HERNANDARIAS	6	490	COLONIA LA FORTUNA
1005495	10	ALTO PARANA	5	HERNANDARIAS	3	495	COLONIA LA FORTUNA SUB-URBANO
1005500	10	ALTO PARANA	5	HERNANDARIAS	6	500	SANTA ELENA
1005510	10	ALTO PARANA	5	HERNANDARIAS	6	510	SAN FRANCISCO
1005520	10	ALTO PARANA	5	HERNANDARIAS	6	520	SANTA MONICA
1005530	10	ALTO PARANA	5	HERNANDARIAS	6	530	BELLA VISTA
1005550	10	ALTO PARANA	5	HERNANDARIAS	6	550	ASENT. FELIX DE AZARA
1006001	10	ALTO PARANA	6	ITAKYRY	1	1	SAN JOSE
1006002	10	ALTO PARANA	6	ITAKYRY	1	2	SANTA LIBRADA
1006003	10	ALTO PARANA	6	ITAKYRY	1	3	SAN CAYETANO
1006004	10	ALTO PARANA	6	ITAKYRY	1	4	MARIA AUXILIADORA
1006005	10	ALTO PARANA	6	ITAKYRY	1	5	CENTRO
1006006	10	ALTO PARANA	6	ITAKYRY	1	6	SAN LORENZO
1006100	10	ALTO PARANA	6	ITAKYRY	6	100	ACARAY COSTA
1006110	10	ALTO PARANA	6	ITAKYRY	6	110	CRISTO REY
1006130	10	ALTO PARANA	6	ITAKYRY	6	130	CRUCE ITAKYRY
1006140	10	ALTO PARANA	6	ITAKYRY	6	140	ITAIPYTE
1006150	10	ALTO PARANA	6	ITAKYRY	6	150	CAPIIBARY
1006160	10	ALTO PARANA	6	ITAKYRY	6	160	3 DE FEBRERO
1006170	10	ALTO PARANA	6	ITAKYRY	6	170	YVY TIMBO
1006180	10	ALTO PARANA	6	ITAKYRY	6	180	TAKUARE'E
1006200	10	ALTO PARANA	6	ITAKYRY	6	200	SAN MIGUEL
1006220	10	ALTO PARANA	6	ITAKYRY	6	220	LAGUNA
1006230	10	ALTO PARANA	6	ITAKYRY	6	230	CALLE 15 DE AGOSTO
1006240	10	ALTO PARANA	6	ITAKYRY	6	240	TAKUARA
1006250	10	ALTO PARANA	6	ITAKYRY	6	250	TIMBO
1006260	10	ALTO PARANA	6	ITAKYRY	6	260	CAMPO REDONDO
1006270	10	ALTO PARANA	6	ITAKYRY	6	270	YTORORO
1006280	10	ALTO PARANA	6	ITAKYRY	6	280	SAN ANTONIO
1006290	10	ALTO PARANA	6	ITAKYRY	6	290	CAREMA GUASU
1006310	10	ALTO PARANA	6	ITAKYRY	6	310	COM INDIG KA'A POTY
1006320	10	ALTO PARANA	6	ITAKYRY	6	320	COLORADO'I
1006330	10	ALTO PARANA	6	ITAKYRY	6	330	CAPI'I
1006350	10	ALTO PARANA	6	ITAKYRY	6	350	TAVA RORY
1006370	10	ALTO PARANA	6	ITAKYRY	6	370	ASENT. CHINO'I - BASE 4
1006375	10	ALTO PARANA	6	ITAKYRY	6	375	ASENT. CHINO'I - BASE 7
1006377	10	ALTO PARANA	6	ITAKYRY	6	377	ASENT. CHINO'I - BASE 10
1006380	10	ALTO PARANA	6	ITAKYRY	6	380	SANTA MARIA 2
1006385	10	ALTO PARANA	6	ITAKYRY	6	385	SANTA MARIA 1
1006400	10	ALTO PARANA	6	ITAKYRY	6	400	SANTA LUCIA
1006440	10	ALTO PARANA	6	ITAKYRY	6	440	NUEVA CONQUISTA
1006460	10	ALTO PARANA	6	ITAKYRY	6	460	NARANJITO
1006480	10	ALTO PARANA	6	ITAKYRY	6	480	VILLA CELESTE
1006500	10	ALTO PARANA	6	ITAKYRY	6	500	SAN JUAN
1006510	10	ALTO PARANA	6	ITAKYRY	6	510	SANTO DOMINGO
1006530	10	ALTO PARANA	6	ITAKYRY	6	530	YTU
1006560	10	ALTO PARANA	6	ITAKYRY	6	560	TREINTA Y CINCO
1006590	10	ALTO PARANA	6	ITAKYRY	6	590	VILLAR KUE
1006600	10	ALTO PARANA	6	ITAKYRY	6	600	COLONIA AGUAPE
1006610	10	ALTO PARANA	6	ITAKYRY	6	610	ASENT. ACARAY POTY
1006620	10	ALTO PARANA	6	ITAKYRY	6	620	COM INDIG PASO CADENA
1006630	10	ALTO PARANA	6	ITAKYRY	6	630	ZANJA MOROTI
1006640	10	ALTO PARANA	6	ITAKYRY	6	640	YKUA PORA
1006650	10	ALTO PARANA	6	ITAKYRY	6	650	ANGELITO
1006660	10	ALTO PARANA	6	ITAKYRY	6	660	JUANITA
1006665	10	ALTO PARANA	6	ITAKYRY	6	665	COM INDIG ARROYO GUAZU - CENTRO
1006670	10	ALTO PARANA	6	ITAKYRY	6	670	LAGUNA KARE
1006675	10	ALTO PARANA	6	ITAKYRY	6	675	COM INDIG ARROYO GUAZU-CHOPA CUE
1006680	10	ALTO PARANA	6	ITAKYRY	6	680	4 BOCAS
1006685	10	ALTO PARANA	6	ITAKYRY	6	685	COM INDIG ARROYO GUAZU-PILICO CUE
1006690	10	ALTO PARANA	6	ITAKYRY	6	690	LOMAS
1006695	10	ALTO PARANA	6	ITAKYRY	6	695	SAN FERNANDO
1006700	10	ALTO PARANA	6	ITAKYRY	6	700	SANTA TERESITA
1006710	10	ALTO PARANA	6	ITAKYRY	6	710	COM INDIG LOMA TAJY
1006715	10	ALTO PARANA	6	ITAKYRY	6	715	COM INDIG YUKYRY
1006720	10	ALTO PARANA	6	ITAKYRY	6	720	COM INDIG KA'ATY MIRI (FORMOSA)
1006725	10	ALTO PARANA	6	ITAKYRY	6	725	ASENT. CHINO KUE - SEGUNDA LINEA
1006730	10	ALTO PARANA	6	ITAKYRY	6	730	COM INDIG YSATI
1006735	10	ALTO PARANA	6	ITAKYRY	6	735	ASENT. CHINO KUE - PRIMERA LINEA
1006745	10	ALTO PARANA	6	ITAKYRY	6	745	ASENT. YKUA PORA
1006750	10	ALTO PARANA	6	ITAKYRY	6	750	COM INDIG KA'AGUY ROKY
1006770	10	ALTO PARANA	6	ITAKYRY	6	770	COM INDIG MBOCAYA'I
1006780	10	ALTO PARANA	6	ITAKYRY	6	780	COM INDIG KA'AGUY YVATE
1006790	10	ALTO PARANA	6	ITAKYRY	6	790	COM INDIG MARISCAL LOPEZ
1006800	10	ALTO PARANA	6	ITAKYRY	6	800	COM INDIG KA'AGUY POTY 2
1006810	10	ALTO PARANA	6	ITAKYRY	6	810	COM INDIG CARRERIA'I 1
1006815	10	ALTO PARANA	6	ITAKYRY	6	815	COM INDIG CARRERIA'I 2
1006820	10	ALTO PARANA	6	ITAKYRY	6	820	COM INDIG URUKU POTY
1006840	10	ALTO PARANA	6	ITAKYRY	6	840	TAVA RORY SEGUNDA LINEA
1006850	10	ALTO PARANA	6	ITAKYRY	6	850	26 DE MARZO
1006860	10	ALTO PARANA	6	ITAKYRY	6	860	SANTO DOMINGO 2
1006870	10	ALTO PARANA	6	ITAKYRY	6	870	LOTE 17
1006880	10	ALTO PARANA	6	ITAKYRY	6	880	ASENT. SAN PABLO
1006890	10	ALTO PARANA	6	ITAKYRY	6	890	ASENT. SAN ISIDRO
1006900	10	ALTO PARANA	6	ITAKYRY	6	900	ASENT. PARAGUAY PYAHU
1006910	10	ALTO PARANA	6	ITAKYRY	6	910	COM INDIG KA'AGUY POTY 1
1006920	10	ALTO PARANA	6	ITAKYRY	6	920	ASENT. TRES MARIA
1006930	10	ALTO PARANA	6	ITAKYRY	6	930	ASENT. SAN PEDRO
1006940	10	ALTO PARANA	6	ITAKYRY	6	940	TIERRA PROMETIDA
1006950	10	ALTO PARANA	6	ITAKYRY	6	950	RANCHO ALEGRE
1006960	10	ALTO PARANA	6	ITAKYRY	6	960	COM INDIG YPORA POTY
1006970	10	ALTO PARANA	6	ITAKYRY	6	970	COM INDIG Y ARYTY MIRI
1006980	10	ALTO PARANA	6	ITAKYRY	6	980	COM INDIG KO`E YU
1006981	10	ALTO PARANA	6	ITAKYRY	6	981	COM INDIG LOMA CLAVEL
1006982	10	ALTO PARANA	6	ITAKYRY	6	982	COM INDIG TUPARENDA'I
1006983	10	ALTO PARANA	6	ITAKYRY	6	983	COM INDIG AKARAYMI POTY
1006990	10	ALTO PARANA	6	ITAKYRY	6	990	ESTANCIA SAN MARCOS
1007002	10	ALTO PARANA	7	JUAN E. O'LEARY	1	2	SAN RAFAEL
1007003	10	ALTO PARANA	7	JUAN E. O'LEARY	1	3	SANTA ROSA
1007004	10	ALTO PARANA	7	JUAN E. O'LEARY	1	4	SAN ANTONIO
1007005	10	ALTO PARANA	7	JUAN E. O'LEARY	1	5	CENTRO
1007006	10	ALTO PARANA	7	JUAN E. O'LEARY	1	6	SAN ISIDRO
1007007	10	ALTO PARANA	7	JUAN E. O'LEARY	1	7	VIRGEN INMACULADA CONCEPCION
1007008	10	ALTO PARANA	7	JUAN E. O'LEARY	1	8	EL PROGRESO
1007009	10	ALTO PARANA	7	JUAN E. O'LEARY	1	9	LA CANDELARIA
1007110	10	ALTO PARANA	7	JUAN E. O'LEARY	6	110	LA VICTORIA YGUASU
1007130	10	ALTO PARANA	7	JUAN E. O'LEARY	6	130	8 DE DICIEMBRE
1007140	10	ALTO PARANA	7	JUAN E. O'LEARY	6	140	CALLE R.I. 14 CERRO CORA
1007150	10	ALTO PARANA	7	JUAN E. O'LEARY	6	150	TACUARA NORTE
1007160	10	ALTO PARANA	7	JUAN E. O'LEARY	6	160	LAS MERCEDES
1007165	10	ALTO PARANA	7	JUAN E. O'LEARY	3	165	LAS MERCEDES SUB-URBANO
1007170	10	ALTO PARANA	7	JUAN E. O'LEARY	6	170	LA VICTORIA MONDAY
1007180	10	ALTO PARANA	7	JUAN E. O'LEARY	6	180	MARIA AUXILIADORA
1007190	10	ALTO PARANA	7	JUAN E. O'LEARY	6	190	VIRGEN INMACULADA CONCEPCION
1007200	10	ALTO PARANA	7	JUAN E. O'LEARY	6	200	SAN PABLO
1007210	10	ALTO PARANA	7	JUAN E. O'LEARY	6	210	SAN AGUSTIN
1007220	10	ALTO PARANA	7	JUAN E. O'LEARY	6	220	SAN ISIDRO II
1007230	10	ALTO PARANA	7	JUAN E. O'LEARY	6	230	SAN FRANCISCO
1007260	10	ALTO PARANA	7	JUAN E. O'LEARY	6	260	SAN ISIDRO
1007270	10	ALTO PARANA	7	JUAN E. O'LEARY	6	270	SAN RAFAEL
1007280	10	ALTO PARANA	7	JUAN E. O'LEARY	6	280	LA CANDELARIA
1007290	10	ALTO PARANA	7	JUAN E. O'LEARY	6	290	TAKUARO SUR
1007300	10	ALTO PARANA	7	JUAN E. O'LEARY	6	300	KO'E RORY
1007305	10	ALTO PARANA	7	JUAN E. O'LEARY	3	305	KO'E RORY SUB-URBANO
1007310	10	ALTO PARANA	7	JUAN E. O'LEARY	6	310	SAN JOSE OBRERO
1007320	10	ALTO PARANA	7	JUAN E. O'LEARY	6	320	LA VICTORIA
1007330	10	ALTO PARANA	7	JUAN E. O'LEARY	6	330	CRISTO REY
1007340	10	ALTO PARANA	7	JUAN E. O'LEARY	6	340	VIRGEN REINA
1007350	10	ALTO PARANA	7	JUAN E. O'LEARY	6	350	VIRGEN DE FATIMA
1008001	10	ALTO PARANA	8	NACUNDAY	1	1	CENTRO
1008100	10	ALTO PARANA	8	NACUNDAY	6	100	PARANAMBU 1
1008110	10	ALTO PARANA	8	NACUNDAY	6	110	COLONIA 8 DE DICIEMBRE
1008120	10	ALTO PARANA	8	NACUNDAY	6	120	SANTA CAROLINA
1008140	10	ALTO PARANA	8	NACUNDAY	6	140	COLONIA MBARETE
1008145	10	ALTO PARANA	8	NACUNDAY	3	145	COLONIA MBARETE SUB-URBANO
1008150	10	ALTO PARANA	8	NACUNDAY	6	150	SANTA ROSA
1008160	10	ALTO PARANA	8	NACUNDAY	6	160	ITAIPYTE
1008165	10	ALTO PARANA	8	NACUNDAY	3	165	ITAIPYTE SUB-URBANO
1008170	10	ALTO PARANA	8	NACUNDAY	6	170	AGROTORO
1008175	10	ALTO PARANA	8	NACUNDAY	3	175	AGROTORO SUB-URBANO
1008200	10	ALTO PARANA	8	NACUNDAY	6	200	TORO KUA
1008205	10	ALTO PARANA	8	NACUNDAY	3	205	TORO KUA SUB-URBANO
1008230	10	ALTO PARANA	8	NACUNDAY	6	230	LOMAS VALENTINAS
1008235	10	ALTO PARANA	8	NACUNDAY	3	235	LOMAS VALENTINAS SUB-URBANO
1008240	10	ALTO PARANA	8	NACUNDAY	6	240	CHACORE PRIMERA LINEA
1008250	10	ALTO PARANA	8	NACUNDAY	6	250	CHACORE SEGUNDA LINEA
1008260	10	ALTO PARANA	8	NACUNDAY	6	260	CHACORE QUINTA LINEA
1008270	10	ALTO PARANA	8	NACUNDAY	6	270	ASENT. 8 DE DICIEMBRE
1008280	10	ALTO PARANA	8	NACUNDAY	6	280	ASENT. 8 DE DICIEMBRE - SANTA LUCIA
1008290	10	ALTO PARANA	8	NACUNDAY	6	290	PARANAMBU 2
1008300	10	ALTO PARANA	8	NACUNDAY	6	300	CRUCE 24
1008310	10	ALTO PARANA	8	NACUNDAY	6	310	COM INDIG KOE PUAHU
1008320	10	ALTO PARANA	8	NACUNDAY	6	320	PUNTA JOVAI
1009001	10	ALTO PARANA	9	YGUAZU	1	1	CENTRO URBANO
1009002	10	ALTO PARANA	9	YGUAZU	1	2	VIRGEN DE CAACUPE
1009003	10	ALTO PARANA	9	YGUAZU	1	3	SAN ISIDRO
1009004	10	ALTO PARANA	9	YGUAZU	1	4	SAN BLAS
1009005	10	ALTO PARANA	9	YGUAZU	1	5	SAN VALENTIN
1009006	10	ALTO PARANA	9	YGUAZU	1	6	SAN MIGUEL
1009100	10	ALTO PARANA	9	YGUAZU	6	100	KILOMETRO 43
1009120	10	ALTO PARANA	9	YGUAZU	6	120	KILOMETRO 44
1009140	10	ALTO PARANA	9	YGUAZU	6	140	KILOMETRO 42
1009150	10	ALTO PARANA	9	YGUAZU	6	150	NUEVA ALIANZA
1009160	10	ALTO PARANA	9	YGUAZU	6	160	SAN LUIS
1009170	10	ALTO PARANA	9	YGUAZU	6	170	SANTO DOMINGO
1009175	10	ALTO PARANA	9	YGUAZU	6	175	ASENT. SANTO DOMINGO
1009210	10	ALTO PARANA	9	YGUAZU	6	210	NUEVA ESPERANZA
1009230	10	ALTO PARANA	9	YGUAZU	6	230	COM INDIG REMANSO TORO
1009240	10	ALTO PARANA	9	YGUAZU	6	240	KILOMETRO 49
1009280	10	ALTO PARANA	9	YGUAZU	6	280	KILOMETRO 38
1009290	10	ALTO PARANA	9	YGUAZU	6	290	KILOMETRO 41
1009300	10	ALTO PARANA	9	YGUAZU	6	300	KILOMETRO 51 A 52
1009310	10	ALTO PARANA	9	YGUAZU	6	310	FRACCION SAN PEDRO
1009320	10	ALTO PARANA	9	YGUAZU	6	320	SAN LORENZO
1009330	10	ALTO PARANA	9	YGUAZU	6	330	KILOMETRO 46
1009340	10	ALTO PARANA	9	YGUAZU	6	340	ARROYO MBYA
1009350	10	ALTO PARANA	9	YGUAZU	6	350	COM INDIG KARANDA'Y
1009360	10	ALTO PARANA	9	YGUAZU	6	360	COM INDIG PUERTO JUANITA
1009370	10	ALTO PARANA	9	YGUAZU	6	370	PUERTO MARGARITA
1010001	10	ALTO PARANA	10	LOS CEDRALES	1	1	LAURELES
1010005	10	ALTO PARANA	10	LOS CEDRALES	1	5	AURORA
1010006	10	ALTO PARANA	10	LOS CEDRALES	1	6	CENTRO
1010007	10	ALTO PARANA	10	LOS CEDRALES	1	7	SAN ANTONIO
1010100	10	ALTO PARANA	10	LOS CEDRALES	6	100	GUAJAKI
1010110	10	ALTO PARANA	10	LOS CEDRALES	6	110	COLONIA LOS CEDRALES
1010120	10	ALTO PARANA	10	LOS CEDRALES	6	120	COLONIA PENGO SAN MIGUEL
1010130	10	ALTO PARANA	10	LOS CEDRALES	6	130	COLONIA 22 DE MAYO
1010150	10	ALTO PARANA	10	LOS CEDRALES	6	150	PARANA POTY GUARANI
1010160	10	ALTO PARANA	10	LOS CEDRALES	6	160	GLEVA 4
1010180	10	ALTO PARANA	10	LOS CEDRALES	6	180	SAN JOSE
1010220	10	ALTO PARANA	10	LOS CEDRALES	6	220	SOCIEGO
1010240	10	ALTO PARANA	10	LOS CEDRALES	6	240	SAN MIGUEL
1010270	10	ALTO PARANA	10	LOS CEDRALES	6	270	MARIA AUXILIADORA
1010310	10	ALTO PARANA	10	LOS CEDRALES	6	310	SAN JUAN
1010320	10	ALTO PARANA	10	LOS CEDRALES	6	320	SANTA JULIANA
1010330	10	ALTO PARANA	10	LOS CEDRALES	6	330	CRUCERINHO
1010340	10	ALTO PARANA	10	LOS CEDRALES	6	340	PENINSULA
1010350	10	ALTO PARANA	10	LOS CEDRALES	6	350	SAN ISIDRO
1010360	10	ALTO PARANA	10	LOS CEDRALES	6	360	ASENT. JAKARE KUA
1011001	10	ALTO PARANA	11	MINGA GUAZU	1	1	KAVURE'I
1011002	10	ALTO PARANA	11	MINGA GUAZU	1	2	ZONA GRANJA MI ABUELA
1011003	10	ALTO PARANA	11	MINGA GUAZU	1	3	FRACCION SCHNEIDER II
1011004	10	ALTO PARANA	11	MINGA GUAZU	1	4	PA'I CORONEL
1011005	10	ALTO PARANA	11	MINGA GUAZU	1	5	DOMINGO SAVIO
1011006	10	ALTO PARANA	11	MINGA GUAZU	1	6	CALLE 16 ACARAY
1011007	10	ALTO PARANA	11	MINGA GUAZU	1	7	SAN ANTONIO
1011008	10	ALTO PARANA	11	MINGA GUAZU	1	8	CENTRO
1011009	10	ALTO PARANA	11	MINGA GUAZU	1	9	VILLA NELIDA
1011010	10	ALTO PARANA	11	MINGA GUAZU	1	10	MARIA AUXILIADORA
1011011	10	ALTO PARANA	11	MINGA GUAZU	1	11	FRACCION LAS PALMERAS
1011012	10	ALTO PARANA	11	MINGA GUAZU	1	12	CEDRALES
1011013	10	ALTO PARANA	11	MINGA GUAZU	1	13	CONAVI
1011014	10	ALTO PARANA	11	MINGA GUAZU	1	14	JARDIN DEL ORIENTE
1011015	10	ALTO PARANA	11	MINGA GUAZU	1	15	FRACCION SANTA TERESA
1011016	10	ALTO PARANA	11	MINGA GUAZU	1	16	FRACCION LAS GARDENIAS
1011017	10	ALTO PARANA	11	MINGA GUAZU	1	17	SAN ROQUE
1011018	10	ALTO PARANA	11	MINGA GUAZU	1	18	MONTE LINDO
1011019	10	ALTO PARANA	11	MINGA GUAZU	1	19	CALLE 16 MONDAY
1011020	10	ALTO PARANA	11	MINGA GUAZU	1	20	NAHATY
1011021	10	ALTO PARANA	11	MINGA GUAZU	1	21	CAMPO VERDE
1011022	10	ALTO PARANA	11	MINGA GUAZU	1	22	FRACCION SAN MIGUEL
1011023	10	ALTO PARANA	11	MINGA GUAZU	1	23	FRACCION GUARANI
1011024	10	ALTO PARANA	11	MINGA GUAZU	1	24	FRACCION MARIA LIA
1011025	10	ALTO PARANA	11	MINGA GUAZU	1	25	FRACCION LOS MINGUEROS
1011026	10	ALTO PARANA	11	MINGA GUAZU	1	26	SAN BERNARDO
1011027	10	ALTO PARANA	11	MINGA GUAZU	1	27	FRACCION RESIDENCIAL JAZMIN II
1011028	10	ALTO PARANA	11	MINGA GUAZU	1	28	FRACCION LOS ALAMOS
1011029	10	ALTO PARANA	11	MINGA GUAZU	1	29	SANTA MONICA
1011030	10	ALTO PARANA	11	MINGA GUAZU	1	30	FRACCION NORMA LUISA
1011031	10	ALTO PARANA	11	MINGA GUAZU	1	31	YHAGUY
1011110	10	ALTO PARANA	11	MINGA GUAZU	6	110	COLONIA TRIUNFO
1011115	10	ALTO PARANA	11	MINGA GUAZU	3	115	COLONIA TRIUNFO SUB-URBANO
1011120	10	ALTO PARANA	11	MINGA GUAZU	6	120	CALLE 30 ACARAY
1011125	10	ALTO PARANA	11	MINGA GUAZU	3	125	CALLE 30 ACARAY SAN MARCOS SUB-URBANO
1011130	10	ALTO PARANA	11	MINGA GUAZU	6	130	CALLE 28 ACARAY
1011140	10	ALTO PARANA	11	MINGA GUAZU	6	140	CALLE 26 ACARAY
1011150	10	ALTO PARANA	11	MINGA GUAZU	6	150	CALLE 22 ACARAY
1011155	10	ALTO PARANA	11	MINGA GUAZU	3	155	CALLE 22 ACARAY SUB-URBANO
1011160	10	ALTO PARANA	11	MINGA GUAZU	6	160	CALLE 20 ACARAY
1011165	10	ALTO PARANA	11	MINGA GUAZU	3	165	CALLE 20 ACARAY SUB-URBANO
1011170	10	ALTO PARANA	11	MINGA GUAZU	6	170	CALLE 16 ACARAY
1011190	10	ALTO PARANA	11	MINGA GUAZU	6	190	CALLE 14 ACARAY
1011200	10	ALTO PARANA	11	MINGA GUAZU	6	200	CALLE 14 MONDAY
1011205	10	ALTO PARANA	11	MINGA GUAZU	3	205	CALLE 14 MONDAY SUB-URBANO
1011210	10	ALTO PARANA	11	MINGA GUAZU	6	210	CALLE 16 MONDAY
1011220	10	ALTO PARANA	11	MINGA GUAZU	6	220	CALLE 18 MONDAY
1011225	10	ALTO PARANA	11	MINGA GUAZU	3	225	TAJY POTY SUB-URBANO
1011230	10	ALTO PARANA	11	MINGA GUAZU	6	230	CALLE 20 MONDAY
1011235	10	ALTO PARANA	11	MINGA GUAZU	3	235	CALLE 20 MONDAY SUB-URBANO
1011240	10	ALTO PARANA	11	MINGA GUAZU	6	240	CALLE 22  MONDAY
1011250	10	ALTO PARANA	11	MINGA GUAZU	6	250	CALLE 24 MONDAY
1011255	10	ALTO PARANA	11	MINGA GUAZU	3	255	CALLE 24 MONDAY SUB-URBANO
1011260	10	ALTO PARANA	11	MINGA GUAZU	6	260	CALLE 26 MONDAY
1011270	10	ALTO PARANA	11	MINGA GUAZU	6	270	CALLE 28 MONDAY
1011290	10	ALTO PARANA	11	MINGA GUAZU	6	290	CALLE 30 MONDAY
1011291	10	ALTO PARANA	11	MINGA GUAZU	3	291	KILOMETRO 30 SAN JORGE SUB-URBANO
1011292	10	ALTO PARANA	11	MINGA GUAZU	3	292	KILOMETRO 30 PIROY SUB-URBANO
1011293	10	ALTO PARANA	11	MINGA GUAZU	3	293	KILOMETRO 30 EL MENSU SUB-URBANO
1011294	10	ALTO PARANA	11	MINGA GUAZU	3	294	CALLE 30 SANTA CATALINA SUB-URBANO
1011295	10	ALTO PARANA	11	MINGA GUAZU	3	295	KILOMETRO 30 VIRGEN DEL CARMEN SUB-URBANO
1011300	10	ALTO PARANA	11	MINGA GUAZU	6	300	ASENT. PORVENIR
1011310	10	ALTO PARANA	11	MINGA GUAZU	6	310	ASENT. TAPYI
1011320	10	ALTO PARANA	11	MINGA GUAZU	6	320	ASENT. PRIMAVERA
1011330	10	ALTO PARANA	11	MINGA GUAZU	6	330	ASENT. KUARAHY RESE
1011340	10	ALTO PARANA	11	MINGA GUAZU	6	340	ASENT. RENACER
1011350	10	ALTO PARANA	11	MINGA GUAZU	6	350	KO'E PYAHU
1011360	10	ALTO PARANA	11	MINGA GUAZU	6	360	JACQUET  KUE - SAN MIGUEL
1011370	10	ALTO PARANA	11	MINGA GUAZU	6	370	NUEVO AMANECER
1011380	10	ALTO PARANA	11	MINGA GUAZU	6	380	ASENT. BUENA VISTA
1011390	10	ALTO PARANA	11	MINGA GUAZU	6	390	FRACCION LOS LAURELES
1011400	10	ALTO PARANA	11	MINGA GUAZU	6	400	SAN JUAN KILOMETRO 18 MONDAY
1011410	10	ALTO PARANA	11	MINGA GUAZU	6	410	GUAVIRA POTY
1011420	10	ALTO PARANA	11	MINGA GUAZU	6	420	CALLE 18 ACARAY
1011430	10	ALTO PARANA	11	MINGA GUAZU	6	430	CALLE 24 ACARAY
1011435	10	ALTO PARANA	11	MINGA GUAZU	3	435	CALLE 24 ACARAY SUB-URBANO
1011440	10	ALTO PARANA	11	MINGA GUAZU	6	440	ASENT. 2DA LINEA BOQUERON
1011450	10	ALTO PARANA	11	MINGA GUAZU	6	450	ASENT. CALLE 26 SAN ISDRO
1011460	10	ALTO PARANA	11	MINGA GUAZU	6	460	ASENT. CRISTO REY
1011470	10	ALTO PARANA	11	MINGA GUAZU	6	470	ASENT. LAS MERCEDES
1011480	10	ALTO PARANA	11	MINGA GUAZU	6	480	SAN FRANCISCO KILOMETRO 31
1011490	10	ALTO PARANA	11	MINGA GUAZU	6	490	VIRGEN DEL CARMEN
1012001	10	ALTO PARANA	12	SAN CRISTOBAL	1	1	URBANO
1012100	10	ALTO PARANA	12	SAN CRISTOBAL	6	100	MARIA AUXILIADORA PRIMERA LINEA
1012110	10	ALTO PARANA	12	SAN CRISTOBAL	6	110	FITINA
1012120	10	ALTO PARANA	12	SAN CRISTOBAL	6	120	VISTA ALEGRE
1012150	10	ALTO PARANA	12	SAN CRISTOBAL	6	150	LINEA BUSANELLO
1012160	10	ALTO PARANA	12	SAN CRISTOBAL	6	160	SAN ANTONIO
1012170	10	ALTO PARANA	12	SAN CRISTOBAL	6	170	SAPIRE
1012190	10	ALTO PARANA	12	SAN CRISTOBAL	6	190	YBAROTY
1012210	10	ALTO PARANA	12	SAN CRISTOBAL	6	210	YACUTINGA
1012220	10	ALTO PARANA	12	SAN CRISTOBAL	6	220	COLONIA TRES NACIENTES
1012230	10	ALTO PARANA	12	SAN CRISTOBAL	6	230	CAMPO ALEGRE
1012240	10	ALTO PARANA	12	SAN CRISTOBAL	6	240	SANTA LUCIA
1012250	10	ALTO PARANA	12	SAN CRISTOBAL	6	250	SAN JORGE
1012260	10	ALTO PARANA	12	SAN CRISTOBAL	6	260	RELOJ KUE
1012280	10	ALTO PARANA	12	SAN CRISTOBAL	6	280	3 DE MAYO
1012290	10	ALTO PARANA	12	SAN CRISTOBAL	6	290	ARROYO QUEMADO
1012310	10	ALTO PARANA	12	SAN CRISTOBAL	6	310	SAN CRISTOBAL
1012330	10	ALTO PARANA	12	SAN CRISTOBAL	6	330	SANTO DOMINGO 32
1012335	10	ALTO PARANA	12	SAN CRISTOBAL	3	335	SANTO DOMINGO 32 SUB-URBANO
1012340	10	ALTO PARANA	12	SAN CRISTOBAL	6	340	COM INDIG KA'A JOVAI
1012350	10	ALTO PARANA	12	SAN CRISTOBAL	6	350	VIRGEN DEL PILAR
1012360	10	ALTO PARANA	12	SAN CRISTOBAL	6	360	GUEMBETY GUAZU
1012370	10	ALTO PARANA	12	SAN CRISTOBAL	6	370	COLONIA 1RO DE MAYO
1012380	10	ALTO PARANA	12	SAN CRISTOBAL	6	380	COLONIA SAN MARCOS
1012390	10	ALTO PARANA	12	SAN CRISTOBAL	6	390	SAN ROQUE
1012400	10	ALTO PARANA	12	SAN CRISTOBAL	6	400	YCUA PYTA
1012410	10	ALTO PARANA	12	SAN CRISTOBAL	6	410	YCUA HOVY
1012420	10	ALTO PARANA	12	SAN CRISTOBAL	6	420	SAN JOSE
1012430	10	ALTO PARANA	12	SAN CRISTOBAL	6	430	SAN JOSE 2
1012440	10	ALTO PARANA	12	SAN CRISTOBAL	6	440	SAN MIGUEL
1012450	10	ALTO PARANA	12	SAN CRISTOBAL	6	450	SANTO DOMINGO 2
1013001	10	ALTO PARANA	13	SANTA RITA	1	1	PORTAL SINUELO
1013002	10	ALTO PARANA	13	SANTA RITA	1	2	SINUELO
1013003	10	ALTO PARANA	13	SANTA RITA	1	3	FRACCION LA AMISTAD
1013004	10	ALTO PARANA	13	SANTA RITA	1	4	UNION
1013005	10	ALTO PARANA	13	SANTA RITA	1	5	ESQUINA GAUCHA
1013006	10	ALTO PARANA	13	SANTA RITA	1	6	BELLA VISTA
1013007	10	ALTO PARANA	13	SANTA RITA	1	7	CENTRO
1013008	10	ALTO PARANA	13	SANTA RITA	1	8	NUEVA ESPERANZA
1013009	10	ALTO PARANA	13	SANTA RITA	1	9	FRACCION SHULTZ
1013010	10	ALTO PARANA	13	SANTA RITA	1	10	NUEVA SANTA RITA
1013011	10	ALTO PARANA	13	SANTA RITA	1	11	BARRIO ALEJANDRINO
1013012	10	ALTO PARANA	13	SANTA RITA	1	12	FRACCION SANTA LUCIA
1013013	10	ALTO PARANA	13	SANTA RITA	1	13	BUEN JESUS
1013014	10	ALTO PARANA	13	SANTA RITA	1	14	LOS TRIGALES
1013015	10	ALTO PARANA	13	SANTA RITA	1	15	FRACCION SCALIA
1013016	10	ALTO PARANA	13	SANTA RITA	1	16	FRACCION DEWES
1013017	10	ALTO PARANA	13	SANTA RITA	1	17	FRACCION SNEIDER
1013018	10	ALTO PARANA	13	SANTA RITA	1	18	EL COLONO
1013019	10	ALTO PARANA	13	SANTA RITA	1	19	SAN RAMON
1013020	10	ALTO PARANA	13	SANTA RITA	1	20	JARDIN AMERICA
1013021	10	ALTO PARANA	13	SANTA RITA	1	21	CARMELITAS
1013022	10	ALTO PARANA	13	SANTA RITA	1	22	SAN JOSE
1013100	10	ALTO PARANA	13	SANTA RITA	6	100	FULGENCIO R. MORENO
1013120	10	ALTO PARANA	13	SANTA RITA	6	120	VILLA NUEVA
1013130	10	ALTO PARANA	13	SANTA RITA	6	130	SAN MIGUEL 1
1013150	10	ALTO PARANA	13	SANTA RITA	6	150	SANTA LUCIA
1013160	10	ALTO PARANA	13	SANTA RITA	6	160	SAN VICENTE
1013170	10	ALTO PARANA	13	SANTA RITA	6	170	CAACUPEMI
1013180	10	ALTO PARANA	13	SANTA RITA	6	180	ESQUINA GAUCHA
1013190	10	ALTO PARANA	13	SANTA RITA	6	190	SAN MIGUEL 2
1013200	10	ALTO PARANA	13	SANTA RITA	6	200	LINEA JAKARE
1013210	10	ALTO PARANA	13	SANTA RITA	6	210	PACU KUA
1013220	10	ALTO PARANA	13	SANTA RITA	6	220	PANAMBI
1013240	10	ALTO PARANA	13	SANTA RITA	6	240	NUEVA ASUNCION
1013260	10	ALTO PARANA	13	SANTA RITA	6	260	KARAJA
1013270	10	ALTO PARANA	13	SANTA RITA	6	270	SANTA LOURDES
1013280	10	ALTO PARANA	13	SANTA RITA	6	280	SAN JOSE
1013290	10	ALTO PARANA	13	SANTA RITA	6	290	CERRO LARGO
1013295	10	ALTO PARANA	13	SANTA RITA	3	295	CERRO LARGO SUB-URBANO
1013300	10	ALTO PARANA	13	SANTA RITA	6	300	LOS MAIZALES
1013310	10	ALTO PARANA	13	SANTA RITA	6	310	CAMPINA VERDE
1013315	10	ALTO PARANA	13	SANTA RITA	3	315	CAMPINA VERDE SUB-URBANO
1013320	10	ALTO PARANA	13	SANTA RITA	6	320	LINEA LA TORRE
1013330	10	ALTO PARANA	13	SANTA RITA	6	330	14 DE MAYO
1013335	10	ALTO PARANA	13	SANTA RITA	3	335	14 DE MAYO SUB-URBANO
1014001	10	ALTO PARANA	14	NARANJAL	1	1	CENTRO
1014002	10	ALTO PARANA	14	NARANJAL	1	2	LAS PALMAS
1014100	10	ALTO PARANA	14	NARANJAL	6	100	LINEA NACUNDAY
1014110	10	ALTO PARANA	14	NARANJAL	6	110	LINEA BUSANELLO
1014120	10	ALTO PARANA	14	NARANJAL	6	120	AURORA
1014125	10	ALTO PARANA	14	NARANJAL	3	125	AURORA SUB-URBANO
1014130	10	ALTO PARANA	14	NARANJAL	6	130	COLONIA 3 DE MAYO
1014135	10	ALTO PARANA	14	NARANJAL	6	135	SAN JOSE OBRERO
1014140	10	ALTO PARANA	14	NARANJAL	6	140	PACU KUA 1
1014150	10	ALTO PARANA	14	NARANJAL	6	150	PRIMERO DE MAYO
1014160	10	ALTO PARANA	14	NARANJAL	6	160	LINEA SAN PEDRO
1014170	10	ALTO PARANA	14	NARANJAL	6	170	PALMITAL
1014175	10	ALTO PARANA	14	NARANJAL	3	175	PALMITAL SUB-URBANO
1014180	10	ALTO PARANA	14	NARANJAL	6	180	10 DE MAYO
1014190	10	ALTO PARANA	14	NARANJAL	6	190	PRIMAVERA
1014200	10	ALTO PARANA	14	NARANJAL	6	200	VILLA MONACO
1014205	10	ALTO PARANA	14	NARANJAL	3	205	VILLA MONACO SUB-URBANO
1014210	10	ALTO PARANA	14	NARANJAL	6	210	SAN ALFREDO
1014215	10	ALTO PARANA	14	NARANJAL	3	215	SAN ALFREDO SUB-URBANO
1014220	10	ALTO PARANA	14	NARANJAL	6	220	COM INDIG TAPY PUERTO BARRA
1014250	10	ALTO PARANA	14	NARANJAL	6	250	COOPERATIVA
1014260	10	ALTO PARANA	14	NARANJAL	6	260	ALTO PARAISO
1014270	10	ALTO PARANA	14	NARANJAL	6	270	ADAN Y EVA
1015001	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	1	1	CENTRO
1015002	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	1	2	GUARANI
1015220	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	220	BELLA VISTA
1015230	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	230	SANTA MARIA
1015260	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	260	FRANCES KUE
1015270	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	270	SANTA ROSA DEL MONDAY NORTE
1015280	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	280	CURUPAYTY LINEA EL PROGRESO
1015285	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	3	285	CURUPAYTY SUB-URBANO
1015290	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	290	SANTA ROSA DEL MONDAY SUR
1015300	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	300	YACUTINGA
1015310	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	310	SAN LUIS
1015340	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	340	3 PINEROS
1015350	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	350	KARAJA
1015360	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	360	COLONIA TORRES
1015370	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	370	SAN CARLOS
1015380	10	ALTO PARANA	15	SANTA ROSA DEL MONDAY	6	380	SAN FRANCISCO
1016001	10	ALTO PARANA	16	MINGA PORA	1	1	SANTA CECILIA
1016003	10	ALTO PARANA	16	MINGA PORA	1	3	VIRGEN DE FATIMA
1016004	10	ALTO PARANA	16	MINGA PORA	1	4	SAN FRANCISCO
1016005	10	ALTO PARANA	16	MINGA PORA	1	5	SAN ISIDRO
1016006	10	ALTO PARANA	16	MINGA PORA	1	6	SAN MIGUEL
1016110	10	ALTO PARANA	16	MINGA PORA	6	110	SAN LORENZO
1016115	10	ALTO PARANA	16	MINGA PORA	3	115	SAN LORENZO SUB URBANO
1016120	10	ALTO PARANA	16	MINGA PORA	6	120	COLONIA GUARANI
1016140	10	ALTO PARANA	16	MINGA PORA	6	140	ENTRE RIOS
1016160	10	ALTO PARANA	16	MINGA PORA	6	160	COOPASAN
1016190	10	ALTO PARANA	16	MINGA PORA	6	190	LIMOY
1016195	10	ALTO PARANA	16	MINGA PORA	3	195	LIMOY SUB-URBANO
1016220	10	ALTO PARANA	16	MINGA PORA	6	220	8 DE DICIEMBRE
1016225	10	ALTO PARANA	16	MINGA PORA	3	225	8 DE DICIEMBRE SUB URBANO
1016240	10	ALTO PARANA	16	MINGA PORA	6	240	SAN JORGE
1016290	10	ALTO PARANA	16	MINGA PORA	6	290	TERCERA LINEA
1016300	10	ALTO PARANA	16	MINGA PORA	6	300	COM INDIG ARROYO GUASU-CAAGUAZU
1016310	10	ALTO PARANA	16	MINGA PORA	6	310	COM INDIG ARROYO GUASU-CENTRO
1016320	10	ALTO PARANA	16	MINGA PORA	6	320	COM INDIG ARROYO GUASU - HUGUA'I
1016330	10	ALTO PARANA	16	MINGA PORA	6	330	COM INDIG ARROYO GUAZU- ARROYO AZUL
1016340	10	ALTO PARANA	16	MINGA PORA	6	340	LOTE 6 Y 7
1016350	10	ALTO PARANA	16	MINGA PORA	6	350	KUARAHY RESE
1016360	10	ALTO PARANA	16	MINGA PORA	6	360	ASENT. 4 PALMAS
1016370	10	ALTO PARANA	16	MINGA PORA	6	370	GLEVA 8 Y 9
1016380	10	ALTO PARANA	16	MINGA PORA	6	380	SEXTA LINEA OESTE
1016390	10	ALTO PARANA	16	MINGA PORA	6	390	QUINTA LINEA OESTE
1016400	10	ALTO PARANA	16	MINGA PORA	6	400	TERCERA LINEA OESTE
1016410	10	ALTO PARANA	16	MINGA PORA	6	410	LOTE 9 VY'A RENDA
1016420	10	ALTO PARANA	16	MINGA PORA	6	420	SAN MIGUEL
1016430	10	ALTO PARANA	16	MINGA PORA	6	430	SEGUNDA LINEA OESTE
1016440	10	ALTO PARANA	16	MINGA PORA	6	440	SAN ISIDRO
1016450	10	ALTO PARANA	16	MINGA PORA	6	450	SEGUNDA LINEA
1016460	10	ALTO PARANA	16	MINGA PORA	6	460	SAN ROQUE
1016470	10	ALTO PARANA	16	MINGA PORA	6	470	SAN ISIDRO PRIMAVERA
1016480	10	ALTO PARANA	16	MINGA PORA	6	480	QUINTA LINEA
1016490	10	ALTO PARANA	16	MINGA PORA	6	490	SAN FRANCISCO
1017002	10	ALTO PARANA	17	MBARACAYU	1	2	GENERAL DIAZ - URBANO
1017100	10	ALTO PARANA	17	MBARACAYU	6	100	BARRO NEGRO
1017120	10	ALTO PARANA	17	MBARACAYU	6	120	ASENT. 1RO DE MAYO
1017130	10	ALTO PARANA	17	MBARACAYU	6	130	ASENT. LA AMISTAD
1017140	10	ALTO PARANA	17	MBARACAYU	6	140	ZONA ESTANCIA ITAVO
1017170	10	ALTO PARANA	17	MBARACAYU	6	170	BELLA VISTA
1017180	10	ALTO PARANA	17	MBARACAYU	6	180	GENERAL DIAZ
1017190	10	ALTO PARANA	17	MBARACAYU	6	190	CRUCE MBARACAYU
1017200	10	ALTO PARANA	17	MBARACAYU	6	200	GLEVA 11 MBARACAYU
1017210	10	ALTO PARANA	17	MBARACAYU	6	210	COM INDIG KIRITO - PINDO
1017211	10	ALTO PARANA	17	MBARACAYU	6	211	COM INDIG KIRITO - GLEVA 10
1017212	10	ALTO PARANA	17	MBARACAYU	6	212	COM INDIG KIRITO - HUGUA'I
1017220	10	ALTO PARANA	17	MBARACAYU	6	220	GLEVA 3 MBARACAYU
1017240	10	ALTO PARANA	17	MBARACAYU	6	240	FORTUNA
1017250	10	ALTO PARANA	17	MBARACAYU	6	250	GLEVA 10 MBARACAYU
1017260	10	ALTO PARANA	17	MBARACAYU	6	260	GLEVA 5 MBARACAYU
1017270	10	ALTO PARANA	17	MBARACAYU	6	270	GLEVA 4 MBARACAYU
1017290	10	ALTO PARANA	17	MBARACAYU	6	290	NUEVA ESPERANZA
1017300	10	ALTO PARANA	17	MBARACAYU	6	300	LA PALOMA BLANCA
1017310	10	ALTO PARANA	17	MBARACAYU	6	310	PROCOPIO
1017315	10	ALTO PARANA	17	MBARACAYU	3	315	PROCOPIO SUB-URBANO
1017320	10	ALTO PARANA	17	MBARACAYU	6	320	COLONIA GUARANI
1017330	10	ALTO PARANA	17	MBARACAYU	6	330	PUERTO INDIO
1017340	10	ALTO PARANA	17	MBARACAYU	6	340	SAN ANTONIO
1017350	10	ALTO PARANA	17	MBARACAYU	3	350	MBARACAYU CENTRO SUB-URBANO
1018001	10	ALTO PARANA	18	SAN ALBERTO	1	1	VILLA DEPORTIVA
1018002	10	ALTO PARANA	18	SAN ALBERTO	1	2	SAN ALBERTO
1018003	10	ALTO PARANA	18	SAN ALBERTO	1	3	PORTAL
1018004	10	ALTO PARANA	18	SAN ALBERTO	1	4	8 DE DICIEMBRE
1018005	10	ALTO PARANA	18	SAN ALBERTO	1	5	CENTRO
1018006	10	ALTO PARANA	18	SAN ALBERTO	1	6	SANTA MARIA
1018007	10	ALTO PARANA	18	SAN ALBERTO	1	7	BETARRAN
1018110	10	ALTO PARANA	18	SAN ALBERTO	6	110	8 DE DICIEMBRE
1018120	10	ALTO PARANA	18	SAN ALBERTO	6	120	GUARAPUABA
1018130	10	ALTO PARANA	18	SAN ALBERTO	6	130	ITAIPU PORA
1018140	10	ALTO PARANA	18	SAN ALBERTO	6	140	SANTA TERESA
1018160	10	ALTO PARANA	18	SAN ALBERTO	6	160	SAN FRANCISCO GLEVA 8
1018165	10	ALTO PARANA	18	SAN ALBERTO	3	165	SAN FRANCISCO GLEVA 8 SUB-URBANO
1018170	10	ALTO PARANA	18	SAN ALBERTO	6	170	SAN RAMON
1018175	10	ALTO PARANA	18	SAN ALBERTO	3	175	SAN RAMON SUB-URBANO
1018190	10	ALTO PARANA	18	SAN ALBERTO	6	190	SAN ISIDRO
1018200	10	ALTO PARANA	18	SAN ALBERTO	6	200	CRUCE SAN ALBERTO
1018205	10	ALTO PARANA	18	SAN ALBERTO	3	205	CRUCE SAN ALBERTO SUB-URBANO
1018210	10	ALTO PARANA	18	SAN ALBERTO	6	210	SAN PATRICIO
1018230	10	ALTO PARANA	18	SAN ALBERTO	6	230	SAN ANTONIO
1018240	10	ALTO PARANA	18	SAN ALBERTO	6	240	TAPE PORA SAN ALBERTO
1018260	10	ALTO PARANA	18	SAN ALBERTO	6	260	MARIA AUXILIADORA
1018270	10	ALTO PARANA	18	SAN ALBERTO	6	270	SANTA ROSA
1018300	10	ALTO PARANA	18	SAN ALBERTO	6	300	ITAIPYTE
1018310	10	ALTO PARANA	18	SAN ALBERTO	6	310	LAGUNA
1018320	10	ALTO PARANA	18	SAN ALBERTO	6	320	LINEA DE NAVEGACION
1018330	10	ALTO PARANA	18	SAN ALBERTO	6	330	LOTE 9
1018340	10	ALTO PARANA	18	SAN ALBERTO	6	340	SAN FRANCISCO
1018350	10	ALTO PARANA	18	SAN ALBERTO	6	350	SANTO TOMAS
1018360	10	ALTO PARANA	18	SAN ALBERTO	6	360	SEXTA LINEA
1019001	10	ALTO PARANA	19	IRUNA	1	1	CENTRO
1019100	10	ALTO PARANA	19	IRUNA	6	100	PASO TIGRE
1019110	10	ALTO PARANA	19	IRUNA	6	110	SAN MIGUEL
1019120	10	ALTO PARANA	19	IRUNA	6	120	JERUSALEN 1
1019130	10	ALTO PARANA	19	IRUNA	6	130	JERUSALEN 2
1019140	10	ALTO PARANA	19	IRUNA	6	140	SAN PEDRO
1019150	10	ALTO PARANA	19	IRUNA	6	150	CONSUELO
1019160	10	ALTO PARANA	19	IRUNA	6	160	BOQUERON
1019170	10	ALTO PARANA	19	IRUNA	6	170	SANTA TERESITA
1019210	10	ALTO PARANA	19	IRUNA	6	210	SAN ROQUE
1019220	10	ALTO PARANA	19	IRUNA	6	220	SAN CARLOS
1019230	10	ALTO PARANA	19	IRUNA	6	230	SAN VICENTE FERRER
1019240	10	ALTO PARANA	19	IRUNA	6	240	JERUSALEN 3
1019270	10	ALTO PARANA	19	IRUNA	6	270	KAAGUY PORA
1019290	10	ALTO PARANA	19	IRUNA	6	290	LINEA DOS HERMANOS
1020001	10	ALTO PARANA	20	SANTA FE DEL PARANA	1	1	URBANO
1020100	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	100	GLEVA 12 GENERAL DIAZ
1020110	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	110	GLEVA 7
1020120	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	120	GLEVA 5 PIKYRY
1020140	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	140	PYKYRY
1020420	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	420	SANTA FE NUEVA ESPERANZA
1020430	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	430	ASENT. NINO JESUS
1020450	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	450	GLEVA 23 COLONIA ITAIPU
1020460	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	460	ASENT. MARACAMOA
1020470	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	470	ASENT. SANTIAGO MARTINEZ
1020480	10	ALTO PARANA	20	SANTA FE DEL PARANA	6	480	GLEVA 11
1021001	10	ALTO PARANA	21	TAVAPY	1	1	SAN ANTONIO
1021002	10	ALTO PARANA	21	TAVAPY	1	2	SAN JOSE
1021003	10	ALTO PARANA	21	TAVAPY	1	3	CENTRO
1021004	10	ALTO PARANA	21	TAVAPY	1	4	SAN ROQUE
1021005	10	ALTO PARANA	21	TAVAPY	1	5	SAN RAMON
1021006	10	ALTO PARANA	21	TAVAPY	1	6	SANTA CATALINA
1021110	10	ALTO PARANA	21	TAVAPY	6	110	DOLORES
1021140	10	ALTO PARANA	21	TAVAPY	6	140	FATIMA
1021150	10	ALTO PARANA	21	TAVAPY	6	150	SAN BLAS
1021170	10	ALTO PARANA	21	TAVAPY	6	170	TAVAPY 1
1021190	10	ALTO PARANA	21	TAVAPY	6	190	8 DE DICIEMBRE
1021200	10	ALTO PARANA	21	TAVAPY	6	200	TAVAPY 2
1021210	10	ALTO PARANA	21	TAVAPY	6	210	COLONIA GUEMBETY MI
1021220	10	ALTO PARANA	21	TAVAPY	6	220	ESTANCIA RANCHO GRANDE AGRO FORESTAL S.A
1021230	10	ALTO PARANA	21	TAVAPY	6	230	ASENT. FATIMA
1021240	10	ALTO PARANA	21	TAVAPY	6	240	ASENT. SEBASTIAN LARROSA
1021250	10	ALTO PARANA	21	TAVAPY	6	250	FULGENCIO R. MORENO
1021260	10	ALTO PARANA	21	TAVAPY	6	260	ESTANCIA PANAMBU - FRANCIS PERRIER
1021270	10	ALTO PARANA	21	TAVAPY	6	270	SANTA LUCIA
1022001	10	ALTO PARANA	22	DR. RAUL PENA	1	1	URBANO
1022230	10	ALTO PARANA	22	DR. RAUL PENA	6	230	AGROPECO
1022240	10	ALTO PARANA	22	DR. RAUL PENA	6	240	RAUL PENA  MCAL ESTIGARRIBIA
1022280	10	ALTO PARANA	22	DR. RAUL PENA	6	280	RAUL PENA LINEA SAN PABLO
1022290	10	ALTO PARANA	22	DR. RAUL PENA	6	290	RAUL PENA LINEA PRIMERO DE MARZO
1022300	10	ALTO PARANA	22	DR. RAUL PENA	6	300	RAUL PENA
1101001	11	CENTRAL	1	AREGUA	1	1	SAN MIGUEL
1101002	11	CENTRAL	1	AREGUA	1	2	SANTO DOMINGO
1101003	11	CENTRAL	1	AREGUA	1	3	SAN ROQUE
1101004	11	CENTRAL	1	AREGUA	1	4	BOQUERON
1101005	11	CENTRAL	1	AREGUA	1	5	SAN CAYETANO
1101006	11	CENTRAL	1	AREGUA	1	6	SANTA CATALINA
1101007	11	CENTRAL	1	AREGUA	1	7	LAS MERCEDES
1101100	11	CENTRAL	1	AREGUA	6	100	PINDOLO
1101110	11	CENTRAL	1	AREGUA	6	110	KOKUE GUASU
1101120	11	CENTRAL	1	AREGUA	6	120	KOKUE GUASU SAN ANTONIO
1101130	11	CENTRAL	1	AREGUA	6	130	ESTANZUELA
1101140	11	CENTRAL	1	AREGUA	6	140	COSTA FLEITAS
1101150	11	CENTRAL	1	AREGUA	6	150	JUKYTY
1101160	11	CENTRAL	1	AREGUA	6	160	CONAVI TAJY POTY
1101170	11	CENTRAL	1	AREGUA	6	170	VALLE PUKU
1101190	11	CENTRAL	1	AREGUA	6	190	JUKYRY
1101200	11	CENTRAL	1	AREGUA	6	200	ISLA VALLE
1101210	11	CENTRAL	1	AREGUA	6	210	VILLA SALVADOR
1101220	11	CENTRAL	1	AREGUA	6	220	ASENT. VIRGEN DE FATIMA
1101230	11	CENTRAL	1	AREGUA	6	230	VILLA ROSITA
1101240	11	CENTRAL	1	AREGUA	6	240	CAACUPEMI 1
1101250	11	CENTRAL	1	AREGUA	6	250	CAACUPEMI 2
1101260	11	CENTRAL	1	AREGUA	6	260	ASENT. VILLA DEL MAESTRO
1101270	11	CENTRAL	1	AREGUA	6	270	DIVINO NINO JESUS
1101280	11	CENTRAL	1	AREGUA	6	280	PINDOLO MARIA AUXILIADORA
1101290	11	CENTRAL	1	AREGUA	6	290	ASENT. CAACUPEMI 1
1101300	11	CENTRAL	1	AREGUA	6	300	ASENT. CAACUPEMI 2
1101320	11	CENTRAL	1	AREGUA	6	320	ASENT. COSTA FLEITAS
1101330	11	CENTRAL	1	AREGUA	6	330	ASENT. DIVINO NINO JESUS
1101350	11	CENTRAL	1	AREGUA	6	350	FRACCION YVOTY
1101360	11	CENTRAL	1	AREGUA	6	360	FRACCION ALICIA
1101370	11	CENTRAL	1	AREGUA	6	370	CAACUPEMI SAN MIGUEL
1101380	11	CENTRAL	1	AREGUA	6	380	SAN MIGUEL
1102001	11	CENTRAL	2	CAPIATA	1	1	COSTA SALINAS
1102002	11	CENTRAL	2	CAPIATA	1	2	NARANJATY
1102004	11	CENTRAL	2	CAPIATA	1	4	VILLA MILITAR
1102006	11	CENTRAL	2	CAPIATA	1	6	LAURELTY
1102007	11	CENTRAL	2	CAPIATA	1	7	CAMPOS VERDES
1102008	11	CENTRAL	2	CAPIATA	1	8	CANDELARIA
1102009	11	CENTRAL	2	CAPIATA	1	9	ROBERTO L PETIT
1102010	11	CENTRAL	2	CAPIATA	1	10	COSTA SALINARES
1102011	11	CENTRAL	2	CAPIATA	1	11	SANTA LIBRADA
1102012	11	CENTRAL	2	CAPIATA	1	12	PUERTA DEL SOL
1102013	11	CENTRAL	2	CAPIATA	1	13	ARATIRI
1102014	11	CENTRAL	2	CAPIATA	1	14	LOMA CLAVEL
1102015	11	CENTRAL	2	CAPIATA	1	15	TORREMOLINOS
1102016	11	CENTRAL	2	CAPIATA	1	16	SAN MIGUEL
1102017	11	CENTRAL	2	CAPIATA	1	17	SAN LORENZO
1102018	11	CENTRAL	2	CAPIATA	1	18	CAPSA
1102019	11	CENTRAL	2	CAPIATA	1	19	ARRUA
1102020	11	CENTRAL	2	CAPIATA	1	20	TOLEDO CANADA
1102021	11	CENTRAL	2	CAPIATA	1	21	ALDANA CANADA
1102022	11	CENTRAL	2	CAPIATA	1	22	ROJAS CANADA
1102023	11	CENTRAL	2	CAPIATA	1	23	LOMA BARRERO
1102024	11	CENTRAL	2	CAPIATA	1	24	YATAITY
1102025	11	CENTRAL	2	CAPIATA	1	25	SAN JORGE
1102026	11	CENTRAL	2	CAPIATA	1	26	VIRGEN DEL PILAR
1102027	11	CENTRAL	2	CAPIATA	1	27	RETIRO
1102028	11	CENTRAL	2	CAPIATA	1	28	POSTA YBYCUA
1102029	11	CENTRAL	2	CAPIATA	1	29	URUGUAY
1102030	11	CENTRAL	2	CAPIATA	1	30	YBYRARO
1102031	11	CENTRAL	2	CAPIATA	1	31	CERRITO
1102032	11	CENTRAL	2	CAPIATA	1	32	SANTISIMA CRUZ
1102033	11	CENTRAL	2	CAPIATA	1	33	SANTA RITA
1102034	11	CENTRAL	2	CAPIATA	1	34	CASCO URBANO
1102035	11	CENTRAL	2	CAPIATA	1	35	SAN ROQUE
1102036	11	CENTRAL	2	CAPIATA	1	36	SANTO DOMINGO
1102037	11	CENTRAL	2	CAPIATA	1	37	KENNEDY
1103001	11	CENTRAL	3	FERNANDO DE LA MORA	1	1	ORILLA DEL CAMPO GRANDE
1103002	11	CENTRAL	3	FERNANDO DE LA MORA	1	2	VILLA OFELIA
1103003	11	CENTRAL	3	FERNANDO DE LA MORA	1	3	LAGUNA GRANDE
1103005	11	CENTRAL	3	FERNANDO DE LA MORA	1	5	ESTANZUELA
1103007	11	CENTRAL	3	FERNANDO DE LA MORA	1	7	LAGUNA SATI
1103008	11	CENTRAL	3	FERNANDO DE LA MORA	1	8	DOMINGO SAVIO
1103010	11	CENTRAL	3	FERNANDO DE LA MORA	1	10	BERNARDINO CABALLERO
1103011	11	CENTRAL	3	FERNANDO DE LA MORA	1	11	KOKUE GUASU
1103012	11	CENTRAL	3	FERNANDO DE LA MORA	1	12	PITIANTUTA
1103013	11	CENTRAL	3	FERNANDO DE LA MORA	1	13	ITA KA'AGUY
1103014	11	CENTRAL	3	FERNANDO DE LA MORA	1	14	TRES BOCAS
1103015	11	CENTRAL	3	FERNANDO DE LA MORA	1	15	SAN JUAN
1103016	11	CENTRAL	3	FERNANDO DE LA MORA	1	16	SAN ANTONIO
1103017	11	CENTRAL	3	FERNANDO DE LA MORA	1	17	CENTRAL
1103018	11	CENTRAL	3	FERNANDO DE LA MORA	1	18	RESIDENTAS
1104001	11	CENTRAL	4	GUARAMBARE	1	1	FELSINA
1104002	11	CENTRAL	4	GUARAMBARE	1	2	SAN MIGUEL
1104003	11	CENTRAL	4	GUARAMBARE	1	3	COLON
1104004	11	CENTRAL	4	GUARAMBARE	1	4	ALEGRE
1104005	11	CENTRAL	4	GUARAMBARE	1	5	ASENT. SAN RAFAEL
1104006	11	CENTRAL	4	GUARAMBARE	1	6	ASENT. COLON
1104100	11	CENTRAL	4	GUARAMBARE	6	100	YVYSUNU
1104110	11	CENTRAL	4	GUARAMBARE	3	110	NUEVA ESPERANZA SUB-URBANO
1104120	11	CENTRAL	4	GUARAMBARE	6	120	RINCON
1104130	11	CENTRAL	4	GUARAMBARE	6	130	KAUGUA
1104140	11	CENTRAL	4	GUARAMBARE	6	140	RINCON LOMA'I
1104145	11	CENTRAL	4	GUARAMBARE	3	145	RINCON LOMA'I SUB-URBANO
1104150	11	CENTRAL	4	GUARAMBARE	6	150	TYPYCHATY
1104160	11	CENTRAL	4	GUARAMBARE	3	160	VILLA LAURA SUB-URBANO
1104170	11	CENTRAL	4	GUARAMBARE	6	170	ASENT. KAUGUA
1104200	11	CENTRAL	4	GUARAMBARE	6	200	ASENT. LIBRADA
1105001	11	CENTRAL	5	ITA	1	1	SPORTIVO
1105002	11	CENTRAL	5	ITA	1	2	CERRO CORA
1105003	11	CENTRAL	5	ITA	1	3	SAN BLAS
1105004	11	CENTRAL	5	ITA	1	4	ASENT. SAN ANTONO- NUEVA ESPERANZA
1105005	11	CENTRAL	5	ITA	1	5	ASENT. SAN ANTONIO - JEROVIA
1105006	11	CENTRAL	5	ITA	1	6	ASENT. VY'A RENDA-SAN ANTONIO
1105007	11	CENTRAL	5	ITA	1	7	ASENT. KOKUERE
1105008	11	CENTRAL	5	ITA	1	8	SAN ANTONIO - FRACCION LAS PERLAS
1105009	11	CENTRAL	5	ITA	1	9	30 DE AGOSTO
1105100	11	CENTRAL	5	ITA	6	100	VALLE JO'A
1105110	11	CENTRAL	5	ITA	6	110	TAPE TUJA
1105120	11	CENTRAL	5	ITA	6	120	AVEIRO
1105125	11	CENTRAL	5	ITA	3	125	AVEIRO 1 SUB-URBANO
1105130	11	CENTRAL	5	ITA	6	130	OCULTO
1105140	11	CENTRAL	5	ITA	6	140	ARRUA'I
1105150	11	CENTRAL	5	ITA	6	150	ITA POTRERO
1105155	11	CENTRAL	5	ITA	3	155	ITA POTRERO SUB-URBANO
1105160	11	CENTRAL	5	ITA	6	160	KURUPIKA'YTY
1105170	11	CENTRAL	5	ITA	6	170	CALLE YVATE
1105180	11	CENTRAL	5	ITA	6	180	HUGUA NARO
1105190	11	CENTRAL	5	ITA	6	190	POTRERO PO'I
1105200	11	CENTRAL	5	ITA	6	200	CALLE PO'I
1105210	11	CENTRAL	5	ITA	6	210	YHOVY
1105220	11	CENTRAL	5	ITA	6	220	KARAGUATAYTY
1105230	11	CENTRAL	5	ITA	6	230	PEGUAHO
1105235	11	CENTRAL	5	ITA	3	235	AVEIRO 2 SUB-URBANO
1105240	11	CENTRAL	5	ITA	6	240	CAAGUAZU
1105245	11	CENTRAL	5	ITA	3	245	MARIA AUXILIADORA SUB-URBANO
1105250	11	CENTRAL	5	ITA	6	250	LAS PIEDRAS
1105255	11	CENTRAL	5	ITA	3	255	KUARAHY RESE AVEIRO SUB-URBANO
1105260	11	CENTRAL	5	ITA	6	260	POSTA GAONA
1105270	11	CENTRAL	5	ITA	6	270	ASENT. KARAGUATAITY
1105280	11	CENTRAL	5	ITA	6	280	ASENT. ITA POTRERO
1105290	11	CENTRAL	5	ITA	6	290	ASENT. SAN BLAS
1105300	11	CENTRAL	5	ITA	6	300	ASENT. SAN CRISTOBAL - AVEIRO
1105310	11	CENTRAL	5	ITA	6	310	PARANAMBU 2
1106001	11	CENTRAL	6	ITAUGUA	1	1	GUAYAIBITY 1
1106002	11	CENTRAL	6	ITAUGUA	1	2	ASENT SAN MIGUEL
1106003	11	CENTRAL	6	ITAUGUA	1	3	ASENT ROSA MISTICA
1106004	11	CENTRAL	6	ITAUGUA	1	4	SAN MIGUEL
1106005	11	CENTRAL	6	ITAUGUA	1	5	GUAYAIBITY 2
1106006	11	CENTRAL	6	ITAUGUA	1	6	VALLE KARE
1106007	11	CENTRAL	6	ITAUGUA	1	7	SAN AGUSTIN
1106008	11	CENTRAL	6	ITAUGUA	1	8	CANADITA
1106009	11	CENTRAL	6	ITAUGUA	1	9	VILLA JARDIN
1106010	11	CENTRAL	6	ITAUGUA	1	10	VIRGEN DEL ROSARIO
1106011	11	CENTRAL	6	ITAUGUA	1	11	ARSENIO ERICO
1106012	11	CENTRAL	6	ITAUGUA	1	12	YBYRATY
1106013	11	CENTRAL	6	ITAUGUA	1	13	BARRIO 6
1106014	11	CENTRAL	6	ITAUGUA	1	14	GUASU VIRA
1106015	11	CENTRAL	6	ITAUGUA	1	15	POTRERO
1106016	11	CENTRAL	6	ITAUGUA	1	16	CRISTO REY
1106017	11	CENTRAL	6	ITAUGUA	1	17	VIRGEN DEL CARMEN
1106018	11	CENTRAL	6	ITAUGUA	1	18	NINO JESUS
1106019	11	CENTRAL	6	ITAUGUA	1	19	ITAUGUA GUASU
1106020	11	CENTRAL	6	ITAUGUA	1	20	LAURELTY
1106021	11	CENTRAL	6	ITAUGUA	1	21	HUGUA POTI
1106022	11	CENTRAL	6	ITAUGUA	1	22	SANTA CECILIA
1106023	11	CENTRAL	6	ITAUGUA	1	23	SAN JUAN 1
1106024	11	CENTRAL	6	ITAUGUA	1	24	ASENT ALDAMA CANADA
1106026	11	CENTRAL	6	ITAUGUA	1	26	FRACCION DIANA
1106027	11	CENTRAL	6	ITAUGUA	1	27	VILLA CONAVI
1106028	11	CENTRAL	6	ITAUGUA	1	28	ASENT MBOCAYATY DEL SUR
1106029	11	CENTRAL	6	ITAUGUA	1	29	MBOCAYATY DEL SUR
1106030	11	CENTRAL	6	ITAUGUA	1	30	FRACCION SAN MIGUEL
1106031	11	CENTRAL	6	ITAUGUA	1	31	SAN JOSE
1106032	11	CENTRAL	6	ITAUGUA	1	32	LA COLINA
1106033	11	CENTRAL	6	ITAUGUA	1	33	UNION GUARANI
1106034	11	CENTRAL	6	ITAUGUA	1	34	VILLA ALTA
1106035	11	CENTRAL	6	ITAUGUA	1	35	SAN CAYETANO
1106036	11	CENTRAL	6	ITAUGUA	1	36	MBOCAYATY DEL NORTE
1106037	11	CENTRAL	6	ITAUGUA	1	37	ASENT MBOCAYATY DEL NORTE
1106038	11	CENTRAL	6	ITAUGUA	1	38	LAS MERCEDES
1106039	11	CENTRAL	6	ITAUGUA	1	39	GUADALUPE
1106040	11	CENTRAL	6	ITAUGUA	1	40	SAN JOSE OBRERO
1106041	11	CENTRAL	6	ITAUGUA	1	41	SAN ROQUE
1106042	11	CENTRAL	6	ITAUGUA	1	42	SANTA TERESA
1106043	11	CENTRAL	6	ITAUGUA	1	43	OKARA POTY
1106044	11	CENTRAL	6	ITAUGUA	1	44	CORAZON DE JESUS
1106045	11	CENTRAL	6	ITAUGUA	1	45	6 DE ENERO
1106046	11	CENTRAL	6	ITAUGUA	1	46	SAN ISIDRO
1106047	11	CENTRAL	6	ITAUGUA	1	47	YCUA NARANJA
1106048	11	CENTRAL	6	ITAUGUA	1	48	MBOCAYATY DEL SUR 2
1106049	11	CENTRAL	6	ITAUGUA	1	49	CONAVI MBOCAYATY 1
1106050	11	CENTRAL	6	ITAUGUA	1	50	CONAVI MBOCAYATY 3
1106051	11	CENTRAL	6	ITAUGUA	1	51	SAN JUAN 2
1106052	11	CENTRAL	6	ITAUGUA	1	52	CONAVI MBOCAYATY 2
1106053	11	CENTRAL	6	ITAUGUA	1	53	ASENT VIRGEN DEL ROSARIO
1106054	11	CENTRAL	6	ITAUGUA	1	54	SANTA ANA
1106055	11	CENTRAL	6	ITAUGUA	1	55	ASENT ALDAMA CANADA 2
1106056	11	CENTRAL	6	ITAUGUA	1	56	ALDAMA CANADA
1106057	11	CENTRAL	6	ITAUGUA	1	57	VILLA REGINA
1106100	11	CENTRAL	6	ITAUGUA	6	100	ESTANZUELA
1106110	11	CENTRAL	6	ITAUGUA	6	110	PATINO
1106130	11	CENTRAL	6	ITAUGUA	6	130	YBYRATY
1106150	11	CENTRAL	6	ITAUGUA	6	150	CANADITA
1106160	11	CENTRAL	6	ITAUGUA	6	160	ITAUGUA GUASU
1106180	11	CENTRAL	6	ITAUGUA	6	180	GUASU VIRA
1106190	11	CENTRAL	6	ITAUGUA	6	190	POTRERITO
1106200	11	CENTRAL	6	ITAUGUA	6	200	ALDAMA CANADA
1106210	11	CENTRAL	6	ITAUGUA	6	210	SAN ANTONIO
1106220	11	CENTRAL	6	ITAUGUA	6	220	POTRERO GUASU
1106230	11	CENTRAL	6	ITAUGUA	6	230	NU PO'I
1106235	11	CENTRAL	6	ITAUGUA	3	235	PATINO SUB-URBANO
1106240	11	CENTRAL	6	ITAUGUA	6	240	ASENT ITAUGUA GUAZU
1106250	11	CENTRAL	6	ITAUGUA	6	250	ASENT NU PO'I
1106260	11	CENTRAL	6	ITAUGUA	6	260	ASENT NU PO'I 2
1106270	11	CENTRAL	6	ITAUGUA	6	270	ASENT CANADITA
1106280	11	CENTRAL	6	ITAUGUA	3	280	MBOIY SAN ROQUE SUB-URBANO
1107001	11	CENTRAL	7	LAMBARE	1	1	PALOMAR
1107002	11	CENTRAL	7	LAMBARE	1	2	SAN RAFAEL
1107003	11	CENTRAL	7	LAMBARE	1	3	SANTO DOMINGO
1107004	11	CENTRAL	7	LAMBARE	1	4	PANAMBIRETA
1107005	11	CENTRAL	7	LAMBARE	1	5	PILAR
1107006	11	CENTRAL	7	LAMBARE	1	6	FELICIDAD
1107007	11	CENTRAL	7	LAMBARE	1	7	SANTA ROSA II
1107008	11	CENTRAL	7	LAMBARE	1	8	MARISCAL LOPEZ
1107009	11	CENTRAL	7	LAMBARE	1	9	VALLE YVATE
1107010	11	CENTRAL	7	LAMBARE	1	10	SANTA LUISA
1107011	11	CENTRAL	7	LAMBARE	1	11	SANTA LUCIA
1107012	11	CENTRAL	7	LAMBARE	1	12	LA VICTORIA
1107013	11	CENTRAL	7	LAMBARE	1	13	KENNEDY
1107014	11	CENTRAL	7	LAMBARE	1	14	CENTRO URBANO
1107015	11	CENTRAL	7	LAMBARE	1	15	VILLA VIRGINIA
1107016	11	CENTRAL	7	LAMBARE	1	16	VILLA CERRO CORA
1107017	11	CENTRAL	7	LAMBARE	1	17	VALLE APU'A I
1107018	11	CENTRAL	7	LAMBARE	1	18	CUATRO MOJONES
1107019	11	CENTRAL	7	LAMBARE	1	19	SAN ANTONIO
1107020	11	CENTRAL	7	LAMBARE	1	20	SAN ROQUE GONZALEZ DE SANTA CRUZ
1107021	11	CENTRAL	7	LAMBARE	1	21	CANADA SAN MIGUEL
1107022	11	CENTRAL	7	LAMBARE	1	22	MBACHIO II
1107023	11	CENTRAL	7	LAMBARE	1	23	PUERTO PABLA
1107024	11	CENTRAL	7	LAMBARE	1	24	SAN ISIDRO
1107025	11	CENTRAL	7	LAMBARE	1	25	SANTA ROSA I
1107026	11	CENTRAL	7	LAMBARE	1	26	MBACHIO I
1107027	11	CENTRAL	7	LAMBARE	1	27	PARQUES DEL YACHT
1107028	11	CENTRAL	7	LAMBARE	1	28	VALLE APU'A II
1108001	11	CENTRAL	8	LIMPIO	1	1	PIQUETE CUE CENTRO
1108002	11	CENTRAL	8	LIMPIO	1	2	CENTRO CAACUPEMI
1108003	11	CENTRAL	8	LIMPIO	1	3	RINCON DEL PENON 15 DE AGOSTO
1108005	11	CENTRAL	8	LIMPIO	1	5	SALADO
1108007	11	CENTRAL	8	LIMPIO	1	7	RINCON DEL PENON SAN SALVADOR
1108008	11	CENTRAL	8	LIMPIO	1	8	CENTRO SAN PEDRO
1108009	11	CENTRAL	8	LIMPIO	1	9	CENTRO SAN JOSE
1108010	11	CENTRAL	8	LIMPIO	1	10	CENTRO SAN ANTONIO
1108011	11	CENTRAL	8	LIMPIO	1	11	CENTRO SANTA LUCIA
1108012	11	CENTRAL	8	LIMPIO	1	12	MONTANA ALTA 1
1108013	11	CENTRAL	8	LIMPIO	1	13	MBAYUE SAN FRANCISCO
1108014	11	CENTRAL	8	LIMPIO	1	14	MBAYUE FILADELFIA
1108015	11	CENTRAL	8	LIMPIO	1	15	CENTRO SAN RAMON
1108016	11	CENTRAL	8	LIMPIO	1	16	CENTRO SAN FRANCISCO
1108017	11	CENTRAL	8	LIMPIO	1	17	CENTRO SANTA LIBRADA
1108018	11	CENTRAL	8	LIMPIO	1	18	MONTANA ALTA 8 DE DICIEMBRE
1108019	11	CENTRAL	8	LIMPIO	1	19	AGUAPEY
1108020	11	CENTRAL	8	LIMPIO	1	20	ISLA ARANDA
1108021	11	CENTRAL	8	LIMPIO	1	21	CENTRO SAN JUAN
1108022	11	CENTRAL	8	LIMPIO	1	22	ISLA AVEIRO
1108023	11	CENTRAL	8	LIMPIO	1	23	COLONIA JUAN DE SALAZAR
1108024	11	CENTRAL	8	LIMPIO	1	24	PIQUETE CUE SANTA LUCIA
1108025	11	CENTRAL	8	LIMPIO	1	25	PIQUETE CUE SAN ANTONIO
1108026	11	CENTRAL	8	LIMPIO	1	26	PIQUETE CUE SAN MIGUEL
1108027	11	CENTRAL	8	LIMPIO	1	27	PIQUETE CUE VIRGEN DE FATIMA
1108028	11	CENTRAL	8	LIMPIO	1	28	RINCON DEL PENON EL PROGRESO
1108029	11	CENTRAL	8	LIMPIO	1	29	ASENT.15 DE AGOSTO RINCON DEL PENON
1108030	11	CENTRAL	8	LIMPIO	1	30	RINCON DEL PENON FRACCION SANTA ROSA
1108031	11	CENTRAL	8	LIMPIO	1	31	RINCON DEL PENON VILLA FLAMENGO 1 Y 2
1108032	11	CENTRAL	8	LIMPIO	1	32	RINCON DEL PENON LOS CISNES
1108033	11	CENTRAL	8	LIMPIO	1	33	RINCON DEL PENON NUEVA ASUNCION
1108034	11	CENTRAL	8	LIMPIO	1	34	ASENT. STA LIBRADA 1 RINCON DEL PENON
1108035	11	CENTRAL	8	LIMPIO	1	35	ASENT AURORA RINCON DEL PENON
1108036	11	CENTRAL	8	LIMPIO	1	36	ASENT.  24 DE MAYO RINCON DEL PENON
1108037	11	CENTRAL	8	LIMPIO	1	37	ASENT. SAN MIGUEL CENTRO
1108038	11	CENTRAL	8	LIMPIO	1	38	ASENT. SALADITO CENTRO
1108039	11	CENTRAL	8	LIMPIO	1	39	ASENT. EL PENON CENTRO
1108040	11	CENTRAL	8	LIMPIO	1	40	ASENT. SANTO DOMINGO SALADO
1108041	11	CENTRAL	8	LIMPIO	1	41	ASENT. CARMEN SOLER SALADO
1108042	11	CENTRAL	8	LIMPIO	1	42	ASENT.  LA VICTORIA MONTANA ALTA
1108043	11	CENTRAL	8	LIMPIO	1	43	ASENT. SANTA ROSA
1108044	11	CENTRAL	8	LIMPIO	1	44	RINCON DEL PENON SAN ANTONIO
1108045	11	CENTRAL	8	LIMPIO	1	45	ASENT. SANTA LIBRADA 2 RINCON DEL PENON
1108046	11	CENTRAL	8	LIMPIO	1	46	RINCON DEL PENON VILLA ROCIO
1108047	11	CENTRAL	8	LIMPIO	1	47	CENTRO SAN ROQUE
1108048	11	CENTRAL	8	LIMPIO	1	48	RINCON DEL PENON 24 DE MAYO
1108052	11	CENTRAL	8	LIMPIO	1	52	ISLA AVEIRO SAN RAFAEL
1108053	11	CENTRAL	8	LIMPIO	1	53	ISLA AVEIRO ANAHI
1108054	11	CENTRAL	8	LIMPIO	1	54	ISLA AVEIRO AMANECER
1108055	11	CENTRAL	8	LIMPIO	1	55	ISLA AVEIRO SAN JORGE
1108056	11	CENTRAL	8	LIMPIO	1	56	MBAYUE SAN AGUSTIN
1108057	11	CENTRAL	8	LIMPIO	1	57	ASENT.  NUEVA ESPERANZA ISLA AVEIRO
1108058	11	CENTRAL	8	LIMPIO	1	58	ASENT. VILLA NAVIDAD
1108059	11	CENTRAL	8	LIMPIO	1	59	ISLA AVEIRO SAN CAYETANO
1108060	11	CENTRAL	8	LIMPIO	1	60	ISLA AVEIRO VILLA NAVIDAD
1108061	11	CENTRAL	8	LIMPIO	1	61	ASENT.  SAN AGUSTIN MBAYUE
1108062	11	CENTRAL	8	LIMPIO	1	62	MBAYUE LOMBARDIA
1108063	11	CENTRAL	8	LIMPIO	1	63	ISLA AVEIRO VILLA HUGUITO
1108064	11	CENTRAL	8	LIMPIO	1	64	MBAYUE SAN FERNANDO
1108065	11	CENTRAL	8	LIMPIO	1	65	MBAYUE VILLA REINA SOFIA
1108066	11	CENTRAL	8	LIMPIO	1	66	COSTA AZUL
1108067	11	CENTRAL	8	LIMPIO	1	67	ASENT. SAN JUAN CENTRO
1108068	11	CENTRAL	8	LIMPIO	1	68	SALADO VILLA ENRIQUE
1108071	11	CENTRAL	8	LIMPIO	1	71	MBAYUE SAN VICENTE
1108072	11	CENTRAL	8	LIMPIO	1	72	FRACCION SAN MARCOS 1 Y 2
1108073	11	CENTRAL	8	LIMPIO	1	73	AGUAPEY VILLA DON  BOSCO
1108074	11	CENTRAL	8	LIMPIO	1	74	AGUAPEY VILLA PA'I CLEBA
1108075	11	CENTRAL	8	LIMPIO	1	75	MONTANA ALTA LAS MAGNOLIAS
1108076	11	CENTRAL	8	LIMPIO	1	76	MONTANA ALTA 2
1108077	11	CENTRAL	8	LIMPIO	1	77	SALADO MARIA AUXILIADORA
1108078	11	CENTRAL	8	LIMPIO	1	78	ASENT. VILLA CRISTINA SALADO
1108079	11	CENTRAL	8	LIMPIO	1	79	SALADO AMANECER
1108080	11	CENTRAL	8	LIMPIO	1	80	RINCON DEL PENON SANTO DOMINGO
1108081	11	CENTRAL	8	LIMPIO	1	81	RINCON DEL PENON SANTA ROSA
1108082	11	CENTRAL	8	LIMPIO	1	82	RINCON DEL PENON LAS MERCEDES
1108083	11	CENTRAL	8	LIMPIO	1	83	RINCON DEL PENON SAN BLAS
1108084	11	CENTRAL	8	LIMPIO	1	84	ASENT.  FULGENCIO YEGROS SALADO
1108085	11	CENTRAL	8	LIMPIO	1	85	ASENT. SALADO
1108086	11	CENTRAL	8	LIMPIO	1	86	ASENT. SAN  CAYETANO SALADO
1108087	11	CENTRAL	8	LIMPIO	1	87	PILAR DEL NORTE
1108088	11	CENTRAL	8	LIMPIO	1	88	SAN BLAS JUAN DE SALAZAR
1108089	11	CENTRAL	8	LIMPIO	1	89	ASENT. ESPITIRU SANTO  ISLA AVEIRO
1108090	11	CENTRAL	8	LIMPIO	1	90	ASENT. NUEVO AMANECER AGUAPEY
1108091	11	CENTRAL	8	LIMPIO	1	91	RINCON DEL PENON AURORA
1108092	11	CENTRAL	8	LIMPIO	1	92	VILLA SAN MIGUEL
1108093	11	CENTRAL	8	LIMPIO	1	93	ISLA AVEIRO EL PORTAL
1108094	11	CENTRAL	8	LIMPIO	1	94	ISLA AVEIRO SAN JOSE
1108095	11	CENTRAL	8	LIMPIO	1	95	ISLA SAN FRANCISCO
1108096	11	CENTRAL	8	LIMPIO	1	96	ISLA AVEIRO BARRIO NORTE
1108097	11	CENTRAL	8	LIMPIO	1	97	ISLA AVEIRO EL BOSQUE
1108098	11	CENTRAL	8	LIMPIO	1	98	RINCON DEL PENON SAN ROQUE
1108099	11	CENTRAL	8	LIMPIO	1	99	ISLA AVEIRO EL EUCALIPTAL
1108100	11	CENTRAL	8	LIMPIO	1	100	RINCON DEL PENON COSTA AZUL
1108101	11	CENTRAL	8	LIMPIO	1	101	RINCON DEL PENON LA ESPERANZA
1108102	11	CENTRAL	8	LIMPIO	1	102	PASO CORREO
1108103	11	CENTRAL	8	LIMPIO	1	103	PIQUETE CUE SAN BLAS
1108104	11	CENTRAL	8	LIMPIO	1	104	PIQUETE CUE SANTA LIBRADA
1108105	11	CENTRAL	8	LIMPIO	1	105	PIQUETE CUE EL PENON 1 Y 2
1108106	11	CENTRAL	8	LIMPIO	1	106	PIQUETE CUE EL PENON 4
1108107	11	CENTRAL	8	LIMPIO	1	107	EL PENON 3
1108108	11	CENTRAL	8	LIMPIO	1	108	PIQUETE CUE SAN FRANCISCO
1108109	11	CENTRAL	8	LIMPIO	1	109	RINCON DEL PENON VILLA JARDIN
1108110	11	CENTRAL	8	LIMPIO	1	110	ASENT. 19 DE JUNIO
1108111	11	CENTRAL	8	LIMPIO	1	111	VILLA MADRID
1108112	11	CENTRAL	8	LIMPIO	1	112	CENTRO PARAISO
1108113	11	CENTRAL	8	LIMPIO	1	113	ASENT. BARCELONA
1109002	11	CENTRAL	9	LUQUE	1	2	ZARATE ISLA
1109003	11	CENTRAL	9	LUQUE	1	3	YCUA CARANDAY
1109004	11	CENTRAL	9	LUQUE	1	4	MORA KUE
1109005	11	CENTRAL	9	LUQUE	1	5	CANADA GARAY
1109006	11	CENTRAL	9	LUQUE	1	6	YCA'A
1109007	11	CENTRAL	9	LUQUE	1	7	LOMA MERLO
1109008	11	CENTRAL	9	LUQUE	1	8	PRIMER BARRIO
1109009	11	CENTRAL	9	LUQUE	1	9	SEGUNDO BARRIO
1109011	11	CENTRAL	9	LUQUE	1	11	CUARTO BARRIO
1109012	11	CENTRAL	9	LUQUE	1	12	CAMPO GRANDE
1109013	11	CENTRAL	9	LUQUE	1	13	HUGUA DE SEDA
1109014	11	CENTRAL	9	LUQUE	1	14	TERCER BARRIO
1109015	11	CENTRAL	9	LUQUE	1	15	MARAMBURE
1109016	11	CENTRAL	9	LUQUE	1	16	YAGUARETE CORA
1109017	11	CENTRAL	9	LUQUE	1	17	COSTA SOSA
1109018	11	CENTRAL	9	LUQUE	1	18	YCUA DURE
1109019	11	CENTRAL	9	LUQUE	1	19	MACA'I
1109020	11	CENTRAL	9	LUQUE	1	20	CANADA SAN RAFAEL
1109021	11	CENTRAL	9	LUQUE	1	21	ISLA BOGADO
1109022	11	CENTRAL	9	LUQUE	1	22	LAURELTY
1109023	11	CENTRAL	9	LUQUE	1	23	ITAPUAMI 1
1109024	11	CENTRAL	9	LUQUE	1	24	TARUMANDY
1109025	11	CENTRAL	9	LUQUE	1	25	MARIN CAAGUY
1109026	11	CENTRAL	9	LUQUE	1	26	ITA ANGU'A
1109027	11	CENTRAL	9	LUQUE	1	27	YUKYRY
1109100	11	CENTRAL	9	LUQUE	6	100	TARUMANDY
1109110	11	CENTRAL	9	LUQUE	6	110	ITAPUAMI 2
1109120	11	CENTRAL	9	LUQUE	6	120	NUEVA ASUNCION
1109125	11	CENTRAL	9	LUQUE	3	125	NUEVA ASUNCION SUB-URBANO
1109130	11	CENTRAL	9	LUQUE	6	130	ASENT ITAPUAMI
1109140	11	CENTRAL	9	LUQUE	6	140	ASENT TARUMANDY 2
1109150	11	CENTRAL	9	LUQUE	6	150	ASENT TARUMANDY 1
1109160	11	CENTRAL	9	LUQUE	6	160	ASENT NUEVA ASUNCION
1110001	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	1	CORUMBA KUE - UNIVERSO
1110002	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	2	SURUBI'Y
1110003	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	3	AREKAJA
1110004	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	4	KA'AGUY KUPE
1110005	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	5	SAN LUIS
1110007	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	7	CENTRAL
1110008	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	8	MARIA AUXILIADORA
1110009	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	9	REMANSO
1110010	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	10	BANADO
1110011	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	11	VILLA MARGARITA
1110012	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	12	ROSA MISTICA
1110013	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	13	DEFENSORES DEL CHACO
1110014	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	14	LA ASUNCION
1110015	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	15	SAN BLAS
1110016	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	16	SAN JORGE
1110017	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	17	SAN RAMON
1110018	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	18	LA CONCORDIA MONSENOR BOGARIN
1110019	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	19	CENTRAL II
1110020	11	CENTRAL	10	MARIANO ROQUE ALONSO	1	20	CAACUPEMI
1111001	11	CENTRAL	11	NUEVA ITALIA	1	1	SANTA ROSA
1111002	11	CENTRAL	11	NUEVA ITALIA	1	2	SAN PEDRO
1111003	11	CENTRAL	11	NUEVA ITALIA	1	3	SAN BLAS
1111004	11	CENTRAL	11	NUEVA ITALIA	1	4	SAN FRANCISCO
1111100	11	CENTRAL	11	NUEVA ITALIA	6	100	ITA YVATE'I
1111110	11	CENTRAL	11	NUEVA ITALIA	6	110	SAN PEDRO
1111120	11	CENTRAL	11	NUEVA ITALIA	6	120	JUKYTY
1111130	11	CENTRAL	11	NUEVA ITALIA	6	130	ISLA GUAVIRA
1111140	11	CENTRAL	11	NUEVA ITALIA	6	140	TAKUARA
1111150	11	CENTRAL	11	NUEVA ITALIA	6	150	PINDOTY
1111160	11	CENTRAL	11	NUEVA ITALIA	6	160	CHACO'I
1111170	11	CENTRAL	11	NUEVA ITALIA	6	170	2 DE MAYO
1111180	11	CENTRAL	11	NUEVA ITALIA	6	180	ASENT. ITA YVATE'I 2
1111190	11	CENTRAL	11	NUEVA ITALIA	6	190	ASENT. ITA YVATE'I 1
1111200	11	CENTRAL	11	NUEVA ITALIA	6	200	ASENT. ITA YVATE'I 3
1111210	11	CENTRAL	11	NUEVA ITALIA	6	210	TATARE
1112001	11	CENTRAL	12	NEMBY	1	1	VILLA ANITA
1112002	11	CENTRAL	12	NEMBY	1	2	PA'I NU
1112003	11	CENTRAL	12	NEMBY	1	3	MBOKAJATY
1112004	11	CENTRAL	12	NEMBY	1	4	VISTA ALEGRE
1112005	11	CENTRAL	12	NEMBY	1	5	CENTRO
1112007	11	CENTRAL	12	NEMBY	1	7	SALINAS
1112012	11	CENTRAL	12	NEMBY	1	12	RINCON
1112013	11	CENTRAL	12	NEMBY	1	13	CAAGUAZU
1112015	11	CENTRAL	12	NEMBY	1	15	CANADITA
1112017	11	CENTRAL	12	NEMBY	1	17	LOS NARANJOS
1112018	11	CENTRAL	12	NEMBY	1	18	3 DE MAYO
1113002	11	CENTRAL	13	SAN ANTONIO	1	2	PUEBLO
1113003	11	CENTRAL	13	SAN ANTONIO	1	3	SAN BLAS
1113004	11	CENTRAL	13	SAN ANTONIO	1	4	MARIA AUXILIADORA
1113007	11	CENTRAL	13	SAN ANTONIO	1	7	LA MERCED
1113008	11	CENTRAL	13	SAN ANTONIO	1	8	SAN ROQUE
1113009	11	CENTRAL	13	SAN ANTONIO	1	9	NARANJATY
1113012	11	CENTRAL	13	SAN ANTONIO	1	12	ACOSTA NU
1113013	11	CENTRAL	13	SAN ANTONIO	1	13	SAN JORGE
1113016	11	CENTRAL	13	SAN ANTONIO	1	16	SAN FRANCISCO
1113017	11	CENTRAL	13	SAN ANTONIO	1	17	ANTIGUA IMAGEN
1113018	11	CENTRAL	13	SAN ANTONIO	1	18	ACHUCARRO
1114001	11	CENTRAL	14	SAN LORENZO	1	1	LAURELTY
1114002	11	CENTRAL	14	SAN LORENZO	1	2	VILLA AMELIA
1114003	11	CENTRAL	14	SAN LORENZO	1	3	SAN MIGUEL
1114004	11	CENTRAL	14	SAN LORENZO	1	4	SANTO REY
1114005	11	CENTRAL	14	SAN LORENZO	1	5	VIRGEN DE LOS REMEDIOS
1114006	11	CENTRAL	14	SAN LORENZO	1	6	VILLA LAURELTY
1114007	11	CENTRAL	14	SAN LORENZO	1	7	SAGRADA FAMILIA
1114008	11	CENTRAL	14	SAN LORENZO	1	8	SAN JUAN - CALLE'I
1114009	11	CENTRAL	14	SAN LORENZO	1	9	SAN ISIDRO
1114010	11	CENTRAL	14	SAN LORENZO	1	10	SAN FRANCISCO
1114011	11	CENTRAL	14	SAN LORENZO	1	11	SANTA MARIA
1114012	11	CENTRAL	14	SAN LORENZO	1	12	SAN PEDRO - NU PORA
1114013	11	CENTRAL	14	SAN LORENZO	1	13	SAN RAMON
1114014	11	CENTRAL	14	SAN LORENZO	1	14	LAS MERCEDES
1114015	11	CENTRAL	14	SAN LORENZO	1	15	SAN JOSE
1114016	11	CENTRAL	14	SAN LORENZO	1	16	SANTA LUCIA
1114017	11	CENTRAL	14	SAN LORENZO	1	17	SAN RAFAEL
1114018	11	CENTRAL	14	SAN LORENZO	1	18	SAN ROQUE
1114019	11	CENTRAL	14	SAN LORENZO	1	19	INMACULADA
1114020	11	CENTRAL	14	SAN LORENZO	1	20	SAN PEDRO
1114021	11	CENTRAL	14	SAN LORENZO	1	21	SAN BLAS
1114022	11	CENTRAL	14	SAN LORENZO	1	22	CORAZON DE JESUS
1114023	11	CENTRAL	14	SAN LORENZO	1	23	SAN ANTONIO - CIUDAD
1114024	11	CENTRAL	14	SAN LORENZO	1	24	SAN FELIPE
1114025	11	CENTRAL	14	SAN LORENZO	1	25	MARIA AUXILIADORA
1114026	11	CENTRAL	14	SAN LORENZO	1	26	FATIMA
1114027	11	CENTRAL	14	SAN LORENZO	1	27	SAN LUIS
1114028	11	CENTRAL	14	SAN LORENZO	1	28	FLORIDA
1114029	11	CENTRAL	14	SAN LORENZO	1	29	LA ENCARNACION
1114030	11	CENTRAL	14	SAN LORENZO	1	30	LUCERITO
1114031	11	CENTRAL	14	SAN LORENZO	1	31	SANTA ANA
1114032	11	CENTRAL	14	SAN LORENZO	1	32	SANTA CRUZ
1114033	11	CENTRAL	14	SAN LORENZO	1	33	SANTA LIBRADA
1114034	11	CENTRAL	14	SAN LORENZO	1	34	BARCEQUILLO
1114035	11	CENTRAL	14	SAN LORENZO	1	35	VILLA UNIVERSITARIA
1114036	11	CENTRAL	14	SAN LORENZO	1	36	ESPIRITU SANTO
1114037	11	CENTRAL	14	SAN LORENZO	1	37	VILLA DEL AGRONOMO
1114038	11	CENTRAL	14	SAN LORENZO	1	38	SANTO TOMAS
1114039	11	CENTRAL	14	SAN LORENZO	1	39	NUESTRA SENORA DE LA ASUNCION
1114040	11	CENTRAL	14	SAN LORENZO	1	40	SAN JUAN - LUCERITO
1114041	11	CENTRAL	14	SAN LORENZO	1	41	TAYAZUAPE
1114042	11	CENTRAL	14	SAN LORENZO	1	42	LOS NOGALES
1114043	11	CENTRAL	14	SAN LORENZO	1	43	MIRAFLORES
1114044	11	CENTRAL	14	SAN LORENZO	1	44	VILLA INDUSTRIAL
1114045	11	CENTRAL	14	SAN LORENZO	1	45	MITA'I - NUEVA AURORA
1114046	11	CENTRAL	14	SAN LORENZO	1	46	VIRGEN DEL ROSARIO
1114047	11	CENTRAL	14	SAN LORENZO	1	47	CAPILLA DEL MONTE
1114048	11	CENTRAL	14	SAN LORENZO	1	48	RINCON
1114049	11	CENTRAL	14	SAN LORENZO	1	49	LERIDA
1114050	11	CENTRAL	14	SAN LORENZO	1	50	REDUCTO
1114051	11	CENTRAL	14	SAN LORENZO	1	51	ANAHI
1114052	11	CENTRAL	14	SAN LORENZO	1	52	LA VICTORIA
1115001	11	CENTRAL	15	VILLA ELISA	1	1	29 DE SETIEMBRE
1115002	11	CENTRAL	15	VILLA ELISA	1	2	TRES BOCAS
1115003	11	CENTRAL	15	VILLA ELISA	1	3	YPATI
1115004	11	CENTRAL	15	VILLA ELISA	1	4	ROSEDAL
1115005	11	CENTRAL	15	VILLA ELISA	1	5	GLORIA MARIA
1115006	11	CENTRAL	15	VILLA ELISA	1	6	8 DE DICIEMBRE
1115007	11	CENTRAL	15	VILLA ELISA	1	7	ARROYO SECO
1115008	11	CENTRAL	15	VILLA ELISA	1	8	SAN JOSE
1115009	11	CENTRAL	15	VILLA ELISA	1	9	CENTRO
1115010	11	CENTRAL	15	VILLA ELISA	1	10	SOL DE AMERICA
1115011	11	CENTRAL	15	VILLA ELISA	1	11	MBOKAJATY
1115012	11	CENTRAL	15	VILLA ELISA	1	12	REMANSO
1115013	11	CENTRAL	15	VILLA ELISA	1	13	PICADA
1115014	11	CENTRAL	15	VILLA ELISA	1	14	VILLA BONITA
1115015	11	CENTRAL	15	VILLA ELISA	1	15	VILLA HERMOSA
1115016	11	CENTRAL	15	VILLA ELISA	1	16	SAN JUAN
1116001	11	CENTRAL	16	VILLETA	1	1	INMACULADA
1116002	11	CENTRAL	16	VILLETA	1	2	SAGRADO CORAZON JESUS
1116003	11	CENTRAL	16	VILLETA	1	3	SAN JORGE
1116004	11	CENTRAL	16	VILLETA	1	4	SAN JUAN
1116005	11	CENTRAL	16	VILLETA	1	5	SAN ISIDRO
1116006	11	CENTRAL	16	VILLETA	1	6	SAN MARTIN DE PORRES
1116007	11	CENTRAL	16	VILLETA	1	7	SAN ROQUE
1116008	11	CENTRAL	16	VILLETA	1	8	VIRGEN DE LOURDES
1116009	11	CENTRAL	16	VILLETA	1	9	ZONA INDUSTRIAL SUR
1116010	11	CENTRAL	16	VILLETA	1	10	SAN JOSE
1116110	11	CENTRAL	16	VILLETA	6	110	VALLE PO'I
1116120	11	CENTRAL	16	VILLETA	6	120	TAKUATY
1116130	11	CENTRAL	16	VILLETA	6	130	ZANJA PYTA
1116140	11	CENTRAL	16	VILLETA	6	140	TAKURUTY
1116150	11	CENTRAL	16	VILLETA	6	150	CUMBARITY
1116160	11	CENTRAL	16	VILLETA	6	160	ITA YVATE
1116170	11	CENTRAL	16	VILLETA	6	170	PUERTO GUYRATI
1116180	11	CENTRAL	16	VILLETA	6	180	SURUBI'Y
1116190	11	CENTRAL	16	VILLETA	6	190	PUERTO SANTA ROSA
1116200	11	CENTRAL	16	VILLETA	6	200	BUEY RODEO
1116210	11	CENTRAL	16	VILLETA	6	210	YPEKA'E
1116220	11	CENTRAL	16	VILLETA	6	220	FRACCION ABAY
1116230	11	CENTRAL	16	VILLETA	6	230	NARANJAISY
1116240	11	CENTRAL	16	VILLETA	6	240	LOMA MERLO
1116250	11	CENTRAL	16	VILLETA	6	250	GUASU KORA
1116260	11	CENTRAL	16	VILLETA	6	260	SAN ANTONIO
1116270	11	CENTRAL	16	VILLETA	6	270	ASENT. ITA YVATE
1116280	11	CENTRAL	16	VILLETA	6	280	ASENT. SANTA ANA  NARANJAISY
1116290	11	CENTRAL	16	VILLETA	6	290	ASENT. SOL NACIENTE
1116300	11	CENTRAL	16	VILLETA	6	300	ASENT. GUASU KORA 2
1116310	11	CENTRAL	16	VILLETA	6	310	ASENT. NARANJAISY 1
1116320	11	CENTRAL	16	VILLETA	6	320	ASENT. 8 DE DICIEMBRE
1116330	11	CENTRAL	16	VILLETA	6	330	NARANJAISY SAN BLAS
1116340	11	CENTRAL	16	VILLETA	6	340	SANTA LUCIA
1116350	11	CENTRAL	16	VILLETA	6	350	ASENT. 24 DE AGOSTO
1116360	11	CENTRAL	16	VILLETA	6	360	ASENT. NARANJAISY
1116370	11	CENTRAL	16	VILLETA	6	370	SENDA
1116380	11	CENTRAL	16	VILLETA	6	380	VILLA INDUSTRIAL
1117001	11	CENTRAL	17	YPACARAI	1	1	CERRO GUY 2
1117002	11	CENTRAL	17	YPACARAI	1	2	SANTA ROSA
1117003	11	CENTRAL	17	YPACARAI	1	3	CERRO GUY
1117004	11	CENTRAL	17	YPACARAI	1	4	LA VICTORIA
1117005	11	CENTRAL	17	YPACARAI	1	5	PALMA
1117006	11	CENTRAL	17	YPACARAI	1	6	SAN BLAS
1117100	11	CENTRAL	17	YPACARAI	6	100	PEDROZO
1117105	11	CENTRAL	17	YPACARAI	3	105	PEDROZO SUB-URBANO
1117110	11	CENTRAL	17	YPACARAI	6	110	HUGUA HU
1117115	11	CENTRAL	17	YPACARAI	3	115	HUGUA HU SUB-URBANO
1117120	11	CENTRAL	17	YPACARAI	6	120	ITAPYTANGUA
1117130	11	CENTRAL	17	YPACARAI	6	130	MBOKAJATY
1117140	11	CENTRAL	17	YPACARAI	6	140	CERRITO
1117150	11	CENTRAL	17	YPACARAI	6	150	ARROYO ESTRELLA
1117160	11	CENTRAL	17	YPACARAI	6	160	PASO PUENTE
1117170	11	CENTRAL	17	YPACARAI	6	170	CERRO GUY KILOMETRO 35
1117180	11	CENTRAL	17	YPACARAI	6	180	CERRO GUY VIA FERREA
1117190	11	CENTRAL	17	YPACARAI	6	190	COSTA LAGO
1117200	11	CENTRAL	17	YPACARAI	6	200	MARGARITA
1117210	11	CENTRAL	17	YPACARAI	6	210	PUERTA DEL LAGO
1117215	11	CENTRAL	17	YPACARAI	3	215	PUERTA DEL LAGO SUB-URBANO
1118001	11	CENTRAL	18	YPANE	1	1	SAN FRANCISCO
1118002	11	CENTRAL	18	YPANE	1	2	SAN PEDRO
1118003	11	CENTRAL	18	YPANE	1	3	CHACO'I
1118004	11	CENTRAL	18	YPANE	1	4	SAN ANTONIO
1118005	11	CENTRAL	18	YPANE	1	5	DEL PILAR
1118100	11	CENTRAL	18	YPANE	6	100	COLONIA THOMPSON
1118120	11	CENTRAL	18	YPANE	6	120	COMPANIA 7
1118130	11	CENTRAL	18	YPANE	6	130	SAN FRANCISCO
1118140	11	CENTRAL	18	YPANE	6	140	PASO DE ORO
1118150	11	CENTRAL	18	YPANE	6	150	SAN NICOLAS
1118160	11	CENTRAL	18	YPANE	6	160	YTORORO
1118170	11	CENTRAL	18	YPANE	6	170	ROSADO GUASU
1118190	11	CENTRAL	18	YPANE	6	190	COSTA ALEGRE
1118200	11	CENTRAL	18	YPANE	6	200	ALTOS DE YPANE
1118210	11	CENTRAL	18	YPANE	6	210	POTRERITO
1118220	11	CENTRAL	18	YPANE	6	220	14 DE MAYO
1118230	11	CENTRAL	18	YPANE	6	230	ASENT. SAN DIEGO
1118240	11	CENTRAL	18	YPANE	6	240	SAN ANTONIO
1118250	11	CENTRAL	18	YPANE	6	250	SAN CAYETANO
1118260	11	CENTRAL	18	YPANE	6	260	SAN BLAS CONAVI
1118270	11	CENTRAL	18	YPANE	6	270	ASENT. SAN MIGUEL
1118280	11	CENTRAL	18	YPANE	6	280	VIRGEN DEL CARMEN
1118290	11	CENTRAL	18	YPANE	6	290	ASENT. SAN VALENTIN 2
1118300	11	CENTRAL	18	YPANE	6	300	SANTA TERESA
1118310	11	CENTRAL	18	YPANE	6	310	8 DE DICIEMBRE
1118320	11	CENTRAL	18	YPANE	6	320	SAN ISIDRO
1118330	11	CENTRAL	18	YPANE	6	330	ASENT. PASO PUKU
1118340	11	CENTRAL	18	YPANE	6	340	ASENT. MADRES PARAGUAYAS
1118350	11	CENTRAL	18	YPANE	6	350	ASENT. 8 DE DICIEMBRE
1118360	11	CENTRAL	18	YPANE	6	360	LAUREL
1118370	11	CENTRAL	18	YPANE	6	370	29 DE AGOSTO
1118380	11	CENTRAL	18	YPANE	6	380	PUERTA DE YPANE
1118390	11	CENTRAL	18	YPANE	6	390	PILAR DEL ESTE
1118400	11	CENTRAL	18	YPANE	6	400	ASENT. 29 DE AGOSTO
1118410	11	CENTRAL	18	YPANE	6	410	VIRGEN DE FATIMA
1118420	11	CENTRAL	18	YPANE	6	420	ASENT. TRES NACIONES
1118430	11	CENTRAL	18	YPANE	6	430	SAN JOSE
1118440	11	CENTRAL	18	YPANE	6	440	ASENT. MANDARINAL
1118450	11	CENTRAL	18	YPANE	6	450	ASENT. SAGRADO CORAZON
1118460	11	CENTRAL	18	YPANE	6	460	SAN DIEGO
1118470	11	CENTRAL	18	YPANE	6	470	ASENT. VIRGEN DE CAACUPE
1118480	11	CENTRAL	18	YPANE	6	480	ASENT. 6 DE JUNIO
1118490	11	CENTRAL	18	YPANE	6	490	ASENT. 5 ESTRELLAS
1118500	11	CENTRAL	18	YPANE	6	500	SAN MIGUEL
1118510	11	CENTRAL	18	YPANE	6	510	ASENT. FRACCION DEL RIO
1118520	11	CENTRAL	18	YPANE	6	520	ASENT. 14 DE MAYO
1118530	11	CENTRAL	18	YPANE	6	530	FRACCION LOS NOGALES
1118540	11	CENTRAL	18	YPANE	6	540	ASENT. MARISCAL LOPEZ
1118550	11	CENTRAL	18	YPANE	6	550	CANADITA CONAVI
1118560	11	CENTRAL	18	YPANE	6	560	ASENT. PASO DE ORO
1118570	11	CENTRAL	18	YPANE	6	570	CANADITA SAN AGUSTIN
1118580	11	CENTRAL	18	YPANE	6	580	CANADITA MARISCAL ESTIGARRIBIA
1118590	11	CENTRAL	18	YPANE	6	590	CANADITA YTORORO
1118600	11	CENTRAL	18	YPANE	6	600	ASENT.SAN VALENTIN 1
1118610	11	CENTRAL	18	YPANE	6	610	MARIA AUXILIADORA
1118620	11	CENTRAL	18	YPANE	6	620	ASENT. 30 JULIO
1118630	11	CENTRAL	18	YPANE	6	630	ASENT. 3 DE JUNIO THOMPSOM
1118640	11	CENTRAL	18	YPANE	6	640	ASENT. THOMPSON
1118650	11	CENTRAL	18	YPANE	6	650	ASENT. MARIA AUXILIADORA  THOMPSON
1118660	11	CENTRAL	18	YPANE	6	660	ASENT. FORTALEZA  I - II - III
1118670	11	CENTRAL	18	YPANE	6	670	CONAVI DIVINO NINO
1118680	11	CENTRAL	18	YPANE	6	680	NUEVA ESPERANZA
1118690	11	CENTRAL	18	YPANE	6	690	YTORORO ISLA
1118700	11	CENTRAL	18	YPANE	6	700	ACHUCARRO
1119001	11	CENTRAL	19	J. AUGUSTO SALDIVAR	1	1	MARISCAL FRANCISCO SOLANO LOPEZZ
1119002	11	CENTRAL	19	J. AUGUSTO SALDIVAR	1	2	SAN MIGUEL
1119003	11	CENTRAL	19	J. AUGUSTO SALDIVAR	1	3	CENTRO
1119004	11	CENTRAL	19	J. AUGUSTO SALDIVAR	1	4	MBOKAJATY
1119005	11	CENTRAL	19	J. AUGUSTO SALDIVAR	1	5	RINCON ALEGRE
1119006	11	CENTRAL	19	J. AUGUSTO SALDIVAR	1	6	SAN RAFAEL
1119007	11	CENTRAL	19	J. AUGUSTO SALDIVAR	1	7	TRES BOCAS
1119130	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	130	YVYRARO 1
1119140	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	140	MBOKAJATY
1119150	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	150	TRES BOCAS
1119160	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	160	NINO JESUS
1119170	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	170	3 DE FEBRERO
1119180	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	180	ALDANA CANADA
1119190	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	190	ASENT. 10 DE AGOSTO
1119200	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	200	ASENT. ALDANA CANADA
1119210	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	210	ASENT. ONONDIVEPA
1119220	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	220	ASENT. PUERTA DEL SOL
1119230	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	230	ASENT. SAN ROQUE
1119240	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	240	ASENT. SANTA ROSA
1119250	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	250	ASENT. TOLEDO CANADA
1119270	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	270	COMPANIA 7
1119280	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	280	COMPANIA 8
1119300	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	300	FRACCION BONANZA
1119310	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	310	FRACCION LOMA BLANCA
1119320	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	320	FRACCION RESEDAD
1119330	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	330	FRACCION SAN PEDRO
1119340	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	340	KO'EMBOTA
1119350	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	350	LA AMISTAD
1119360	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	360	LAS MELLIZAS
1119370	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	370	LOS GLADIOLOS
1119380	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	380	LOS NARANJOS
1119410	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	410	SAN FRANCISCO
1119420	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	420	SAN ISIDRO
1119430	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	430	SAN JUAN
1119440	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	440	SAN LAZARO
1119470	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	470	SAN RAMON
1119480	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	480	SAN ROQUE
1119490	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	490	SANTA CATALINA
1119500	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	500	SANTA LUCIA
1119510	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	510	SANTA MARIA
1119520	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	520	SANTA ROSA
1119530	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	530	TOLEDO CANADA
1119540	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	540	VILLA ESPERANZA
1119545	11	CENTRAL	19	J. AUGUSTO SALDIVAR	3	545	VILLA MANO ABIERTA SUB-URBANO
1119550	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	550	VILLA MARINA
1119555	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	555	VIRGEN DEL CARMEN
1119570	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	570	YVYRARO 2
1119580	11	CENTRAL	19	J. AUGUSTO SALDIVAR	6	580	LAS PIEDRAS
1201001	12	NEEMBUCU	1	PILAR	1	1	VILLA PASO
1201002	12	NEEMBUCU	1	PILAR	1	2	GUARANI
1201003	12	NEEMBUCU	1	PILAR	1	3	SAN JOSE
1201004	12	NEEMBUCU	1	PILAR	1	4	GENERAL DIAZ
1201005	12	NEEMBUCU	1	PILAR	1	5	12 DE OCTUBRE
1201006	12	NEEMBUCU	1	PILAR	1	6	OBRERO
1201007	12	NEEMBUCU	1	PILAR	1	7	CRUCECITA
1201008	12	NEEMBUCU	1	PILAR	1	8	LOMA CLAVEL
1201009	12	NEEMBUCU	1	PILAR	1	9	SAN ANTONIO
1201010	12	NEEMBUCU	1	PILAR	1	10	SAN FRANCISCO
1201011	12	NEEMBUCU	1	PILAR	1	11	SAN MIGUEL
1201012	12	NEEMBUCU	1	PILAR	1	12	8 DE DICIEMBRE
1201013	12	NEEMBUCU	1	PILAR	1	13	SAN LORENZO
1201014	12	NEEMBUCU	1	PILAR	1	14	YTORORO
1201015	12	NEEMBUCU	1	PILAR	1	15	LAS RESIDENTAS
1201016	12	NEEMBUCU	1	PILAR	1	16	LA ESPERANZA
1201017	12	NEEMBUCU	1	PILAR	1	17	VILLA POLICIAL
1201018	12	NEEMBUCU	1	PILAR	1	18	VILLA PARQUE
1201019	12	NEEMBUCU	1	PILAR	1	19	SAN ROQUE
1201020	12	NEEMBUCU	1	PILAR	1	20	COLINAS DE PILAR
1201022	12	NEEMBUCU	1	PILAR	1	22	VILLA AURORA
1201023	12	NEEMBUCU	1	PILAR	1	23	PUERTO NUEVO
1201024	12	NEEMBUCU	1	PILAR	1	24	MBOKAJATY
1201025	12	NEEMBUCU	1	PILAR	1	25	SAN RAFAEL
1201026	12	NEEMBUCU	1	PILAR	1	26	SAN ALFONSO
1201027	12	NEEMBUCU	1	PILAR	1	27	SAN VICENTE
1201028	12	NEEMBUCU	1	PILAR	1	28	JUAN PABLO II
1201120	12	NEEMBUCU	1	PILAR	6	120	YATAITY
1201130	12	NEEMBUCU	1	PILAR	6	130	MEDINA
1201140	12	NEEMBUCU	1	PILAR	6	140	VALLE APU'A
1201150	12	NEEMBUCU	1	PILAR	6	150	CAMBA KUA
1201170	12	NEEMBUCU	1	PILAR	6	170	VILLA PASO
1201180	12	NEEMBUCU	1	PILAR	6	180	PUERTO NUEVO
1201190	12	NEEMBUCU	1	PILAR	6	190	BOQUERON
1201200	12	NEEMBUCU	1	PILAR	6	200	DEL ESTE
1201210	12	NEEMBUCU	1	PILAR	6	210	MBOKAJATY
1202001	12	NEEMBUCU	2	ALBERDI	1	1	CENTRO
1202002	12	NEEMBUCU	2	ALBERDI	1	2	3 DE MAYO
1202003	12	NEEMBUCU	2	ALBERDI	1	3	SAN FRANCISCO
1202004	12	NEEMBUCU	2	ALBERDI	1	4	SAN CAYETANO
1202005	12	NEEMBUCU	2	ALBERDI	1	5	YAGUARON
1202006	12	NEEMBUCU	2	ALBERDI	1	6	LOMA CLAVEL
1202007	12	NEEMBUCU	2	ALBERDI	1	7	SANTA ELENA
1202008	12	NEEMBUCU	2	ALBERDI	1	8	YTORORO
1202009	12	NEEMBUCU	2	ALBERDI	1	9	CARACOLITO
1202010	12	NEEMBUCU	2	ALBERDI	1	10	ALBERDI VIEJO
1202011	12	NEEMBUCU	2	ALBERDI	1	11	STELLA MARIS
1202012	12	NEEMBUCU	2	ALBERDI	1	12	EL TUNEL
1202013	12	NEEMBUCU	2	ALBERDI	1	13	CHACO'I
1202014	12	NEEMBUCU	2	ALBERDI	1	14	TARUMA VUELTA
1202110	12	NEEMBUCU	2	ALBERDI	6	110	ISLA LEON
1202120	12	NEEMBUCU	2	ALBERDI	6	120	ACEVEDO
1202130	12	NEEMBUCU	2	ALBERDI	6	130	ESTERO KORA
1202150	12	NEEMBUCU	2	ALBERDI	6	150	LOMAS
1202170	12	NEEMBUCU	2	ALBERDI	6	170	MONAI  KUARE
1203001	12	NEEMBUCU	3	CERRITO	1	1	NORTE
1203002	12	NEEMBUCU	3	CERRITO	1	2	AVIACION
1203003	12	NEEMBUCU	3	CERRITO	1	3	OBRERO
1203004	12	NEEMBUCU	3	CERRITO	1	4	CENTRAL
1203005	12	NEEMBUCU	3	CERRITO	1	5	CERRITO SUR
1203110	12	NEEMBUCU	3	CERRITO	6	110	BLANCO NU
1203120	12	NEEMBUCU	3	CERRITO	6	120	KURUSU AVA
1203130	12	NEEMBUCU	3	CERRITO	6	130	SAN SALVADOR
1203140	12	NEEMBUCU	3	CERRITO	6	140	YRYVU KUA
1203150	12	NEEMBUCU	3	CERRITO	6	150	PASO TAJY
1203160	12	NEEMBUCU	3	CERRITO	6	160	POTRERITO
1203170	12	NEEMBUCU	3	CERRITO	6	170	TAKURUTY
1203180	12	NEEMBUCU	3	CERRITO	6	180	POTRERO VILLALBA
1203190	12	NEEMBUCU	3	CERRITO	6	190	CERRO NU
1203200	12	NEEMBUCU	3	CERRITO	6	200	ZANJA RUGUA
1203210	12	NEEMBUCU	3	CERRITO	6	210	COSTA'I
1203220	12	NEEMBUCU	3	CERRITO	6	220	ISLA RO'Y
1203230	12	NEEMBUCU	3	CERRITO	6	230	TAVA'I
1203240	12	NEEMBUCU	3	CERRITO	6	240	BAULES
1204001	12	NEEMBUCU	4	DESMOCHADOS	1	1	URBANO
1204100	12	NEEMBUCU	4	DESMOCHADOS	6	100	SANTA CATALINA
1204140	12	NEEMBUCU	4	DESMOCHADOS	6	140	SAN ANTONIO NORTE
1204150	12	NEEMBUCU	4	DESMOCHADOS	6	150	SANTA MARIA
1204170	12	NEEMBUCU	4	DESMOCHADOS	6	170	CAPILLITA
1204180	12	NEEMBUCU	4	DESMOCHADOS	6	180	COSTA PO'I
1204190	12	NEEMBUCU	4	DESMOCHADOS	6	190	POTRERO SAN JUAN
1204200	12	NEEMBUCU	4	DESMOCHADOS	6	200	POTRERO ZARZA
1204210	12	NEEMBUCU	4	DESMOCHADOS	6	210	SAN ROQUE
1204220	12	NEEMBUCU	4	DESMOCHADOS	6	220	FLORA PUNTA
1205001	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	1	1	8 DE DICIEMBRE
1205002	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	1	2	CENTRO
1205003	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	1	3	SANTA LIBRADA
1205004	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	1	4	SANTA LUCIA
1205005	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	1	5	SAN ANTONIO
1205110	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	6	110	VELAZQUEZ KUE
1205120	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	6	120	LOMA RINCON
1205130	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	6	130	PUESTO TORRES
1205140	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	6	140	CAMPAMENTO KUE
1205150	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	6	150	YVYKUI
1205160	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	6	160	ESTERO BELLACO
1205180	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	6	180	LOMA
1205185	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	3	185	LOMA SUB-URBANO
1205190	12	NEEMBUCU	5	GRAL. JOSE EDUVIGIS DIAZ	6	190	LOMA'I
1206001	12	NEEMBUCU	6	GUAZU-CUA	1	1	URBANO
1206100	12	NEEMBUCU	6	GUAZU-CUA	6	100	KARANDAYTY
1206110	12	NEEMBUCU	6	GUAZU-CUA	6	110	PASO TYPY
1206120	12	NEEMBUCU	6	GUAZU-CUA	6	120	GUASU KUA NORTE
1206130	12	NEEMBUCU	6	GUAZU-CUA	6	130	MONTUOSO COSTA
1206150	12	NEEMBUCU	6	GUAZU-CUA	6	150	POTRERO PIRU
1206160	12	NEEMBUCU	6	GUAZU-CUA	6	160	DUARTE KUE
1206190	12	NEEMBUCU	6	GUAZU-CUA	6	190	YVAVIJU
1206200	12	NEEMBUCU	6	GUAZU-CUA	6	200	POTRERO YVYRA'I
1207001	12	NEEMBUCU	7	HUMAITA	1	1	ACOSTA NU
1207002	12	NEEMBUCU	7	HUMAITA	1	2	CHACO'I
1207003	12	NEEMBUCU	7	HUMAITA	1	3	SAN FRANCISCO
1207004	12	NEEMBUCU	7	HUMAITA	1	4	ARROYO HAVO
1207100	12	NEEMBUCU	7	HUMAITA	6	100	PASO PUKU
1207110	12	NEEMBUCU	7	HUMAITA	6	110	TUJU KUE
1207120	12	NEEMBUCU	7	HUMAITA	6	120	PARED KUE
1207130	12	NEEMBUCU	7	HUMAITA	6	130	PASO CORNELIO
1207140	12	NEEMBUCU	7	HUMAITA	6	140	ARROYO HONDO
1208001	12	NEEMBUCU	8	ISLA UMBU	1	1	URBANO
1208100	12	NEEMBUCU	8	ISLA UMBU	6	100	BOQUERON
1208110	12	NEEMBUCU	8	ISLA UMBU	6	110	CAMBA KUA
1208120	12	NEEMBUCU	8	ISLA UMBU	6	120	LOMA CLAVEL
1208130	12	NEEMBUCU	8	ISLA UMBU	6	130	VALLE PO'I
1208140	12	NEEMBUCU	8	ISLA UMBU	6	140	COSTA PUKU
1208150	12	NEEMBUCU	8	ISLA UMBU	6	150	ISLERIA
1208160	12	NEEMBUCU	8	ISLA UMBU	6	160	TAKURU PYTA
1209001	12	NEEMBUCU	9	LAURELES	1	1	SAN BLAS
1209002	12	NEEMBUCU	9	LAURELES	1	2	SAN JOSE
1209003	12	NEEMBUCU	9	LAURELES	1	3	VIRGEN MARIA
1209004	12	NEEMBUCU	9	LAURELES	1	4	6 DE ENERO
1209005	12	NEEMBUCU	9	LAURELES	1	5	SANTA LIBRADA
1209006	12	NEEMBUCU	9	LAURELES	1	6	MEDALLA MILAGROSA
1209007	12	NEEMBUCU	9	LAURELES	1	7	JESUS MISERICORDIOSO
1209008	12	NEEMBUCU	9	LAURELES	1	8	VIRGEN DEL ROSARIO
1209009	12	NEEMBUCU	9	LAURELES	1	9	CONAVI CAACUPEMI
1209100	12	NEEMBUCU	9	LAURELES	6	100	PASO PINDO
1209110	12	NEEMBUCU	9	LAURELES	6	110	APIPE
1209120	12	NEEMBUCU	9	LAURELES	6	120	POTRERO ESTECHE
1209130	12	NEEMBUCU	9	LAURELES	6	130	POTRERO SAN JUAN
1209140	12	NEEMBUCU	9	LAURELES	6	140	CASTILLO KUE
1209150	12	NEEMBUCU	9	LAURELES	6	150	POTRERO PO'I
1209160	12	NEEMBUCU	9	LAURELES	6	160	ISLA YRYVU
1209170	12	NEEMBUCU	9	LAURELES	6	170	ISLA CABRERA
1209180	12	NEEMBUCU	9	LAURELES	6	180	ESPINILLO
1209190	12	NEEMBUCU	9	LAURELES	6	190	KA'AROGUE
1209200	12	NEEMBUCU	9	LAURELES	6	200	LOMAS
1209210	12	NEEMBUCU	9	LAURELES	6	210	COSTA PUKU
1209220	12	NEEMBUCU	9	LAURELES	6	220	POTRERO PINDURA
1209230	12	NEEMBUCU	9	LAURELES	6	230	NEEMBUCU MI
1209240	12	NEEMBUCU	9	LAURELES	6	240	ISLERIA
1209250	12	NEEMBUCU	9	LAURELES	6	250	ISLA SOLA
1209260	12	NEEMBUCU	9	LAURELES	6	260	SAN ANTONIO
1209270	12	NEEMBUCU	9	LAURELES	6	270	YATAITY
1210001	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	1	1	ESCOLAR
1210002	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	1	2	CENTRO
1210003	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	1	3	LA FLOR
1210004	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	1	4	VIRGEN DE ITATI
1210005	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	1	5	DON ORIONE
1210006	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	1	6	SANTA LIBRADA
1210100	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	100	FUERTE KUE
1210110	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	110	SAN ROQUE
1210120	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	120	CABRERA KUE
1210130	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	130	ISLA YSYPO
1210140	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	140	YATAITY
1210150	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	150	KA'AGUY KUPE
1210160	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	160	TRES CORONAS
1210170	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	170	POTRERO BORDON
1210180	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	180	KURUSU KUATIA
1210190	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	190	KUATIA'I
1210200	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	200	ITA KORA
1210205	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	3	205	ITA KORA SUB-URBANO
1210220	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	220	ALARCON
1210230	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	230	ESTERO PUNTA
1210240	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	240	LOMA'I
1210250	12	NEEMBUCU	10	MAYOR JOSE DEJESUS MARTINEZ	6	250	CHACARITA
1211001	12	NEEMBUCU	11	PASO DE PATRIA	1	1	SAN FRANCISCO
1211002	12	NEEMBUCU	11	PASO DE PATRIA	1	2	SAN BLAS
1211003	12	NEEMBUCU	11	PASO DE PATRIA	1	3	SAN PATRICIO
1211004	12	NEEMBUCU	11	PASO DE PATRIA	1	4	SAN JOSE
1211110	12	NEEMBUCU	11	PASO DE PATRIA	6	110	PASO CANOA
1211120	12	NEEMBUCU	11	PASO DE PATRIA	6	120	COSTA PARANA
1211130	12	NEEMBUCU	11	PASO DE PATRIA	6	130	PASO DE PATRIA NORTE
1211140	12	NEEMBUCU	11	PASO DE PATRIA	6	140	PASO DE PATRIA SUR
1212001	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	1	1	CENTRO
1212002	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	1	2	NEEMBUCU SUR
1212003	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	1	3	12 DE OCTUBRE
1212100	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	100	CIERVO BLANCO
1212110	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	110	OTAZU
1212120	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	120	LAGUNA ITA
1212130	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	130	COSTA PINDO
1212140	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	140	LOMA KAMBA KUA
1212150	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	150	COSTA ROSADO
1212160	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	160	SAN JUAN NEEMBUCU NORTE
1212170	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	170	POTRERO CABALLERO
1212180	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	180	8 DE DICIEMBRE
1212190	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	190	SAN JUAN NEEMBUCU SUR
1212200	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	200	PIRITY
1212220	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	220	ESPINILLO
1212230	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	230	MALVINAS
1212240	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	240	POTRERO PO'I
1212250	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	250	ESTERO KAMBA
1212260	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	260	KARANDAYTY
1212270	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	270	CIUDAD NUEVA
1212280	12	NEEMBUCU	12	SAN JUAN BAUTISTA DE NEEMBUCU	6	280	PARAISO
1213001	12	NEEMBUCU	13	TACUARAS	1	1	URBANO
1213110	12	NEEMBUCU	13	TACUARAS	6	110	PUNTA DIAMANTE
1213120	12	NEEMBUCU	13	TACUARAS	6	120	TACUARA'I
1213125	12	NEEMBUCU	13	TACUARAS	3	125	TACUARA'I SUB-URBANO
1213130	12	NEEMBUCU	13	TACUARAS	6	130	POTRERO GONZALEZ
1213140	12	NEEMBUCU	13	TACUARAS	6	140	COLONIA MBURIKA
1213145	12	NEEMBUCU	13	TACUARAS	3	145	COLONIA MBURIKA SUB-URBANO
1213150	12	NEEMBUCU	13	TACUARAS	6	150	ASENT. BELEN
1213160	12	NEEMBUCU	13	TACUARAS	6	160	YAGUARON
1213170	12	NEEMBUCU	13	TACUARAS	6	170	YATAITY
1213190	12	NEEMBUCU	13	TACUARAS	6	190	TACUARAS NORTE
1213200	12	NEEMBUCU	13	TACUARAS	6	200	TACUARAS SUR
1213210	12	NEEMBUCU	13	TACUARAS	6	210	PIRETU KUE
1213220	12	NEEMBUCU	13	TACUARAS	6	220	SAN JORGE
1214001	12	NEEMBUCU	14	VILLA FRANCA	1	1	URBANO
1214100	12	NEEMBUCU	14	VILLA FRANCA	6	100	ISLA REAL
1214110	12	NEEMBUCU	14	VILLA FRANCA	6	110	SEBASTIAN GABOTO
1214120	12	NEEMBUCU	14	VILLA FRANCA	6	120	KARANDAYTY
1214130	12	NEEMBUCU	14	VILLA FRANCA	6	130	BANCO PIRA'I
1214140	12	NEEMBUCU	14	VILLA FRANCA	6	140	BANCO PARAGUAY
1214150	12	NEEMBUCU	14	VILLA FRANCA	6	150	ZONA DE ESTANCIAS VILLA FRANCA
1215001	12	NEEMBUCU	15	VILLA OLIVA	1	1	CENTRO
1215002	12	NEEMBUCU	15	VILLA OLIVA	1	2	TORYPA
1215003	12	NEEMBUCU	15	VILLA OLIVA	1	3	MARIA AUXILIADORA
1215004	12	NEEMBUCU	15	VILLA OLIVA	1	4	4 VIENTOS
1215005	12	NEEMBUCU	15	VILLA OLIVA	1	5	LA ESPERANZA
1215006	12	NEEMBUCU	15	VILLA OLIVA	1	6	VALLE PUKU
1215007	12	NEEMBUCU	15	VILLA OLIVA	1	7	SAN MIGUEL
1215008	12	NEEMBUCU	15	VILLA OLIVA	1	8	ALEGRE
1215100	12	NEEMBUCU	15	VILLA OLIVA	6	100	ZANJITA
1215110	12	NEEMBUCU	15	VILLA OLIVA	6	110	ZANJITA YVY ATA
1215140	12	NEEMBUCU	15	VILLA OLIVA	6	140	KARANDAYTY
1215180	12	NEEMBUCU	15	VILLA OLIVA	6	180	SAN JUAN
1215200	12	NEEMBUCU	15	VILLA OLIVA	6	200	PARAY
1215210	12	NEEMBUCU	15	VILLA OLIVA	6	210	PUERTO PARAISO
1215220	12	NEEMBUCU	15	VILLA OLIVA	6	220	YVY POHYI
1215230	12	NEEMBUCU	15	VILLA OLIVA	6	230	ESTANZUELA
1215240	12	NEEMBUCU	15	VILLA OLIVA	6	240	SAN MIGUEL
1216001	12	NEEMBUCU	16	VILLALBIN	1	1	URBANO
1216100	12	NEEMBUCU	16	VILLALBIN	6	100	SAN FRANCISCO
1216110	12	NEEMBUCU	16	VILLALBIN	6	110	MANANTIAL
1216120	12	NEEMBUCU	16	VILLALBIN	6	120	SAN SEBASTIAN
1216130	12	NEEMBUCU	16	VILLALBIN	6	130	SAN MIGUEL
1216150	12	NEEMBUCU	16	VILLALBIN	6	150	ISLA RO'Y
1216155	12	NEEMBUCU	16	VILLALBIN	3	155	ISLA RO'Y SUB-URBANO
1216160	12	NEEMBUCU	16	VILLALBIN	6	160	ISLA REAL
1216170	12	NEEMBUCU	16	VILLALBIN	6	170	TTE SANCHEZ
1216175	12	NEEMBUCU	16	VILLALBIN	3	175	TTE SANCHEZ SUB-URBANO
1216180	12	NEEMBUCU	16	VILLALBIN	6	180	NU PA'U
1301001	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	1	MARIA VICTORIA
1301002	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	2	MARISCAL ESTIGARRIBIA
1301003	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	3	PERPETUO SOCORRO CENTRO
1301004	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	4	GENERAL DIAZ
1301005	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	5	SAN ANTONIO
1301006	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	6	BERNARDINO CABALLERO
1301007	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	7	GUARANI
1301008	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	8	VIRGEN DE CAACUPE
1301009	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	9	SAN GERARDO
1301010	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	10	GENERAL GENES
1301011	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	11	SAN JUAN NEUMAN
1301012	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	12	OBRERO
1301013	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	13	JARDIN AURORA
1301014	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	14	DEFENSORES DEL CHACO
1301015	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	15	FRACCION AMAMBAY
1301016	13	AMAMBAY	1	PEDRO JUAN CABALLERO	1	16	SAN BLAS
1301105	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	105	COLONIA ESTRELLA
1301110	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	110	SAN LUIS
1301120	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	120	ASENT. GRACIA DE DIOS
1301130	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	130	PARTERA ORTIZ
1301140	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	140	TRES PALOS
1301150	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	150	KACHIMBO
1301155	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	155	AMARO KUE - CERRO CORA'I 1
1301165	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	165	CERRO CORA'I
1301175	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	175	MARIA AUXILIADORA
1301180	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	180	SANTA CLARA
1301185	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	185	1RO DE MAYO
1301190	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	190	COM INDIG JAKAIRA POTRERITO
1301205	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	205	CALLEJON GENES
1301210	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	210	VISTA ALEGRE
1301230	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	230	COM INDIG JAKAIRA
1301240	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	240	COLONIA MAFUCCI
1301245	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	245	YVYPE
1301250	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	250	REPUBLICA
1301260	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	260	COLONIA CUMBRE
1301265	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	265	15 DE AGOSTO
1301270	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	270	COM INDIG JAGUATI
1301280	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	280	COLONIA POTRERO SUR
1301285	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	285	COM INDIG PIRITY
1301290	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	290	COLONIA  Y'AMBUE
1301295	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	295	CERRO CORA - PICADA LORITO
1301300	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	300	NARANJA HAI
1301310	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	310	CHIRIGUELO
1301335	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	335	SAN VICENTE
1301355	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	355	COM INDIG ITA GUASU
1301375	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	375	COM INDIG TAVAMBOA'E
1301390	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	390	NANDEJARA PUENTE
1301405	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	405	COM INDIG TAJY
1301415	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	415	COM INDIG ITAYPAVUSU
1301425	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	425	YPYTA - MBARAKAJA'I
1301445	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	445	CERRO 21
1301450	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	450	CAMPO FLOR
1301470	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	470	COM INDIG YVYPYTE - JURUKA
1301500	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	500	ESTANCIA PAPA NOEL - PUENTE KAPEI
1301510	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	510	JAGUARY
1301525	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	525	COM INDIG YVYPYTE - YJEVY
1301535	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	535	COM INDIG YVYPYTE - TATU KAITA
1301540	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	540	ESTANCIA CERRO GUASU
1301550	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	550	COM INDIG NUAPY
1301555	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	555	ESTANCIA CHAPARRAL
1301560	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	560	COM INDIG MBA'E MARANGATU - ITA JEGUA
1301561	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	561	COM INDIG MBA'E MARANGATU - Y'AKA GUASU
1301562	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	562	COM INDIG MBA'E MARANGATU - TAJA'Y
1301575	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	575	ESTANCIAS KA'I MEMBY E YPANE
1301580	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	580	ESTANCIAS PA'I KUARA Y ARROYO BLANCO
1301590	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	590	COM INDIG ANGUJA'I YVANGUSU
1301600	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	600	COM INDIG MBA'E NEMI
1301605	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	605	GUAVIRA
1301610	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	610	GUILLERMINA
1301615	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	615	ASENT. GUILLERMINA
1301620	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	620	COM INDIG VY'A  RENDA
1301630	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	630	VILLA ESTEFAN
1301640	13	AMAMBAY	1	PEDRO JUAN CABALLERO	3	640	LIBERTAD SUB-URBANO
1301650	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	650	ASENT. MARIA AUXILIADORA
1301660	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	660	COM INDIG PANAMBI'Y
1301665	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	665	NUEVA AQUIDABAN
1301675	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	675	COM INDIG YVY ATA'I
1301685	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	685	KOKUE PYAHU
1301690	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	690	ASENT. SAN MIGUEL
1301695	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	695	ASENT. ROMERO KUE
1301700	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	700	ASENT. COLONIA POTRERO SUR
1301705	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	705	COM INDIG PIKYKUA  - ARROYO GUASU
1301710	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	710	COM INDIG PIKYKUA - CERRO PA'U
1301715	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	715	COM INDIG PIKYKUA
1301720	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	720	PIKY
1301725	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	725	YATAITY KORA
1301730	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	730	GASORY
1301745	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	745	COLONIA 204
1301750	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	750	COLONIA KORA'I
1301760	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	760	COM INDIG PIRARY
1301770	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	770	COM INDIG YVYPYTE - CAMPO FLOR
1301775	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	775	COM INDIG YVYPYTE - KURIJUY
1301780	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	780	COM INDIG YVYPYTE - LOMA'I
1301790	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	790	COM INDIG YVYPYTE - ATYVA
1301795	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	795	COM INDIG YVYPYTE - YETE MIRI
1301800	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	800	COM INDIG JASUKA VENDA - JEROPE
1301805	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	805	COM INDIG JASUKA VENDA - KARAVIE GUASU
1301810	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	810	COM INDIG JASUKA VENDA - TARAVE
1301820	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	820	ESTANCIA KA'I RAGUE
1301830	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	830	CIUDAD NUEVA
1301835	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	835	ASENT. VIRGEN DE LOS POBRES
1301840	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	840	ASENT. SANTA ANA
1301845	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	845	SOLARES DE AMAMBAY
1301850	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	850	VILLA TERESA
1301860	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	860	COM INDIG PYSYRY
1301870	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	870	COM INDIG TAKUAGUYOGUE TAKUARA
1301880	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	880	COM INDIG MBOKAJA'I
1301890	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	890	COM INDIG JASUKA VENDA - AVA KUA
1301895	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	895	COM INDIG AVARENDYJU
1301900	13	AMAMBAY	1	PEDRO JUAN CABALLERO	6	900	COM INDIG Y'ETE POTY
1302001	13	AMAMBAY	2	BELLA VISTA	1	1	SAN ANTONIO
1302002	13	AMAMBAY	2	BELLA VISTA	1	2	APA
1302003	13	AMAMBAY	2	BELLA VISTA	1	3	CENTRO
1302004	13	AMAMBAY	2	BELLA VISTA	1	4	OBRERO
1302005	13	AMAMBAY	2	BELLA VISTA	1	5	INMACULADA CONCEPCION
1302100	13	AMAMBAY	2	BELLA VISTA	6	100	APAMI
1302110	13	AMAMBAY	2	BELLA VISTA	6	110	SARGENTO DURE
1302120	13	AMAMBAY	2	BELLA VISTA	6	120	MANDYJU POTY
1302130	13	AMAMBAY	2	BELLA VISTA	6	130	RINCONADA
1302150	13	AMAMBAY	2	BELLA VISTA	6	150	SAN ISIDRO
1302180	13	AMAMBAY	2	BELLA VISTA	6	180	EST. DON GUALBERTO
1302200	13	AMAMBAY	2	BELLA VISTA	6	200	LA LOMITA
1302210	13	AMAMBAY	2	BELLA VISTA	6	210	LAGUNA PLATILLO
1302240	13	AMAMBAY	2	BELLA VISTA	6	240	SAN ROQUE
1302250	13	AMAMBAY	2	BELLA VISTA	6	250	LAS MERCEDES
1302260	13	AMAMBAY	2	BELLA VISTA	6	260	CASUALIDAD
1302300	13	AMAMBAY	2	BELLA VISTA	6	300	SAN JOSE
1302310	13	AMAMBAY	2	BELLA VISTA	6	310	CASCADA
1302320	13	AMAMBAY	2	BELLA VISTA	6	320	COM INDIG ARROYO KA'A
1302330	13	AMAMBAY	2	BELLA VISTA	6	330	COM INDIG YVY OKA
1302340	13	AMAMBAY	2	BELLA VISTA	6	340	COM INDIG APYKA JEGUA
1302350	13	AMAMBAY	2	BELLA VISTA	6	350	SAN LORENZO
1302360	13	AMAMBAY	2	BELLA VISTA	6	360	ASENT. SANTA ANA
1302370	13	AMAMBAY	2	BELLA VISTA	6	370	DUARTE KUE
1302380	13	AMAMBAY	2	BELLA VISTA	6	380	SANTA ANA
1302390	13	AMAMBAY	2	BELLA VISTA	6	390	COM INDIG ITA JEGUAKA
1302400	13	AMAMBAY	2	BELLA VISTA	6	400	SAN NICOLAS
1302410	13	AMAMBAY	2	BELLA VISTA	6	410	COM INDIG YVYTY ROVI CERRO PO'I AMAMBAY
1302420	13	AMAMBAY	2	BELLA VISTA	6	420	SAN ANTONIO-INMACULADA
1302430	13	AMAMBAY	2	BELLA VISTA	6	430	COLONIAS UNIDAS
1302440	13	AMAMBAY	2	BELLA VISTA	6	440	COM INDIG SATI
1302450	13	AMAMBAY	2	BELLA VISTA	6	450	COM INDIG GUYRA NE'ENGATU AMBA
1302460	13	AMAMBAY	2	BELLA VISTA	6	460	SANTA TERESA
1302470	13	AMAMBAY	2	BELLA VISTA	6	470	COM INDIG CERRO AKANGUE
1302480	13	AMAMBAY	2	BELLA VISTA	6	480	COM INDIG APYKA RENDY'I
1303001	13	AMAMBAY	3	CAPITAN BADO	1	1	MARISCAL LOPEZ COLONIA SEGUNDA
1303002	13	AMAMBAY	3	CAPITAN BADO	1	2	MARISCAL LOPEZ COLONIA PRIMERA
1303003	13	AMAMBAY	3	CAPITAN BADO	1	3	OBRERO
1303004	13	AMAMBAY	3	CAPITAN BADO	1	4	PRIMAVERA
1303005	13	AMAMBAY	3	CAPITAN BADO	1	5	SAN ROQUE
1303006	13	AMAMBAY	3	CAPITAN BADO	1	6	SAN JOSE
1303007	13	AMAMBAY	3	CAPITAN BADO	1	7	SAN MIGUEL
1303100	13	AMAMBAY	3	CAPITAN BADO	6	100	NUEVA JA'U
1303110	13	AMAMBAY	3	CAPITAN BADO	6	110	CERRO KUATIA
1303130	13	AMAMBAY	3	CAPITAN BADO	6	130	MANTA POTRERO
1303150	13	AMAMBAY	3	CAPITAN BADO	6	150	CURUPAYTY
1303160	13	AMAMBAY	3	CAPITAN BADO	6	160	CRISTINO POTRERO
1303170	13	AMAMBAY	3	CAPITAN BADO	6	170	KA'AGUY POTY
1303190	13	AMAMBAY	3	CAPITAN BADO	6	190	UMBU
1303210	13	AMAMBAY	3	CAPITAN BADO	6	210	MENTA
1303220	13	AMAMBAY	3	CAPITAN BADO	6	220	MARISCAL LOPEZ
1303240	13	AMAMBAY	3	CAPITAN BADO	6	240	AGUARA
1303260	13	AMAMBAY	3	CAPITAN BADO	6	260	POTRERITO
1303270	13	AMAMBAY	3	CAPITAN BADO	6	270	ARROYITO
1303320	13	AMAMBAY	3	CAPITAN BADO	6	320	POTRERO NOVILLO
1303330	13	AMAMBAY	3	CAPITAN BADO	6	330	JUKYRY GUASU PAKOLA
1303340	13	AMAMBAY	3	CAPITAN BADO	6	340	PASO ITA
1303350	13	AMAMBAY	3	CAPITAN BADO	6	350	COLONIA PIRAY
1303360	13	AMAMBAY	3	CAPITAN BADO	6	360	YSOSO
1303370	13	AMAMBAY	3	CAPITAN BADO	6	370	CAAZAPAMI
1303400	13	AMAMBAY	3	CAPITAN BADO	6	400	PUENTE KURE
1303410	13	AMAMBAY	3	CAPITAN BADO	6	410	CADETE BOQUERON
1303420	13	AMAMBAY	3	CAPITAN BADO	6	420	JAGUARUNDI
1303450	13	AMAMBAY	3	CAPITAN BADO	6	450	AGUARA VEVE
1303470	13	AMAMBAY	3	CAPITAN BADO	6	470	ZANJA HU
1303490	13	AMAMBAY	3	CAPITAN BADO	6	490	COM INDIG ITA POTY
1303500	13	AMAMBAY	3	CAPITAN BADO	6	500	PUERTO PANADERO
1303520	13	AMAMBAY	3	CAPITAN BADO	6	520	ESTANCIA CERRO VERDE
1303550	13	AMAMBAY	3	CAPITAN BADO	6	550	ESTANCIA SAN CAMILO
1303590	13	AMAMBAY	3	CAPITAN BADO	6	590	ASENT. MARIA AUXILIADORA
1303600	13	AMAMBAY	3	CAPITAN BADO	6	600	ASENT. PIRAY
1303610	13	AMAMBAY	3	CAPITAN BADO	6	610	CHACO'I
1303620	13	AMAMBAY	3	CAPITAN BADO	6	620	COLONIA SAN FERNANDO
1303630	13	AMAMBAY	3	CAPITAN BADO	6	630	COM INDIG CRISTINO POTRERO
1303640	13	AMAMBAY	3	CAPITAN BADO	6	640	COM INDIG MBARAKAY
1303650	13	AMAMBAY	3	CAPITAN BADO	6	650	COM INDIG COMUNIDAD GUARANI PASO HISTORIA
1303660	13	AMAMBAY	3	CAPITAN BADO	6	660	COM INDIG TAVYTERA
1303670	13	AMAMBAY	3	CAPITAN BADO	6	670	COM INDIG TAKUAJU POTY
1303690	13	AMAMBAY	3	CAPITAN BADO	6	690	COM INDIG PIRAYMI
1303710	13	AMAMBAY	3	CAPITAN BADO	6	710	COM INDIG ITAJU
1303715	13	AMAMBAY	3	CAPITAN BADO	6	715	COM INDIG ITAJU - PASO LIMA
1303720	13	AMAMBAY	3	CAPITAN BADO	6	720	COM INDIG POTRERO NOVILLO
1303730	13	AMAMBAY	3	CAPITAN BADO	6	730	COM INDIG ITAY
1304001	13	AMAMBAY	4	ZANJA PYT�	1	1	VIRGEN DE CAACUPE
1304002	13	AMAMBAY	4	ZANJA PYT�	1	2	FLORIDA
1304003	13	AMAMBAY	4	ZANJA PYT�	1	3	SANTA LUCIA
1304004	13	AMAMBAY	4	ZANJA PYT�	1	4	SAN ROQUE
1304005	13	AMAMBAY	4	ZANJA PYT�	1	5	PERPETUO SOCORRO
1304006	13	AMAMBAY	4	ZANJA PYT�	1	6	SAN MIGUEL
1304100	13	AMAMBAY	4	ZANJA PYT�	6	100	COLONIA POTRERO SUR
1304110	13	AMAMBAY	4	ZANJA PYT�	6	110	CALLEJON CANO
1304120	13	AMAMBAY	4	ZANJA PYT�	6	120	CALLEJON BRASIL
1304130	13	AMAMBAY	4	ZANJA PYT�	6	130	RINCON DE JULIO
1304140	13	AMAMBAY	4	ZANJA PYT�	6	140	COM INDIG Y MOROTI
1304150	13	AMAMBAY	4	ZANJA PYT�	6	150	CERRO 21
1304160	13	AMAMBAY	4	ZANJA PYT�	6	160	NEPU'A PYAHU
1304170	13	AMAMBAY	4	ZANJA PYT�	6	170	CERRO BOBO
1304180	13	AMAMBAY	4	ZANJA PYT�	6	180	Y'AMBUE
1304190	13	AMAMBAY	4	ZANJA PYT�	6	190	FORTUNA
1304200	13	AMAMBAY	4	ZANJA PYT�	6	200	COLONIA SAN MIGUEL
1304210	13	AMAMBAY	4	ZANJA PYT�	6	210	CALLEJON SANTA MARIA
1304220	13	AMAMBAY	4	ZANJA PYT�	6	220	COM INDIG NUEVA VIRGINIA CERRO 21
1305001	13	AMAMBAY	5	KARAPAI	1	1	URBANO
1305330	13	AMAMBAY	5	KARAPAI	6	330	JUKYRY GUASU PAKOLA
1305430	13	AMAMBAY	5	KARAPAI	6	430	KARAPA'I
1305700	13	AMAMBAY	5	KARAPAI	6	700	ASENT. KARAPA'I
1401001	14	CANINDEYU	1	SALTO DEL GUAIRA	1	1	JARDIN DEL NORTE
1401002	14	CANINDEYU	1	SALTO DEL GUAIRA	1	2	1RO DE MAYO
1401003	14	CANINDEYU	1	SALTO DEL GUAIRA	1	3	STELLA MARYS
1401004	14	CANINDEYU	1	SALTO DEL GUAIRA	1	4	DONDE NACE EL SOL
1401005	14	CANINDEYU	1	SALTO DEL GUAIRA	1	5	VILLA FLORIDA
1401006	14	CANINDEYU	1	SALTO DEL GUAIRA	1	6	SANTA ROSA
1401007	14	CANINDEYU	1	SALTO DEL GUAIRA	1	7	SAN FRANCISCO
1401008	14	CANINDEYU	1	SALTO DEL GUAIRA	1	8	SAN JORGE KILOMETRO 3
1401009	14	CANINDEYU	1	SALTO DEL GUAIRA	1	9	RAYITO DE SOL KILOMETRO 2
1401010	14	CANINDEYU	1	SALTO DEL GUAIRA	1	10	RESIDENCIAL ACUARIO
1401011	14	CANINDEYU	1	SALTO DEL GUAIRA	1	11	SAN MIGUEL
1401012	14	CANINDEYU	1	SALTO DEL GUAIRA	1	12	PRIMAVERA
1401013	14	CANINDEYU	1	SALTO DEL GUAIRA	1	13	SAN BLAS
1401014	14	CANINDEYU	1	SALTO DEL GUAIRA	1	14	ADELA SPERATTI
1401015	14	CANINDEYU	1	SALTO DEL GUAIRA	1	15	SANTA TERESA
1401016	14	CANINDEYU	1	SALTO DEL GUAIRA	1	16	MARIA AUXILIADORA
1401017	14	CANINDEYU	1	SALTO DEL GUAIRA	1	17	KAREN LUANA
1401018	14	CANINDEYU	1	SALTO DEL GUAIRA	1	18	SAN JOSE
1401019	14	CANINDEYU	1	SALTO DEL GUAIRA	1	19	RENACER
1401020	14	CANINDEYU	1	SALTO DEL GUAIRA	1	20	NUEVO HORIZONTE
1401110	14	CANINDEYU	1	SALTO DEL GUAIRA	6	110	PUERTO ADELA
1401115	14	CANINDEYU	1	SALTO DEL GUAIRA	3	115	PUERTO ADELA SUB-URBANO
1401120	14	CANINDEYU	1	SALTO DEL GUAIRA	6	120	SALTO DEL GUAIRA KILOMETRO 32
1401130	14	CANINDEYU	1	SALTO DEL GUAIRA	6	130	SALTO DEL GUAIRA KILOMETRO 20
1401140	14	CANINDEYU	1	SALTO DEL GUAIRA	6	140	SALTO DEL GUAIRA KILOMETRO 18
1401150	14	CANINDEYU	1	SALTO DEL GUAIRA	6	150	COLONIA GASORY
1401160	14	CANINDEYU	1	SALTO DEL GUAIRA	6	160	COLONIA CANINDEYU
1401170	14	CANINDEYU	1	SALTO DEL GUAIRA	6	170	ALBORADA
1401180	14	CANINDEYU	1	SALTO DEL GUAIRA	6	180	GUAVIRA
1401190	14	CANINDEYU	1	SALTO DEL GUAIRA	6	190	YVY PORA
1401200	14	CANINDEYU	1	SALTO DEL GUAIRA	6	200	COLONIA GUADALUPE CAMINO TRES
1401210	14	CANINDEYU	1	SALTO DEL GUAIRA	6	210	COM INDIG GUAVIRA
1401220	14	CANINDEYU	1	SALTO DEL GUAIRA	6	220	VILLA KARAPA
1401230	14	CANINDEYU	1	SALTO DEL GUAIRA	6	230	ASENT. LA PAZ
1401240	14	CANINDEYU	1	SALTO DEL GUAIRA	6	240	IBEL
1401250	14	CANINDEYU	1	SALTO DEL GUAIRA	6	250	SAN BLAS
1401260	14	CANINDEYU	1	SALTO DEL GUAIRA	6	260	ASENT. 29 DE SETIEMBRE
1401270	14	CANINDEYU	1	SALTO DEL GUAIRA	6	270	VILLA PARQUE ITAIPU
1401280	14	CANINDEYU	1	SALTO DEL GUAIRA	6	280	ASENT. KO'E RORY
1401290	14	CANINDEYU	1	SALTO DEL GUAIRA	6	290	ASENT. 8 DE DICIEMBRE
1401300	14	CANINDEYU	1	SALTO DEL GUAIRA	6	300	COM INDIG TEKOHA POTY VERA
1401310	14	CANINDEYU	1	SALTO DEL GUAIRA	6	310	COM INDIG CARIOCA
1402001	14	CANINDEYU	2	CORPUS CHRISTI	1	1	SAN MIGUEL
1402002	14	CANINDEYU	2	CORPUS CHRISTI	1	2	SAN FRANCISCO
1402003	14	CANINDEYU	2	CORPUS CHRISTI	1	3	SAN ANTONIO
1402004	14	CANINDEYU	2	CORPUS CHRISTI	1	4	INMACULADA CONCEPCION
1402005	14	CANINDEYU	2	CORPUS CHRISTI	1	5	SAN ROQUE
1402110	14	CANINDEYU	2	CORPUS CHRISTI	6	110	COLONIA AMERICANA
1402120	14	CANINDEYU	2	CORPUS CHRISTI	6	120	CERRO PORTENO
1402130	14	CANINDEYU	2	CORPUS CHRISTI	6	130	PAKOVA
1402160	14	CANINDEYU	2	CORPUS CHRISTI	6	160	SOLIS KUE
1402170	14	CANINDEYU	2	CORPUS CHRISTI	6	170	SANTA MARIA CONTROL
1402240	14	CANINDEYU	2	CORPUS CHRISTI	6	240	CORPUS VIEJO
1402260	14	CANINDEYU	2	CORPUS CHRISTI	6	260	COLONIA GUARANI KILOMETRO 5
1402280	14	CANINDEYU	2	CORPUS CHRISTI	6	280	CERRO PYTA
1402290	14	CANINDEYU	2	CORPUS CHRISTI	6	290	AGUA BLANCA
1402300	14	CANINDEYU	2	CORPUS CHRISTI	6	300	COPARO
1402310	14	CANINDEYU	2	CORPUS CHRISTI	6	310	COLONIA ANAHI
1402360	14	CANINDEYU	2	CORPUS CHRISTI	6	360	CRUCE GUARANI
1402365	14	CANINDEYU	2	CORPUS CHRISTI	3	365	CRUCE GUARANI SUB-URBANO
1402370	14	CANINDEYU	2	CORPUS CHRISTI	6	370	Y'HOVY
1402400	14	CANINDEYU	2	CORPUS CHRISTI	6	400	VILLA ALTA
1402450	14	CANINDEYU	2	CORPUS CHRISTI	6	450	SANTA LUCIA
1402460	14	CANINDEYU	2	CORPUS CHRISTI	6	460	PINDOTY PORA
1402465	14	CANINDEYU	2	CORPUS CHRISTI	3	465	COLONIA PINDOTY PORA SUB-URBANO
1402470	14	CANINDEYU	2	CORPUS CHRISTI	6	470	KARUMBEY
1402480	14	CANINDEYU	2	CORPUS CHRISTI	6	480	CAYE KUE
1402490	14	CANINDEYU	2	CORPUS CHRISTI	6	490	CERRO LIMA
1402500	14	CANINDEYU	2	CORPUS CHRISTI	6	500	COM INDIG ARROYO PORA
1402510	14	CANINDEYU	2	CORPUS CHRISTI	6	510	COM INDIG ARROZ TYGUE
1402520	14	CANINDEYU	2	CORPUS CHRISTI	6	520	COM INDIG CERRO PYTA
1402530	14	CANINDEYU	2	CORPUS CHRISTI	6	530	COM INDIG FELICIDAD
1402540	14	CANINDEYU	2	CORPUS CHRISTI	6	540	COM INDIG NU'I
1402580	14	CANINDEYU	2	CORPUS CHRISTI	6	580	COM INDIG YNAMBU YGUA
1402590	14	CANINDEYU	2	CORPUS CHRISTI	6	590	GUATAMBU
1402600	14	CANINDEYU	2	CORPUS CHRISTI	6	600	OLIMPIA
1402610	14	CANINDEYU	2	CORPUS CHRISTI	6	610	SAN RAFAEL
1402620	14	CANINDEYU	2	CORPUS CHRISTI	6	620	SAN ROQUE
1403001	14	CANINDEYU	3	VILLA CURUGUATY	1	1	SANTA MARIA
1403002	14	CANINDEYU	3	VILLA CURUGUATY	1	2	INDUSTRIAL
1403003	14	CANINDEYU	3	VILLA CURUGUATY	1	3	MARIA AUXILIADORA
1403004	14	CANINDEYU	3	VILLA CURUGUATY	1	4	CENTRO
1403005	14	CANINDEYU	3	VILLA CURUGUATY	1	5	FATIMA
1403006	14	CANINDEYU	3	VILLA CURUGUATY	1	6	SAN JOSE OBRERO
1403007	14	CANINDEYU	3	VILLA CURUGUATY	1	7	CERRO CORA
1403008	14	CANINDEYU	3	VILLA CURUGUATY	1	8	CIUDAD NUEVA
1403009	14	CANINDEYU	3	VILLA CURUGUATY	1	9	PRIMAVERA
1403010	14	CANINDEYU	3	VILLA CURUGUATY	1	10	JUANA MARIA DE LARA
1403011	14	CANINDEYU	3	VILLA CURUGUATY	1	11	SAN MIGUEL
1403012	14	CANINDEYU	3	VILLA CURUGUATY	1	12	SAN CAYETANO
1403013	14	CANINDEYU	3	VILLA CURUGUATY	1	13	ASENT. SAN ISIDRO
1403100	14	CANINDEYU	3	VILLA CURUGUATY	6	100	MARACANA
1403110	14	CANINDEYU	3	VILLA CURUGUATY	6	110	ASENT. HUBER DURE
1403120	14	CANINDEYU	3	VILLA CURUGUATY	6	120	6TO ENCUADRE 1RA. LINEA
1403130	14	CANINDEYU	3	VILLA CURUGUATY	6	130	ASENT. 4TO ENCUADRE MARACANA
1403140	14	CANINDEYU	3	VILLA CURUGUATY	6	140	ASENT. 6TO ENCUADRE 2DA.LINEA MARACANA
1403150	14	CANINDEYU	3	VILLA CURUGUATY	6	150	KANGUERY MARACANA
1403160	14	CANINDEYU	3	VILLA CURUGUATY	6	160	ASENT. 6TO ENCUADRE 1RA.LINEA 1
1403170	14	CANINDEYU	3	VILLA CURUGUATY	6	170	COLONIA NUEVA DURANGO
1403180	14	CANINDEYU	3	VILLA CURUGUATY	6	180	7 MONTES
1403190	14	CANINDEYU	3	VILLA CURUGUATY	6	190	PASO REAL
1403200	14	CANINDEYU	3	VILLA CURUGUATY	6	200	ASENT. 5TO ENCUADRE 2DA. LINEA MARACANA
1403210	14	CANINDEYU	3	VILLA CURUGUATY	6	210	ASENT. 6TO ENCUADRE 1RA. LINEA 2
1403220	14	CANINDEYU	3	VILLA CURUGUATY	6	220	ASENT. MARACANA 1
1403230	14	CANINDEYU	3	VILLA CURUGUATY	6	230	CARRO KUE
1403240	14	CANINDEYU	3	VILLA CURUGUATY	6	240	COLONIA PYNANDI
1403250	14	CANINDEYU	3	VILLA CURUGUATY	6	250	SANTA LIBRADA
1403260	14	CANINDEYU	3	VILLA CURUGUATY	6	260	SAN BLAS KO'ETI
1403270	14	CANINDEYU	3	VILLA CURUGUATY	6	270	5TO ENCUADRE 1RA LINEA
1403280	14	CANINDEYU	3	VILLA CURUGUATY	6	280	5TO ENCUADRE MARACANA
1403290	14	CANINDEYU	3	VILLA CURUGUATY	6	290	COM INDIG YVY PORA
1403300	14	CANINDEYU	3	VILLA CURUGUATY	6	300	COM INDIG VERA RO
1403310	14	CANINDEYU	3	VILLA CURUGUATY	6	310	COM INDIG Y APY PIRO'Y
1403320	14	CANINDEYU	3	VILLA CURUGUATY	6	320	COM INDIG 12 DE NOVIEMBRE
1403330	14	CANINDEYU	3	VILLA CURUGUATY	6	330	ASENT. MARACANA  2
1403340	14	CANINDEYU	3	VILLA CURUGUATY	6	340	COM INDIG PINDOJU MARACANA
1403350	14	CANINDEYU	3	VILLA CURUGUATY	6	350	LAGUNITA
1403360	14	CANINDEYU	3	VILLA CURUGUATY	6	360	ASENT. PIRO'Y MARACANA
1403370	14	CANINDEYU	3	VILLA CURUGUATY	6	370	ITA KYSE
1403380	14	CANINDEYU	3	VILLA CURUGUATY	6	380	CERRITO
1403390	14	CANINDEYU	3	VILLA CURUGUATY	6	390	ASENT. 5TO ENCUADRE 1RA. LINEA
1403400	14	CANINDEYU	3	VILLA CURUGUATY	6	400	ASENT. MARACANA 3
1403410	14	CANINDEYU	3	VILLA CURUGUATY	6	410	ASENT. SUIZO KUE
1403420	14	CANINDEYU	3	VILLA CURUGUATY	6	420	COM INDIG ITAY MI
1403430	14	CANINDEYU	3	VILLA CURUGUATY	6	430	ASENT. PASO REAL
1403440	14	CANINDEYU	3	VILLA CURUGUATY	6	440	COM INDIG 1� DE MARZO
1403450	14	CANINDEYU	3	VILLA CURUGUATY	6	450	LA RUBIA
1403460	14	CANINDEYU	3	VILLA CURUGUATY	6	460	COM INDIG FORTUNA YUKYRY
1403470	14	CANINDEYU	3	VILLA CURUGUATY	6	470	COM INDIG FORTUNA - CORDILLERA
1403480	14	CANINDEYU	3	VILLA CURUGUATY	6	480	COM INDIG FORTUNA - CENTRO
1403490	14	CANINDEYU	3	VILLA CURUGUATY	6	490	COM INDIG FORTUNA PRIMAVERA
1403500	14	CANINDEYU	3	VILLA CURUGUATY	6	500	COM INDIG FORTUNA SAN LORENZO
1403510	14	CANINDEYU	3	VILLA CURUGUATY	6	510	COM INDIG FORTUNA YATAITY
1403520	14	CANINDEYU	3	VILLA CURUGUATY	6	520	COM INDIG FORTUNA 12 DE JUNIO
1403530	14	CANINDEYU	3	VILLA CURUGUATY	6	530	SAN ISIDRO
1403540	14	CANINDEYU	3	VILLA CURUGUATY	6	540	PUERTO LATA
1403550	14	CANINDEYU	3	VILLA CURUGUATY	6	550	TAKUARI  KILOMETRO 15
1403560	14	CANINDEYU	3	VILLA CURUGUATY	6	560	NU APU'A
1403570	14	CANINDEYU	3	VILLA CURUGUATY	6	570	ARAUJO KUE
1403580	14	CANINDEYU	3	VILLA CURUGUATY	6	580	COM INDIG FORTUNA SAN FRANCISCO
1403610	14	CANINDEYU	3	VILLA CURUGUATY	6	610	ITANDEY
1403630	14	CANINDEYU	3	VILLA CURUGUATY	6	630	SANTA ROSA
1403640	14	CANINDEYU	3	VILLA CURUGUATY	6	640	SANTA CATALINA
1403650	14	CANINDEYU	3	VILLA CURUGUATY	6	650	COLONIA JEPOPYHY
1403660	14	CANINDEYU	3	VILLA CURUGUATY	6	660	TAKUARATY
1403680	14	CANINDEYU	3	VILLA CURUGUATY	6	680	NARANJATY
1403740	14	CANINDEYU	3	VILLA CURUGUATY	6	740	GENERAL ARTIGAS
1403750	14	CANINDEYU	3	VILLA CURUGUATY	6	750	SAN FRANCISCO
1403760	14	CANINDEYU	3	VILLA CURUGUATY	6	760	PUESTO HU
1403770	14	CANINDEYU	3	VILLA CURUGUATY	6	770	JURUTI
1403780	14	CANINDEYU	3	VILLA CURUGUATY	6	780	AGUA'E
1403785	14	CANINDEYU	3	VILLA CURUGUATY	6	785	GENERAL DIAZ
1403790	14	CANINDEYU	3	VILLA CURUGUATY	6	790	CIUDAD NUEVA
1403795	14	CANINDEYU	3	VILLA CURUGUATY	6	795	COM INDIG MONTANIA 5TA LINEA
1403800	14	CANINDEYU	3	VILLA CURUGUATY	6	800	COM INDIG Y HOVY
1403805	14	CANINDEYU	3	VILLA CURUGUATY	6	805	COM INDIG 26 DE FEBRERO
1403840	14	CANINDEYU	3	VILLA CURUGUATY	6	840	YVYPYTA KILOMETRO 35
1403845	14	CANINDEYU	3	VILLA CURUGUATY	6	845	YVYPYTA KILOMETRO 25
1403850	14	CANINDEYU	3	VILLA CURUGUATY	6	850	SAN JOSE
1403855	14	CANINDEYU	3	VILLA CURUGUATY	6	855	ASENT. CERRITO
1403880	14	CANINDEYU	3	VILLA CURUGUATY	6	880	ASENT. SANTA CATALINA
1403885	14	CANINDEYU	3	VILLA CURUGUATY	6	885	ASENT. 2DA LINEA ACEPAR
1403890	14	CANINDEYU	3	VILLA CURUGUATY	6	890	ASENT. 3RA LINEA ACEPAR
1403895	14	CANINDEYU	3	VILLA CURUGUATY	6	895	ASENT. ARAUJO KUE
1403900	14	CANINDEYU	3	VILLA CURUGUATY	6	900	ARAUJO KUE ONAC
1403905	14	CANINDEYU	3	VILLA CURUGUATY	6	905	5TA LINEA ACEPAR
1403910	14	CANINDEYU	3	VILLA CURUGUATY	6	910	ARAUJO KUE  CCR SAN ANTONIO
1403915	14	CANINDEYU	3	VILLA CURUGUATY	6	915	ARAUJO KUE SANTA ROSA MI
1403920	14	CANINDEYU	3	VILLA CURUGUATY	6	920	ASENT. ARAUJO KUE 2
1403925	14	CANINDEYU	3	VILLA CURUGUATY	6	925	INDUSTRIAL
1403930	14	CANINDEYU	3	VILLA CURUGUATY	6	930	COM INDIG NUEVA ESPERANZA
1403935	14	CANINDEYU	3	VILLA CURUGUATY	6	935	COM INDIG TEKOJOJA 4 BOCAS
1403940	14	CANINDEYU	3	VILLA CURUGUATY	6	940	COM INDIG MYTUY
1403945	14	CANINDEYU	3	VILLA CURUGUATY	6	945	COM INDIG MBA'E KATU
1403950	14	CANINDEYU	3	VILLA CURUGUATY	6	950	COM INDIG PASO REAL
1403955	14	CANINDEYU	3	VILLA CURUGUATY	6	955	ASENT. TAVAI JOPOI
1403960	14	CANINDEYU	3	VILLA CURUGUATY	6	960	COM INDIG Y AKA POTY JUKERI
1403965	14	CANINDEYU	3	VILLA CURUGUATY	6	965	COM INDIG ISLA JOVAI
1403970	14	CANINDEYU	3	VILLA CURUGUATY	6	970	COM INDIG PIROY
1403975	14	CANINDEYU	3	VILLA CURUGUATY	6	975	PARAJE LUISA
1403980	14	CANINDEYU	3	VILLA CURUGUATY	6	980	COM INDIG MONTANIA
1403985	14	CANINDEYU	3	VILLA CURUGUATY	6	985	COLONIA MARCELINO MONTANIA
1403990	14	CANINDEYU	3	VILLA CURUGUATY	6	990	COM INDIG ARROYO MOROTI
1403995	14	CANINDEYU	3	VILLA CURUGUATY	6	995	COM INDIG Y'AKAJU
1403996	14	CANINDEYU	3	VILLA CURUGUATY	6	996	COM INDIG HU'YJU SAN CARLOS
1403997	14	CANINDEYU	3	VILLA CURUGUATY	6	997	COM INDIG KO'E PYAHU
1403998	14	CANINDEYU	3	VILLA CURUGUATY	6	998	COM INDIG RIO VERDE YSAKA
1404002	14	CANINDEYU	4	VILLA YGATIMI	1	2	SAN BLAS
1404003	14	CANINDEYU	4	VILLA YGATIMI	1	3	SAGRADA FAMILIA
1404004	14	CANINDEYU	4	VILLA YGATIMI	1	4	ASENT. SAGRADA FAMILIA
1404005	14	CANINDEYU	4	VILLA YGATIMI	1	5	VIRGEN DEL CARMEN
1404006	14	CANINDEYU	4	VILLA YGATIMI	1	6	SAN JOSE OBRERO
1404007	14	CANINDEYU	4	VILLA YGATIMI	1	7	LOMA CLAVEL
1404008	14	CANINDEYU	4	VILLA YGATIMI	1	8	ASENT. LOMA CLAVEL
1404100	14	CANINDEYU	4	VILLA YGATIMI	6	100	KO'E PORA
1404120	14	CANINDEYU	4	VILLA YGATIMI	6	120	ITANARAMI
1404140	14	CANINDEYU	4	VILLA YGATIMI	6	140	JEJUI MI
1404150	14	CANINDEYU	4	VILLA YGATIMI	6	150	JEJUI GUASU
1404160	14	CANINDEYU	4	VILLA YGATIMI	6	160	COLONIA 1� DE MAYO
1404180	14	CANINDEYU	4	VILLA YGATIMI	6	180	LA MORENA
1404240	14	CANINDEYU	4	VILLA YGATIMI	6	240	COM INDIG MBOI JAGUA - CENTRO
1404241	14	CANINDEYU	4	VILLA YGATIMI	6	241	COM INDIG MBOI JAGUA - CANADITA
1404242	14	CANINDEYU	4	VILLA YGATIMI	6	242	COM INDIG MBOI JAGUA - PINDOTY
1404260	14	CANINDEYU	4	VILLA YGATIMI	6	260	COM INDIG ITA POTY
1404270	14	CANINDEYU	4	VILLA YGATIMI	6	270	TENDAL RESERVA DEL MBARACAYU
1404300	14	CANINDEYU	4	VILLA YGATIMI	6	300	LAS RESIDENTAS
1404310	14	CANINDEYU	4	VILLA YGATIMI	6	310	SAN ANTONIO
1404320	14	CANINDEYU	4	VILLA YGATIMI	6	320	ARROYO GUASU
1404330	14	CANINDEYU	4	VILLA YGATIMI	6	330	11 DE SETIEMBRE
1404340	14	CANINDEYU	4	VILLA YGATIMI	6	340	NANDU ROCAI
1404350	14	CANINDEYU	4	VILLA YGATIMI	6	350	NU HAI
1404370	14	CANINDEYU	4	VILLA YGATIMI	6	370	COM INDIG 8 DE DICIEMBRE
1404380	14	CANINDEYU	4	VILLA YGATIMI	6	380	SANTA LIBRADA
1404390	14	CANINDEYU	4	VILLA YGATIMI	6	390	COM INDIG SAN ANTONIO
1404440	14	CANINDEYU	4	VILLA YGATIMI	6	440	TENDAL MARIA AUXILIADORA
1404460	14	CANINDEYU	4	VILLA YGATIMI	6	460	TENDAL 2DA LINEA
1404480	14	CANINDEYU	4	VILLA YGATIMI	6	480	COM INDIG KA'AGUY POTY
1404490	14	CANINDEYU	4	VILLA YGATIMI	6	490	SAGRADA FAMILIA
1404510	14	CANINDEYU	4	VILLA YGATIMI	6	510	ASENT. NUEVA ALIANZA
1404580	14	CANINDEYU	4	VILLA YGATIMI	6	580	COM INDIG ARROYO BANDERA
1404620	14	CANINDEYU	4	VILLA YGATIMI	6	620	ASENT. 1� DE MAYO
1404630	14	CANINDEYU	4	VILLA YGATIMI	6	630	COM INDIG VERA VUSU
1404650	14	CANINDEYU	4	VILLA YGATIMI	6	650	COM INDIG ITANARAMI
1404670	14	CANINDEYU	4	VILLA YGATIMI	6	670	COM INDIG CHUPA POU
1404760	14	CANINDEYU	4	VILLA YGATIMI	6	760	COM INDIG TAKUARY
1404780	14	CANINDEYU	4	VILLA YGATIMI	6	780	SAN AGUSTIN
1404790	14	CANINDEYU	4	VILLA YGATIMI	6	790	SAN SEBASTIAN
1404800	14	CANINDEYU	4	VILLA YGATIMI	6	800	YVU
1404810	14	CANINDEYU	4	VILLA YGATIMI	6	810	COM INDIG KA'AGUY PORA POTY
1404820	14	CANINDEYU	4	VILLA YGATIMI	6	820	NUEVA ALIANZA
1404830	14	CANINDEYU	4	VILLA YGATIMI	6	830	SAN JORGE
1404840	14	CANINDEYU	4	VILLA YGATIMI	6	840	SAN JOSE
1404850	14	CANINDEYU	4	VILLA YGATIMI	6	850	COM INDIG ARROYO MOKOI YVA POTY
1404870	14	CANINDEYU	4	VILLA YGATIMI	6	870	COM INDIG YAPO
1405001	14	CANINDEYU	5	ITANARA	1	1	CENTRO
1405100	14	CANINDEYU	5	ITANARA	6	100	ESTANCIA KARY
1405170	14	CANINDEYU	5	ITANARA	6	170	ASENT. COLONIA CRESCENCIO GONZALEZ
1405180	14	CANINDEYU	5	ITANARA	6	180	ASENT. SAN ROQUE
1405190	14	CANINDEYU	5	ITANARA	6	190	COLONIA ITANARA
1405210	14	CANINDEYU	5	ITANARA	6	210	COM INDIG YVYTY MIRI
1405220	14	CANINDEYU	5	ITANARA	6	220	COM INDIG PARIRI
1405240	14	CANINDEYU	5	ITANARA	6	240	MARIA AUXILIADORA
1405250	14	CANINDEYU	5	ITANARA	6	250	SAN FRANCISCO
1406001	14	CANINDEYU	6	YPEJHU	1	1	SAN JUAN
1406002	14	CANINDEYU	6	YPEJHU	1	2	VIRGEN DE FATIMA
1406003	14	CANINDEYU	6	YPEJHU	1	3	CENTRO
1406004	14	CANINDEYU	6	YPEJHU	1	4	VIRGEN DEL ROSARIO
1406005	14	CANINDEYU	6	YPEJHU	1	5	SAN ISIDRO
1406110	14	CANINDEYU	6	YPEJHU	6	110	COLONIA 8 DE DICIEMBRE
1406120	14	CANINDEYU	6	YPEJHU	6	120	ARA VERA
1406140	14	CANINDEYU	6	YPEJHU	6	140	ASENT. CRESCENCIO GONZALEZ
1406150	14	CANINDEYU	6	YPEJHU	6	150	KO'E PORA
1406170	14	CANINDEYU	6	YPEJHU	6	170	COM INDIG BARRANCO APY - Y'APY BARRANCO
1406180	14	CANINDEYU	6	YPEJHU	6	180	KA'AGUY PORA
1406190	14	CANINDEYU	6	YPEJHU	6	190	PYPUKU
1406240	14	CANINDEYU	6	YPEJHU	6	240	YVU
1406250	14	CANINDEYU	6	YPEJHU	6	250	COM INDIG PYPUCU
1406260	14	CANINDEYU	6	YPEJHU	6	260	COM INDIG KAVAJU PASO
1406270	14	CANINDEYU	6	YPEJHU	6	270	COM INDIG TEKOHA YVYPOTY
1406280	14	CANINDEYU	6	YPEJHU	6	280	COM INDIG YVYPAVE CANADA
1406300	14	CANINDEYU	6	YPEJHU	6	300	COM INDIG NARANDY
1406310	14	CANINDEYU	6	YPEJHU	6	310	COM INDIG ARROYO SATI
1406320	14	CANINDEYU	6	YPEJHU	6	320	SAN ROQUE GONZALEZ
1406330	14	CANINDEYU	6	YPEJHU	6	330	SAN JUAN
1406340	14	CANINDEYU	6	YPEJHU	6	340	SANTA LIBRADA
1406350	14	CANINDEYU	6	YPEJHU	6	350	COM INDIG PARIRI
1406370	14	CANINDEYU	6	YPEJHU	6	370	COM INDIG YVY PONY
1406390	14	CANINDEYU	6	YPEJHU	6	390	COM INDIG YVY KATU
1406400	14	CANINDEYU	6	YPEJHU	6	400	COM INDIG BARRANCO APY
1406410	14	CANINDEYU	6	YPEJHU	6	410	COM INDIG KA'AGUY JU CANDADO
1406420	14	CANINDEYU	6	YPEJHU	6	420	COM INDIG BARRANCO RUGUA
1406440	14	CANINDEYU	6	YPEJHU	6	440	COM INDIG KA'AGUY POTYRA
1406450	14	CANINDEYU	6	YPEJHU	6	450	COM INDIG Y MIRI
1406460	14	CANINDEYU	6	YPEJHU	6	460	COM INDIG TEKOHA PYAHU
1407001	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	1	1	SAN JOSE
1407002	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	1	2	SAN ROQUE
1407003	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	1	3	SANTA ANA
1407004	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	1	4	OBRERO
1407005	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	1	5	COLONIA SAN LUIS
1407006	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	1	6	ASENT. 15 DE MAYO
1407007	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	1	7	ASENT. URBANO
1407100	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	100	COLONIA ALBORADA
1407105	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	3	105	COLONIA ALBORADA SUB-URBANO
1407110	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	110	SAN JUAN
1407120	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	120	ARENA BLANCA
1407150	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	150	IBEL
1407160	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	160	LINEA PROGRESO
1407170	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	170	1RO DE MARZO
1407180	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	180	SAN IGNACIO
1407190	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	190	COLONIA SAN LUIS
1407200	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	200	SAN PEDRO
1407210	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	210	SAN JOSE
1407220	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	220	SAN ANTONIO
1407230	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	230	COM INDIG ITAKUA PU
1407240	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	240	COLONIA SANTA ROSA
1407250	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	250	PRIMAVERA
1407260	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	260	ASENT. SAN JUAN 2
1407270	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	270	SAN JUAN 2
1407280	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	280	ASENT. SAN JORGE
1407290	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	290	SAN ROQUE
1407300	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	300	ASENT. SAN JUAN
1407310	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	310	COM INDIG BAJADA GUAZU
1407315	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	315	COM INDIG BAJADA GUAZU - GUYRAJU MIRI
1407320	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	320	MARIA AUXILIADORA
1407330	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	330	SAN JORGE
1407340	14	CANINDEYU	7	FRANCISCO CABALLERO ALVAREZ	6	340	ITAVO MI
1408002	14	CANINDEYU	8	KATUETE	1	2	PRIMAVERA
1408003	14	CANINDEYU	8	KATUETE	1	3	CENTRO
1408004	14	CANINDEYU	8	KATUETE	1	4	MARIA AUXILIADORA
1408005	14	CANINDEYU	8	KATUETE	1	5	SANTA LIBRADA
1408006	14	CANINDEYU	8	KATUETE	1	6	SAN JUAN
1408007	14	CANINDEYU	8	KATUETE	1	7	ASENT. VIRGEN SERRANA
1408120	14	CANINDEYU	8	KATUETE	6	120	KUMANDA KAI
1408130	14	CANINDEYU	8	KATUETE	6	130	COLONIA LA BOLSA
1408140	14	CANINDEYU	8	KATUETE	6	140	FAZENDA PALOMA
1408150	14	CANINDEYU	8	KATUETE	6	150	AGRICOLA PARAGUAYA
1408170	14	CANINDEYU	8	KATUETE	6	170	1RO DE MARZO
1408180	14	CANINDEYU	8	KATUETE	6	180	CURVA DE LATA
1408190	14	CANINDEYU	8	KATUETE	6	190	FAZENDA ESPANA
1408200	14	CANINDEYU	8	KATUETE	6	200	TEMBEY
1409001	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	1	1	MARIA AUXILIADORA
1409002	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	1	2	SAN BLAS
1409003	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	1	3	LAS MERCEDES
1409004	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	1	4	SANTA RITA
1409005	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	1	5	SAN JOSE OBRERO
1409006	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	1	6	JAMAICA
1409110	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	110	COLONIA GUADALUPE
1409120	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	120	COLONIA SANTA CLARA
1409130	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	130	MBARAKAJU
1409140	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	140	JAMAICA
1409150	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	150	LOTE 5
1409160	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	160	6 DE ENERO
1409170	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	170	YVU PORA
1409180	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	180	VIRGEN DE FATIMA
1409190	14	CANINDEYU	9	LA PALOMA DEL ESPIRITU SANTO	6	190	SAN BLAS
1410001	14	CANINDEYU	10	NUEVA ESPERANZA	1	1	CENTRO
1410002	14	CANINDEYU	10	NUEVA ESPERANZA	1	2	OBRERO
1410003	14	CANINDEYU	10	NUEVA ESPERANZA	1	3	LA VICTORIA
1410004	14	CANINDEYU	10	NUEVA ESPERANZA	1	4	SAN ANTONIO
1410100	14	CANINDEYU	10	NUEVA ESPERANZA	6	100	14 MIL
1410110	14	CANINDEYU	10	NUEVA ESPERANZA	6	110	LAUREL
1410115	14	CANINDEYU	10	NUEVA ESPERANZA	3	115	LAUREL SUB-URBANO
1410130	14	CANINDEYU	10	NUEVA ESPERANZA	6	130	TRACTOR KUE
1410140	14	CANINDEYU	10	NUEVA ESPERANZA	6	140	COLONIA MARANGATU
1410160	14	CANINDEYU	10	NUEVA ESPERANZA	6	160	COLONIA ITAMBEY
1410165	14	CANINDEYU	10	NUEVA ESPERANZA	3	165	COLONIA ITAMBEY SUB-URBANO
1410180	14	CANINDEYU	10	NUEVA ESPERANZA	6	180	TRONCAL 4 SUR
1410190	14	CANINDEYU	10	NUEVA ESPERANZA	6	190	SAN BLAS
1410200	14	CANINDEYU	10	NUEVA ESPERANZA	6	200	COLONIA SAN MARCOS
1410210	14	CANINDEYU	10	NUEVA ESPERANZA	6	210	KARUMBEY
1410220	14	CANINDEYU	10	NUEVA ESPERANZA	6	220	CERRO AZUL
1410230	14	CANINDEYU	10	NUEVA ESPERANZA	6	230	TRONCAL 4
1410240	14	CANINDEYU	10	NUEVA ESPERANZA	6	240	COM INDIG ITAVO GUARANI
1410250	14	CANINDEYU	10	NUEVA ESPERANZA	6	250	COM INDIG ARA PYAHU AVA GUARANI
1410260	14	CANINDEYU	10	NUEVA ESPERANZA	6	260	1RO DE MARZO
1410280	14	CANINDEYU	10	NUEVA ESPERANZA	6	280	SAN AGUSTIN
1410290	14	CANINDEYU	10	NUEVA ESPERANZA	6	290	KUMANDA KAI
1410300	14	CANINDEYU	10	NUEVA ESPERANZA	6	300	SAN ANTONIO
1410310	14	CANINDEYU	10	NUEVA ESPERANZA	6	310	EL PORTAL
1410315	14	CANINDEYU	10	NUEVA ESPERANZA	3	315	EL PORTAL SUB-URBANO
1410320	14	CANINDEYU	10	NUEVA ESPERANZA	6	320	COM INDIG TAKUARA'I
1411001	14	CANINDEYU	11	YASY CANY	1	1	CENTRO
1411002	14	CANINDEYU	11	YASY CANY	1	2	SAN MIGUEL
1411003	14	CANINDEYU	11	YASY CANY	1	3	SAN JOSE OBRERO
1411100	14	CANINDEYU	11	YASY CANY	6	100	ASENT. NINO SALVADOR
1411110	14	CANINDEYU	11	YASY CANY	6	110	ASENT. 8 DE DICIEMBRE
1411120	14	CANINDEYU	11	YASY CANY	6	120	CALLE TAKUAPI
1411130	14	CANINDEYU	11	YASY CANY	6	130	GUAJAYVI
1411140	14	CANINDEYU	11	YASY CANY	6	140	YVY PYAHU
1411150	14	CANINDEYU	11	YASY CANY	6	150	SAN JUAN 1
1411160	14	CANINDEYU	11	YASY CANY	6	160	ASENT. ACEPAR 1
1411170	14	CANINDEYU	11	YASY CANY	6	170	ACEPAR 4TA LINEA SAN JUAN
1411180	14	CANINDEYU	11	YASY CANY	6	180	ACEPAR 3RA LINEA
1411190	14	CANINDEYU	11	YASY CANY	6	190	ACEPAR 2DA LINEA
1411200	14	CANINDEYU	11	YASY CANY	6	200	ACEPAR 5TA LINEA
1411210	14	CANINDEYU	11	YASY CANY	6	210	ACEPAR 6TA LINEA
1411220	14	CANINDEYU	11	YASY CANY	6	220	ALEMAN KUE
1411230	14	CANINDEYU	11	YASY CANY	6	230	ASENT. SAN LORENZO
1411240	14	CANINDEYU	11	YASY CANY	6	240	ASENT. ACEPAR 2
1411250	14	CANINDEYU	11	YASY CANY	6	250	SIDEPAR COLONIA BELLEZA
1411260	14	CANINDEYU	11	YASY CANY	6	260	TAPIA
1411270	14	CANINDEYU	11	YASY CANY	6	270	NUEVA ALIANZA
1411280	14	CANINDEYU	11	YASY CANY	6	280	SAN MIGUEL
1411290	14	CANINDEYU	11	YASY CANY	6	290	COM INDIG TEKOHA KA'AGUY POTY - KAMBA
1411300	14	CANINDEYU	11	YASY CANY	6	300	KA'AY
1411310	14	CANINDEYU	11	YASY CANY	6	310	COM INDIG SAN ANTONIO
1411320	14	CANINDEYU	11	YASY CANY	6	320	COM INDIG YVY PORA
1411330	14	CANINDEYU	11	YASY CANY	6	330	COM INDIG MBOCAJATY
1411340	14	CANINDEYU	11	YASY CANY	6	340	COM INDIG TUNA POTY
1411350	14	CANINDEYU	11	YASY CANY	6	350	YKUA PORA
1411360	14	CANINDEYU	11	YASY CANY	6	360	ASENT. YKUA PORA
1411370	14	CANINDEYU	11	YASY CANY	6	370	ASENT. MANDU'ARA
1411380	14	CANINDEYU	11	YASY CANY	6	380	6 DE ENERO
1411390	14	CANINDEYU	11	YASY CANY	6	390	SAN BLAS
1411400	14	CANINDEYU	11	YASY CANY	6	400	NACIENTE
1411410	14	CANINDEYU	11	YASY CANY	6	410	LAGUNITA
1411420	14	CANINDEYU	11	YASY CANY	6	420	ASENT. SANTA LUCIA
1411430	14	CANINDEYU	11	YASY CANY	6	430	LAGUNA PAKOVA
1411440	14	CANINDEYU	11	YASY CANY	6	440	COM INDIG YVY PYAHU
1411450	14	CANINDEYU	11	YASY CANY	6	450	BARRERO VILLAR
1411460	14	CANINDEYU	11	YASY CANY	6	460	PINDO
1411470	14	CANINDEYU	11	YASY CANY	6	470	ASENT. SOL NACIENTE
1411480	14	CANINDEYU	11	YASY CANY	6	480	SOL NACIENTE
1411490	14	CANINDEYU	11	YASY CANY	6	490	VYSOKOLAN
1411500	14	CANINDEYU	11	YASY CANY	6	500	COM INDIG PAKURI STA LIBRADA
1411510	14	CANINDEYU	11	YASY CANY	6	510	COM INDIG TAKUA MIRI
1411520	14	CANINDEYU	11	YASY CANY	6	520	COM INDIG YKUA VIJU
1411530	14	CANINDEYU	11	YASY CANY	6	530	COM INDIG KANINDE
1411540	14	CANINDEYU	11	YASY CANY	6	540	SAN JUAN 2
1411550	14	CANINDEYU	11	YASY CANY	6	550	TUNA POTY
1411560	14	CANINDEYU	11	YASY CANY	6	560	SAN BARTOLOME
1411570	14	CANINDEYU	11	YASY CANY	6	570	MANDU'ARA
1411580	14	CANINDEYU	11	YASY CANY	6	580	SAN LUIS
1411590	14	CANINDEYU	11	YASY CANY	6	590	MBURUKUJA
1411600	14	CANINDEYU	11	YASY CANY	6	600	COLONIA JASY KANY
1411610	14	CANINDEYU	11	YASY CANY	6	610	ACEPAR
1411620	14	CANINDEYU	11	YASY CANY	6	620	SIDEPAR 3000
1411630	14	CANINDEYU	11	YASY CANY	6	630	COM INDIG TAJY POTY
1411640	14	CANINDEYU	11	YASY CANY	6	640	COM INDIG TEKOHA MIRI POTY-ALIKA KUE
1411650	14	CANINDEYU	11	YASY CANY	6	650	COM INDIG PINDO I
1411660	14	CANINDEYU	11	YASY CANY	6	660	COM INDIG KA'AGUY POTY KAPI'ITINDY
1411670	14	CANINDEYU	11	YASY CANY	6	670	COM INDIG JOYVY
1411680	14	CANINDEYU	11	YASY CANY	6	680	COM INDIG TEKOJOJA
1411690	14	CANINDEYU	11	YASY CANY	6	690	COM INDIG YVAY MIRI
1412001	14	CANINDEYU	12	YBYRAROBANA	1	1	SAN MIGUEL
1412002	14	CANINDEYU	12	YBYRAROBANA	1	2	SAN JOSE
1412003	14	CANINDEYU	12	YBYRAROBANA	1	3	21 DE SETIEMBRE
1412004	14	CANINDEYU	12	YBYRAROBANA	1	4	GENERAL BERNARDINO CABALLERO
1412100	14	CANINDEYU	12	YBYRAROBANA	6	100	ARROYO GUASU
1412110	14	CANINDEYU	12	YBYRAROBANA	6	110	ASENT. ITA VERA
1412120	14	CANINDEYU	12	YBYRAROBANA	6	120	NARANJITO
1412130	14	CANINDEYU	12	YBYRAROBANA	6	130	JUANA DE LARA
1412140	14	CANINDEYU	12	YBYRAROBANA	6	140	11 DE SETIEMBRE
1412150	14	CANINDEYU	12	YBYRAROBANA	6	150	CERRO YSAU
1412160	14	CANINDEYU	12	YBYRAROBANA	6	160	SAN JUAN
1412170	14	CANINDEYU	12	YBYRAROBANA	6	170	SAN ANTONIO 1
1412180	14	CANINDEYU	12	YBYRAROBANA	6	180	SAN BLAS
1412190	14	CANINDEYU	12	YBYRAROBANA	6	190	SANTO DOMINGO
1412200	14	CANINDEYU	12	YBYRAROBANA	6	200	3 CORAZONES
1412210	14	CANINDEYU	12	YBYRAROBANA	6	210	FATIMA
1412220	14	CANINDEYU	12	YBYRAROBANA	6	220	SAN ISIDRO 2
1412230	14	CANINDEYU	12	YBYRAROBANA	6	230	PASO ITA
1412240	14	CANINDEYU	12	YBYRAROBANA	6	240	MINGA'I
1412250	14	CANINDEYU	12	YBYRAROBANA	6	250	COPACRI
1412260	14	CANINDEYU	12	YBYRAROBANA	6	260	COPARO
1412270	14	CANINDEYU	12	YBYRAROBANA	6	270	COM INDIG ARROYO PORA
1412280	14	CANINDEYU	12	YBYRAROBANA	6	280	COM INDIG YVY APY KATU POTRERITO
1412290	14	CANINDEYU	12	YBYRAROBANA	6	290	LEON KUE
1412300	14	CANINDEYU	12	YBYRAROBANA	6	300	COLONIA YVYRAROVANA
1412310	14	CANINDEYU	12	YBYRAROBANA	6	310	COM INDIG TEKOHA POTY PYAHU
1412320	14	CANINDEYU	12	YBYRAROBANA	6	320	COM INDIG SAN JUAN
1412340	14	CANINDEYU	12	YBYRAROBANA	6	340	SANTA ANA
1412350	14	CANINDEYU	12	YBYRAROBANA	6	350	COM INDIG TATU KUE
1412360	14	CANINDEYU	12	YBYRAROBANA	6	360	COM INDIG CERRO PYTA
1412370	14	CANINDEYU	12	YBYRAROBANA	6	370	COM INDIG CERRO CAMPIN
1412380	14	CANINDEYU	12	YBYRAROBANA	6	380	ARROYO MOKOI
1412390	14	CANINDEYU	12	YBYRAROBANA	6	390	COM INDIG ARROYO MOKOI
1412400	14	CANINDEYU	12	YBYRAROBANA	6	400	SAN ANTONIO 2
1412410	14	CANINDEYU	12	YBYRAROBANA	6	410	LOMAS VALENTINAS
1412420	14	CANINDEYU	12	YBYRAROBANA	6	420	JEJUI SUR
1412430	14	CANINDEYU	12	YBYRAROBANA	6	430	COM INDIG JEJYTY MIRI
1412440	14	CANINDEYU	12	YBYRAROBANA	6	440	CERRO VERDE
1412450	14	CANINDEYU	12	YBYRAROBANA	6	450	SAN MIGUEL
1412460	14	CANINDEYU	12	YBYRAROBANA	3	460	YHOVY SUB-URBANO
1412470	14	CANINDEYU	12	YBYRAROBANA	6	470	FORTUNA CRISTO REY
1412480	14	CANINDEYU	12	YBYRAROBANA	6	480	COM INDIG CERRO VERDE
1412490	14	CANINDEYU	12	YBYRAROBANA	6	490	COM INDIG KA'AGUY MIRI
1413001	14	CANINDEYU	13	YBY PYTA	1	1	URBANO
1413100	14	CANINDEYU	13	YBY PYTA	6	100	CERRO LIMA
1413110	14	CANINDEYU	13	YBY PYTA	6	110	GUYRA KEHA SAN ANTONIO
1413120	14	CANINDEYU	13	YBY PYTA	6	120	GUYRA KEHA
1413130	14	CANINDEYU	13	YBY PYTA	6	130	BRITEZ KUE 1RA LINEA CAAGUAZU
1413140	14	CANINDEYU	13	YBY PYTA	6	140	BRITEZ KUE PASTOREO
1413150	14	CANINDEYU	13	YBY PYTA	6	150	BRITEZ KUE 4 DE OCTUBRE
1413160	14	CANINDEYU	13	YBY PYTA	6	160	BRITEZ KUE LAS MERCEDES
1413170	14	CANINDEYU	13	YBY PYTA	6	170	BRITEZ KUE MARIA AUXILIADORA
1413180	14	CANINDEYU	13	YBY PYTA	6	180	BRITEZ KUE SAN MIGUEL
1413190	14	CANINDEYU	13	YBY PYTA	6	190	BRITEZ KUE
1413200	14	CANINDEYU	13	YBY PYTA	6	200	YVY PYTA SAN LUIS
1413210	14	CANINDEYU	13	YBY PYTA	6	210	YVY PYTA SAN LUIS TRIUNFO
1413220	14	CANINDEYU	13	YBY PYTA	6	220	KARUPERA 2
1413230	14	CANINDEYU	13	YBY PYTA	6	230	KARUPERA 1
1413240	14	CANINDEYU	13	YBY PYTA	6	240	YVY PYTA 3
1413250	14	CANINDEYU	13	YBY PYTA	6	250	YVYPYTA VISTA ALEGRE
1413260	14	CANINDEYU	13	YBY PYTA	6	260	YVY PYTA 2
1413270	14	CANINDEYU	13	YBY PYTA	6	270	YVYPYTA  COLONIA 8 DE DICIEMBRE
1413280	14	CANINDEYU	13	YBY PYTA	6	280	YVYPYTA
1413290	14	CANINDEYU	13	YBY PYTA	6	290	YVYPYTA TAKUAPI
1413300	14	CANINDEYU	13	YBY PYTA	6	300	YVYPYTA  MARIA AUXILIADORA
1413310	14	CANINDEYU	13	YBY PYTA	6	310	YVYPYTA 1500
1413320	14	CANINDEYU	13	YBY PYTA	6	320	YVYPYTA 2000
1413330	14	CANINDEYU	13	YBY PYTA	6	330	YVYPYTA KILOMETRO 25
1413340	14	CANINDEYU	13	YBY PYTA	6	340	CALLE 1
1413400	14	CANINDEYU	13	YBY PYTA	6	400	COM INDIG YRYAPU
1413450	14	CANINDEYU	13	YBY PYTA	6	450	COM INDIG 12 DE JUNIO
1413540	14	CANINDEYU	13	YBY PYTA	6	540	COM INDIG TAKUA POTY
1413550	14	CANINDEYU	13	YBY PYTA	6	550	COM INDIG PASO JOVAI
1413560	14	CANINDEYU	13	YBY PYTA	6	560	COM INDIG KA'AGUY MIRI
1413570	14	CANINDEYU	13	YBY PYTA	6	570	COM INDIG TEKOHA RYAPU LAGUNA HOVY
1413600	14	CANINDEYU	13	YBY PYTA	6	600	COM INDIG Y ARY POTY
1413610	14	CANINDEYU	13	YBY PYTA	6	610	COM INDIG NU VERA KATU
1413630	14	CANINDEYU	13	YBY PYTA	6	630	COM INDIG TEKOHA POTY PYAHU
1413640	14	CANINDEYU	13	YBY PYTA	6	640	COM INDIG SAN JUAN
1413720	14	CANINDEYU	13	YBY PYTA	6	720	COM INDIG ARAPOTY
1413770	14	CANINDEYU	13	YBY PYTA	6	770	COM INDIG KO'E PYAHU POTY (EX KURUSU)
1413815	14	CANINDEYU	13	YBY PYTA	6	815	COM INDIG AGUA'E
1413820	14	CANINDEYU	13	YBY PYTA	6	820	COM INDIG YVY KATU
1413880	14	CANINDEYU	13	YBY PYTA	6	880	COM INDIG YVYJU
1413890	14	CANINDEYU	13	YBY PYTA	6	890	COM INDIG YTU
1413895	14	CANINDEYU	13	YBY PYTA	6	895	COM INDIG YVERA KA'A POTY
1413900	14	CANINDEYU	13	YBY PYTA	6	900	COM INDIG KUETUVYVE
1413910	14	CANINDEYU	13	YBY PYTA	6	910	COM INDIG MYTUY ARAGUAYU
1413920	14	CANINDEYU	13	YBY PYTA	6	920	COM INDIG KUETUVY
1502001	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	1	1	CENTRAL
1502002	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	1	2	MARISCAL LOPEZ
1502003	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	1	3	15 DE AGOSTO
1502004	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	1	4	PASITO
1502005	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	1	5	SAN AGUSTIN
1502100	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	100	ZONA PECHUGON
1502110	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	110	ASENT SAN JOSE
1502120	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	120	ZONA CONFUSO
1502130	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	130	COM INDIG ROSARINO
1502140	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	140	ZONA CERRITO
1502145	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	3	145	CERRITO SUB-URBANO
1502150	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	150	COM INDIG SAN FRANCISCO DE ASIS
1502160	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	160	COSTA
1502190	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	190	COM INDIG SANTA LUCIA
1502200	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	200	ZONA RIO NEGRO
1502210	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	210	ZONA MONTELINDO
1502220	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	220	COM INDIG RIO VERDE
1502230	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	230	ASENT TIERRA PROMETIDA
1502240	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	240	ZONA POZO AZUL
1502250	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	250	ZONA ESTANCIA GOLONDRINA
1502260	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	260	ZONA KM 134
1502270	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	270	ZONA TACUARA
1502280	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	280	ZONA KM 110
1502290	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	290	ZONA MOISES GALEANO
1502300	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	300	ZONA KM 125
1502310	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	310	COM INDIG SANTA MARIA
1502320	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	320	COM INDIG SANTA ROSA
1502330	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	330	COM INDIG KEM HA YAT SEPO - MONTELINDO
1502340	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	340	COM INDIG NGALEC QOM
1502350	15	PRESIDENTE HAYES	2	BENJAMIN ACEVAL	6	350	COM INDIG KAEL SAT LECPI (CERRITENO)
1503001	15	PRESIDENTE HAYES	3	PUERTO PINASCO	1	1	URBANO
1503100	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	100	ZONA PINASCO
1503110	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	110	ZONA CEIBO
1503130	15	PRESIDENTE HAYES	3	PUERTO PINASCO	3	130	COLONIA CEIBO SUB URBANO
1503140	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	140	ZONA ZALAZAR
1503150	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	150	COM INDIG EX CORA'I - NEPOXEN
1503160	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	160	COM INDIG EX CORA'I - SARIA
1503170	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	170	COM INDIG EX CORA' I - KENATEN
1503180	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	180	ZONA LA PATRIA
1503190	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	190	COM INDIG LA PATRIA - LA PACIENCIA
1503200	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	200	COM INDIG LA PATRIA - URUNDEY
1503210	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	210	COM INDIG LA PATRIA - LAS FLORES
1503220	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	220	COM INDIG LA PATRIA - PARAISO
1503230	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	230	COM INDIG LA PATRIA - KAROA GUASU
1503240	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	240	COM INDIG LA PATRIA - PUENTE KAIGUE
1503250	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	250	COM INDIG LA PATRIA - KAROA'I
1503260	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	260	COM INDIG LA PATRIA - LAGUNA HU
1503270	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	270	COM INDIG LA PATRIA - LA LEONA
1503280	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	280	COM INDIG LA PATRIA - SAN FERNANDEZ
1503290	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	290	COM INDIG LA PATRIA - COLONIA 24
1503300	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	300	COM INDIG LA PATRIA - TATARE
1503310	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	310	COM INDIG LA PATRIA - CARPINCHO
1503320	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	320	COM INDIG LA PATRIA - LAGUNA TEJA
1503330	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	330	COM INDIG LA PATRIA - MONTE KUE
1503340	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	340	ZONA RIO VERDE
1503350	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	350	NUEVA MESTRE
1503360	15	PRESIDENTE HAYES	3	PUERTO PINASCO	3	360	NUEVA MESTRE SUB URBANO
1503370	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	370	COM INDIG LAGUNA PATO - LA INDIA
1503375	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	375	COM INDIG LAGUNA PATO
1605210	16	BOQUERON	5	LOMA PLATA	6	210	ALDEA SCHONTAL
1503380	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	380	COM INDIG LA PALMERA
1503390	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	390	COM INDIG LAGUNA PATO - KUNATAI
1503400	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	400	COM INDIG LAGUNA PATO - LOLAICO'I
1503410	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	410	COM INDIG LAGUNA PATO - SALADO
1503420	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	420	COM INDIG SAN FERNANDO - PASO LIMA
1503430	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	430	COM INDIG SAN FERNANDO - CURUPAYTY
1503440	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	440	COM INDIG SAN FERNANDO
1503450	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	450	COM INDIG RIACHO SAN CARLOS - SAN CARLOS
1503460	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	460	COM INDIG RIACHO SAN CARLOS - HUGUA CHINI
1503470	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	470	COM INDIG GENTE RORY
1503480	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	480	COM INDIG EX-CORAI - TAJAMAR KAVAJU
1503490	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	490	COM INDIG EX-CORAI - 4 DE AGOSTO
1503500	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	500	COM INDIG EX-CORA'I - 8 DE ENERO
1503510	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	510	COM INDIG LAGUNA PATO - LOLAICO GUASU
1503520	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	520	COM INDIG LAGUNA PATO - BRILLANTE
1503530	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	530	COM INDIG LAGUNA PATO - PAISA TEMPELA
1503540	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	540	COM INDIG XAKMOK KASEK (25 DE FEBRERO)
1503550	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	550	COM INDIG LA PATRIA - TRES QUEBRACHOS
1503560	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	560	COM INDIG LA PATRIA - 6 DE MARZO
1503570	15	PRESIDENTE HAYES	3	PUERTO PINASCO	6	570	COM INDIG RIACHO SAN CARLOS - MBOKAJATY
1504001	15	PRESIDENTE HAYES	4	VILLA HAYES	1	1	CIUDAD NUEVA
1504002	15	PRESIDENTE HAYES	4	VILLA HAYES	1	2	PANETE
1504003	15	PRESIDENTE HAYES	4	VILLA HAYES	1	3	ALONSO
1504004	15	PRESIDENTE HAYES	4	VILLA HAYES	1	4	GOLONDRINA
1504005	15	PRESIDENTE HAYES	4	VILLA HAYES	1	5	SANTA LIBRADA
1504006	15	PRESIDENTE HAYES	4	VILLA HAYES	1	6	GUBIER
1504007	15	PRESIDENTE HAYES	4	VILLA HAYES	1	7	SAN JORGE
1504008	15	PRESIDENTE HAYES	4	VILLA HAYES	1	8	ASENT. SUENOS Y ESPERANZAS
1504009	15	PRESIDENTE HAYES	4	VILLA HAYES	1	9	PA'I ROBERTO
1504010	15	PRESIDENTE HAYES	4	VILLA HAYES	1	10	LA VICTORIA
1504011	15	PRESIDENTE HAYES	4	VILLA HAYES	1	11	MARIA AUXILIADORA
1504012	15	PRESIDENTE HAYES	4	VILLA HAYES	1	12	SAN JUAN BAUTISTA
1504013	15	PRESIDENTE HAYES	4	VILLA HAYES	1	13	ASENT. EL PROGRESO
1504014	15	PRESIDENTE HAYES	4	VILLA HAYES	1	14	EL PROGRESO 1
1504015	15	PRESIDENTE HAYES	4	VILLA HAYES	1	15	8 DE DICIEMBRE
1504016	15	PRESIDENTE HAYES	4	VILLA HAYES	1	16	VILLA GRACIELA
1504017	15	PRESIDENTE HAYES	4	VILLA HAYES	1	17	EL PROGRESO 2
1504018	15	PRESIDENTE HAYES	4	VILLA HAYES	1	18	CERRO
1504140	15	PRESIDENTE HAYES	4	VILLA HAYES	6	140	ZONA CERRITO
1504150	15	PRESIDENTE HAYES	4	VILLA HAYES	6	150	ZONA MONTELINDO
1504160	15	PRESIDENTE HAYES	4	VILLA HAYES	6	160	ZONA PFANELL
1504270	15	PRESIDENTE HAYES	4	VILLA HAYES	6	270	SALADILLO
1504290	15	PRESIDENTE HAYES	4	VILLA HAYES	6	290	ZONA RIO VERDE VILLA HAYES
1504310	15	PRESIDENTE HAYES	4	VILLA HAYES	6	310	ZONA BETERETE KUE
1504320	15	PRESIDENTE HAYES	4	VILLA HAYES	3	320	CHACO'I SUB-URBANO
1504330	15	PRESIDENTE HAYES	4	VILLA HAYES	3	330	REMANSITO SUB-URBANO
1504340	15	PRESIDENTE HAYES	4	VILLA HAYES	6	340	ZONA CHACO'I
1504350	15	PRESIDENTE HAYES	4	VILLA HAYES	6	350	ZONA REMANSITO
1504360	15	PRESIDENTE HAYES	4	VILLA HAYES	6	360	COSTA GUASU
1504370	15	PRESIDENTE HAYES	4	VILLA HAYES	6	370	ASENT. SALADILLO
1504380	15	PRESIDENTE HAYES	4	VILLA HAYES	6	380	ASENT. REMANSITO
1504390	15	PRESIDENTE HAYES	4	VILLA HAYES	6	390	COM INDIG YANEKYAHA- ESPINILLO
1504400	15	PRESIDENTE HAYES	4	VILLA HAYES	6	400	SAN ANTONIO
1504410	15	PRESIDENTE HAYES	4	VILLA HAYES	6	410	ZONA RIO VERDE
1504420	15	PRESIDENTE HAYES	4	VILLA HAYES	6	420	COM INDIG LA HERENCIA  - JERUSALEN
1504430	15	PRESIDENTE HAYES	4	VILLA HAYES	6	430	COM INDIG LA HERENCIA  - PALO BLANCO
1504440	15	PRESIDENTE HAYES	4	VILLA HAYES	6	440	COM INDIG LA HERENCIA - PRIMAVERA
1504450	15	PRESIDENTE HAYES	4	VILLA HAYES	6	450	COM INDIG LA HERENCIA - LA HERENCIA
1504460	15	PRESIDENTE HAYES	4	VILLA HAYES	6	460	COM INDIG LA HERENCIA  - NAZARETH
1504470	15	PRESIDENTE HAYES	4	VILLA HAYES	6	470	COM INDIG LA HERENCIA - LARROSA CUE
1504475	15	PRESIDENTE HAYES	4	VILLA HAYES	6	475	COM INDIG LA HERENCIA - LARROSA CUE 2
1504490	15	PRESIDENTE HAYES	4	VILLA HAYES	6	490	COM INDIG YANEKYAHA ESPINILLO - TIMBO TY
1504510	15	PRESIDENTE HAYES	4	VILLA HAYES	6	510	COM INDIG YANEKYAHA ESPINILLO - 26 DE JUNIO
1504520	15	PRESIDENTE HAYES	4	VILLA HAYES	6	520	COM INDIG YANEKYAHA ESPINILLO - SAMARIA
1504540	15	PRESIDENTE HAYES	4	VILLA HAYES	6	540	COM INDIG POZO COLORADO
1504550	15	PRESIDENTE HAYES	4	VILLA HAYES	6	550	ASENT. NINO JESUS
1504560	15	PRESIDENTE HAYES	4	VILLA HAYES	6	560	COM INDIG MAKXAWAYA - MONTE ALTO
1504570	15	PRESIDENTE HAYES	4	VILLA HAYES	6	570	POZO COLORADO
1504575	15	PRESIDENTE HAYES	4	VILLA HAYES	3	575	POZO COLORADO SUB-URBANO
1504580	15	PRESIDENTE HAYES	4	VILLA HAYES	6	580	COM INDIG MAKXAWAYA
1504590	15	PRESIDENTE HAYES	4	VILLA HAYES	6	590	COM INDIG BUENA VISTA
1504600	15	PRESIDENTE HAYES	4	VILLA HAYES	6	600	COM INDIG RODOLFITO (ALBORADA)
1504610	15	PRESIDENTE HAYES	4	VILLA HAYES	6	610	ZONA SANTA CATALINA
1504620	15	PRESIDENTE HAYES	4	VILLA HAYES	6	620	COM INDIG YAKYE AXA
1504630	15	PRESIDENTE HAYES	4	VILLA HAYES	6	630	COM INDIG SAWHOYAMAXA - SANTA ELISA
1504640	15	PRESIDENTE HAYES	4	VILLA HAYES	6	640	COM INDIG SAWHOYAMAXA - KM 16
1504650	15	PRESIDENTE HAYES	4	VILLA HAYES	6	650	COM INDIG KELYENMAGATEGMA  (KARAJA VUELTA)
1504660	15	PRESIDENTE HAYES	4	VILLA HAYES	6	660	COM INDIG COMUNIDAD 96
1504670	15	PRESIDENTE HAYES	4	VILLA HAYES	6	670	COM INDIG NARANHATY
1504690	15	PRESIDENTE HAYES	4	VILLA HAYES	6	690	ZONA BUENA VISTA
1504700	15	PRESIDENTE HAYES	4	VILLA HAYES	6	700	ZONA ESTANCIA VILLA REY
1504710	15	PRESIDENTE HAYES	4	VILLA HAYES	6	710	ASENT. SAN ROQUE
1504720	15	PRESIDENTE HAYES	4	VILLA HAYES	6	720	SAN ROQUE
1504730	15	PRESIDENTE HAYES	4	VILLA HAYES	6	730	ASENT. KILOMETRO 25
1504740	15	PRESIDENTE HAYES	4	VILLA HAYES	6	740	ASENT. SAN RAMON
1504750	15	PRESIDENTE HAYES	4	VILLA HAYES	6	750	ASENT. INMACULADA
1504760	15	PRESIDENTE HAYES	4	VILLA HAYES	6	760	COM INDIG KENKUKET
1504770	15	PRESIDENTE HAYES	4	VILLA HAYES	6	770	ZONA PUERTO MILITAR
1504780	15	PRESIDENTE HAYES	4	VILLA HAYES	6	780	COM INDIG LA HERENCIA - PALO AZUL
1504790	15	PRESIDENTE HAYES	4	VILLA HAYES	6	790	COM INDIG LA HERENCIA - 3 LAGUNAS
1504800	15	PRESIDENTE HAYES	4	VILLA HAYES	6	800	COM INDIG MAKXAWAYA - ISLA MAINUMBY
1505001	15	PRESIDENTE HAYES	5	NANAWA	1	1	SAN MIGUEL
1505002	15	PRESIDENTE HAYES	5	NANAWA	1	2	QUINTA
1505003	15	PRESIDENTE HAYES	5	NANAWA	1	3	VIRGEN DEL ROSARIO
1505004	15	PRESIDENTE HAYES	5	NANAWA	1	4	SAN ANTONIO
1505005	15	PRESIDENTE HAYES	5	NANAWA	1	5	CENTRAL
1505006	15	PRESIDENTE HAYES	5	NANAWA	1	6	INDEPENDIENTE
1505007	15	PRESIDENTE HAYES	5	NANAWA	1	7	ORIENTAL
1505008	15	PRESIDENTE HAYES	5	NANAWA	1	8	8 DE DICIEMBRE
1505009	15	PRESIDENTE HAYES	5	NANAWA	1	9	SPORTIVO PUERTO ELSA
1506001	15	PRESIDENTE HAYES	6	JOSE FALCON	1	1	SANTA ROSA
1506002	15	PRESIDENTE HAYES	6	JOSE FALCON	1	2	8 DE DICIEMBRE
1506100	15	PRESIDENTE HAYES	6	JOSE FALCON	6	100	ZONA DE NINFA
1506105	15	PRESIDENTE HAYES	6	JOSE FALCON	3	105	ZONA DE NINFA - SUB-URBANO
1506120	15	PRESIDENTE HAYES	6	JOSE FALCON	6	120	ZONA DE ESPARTILLO - COLONIA 12 DE JUNIO
1506130	15	PRESIDENTE HAYES	6	JOSE FALCON	6	130	ZONA DE GANADERA ESPINILLO
1506140	15	PRESIDENTE HAYES	6	JOSE FALCON	6	140	ZONA PARIRI
1506180	15	PRESIDENTE HAYES	6	JOSE FALCON	6	180	SANTA ROSA
1506190	15	PRESIDENTE HAYES	6	JOSE FALCON	6	190	8 DE DICIEMBRE
1506200	15	PRESIDENTE HAYES	6	JOSE FALCON	6	200	KARANDAYTY
1506210	15	PRESIDENTE HAYES	6	JOSE FALCON	6	210	SAN RAMON
1506220	15	PRESIDENTE HAYES	6	JOSE FALCON	6	220	LAS MERCEDES
1506230	15	PRESIDENTE HAYES	6	JOSE FALCON	6	230	SAN IGNACIO
1506240	15	PRESIDENTE HAYES	6	JOSE FALCON	6	240	SAN ROQUE GONZALEZ
1506250	15	PRESIDENTE HAYES	6	JOSE FALCON	3	250	SAN FRANCISCO SUB-URBANO
1507001	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	1	1	LA PIEDAD 25 LEGUAS
1507002	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	1	2	ROSA MISTICA
1507100	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	100	ALDEA BALDROBE
1507105	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	105	ALDEA BALTALM
1507110	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	110	ALDEA NEUHOF
1507115	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	115	ALDEA NOELANGER
1507120	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	120	ALDEA TIEGRE
1507125	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	125	ALEGRIA
1507130	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	130	ALTONA
1507135	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	135	AVALOS SANCHEZ NU PYAHU
1507140	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	140	BLUMENTAL
1507145	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	145	BUENA VISTA
1507150	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	150	CAMPO ACEVAL - CRISTO REY
1507155	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	3	155	CAMPO ACEVAL SUB - URBANO
1507160	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	160	CAMPO LEON
1507165	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	165	CAMPO ROSA
1507170	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	170	CAMPO VIA
1507175	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	175	CAMPO VIA SAN ANTONIO
1507180	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	180	COLONIA 10
1507185	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	185	COLONIA 22
1507190	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	190	COLONIA FALCON
1507195	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	195	COLONIA RANDAU
1507200	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	200	COM INDIG ANACONDA
1507205	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	205	COM INDIG CAMPO LARGO - 5 DE MAYO
1507210	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	210	COM INDIG CAMPO LARGO
1507215	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	215	COM INDIG CAMPO LARGO - CAMPO ARANA
1507220	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	220	COM INDIG CAMPO LARGO  -CAMPO BAJO
1507221	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	221	COM INDIG CAMPO LARGO - POZO NEGRO
1507225	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	225	COM INDIG CASANILLO
1507230	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	230	COM INDIG CASANILLO-CAMPO AROMA
1507235	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	235	COM INDIG CASANILLO-CAPIATA
1507240	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	240	COM INDIG CASANILLO-LINDA VISTA
1507245	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	245	COM INDIG CASANILLO-SAN RAFAEL
1507250	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	250	COM INDIG DIEZ LEGUAS
1507255	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	255	COM INDIG DIEZ LEGUAS- 12 DE JUNIO
1507260	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	260	COM INDIG DIEZ LEGUAS-KARANTILLA
1507265	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	265	COM INDIG DIEZ LEGUAS-MARTILLO
1507270	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	270	COM INDIG DIEZ LEGUAS-PALO BLANCO
1507275	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	275	COM INDIG EL ESTRIBO-20 DE ENERO
1507280	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	280	COM INDIG EL ESTRIBO-ALEGRE
1507285	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	285	COM INDIG EL ESTRIBO-DOS PALMAS
1507290	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	290	COM INDIG EL ESTRIBO-KARANDA
1507295	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	295	COM INDIG EL ESTRIBO-LA MADRINA
1507300	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	300	COM INDIG EL ESTRIBO-PALO SANTO
1507305	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	305	COM INDIG EL ESTRIBO-PARATODO'I
1507310	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	310	COM INDIG EL ESTRIBO-SAN CARLOS
1507315	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	315	COM INDIG EL ESTRIBO-SANTA FE
1507320	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	320	COM INDIG EL ESTRIBO-TRES TAMARINO
1507325	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	325	COM INDIG KARANDA'Y PUKU
1507330	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	330	COM INDIG LA ABUNDANCIA
1507335	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	335	COM INDIG LA ARMONIA-ALDEA 1
1507340	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	340	COM INDIG LA ARMONIA-ALDEA 2
1507345	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	345	COM INDIG LA ARMONIA-ALDEA 3
1507350	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	350	COM INDIG LA ARMONIA-ALDEA 5
1507355	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	355	COM INDIG LA ARMONIA-ALDEA 4
1507360	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	360	COM INDIG LA ARMONIA-ALDEA 7
1507365	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	365	COM INDIG LA ESPERANZA
1507370	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	370	COM INDIG NICH'A TOYISH-12 DE JUNIO
1507375	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	375	COM INDIG NICH'A TOYISH-12 DE OCTUBRE
1507380	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	380	COM INDIG NICH'A TOYISH-14 DE MAYO
1507385	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	385	COM INDIG NICH'A TOYISH-19 DE ABRIL
1507390	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	390	COM INDIG NICH'A TOYISH-1RO DE MARZO
1507395	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	395	ZONA RANCHO 8
1507400	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	400	COM INDIG NICH'A TOYISH-1RO DE MAYO
1507405	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	405	COM INDIG NICH'A TOYISH-BOQUERON
1507410	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	410	COM INDIG NICH'A TOYISH-MACEDONIA
1507415	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	415	COM INDIG NICH'A TOYISH-PAZ DEL CHACO
1507420	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	420	COM INDIG NICH'A TOYISH-PRIMAVERA
1507425	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	425	COM INDIG NOVOCTAS - CENTRO
1507430	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	430	COM INDIG NUEVA PROMESA-ALDEA 1
1507435	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	435	COM INDIG NUEVA PROMESA-ALDEA 2
1507440	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	440	COM INDIG NUEVA PROMESA-ALDEA 3
1507445	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	445	COM INDIG NUEVA PROMESA-ALDEA 4
1507450	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	450	COM INDIG NUEVA PROMESA-ALDEA 6
1507455	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	455	COM INDIG NUEVA VIDA
1507460	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	460	COM INDIG PARA TODO
1507465	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	465	COM INDIG PAZ DEL CHACO - UNIDA
1507470	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	470	COM INDIG POZO AMARILLO - CENTRO
1507475	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	475	COM INDIG POZO AMARILLO-NUEVA UNION
1507480	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	480	COM INDIG POZO AMARILLO-TOBATI
1507485	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	485	COM INDIG PAZ DEL CHACO - TERRENAL
1507490	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	490	COM INDIG DIEZ LEGUAS-VISTA ALEGRE
1507495	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	495	COM INDIG NIVACLE UNIDA - BETANIA
1507500	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	500	COM INDIG NIVACLE UNIDA - CANA
1507505	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	505	COM INDIG NIVACLE UNIDA-JERICO
1507510	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	510	COM INDIG NIVACLE UNIDA-JOPE
1507515	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	515	ZONA CRUCE PIONEROS
1507520	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	3	520	CRUCE PIONEROS SUB-URBANO
1507530	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	530	FATIMA
1507535	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	535	FORTIN BOQUERON
1507540	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	540	HISMTAET
1507545	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	545	HOHENAU
1507550	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	550	ISLA PO'I
1507555	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	555	KLENHOF
1507560	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	560	LA PASTURA
1507565	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	565	LAGUNA CAPITAN
1507570	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	570	LAGUNA LEON
1507575	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	575	LAGUNITA
1507580	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	580	LIDENDORF
1507585	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	3	585	LOLITA SUB-URBANO
1507590	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	590	ZONA LOLITA
1507595	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	595	MOLINO
1507600	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	600	MONTE VERDE
1507605	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	605	NARANJAL
1507610	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	3	610	PARA TODO SUB-URBANO
1507615	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	615	ZONA PARATODO
1507620	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	620	REINHOF
1507625	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	625	RIO VERDE
1507630	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	630	SAN BLAS
1507635	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	635	SAN JOSE OBRERO
1507640	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	640	SAN MIGUEL
1507645	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	645	SANTA AURELIA
1507650	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	650	SANTA CECILIA
1507655	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	655	SO'O KANGUE'I
1507660	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	660	ZONA ROSA MISTICA
1507665	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	665	ZONA ZALAZAR
1507670	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	670	VALENCIA
1507675	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	675	VILLA FLORIDA
1507680	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	680	ZONA AVALOS SANCHEZ
1507685	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	685	ZONA CAMPO ACEVAL
1507690	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	690	ZONA CRUCE DOUGLAS
1507695	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	695	ZONA GONDRA
1507700	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	700	ZONA GRAL. DIAZ
1507705	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	705	ZONA LAGUNA PORA
1507710	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	710	LOMA VERDE
1507720	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	720	COM INDIG NOVOCTAS - MARCELO KUE
1507725	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	725	COM INDIG NUEVA PROMESA-ALDEA 5
1507755	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	755	COM INDIG CASANILLO- 3 PALMAS
1507765	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	765	COM INDIG LA ESPERANZA - ALDEA 6
1507766	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	766	COM INDIG LA ESPERANZA - ALDEA 4
1507767	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	767	COM INDIG LA ESPERANZA - ALDEA 5
1507770	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	770	COM INDIG LA ESPERANZA - ALDEA 7
1507775	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	775	COM INDIG POZO AMARILLO-ROJAS SILVA
1507780	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	780	COM INDIG POZO AMARILLO-CHACO'I
1507785	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	785	COM INDIG POZO AMARILLO-COLONIA 1
1507790	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	790	COM INDIG POZO AMARILLO-4 DE AGOSTO
1507791	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	791	COM INDIG POZO AMARILLO-CARPA KUE
1507792	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	792	COM INDIG POZO AMARILLO-NATEM POME
1507795	15	PRESIDENTE HAYES	7	TTE. 1� MANUEL IRALA FERNANDEZ	6	795	COM INDIG POZO AMARILLO-ARARAT
1508001	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	1	1	CENTRO
1508100	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	6	100	COM INDIG FISCHAT (SAN LEONARDO)
1508110	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	6	110	ZONA TTE ROJAS SILVA
1508115	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	3	115	TTE. ROJAS SILVA SUB-URBANO
1508120	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	6	120	ZONA ESTEBAN MARTINEZ
1508130	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	6	130	ZONA SAN ISIDRO
1508140	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	6	140	ZONA FORTIN CABALLERO
1605220	16	BOQUERON	5	LOMA PLATA	6	220	ALDEA SIBERTEL
1508145	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	3	145	FORTIN CABALLERO SUB-URBANO
1508150	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	6	150	ZONA CABO CANO
1508160	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	6	160	COM INDIG CACIQUE SAPO
1508170	15	PRESIDENTE HAYES	8	TENIENTE ESTEBAN MARTINEZ	6	170	ZONA GENERAL DIAZ
1509001	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	1	1	CENTRO
1509002	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	1	2	SAN AGUSTIN
1509220	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	220	ZONA CADETE PANDO
1509225	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	3	225	CADETE PANDO SUB-URBANO
1509230	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	230	COLONIA 12 DE JUNIO
1509240	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	240	COM INDIG KEM HA YAT SEPO - LA ESPERANZA
1509250	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	250	COM INDIG LA ESPERANZA - LA ALTURA
1509260	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	260	COM INDIG LA ESPERANZA - CENTRO
1509270	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	270	COM INDIG LA ESPERANZA - LA PROMESA
1509280	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	280	COM INDIG SAN JOSE
1509290	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	290	COM INDIG LA ESPERANZA - TAPITI
1509300	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	300	COM INDIG TOOSHEC QALTAC
1509310	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	310	ZONA CABO TALAVERA
1509320	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	320	ZONA ESTERO GUASU
1509330	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	330	ZONA LA ESPERANZA
1509340	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	340	ZONA TRIANGULO
1509350	15	PRESIDENTE HAYES	9	GENERAL JOSE MARIA BRUGUEZ	6	350	COM INDIG LA ESPERANZA - KARAGUATA POTY
1602001	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	1	VILLA MILITAR
1602002	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	2	SANTA MARIA
1602003	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	3	SAN JUAN
1602004	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	4	SAN ANTONIO
1602005	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	5	SAGRADA FAMILIA
1602007	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	7	MARIA AUXILIADORA
1602008	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	8	COM INDIG SANTA TERESITA
1602009	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	9	COM INDIG SANTA TERESITA - SANTA ROSA
1602010	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	10	COM INDIG SANTA TERESITA - SANTA ELENA
1602011	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	11	COM INDIG SANTA TERESITA - SAN JOSE
1602012	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	12	COM INDIG SANTA TERESITA - VIRGEN DEL CARMEN
1602013	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	13	COM INDIG SANTA TERESITA - VILLA BELEN
1602014	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	14	COM INDIG SANTA TERESITA - VIRGEN DE CAACUPE
1602015	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	15	COM INDIG SANTA TERESITA - SANTA MARIA
1602016	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	16	COM INDIG SANTA TERESITA - SANTA LUCIA
1602017	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	17	COM INDIG SANTA TERESITA - MARIA AUXILIADORA
1602018	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	18	COM INDIG SANTA TERESITA - SANTA ISABEL
1602019	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	19	COM INDIG SANTA TERESITA - VIRGEN DEL ROSARIO
1602020	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	20	COM INDIG SANTA TERESITA - SANTA CECILIA
1602021	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	1	21	COM INDIG SANTA TERESITA - SAN JUAN
1602100	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	100	ZONA GENERAL GARAY
1602105	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	105	COM INDIG FISCHAT (SAN LEONARDO)
1602110	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	110	COM INDIG PYKASU
1602115	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	115	COM INDIG MEDIA LUNA
1602120	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	120	COM INDIG SIRACUA
1602125	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	125	COM INDIG LAGUNA NEGRA-JERUSALEN
1602130	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	130	COM INDIG NU GUAZU
1602135	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	135	COM INDIG CAMPO LOA - SAN MIGUEL
1602140	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	140	ZONA LA PATRIA
1602145	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	145	COM INDIG CAMPO ALEGRE - ALDEA 1
1602150	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	150	ZONA TENIENTE PICO
1602155	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	155	COM INDIG CAMPO ALEGRE - ALDEA 8
1602160	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	160	ZONA MAYOR INFANTE RIVAROLA
1602165	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	165	COM INDIG CAMPO ALEGRE - ALDEA 5
1602170	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	170	ZONA HERNANDARIAS
1602175	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	175	COM INDIG CAMPO ALEGRE - ALDEA 10
1602180	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	180	COM INDIG SANTA ROSA
1602185	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	185	COM INDIG CAMPO ALEGRE - ALDEA 4
1602190	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	190	ZONA OCHOA
1602200	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	200	ZONA POZO HONDO
1602205	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	3	205	ZONA POZO HONDO SUB-URBANO
1602210	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	210	COM INDIG YASY'ENDY
1602215	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	215	COM INDIG CACIQUE SAPO
1602220	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	220	CRUCE DON SILVIO
1602230	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	230	ZONA  PRATTS GILL
1602240	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	240	ZONA CAMPO KAREN
1602250	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	250	ZONA LA ROSALEDA
1602260	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	260	ESCUELA AGRICOLA
1602270	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	270	ZONA REMONIA
1602280	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	280	ZONA PEDRO P PENA
1602290	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	290	COM INDIG SAN AGUSTIN - LAGUNA
1602300	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	300	COM INDIG SAN AGUSTIN - CRISTO REY
1602310	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	310	COM INDIG SAN AGUSTIN - SAN EUGENIO
1602311	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	311	COM INDIG SAN AGUSTIN - SAGRADO CORAZON DE JESUS
1602312	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	312	COM INDIG SAN AGUSTIN - SAN ROQUE
1602313	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	313	COM INDIG SAN AGUSTIN - SAN JOSE
1602320	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	320	COM INDIG SAN AGUSTIN - MARIA AUXILIADORA
1602330	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	330	ZONA PELICANO
1602340	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	340	ZONA JOEL ESTIGARRIBIA
1602350	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	350	ZONA MBUTURETA
1602360	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	360	LAGUNA NEGRA
1602370	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	370	COM INDIG LAGUNA NEGRA - TIMOTEO
1602375	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	375	COM INDIG LAGUNA NEGRA - EMAUS
1602380	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	380	COM INDIG LAGUNA NEGRA - NUEVA  ESTRELLA
1602390	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	390	COM INDIG LAGUNA NEGRA - KO'E PYAHU
1602400	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	400	COM INDIG LAGUNA NEGRA-DAMASCO
1602410	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	410	COM INDIG LAGUNA NEGRA-CANAAN
1602415	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	415	COM INDIG LAGUNA NEGRA - MBYJA KO'E
1602420	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	420	COM INDIG MACHARETI
1602430	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	430	COM INDIG LAGUNA NEGRA - BELEN
1602440	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	440	COM INDIG CAMPO LOA - STMA. TRINIDAD
1602450	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	450	COM INDIG CAMPO LOA - JOTOICHA
1602460	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	460	COM INDIG CAMPO LOA - SAN PIO 10
1602470	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	470	COM INDIG CAMPO LOA - SAN RAMON
1602480	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	480	COM INDIG CAMPO LOA - PRIMAVERA
1602490	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	490	COM INDIG CAMPO LOA - NASUC
1602500	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	500	ZONA EL SOLITARIO
1602510	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	510	MISTOLAR
1602520	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	520	COM INDIG MISTOLAR
1602530	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	530	ZONA AGROPIL
1602535	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	535	COM INDIG CAMPO AMPU
1602540	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	540	ZONA AYALA VELAZQUEZ
1602550	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	550	DEMATTEI
1602560	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	560	PIRIZAL
1602570	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	570	COM INDIG LA PRINCESA
1602580	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	580	BUZARQUIS PLATANILLO
1602590	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	590	COM INDIG YACACVASH
1602600	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	600	COM INDIG PARAISO
1602610	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	610	COLONIA I
1602620	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	620	COLONIA 50
1602635	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	3	635	VILLA CHOFERES DEL CHACO SUB-URBANO
1602640	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	640	COLONIA 52
1602650	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	650	SANDHORST
1602660	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	660	COM INDIG SANDHORST
1602670	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	670	NEULAND
1602675	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	3	675	NEULAND SUB-URBANO
1602680	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	680	COM INDIG CAYI'O CLIN
1602700	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	700	ZONA MARGARINO
1602710	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	710	COM INDIG QUENJACLOI
1602720	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	720	VIRGEN DEL ROSARIO
1602730	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	730	ZONA CAMPO VIA
1602740	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	740	ZONA CAMPO GRANDE
1602750	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	750	COM INDIG CAMPO LARGO 6 DE OCTUBRE
1602760	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	760	COM INDIG CASUARINA - LA SERENA
1602770	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	770	COM INDIG CASUARINA - LA PROMESA
1602775	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	775	COM INDIG CASUARINA - LA CORONA
1602780	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	780	COM INDIG CASUARINA - CAMPO VIRGEN
1602785	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	785	COM INDIG CASUARINA - CAMPO GRANDE
1602790	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	790	COM INDIG CAMPO ALEGRE - ALDEA 3
1602800	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	800	COM INDIG CAMPO ALEGRE - ALDEA 2
1602810	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	810	COM INDIG CAMPO ALEGRE - LAGUNA VERDE
1602820	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	820	COM INDIG CAMPO ALEGRE - ALDEA 6
1602830	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	830	COM INDIG CAMPO ALEGRE - ALDEA 7
1602840	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	840	COM INDIG CAMPO ALEGRE - ALDEA 9
1602910	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	910	VIRGEN DE FATIMA
1602920	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	920	COM INDIG YISHINACHAT
1602930	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	930	ZONA SANTA MARTA
1602940	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	940	COM INDIG SANTA MARTA
1602950	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	950	ZONA 4 VIENTOS
1602960	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	960	ZONA AVALOS SANCHEZ
1602970	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	970	GENERAL DIAZ
1602980	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	980	COM INDIG PABLO STHALL
1602990	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	990	COM INDIG SAN JOSE ESTEROS
1602995	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	995	COM INDIG CUYABIA
1602996	16	BOQUERON	2	MARISCAL JOSE FELIX ESTIGARRIBIA	6	996	COM INDIG LAGUNA NEGRA - NUEVA LUNA
1604001	16	BOQUERON	4	FILADELFIA	1	1	LA AMISTAD
1604002	16	BOQUERON	4	FILADELFIA	1	2	CENTRO
1604003	16	BOQUERON	4	FILADELFIA	1	3	INDUSTRIAL
1604004	16	BOQUERON	4	FILADELFIA	1	4	COM INDIG YVOPEY RENDA (BARRIO GUARANI)
1604005	16	BOQUERON	4	FILADELFIA	1	5	COM INDIG UJ'ELHAVOS
1604006	16	BOQUERON	4	FILADELFIA	1	6	COM INDIG CACIQUE MAYETO
1604007	16	BOQUERON	4	FILADELFIA	1	7	FLORIDA
1604100	16	BOQUERON	4	FILADELFIA	6	100	15 DE AGOSTO
1604110	16	BOQUERON	4	FILADELFIA	6	110	CAMPO LORO
1604120	16	BOQUERON	4	FILADELFIA	6	120	COLONIA 22
1604130	16	BOQUERON	4	FILADELFIA	6	130	FRIEDENSFELD
1604140	16	BOQUERON	4	FILADELFIA	6	140	COM INDIG JOBASUI
1604150	16	BOQUERON	4	FILADELFIA	6	150	COM INDIG 10 DE FEBRERO
1604160	16	BOQUERON	4	FILADELFIA	6	160	COM INDIG 10 DE JUNIO
1604170	16	BOQUERON	4	FILADELFIA	6	170	COM INDIG 15 DE SETIEMBRE
1604180	16	BOQUERON	4	FILADELFIA	6	180	COM INDIG 2 DE ENERO
1604190	16	BOQUERON	4	FILADELFIA	6	190	COM INDIG CAMPO LORO
1604200	16	BOQUERON	4	FILADELFIA	6	200	COM INDIG COLONIA 22
1604210	16	BOQUERON	4	FILADELFIA	6	210	COM INDIG EBETOGUE
1604220	16	BOQUERON	4	FILADELFIA	6	220	COM INDIG IJNAPUI
1604230	16	BOQUERON	4	FILADELFIA	6	230	COM INDIG JESUDI
1604240	16	BOQUERON	4	FILADELFIA	6	240	COM INDIG LA ESQUINA
1604250	16	BOQUERON	4	FILADELFIA	6	250	COM INDIG COLONIA 5 - OBRERO
1604260	16	BOQUERON	4	FILADELFIA	6	260	COM INDIG OLERIA TREBOL
1604270	16	BOQUERON	4	FILADELFIA	6	270	COM INDIG SAN LOEWEN
1604280	16	BOQUERON	4	FILADELFIA	6	280	COM INDIG SAN MARTIN
1604290	16	BOQUERON	4	FILADELFIA	6	290	COM INDIG SANTO DOMINGO
1604300	16	BOQUERON	4	FILADELFIA	6	300	COM INDIG TUNUCOJAI
1604310	16	BOQUERON	4	FILADELFIA	6	310	CRUCE FILADELFIA
1604320	16	BOQUERON	4	FILADELFIA	6	320	FERNHEIN COLONIA 7
1604330	16	BOQUERON	4	FILADELFIA	6	330	FILADELFIA NORTE
1604340	16	BOQUERON	4	FILADELFIA	6	340	FRIEDENSRUH
1604350	16	BOQUERON	4	FILADELFIA	6	350	GNADENIHEIM
1604360	16	BOQUERON	4	FILADELFIA	6	360	KARLSRUHE
1604370	16	BOQUERON	4	FILADELFIA	6	370	LICHTFELDE
1604380	16	BOQUERON	4	FILADELFIA	6	380	ORLOFF
1604390	16	BOQUERON	4	FILADELFIA	6	390	BLUMENORT
1604400	16	BOQUERON	4	FILADELFIA	6	400	SCHONAU
1604410	16	BOQUERON	4	FILADELFIA	6	410	SCHONBRUNN
1604420	16	BOQUERON	4	FILADELFIA	6	420	PRIMAVERA
1604430	16	BOQUERON	4	FILADELFIA	6	430	WALDESRUH
1604440	16	BOQUERON	4	FILADELFIA	6	440	ZONA KALAL
1604450	16	BOQUERON	4	FILADELFIA	6	450	ZONA TENIENTE MARTINEZ
1604460	16	BOQUERON	4	FILADELFIA	6	460	ZONA TENIENTE MONTANIA
1604470	16	BOQUERON	4	FILADELFIA	6	470	ZONA TENIENTE PICO
1605001	16	BOQUERON	5	LOMA PLATA	1	1	CASCO URBANO
1605100	16	BOQUERON	5	LOMA PLATA	6	100	ALDEA BLUMENGART
1605110	16	BOQUERON	5	LOMA PLATA	6	110	ALDEA CHORTITZER
1605120	16	BOQUERON	5	LOMA PLATA	6	120	ALDEA EBENFELT
1605130	16	BOQUERON	5	LOMA PLATA	6	130	ALDEA FRIEDENSFELD
1605140	16	BOQUERON	5	LOMA PLATA	6	140	ALDEA HALBSTADT
1605150	16	BOQUERON	5	LOMA PLATA	6	150	ALDEA HOCHFELD
1605160	16	BOQUERON	5	LOMA PLATA	6	160	ALDEA KLEISTADT
1605170	16	BOQUERON	5	LOMA PLATA	6	170	ALDEA LICHTFELD
1605180	16	BOQUERON	5	LOMA PLATA	6	180	ALDEA OSTERWICK
1605190	16	BOQUERON	5	LOMA PLATA	6	190	ALDEA REINLAND
1605200	16	BOQUERON	5	LOMA PLATA	6	200	ALDEA ROSENFAL
1605230	16	BOQUERON	5	LOMA PLATA	6	230	ALDEA STRASSBERG
1605240	16	BOQUERON	5	LOMA PLATA	6	240	COLONIA GNADENFELT
1605250	16	BOQUERON	5	LOMA PLATA	6	250	COLONIA KLEEFELT
1605260	16	BOQUERON	5	LOMA PLATA	6	260	COM INDIG OLERIA LOMA PLATA
1605270	16	BOQUERON	5	LOMA PLATA	6	270	COM INDIG PESEMPO'O
1605280	16	BOQUERON	5	LOMA PLATA	6	280	COM INDIG YA'ALVE SAANGA -10 DE AGOSTO
1605290	16	BOQUERON	5	LOMA PLATA	6	290	COM INDIG YA'ALVE SAANGA - CENTRO
1605300	16	BOQUERON	5	LOMA PLATA	6	300	COM INDIG NIVACLE UNIDA - BETANIA
1605310	16	BOQUERON	5	LOMA PLATA	6	310	COM INDIG YA'ALVE SAANGA - CAACUPE
1605320	16	BOQUERON	5	LOMA PLATA	6	320	COM INDIG YA'ALVE SAANGA - CAMPO BELLO
1605330	16	BOQUERON	5	LOMA PLATA	6	330	COM INDIG NIVACLE UNIDA - CAMPO NUEVO
1605340	16	BOQUERON	5	LOMA PLATA	6	340	COM INDIG NIVACLE UNIDA - CAMPO SALADO
1605350	16	BOQUERON	5	LOMA PLATA	6	350	COM INDIG YA'ALVE SAANGA - KANAVSA
1605360	16	BOQUERON	5	LOMA PLATA	6	360	COM INDIG NIVACLE UNIDA - CESAREA
1605370	16	BOQUERON	5	LOMA PLATA	6	370	COM INDIG YA'ALVE SAANGA - EFESO
1605380	16	BOQUERON	5	LOMA PLATA	6	380	COM INDIG NIVACLE UNIDA - GALILEA
1605390	16	BOQUERON	5	LOMA PLATA	6	390	COM INDIG NIVACLE UNIDA - JERICO
1605410	16	BOQUERON	5	LOMA PLATA	6	410	COM INDIG YA'ALVE SAANGA-NAOK AMYEP
1605420	16	BOQUERON	5	LOMA PLATA	6	420	COM INDIG YA'ALVE SAANGA-NAZARETH
1605430	16	BOQUERON	5	LOMA PLATA	6	430	COM INDIG NIVACLE UNIDA-SAMARIA
1605435	16	BOQUERON	5	LOMA PLATA	3	435	SAN MIGUEL SUB-URBANO
1605440	16	BOQUERON	5	LOMA PLATA	6	440	COM INDIG YA'ALVE SAANGA-TARSO AMYEP
1605450	16	BOQUERON	5	LOMA PLATA	6	450	COM INDIG NIVACLE UNIDA-TIBERIA
1605460	16	BOQUERON	5	LOMA PLATA	6	460	COM INDIG NIVACLE UNIDA - CENTRO
1605463	16	BOQUERON	5	LOMA PLATA	6	463	COM INDIG YA'ALVE SAANGA - MARISCAL LOPEZ
1605470	16	BOQUERON	5	LOMA PLATA	6	470	LENDENOUF
1605480	16	BOQUERON	5	LOMA PLATA	6	480	LOBENHAIN
1605490	16	BOQUERON	5	LOMA PLATA	6	490	LOMA PLATA PERIFERIA
1605500	16	BOQUERON	5	LOMA PLATA	6	500	POZO GRANDE
1605510	16	BOQUERON	5	LOMA PLATA	6	510	REGION 180
1605530	16	BOQUERON	5	LOMA PLATA	6	530	VILLA BOQUERON
1605535	16	BOQUERON	5	LOMA PLATA	3	535	VILLA BOQUERON SUB-URBANO
1605540	16	BOQUERON	5	LOMA PLATA	6	540	ZONA KALAL
1605690	16	BOQUERON	5	LOMA PLATA	6	690	COM INDIG YA'ALVE SAANGA - SAVAAYA  AMYEP
1605691	16	BOQUERON	5	LOMA PLATA	6	691	COM INDIG YA'ALVE SAANGA - MADIAN
1605692	16	BOQUERON	5	LOMA PLATA	6	692	COM INDIG YA'ALVE SAANGA - SETESVES
1701001	17	ALTO PARAGUAY	1	FUERTE OLIMPO	1	1	SAN MIGUEL
1701002	17	ALTO PARAGUAY	1	FUERTE OLIMPO	1	2	MARIA AUXILIADORA
1701003	17	ALTO PARAGUAY	1	FUERTE OLIMPO	1	3	SAN BLAS
1701004	17	ALTO PARAGUAY	1	FUERTE OLIMPO	1	4	DON BOSCO
1701130	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	130	ZONA CORRALITO
1701140	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	140	ZONA JAGUARETE KORA
1701150	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	150	ZONA RIBERA OLIMPO
1701160	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	160	ZONA TORO PAMPA ESTE
1701170	17	ALTO PARAGUAY	1	FUERTE OLIMPO	3	170	SAN CARLOS SUB-URBANO
1701180	17	ALTO PARAGUAY	1	FUERTE OLIMPO	3	180	TORO PAMPA SUB-URBANO
1701190	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	190	ZONA FLORIDA
1701200	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	200	ZONA TTE. MARTINEZ
1701210	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	210	MARIA AUXILIADORA
1701220	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	220	COM INDIG PUERTO MARIA ELENA (PITIANTUTA)
1701230	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	230	ZONA TORO PAMPA OESTE
1701240	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	240	COM INDIG VIRGEN SANTISIMA
1701250	17	ALTO PARAGUAY	1	FUERTE OLIMPO	6	250	COM INDIG LA  ABUNDANCIA
1702001	17	ALTO PARAGUAY	2	PUERTO CASADO	1	1	SANTA TERESITA
1702002	17	ALTO PARAGUAY	2	PUERTO CASADO	1	2	DON BOSCO
1702003	17	ALTO PARAGUAY	2	PUERTO CASADO	1	3	MARIA AUXILIADORA
1702004	17	ALTO PARAGUAY	2	PUERTO CASADO	1	4	SAN MIGUEL
1702005	17	ALTO PARAGUAY	2	PUERTO CASADO	1	5	SAN JUAN
1702006	17	ALTO PARAGUAY	2	PUERTO CASADO	1	6	SAN RAMON
1702007	17	ALTO PARAGUAY	2	PUERTO CASADO	1	7	SAN ANTONIO
1702008	17	ALTO PARAGUAY	2	PUERTO CASADO	1	8	COM INDIG LIVIO FARINA
1702170	17	ALTO PARAGUAY	2	PUERTO CASADO	6	170	ZONA MONTANIA
1702180	17	ALTO PARAGUAY	2	PUERTO CASADO	6	180	LAS PALMAS -REGION 180
1702190	17	ALTO PARAGUAY	2	PUERTO CASADO	6	190	COM INDIG CHAIDI
1702200	17	ALTO PARAGUAY	2	PUERTO CASADO	6	200	COM INDIG GARAI
1702210	17	ALTO PARAGUAY	2	PUERTO CASADO	6	210	COM INDIG COMUNIDAD 5
1702220	17	ALTO PARAGUAY	2	PUERTO CASADO	6	220	ZONA RIEDER
1702230	17	ALTO PARAGUAY	2	PUERTO CASADO	6	230	ZONA PUERTO GUARANI
1702240	17	ALTO PARAGUAY	2	PUERTO CASADO	6	240	ASENT. SAN ROQUE
1702250	17	ALTO PARAGUAY	2	PUERTO CASADO	6	250	ZONA CENTINELA
1702260	17	ALTO PARAGUAY	2	PUERTO CASADO	6	260	COM INDIG AROCOJNADI
1702270	17	ALTO PARAGUAY	2	PUERTO CASADO	6	270	ZONA 22 PARAGRO
1702280	17	ALTO PARAGUAY	2	PUERTO CASADO	6	280	ZONA PUERTO CASADO
1702290	17	ALTO PARAGUAY	2	PUERTO CASADO	6	290	COM INDIG MARIA AUXILIADORA KM 40
1702300	17	ALTO PARAGUAY	2	PUERTO CASADO	6	300	COM INDIG SAN ISIDRO KM 39
1702310	17	ALTO PARAGUAY	2	PUERTO CASADO	6	310	COM INDIG CASTILLA
1702320	17	ALTO PARAGUAY	2	PUERTO CASADO	6	320	COM INDIG MACHETE VAINA
1702330	17	ALTO PARAGUAY	2	PUERTO CASADO	6	330	COM INDIG BOQUERON CUE
1702340	17	ALTO PARAGUAY	2	PUERTO CASADO	6	340	COM INDIG RIACHO MOSQUITO
1704001	17	ALTO PARAGUAY	4	BAHIA NEGRA	1	1	CENTRAL
1704100	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	100	COLONIA SAN ALFREDO
1704110	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	110	COM INDIG 14 DE MAYO KARCHABALUT
1704120	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	120	COM INDIG PUERTO DIANA
1704130	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	130	COM INDIG PUERTO ESPERANZA INIHTA
1704140	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	140	LAGERENZA 4 DE MAYO
1704150	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	150	LAGERENZA'I
1704160	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	160	SIERRA LEON
1704170	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	170	ZONA AGUA DULCE
1704180	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	180	ZONA BAHIA NEGRA POTY
1704190	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	190	ZONA YACYRETA
1704200	17	ALTO PARAGUAY	4	BAHIA NEGRA	6	200	COM INDIG PUERTO POLLO
1705001	17	ALTO PARAGUAY	5	CARMELO PERALTA	1	1	CAACUPE
1705002	17	ALTO PARAGUAY	5	CARMELO PERALTA	1	2	VIRGEN DEL CARMEN
1705003	17	ALTO PARAGUAY	5	CARMELO PERALTA	1	3	SAN BLAS
1705004	17	ALTO PARAGUAY	5	CARMELO PERALTA	1	4	SAN MIGUEL
1705100	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	100	ZONA RIBERA CARMELO PERALTA
1705110	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	110	COLONIA ANGEL MUZZOLON
1705130	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	130	COM INDIG GUIDA ICHAI
1705140	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	140	COM INDIG ISLA ALTA
1705150	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	150	COM INDIG NUEVA ESPERANZA
1705160	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	160	COM INDIG PUNTA
1705170	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	170	COM INDIG TIOGAI
1705180	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	180	COM INDIG CUCAANI
1705200	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	200	ISLA MARGARITA
1705210	17	ALTO PARAGUAY	5	CARMELO PERALTA	3	210	PUERTO GUARANI - SUB URBANO
1705220	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	220	PUERTO SASTRE - LA ESPERANZA
1705225	17	ALTO PARAGUAY	5	CARMELO PERALTA	3	225	PUERTO SASTRE - LA ESPERANZA SUB-URBANO
1705230	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	230	ZONA CRUCE A CARMELO PERALTA
1705240	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	240	COM INDIG ATAPI
1705250	17	ALTO PARAGUAY	5	CARMELO PERALTA	6	250	COM INDIG PUNTA EUEI
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
26	2014_10_10_144156_create_groups_table	1
27	2014_10_12_000000_create_users_table	1
28	2014_10_12_100000_create_password_resets_table	1
29	2019_04_10_015304_create_permission_tables	1
30	2019_10_22_144157_create_groups_has_members_table	1
31	2019_10_22_144158_create_organigramas_table	1
32	2019_11_05_131634_create_servicios_table	1
33	2019_11_05_131635_create_formulario_variables	1
34	2019_11_05_131805_create_formulario_items_table	1
35	2019_11_05_132430_create_formulario_fomularios	1
36	2019_11_08_163726_create_formulario_formulario_has_variables_table	1
37	2019_11_20_161833_create_formulario_clasificadores_table	1
38	2019_12_12_023319_create_foda_models_table	1
39	2019_12_12_190346_create_foda_perfiles_table	1
40	2019_12_12_190347_create_foda_categorias_has_perfil_table	1
41	2019_12_12_190348_create_foda_groups_has_perfiles_table	1
42	2020_06_17_153700_create_foda_analisis_table	1
43	2020_06_18_183606_create_foda_cruce_ambientes_table	1
44	2020_06_18_183607_create_foda_cruce_ambientes_has_fortalezas_table	1
45	2020_06_18_183608_create_foda_cruce_ambientes_has_oportunidades_table	1
46	2020_06_18_183609_create_foda_cruce_ambientes_has_debilidades_table	1
47	2020_06_18_183707_create_foda_cruce_ambientes_has_amenazas_table	1
48	2020_06_18_232949_create_pei_profiles_table	1
49	2020_06_18_232950_create_peis_profiles_has_dependencies_table	1
50	2020_06_18_232951_create_peis_profiles_has_analysts_table	1
51	2021_08_09_134713_create_e_p_c_especialidads_table	1
52	2021_08_09_172310_create_e_p_c_talento_humanos_table	1
53	2021_08_10_170616_create_e_p_c_equipamientos_table	1
54	2021_08_12_144458_create_e_p_c_infraestructuras_table	1
55	2021_08_12_174648_create_e_p_c_otro_servicios_table	1
56	2021_08_12_184529_create_e_p_c_medicamento_insumos_table	1
57	2021_08_19_183441_create_e_p_c_servicios_table	1
58	2021_08_23_173940_create_e_p_c_equipamientos_servicios_table	1
59	2021_08_24_151522_create_e_p_c_tthhs_servicios_table	1
60	2021_08_31_172332_create_e_p_c_infraestructura_servicio_table	1
61	2021_09_20_141836_create_e_p_c_horarios_table	1
62	2021_09_22_180347_create_e_p_c_turnos_table	1
63	2021_09_29_153922_create_e_p_c_prestaciones_table	1
64	2021_09_30_174709_create_e_p_c_otroservicio_has_servicios_table	1
65	2021_10_26_143121_create_e_p_c_estandars_table	1
66	2021_10_26_143424_create_e_p_c_estandars_servicios_table	1
67	2022_05_06_190702_create_risks_table	1
68	2022_10_12_173224_create_categories_table	1
69	2023_10_11_232634_create_typetasks_table	1
70	2023_10_12_140236_create_tasks_table	1
71	2023_10_12_211029_create_tasks_has_analysts_table	1
72	2023_10_12_211053_create_tasks_has_type_tasks_table	1
73	2023_10_29_024708_create_planificacion_pei_profiles_has_strategies	1
74	2023_10_29_170126_create_peis_profiles_has_responsibles_table	1
75	2024_02_07_180113_localities	1
76	2024_02_12_181321_create_patromonies_table	1
77	2024_03_07_174859_create_activities_table	1
117	2024_10_13_031534_create_surveys_table	2
118	2024_10_13_031535_create_participants_has_surveys_table	2
119	2024_10_13_033824_create_surveys_has_analysts_table	2
120	2024_10_14_004600_create_questions_table	2
121	2024_10_14_005800_create_answers_has_questions_table	2
122	2024_10_17_151637_create_answers_table	2
\.


--
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
1	App\\Models\\User	1
2	App\\Models\\User	2
2	App\\Models\\User	3
2	App\\Models\\User	7
2	App\\Models\\User	6
2	App\\Models\\User	8
2	App\\Models\\User	9
2	App\\Models\\User	11
3	App\\Models\\User	13
3	App\\Models\\User	14
3	App\\Models\\User	15
3	App\\Models\\User	16
3	App\\Models\\User	17
3	App\\Models\\User	18
3	App\\Models\\User	19
3	App\\Models\\User	20
3	App\\Models\\User	21
3	App\\Models\\User	22
3	App\\Models\\User	23
3	App\\Models\\User	24
3	App\\Models\\User	25
3	App\\Models\\User	26
3	App\\Models\\User	27
3	App\\Models\\User	28
3	App\\Models\\User	29
3	App\\Models\\User	30
3	App\\Models\\User	31
3	App\\Models\\User	32
3	App\\Models\\User	33
3	App\\Models\\User	34
3	App\\Models\\User	35
3	App\\Models\\User	36
3	App\\Models\\User	37
3	App\\Models\\User	38
3	App\\Models\\User	39
3	App\\Models\\User	40
3	App\\Models\\User	42
3	App\\Models\\User	43
3	App\\Models\\User	45
3	App\\Models\\User	46
3	App\\Models\\User	47
3	App\\Models\\User	48
3	App\\Models\\User	49
3	App\\Models\\User	50
3	App\\Models\\User	51
3	App\\Models\\User	52
3	App\\Models\\User	53
3	App\\Models\\User	54
3	App\\Models\\User	55
3	App\\Models\\User	56
3	App\\Models\\User	58
3	App\\Models\\User	59
3	App\\Models\\User	60
3	App\\Models\\User	61
3	App\\Models\\User	62
3	App\\Models\\User	63
3	App\\Models\\User	65
3	App\\Models\\User	66
3	App\\Models\\User	67
3	App\\Models\\User	68
3	App\\Models\\User	69
3	App\\Models\\User	70
3	App\\Models\\User	71
3	App\\Models\\User	72
3	App\\Models\\User	73
3	App\\Models\\User	74
3	App\\Models\\User	75
3	App\\Models\\User	78
3	App\\Models\\User	79
3	App\\Models\\User	80
3	App\\Models\\User	81
3	App\\Models\\User	82
3	App\\Models\\User	83
3	App\\Models\\User	84
3	App\\Models\\User	64
3	App\\Models\\User	76
2	App\\Models\\User	5
3	App\\Models\\User	77
3	App\\Models\\User	85
3	App\\Models\\User	87
3	App\\Models\\User	88
3	App\\Models\\User	89
3	App\\Models\\User	44
3	App\\Models\\User	90
3	App\\Models\\User	41
3	App\\Models\\User	91
3	App\\Models\\User	92
3	App\\Models\\User	93
3	App\\Models\\User	95
2	App\\Models\\User	94
1	App\\Models\\User	12
2	App\\Models\\User	12
2	App\\Models\\User	96
3	App\\Models\\User	86
3	App\\Models\\User	97
3	App\\Models\\User	98
3	App\\Models\\User	99
3	App\\Models\\User	100
3	App\\Models\\User	101
3	App\\Models\\User	103
3	App\\Models\\User	104
2	App\\Models\\User	10
3	App\\Models\\User	57
3	App\\Models\\User	201
3	App\\Models\\User	202
3	App\\Models\\User	203
3	App\\Models\\User	204
3	App\\Models\\User	205
3	App\\Models\\User	206
3	App\\Models\\User	207
3	App\\Models\\User	208
3	App\\Models\\User	209
3	App\\Models\\User	210
3	App\\Models\\User	211
3	App\\Models\\User	212
3	App\\Models\\User	213
3	App\\Models\\User	214
3	App\\Models\\User	215
3	App\\Models\\User	216
3	App\\Models\\User	217
3	App\\Models\\User	218
3	App\\Models\\User	219
3	App\\Models\\User	220
3	App\\Models\\User	221
3	App\\Models\\User	222
3	App\\Models\\User	223
3	App\\Models\\User	224
3	App\\Models\\User	225
3	App\\Models\\User	226
3	App\\Models\\User	227
2	App\\Models\\User	4
2	App\\Models\\User	228
3	App\\Models\\User	233
\.


--
-- Data for Name: organigramas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.organigramas (id, dependency, _lft, _rgt, parent_id, manager, phone, email, user_id, created_at, updated_at) FROM stdin;
1	Gerencia de Salud	0	0	\N	El Gerente	13485	HjJzfdCIZK@ips.gov.py	1	2023-10-18 14:49:40	\N
2	Dirección de Hospitales Área Central	0	0	1	El Director	16128	rN6soJvBbt@ips.gov.py	1	2023-10-18 14:49:40	\N
3	Clinica Pereférica Yrendague	0	0	2	El Director	14289	G4D20ItYU1@ips.gov.py	1	2023-10-18 14:49:40	\N
4	Clinica Pereférica Isla Poí	0	0	2	Dr César Acosta	17225	ln5FJK0Wsc@ips.gov.py	1	2023-10-18 14:49:40	\N
5	Clinica Pereférica Nanawa	0	0	2	Dr Estean Duarte	14209	3iEjStciLb@ips.gov.py	1	2023-10-18 14:49:40	\N
11	Gerencia de Abastecimiento y Logística	7	8	9	Lic. Jaime Caballero	21290136	jjcaba@ips.gov.py	\N	2023-10-25 11:45:49	2023-10-25 11:45:49
12	Gerencia de Desarrollo y Tecnología	9	10	9	Lic. Rodrigo Fretez Llanes	21290136	rfretez@ips.gov.py	\N	2023-10-25 11:47:07	2023-10-25 11:47:07
13	Gerencia de Prestaciones Económicas	11	12	9	Abog. Vanessa Cubas Díaz	21290136	vcubas@ips.gov.py	\N	2023-10-25 11:48:15	2023-10-25 11:48:15
8	INSTITUTO DE PREVISIÓN SOCIAL	1	16	\N	Dr. Jorge Magno Brítez	21290136	jbritez@ips.gov.py	\N	2023-10-25 11:41:22	2023-10-25 11:41:22
9	Consejo de Administración	2	15	8	Dr. Brítez	21290126	consejo@ips.gov.py	\N	2023-10-25 11:43:42	2023-10-25 11:43:42
14	Gerencia Administrativa y Financiera	13	14	9	Lic. Luis Cardozo Brizuela	21290136	lcardozo@ips.gov.py	\N	2023-10-25 11:48:58	2023-10-25 11:48:58
10	Gerencia de Salud	3	6	9	Dr. Gustavo González Mafiodo	21290136	cmorinig@ips.gov.py	\N	2023-10-25 11:45:03	2024-04-23 16:02:40
15	Dirección de Hospitales de Área Interior	4	5	10	DR. VICTOR RODOLFO VERT GOSSEN	21290136	vivert@ips.gov.py	\N	2024-04-23 16:10:28	2024-04-23 16:10:28
\.


--
-- Data for Name: participants_has_surveys; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.participants_has_surveys (id, survey_id, participant_id, completed) FROM stdin;
1	9d5299ee-f1db-410d-980d-94d9fe22b464	228	t
3	9d54e957-930a-48fa-a706-9450b7e8983c	228	f
4	9d54ea9b-0ffa-41e4-871e-a8e6052cacd7	228	f
2	9d54b101-e5b7-44ba-a92f-5e7fc7eedfc9	228	t
5	9d5efe4d-e8cd-41df-a45d-888fe41fa404	228	f
\.


--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: patrimonies; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.patrimonies (id, type, quantity_account_current, detail_location, estate_quantity, department, city, locality, latitude, longitude, location_address, infrastructure_type, description, registry_number, cadastral_current_account, estate_status, committed_investment, transfer, balance_for_transfer, tenant, rent_amount, rent_amount_period, contract_resolution, contract_number, current_period_start, current_period_end, status_documentation, land_area_mt2, land_area_hectares, land_sub_area, built_area_m2, built_value_gs, property_value_gs, total_value_gs, possession_rent_without_title, main_photo_file, main_photo_file_path, evidence_file, evidence_file_path, created_at, updated_at) FROM stdin;
1	BIEN DE USO	649	Qui odio dolor dolor	237	ALTO PARAGUAY	BAHIA NEGRA	CENTRAL	83	87	Nostrum et ipsum qu	Edificio	<p>A</p>	477	\N	EN GESTION JUDICIAL	\N	12	75	Qui cupidatat quis t	77	1992	Eveniet quo quas et	371	\N	\N	A	18	Aperiam voluptatem	Iusto sit non quo ip	Voluptas aut ullamco	Exercitation labore	Unde voluptatum dolo	Velit dolor quis ill	Do distinctio Expli	\N	\N	\N	\N	2024-11-06 19:48:54	2024-11-06 19:48:54
2	BIEN DE USO	24	Sed tempor accusanti	741	CAAGUAZU	YHU	SIDEPAR 2DA LINEA	92	76	Explicabo Repellend	Predio Vacío	<p>A</p>	495	\N	ALQUILADO + INV	\N	63	9	Duis et ut neque aut	34	1957	Praesentium ullam de	101	\N	\N	A	99	Est et in cillum et	Reprehenderit tempor	Non labore voluptate	Non ut quos qui esse	Laboris fugiat ducim	Modi itaque qui est	Est esse laborum omn	\N	\N	1730922787_1-3-Song-of-the-Wind.pdf	06-11-2024	2024-11-06 19:53:07	2024-11-06 19:53:07
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
1	role-list	web	2023-10-18 14:49:39	\N
2	role-edit	web	2023-10-18 14:49:39	\N
3	role-create	web	2023-10-18 14:49:39	\N
4	role-delete	web	2023-10-18 14:49:39	\N
\.


--
-- Data for Name: questions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.questions (id, survey_id, question, created_at, updated_at) FROM stdin;
1	9d5299ee-f1db-410d-980d-94d9fe22b464	<p>Unico Campeon de la libertadores de america</p>	2024-10-24 14:48:54	2024-10-24 14:48:54
2	9d5299ee-f1db-410d-980d-94d9fe22b464	<p>Quien descubrio America</p>	2024-10-24 14:49:36	2024-10-24 14:49:36
3	9d5299ee-f1db-410d-980d-94d9fe22b464	<p>Nueva pregunta</p>	2024-10-25 15:32:49	2024-10-25 15:32:49
8	9d54b101-e5b7-44ba-a92f-5e7fc7eedfc9	<p>a</p>	2024-10-25 15:48:17	2024-10-25 15:48:17
9	9d54b101-e5b7-44ba-a92f-5e7fc7eedfc9	<p>a</p>	2024-10-25 15:51:50	2024-10-25 15:51:50
11	9d54e957-930a-48fa-a706-9450b7e8983c	<p>a</p>	2024-10-25 18:17:47	2024-10-25 18:17:47
12	9d54e957-930a-48fa-a706-9450b7e8983c	<p>a</p>	2024-10-25 18:18:19	2024-10-25 18:18:19
23	9d5efe4d-e8cd-41df-a45d-888fe41fa404	¿Quién se considera la "Madre de la Enfermería Moderna"?	2024-11-14 14:02:42	2024-11-14 14:02:42
24	9d5efe4d-e8cd-41df-a45d-888fe41fa404	¿En qué siglo surgió la enfermería como profesión distintiva?	2024-11-14 14:02:42	2024-11-14 14:02:42
25	9d5efe4d-e8cd-41df-a45d-888fe41fa404	¿Cuál de las siguientes fue una contribución importante de Florence Nightingale a la enfermería?	2024-11-14 14:02:42	2024-11-14 14:02:42
26	9d5efe4d-e8cd-41df-a45d-888fe41fa404	¿Quién fundó la Cruz Roja Internacional?	2024-11-14 14:02:42	2024-11-14 14:02:42
27	9d5efe4d-e8cd-41df-a45d-888fe41fa404	¿Cuál de las siguientes fue una de las primeras escuelas de enfermería en Estados Unidos?	2024-11-14 14:02:42	2024-11-14 14:02:42
\.


--
-- Data for Name: servicios; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.servicios (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: surveys; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.surveys (id, name, type, group_id, dependency_id, description, created_at, updated_at, deleted_at) FROM stdin;
9d5299ee-f1db-410d-980d-94d9fe22b464	Encuesta Tests	group	44	\N	<p>Encuesta Test</p>	2024-10-24 14:42:34	2024-10-25 15:33:21	2024-10-25 15:33:21
9d54ea9b-0ffa-41e4-871e-a8e6052cacd7	asd	group	44	\N	<p>a</p>	2024-10-25 18:19:48	2024-10-30 18:31:22	2024-10-30 18:31:22
9d54e957-930a-48fa-a706-9450b7e8983c	A	group	44	\N	<p>a</p>	2024-10-25 18:16:16	2024-10-30 18:31:26	2024-10-30 18:31:26
9d54b101-e5b7-44ba-a92f-5e7fc7eedfc9	Test Encuesta	group	44	\N	<p>Test Encuesta</p>	2024-10-25 15:38:45	2024-10-30 18:31:29	2024-10-30 18:31:29
9d5efe4d-e8cd-41df-a45d-888fe41fa404	Test	group	44	\N	<p>Test</p>	2024-10-30 18:33:10	2024-10-30 18:33:10	\N
\.


--
-- Data for Name: surveys_has_analysts; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.surveys_has_analysts (id, survey_id, analyst_id) FROM stdin;
1	9d5299ee-f1db-410d-980d-94d9fe22b464	228
2	9d54b101-e5b7-44ba-a92f-5e7fc7eedfc9	228
3	9d54e957-930a-48fa-a706-9450b7e8983c	228
4	9d54ea9b-0ffa-41e4-871e-a8e6052cacd7	4
5	9d5efe4d-e8cd-41df-a45d-888fe41fa404	1
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, group_id, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
4	Cristina Cabrera Marecos	crcabre@ips.gov.py	27	\N	$2y$10$B7CSRMJ7UO4NozFbVcDsveR966MXoqestWBIxc7N9/jojXPdttLo2	NKmT1Rtf8DUpWX0qH4TreHDYRDSbS7QgmlA2koC30BfygSEAEk5K2sAI1ohL	2023-10-18 15:01:26	2024-04-20 23:02:25
201	Gustavo González Maffiodo	gagonzal@ips.gov.py	34	\N	$2y$10$oa/VEgyh6a5DzB0PiA.ZpuWGBdlU/ymrSNPG0btE/wYMA0DwQuU/G	\N	\N	2024-04-16 16:13:11
202	Freddy Velazquez	fravelaz@ips.gov.py	34	\N	$2y$10$AXXvFazmg1g3qSlr6/sD5.Fy3d04NugK4L6W3CP55oijkcCEBobDy	\N	\N	2024-04-16 16:13:21
203	Sandra Rocio Martinez	sarmarti@ips.gov.py	34	\N	$2y$10$WlOC09M/gZlF.xP4fBcsD.v/4Mt8ibm6TTOkBj.yKP64L8NnyQuUy	\N	\N	2024-04-16 16:13:28
204	Carmen Marcela Agüero Echeguren	maaguero@ips.gov.py	34	\N	$2y$10$rBD7vhvyMisRLMKum9PjSebLoJp5locMjZcrjYavZJEJSvAoxAGgK	\N	\N	2024-04-16 16:13:34
205	Ramona Garcia	ragarcia@ips.gov.py	34	\N	$2y$10$aYv5q15YRNRMADXSfAxBZ.Gv/HJD73Gl8Ev8nx3j0vYWtM9afysaa	\N	\N	2024-04-16 16:13:40
206	Lourdes Karina Duarte	lkduarte@ips.gov.py	34	\N	$2y$10$g1D5SiPsRL.RAsVoxwRFcuNeNRDd8DHUGg.jzGSzBcpWLLPa2yg5q	\N	\N	2024-04-16 16:13:51
207	Osmar Cantero Wolf	oscanter@ips.gov.py	34	\N	$2y$10$ocpunhdMdhXwm/zNY2.CTeVS78A1rYDNybiul3T/lcE2zyBfR2Bum	\N	\N	2024-04-16 16:13:56
208	Walter Ariel Delgado	waadelga@ips.gov.py	34	\N	$2y$10$Y81pW7z5XtudZ.XXd6rc1.iSGKGnJ8gZB02/UCxbOkQwKyC55L7Ui	\N	\N	2024-04-16 16:14:02
209	Domingo Aliende Robles	domialie@ips.gov.py	34	\N	$2y$10$.nbKuSHc8mxPeVVhLD.ZR..9Y.VOSjoU73xvOzccMmBB.PEnnBUEy	\N	\N	2024-04-16 16:14:08
210	Osvaldo Daniel Leon	oleon@ips.gov.py	34	\N	$2y$10$NMH0Z.V/IEue/0HCYqWb7OeEE0nCzN3csAeFWuJckVLqF/iWN5mTW	\N	\N	2024-04-16 16:14:13
211	Maria Antonietta Villalba Ferreira	mantvill@ips.gov.py	34	\N	$2y$10$Wg85TKNj6/frk3RTHHr1l.3kn/nuj4KN7iEZb6fjLwDN/sHjLJB7K	\N	\N	2024-04-16 16:14:18
212	Diego Irala	dairala@ips.gov.py	34	\N	$2y$10$4AoRbnC7zz1kTos4DC15g.wg5.QrDwWHsoBQAcwv098EbtxBKno06	\N	\N	2024-04-16 16:14:23
213	Rogelio Salaberry	secretariarsgp@itaipu.gov.py	34	\N	$2y$10$sYjzGVkLmNr5mVoEBTQTE.E5OfMjS/kXL8Ez8DNnMlkeU.k3nkgUq	\N	\N	2024-04-16 16:14:27
214	Silvia Abente	secretariarsgp_2@itaipu.gov.py	34	\N	$2y$10$zDyhkf0EfJlI3HfHjvGxE.Oa4LeNjnsIr5ldHVps7aytAIh9Ykj3C	\N	\N	2024-04-16 16:14:33
215	Ana Ayala	anaaya@itaipu.gov.py	34	\N	$2y$10$5lXKtECHP7jfNPlvMFlm3uKJSutjTnvS428vt8yxX.YFGXfHD8J7K	\N	\N	2024-04-16 16:14:39
216	Santiago García Destéfano	viceministeriointegral@mspbs.gov.py	34	\N	$2y$10$59QOPm7wp0aC.xjZa0Z0a.2kdXdib5We8gc90Qr.MoJ9hgrKv5eLi	\N	\N	2024-04-16 16:14:45
217	Noelia Torres	regionsanitaria10@mspbs.gov.py	34	\N	$2y$10$7ximF2tiQURWs9XKAOBiAeCpcXTFb4COso2DC1dEfFwMy0Td79mvm	\N	\N	2024-04-16 16:14:54
218	Gustavo Ortiz	dgpe@mspbs.gov.py	34	\N	$2y$10$Iux007PWSB6a5Tly3jneOeODa6mrN1vWU1ONKNXovB5nSvncWygPG	\N	\N	2024-04-16 16:14:59
219	Andrea Hermosa	andrea.hermosa@example.com	34	\N	$2y$10$lIXRQQwT2Cu9hPsZ5DbZa.a8ZVUi/ngazTkkkO90roFFYIShaJ9SC	\N	\N	2024-04-16 16:15:05
220	Derlis León	serviciosdesalud@mspbs.gov.py	34	\N	$2y$10$AqfY7jsRcUwhxPeDoIkhROMPE/C70C2tniGoYLUl3.Pz9K/3HYKLu	\N	\N	2024-04-16 16:15:10
221	Miguel Caballero	miguel.caballero@example.com	34	\N	$2y$10$daiIL3eyGcXsdw5qODRKfeS5poK0qO2FTxapK7cFW4CVij46wSFKi	\N	\N	2024-04-16 16:15:28
222	Federico Schrodel	federico.schrodel@example.com	34	\N	$2y$10$TW22DPTrpgqC7rg9DyKSo.grpw2lfGpEq4Am5IKm.CqItLDlhqAqW	\N	\N	2024-04-16 16:15:36
223	Marta Sosa	marta.sosa@example.com	34	\N	$2y$10$MSmorIPG9ZUCrRxFX9Pxae.NbNnz8JZSvcPgUVcz3v3tnYjW8ZLwe	\N	\N	2024-04-16 16:15:45
224	Hugo Aca	hugo.aca@example.com	34	\N	$2y$10$E4n1DgkrkLgWrFNrchgcheGl0KYpnDROH4rj0Pnfua7/Dqpstl5sm	\N	\N	2024-04-16 16:15:53
225	Veronica Silvero	veronica.silvero@example.com	34	\N	$2y$10$akGWP/9Z3Djdy8HsZVxE1uGwEDZV/y8305TDpWpaR601RgOV3WhNC	\N	\N	2024-04-16 16:15:58
226	Sergio Ambrasath	regionsanitaria14@mspbs.gov.py	34	\N	$2y$10$iRin.qIZvabW4eUjXqIMa.U13kYSRRcJsTghY52H4wUzROdT6RwHe	\N	\N	2024-04-16 16:16:03
227	Fernando Bittinger	fernando.bittinger@example.com	34	\N	$2y$10$chNX0ssFNxXquaafbCjERugfMqgSf2yHw.hySTmVlNTLHlvdBXMni	\N	\N	2024-04-16 16:16:08
99	Aida Liliana Franco Cardozo	aidacar@ips.gov.py	27	\N	$2y$10$vLQZ6iznLWi6ZC/0x/qPk.CbUZqLb1PGVvrWT6h6b9CLcgerkLZf6	\N	2023-10-31 17:52:46	2023-10-31 17:52:46
100	Job Ramírez	jobram@ips.gov.py	27	\N	$2y$10$08jAfpJmTNfmPLRVvdJvUee4oZ8jXllNI13mK0qrEtqRUQmBPr8xm	\N	2023-10-31 17:57:36	2023-10-31 17:57:36
101	Francisco Benito González Benítez	fbogonza@ipg.gov.py	27	\N	$2y$10$1l/ZuDgBfzVHaw2NZHDH5evkKo7cEo3B6qRPQBhQ35ttICFgQvn/a	\N	2023-10-31 17:58:54	2023-10-31 17:58:54
103	Oscar Alberto Ocaño Guzman	oaoca@ips.gov.py	27	\N	$2y$10$xNNvkBr8G3swsCqsIRB94ewpAi5nW67ErqEepXx/gzjD5GuqydLVu	\N	2023-10-31 18:06:55	2023-10-31 18:06:55
104	María Auxiliadora Vargas	mavargas@gmail.com	27	\N	$2y$10$2.pub9PzUn/LlOTpwgP0POocd.y88VnQvrea.6iyyGDNJAR32elcy	\N	2023-10-31 18:11:19	2023-10-31 18:11:19
10	Giulietta Levi	giullevi@ips.gov.py	27	\N	$2y$10$f0FCbXw1m4xwht.SBtEr.eEbB4eXqQTs0R4eAjIp7KApAQz/UPbcS	plW9H7KeMdX7ZqbHiYIZadF8ixRJcLKQV8WtVlxqWSs9JmnjxhZVuEbjm2FP	2023-10-18 17:54:39	2023-11-01 11:22:23
1	Julio Franco	jucfra23@gmail.com	27	2023-10-18 14:49:39	$2y$10$3Wx5qt1PmpQ3RS5yuLnjSujrX05KnCUjkStf3PLNVcMwKBhpvQ9Wm	\N	2023-10-18 14:49:39	2023-10-18 14:49:39
7	Andrea Mongelos	ammongel@ips.gov.py	27	\N	$2y$10$bsOw82BfPvCCkslK2JHZ5uc/1mEccVlMtrw83QVScMscYVNW6j4jS	\N	2023-10-18 17:49:12	2023-10-18 17:49:12
6	María Silvia Villar	msvillar@ips.gov.py	27	\N	$2y$10$9tzGPb63W90PfFddg2/zCOAQ.NEw6yeEM//mSa6BIY/my8n4g3hjG	\N	2023-10-18 15:04:21	2023-10-18 17:50:23
13	José Gabriel Velázquez Franco	participantes@ips.gov.py	27	\N	$2y$10$GZVcTjnblMi3PHXjPzeBcOXxJSSSyRiF5cz6rz51p.LJ.oFYD5XqW	\N	2023-10-18 18:04:36	2023-10-18 18:04:36
14	Liz Susan Benítez	participantes2@ips.gov.py	27	\N	$2y$10$5JFEKEB9ypGgrIMnbHqgneSk6TcyaE.FAubZO/stoqNB0tcA7Ccfy	\N	2023-10-18 18:06:10	2023-10-18 18:06:10
15	Diana Elizabet Giménez García	participantes3@ips.gov.py	27	\N	$2y$10$YS2fZOtEwK8tITFbN7kuBOP/LzSUu8E3ez5dRxksnE5su9Ur.i3b2	\N	2023-10-18 18:06:57	2023-10-18 18:06:57
16	Ana María Castro	participantes4@ips.gov.py	27	\N	$2y$10$7sPq39w5A8XseXsJXvirJexqOBzCoWzPYr1ImFfDPpy0cvzkzrAH2	\N	2023-10-18 18:07:27	2023-10-18 18:07:27
17	Tanía Noemí Arguello Torres	participantes5@ips.gov.py	27	\N	$2y$10$r3E7wrWAjtsdFQia7/avs.P5G4YPz6P.hNg2Mq1r/I6zjzwavnVUy	\N	2023-10-18 18:08:06	2023-10-18 18:08:06
18	Fabiola Rodríguez	participantes6@ips.gov.py	27	\N	$2y$10$TsgqIEqqjVnrPexq/aCvvuwFTDOL4ZGQqNTWZJmQkVjOWoy1WzcGq	\N	2023-10-18 18:08:44	2023-10-18 18:08:44
19	Juana Romero	participantes7@ips.gov.py	27	\N	$2y$10$zyUF2MDizVPRIxz0e8iiMOQ.i1AzuNCytwocMJMcuVz1386f0lG7i	\N	2023-10-18 18:09:16	2023-10-18 18:09:16
20	María Mercedes Ocampos	participantes8@ips.gov.py	27	\N	$2y$10$puRotqx5fh.CmO1n4VkWPuv8k8IVwCIm6mjWluMz5f.fIGh5UP3vq	\N	2023-10-18 18:10:02	2023-10-18 18:10:02
21	Lourdes Sanmartin	participantes9@ips.gov.py	27	\N	$2y$10$E/OpJCpUJqG5zRLzA4PqLeON0dNSq6E4PL02dIcvmnjMEhUBQlwmC	\N	2023-10-18 18:10:31	2023-10-18 18:10:31
22	Juan Carlos Ávalos	participantes10@ips.gov.py	27	\N	$2y$10$vmbLIzXPRqFPZW66tpJWHu2Mg2Hf1QjwX2t/3g41VMps3.sZRetlm	\N	2023-10-18 18:11:04	2023-10-18 18:11:04
23	Heriberto González Colmán	participantes11@ips.gov.py	27	\N	$2y$10$Sjl5HbuW9p2HuZu.U7Ueee0c6BDz./NyYhWmPCMx2do7x8pxOcVU.	\N	2023-10-18 18:12:13	2023-10-18 18:12:13
24	Heriberto González	participantes12@ips.gov.py	27	\N	$2y$10$H8fvNTVprZdgNGFc.peRq.VKxPfjsx.5107nurkFRF/YIC0xFn7Gm	\N	2023-10-18 18:14:35	2023-10-18 18:14:35
25	Graciela Nuñez	participantes13@ips.gov.py	27	\N	$2y$10$S9uAPwD3gcgYRXdyQ8sfmeqaz/OUIPEny8BgSyAzBTbHRwLBcDuwq	\N	2023-10-18 18:15:07	2023-10-18 18:15:07
26	José González	participantes14@ips.gov.py	27	\N	$2y$10$HNEsR1nTbFnI2/21DWFrEOUKDWH0XT8ylOZUWH2x5gjqWNJ8qG1NW	\N	2023-10-18 18:16:22	2023-10-18 18:16:22
27	Ricardo Brítez	participantes15@ips.gov.py	27	\N	$2y$10$5uXBMdUEYaI5IxyD2YJZ..yskzfj15qP0mS/Woc913azdZk85FlMy	\N	2023-10-18 18:16:56	2023-10-18 18:16:56
28	Fernando Ibarra	participantes16@ips.gov.py	27	\N	$2y$10$.vuKPZTUWJGzZscyvjT/keWlMY1vHGXgO6lPZXaVDY8Si96D8jtUe	\N	2023-10-18 18:17:46	2023-10-18 18:17:46
29	Rodrigo Fretez Llanes	participantes17@ips.gov.py	27	\N	$2y$10$pDVGng94E9NxY7UazYGowe9VkR0JmvtOIzSO4rIYQ9XOxRBs8cb/G	\N	2023-10-18 18:19:58	2023-10-18 18:19:58
30	Juan Carlos Frutos	participantes18@ips.gov.py	27	\N	$2y$10$ocxsmWYoyFAKS2d.y5Nh0erBuckcr3JOZjcycrSLshYf.Nyzn6vY.	\N	2023-10-18 18:22:36	2023-10-18 18:22:36
31	Lissie Marti	participantes19@ips.gov.py	27	\N	$2y$10$ElJRI0DHrq0yH9zgaiUp3urdsF1AgaO7yky1melfAlRdklDPIQOOi	\N	2023-10-18 18:23:31	2023-10-18 18:23:31
32	Enrique Sosa	participantes20@ips.gov.py	27	\N	$2y$10$OGIgUSW3a1m2cOCx2CLLi.Sp9gfnaKlqYfbPayTOrUfByhmvGatuG	\N	2023-10-18 18:24:27	2023-10-18 18:24:27
33	Fanhy Ayala	participantes21@ips.gov.py	27	\N	$2y$10$lroy.E.kqGXHGb1V7UMLp.sm4GLMY1Ia6fOzoNsRDLvUIPqivg.Sa	\N	2023-10-18 18:25:00	2023-10-18 18:25:00
34	Silvio Barrios	participantes22@ips.gov.py	27	\N	$2y$10$v8dCYDrpKK.V5twisd8A8ORaob0NmXNNZGzRAHuUnG9OI9fregFdS	\N	2023-10-18 18:25:36	2023-10-18 18:25:36
35	Patricia Giménez	participantes23@ips.gov.py	27	\N	$2y$10$LOj.iM/J2H5PJjFM5FuCr.BCDlQpkZkJK5XVUjihR24gYdwwLWf1i	\N	2023-10-18 18:26:52	2023-10-18 18:26:52
36	Nelson Caballero	participantes24@ips.gov.py	27	\N	$2y$10$IlUaxMaLWuGSWs5zvXOH8.RiJCdQOt5rxnQbu1.vr6ZaE260cZisW	\N	2023-10-18 18:28:16	2023-10-18 18:28:16
37	Juan Olegario Ortíz	participantes25@ips.gov.py	27	\N	$2y$10$WFmmg451eVS/W51RFchUhORr0tGmvt1Nl73cG1yXJwW7za2XT8Rr.	\N	2023-10-18 18:28:43	2023-10-18 18:28:43
38	Marcelo Bordón	participantes26@ips.gov.py	27	\N	$2y$10$na4EtRm3U4XqgCvPgupsQeqDC2CjjOtn6o.a7WOc6QqMnacl3GHry	\N	2023-10-18 18:30:56	2023-10-18 18:30:56
39	María Bernardita Rodríguez	participantes27@ips.gov.py	27	\N	$2y$10$kdvJntU8gLZoK1SJurzv3eZxA4BwvABzgWx4vwmde3WyQQ3zpUtq2	\N	2023-10-18 18:31:26	2023-10-18 18:31:26
40	Verónica Blanco	participantes28@ips.gov.py	27	\N	$2y$10$8vWVq03.umsSh26bTy6fS.wxb4yrzcSGb0lB7W9e3UgOjfGgDJA2G	\N	2023-10-18 18:31:47	2023-10-18 18:31:47
42	Gladis González	participantes30@ips.gov.py	27	\N	$2y$10$a7or8wHpJwcBkLHn31/dK.yTOC36pQw9TTzR89pIzDT.uTljaBr1y	\N	2023-10-18 18:33:28	2023-10-18 18:33:28
43	Jaime Caballero	participantes31@ips.gov.py	27	\N	$2y$10$kaYegVwmd5nBlGMx7MIWc.mzuC6AW66sMcs2QVFeARsY4zyeKHPie	\N	2023-10-18 18:34:05	2023-10-18 18:34:05
45	Nilda Ortíz	participantes33@ips.gov.py	27	\N	$2y$10$.V3O6PQjwQ4iMbCINvRUUeElCQL0u9O6J7DFrZsa/OLDvBssuWK5m	\N	2023-10-18 18:35:11	2023-10-18 18:35:11
46	Mauricio Silveira	participantes34@ips.gov.py	27	\N	$2y$10$JPzmEfA/Kvh6t3xK09siduBe5nKl.oXB5rxvkNqT0mITRgcHYtlX2	\N	2023-10-18 18:36:48	2023-10-18 18:36:48
47	Alicia Rocholl	participantes35@ips.gov.py	27	\N	$2y$10$ow7lFSunOlk2SkIgXiJ.vOTVx8bDpdn5lEY0Pthyt.9RfCMeVXlBy	\N	2023-10-18 18:37:16	2023-10-18 18:37:16
48	María Dolores Miracca	participantes36@ips.gov.py	27	\N	$2y$10$QLvXVlSKwQHwDOjnoAm6IOLbMU1zARhZgIjN9rtYCTCRu26pPh/6q	\N	2023-10-18 18:37:47	2023-10-18 18:37:47
49	Arnaldo González	participantes37@ips.gov.py	27	\N	$2y$10$9GC7djT53fuSGq2GiEqBRO2q6Xuvve6P.cRcUaxajH74tJQ979qe6	\N	2023-10-18 18:38:40	2023-10-18 18:38:40
8	Javier González	jafegonz@ips.gov.py	27	\N	$2y$10$EK6AM7PK72bQo3akg1Ipb.J/gqmSGpd13l73KPQCVXYu6s0SK3nQe	8wO6Ak4aayLlJNjsL1RsoYVHcJWNHit89hVLhx9n0BPvsnttKou7H4F0Dl3b	2023-10-18 17:51:46	2023-10-18 17:51:46
3	Oscar Franco	ofalcara@ips.gov.py	27	\N	$2y$10$8q8u5bnHOw7LPONKjW0XEO.UvtFBypsBeTPq33Zd5MFRmUZhG0GE6	ZVNeGKGshRMDvcfzIuqoPKTUFKf6lCG3IS3ZnQEeZHrwYNg3D9cGx2irneVi	2023-10-18 15:00:25	2023-10-18 15:00:25
12	Letizia Pérez Crovato	lbperez@ips.gov.py	27	\N	$2y$10$75nFzUFYYQ/fK5hDqBQJueVg/frHfnfbUEEaa0F/96pO7G/sLB6N.	pPQWNPvUZ48jOaoPTbGmfAJJN9cdunYYfxY92MA0ZBmjKwtdWmOiAzGtuLEP	2023-10-18 18:02:19	2023-10-18 18:02:19
11	Ricardo Insfrán	rvinsfra@ips.gov.py	27	\N	$2y$10$im8ONfrHth2l2hdr7hrHqu1zbwmzn5GRWe.Yv.NLSwZ5ykwOhJSi.	zAnA8fLW3J9jhfQvB8PNO0FirVfOOJIAicyO27CkuezSKZtqNAY0ZWeTAz4a	2023-10-18 17:56:08	2023-10-18 17:56:08
44	José María Giménez	jmgime@ips.gov.py	27	\N	$2y$10$Z24WXm/wKxdvmIy/X69SU.ca6v.VcHUPPkGptIiUW7eT6LiFECm6K	\N	2023-10-18 18:34:42	2023-10-19 11:46:36
41	Marta Colmán	mcolman@ips.gov.py	27	\N	$2y$10$Sop.aIjSV.rw2apT6KUx8.YDs6cNR/HrH2mB30Okkn0ZlVyWfGBO.	\N	2023-10-18 18:32:37	2023-10-19 11:56:04
2	Alejandro Riveros	arrivero@ips.gov.py	27	\N	$2y$10$CESymKVnTmCFniYQiTFEj.uK7MqHpu3DTJrLOIkomN5ak5VieiIAW	SfWkyrqw7NZqBNRwIvbzI30KGPpq7GzW1jr2GK0Tu4TX3ZU0Cf2Hf7degA7S	2023-10-18 14:59:47	2023-10-18 14:59:47
50	Arnaldo Rubén González	participantes38@ips.gov.py	27	\N	$2y$10$f09GeXrM2l1ilFTZqnHvne6PKGJxVkjdbWrExCuIwAEFnMbqpc3DK	\N	2023-10-18 18:39:23	2023-10-18 18:40:04
51	Blas Aguirre	participantes39@ips.gov.py	27	\N	$2y$10$g122gH388Xt3Np.5EeXAfuvxP.1nB513HiqGXIdU6VIp/Si0TugdK	\N	2023-10-18 18:40:58	2023-10-18 18:40:58
52	Carlos Morínigo	participantes40@ips.gov.py	27	\N	$2y$10$/4G9v9YZ54qAApGbesBod.jRZHDs7FePs3mZkWWvBgotxn189ubjm	\N	2023-10-18 18:41:44	2023-10-18 18:41:44
53	Hugo Martínez	participantes41@ips.gov.py	27	\N	$2y$10$zWVBPwE2NCVlCpKiXEXjDejAP.KzptEwsbgLyfWw/iNIj8M8.7bv.	\N	2023-10-18 18:42:24	2023-10-18 18:42:24
54	Marcos Martínez	participantes42@ips.gov.py	27	\N	$2y$10$0o4j28eWTTIQ6ZAuYpCeJ.rtXfraCIff1mcAsUPrRugIxkmasjUz6	\N	2023-10-18 18:44:33	2023-10-18 18:44:33
55	Sonia Arza	participantes43@ips.gov.py	27	\N	$2y$10$Y3eVAJP7pTXdBoEuH/Y2cuPZPSI5LhabLNPDTZMWua/uEkVZW81QK	\N	2023-10-18 18:45:48	2023-10-18 18:45:48
56	Edgar Ortega	participantes44@ips.gov.py	27	\N	$2y$10$CcLeQPopzPYPb25SKBs9yuFSRVJgm71CU/Oqdtyf.FkSXLdsH6BxK	\N	2023-10-18 18:46:28	2023-10-18 18:46:28
58	Laura Prieto	participantes46@ips.gov.py	27	\N	$2y$10$80TtZrbRA7RZ7af9wKWyy.4RPeH6jHbfE3gPq6Jors7QBoMjkDwXy	\N	2023-10-18 18:48:40	2023-10-18 18:48:40
59	Patricia Galeano	participantes47@ips.gov.py	27	\N	$2y$10$/ht0x7PyFPnSGQWcFrleiexfLqwxtgAdvvJ.9HNoi4QfSKJC8rKc2	\N	2023-10-18 18:50:44	2023-10-18 18:50:44
60	Osmar Amarilla	participantes48@ips.gov.py	27	\N	$2y$10$kCumcUr6aZzYeVuJL/KBn.joChp8xks6FGrOUqjkeJ/8YRowh6cFe	\N	2023-10-18 18:51:33	2023-10-18 18:51:33
61	José Genero Santacruz	participantes49@ips.gov.py	27	\N	$2y$10$f8KaVpY1/mZcC4FT9Qsyz.QG5sHSeh9aYTK3umLp3uAKLb3eK/M1i	\N	2023-10-18 18:52:03	2023-10-18 18:52:03
62	Jorge Sosa	participantes50@ips.gov.py	27	\N	$2y$10$Dn4qAXNd7mwwfdOPJhXAWe/TZevkPolII4cx5oV.WRTpiQgMVFD/y	\N	2023-10-18 18:53:13	2023-10-18 18:53:13
63	Rodolfo Barrios	participantes51@ips.gov.py	27	\N	$2y$10$FZN8IR1ykVwdsH.z.W9VuuLcc19my9FNuQ.SUpTdRyY/afqP46P2C	\N	2023-10-18 18:53:34	2023-10-18 18:53:34
65	Osvaldo Insfran	participantes53@ips.gov.py	27	\N	$2y$10$TkFI/WhHnEYVC6BfWecMROc56AwHc9ejSQxpIYIWbfbUmexHulhxa	\N	2023-10-18 18:55:17	2023-10-18 18:55:17
66	Fernando Perinetti	participantes54@ips.gov.py	27	\N	$2y$10$EOAe9rxt71Jvt/YljToXcuzu7sTlwX3HDwiNfAwFcsSqKVVA.PkPC	\N	2023-10-18 18:55:55	2023-10-18 18:55:55
67	Thiago Da Costa	participantes55@ips.gov.py	27	\N	$2y$10$zEdoCsuDrLA4fc9nFMVttOwgY8Vvmo9lqs0Qkv3eF/llhKAyG959S	\N	2023-10-18 18:57:14	2023-10-18 18:57:14
68	Patricia Acosta Soler	participantes56@ips.gov.py	27	\N	$2y$10$nqPu5K76OsrmIL5ZawCAcu6kU4OUc5iYUCYRy/ka2xh02S5IeuT1O	\N	2023-10-18 18:58:11	2023-10-18 18:58:11
69	Luis Cardozo	participantes57@ips.gov.py	27	\N	$2y$10$Lgp62yZwCK9zQ9FFqb03Y.MEZ2RtOy5R6IVCI7ijQFh1qyguwQXAm	\N	2023-10-18 18:58:48	2023-10-18 18:58:48
70	Gladys Vera	participantes58@ips.gov.py	27	\N	$2y$10$lrpINRzfRvhLbg6WYZxxSuQsg6Oz6r6n/stFc4Iei1Bay/uRcyFWW	\N	2023-10-18 19:03:02	2023-10-18 19:03:02
71	Reinaldo Augusto Silva	participantes59@ips.gov.py	27	\N	$2y$10$5qb89rqbsqkTFFmqC79Q9OPGF2E9iG0AjrxuSiycnm2tocvGA.o5e	\N	2023-10-18 19:03:26	2023-10-18 19:03:26
72	Carolina González	participantes60@ips.gov.py	27	\N	$2y$10$SWHNNMwz6uWSal9aHUUJJ..36q4j0mmVWH5MnAdArFjDaqSGUky46	\N	2023-10-18 19:04:31	2023-10-18 19:04:31
73	Hugo Martinez Báez	participantes61@ips.gov.py	27	\N	$2y$10$4xiSwPKNSLAP1AljodEkJOzGNiZCGunPcG9fmPSn8ULONCWYt98ly	\N	2023-10-18 19:05:41	2023-10-18 19:05:41
74	Americo Riquelme	participantes62@ips.gov.py	27	\N	$2y$10$y0prKOoGniZXNRgpMKiaeewlty6aW/iHoOHjzHCgtocbjd46wr/rW	\N	2023-10-18 19:07:06	2023-10-18 19:07:06
75	Víctor Rivas	participantes63@ips.gov.py	27	\N	$2y$10$MdjSVOd6Eb4.2o9k93SR4.i81ULTNSUxlFKyCAnucKYxZ15xA2lWe	\N	2023-10-18 19:07:53	2023-10-18 19:07:53
78	María Ángeles Sánchez	participantes66@ips.gov.py	27	\N	$2y$10$SltQYd/MCiznhrzvFlfj/.5vJvotsEIC13/SW8PBORrvw6Zbm801C	\N	2023-10-18 19:09:53	2023-10-18 19:09:53
79	Carlos David Reyes	participantes67@ips.gov.py	27	\N	$2y$10$C32eBV4XDjTXXhxguXYUi.knnN6HqAKhVhhgZsTfPgg1CKtFwLTi.	\N	2023-10-18 19:10:50	2023-10-18 19:10:50
80	Juan Ramón Ferreira	participantes68@ips.gov.py	27	\N	$2y$10$izyRL2yaXFHNuaK8qI4uMuJyBeKiX/sjK1OKoTOCiHsJ/nAvwA0Qu	\N	2023-10-18 19:11:28	2023-10-18 19:11:28
81	Carlos Vidal Cabral	participantes69@ips.gov.py	27	\N	$2y$10$1X8hxHyi8xeSrWHPlqdgYeP0qINBOiyDDMunD6ymNHHmMYYicKZFm	\N	2023-10-18 19:12:03	2023-10-18 19:12:03
82	Vanessa Cubas	participantes70@ips.gov.py	27	\N	$2y$10$uM3r1/pOlbRg/5DXHhzYyuxALBQic.JrgLKzlkmR15DZqhswN/Vty	\N	2023-10-18 19:12:45	2023-10-18 19:12:45
83	Tanya Ibañez	participantes71@ips.gov.py	27	\N	$2y$10$.I4A4RrXnTF4HtSsr49YSeB0KJYQoQkMhxZcEwXQyfaDUF4Keo8r6	\N	2023-10-18 19:13:18	2023-10-18 19:13:18
84	Bicia Jarolín	participantes72@ips.gov.py	27	\N	$2y$10$1svDCOwNu8j6leNcIzDki.N0ezG4lBFdunXxOIXfoUfaUZS09EWZG	\N	2023-10-18 19:13:50	2023-10-18 19:13:50
64	María Raquel Martínez	participantes52@ips.gov.py	27	\N	$2y$10$S/yjpi9wObsBErBxTXRaAegslng7KMiTSzxSNWr84ZEVgyIYUg9VS	\N	2023-10-18 18:54:18	2023-10-18 19:15:12
76	María Soledad Ramírez Amarilla	participantes64@ips.gov.py	27	\N	$2y$10$PLyTVvH5srY7TgIKx6jXremBEJgEGvVoP0HXVWvKPrVeMTF9bqezW	\N	2023-10-18 19:08:49	2023-10-18 19:37:55
77	Federico Estigarribia	festigar@ips.gov.py	27	\N	$2y$10$czi/NQi1pcI4US1vmxg7o.kQcU16MUL/edFfSgv8YOqv34XK07Fq2	\N	2023-10-18 19:09:27	2023-10-19 11:24:32
85	Verónica Gauto Alderete	vgauto@ips.gov.py	27	\N	$2y$10$3JqkInxZQY2/LNFvKBcFjeABdLpmHlxATHf8vXZL1Z5nlwX7uRj1G	\N	2023-10-19 11:25:53	2023-10-19 11:25:53
87	Anibal Manuel de los Ríos Bogado	delosrio@ips.gov.py	27	\N	$2y$10$cQ5NFH9fSmVzSb06msjAsuJP8/zBTC5G97oZ7La4svqnpbpmdfzSm	\N	2023-10-19 11:36:17	2023-10-19 11:36:17
88	Miguel Angel Doldán	migueldoldan@ips.gov.py	27	\N	$2y$10$SopvtGVtz6Qs64ZNGmB6OuwLnQLB40TSBdMpsel.VSDmH1UqUlIaG	\N	2023-10-19 11:39:38	2023-10-19 11:39:38
89	Víctor Eduardo Insfran	victorinsfran@ips.go.py	27	\N	$2y$10$nZ6r17mWVpPj5R1yoFq0Ve9pi6et06uOH4rTRf1iIuRoL3Q9ZBrFa	\N	2023-10-19 11:43:34	2023-10-19 11:43:34
90	José Jara Rojas	josejara@ips.gov.py	27	\N	$2y$10$/1oQCpSOMItBoky/2TVW7.AEUa90id1L0mP6UHaFZFvFMakiAI06W	\N	2023-10-19 11:50:50	2023-10-19 11:50:50
91	Matilde Vera López	mati@ips.gov.py	27	\N	$2y$10$18wLB0htXWpLZMz2VHWzG.CbbBeXzThRkbuGydy7niWAyc/iSkY4e	\N	2023-10-19 12:28:29	2023-10-19 12:28:29
92	Karina Landaira	karnina@gov.py	27	\N	$2y$10$3wLfQ0OjwxncHGrPWRk45.zB7SRJjPJbADzTZ8uXMwgJIG62lUoNe	\N	2023-10-19 12:51:32	2023-10-19 12:51:32
93	Patricia Jara Caballero	pjara@ips.gov.py	27	\N	$2y$10$gP2EkPRaYiPgLoOIe9L1O.DgWa4/gAUnPQuqRxldJcDDb0SPrhSWa	\N	2023-10-19 14:21:49	2023-10-19 14:21:49
95	Larissa Fleitas	lmfleita@ips.gov.py	27	\N	$2y$10$gVR8wRFLI3wmyPYn5s.dI.gZkRsmD4LgIVSvV5v2ncCK82BVNkzHC	\N	2023-10-19 15:37:25	2023-10-19 15:37:25
94	Marta Marín	mmarin@ips.gov.py	27	\N	$2y$10$29lD1dIfg4nRYy0BJW4f3OQ/O6S/KJQCGU1KtQ2PXhq78yYfRx4Ci	\N	2023-10-19 14:44:41	2023-10-19 15:56:42
9	Carlos Rino Cubas Díaz	cacubas@ips.gov.py	27	\N	$2y$10$lXEznrJx80G5WkuaXFwVCeNKgPpEe/kG1vwCMs6qkpSH040683gsi	YbwK8dZmwbje5ShJt3c6SIXTHjgOoU8AeR3Wxu9SeuG2mxKYN3KblezeNwWD	2023-10-18 17:52:59	2023-10-18 17:52:59
5	Maureen Eisenhut	eisenhut@ips.gov.py	27	\N	$2y$10$pWzwlyHy.T49QFxT9N34F.65AwMK4LbtfmElTcRfVyKqn4YVUq7am	rMZdOQsG7tmHI4900hifJuR0JEcTtdLKIBM0sNGT57O8tob9PesmA2rH6MJf	2023-10-18 15:02:09	2023-10-18 15:02:09
86	Carlos Alberto Pereira Oviedo	carlosalberto@ips.gov.py	27	\N	$2y$10$PiaI4C2mwJUNPIcyLHFG5eC7s2ZGzWjDi3rcJw8UXP051BTgjfZXS	\N	2023-10-19 11:32:11	2023-10-31 17:45:56
96	Oscar Daniel Franco	franco.daniel@gmail.com	27	\N	$2y$10$8Rkcf2WTx7zETt4FgLsfzeEF0ExG3HiQ67wAdbeiTP.OPbawjEIqW	BZhHhaoyCfaVE87muOEWAyAJJCx6xH0SFSJVTBFN2OxWInAs1t8RU6jUSu2D	2023-10-25 13:41:12	2023-10-25 13:41:12
97	Juan Pablo Servín	juanpaservin@ips.gov.py	27	\N	$2y$10$T4Moc8H/3qXgl2VFDMxiqOJo6fjcY3wr5NsAnVcJZseUMXVsUVqOa	\N	2023-10-31 17:49:45	2023-10-31 17:49:45
98	María Celeste Barrios Maciel	mmaciel@ips.gov.py	27	\N	$2y$10$TPKE/5O3QVd.695DV5l7kO5KYlp.ZPO91TWqtGn/VbUxXcIUmnnXC	\N	2023-10-31 17:51:03	2023-10-31 17:51:40
57	Víctor Vert Gossen	vivert@ips.gov.py	34	\N	$2y$10$caiE80Kl99SO0tY8tL3eLup8EkHE72N1Ribqb6wtQAwNXqYeHGLNm	\N	2023-10-18 18:48:08	2024-04-16 16:06:09
228	Test Encuesta	test@encuesta.com.py	43	\N	$2y$10$UfkS2WufzT0NDJryO0ap.OFUqKLyORKf.SLRQYCMrKBgK1PqsryL.	\N	2024-10-18 12:45:58	2024-10-18 12:45:58
233	Deacon Stephens	lamorahyma@mailinator.com	\N	\N	$2y$10$ZJXRq3tgfGlzZssGD7sITe8ODZvXs7ikFc2ipCHCpxTuoJ5NaL/IS	\N	2024-11-05 20:59:09	2024-11-05 20:59:09
\.


--
-- Name: formulario_clasificadores_id_seq; Type: SEQUENCE SET; Schema: estadistica; Owner: postgres
--

SELECT pg_catalog.setval('estadistica.formulario_clasificadores_id_seq', 1, false);


--
-- Name: formulario_formulario_has_variables_id_seq; Type: SEQUENCE SET; Schema: estadistica; Owner: postgres
--

SELECT pg_catalog.setval('estadistica.formulario_formulario_has_variables_id_seq', 1, false);


--
-- Name: formulario_formularios_id_seq; Type: SEQUENCE SET; Schema: estadistica; Owner: postgres
--

SELECT pg_catalog.setval('estadistica.formulario_formularios_id_seq', 1, false);


--
-- Name: formulario_items_id_seq; Type: SEQUENCE SET; Schema: estadistica; Owner: postgres
--

SELECT pg_catalog.setval('estadistica.formulario_items_id_seq', 1, false);


--
-- Name: formulario_variables_id_seq; Type: SEQUENCE SET; Schema: estadistica; Owner: postgres
--

SELECT pg_catalog.setval('estadistica.formulario_variables_id_seq', 1, false);


--
-- Name: foda_analisis_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.foda_analisis_id_seq', 3208, true);


--
-- Name: foda_cruce_ambientes_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.foda_cruce_ambientes_id_seq', 25, true);


--
-- Name: foda_groups_has_perfiles_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.foda_groups_has_perfiles_id_seq', 1, false);


--
-- Name: foda_models_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.foda_models_id_seq', 216, true);


--
-- Name: pei_profiles_has_dependencies_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.pei_profiles_has_dependencies_id_seq', 1, false);


--
-- Name: pei_profiles_has_strategies_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.pei_profiles_has_strategies_id_seq', 10, true);


--
-- Name: peis_profiles_has_analysts_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.peis_profiles_has_analysts_id_seq', 32, true);


--
-- Name: peis_profiles_has_responsibles_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.peis_profiles_has_responsibles_id_seq', 120, true);


--
-- Name: tasks_has_analysts_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.tasks_has_analysts_id_seq', 68, true);


--
-- Name: tasks_has_type_tasks_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.tasks_has_type_tasks_id_seq', 91, true);


--
-- Name: tasks_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.tasks_id_seq', 62, true);


--
-- Name: typetasks_id_seq; Type: SEQUENCE SET; Schema: planificacion; Owner: postgres
--

SELECT pg_catalog.setval('planificacion.typetasks_id_seq', 50, true);


--
-- Name: e_p_c_equipamientos_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_equipamientos_id_seq', 1, false);


--
-- Name: e_p_c_especialidads_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_especialidads_id_seq', 1, false);


--
-- Name: e_p_c_estandars_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_estandars_id_seq', 1, false);


--
-- Name: e_p_c_horarios_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_horarios_id_seq', 1, false);


--
-- Name: e_p_c_infraestructuras_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_infraestructuras_id_seq', 1, false);


--
-- Name: e_p_c_medicamento_insumos_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_medicamento_insumos_id_seq', 1, false);


--
-- Name: e_p_c_otro_servicios_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_otro_servicios_id_seq', 1, false);


--
-- Name: e_p_c_prestaciones_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_prestaciones_id_seq', 1, false);


--
-- Name: e_p_c_servicios_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_servicios_id_seq', 1, false);


--
-- Name: e_p_c_talento_humanos_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_talento_humanos_id_seq', 1, false);


--
-- Name: e_p_c_turnos_id_seq; Type: SEQUENCE SET; Schema: proyecto; Owner: postgres
--

SELECT pg_catalog.setval('proyecto.e_p_c_turnos_id_seq', 1, false);


--
-- Name: activities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.activities_id_seq', 1, false);


--
-- Name: answers_has_questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.answers_has_questions_id_seq', 27, true);


--
-- Name: answers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.answers_id_seq', 9, true);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_id_seq', 1, false);


--
-- Name: groups_has_members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.groups_has_members_id_seq', 261, true);


--
-- Name: groups_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.groups_id_seq', 44, true);


--
-- Name: localities_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.localities_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 122, true);


--
-- Name: organigramas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.organigramas_id_seq', 15, true);


--
-- Name: participants_has_surveys_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.participants_has_surveys_id_seq', 5, true);


--
-- Name: patrimonies_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.patrimonies_id_seq', 2, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permissions_id_seq', 4, true);


--
-- Name: questions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.questions_id_seq', 27, true);


--
-- Name: servicios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.servicios_id_seq', 1, false);


--
-- Name: surveys_has_analysts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.surveys_has_analysts_id_seq', 5, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 233, true);


--
-- Name: formulario_clasificadores formulario_clasificadores_pkey; Type: CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT formulario_clasificadores_pkey PRIMARY KEY (id);


--
-- Name: formulario_formulario_has_variables formulario_formulario_has_variables_pkey; Type: CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT formulario_formulario_has_variables_pkey PRIMARY KEY (id);


--
-- Name: formulario_formularios formulario_formularios_pkey; Type: CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT formulario_formularios_pkey PRIMARY KEY (id);


--
-- Name: formulario_items formulario_items_pkey; Type: CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT formulario_items_pkey PRIMARY KEY (id);


--
-- Name: formulario_variables formulario_variables_pkey; Type: CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT formulario_variables_pkey PRIMARY KEY (id);


--
-- Name: foda_analisis foda_analisis_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT foda_analisis_pkey PRIMARY KEY (id);


--
-- Name: foda_cruce_ambientes foda_cruce_ambientes_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT foda_cruce_ambientes_pkey PRIMARY KEY (id);


--
-- Name: foda_groups_has_perfiles foda_groups_has_perfiles_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT foda_groups_has_perfiles_pkey PRIMARY KEY (id);


--
-- Name: foda_models foda_models_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_models
    ADD CONSTRAINT foda_models_pkey PRIMARY KEY (id);


--
-- Name: foda_perfiles foda_perfiles_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT foda_perfiles_pkey PRIMARY KEY (id);


--
-- Name: pei_profiles_has_dependencies pei_profiles_has_dependencies_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT pei_profiles_has_dependencies_pkey PRIMARY KEY (id);


--
-- Name: pei_profiles_has_strategies pei_profiles_has_strategies_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT pei_profiles_has_strategies_pkey PRIMARY KEY (id);


--
-- Name: pei_profiles pei_profiles_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT pei_profiles_pkey PRIMARY KEY (id);


--
-- Name: peis_profiles_has_analysts peis_profiles_has_analysts_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT peis_profiles_has_analysts_pkey PRIMARY KEY (id);


--
-- Name: peis_profiles_has_responsibles peis_profiles_has_responsibles_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT peis_profiles_has_responsibles_pkey PRIMARY KEY (id);


--
-- Name: tasks_has_analysts tasks_has_analysts_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT tasks_has_analysts_pkey PRIMARY KEY (id);


--
-- Name: tasks_has_type_tasks tasks_has_type_tasks_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT tasks_has_type_tasks_pkey PRIMARY KEY (id);


--
-- Name: tasks tasks_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT tasks_pkey PRIMARY KEY (id);


--
-- Name: typetasks typetasks_pkey; Type: CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.typetasks
    ADD CONSTRAINT typetasks_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_equipamientos e_p_c_equipamientos_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_equipamientos
    ADD CONSTRAINT e_p_c_equipamientos_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_especialidads e_p_c_especialidads_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_especialidads
    ADD CONSTRAINT e_p_c_especialidads_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_estandars e_p_c_estandars_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_estandars
    ADD CONSTRAINT e_p_c_estandars_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_horarios e_p_c_horarios_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_horarios
    ADD CONSTRAINT e_p_c_horarios_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_infraestructuras e_p_c_infraestructuras_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_infraestructuras
    ADD CONSTRAINT e_p_c_infraestructuras_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_medicamento_insumos e_p_c_medicamento_insumos_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos
    ADD CONSTRAINT e_p_c_medicamento_insumos_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_otro_servicios e_p_c_otro_servicios_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_otro_servicios
    ADD CONSTRAINT e_p_c_otro_servicios_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_prestaciones e_p_c_prestaciones_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_prestaciones
    ADD CONSTRAINT e_p_c_prestaciones_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_servicios e_p_c_servicios_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_servicios
    ADD CONSTRAINT e_p_c_servicios_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_talento_humanos e_p_c_talento_humanos_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_talento_humanos
    ADD CONSTRAINT e_p_c_talento_humanos_pkey PRIMARY KEY (id);


--
-- Name: e_p_c_turnos e_p_c_turnos_pkey; Type: CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_turnos
    ADD CONSTRAINT e_p_c_turnos_pkey PRIMARY KEY (id);


--
-- Name: activities activities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.activities
    ADD CONSTRAINT activities_pkey PRIMARY KEY (id);


--
-- Name: answers_has_questions answers_has_questions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answers_has_questions
    ADD CONSTRAINT answers_has_questions_pkey PRIMARY KEY (id);


--
-- Name: answers answers_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answers
    ADD CONSTRAINT answers_pkey PRIMARY KEY (id);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: groups_has_members groups_has_members_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_pkey PRIMARY KEY (id);


--
-- Name: groups groups_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);


--
-- Name: localities localities_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.localities
    ADD CONSTRAINT localities_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: organigramas organigramas_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_email_unique UNIQUE (email);


--
-- Name: organigramas organigramas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_pkey PRIMARY KEY (id);


--
-- Name: participants_has_surveys participants_has_surveys_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participants_has_surveys
    ADD CONSTRAINT participants_has_surveys_pkey PRIMARY KEY (id);


--
-- Name: patrimonies patrimonies_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.patrimonies
    ADD CONSTRAINT patrimonies_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: questions questions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_pkey PRIMARY KEY (id);


--
-- Name: servicios servicios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_pkey PRIMARY KEY (id);


--
-- Name: surveys_has_analysts surveys_has_analysts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.surveys_has_analysts
    ADD CONSTRAINT surveys_has_analysts_pkey PRIMARY KEY (id);


--
-- Name: surveys surveys_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.surveys
    ADD CONSTRAINT surveys_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: estadistica_formulario_variables__lft__rgt_parent_id_index; Type: INDEX; Schema: estadistica; Owner: postgres
--

CREATE INDEX estadistica_formulario_variables__lft__rgt_parent_id_index ON estadistica.formulario_variables USING btree (_lft, _rgt, parent_id);


--
-- Name: planificacion_foda_models__lft__rgt_parent_id_index; Type: INDEX; Schema: planificacion; Owner: postgres
--

CREATE INDEX planificacion_foda_models__lft__rgt_parent_id_index ON planificacion.foda_models USING btree (_lft, _rgt, parent_id);


--
-- Name: groups__lft__rgt_parent_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX groups__lft__rgt_parent_id_index ON public.groups USING btree (_lft, _rgt, parent_id);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: organigramas__lft__rgt_parent_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX organigramas__lft__rgt_parent_id_index ON public.organigramas USING btree (_lft, _rgt, parent_id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: servicios__lft__rgt_parent_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX servicios__lft__rgt_parent_id_index ON public.servicios USING btree (_lft, _rgt, parent_id);


--
-- Name: formulario_clasificadores estadistica_formulario_clasificadores_clasificador_id_foreign; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign FOREIGN KEY (clasificador_id) REFERENCES estadistica.formulario_clasificadores(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_clasificadores estadistica_formulario_clasificadores_user_id_foreign; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_formulario_id_f; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f FOREIGN KEY (formulario_id) REFERENCES estadistica.formulario_formularios(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_variable_id_for; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_formularios estadistica_formulario_formularios_dependencia_emisor_id_foreig; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig FOREIGN KEY (dependencia_emisor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_formularios estadistica_formulario_formularios_dependencia_receptor_id_fore; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore FOREIGN KEY (dependencia_receptor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_formularios estadistica_formulario_formularios_user_id_foreign; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_items estadistica_formulario_items_user_id_foreign; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_items estadistica_formulario_items_variable_id_foreign; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formulario_variables estadistica_formulario_variables_user_id_foreign; Type: FK CONSTRAINT; Schema: estadistica; Owner: postgres
--

ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT estadistica_formulario_variables_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_analisis planificacion_foda_analisis_aspecto_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign FOREIGN KEY (aspecto_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_analisis planificacion_foda_analisis_perfil_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_analisis planificacion_foda_analisis_user_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_category_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign FOREIGN KEY (category_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_perfil_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore FOREIGN KEY (amenaza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id FOREIGN KEY (debilidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_ FOREIGN KEY (fortaleza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_oportunida; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida FOREIGN KEY (oportunidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes planificacion_foda_cruce_ambientes_perfil_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_cruce_ambientes planificacion_foda_cruce_ambientes_user_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_group_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_perfil_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_perfiles planificacion_foda_perfiles_dependency_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_perfiles planificacion_foda_perfiles_group_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: foda_perfiles planificacion_foda_perfiles_model_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_model_id_foreign FOREIGN KEY (model_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: pei_profiles planificacion_pei_profiles_dependency_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_dependency_id_foreign FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: pei_profiles planificacion_pei_profiles_group_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_dependency_id_forei; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_pei_profile_id_fore; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_profile_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_strategy_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign FOREIGN KEY (strategy_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: pei_profiles planificacion_pei_profiles_user_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_analyst_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_pei_profile_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_profile_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_responsible_id_for; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for FOREIGN KEY (responsible_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks planificacion_tasks_group_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT planificacion_tasks_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks_has_analysts planificacion_tasks_has_analysts_analyst_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks_has_analysts planificacion_tasks_has_analysts_task_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: tasks_has_type_tasks planificacion_tasks_has_type_tasks_task_id_foreign; Type: FK CONSTRAINT; Schema: planificacion; Owner: postgres
--

ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT planificacion_tasks_has_type_tasks_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign FOREIGN KEY (equipamiento_id) REFERENCES proyecto.e_p_c_equipamientos(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_estandar_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign FOREIGN KEY (estandar_id) REFERENCES proyecto.e_p_c_estandars(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_servicio_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore FOREIGN KEY (infraestructura_id) REFERENCES proyecto.e_p_c_infraestructuras(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_servicio_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_tthh_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign FOREIGN KEY (tthh_id) REFERENCES proyecto.e_p_c_talento_humanos(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: otroservicio_has_servicios proyecto_otroservicio_has_servicios_otro_servicio_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign FOREIGN KEY (otro_servicio_id) REFERENCES proyecto.e_p_c_otro_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: otroservicio_has_servicios proyecto_otroservicio_has_servicios_servicio_id_foreign; Type: FK CONSTRAINT; Schema: proyecto; Owner: postgres
--

ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: answers_has_questions answers_has_questions_question_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answers_has_questions
    ADD CONSTRAINT answers_has_questions_question_id_foreign FOREIGN KEY (question_id) REFERENCES public.questions(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: answers answers_participant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answers
    ADD CONSTRAINT answers_participant_id_foreign FOREIGN KEY (participant_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: answers answers_question_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answers
    ADD CONSTRAINT answers_question_id_foreign FOREIGN KEY (question_id) REFERENCES public.questions(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: answers answers_survey_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.answers
    ADD CONSTRAINT answers_survey_id_foreign FOREIGN KEY (survey_id) REFERENCES public.surveys(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: groups_has_members groups_has_members_group_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: groups_has_members groups_has_members_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: organigramas organigramas_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: participants_has_surveys participants_has_surveys_participant_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participants_has_surveys
    ADD CONSTRAINT participants_has_surveys_participant_id_foreign FOREIGN KEY (participant_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: participants_has_surveys participants_has_surveys_survey_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.participants_has_surveys
    ADD CONSTRAINT participants_has_surveys_survey_id_foreign FOREIGN KEY (survey_id) REFERENCES public.surveys(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: questions questions_survey_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.questions
    ADD CONSTRAINT questions_survey_id_foreign FOREIGN KEY (survey_id) REFERENCES public.surveys(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: servicios servicios_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: surveys surveys_dependency_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.surveys
    ADD CONSTRAINT surveys_dependency_id_foreign FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: surveys surveys_group_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.surveys
    ADD CONSTRAINT surveys_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: surveys_has_analysts surveys_has_analysts_analyst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.surveys_has_analysts
    ADD CONSTRAINT surveys_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: surveys_has_analysts surveys_has_analysts_survey_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.surveys_has_analysts
    ADD CONSTRAINT surveys_has_analysts_survey_id_foreign FOREIGN KEY (survey_id) REFERENCES public.surveys(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: users users_group_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

