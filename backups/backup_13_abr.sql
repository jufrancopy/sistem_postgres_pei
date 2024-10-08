PGDMP     
    (                |           siplan %   12.18 (Ubuntu 12.18-0ubuntu0.20.04.1) %   12.18 (Ubuntu 12.18-0ubuntu0.20.04.1) �   �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    30267    siplan    DATABASE     x   CREATE DATABASE siplan WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8';
    DROP DATABASE siplan;
                postgres    false            	            2615    30416    estadistica    SCHEMA        CREATE SCHEMA estadistica;
    DROP SCHEMA estadistica;
                postgres    false                        2615    30415    planificacion    SCHEMA        CREATE SCHEMA planificacion;
    DROP SCHEMA planificacion;
                postgres    false                        2615    30417    proyecto    SCHEMA        CREATE SCHEMA proyecto;
    DROP SCHEMA proyecto;
                postgres    false                        3079    31016 	   uuid-ossp 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;
    DROP EXTENSION "uuid-ossp";
                   false            �           0    0    EXTENSION "uuid-ossp"    COMMENT     W   COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';
                        false    2            �            1259    30499    formulario_clasificadores    TABLE       CREATE TABLE estadistica.formulario_clasificadores (
    id bigint NOT NULL,
    clasificador character varying(191) NOT NULL,
    clasificador_id integer,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 2   DROP TABLE estadistica.formulario_clasificadores;
       estadistica         heap    postgres    false    9            �            1259    30497     formulario_clasificadores_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_clasificadores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 <   DROP SEQUENCE estadistica.formulario_clasificadores_id_seq;
       estadistica          postgres    false    9    235                        0    0     formulario_clasificadores_id_seq    SEQUENCE OWNED BY     o   ALTER SEQUENCE estadistica.formulario_clasificadores_id_seq OWNED BY estadistica.formulario_clasificadores.id;
          estadistica          postgres    false    234            �            1259    30480 #   formulario_formulario_has_variables    TABLE     _  CREATE TABLE estadistica.formulario_formulario_has_variables (
    id bigint NOT NULL,
    formulario_id integer NOT NULL,
    variable_id integer NOT NULL,
    selected boolean DEFAULT false NOT NULL,
    selected_variable_id integer,
    value integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 <   DROP TABLE estadistica.formulario_formulario_has_variables;
       estadistica         heap    postgres    false    9            �            1259    30478 *   formulario_formulario_has_variables_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_formulario_has_variables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 F   DROP SEQUENCE estadistica.formulario_formulario_has_variables_id_seq;
       estadistica          postgres    false    233    9                       0    0 *   formulario_formulario_has_variables_id_seq    SEQUENCE OWNED BY     �   ALTER SEQUENCE estadistica.formulario_formulario_has_variables_id_seq OWNED BY estadistica.formulario_formulario_has_variables.id;
          estadistica          postgres    false    232            �            1259    30457    formulario_formularios    TABLE     Q  CREATE TABLE estadistica.formulario_formularios (
    id bigint NOT NULL,
    formulario character varying(191),
    status character varying(191),
    dependencia_emisor_id integer,
    dependencia_receptor_id integer,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE estadistica.formulario_formularios;
       estadistica         heap    postgres    false    9            �            1259    30455    formulario_formularios_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_formularios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE estadistica.formulario_formularios_id_seq;
       estadistica          postgres    false    231    9                       0    0    formulario_formularios_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE estadistica.formulario_formularios_id_seq OWNED BY estadistica.formulario_formularios.id;
          estadistica          postgres    false    230            �            1259    30436    formulario_items    TABLE       CREATE TABLE estadistica.formulario_items (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    questions text NOT NULL,
    variable_id integer,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE estadistica.formulario_items;
       estadistica         heap    postgres    false    9            �            1259    30434    formulario_items_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE estadistica.formulario_items_id_seq;
       estadistica          postgres    false    229    9                       0    0    formulario_items_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE estadistica.formulario_items_id_seq OWNED BY estadistica.formulario_items.id;
          estadistica          postgres    false    228            �            1259    30420    formulario_variables    TABLE     r  CREATE TABLE estadistica.formulario_variables (
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
 -   DROP TABLE estadistica.formulario_variables;
       estadistica         heap    postgres    false    9            �            1259    30418    formulario_variables_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_variables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 7   DROP SEQUENCE estadistica.formulario_variables_id_seq;
       estadistica          postgres    false    227    9                       0    0    formulario_variables_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE estadistica.formulario_variables_id_seq OWNED BY estadistica.formulario_variables.id;
          estadistica          postgres    false    226            �            1259    30585    foda_analisis    TABLE     O  CREATE TABLE planificacion.foda_analisis (
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
 (   DROP TABLE planificacion.foda_analisis;
       planificacion         heap    postgres    false    7            �            1259    30583    foda_analisis_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_analisis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE planificacion.foda_analisis_id_seq;
       planificacion          postgres    false    243    7                       0    0    foda_analisis_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE planificacion.foda_analisis_id_seq OWNED BY planificacion.foda_analisis.id;
          planificacion          postgres    false    242            �            1259    30552    foda_categorias_has_perfil    TABLE     �   CREATE TABLE planificacion.foda_categorias_has_perfil (
    perfil_id uuid NOT NULL,
    category_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 5   DROP TABLE planificacion.foda_categorias_has_perfil;
       planificacion         heap    postgres    false    7            �            1259    30609    foda_cruce_ambientes    TABLE     -  CREATE TABLE planificacion.foda_cruce_ambientes (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    perfil_id uuid NOT NULL,
    estrategia text NOT NULL,
    tipo character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE planificacion.foda_cruce_ambientes;
       planificacion         heap    postgres    false    7            �            1259    30664 !   foda_cruce_ambientes_has_amenazas    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_amenazas (
    cruce_id integer NOT NULL,
    amenaza_id integer NOT NULL
);
 <   DROP TABLE planificacion.foda_cruce_ambientes_has_amenazas;
       planificacion         heap    postgres    false    7            �            1259    30651 $   foda_cruce_ambientes_has_debilidades    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_debilidades (
    cruce_id integer NOT NULL,
    debilidad_id integer NOT NULL
);
 ?   DROP TABLE planificacion.foda_cruce_ambientes_has_debilidades;
       planificacion         heap    postgres    false    7            �            1259    30625 #   foda_cruce_ambientes_has_fortalezas    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_fortalezas (
    cruce_id integer NOT NULL,
    fortaleza_id integer NOT NULL
);
 >   DROP TABLE planificacion.foda_cruce_ambientes_has_fortalezas;
       planificacion         heap    postgres    false    7            �            1259    30638 &   foda_cruce_ambientes_has_oportunidades    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_oportunidades (
    cruce_id integer NOT NULL,
    oportunidad_id integer NOT NULL
);
 A   DROP TABLE planificacion.foda_cruce_ambientes_has_oportunidades;
       planificacion         heap    postgres    false    7            �            1259    30607    foda_cruce_ambientes_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_cruce_ambientes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE planificacion.foda_cruce_ambientes_id_seq;
       planificacion          postgres    false    7    245                       0    0    foda_cruce_ambientes_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE planificacion.foda_cruce_ambientes_id_seq OWNED BY planificacion.foda_cruce_ambientes.id;
          planificacion          postgres    false    244            �            1259    30567    foda_groups_has_perfiles    TABLE     �   CREATE TABLE planificacion.foda_groups_has_perfiles (
    id bigint NOT NULL,
    perfil_id uuid NOT NULL,
    group_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 3   DROP TABLE planificacion.foda_groups_has_perfiles;
       planificacion         heap    postgres    false    7            �            1259    30565    foda_groups_has_perfiles_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_groups_has_perfiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 =   DROP SEQUENCE planificacion.foda_groups_has_perfiles_id_seq;
       planificacion          postgres    false    7    241                       0    0    foda_groups_has_perfiles_id_seq    SEQUENCE OWNED BY     q   ALTER SEQUENCE planificacion.foda_groups_has_perfiles_id_seq OWNED BY planificacion.foda_groups_has_perfiles.id;
          planificacion          postgres    false    240            �            1259    30517    foda_models    TABLE     �  CREATE TABLE planificacion.foda_models (
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
 &   DROP TABLE planificacion.foda_models;
       planificacion         heap    postgres    false    7            �            1259    30515    foda_models_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_models_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE planificacion.foda_models_id_seq;
       planificacion          postgres    false    237    7                       0    0    foda_models_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE planificacion.foda_models_id_seq OWNED BY planificacion.foda_models.id;
          planificacion          postgres    false    236            �            1259    30529    foda_perfiles    TABLE     r  CREATE TABLE planificacion.foda_perfiles (
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
 (   DROP TABLE planificacion.foda_perfiles;
       planificacion         heap    postgres    false    7            �            1259    30677    pei_profiles    TABLE     7  CREATE TABLE planificacion.pei_profiles (
    id uuid NOT NULL,
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
    group_id integer,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    deleted_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    dependency_id integer,
    name text,
    action text,
    indicator text,
    baseline text,
    target text,
    parent_id uuid,
    year_start date,
    year_end date,
    user_id integer,
    order_item integer
);
 '   DROP TABLE planificacion.pei_profiles;
       planificacion         heap    postgres    false    7            �            1259    30695    pei_profiles_has_dependencies    TABLE     �   CREATE TABLE planificacion.pei_profiles_has_dependencies (
    id bigint NOT NULL,
    pei_profile_id uuid NOT NULL,
    dependency_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 8   DROP TABLE planificacion.pei_profiles_has_dependencies;
       planificacion         heap    postgres    false    7            �            1259    30693 $   pei_profiles_has_dependencies_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 B   DROP SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq;
       planificacion          postgres    false    7    252            	           0    0 $   pei_profiles_has_dependencies_id_seq    SEQUENCE OWNED BY     {   ALTER SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq OWNED BY planificacion.pei_profiles_has_dependencies.id;
          planificacion          postgres    false    251            '           1259    31029    pei_profiles_has_strategies    TABLE     �   CREATE TABLE planificacion.pei_profiles_has_strategies (
    id bigint NOT NULL,
    profile_id uuid NOT NULL,
    strategy_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 6   DROP TABLE planificacion.pei_profiles_has_strategies;
       planificacion         heap    postgres    false    7            &           1259    31027 "   pei_profiles_has_strategies_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.pei_profiles_has_strategies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 @   DROP SEQUENCE planificacion.pei_profiles_has_strategies_id_seq;
       planificacion          postgres    false    7    295            
           0    0 "   pei_profiles_has_strategies_id_seq    SEQUENCE OWNED BY     w   ALTER SEQUENCE planificacion.pei_profiles_has_strategies_id_seq OWNED BY planificacion.pei_profiles_has_strategies.id;
          planificacion          postgres    false    294            �            1259    30713    peis_profiles_has_analysts    TABLE     �   CREATE TABLE planificacion.peis_profiles_has_analysts (
    id bigint NOT NULL,
    pei_profile_id uuid NOT NULL,
    analyst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 5   DROP TABLE planificacion.peis_profiles_has_analysts;
       planificacion         heap    postgres    false    7            �            1259    30711 !   peis_profiles_has_analysts_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.peis_profiles_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ?   DROP SEQUENCE planificacion.peis_profiles_has_analysts_id_seq;
       planificacion          postgres    false    7    254                       0    0 !   peis_profiles_has_analysts_id_seq    SEQUENCE OWNED BY     u   ALTER SEQUENCE planificacion.peis_profiles_has_analysts_id_seq OWNED BY planificacion.peis_profiles_has_analysts.id;
          planificacion          postgres    false    253            )           1259    31047    peis_profiles_has_responsibles    TABLE     �   CREATE TABLE planificacion.peis_profiles_has_responsibles (
    id bigint NOT NULL,
    profile_id uuid NOT NULL,
    responsible_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 9   DROP TABLE planificacion.peis_profiles_has_responsibles;
       planificacion         heap    postgres    false    7            (           1259    31045 %   peis_profiles_has_responsibles_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 C   DROP SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq;
       planificacion          postgres    false    297    7                       0    0 %   peis_profiles_has_responsibles_id_seq    SEQUENCE OWNED BY     }   ALTER SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq OWNED BY planificacion.peis_profiles_has_responsibles.id;
          planificacion          postgres    false    296            !           1259    30926    tasks    TABLE     �   CREATE TABLE planificacion.tasks (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    details character varying(191),
    status integer DEFAULT 0,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
     DROP TABLE planificacion.tasks;
       planificacion         heap    postgres    false    7            #           1259    30940    tasks_has_analysts    TABLE     �   CREATE TABLE planificacion.tasks_has_analysts (
    id bigint NOT NULL,
    task_id integer NOT NULL,
    analyst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 -   DROP TABLE planificacion.tasks_has_analysts;
       planificacion         heap    postgres    false    7            "           1259    30938    tasks_has_analysts_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.tasks_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 7   DROP SEQUENCE planificacion.tasks_has_analysts_id_seq;
       planificacion          postgres    false    7    291                       0    0    tasks_has_analysts_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE planificacion.tasks_has_analysts_id_seq OWNED BY planificacion.tasks_has_analysts.id;
          planificacion          postgres    false    290            %           1259    30958    tasks_has_type_tasks    TABLE       CREATE TABLE planificacion.tasks_has_type_tasks (
    id bigint NOT NULL,
    task_id integer NOT NULL,
    type_task_id integer NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE planificacion.tasks_has_type_tasks;
       planificacion         heap    postgres    false    7            $           1259    30956    tasks_has_type_tasks_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.tasks_has_type_tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE planificacion.tasks_has_type_tasks_id_seq;
       planificacion          postgres    false    293    7                       0    0    tasks_has_type_tasks_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE planificacion.tasks_has_type_tasks_id_seq OWNED BY planificacion.tasks_has_type_tasks.id;
          planificacion          postgres    false    292                        1259    30924    tasks_id_seq    SEQUENCE     |   CREATE SEQUENCE planificacion.tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE planificacion.tasks_id_seq;
       planificacion          postgres    false    289    7                       0    0    tasks_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE planificacion.tasks_id_seq OWNED BY planificacion.tasks.id;
          planificacion          postgres    false    288                       1259    30918 	   typetasks    TABLE     #  CREATE TABLE planificacion.typetasks (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    typetaskable_id uuid NOT NULL,
    typetaskable_type character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 $   DROP TABLE planificacion.typetasks;
       planificacion         heap    postgres    false    7                       1259    30916    typetasks_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.typetasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE planificacion.typetasks_id_seq;
       planificacion          postgres    false    287    7                       0    0    typetasks_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE planificacion.typetasks_id_seq OWNED BY planificacion.typetasks.id;
          planificacion          postgres    false    286                       1259    30753    e_p_c_equipamientos    TABLE       CREATE TABLE proyecto.e_p_c_equipamientos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE proyecto.e_p_c_equipamientos;
       proyecto         heap    postgres    false    5                       1259    30751    e_p_c_equipamientos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_equipamientos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE proyecto.e_p_c_equipamientos_id_seq;
       proyecto          postgres    false    5    260                       0    0    e_p_c_equipamientos_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE proyecto.e_p_c_equipamientos_id_seq OWNED BY proyecto.e_p_c_equipamientos.id;
          proyecto          postgres    false    259                       1259    30794    e_p_c_equipamientos_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_equipamientos_servicios (
    servicio_id integer NOT NULL,
    equipamiento_id integer NOT NULL,
    cantidad integer NOT NULL
);
 3   DROP TABLE proyecto.e_p_c_equipamientos_servicios;
       proyecto         heap    postgres    false    5                        1259    30731    e_p_c_especialidads    TABLE     6  CREATE TABLE proyecto.e_p_c_especialidads (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE proyecto.e_p_c_especialidads;
       proyecto         heap    postgres    false    5            �            1259    30729    e_p_c_especialidads_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_especialidads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE proyecto.e_p_c_especialidads_id_seq;
       proyecto          postgres    false    5    256                       0    0    e_p_c_especialidads_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE proyecto.e_p_c_especialidads_id_seq OWNED BY proyecto.e_p_c_especialidads.id;
          proyecto          postgres    false    255                       1259    30878    e_p_c_estandars    TABLE       CREATE TABLE proyecto.e_p_c_estandars (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 %   DROP TABLE proyecto.e_p_c_estandars;
       proyecto         heap    postgres    false    5                       1259    30876    e_p_c_estandars_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_estandars_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE proyecto.e_p_c_estandars_id_seq;
       proyecto          postgres    false    5    280                       0    0    e_p_c_estandars_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE proyecto.e_p_c_estandars_id_seq OWNED BY proyecto.e_p_c_estandars.id;
          proyecto          postgres    false    279                       1259    30887    e_p_c_estandars_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_estandars_servicios (
    estandar_id integer NOT NULL,
    servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);
 /   DROP TABLE proyecto.e_p_c_estandars_servicios;
       proyecto         heap    postgres    false    5                       1259    30835    e_p_c_horarios    TABLE     '  CREATE TABLE proyecto.e_p_c_horarios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    start_time character varying(191) NOT NULL,
    end_time character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 $   DROP TABLE proyecto.e_p_c_horarios;
       proyecto         heap    postgres    false    5                       1259    30833    e_p_c_horarios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_horarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE proyecto.e_p_c_horarios_id_seq;
       proyecto          postgres    false    273    5                       0    0    e_p_c_horarios_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE proyecto.e_p_c_horarios_id_seq OWNED BY proyecto.e_p_c_horarios.id;
          proyecto          postgres    false    272                       1259    30820    e_p_c_infraestructura_servicio    TABLE     �   CREATE TABLE proyecto.e_p_c_infraestructura_servicio (
    servicio_id integer NOT NULL,
    infraestructura_id integer NOT NULL,
    cantidad integer NOT NULL
);
 4   DROP TABLE proyecto.e_p_c_infraestructura_servicio;
       proyecto         heap    postgres    false    5                       1259    30761    e_p_c_infraestructuras    TABLE     J  CREATE TABLE proyecto.e_p_c_infraestructuras (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    measurement double precision NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 ,   DROP TABLE proyecto.e_p_c_infraestructuras;
       proyecto         heap    postgres    false    5                       1259    30759    e_p_c_infraestructuras_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_infraestructuras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE proyecto.e_p_c_infraestructuras_id_seq;
       proyecto          postgres    false    5    262                       0    0    e_p_c_infraestructuras_id_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE proyecto.e_p_c_infraestructuras_id_seq OWNED BY proyecto.e_p_c_infraestructuras.id;
          proyecto          postgres    false    261            
           1259    30777    e_p_c_medicamento_insumos    TABLE     "  CREATE TABLE proyecto.e_p_c_medicamento_insumos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE proyecto.e_p_c_medicamento_insumos;
       proyecto         heap    postgres    false    5            	           1259    30775     e_p_c_medicamento_insumos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq;
       proyecto          postgres    false    266    5                       0    0     e_p_c_medicamento_insumos_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq OWNED BY proyecto.e_p_c_medicamento_insumos.id;
          proyecto          postgres    false    265                       1259    30769    e_p_c_otro_servicios    TABLE       CREATE TABLE proyecto.e_p_c_otro_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 *   DROP TABLE proyecto.e_p_c_otro_servicios;
       proyecto         heap    postgres    false    5                       1259    30767    e_p_c_otro_servicios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_otro_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 4   DROP SEQUENCE proyecto.e_p_c_otro_servicios_id_seq;
       proyecto          postgres    false    5    264                       0    0    e_p_c_otro_servicios_id_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE proyecto.e_p_c_otro_servicios_id_seq OWNED BY proyecto.e_p_c_otro_servicios.id;
          proyecto          postgres    false    263                       1259    30854    e_p_c_prestaciones    TABLE     ;  CREATE TABLE proyecto.e_p_c_prestaciones (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 (   DROP TABLE proyecto.e_p_c_prestaciones;
       proyecto         heap    postgres    false    5                       1259    30852    e_p_c_prestaciones_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_prestaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE proyecto.e_p_c_prestaciones_id_seq;
       proyecto          postgres    false    277    5                       0    0    e_p_c_prestaciones_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE proyecto.e_p_c_prestaciones_id_seq OWNED BY proyecto.e_p_c_prestaciones.id;
          proyecto          postgres    false    276                       1259    30785    e_p_c_servicios    TABLE       CREATE TABLE proyecto.e_p_c_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 %   DROP TABLE proyecto.e_p_c_servicios;
       proyecto         heap    postgres    false    5                       1259    30783    e_p_c_servicios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE proyecto.e_p_c_servicios_id_seq;
       proyecto          postgres    false    268    5                       0    0    e_p_c_servicios_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE proyecto.e_p_c_servicios_id_seq OWNED BY proyecto.e_p_c_servicios.id;
          proyecto          postgres    false    267                       1259    30742    e_p_c_talento_humanos    TABLE     I  CREATE TABLE proyecto.e_p_c_talento_humanos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    hours character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 +   DROP TABLE proyecto.e_p_c_talento_humanos;
       proyecto         heap    postgres    false    5                       1259    30740    e_p_c_talento_humanos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_talento_humanos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE proyecto.e_p_c_talento_humanos_id_seq;
       proyecto          postgres    false    5    258                       0    0    e_p_c_talento_humanos_id_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE proyecto.e_p_c_talento_humanos_id_seq OWNED BY proyecto.e_p_c_talento_humanos.id;
          proyecto          postgres    false    257                       1259    30807    e_p_c_tthhs_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_tthhs_servicios (
    servicio_id integer NOT NULL,
    tthh_id integer NOT NULL,
    cantidad integer NOT NULL
);
 +   DROP TABLE proyecto.e_p_c_tthhs_servicios;
       proyecto         heap    postgres    false    5                       1259    30846    e_p_c_turnos    TABLE     �   CREATE TABLE proyecto.e_p_c_turnos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 "   DROP TABLE proyecto.e_p_c_turnos;
       proyecto         heap    postgres    false    5                       1259    30844    e_p_c_turnos_id_seq    SEQUENCE     ~   CREATE SEQUENCE proyecto.e_p_c_turnos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE proyecto.e_p_c_turnos_id_seq;
       proyecto          postgres    false    5    275                       0    0    e_p_c_turnos_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE proyecto.e_p_c_turnos_id_seq OWNED BY proyecto.e_p_c_turnos.id;
          proyecto          postgres    false    274                       1259    30863    otroservicio_has_servicios    TABLE     �   CREATE TABLE proyecto.otroservicio_has_servicios (
    servicio_id integer NOT NULL,
    otro_servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);
 0   DROP TABLE proyecto.otroservicio_has_servicios;
       proyecto         heap    postgres    false    5                       1259    30910 
   categories    TABLE     �   CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(128) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.categories;
       public         heap    postgres    false                       1259    30908    categories_id_seq    SEQUENCE     z   CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.categories_id_seq;
       public          postgres    false    285                       0    0    categories_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;
          public          postgres    false    284            �            1259    30351    groups    TABLE        CREATE TABLE public.groups (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.groups;
       public         heap    postgres    false            �            1259    30362    groups_has_members    TABLE     �   CREATE TABLE public.groups_has_members (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 &   DROP TABLE public.groups_has_members;
       public         heap    postgres    false            �            1259    30360    groups_has_members_id_seq    SEQUENCE     �   CREATE SEQUENCE public.groups_has_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.groups_has_members_id_seq;
       public          postgres    false    221                       0    0    groups_has_members_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE public.groups_has_members_id_seq OWNED BY public.groups_has_members.id;
          public          postgres    false    220            �            1259    30349    groups_id_seq    SEQUENCE     v   CREATE SEQUENCE public.groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.groups_id_seq;
       public          postgres    false    219                       0    0    groups_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.groups_id_seq OWNED BY public.groups.id;
          public          postgres    false    218            �            1259    30273 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(191) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap    postgres    false            �            1259    30271    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public          postgres    false    207                       0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public          postgres    false    206            �            1259    30312    model_has_permissions    TABLE     �   CREATE TABLE public.model_has_permissions (
    permission_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);
 )   DROP TABLE public.model_has_permissions;
       public         heap    postgres    false            �            1259    30323    model_has_roles    TABLE     �   CREATE TABLE public.model_has_roles (
    role_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);
 #   DROP TABLE public.model_has_roles;
       public         heap    postgres    false            �            1259    30380    organigramas    TABLE     �  CREATE TABLE public.organigramas (
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
     DROP TABLE public.organigramas;
       public         heap    postgres    false            �            1259    30378    organigramas_id_seq    SEQUENCE     |   CREATE SEQUENCE public.organigramas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.organigramas_id_seq;
       public          postgres    false    223                        0    0    organigramas_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.organigramas_id_seq OWNED BY public.organigramas.id;
          public          postgres    false    222            �            1259    30292    password_resets    TABLE     �   CREATE TABLE public.password_resets (
    email character varying(191) NOT NULL,
    token character varying(191) NOT NULL,
    created_at timestamp(0) without time zone
);
 #   DROP TABLE public.password_resets;
       public         heap    postgres    false            �            1259    30298    permissions    TABLE     �   CREATE TABLE public.permissions (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.permissions;
       public         heap    postgres    false            �            1259    30296    permissions_id_seq    SEQUENCE     �   CREATE SEQUENCE public.permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.permissions_id_seq;
       public          postgres    false    212            !           0    0    permissions_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;
          public          postgres    false    211                       1259    30902    risks    TABLE     �   CREATE TABLE public.risks (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    detail character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.risks;
       public         heap    postgres    false                       1259    30900    risks_id_seq    SEQUENCE     u   CREATE SEQUENCE public.risks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.risks_id_seq;
       public          postgres    false    283            "           0    0    risks_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.risks_id_seq OWNED BY public.risks.id;
          public          postgres    false    282            �            1259    30334    role_has_permissions    TABLE     o   CREATE TABLE public.role_has_permissions (
    permission_id integer NOT NULL,
    role_id integer NOT NULL
);
 (   DROP TABLE public.role_has_permissions;
       public         heap    postgres    false            �            1259    30306    roles    TABLE     �   CREATE TABLE public.roles (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.roles;
       public         heap    postgres    false            �            1259    30304    roles_id_seq    SEQUENCE     �   CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.roles_id_seq;
       public          postgres    false    214            #           0    0    roles_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;
          public          postgres    false    213            �            1259    30401 	   servicios    TABLE     b  CREATE TABLE public.servicios (
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
    DROP TABLE public.servicios;
       public         heap    postgres    false            �            1259    30399    servicios_id_seq    SEQUENCE     y   CREATE SEQUENCE public.servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.servicios_id_seq;
       public          postgres    false    225            $           0    0    servicios_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.servicios_id_seq OWNED BY public.servicios.id;
          public          postgres    false    224            �            1259    30281    users    TABLE     x  CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    email character varying(191) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(191) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.users;
       public         heap    postgres    false            �            1259    30279    users_id_seq    SEQUENCE     u   CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public          postgres    false    209            %           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public          postgres    false    208            \           2604    30502    formulario_clasificadores id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_clasificadores ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_clasificadores_id_seq'::regclass);
 P   ALTER TABLE estadistica.formulario_clasificadores ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    235    234    235            Z           2604    30483 &   formulario_formulario_has_variables id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formulario_has_variables_id_seq'::regclass);
 Z   ALTER TABLE estadistica.formulario_formulario_has_variables ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    232    233    233            Y           2604    30460    formulario_formularios id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_formularios ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formularios_id_seq'::regclass);
 M   ALTER TABLE estadistica.formulario_formularios ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    230    231    231            X           2604    30439    formulario_items id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_items ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_items_id_seq'::regclass);
 G   ALTER TABLE estadistica.formulario_items ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    228    229    229            U           2604    30423    formulario_variables id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_variables_id_seq'::regclass);
 K   ALTER TABLE estadistica.formulario_variables ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    227    226    227            a           2604    30588    foda_analisis id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_analisis ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_analisis_id_seq'::regclass);
 F   ALTER TABLE planificacion.foda_analisis ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    242    243    243            c           2604    30612    foda_cruce_ambientes id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_cruce_ambientes_id_seq'::regclass);
 M   ALTER TABLE planificacion.foda_cruce_ambientes ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    245    244    245            `           2604    30570    foda_groups_has_perfiles id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_groups_has_perfiles_id_seq'::regclass);
 Q   ALTER TABLE planificacion.foda_groups_has_perfiles ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    240    241    241            ]           2604    30520    foda_models id    DEFAULT     ~   ALTER TABLE ONLY planificacion.foda_models ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_models_id_seq'::regclass);
 D   ALTER TABLE planificacion.foda_models ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    237    236    237            f           2604    30698     pei_profiles_has_dependencies id    DEFAULT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_dependencies_id_seq'::regclass);
 V   ALTER TABLE planificacion.pei_profiles_has_dependencies ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    252    251    252            {           2604    31032    pei_profiles_has_strategies id    DEFAULT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_strategies_id_seq'::regclass);
 T   ALTER TABLE planificacion.pei_profiles_has_strategies ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    294    295    295            g           2604    30716    peis_profiles_has_analysts id    DEFAULT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_analysts_id_seq'::regclass);
 S   ALTER TABLE planificacion.peis_profiles_has_analysts ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    253    254    254            |           2604    31050 !   peis_profiles_has_responsibles id    DEFAULT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_responsibles_id_seq'::regclass);
 W   ALTER TABLE planificacion.peis_profiles_has_responsibles ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    297    296    297            v           2604    30929    tasks id    DEFAULT     r   ALTER TABLE ONLY planificacion.tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_id_seq'::regclass);
 >   ALTER TABLE planificacion.tasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    288    289    289            x           2604    30943    tasks_has_analysts id    DEFAULT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_analysts_id_seq'::regclass);
 K   ALTER TABLE planificacion.tasks_has_analysts ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    290    291    291            y           2604    30961    tasks_has_type_tasks id    DEFAULT     �   ALTER TABLE ONLY planificacion.tasks_has_type_tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_type_tasks_id_seq'::regclass);
 M   ALTER TABLE planificacion.tasks_has_type_tasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    293    292    293            u           2604    30921    typetasks id    DEFAULT     z   ALTER TABLE ONLY planificacion.typetasks ALTER COLUMN id SET DEFAULT nextval('planificacion.typetasks_id_seq'::regclass);
 B   ALTER TABLE planificacion.typetasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    287    286    287            j           2604    30756    e_p_c_equipamientos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_equipamientos_id_seq'::regclass);
 G   ALTER TABLE proyecto.e_p_c_equipamientos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    259    260    260            h           2604    30734    e_p_c_especialidads id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_especialidads ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_especialidads_id_seq'::regclass);
 G   ALTER TABLE proyecto.e_p_c_especialidads ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    255    256    256            r           2604    30881    e_p_c_estandars id    DEFAULT     |   ALTER TABLE ONLY proyecto.e_p_c_estandars ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_estandars_id_seq'::regclass);
 C   ALTER TABLE proyecto.e_p_c_estandars ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    279    280    280            o           2604    30838    e_p_c_horarios id    DEFAULT     z   ALTER TABLE ONLY proyecto.e_p_c_horarios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_horarios_id_seq'::regclass);
 B   ALTER TABLE proyecto.e_p_c_horarios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    273    272    273            k           2604    30764    e_p_c_infraestructuras id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_infraestructuras_id_seq'::regclass);
 J   ALTER TABLE proyecto.e_p_c_infraestructuras ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    261    262    262            m           2604    30780    e_p_c_medicamento_insumos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_medicamento_insumos_id_seq'::regclass);
 M   ALTER TABLE proyecto.e_p_c_medicamento_insumos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    265    266    266            l           2604    30772    e_p_c_otro_servicios id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_otro_servicios_id_seq'::regclass);
 H   ALTER TABLE proyecto.e_p_c_otro_servicios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    264    263    264            q           2604    30857    e_p_c_prestaciones id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_prestaciones ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_prestaciones_id_seq'::regclass);
 F   ALTER TABLE proyecto.e_p_c_prestaciones ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    276    277    277            n           2604    30788    e_p_c_servicios id    DEFAULT     |   ALTER TABLE ONLY proyecto.e_p_c_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_servicios_id_seq'::regclass);
 C   ALTER TABLE proyecto.e_p_c_servicios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    267    268    268            i           2604    30745    e_p_c_talento_humanos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_talento_humanos_id_seq'::regclass);
 I   ALTER TABLE proyecto.e_p_c_talento_humanos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    257    258    258            p           2604    30849    e_p_c_turnos id    DEFAULT     v   ALTER TABLE ONLY proyecto.e_p_c_turnos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_turnos_id_seq'::regclass);
 @   ALTER TABLE proyecto.e_p_c_turnos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    274    275    275            t           2604    30913    categories id    DEFAULT     n   ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);
 <   ALTER TABLE public.categories ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    284    285    285            K           2604    30354 	   groups id    DEFAULT     f   ALTER TABLE ONLY public.groups ALTER COLUMN id SET DEFAULT nextval('public.groups_id_seq'::regclass);
 8   ALTER TABLE public.groups ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    219    218    219            N           2604    30365    groups_has_members id    DEFAULT     ~   ALTER TABLE ONLY public.groups_has_members ALTER COLUMN id SET DEFAULT nextval('public.groups_has_members_id_seq'::regclass);
 D   ALTER TABLE public.groups_has_members ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    221    220    221            G           2604    30276    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    206    207    207            O           2604    30383    organigramas id    DEFAULT     r   ALTER TABLE ONLY public.organigramas ALTER COLUMN id SET DEFAULT nextval('public.organigramas_id_seq'::regclass);
 >   ALTER TABLE public.organigramas ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    223    222    223            I           2604    30301    permissions id    DEFAULT     p   ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);
 =   ALTER TABLE public.permissions ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    211    212    212            s           2604    30905    risks id    DEFAULT     d   ALTER TABLE ONLY public.risks ALTER COLUMN id SET DEFAULT nextval('public.risks_id_seq'::regclass);
 7   ALTER TABLE public.risks ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    283    282    283            J           2604    30309    roles id    DEFAULT     d   ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);
 7   ALTER TABLE public.roles ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    213    214    214            R           2604    30404    servicios id    DEFAULT     l   ALTER TABLE ONLY public.servicios ALTER COLUMN id SET DEFAULT nextval('public.servicios_id_seq'::regclass);
 ;   ALTER TABLE public.servicios ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    225    224    225            H           2604    30284    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    209    208    209            �          0    30499    formulario_clasificadores 
   TABLE DATA           |   COPY estadistica.formulario_clasificadores (id, clasificador, clasificador_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    235   z0      �          0    30480 #   formulario_formulario_has_variables 
   TABLE DATA           �   COPY estadistica.formulario_formulario_has_variables (id, formulario_id, variable_id, selected, selected_variable_id, value, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    233   �0      �          0    30457    formulario_formularios 
   TABLE DATA           �   COPY estadistica.formulario_formularios (id, formulario, status, dependencia_emisor_id, dependencia_receptor_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    231   �0      �          0    30436    formulario_items 
   TABLE DATA           r   COPY estadistica.formulario_items (id, item, questions, variable_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    229   �0      �          0    30420    formulario_variables 
   TABLE DATA           {   COPY estadistica.formulario_variables (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    227   �0      �          0    30585    foda_analisis 
   TABLE DATA           �   COPY planificacion.foda_analisis (id, user_id, perfil_id, aspecto_id, tipo, ocurrencia, impacto, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    243   1      �          0    30552    foda_categorias_has_perfil 
   TABLE DATA           k   COPY planificacion.foda_categorias_has_perfil (perfil_id, category_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    239   ~G      �          0    30609    foda_cruce_ambientes 
   TABLE DATA           w   COPY planificacion.foda_cruce_ambientes (id, user_id, perfil_id, estrategia, tipo, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    245   �K      �          0    30664 !   foda_cruce_ambientes_has_amenazas 
   TABLE DATA           X   COPY planificacion.foda_cruce_ambientes_has_amenazas (cruce_id, amenaza_id) FROM stdin;
    planificacion          postgres    false    249   �K      �          0    30651 $   foda_cruce_ambientes_has_debilidades 
   TABLE DATA           ]   COPY planificacion.foda_cruce_ambientes_has_debilidades (cruce_id, debilidad_id) FROM stdin;
    planificacion          postgres    false    248   �K      �          0    30625 #   foda_cruce_ambientes_has_fortalezas 
   TABLE DATA           \   COPY planificacion.foda_cruce_ambientes_has_fortalezas (cruce_id, fortaleza_id) FROM stdin;
    planificacion          postgres    false    246   �K      �          0    30638 &   foda_cruce_ambientes_has_oportunidades 
   TABLE DATA           a   COPY planificacion.foda_cruce_ambientes_has_oportunidades (cruce_id, oportunidad_id) FROM stdin;
    planificacion          postgres    false    247   L      �          0    30567    foda_groups_has_perfiles 
   TABLE DATA           j   COPY planificacion.foda_groups_has_perfiles (id, perfil_id, group_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    241   $L      �          0    30517    foda_models 
   TABLE DATA           �   COPY planificacion.foda_models (id, type, name, owner, environment, description, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    237   AL      �          0    30529    foda_perfiles 
   TABLE DATA           �   COPY planificacion.foda_perfiles (id, name, context, type, model_id, group_id, dependency_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    238   -`      �          0    30677    pei_profiles 
   TABLE DATA           <  COPY planificacion.pei_profiles (id, type, level, mision, vision, "values", period, numerator, operator, denominator, goal, progress, group_id, _lft, _rgt, deleted_at, created_at, updated_at, dependency_id, name, action, indicator, baseline, target, parent_id, year_start, year_end, user_id, order_item) FROM stdin;
    planificacion          postgres    false    250   �c      �          0    30695    pei_profiles_has_dependencies 
   TABLE DATA           y   COPY planificacion.pei_profiles_has_dependencies (id, pei_profile_id, dependency_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    252   ��      �          0    31029    pei_profiles_has_strategies 
   TABLE DATA           q   COPY planificacion.pei_profiles_has_strategies (id, profile_id, strategy_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    295   ��      �          0    30713    peis_profiles_has_analysts 
   TABLE DATA           s   COPY planificacion.peis_profiles_has_analysts (id, pei_profile_id, analyst_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    254   �      �          0    31047    peis_profiles_has_responsibles 
   TABLE DATA           w   COPY planificacion.peis_profiles_has_responsibles (id, profile_id, responsible_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    297   ��      �          0    30926    tasks 
   TABLE DATA           ]   COPY planificacion.tasks (id, group_id, details, status, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    289   9�      �          0    30940    tasks_has_analysts 
   TABLE DATA           d   COPY planificacion.tasks_has_analysts (id, task_id, analyst_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    291   ��      �          0    30958    tasks_has_type_tasks 
   TABLE DATA           p   COPY planificacion.tasks_has_type_tasks (id, task_id, type_task_id, status, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    293   y�      �          0    30918 	   typetasks 
   TABLE DATA           p   COPY planificacion.typetasks (id, name, typetaskable_id, typetaskable_type, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    287   x�      �          0    30753    e_p_c_equipamientos 
   TABLE DATA           ]   COPY proyecto.e_p_c_equipamientos (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    260   ��      �          0    30794    e_p_c_equipamientos_servicios 
   TABLE DATA           a   COPY proyecto.e_p_c_equipamientos_servicios (servicio_id, equipamiento_id, cantidad) FROM stdin;
    proyecto          postgres    false    269   ��      �          0    30731    e_p_c_especialidads 
   TABLE DATA           e   COPY proyecto.e_p_c_especialidads (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    256   �      �          0    30878    e_p_c_estandars 
   TABLE DATA           [   COPY proyecto.e_p_c_estandars (id, item, type, detail, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    280   3�      �          0    30887    e_p_c_estandars_servicios 
   TABLE DATA           Y   COPY proyecto.e_p_c_estandars_servicios (estandar_id, servicio_id, cantidad) FROM stdin;
    proyecto          postgres    false    281   P�      �          0    30835    e_p_c_horarios 
   TABLE DATA           b   COPY proyecto.e_p_c_horarios (id, item, start_time, end_time, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    273   m�      �          0    30820    e_p_c_infraestructura_servicio 
   TABLE DATA           e   COPY proyecto.e_p_c_infraestructura_servicio (servicio_id, infraestructura_id, cantidad) FROM stdin;
    proyecto          postgres    false    271   ��      �          0    30761    e_p_c_infraestructuras 
   TABLE DATA           m   COPY proyecto.e_p_c_infraestructuras (id, item, type, measurement, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    262   ��      �          0    30777    e_p_c_medicamento_insumos 
   TABLE DATA           c   COPY proyecto.e_p_c_medicamento_insumos (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    266   ��      �          0    30769    e_p_c_otro_servicios 
   TABLE DATA           ^   COPY proyecto.e_p_c_otro_servicios (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    264   ��      �          0    30854    e_p_c_prestaciones 
   TABLE DATA           d   COPY proyecto.e_p_c_prestaciones (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    277   ��      �          0    30785    e_p_c_servicios 
   TABLE DATA           `   COPY proyecto.e_p_c_servicios (id, item, type, description, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    268   �      �          0    30742    e_p_c_talento_humanos 
   TABLE DATA           f   COPY proyecto.e_p_c_talento_humanos (id, item, hours, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    258   8�      �          0    30807    e_p_c_tthhs_servicios 
   TABLE DATA           Q   COPY proyecto.e_p_c_tthhs_servicios (servicio_id, tthh_id, cantidad) FROM stdin;
    proyecto          postgres    false    270   U�      �          0    30846    e_p_c_turnos 
   TABLE DATA           J   COPY proyecto.e_p_c_turnos (id, item, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    275   r�      �          0    30863    otroservicio_has_servicios 
   TABLE DATA           _   COPY proyecto.otroservicio_has_servicios (servicio_id, otro_servicio_id, cantidad) FROM stdin;
    proyecto          postgres    false    278   ��      �          0    30910 
   categories 
   TABLE DATA           F   COPY public.categories (id, name, created_at, updated_at) FROM stdin;
    public          postgres    false    285   ��      �          0    30351    groups 
   TABLE DATA           Y   COPY public.groups (id, name, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
    public          postgres    false    219   ��      �          0    30362    groups_has_members 
   TABLE DATA           [   COPY public.groups_has_members (id, group_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    221   &�      �          0    30273 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public          postgres    false    207   ��      �          0    30312    model_has_permissions 
   TABLE DATA           T   COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
    public          postgres    false    215   ��      �          0    30323    model_has_roles 
   TABLE DATA           H   COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
    public          postgres    false    216   ��      �          0    30380    organigramas 
   TABLE DATA           �   COPY public.organigramas (id, dependency, _lft, _rgt, parent_id, manager, phone, email, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    223   �      �          0    30292    password_resets 
   TABLE DATA           C   COPY public.password_resets (email, token, created_at) FROM stdin;
    public          postgres    false    210   ��      �          0    30298    permissions 
   TABLE DATA           S   COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    212   ��      �          0    30902    risks 
   TABLE DATA           I   COPY public.risks (id, name, detail, created_at, updated_at) FROM stdin;
    public          postgres    false    283   3�      �          0    30334    role_has_permissions 
   TABLE DATA           F   COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
    public          postgres    false    217   P�      �          0    30306    roles 
   TABLE DATA           M   COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    214   x�      �          0    30401 	   servicios 
   TABLE DATA           k   COPY public.servicios (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    225   ��      �          0    30281    users 
   TABLE DATA           u   COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
    public          postgres    false    209   �      &           0    0     formulario_clasificadores_id_seq    SEQUENCE SET     T   SELECT pg_catalog.setval('estadistica.formulario_clasificadores_id_seq', 1, false);
          estadistica          postgres    false    234            '           0    0 *   formulario_formulario_has_variables_id_seq    SEQUENCE SET     ^   SELECT pg_catalog.setval('estadistica.formulario_formulario_has_variables_id_seq', 1, false);
          estadistica          postgres    false    232            (           0    0    formulario_formularios_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('estadistica.formulario_formularios_id_seq', 1, false);
          estadistica          postgres    false    230            )           0    0    formulario_items_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('estadistica.formulario_items_id_seq', 1, false);
          estadistica          postgres    false    228            *           0    0    formulario_variables_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('estadistica.formulario_variables_id_seq', 1, false);
          estadistica          postgres    false    226            +           0    0    foda_analisis_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('planificacion.foda_analisis_id_seq', 515, true);
          planificacion          postgres    false    242            ,           0    0    foda_cruce_ambientes_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('planificacion.foda_cruce_ambientes_id_seq', 26, true);
          planificacion          postgres    false    244            -           0    0    foda_groups_has_perfiles_id_seq    SEQUENCE SET     U   SELECT pg_catalog.setval('planificacion.foda_groups_has_perfiles_id_seq', 1, false);
          planificacion          postgres    false    240            .           0    0    foda_models_id_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('planificacion.foda_models_id_seq', 101, true);
          planificacion          postgres    false    236            /           0    0 $   pei_profiles_has_dependencies_id_seq    SEQUENCE SET     Z   SELECT pg_catalog.setval('planificacion.pei_profiles_has_dependencies_id_seq', 1, false);
          planificacion          postgres    false    251            0           0    0 "   pei_profiles_has_strategies_id_seq    SEQUENCE SET     X   SELECT pg_catalog.setval('planificacion.pei_profiles_has_strategies_id_seq', 10, true);
          planificacion          postgres    false    294            1           0    0 !   peis_profiles_has_analysts_id_seq    SEQUENCE SET     W   SELECT pg_catalog.setval('planificacion.peis_profiles_has_analysts_id_seq', 32, true);
          planificacion          postgres    false    253            2           0    0 %   peis_profiles_has_responsibles_id_seq    SEQUENCE SET     \   SELECT pg_catalog.setval('planificacion.peis_profiles_has_responsibles_id_seq', 115, true);
          planificacion          postgres    false    296            3           0    0    tasks_has_analysts_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('planificacion.tasks_has_analysts_id_seq', 46, true);
          planificacion          postgres    false    290            4           0    0    tasks_has_type_tasks_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('planificacion.tasks_has_type_tasks_id_seq', 78, true);
          planificacion          postgres    false    292            5           0    0    tasks_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('planificacion.tasks_id_seq', 42, true);
          planificacion          postgres    false    288            6           0    0    typetasks_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('planificacion.typetasks_id_seq', 49, true);
          planificacion          postgres    false    286            7           0    0    e_p_c_equipamientos_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('proyecto.e_p_c_equipamientos_id_seq', 1, false);
          proyecto          postgres    false    259            8           0    0    e_p_c_especialidads_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('proyecto.e_p_c_especialidads_id_seq', 1, false);
          proyecto          postgres    false    255            9           0    0    e_p_c_estandars_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('proyecto.e_p_c_estandars_id_seq', 1, false);
          proyecto          postgres    false    279            :           0    0    e_p_c_horarios_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('proyecto.e_p_c_horarios_id_seq', 1, false);
          proyecto          postgres    false    272            ;           0    0    e_p_c_infraestructuras_id_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('proyecto.e_p_c_infraestructuras_id_seq', 1, false);
          proyecto          postgres    false    261            <           0    0     e_p_c_medicamento_insumos_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('proyecto.e_p_c_medicamento_insumos_id_seq', 1, false);
          proyecto          postgres    false    265            =           0    0    e_p_c_otro_servicios_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('proyecto.e_p_c_otro_servicios_id_seq', 1, false);
          proyecto          postgres    false    263            >           0    0    e_p_c_prestaciones_id_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('proyecto.e_p_c_prestaciones_id_seq', 1, false);
          proyecto          postgres    false    276            ?           0    0    e_p_c_servicios_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('proyecto.e_p_c_servicios_id_seq', 1, false);
          proyecto          postgres    false    267            @           0    0    e_p_c_talento_humanos_id_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('proyecto.e_p_c_talento_humanos_id_seq', 1, false);
          proyecto          postgres    false    257            A           0    0    e_p_c_turnos_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('proyecto.e_p_c_turnos_id_seq', 1, false);
          proyecto          postgres    false    274            B           0    0    categories_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.categories_id_seq', 1, false);
          public          postgres    false    284            C           0    0    groups_has_members_id_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('public.groups_has_members_id_seq', 251, true);
          public          postgres    false    220            D           0    0    groups_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.groups_id_seq', 39, true);
          public          postgres    false    218            E           0    0    migrations_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.migrations_id_seq', 49, true);
          public          postgres    false    206            F           0    0    organigramas_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.organigramas_id_seq', 14, true);
          public          postgres    false    222            G           0    0    permissions_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.permissions_id_seq', 4, true);
          public          postgres    false    211            H           0    0    risks_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.risks_id_seq', 1, false);
          public          postgres    false    282            I           0    0    roles_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.roles_id_seq', 3, true);
          public          postgres    false    213            J           0    0    servicios_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.servicios_id_seq', 1, false);
          public          postgres    false    224            K           0    0    users_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.users_id_seq', 104, true);
          public          postgres    false    208            �           2606    30504 8   formulario_clasificadores formulario_clasificadores_pkey 
   CONSTRAINT     {   ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT formulario_clasificadores_pkey PRIMARY KEY (id);
 g   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT formulario_clasificadores_pkey;
       estadistica            postgres    false    235            �           2606    30486 L   formulario_formulario_has_variables formulario_formulario_has_variables_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT formulario_formulario_has_variables_pkey PRIMARY KEY (id);
 {   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT formulario_formulario_has_variables_pkey;
       estadistica            postgres    false    233            �           2606    30462 2   formulario_formularios formulario_formularios_pkey 
   CONSTRAINT     u   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT formulario_formularios_pkey PRIMARY KEY (id);
 a   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT formulario_formularios_pkey;
       estadistica            postgres    false    231            �           2606    30444 &   formulario_items formulario_items_pkey 
   CONSTRAINT     i   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT formulario_items_pkey PRIMARY KEY (id);
 U   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT formulario_items_pkey;
       estadistica            postgres    false    229            �           2606    30427 .   formulario_variables formulario_variables_pkey 
   CONSTRAINT     q   ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT formulario_variables_pkey PRIMARY KEY (id);
 ]   ALTER TABLE ONLY estadistica.formulario_variables DROP CONSTRAINT formulario_variables_pkey;
       estadistica            postgres    false    227            �           2606    30591     foda_analisis foda_analisis_pkey 
   CONSTRAINT     e   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT foda_analisis_pkey PRIMARY KEY (id);
 Q   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT foda_analisis_pkey;
       planificacion            postgres    false    243            �           2606    30614 .   foda_cruce_ambientes foda_cruce_ambientes_pkey 
   CONSTRAINT     s   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT foda_cruce_ambientes_pkey PRIMARY KEY (id);
 _   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT foda_cruce_ambientes_pkey;
       planificacion            postgres    false    245            �           2606    30572 6   foda_groups_has_perfiles foda_groups_has_perfiles_pkey 
   CONSTRAINT     {   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT foda_groups_has_perfiles_pkey PRIMARY KEY (id);
 g   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT foda_groups_has_perfiles_pkey;
       planificacion            postgres    false    241            �           2606    30527    foda_models foda_models_pkey 
   CONSTRAINT     a   ALTER TABLE ONLY planificacion.foda_models
    ADD CONSTRAINT foda_models_pkey PRIMARY KEY (id);
 M   ALTER TABLE ONLY planificacion.foda_models DROP CONSTRAINT foda_models_pkey;
       planificacion            postgres    false    237            �           2606    30551     foda_perfiles foda_perfiles_pkey 
   CONSTRAINT     e   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT foda_perfiles_pkey PRIMARY KEY (id);
 Q   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT foda_perfiles_pkey;
       planificacion            postgres    false    238            �           2606    30700 @   pei_profiles_has_dependencies pei_profiles_has_dependencies_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT pei_profiles_has_dependencies_pkey PRIMARY KEY (id);
 q   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT pei_profiles_has_dependencies_pkey;
       planificacion            postgres    false    252            �           2606    31034 <   pei_profiles_has_strategies pei_profiles_has_strategies_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT pei_profiles_has_strategies_pkey PRIMARY KEY (id);
 m   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT pei_profiles_has_strategies_pkey;
       planificacion            postgres    false    295            �           2606    30692    pei_profiles pei_profiles_pkey 
   CONSTRAINT     c   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT pei_profiles_pkey PRIMARY KEY (id);
 O   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT pei_profiles_pkey;
       planificacion            postgres    false    250            �           2606    30718 :   peis_profiles_has_analysts peis_profiles_has_analysts_pkey 
   CONSTRAINT        ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT peis_profiles_has_analysts_pkey PRIMARY KEY (id);
 k   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT peis_profiles_has_analysts_pkey;
       planificacion            postgres    false    254            �           2606    31052 B   peis_profiles_has_responsibles peis_profiles_has_responsibles_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT peis_profiles_has_responsibles_pkey PRIMARY KEY (id);
 s   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT peis_profiles_has_responsibles_pkey;
       planificacion            postgres    false    297            �           2606    30945 *   tasks_has_analysts tasks_has_analysts_pkey 
   CONSTRAINT     o   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT tasks_has_analysts_pkey PRIMARY KEY (id);
 [   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT tasks_has_analysts_pkey;
       planificacion            postgres    false    291            �           2606    30964 .   tasks_has_type_tasks tasks_has_type_tasks_pkey 
   CONSTRAINT     s   ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT tasks_has_type_tasks_pkey PRIMARY KEY (id);
 _   ALTER TABLE ONLY planificacion.tasks_has_type_tasks DROP CONSTRAINT tasks_has_type_tasks_pkey;
       planificacion            postgres    false    293            �           2606    30932    tasks tasks_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT tasks_pkey PRIMARY KEY (id);
 A   ALTER TABLE ONLY planificacion.tasks DROP CONSTRAINT tasks_pkey;
       planificacion            postgres    false    289            �           2606    30923    typetasks typetasks_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY planificacion.typetasks
    ADD CONSTRAINT typetasks_pkey PRIMARY KEY (id);
 I   ALTER TABLE ONLY planificacion.typetasks DROP CONSTRAINT typetasks_pkey;
       planificacion            postgres    false    287            �           2606    30758 ,   e_p_c_equipamientos e_p_c_equipamientos_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY proyecto.e_p_c_equipamientos
    ADD CONSTRAINT e_p_c_equipamientos_pkey PRIMARY KEY (id);
 X   ALTER TABLE ONLY proyecto.e_p_c_equipamientos DROP CONSTRAINT e_p_c_equipamientos_pkey;
       proyecto            postgres    false    260            �           2606    30739 ,   e_p_c_especialidads e_p_c_especialidads_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY proyecto.e_p_c_especialidads
    ADD CONSTRAINT e_p_c_especialidads_pkey PRIMARY KEY (id);
 X   ALTER TABLE ONLY proyecto.e_p_c_especialidads DROP CONSTRAINT e_p_c_especialidads_pkey;
       proyecto            postgres    false    256            �           2606    30886 $   e_p_c_estandars e_p_c_estandars_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY proyecto.e_p_c_estandars
    ADD CONSTRAINT e_p_c_estandars_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY proyecto.e_p_c_estandars DROP CONSTRAINT e_p_c_estandars_pkey;
       proyecto            postgres    false    280            �           2606    30843 "   e_p_c_horarios e_p_c_horarios_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY proyecto.e_p_c_horarios
    ADD CONSTRAINT e_p_c_horarios_pkey PRIMARY KEY (id);
 N   ALTER TABLE ONLY proyecto.e_p_c_horarios DROP CONSTRAINT e_p_c_horarios_pkey;
       proyecto            postgres    false    273            �           2606    30766 2   e_p_c_infraestructuras e_p_c_infraestructuras_pkey 
   CONSTRAINT     r   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras
    ADD CONSTRAINT e_p_c_infraestructuras_pkey PRIMARY KEY (id);
 ^   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras DROP CONSTRAINT e_p_c_infraestructuras_pkey;
       proyecto            postgres    false    262            �           2606    30782 8   e_p_c_medicamento_insumos e_p_c_medicamento_insumos_pkey 
   CONSTRAINT     x   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos
    ADD CONSTRAINT e_p_c_medicamento_insumos_pkey PRIMARY KEY (id);
 d   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos DROP CONSTRAINT e_p_c_medicamento_insumos_pkey;
       proyecto            postgres    false    266            �           2606    30774 .   e_p_c_otro_servicios e_p_c_otro_servicios_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios
    ADD CONSTRAINT e_p_c_otro_servicios_pkey PRIMARY KEY (id);
 Z   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios DROP CONSTRAINT e_p_c_otro_servicios_pkey;
       proyecto            postgres    false    264            �           2606    30862 *   e_p_c_prestaciones e_p_c_prestaciones_pkey 
   CONSTRAINT     j   ALTER TABLE ONLY proyecto.e_p_c_prestaciones
    ADD CONSTRAINT e_p_c_prestaciones_pkey PRIMARY KEY (id);
 V   ALTER TABLE ONLY proyecto.e_p_c_prestaciones DROP CONSTRAINT e_p_c_prestaciones_pkey;
       proyecto            postgres    false    277            �           2606    30793 $   e_p_c_servicios e_p_c_servicios_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY proyecto.e_p_c_servicios
    ADD CONSTRAINT e_p_c_servicios_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY proyecto.e_p_c_servicios DROP CONSTRAINT e_p_c_servicios_pkey;
       proyecto            postgres    false    268            �           2606    30750 0   e_p_c_talento_humanos e_p_c_talento_humanos_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos
    ADD CONSTRAINT e_p_c_talento_humanos_pkey PRIMARY KEY (id);
 \   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos DROP CONSTRAINT e_p_c_talento_humanos_pkey;
       proyecto            postgres    false    258            �           2606    30851    e_p_c_turnos e_p_c_turnos_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY proyecto.e_p_c_turnos
    ADD CONSTRAINT e_p_c_turnos_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY proyecto.e_p_c_turnos DROP CONSTRAINT e_p_c_turnos_pkey;
       proyecto            postgres    false    275            �           2606    30915    categories categories_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.categories DROP CONSTRAINT categories_pkey;
       public            postgres    false    285            �           2606    30367 *   groups_has_members groups_has_members_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_pkey PRIMARY KEY (id);
 T   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_pkey;
       public            postgres    false    221            �           2606    30358    groups groups_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.groups DROP CONSTRAINT groups_pkey;
       public            postgres    false    219            ~           2606    30278    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public            postgres    false    207            �           2606    30322 0   model_has_permissions model_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);
 Z   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_pkey;
       public            postgres    false    215    215    215            �           2606    30333 $   model_has_roles model_has_roles_pkey 
   CONSTRAINT     }   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);
 N   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_pkey;
       public            postgres    false    216    216    216            �           2606    30398 &   organigramas organigramas_email_unique 
   CONSTRAINT     b   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_email_unique UNIQUE (email);
 P   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_email_unique;
       public            postgres    false    223            �           2606    30390    organigramas organigramas_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_pkey;
       public            postgres    false    223            �           2606    30303    permissions permissions_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_pkey;
       public            postgres    false    212            �           2606    30907    risks risks_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.risks
    ADD CONSTRAINT risks_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.risks DROP CONSTRAINT risks_pkey;
       public            postgres    false    283            �           2606    30348 .   role_has_permissions role_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);
 X   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_pkey;
       public            postgres    false    217    217            �           2606    30311    roles roles_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
       public            postgres    false    214            �           2606    30408    servicios servicios_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.servicios DROP CONSTRAINT servicios_pkey;
       public            postgres    false    225            �           2606    30291    users users_email_unique 
   CONSTRAINT     T   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
       public            postgres    false    209            �           2606    30289    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    209            �           1259    30428 :   estadistica_formulario_variables__lft__rgt_parent_id_index    INDEX     �   CREATE INDEX estadistica_formulario_variables__lft__rgt_parent_id_index ON estadistica.formulario_variables USING btree (_lft, _rgt, parent_id);
 S   DROP INDEX estadistica.estadistica_formulario_variables__lft__rgt_parent_id_index;
       estadistica            postgres    false    227    227    227            �           1259    30528 3   planificacion_foda_models__lft__rgt_parent_id_index    INDEX     �   CREATE INDEX planificacion_foda_models__lft__rgt_parent_id_index ON planificacion.foda_models USING btree (_lft, _rgt, parent_id);
 N   DROP INDEX planificacion.planificacion_foda_models__lft__rgt_parent_id_index;
       planificacion            postgres    false    237    237    237            �           1259    30359     groups__lft__rgt_parent_id_index    INDEX     d   CREATE INDEX groups__lft__rgt_parent_id_index ON public.groups USING btree (_lft, _rgt, parent_id);
 4   DROP INDEX public.groups__lft__rgt_parent_id_index;
       public            postgres    false    219    219    219            �           1259    30315 /   model_has_permissions_model_id_model_type_index    INDEX     �   CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);
 C   DROP INDEX public.model_has_permissions_model_id_model_type_index;
       public            postgres    false    215    215            �           1259    30326 )   model_has_roles_model_id_model_type_index    INDEX     u   CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);
 =   DROP INDEX public.model_has_roles_model_id_model_type_index;
       public            postgres    false    216    216            �           1259    30391 &   organigramas__lft__rgt_parent_id_index    INDEX     p   CREATE INDEX organigramas__lft__rgt_parent_id_index ON public.organigramas USING btree (_lft, _rgt, parent_id);
 :   DROP INDEX public.organigramas__lft__rgt_parent_id_index;
       public            postgres    false    223    223    223            �           1259    30295    password_resets_email_index    INDEX     X   CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);
 /   DROP INDEX public.password_resets_email_index;
       public            postgres    false    210            �           1259    30409 #   servicios__lft__rgt_parent_id_index    INDEX     j   CREATE INDEX servicios__lft__rgt_parent_id_index ON public.servicios USING btree (_lft, _rgt, parent_id);
 7   DROP INDEX public.servicios__lft__rgt_parent_id_index;
       public            postgres    false    225    225    225            �           2606    30505 W   formulario_clasificadores estadistica_formulario_clasificadores_clasificador_id_foreign    FK CONSTRAINT        ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign FOREIGN KEY (clasificador_id) REFERENCES estadistica.formulario_clasificadores(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign;
       estadistica          postgres    false    235    235    3239            �           2606    30510 O   formulario_clasificadores estadistica_formulario_clasificadores_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 ~   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign;
       estadistica          postgres    false    235    209    3202            �           2606    30487 c   formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_formulario_id_f    FK CONSTRAINT       ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f FOREIGN KEY (formulario_id) REFERENCES estadistica.formulario_formularios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f;
       estadistica          postgres    false    231    233    3235            �           2606    30492 c   formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_variable_id_for    FK CONSTRAINT       ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for;
       estadistica          postgres    false    233    3231    227            �           2606    30463 V   formulario_formularios estadistica_formulario_formularios_dependencia_emisor_id_foreig    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig FOREIGN KEY (dependencia_emisor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig;
       estadistica          postgres    false    231    223    3225            �           2606    30468 V   formulario_formularios estadistica_formulario_formularios_dependencia_receptor_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore FOREIGN KEY (dependencia_receptor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore;
       estadistica          postgres    false    223    3225    231            �           2606    30473 I   formulario_formularios estadistica_formulario_formularios_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_user_id_foreign;
       estadistica          postgres    false    209    3202    231            �           2606    30450 =   formulario_items estadistica_formulario_items_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 l   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT estadistica_formulario_items_user_id_foreign;
       estadistica          postgres    false    209    3202    229            �           2606    30445 A   formulario_items estadistica_formulario_items_variable_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT estadistica_formulario_items_variable_id_foreign;
       estadistica          postgres    false    227    3231    229            �           2606    30429 E   formulario_variables estadistica_formulario_variables_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT estadistica_formulario_variables_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY estadistica.formulario_variables DROP CONSTRAINT estadistica_formulario_variables_user_id_foreign;
       estadistica          postgres    false    3202    209    227                       2606    31009    pei_profiles fk_dependency    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT fk_dependency FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 K   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT fk_dependency;
       planificacion          postgres    false    223    3225    250                       2606    31110    pei_profiles fk_user_id    FK CONSTRAINT     }   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES public.users(id);
 H   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT fk_user_id;
       planificacion          postgres    false    250    209    3202            �           2606    30602 <   foda_analisis planificacion_foda_analisis_aspecto_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign FOREIGN KEY (aspecto_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 m   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign;
       planificacion          postgres    false    237    243    3241            �           2606    30597 ;   foda_analisis planificacion_foda_analisis_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 l   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_perfil_id_foreign;
       planificacion          postgres    false    238    3244    243            �           2606    30592 9   foda_analisis planificacion_foda_analisis_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 j   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_user_id_foreign;
       planificacion          postgres    false    3202    209    243            �           2606    30560 W   foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_category_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign FOREIGN KEY (category_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil DROP CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign;
       planificacion          postgres    false    237    3241    239            �           2606    30555 U   foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil DROP CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign;
       planificacion          postgres    false    3244    238    239                       2606    30672 a   foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore FOREIGN KEY (amenaza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore;
       planificacion          postgres    false    249    3241    237                       2606    30667 a   foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig;
       planificacion          postgres    false    249    245    3250                       2606    30654 d   foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for;
       planificacion          postgres    false    3250    245    248                       2606    30659 d   foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id    FK CONSTRAINT        ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id FOREIGN KEY (debilidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id;
       planificacion          postgres    false    237    248    3241            �           2606    30628 c   foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore;
       planificacion          postgres    false    3250    246    245            �           2606    30633 c   foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_ FOREIGN KEY (fortaleza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_;
       planificacion          postgres    false    246    3241    237            �           2606    30641 f   foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f;
       planificacion          postgres    false    3250    247    245                        2606    30646 f   foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_oportunida    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida FOREIGN KEY (oportunidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida;
       planificacion          postgres    false    3241    237    247            �           2606    30620 I   foda_cruce_ambientes planificacion_foda_cruce_ambientes_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 z   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign;
       planificacion          postgres    false    245    3244    238            �           2606    30615 G   foda_cruce_ambientes planificacion_foda_cruce_ambientes_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign;
       planificacion          postgres    false    209    3202    245            �           2606    30578 P   foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign;
       planificacion          postgres    false    219    3218    241            �           2606    30573 Q   foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign;
       planificacion          postgres    false    3244    241    238            �           2606    30545 ?   foda_perfiles planificacion_foda_perfiles_dependency_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign;
       planificacion          postgres    false    238    3225    223            �           2606    30540 :   foda_perfiles planificacion_foda_perfiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 k   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_group_id_foreign;
       planificacion          postgres    false    219    238    3218            �           2606    30535 :   foda_perfiles planificacion_foda_perfiles_model_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_model_id_foreign FOREIGN KEY (model_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 k   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_model_id_foreign;
       planificacion          postgres    false    237    238    3241                       2606    30685 8   pei_profiles planificacion_pei_profiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 i   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT planificacion_pei_profiles_group_id_foreign;
       planificacion          postgres    false    250    3218    219            	           2606    30706 ]   pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_dependency_id_forei    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei;
       planificacion          postgres    false    223    252    3225                       2606    30701 ]   pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_pei_profile_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore;
       planificacion          postgres    false    252    250    3252                       2606    31035 X   pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign;
       planificacion          postgres    false    3252    250    295                       2606    31040 Y   pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_strategy_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign FOREIGN KEY (strategy_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign;
       planificacion          postgres    false    3250    295    245                       2606    30724 V   peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_analyst_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign;
       planificacion          postgres    false    254    209    3202            
           2606    30719 Z   peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_pei_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign;
       planificacion          postgres    false    250    3252    254                       2606    31053 ^   peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign;
       planificacion          postgres    false    297    3252    250                       2606    31058 ^   peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_responsible_id_for    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for FOREIGN KEY (responsible_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for;
       planificacion          postgres    false    3225    297    223                       2606    30933 *   tasks planificacion_tasks_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT planificacion_tasks_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 [   ALTER TABLE ONLY planificacion.tasks DROP CONSTRAINT planificacion_tasks_group_id_foreign;
       planificacion          postgres    false    3218    219    289                       2606    30951 F   tasks_has_analysts planificacion_tasks_has_analysts_analyst_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 w   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign;
       planificacion          postgres    false    291    3202    209                       2606    30946 C   tasks_has_analysts planificacion_tasks_has_analysts_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign;
       planificacion          postgres    false    3286    291    289                       2606    30965 G   tasks_has_type_tasks planificacion_tasks_has_type_tasks_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT planificacion_tasks_has_type_tasks_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY planificacion.tasks_has_type_tasks DROP CONSTRAINT planificacion_tasks_has_type_tasks_task_id_foreign;
       planificacion          postgres    false    3286    293    289                       2606    30970 L   tasks_has_type_tasks planificacion_tasks_has_type_tasks_type_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT planificacion_tasks_has_type_tasks_type_task_id_foreign FOREIGN KEY (type_task_id) REFERENCES planificacion.typetasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 }   ALTER TABLE ONLY planificacion.tasks_has_type_tasks DROP CONSTRAINT planificacion_tasks_has_type_tasks_type_task_id_foreign;
       planificacion          postgres    false    287    293    3284                       2606    30802 \   e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign FOREIGN KEY (equipamiento_id) REFERENCES proyecto.e_p_c_equipamientos(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios DROP CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign;
       proyecto          postgres    false    3262    269    260                       2606    30797 X   e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios DROP CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign;
       proyecto          postgres    false    269    268    3270                       2606    30890 P   e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_estandar_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign FOREIGN KEY (estandar_id) REFERENCES proyecto.e_p_c_estandars(id) ON UPDATE CASCADE ON DELETE CASCADE;
 |   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios DROP CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign;
       proyecto          postgres    false    281    3278    280                       2606    30895 P   e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 |   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios DROP CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign;
       proyecto          postgres    false    281    3270    268                       2606    30828 ^   e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore    FK CONSTRAINT       ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore FOREIGN KEY (infraestructura_id) REFERENCES proyecto.e_p_c_infraestructuras(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio DROP CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore;
       proyecto          postgres    false    271    3264    262                       2606    30823 Z   e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio DROP CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign;
       proyecto          postgres    false    271    3270    268                       2606    30810 H   e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios DROP CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign;
       proyecto          postgres    false    268    270    3270                       2606    30815 D   e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_tthh_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign FOREIGN KEY (tthh_id) REFERENCES proyecto.e_p_c_talento_humanos(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios DROP CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign;
       proyecto          postgres    false    258    3260    270                       2606    30871 W   otroservicio_has_servicios proyecto_otroservicio_has_servicios_otro_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign FOREIGN KEY (otro_servicio_id) REFERENCES proyecto.e_p_c_otro_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios DROP CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign;
       proyecto          postgres    false    3266    278    264                       2606    30866 R   otroservicio_has_servicios proyecto_otroservicio_has_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 ~   ALTER TABLE ONLY proyecto.otroservicio_has_servicios DROP CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign;
       proyecto          postgres    false    3270    268    278            �           2606    30368 6   groups_has_members groups_has_members_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 `   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_group_id_foreign;
       public          postgres    false    3218    219    221            �           2606    30373 5   groups_has_members groups_has_members_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 _   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_user_id_foreign;
       public          postgres    false    3202    209    221            �           2606    30316 A   model_has_permissions model_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 k   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_permission_id_foreign;
       public          postgres    false    3205    215    212            �           2606    30327 /   model_has_roles model_has_roles_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 Y   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_role_id_foreign;
       public          postgres    false    216    214    3207            �           2606    30392 )   organigramas organigramas_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_user_id_foreign;
       public          postgres    false    3202    209    223            �           2606    30337 ?   role_has_permissions role_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 i   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_permission_id_foreign;
       public          postgres    false    217    3205    212            �           2606    30342 9   role_has_permissions role_has_permissions_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 c   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_role_id_foreign;
       public          postgres    false    214    217    3207            �           2606    30410 #   servicios servicios_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 M   ALTER TABLE ONLY public.servicios DROP CONSTRAINT servicios_user_id_foreign;
       public          postgres    false    3202    209    225            �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x���K��8r@�7W����Ȝ0<�����(��l�W���z)��(����sI��%g���-���d�#L!�4ݞ�N��M���埔��u��o�=o��b�l~�q~�f��l��|�e�͚�����,�d��;M�������✒	ѿ^�����x������?^�0f���ߴW�η�]2椯8�r|d��Z�.�`���b�����
ro��g1f
w��*��9��݆ټr���۫O�o�1�|
$��+P�#������ۼ�|��)������Τǳ$Q���$��þ���'��H���w��I3���[�.�\0i��vI�����H��X��W���v�����x��c�]߹	��>��܄��O�;�9{|��/��w�,�W$�s�R>sҗ��uD�r����H�uI��8��vI\G�/��uD@�S��uD�N������Q�:"|�s�����_�:��)�#���.��M�ﴑ? {��_���q���o�hG���K��	y��H�]WF��>�+��TD�ĕQA�'q�(H���B�l D ?:_�k}�̆�@�`d1i�.(e*dӶ@�j�E�VE�E#Z�]�� �F}P� t.�A��f$�}ЌAh��A|��j�5��E��%����oX�Uw@8���j4c�0����_��v�B�b�v�����-<����)仗3�����nٛ�-Ew�G�U<�ƷK��T�(aQ�; �ADu7A��d�2�5����o�;!h&���#"Nf��?��zL��.{��4���d�K���o��]���`�.�����3�=��D92�'@N�/��S�f<�0�\��ѐԩAޒb�<�����##��J���U�m�ÜQڹE���76��E�����hB�pcgb��HN6��q�R̺�cr��.�@"˜�(u{qY��D���Hd!�ۤ�T�Dp���5Hb��w��Ν�D�HV������<9&�R.�!= ��?i!K������ l��D��54ck�l�&(i�`����G�:P�]
��>�s �q4k1���'-��2S��y
��{*��ɿ�sy�?6Y������	)Q��I3��U�'eL�J�$�
�B*s�R~������.�N�S��v�"������\M^}�AO@㏯$�{27��t7���6�q����^NlJ���E)�*��������0)�15II��1�W���zʜ�_Sy$Y��7�XY����o���gK�ҦL�	XɝuZv�X:�Br�4�޹L����>�x��{��nX"6c
IL!9J�c۵C��S�Ǥq
�KR����H"��$B�� Q�D����D��&���3�O���^��i3��EX�ψ�p?)o͞���\J��m#0�7
6�S0���Ice�!�0��Fr���n%�c��%��4c� B�q�3�a�cr9�V�� 4<��JD��
�q�YR�z��d���~�̞,���Cm��JU���\I�T�8Ag���[�*{5��tL�����r��$�HL#3cRdvK�ʭ��t�T��oH��d�,�{)�¼}Rm����:�(�����?O��:�I�%�zo�;D
"����h#��#�	�c²����b^�dC��pX�¼��7�Q��<$��E[�"e�aeD|��֬d�"v��׀�����W1`E�9�oC���k��%�N��:���͘����q�'e\s�J�g�oX�T׉�[7<q;��̰KylH#�z�z�!�=+{K���1��!�X�律�J�8���<�����"�~����g��s�z[��`-��\�r�wn8�˝�A����d����&m�'nb�ʖ�뱭��[��Ѱ�^<�N�t��u
�����$t|��y���=w��(��������KD�~�?7�^]��bI�%�S>��c����x��u�խM�Ŏ>��/�Ι�ƨ��n��Nwֶ�Pm��OA�ß+������F,�)y��qa��:��n
�j���7��mY��F��ay�j&j�z�9/}�9<��)?�<k�t{�<�߱���AR�5yf��L���N��1m�\s� x�+�o�h	Ij's�q���JBi}O������D$b�$�M�4.��'YLW��&�Kr�I��59���I�ʒ=R��F�d�`[ay#ކ(�B�(����Tqm�%RU�H�yܐ�,
B�Ƹf�K��jd��I~�9{^��l��%�:�3��˸b��c
5��5�'���|*�ރ԰4gO-��3��XA����3"��>^�0��팩��e�m�z���a!봕��$�Ze�k�@Myk��$�N�uk��f�#�u8�?��%�V��"8�\���;��|d^k4.��H�R�op�Hv�䴐�\�*�;$_�\�(:{D���W�a]N�Q3)���!�ך���\�w�8��s�8S�(xLc�޹$>���D$��N��m����k��\�������3{��TGX,$�ٔ��Ș,���6ꌩ�p��t�ȘZ�\�w��.aM>�FBRd�y�P�^�}v�A���:�~W|wӹr��}c;����~��Z�{�b��t뽖Z����9��B��l��86:{�^~��;����p���;�����Y��6fo�N��6cJ�k8�}�U�ށu�2Ș���"�1�Z��.cI��o&�Z^.c�5�
�)k����� �SG�5%��;]Ҹ�3{Y+��1�xV�dF�dy ����%E͋��m4�bh�l�gn'�_-�oB�Alp��>	��}B�$�
�p�"�Qh�����B�$�}�4nlX����IH����L�4.�b�0Sm�{�W��'�oQl#���crՎ�8� �9��& �x��%cj�B�:{<J@N��U'�RB%vD�5����Z��<����\�X= ��Bs#W���Z���O�Df�����:�{	�7��d�;ϣn��hJD�3��X�����7�Ɉm����qd���e�ќ4�vIQo�9�@�^�4�"������PP�^�9�qyygL��$q�`%{-v)}]��U�x��D	:c�J���?-���E%�F!4{�T�:��jkXmn-�zjD7���vջ��F�bi��֨[�>aD��!B
��x<�����^S"�2{ܣ&9���9�}z��m�X�ԔW�܌�a��ꬓ�ϵl����И����&H��)�F>2�X��3��V!���B"TR]'uk5ıu
�6*�6��.o�)T�`�(��]�ބy������-]�`+��h��U�z�c�bm�Ov>���*�
�'l�)����������#R��l��Q��T��_�:s�e��8�-�7J�toJĒ/ز��m�^^t�XN��ޣ#$~�6�s���&i��QG��L��>	�;9{a���6��hSC;7h�H9��@^MKʅd��8⋬�Ig�p;y5�u��SKX�k��X�NfG�C���͞�.5ox4E�Z����[Ŏg�e!9=ݹmt�wW���<n�r mR2{ܣ&ќ��md���j�g$���<Bu�SR�2&�!k�3��$�k�l��W}��#V��!���F��k��"<�w֫Q����O��:cJ�Aa���pZ�Ś�����V;�Ғm���Cm��?�:��m�Ο�6���3�%�7�o��E^��t勍�B�n�?Դe�F\�I}�z�*Vm�p�*x&y��kfH-�	�̀g��I�ff���O�53�ry��kf�3��$\3�I�'��p�L�>	7��g��I\G|){3����}����}����}����}����}����}�ߎ��/�ьux&y��uė�{�vė�)s����������܎�RE|5�7�^�4R�m�.�pR}���?��qۗ��.'��/]��d�6����_y�ל�x��3�3��ҭ9s�fj|�rg���p
�����8��z=�?G�{͙�rH�*̦�mnO�3"���	�_=�)Ջ.�cw�l��U�*��ڸ.���KƗi�%!�#|���Ξ�>�o�˸cǹ_8��T9U�
m$a3����~� ^  �x�JB��%�_.Dy��9:#�x�S~�8��*�ٛ���`"����&(�Q(B��r�BD{(�GE��=T�"��*r�a�P	�P�h5sa�vT��1��(_��R��pj�����/ؐ��i��F�w8#U`4��v���۳կ�-6+�?Uȷ��X�����r�9�P��x��l�J\r�W��u���Zȼެ��d�ڱ�=�S�g-�X�����xZ��ŧŝ�ƻI�ZU��/��Z�I�����tR�%�[�*	���J�CҪ���@Vw�"-6`?#��6�$We/`˪!Z$_-s�a�*E���H9߹��T�E�)��Eo��w����e!�{.NT�=d3�)t�IZ��~.j�w�/qP|��k�J�O��F��C�37����/�B{����U�ܭFI�=w�QVu��j�V�Cq��U�PܭF��=w�Qfuu���vT��/=x˵J���m�ed��}���m�e`��}�t�p_�[w ��b�����I�ps�eH����R����Z��V���p�S	���֬]!2A��΍I�E��X�C���ǽ'���\{�<�0�y֕�E<�<��tF�yz�~�:{��D��,+͸ϻX�51���f����oY������g1U�n �J�ް�7!a�`���C�w\9b���^rĪ�#�d�M�G��ȑ}�ķ1��d�b;=�a����u@�8�������0:�	U��G�IϾ�Һ�Is(2�������Ψ47��dT�M���ީ*,~⮟
<��/�F���LJ���#�����H�Wm�lg���v��c� �[]����!�ѝ�����^�o�$#�y]��ʺ��b��+'@����Ԋ�qF��_-N}i�l��;Q:���#��܏�=[��feL#S�d�F��;ˉ"o	g�7Z��ڼqW��4$~WѬVԎ��p7���80�Zw�xތ�J�u�~�b%��F5��\���X���k�F�j�j�����H��V߬Yf��{������U���@v9^�D�ʨ eԞ:I��?
Gp�6��b��nS��� ٲ��΍��}N��͡���ﮍ}�E̡oܡ9��D���+���Y9�6s��3޲ed>t9ٝ��2V�MN}q:�X�}8��8j�񧤆f�k�����Y�L��(�X͹M�ڢi�7���폎�Ұ�(�4c�ˎ2	���elo��1��M���e7j��O�i
�q�q����vі��܎ì�$ܚ:N��x��Y��E�.��@%�k�|S�U8�@ʏW]NPMʃc˧#�F�*��#���U뛐p��G6��-[߄��l=��vH��C�������%T��GrE���(A��iӂ�5Q ��n\�b�J+a�Mf��φu�fy�#~�쨫���]����C�D�o���P(G��(�r6�Ԋ}1`�(�>-!,���I|�ST�F�o�v�v9�E����v�nר͉:��ć�ߊH�9{�����M;�S�>X�a�?8SF��]��-��v#n[�[?�U�'�6^r[�ߚBzM���b���mS�����nU-nUN��}��"矕����y�1�c�9>�c�� �d�>�*�{9��߄�0�k=@�Ǥ���H���r�?�3      �     x���Kn1D��]�C��K���{�#DY��\�����	��В�z}B4��-;��udu���9�\������������sd�V�y(��j�F�J�Mզ`7c�U�i&��ʈKEm�g�Rg��'�� �;�h����+��w�J�Mզ`7c�*y�#�!HK�~[��8O�'�� �;�h����+��w�J�Mզ`7��WM������X$�1g����zG��;�h���#����T~�H+7U��݌5f�v�h�O���O�AK�;��{G�A�w�Tߕ��;i��jS��qO�����*��I��B����v���_� ���_� ��R��A�RqS�)����=�X�/���I��%m5]#[=�:�"81�w��D{G M�]���#�V*n�6���Rۼ��S���Wt�j9���Apb`���#���@��R�yG �T�Tm
v3v]�fhwwY����z�a�;�� 81�w��D{G M�]���#�V*n�6��N�!�{xYA��KB4��1�����+U����#�Ǉhc`�6l�
�
�*�탴�di������nQ+Ey�j'�qBo/G�A4{G��!���#�*l*��wҮ��*}�u����f����C�xo��}�@�w��At��4���F���@���H�NN���H���ݘڒ�B����k�A�w��At��4���F���@���H�N֖���3�Z����nݶ��8���_� ��_� :QC�A�PaS�P�_� ����;ӽ�hJ[zjMF�sܣ�F�@�9i
v�@:QC;Gp#T�T Ti��?;I�\��k�H*�m�z�7���7���Bb:1���ܔ.ALG�NT'F-S�}�L.v$�XE�2�	�'��Z�5�v�`���9i����#C'������>V�i��֩�
�������b�9��7pL:�����L:�Z):2t�:1jq��ϭ�<�Y������ݙZ)�Xke��r��HS�xG���Rtd�Dub��00���������2��l      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x��[�nI�]g~Enf��l�Ir]�)�VA�IU�zL���䃕Mɫ�O�f /�hx�[��|ɜ����*�j����7#�=���s���(��mv��ZG�>�n�=�sq�%���zo�_��YYn����uQ�a��0>}�hU��e���.��U���D�������-�}TZ1nx(؞�V�|H"q�z�W7�緷?���ǿv��?�X�g��gr���`ekefмﺊ2�7�j�e�
<i���QFG��_0���*���ѡ���������%����g\E�����^X��r���Nf:n�ކe�����e�xRD��xPY1����V����U=��A��yz���c^
=�]��V�l5�W�@'3�F��"*�8�����Sp��}��:�#V�ƿ�RqܾA�:�9*d˕ﮜ�\�J��QQ򛶶�ɓ�>�c�.q��0�M�1�eT�d�uC�j[b�����ͪ�Byk���<��K����[�Ҳݕo��@'3�E�W�>�X�9�������WM�k�v+�JM�-��+X����<,N���l*t�CɹcãwV8}���y+i.f��7�!a�|<vb�1Qf���5ߋ/XA^�����˧֫k���4f����3h��|?��[x��[@왮߽�mN�����[���k�=��F�b;+���L�C��?0�sqƍ��r:~��7�>���!��TqVVqU��:���$p��[����g����/�ۻ�{x���Y�}~s��L�����`QgWoN_K����7yV��Pf�5\�l��`x�����_o|�3�YU��ܢHu�# ���"��Q[	Sn��K���>���Ka<b[d���V4[�gg��׷W���ie��d��r���"�q��8�1/��%Ɇ*&և�<�^&� �i=Z9י���H��7���_�b�j��{r'3]�a*��x4�Sv���w���e|�X���t,a\�Yv��]w%c��s��f�$35�p�9o��2
Y���
lI�p���RIt��HGB�=S�����r��Ә�{��\f.[�����Ϣ4? �a��j��W�z�*n�{a��mx�1�3�PN#�ͥ�hpGA��{n6~�m��߃jP\��,����aYJ ��Y:�i/[��*��L��6��W��O�I�m$82�7��[��-�R��pgCC| Xy�Nfڋ��4!`�D<�B��x?��e`Ы[Ap��g��c,�ў>�U�`�o�$ ��,T%� ��L�=�����]lRWP4@�(�F��ל�r�+�o��*p�r���L����>���*_�߰����f��K��qƯ�>�c�`�&=����/��z�sn��F�)�`�4���&��,;��-;Ɲ�d�q�;[�l���\��+P*a/��kx`���@b�Ƅ�q!��� �#b�)�|��ٮ<���m�MU̞���Nf.:�~���
N�s���y���$���4
������7��$@��ٚ��{��I�9�:��C�Og�6���"VѐX<DY�d9��)`�9��5<�#�3y�k��C�\������:�8���a�	����A����Ãl �p3$��W:�t��rc��Q��h?�����N}��&j���zs�~}����$��#��8�d#.�@P 챔��v_ӛ��rb��~����.��zu3�6�?����ﯬ��uus5�n�w�7W֛������7_�R��j�2�y��EL���k��R'lU0���it�<stTGA��2�6U6�Q�$X��UE������0}���|`�Kk��6�$[6s[�����)�7��9֩��o�Y���Dy�gj��ej���H��N��+ə8�&�i�:�^@����#�ZW�2�� ˹�@*&���t����n-�F���B<s��E����u�҂W<Z?�xeIA)��z�$�����~�}�	(��; Kaf�K�ik�J���-���]V*#9�b\j��?|#�i}۬���UK�tA�\��Uy�yL^g��	�-2N:_�-:�)5r�b���@�A�M�b�:�&�z^��N�I�2[l�e�S�����3 C+Np��}sޒ�w���+��R�t�#��A)i�U�(""�&T��;��TGIΡdb��}��#26�7����"��1�y-���Cy�F�:�v,A�����4��+H�}�iA�	�:�z���X��-P�6�o��6b����,P�'��nzdf�^Fy9#��Ng��K�醁�'T�be��C q���0O�5����N�4�õWN��-L��gq�(�c?�q�<L� �. �a���Ar��L�EA���"n �E)���_�����=O���`(�(��C����l_�����V��7��f'�A}���~W����\5����6���H��o(/ݴǋ��P^R�Z��vs~-QP.���5����lL8�̜�p}��W�ٟ��[��Q�(&�H��'���`M�fަvU�ʄ�)�`5Y���O�틘��	�����!�2�:"����t�c���`���;ᄰnp������'�"����T���ʞ�d��h�Oob�͞-1�R��R�������	�j8e�����RW�[��P�L�u�KV��ux��L������>ЎU���8冎Nf:��t���{��6:�?>˷�46��-��(y�w�V�Y���CۂÚ��EQ`f���a1�0)3���ާ�5 ���c�f�Y�%��%U �� Bz�	���!F�;�'  ���z�h�hl��cO�*E����&V�'V���&ڿc�D�������V��Q]�+)3����Zr6��/��5t�1��#���?Q5vAG)W-,����Q�ye�3&�ޛ��5W�o�E���{�DhN�a��z�K��� ������ޫR(o��4�pFeo��bT�5of�m���4ϵ�����_Kkn�Q)�B<��W�T��2�m�p�����Bᅫ�������f+��\f��BM~�A�tS]ј��Ll��{(��D�7����vM�m	�:�t� վ�}�����7
�?��#BmSL��D�)�Bt�Ob�4�t{g� u���������_�n�P|W�>�1��Y$U�{��<�=����ɑ�����hy>q�~�'B�9}Ky�K����	Z�4:м��A���_���Mz穜�P�"a��ȉ�\G��u�q����!�2��H���|H5��XU$��d�����ȁm�i������w���=Z�*�O��E_BK�%LV�^ן�ҿ��G��o��������y��Ju[��s�2O��>�	t�1�e��Leu?���p��.�p4��L���kV����h,����t\W�i�o.[?�klUva�hF&�g�H5�1�E��h���s>�Tp�L�Ic,�F��ug6����tZ_���ӷ�H[�tn�2g� ��#1� *Xɮk����)e���%x��9��2�����ن먭D�O'3����H��\?ݷĮv�]���=�  �����Q�;��E�`�;�1�-hء�P�4��u�m��a�����v�]�>�N�;.���w/)q�D�˱��0I�G�P�N�+���hN�&����� ����qep�jm�^�s4�?��"��Q�}�4НL�05�I����|��^�)�L,��^�n�o���J>�����3A>��܇��.����\ʨ��{L�+QK�o��:��v#3�nx��U�-�n�'zC
l`�i^��V˚�<�~���}����M2�̋�Oi-��^��'�5;}-���ihJ4��&Fɂ@3�Aۅ:a	�R�ģQ'��j5bƀ��鷜]?�Fp��<1���"��A�7E�$L��W�8�W�&À��e�ȱ��rB���I D�%��X�r�Ffڭ߽�����5�]^T��sZ��!�q�TMC���w.}c�&}pF��\�;��F�ּ������-��Kơ6�0K��L�k��2`<��<�nD�
+�(�2&T�a��XY(��� �  a0!dȸ��^�L7�쏫�30a��ZF��mq���q�II%��BВ&}�V��7���h��Rv�Qzޣ�����c�������@����yj3����׵�'A���n�K�J\f"L���N�7��F�9��U� ��y�Ff���t�'�K��':Pb��ɈJ�F2�k��\��*����ֈQ֨�8��%V9�o�+����Bז��8_#3�=2��0zY�ծ��I�Z�pq�e�g>��523h���lm���>SPX�{�LgY���`{�B����3�y
^��6M:��BK�hS������jl��L��$�]dyr!O$B"��]^�O6x"s����5 ԇ�>����˧fG=�Ms٫�8��23�F��,e�.)�z9�EJ����l�q�h4��;���~u{�~��j������	��i�I��b)3�y��}4�Լ���A��n���>M��ooܐ�2�o�)]�����^��x�ܿ��0�箜�?�A�A��~�b��ߜq�!e���r+����8!�Ā����ɴ��:��#/��U���oS�bH�Ȭ���x�~���V_�&!Ĳ�0\D	R"I�)�4����?�=�����΢{�~��M�(9�Kb�9��F���UC��=�&s�9o��+�I����xL|�`��l	��~9ፆ���.t����e�[A���%�-1)Ex�lW*	f�gL3앳TM�]�:�ִ��r��~��ϻ��7vj��(�@:�{��iCFd��"�Q��D�(7��yY����4n��S��	�~���a������m\UY̰)jn�v���7�8��	�z?������Z��pӅ ^����mZ �Ar�j��n��i�����#P@�W	�jZM4�#J���M�M3�\�rV3j�!
'�Q�%n	Np�AJi^�S��������|` �]]rɱ�W0_]�?� �X��ỴV���� �����h��}�/��U���l��������S��      �   �  x����nG�k�)�t��}g�'H`Bb����aj),� z��*T��b9�Ub^�[��d����[@�
Xi3K���ťhS^�vx�4w8����7w#m�LC�������	S�44o��w��{V�������S�p�(�4��_ݏ�G\��ʭ>��:�R��ҝ֝�5�&L�*9����D��@��ֺ��u��:��_*�L�ɀκ�1B�W��F2�$��3��#~��
e购i3��G���`!-�S�Ժ�)�Wy\�:u��GmsR��ŁA���3g�0V�����:m�iӼ���c�M����8n��M��|�4l֛����t}ńe��j�P���QD ���⥉�W��N��6�y�<?~��m��79��J8i����y·�;J�CO�nJ��0��!�kVq�״�Eh8��ъ%��	&Y�D��=s�0�JX�IQ�f84r�k9j�$Z(Q�ׂxҖ�9�o0�^�h;}ًGm6�Lb�1�y'�����A���'&��?~h��i3Z6�Yx�㸘�����t��3��Ͱ�u?�9�;��ya�JT�mgEM���@"^����x�e-l�<�S�d�
|�UM��� �O-��f}���K98�f�?����|�R]�JљP�^y3Ak�'9��� pr�ƒNyf)+l\���٤H���s�(E��{�N�ȓ�UQ���>R�?��9�;�phۼM����9�K6�{�vt�
7X|Ƙȋ"P��[� :J���.~���������#[�7�����q�ӗ�t;�
�hGچ%�%
���2��x�ZXX$W,;��j���.�9^��i����F���Xb1��?<��+A]V�4�=��?k3r*�;-�� �dD�����Y�k�w�z1��`>hj����onn��M�U      �      x���Ko�H�&���Du�+*i��u�T(3�����jP#iTx��T��U�\�,�An�E3�]-G�d~�=��I���Y5�])�H�����;��EF�8�aV�:a^VQ%ʜ�<�٬l�w�Z�/�L���f5�����Gy
�L�QF$ �IDO�K�(?��	�f��w:w�\܋u �A�]��(�^��	*��W�����.��a�Y<��*�n�� W��R����V�rs�~��|s����kx��nݔr�l�ǠlV��f,V�r�j���ݟ��bu��D�����Q�^V�sOY�v�i���q����o�Kq���~+ת��A���XK�������7�o[|�b��ZT��
�g��+�&��f�&�~q#��6�Ӧ����Z��Z7��Z����U8�Y�g��=�}�Y.2"�8k��!ϥ�8�aģ2�(D,ͪHg�wxy҈�!OK
)�P�5Ϣ"�h^������-Nfq�3Xd1?a��.
	(9��ש�w����σ�٪y0+�^��f����`�4w����Ovy���薕���ZQ�.V���A�e���"�EZ�q�Ӑ',3^�0Id׬�HZ�7��Ѭ.�P07�4�R�P�,+ⴎ�:|�������jrەD�wI@�I�NX���Jv��F��vqk�X4�O�e��H#�M�V�7��ۥ�U+M��:��,�C V[�4���B.8	yQ�0��*�ҤH�eq��ך5`1���[\а�%	ӚI)SY�I<a�`T!�h���'Q��.:�ߩ����F����W�6�,ae�FU;r)P*���{	{Qm�`	��n�?~a�g�m�4XK��e�� DɝA���l"�j)�"�UR�yC+�(�h�UU�1���<�i�K4�a^
Y�KJ6����dȹ7^�I�P�[�q~B��uj\���V��v�F��@< `<�|��7O_�z�%��[T�v�,�w��(��L�?�F�R��>}F"-����7Q���̳`��4R��(�`0�%MÄ���ЂXI��Y$� ٛ��"+��Q	Ҳ.�T��@Q�$�$�D,�H���1�NO �����i�H��&0Q�6�q�HV�z�*Ma��� �Nx����"A��ש�$a0��[.pi���lV�6l���\?�$TB�^.%���b%Ɔ����a��f敳*�I�x�+)���|�;:7M+0v��Y�?JP��"`�	��DE�6��6R���Q)ۓe��Q)�`܈�Ffq���Jʫ����!"�a��ސT\K��H��e	o�X��0QB��`@�aB�:�s��JN9]|�pH"�i��t�F9�w^nk�~���~���[!���I��ų�f]�l���x0������\�;��^h	����l�!�a�.UrE���iE)�#���eւ�2��GF�+x*$}s"�Oh
;�[W����9q��ٯ�������ؔ���Z��jK�����	���A��5^��b�\8>�?ß$�|�X>,�����W��O�d�`"�eV�b�28��2QQ�u�e�E2)��ͺ�������)�f�,�f 
I�����~�^=����%h2�	^}-�J����Fn���%��5%���/��3�PoTG����J�0KR����i�ӽ߈�� �_
�t�7��ڗ��r��/v�%n�;2NA����ʨ,X��,)��l��k�W�	I��e��^��~�����(-�N�J��`�`�"$q�h�˺f�iH�aQ_�'$��#'Tg:z���w�-��� Y+��<� A%a�d)h4Y\V<��W`�h��a=���E�a�K�Lh���(��hd=�h5�}wЌ`_c椨��E�e")�i��Pc��:
"bd���_Q�;���庒/�4KB�=�v���4�� �YY&e$s��.I��#=%�b�g{���|�XU͋�e4������4!�f8MK�
)��`�Kg,�.��*�;����x����U���
��
��e��z�~�Z��%k;��?�D'l �@m���َ+�>�����wN���
TV��=/���f��0�*�g�afH,a��y�����<1([����'��&���z����w��о��eP,���J�+�o[��:�����E���BG��s*K��mZ�ڈ��H���E��Ù��I$��q\�����f�t��2�A��^�O81�H��b��;H����{y�UT��T�˛f�jm��}�C'[O	hn��*��xhq�Z<�j�T���^��?����}��N��ԿZ���||�~���WZ�w���� �wz�FϿ(��/�~YI��
���s0&�<\�;�S�<����+��C\�Z��� L^�~/�P�yg��9���OX0$Cq4p4����2��;�rew��G��Z>�F���kY�/G����A�����o��z��rc���u`xn�բ�as&��m9eSr=Ơ��`B�Mi�c����2%<���)c�W��8FG%�G�ظz/<�%�`8l�&g������J�r�C�w�؍������.M�؈��
�V6�Je���*O�dS�Ok1X��(axlDQ_��%��,�FT�#Z޸Aѣ�_�yo�}u9?}��2,�Ll�i݀p��_�Pܮo�u����ig��#e�bd(8z�̀i0>�?xW�+� o�~֮��w�ķ�����d%0;��W�J�`~�u&SP�I��v�������Ĺ�A�"X��� -<c��<WK��\e�����
B���c�B�eQl;Aj=��(��z���6H޵��Q�#�����h���*y/�N$�&��d��LFs�n��Ɠ[ޅ0�\\�CE��aND_2��Ŧ�m���T��&9ݴ��'u��堋���E�jf 51�Yj��LV�E�%��D�qB҈Mه�(��đ�U�j�V/��]��w��yW��ў��P������)jdTZ�PfE�JY���(c�?��#�#d ��:I��ؠ��ߍ^���v������߬���U�Q��B����Y��P�Z��0��ݖ����jh�^kI[lÆN�/��bq-��#/"��K��fZ7�U]W`ā���>�O��w�E�����u0���fo[��m���+5�����/��h�G��>UJk�ЭZ�Z,Q�2�����n����i��0ڌ�J���s�W�U�u^�n�v�E�F7-�	�qV���T�4�^�Q\�y��d���M�����Y_��ѓ���[Ц6c�,h���T�1{p �F̡kK1�իaz�J�ƨV�N��e�*p�!�CK}��p��n�>S�A��|���r�絶�qI�F��H+x>u�f�h�����j��cFpx���Y���N+����`NO�Kd���]����:�S.K9j�x��r����^�[lpi���MĬ�I��Ը��BW�6VN%#`�LQ�H
� D�vl�g���A�H�Kߖ�1�SA{b�֍Ŕ�����pI��^�҇��Q�\m�Ӫ���n��٩c��y����E�b2�c�2zD��:�d�d
0��`��D�2������o�ש��j<8{���L����?�s�)��6NY�8ͫx�k�����e,=r�CI�wt�S�H�`��pҍ���L[)k�J��d�F�����QF��VN���3����I\�R�yn.m�/.�#�$�}E�6�7~�=7����1�������勂���GT)�Q�X�k���m�Ƭ~�e���� �|�|�����d�Q',HԜUJ+�8���n6�8�����A�$��ЇÄ�J�"�5Ixg9�"^:̰t�����s��!�$�sތ�Z�`�(��u������օ��#C]��<;�L���M"p�i�'|�{U�a}�9��J�*b2�Y�ȍ:�B֢�h-y6U�b>b)Ƭ�\���>���(�A�E�:�~�Ǟ�`�Y��)��^��՘`�Ajύ�=��;�{�;W�Rc��[P�l+�q�O���S���y��c��yY:��6,&#�&:!I    _&'�x�?�x�\~[�F�D�E�T�� ���"����KR��S�uÙ��4��+x9n�0VKG��`�Ӹ���_f��k�����;��XO� F����[�>�j��@��?�,ŭ����V���_� ~�r.r��.��u��v��cO�hm����m܇>�pu#�KX�� Y�"�-KeX3����D�E;��1)"�ì"��V$T�3Y�I�� h2<��R�	��e��a�c.���6��nR
�0�Z�Z�����X��v��Z8�7�X��2����1�kV*h
�L=l��r�N#���b��Ь1�;4�G��o�>@c��yu ڧ5�`2�j�h��uR�_຋%�T���%eUL��&�چ�;>��l�5݁~�ug���8=
�����fQ����f������:����]|��=}0p��gT�0#9��I��LY��%�IK����'g����jZ�*X�Q8%v��T�:�W;iX"z�T(	#� �a�У�c4�]���C����
6y;S6��ܪ��瓗<?�U�q�����ȁ��	�r�l�V�#<�����*.��i��Ύ	qW|hÌ�<���e6�=�Z���pF��46��X�ӳ�Eu��0�?�b��aZ�w\�G���v��_�9�?x��������0gɳ�@��uV�XT����#�	F=]�Z��Vk��7G�C�zS��\��X6ڱkr$�yQ���:^h�͇���k���L����K�96y���w Щ��UP/�?4p���i� �S�r}b�ōM��t���r���N��Q��<�*�a�!@-��&#z(���$��;4�_�;8�{���.}1��d�n�IV��Vn���i��~�P
'a\�Sv�`YҢ��5��d��&�J�B$��|���_cΫq	��/����=|�w& �����Cy��V5,-����O:� ���Q3,v8�ۧm>��]Zy�]5��7*"o��^�}��ǾY�Bj3K��&2��fW�jD��g�U���k���ZfQ��â ��(˚'qN����J�b=Ư:ܪ:5�� ilz�M�l@(݈Uմ	�>�n�愉Un�
��M
g�]�G�[8c�5� �V��Q��������+7$�i�8�-�K��Շ\e�(ص���Ur�4&���F���'�P� ��z�V��[�҉R���7}$�tO�q�������Wgם�����O\.��ޯ�ˉ���?������s?��Q���f�):
���c�qVD:��,���/���m�KU���=����?J=oh(�yg�o��|q���q%Qfl���i/�U�@� �V+�)���T�����?���~@3^'���n�f)1ӫ�����f�q�|_���A!�| ���m#���}>��>�Սr-��3�x�a�4A��M��V�����o�}�w����Lg���6��ݙƿr�>��ނ���wS� uHw�9��e, �a{���k�N���� w�m�#�cE8���:8��p5����~}~zq}b�sx0�u<A��]������gl?<*�j0�u��b�>��j_�S�6�DS3�p����\�v���H
Z��?p`	�u�����NU�\{�;&V�\keąo���v�}կ���(u�Q=�ߪ�YG���I�5�R��Z���|G�R(��o�� UL:Tl@��dq��M�vd���
A�c8X�}ޟ�� �,6:����R�����3�µ��xd+ j��tj��vA뭥���W��-��s�k�D�6*���e�.���fc��֛��q)*չ�n��
�Xw��#��������A����_w�(S����w�v�Jh�F�+V�Po��r�t�J�p�e�T�fiu��a ʵ��j���]����H�:�$���@Zp\O??�v{�ˈL�Y�����_Ȓ�u�4Rp�������~���ŏ�[\w������d��*�C��u(x��	����JL�ɥ>�2?�Y?iL(��9@��Ϸb���Zְ����Q.��W�L寗d�ET�)Ì�Va^TyȪS���I���A�9���D~���m����_t�2��]$���3�mc@�^C�AK_.XT���u`B%;|�o�?љf7���W��;��`3��2$B�墄=(9��Q�)M��U�`���@��;������O8�N-:��fp���0k�5 �+�].�jT~et}^�VY�%��y)�0+�2�iMDQ�����H�3�cJ�	�(>��y��etԍ�Qq�(���*S	*�v��3N��ё�5u׮��í��®�V�q�/r�g��ƐY�{��M��:��Ԯ�@������O��o���z��t4$f�!ǭ����~W�h>x�v��U������;B5��4'�oBB�y��8�3V2A��zY>c|�DK1%z A��K_��QP�A�`�@os}���(D�����.�πDEPn!è,cd�QH�4�X���O��/��j��1�}oab�x��]��� �ƽ�;����6�1P�ƍ8M��z�@Ni������GN�4�A1��?	�{��c���%�K�uCg���%�~��߯��Sh��IxF?��A+�u��p�?����!3�`ףzQ�A�à�{ewYֽ߬�)_�C��6�F�s�Z���.����Fi�2�笛e��ms�X��(`�K4$͸�v����v�~�C��N�/���-+����hE��3㱵�w9���%0uGg7�!`�V�-ksS�q]-5`z�m���jCG�6�A��¸�kV�bc�)h��ޚ�.��x�-�|�J%�hVU���Q��-N�g����Pᘡ�֩��͍�����Es�D��+�-au��`%p�/�O���T�\o�\�����k�4�/�PW�����W7�;½8��Zl��K{�B3��3���#���\�s�[
�+߇#�mҺ�OG��&��� �&=̀�8[������л干���:���x�Hu��o6K�3 ���#���INB���(]�}��se@~�1���&,��Z?M�¬Q�kT���n�D��#h�F/aU�m���`����cS�c���T|���k���n���(�7�R�(���ۤ{�ءTT����!����V��s'u��1p+PO�X���-~�H���A��i0E�q�?�M��?�fW������o`>�����sT �3-�^�*� �<suR��M��W�	�HBѽ%XǷ�8Ga�p�u��x��-,\y�C7���N0TV�Z������h�\���z��f�H���s���:RӺEp�#�`�;rS�>xf�]!�;��ó�������Z�n<Hձ����n�Zɝ�tu��(6ϼ�?a\ggW?G����Qs�+Dz�N�L��%����?��rR�D�z�S�;6G��):������{��~њV��(TgME ��h��܏��g��%J�� y��D����fu�:�>�����?.`��-�w�G��o�ۢ���_��J�����|Ǘ4�q���� ���V�R"C�g��Z�������"<�j�q���)�?RE��wZz����אzח�����:��:y��0K�<�""��|�Q>���t�9�vI�Ut��ˀs#mf��#r�7Hy("Y�<CZ�,.�2����J���7��3�߀��<�����-�J/22þJa��$�T�0�KV��	�����F=x'��8�֋�ia'�B-UpV��uFؑ5ڵ�5�O�T����
�~�P)���=���Cj.F��G�E{�t��\�3�O?�,�]��ɔܭpx0Ѝq������� ���sZ�oP�0v���8��)��z3pd`�T��U�]������k�Y��c�q?����s�yѷ6��@N%'{iG��CWI���������N��
�z��Y����X��Wk�~�oШʋ|uq�a��������������<�����������vu�&8������9~w=���\l    �$9���+�B�taǦ�[ uT���C�������9�Nۅ��!%ܜ����2_l�a���������_��=3����������w��)���u��������Y/�?��Z ��������u)a����>�W��y��`}Amk�:�>ѳ��ۭ�)@�d���_|?�jw�ӿ��oޝ�?W.����F�v����9L{�����5\~~:?q��l�gM�C�v?ЬA��M�+�Xu������\Sm�͸���7�7x�h����9ݟ�Ӌw߽��P����5��o��qlLS�PQ�~�3�iPJ��:��y���u�}Q���ݻ��o�=��b���c	]Y�(�V{�T�`/T��XI8�q��ll�>/G�ex}�1����WL�/T��������/�+��u��ި�
�W����O�Ϯ���Mv�9JR_j@z�9<��إ�ʛ��e{k�k
��n;Eg�(�f�¨8RБ��f`v&,�;��W�Lr��̨�rc��}M���A:Z�@���j�9��Ѷ�>C��J�{�P�+��ε{�j����N?|w�3	{ O�3���v�5�i��g�GϜ�/{���xİ�g�l����2j�q#��s���~�\ �$匓0.k��SՖ��_�����H��s����H�b2Ov���Ǖ�O������o���]��x֤80c� @�g�w&Lن�Q8+�u�(=��'Ÿբm�Q�䳁E^hw�N���h�%4f�+���@�-HI�X�٧��5��.߀�Wb�q���]J}D�6�%WJ�+#?��<�]V��`�p�Q}�E3]��siaY#Є�J	�Ow��T��Z���w�dʑ�Bю��F��݊<���#�:��]�x�J��!g�ie����Q���ʚ�]J��N7������B�����=�����t<�p�CG�]�֦{�o�=iG�S����υ��i��侶94�l�F"�γ:ͺG2X#��EW,�[��<2��+%�n֘(uH^�9qPP��y��'�����F��i��8�����?߻r޷;���5X;"�mGz���uH��5>Z�Q#c�����f��k�[>��ǎ���(.!4�7�%ƌ�Kc�tҥ%H��vO8�B+��(y�y�qR�f�%ذ'3�^T%�X��D����/�F���a��+�>	E�Wa$J.��T6�&c1|�4�ȑM��C�Ru��1~jb��}o43�a3^��p�lա�Y�)DHW�!��YEc�x"�q�f	��3��Wu˼� 纎��W,Y�yx��\�2*0�[���4�	<�8Ʉ,��a;��H�L����-��v)n->d�2���-����8i�.�*@)N�ՃN���۶�`�<�Z��Gϕ0�e*��
2eX�y�T�����E\�8	Ia�NIbc����q����'�{Ń��l�,��-������c�"h~��^�e��|L��fl#ٲ��Z� �F��k[k�ν)�7����1z�1��,׳7gp��_��� 2D�o��%Y�aJ�4I3P"���x�0�"�蕊��p;\Yc�R��Z�Ar�z��;�É|��S�'7@�x'�Ь�;i�F�|'HB��K�`��D����{L;c�c�74[Q�#M�ҽThӳњՃX*�y_13���r��o�F�돸�\aa��ۋ���F��|3����|!�_��g~v��7��>�Y@���cz�o-���bˇ?�k�ҋ�v��]��� �v�4~�ދ���^��f�)Ngw���oM�G��S���.��+�ݯ'ۊ�1aa�N�˞��)�.BM�j��h������vu�����U�M��9
��x��J���>Y������<�d�b��q(P�N���.�C�)��	PA���@�s,4�
VEYX��グ4eS��Q��W����x��ê˽�;��+i��-=c��\k�m�"t��ph�7J	��^�RĘ2�D��ьUiq(�B���>���ސ�7L���V~yћ�'�cW�xl�cXnI�����P�U<�Q�MQ�yq"As�,,��
��R�eY�I,�h_%v��g'рL�g	Kl��c��m������>%�.秳KdE�[�S��o�r�w 5�����7fB*�0gXxRf"���"-��1�r�.H�d=��_�/�EI����^ڌg�M�\�5\�*t�����<��N���x��=���ع8,�h)e����J��d�89��4E�S�T� �x�����T���P'�%��\�Io��Á�_�8!�Q�� N�� �i@2ߜ��X�iXDS8��Jк�j.؅��������M�sļ�QQ��<��g��� ��;l?��Iq�jH��;�?~Z|��'C�	�}"��L��Pȃ�;�}�c�{���X���/_��C�O?���?v+�n�s4���9~�,]�N?%��3 ����]�[-��k����X9��|���ɧa�콂"�쏂�^+݆�3�>
��´Z��J'S�g!��h~���Ī�t���iL;"�~LG���G��HO��t�����F`����� �E�GQ��W��)d�Q��}#�ѩ��]⠘5f�^���r�m���*BI5�(��	%�γ,�&��	�ˤ��X��f=C���U��2C-�׋?G)_X�4,3厯e����4�h�eTMX�`���bf(	YԷQ�W�̹N1*/k,I�<֗n��?Hfp%ګ[�j9վ�î���l�x��v!ڀȹ�W(q:L���zՄ�X�I����]����0��1��fS�)�tP�%>��ǂgHi��z�?������<Q�˿�xT�� �p�^�w�������'��2=�Je9r��� y�JYQQ�$�Nt$���~!\M�����>��n��z�h����K�]Zp�u�F�>�ъr�K��ړ@�m���M��!E�<�汘! �@$	��U�L�.N�3`���s����ƹ�SjU�W�ykX�6�C���z׵�O5 ݒ|���lF��*��6��]��B����7���a\�J�c"B
$�ӳ(���
�S��| "�B=�b]�,iE�W�ȁ.�s�d�j�����YQ����N�ǤX.�!�V?>���lXW	�f�}��~���ӳ�UWv-�r��a�^�&e't�ײֵcT��K��+#��Z�qȱ��$D<*S.�BĶ �)���+�@�9��I:�j<:!��1�V�d�d��'���<ނ�uQ�D$�Syԣ��c�⬞� ��1>VV8=q}�n��Cჭ�Th�A5�+6���7f�,���x�l�F�/]3�Y)��M���4�oݡ׆��3����N�����悧aUH�5@a2������(�i9ev"�����}�ם��H�OG�3'����vr��I�*�+4�����H_��Cm�H�V����a���Y���#&�j�f���b�Ӟ��c�}Ѣ�)W�XqX/�R��
�]v����nۛڊ�BW��1kQ���.>6{gȃLZ�}q���C��Ԗ�(�I���,�Bd7�K^NAj�	|B39�X������P�eW��Fs���m(3��N�~��^q�AsVQ��i��"����h��J�TL.���~h3�n0ӡ�D�((���<��e��"&��~��ǁ�/Α��%�]v:kO-Uzd�BCz�6�\,��1l�n����,w��6x�2��#_�O؇�̏9r��{��lhL ��n��Qcw��i/:1�?�~���g�f�gͱ91���� m����򆻗���e�p��g9���k��?�%��V��z��	�74zf��O��D�4��r{c�'��/�]\xD�.{�����Oû���E���cbԈ�*%��w'궩�nF~K��<�M'H�!��j�m�\����$FK,Wɳ���`� �m�+����g�;[G��O��f�(�
�kA㊄Cݠ�.
�R�WL��[�� �s��|�%'t=B�dZ���f��k��������}��&$<Y���{y��$ݳӋ�O����t>R�z~�X��    �2���pڵ�B����q���� �fg%��[ۇ������x�f��1˞��Hg8�@7''4���I��l�B�G?�}��Y7F/�����*�!�2���\�`���4��؛*���g�cu�(n���6?a|���|��$�Y"´����sKjxh�)���4:���X>kkL����q�_1f�����NX�IJB^r��$dQ��(g	/�!}ǲ��a�>�Q��Կ��e��� '�8�E4"uVg2:���\�l�-��w�0˒�!�,�%"�0�Qf)�A��Q^�Q�C�:6�[G���܊��0��է���V�A��!�mӴ}�b�S܌���I���Tk�|�׮�c�u�5��변���cj!��l�)���а��{���������p�����أ�z�6�]|��Acϸ�/p'�ؠ�;�.)n�]U=ⶾYql��G\V�$4Ί��K�zs�� �N�^u8�nY�;��]pAKCڕ����(ٷ·ܥ���gf	�G��Y���`/r
9�8U�MK�;�o��s[����Y�t��.�]��[潆U���ΐ�/^��?�d��������t����+),�{Q-��+
��m1sH-X>k[TiD�	4�����t����\�a��4���]����A�%� �.C�=uu���Q�,����ț�z�f�s{���7(�Lsk@��N���-4���:TA���XKFPa�G��NTP �i�ú��P*��L����B�tE�̐#�:&<Dg	r���Ng��X=����"�G��E��������aɋ,/�(}`�"U?�%�'$�ŗ��dX�T]��� �JU:�M��KYNx
�d����~���.���ߚe�eVq�<C�gU������,���HZQ,�ELCN��L�,,AU~O�	���>��W��I�c���ƩGk�)�
e#w8W[�v�2}��3�#�����d�isT�W��=@�ۥe�襬ȕ�57�����٥_D����-:PTA��@�C/��	<�0�v|H��DH_z*]�HV1G*�fU��8B ���tS	�V�=[�իM���d꺪N�#\	���!Yށ�߁LI�,�j0�8�VԒ�Y�2��P׼�%��'UXe,k�Í�9�=��ISq0D�8�&CQ7����a�y�?��eHc�g!r̨�����	��X&S8X�>��!q/a����v��E�my�FEξ���׃$�~��/�۵3�0z�����;��;mJmԁŅ!�j��p?7��5��A�U"n�HB�T�%4a@�T8����שrM���v�`~��"�64\�Zs�qml$���c�֋�e�������#�(=�F3��=$��!�7�*X_��whٳRl.�-ؐ����V�v�+�5�f�ܬ=�1֛]�9	�Vp�*I3?����Je⯨������\�����,{~w�����:d��Ҷ� �w=��XN��©E�&ޙJ��!1���8^��S�y�y�r�:���o�>�q\^]\~wv���
t�����ߜ7C$����߽s�ѵ5{5Iv�����9�7Nj�[0���
��Y��NA<U�2c��hZ�n�<~��=�T���̬�3 Z335z˔t�D�<�dF��0K@�Ƅe-E-9���g$��q�A�H7�8ّ���q�L���k�@��4���amtݭ�7����,��2���D��o�Y�{�KLHD!�R#СA��A��r�Ⰺ)�uRER�B�}�0�yP�BFJ�8���(�\T��c��ߝ�{�So>�1M!,�à!J�IED07���|Bf�`��1L7�M[��	�����Vh����0�^d#�b��gj& ��3�Ơ�û�;���dQ�ۙ0/$���x+�>ɗ[�Kb蹷�HY�)�3�Fi
�CNeR�HT�H)��}Z4�h?/�����5����K��Q37=jfÂ�H�>���C</Pe�@�a��%X�U��HY�MX�ڦ����� JFP���M����^"dۊm�>�]_y|�p�����^��r�F���}3��M%4L�-�ԭ|�X߫CCczU��[��Ȱ6[��*���
v�� J��(��E_��"B��7�ˣ�^�=�p�חg���k�Tw��b�)׋���)M=��/�׻�V�el\Ŧ{`_��h�>�JQ��7AE���-+$6�d鵇�[�ːG�F�F�%��Z�N�p�g���1�B
��U��b����q#��;>�~�4���|��-��Z�1�("{A;5��8��㩡��[	[G��o)�FÑ�E�v� ���ӎT���cl��AT!�=�X�d���Z���^t�#T}�4yn�Ѝ��v�6f�b7̙.��׾��!��:��b�@���E@��=���*b�����
:l��T��X���15�n���ټvϩ��*0�7�u``�Xpv�a�犏��׷�*I�@�@�ί�.޾��*9��vX�������s]��t�K�OOϮϿ|�J(�}������<��8��j~z������gx-�uvmK#ίϾ��j��⺛�~ŷ����$�|���4&�.��N�_`]��y[l�������0�qBf���<���?�/�N���˷g��uC�K��`�_\�U���rx{vj�o~yu��ŵ���ߜ]���[ߜ}�7��]\}���������׃n ������T�>U��������`8᭿�;�±:����//vWq����%���&�:���x=7S6�S{�5���,/Ϯ`���?=���l&�3a�C��M}��7���W��oƎ�'��P�䃫c�c�[CC�$�жj=Q=RZ���TE�;���b�X�Cc�c�����- �+� �R=(՟��Ѫ ���*F}I�{�R,$OG��<눍(�O��3���{.a��c3����f��`+J��$�,KC&���8/�L[BS�d���*1�˪e�	���'Y�g��Ӕ��]V�]P0�"�����$�)65�-wk/:�%J��bS՟����4�	�3�#�I�~����	���%u��'�WX�6lio>1���Wx���!36$�Aj���0k�I�[2Gsa;pc�8L{;2��U�(�$�*�YH����*�֘,��`�I���T��Dl�
QG��F���[���EK�g"���g�G�H���>@Y��Zص]5�I63��h�u,���+�h�aj�t|��1��,��qk��!�>�E�k�1��xs����AB�i��6�,i��s��o�>J�E�Ӝ��4�{�N�0��+X����+S�;1�x����Z�R�V�#�I�"ys�P,I�(��h�� �eJh"@Os[lH��܂6z�i+fN��f��_�(M;�M��~d'�;���7}�D{��9u�͢��h�:)
a�����}�zv���i��>t��]�7��=��Ij�ű�T���HI�h�����Z1�����~�(����������Ĵ%�!r4�0ˠ�BE��D`]�	KC��!�wD�K��n����V8ź��Xk�����?1��\���Z� TR:j>��ȑ������,��>����V��/�Ke��4î?�6�X���\yEKԅ��(X
���0�� Dd���J�E^�Z�� wN�*T_gш�`�̣VL�Ls�G��V�)����g��͢�F��$�����א
���p{
KI�0!iV��Ђ����H3Q&!ɋ
1��/	�3�2�QGh����V�}4��ؑ�`�3�����ίgcS��� ���9GJ;R��������}?�S�"wW���˩PO��o�cL����9����H��&�ȱd[s*G�4N"�dX���Y֛7�]
b�$�����:�б���ւ��gZY�V���~���7n�t��4�)��R���a)�?l�0�����D��Ί��q�ʘN@���,�������t�i� ��R�׹����b��E��9�?|W�!�F�    '�ұ������VǢ��o�^s�ٜ�,�z�y���H2�	�Y�MP,F���	7�3��D뜿[��2-U����{tG�{�Av��:���,�J��+$���IxHq`a�1c"(������X���n�Ꝁ�	�q��������OQ�;�H^�u�Δ�Ta!�Ʋ�E�3��S�Qt��� ��� :#������~^.6��A�n��ֺڏ��ר�#�p�Q���E"���q�jb7�Lҡ�`����7 �K�a�s���`�g�u2Q$`QÈ�V��u,�S���.{��gl����138ms�<<�&�`9�{�j�X)+�Zn,�_�x6�ƌ�"7Y�E/��������/��׿/�6�7����c�����ö�G
3�����G,��}�aq�_����E�Yn�&�] �4��^ڲ�>�0{#�N)�\�(��$,P.E\�+Z����`:�p:�$RN�z)f� �N~��yk(��rῺ:��y�ҷv0����Kٷ'�����u�Ec��a�P�����=::W6AAu~8w�?��o�b��J6��b��XΎ�t��DvI�.�;�O��V����r�F�ƀ�>Dw�U��@Vf�dp��� i�&I��yċI*W$�"��D���i��K����6��g�^*8�(�������t����Oxƨ(�������Y�i�I:	��C�������<���6�:Q{��07�1wh;��������Z��Ox]@���IԔ�J�V'@��E�EUi2��>�&�Q����/LYK���Jql�e>|Xlla��,�?ށ���[��2�Gj^(x�Wvk �6�����߭��{�#Vu�xŴ���� ȶJ�������{{��;��7��[��.�x�n��/>�ٷN�ascs���]��W�:)%OD��	V�Sj�d�,#}YL=�M׫�0�4v�6)\�Hm<=��bUL�G�gg��H>g��������)��Y<@#����l�|��̮=��[����_�O}�ç=4�?X�A�����9�1�\~]k�`�& �@���_�Y�$��$��F��4�KR���Z�2<���FI�UYMP.�g~���I�	_E�y6z�a��b�<��`/���U�<T���W������7���y����
���1�4�#�XVҸΣ	�������ϥg��6>w���LeOǇ��~�1Z،���Z�/����� �?Ut�����&���pY��b��p��=y[K�u9��cY������ξ��xi�<	��"A�*�`T�y}��c���o/B���/�o/� ����M��bs���NE������X5�Y�v$��Ve�_��X��"n|R�A�C$�-P] �&�8�H˓�4����tB*{E`�ӻ�H�jNd^U`!u+VZ�*�)s��dU^^�N�d|?NZ?���p��Z,�A��᲏�g/�6���aD�#S
v]�ar��QR�*٤��6�m��	`���8�nky��L�.²��_!p���gT`G�>�����q)e�1 ʧ��X_w��v�T�$�|q����W�{��:�*��p����2,�8�)�r�턒�>�<{��#z����޺G�K�/�%4G�����x����-�+	\&�L�Pd)	�2KE^��ʦ�H�>#U$^�׎��օ	�qU�d*N���(���H8��讶���0��E���"c}T�cp���_>��Qv��a��´�/�i�y7	*x��;�Н��#TB���!��D\�P�m��f)1�M�W�U�:h������^�����[GqA�Ҵ@N�<I¼ȓPRN�8�c¦�6�|Ƣ�B�p(�/�I���ڣ!���&Il�5P�cl�F�+$�Ɨw���.0,�Ҥ��j]Ö�P����F[���[!h؊3%�C�$֒D��nP�SPx򜒰N�2+p��Iu�`�y@D�`���ֻ�<�r�������[��G��D�e��ʾ\5�*;!Te*�Ñx+T2*�6t���9�yZt��ߢ" *�*_gi��!�����+UTH
���uӶ}�{���Lb�?��	XbYV�aY�D���j���qX_�oH�d�ys�<z�M3�2�)h�:�^B��{���-�?���Wơ&�T��$��,@�U%F����4K��O�Pw/�O��6p>���{%�u}1��������w�4e�/�G�&N?}�M��;��n
u9�mZ�@�VQ�G�7���̣"��:+@&>[)��q_�Qdz¯\�����Fz<z�Y�Tn�O~���úw�"�X�h����X���j;�����V���+|6�Rٝ
���-��)8�7H�B����Fq��o�c!<�e�(�$,�:E=-X:!+�b��
H2� Q$�:e�����y��S2l�'7'A�ʤ=�|�{�����_��o��;�4���a��16��W}�*W�๦�g�O1��ˤ
K*3<!�P�2
먌�
�׺��O�gd�1hHz�TD[�����KXg��8%V�Bv�)��AʵNV<*�^��Z_����6I����z���ԕS�*�j�ck[�|�+K��l��	�m���ͶP�t9w�Pwz�8�ɚ�e3�J��$��̾Sn�e`�2����&���BH� �6��G�;���nylƠ�J�6�$~�a&	��"�ӊ�i$��'d��X�q��ܞs���G�d��k���Eb��Vğ���v�AJ���g�m�~�r�?�</K����t͍
��g�|�p���Ÿ���4����,��	�P#�[4�ҌcL)�Dq������#��#:���D�΃/�w�����+�O_�ޑ�S���,��s��A�4t��Z�Y����yԷC�~|/Ud�d�:���x�>��ؽS2VxV�0��,��OV��3����)��$��3�����	ڙZZKܧ}��k%,�^��!�ثN�li�ѯ��U�ʝмWߜ�kp�����J�{�<s�>�6B	12	i�t�����
:j���RU�N(��7�		P~j�I�~l7��X��r?�j"���J��r�ҪFZ�B�eXW�����<�R��|�9we�w#�fi�?��{���/\�����* ���<��.�΍,TGK�d;�J��=�1�y{@:y��5��[u��%;L=�����f�%H�B� �EFe�Ӕ�2Ll�����΁dX�Ck���Qٜz�A�A�0u9�6��M�l���lԐ�EX#���ԼZ��Db�{����]��6է5bM7ۂ42����9��U�	.,���<�z��{`P����	dk���cQW�οNg�{�^6l�.��+}
�_���ġ�v��!K�yW�ǁ�D��B��+��L�	׶^�t��{.�}e	����n�Y��d�Jq�IG}�a��PDQ���E�9&�qX
��8�	V rd�.SCOh�/1��~�֯��u@�V�Z����F/6jj,c,g�Ʊ/�py3�\y@!�5��`�_�&��^�ġ%X��H�X/d�K�D]A���o��;z���Ո-ר4��O��!]��8� �3Y�yZ�EU���Mq�`7��HG��g��(�Ez��?�\�$�y	$G:s�갠��q�GUU$�R���3�(�d3��S$�2�����i�Tl&F����cp��7U�Z}��1���;�{��L;�C�F)��搱��M��D3�ںUK���+ٮ�Šn��Ln�\�uG�L"��d�Z	e���8Ȉ���$�ؿ��6x�̃<,���sXU3�1P�@O8��C�-}���*:Ef`Ფgނ��I���˴u�St}���Y�(�?�a�q�����B�Q��"�B�%3}���j
*U����������k��Bt�F�FX�K�|詢;̵e`����`G��A��X��Ƕ��^a[~����F:��`���q�eZ�E�'��1��\ʨL�d( �m�����y:���[��y�Ǒi�h� *m>�ZQ�
}Y-��/ti@�U�w�h;�Y埳#��@;u�{2��ot���O )�#"�a.�b3����o�!�88lE)��˞_wE��    ����y��4a�G=��oJƂ��4��Q�Z��#���[U�L�b��K���0�q��S7��˿5V޺��x7[�oL���A����k�*u�R�z������ko��fC���M1l�]�3z��vJ���I*b�{�Ĕ���pރ6�����"Lt7P�M�T�eC����`.�q�VXRMw��Y���.������J�n��@�+	��޷�fen� W7�xseV�T���	L3����=�]?=ä��?P��{/a8�*���r{c��l��H�="��Y����\1١>��Pl�1{F��^pXl��a�1C�f$ޝ���/@��61l@��Wy�'����^g�Z�1�k���$��)XJ���L��W�@m�w{��9���{��u�2�v}���[t�gN5'i�2P;g𪪘�_9����.��f^Ͼ\LȲ����&4�6u(r����� ���)L/ZmQuX�Ʋ3#�U�G�l�5�U~|���*p�E͍��c�iE�rSN��	*��p"�Y�6���>*,�>hÚ�<Ī�Ƙ`E��YT�u=IJU��x���C�n�\�۬��T�^��B�
�_ ��Hr�������l�n�����q��� Ou�S6F\0y�8*e�4���5.��8G�L�@�K1���J��eYU�d��=���с��4��*pQY���Buۅx��QB��z�p�X��{��^׶����ku˼��g/�m_�?�tD#0�	VߋeX�U��%UIE9�� �ң�ߑ���6����Xi_��p;�nҢ[��%6�{�,^���^S_t��t�����ђ�$7�fa��:�x&�RiڧCۋC0��{�?a��O�Y��40�!��Q2~X����䠁�O�:C�ċ8Q�6_��V�	��5V#��=>?D�ޯu���rm�3f��"���kX~H�|Fes��U��Z�����-�7 �sΐ��J)��PD֪g�h�nh/��n����;f��~8>'�N우�C�P��.؍5,��{�3���k���>�ٕ1�e�H��'2Ű���QYM�FN(|!M��^�E�-�w����Kء>ia��5F�Egs�ͥ��P���PR	O�pi�@MIPu�O�>���JP�%-`*��d���8��$�ӊM�o�5L⁶���Q?6bm~�[� s~w�^g�l���q1 �e��\����in('�qW�b �UM^^��ޜ�ί�#<Q��n��b���^:;յ�L�t�.�����a8[=���<̓&��aӞ�����uYM	�Q��"#ي`���?�i��j^�}9b�z�yp�����|ÿ��4�6�y�6]�Z߳�N���C�����<,
P�R��N�M� ���M�s��Ptܪw2�m�}/ǒ�����~6��h�5܍Q�;������l��w�t��J�D����c)M�@�C��u�5�)l�	��@�R1��$'�d|^J�����t,{l"v3iP�>e�m���^����f,7 �*��/@�s�X!��&uQ�(�¾�$��G�Y4�H�
��D��;��Lg�����aU�(9�bt(����s��4��[���4��Z�n��9��1z�a��D�<�ò�:A���<�X�
B7���L���H��3�V��D֧���t��n-Ӌ����M`ӱ\�bL��<㞵;�T��r2i���2ϒ�y�a
0��W9ءL�e<EO�R���=z�%Ue��P j[R����D����x��Ͳ��hKD,;ê�̽G�H���f�e�	L����y��������X���>�SCK���mj7\���4�ȯ���������#�V��l������3�)�>M���#��=$˛�����!1�
f�DD&�eʦT���ȀH���_	2N�2��u[\��oML��<.j�P�2����O�y�]}*�A<G�4PJ'��y	S�EU!��*�)\^�1+�k�*���?S�ǵ��[j���&ʧ�l ݂7���1����]�]�U��Wx_�M���'�+�Y� F����=L��˰`���2�'a�r�:��N!��X�`Ԏ�'���hOn٠k�zǢY#���m2��Ԕ|t�*<�a�� �3�
������r6�#��9t�U��~W��4mY=Z�j�q��y�i������4?��w֑ɘ�h'⇓���Z����	�Q"T?̊�:�)�t����!^�A���k'o��5��H���	�Y
ȐCC��n�Y�gm�y4�u��.҉+ϧ�oL�}4^���L�(XMͷ��jڨ���>*���Tg��6���ש��G�a�.~�k'3�3��A���:�$��0N�$��N�LDI���e2I[�g��-a��6G���-��#�KU/|�h:S�����:�{ZP�-�
EjO�Fz�ŪƉ�F����7��"W
&#�j!m�D��Xrp�1:ƨw�Z�Ғ�V�qe��c7`�����,|	�-4I���/-��C��ȁ(�5Ջ�B=𰥪���`��m���6����H5����b� L%�� �~������*>���*�bfR�	�|�
֍v�.dy�Α'�@<R�W1�0�p��K�1XnQ���<	�,��:��w©Pe-�U_�S�h.6Y�9jwϸg�Ͱ��\��
<���wv�b���+0�8�{�c��$�YP���!#0�Y^Q�'x�)����%C�WLmI��[/�֬��z�+�
V�f��;а������2Ԉ�.o��c��}����_y՜0���\x�����b�;���;�?K]��\8qA]d��U`��҈V���򸥔v�]ݭх�r�N�{+���^"��KG9�e��"����ӯ�r��L�A�Bz;T,�)��'���HԘ�H�QG(e�f1)�IV
(,l�Ҕ����f�����x�U�.|�Sax��B��;5uL:y[�k~[l�Z�^uww�kV�@\^��&F��0>9US�L�r�a���BsWn�7�}�����tS]L��|s�E��[�YT�*f��W��2cZC�(�8p&�p��(J�(�YB�(�bJ���}0��yG�~� ��}���+��;�R\��6����-�xF��j�����׊��U����5��2��=-G\���@�dM��,
YJzZDŤ
K�9|�d�zE��hbl,H��_��AV���.�<�´tԥ��k���������ZW��о���'3췄�Q�&���ŏ�||h����~D?ɱG6�'��q�Y�t�@9e��1���T(#f�ae�$�qT&S軟�E&#��m������O�	-UM�|>�ܡ8k�x�n�aJ���M���/��.�=�˅�p��V�@'�n����q���򟄞'E���0:e=}/^~hf|�����Q���3q@�;���b�Q�"i���G��(̋:�O�E��xū�rr:�z�0���b��6�-<dcQ��P�E�	8@H����y2�Y(�!�
�?pbD�	��s����s�_j2h�p������ *z.ᔙْ3B;�v5�3s�Q�	�8�:p~�IR�e%SV'3�I|�R|QL�ź~� u(%�X�kN�c]|$�O��1�:�W�'T��%�+��z~q�c�Ȋn�K.oY�60�~)W*������4�f���G�{�-?�ڃ�h����U�]2�.1�@Psi��;����FK��;�����6ă�I���*5�F+��x��Ґ'Z*L��}��C-��vDK�*�U'lZ�X�.��:�=3[�(x���^�X�D;�Z.|����,'��'FaU�g\��'���L�������Hl�%���x���BsO�#�3�t��~���l6�ӥ�Rw���$�AK�2�3&
�&���dV��؟2h�uLF0e<��5�N+"�I���v��w���u�u\'��4��`X��B�8u�:�B��v)��4��^��	���/m�=0�H�[�l�x~���(�}d�b�Щ긂��tlͧe�写K]t���q�Ȑ$q��i͊r��3��E��I>3� �  @|���ޯ��lU�Oa��H=�>	)%x=h�
�&��&���S�f�Y>Pw�B�!m�ݯ} <�,�N��A0��U)k�"��C�'~:�Q_.6
s�����	Ep��25����Q�o���X=p�.j��7k,����[��)�\�=ULW��9�X�U5�4�Re���� L�߶�{����#��Ǿˎ���x�V��`��vdf���CػF�>,�}{yB��xsK��u��!��0^��'R�<�2a���JL)i�!��4�Jv��u��w̓C%��uM�m�F.���HSpm�m��z�Y��̋�t��l��$��3V�,�Q�ya���b�ʊ�.d=�O@�"C�J%j���5�]ܮ�	���Ybj����a�����Ѷ2@�w�H��%����x8���_;�O�
�a��[��6�O=(-#��_�������H�      �      x������ � �      �      x������ � �      �   �  x���˭�0Eך^P��d��m$�꿄hf�$K^	�⚇���+}��t�h	Ǆ�>���Q�����+��~�x�L�����,�,&�R��W�S`Mm }$��`p�)�洊�>�0(:bo���Q}iP��󗯜���Bz[07\���t�.��0�.�;[w�L�����k��c�x���z^���{����{�-�@�/dU�l�W0?���bRJ{+� ��$K���M�7 �h[Ɋ�x]uɘd���(}����n�.�*��HX�/����?F]��-��[�V;������0ٗp��.��A<;����R��{�;���RL/�6˹!�g�Y�3���3�e|Ʒ�ׇ󞁙�M��z�^ ;��      �   �	  x���I�d7D��w��Y�%|o4��~��ʯ�l1%2�`�/��j�Ō6��U�iҝIu?|���/���_����q\����M�-��u�Vz����~9{·�|�݄>�z+;2�v� ݧ��˅s>>���&�:�]FC�&���jy�/����}>��LԖ8�{c�m)�Г����9�������yM�h-�W�i|�_��6��M,��;'&���T-��y��Tq��Hay�7�U��])3�߯���*�B1�I7m�`\�}�C�=χ��ۻ�J�k�P/�瘻���_ğ��(��1um��0F�xW�*@�)��X�G����	�FκI��%���'�Zq�{gMO�ڈRWC�c���lS�Y��!�j3w�S�c�����[�Հ�4g�VsF���s��ڗ�?���ѕh� �҇�-����J���2�B冔ň�L{+���Ϯ���3Y�A�>5��K��+	/���2�s�A�F��r�ڍШf��f��gLo�� -3����V�S��ZN���8� ��1\�{�Omm���D��R�*��/c݂��?�yjE���9��j���M�lV�9���~�� ��of�A���N�_mI�ʪ��I:���n���j5Z��n��Q���Cў��~
���@��������+�� ��(&W�:�h�8NI�����K� ]��tn��i.�ړ��u�A��`�����Flf�9�����W��tw�����!����|���+� ��
�I��Y)K�
��W��t��GR��ԩQ�|@�Q�^
��ԠH![�$�@�^���J���� ��R9�)9�\�����ҹ�b�nmm�1�56�H)�]O��Y�+;��5�BfwꫵjKx{)�[�[��(�E���(k5�9.��p<�ٰVa6�s|i[t�|�y|�.V9�ͺ �>�P_$���5�c:Xoh"Rh��;d�n#���{��s񕔳M[3(j ���LQR�Q�P8��lEL�Yl�h��ƈl#a�V^�,R��0c��4$� }^�?;����oA��)���(#\tK����I�2�gS�ʦs)�[��{�?���V�����v,؝�	�Q��pQ7֌یkr�j��,-w��"�Ԝ�� j�'����;��O �*H$]�twf�X�_���m�>��JG���aTDx���'"|W��[�]�𸤈p�wY�h+Χ_���\�l�qr����=�bJ�V�����}të�b����Z��{C~�[�vY�w�jʞ�i��]����1����Q%�����?t������ҽ����8_�aZ��?�	@2vs����S�`U���]I�8��X`��ȞR%Ǵm2{��7��@�A|�F��6,e*wU�7p�N�כ�p��=4T��l�}I��'?�Id�lK嶮F��`z�f�z����L/m���b Q�E<+�����4o�ꨏek�ê��kٮ��rp+���]O�0I�t�L��߶��2��#и�6�Y	4�8�kJƲ�x���� -��h���2s:�I\ѻ��4��0r��"���Ek�b�����43YM�{F� ����F�v�o�	8HW�5��mFp���s��aO��r�����t�䌓45Y��^�o�r�FM	�>BI���~!���^$�((3�n��P��ӡ<�7� ���Q�=MJ躟��KeaT�� ��(�oh߻.��W�K�U��J� ��O,'3����J��RK�W@:���������C̱$�.c/�PG�-\�L�!��UU��v<@�B)�Fh��v�?�m�D�\��n-{m��:���������=L��$=02�*��`���ު���F�ǝ�l�nF�۵���*���2�7�����Mۥ�Fw�ҕ�ۤ<wk@��Bњs#?�D߀������&�Vi����J����[5� �ۣ�M� �w����6t�_��>H�hՔ�k±#T[܂����|�	��	Ěp��[�{�� ������]�.%7lc���5� �4v�ȸ��Y�� �c�}�~?I\t1��M�3X������ru��q�>P�)%#,yh+-el�Ї{>��?��~́�����f�Q�2�R�F���-��+�,����G�]r���;ވmt���{�%���*S�E��ܿ�<p�=�W�vc�i�=0~X����^��M'���l��Aӎݞ�^��_��<�7�ͤ��6�k�SBٳ`��+����y���S]�F�o�������	��RM������{�
_x+�� �zV��rSa������1�V��G0Ժ�i���p��dp7|??J�aY����>\ǨHK�~�q�����~)�oD��
�߀�2�RAn���	��ls>�|mO����ϟ��JU      �   �  x���Kj�@���)|��z��1��Ȯ��Z��{��{�\���h��B`�@�g�_�Q�a}ܼ�qx��~8����4}�����H�O�0}퇏�?TB�+{�����o�wQQ)�xp�S�B���1��	j������Y�x[hC�:��1�C��T�e�]�Rv���EJ��z3CV��]i��5�J�B�]]�K-�>3����)��vC3���.�zm$�3¤�j������J�l��Ц"�%K���Ce0��Iu%qI�1T<>l*�eMCM�燥��d��up�T�YW��jw�[ڬ6\=���p������"[��0��uk�H|�=�6X���[?��Wo&�G��b��_M*�����=�I@�~|���a������m��      �   �   x�E��D!Ϧ�Ճ�_[��_��9Hs�@P�L����z`�>�L�,,���Y�A�� �- ��l"McE�j��&D�6�-ĝ��b���!v>W5nf�X�2Nh���T���cN�];��>�c�`v�;?es�ߧ����N5      �   �   x�U��q�0D�T1�K��T���-2��>C�gȱl����[�'+�N}o��ΛDj�2jE��V�ƨٚy3���|�����B5�(���J����F�l�=K|)ϡ���mK�����	4�b� F1+ӳ��10��`�q"�i�>���q�)��`,���8���3VɎ���-g��x�y��ZY��=����Uc.���l=Ig�qHN8��`�ǚ��X|���І�      �   T  x��XM�c�]�E��(d��v9�O #@HHl�9�O�����簜Ŭ�	�Ǩ��ss��SoZJ��=>�s��n~|�xؼI���>��|��_���&&�-R�TA6��b�!;�O7�7V[��č�[�dF1.�޼���.��	H��C����h]�7���+��(��?0�~O*�48���Q�s)ڄ؂Q�f�.1N1e��3���������PH��:�a�$gS�5���]zX��@y���2���=�S�y���F1e�
ꙓa��&Wc:p'�T4=e�^�8���(��Y	��\0�����%w�A��@���&%�F���2�虏�\vRjXru*2���|܀�ce���v�m��P���qJUC��6�Rn:��C6���j�c��挋3M��q���ؐJ+:Lią��F1e��������}]\#����Rz�X�tS��Fz���m�Ŕk�3V�p�ޡS�"�sX�/�Ev������B��w��F�6��5���-ր:�^�0�i�f�s�F4�g6@Y�;Y���S�0f�Ve=����n���eJ�/ș�'��D�b�R�)z�ؒ]֟���,�SL����k˹�.E�B25 c��2�:�-�lkG1��
{�&6ici 1`k�cn$mJg=�C=u}u}�b��ЋL5�h��]�%v;�%^$^��ە���O�i���m:��a�~�s+�������E
�Ai�.Jbn�Y3x��akfܘv hsLz��2ĳHT)��$�+t�\�u�%^1�U[�1e������UN�w�L禮��O��|�ۻ�\��EPRd�� ����+S�R�=���ե�1e��G:�Q�U.Ͷ1�&ۺ�n�|ń.�SV/��EJT� Z�b���&��(I�fW'��$��z�O%��##�\Ȩ&������-���9�,����L?��}]��ʐ��L�D^J�Z+w�s׉�X��������?c�5Y�U�)j+�KM�����.�:@7��Kc��]7�YB�7�"���\�}��K��*�9�P�;�����e���	׶Xl���U��1�O��t�r���೸ߥ2��v�����]��h�JYd򵶉s3C�X��K��s�� �b
��'zΐ��r��4$�F�MZJ�Gq�^)�S�8�|��{���E�,q^b�!���j����ϝ��n��Ώb
���W�ݴ�n��n�#�|=�|ӎKe��'�Q̝�hwl1C2Ic6$庾�y?�:3�)���<㠋t2��g�w���nR��R�q�b���ǘ"|��; ���\3��I�9N�Yn}=4��/��ǘ��`��aۈ��3�QL�PiE���o��P/|�)�^�=������Ϻ5��ڙg(َqS4��wv��1��SC��*V�>p�p��_�b�+�w�n	HA�(=/Q�d�b��D�Ѣ��[���Ö�(����%�t�Ok���'�h;�]dvU��Z^���t�So�<�z�K9%�����.�7�O%����T�o���~�����g��������?�%v���:�Pd7'9�G9(� ��ױk�"?h6���֏b�/�R5�s      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �   M  x��V�n�F<�|������M�eǆ_�w$��Xb�1(Ҡ���٣>�'��RCɲH��$�)V�tWu�H�-ˢ)VѢ���}�;w��~��}i�h�Z7v�y{r�:��Vk�n箮l=Om���C���=&�I���D,�	�O$�x�K�s�1�i�y����Z��utt�z�����[��cJ)#��i�3 y(Ɛ����V��  �	�G�*G���ryU<�����b�*׵�}��kt�*�'E�v��8ʒ��W�,��q��c�گ�sZ[�{�k:���ϥ��)�z�H�9OB1fvylc�Z�4�-�L@3���hB��|Ʋ��\`�(;���^4��W��Ѯ���-]Q�k��~ڼ�Cs�'%�k�k�ˣ|nΙ�4��F2:�.f_����hvwuwLڐ�I��R�Fc:�K�O^�������i]���*��Ҿ�{V�l��e����Wu�O�uB�i	�;�6�xZd�Ӫ6?�G�Ƙ�*Ř�t�څ]tڇ�21N��P'�:�"★�Ioc�r�J�ټ-��DL��>Y�d�B1&���A�Y�\�4$�q�o�W�@d7+ń�Kx����l������*aB��C��'=`�2��<�1$�^��#	R2��U(Ƹ�{�w[�����S��^�k)}E��da��F
�B��X��H!���Ϟ�h�����1�I:��M���$yH���x��W�P }Bm=��L��y혩H���?/����=������>�]״/��Cl*�����V`W�C��f���O!����0�/�v�%���\ɠ��\�0�f����Ek]��AF������I���+Q\^O 
Cj����*�V>��ʶH{��\��&�W-%��hZ�*�u���hb�v
"MggS�QL�.FXY��pF�@޾��An~�� �	C_����g�$C�>�P7������I����m#8������f�]iWd���h�1����;�%��2�mZ,��+C&	�%;��Ǡ�u�+�ѷ�ѬX�J�.�,.���k������w�XW�K��+{�%�ԇ��N���O|\�Et�E4�ɤd��A��8c�~a����:      �   J  x�M�ۑ�0���q��d������a�*����cȻ>�g�Ͽ�����/<������-x~a�]4D�(�8�����(:EQt�ʃJPզT��r�-�z�Z��Z�(3J�Ϭꔂ�z���NaȽܢr�0T.ޟY.���e��N��9k$(���r�0T.f�ϒ���l(-,jˇ���:x��%A}��*�����4���@@X��k`��*)+|�e������>���GX�Kai�#�/�y�>Z�e������>Bo�3�zFOX�wn��$��$z�Gۤ��WY�#,+|�e�����zs_��URO��������	�C���θ:Q�W}.:ju�����R�8fV�ߒ�:?�z��-���C荲G�[c�#{��3v���p��/���hg�9��#��%\~w;�#���b��8"۝|�����S�-��q��$���}���W�8V>���lw
���<��n�St0��^'<KH�������<*V����u������Jz���x�f�G=���}��?f5^9"����{�O�Ϯ��sd`���K��x��7��<�F��X�b����	7�<@g�/����d�
;O���W���M2��S�sk���q�rƎ+����-���ȋ��?��=�����H�������>~���+��1e�_���_�K��՞��ۙyx�Md(z��b����o�H��eg�=~O�L��'/3�>�9�ǟ�ѝ���l��o�_��J��e�����ү�c�����o��+�z���`���L������&����|��덙��~'�տ���`�u^��n��=T{~v���:�c���G��v�C����|]��<�G�s駘����wi�_�6ڊW�@���x�bI�9^�XY��)�,������}tb������8}��7�w�������'"����-"�|N�u�#؞�������O�{Ŀ����w>o����}lt�������Ċ�~���L?��C
�����������h�-��!�<O|E�3g���!���U04jo>�݂��=�,�9�Rg��:�ׯ���	���G|\駛���~~~�|�%      �   A  x���ю�*����y��F���x w���[h����g.&v�AQU o� W@PE�?�{��QK0>����n�B%�u?��7�Č��HEV�@���?���6u@9�䨥�shڗ�ûe���I)�O�h��r��^��z�^�:��S�4
�l_>f������$n^������:��oۥ7y :rE�h�WzRC�3r���+��L�pa���������4Ǧ��"h�c�§�`�׃�%�,�T<Xh�1��ӃV�̣��R�0�Vz�����לQ=~}8��V�A���UxQY�ةV0+�U V� ����6؂�2��ұ��������wk�X��'P|	���D�0�t���������h�Lv�C�$
'+�W�����ш���؛ͤ���҅2*��sǪٻ��(?)���m᳙3���hN�g�T?C,�T|tmPL`���[h�ze�lz�׌��8A��K�>E��eԓ+�n'�H������zL�.9��u��<E7�Nw�M�xTq�Ź0R��������i�"���̀g\wj���%M3-SQ��鋈lg(å2��Wٹp����t꓉���d���3e�����UNr�<�dm���z?dDd$帜���.�, �e@��'��q�G�뚊Far�X�;.�<����-��g%���3�pl���A����)�sq-"�n��������P�M�|(	Fs��T䃯u�V9��Q�2���D]�7Y�����=(�RIH�My9��8b;cia�x�b B�\����JO�T�^�7�����qY��\��Z9R���M�v������h��      �      x������ � �      �     x�u�1n!��<�Z �:�ЗY����(�ZuZ��}<�������y������VKCژ��t2M���fZk	��;���t�o��rlNk��lV6+����fe�2�+�ʰ2�+�ʰ2�+��ݜnNw���ݽ�{w��=�;Üa���+��ÜaN���9iN�w�wzwҜ4g�3͙�Ls�wgZ9�{��r���˽�{/������=[^��=�u87\{�pb�u{�{�����J�s��è��l����`�����Z�υˏ����,�{)�����      �   �  x���AN�0���)|��I�$�)I��R-��q�rcd��ݜaN��G���O�TA�M���{~�s:F�\2�<cj�#~�S4R�]�"��l9�>�I����|��Boz�O� �RG$�ď�(����Ri�~-��ϴ}�S����'��a���>_i�H���i����xQ��P�d)9�W`��~6��;8AΊ�h1t��0B�~Jݬ��!a�NLf�~��IN�g�rm+�Ȁ� �28���-?�t���d?�4��`%N��4��!O������� q;z�!E ���-�Ц~)e�%4r��G|�8,>R�;�`Bb?�]�Ks�����JB54~�]�/��S� ���D�3�`j��惛咃��?��B����g�R����V��(|7r�sq�#*���Jawv�C��g��.�!ޞ�+#�G\j@��e���\,x��Eh��E�6,ܭ5D�S�����F��N��KsB�Mg�l~3���_]�n�YV����e�'P.�76�kS|��R�c(��aJ#���ĔviN�]Z��ma����g����}�����>�BU�^�����Fs�l����Vrà'�d�"�s����c����<��69��Z(�B񷥯&�]�s�s����T      �      x������ � �      �   P   x�3�,��I���,.�,OM�4202�54�5�P04�2��2����2�(KM�ī��,�(5�$�B�ԜT�
c���� ��(�      �      x������ � �      �      x�3�4�2bc 6�=... c�      �   f   x�3�tL����,.)JL�/�,OM�4202�54�5�P04�2��2����2�t�K��LĢ�������1g@bQIfrfAb^Ij1�^+c+#clb\1z\\\ D�&�      �      x������ � �      �      x�}{˲�Z�u[�b7�[������  ((QD��܅^=����QQ��/�/��s2���L;"c��\c�1��m�$ʿ��ͼ|7ޥtQ��ԍ���
��"�"��Ùo3�����~E���}�&�7J�M\aBΖ��f�LWT��v2w��;�Ng��N�����I��_�_F�e^�ܲ|~���^Aa�B�~�/RZ��>��l�.�,:��=���F���xo��~S*�t�S�po���ڳ��~�{����ZA�4Ď���a����$�II���|����qȁ�>�/�	�NMasjƂ_$p��y���������r���F���.��*�э���@ҵ�Yɶ.;�Y�������d�����R�J"�6'g[�����eFI�_V�$n9K���a�S�~&1�����!�����uG��z�X��Kr�i��t���5^Y�`����"�o(6G��&����Kp�e$_V�|��P4��J�nYG^tw�:��i
�����l#L��>���4{#�	o ˁ@�r��"���J�ib�6G�_fS��d�����Of�45bï�5��w!,�mv�0���zw�lsv�EU�����RK��OP#�!�6G��*r3�k�D�{�/!J��;(�����<�i�'�8Z��d����Y�n��e0�Ƃ���ǭ��D�0���3:ɓ���9B��Ï"\�U]�<[|�U��tK��Xm�mTn�G�q�2��T"R�_V����v�%+N1���o�Fl�P������<H���Ŗa$I����2��dIL�,�5Օ6ו��"�Z�m鄀��ԡ���)9�C継u�z��rMas����9����}����K	����UXH뢈�����-�e�6�_i
��B���&�ݬX���O(&���6G�W�4�4x}��4��?��v�nH��(�j;(BءQ�}���v��k@0����@E�����׈�Q���m������<7��/ϓ��wo��.��
-S�p�f�MK�h��RJ���4ہ�@I�k'����6���M9�2�,)e2c����}��6�@�2��^(��¾j���%�M�x���]ҥ�If2��Q��8�-A����?����O�kӳ"G�(xݱI��ol�A�!*^�]��z��8bm�U��ɛI D �S��fbPF砬�/!φ��J��-�$����D�i�f�⹱�;*6N�C�Gr�Z���j��r�@��z����Yh�$���	l��S$_�}h"}iսu/?Tރ�Q@���/q��������8IK��g�0���oS�%fB�z���k�����dV�VX�'���;���A�� �묧���ف۟E�S8o�tS;؁W~bs���>�؇!���@����$t�"+�/��A^�"|��}�9[DD���Q'�?B~C߶��A�0"�-���+'Z?�'͑����ɕ�ѯ��Ɓ����!
Nͅ�{�����h�O�=� ^5��Q��2�9����+��z�CG��F֝����Sy}���N�'��c����]ku�͈!Wt\�	b�[��9���&�c���})����R�Co��,!|ͨ�up���wc�x���&����i����U�w^��N@��F�S���P8�l�7���r�Q��)�yV6Q�	�
%�x7��6�{�W�Ju=] �2�=Mh��[�'6��DU�U��V�zB�1$x%�܋���ᎁ�R����z$���-Y��N�&�)����'6���:+#�˿̼z)3�C3�)<�6�")�i��T�2�Lx�\9)N����{�<\�����[7��ߝ��c�Dٵ�b{7y%�A��2�5t+��(��:l�;T�����\��Z+��VDm��) Y�����1|��X@,���ZX��oiyZ�wY�,��ʧYZ3����QUa0X�9d�I̥B��O�E<���n]"�����W����*�&��,K�[������62q�qt5P<<�]��d���f�
���j�T��#�n���H��K��}l]�nӮ��=j"�f�ﴚ(��� P[���FI�s�jB�Q��E>�9F�P
-	B<�/������n����4�	$�����{��U3J���!jBa����MgS���^e�SZL���xbs�=��������^�A�3_�F��c.[=l��.(VKt�Z��9�R�摻b��^�	b9ј0��1=�9��2��؟J?�?��C���&�t�8�����Z,p,�u-�����.���w;l��b"�a��)l��3+(������%���C�[�*`j�ʼ��yߓ�g�/��p�Q6`�P�/B�ڰ脰���=�9�΄�����>H�K�%݉�M�q7E�0d����%F�w���ܥa���}�\�L=YT�6��q�4��S��{sOAh��Ed\"l�V�=(�%ɂ�Pm�
�Y|��	�@�h�r`�7���>�9N��(����}P6��4R�d�G��RR[�p��r����jŗN�.4e�rU��21a�1��)l��c�f��9x�W��}���>�����+Ycf��o����ʏ���{8��F艧:9NU?�{�#6��&�n`��5O�^D-宅7�LKn�y��c��Zmo=����gD�>�z}�k�1.�m`n�¨w�}bs����<�K���`�<�u�>h�Ni�Vb��N�VZ��lJJ�r���5�N(�*S֧�~i4(yׯr"� .�{�z�Mf�MK���� h����xO`��
T��ghhA��H��37)����)r���W����4؛m����K�o|�L�[J�)���1�"��ma����G0Тd}������`SƲ]0�;�do���J�Q�JA�*?Y���U1�i�j)�����VY]�yC�8���:F^#�Ol�ʹ
d�_S���&��A��!Ι�u��k���q�A����^q�^/0��-o��*��9��P]����.���.��|!���I&�d�u��ewRCl�x���,���	7\�6GЙ�� ������8�(�֭�Yr�����'��Á?�v��L\W��4�..�x�.��z��0�`ȻF	�J�H����������Xss}��٨��7��ty�t����rW�v�E� ԍ��_�h(KS�Hݯ8,eե�.e�?<�(�5��h�^����b� ùK��;�N-�*f���Ӯ3�f���E��_/���uU����.������5�Zހ�0e�.T�;v�g�}-)�LOas�9t��9;�8A���}��颓~�J��H2��\��,O<��M��Rt��`O*�^��<����1󅌕?zu5x�_s�����d&�C��Tv�{ߓ��AC�UEz��KM9�n�v��/�M�L�O�θH��-˨��lܟˠ|��d��F�S���[�#G��@�?���Cāl�Huj�� >�G{}iy��Z��ὠ�ی��C������AHwD���כ���m���]Z�+ee�� |݂o��'6'�U�h�����3Bp,�h�D	�w�Ll3 ⲰyX��?����l��]���\�=l5UH�/��N 3�I�/6l��^X}���"F��S����i:�d�`br��HK�/��7�?�
0x�<�9Hp?�&�l�,
_Oq>8�.0-sr�`ٻp*.���p�UE�^a�s�����!4Ӝ�	��#�OlN`3�	���{}��c:��wkT]Z��.G��1^1�C�p��]u��bw�H�bzKCԹ��<8�}�5bs��q�>0�0�s<F�������6PY��.��I=�:�ф�㖺U|&���>��	bf�hLl9��+��1!X�F�������F\�P��ݔ��U_흭ݸ�f}��FvS�E���'6�r탨=�x�_X}��KO	vz~��~F	S�*�oxӰ6aJ!��B+���@��<*~%��c���z��l�x���(�_+����}�g���r/L'�}��d,�/k,�r�����m|[u�	��Oؽ'6'��& >  :n��+�Ftq�����������2	��qQ��Q��߶��<�wS�,�R�&����'6'a��R�Y��x����M�-��;�����j6�B�2(Η�;��U$/�ڡ�o�fA�����?��|��'6'�_GA��x�V�^ټ���z�e׺��E�xK��3����@T�\����&U�X�����vjF���7~#6'Q������]]e@P��O�]w�5}s=�v��;A{��D�p�A�d�{���ZLT��f=�9����<�|h��wTZ2��fu`�Ad3V�(��0i��j����}��i�^
'ut���ފl��$��}��u_�_���xia_�l}��$w�okhA���1Al�w�$�g���!6��11&@�M�OlN����AeA�:�'>(�Zc�|��i��)����k����Iw��U����,d�b!��O-�Las�������jt�U�Zfb����eլJ��/��[���.<�V��LRT���b�H����C0�iD���9bs��G�Xo$6A�/�>M4�!d�Ԫ2��q�%�5��k��N����7}\a�$���#�=�}����$3S�����|x�Y��*�D{�[�̰cx�8��	ڮԨ��0H�ZJT��+R�a����!��n������՗���"��wI5���^�sH�'��0�]��@�d��j�å B8�_4�����̜j�z��'6�@>�ޚm¦�̾^�}Y�8�LY������XR̎�t�G�L$�l\>3�,E��kʉ�&���b�bs�ia�d�3L?�AlM[T�m7��m�	㊇�fAY�1���-ی�ْ�Wna
�[��Y2�y�����o&6�pq���F�����tY5VG�$���m��9K��tO`.�nf�A�ԥ}�:y���ao�FlN�36z��3�&HҗTB~p�=|/e-��9�Fx�ʑ]R��k/"1��x�!x����ɮ\�S%G�^����)bf}�˫�r�9�t(�C#�� 3k>�>��373�h��U��Hx�_�Y�ɧ��M�J�@C����ߣ���3^����4�]��Y3�zw��e4d�rh��/ �ݴy]��%�-L��9�(ێt�)#o�oD�3d��_qn嶑�e��)=�Aܖp~\��������A�,SI�`�u��N���a�,��}�����Oas��)����/�-�|��T.zCA{�ȋj��t!�Ͷ��\ "ޢ�eD�󽶌�j��ضc�]3�y�?�9��Z9+���9q_f���C���!��������S�Å�rZԯV�&[�}��b�=�"Ov���0��}��?�9�ά�Ez|s~��uk�X�,�Zr6��:����7V�v�m�P����r�kթ��@'��9��7-{w���~���k����Q�=���Y�8s2�7�]��m��[w�]qW���.'Dn��Q�؜�g��wlƶ ����T�j�ujC�d��4�n��� �	��Mv|h��.��1afm;o��O�`��$�K>w��O�As�����sŭK�?.�U��������0U��qgm��t:��5�����#�;��?�ʁU�]��W�������`{u��[�HT�ڇ�� �c��z����uX<Zv+�l�<s.�a�A Sļ`5�^����ѻ�U�c�9G����6��������'�i�GH��m�r{P��_̰�OZ�b�Q�)�������d����oGЂ� ��&�gP�6�O�6�M���.�oe����|垊Ƀ݋�=:
�Tc�ȟ����Gc�������.k��F�3��������?�� ���*/23���i�U�����헄�P�C).^�Ev?�S�2��;G��#g���i�^���;v���˟ ����gb�ւU�Ԥ�
)GjM�d;������{$�e�R9�Dr'��EOas��ې��柳�Q�h�����f�D�Զ�zL���l�="�&M�˽qA"�1r�1W��;;����6g��c x��o:D�WA��m����ԶR��~��-�����e��G8�Ƀ����[��L�?��?؜O<���3�|)���t.���;����Ѿ+�v@-�hy>s�q�_�۹z����f{o���2Bǋ4(3��t&!AI����-3 ���v�����y�P�q�	h�(�����̮��C7�mn$�D�C�N��T�6g�����v���Sut}��顒kR�(��B��!{��]S���x��6��j�o�4������b��5}�Q�(I/�OY��"a]�만� _�B�fT�
WB�2[�h��[�4�Y��WF�(��������ٳ1-��@�d���%�3������/��[h���7��p@�z���kA���b<�e��<8�G`z�!�o8xj�,�e�'���r�y�7~��Џ�!+7�� �Y�@�C��7i��"���a&i,�����ɴ��9�1�4��^�C-?�l``��a̠��!�TL>�����z=e�Las��[��6�A~����/�FH>۷}��&�'�&F�2JȐ�2�*�H���5<�����;_�,�s���Y.�<_qC�K��֛M~ܽ׃gd���( ��{�����N$r%|������+"/���PG�xa�����Q�	�ۇ��9���}����Xs�y!(��u���Գ������%��C{���=�b8��me\9חt��=�b�r��'�PPn:N�5fN��<ݚ�+(ē��Q�=��Uͷ�wJ��*�&�z��o���t}���H�Las���H�='������b ��*�|����ܣ�V������&'/�wº�P+6���S��ѪV���K?��=h��͙���rL�u�ktda|�a�����ty� 4lg��+�H��Fȧ�9�� {���la���'�,;.����-�����13��/%J��������l�?�>Y�V�9d4d���r?�~}�����-�=y%��R� �)΅� ��~��7l���;8�m�3 �6�pFI���I��%��j�M{<w���$��`��.��.���~�l��o-��6��<QV����~�~�r��kNc�	'�B�p�U�]Kt;TuĕH�M�)o�cY����0���%��l1Qp���0@��}�_ңy ��͐ӗ����%>T��q%-`UW-�J28�;�MR�X��c�����y���-Kb
��Αl�K�ρ&Zn�~�����o/���!�{sf��-�D�߻P�u-�|��i+۵e�Bd���Jݰ��׿s���]��0�m&DM5h�J�F�|M��O�.����!)��5dr������Ãc��W��l,�)����aq�Ϟ9�'6#Rr����S���$9������x���h���Ga��I��qu�f}�S��_� �������31�����Vn6���������?��E���wA������C�������Iu���i����T�3�j��bX�W�7\W�fǄ���DJ,��n���vՒ��d��ؽC�Xy�5���P������K�a�������X_�w     