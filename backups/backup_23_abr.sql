PGDMP         '                |           siplan %   12.18 (Ubuntu 12.18-0ubuntu0.20.04.1) %   12.18 (Ubuntu 12.18-0ubuntu0.20.04.1) �              0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false                       0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false                       0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false                       1262    30267    siplan    DATABASE     x   CREATE DATABASE siplan WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8';
    DROP DATABASE siplan;
                postgres    false                        2615    44824    estadistica    SCHEMA        CREATE SCHEMA estadistica;
    DROP SCHEMA estadistica;
                postgres    false                        2615    44825    planificacion    SCHEMA        CREATE SCHEMA planificacion;
    DROP SCHEMA planificacion;
                postgres    false                        2615    44826    proyecto    SCHEMA        CREATE SCHEMA proyecto;
    DROP SCHEMA proyecto;
                postgres    false                        3079    44827 	   uuid-ossp 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;
    DROP EXTENSION "uuid-ossp";
                   false                       0    0    EXTENSION "uuid-ossp"    COMMENT     W   COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';
                        false    2            �            1259    44838    formulario_clasificadores    TABLE       CREATE TABLE estadistica.formulario_clasificadores (
    id bigint NOT NULL,
    clasificador character varying(191) NOT NULL,
    clasificador_id integer,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 2   DROP TABLE estadistica.formulario_clasificadores;
       estadistica         heap    postgres    false    6            �            1259    44841     formulario_clasificadores_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_clasificadores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 <   DROP SEQUENCE estadistica.formulario_clasificadores_id_seq;
       estadistica          postgres    false    206    6                       0    0     formulario_clasificadores_id_seq    SEQUENCE OWNED BY     o   ALTER SEQUENCE estadistica.formulario_clasificadores_id_seq OWNED BY estadistica.formulario_clasificadores.id;
          estadistica          postgres    false    207            �            1259    44843 #   formulario_formulario_has_variables    TABLE     _  CREATE TABLE estadistica.formulario_formulario_has_variables (
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
       estadistica         heap    postgres    false    6            �            1259    44847 *   formulario_formulario_has_variables_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_formulario_has_variables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 F   DROP SEQUENCE estadistica.formulario_formulario_has_variables_id_seq;
       estadistica          postgres    false    6    208                       0    0 *   formulario_formulario_has_variables_id_seq    SEQUENCE OWNED BY     �   ALTER SEQUENCE estadistica.formulario_formulario_has_variables_id_seq OWNED BY estadistica.formulario_formulario_has_variables.id;
          estadistica          postgres    false    209            �            1259    44849    formulario_formularios    TABLE     Q  CREATE TABLE estadistica.formulario_formularios (
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
       estadistica         heap    postgres    false    6            �            1259    44852    formulario_formularios_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_formularios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE estadistica.formulario_formularios_id_seq;
       estadistica          postgres    false    6    210                        0    0    formulario_formularios_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE estadistica.formulario_formularios_id_seq OWNED BY estadistica.formulario_formularios.id;
          estadistica          postgres    false    211            �            1259    44854    formulario_items    TABLE       CREATE TABLE estadistica.formulario_items (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    questions text NOT NULL,
    variable_id integer,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE estadistica.formulario_items;
       estadistica         heap    postgres    false    6            �            1259    44860    formulario_items_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE estadistica.formulario_items_id_seq;
       estadistica          postgres    false    212    6            !           0    0    formulario_items_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE estadistica.formulario_items_id_seq OWNED BY estadistica.formulario_items.id;
          estadistica          postgres    false    213            �            1259    44862    formulario_variables    TABLE     r  CREATE TABLE estadistica.formulario_variables (
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
       estadistica         heap    postgres    false    6            �            1259    44867    formulario_variables_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_variables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 7   DROP SEQUENCE estadistica.formulario_variables_id_seq;
       estadistica          postgres    false    6    214            "           0    0    formulario_variables_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE estadistica.formulario_variables_id_seq OWNED BY estadistica.formulario_variables.id;
          estadistica          postgres    false    215            �            1259    44869    foda_analisis    TABLE     m  CREATE TABLE planificacion.foda_analisis (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    perfil_id uuid NOT NULL,
    aspecto_id integer,
    tipo character varying(255) NOT NULL,
    ocurrencia numeric(8,2),
    impacto numeric(8,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT foda_analisis_tipo_check CHECK (((tipo)::text = ANY (ARRAY[('Pendiente'::character varying)::text, ('Fortaleza'::character varying)::text, ('Oportunidad'::character varying)::text, ('Debilidad'::character varying)::text, ('Amenaza'::character varying)::text])))
);
 (   DROP TABLE planificacion.foda_analisis;
       planificacion         heap    postgres    false    7            �            1259    44873    foda_analisis_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_analisis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE planificacion.foda_analisis_id_seq;
       planificacion          postgres    false    216    7            #           0    0    foda_analisis_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE planificacion.foda_analisis_id_seq OWNED BY planificacion.foda_analisis.id;
          planificacion          postgres    false    217            �            1259    44875    foda_categorias_has_perfil    TABLE     �   CREATE TABLE planificacion.foda_categorias_has_perfil (
    perfil_id uuid NOT NULL,
    category_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 5   DROP TABLE planificacion.foda_categorias_has_perfil;
       planificacion         heap    postgres    false    7            �            1259    44878    foda_cruce_ambientes    TABLE     -  CREATE TABLE planificacion.foda_cruce_ambientes (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    perfil_id uuid NOT NULL,
    estrategia text NOT NULL,
    tipo character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE planificacion.foda_cruce_ambientes;
       planificacion         heap    postgres    false    7            �            1259    44884 !   foda_cruce_ambientes_has_amenazas    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_amenazas (
    cruce_id integer NOT NULL,
    amenaza_id integer NOT NULL
);
 <   DROP TABLE planificacion.foda_cruce_ambientes_has_amenazas;
       planificacion         heap    postgres    false    7            �            1259    44887 $   foda_cruce_ambientes_has_debilidades    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_debilidades (
    cruce_id integer NOT NULL,
    debilidad_id integer NOT NULL
);
 ?   DROP TABLE planificacion.foda_cruce_ambientes_has_debilidades;
       planificacion         heap    postgres    false    7            �            1259    44890 #   foda_cruce_ambientes_has_fortalezas    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_fortalezas (
    cruce_id integer NOT NULL,
    fortaleza_id integer NOT NULL
);
 >   DROP TABLE planificacion.foda_cruce_ambientes_has_fortalezas;
       planificacion         heap    postgres    false    7            �            1259    44893 &   foda_cruce_ambientes_has_oportunidades    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_oportunidades (
    cruce_id integer NOT NULL,
    oportunidad_id integer NOT NULL
);
 A   DROP TABLE planificacion.foda_cruce_ambientes_has_oportunidades;
       planificacion         heap    postgres    false    7            �            1259    44896    foda_cruce_ambientes_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_cruce_ambientes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE planificacion.foda_cruce_ambientes_id_seq;
       planificacion          postgres    false    7    219            $           0    0    foda_cruce_ambientes_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE planificacion.foda_cruce_ambientes_id_seq OWNED BY planificacion.foda_cruce_ambientes.id;
          planificacion          postgres    false    224            �            1259    44898    foda_groups_has_perfiles    TABLE     �   CREATE TABLE planificacion.foda_groups_has_perfiles (
    id bigint NOT NULL,
    perfil_id uuid NOT NULL,
    group_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 3   DROP TABLE planificacion.foda_groups_has_perfiles;
       planificacion         heap    postgres    false    7            �            1259    44901    foda_groups_has_perfiles_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_groups_has_perfiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 =   DROP SEQUENCE planificacion.foda_groups_has_perfiles_id_seq;
       planificacion          postgres    false    7    225            %           0    0    foda_groups_has_perfiles_id_seq    SEQUENCE OWNED BY     q   ALTER SEQUENCE planificacion.foda_groups_has_perfiles_id_seq OWNED BY planificacion.foda_groups_has_perfiles.id;
          planificacion          postgres    false    226            �            1259    44903    foda_models    TABLE     �  CREATE TABLE planificacion.foda_models (
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
       planificacion         heap    postgres    false    7            �            1259    44911    foda_models_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_models_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE planificacion.foda_models_id_seq;
       planificacion          postgres    false    227    7            &           0    0    foda_models_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE planificacion.foda_models_id_seq OWNED BY planificacion.foda_models.id;
          planificacion          postgres    false    228            �            1259    44913    foda_perfiles    TABLE     r  CREATE TABLE planificacion.foda_perfiles (
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
       planificacion         heap    postgres    false    7            �            1259    44919    pei_profiles    TABLE     7  CREATE TABLE planificacion.pei_profiles (
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
       planificacion         heap    postgres    false    7            �            1259    44927    pei_profiles_has_dependencies    TABLE     �   CREATE TABLE planificacion.pei_profiles_has_dependencies (
    id bigint NOT NULL,
    pei_profile_id uuid NOT NULL,
    dependency_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 8   DROP TABLE planificacion.pei_profiles_has_dependencies;
       planificacion         heap    postgres    false    7            �            1259    44930 $   pei_profiles_has_dependencies_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 B   DROP SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq;
       planificacion          postgres    false    231    7            '           0    0 $   pei_profiles_has_dependencies_id_seq    SEQUENCE OWNED BY     {   ALTER SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq OWNED BY planificacion.pei_profiles_has_dependencies.id;
          planificacion          postgres    false    232            �            1259    44932    pei_profiles_has_strategies    TABLE     �   CREATE TABLE planificacion.pei_profiles_has_strategies (
    id bigint NOT NULL,
    profile_id uuid NOT NULL,
    strategy_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 6   DROP TABLE planificacion.pei_profiles_has_strategies;
       planificacion         heap    postgres    false    7            �            1259    44935 "   pei_profiles_has_strategies_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.pei_profiles_has_strategies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 @   DROP SEQUENCE planificacion.pei_profiles_has_strategies_id_seq;
       planificacion          postgres    false    7    233            (           0    0 "   pei_profiles_has_strategies_id_seq    SEQUENCE OWNED BY     w   ALTER SEQUENCE planificacion.pei_profiles_has_strategies_id_seq OWNED BY planificacion.pei_profiles_has_strategies.id;
          planificacion          postgres    false    234            �            1259    44937    peis_profiles_has_analysts    TABLE     �   CREATE TABLE planificacion.peis_profiles_has_analysts (
    id bigint NOT NULL,
    pei_profile_id uuid NOT NULL,
    analyst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 5   DROP TABLE planificacion.peis_profiles_has_analysts;
       planificacion         heap    postgres    false    7            �            1259    44940 !   peis_profiles_has_analysts_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.peis_profiles_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ?   DROP SEQUENCE planificacion.peis_profiles_has_analysts_id_seq;
       planificacion          postgres    false    7    235            )           0    0 !   peis_profiles_has_analysts_id_seq    SEQUENCE OWNED BY     u   ALTER SEQUENCE planificacion.peis_profiles_has_analysts_id_seq OWNED BY planificacion.peis_profiles_has_analysts.id;
          planificacion          postgres    false    236            �            1259    44942    peis_profiles_has_responsibles    TABLE     �   CREATE TABLE planificacion.peis_profiles_has_responsibles (
    id bigint NOT NULL,
    profile_id uuid NOT NULL,
    responsible_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 9   DROP TABLE planificacion.peis_profiles_has_responsibles;
       planificacion         heap    postgres    false    7            �            1259    44945 %   peis_profiles_has_responsibles_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 C   DROP SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq;
       planificacion          postgres    false    237    7            *           0    0 %   peis_profiles_has_responsibles_id_seq    SEQUENCE OWNED BY     }   ALTER SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq OWNED BY planificacion.peis_profiles_has_responsibles.id;
          planificacion          postgres    false    238            �            1259    44947    tasks    TABLE     �   CREATE TABLE planificacion.tasks (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    details character varying(191),
    status integer DEFAULT 0,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
     DROP TABLE planificacion.tasks;
       planificacion         heap    postgres    false    7            �            1259    44951    tasks_has_analysts    TABLE     �   CREATE TABLE planificacion.tasks_has_analysts (
    id bigint NOT NULL,
    task_id integer NOT NULL,
    analyst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 -   DROP TABLE planificacion.tasks_has_analysts;
       planificacion         heap    postgres    false    7            �            1259    44954    tasks_has_analysts_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.tasks_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 7   DROP SEQUENCE planificacion.tasks_has_analysts_id_seq;
       planificacion          postgres    false    7    240            +           0    0    tasks_has_analysts_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE planificacion.tasks_has_analysts_id_seq OWNED BY planificacion.tasks_has_analysts.id;
          planificacion          postgres    false    241            .           1259    45665    tasks_has_type_tasks    TABLE     '  CREATE TABLE planificacion.tasks_has_type_tasks (
    id bigint,
    task_id integer,
    typetaskable_id uuid,
    typetaskable_type character varying,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
 /   DROP TABLE planificacion.tasks_has_type_tasks;
       planificacion         heap    postgres    false    7            �            1259    44962    tasks_id_seq    SEQUENCE     |   CREATE SEQUENCE planificacion.tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE planificacion.tasks_id_seq;
       planificacion          postgres    false    239    7            ,           0    0    tasks_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE planificacion.tasks_id_seq OWNED BY planificacion.tasks.id;
          planificacion          postgres    false    242            �            1259    44964 	   typetasks    TABLE     #  CREATE TABLE planificacion.typetasks (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    typetaskable_id uuid NOT NULL,
    typetaskable_type character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 $   DROP TABLE planificacion.typetasks;
       planificacion         heap    postgres    false    7            �            1259    44967    typetasks_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.typetasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE planificacion.typetasks_id_seq;
       planificacion          postgres    false    243    7            -           0    0    typetasks_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE planificacion.typetasks_id_seq OWNED BY planificacion.typetasks.id;
          planificacion          postgres    false    244            �            1259    44969    e_p_c_equipamientos    TABLE       CREATE TABLE proyecto.e_p_c_equipamientos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE proyecto.e_p_c_equipamientos;
       proyecto         heap    postgres    false    8            �            1259    44972    e_p_c_equipamientos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_equipamientos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE proyecto.e_p_c_equipamientos_id_seq;
       proyecto          postgres    false    245    8            .           0    0    e_p_c_equipamientos_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE proyecto.e_p_c_equipamientos_id_seq OWNED BY proyecto.e_p_c_equipamientos.id;
          proyecto          postgres    false    246            �            1259    44974    e_p_c_equipamientos_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_equipamientos_servicios (
    servicio_id integer NOT NULL,
    equipamiento_id integer NOT NULL,
    cantidad integer NOT NULL
);
 3   DROP TABLE proyecto.e_p_c_equipamientos_servicios;
       proyecto         heap    postgres    false    8            �            1259    44977    e_p_c_especialidads    TABLE     6  CREATE TABLE proyecto.e_p_c_especialidads (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE proyecto.e_p_c_especialidads;
       proyecto         heap    postgres    false    8            �            1259    44983    e_p_c_especialidads_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_especialidads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE proyecto.e_p_c_especialidads_id_seq;
       proyecto          postgres    false    248    8            /           0    0    e_p_c_especialidads_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE proyecto.e_p_c_especialidads_id_seq OWNED BY proyecto.e_p_c_especialidads.id;
          proyecto          postgres    false    249            �            1259    44985    e_p_c_estandars    TABLE       CREATE TABLE proyecto.e_p_c_estandars (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 %   DROP TABLE proyecto.e_p_c_estandars;
       proyecto         heap    postgres    false    8            �            1259    44991    e_p_c_estandars_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_estandars_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE proyecto.e_p_c_estandars_id_seq;
       proyecto          postgres    false    8    250            0           0    0    e_p_c_estandars_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE proyecto.e_p_c_estandars_id_seq OWNED BY proyecto.e_p_c_estandars.id;
          proyecto          postgres    false    251            �            1259    44993    e_p_c_estandars_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_estandars_servicios (
    estandar_id integer NOT NULL,
    servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);
 /   DROP TABLE proyecto.e_p_c_estandars_servicios;
       proyecto         heap    postgres    false    8            �            1259    44996    e_p_c_horarios    TABLE     '  CREATE TABLE proyecto.e_p_c_horarios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    start_time character varying(191) NOT NULL,
    end_time character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 $   DROP TABLE proyecto.e_p_c_horarios;
       proyecto         heap    postgres    false    8            �            1259    45002    e_p_c_horarios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_horarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE proyecto.e_p_c_horarios_id_seq;
       proyecto          postgres    false    253    8            1           0    0    e_p_c_horarios_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE proyecto.e_p_c_horarios_id_seq OWNED BY proyecto.e_p_c_horarios.id;
          proyecto          postgres    false    254            �            1259    45004    e_p_c_infraestructura_servicio    TABLE     �   CREATE TABLE proyecto.e_p_c_infraestructura_servicio (
    servicio_id integer NOT NULL,
    infraestructura_id integer NOT NULL,
    cantidad integer NOT NULL
);
 4   DROP TABLE proyecto.e_p_c_infraestructura_servicio;
       proyecto         heap    postgres    false    8                        1259    45007    e_p_c_infraestructuras    TABLE     J  CREATE TABLE proyecto.e_p_c_infraestructuras (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    measurement double precision NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 ,   DROP TABLE proyecto.e_p_c_infraestructuras;
       proyecto         heap    postgres    false    8                       1259    45010    e_p_c_infraestructuras_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_infraestructuras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE proyecto.e_p_c_infraestructuras_id_seq;
       proyecto          postgres    false    8    256            2           0    0    e_p_c_infraestructuras_id_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE proyecto.e_p_c_infraestructuras_id_seq OWNED BY proyecto.e_p_c_infraestructuras.id;
          proyecto          postgres    false    257                       1259    45012    e_p_c_medicamento_insumos    TABLE     "  CREATE TABLE proyecto.e_p_c_medicamento_insumos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE proyecto.e_p_c_medicamento_insumos;
       proyecto         heap    postgres    false    8                       1259    45015     e_p_c_medicamento_insumos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq;
       proyecto          postgres    false    8    258            3           0    0     e_p_c_medicamento_insumos_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq OWNED BY proyecto.e_p_c_medicamento_insumos.id;
          proyecto          postgres    false    259                       1259    45017    e_p_c_otro_servicios    TABLE       CREATE TABLE proyecto.e_p_c_otro_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 *   DROP TABLE proyecto.e_p_c_otro_servicios;
       proyecto         heap    postgres    false    8                       1259    45020    e_p_c_otro_servicios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_otro_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 4   DROP SEQUENCE proyecto.e_p_c_otro_servicios_id_seq;
       proyecto          postgres    false    8    260            4           0    0    e_p_c_otro_servicios_id_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE proyecto.e_p_c_otro_servicios_id_seq OWNED BY proyecto.e_p_c_otro_servicios.id;
          proyecto          postgres    false    261                       1259    45022    e_p_c_prestaciones    TABLE     ;  CREATE TABLE proyecto.e_p_c_prestaciones (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 (   DROP TABLE proyecto.e_p_c_prestaciones;
       proyecto         heap    postgres    false    8                       1259    45028    e_p_c_prestaciones_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_prestaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE proyecto.e_p_c_prestaciones_id_seq;
       proyecto          postgres    false    262    8            5           0    0    e_p_c_prestaciones_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE proyecto.e_p_c_prestaciones_id_seq OWNED BY proyecto.e_p_c_prestaciones.id;
          proyecto          postgres    false    263                       1259    45030    e_p_c_servicios    TABLE       CREATE TABLE proyecto.e_p_c_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 %   DROP TABLE proyecto.e_p_c_servicios;
       proyecto         heap    postgres    false    8            	           1259    45036    e_p_c_servicios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE proyecto.e_p_c_servicios_id_seq;
       proyecto          postgres    false    264    8            6           0    0    e_p_c_servicios_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE proyecto.e_p_c_servicios_id_seq OWNED BY proyecto.e_p_c_servicios.id;
          proyecto          postgres    false    265            
           1259    45038    e_p_c_talento_humanos    TABLE     I  CREATE TABLE proyecto.e_p_c_talento_humanos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    hours character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 +   DROP TABLE proyecto.e_p_c_talento_humanos;
       proyecto         heap    postgres    false    8                       1259    45044    e_p_c_talento_humanos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_talento_humanos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE proyecto.e_p_c_talento_humanos_id_seq;
       proyecto          postgres    false    8    266            7           0    0    e_p_c_talento_humanos_id_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE proyecto.e_p_c_talento_humanos_id_seq OWNED BY proyecto.e_p_c_talento_humanos.id;
          proyecto          postgres    false    267                       1259    45046    e_p_c_tthhs_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_tthhs_servicios (
    servicio_id integer NOT NULL,
    tthh_id integer NOT NULL,
    cantidad integer NOT NULL
);
 +   DROP TABLE proyecto.e_p_c_tthhs_servicios;
       proyecto         heap    postgres    false    8                       1259    45049    e_p_c_turnos    TABLE     �   CREATE TABLE proyecto.e_p_c_turnos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 "   DROP TABLE proyecto.e_p_c_turnos;
       proyecto         heap    postgres    false    8                       1259    45052    e_p_c_turnos_id_seq    SEQUENCE     ~   CREATE SEQUENCE proyecto.e_p_c_turnos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE proyecto.e_p_c_turnos_id_seq;
       proyecto          postgres    false    8    269            8           0    0    e_p_c_turnos_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE proyecto.e_p_c_turnos_id_seq OWNED BY proyecto.e_p_c_turnos.id;
          proyecto          postgres    false    270                       1259    45054    otroservicio_has_servicios    TABLE     �   CREATE TABLE proyecto.otroservicio_has_servicios (
    servicio_id integer NOT NULL,
    otro_servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);
 0   DROP TABLE proyecto.otroservicio_has_servicios;
       proyecto         heap    postgres    false    8            -           1259    45629 
   activities    TABLE     �   CREATE TABLE public.activities (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.activities;
       public         heap    postgres    false            ,           1259    45627    activities_id_seq    SEQUENCE     z   CREATE SEQUENCE public.activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.activities_id_seq;
       public          postgres    false    301            9           0    0    activities_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.activities_id_seq OWNED BY public.activities.id;
          public          postgres    false    300                       1259    45057 
   categories    TABLE     �   CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(128) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.categories;
       public         heap    postgres    false                       1259    45060    categories_id_seq    SEQUENCE     z   CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.categories_id_seq;
       public          postgres    false    272            :           0    0    categories_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;
          public          postgres    false    273                       1259    45062    groups    TABLE        CREATE TABLE public.groups (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.groups;
       public         heap    postgres    false                       1259    45067    groups_has_members    TABLE     �   CREATE TABLE public.groups_has_members (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 &   DROP TABLE public.groups_has_members;
       public         heap    postgres    false                       1259    45070    groups_has_members_id_seq    SEQUENCE     �   CREATE SEQUENCE public.groups_has_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.groups_has_members_id_seq;
       public          postgres    false    275            ;           0    0    groups_has_members_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE public.groups_has_members_id_seq OWNED BY public.groups_has_members.id;
          public          postgres    false    276                       1259    45072    groups_id_seq    SEQUENCE     v   CREATE SEQUENCE public.groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.groups_id_seq;
       public          postgres    false    274            <           0    0    groups_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.groups_id_seq OWNED BY public.groups.id;
          public          postgres    false    277            )           1259    45607 
   localities    TABLE     L  CREATE TABLE public.localities (
    id bigint NOT NULL,
    cod_dpto integer NOT NULL,
    desc_dpto character varying(191) NOT NULL,
    cod_dist integer NOT NULL,
    desc_dist character varying(191) NOT NULL,
    area integer NOT NULL,
    cod_barrio_loc integer NOT NULL,
    desc_barrio_loc character varying(191) NOT NULL
);
    DROP TABLE public.localities;
       public         heap    postgres    false            (           1259    45605    localities_id_seq    SEQUENCE     z   CREATE SEQUENCE public.localities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.localities_id_seq;
       public          postgres    false    297            =           0    0    localities_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.localities_id_seq OWNED BY public.localities.id;
          public          postgres    false    296                       1259    45074 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(191) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap    postgres    false                       1259    45077    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public          postgres    false    278            >           0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public          postgres    false    279                       1259    45079    model_has_permissions    TABLE     �   CREATE TABLE public.model_has_permissions (
    permission_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);
 )   DROP TABLE public.model_has_permissions;
       public         heap    postgres    false                       1259    45082    model_has_roles    TABLE     �   CREATE TABLE public.model_has_roles (
    role_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);
 #   DROP TABLE public.model_has_roles;
       public         heap    postgres    false                       1259    45085    organigramas    TABLE     �  CREATE TABLE public.organigramas (
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
       public         heap    postgres    false                       1259    45093    organigramas_id_seq    SEQUENCE     |   CREATE SEQUENCE public.organigramas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.organigramas_id_seq;
       public          postgres    false    282            ?           0    0    organigramas_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.organigramas_id_seq OWNED BY public.organigramas.id;
          public          postgres    false    283                       1259    45095    password_resets    TABLE     �   CREATE TABLE public.password_resets (
    email character varying(191) NOT NULL,
    token character varying(191) NOT NULL,
    created_at timestamp(0) without time zone
);
 #   DROP TABLE public.password_resets;
       public         heap    postgres    false            +           1259    45618    patrimonies    TABLE     �  CREATE TABLE public.patrimonies (
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
    DROP TABLE public.patrimonies;
       public         heap    postgres    false            *           1259    45616    patrimonies_id_seq    SEQUENCE     {   CREATE SEQUENCE public.patrimonies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.patrimonies_id_seq;
       public          postgres    false    299            @           0    0    patrimonies_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.patrimonies_id_seq OWNED BY public.patrimonies.id;
          public          postgres    false    298                       1259    45098    permissions    TABLE     �   CREATE TABLE public.permissions (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.permissions;
       public         heap    postgres    false                       1259    45101    permissions_id_seq    SEQUENCE     �   CREATE SEQUENCE public.permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.permissions_id_seq;
       public          postgres    false    285            A           0    0    permissions_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;
          public          postgres    false    286                       1259    45103    risks    TABLE     �   CREATE TABLE public.risks (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    detail character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.risks;
       public         heap    postgres    false                        1259    45106    risks_id_seq    SEQUENCE     u   CREATE SEQUENCE public.risks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.risks_id_seq;
       public          postgres    false    287            B           0    0    risks_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.risks_id_seq OWNED BY public.risks.id;
          public          postgres    false    288            !           1259    45108    role_has_permissions    TABLE     o   CREATE TABLE public.role_has_permissions (
    permission_id integer NOT NULL,
    role_id integer NOT NULL
);
 (   DROP TABLE public.role_has_permissions;
       public         heap    postgres    false            "           1259    45111    roles    TABLE     �   CREATE TABLE public.roles (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.roles;
       public         heap    postgres    false            #           1259    45114    roles_id_seq    SEQUENCE     �   CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.roles_id_seq;
       public          postgres    false    290            C           0    0    roles_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;
          public          postgres    false    291            $           1259    45116 	   servicios    TABLE     b  CREATE TABLE public.servicios (
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
       public         heap    postgres    false            %           1259    45121    servicios_id_seq    SEQUENCE     y   CREATE SEQUENCE public.servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.servicios_id_seq;
       public          postgres    false    292            D           0    0    servicios_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.servicios_id_seq OWNED BY public.servicios.id;
          public          postgres    false    293            &           1259    45123    users    TABLE     �  CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    email character varying(191) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(191) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    group_id integer
);
    DROP TABLE public.users;
       public         heap    postgres    false            '           1259    45129    users_id_seq    SEQUENCE     u   CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public          postgres    false    294            E           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public          postgres    false    295            Z           2604    45131    formulario_clasificadores id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_clasificadores ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_clasificadores_id_seq'::regclass);
 P   ALTER TABLE estadistica.formulario_clasificadores ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    207    206            \           2604    45132 &   formulario_formulario_has_variables id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formulario_has_variables_id_seq'::regclass);
 Z   ALTER TABLE estadistica.formulario_formulario_has_variables ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    209    208            ]           2604    45133    formulario_formularios id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_formularios ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formularios_id_seq'::regclass);
 M   ALTER TABLE estadistica.formulario_formularios ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    211    210            ^           2604    45134    formulario_items id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_items ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_items_id_seq'::regclass);
 G   ALTER TABLE estadistica.formulario_items ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    213    212            a           2604    45135    formulario_variables id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_variables_id_seq'::regclass);
 K   ALTER TABLE estadistica.formulario_variables ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    215    214            b           2604    45136    foda_analisis id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_analisis ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_analisis_id_seq'::regclass);
 F   ALTER TABLE planificacion.foda_analisis ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    217    216            d           2604    45137    foda_cruce_ambientes id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_cruce_ambientes_id_seq'::regclass);
 M   ALTER TABLE planificacion.foda_cruce_ambientes ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    224    219            e           2604    45138    foda_groups_has_perfiles id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_groups_has_perfiles_id_seq'::regclass);
 Q   ALTER TABLE planificacion.foda_groups_has_perfiles ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    226    225            h           2604    45139    foda_models id    DEFAULT     ~   ALTER TABLE ONLY planificacion.foda_models ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_models_id_seq'::regclass);
 D   ALTER TABLE planificacion.foda_models ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    228    227            k           2604    45140     pei_profiles_has_dependencies id    DEFAULT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_dependencies_id_seq'::regclass);
 V   ALTER TABLE planificacion.pei_profiles_has_dependencies ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    232    231            l           2604    45141    pei_profiles_has_strategies id    DEFAULT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_strategies_id_seq'::regclass);
 T   ALTER TABLE planificacion.pei_profiles_has_strategies ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    234    233            m           2604    45142    peis_profiles_has_analysts id    DEFAULT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_analysts_id_seq'::regclass);
 S   ALTER TABLE planificacion.peis_profiles_has_analysts ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    236    235            n           2604    45143 !   peis_profiles_has_responsibles id    DEFAULT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_responsibles_id_seq'::regclass);
 W   ALTER TABLE planificacion.peis_profiles_has_responsibles ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    238    237            p           2604    45144    tasks id    DEFAULT     r   ALTER TABLE ONLY planificacion.tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_id_seq'::regclass);
 >   ALTER TABLE planificacion.tasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    242    239            q           2604    45145    tasks_has_analysts id    DEFAULT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_analysts_id_seq'::regclass);
 K   ALTER TABLE planificacion.tasks_has_analysts ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    241    240            r           2604    45147    typetasks id    DEFAULT     z   ALTER TABLE ONLY planificacion.typetasks ALTER COLUMN id SET DEFAULT nextval('planificacion.typetasks_id_seq'::regclass);
 B   ALTER TABLE planificacion.typetasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    244    243            s           2604    45148    e_p_c_equipamientos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_equipamientos_id_seq'::regclass);
 G   ALTER TABLE proyecto.e_p_c_equipamientos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    246    245            t           2604    45149    e_p_c_especialidads id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_especialidads ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_especialidads_id_seq'::regclass);
 G   ALTER TABLE proyecto.e_p_c_especialidads ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    249    248            u           2604    45150    e_p_c_estandars id    DEFAULT     |   ALTER TABLE ONLY proyecto.e_p_c_estandars ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_estandars_id_seq'::regclass);
 C   ALTER TABLE proyecto.e_p_c_estandars ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    251    250            v           2604    45151    e_p_c_horarios id    DEFAULT     z   ALTER TABLE ONLY proyecto.e_p_c_horarios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_horarios_id_seq'::regclass);
 B   ALTER TABLE proyecto.e_p_c_horarios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    254    253            w           2604    45152    e_p_c_infraestructuras id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_infraestructuras_id_seq'::regclass);
 J   ALTER TABLE proyecto.e_p_c_infraestructuras ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    257    256            x           2604    45153    e_p_c_medicamento_insumos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_medicamento_insumos_id_seq'::regclass);
 M   ALTER TABLE proyecto.e_p_c_medicamento_insumos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    259    258            y           2604    45154    e_p_c_otro_servicios id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_otro_servicios_id_seq'::regclass);
 H   ALTER TABLE proyecto.e_p_c_otro_servicios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    261    260            z           2604    45155    e_p_c_prestaciones id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_prestaciones ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_prestaciones_id_seq'::regclass);
 F   ALTER TABLE proyecto.e_p_c_prestaciones ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    263    262            {           2604    45156    e_p_c_servicios id    DEFAULT     |   ALTER TABLE ONLY proyecto.e_p_c_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_servicios_id_seq'::regclass);
 C   ALTER TABLE proyecto.e_p_c_servicios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    265    264            |           2604    45157    e_p_c_talento_humanos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_talento_humanos_id_seq'::regclass);
 I   ALTER TABLE proyecto.e_p_c_talento_humanos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    267    266            }           2604    45158    e_p_c_turnos id    DEFAULT     v   ALTER TABLE ONLY proyecto.e_p_c_turnos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_turnos_id_seq'::regclass);
 @   ALTER TABLE proyecto.e_p_c_turnos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    270    269            �           2604    45632    activities id    DEFAULT     n   ALTER TABLE ONLY public.activities ALTER COLUMN id SET DEFAULT nextval('public.activities_id_seq'::regclass);
 <   ALTER TABLE public.activities ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    301    300    301            ~           2604    45159    categories id    DEFAULT     n   ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);
 <   ALTER TABLE public.categories ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    273    272            �           2604    45160 	   groups id    DEFAULT     f   ALTER TABLE ONLY public.groups ALTER COLUMN id SET DEFAULT nextval('public.groups_id_seq'::regclass);
 8   ALTER TABLE public.groups ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    277    274            �           2604    45161    groups_has_members id    DEFAULT     ~   ALTER TABLE ONLY public.groups_has_members ALTER COLUMN id SET DEFAULT nextval('public.groups_has_members_id_seq'::regclass);
 D   ALTER TABLE public.groups_has_members ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    276    275            �           2604    45635    localities id    DEFAULT     n   ALTER TABLE ONLY public.localities ALTER COLUMN id SET DEFAULT nextval('public.localities_id_seq'::regclass);
 <   ALTER TABLE public.localities ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    297    296    297            �           2604    45162    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    279    278            �           2604    45163    organigramas id    DEFAULT     r   ALTER TABLE ONLY public.organigramas ALTER COLUMN id SET DEFAULT nextval('public.organigramas_id_seq'::regclass);
 >   ALTER TABLE public.organigramas ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    283    282            �           2604    45621    patrimonies id    DEFAULT     p   ALTER TABLE ONLY public.patrimonies ALTER COLUMN id SET DEFAULT nextval('public.patrimonies_id_seq'::regclass);
 =   ALTER TABLE public.patrimonies ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    299    298    299            �           2604    45164    permissions id    DEFAULT     p   ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);
 =   ALTER TABLE public.permissions ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    286    285            �           2604    45165    risks id    DEFAULT     d   ALTER TABLE ONLY public.risks ALTER COLUMN id SET DEFAULT nextval('public.risks_id_seq'::regclass);
 7   ALTER TABLE public.risks ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    288    287            �           2604    45166    roles id    DEFAULT     d   ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);
 7   ALTER TABLE public.roles ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    291    290            �           2604    45167    servicios id    DEFAULT     l   ALTER TABLE ONLY public.servicios ALTER COLUMN id SET DEFAULT nextval('public.servicios_id_seq'::regclass);
 ;   ALTER TABLE public.servicios ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    293    292            �           2604    45168    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    295    294            �          0    44838    formulario_clasificadores 
   TABLE DATA           |   COPY estadistica.formulario_clasificadores (id, clasificador, clasificador_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    206   HF      �          0    44843 #   formulario_formulario_has_variables 
   TABLE DATA           �   COPY estadistica.formulario_formulario_has_variables (id, formulario_id, variable_id, selected, selected_variable_id, value, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    208   eF      �          0    44849    formulario_formularios 
   TABLE DATA           �   COPY estadistica.formulario_formularios (id, formulario, status, dependencia_emisor_id, dependencia_receptor_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    210   �F      �          0    44854    formulario_items 
   TABLE DATA           r   COPY estadistica.formulario_items (id, item, questions, variable_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    212   �F      �          0    44862    formulario_variables 
   TABLE DATA           {   COPY estadistica.formulario_variables (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    214   �F      �          0    44869    foda_analisis 
   TABLE DATA           �   COPY planificacion.foda_analisis (id, user_id, perfil_id, aspecto_id, tipo, ocurrencia, impacto, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    216   �F      �          0    44875    foda_categorias_has_perfil 
   TABLE DATA           k   COPY planificacion.foda_categorias_has_perfil (perfil_id, category_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    218   �_      �          0    44878    foda_cruce_ambientes 
   TABLE DATA           w   COPY planificacion.foda_cruce_ambientes (id, user_id, perfil_id, estrategia, tipo, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    219   �c      �          0    44884 !   foda_cruce_ambientes_has_amenazas 
   TABLE DATA           X   COPY planificacion.foda_cruce_ambientes_has_amenazas (cruce_id, amenaza_id) FROM stdin;
    planificacion          postgres    false    220   dg      �          0    44887 $   foda_cruce_ambientes_has_debilidades 
   TABLE DATA           ]   COPY planificacion.foda_cruce_ambientes_has_debilidades (cruce_id, debilidad_id) FROM stdin;
    planificacion          postgres    false    221   �g      �          0    44890 #   foda_cruce_ambientes_has_fortalezas 
   TABLE DATA           \   COPY planificacion.foda_cruce_ambientes_has_fortalezas (cruce_id, fortaleza_id) FROM stdin;
    planificacion          postgres    false    222   �g      �          0    44893 &   foda_cruce_ambientes_has_oportunidades 
   TABLE DATA           a   COPY planificacion.foda_cruce_ambientes_has_oportunidades (cruce_id, oportunidad_id) FROM stdin;
    planificacion          postgres    false    223   Ah      �          0    44898    foda_groups_has_perfiles 
   TABLE DATA           j   COPY planificacion.foda_groups_has_perfiles (id, perfil_id, group_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    225   �h      �          0    44903    foda_models 
   TABLE DATA           �   COPY planificacion.foda_models (id, type, name, owner, environment, description, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    227   �h      �          0    44913    foda_perfiles 
   TABLE DATA           �   COPY planificacion.foda_perfiles (id, name, context, type, model_id, group_id, dependency_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    229   2�      �          0    44919    pei_profiles 
   TABLE DATA           <  COPY planificacion.pei_profiles (id, type, level, mision, vision, "values", period, numerator, operator, denominator, goal, progress, group_id, _lft, _rgt, deleted_at, created_at, updated_at, dependency_id, name, action, indicator, baseline, target, parent_id, year_start, year_end, user_id, order_item) FROM stdin;
    planificacion          postgres    false    230   ��      �          0    44927    pei_profiles_has_dependencies 
   TABLE DATA           y   COPY planificacion.pei_profiles_has_dependencies (id, pei_profile_id, dependency_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    231   �      �          0    44932    pei_profiles_has_strategies 
   TABLE DATA           q   COPY planificacion.pei_profiles_has_strategies (id, profile_id, strategy_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    233   �      �          0    44937    peis_profiles_has_analysts 
   TABLE DATA           s   COPY planificacion.peis_profiles_has_analysts (id, pei_profile_id, analyst_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    235   v      �          0    44942    peis_profiles_has_responsibles 
   TABLE DATA           w   COPY planificacion.peis_profiles_has_responsibles (id, profile_id, responsible_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    237         �          0    44947    tasks 
   TABLE DATA           ]   COPY planificacion.tasks (id, group_id, details, status, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    239   �      �          0    44951    tasks_has_analysts 
   TABLE DATA           d   COPY planificacion.tasks_has_analysts (id, task_id, analyst_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    240   :                0    45665    tasks_has_type_tasks 
   TABLE DATA           ~   COPY planificacion.tasks_has_type_tasks (id, task_id, typetaskable_id, typetaskable_type, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    302   �      �          0    44964 	   typetasks 
   TABLE DATA           p   COPY planificacion.typetasks (id, name, typetaskable_id, typetaskable_type, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    243   Q#      �          0    44969    e_p_c_equipamientos 
   TABLE DATA           ]   COPY proyecto.e_p_c_equipamientos (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    245   *      �          0    44974    e_p_c_equipamientos_servicios 
   TABLE DATA           a   COPY proyecto.e_p_c_equipamientos_servicios (servicio_id, equipamiento_id, cantidad) FROM stdin;
    proyecto          postgres    false    247   *      �          0    44977    e_p_c_especialidads 
   TABLE DATA           e   COPY proyecto.e_p_c_especialidads (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    248   ;*      �          0    44985    e_p_c_estandars 
   TABLE DATA           [   COPY proyecto.e_p_c_estandars (id, item, type, detail, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    250   X*      �          0    44993    e_p_c_estandars_servicios 
   TABLE DATA           Y   COPY proyecto.e_p_c_estandars_servicios (estandar_id, servicio_id, cantidad) FROM stdin;
    proyecto          postgres    false    252   u*      �          0    44996    e_p_c_horarios 
   TABLE DATA           b   COPY proyecto.e_p_c_horarios (id, item, start_time, end_time, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    253   �*      �          0    45004    e_p_c_infraestructura_servicio 
   TABLE DATA           e   COPY proyecto.e_p_c_infraestructura_servicio (servicio_id, infraestructura_id, cantidad) FROM stdin;
    proyecto          postgres    false    255   �*      �          0    45007    e_p_c_infraestructuras 
   TABLE DATA           m   COPY proyecto.e_p_c_infraestructuras (id, item, type, measurement, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    256   �*      �          0    45012    e_p_c_medicamento_insumos 
   TABLE DATA           c   COPY proyecto.e_p_c_medicamento_insumos (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    258   �*      �          0    45017    e_p_c_otro_servicios 
   TABLE DATA           ^   COPY proyecto.e_p_c_otro_servicios (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    260   +      �          0    45022    e_p_c_prestaciones 
   TABLE DATA           d   COPY proyecto.e_p_c_prestaciones (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    262   #+      �          0    45030    e_p_c_servicios 
   TABLE DATA           `   COPY proyecto.e_p_c_servicios (id, item, type, description, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    264   @+      �          0    45038    e_p_c_talento_humanos 
   TABLE DATA           f   COPY proyecto.e_p_c_talento_humanos (id, item, hours, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    266   ]+      �          0    45046    e_p_c_tthhs_servicios 
   TABLE DATA           Q   COPY proyecto.e_p_c_tthhs_servicios (servicio_id, tthh_id, cantidad) FROM stdin;
    proyecto          postgres    false    268   z+      �          0    45049    e_p_c_turnos 
   TABLE DATA           J   COPY proyecto.e_p_c_turnos (id, item, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    269   �+      �          0    45054    otroservicio_has_servicios 
   TABLE DATA           _   COPY proyecto.otroservicio_has_servicios (servicio_id, otro_servicio_id, cantidad) FROM stdin;
    proyecto          postgres    false    271   �+                0    45629 
   activities 
   TABLE DATA           @   COPY public.activities (id, created_at, updated_at) FROM stdin;
    public          postgres    false    301   �+      �          0    45057 
   categories 
   TABLE DATA           F   COPY public.categories (id, name, created_at, updated_at) FROM stdin;
    public          postgres    false    272   �+      �          0    45062    groups 
   TABLE DATA           Y   COPY public.groups (id, name, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
    public          postgres    false    274   ,      �          0    45067    groups_has_members 
   TABLE DATA           [   COPY public.groups_has_members (id, group_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    275   ]0                0    45607 
   localities 
   TABLE DATA           y   COPY public.localities (id, cod_dpto, desc_dpto, cod_dist, desc_dist, area, cod_barrio_loc, desc_barrio_loc) FROM stdin;
    public          postgres    false    297   �4      �          0    45074 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public          postgres    false    278   W�                 0    45079    model_has_permissions 
   TABLE DATA           T   COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
    public          postgres    false    280   �                0    45082    model_has_roles 
   TABLE DATA           H   COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
    public          postgres    false    281   �                0    45085    organigramas 
   TABLE DATA           �   COPY public.organigramas (id, dependency, _lft, _rgt, parent_id, manager, phone, email, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    282   v�                0    45095    password_resets 
   TABLE DATA           C   COPY public.password_resets (email, token, created_at) FROM stdin;
    public          postgres    false    284   f�                0    45618    patrimonies 
   TABLE DATA           �  COPY public.patrimonies (id, type, quantity_account_current, detail_location, estate_quantity, department, city, locality, latitude, longitude, location_address, infrastructure_type, description, registry_number, cadastral_current_account, estate_status, committed_investment, transfer, balance_for_transfer, tenant, rent_amount, rent_amount_period, contract_resolution, contract_number, current_period_start, current_period_end, status_documentation, land_area_mt2, land_area_hectares, land_sub_area, built_area_m2, built_value_gs, property_value_gs, total_value_gs, possession_rent_without_title, main_photo_file, main_photo_file_path, evidence_file, evidence_file_path, created_at, updated_at) FROM stdin;
    public          postgres    false    299   ��                0    45098    permissions 
   TABLE DATA           S   COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    285   ��                0    45103    risks 
   TABLE DATA           I   COPY public.risks (id, name, detail, created_at, updated_at) FROM stdin;
    public          postgres    false    287    �      	          0    45108    role_has_permissions 
   TABLE DATA           F   COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
    public          postgres    false    289   �      
          0    45111    roles 
   TABLE DATA           M   COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    290   E�                0    45116 	   servicios 
   TABLE DATA           k   COPY public.servicios (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    292   ��                0    45123    users 
   TABLE DATA              COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, group_id) FROM stdin;
    public          postgres    false    294   ح      F           0    0     formulario_clasificadores_id_seq    SEQUENCE SET     T   SELECT pg_catalog.setval('estadistica.formulario_clasificadores_id_seq', 1, false);
          estadistica          postgres    false    207            G           0    0 *   formulario_formulario_has_variables_id_seq    SEQUENCE SET     ^   SELECT pg_catalog.setval('estadistica.formulario_formulario_has_variables_id_seq', 1, false);
          estadistica          postgres    false    209            H           0    0    formulario_formularios_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('estadistica.formulario_formularios_id_seq', 1, false);
          estadistica          postgres    false    211            I           0    0    formulario_items_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('estadistica.formulario_items_id_seq', 1, false);
          estadistica          postgres    false    213            J           0    0    formulario_variables_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('estadistica.formulario_variables_id_seq', 1, false);
          estadistica          postgres    false    215            K           0    0    foda_analisis_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('planificacion.foda_analisis_id_seq', 2352, true);
          planificacion          postgres    false    217            L           0    0    foda_cruce_ambientes_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('planificacion.foda_cruce_ambientes_id_seq', 25, true);
          planificacion          postgres    false    224            M           0    0    foda_groups_has_perfiles_id_seq    SEQUENCE SET     U   SELECT pg_catalog.setval('planificacion.foda_groups_has_perfiles_id_seq', 1, false);
          planificacion          postgres    false    226            N           0    0    foda_models_id_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('planificacion.foda_models_id_seq', 216, true);
          planificacion          postgres    false    228            O           0    0 $   pei_profiles_has_dependencies_id_seq    SEQUENCE SET     Z   SELECT pg_catalog.setval('planificacion.pei_profiles_has_dependencies_id_seq', 1, false);
          planificacion          postgres    false    232            P           0    0 "   pei_profiles_has_strategies_id_seq    SEQUENCE SET     X   SELECT pg_catalog.setval('planificacion.pei_profiles_has_strategies_id_seq', 10, true);
          planificacion          postgres    false    234            Q           0    0 !   peis_profiles_has_analysts_id_seq    SEQUENCE SET     W   SELECT pg_catalog.setval('planificacion.peis_profiles_has_analysts_id_seq', 32, true);
          planificacion          postgres    false    236            R           0    0 %   peis_profiles_has_responsibles_id_seq    SEQUENCE SET     \   SELECT pg_catalog.setval('planificacion.peis_profiles_has_responsibles_id_seq', 115, true);
          planificacion          postgres    false    238            S           0    0    tasks_has_analysts_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('planificacion.tasks_has_analysts_id_seq', 47, true);
          planificacion          postgres    false    241            T           0    0    tasks_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('planificacion.tasks_id_seq', 43, true);
          planificacion          postgres    false    242            U           0    0    typetasks_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('planificacion.typetasks_id_seq', 50, true);
          planificacion          postgres    false    244            V           0    0    e_p_c_equipamientos_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('proyecto.e_p_c_equipamientos_id_seq', 1, false);
          proyecto          postgres    false    246            W           0    0    e_p_c_especialidads_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('proyecto.e_p_c_especialidads_id_seq', 1, false);
          proyecto          postgres    false    249            X           0    0    e_p_c_estandars_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('proyecto.e_p_c_estandars_id_seq', 1, false);
          proyecto          postgres    false    251            Y           0    0    e_p_c_horarios_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('proyecto.e_p_c_horarios_id_seq', 1, false);
          proyecto          postgres    false    254            Z           0    0    e_p_c_infraestructuras_id_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('proyecto.e_p_c_infraestructuras_id_seq', 1, false);
          proyecto          postgres    false    257            [           0    0     e_p_c_medicamento_insumos_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('proyecto.e_p_c_medicamento_insumos_id_seq', 1, false);
          proyecto          postgres    false    259            \           0    0    e_p_c_otro_servicios_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('proyecto.e_p_c_otro_servicios_id_seq', 1, false);
          proyecto          postgres    false    261            ]           0    0    e_p_c_prestaciones_id_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('proyecto.e_p_c_prestaciones_id_seq', 1, false);
          proyecto          postgres    false    263            ^           0    0    e_p_c_servicios_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('proyecto.e_p_c_servicios_id_seq', 1, false);
          proyecto          postgres    false    265            _           0    0    e_p_c_talento_humanos_id_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('proyecto.e_p_c_talento_humanos_id_seq', 1, false);
          proyecto          postgres    false    267            `           0    0    e_p_c_turnos_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('proyecto.e_p_c_turnos_id_seq', 1, false);
          proyecto          postgres    false    270            a           0    0    activities_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.activities_id_seq', 1, false);
          public          postgres    false    300            b           0    0    categories_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.categories_id_seq', 1, false);
          public          postgres    false    273            c           0    0    groups_has_members_id_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('public.groups_has_members_id_seq', 256, true);
          public          postgres    false    276            d           0    0    groups_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.groups_id_seq', 39, true);
          public          postgres    false    277            e           0    0    localities_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.localities_id_seq', 1, false);
          public          postgres    false    296            f           0    0    migrations_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.migrations_id_seq', 52, true);
          public          postgres    false    279            g           0    0    organigramas_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.organigramas_id_seq', 15, true);
          public          postgres    false    283            h           0    0    patrimonies_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.patrimonies_id_seq', 1, false);
          public          postgres    false    298            i           0    0    permissions_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.permissions_id_seq', 4, true);
          public          postgres    false    286            j           0    0    risks_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.risks_id_seq', 1, false);
          public          postgres    false    288            k           0    0    roles_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.roles_id_seq', 3, true);
          public          postgres    false    291            l           0    0    servicios_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.servicios_id_seq', 1, false);
          public          postgres    false    293            m           0    0    users_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.users_id_seq', 227, true);
          public          postgres    false    295            �           2606    45173 8   formulario_clasificadores formulario_clasificadores_pkey 
   CONSTRAINT     {   ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT formulario_clasificadores_pkey PRIMARY KEY (id);
 g   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT formulario_clasificadores_pkey;
       estadistica            postgres    false    206            �           2606    45175 L   formulario_formulario_has_variables formulario_formulario_has_variables_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT formulario_formulario_has_variables_pkey PRIMARY KEY (id);
 {   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT formulario_formulario_has_variables_pkey;
       estadistica            postgres    false    208            �           2606    45177 2   formulario_formularios formulario_formularios_pkey 
   CONSTRAINT     u   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT formulario_formularios_pkey PRIMARY KEY (id);
 a   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT formulario_formularios_pkey;
       estadistica            postgres    false    210            �           2606    45179 &   formulario_items formulario_items_pkey 
   CONSTRAINT     i   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT formulario_items_pkey PRIMARY KEY (id);
 U   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT formulario_items_pkey;
       estadistica            postgres    false    212            �           2606    45181 .   formulario_variables formulario_variables_pkey 
   CONSTRAINT     q   ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT formulario_variables_pkey PRIMARY KEY (id);
 ]   ALTER TABLE ONLY estadistica.formulario_variables DROP CONSTRAINT formulario_variables_pkey;
       estadistica            postgres    false    214            �           2606    45183     foda_analisis foda_analisis_pkey 
   CONSTRAINT     e   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT foda_analisis_pkey PRIMARY KEY (id);
 Q   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT foda_analisis_pkey;
       planificacion            postgres    false    216            �           2606    45185 .   foda_cruce_ambientes foda_cruce_ambientes_pkey 
   CONSTRAINT     s   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT foda_cruce_ambientes_pkey PRIMARY KEY (id);
 _   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT foda_cruce_ambientes_pkey;
       planificacion            postgres    false    219            �           2606    45187 6   foda_groups_has_perfiles foda_groups_has_perfiles_pkey 
   CONSTRAINT     {   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT foda_groups_has_perfiles_pkey PRIMARY KEY (id);
 g   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT foda_groups_has_perfiles_pkey;
       planificacion            postgres    false    225            �           2606    45189    foda_models foda_models_pkey 
   CONSTRAINT     a   ALTER TABLE ONLY planificacion.foda_models
    ADD CONSTRAINT foda_models_pkey PRIMARY KEY (id);
 M   ALTER TABLE ONLY planificacion.foda_models DROP CONSTRAINT foda_models_pkey;
       planificacion            postgres    false    227            �           2606    45191     foda_perfiles foda_perfiles_pkey 
   CONSTRAINT     e   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT foda_perfiles_pkey PRIMARY KEY (id);
 Q   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT foda_perfiles_pkey;
       planificacion            postgres    false    229            �           2606    45193 @   pei_profiles_has_dependencies pei_profiles_has_dependencies_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT pei_profiles_has_dependencies_pkey PRIMARY KEY (id);
 q   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT pei_profiles_has_dependencies_pkey;
       planificacion            postgres    false    231            �           2606    45195 <   pei_profiles_has_strategies pei_profiles_has_strategies_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT pei_profiles_has_strategies_pkey PRIMARY KEY (id);
 m   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT pei_profiles_has_strategies_pkey;
       planificacion            postgres    false    233            �           2606    45197    pei_profiles pei_profiles_pkey 
   CONSTRAINT     c   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT pei_profiles_pkey PRIMARY KEY (id);
 O   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT pei_profiles_pkey;
       planificacion            postgres    false    230            �           2606    45199 :   peis_profiles_has_analysts peis_profiles_has_analysts_pkey 
   CONSTRAINT        ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT peis_profiles_has_analysts_pkey PRIMARY KEY (id);
 k   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT peis_profiles_has_analysts_pkey;
       planificacion            postgres    false    235            �           2606    45201 B   peis_profiles_has_responsibles peis_profiles_has_responsibles_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT peis_profiles_has_responsibles_pkey PRIMARY KEY (id);
 s   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT peis_profiles_has_responsibles_pkey;
       planificacion            postgres    false    237            �           2606    45203 *   tasks_has_analysts tasks_has_analysts_pkey 
   CONSTRAINT     o   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT tasks_has_analysts_pkey PRIMARY KEY (id);
 [   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT tasks_has_analysts_pkey;
       planificacion            postgres    false    240            �           2606    45207    tasks tasks_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT tasks_pkey PRIMARY KEY (id);
 A   ALTER TABLE ONLY planificacion.tasks DROP CONSTRAINT tasks_pkey;
       planificacion            postgres    false    239            �           2606    45209    typetasks typetasks_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY planificacion.typetasks
    ADD CONSTRAINT typetasks_pkey PRIMARY KEY (id);
 I   ALTER TABLE ONLY planificacion.typetasks DROP CONSTRAINT typetasks_pkey;
       planificacion            postgres    false    243            �           2606    45211 ,   e_p_c_equipamientos e_p_c_equipamientos_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY proyecto.e_p_c_equipamientos
    ADD CONSTRAINT e_p_c_equipamientos_pkey PRIMARY KEY (id);
 X   ALTER TABLE ONLY proyecto.e_p_c_equipamientos DROP CONSTRAINT e_p_c_equipamientos_pkey;
       proyecto            postgres    false    245            �           2606    45213 ,   e_p_c_especialidads e_p_c_especialidads_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY proyecto.e_p_c_especialidads
    ADD CONSTRAINT e_p_c_especialidads_pkey PRIMARY KEY (id);
 X   ALTER TABLE ONLY proyecto.e_p_c_especialidads DROP CONSTRAINT e_p_c_especialidads_pkey;
       proyecto            postgres    false    248            �           2606    45215 $   e_p_c_estandars e_p_c_estandars_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY proyecto.e_p_c_estandars
    ADD CONSTRAINT e_p_c_estandars_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY proyecto.e_p_c_estandars DROP CONSTRAINT e_p_c_estandars_pkey;
       proyecto            postgres    false    250            �           2606    45217 "   e_p_c_horarios e_p_c_horarios_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY proyecto.e_p_c_horarios
    ADD CONSTRAINT e_p_c_horarios_pkey PRIMARY KEY (id);
 N   ALTER TABLE ONLY proyecto.e_p_c_horarios DROP CONSTRAINT e_p_c_horarios_pkey;
       proyecto            postgres    false    253            �           2606    45219 2   e_p_c_infraestructuras e_p_c_infraestructuras_pkey 
   CONSTRAINT     r   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras
    ADD CONSTRAINT e_p_c_infraestructuras_pkey PRIMARY KEY (id);
 ^   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras DROP CONSTRAINT e_p_c_infraestructuras_pkey;
       proyecto            postgres    false    256            �           2606    45221 8   e_p_c_medicamento_insumos e_p_c_medicamento_insumos_pkey 
   CONSTRAINT     x   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos
    ADD CONSTRAINT e_p_c_medicamento_insumos_pkey PRIMARY KEY (id);
 d   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos DROP CONSTRAINT e_p_c_medicamento_insumos_pkey;
       proyecto            postgres    false    258            �           2606    45223 .   e_p_c_otro_servicios e_p_c_otro_servicios_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios
    ADD CONSTRAINT e_p_c_otro_servicios_pkey PRIMARY KEY (id);
 Z   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios DROP CONSTRAINT e_p_c_otro_servicios_pkey;
       proyecto            postgres    false    260            �           2606    45225 *   e_p_c_prestaciones e_p_c_prestaciones_pkey 
   CONSTRAINT     j   ALTER TABLE ONLY proyecto.e_p_c_prestaciones
    ADD CONSTRAINT e_p_c_prestaciones_pkey PRIMARY KEY (id);
 V   ALTER TABLE ONLY proyecto.e_p_c_prestaciones DROP CONSTRAINT e_p_c_prestaciones_pkey;
       proyecto            postgres    false    262            �           2606    45227 $   e_p_c_servicios e_p_c_servicios_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY proyecto.e_p_c_servicios
    ADD CONSTRAINT e_p_c_servicios_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY proyecto.e_p_c_servicios DROP CONSTRAINT e_p_c_servicios_pkey;
       proyecto            postgres    false    264            �           2606    45229 0   e_p_c_talento_humanos e_p_c_talento_humanos_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos
    ADD CONSTRAINT e_p_c_talento_humanos_pkey PRIMARY KEY (id);
 \   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos DROP CONSTRAINT e_p_c_talento_humanos_pkey;
       proyecto            postgres    false    266            �           2606    45231    e_p_c_turnos e_p_c_turnos_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY proyecto.e_p_c_turnos
    ADD CONSTRAINT e_p_c_turnos_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY proyecto.e_p_c_turnos DROP CONSTRAINT e_p_c_turnos_pkey;
       proyecto            postgres    false    269            �           2606    45634    activities activities_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.activities
    ADD CONSTRAINT activities_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.activities DROP CONSTRAINT activities_pkey;
       public            postgres    false    301            �           2606    45233    categories categories_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.categories DROP CONSTRAINT categories_pkey;
       public            postgres    false    272            �           2606    45235 *   groups_has_members groups_has_members_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_pkey PRIMARY KEY (id);
 T   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_pkey;
       public            postgres    false    275            �           2606    45237    groups groups_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.groups DROP CONSTRAINT groups_pkey;
       public            postgres    false    274            �           2606    45615    localities localities_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.localities
    ADD CONSTRAINT localities_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.localities DROP CONSTRAINT localities_pkey;
       public            postgres    false    297            �           2606    45239    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public            postgres    false    278            �           2606    45241 0   model_has_permissions model_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);
 Z   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_pkey;
       public            postgres    false    280    280    280            �           2606    45243 $   model_has_roles model_has_roles_pkey 
   CONSTRAINT     }   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);
 N   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_pkey;
       public            postgres    false    281    281    281            �           2606    45245 &   organigramas organigramas_email_unique 
   CONSTRAINT     b   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_email_unique UNIQUE (email);
 P   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_email_unique;
       public            postgres    false    282            �           2606    45247    organigramas organigramas_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_pkey;
       public            postgres    false    282            �           2606    45626    patrimonies patrimonies_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.patrimonies
    ADD CONSTRAINT patrimonies_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.patrimonies DROP CONSTRAINT patrimonies_pkey;
       public            postgres    false    299            �           2606    45249    permissions permissions_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_pkey;
       public            postgres    false    285            �           2606    45251    risks risks_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.risks
    ADD CONSTRAINT risks_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.risks DROP CONSTRAINT risks_pkey;
       public            postgres    false    287            �           2606    45253 .   role_has_permissions role_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);
 X   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_pkey;
       public            postgres    false    289    289            �           2606    45255    roles roles_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
       public            postgres    false    290            �           2606    45257    servicios servicios_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.servicios DROP CONSTRAINT servicios_pkey;
       public            postgres    false    292            �           2606    45259    users users_email_unique 
   CONSTRAINT     T   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
       public            postgres    false    294            �           2606    45261    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    294            �           1259    45262 :   estadistica_formulario_variables__lft__rgt_parent_id_index    INDEX     �   CREATE INDEX estadistica_formulario_variables__lft__rgt_parent_id_index ON estadistica.formulario_variables USING btree (_lft, _rgt, parent_id);
 S   DROP INDEX estadistica.estadistica_formulario_variables__lft__rgt_parent_id_index;
       estadistica            postgres    false    214    214    214            �           1259    45263 3   planificacion_foda_models__lft__rgt_parent_id_index    INDEX     �   CREATE INDEX planificacion_foda_models__lft__rgt_parent_id_index ON planificacion.foda_models USING btree (_lft, _rgt, parent_id);
 N   DROP INDEX planificacion.planificacion_foda_models__lft__rgt_parent_id_index;
       planificacion            postgres    false    227    227    227            �           1259    45264     groups__lft__rgt_parent_id_index    INDEX     d   CREATE INDEX groups__lft__rgt_parent_id_index ON public.groups USING btree (_lft, _rgt, parent_id);
 4   DROP INDEX public.groups__lft__rgt_parent_id_index;
       public            postgres    false    274    274    274            �           1259    45265 /   model_has_permissions_model_id_model_type_index    INDEX     �   CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);
 C   DROP INDEX public.model_has_permissions_model_id_model_type_index;
       public            postgres    false    280    280            �           1259    45266 )   model_has_roles_model_id_model_type_index    INDEX     u   CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);
 =   DROP INDEX public.model_has_roles_model_id_model_type_index;
       public            postgres    false    281    281            �           1259    45267 &   organigramas__lft__rgt_parent_id_index    INDEX     p   CREATE INDEX organigramas__lft__rgt_parent_id_index ON public.organigramas USING btree (_lft, _rgt, parent_id);
 :   DROP INDEX public.organigramas__lft__rgt_parent_id_index;
       public            postgres    false    282    282    282            �           1259    45268    password_resets_email_index    INDEX     X   CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);
 /   DROP INDEX public.password_resets_email_index;
       public            postgres    false    284            �           1259    45269 #   servicios__lft__rgt_parent_id_index    INDEX     j   CREATE INDEX servicios__lft__rgt_parent_id_index ON public.servicios USING btree (_lft, _rgt, parent_id);
 7   DROP INDEX public.servicios__lft__rgt_parent_id_index;
       public            postgres    false    292    292    292            �           2606    45270 W   formulario_clasificadores estadistica_formulario_clasificadores_clasificador_id_foreign    FK CONSTRAINT        ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign FOREIGN KEY (clasificador_id) REFERENCES estadistica.formulario_clasificadores(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign;
       estadistica          postgres    false    206    206    3220            �           2606    45275 O   formulario_clasificadores estadistica_formulario_clasificadores_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 ~   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign;
       estadistica          postgres    false    294    3314    206            �           2606    45280 c   formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_formulario_id_f    FK CONSTRAINT       ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f FOREIGN KEY (formulario_id) REFERENCES estadistica.formulario_formularios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f;
       estadistica          postgres    false    210    3224    208            �           2606    45285 c   formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_variable_id_for    FK CONSTRAINT       ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for;
       estadistica          postgres    false    3229    214    208            �           2606    45290 V   formulario_formularios estadistica_formulario_formularios_dependencia_emisor_id_foreig    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig FOREIGN KEY (dependencia_emisor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig;
       estadistica          postgres    false    3298    282    210            �           2606    45295 V   formulario_formularios estadistica_formulario_formularios_dependencia_receptor_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore FOREIGN KEY (dependencia_receptor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore;
       estadistica          postgres    false    3298    210    282            �           2606    45300 I   formulario_formularios estadistica_formulario_formularios_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_user_id_foreign;
       estadistica          postgres    false    3314    294    210                        2606    45305 =   formulario_items estadistica_formulario_items_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 l   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT estadistica_formulario_items_user_id_foreign;
       estadistica          postgres    false    3314    212    294                       2606    45310 A   formulario_items estadistica_formulario_items_variable_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT estadistica_formulario_items_variable_id_foreign;
       estadistica          postgres    false    214    212    3229                       2606    45315 E   formulario_variables estadistica_formulario_variables_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT estadistica_formulario_variables_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY estadistica.formulario_variables DROP CONSTRAINT estadistica_formulario_variables_user_id_foreign;
       estadistica          postgres    false    214    3314    294                       2606    45320    pei_profiles fk_dependency    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT fk_dependency FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 K   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT fk_dependency;
       planificacion          postgres    false    3298    230    282                       2606    45325    pei_profiles fk_user_id    FK CONSTRAINT     }   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES public.users(id);
 H   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT fk_user_id;
       planificacion          postgres    false    294    3314    230                       2606    45330 <   foda_analisis planificacion_foda_analisis_aspecto_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign FOREIGN KEY (aspecto_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 m   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign;
       planificacion          postgres    false    216    227    3237                       2606    45335 ;   foda_analisis planificacion_foda_analisis_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 l   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_perfil_id_foreign;
       planificacion          postgres    false    229    3240    216                       2606    45340 9   foda_analisis planificacion_foda_analisis_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 j   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_user_id_foreign;
       planificacion          postgres    false    216    3314    294                       2606    45345 W   foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_category_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign FOREIGN KEY (category_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil DROP CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign;
       planificacion          postgres    false    3237    218    227                       2606    45350 U   foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil DROP CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign;
       planificacion          postgres    false    229    218    3240            
           2606    45355 a   foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore FOREIGN KEY (amenaza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore;
       planificacion          postgres    false    3237    227    220                       2606    45360 a   foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig;
       planificacion          postgres    false    3233    220    219                       2606    45365 d   foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for;
       planificacion          postgres    false    3233    221    219                       2606    45370 d   foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id    FK CONSTRAINT        ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id FOREIGN KEY (debilidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id;
       planificacion          postgres    false    3237    221    227                       2606    45375 c   foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore;
       planificacion          postgres    false    219    222    3233                       2606    45380 c   foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_ FOREIGN KEY (fortaleza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_;
       planificacion          postgres    false    222    227    3237                       2606    45385 f   foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f;
       planificacion          postgres    false    223    219    3233                       2606    45390 f   foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_oportunida    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida FOREIGN KEY (oportunidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida;
       planificacion          postgres    false    227    3237    223                       2606    45395 I   foda_cruce_ambientes planificacion_foda_cruce_ambientes_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 z   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign;
       planificacion          postgres    false    229    219    3240            	           2606    45400 G   foda_cruce_ambientes planificacion_foda_cruce_ambientes_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign;
       planificacion          postgres    false    3314    294    219                       2606    45405 P   foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign;
       planificacion          postgres    false    3283    274    225                       2606    45410 Q   foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign;
       planificacion          postgres    false    229    225    3240                       2606    45415 ?   foda_perfiles planificacion_foda_perfiles_dependency_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign;
       planificacion          postgres    false    3298    229    282                       2606    45420 :   foda_perfiles planificacion_foda_perfiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 k   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_group_id_foreign;
       planificacion          postgres    false    274    229    3283                       2606    45425 :   foda_perfiles planificacion_foda_perfiles_model_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_model_id_foreign FOREIGN KEY (model_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 k   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_model_id_foreign;
       planificacion          postgres    false    227    3237    229                       2606    45430 8   pei_profiles planificacion_pei_profiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 i   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT planificacion_pei_profiles_group_id_foreign;
       planificacion          postgres    false    274    230    3283                       2606    45435 ]   pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_dependency_id_forei    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei;
       planificacion          postgres    false    231    3298    282                       2606    45440 ]   pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_pei_profile_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore;
       planificacion          postgres    false    3242    230    231                       2606    45445 X   pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign;
       planificacion          postgres    false    3242    230    233                       2606    45450 Y   pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_strategy_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign FOREIGN KEY (strategy_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign;
       planificacion          postgres    false    219    233    3233                       2606    45455 V   peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_analyst_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign;
       planificacion          postgres    false    3314    294    235                       2606    45460 Z   peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_pei_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign;
       planificacion          postgres    false    230    235    3242                        2606    45465 ^   peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign;
       planificacion          postgres    false    237    3242    230            !           2606    45470 ^   peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_responsible_id_for    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for FOREIGN KEY (responsible_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for;
       planificacion          postgres    false    282    237    3298            "           2606    45475 *   tasks planificacion_tasks_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT planificacion_tasks_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 [   ALTER TABLE ONLY planificacion.tasks DROP CONSTRAINT planificacion_tasks_group_id_foreign;
       planificacion          postgres    false    274    239    3283            #           2606    45480 F   tasks_has_analysts planificacion_tasks_has_analysts_analyst_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 w   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign;
       planificacion          postgres    false    240    3314    294            $           2606    45485 C   tasks_has_analysts planificacion_tasks_has_analysts_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign;
       planificacion          postgres    false    240    239    3252            %           2606    45500 \   e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign FOREIGN KEY (equipamiento_id) REFERENCES proyecto.e_p_c_equipamientos(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios DROP CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign;
       proyecto          postgres    false    3258    247    245            &           2606    45505 X   e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios DROP CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign;
       proyecto          postgres    false    3274    264    247            '           2606    45510 P   e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_estandar_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign FOREIGN KEY (estandar_id) REFERENCES proyecto.e_p_c_estandars(id) ON UPDATE CASCADE ON DELETE CASCADE;
 |   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios DROP CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign;
       proyecto          postgres    false    252    250    3262            (           2606    45515 P   e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 |   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios DROP CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign;
       proyecto          postgres    false    252    264    3274            )           2606    45520 ^   e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore    FK CONSTRAINT       ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore FOREIGN KEY (infraestructura_id) REFERENCES proyecto.e_p_c_infraestructuras(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio DROP CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore;
       proyecto          postgres    false    255    256    3266            *           2606    45525 Z   e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio DROP CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign;
       proyecto          postgres    false    3274    255    264            +           2606    45530 H   e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios DROP CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign;
       proyecto          postgres    false    264    3274    268            ,           2606    45535 D   e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_tthh_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign FOREIGN KEY (tthh_id) REFERENCES proyecto.e_p_c_talento_humanos(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios DROP CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign;
       proyecto          postgres    false    266    3276    268            -           2606    45540 W   otroservicio_has_servicios proyecto_otroservicio_has_servicios_otro_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign FOREIGN KEY (otro_servicio_id) REFERENCES proyecto.e_p_c_otro_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios DROP CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign;
       proyecto          postgres    false    271    260    3270            .           2606    45545 R   otroservicio_has_servicios proyecto_otroservicio_has_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 ~   ALTER TABLE ONLY proyecto.otroservicio_has_servicios DROP CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign;
       proyecto          postgres    false    264    271    3274            7           2606    45597    users fk_group_id    FK CONSTRAINT     r   ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_group_id FOREIGN KEY (group_id) REFERENCES public.groups(id);
 ;   ALTER TABLE ONLY public.users DROP CONSTRAINT fk_group_id;
       public          postgres    false    274    3283    294            /           2606    45550 6   groups_has_members groups_has_members_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 `   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_group_id_foreign;
       public          postgres    false    274    275    3283            0           2606    45555 5   groups_has_members groups_has_members_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 _   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_user_id_foreign;
       public          postgres    false    3314    294    275            1           2606    45560 A   model_has_permissions model_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 k   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_permission_id_foreign;
       public          postgres    false    3301    285    280            2           2606    45565 /   model_has_roles model_has_roles_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 Y   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_role_id_foreign;
       public          postgres    false    3307    290    281            3           2606    45570 )   organigramas organigramas_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_user_id_foreign;
       public          postgres    false    3314    282    294            4           2606    45575 ?   role_has_permissions role_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 i   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_permission_id_foreign;
       public          postgres    false    285    3301    289            5           2606    45580 9   role_has_permissions role_has_permissions_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 c   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_role_id_foreign;
       public          postgres    false    290    3307    289            6           2606    45585 #   servicios servicios_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 M   ALTER TABLE ONLY public.servicios DROP CONSTRAINT servicios_user_id_foreign;
       public          postgres    false    294    292    3314            �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x���ˎ,�q��]O�H��K�^�~ m��� ��0䍟�A��s:�L~�g����A����|���Q�\X��v�s!,���K������Q���׿�����^�ח�sV�OR_F�h���'��*�m�|���-~�'h���	�{|�BR��!(�����_&~������޿��-J������n���ַ[�8a
'S���o7�0\���@p�z@�KW �m{e��4n���\^Qgg��E��Jid�V�mh}�i�����@rj��<�h�G@V�I}�{I��Y\6���^�Ө���AH����IxA~ض�ݬ�Hf�r$;��0R���R��M�#�y�)p$�z=����X��W���vSE����!9�es�!?���>?�iy��\;�z���I��ɕ-�B�'�Ojy�H���9�O���x���9G��v��9�M��9�!��G���ܟ��I�Np�y�n���1g��	?b�<���9�N�����1ǰ�1�F�����������}��m$o��Rr7;���8eD}$NFQD��QF
�G�
�����2ͳ���h���v����!��@���@!a�Lm(ݵ�I�Js��^4r���M�~
����1�K}�@�"R�>P�@������%����m�E��-��h� XĪ@���H�13į�����-4Q�]�[r_fEy����Viq�i�F��|�摬z�QvW~��%�h|�%O�u D�� �b B�M w7�G	�=���o7�;!P$����� �d�����^��)g(��dgҢ�[�����gQ�_�~�EJߝ���8Q��(�H��q�Ǜ���T���q��bN����N�(�ɧ���,ў�H����]�y]�[�9��s�b0'��@��lʏo�T)�al$~j�ȡ�\�[wQ&s��fN��U����͜(d�2���9Q�B��+
R��"��k ��"ѥ�'7"�h!�R��<%��E�l�r��D^d���<�>������+*txC{C�(5�B��e챢2����� �[����ag?:���biF���ɽ���'.���,Yi�����[��%�����UgZ�)P$)#E,�8T:FJi+"�W�V����gq�^����}�V?Z���n�]��7���يd}�,ꡃ =���|Ԣ���o#>eѽ�G)�*�N�|qw�)`$dj"��^��ȩ��[��K���^�w�m{Y峖�����m$����#�G�C�����>�'W<��"��<>)/��햧~����O��wx`���䂸�d(R��$پ�d1Ҹ�E*1��ϑF��kiD�ϜhD�16�m��M����'�������N�\�����y"Z�Z=�m����Fb ��mK0���V#�ɨ�djj@�4v�;H���JQ�8��.R=N+Q�BH((H>a%&<V%��AZ���/wU#��*H<�c�"��-
�^������	E(�j�Weʕ`Ng�"��H5����i�z���WS+�Ȕ�L��L���I�H\#1�g�G��ʵ�\�I�clwHr�d�4�Fi�>R��\�m$/_�0GğV��Om�b�ۤ�N!9��m�&)�H���ą-2a����t�|�"Ɂ�0X�\�r�_��p
�:���h�-�$>�H�wI��J��ů~u�_�v�ï��
�|n]ޯ����N��>���L�T��p�pu�Լ����EP��O�ܚ��� �b�vX�}C#���b�����	�e�W5B"R�`->O>\�	|�<Y���3��B*��b���=�yϨiX��z䃵��䊖[|r�U.7�&��"�&#:H�8�����+{$Wm�ž�s�+o!Պ�6X���}
wWN.�'g��^������בJ��p�3W������$����J��o�v���,�z��Dʗ�~?�|�~i���p7[��Z�c�w�L�f�aJ���i�����q\��=����?w�+��۳u�@{�㶩y˯ra��h���49W��cE��~{,_s��Ӵ<)	55�4z�ֹ��?�%�r\��~y�SZ���S�/�MJ��x���L�� )\>�2��\�F �M���7Z���.��q���J\n}$^����2��h��,7�i�q��1Ҹ#��`�qK�1��H㞜c$��Ɲ%GH�'��=����J�!�L����?_��8U��S*GH��C��	7㞽.��P#q*'�����ҥ�3�,݁�V7,��Ԕ��2��8���4,��S������zœ�ei��O�߿s#|��S��nێL��~Qv�خ�wE*��{X�>�u�	K��½F����O )�O�uk�Rq̄#�6��?^����6��EpE#p_�����x�Ѹ��kG���2�HؑɔF�p��� �jsE����k��[C����Q�(�V�Mx��Vϭ'���;n��\qΊFd,Әa�'���g�x��F4�IB���{d����>i�yo|�:���p��AJ�M�(��Ic��QG�\�b�>�Z��^j��!a->�FARt�XP�Y�c$P�a�#$mx�i���-��5'&��~Dg>��󤱷�{�c���i潖�Z���fK#�18&�nk�@�p����~�1�wV�U�dp��;����<�m��n�
��d
%�0����$҉L8Rk!1�bi}4���F�7���^.2�L�tdJ���hk ����R���Ó.a�v�Y�T:��9U�dEI� �����k��7�}6�6b�IY�mn�_ͭo�����:�c$���	#�$6�P�	O-������"�R���0��&��y$9OF|&�4n�� �҈m\�l�{�W�6&�o^|#��L�L��W@6��d��1|�E�VC�lY=�% VC�l3���P��pD�|�&RmZ0��{$h��U�ب��}�RX�S��)��k��\f	�᳄�yz�W����Y7k45��a"�rp����}R�%��n<�E*�e�ٜ0�v�|�E� �&R�M��7�7�5h�`��kj���L��$�q�`�{-vm}�������'�%������e��#~��,�k
�ճ�F�w-��S�a�pk���P��I��6x��2Uo<�!��)��Y��cB�������y���F��zM�0���� ��gJ��*�G�G�HM�0��&\'t�JWg�L}�Ea/l�O�d*��6Gr,M��5����\[G�L��Pu��>�[k �퓫�Qƾ�'~yS&W}،}#?�v��Mx�g|�yjqD�2{a�A+&�/k�-�o��>�}
�ҕ��PLؒ)������x�����3����[2�j��W:ll.�,�����F�X��F����=K�lnk����c��O�A�6�1��ߧ6R���1j�ب�NՈ:cK����skv��Q�Ƙ:��!���Nմ�\��D g|���A*�������>��̰���$޲���Yl��f�gʔ�U<���n��3e��j���W�eA2źs��j�^�J���F��FrNV�G�$��Y��i��F��]cB����	��"�Ԑ�ܑ�$kl|��Wc��#6��)�b5�����D�F���ը� ��}_wՑ)�����2��Z���[�{/�٧�Vx>b|�P��?�}���u�Ώ�_�ґi��E��mĹ(�)3]�=�P�n��Om{9���z�_R"�]�+�ܧ
�I>F�=3��	�̀g���x��x����̐��#$�3�I>F�=3���c$<�I>F������c$���1G�g���0G�g���0G�g���0G�g���0G�g���0G�g���8G���9b��"��L�1�I랸1y��I���1�%�GL2l�~�$y|5�U�_�b�L�ǷU�<�x���+w��Qg�����R�2�O�°�UJkUk�k���Wi�����c����'�tk�\,�����<�ī+���/���N��^���Q�^s�J;�H��
�a{��.��Hd���k��W/��Eg�wI��Z�*�rtm\�f�<K�Ɨi�5��#lƝ�F_b�Ro�Kxbǘ_p�ũ�TM�;�ӄ�<��� �  f�	��d=Ԅ�s7�~���^�7J�x�Kzy���ίh����f	"�Y��&PC����P(Ete�Tċ<�r\*��AyEb�#���P��*re��*q� �x�f�d34NM��o�،�x��������kt���B���^�~�f�Y��!�C�eo}����w��΁�L��dx��j׊_k�w���}�����!�~�F��!Չ}k񜢽��Z�Op�˺טO2��&R*]����O���H���x�N���H�ܭ��kQ�r�t�8����ʰ��ܵ�D2U�fX��B��3�ƨS���W-�'��L�Y$�g��5���v�b��d��� �$H��^��zY����m���y�~-kX�_��&-��D��<y��^���\�2w�'�B}"���4�QQ���ը�z��jTV=��a5��A�V��xX�*�GP'��Y�N�Փ��l���P�[Lf�̉$��2'�b2�cN$�&���Dn�n͉$�1Y~I�	i��G	?��Cj[��_k�ߪ���|�nS1>��T�,�2�d*�����E�p��sϞ��<��Iu�r�Zd]��h�œ'����T��w�7[VϞ�~^��Tܝ�S�Í�0��V�f����eu��9�/��
S��sqV|�k}$��ؿ$~Ǖ!��>K�x�GH�$i�!�sd�E?B�G�8_G����N��:X�Oq��@D-�z��w���0:��*�:<G���~Y���S���D����}�Jmf�͉T�����	�r��`q��}*��-�B~k����RII��v�4~<����Ĝ��U�G>ە(/����z�j���VƧ��S��w���ԘN�uk�W��7T�p]�QގqU+���P௴`jǑ�KܯN}i�:쏣;Q:�W$����{��k�ʨF���y������E���X���{�~+��]�Y;X�L컀�qw�\����xV��J�:�y�g�QOo'֪��5�k��Z�)�0����(y��7�����2�h@�����;l���X!���(2j/]II��4����e���;3�꯺��#�G��8�9F�ֶ;�[��[�<�"�P�w�uq��]�D4�;-�i�v��������)���ݔ1�6q��.b����)�Jj�v��{��<��F^d�{�"�9��Rk�l�fr1m��������&�s�Q%����|	�[fl�:8�'N���;�a�Sg��|\>�:�ߴ5���6��4�1MɎ�x��UF����HY@	��vߊ�*8�Aʎ
W]W��'3ƞOG��U�J�EW�W�o���l-�Jݲ�M��E>��w(=��8�k�f��2�y� ��S;�o�l�q�&J�����P�^i%X�a����n��Y��p��+;�ꭿ��njy��߼·W�s(G칗Cqv�����b��(LyZB����?\�8��W�F��lG�#��Y�����q|��(���oe��8%�^��_v��;������.���3y4���1�ᲁީ��l��ߜL	�(��r��]�rM�Ȥ1-��h����Z
���C�54�����No�<�G(�>�K���>���3hJ��W�׿���7ݢ�bԟ�[ZB?����D����O�$��N���	�R�?���,Ϗv��8�)�q~�l���|��>������4O��c��Ήc:#O��T�u������t�fh'p:03t8��c8����t0e�7�j�l�|0uN9�y�	>�q"�=�;��9��󁛱����|0�E�z���	7~��"�=�o��ٟ�����O�|`gx4a>0Sv!��f�-�f�-q>0S�v"\���������L�%����+#�X�ߦ�����eN=v�t��Ɍ˓Od*����L�é���̩�N�s"�8sT3�7c�3�3�Ze�n�4���(�����ַ��)�Qa���)�6a�	W���q&Lwy�gʧpN���w��<iJ�O���`>03!wy�L���
#ݟ<���A��Q���̤���X�	{Z������>�Sz���̤|��wg�G5�����=0����q�w���i�w�����TE��k���0ã��*�q>�3<j9�>�'�`���|0ã��A����A�9?��A���iJ�0S*�c�`J�8��� ���� ���|�f�;�3v�����v�����:����<�o':����P�3z�;u��^��y�wx'��h���o����jp      �     x���Kn1D��]�C��K���{�#DY��\�����	��В�z}B4��-;��udu���9�\������������sd�V�y(��j�F�J�Mզ`7c�U�i&��ʈKEm�g�Rg��'�� �;�h����+��w�J�Mզ`7c�*y�#�!HK�~[��8O�'�� �;�h����+��w�J�Mզ`7��WM������X$�1g����zG��;�h���#����T~�H+7U��݌5f�v�h�O���O�AK�;��{G�A�w�Tߕ��;i��jS��qO�����*��I��B����v���_� ���_� ��R��A�RqS�)����=�X�/���I��%m5]#[=�:�"81�w��D{G M�]���#�V*n�6���Rۼ��S���Wt�j9���Apb`���#���@��R�yG �T�Tm
v3v]�fhwwY����z�a�;�� 81�w��D{G M�]���#�V*n�6��N�!�{xYA��KB4��1�����+U����#�Ǉhc`�6l�
�
�*�탴�di������nQ+Ey�j'�qBo/G�A4{G��!���#�*l*��wҮ��*}�u����f����C�xo��}�@�w��At��4���F���@���H�NN���H���ݘڒ�B����k�A�w��At��4���F���@���H�N֖���3�Z����nݶ��8���_� ��_� :QC�A�PaS�P�_� ����;ӽ�hJ[zjMF�sܣ�F�@�9i
v�@:QC;Gp#T�T Ti��?;I�\��k�H*�m�z�7���7���Bb:1���ܔ.ALG�NT'F-S�}�L.v$�XE�2�	�'��Z�5�v�`���9i����#C'������>V�i��֩�
�������b�9��7pL:�����L:�Z):2t�:1jq��ϭ�<�Y������ݙZ)�Xke��r��HS�xG���Rtd�Dub��00���������2��l      �   `  x��V�n�0]��𲕚;�6옦U���j���c��G���:3�.��Oȏ�q�	u�
݄�</P@~P.�p.�0�d�X01�N�e"D�'r0U�ʪ�ߜ$��ڒg�W�gܒ���ҒB�"\]<�B�R��-S����r��}�|6RȮ	�1��k�Q��/�P%��aB��+�)y�����x���	��Վţ4 ��9H|_�l�T������8�[X�̣�/�Y���T�&XI>1�^7����4݂=ʽ��'0 \^��u�W�O̧�йZ8#�K �ؤ=�$�$�l[`.�\8)_o>,�yT��[���+HK��g2$O���]<��E��u��]3�}�>hS�~�v`��ƻ�޿U�˒oJg��D	\'�$�1��i��A|@t�S;���B܄{��k�Ѱw��y����.0�rN�љj��:-���.����0�&[�q�9�^�G��:�#v�5���W�ǡǊ�\�2̷~��x6r���5��w�LE0���c���ז�;Y��vm����2��#�.F��+����O��������ȋ.>4O�-/n��兛y���n�Ձ�Ҭ�9�Ji� u\�e��o�t�|PV��E���0���^"34����ޏމ��"=1~~8����CmZ�-i��t��i(]*�[8�J3��P�!������K�QS�����P���Gox|ɘY���ͧu+��R�H�@^���˞�ft�3BYİ&z��c'�'jw�I��IȢ�`yZ% �B�R����l6��� J(�K���9���{_�g�&�%����v���"W����CXKW$~�8b�m_�_��^�W�Q�V6���6����1�<���      �   M   x���� C�3����.��ڹ|��0<���M��
��;���u@48~�s6+�dZ3�zO��5N|�נ�      �   (   x�34�0�24�4�22�47�22�Xr��s��qqq _�!      �   8   x���  C�3�P�����
���&�J�[�HIMR�=�OGk�(���. >6	|      �   6   x�%ʱ 0�a��2��@�\�z�i�4%5��E�|O�c�9�ǳ \v�
A      �      x������ � �      �      x��}�n�H��:��l�r���y<j�z�.��
U�۸*�R�.3�!3u;ke?Jo���x�7���Eĉ`��T�/�0��"�d0��9��_�Ew[�v���u�}����}��>��]�����(��z��݇����l�����:ow�j_����-^U�ŝ�Z�_K�t�w�?�P�n�������
nx��KK�G]��������W>��_��i����3,f�|���������l~6��eѱdn��|Wny�M{����㻲]��0z����v-\^t��&�/�\�e�*�j]�5ݹ��ǃ�`jg�-�lz6����$��]�܏6���O�jO*�
��
���v��M��h�i����mY�����9����U>:eg�yl,����k�^��������Zmz�6��������vO����M`J���l:>����N�euW�����ES7�'N ���tX���j[�^�m���_U0��e+X�%/7���\e^`%��']7[X|!�l��"�i6>�fg�<6���޶�r[l*xnC���E�kBr�_�"}��Ф^��@���(8�ڷͪ���r��8�I�������v?ӜLΦ�d12�|_���F+qU���r�ޖ���͚?(Z<%E8���"��]�]���H/����K����-�s�YD�b���'�x��7�C�g�O�����x0�Ɠ�d��8��%��D5���+��U["V���/��kQĿ��NUm�]��3q��}��j`K�W��7)P�7�^��˫��z���?�{L�X�ex�g@���dj�����-JGW����I�|�lwņ�6��p$�V���_�ׁT��o�[�g���jR�T����ݶn��:��ލ
������ִp ^A��W5]�	��pfG���gg��lK&Ge�x4�lK�vī#�ia�E]_���a���n���
`3�C�МI�^�|&��p7��b>X,���S�O�K��`�rӴ���Mq���QSQ����G�u1y�mYU�u�����2���f8��߄�dԗ<4��,�yS��m�m�U��45��Q�-;���G�#r�uѓ�Ϻ��r@�$B���ť�di�ErY���r�� m"�f�r��H�+vD�k��I6�䃐�M��EƲd��|D!��� l>����>��r�i�d	֣Ge��d	8+6�dK���� �.�h�n@����BR{+	n>��7n�-V�4�|9�|�x`~6��ƒl� p�nJ����Ϣ���j=�=�� ���������ۊ�����>����I2 ޟ/����Kr�i/Ǫ����ɦ�(@Rt�F���!/��H%��p: T�C���<`4��Ǌ���UET�(����ZN7+�9�%oo�-}���߯jX�!s����?�����M��/Q6�>cf� ��I摥K&K��7W�i���t�l�k�Q@ȅ��@*����	&�Ѱ�; ����V����n `�U�H|S�����^u���^b����y��,��ƒ�c�� �V`G'�AHk���4�IKf�i�ŗBo�Z��0H@`YD�L'g��X2�8�گ�<�,p�P/l�� ��]����|&D�"���B����;�q��<����Y���%��-�GXa�W���As�S~<(��/F�*���X2w�j�1�o���`?0�k�[��z�7_�*3u������_~�  �����@6���-��bS@[������az����ߦo����������/�_��6}y��}�v�����������ׯ��h0@y��6ȴg�I,�<zȂLX��w8�G� s�����s�D�4�$���:�a+�ܡ�yW��-V�B٪ \خ]阯�Ϸt�ꪶ@w��ܟ��#�e:��;i�̇]��2<�<YCk ����E��%�G��`��>/R�^^���ؔ����M<�5����������!��H�^u��b�ܤw�C1Y
@��$�9N��D=�#�ZdF�������.@�"��*��Ҍ+�?���������(�:-;�)p������!3��$�6����b�WN+I��XuQ���7bL;�2�t�i��e,�;��G`��<u�0O>���XóY!#���=����J:C��� ���o|�� 0�ONT���ۺ���ME���>���y( |�;�8˦�̂ؿ��_��)�N�h0��4��QA"��h�"�]�@�.�X�0�P���9�����N�!���
�<�eH��u����:L��dHi770�i�����>�i��@�+�^:��[���q�5���@�̦�:��}�Ic�؊�lWM��Q��?���V��?��^��k4I���a@��a��f��ZbR�ӇX�<:��Y>��-��Dkq�a=�8�	�c�/�8��(�C��r�ƒ����"��n;�����M|�����I k �l٠/ҋ}�n����G�����|Yɹg_uc��.�����5��G#V��9�Ao(_���|�-�%;�����x�N��\κ�r<XFl �G!ऱdf����zG���U� ���\L�H���x��ߥ/Rs��޻][tu��Up�j��mx/�t����mDi�L���K�s���U7̫��tM2���*v~��!P7`�-Z*��#wa��| ��o��e��X2ͣƧo+dZ��=hb�/EQ_n �u�/�
&�� �ܯK���`3�-�5��dj⛢]5���T�ԧK�Qu���N?,#�
u�<6��J!��߽�+�{��V�Bcd,%o���������u��5���(0LC��"<a2���GN��z��>q��6�F�|t$��T�hn ���w��B,��l:�@|?L�R�-;o"�k�V)��v��0uR~�������_�ݐ������S�"�Zؙ�Q�C`%c��2Ǘ��^0��c���m�O9p��/c3>b5@�=s��C�n,�m���ۂ�޷o_�Ӥ��;6�G���Q���hD�26�(�����y��מ��~"0o�fo��"0{�f2J2�l�u��\�E�j�_�t�n�N�B����%f�ѱdl�a���8?q!<�m���`����Fg��L�X2���?���$f�
CzL�����2�&�G�`ߠ�n�啱�-��"$`�����_������Yu� j�1����l� j\ �K�'j�F݇��`c�y��h,��7���(��c{��B�|��dD��]����G�3Elr8�L�ο��|�4��S�й�eC�����7bX�,byK��^�m_��"}S5���!<� 	;�#����#�E���k؞��f˺��Ǡ|��"�*(�d��-������ddoA�Eǒ�Tu���
-�))~��/��ՅX����I�m_~���C��2��p���_�_�t-f$��kj��GB� ��ܐ�l�mD�z]ݱH[�����GT9K�
�j�sn�;Y�W� ��/���di��GC��9⌬�X�XW�,b���AȮ�PTSKr�+�+�̐���CpF�wcIn��;Gd�t��]���΅S&��X0�%�0 ܉�u�����]�~1�?�-ӘD�k��yߕbdK^�����M<��=��v��a�����������c����T8 ՚ݸ���U�[`�C� �z�Q*[���&�YӘa��p�A��9u�ň�x�!�*�_p!�;L�KvH�u�;qWP���h��4�� (v���=uZT����&y����s�X2�R��}��@E�E�TH��KX�f�h�Ҥ�6ű.u^/���/7�ү�i����g�X��>�>ߥ�d��R�z���mɓ�d�wu.��K2�ch5�kd��	�Px�iv�R�Ŧ/;��-��X$@փ�8�X���<�S,�w�W����w���]o��j�b����(1��t�.��p�z�d��Ny�l5cP��[��bCv����{VaE��("X@���0���FH@�W����ck���\A��DH���	Q𒽝    X��K2{���/��Y{�jא�*�H���up<�D��@3��M,0����cc�b掌E;'���,��]$��	G�Q�O�XJ7���)ӈ�x�9��<x9 8F�а��h��1�2�Q
�@���c�V�	� $2�G�����(�<��#@�Y2����mao_���/=U��`�Q���!�Qz��12e7[T�|�G�{��`�s����T'�Dq��z����x0�y���% �-Ź��pQ�'"�f@_�~�2�G1c�T�x N�M]=��8z���I�b0�COOñdb��.K�ʶ��Ů�PֲExK��V	�[���t���`sˌ�gƒ��·�r?H7z� Hk�����A/�g���X2�Dh�)-�;z�H���z�E��5���Ih(��d��6O�u�EԹ�������Gw>�G�����X2����F���"�A�	8W�~-��<AsE�g��a��W�����Hh��Q,�+;�J$�%s"h��zusH��%�'���s68\�B�ɓ�W����;�����kɳ ̀O���,��X2������Ş��P�K�F���u�51�{:$i,�Zs��6��R/���t���a�}���Ԇy�LO�b�oy��X2q�De��L�G��h�`r/2m�Q���3�G�U�/+�ۋ�����aىx���4��3iOF!g`�'6ܖ5�D��-˘�n�s�X2���ey����h���\����=�;�C�Ѳ�5tr���dKf�:� $�"�x�`��/z�'0�eNL�`l3��1Ae"k����z���jL��H)�'���:S�|�	aFv�/��=^�t�����0P�w�wu6Ǯo)��(w���FD^��H��G���	���&cS�f������~�"w�P?���8W+W�(ƷE�͍H��|�%nv�6����J��^�� #^��Xq�D��������t�@N��G��Fmj���u	�U|���	�x�d�&mLl�睘�L�!�(����x��n�)���mTRl!ƇS���վ��[ Z0�zg 0�;��Z+n�]�{��{!�p
|}���iӷY�l��#+US��U����&1|x�1�k��
��7�r���"R����j�-����ܡ1��m�(��a��}ӡ!�e2J��x���N��L�0��!�#
}��p,ɜ	Ș?q�_IKĈ=y^�[?�o�w��i��0{|���x0�.lV)Z`�R��������1I��Uf��o{�N��2��2)V��hL:%��%�P�X��Rt�͖��e�6m��ċ���߼ޗ-��W������/I�0W��^ѥ�g��c
�Q(�NglP���f)!�3M�X��Oj<��0��8��l��"�	�Qڢ[�~�5h�À�X���c'���6�>�6��b`gc��P��^�9�C籱$s�\^ ��DI7Hp��X�q��h5a���}rPA@x$}ks01�%y�-Ö�tk+�cV�Qj������a�R���A)�g-�N��&8�;�m�u�U΂��jmΐu��8:lQR����S�B��;q��h��U����I��F����
) ���Y��)K����=3?W�'�����rH���?�����w����_9$��s���5p�>���� ���ƒ��o�.9��2k�������ǽ���&�}�?n|!�d�l�Nlos ����96� K2g�|٨��Jh���S^��_<��v�=wy,��4��c�o�tc%&��C�	,C}�����|����gx��B�Hc	����#+|���	���Ql�;L�� P&۽K���V"m��lJe +߶ܮ����Uu�G�HL��ڈ�8n��;��Wu�<��7�S"��២�S[��)�6Yr�5
��I��5]7�pjh��(Pr�% 9�f��9�Օ>A�`��%j��%7�O�〈Yn���ϥ1���n��7�h�GM�g_wB�@}�X���������_��r;|���H��A�c�=�L���m��\���*�Ē(�����D�\�X����w&]�gz|�H�$J^���tI��ݲ4����K:5"�9�vZ�x�.l��uL�������:���~!<L���h�x��^��`Z<o�\��p�SV�16���%��/"�,�&�=���2]}�lK2��xS���$!)�r	��F"�? ��(��`��-P�c�IE+��R=�~r�2�Ѵx+�b�X��8����� =h*�˂��ñ$SFP�S\��]n8��4�S� JЖV��Z)�ؐ4-����M�<��ݩ@���W.��_VlH�wR�K��-*����%CNd�U����U�mN>0U��X��N��A�;LF�ol)+����%�Q��*�.u����ݟ�N��h�/�l��l��ƒ�2R�1EO��?"����P�!������j�A�jXK�����J�܂��r��\���l�`���v����ʨ�E����*�;�!����@0����,�)�-rD�`Hӹ�N��Ḡ���E�o�2'q�MsG`)�)����Ү��/hǮ4(���j�N��*��m��;��UĸH�k���0��,�$_}�iH%W��mȰ�����G>m �T�H�lŪX��Dq̨���hA*@�����i��%r��n�WX���[��䒠#��Ar�ei��?_����R��|*��r��
Wjq�B�Զ���3i� Ex�>E�i�"A��@�룣�8fñ$s�c�������/�y����ФLExsYS�73��
�����X�E���l!���H8X=�e���/,#b�o����V��\�ͧ>��{�S`�m���d�" ���Z�b��획�Y�C��-���Ft�p;؟��K@�c	f���+u�D��o��N�Y�G�܎�\�7��w��t��0ԩ�6�^
��Ux�1��+ֆ{�I��3��aC4���w&F����VWY��wF	���f�ʗw�J��%��A̚V�RJe�
OW�q���Mqh��"��a�
|�J���T�@��kIJ"󄵍,2b�2Vv���f����5�,gr�9���k/"�,w�Y���P[c��J�!��X�Âo�=��\ٸ�2R��UC��0�)��"Lq&��X����7�I��5�S��srS���p�΍+�gc���Y�M0��DR,�4Q�yA�1Y��u{�)w��1��$�}�$��{�X�agan�Ŷ�є�L����3&�'�&�.b��
�*-������R~�U���Ҳ�����b�5|�2T�J[*�3���*�RIyvk4�|���� �'�Vv/
Z��-2��\e��9�Oh}��F������[��ZY��ÌSC.���icU�����y$B�ay���g%��T�ݭ^��Y;:�G�}R~G6c򣌑���a �"F P�#�6,ϱ����0 }�7&+1K2����9�����K�ӎ����gk�F\a)�ȵˍ1�����Dz���Y�,n��T%�����AYD/��,M��5�1��3#V5�@Y�>5@�F��8���lq��1ۤ�ű�um�g�o]~������>�������e[���Ħ�9�b\-W\�����sw��`��|8���mP����UI�t�KcD�&3E&G�</���6��2Go9`"�
A�1Ó$�ڵ�jP�P�������/�9���[�;w��x����*䎑3w��V���
��vE��b0���lC�ے/�{��.��U����Nn�B$�lT�b�!+�$�1T|��4��l�Kz���>�ױWH���� [�\����X�)�A?[�w¹�~ky����Qa΂��7��������^RdU�<p�|�5��RN�!���r+�L��m���9��^�TܒWhV{�W�~�3\�Z;cWt�a�V��P�t?|F�{�`8�C������34<*r�g�Rڒ� =���I���r��**�'z�5��u�v�cY{n��` JF��    �K���NL��v�9^-Ē8��F\f��-$�5K2Z�A�o��OG�0�`�m�����V�mIZ�����Ð�����s��1��}�c-^��	t�������T
�6>S|>���J=R9v�� �ټ)<c�`6 5-�B�F1�+��j�z>_q'({��XtY66p�]�vJ�!�S�҃��|�ȼS�l���@Iw���g](�0b��"�@)�BܢUF�ۡH3&tLT�$�� q|4vWU߀ J�C�؁�A�J�������EV��$��� ��ް�ν��(G����������s�+�.�(�q�X�6�c�/��۳o� Q���]��<v��)3f}�§���o/#��%�rF�H���w?M�+���K����ǧ�,8���:�E���H�p@<Ԩ��)m%9���P;������^��"	_�f���
n\R�/0�{
����`�mSp�P��l�}B�cd+�%�Bh��b�G$n"F�v%�O���G�t$�
����#�b��m?GH��d63���y��h�N�n[��X�����3�B���Iu�T�g�E�
{2�;h}��F��D��9�S���`'��<4��X�YzA�]?���	��R�������"�4��I/��"�pJTٴ��H̖�&7N���,��lI�I�(������&`�ݯ�"B�d0���R���SE��)��mM����	�F>�0X��L�'�;9��Q�Ƞ��%�9�@����Rҍ�m{唜qRXX��X�[�*���..���D0�1G�����j}Ͼ��:���5���*�^ȝ�i	�j/�q9���	���PcI�J��K�]��l+mߜ��q	������V$ ���S�M�&`cނ��7%;.�O��Rݔ��� �+{o�AG1�a-��,��L�8�`s$k{ե惠�_gx[?��6E�Ӫ�q��P��@�=Ӻ�9���0n<V��/�W���|�r��v�|+<�}a8�Y����V�8�E�[k�ߔbz�ޭ݃��\�+K$��hz���:gK3�-`q���	x��x# ����]�^�T���~���,k{6����X\���EKE6Bi����uq�hs�晞5QA�FC�00�%b �8��%h��Ap�iy:W��W�=����(j�5�#��8���Ѻd��Z���@1W��u%y*/�2�f}�3�K��6����2��8�Asݓ?}��`|Ne���0i3������,��h,��vG��e�*y�?Q'��g<Ax:G.a�/*�8D���PU���I.��y+�c��>�6�P��C#�$C�Òq�jKl��w����"'��=(^'��_���>��[OZ<A����D1A��MA�a$Y#�b)3|�T��Ľ���w"LT��	s��0��( �,P�<�����g�����=n�Mf��Ke�F���1�'�w����y��ⓨV�y�@�l+��LK�`Q�
q@�A�0J88�7l�͈���9�4�d��-��J�ay��K�2�N��7�cs��7���������к�X'z��}F�J�2x3i<8�:6~���BQg��3���R�0K�N�q��<"=��bx9�'�:b�Q�fҨ�B�P�vZ�&�³�<��Ӹ�W���@��3�}�ħOÞ�5X}]j>۷w+��C0S2/��*|-�P���源*A���	6��;�R�H] @�Z"�>Mx�8]L�֥��P 1"�-��IS�C��"*ߖ�~��v�W6F��V����,�Iܨ
Q���� ۣ$'~�W�\ZÄ��H����p�}�7!�f"�g$4����5�v;����0ğh]�h�M�:���˺-z4v'":Q�)���F�Tq���/�m���(���r�����#����D�#c2-8�Z��R��|�l��	�G��`L�=e�zJX�e��U#���ʯQTPC���OϲЬMc	�Z�-�+dX�aE*e{$��U��R��=����؍�8�]Y�M��W�4uiI�Yphbw�C�<���L�W0���+P�&�Զ7��[76Y�%���e�!�W�k���ι�u�A�hA	SW-K2��������_����b��ܵ`W]��ԃ�G���0��:��3��`"�¹��0ǐ����2�4�kl��@nI.�KC*?�(&�9G�PUF��ԁ�E�>g��1Q�Q���t�RIޝR�"F�.@�[�{wC�r���fM�m4F�\<��Z/�ӎ:ro�a��so�/r���j�����I.��#(T���y��~�Ru\��a�)hU�t~���R��rW|_S4�l?Hu,@+�4��%X�����z+�ܛ���u�>��;ty^,k���P1�sث�ּ�^�$|ѡ� �M�ϑ��dw����D����0�B<�I�J��
�2E��M ꝄW���\����P���$s��]�B���h2)-���c���}��
�"�d��?xg�������>����k7��(̝�xx4VI��~���֓UL<"3�~<����O�%���\P�P,+�ʟ�V�pE���v>�S<���e�@x�l�>W�Ԋ���+QGk���%���z_"�p�O),�a�2�#yq�gUPK DK����++{�4Gg�X 	|�=��}Mf����>U~�J��
��W�__�{�R�VaR��-#k�:��d<��'�������X�}L����}xna���f{Wa֨�m��|���J��8i߫,e���(4�s5B����I8�q �4�: �*[c��~��"I���H���p�������6�(��i<��%��n:�y6��b�U�ߑ�f^�ކ�\�w���aZ*�I-��pdi��Ut�� \�N�08ZLQ�;	��{
j���l*"��Tc�9�K꡸v]�k~$9N�t��[�<�t\�:Al�� 0aw��އ$�$��\�~�j˟T'�߼�y�����R���'	�b`�S3��x%E�*#&!JS�)'���F�[:�E9z���`Co�Gp�tD/�U��o`��h�����
ǒ�u}��������n)�C𰡏m%/��L���>��,�YӊQmƛ�F_���q�y�-c	�S?���P�o�B1�O��6S)ѽ,\i��.��%IH� �Q��ؓX������&V"���mtN}��i����J�{<ɇ=4���t�|�RP���d,�նKL|%~�XO�����;;�o�9nq�G�˜ۻ�l��/3����H��$���e�X���o���x���?��Z���Ȧ?B7��3 �GS��X��,ʡ�1�UX���������{p�W�D<��8�2 *t7�C)ۈQ�T�ث�i<D��\lr���]��:Oϕk(L�g��"k��~�mG����k��|���eQ���L�:,E�ie�^�j/^͙Rל�����=��/�����`�96��{�8������!�S�ʁ�l��Vh+�o_�kŐ��������n�ñ2[&�Bg�6ṩ�wB�xLP�Z�k���d,���a3Wn�}~?ؚ"'���E���?r����>��VP��
@���z;`)�I���s��;���0���K[�^+a�{�f�l6���6�2�_à.��2��W��i<@����r[���f�Hǰ�l�����aЖ�>�S�A�_y�݉�P!
�w�gJkl"b����O����W�}���6ӽ�=�D�ćP�!&�zf=��,"�$����e,�SYNO&L�������J���:��Q������;��F��XY�]��*��R4س�� Ԩ�v��x.�5��a#��ye��ĝK�)�taiZ����5E�}1�c��lw�OP%��J��=S4GZ��:f2$AltD�����3_�Jˆ�Ñ��a6�1�6�QUL&*�<�v_�*���@�#KM=��D�5ݼ+,k�I����T�:hC�wN�g�$&��g����X����7V��c��
���5]��Ǳ��b$=Z��/C�eХ���4�s��آ�D��I5�'��2=x�QyQ��`�f�j��	6    ���$w鯯�E�o���I/ɞ�_Ӣ�kZ�fs�u�$�0���3��5m� L����b���f��,�D]�����3�:�G��亸����x�����@��2�Eb��]�~X���0}���iM ���:@[�p�Y�-��ϲ�9@cI�"��z��v��m��;�'��ڗ�X�j�Q��%iu<C�"�EZ�˪�-�RI����)��f�D
��S�����( �ƀ����W�X���5sk�cpO�vƪ�U�{8%i��Y���ؖ �+�~X�)�>�f-��x�p��ڸ�}0�R��O�����u�Ơ�2@6`>��E���% �ۦ��-���������W�>|xl��;VDy�ەY�H1>�[����zڏ��J��E��S4+��s��H�u>��9�Ǐ�{��2�d�QwB��A�7���T�z��B�����;0�%���z��"W������<�b]��M@�~F�5�J���>[e�S��hN���OV�}@l�<�!�C�/��� �^>aC� �9���7T�CqrG�;{ś��MJ��sAq.IE���Ǿ���� N�_T>d�5��q2�a��Y�5��R<�WDS�5�.��b+���7�Q+e�cה���C.�	��*1{�%QA�?v���R��*�ܟ,�8�oK��|��2�S��;[v�!h4��:vȹkO��F����Fy�-��2GJ�����rm�Ԗ�p����#5Hb3���˱��t_�Id�'���E��xg!#-^7�}���B{���E�6�]��v��Ҏ�I����օ�^=h��o,���8}�0i�Ȁ�N����Y�ӕ&�e�<�Os��l��ᣁ��Wa ��JZ�Ua��V���F�����O^�
O�*Le��z����xKrW����͏�Aη�{ q4�����/C�u���w��ڧf����G6:��y��u�5�`Hy��hW�'�ܴ�ȅm� 	�K{�T'�����TY'��Ҏ�ֶ��@�F� �!H���4D$4��.��£=~u<��T]�ĵ�D !���9�[[:_��F'S�U%#}��Z�Id�g��5��������b�5�Pb�?G�#6G䯍a��k*a'���M�uD��-Z,b�;=��u�X����w�+����J�_;���ƙ� Y{�!ep��r)�CNF;N��^& ��B¼�6 �`-�ѵ�r5��*���,N�n�X�ޗyHO|��]W 	K$�����@|���G���j����������5��7��k�=?"����'�fJ���{��w�o�eݾ�e���O��8Z�I���JU�~�Y/�z(�d�5.�)�1�z�k��8�t�f�嚊/�����Ya���Z�w˘C���h�3b�̊��wWՅ���DNy����W���Y�B��N��X��L�ռ�4��g]-喝W�Wiaw'�K �Λ嫮>lp(b�wJ�{(������/g��A�r3����+�ૐ,H�\�ЇQI���	���-Ll��I�+.��$Ia�"�(����y�9��[f���|^�����5VQ��J5�[I5�6��p8=ދ��N���j�]L��T�q:1���+�����S�P�WZ.Cj��f�X�U%����q�qRp�zI4~~��M�h�"�W}϶��>�b.��MK��՞C�ͧP�릿��]��O}��gF;������YgWR!/�g��g;�� Ԍ%�k��*�t�w)n�Kq�MS!N㣦�n�LV�u�B���zV���)��ư�m)W3.��Nk�l+��.�,�zu�%&N4R厈��'�i[S�*���?5��dcg��3R9�M�Bš���+�SFz}P�5��LY��ֶj���u��y{JFIۗP�Utz��nFe׍��W�E���#��������LO��m:���s{���<<����ۣ-��C
�9�9�4��p,�n�t$�9O��I�N�R?r���{YH%�츁�Ƽ�AS~�_؊D�,���b��U9V���U�1���ڀO�%eJ�����g��~f1f��eѱ��E~��y��J������)"��4����a֣����t2����g�,�����o�L���_�z�.��v��jس�����Ic9������+�@Kd��߂�8�u�Ff�[ )�g�����F�0.�M���H�����5˼-n�֮�/�D�+F�F�P{U�x�8d�ջ��y� 9��dҀ��È�a��]Ձd/�S�4"P�E�ȱ�h�^,�@D\B�a'/X[	3�Մ����4:Kb����\C}�%K�+���}һ�F�vh�#D��22l��ʹ�[���r�7�lTv�9��\�6��l�m��i4�d�p��3OT=�w��˳�!�.KT-Fa|�QMvXM��#�����ޠޑ��f����3���Qo�p,��"�@{A��>�]z�����%�t27��U�n�P���PAw�g���})�ܺBD{�sE,io�d�Gֲ
Gc@N�׸���8���9��i��Z#q}���k��[)�i�V�65�vR&���� h�����F�W!�Q��*��X{�vm���^�bԗ�M)dT����	�<I{z����#%�g �`qm����KvC{��g�ƅ���j�v*����T���hO�o��׫�Ac�^�^ſ�b&�H{�w3������I�-Ii��
�����*�J,'�U���)��*Y�׫c��w.%�;����:]Hk ���i,�}WԆ�]	+Id���ʗ�b�V:��A�b�����K�˨<���`�������o��xR�	�U����guU-�=�8zN]���bv��0�$�a�X�(�S����Q�RW�����MP�K�D̺�b�q?A��`��}c�-�������:�Vg?ڀY:Gպ��[m;W1;��1k���c��ģUz�hV͂:�>��lS��*~�o����dEnc����Y���
ۑBy� ���:�$a��i#��h���p�(�����F4K8��A�چ��!5]�ߗ'�l�54��d�J�Ʒ�y�2�a,��;�d]�mDY/�F��XDi�j?����8�TcX�E�k�W�e/�²�
��H�D?�<��m<0-�����o|�mJk���0KB�3��'k�Lf,�v[�����m��X��W�vV���5'%y֋�j�ɇU���&��J&�i�::j�q��Emq6��h�@*/�.���e�*����ҏ[u�v�n q�hy�J94��Y�I����륄��x}����[�[�e3��?J�V�Q§��^�Z6k]J�q���XW��#Վ�^���6�l=�����l��h,�]�`H�-om��%9�B5W�&�ͻ�J�5]�ٕ}�qmWb�IA�m�j�"(tf��UG�8��~����Lt�P5��}��^}��,��r�e�'9.
���������bZ���4U&'��G��&���`��btx5�s�]�
�X�M����������5�R����i{�]��/����a�gg��4r� '�1� �,�=����W:�/X���Γ���z��*3tn����O�e�����
z.2
è�l��Q�Y}]�+���*��q�m=�Ԃn��p|�}�*�l���f�K��Q�~��*��Ma�h���a���/kqj?I�����/)��wEB�.�s�lA&M���b�f� $�c a�E�^�G����ޗ�-��;�mA���N_T�#Do 	� /��ˏ|���W�7,����u!0f=F��0"�+�4���\��Ph⦾/���Z�H�SJ���θS�?"9�7ܢ�<���M�\�C�9^��c�9��=��hKsFNu5�dKk=���}@f/a/��=�*���>��w�KM�T?������@X\�tw�T������ۘ��h�v���2+m�������NG�8�`-VuBPų���]k��(�u#ə��
ſ�9\P�1^�Бz�Eh����f���7���Aoc�e �O� j  �Ef6ê�OlZ$�ɞ]r}zxb,��Xz튂n��`sQ�.97�3�l.�eO@�ae��bN�@P�Ŝ�H�	<u1�j�g߿X5M<2.mQ�����߄U��6��W��v����K�<�YY�k��_���8�ƣ����5T��۠�\�o�^��E�J��N,A�_��99*8����b�����
��K�Tc�V��)ห�(�z0@��y�{�Ku�i	@0����ၰ�T��M{GzJ�֗���U��g���EHl������������Z=���G��s
cϬ�&��M��y�;=VM#p�ŧ�m�>?6s`DЁc$l��[l�ik#	!Y��\D{lLtS/5���$I���]�      �   d  x����n�F�k�)Xn�	��v®�P`;�e�͙���&JZ�e��y��.��GЋ���Ė<M�MA�)���UtE��D1�T�!� V���S>7�/}y���|w=�Mb����n�kR��7��]y����?ݡR��Ͷ��|�wЖ�rA��w��ڂ��tUtF	3%�5c9maGT�5�d,��K�X�Dژ*����Q��1���5~%�Mt"J"�HDJJ��?�(���'C'��|O�����i��$+�*"#$b)SD$�=e�����r�}��J^����	L3A�I�H@:�rVZS�D��='�i}�}��c�!����o۾|,o�����������9�==���jb%7�ڈ_��;b�Ǚ�KSS��&<c�����WX�N�@L028au�����u����f}s��n;�|1��~���m�c��)
[i���FK*��Dz��` �RGQ̴8SJՌ�	d �S���q�V$9FЈ�6Mp��=��FڷAT�8�Ń6�G�H�*���
^1�8�������:�MhAjd��/��b��J��v����.�nۦ'sb�.̴�����is��@��b}��z��-�,�A��)��gpQ崉/�H8�S�{�Y��T�`5'�_���������e��6�=�H*ű<�V8� m���kޙ^�֢�i6F}��Ĺ$9��1�i�N��;��������.�7����{���uXf�8�c/�!
�����3F:\6ZGB�@8&��)���o���W!,�}�5��3�;o:�'q�=��il�pf�CB�D(0�'��ĥD	�.1����fɛf���9md3,�h���3q��ę !��b1�]�����O+��Z��/ڄ��N�x:0<!b]���ƨ@�[䋦���2��������2b�8���9�W����)�������z�.���,W����by�:_�_�_���*�ַ7���Q)_Qa;n��	���x��dXzxcg��u�1L2N���]8b��&!q���LjW,�Xi�M���bWƶ�����dh��Y������|m����~��i>7a�����x ��J�伖�f:�->��X,���)�      �      x����n�H�&��z
�����43����PF*
���jP#iT0�r���T�f�w�O��j�@zW�֛̓�9�C��tɝ�̪{��呒�4������w2��<'�/E�aD>'��a�2�4e!���Y��^�xqW7�ٟ?<�?��I�GP���#�ɎH�}{�E�Q���W��e��[�����c�b�݊պYr����Wl�U����X{�F����N��zs�G|�ǿ�w��U�e�嫺Y{����+�U[���yP�$�R6w���ӆXz�݊�UC��1_ޙ��o�r��n��_���j<|��q����Fvc������k���J=�p��d�Y�}Z����a�2?�(+^U4M���^r�<�2?b?���8��*�"
��[J#����eG;b�?��QH��Η�?/�u��ʻ<=���/zq�&�=�w�Y�?����� }[���X��|!����Yo��k�ؔ�f�/�U�?����/���|��>��%�85E�jK~)�?o���|xm���\��{�k�G��l�xk�c�9��[<���0�������v�|�-�ިW̠'��U�q���ƮD��[p?�"�aP$!�s�v�0\0$�8�i@`�$��ϋ*L�<-iV������� ��P\,��>#��m�N.�����S��N���^�)l�e�x����ރ�����0� ��s�E�
r��=�M_�˛zY���{ޮ��0AƓ�0���R?�ǱH��UIJ���Ҫ(}��&�� Hy��<J� ���7�a#Ǵ���#��� �rfF�v��A�eS��"�����oe]4������e^�����m6{��7r}�F��Z��Y�X� ��b�?�!�ü�~Z�_&q�DK�8�I����9�ɩ�W��Iń�(�8�0`0*���hb�Q�����w�:9�_��h����Y�^n���Ess��{�
&[��F�?6���j �%��Rt@K�;ﺅ���BJC��;���V�A��$-˄b&R��YӐ��ӌ�Y�EY�,.@J�/�3�Y�WrG��V&甍^����n#_w�p����_p oǣ�!���7kq����%F�m]���Y4��*^nP:˙6"~��.���>}�J��v��8���,�3��f	@�By�LcA?&0�,'4'F�zI�A�fU	��
�|P�����`<�E��(��Y�8"f
������uja4_F\�T$~P� 1�x9,�(��X�BTVi;?�^�yAp���+Dh6z�|I�{s8uk\x&w���<��bu�P
�;�g4��z�ǆ���~�ۦ!�k�J?"9˰<�����ۣsݴc���y�F����ܳ�"�`�I�����.V�Ms/�Xp��RD@_)ĭ�R����F�G�k�a���qˤ
�I*��	�(�`��"RV�K�V0 `��1ͫ �8�K1�te�	�C�0*��5���1��bS���59Z��a�o�8�:&��tU�tUyF�\^����P�ZI�Ύ��;u��wQ�@8u��JE�_%/2��Q4�t� Xh���F �M ��H}�p̺�$�2����m��X�{P�q�]I���n]��V�:�5��Uv���j���WÑ�Z6c��mə�uT�q�o�����jr������?V7�?�[l�g��(_	���XN��3�K?˲.��%��b����jV�����-G
�0�'ٱ�VQ�N����پ�~&E(�1�0�'%��FE�碨��Q$��Ȟ�j!q��M@�;r��r#�6!�;�UM��e�܃w�.`A����������x���/�^���,j��'�g�������}����x����նyT�vGGO�LND�%ENiXf6>H$(C	�8z���A���K���t�,p��9!e nT��ɯ.�ǯa!���x7M��~�j^���HW��B�ǂ�ۍ��ޯ����k,�f%���s��k���g����e����Lo��rJhs�U0)X:'I�!�X�%te
{��A^�U��E�".�������`+�{j��Y4��5�OR<�YxD�������_�^�����;�2C�k���U�Qn�������N�7����+&���	���Q�g4���oD��a�×�=n��ڗ�s�z���,�<H�\Q�	�DE΂��q�{g��i�W���w)J��ٽ_>��t�SJN�� U �s�s�s��C7�DU�|��D�aA_MJ�H�|G�B0���ur�߮6�K��Z����]�9(1�#�4k'��2�BB	�0E4H@�E��X���iV�E��i�i�)�f,YOzTƾ�kF���3�W����OS��l8Qh����
q>�����(����� ��=Mcd�������:aQ�E 2��.I�،�#=%���~gzz�_,j��v��
��*@��t�&E�p!�r��X2c�Hwi�W�ߙ��o�s��΄~UF1�@ Y�+?gYY��HRZ��O0Y�dM�2����s��+LZ:z���w���/�|�;:�o������s�=�i2?���g�̐H�.��4�	R'%�x?#0�E�m�0�~j5BG
�ͮ�v��m-~�J)^�[zy-�#�.(�+��g����\y�A��Ԧ�˯�Ck���~��S�@M(O���Uk6q� BE��d��IquL�=&�����*��^V,�@�7:���^�7��Z�D����\�S-����ߊ��6���\'�뼲^�6�:� ��yb���q��T1��))A�b�v"��0����)c�i�5�Ȉ=�ګ�X'�����qzk��(��tP?���c7��z̆�4m��&L
�4���C�<�@f���"QM�xnB���d�r�f�D��:t��*�m7`���	���\��	���b��ra�/��8p�����)�~{[�������샊n��^<߳�Gk��9h�`�$h���V��YYU%�w4ϧh�A
'�%�#b���TW��޶��[�?������+�_�Oޔ�|�."Ѯ����N������
�Q����QO�/��fܰ@xg�UߴW�wN��JYwk�~�� ���ŏ"�Y�U����+��,J�0�I2�Q@�>#���N�߰�mǊٖʗZ���B����Q� m���ƅ;��P��4A߫ro��)�{F#p�L֐N��ĸ�H<8�f�;�a��O�K��1 ��t��Ucܬ�/7�n�e���7w�66j�s�X�ղ^ϳc1���:�[�Qs�8f��`�i ���S��+����9���m�l�:9�|]��@�3zSs��K��S?G{�y�V<�e4aI�e
��Q¶<
����]� ��ft�oVM�O�V�����+o]��,5y������mou�]:m9{��<@����gF��)\��f�U�������7(���8.a5�M�#^��b�����Sgf�bX�h\�!��E�Y����(��h����reio������L���Rߛ��e#qO0�6K�#5��������-D�@��U��ԧ%��'�2,(X�EU��Q��K�����~��'�A4����Qd��%F$Q��>�aA�Z�Ex�����*�r9��j�1F�)�U���e��9�`-.����v�-4���$��\�����@��z��|��,D���y@}P�3��0W9h�-8�`f&,D8�Y<�}"��sw��S KO��S��Pn��è�Q�n���S���簠�	���2� 5e���18G������Պ����R�@m�#K=&#��������i��ǅa)|���qDi0��nDQ��-Ƚԁ}��{��N�=!�ʥ(aڤ�Ur�o�ܺj�3W�%8P��e�H��z`&A�{�a h���^-.��$��Gn��%	ᆢ���s��$�,!S�� ����gh��}1ɭ��7?�.����C�+�N�#?�?'#(�+��А�WCh��;��J�?���H=堺J �jk7^�m    FXK�{��y�|nx�/GlݑVp��u��O+9��2�ˀ���&�H�8�E�ˀV"L���@�e5�_�E�����ύ�8��iF�r��N�k�f�u�v�`H�.h5��1y�;,h�=O��PX8��i����� �|/�Q����i��yebO9�@�gQ�#�7#9�:����z�Ԓ0n����6�Rԅ:.��-��ȨXٙh鐘hl� �"�"X�İ|yXL��2�3�eѨ���7g<�+y�*�-L���r��ͨ�$�f.Qu��|X�����Q���F��e���3����E����-p:qe:a팱���Xm�*	e���}h13Z5+`�T��|i"���)����kgTA�x�~Z��D$�4f ��0E��dJ��pH��F"�n�?��1	r,�f)�a�k\�"Y��Mа@-4Vb�Y(UL	0�j�M���
g�H^I��d�a+{�#!h��g���^�B����,y�U� �=��M��c���55�-�E�Wq�!��. Ri�`Ee>%I(��6DVFx؅�G���_�?�,��݄��I@K�!�X���պ�yA����ߦ�uc��
O��a��s���}P/���(E�*�bMZ&(�����Kg
GK�<)H�	a)�)�,b�à�m��of�v#���TEc�1P��,b0�67�@h;�'�׭��4{�)������������������Y"R��i�����"��辐M�q�S�h�
�w 	zY`�.l�ќ��O�~E3���L[j\m5��b�)�}��u���,�}��<�ʫ4��|��ņ��o�a��ٳ�z���Ȗ_���xH���t��DTK{Z��N��X�>&C��Oʶ���r[�A�o���O[���B���,���[i��u�u/��L����c��<J�S��F�����^����e��{��*��S�H��
V�yx^UG��:=l�
4�;��9W(}�7Yo`Э���l�<68s�B�E�*�|�~�	W(G��������Z�|�I�u��7y�fiC�A��=-R�Ag*��JB����et9^R��R��!�T�(�h��p�����y�|ɷ�H�/��·��'@�|��\�m����?�<��,�����Z�����H�qۿ�������8����/n�0���W������R��7�e����n�p����U�X��B���x��d�k�W���=.ʌ�92��e�`�y�)�G.Vr�7����Ke�xq��s����G��'��^������h�������Xr6��^׫{�����^����G�y���(��ғ"N�-���V�%n�e?�b���|c�㷸�n��cV����Y�n�N�0���������T.�&�A((�}���J� ���΍���I]��{�'��U��H��O�;��N�>^�?>���N�ϯ��uV�8FQ'輎��f��>���G�7�P���t�;�sO�sj�Fء�?���O����v�/V�-�������Q��8������:�r:4�vH�>�Rʈ��9�O��}�/���(u�Q=���<0K�����6BjJ�0�CK�����o�K��	�*������fD錅�5�;��ga��Vc�3*��?e�A��Ut�7�,�is+
 �=4���k����N�f�j��@Iy���RO]�+���ү����f"Xe\�v������~��1jT�ͪ�<�Ru.��*��]�}��H�݁�F���`�r����Bi��/�j$����w�ؓ%W*�_,�m��֓���JC/�k�;�j!����F%��(Vʅ�0�t��T��CÆ1Z_7@D����n�~1�I0��?������4�p���?�9.n������{\w��>�����2��0�*��)&�s�s�|2!��[ѴO����É������R�k%*X�B��(%�֫G��׃��xV�~�0������e�2��5F�tR�?,�dϊq���4<
��L�6��Bz�/��K�S�.�N�����q|�����7�Ww�8U�b�l �����O��Y�i~���q�0����k5��^>�.��A2XU��8�YI�F�X4�C/�#������p�:�8B��4փ3�t�!�y�YZXɢ�Q�5���h�NX�	OA:!�8�#?O�*ū�b�'��>ioτa� F�]�x������[���,��ky���%c�^��+eFЫ�5D���CY���ow���:�d;�Pp�s_aG� ���hG��;U��x�$p�Ĺ���"-�0��+V�}j��"�	|�L�;�a/��2���"�sb�:�,���B����V�\���V�=p����o[����x(%���5�j��L��'��L�VۜKOwO����{�Z�J��pwX=x�����#ޫ�z�`,�g�V��@,%Z�R���{�+;4ｒ�C��5��g���0X�$���08)c�����F�Y�8��dٌ��R�<�,q�_X�:�����Q�I*����3�Ÿ��2zH1��s�E����|�L��e1�����▥i�Q.g�Ҿ��&��X
Y}�Ƽ�;������l8mK�`��,w^�!�M���u��%e�w�hp�P~����s�s�s> ��|�h9�N�;�C�Eg���!v���OE�Gr]��{eTm.��xCAb�L��m��y��#�Ա契l��ꀱ�|a>Z�.X�����q8�]}�5� ;�T����,��o���@�Y�ͯ��p�/��77��	g�rh]|Ue��lu#�������Wy/��RE�Ҵ6�v0�a�<�)��q���ГM�5$��8�;h󏤈�H�#��`�k��BC~Sܵ����8:�-[JH�X��Y����Px�%���mp��K�%��n���3����Y�$г�@X��Ңx]^�4Xr\���ze�<���[�����^i�n��F���j~������|���Z�M9x�Cǭ�'}�-�9O%`�p_u^�|#��cs�ۄ�MZo�qcBޝ��s�h�F��u�g�������/�:"�#�%m?��OR���l����fi���<�ܹ�=���uZ�����wo[Wz��^�v�n��('"����F��s8t����}v;4z�ze�T� c_nM���-���'��EV_��ӛ��N�A��_{q-m 풕����c�R����g7��DT-����V�����������/J�.���2�@�(�CQ��6�oe�k�2˶�{��
(h�i�QO����^�RA�z�*���M�h�W�\KB޽���/7B��a�p�b��x����[tS[�`�.FeŶȞ\��
��֥iG�旛�>�\p��9�Q[B����t�'���
J_H��?sx��|%86JK��);�=}�ޮP+�U�끮�^������'�m��KD��Q!99g�BPN�TOD�a��;Z�C�*dn�^��a�v��`�1ŸE���;�<N�}�[Ӫ[������fi��Ú�Q���;[�Dj�zR2�耤���<�)�G�|����	� �_8�|;�-����ʍUj��)؜�V|I����>�ư4RG2����Kj�O�Gc��;`� ,�>���@nP��G���3��ޫ��!����]�QH+?H����ig~�0KH�Τ��h�$��vI��0p���I��z��#r�7HB�������(��,H3�J���;�t?����h����n��é�,�(�����
�gY!��	+"B�\}��#oG�l��s���}Q�2��/T�Ё1ڕ�ջO�T�`����+㮖ɕ��=Pm�j�QL��`�:]�%���t2�������7X9t����2>p������v^v.B#�5`Ʈs�j��<�;�Ho���*S���}�_ב�o� �x�rt�TC]��u�5���F|��)�d/���v���>=�ۚ�|\�w��ũY�J��1�:�<��ɉ4����� �H�7���O�O�NO>|<�N>x��޻�������'����[���Ǔw��s��    j���[�\l�$����+�Bqta˦Q[ uT��b��3b�٣3;��i���?$���Y/�f�+��ί>�|8�������[9��W0�����>����0�'Wޟ���W�g���4bc�ʎf���ƥ����VzA�R�1���������:�>޳����F�l��2X�ο�_�����>x�g�N������֮��2�z���?�����O��G�T���	���n���ݸ�z5?ʮ ��ڬj��k�V�X�{��;p8�g���.g��q�����}� C!���vk<i�
�IH��ڡ"�����8P��n�tf��V�3�����i�����ù3�ƶ��vǴ��BWV6J��M%0�k�l��=V�|\>�eI'V�Q{^_��F����jY�g���-b�D��n`��7jE������ӓ˹=|�z��T�Ѐ^�Oq��fi��f~~����Z�Bd��NQ�BR���0*��$`��	K�o�s��7�3*��خ|_]Ti�E�$���z�Q�5�w�B������婒��[��һ�u��^��c�����O�8���<�	�x�l�']�_�|�1x�ĩ���𰿈G�l��F��Y�3�����d���K��$d!�B�DYC��E�bj;O�d`�?e��ı�+:Ih����}\i=�ԙ���&����F�'��=�[� �8Fxg�m����[w��#��rR�[-�&5J^,�VD�3��E&�F�-�1;�P�<�"n@J�źh�<m���u�D�����B�#��-��B^��g��8���K���/���Y�K�q��&T�ץ�_�Z@����j��-��>�ۅ�iw�R��y�7�[G�1tz�(���+"���CN���_9��9o�19��)7�q{6߰�\~�8��;���iN:�':Hw��6Mk�=a�����C�)`�m�ԧ������	�r_����s-���Y�f�#���j���x��V���y�+�i�'uۜX(��wԿ�[�@O�ڶ#�ȴHm�E[S]���]:�۝`u���Eܦ����:$����'�����rp�3�=V�5�,��cG���y0J��k�c�܅�V:�Ғ�9m�5B,��Ro���v�E�����G|f!��n91Y(��v�+T����k�����L*��ϣ��^�K�lB�%c|��HvN��CNKy�f0~jb��}�53I74^�y�sT��X��^H�\%>��iI#��X�˸�b'a��=BxY�������~Ţ�/C��4��2�0Q|�QΣ��$��3%�������~� �$��_�&�΂R��_a�<�}��p�4P��@
x��
�v�� 'Z8�����H�k՘�@g���ʻF�lA,�Y�"❽=izz�O�Qc�HJ��Ȅ�B���O����笂�׷�;���h��F9|���=��F��%>i,K�o���&�d9�3Gv�{%�/c��0�boK�n�I&OE�Y}���X��k8_}�$��R=�<�&���M0�����⑰2��!�"9��M����T(R:�P�S�&{���MGl��+m��_Ԗz���]��=�C�N��~��r��PV	7��v������=x�앧oF� ���
�F���l�����c �Ї�=��_^���9 u,��'�ߟ��/p�s���\�~�^j��׏�r��b~9��@���f~|�a�+��	^w�\������ݧ����n�����*� O�=,��A��Ǔ��N����/>���7u����	�8���O��]�?9��V�/MC����9�����jfz���X�������[�+9ߟ�=9���䣺�����s3ؗ�'W��������odlIN�?����	o�����%��ɿ~:�8�>t�U��>������wyruq��j���d��mV ��[�,/N.a����>^�b�ք���	��sw7ə�=6�:ԣ3zYͦ};$�%|���լó��@(*'�����1��fY����	������
@�c��k�4/G������?ґ�a�#�Ƣ����5؟�j�iz1\�����'q��B����)t� � C_H.t�s�=��G8��S�F:��ZnT�4v��ϰ��lu����Y��2v�FƢ*X��ɗ�
��D���O�R;U��i�7��C����|� �)�(��=_Șz߳�c+E�>���|�`����K��ߞ�.�wHO����*_��~Ő���Q�7���.�܉G�<�c��Y���m�!�AG*�C�mC���gO�"xཨ�d�I'�^h������N���S��dd]t���9����E�	�3�*z�h�L�s���ǣ="��l��爆�U��`~R�x�*�`h�^
;�ϸf��O!8�l��jn�ң s���=V��/H"@��R�q�aq���y�~��,�r�M)L�3^�w@�D����c%z�&�l�FW
S=k��[]2�r�Naڳ�/%��q**?d	f�����Ҕ�I�/kV�͒>g��ސ�L��Δ�{6δ#�s[DxlF���"��0}�ô��S�a}� �tX��$~E� �<�@�C�y�vZK6G���_���ؼ_����N�/8�ˍ��˨ց���DW��R�R)�{ŲӕDl۶��0�����[|���������@�Gs���B"@a�σ��O�0E�'�/A�w<���`P|29�O2`v���� N��j3���M�l���.������i�<�%:ک��G���+��
j4A1R O\R%L2?�A���`.�j����BX�c$��Ư�Bިv�Iq_$�O��
�%0�I�T@A$dE�iU�U��Թ�U��i-��	2��*%�����Ο���$m�q�O���"4P9d�ݎ��=K��L��'B�����}2���Cz.�h �k��o�9�ď?����4��Y4���3�-�s�2n��F���̢�|�c�0&��K�e^ O����9o��m��x%0X[�� C�����A&h�^A�w�GAu��nÈ����`B{JEUb��)�7^�n�<����*klt����4f�?��`�I߫H��ٸ-)�CfKy�<T�9�'��,"��*I��7�� �|B�z2���h�E͍���RxcVcU[u�O���Z��	�߫��nj�MrUn�yĬ��<��0ɔ�o0;�������n�����)������s�E�Lp$���4/ʬf�I�tk�_���	2�f��Q�������k��� �r����s��@Q��ŗ/�Hk���th6,�Gm��<�M� X����R�uQ֍��)n{ص׽���i���TY#���\��*uM�3�@+-�@��	Po� ��>�Y=�Cvs�/ጹhوt[Zy�*���+�����M�-)�~'@J#?`�qD�xXLA��|�U�ч��`5���H�NL������V|�q�٪��cs����-u�+�:hΰ�̆���5::������֫������~��2Uյ�30ei!@��U��C�/ʊW�35˟)�X\� ��%vp7�ec��b&���ϓ�0�2�c��Ga�f��.�$��p��1wھ�� W�_�OV	?+r0�)HqA9A�0��b�y��돔�gvqa�:}�ُue���f]rI��2���W-;��fؕn'-����y��;5@u��ٟ]�E�� �)����"�6Q�G�<�V�t�'�$�;�A%S���'n�n�f�=�=G�\ͮ�ZՎ:}���%@O��E,����ՖJ&�	fP����� )���9�C� ��QR�g� *�8�H2Rk�!��HE�l����p�[e�Z4*��^|~���,L��
�HF�d1���w�N�m�X5�my��M��!y�,a�ccB@z�H�"��<�T�'�Ϡ��5F��0M?7�Bc�qW�|����K�X���z׵PE��k���xI�]6�q{��ۦ�I�$�o|����i�3�DA͡q2\�!�4��J�'��ح0�W�Ƕ��N�VL    �"N��(���0��KDU²Y5ᘃwcC#8�P�]�E�'��P��	������ ʝ+֦�\TZͳ ���K7»�H�9�,ef�!8���1��z�u�r���_�o���#���p�0��\��&3-��ђ2�Sf'��	N�Ԏ,ɐ�(���i��D�����!zf%�fhy���`�+��Da��!���9�GB�<x

n2��"�!��t�+�݁y,%��\(n�k�ܺ�y�\m\�i�l�5Y�HÃ�Y�|�m �y^;)�+݇{i��9��=�I�҄p�b7�p
`t��i/:1����K��{�3���s��W�l~��>���矲����8�wYY�W��>�?��s�_��a+ojk[qv���.!�ș��8[��-6����Y�\�ڡ��k�a��5��.2l��F@N!0޾�����yw�ǧ�k��p7�Jwh2�����I���<�z�$�@)*X�ʃg�sy{��.��U������Vu���Z �T[��i�����`r�zf��ΕwI-҇���n)�o<�ɛ:~����7{�4��#�Kд�����~1�q'������x>��7��8���X(�v�=�gb�d��������e)Z�p�4|���n��o�]~s��3�2��m�9���f���/���E�yp����tc�<���?-�+�� >`aEn��L�N،D3�p�c`�eCW��숅������b1��*���B�,��yH�=�$���tƲY[I��Hl^	��C���u�+>�w�
���E�޿��,�3d,s�O߱�xءOQ�_�IW�ֽn��'!�?�h����Ϣ(�ЀTi��`���lf;��nY8X����Y,�`1,��r?M���
�*�94�CL��9s�$:O�hnU���1�[�O���M�zpA�Nq=N� &���7����^�Ve��&�nl@�z`+ �q�JHbj�Be�(��d�;��T�~��*Z3:��6�,1���6�zk��AИ3�cފ�<Ԑ�g�Kp[�r�e���oV�����:	��b�����܇+v���Ż��h�e�l<��>���xW`P������&�y�D�ئ����z	�GIZ��o�De1�%s�]����Df�bz�je�3����Z}K�^�Q�nJ``3�v��N�l坃/���[Q�zBM�cQ���H�����Z��"����ӗ�Ԃ�2��@�F�G�#��䵣�}h��JǨ��;��z�X�Я�j��6�*�Pb�$��-\�L[�Hh�Lõi���J>N���=U}Cb�k��Ѡ[C��ut����L���B��%#���#u~��*W��*O1"W�|�TF��e��dwE�̐k$�<"]�Re���!����[�|���]Mia�q�3?$A��QU�eE0vE"�»�����&8��.� ؾ�05�V@�O�=~����6~�l'�V��ڙ}�b�sg����@i[�B��`CH�-�Wj�� ��2b~�R
FC��E��Y(� �	�|�}xD�^x4��	cשx��t��-�G�*G��A�Fx����b������[�̍2*?L`�ҏH��2A�v�K���s�G<��[�ʔg�/@g�~O�	�Ģ>Y��XA+	T�F,���.eb�t�XkCc�������=d�P��bIl0��~*����far{�Kb�����n��:�]�5��Ӿ@�GO�L%�:�!B!gB�	TG00}��⧿�)f�'&��#}�'&˥�Y9 �ڤ��9%s�=Y�5�t��i.�FMJ@����]ScK
�`E\�H���ɖW��i�0�YUa;�:t��p��T�(���<.��Dd�$	�a��`�`�ϼ!h�����f��3+cBlR �/�}��Td,X8q4/ȽV#g��6E��}���B��n}?��7�F�y�����66R��B��1C�ByO񪌘�V��~�zw����;2��^����]x�V�[�������f�2W�JVNq�#�T+(�K]�����y'K�[���4��Y��+*�+�凷��$�:A�5��"������c�U���W�~dq�'�bYX���;�Dqe��3�Kp�V]����E�o]v����O'W��b�ɡI�9g�������9����5{�Ƕ���{mIﵕZg�E�uSo	� ���s0�C�C����*���5���iO?�~�13+�����Bq�`���s���(Aw��Oc��ai@^��L��f3�Z���Xi6�e!��F �a|`M���+	��.����UTtݭ�7����k,�$���8Hw盽a��P�	 i(o��
�9�
��X�1h֌e�N�,):�� ^�d>!�kU!:�5^����2�fZF�}[{�����Ձ�O����%C2>ػ7j�/��Ue��k-��خ��}_����P�=	���jh�u��e~��J�J����p�J�����-�r���v~~q��ɿGnwq_~�vw�5��r]��ۻ�*2?�{3/�ӻϖ�-��A�{�C,o5zM}&�Ak/
���h���~9rJ�@m�*���ϳ�/�X�y���� �"�����j!��ψG嚋���:zo�i�Ky7Y���f��v�_p$BK�`�`�H����*CA����HS����n�5�E{`�ض��p#k��-�U���3�Ơ�û�;��F�ˠ����`�[��V���-�%��6���B�di	c� ��`��!�i�
�^�k�%d��s��"��|"���"��"va���G�+��X$9��E��g^�h��	�_i�3Q0"Q��	�rB��=��@����(G��3�� �pJN��b�O�P�aQb@NA�D�Pe�R��'���&��?����S|]V/�m��{�L��-�I�[�Z/k跬���<���b-S����BG��H?���{`Ȍ31�kč�کFfʖH�m�喀����u�"H�� �ATq��	����U� N���50M�P`כ��Jx'm�V�hJ������D�]�c/�e������+vD�gsx*�����좯����>t��m��FV���H��r���/3�� ��!!�i�B@Sy8%�,(�V3(��H� ��<����ƶW�jXw[z��_��:�GZ
Uh�pԎ0���*� i��itA�v�XLָ����Y0�:�N��`4�0�{ھj���4A*�( ����(��T��r�����)�H�����m,Jж�]�����=��PJA^7*�	T��N'�KX��܈����r�N�$do}��AT*m�����8��Ξcnl�H��QYHb�b<�#&8�E��/E�lԖ��6_ԋg���sQ"��W�OQX�9|�@O�Y�U'`LP|iJu6��8.2Iy�����:�:n@�,�z��KЭ��8�!��k�F���{�L2��ѫL^���rI�.�|ŉ�N��&~L��d9�91��);Fs�1�#�`р���E����U�&h	p����%E�π�S)�Af�Ii�E?�U�W�R����8θz<���2i���f1�Ψ|v<��U������,����>������/�J�#�� ��?�6,}w�i��"�I��c-A|	��(HyPyL �b��	�b<�AQ��+�ɷ���ͭKjn0j���W(�E"XU���5��Z�}��KIc|���;0�|�z��|'�9����f�=spyE|�aݎ
v.T�O�(�8����<���T�:�z�\!�����2�cR*��yk8˛��	��pџ��;,���%�1n��Ʋ��F���^���6{�ȃ�3�
��*�}LVEQ"":!�����_�o��,t���䬊�Ո˴+V�6��u�c�s�5��6�yC����Ru�7ڹ.c��d��uZ��f!̒�☥���8%!!"��	��.��9>�rO�E�n6�ɱ�����qo6���iWjɊ;�r)l��T�X�$<�ݓv̘J�Z��G)�^R    ��^�qX�0'�Z,?o�^�l��q��74NaD	��	�'q�I���U�����9�"�:ݘ�Q41H|��h?!�|���T>%?��g���&ȜV�YqX���>�2���L�QH��$�B��>I6��Ci����F_���}S�Y�9��1le�����k��x�5�V��~��� ���/E.�$LAe)�	V&���^ɰ6F4�G���{�ǟ���4���X>�}��������f�F�ď��FG&�Ɯ¡�2~/������A���>b5�џ�
9+6�0�*��<�� FL�د"���4)��R�ٌD��C��������G�(����z)rt�U�=F��=�2|��U���ww��7o��׿ַ��������{��f�q���1�D���#�cm�����o{����I&�.8����%�}�~�X��j��ge�O��O	�e\V���ǒ����=���/��_t�4�Ի���h����X���Z�qq;x���Z���)l��^�h�-(�]v̮�I�ѐ����A���9�EX�2K'��,L�3o$GA�cM}�h=���r5�[��T�Z��W��󷯽W�֮R��ߋ��;��jy�� ���F�}A#�<w�y_Ь�>���������N)[B�ܴa�-���HHbu����[�O��V�����FȄ��>�E�}�(~S��HQr����g!/�<��0��p�#U�0���Wky�>-%7Tx���d+xŏ���$ Y�<���T�ujbp%z���1K�yHL;in�Tl���n�$�r�h!3��F�K?�ɹ���zL����ru9!���y��O��p���h��*X�9�)�|�P�(x �gE4��7f)����i�����fS:)U"J��Ѳ����R 3���X6�6��}�=4"���T�%�E{3�r������{�{ta��[:|ړ�=�C	dq��藥�������0g>�A���	�����8��8�r�*j��a�)�8����a�|�!�*(ʢ�pʂq����w����DV���u��ӌ�e��Z��N�1�s�2�qÐ�S�����5���y��͊R�RI1�%~�҂FUL�7`b2{��ADH��b:���w9����`H��{�w5�_�9��⧃R�q���	�B#���xe���"�׽(D[ޣu�Y���@2�����ξr�x��<��/���(hH%B�^�����s���s�1���݅��Y{ݩ��O�B�/{��"��R���8e#��b�@�o�e��P�pt~�2�J��#iL]�@�|��(�,�B��yY�� ,� ֣	��ɀ79��������#�u�".���u��d� ���L�ʧx	�`'ј7&�X�,/�I5��";Z�>|�`�w� ��^Țu2�B�s�Q&X��i�6�j�W���m`e�F�i�v�N�%��#p*�J�Ƅ��c��&W~�<�@�,�?��w�Y	������+��y��<��vI�]P�bI��d#�[(�BIHq}����9��9/�a͈�w$�i��*�iNhL�`�j�0Pb�>?"y�Fb��H-�QXé^��VX�3[C���h��#Ɓ���c���g�{E0MG.��H ��{ݙ��e�Qݓt�~��O�)tܓ�������'0����*J#JJ'��O!|�K:��I��ɡ�u��9ٙ��2a\�ܬ��%+��u�����t�pGvyC�&ď�4�YY�e:���&��"y^�6�81���=��L��mď�g��5�v*��絬���(����ʣ���/A�~���VE�e�{]���=C�ś��i���ّ�;�Щo�#d��F�!̺��$]�Xɍ���f!d�l��fټ��j �_e���i��� )��¥�P��,��,�b_А�QE�Mq�eٌ�XD"�}a_��6���M�-ZEt� ��q����%��������S�ՊZ���aK]xh��������Ѯ[6lŚ��>м��Eh�@P�yQ�~�ȑ$g��Q�l,����J�%��o\��w��A���)�:��5�tv3i��(\R/���_�|hkiY5?���a�^�Ш���U�H�QxO5�<{��^���qmj�g��Ui�;,�T ��0�����6J[`e[(��yj�҅�,k� J�)�%�y����,ނ�lj��`��h2h47\j�(�9	�a/Ӣ�#�-J�bS�,�V�i���/e]5(�}�n�����	��	,W,T�!�3?M��/�0&A%����[`!����.�Ɏ��l����u&a�R��<8o�T���_��YIis�i�V���.p���@�0�Ġ=9Re�أ��3�d1hF�
�D��{>E��G��}ߣ��
��={rvr��L�V���h�������[;��&�Z�u2M):����e`'�܇g!����Js8����$�%Q_�Qt�}���v�a�[��@������/~����b�p����U�������B��ъ�t+�#�^,F!���ݮ�	0P�0Ĉ>H�\TȔ�Q	��o��YA�y&�T�Qs�i��4�R�2BV�1�(;"oӑ��?^(�,��d�������
1�ʙe�if��ƣXcJ��पA�A��k-��/���S�m�zH��57�O�>k�1ƚ	W�+00,qf{��4�,-*_��
�I�%	&YI�h
"����j�2��/��Q�+aa0�� �2��Ts� ��C�`�m��/������%z^1�}{�`�]��ڞ�;�<}�r/���!����W&��{��H��u[Yf���X7�ou�%�#��<��۬%!�|i�J"b���U(2E�q
�N�+P x�h�	�E�'D���o*�����xH �\�����?JL��Es��]�a��V���������� =4�3D�t���XL��Y(�oq
���h@�����o*��Rʋ8C3����!.9y��"y,�38��#����&j�ҋ)&.��W�����я�~�����x�t�$��wk�w0�����b�DYה�޺;Ci���Rh������!p��C{a�+��_���{��T��/1[�T�U4ν7ޙT�ΐ�ri����[X���PJH&��a�p�q�v�Hhų�̪	՜i
b.��\��B$��z�Z�� E��&�a�NɃE%�/+4��j�"+���̢0ɂ)�HW�	�-�k��1���7���KG���=m����T�ү.NN�n�"ˇ�-���_�*��c�)]�F[��9й:�ܶ1RU=X��K��SK�=Q�0�\F~ǥς*�I��e�%e'�
�s'����X�r�S	��q��\ܧ�6��/������웒J��N!�u�,�O�Z��wN���d�V(�5�-��!0m�`�ed龮��]�O�����R��3_�	\�@KE�QYrx}
��"`d�u `�#�9yvF!fZ!~��7[VwDr�h���:ɢ�0�q�)��r�l�s�����4�����Du��s{|_�pL��X�Xϳ�8�q}�j9Z��^.���b9�&oK�]ï��;J�5�H����U~NCX�A�e�N��ba���;�k�\A�{Q'�(����Q��
@�]���UP�R̑%����Z` ���ڄ�X�ђȀ���+��+x*잶�d�*ag�f���KP�yk����RI�26�[��n������w�cֆO�yP���Z��=>�RV
KǏi&l�+�g<���=���)y���__$S0ҡ����W��Z�Y(�h���/�F������6v����L�^X|��M�!�V`6�#a2���;�j�[�U
��rӱ<����i�'U5%(E��7�w��d�X3V�\C$�hU�����$\�%���pz@G��H+&���d~6�'�]��5�z�n!�����a{����θZ�<g�S�s��{�Q��/!���(��pT�iLx��	<��Fd$xK�c�P���c��kUc��q{��͎�y8%��Y4)'�*|
���P �)��>��A��,-��"hF�Žŋ    �����ʺN����E�=)�?�a�`_��������$#y2��)�g�3�x�q��n��V�h� �.��2�Ǭ��7�ѵ0q�����Ũ\x�{���m�YH|7?g�-VJ����k�}��ծ�w%��PD~���w�b���>,HR*8�&L3���P�T�{D[ss���xw}P�ғ�F���M��E�����ǧ�Љe�s�o;��^�#��@;��{2�S�Y!�ҋ����s�����v�y�>�ѽ�ߊ2��3��=�#�)!��b�(�I8z�vh��D��,��D�`>~��%�.EQ��`�H#�>��J�Հ�>��$y�*q���l�6��t �f5����k�{��[~���일e��v�����+��L%xޙn��U�j[��+U;�J׎z��ӃCo�,l��������'[[ Zk��z����]��^�kɘ��Wd���ƨ{�"t�Η*`)c4˗�G��	���oeef�6{�H�+X�W����LI,��� c^څ5��?�+B_5��#�)�V��������[�s>��lR�i^J��5�B�;hS�V0����D��K����|V�JBU���P����{]��=른y�yP�ٔ3�7~��f���4Z`/�^c��u3����>�]�9���<T�����_�[���Yɤy��͵40�έo`E��ha�X+'S�ҢG&>�. X�pHQ�($�ݽ�ߕq�醺���V1}�$�4�i �4��#��������,(/��`�#=��-�,qߑ?��5��rjtU�s�Y,��O��*�m����F�u��.����7�q�9�,�r?	�ʋ�[.�#^��!�|M2�TN���]�Gt�E��|��#�t����Z@ރ�!\���,F}����N3�c�F�mXT����8��i�� ��=x�fq�$̪Q&�\b\8��:���#Nq�~��r67�\
 	�hU	#��������\�����Bj��Hs�JL�e"�(��(��8˒�MJ��=CgGA?G,`m��{���|z��[��
���om���+�x�pd��v��w��4-��f�$��D��Ņwv���x~5�@pHV�u��#u̥x���ˍ�%�=��:=����Z��8�`�d~�'���Q��6'd�I�kd���-;.�){4�S%K�����:;���
m.��*��M��Y5�׳��@������~�JcM��6�1U��d���2LE	-$I��t'R�x���0�Gl@;������R�P�D�Yt��Vp<�6�����J��
���A�^F	-K8�&8��>�	���(iu�#��*�$��Qn��Ү�ʽA���*^h\�tV�m��){�]�r��=��L��ޙ��p~����ǉ�I���5�=�V��c����^?,g����w���F�o�����$���{�5�{�����D2���Y��B
���Ҁ�,'�cU^�)���0I�X�)�c,:~ؕ%��-%�oL~����N[�q��*
�e�>��0�)�S1T3A5���lYe9�q?+H�A���(�d
WE���՘
��D���EQsW6�`��-�p&��R
`Gﭤ�y��q�'�i������yc�Fw��$�|�4�K#�2����Wc#I0)�ϒ��Y�`֧m&|Ȩ3�� Hp:�yY�����Qj)�ǘ��{K% (CG�[nKý�aV3{i;��vP6�Cc�)�b?c|	A��eSL�5"�#�G�`�?3�@�ǿp{���'�v�Q]&��R��[˓[XY���ѡ݅[Pךh�29_���9���~+	cv��2�M�]�2Y�N4�]��D����#��5g����m���J��4�R�c����q�gU*0=H�t�.L#Y��R�w�C��(�},�J*��A2�^$��P�I��`�)`J����*�ϴ���nh���
��.�C��v1n�����:l�7��"����a���+�'���,��J1�~�J8�S&�"��
�����>tK_���V길�X�K�����mW1�S�遉ΆAYt�i��{�
&_�a�Lם��yw��:�L��P�&��LD���,tӛ�z��Y��f��>��������78BR�L(���"�u7s���i��1�y�>�ڤ͔M���vC���ޑ�����H����D�N����Q�%�Ʈ��;I�������;�Tk�uW߷<��w����D����L�����X,+�'X�5�p���IՐ�0���^ G@T
��4����3��5�V�N�O/|���&��T�+s�a-}�p�\�Nҟ;����0��.K\R���6*��2�U�8<�4��h],QyW�Ğ����5�|�Q�<S��b�̃�������G<wq���ݥG���I��\H"��o?�]�!'��
h��"���W,"y5�HC`\8F)�ǽ��o�tR>%���ׁox�M(�X�s��]�!�U9�7�f���UwwW�O��4����e�� $��$����\ ���VT|�M<o��؏��ӿ_Ð�+�ʦ�ރon6���|����!�߼җW!D���5���A�F�KH䴨�	�dY�g୓j!D�H[Mv4�s-���#��-Zk<�(��+�٫��P:�z��	ڴ���$�SS�qI��p���o��S����M\h�Q�>hOmN�^Ge�GI�!�[9��?�A���cO������rm.iύ�W \1���Ag+�WS2��AB�	v��|S4%n@����E��pb�2��7��	����i�s�+! ��ŠC�-�X�Mu�˕��mn����{W�J�Z��=�A	MR$Gv��g�"���쉼�kPqqv�&R����g�f���z)F���19��,j�ξ�0?��{�'�Ⲫ�,��74�5��Lʂ��{W���dڏ<]��1�A>R�W��$p��Џ"�� ������d��m��s|�he-�V�݀��4	�����3�l�=��p*􀀁���\{�F}\�4���o��5�vF��A�A�b��ɧD�A= d�`�}���z�/L��D��1�ړ�w�=�,���~�Y� �$�I%�(2Ɔc�G�)�m�:�ƀ�1���s0Z?�����5�X��A���U�Yz�񿏿�TUٖ��q:�ri��-!_ҝ����6�7�ǖ�{��k�Gt�:���tJV���z�+{jx��y&����f�Jc(�`�ӡ�3�f�{FE�~��Dkur(��[��S�5�zW9�+
`�L!TY!�$�����FAOa[~�Ɩ��ض,�M8~�+t!� �����
�u�R[�ؚ*,}C�P�i�yӸ-u,5�)�AsD��EkS�]Y��~+3�sm?�}�ŭ�T����oM�b��e�����A fyJ	�i��)FkGL4�����>��H ����(hVf���O���Ƴ��0���C �l~v��͋OcX�@��>��y��Dx���A�*Y ���"C�t$�Ák�ـ̍�,Q2mYbL��B�>.EvemЉ�i����wXy��� +�*�d��.��3�Er�v�U�SE��>Y �+���=3��N"�i���&�60��o��o���b�	��a���*��fS|ƕ�p-�������U�K2Ņ�b[)A�9���_��F9K��%��8,���3��z0&��{XH �RicpJM�hJt �0���|F�ó���`�M�m��$u�ED�g�33���ǿ/���I(��%@����ZϲѮ��M\�e�-���ob�:�4�����iUd���Wn�i��M��f�d�+a�N�>[4{r�f�@�IFAAJL1�WI�c�.�&H�Ť��A����_��=��aB߸rJ�<�`�*5F�|S��I<Ko1���JL_F���	0���"�A怪M�$�y�e�$Z��q<"�C6���?�-R�%(*��bLWJ�UH�-4���k�?3�׸�.lkF%�#�9�b�, �LQ?��(Eª���e�Xt��$D��Q'I��b�H3�&E<��ˊ_��34��� �  ��F���WG?��O)��Ai���f�3���Pf�@sc2Cf���Vs��з�'��Qƍ$�u�tY��*��Ua��O�l�ɍE��A�k�$8�O�[�*����g~�混c厹����_��·lg[o����s�t{*)��S++ݩ���P���A|�_6�{T^����Ǽ˖n�$��O�6#3�͖�/�6��)������Y�ڄ��k\�I��އ%�5<�H�'bF�(+��
Tp��K*8��V~�:<k�-v��_�*%n��o�+�jV���	���I]üȹ�p�Pҁm�I�	��"b~��Z��`�1�K&�4/�\TR�����&W"�)D,���,-�v�{}��d�ڬ��F�}k����Q:��n��`���
<Ŏ�L��\��m�����OY��§�0���χ�����wa=�      �      x������ � �      �   �   x���A��0�s�Fƴ�|b_0l��?a3����H�[o���$�#ȃ�XH�uG)8�Z���u{}�,/_�I�q�D�a�I2ğ"6�~,?/:�'y��NA�&�1���>��E�`!d��j�l=ep|�^z,o�ˤlY����x��M����׫Ci�P���&I9�\�̍y�+�,�+�~����e�      �   �  x���˭�0Eך^P��d��m$�꿄hf�$K^	�⚇���+}��t�h	Ǆ�>���Q�����+��~�x�L�����,�,&�R��W�S`Mm }$��`p�)�洊�>�0(:bo���Q}iP��󗯜���Bz[07\���t�.��0�.�;[w�L�����k��c�x���z^���{����{�-�@�/dU�l�W0?���bRJ{+� ��$K���M�7 �h[Ɋ�x]uɘd���(}����n�.�*��HX�/����?F]��-��[�V;������0ٗp��.��A<;����R��{�;���RL/�6˹!�g�Y�3���3�e|Ʒ�ׇ󞁙�M��z�^ ;��      �   �	  x���I�d7D��w��Y�%|o4��~��ʯ�l1%2�`�/��j�Ō6��U�iҝIu?|���/���_����q\����M�-��u�Vz����~9{·�|�݄>�z+;2�v� ݧ��˅s>>���&�:�]FC�&���jy�/����}>��LԖ8�{c�m)�Г����9�������yM�h-�W�i|�_��6��M,��;'&���T-��y��Tq��Hay�7�U��])3�߯���*�B1�I7m�`\�}�C�=χ��ۻ�J�k�P/�瘻���_ğ��(��1um��0F�xW�*@�)��X�G����	�FκI��%���'�Zq�{gMO�ڈRWC�c���lS�Y��!�j3w�S�c�����[�Հ�4g�VsF���s��ڗ�?���ѕh� �҇�-����J���2�B冔ň�L{+���Ϯ���3Y�A�>5��K��+	/���2�s�A�F��r�ڍШf��f��gLo�� -3����V�S��ZN���8� ��1\�{�Omm���D��R�*��/c݂��?�yjE���9��j���M�lV�9���~�� ��of�A���N�_mI�ʪ��I:���n���j5Z��n��Q���Cў��~
���@��������+�� ��(&W�:�h�8NI�����K� ]��tn��i.�ړ��u�A��`�����Flf�9�����W��tw�����!����|���+� ��
�I��Y)K�
��W��t��GR��ԩQ�|@�Q�^
��ԠH![�$�@�^���J���� ��R9�)9�\�����ҹ�b�nmm�1�56�H)�]O��Y�+;��5�BfwꫵjKx{)�[�[��(�E���(k5�9.��p<�ٰVa6�s|i[t�|�y|�.V9�ͺ �>�P_$���5�c:Xoh"Rh��;d�n#���{��s񕔳M[3(j ���LQR�Q�P8��lEL�Yl�h��ƈl#a�V^�,R��0c��4$� }^�?;����oA��)���(#\tK����I�2�gS�ʦs)�[��{�?���V�����v,؝�	�Q��pQ7֌یkr�j��,-w��"�Ԝ�� j�'����;��O �*H$]�twf�X�_���m�>��JG���aTDx���'"|W��[�]�𸤈p�wY�h+Χ_���\�l�qr����=�bJ�V�����}të�b����Z��{C~�[�vY�w�jʞ�i��]����1����Q%�����?t������ҽ����8_�aZ��?�	@2vs����S�`U���]I�8��X`��ȞR%Ǵm2{��7��@�A|�F��6,e*wU�7p�N�כ�p��=4T��l�}I��'?�Id�lK嶮F��`z�f�z����L/m���b Q�E<+�����4o�ꨏek�ê��kٮ��rp+���]O�0I�t�L��߶��2��#и�6�Y	4�8�kJƲ�x���� -��h���2s:�I\ѻ��4��0r��"���Ek�b�����43YM�{F� ����F�v�o�	8HW�5��mFp���s��aO��r�����t�䌓45Y��^�o�r�FM	�>BI���~!���^$�((3�n��P��ӡ<�7� ���Q�=MJ躟��KeaT�� ��(�oh߻.��W�K�U��J� ��O,'3����J��RK�W@:���������C̱$�.c/�PG�-\�L�!��UU��v<@�B)�Fh��v�?�m�D�\��n-{m��:���������=L��$=02�*��`���ު���F�ǝ�l�nF�۵���*���2�7�����Mۥ�Fw�ҕ�ۤ<wk@��Bњs#?�D߀������&�Vi����J����[5� �ۣ�M� �w����6t�_��>H�hՔ�k±#T[܂����|�	��	Ěp��[�{�� ������]�.%7lc���5� �4v�ȸ��Y�� �c�}�~?I\t1��M�3X������ru��q�>P�)%#,yh+-el�Ї{>��?��~́�����f�Q�2�R�F���-��+�,����G�]r���;ވmt���{�%���*S�E��ܿ�<p�=�W�vc�i�=0~X����^��M'���l��Aӎݞ�^��_��<�7�ͤ��6�k�SBٳ`��+����y���S]�F�o�������	��RM������{�
_x+�� �zV��rSa������1�V��G0Ժ�i���p��dp7|??J�aY����>\ǨHK�~�q�����~)�oD��
�߀�2�RAn���	��ls>�|mO����ϟ��JU      �   �  x���Kj�@���)|��z��1��Ȯ��Z��{��{�\���h��B`�@�g�_�Q�a}ܼ�qx��~8����4}�����H�O�0}퇏�?TB�+{�����o�wQQ)�xp�S�B���1��	j������Y�x[hC�:��1�C��T�e�]�Rv���EJ��z3CV��]i��5�J�B�]]�K-�>3����)��vC3���.�zm$�3¤�j������J�l��Ц"�%K���Ce0��Iu%qI�1T<>l*�eMCM�燥��d��up�T�YW��jw�[ڬ6\=���p������"[��0��uk�H|�=�6X���[?��Wo&�G��b��_M*�����=�I@�~|���a������m��      �   �   x�E��D!Ϧ�Ճ�_[��_��9Hs�@P�L����z`�>�L�,,���Y�A�� �- ��l"McE�j��&D�6�-ĝ��b���!v>W5nf�X�2Nh���T���cN�];��>�c�`v�;?es�ߧ����N5         `  x��W�jK\��E��Z--��ˋ?�l�	��$����6��8����*��!�W-!u���IFjq��UZ�ڵ�~�z<����~<��돷��k���������������/vA�
}PK^IB�dZ*㐭yʞ��~�o���?<od-ߴeʼ
ɬ�<�L�Z�!�E7hG>ʳ��n�8ej9��;�x"I�K�s��1�h�_�uv�����(�L!�����Lʅ�+Z��}�-�"[�T��j�a���$���k$^���´�/I��k�%o�$��+�B�e�Syѐ.�����|@��Ļt�!�:y��Bt�m�06�'��A<G��0���j�Tj�=x����?!�G��)B��1�5ɻ�K��G�b�8��R��Ȼ3�)g�]{���a>���5&��0�ldHE��¡��w�����,�4���h o5�Z�lh����)ǌygĖ�VW8���w�����&��1�V�j��]�0�cG�
�7m�Bp$��@�~�)�O�˾4�gY}��M�N<Y�(��S��J/��t�~t�2�!2̾ ]��L�,�A2��V,�������`�-�����,ߩ)���D�� �4+���e1W���/��U�|��W �5�D���*:ʄ��q��u1d�	�.A��B��n�B-�A��n?;2D�5��G�Tx؊�V��C�npw��b�s"=$L���iu,����5W���� ��1q;b�>\�_̝�M78�/&C�8B֭�Y����:2}#d� ��x�� JJJnFM�7�������,S��P�Qm���Z�����wP>�U
�[��g��Q�sF)�P�l����G�V&!4+���FAK�o�Ӯ�8g_j<Ӱjk±�V�3����)���"�Ӥb*3[oLc+��^RL*�lP�T�y�e�ȕ9��a	���ٙ�a΁L,I������K<X/l)�n�n6Q�,7����yc���2�e6;"✪���Wv�<��kܙg�Z�Z:�� \���U%/^�پ�@�/��ga�!�Y�(X�,l9؞���ga�!�nm�/"���q�4VJy4���"|�������>-      �   �  x��X�n#G<�����!��z�&��-<��2�K=\pH�����8�9��cM��f�]1���ʌȤ�����~�!�Ǉ�y���~���������8J=D�Z���L[��ZW\����RR�T\I�V~�j��û�#��T�:&b)��`�9j��<e\�8���'���iF��$k�'n�S�ʒ��|lލ�h���ǘP�䕆і�Օ��B��&־�O��:��5�3�	e�ҵٖ���y?c�Z���t�I���=.���YKń��WN*h�%6\��:;��S6��'�Va�v���E�ȅґ;�(�'q�.����#6��P�蕏��l�(5S�j;U.��t�[3�c|l��~n��fl
<��9UIѧJREN��hd��^T���`N�XEQe�,�L!$OڧҊ�Ť4��k����OE�~��o�ͮ��1�TŸ��(�n��Ш�Se\pv��(&�_b��
�J��>v�1d�)�٢v�]���S!��+��ˌQ���ո��,�jQp���L���x��R5�9a��9(��NZ��d��)?fce=�/���6���&}3#�b$�,��B�b���:�d[���V�q�	��a_����R�Ȝ%U=����:�-H�֣��
{��iLhc4 ��������U�A�PD]E]^�|�XL$ӌ�L�@QK���lJ�2vK�H���������+4���.��v�������~�����,N�ǁ�-�����I��55nL=�)&��D'
�HT�yt��̕z�� �+�a,�j�	��t��4�9�ݷ���i���D�O���ߔ�J���Q ��D�����p��Y\�*��H'9j0!Wqi�%��4xl늻j��:o�cLh9s�7e(q�$�,d�b�G��F�%��j�$N��x���T���<�(��	�f�����YL7SLh^���n�����Q1�H���١t�V�M]��e
�A�QLh��៰���V�
g�R�6b�I���kh���z��_0�f	xo����\�R�C��fQSL���'����% �0���&\�b�=f͋&�b�<���&���y>��}*���ݭޕ���O(��Y.�DWbM��u�s�@ٙ��.^g=�E��0�	�z��a���R1�25�Fs���p�B駘ু�_�?�__Q`e����SS������-Q�ڄ�u��0q��B��~��6w��t��-A�����seB�3�s��-f���ɊQ��K����U��`=?�+����Lj��,)�.	�o�*5��j������b�͛h��+���iN��fCg��rh<��O1�����ö�����(&x��P��?v���_��N�+�3�]�	m곮�I�X6Kg����QL�Ph��܃�t.�i~j:[a�^;�|h���|���sL�`��~����%�%*4,Q�f*Q����.����~�n.�$�ţ5b�Q�b���k��!�*�e��[y6>�ǫ�w�އR�I�m���M�ջcI?~��)����?Lb��+z���S�~����v��ؾ�'Ș|�n�8�38(C�!{�vӥ�u��V*���ڍb�ʫk�g[�ݭ&���[��ӡO�M�onno��o>ܾ�-׬P��/2X�cĸV�ֶf������I2i���g��M�yL���B���A�      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �            x������ � �      �      x������ � �      �   B  x��V�n�F<�|���'7Ɩ�-H��eL2�iPd �o��O�	���P�,�$9Yni�z������Քe�[�tV�Ǻ1�������.KS�ٶmL�{{�YM�ն�m�ٺ2%=[O]��.6�2� �����`��`����	�aV�����U[���kzv��T�5.��)��"�AXDY� ��r�����j��d �R&��'F��C�����2�7��}��_���k[��h����DI |1��ei�X<v��ܗاָ�c�fY]�~nlf�H$��	��bDxLc�:�4��=�i�sc4��?c1e,��GL;���V4�� ���l�"�[Tm��Y�O�w�P�x"`����#B��vF��7�o�zM�V��4(�i8�X�gP�JXL�}��ݝôa%0VZ#�G�ʬޘבx���i��,�ſYU���nN`�-����B'���[����(�O�Dƾ��7����n����< >�l�J
!��g�È�X���9"�����Q��Y·�nLc���{��I'2$\��K� $5R�ԧr:�$��.l�8H�CR�#L�G3��+���s5��V?�B���>Q�B�	�@Ȅ�1�m4����I�٩ Y=e�㲳D�$�t���{�h���%,xM�Qpl�e�X
7�"<���L	�Sg��~q7�J5ؠna�?QB��>�t/��/�e���qUr�y�*�L�y<�,�"�0M�0L�K����\��G.�	��9-�0��<_�(grb���F��+���(]���-�Ec4%�ф���Əh�(���j�+  g��سP����
�o����x�My��8BIA�a5C��t5_,�rB�W8,�N9���ǥ�[���W)-*��;��c�-]����<_�A��Nˋ@^0��ŭj����M�?��W�@37��+vx��cD?��Z4���ѕ�4-� J{ b�&~�5�]�
Z�V��RNOˀ�&�W][�7��mv?@kСE�PB�+i�_�5TV��@����8�B��!�s��w      �   d  x�M�ۑ�0���q��d������a�*����cȻ>�g�Ͽ�����/<������-x~a�]4D�(�8�����(:EQt�ʃJPզT��r�-�z�Z��Z�(3J�Ϭꔂ�z���NaȽܢr�0T.ޟY.���e��N��9k$(���r�0T.f�ϒ���l(-,jˇ���:x��%A}��*�����4���@@X��k`��*)+|�e������>���GX�Kai�#�/�y�>Z�e������>Bo�3�zFOX�wn��$��$z�Gۤ��WY�#,+|�e�����zs_��URO��������	�C���θ:Q�W}.:ju�����R�8fV�ߒ�:?�z��-���C荲G�[c�#{��3v���p��/���hg�9��#��%\~w;�#���b��8"۝|�����S�-��q��$���}���W�8V>���lw
���<��n�St0��^'<KH�������<*V����u������Jz���x�f�G=���}��?f5^9"����{�O�Ϯ��sd`���K��x��7��<�F��X�b����	7�<@g�/����d�
;O���W���M2��S�sk���q�rƎ+����-���ȋ��?��=�����H�������>~���+��1e�_���_�K��՞��ۙyx�Md(z��b����o�H��eg�=~O�L��'/3�>�9�ǟ�ѝ���l��o�_��J��e�����ү�c�����o��+�z���`���L������&����|��덙��~'�տ���`�u^��n��=T{~v���:�c���G��v�C����|]��<�G�s駘����wi�_�6ڊW�@���x�bI�9^�XY��)�,������}tb������8}��7�w�������'"����-"�|N�u�#؞�������O�{Ŀ����w>o����}lt�������Ċ�~���L?��C
�����������h�-��!�<O|E�3g���!���U04jo>�݂��=�,�9�Rg��:�ׯ���	���G|\駛H�Z~�K���:��S=fW�.��ǭ%�?�������Ш�            x����v븮.�N�½����EQ���h&J�Ǘ���y�R"��k�kU��(Y A ��e��^n�}?����.��p��W�Hc��i�W�^�E������潽����<����[��y�6�-��vk_�k�9����}-I��C����|l�O��"i�rm������Z��^>o_����^$����ڽ���k�H׼�w�h6�å�to�����l����5�.3a�2���y���kw|����|���{ߞ��,[�f/�aם�������]��k��K:���ކ��|{o���l��0��?��ֽf�܃���ws�?��oq����o��G���=���l��0t��/��2a觿�������-��@&�;��2;���������%@v�� �z5���}{k�6�[��k��0th/����޺˫I,D�k���Ĺ���e���'�,3��A�.{���H/�as-���p�~_�2a�?�7�5~�|�P
_�2a� |�F�px5��!$ܷ��
��j�9
C��t���H��T�IQL��k��AB����7��|��y�<��3�.��@�:9w�:P'4j�r���0\�"v�ɗ�C���=w׏�|��0t����t[��<��O�Շo����3�N�� t�,���� _����l�2�`��Ha��e��е�ށĜ�v�i0t���_�2�`���{8�R�˼��q)�������r��������� -;�p@ü�e�������2���v�e0t����x� ���ΰ���,3�VN��� �2a6l��˜�������x-��CW�X=Ncˬ��Q�l��7��e��ؑ�p��04͋��^�e��ЮC����H�-��!Xk��k��8ڣ�oI���g���2�
fY�O����c[�k��9�E	�P,��pg:���\��vz����k��7BU��ѡ��F݃QZ��x-�X"?���{|ߎo�k��H���o�`֕�̃���,��k��9�o@��k��6K�u�b�\�̂�� Z����"���� �����6�n�`��}wrT�dn����B!�Ibp?���
�ϓ�l��7�kG�$���!�.�ԅ�𭉾Lҗ/��l�Y�H_%�+?�����&	i�؃|�z"k=�0�`�v_?[�X����@0|:�2�A-N���NEq��d�w�� c�4�����/|\Ʀ?-��X�n�cM�3ð�꽓���0��s�y�W3+�G�O���p�{�������B��L
TZL�c�	������}��48���n�OK������ڤ� ��ÁJ��@�<������6 �=���J� O�68�H����w��}J��}�ȒJ  �8 "&)р!�E\���%? �+�2��+?�"������f�l�������q��`��y�6��2+_�'Sm�f�V����b��8�H����z?W��������XA����ۜ��_���9��n��V�t8���0+�4��~3�W�����N��J�;_�!����ڼ����"����O�e�|gP���󕟃�p�C��f�y�6�+�ǣ��\5g�����HzJ��� ��ك;bWd��[��\�Yy�36h^��Z�H������+󗅶�I-�`�f�Xy��j��'��q�^yN=�/����`���m�8�hE�p�Ή����p��S7+OjP���/-�U+���<��S%�h�/�]Y��[w��xc��3`���ھu���Ty]M'�x3l�I0�nՊ2�r��n{��*_yD^L���!0En{o�U+kǃ}�v�jemW���mϿ��+K�rK�>ߩ����z�Q8���?�y����K��@>�v��Ñ��S�&��+<��ӭ�`�G�p�8���x���3u�]ǿw�W���g2��l�s��9���(Zc��y�:$�/Ar��!j�Dm����%edԱy"-f4�Q�找��͌26=b�3ѡy�X
w@�rݟz��2H�rڽc]e2���*2a��]R:w �4��Z'Jb��2�T�-ۨ>|��R���)�@�Yٍ51�䅋藿�!�l=?����ȗ?�3�uo��O�z�$�J�?�^��β�����e[N�8t���?��L��	����
�/+�oR&�J"����1b�e��Px�3�2Gr!Ӱ��Kf�e���g�}�o)�|�1y=~�n�׻2�zg��Q��D�C�v/�|y=�P���}s�'_�&EGp��#f��8T���}�}�Τ��2kqh�Ŵ���?��j�_�������哫 "6��1W�?��'a˕ޏ(m&�"A_��=�*͕ꏨKa��a�W��E��%��%���^:%`�' @mbtJȕ�%��(��>��;�'��.ݮ�\{�P��)CL�a�l.�%�d�=x�0|{�&E<��������@���Q����ꈁ�������s`t�� H��D�͡?�>|��S`ԩ�y��*1F��{�?�������j�S�Fǐ\�19������s�YO��6Q6ED����@��G1��1���I�QҌ��MRv�	�2D��[�oc˖�$%GàC�2��Z��ς����t���npç0<�˃���N*����U�9M�\�A6�6�<�VÞ��T��;�)��B��bD=�n��'�G�Apj��)&L����7�&D�!.�1�se�� ;��:9=j��������)S���Z/��1��"2�re�ňJ��6��Iu���bHRG�(��ʓ
G�Ǫ�:����1cDr~�!�w��D��3# ���l���;�d2��Xҕbs�����w�#���Z#5�aP�G�h�vT�ʲ���I��zgD�	���m�̕I�l�C��Sl��c�C����J�P��6�$�[76�lri�4�mre���`��i2ѝYWۤ��hw9������b���|����
 I�!�Nf�z��x.�bZ�ɋ����в.���Q����W^$Y��S�%�'�*��%��w� ՛��u8��;�
��䘎۝hs^a��	G���i�2��pt��~�nrى���w�+�**Dӗ�lp�S�.1}��(�&�n�����$/p���;�dC��D��ヲM��T�!�*G���/��'���A�0[J�o�!*�qh/����Q�X���*��V���:m^%?m��s�/��q�m���������v�:)|8�s�N��:ꂨ�_G��_<��g���K~��� %<�{@��ƫX�]����������n��'/�&��qtک	a�;ɾ`(�:�3i��X�I
�
X�J
��)��*GR@g���*?R@^L��@��;#%{w"��U>��:����*�DHmc��U����~q�D�C��3Y�VB�q�� �qp�F9BH����!���l�B1��(�p ��*A�Ć`�!=�]K��`i��	���!���K:@���fܨ�yɪ�DH^y��p���	N��܎����G��͜[ª�x 	�]��Ug�ch���8R�QǍ1F�! ����,w�4�U!����|�7���άP�y8Ĕ���3�VnCD	�?����ԣ	��1Ǒ���ў�	���ѡbU�#��X(����UG�p�L� � <!!"8m	��PP�3/7��@	a����m�٨`�U��p�bq���Ù���.7fAB�l)���&��V��D昵	��1��o��I��!m��~��%hZu�q,R�EBpp̫�v���j���Bf�V��BHn���(bRɏ��mա1�����=�,���"Jw1"5W(�*�v�ٞ|�N&?��Z qa��C��Z,��C0;�n�2�f1lnu�G�*�{����*8�4C�./?�l)WKH���m�J
�Z�������T���-����a� e�8* �eNW��G0��RӖ��HD�|�*�>H�LZ	��O؜qS��(O�L[	*X�ú�Y<Ke�	�ɣ��	3�.��+��'��v�������{�`�D���[�c�b���IF$�T��J    fӔʞ� 9�t�I���$��\�0���g	�r"�$�DY�:����Xy�lz�1/EΑ��l��=(/�؄S����^C�Ɣo�ۜ��m0�QN��6y�!���$�5���N��5��{��=8HN�\Q9Y��$�``�9�ʫ�x��bt�Tp�r��7��6�_Ό��e�|��Dww*���6t�W�#�F���}�\B��nT)�Jt	a�Z es���h���2a��c��Vj爨��WH���6���OqصΥZ�m&,��+��D8rv?���H���.�0ոY|v���)��r�Dt���I���6d���RΓ�)��t��R�����~��'"r����څ#@N��n�X[�v��_���Y?������p��wR)�I���x9�]��Y)�J4	~wo-UD���.I�Rq��g�J���t�w��v=�R��������v��é��~"H>�1q�S8�\ �c�b��������C�����Hg+��)6��(���O?Ly>Bz�bv,����㑂�����#�rmDZH�۾�6�;^�yS,�v��O����᷑�jD]�F��ܞXo(�D�"�����~��m�R��)��A� -w4>l���^)/E��85�rT@��G��"Bd���*?ED?��])7E��c�I�u��{m��_{�rSD�"����y�\o_����$�S.������ns��,�EJ$p0ت�åd��ꓼ��
�G�0x��¸�)�R!�����)2��e��q0��?:�*y�PF,�s�� ��{�α ��c���d��davSU�����S�PFJ��}m�|��jes�/��p��ܽ�=&M����]�� �2$.�Z����_G��"�,�m����g�A�L����ʵ��#j띜��y���<� >��k�Z2:�.�}Z�2�#n���F.����ab���DPFrke>GS4�SD�W���p
�}��kedG�lYݵ��#|�6~�Z�w֨5���p-��A�-�bM�
���lԪJ��}������/<�dPV���9���ȑ�F���H�=t�.pۨssz�2(zD��t�����5J夦`%d���+�F����MKN$�oe�)27m�4�T\+���<��
M�O���Vf���;�.7`e�o>�5J߭�U��t�Ҁ+��\푖v�4�
��ר�ғ+�����7ȑ���;J{�{��8��#3�u����V�m��`Hq�"���@j�#���|�����:(X9¾��!�T��%)�\��_̎��XOOT6�Fs�L�M��[�
L�������d����b���Uu
���]d��3U��iT�	5M=;�?3��`:U_BM��N��Z����R3�O����/�j�/���50���t4���Bu�Ab ӥǍґ)_�$w��OS+N�e�h��S�U�0U[��>�aN�/AӨ�"z ��~��'����F�i�e��?��W��\}��*tU��^��%�����2#1��p�B3���l���-������a��27W,|m�{}��	V?:�D΍ᒆ�O���z�7|m����GO�=[>�-���B���}�W<��~\��3��G63׋�c{tW�m*�e�p�x�1���O�-v:ܧ��f�Uxhɐ��;�O�0���z
���=9P��||â�0K��,@�bt|��p]�'f@��.J�0\f�q8R��z❕"���08�SdFX���O��!�(V�jP��O�R��k����J�s���y�wH��~�V�9vH��O}�<���)af`���Cj*���M�����G����uO���sS8�e��vH&]�O�_�

Rx�]��EgM�᫬�	��>�M)/�?
����vL$w���J�h������[�8�Sܱ�
bǝ��)����փ�D�xJ���k���8�S�Fj8}�c���w��W���ԓ����$>:CN%ݹ��Ӟ��(5�p���_�tI��MjÕ��B�(��p�'f�{#��\=a:rɿ8�xr{޵{b�l;�ep���{fQ�pe�'f0�u��>3��閜p4�S��x��^g�v|w�l�UO-�*J�w���
��e�sb���3a��O��jrWE'��)s�[,�?�S�U�p���0R�O�'�� \?�K �T��O-�zrww��\[^'�SK�v�X��x���ZHM٤��eӓ9T?%�u|3%���$�%����ʺ��e��<%���
��w]?%�5�>Am����)٬��
/�����c�\>'xJ<�,�g��S҉Ԣ~9��6����7���vK�>%���U�p����)�/f��=%�H��5�>%�H�=p]f?%�H��N��c -�<%�e��\m�pq�n`�b��3�B���~z�o��$�g�+w]������.\�W���(��u�W�H7Y�����:��QY���5�`� �g�}���R�	�|�7�&T�D��9QN��2�B��@��/&h���@G\{ā���:��q1`���@Oʥ8�X��yg�l�d���<�w��Z7�r8�r����m���<i,}������3�s)r��}�B�L�\~�������TK��f-�TRm��������/�Bz��b��	H�®S��}����������]�\=��mz"l	OH��.~�qph��}�-f�m���v�ؒ��ь2��<�◦�[�
�gwZ�,'1ʉt���'%�����t4�'���å�r�i��(�t�J_M�v��W�q5*��@;u��@^,~Aז_�Ţ�a]��y��k��rQ�pd����SK�1���pQ�(�3��g�v�5�y�w�u�	@iE�k'-З�(��I�~�/���8���\��8�s�*�4ő����^�oh6�.�\�\�����P�ŕ�3���WZ بN�.���qD�Ȭ��κ-6�zQ!������s n_@*O�i�xg����<..���\�'u����.��TD��/�Ɲsy�pT%}�Jk���-��6h$A�'逰���施<<�z�}}�<4�&L�GZyD�h���'���+P-Pg΂@g������ɍ{�syd�b|�Q.
D��C��<��6Һ�w��� � ����2ą��(M� ��w���d (t}|s{�<��x��mk˨<u�f��`�]o����_�����˔������z���J�<����^>/�L+�HR�j��2$���w�����э�1�V��$�b=n�[�����t`�_*F�P>*�/��FЇߘ�rw|��O@�R�G�}�wp�2�%��*��;�,�@b"Zx&��ԞD��	{���~ =��<��DՂ]?�VE��{x�ޱ2�%4�	�?�d� �Qm �f[��x/�"s�)��Gݑ��	���r4+����$>����I�L�I�-��.|N�'��x?�Y4\�ap�%��u$�q5�?!�f��h�'$��c�;X�ۓB�2�defO�ypd��w��6 l��C�
��$O0i?o��GG:C���P�e�5�P�t%ёqc��(	����M�ڭt+%'���-����ryV����"t?��>��Q��>�|��Jz�)5�����0�K0�~���J�X\ȖG�l��,9G��͞��t�%g�����]݅������D�}B )�
���h��W)|1��	����-��_g�0�����N�x�'�i-l�He�W,C�,K�������S+sƒോ|����'������~B �L�K;�a�#M���b���4����J���Y���%^��b�'�i�ác�Izn��\���t�&�SMZD>!se!|aV:z�h�ػdr+��Il��#�Q�LL��T��]��)��8�����t�;NA����X�>N�3�-�O��Y�ENڵH^�;99G�.�ݕ����1'g�a}B?!yU!|�V�����u�䊷�~B��J*k��OH�oM0��[��N�� Mϖ���2�f���4Ob�ɔv+�I�����O�a*���O��Z��j �;ON�5)    @[��g�����/�bZˮ���t�L-XF�X��_���ˋ.���Ε���g��B:<��e�g-�o	�N�������=���64��b���@�3'�B��x;y�H������Bz���(�"�QH`^�u��pv�|�s_J�γB����hr<���DJt;]���]��0zm
�K@�Tw�JO!�aQCJ�s���;?�a9c_�yp���t�%����羃78����)�����x����B|l*��-1A���ߺv�ſH&@-OA�%۟������a	BJ��Mvh!�~	 P��m��V2M��yT`���R�Y�t�%���k�=)���r���N���g��XV���|9���v��&�t�1fcԆWʭz��_�����F8�C�<Ć[b)�����֘��}����#����",�9�9�����Rnti>V�"�Q�-m�d�r�ށ"ψ�X"-R~�r����U8r{~�z	Q���Q1���`�Ł�ϝ\�T�U�N�bw&ڥ��y����>(⛵�~L�e��������R�O�8��.�`���*��'���t"m�륔�s���.LS�w)����VO��ηK{ ����r�M^�SH#�R/N��U���W
C�RN���-�R��	3�K����B*�c����i:�PU����|�ԏ�+J�ޝ�qI���� �YÙX`*�*g a��WvN|*����:g%��ƭW_��=�,����6�Jj�9X>=�j+����K%���LcwP�TI�|UWR�5X�kv��o��*i��A��PIu;�j�R��|���X���k�?^�{;h�wfR	W��:�^{�H��ޟ��:��a��l��I�G�����!�=/s������i��\��N�C|��0�-�Y�o��^��>�;|��,j��gPV�-���f0fͿw����G1(�{%�̫��ԁ��2�j���b �>ˢ��ZfN�!�/�1��͌ǧ� Z�'Pp������l��D����{{m���~p"��r(. �N��j���tƭ� Px�$�CtU�B`̪(�0F�UI 
�~n}m�7�Z��������B÷�ָ�[t�6EB�YGM��>s刮3�����@�%�bX Y�TW`s�v�kk�'��e?ԙ]�c*���өŬXE|y{|p���j8�Vs�ցUX��������:�W�����$B�g�:HÕ/o��Ƙ�)7��b��+{Eki=̡���[��:�zt-��:������;�m��|5ηѓC�ڮ�ڂ?:D����F��hyҙ�l%�ZW��Z�d�9Ș5#�C&ṕ��Tˬ�u/m1��

I@Êm)�!E���g�ܦ906oƦ�7F�.J$a;6e
����e�>̴�G���Z�Ó\!��m3�&v]�!	��]�r��K�`��s縿�𐄿���
8��5���,��g�L̚�e��Xb�LҚ�ɸ���_��F���"O���I��X���H˯̽����\�^�I�����",ӷ�`�HF��u�E2߯76�e��fܐ�V��[e���\H�S=O��Y�t�9��F�^�e��Vw�����J�LɚC�>/�G�a�!�[�������%�:b��,�l]K���/^�2�j!k* hUv��"��2}jW�
��L{�a��㞟��>r*Aƀ8�X�ܤ��a=/�j�G��?��<�9H9��I�U�:b� ;��u��{"ܺ�T���ܬe��v���E-���`�Z1���Gќ���&��љ��[^3q�t�w���J�ؕz��`<�9^�5�g;7���9d�68_��7dg���@j�� s-���!���-������x�j�HV�K�s@�qO\��H¦Ҥi��9X�+Z�����mΰ:D-o��A�ֈ���9(_S�9y'w1��E|
ld���RX�E�7�h��L�ˍ�#ǔ�l��F��cz�e<�����^�+��Yv빑1&��,�ە��FƔ�g�;��tR	zߧ�݋kd�]��X*{�I�w8ݩa�$����U����(���F�o�����F:�b�يoDI��f<���R�t!	��=⽞If�kb᭤F:�bl�9�a�w Sҹ��+O��= �����d����7�c�1خ���"y�L��>�"Ab�Ll��-�i�9m��rS��Ͷ�+g�p:��ɘx��x���r�V�56����������^{��Ǜz�3��}�i^@�Ŵ�\cDk�K��\�����F^e�_����&+;F��V����zX�o��j�L�{�`e�(�1�@�gM�z� Y2�T�qcD�)�SN��mUg�VOzf������ԓ����! ��U%#X��@��C���|������O'/�GH�w{P.!�J������*mj�l�X=��P�2�Qj�� ZiX��S��Aʰ��+��q�)�=:��651R"���yj�`���ӝ��R��LѬ��S+����!�v����S�+3�G��.� ��s�M�9����D���O/�Z�8�v�ĩ�����?W�Щ�k�8�䩇Mkw�s�V�i�<�k��4F��/�@0Q�`�$��QS�`��!%(1r<�p{�eX����϶2:�l�S	�E�OF�����j�!��Fy�Q�P�N����������F2a"۪^,��v�`c�e"�M�S��b�lJ���_�o�2�\�� H����ܫ�Vu��g�hx�϶��K4Ak����J� �MS��b�(����O�Ce��\�-r�����}8wWb��%7Q�ݲ�=�&�(4���&J���=��py1&e���=�������C�����N`��V�M��x���L�/hu)�Lw+����WQ�f �D�v�[��u�{I�jH��ӌ�1S��1�6�f����/����|}*��j�F��̠��r^*�5���Lw �z%k�%�bUo���+P;*З�5��T����<~t�d���<,H)�3�*;>�>?��;�䗞E�,�9h �~(P�Qy�k��)#,Ƃ6>��z���]\��U�j���*.���I.�YDp��"�dF�f*�!��(��ȅ:����B���k�%�B���$t�F/a��)����	��Hg���/���yUlg�N�!�ɨ[�n��@���*���:���B$��� �&260p���d�ˇ�BQV�,0�����p_��>i���$a�O۟Q��,܊�	�{D�����Yn�?�)�M�([~�#��|Dn���ٙ��]�(�~i��7�`�G��y�j	����L�LW�K��qQ�!�&��� ��g�c��*S[��X@r���!"�|i@P�����\�4�Ga�q1f�����4�!��������T׍Y��qw��˾=t���]i
�2m&���ρs����x�ϕ]��@�����J����.�`����Vz8t�$�ʔZ@Q~(,��|N*�\'a�܇�S!X����qք�ul,�ʿ<�_��S��k��9/D�����se��.(���r��o��}d.*��1�Q��莸��s�FO��	��J�gʷ�L;�Ng�Xre�,L�~�lt�(�� ��o��"�澱4�clC21Ef`�,�I[�H&&)��j%�o>^{w*P�w��Ud��N<K0����1�A2W�Q���GYy|V]{��Y��PR볩��Ta���3u"eO�[����.ؾݡr�w��yl¹�j�����d���Y�+xd��{���R���F��7�hG�U�G�f�A8����C
:2�R菾x��;�T������˕����6�8�����4n�gJ��T��'2qA�Lt�"�n���oVEc;¦D���?��;�ԕi�9��r����B����h�����.���baQ[�cs��x�T5�����)�S�D�t���	Nce���i&�w�д�mmp$i��`?�'�o�B+μ�S�k��|���;�{�꿽�]~KX�{�����ݏN^���ڞ��jQż�    1��4��Ta�Gg���ư���7�����:��z��N�,L���������!����|B�]z�;�=:ګ��Ν�}7��b�Nlb���@���*<�����O��2U��љ��;=w��f�o+�(濴;�2�N[.K6]'�T��Gg���&��c��LU=t�:R��ky���	��o�c��D����j��X�F_�*���C�3$|���.'���ˏ�o�4��P?�+EU1�G�Y��M���5Y���GY�����O(Vy��U��GQ΋�[Qu��y��F�4tU?�ч�w�h���^��}�>�Xe5S%���
[�a�\��-�jr_p�C՞t"�%!)�7�i���B�>	�*�Nf�{W)S��ح��p$#��3��g#ב����4q�.�h(j�J�?:_M2v�/�mT��"M�3�J��\o��gΞ����<���a��F������,����g�qz�[K������
����ئ� d��G�.�����^��>�����y�w3�K����Z��e���e+o=?:y=?��.y���G7e��2�<��N،���;�����V�]~t�N�%�wG����9�V^V~�!&�#��C���m��~}l[�g>�[�ͬ�I����᧹��\��o��V.���0��i���V^�~t�ʻ�����;��V���o+�e?:[���F�&3�Ϳ[Сf�oa<[x=S�S�⍣�͗�T���rdUӔHߍTS`Vy�U��dX	$S�NfA�R�ȿ0-���
�- �ۜӁ]�+Y��Yު���*�f�A�j�h$w���z-`�ڬ"^�u�������Qr�Y@y/��Uw�%����e���0�@)���R�/ ѽ��E����~io�:zR��*��N�>d	�����@�|,AD!�L5�X6�c�vp��3Րc��R�����6�Sg���JTR �c��'Zж�� ���d�M�BeN�#�,+7��B�N, 3Z��ۆ����d��������ac
���.��V[ N5����eC�i:�Z �[Q[����a0wY�P��;PF =��ltN�
��G��q��!$;�Z��u���,@1��֝��[ϝ�����P��0���f�}��+����?�1�)��q�;!Y �ݙ��2y�P1�`�=�
Ԙ"�Z��LA�B�*��L�l@?&GD&D���
1,�s�pV}���g{��ǂJ·��Ŵf�HR��bP���&��6��3�FF1u1W�e�V*�7 ��{��nv��I]u/ฌR�Z	�I�W�d3>'P&]4�N/;�(�	\�~9�D8=WY#1y>o'�f6f�V�l}��g���ȥv�ɱ��w�`��^#�#�6�g������F1�M�R�31���.�-���CS�,�3�,��Q�R1�n���bJe���>|wԥ������r���r�X^��qX��d��v��wY�,����1��%H�c�(�9?����wU{~���W�~;�� ��r�d��j/ն���m�oK�jAMv~�?� @�_~��T��+#H9�^��V�cL?�S��O~�rt���>��p�{�8����1y��h���`Q�옸�g�R�c \O��\řcZg	��;r�<{��e#
�
G�U����W���M��#����[�NINZҽc��U.��v���7�ؤ�(�����RW1ژ8��˓'���l�T�֘x*��5d@�r��*�."
��o.U�4���h����Ph��.dE��*�'�ȃ��2QO2�e��Sa�7	�'���@65ؒ���c<C�b��MEؤ��hlt��R�cܔ_�y�ˏ�KdΩ�`<M>^�D��8�h�����T!�لv���P��]��īۻp�=�[*c���'�V�GQ4��޽ׯT����ovHb1R���F�-	��N�bTGŬ���=h�;�l�߫�"
�|����(@�=͕:���s�*uA8�D�+u18&��(M�nǤ��I�R�cTC������D�D
�`�p�V����ϔ�U�Fjb^��WH�d5���J�P�;��ᔅJ�B�x�		�ࢥ���尪&�Gu�>����,鱢8v[ݷx������%1Qg4*��*��6t������y!2t��[J �� ��"�^ލ���鉪��6_��z��0UkkG�j�)��W���2$�~f�X?)�{�ت��<�y��;���o�OGu͔S��j�)ԯ��];�ET��`a��X&�RU=��R�.��2��*U�Qb�j���J�@@���#����L�7R9�b$;H���$��h�:��'�4���	`����J?.���η����O?�Gz�ʙ'�"e���j.���`�E$�6.R��gM�_Oc��J����q.�oJ������%���> ҏ+��V�q)�T�b7}����a��m���Ơ��h��q8�Q��R����ʻ�'b�9��\z�ڙ�;`��r�2����,��A��;sI�od:�۸�bW�[�,f�ŕ
���=h���0ٍO��f*-�t5�i�H6� @Z��¡��s��9\<(�)�q��;�f�.�9p��\Q`:�26-�8�aR_�\�g&�r�m�����)s��Y�c�r�ç�0��c���0����K7x���b��s6-�8,��8'�F���(ub���e��D��+_}�a/#xZZ�,p�}��J��f�g�0�#_ ������Tʡ/`v;B"`Z���~7�������Z9`���/ς>:W�ǲo����ݧ�S��Lu�O��p;���Z�u3���Ӷk��I�P���|x��DGf�|��\��e��h��
)pY!��Ha���f-��k�O�m���R����H�R^S�Z���.�it�<Y������Z"b*�u;�GZn��e�T��$��߻vs�f�r-��yЎ����U���v>��V�0��ȶ����.������#Eѣ��B5�iݨtb�����Ț�`��|g�?J�UmēS ����&�j��Dţ�����[���|�n8���o3�ć��/p�|~��3>���v�Oih�:��,��0�j՞��
N;?TV�L�4Ohɠ7����:9{B��Ad0�O�Vo���9�	�0�+����gǠ����ə�7qd�V�ԄvfBw���zBE�*�\��zBg�����kTfS�Us�����	!���Ϥ}Q~Ss5�s�\uxO�U�%Kgت�{r��,�u���ə�|�]�9c�V�é��,lSu&N������ꠛ���wD~�'�i9�a�_o�RmޓL"ۅꠛ�dN��ػ�z�''�����0��;J������f�ho�C�Pu�NL]ng~��Ԗ�~�����oM�F�SSg��5I�:|����*c<��Cyj�by��ͯ��'g,C9���<O��rZ/���J�K�U�R���s��RsL+%:�����$ϤP���V	���&9�\�Kw&zB�GY���X�JLM����_����<���	SS-?��<!�H�PR*�0�<����:LAk%���S��C��X����"m��He#�f��}�>��IL������Z�(���"�����S�Q��B��"����Ze+��(��#�'��9���	A�#N�G����+l�{L�<wt:S9�	4�^���H�v�?!�H;��n�"mk*�15����1��Z���^�c�U�c
L!�}��@��j�����TT�V�>RS�jqK*U��Ha��ϯ��J��������om�^�Q�r��oz6�G�O{����o64�9���j���:���]���z�o����6��C��u�sV�!�Fy��	&E3��6�$w�)p4��4�I�K�x���1}T��iO����v��;�@9�#��j�k�zi�p��F���SL���
p\h�Q�5�e�0�[ߣ�Q����8�(�w��~�?�q|�Kʌ)����_��D��������W�
IĀj�	{��T�]<�d��~ElT�\��Lvwe�\���}�P8�'�#    ��.��Nx��SoT&Z��O��Q��8��Fy�c�d]�p�ҿbj�H�UWL_����_��+_�a��$� w=l��(�q��ԉ_ΛȰl���X���a�V����J����x����ro�(���D����-^��j��)�8�Yw�"��$9GR��<B�$$w/6��Q6����A@��!��l������0# �
$�p��5�� �2��|0S�	Pv�aE�Ӟ� ,V�0��}�| ��Lc@U+�*�O��+Y� �
$��I���D��9�p��W�`j��3���1���?��c�S� +�`��lw�5�>P�B��Ɵ�"�Ȕ�7�_��a�IVDc�:��"&>�����h��J�@R��)��o�� V�#Ϣc	 V$#0	�>���B�ۃz�_�׬���Z'�����Ύ���Q��#�7u�@��=n@�+,�����^-O�k���][B�vc��f��V���
�i�v�:�!�e�Y!��n3��p܎����
�mT,tܶdhX�p\��lEl>-P�2ޫ�u�ލ+�`�@q�b�-'����r��~p,�."��;u&b�LlƊ�o}����2yNs3�]&���'Uu�L]���~v��Z&�^���{�3e�LY{g6˄������˕����<D�bD�.&K� ��?��a��g�����sߺ�'x��IEm��7A)|��e��|yk�=���ş"���
�'��>����'x�q�^W��M��F9!@��&7R�N-(X��0�++'8j|��D�`�����]���y`ćlw�Ƥ��͇�;�
�r��*Y��A����\o�MQۨ�.�)rR��&1G����J��G|*���u���w�0y�&E��,w�5Տ�;&�Hl��fR��A<���I���nH�O��'g���#�(��EG;�I��;yA0j3 ���B�ؘ�8u9&�Y��zC�|	�Ϥ��.!"�jԮ6�Fa�"��n��o��Z�֪�W<ӧS4 4����O)yPR9��V ��_@hq�_���A-ʀ��$�a/A
٨U����������a��b"�K2 �Y�FQS�1�m���c	���Z��xk7j!T�\0j��U7^`�q84��HA�e.1F�eF�b!ʍ�e��(�f�h��U��ܹ�.�;���n�{��u$C�#P3~�Z��\���Z�s@^���P1fDIIX@�2���i`ݜ�����U��&�cd�Aa�|wG�I�X��؃�H))��]��k/W�XR� -l��TS�xDI����:ypP9�u}'���FX*�R�@*/dN�(�s"W�ddBW�����a�k~��]!$���:~�c�c��g�}������w?���*Mc_���́��]�*3!�KTO���%�\,�̉�62'�2'&��e��t�0q�D\z�&�4|SM,��J�y��es�~&E3��8��W�c�tY@j�	a���ў&J)p%^��&v~gb)g1���I�Z��Jn���s��B��9���%�n��	h��g{{��R���V��F�r���D�C�\j�~��'i܉��\
d+��\�k��!S��"�M�CE�y�>���B�A;���HR�O{�>��B��+#�[��R���ǕSw���l��G%P��}e�&I�(�PK3����b�-mT�KTB�۾ݦV�e*�����^$W�aX��w8���\��Z���f��M&W��D����D,��$��x�7,����rK��0Fl&�Z�9��]�KP�m1[q�(����/��_�2$�u��~��8��v ��a�����~��K���\X-\�j��@*�u��#�D��DT��i"��S����$�T��|�1�D��E�I�	�`�3)�y	{�n�h1���<�)�H ����c���/�ڌ�K���Ҽ0}��/_�=�~lȝ�5U
�5�á߻�V���SLޤț�c��9�D,�`�V��Ke-�����/�V�9�T�X `��gGe����ͷc]P��K�Q�:��9��$�J�
 e��A�����tOc�������F�PWj5U/����@w����+*�qTj%i�	��J�ȃ87!�"jAA�bR�Q�J� A�[�p��pdVj)�	�x�ԂҐ��x�w��'MѬM���%*��f����f]@Ы�Ό}*!@A��`X)��	�R����̕빦�^�����;|i�?����Xw|�6����A����3�\j��ۙ�k� ��m8PR�{%�of��;E�R��Џ	[ԣ�1\�`��+��[�}�rC�4�f0�6��q��R��F �����(�|����8+e��@��|��N��� u�i/�7��T�3 ��L`]
(�;pGU�����a���.��='��<���ԓ�+t�R��5l#j��K��?�A�r��\��qI�D� flV�/�ԡ�k�<(6uo�2Kǁ����T��$.d�[�.5�����vD��O>�$�)��v*��QPAdtk`a���ʨ���y]'a�A�l��0je�Ĥ��ZY41�
e�̙���Y?�2bb�22�je���Ք{6윫�V�g���N9�O��NA4W�mBf���O�<f�Dt	fj�Ê	r�"�IY�WL`�D�^��_�/f���S�u$��D�&�v�(�����8��&cjƨ�x����( ��E�e��&،y�'�H��8����{���}�;yj��fI�5J�483n��ޝ��AR;�QZge3���(��2��4J;�L`�>5%��x
�s�8��&(���ϗ�Rj+��9�z0��𑏸QǱt�"Wy��ek�������F)�$�56�W��+E����F��5|.�ƍR�k3X�a����?B�ڵ)���o��݁#ԍ:���S^Hu��'��|�ϽQ�xe�sҏ�[�թ�p��MWlw�nF�̝T�s�E	))2������!2�RA��6�i�
RH��ٗ�+�ѭ4Q�d[����Yx����NH�ϧ�&�B�(�էQ����7�D��}��z�p���R�7z���:R���v��s��#u2�� ��o�@���@�e��� tÝ>�:Q� +��J`��#��!j�s�FV���"��|�?�.޺��F6��/A�n�"��Ӊ�G�r�;�\z,V�Ls�m���|`�L6�B��+I��&�ImV0�pPĨ��0�d�L��Y���ͤ;+X��O	���%�t*��Wa`0�ɆV��-��W:�7��{Xz�w�����v��f�`,T����X����<>k�=��b�t�ml3X2����#��Kl�����$6����%\�S`KC��#?[I�2���L��&`�s���l�����M��8�����e0�'%�,�tjpb�l�=�R�8��gLp)�����]I�ǥ�*z�pt��|��Y�#�A�{�{[��;1����s�|�;�ޡf�V�P��gP�a��%g:�X���\��M��B����s%-s�p3�I<���t����Y�����T�N+��cR����������,�p��v��/��)��ϙ�X�x
���ŗ�2��)�����TOA[̚d:�S��Y�M�?#K9� ���N��2��8\���OIn��?D�DN����Ɲ�2���	��h	T1eSg:TR�=���R�sA%�O~�`r��	�:v}f:/T"�0�zۊtq��r�6�I��<ؖp��V�[��]�!V�㼭Э �0�O�Ck�4?i��o'��Wxn��@Xa��|I&����C�ʲ b��_� ��IRTxQ�p�}�	��|>���+,�M�C0~	���y���l��8~?��x��8.�j��8�2��p="ͼ?�ꬠ��]��r�c�3���Q&ျ*z)�ܢ����C�ξ��0җ�,�y��f�zc���\�����v�'��i	d�����w�y�������~Q%���<�m�8%��8r #��f��������^gH/�Q�I�Η^�V���Ξ^��ѱE�N/����k�N�����SO'    F�P���#�1�B2��	ƙ7��x�����d)5�� o0O��瞶H�b<���-�_���o�<�:�xn�0k��mPj�`��!�ꝁ���P'ρ*<h\���.�6x��=��uN��<��}w����#λ�*�V�`� {��o�7�*Y����Z�_��UI�9��SG��	h��#��)np�(�v�1��o��V���,�|t��i����?����h����gp^�Ros0��yg:�z���njU>�.��^�s�gA��.T��$k.������Xg� ��b^��c��2�b��E�?�9�
b���V�Τ�)ț!:�VC�vcc	@ĩ�]�rw�eqD��8�V�+���!T1WAKGh�ޒȟ9aK<�<�}X���	6�����ՠF�:V��^}����@��zg�2���W�µ��Nn:O/�_pK��������i͜�Sgꅐ\�yt�^H�w�)�9�Iz!���xKxߝ��s�B\9+�:s/�Ly{L*7Ȁ�^>��zw��T�h�����N��Zo!��|ޟ[�M/$o� m�ÙTy������,R'��)��Γ��Y1��
+��Θ��X(�Ԟ�f��"#Z�y�tdD�;r���]�L�<F �_:�1".�����2��ƫ����<ƈ���l\
�;�.�����}�G�J���Mx�#Ӊ�!qp��hS<���Y��i�=�~/����q��N�s��<����
Ä⬽v!N֡ᒐ9�*]�M9�{,W$�,�J�5/�+��m��Y���(ծ3$%&����HIo��0X<�7�iFI�!PE��t֣$/C���%y5V��1����L��F�#I?�։�
h"w���S ��p�-]g�{���,%f�Z�Li	�|܅O%:!P!j�1�� ��n�d:�O"«JS��ъF���hU#&(�G%��D���XX6Z�H���F�	)x�u�$�N�F�$����g��Si�2�|U��ԁ��o���4���x�w��?��dS�ҋx�0:ql&ppt���m����
q4��� ]�gwrm�`�QP|���yx��Y��kQ7�(3lz�����'����3�Y��ʲ�o^X.�D���3��}�h��~�K�ED��S�+�J,��.F˥��(�aϗB�\x����~�5���t��.����Ȋ@<-�^$�Zp�C��rM�Eڨ~������v��Md�,^��r�:�e�ec��0��d���l��&G�y���!��a�kH?ݑiM�6�,(��r��E����U,��]�ǂ1�q���z���5��F}f�<�"-�vé�m�rm�E���RO@$5��<mҸ�Z�
�Hco���Z���$>4�����$�0���
�#u��.ƳR&8��^�_8�����1N�� R'����t�S���䉏�c�ݨJ�ė�M|�;Z�y�K�S�=���^$�#1�|�0<J����Ǆ���9B��1:���X8��}�tI��I	�|e��m�Ϥ���󸊾�b�#�y��O�-�	M��<	k�1�g���Y���l�@����s�<L��\bw�����x���y��2���X���HG�s�OOD�<Qpg��hu:�o���<�\"w��2b�5-F +4Y���d���?��Uf�U��v�n����ʺs�&����s�ճv6γɸ���������ǘ	b�T�Y��n�)���̌�,R�s�
�j��	�IN�+{�|�����k����?)��NT��?�=��l�%�g�0{�- )sʶ�R��4��ɦ�_���^���XٴA��:�����*^E����#�xYĚ-�l�Ut��ydE�b�(:C��	x�;yX.��(����!�����+���`��Ψ�{�Oa��z���)��%�ͯ\<��o Y.����'�v�u��ᒳ���
�K'?1W3�q�*��ʏO ㉖)?1A��$�\X��Iu�yn��]��r�q
����%�9�\�80���H�"�h����xO�L	3�����8���ip��������p|��&;���Vq�~�m�F�iU�(�Ԭ��3gF����,���`�> �hq�#��2�nx磚��<��ơ���yH>������H��ĺ��8���"Z,��������a��W&�G�jY���9N� T�Σ\�ֻ)��_�SQD{�"6�oY��1�P��E��-��eUV�\�wHW�w��T<
*&|w�w8�|�ռ�Sg|֒�����j��e� �:���X.���i\��s7n�yDT��K������~��Ѫ)]!���_�����q�O e�x8���h=%f�C{����ʊS�=	`��ՃI����4�,B���ZFKǫ?HN�\Hv�O�!���T������o�S)#�z)����7
Mn�Obh���Y���ݘ���A���F�/D��_���~
W�����~V�y�lbKWJ���gb�+��O�=�R3Vs� +Uq]�� �i�
(�&�A�%5v{~::g��AA@�,�K�!�Ay0T�ڝ�|����
���÷]�H ���X����M��ej P�\S� S�
\nf��#�A���mt�)�e�����O��b�?(ly���^�F�E.�1��	L���!�_`����k;�u����^��c�ǭ��Y��5W�>����^!��e'�-�r�n�^�H�/�}WE���._��۞m�*�miZ�P
2-g^�����__E� �]�cw�(m��3��&�H�_Ǧ�Qs�a�*2�g h4H��J�P�1x�=��P���>��MsH��������=��Ә�Mf�6f��r
�AQ�N��}��J��K.'@��J�)DF70�=�� DBz�v��X�V&-ӆ�<�U�� �؂�LZ�p8�l�@i)���(-F���De���у,��"��� PZ|��AyZ|��t��� 
���@i9���(-8/�-���K��h%�i�ȥHd$HyZ&p8F�$�i�ȫm���d#O��p�װ�����*ߧ�Ʀ�R���������N�ʦ���E�J�{n��UٴHX���,ٲ�Rٴ<�0��?��NA¤��٢�c=҂`)����|Z pX_JA\��`Ì�n�8��mZp������Hc���Z�fi1�]?t��"�L�,�"���a���_�9��Ά���9��Hs���-�"��b��{ja@��R.(��M��7?ݻ_�ud�/�c����a�m��BC��,�	a���0�2��>���CudOr]K�W����8���\�|�6r��t�#�3 ��t�]>O�OYx�E���s�Ŗ�}���udf�Ah�ܽݼ*�#�2���;8���ʀ,�֑�P��EN��������	��/Q���⸋��f��S����y��?=/�&�v���l�Ч���s�f��a�z�����Tv�JzU��p�C��NuY�2\�h]�4�T��ݹ��y���8���VFHh��f���<ڑЯ�&Z!-]D���O\.���wOX-��b�8����+�!���}�n�Q$�!��*����V��(f>q2�\�LtȠ4�毖kC��X^����/�� ��W�N��pq��e��w�����
u�	����1s9�ƙ�3�R;`�m�e<�
n}�B?�A��a�%���&�l'k��G���ol�/1ۚ�O-���6�F�A�J���I'!6���X�����I����9oV8o(�Ny�$`f��8>���Ŧ��&���Wx�o#LI��s��Sf��8`j¬��;�}�+���y���Su����m�l*�*��am���;1l�����-�3��f�2O��ģL��ɽ��%�9�����:��k��o�V0δ�$��J?8ڔ%=��ѹ����p�g@� �_p,DgD����0.�һ�]&(��b!Ṵ߷7�_��n��]�=�N@j�ݖ���Z"��$���J&n���i�מ�Ʊ��C����6\/��~��+uA�O%m��g`�^�Yu��ƦU׿�e+F    �u�3�[ �/�eφ)"�:�\��"�pM|�nT�ۧ���,ȥ��1#H�${)���,�H�4BaǷ)a��UYs>BB�G���6S�,A�A�'��U,bjg��4����V�a���aY|�a��]�,�LF�a��荦Ic�i	V8�2��+�O��ҡi�NY�Y��?�>y�i��Y��Қ���LX�ʞ���=�(�S�_�}��a�xd����a%l�8u; D���7Ng���̗p�KEH��HIm`SV�V�g��|'t��x򊓌5i|�"�S�5�Ul���5�xA�ws�(,�*��R%l|+Y��n_��	� h�E�M����h6�˂�52_0�&����axb[,�����,Y�e=C_bm�/PLD\��+�l٦�3�5����N���6�S�g�Y���
�.��²�aw�ވ۱��(�{�+���8s9+|unҁW0�1=��#23�]�2��T~t�����]����8�9 Wi��{{a��[ � az������3)?}ף�{-�8�9 �03j�.�fq�rLO�������5�{��"i9^��;�j�vR�~�z��V�8�8���,�fq�qDl�p_���,�:��_�,N.�ʗ�S�6��
�>�|�,N(��f{�.������Cl��ʘ����ǃ��*����y;�;�<��<��N�:}}�>�>��5|��N!��X*�&0�q�`��X��t�.�ܲ�����k�Ւm'�I���)Nj��ls�B�Icіc���t�)�S����.��1����
�芈���c�Ƶ����*�ˈ|rY���yWY���0vZ'q��"��<Ȱ��31,^?RN���������3�ͪ�h�8q�z^'�gq>�$�@J{��L�b�����aD�`<N��&�Gn���&q��"���w8xb�z%[L��:~ge���
�OB��
�&��ƫ:� �yj��{��#y��.�9���.Eo�b���>N�R�9ݼ:�6FH�� 5�9`�6�N�@�K�r�`�ʷ�1�%H}.����m���ϵ�p��r{K�y��j�"u��8�E6�VaS��[ߵ�	�b1�c��%N�b�A�f)�	q��~̆��쯈Ӵ��7�S�$�N%3�� O��N��R�@Z�k�``����0�p=���_~Ô<X
RqZO�h)���x�H�����O�y۰�+Rb��Q�9Ч� ��VJ���,RB�����-��"��,��R T/�����g.R,�A�6���]�H	 rZ;�&�t�r�)R���_T����M(��O��eU�$�� W+�2%Ne�X?������(n�ʔ,�y�z|�)��ʔ`�6D�`���e��np'��L	WYΧ�,%V%_���(UU�䣜K���)A��̎!��J��?�JIB���q�"�(�:���>���0�����*
:�U=��4EA��.w��sQH>�=���p�ҏ�F�>���+Jj>�W^��Y`A8 ���u��TSo�v?%�Z��L��j�ø|y����FX�Q�����i�X6�ಙi�r9���H�H�Lc��V�e4���Xw�Z�Z)���
g��l�\d���ϾoÝ�k��Y�Y� ����r+��
�N�:�Ja�QpY��h3uw/�&)@�^X��^ ph,� C��k��;��ҍB®�ȇ�Y�P�&=���5�e��&C@ B�g� �؇�q���Ǖ�<z17krdr�a�{�&�Z! p����i�5)���^!v/�&G������t� Z0�_�$S�'�Ŭ������5I� �!����0k�T�
Gq�=�5��G�9����0�ս(��ZY `'7��dML����@'7c�$/����5� ���=v��3`M�����Y	�&@�Um��uL�A�],<��o���$��{��ܮ	�5/A� k�`�~��$��Z��dM� Xq�J8;KƮ	�-�+��kbS�9B�Ʌ�_��Ԯ	��g����bM�CQx�~�1zM$�`m�&!Ũ.�K���SP��?o_w^/ي6Z�`R}���V�]�"�џ�Z��5�
��Z
��m3[a�O�._��+WA��aH�|s�X��*������?�^�Q���+�1]�=;eVyk�݂���`�JS�S���� �X)��vn��:9�b��̬��pA�}χ@��3���{K�Ӭ2���j�\C|��&�W��O%&���@���EX�(o�5�W`&�,���
$����#��Wy�a��Q��k0�`|)����+� 
�=��A�A�Un�u��ग��;-�b����q����\�t%�������j{������6��*��ȱ��bWye��L^X�rˎ�z�r�����<����)LcD��)�N��)��Y���X�P�`���H�9���_PS:v��*�����9�ʾ\eY���#D�2)��d�e%ߵE�kKw/���r�S��l������t��E�f�U��t��/��o�Q[�6R��g�S�����N����*�������xk�Vٌ�^�j���K�s|x ]p���q����ܫV�+R�a�# �Z�9R�A��u���{��h�Q��܏Z�����E��Z��pG����z�(�j�*8H��n��mW���ԣ>￻cG�S�*65A���@Ȫv@��u��zU0�"����U��eSK�M��U������2U�%Ԫxԣx���3�^�U	�����3Gتl ��˧|x�x��ķ]{xsΒfU6��tڱ��׬�KexD[t��g�b����Ϥa���)���HլH��������B��:��l2W�
)؛�hͪl!���-mͪT!E�F�!F�M��^�ӏ�2�T�Y|��ݑ�͈�_D=����;߹$�]��00]�HWpE�f�L\.�D_I�h�E�j�"ދ��_����#n������XD���L�.��!\�yY�3"tӚ_��(��C+�H#�v1��NY#�t1q�Xkqt��b�DF/��:�4��1>��d��SPN�_���)'���ūl;J��F��b�l<��/Ո�[Ll\>���f��&w'(׃�ȗ�=�֦��Yf�)�pr�g�l�ۦ|��_洩�d�x��13�v�����Ύ�k���5_�n�HZf2�˂J����/�z�����+�4X_��=`���xA��I(�1b��is�,Ö�!�qT���9{���y�K��=
�U��S;D\򩃶Y6mYү�ϯ�kG��p�� �L ]�#�]�Id�%n�8�>-�`^|љ���p	y}�.	��ؼ:����z�I�t^)t�W��Ti�Ja�X�A�&��zÜ�k�Q��}�0N���Mj���T�����eIx��
x�eK���)��& <�^ ��Uj<3	�p��50�3iU��k>�i�.�[���3׀�[��k�t�����Y�W�׳��.�kr�u�V�~CU�و.��u`)wc�� olXmVYu�H0S:��(qsű�퇼����+�-b�iŤV%J�tqRn	���� �e�i~FFS`@ٜ��є�T���9�!�H�ЅЗg�R��
��x:���e��!����^�D��8(�=���#-��J;\�7JW����sP6���5��6�r`f���}�|#W
K�0�_���՝�#b�
�eӎH�^MJ��V݇����
��R=����@���&s���
 ��Gp��Hm��}�4D[���3���2]�)x��@{�.��R}C��AY�L�.�O�5�U���Z�Y�fQ�"�$�)]�(��JTa�LOAjÂ@Y��j
��2�'3�@�|�i�[`�d� [��/�3�o0��+���X?A����-e
*e��Ӿј�'�q�H}�2�/5m2ӹ�8ؙ��>�]<��+�%�)�$��n�OZ�1(S���BC�)�4P�� c؂Z��:{AlAA�L8b��h���`� ��_�(���g/԰    7���2�%��J��A��"~Da@�2}Y���ȡ���h�ίG�lRJf��y��j-�N�(��_�C�d]�-r���3ua��j���B�9�>��w�����nKO)�b-S�RϪc�_->���u-�"!/E(K��Z���;��|��ʌ�З�:&�U�A�)(�e����$�����ڡlt3���e��n�P��NW�I(�C��+�!��:z��+h�U:+W�0(�+PX����TX�CYz���LÝ+�#��� g�|�

e�f�����M/�P�/>$9W�2g���v��-$l����)	Rc)}�f�P�k��ǀ��,I�e)�|�h�$XCM��w�o�=�gP�~WнR�tG:\1�,uk-�Q^S�Lp�{u-�Rތˬ�n�p;���L�x��	��T�c���Vc���]�f�E-�U�����3 @����R��|Lr��_�¢P���`��2m��k�J��+5֞W�&�YJ����Dh η9� ��w�w��PA^
P4f������C�2�_�&R�|k��*F'�`��-��*a{a���Y
��;��`�pM�ȼt� 8v�Po^0s��x�z&B��j;X��v��E�|��x	�<�����y%У���*�N�ȧ]|5v���n�M����mcD�����Ϩ&/X�W�ߐNfFLP�	⋑���Vt�_���4݈Ik���0O�Ы� mĜ��1�����,!KP#"	c>ĻU<x@����0�Xd6�CFJ,�[<��9؈9x�)��w_0���_�N����%�I�=FL���h|>�d�L�̲��p���7��}j_b���q�����f?�P̞˄6���9b
]�,�� ���}2�6j���+����ƈIx�2;�5j�V(ʈF'�A#Е'd��5Pq��o̿sV�V��q9zF~���۝�k��:+�]���hZß����1q�m�u�<����Ҿ���2 �l�A���7�D�P��&^F�z\�Z�I��'a�!aEo-���v+u\њ=EW�1pe�v����8�?vDܪ �{�ٷj�2�T6��a�V}��'����[�b�p���ڪ�\�t�a�VŘ��I��Jl�$�oVmը)� �V�T�U���E�D�V�J\�B������9���U�������D&�Vo�0 .l�ުb �X�U�4�?	ԭ���{q���$R���2!�ɟ��v��!P���Ś�����џ���U�Aگ��6m�Xa�2F>��F�x2r�[o���QY-��d�H�f#�Y���b1^��}��~x
�Z1���K����gpY*3K�j�ii���b}R��=����bmR���s����j�R)�b������E�g7��k�P)q�˘loj�L)Qq:p�^��E��$ѓ��nf�I�U6����/��Y�p�>Ȑ�V��!��v�*6+ S�ZmV�1%�Z���X�fŋkB-��!ֲYq's���kOëڬ�����o�*7���fe�C���.�o�Vrv�_��JHj��GP�������z�����=��L�֛UE�Qx�bo���K����,����.[�z��u#]�k�Y�zJ?�B6��<E;�w��͢dHXM�l6�L3&opd
O5��,8�p�;�s��{�h6�8�����F7<��xvP��Hv�Z��C,Zۼw/t�W��]Śd�:}���_��͒�6�VH�,0[�F�M2�Y`��3�FR��}n
�`���P�f-@S��ſЀ'�u������GeF�~�B�N�}�ӥ��(��i�_)�HQ��߉]����w'�63������̰�P�w@<�C�X�
-�x��j͌����L��
|NT�yZJ4+D�C��v�Q�;8';���Wn�(���9�Nx�X+��.��Yx/WR4�V�J�ܫ�xB��r�<Lwb)>������[r7Z�9��Rj��.�s�|'ڊnq�DEԊnA��������V���N�r���N�h�±;x�� ��O��Z�/��p�|za
�Z�W�K_A���" �4�����+����<��k����6�ڹ���bEؚ	����^�3��Tr�_���1ʧO�xl���h��/,��N��m��ɋi������W�Ҽ�|������d�K��(%��{j!���P�8��_ڬh!��ˬ�I�BU~�~���[�胩��$Z�8��P��hgV���%���N��QX��B��8�%ϟ�9�_:�"!(�E����d圵#���	lU��\T�n�|��T��*��f�_Z��,񿇗���P�P.jH�{<�^�ג�/��V�N�ǞB�f��H~HL@�v��J6���;�L����m��л�Z�C5��٪ڮL5V��&���@�8B�Ͽ<���LP~��t�ٕ�Fw�?KV[ve<��%a
q��ʠd�,%��	��C?�hdW�(g�ze(����S����+CV=��t���A��9��W������+r��s��4[�,`���t\.�+�Ӻ����-�6pO��"iEGj�����+�����pnEW�"��=t�[�(3�݊�@y$�'�έ�����%��H�o�݊Ҹ��wx��2�VTƱU�b2�|�?�`Qέ��>�g��8���3ԏ�w7��xجhA�9�Y�~��INS��h|���p���}9Y޻fE�~��5+���xH�)�Y�}���$9/�W��az0.�v|�kVd�0�>`Ō%�8�k�m��t|��W�҈��9x��f�B�fg�π����ӵ9��r@��hq߈[�������F�-���շ���]#���T)��i�:Ĩ���q��@q��+0"4k�&Z�~�c|#��(�n�C���K�GCi	rׄ��6��Z�֔  _�~Y�=LM��4 IƨF��2��bzƚ&ЩW8 mEol���U�/��/J�J�&�.�u�mE��pȍ�'��`������@c������5F`��B� 1u\����w�%؅e.֡o�q��˓���O�|�P�G>���_�s������?��k[��o�7�M�/�h��񶌷I��@��b\�Qqa}���_+��`;���p!������qe��awۡ)��-`��Z1���x7��]2䴪��x)_W���ѓ߁�,ڪr���c0ժRϠAs�yЍ1PZU� (���ϽUe9B1��+�V�E�~�����lUY�P���kUY�P�9e$�x]��W?��(t^��2���ǝc�˒и�y�p�a���r_�{��}B�ˍ���޽.vV(^N�ܲԠ8�g���.��'/�V�%�"��p��,8(>���9�_�����b^0ei��^�����)wQ(>A����hEً̞�� _��YP�H�LY������]��_?��Ery@0��'n���C$p�l#��oi�K[����P����uWo�N���4��&�C���F&���62��*�[EK~ʁYK��ƕo0j��8�5�*���/��wp}�}Ҙ���$%��B�����J�T�w���m4qFo�ǺB%~���͂UA���M����sw�W�H���XIl�`�}F�LV��p�8����|`Ej��ï9H'd~�b*����A�hf4��#�����
�����3��@q���x��P@ƛ��32�B�ա�����n�kL���h��	 o�v�FT���Qd±"�c�8�C�G_Tu�dؐ��AR���H�2<v�R������ӨfI�+� �h��F��f��#T-�]W�������/��G����ax�^�a�-f�Y�`o�5U/�&��j��<b�? �J �<���١ѵ�Tcl���YZ��jٝK"�C�[�ݝ�Z��h�� Z� :�H��%� �<}�~�a�
�LS`�g�Q�Y�<%�̞'��HA�v?)��KzS����7)x�\��F�x�ޝ�EFh,9v�2Կ_dX�rL�H���|y��Ї	9M�L�0	�`d>�I�̗����#�]<O�fY"#�    �?q�zr�Y=*¶��%yq�w/��.�?�=���s&,�\�~��mf� G���������,��m�Z|h�=5��=�����nS-�:�2|�2��w�R�A�$�e��*=�����Q��~��ʢby��<H�o)R)��G9�}nq��Á���#�=��f����t)�,�fI+GV����l
	U�ߧ���`VI��Y�ʑ��@sF�S�ﺟ=D��
2jiF'�1p�B��O[�]@.�D��5MC���_���>�YNΑoBڜj��sD�i�1Z<T��#\s�l��9�}Z�g&;�f�E��Ѓd�͑�Do��F������o��Rf���zLF�q�Y��jB��K�G��~�x�;ĮUg�VM�o����y,G�x���Ck���B��S�Y���?`� ��tM�~O̴�D-�(�,��l��@ߜ��w
A�1 ���v���j�3R�wܥ�pe>�U��9�Y�<f��z~|R��.����NeR��%������2���Φ��r���D�o���l�XllI�M\`�G�z���� ����+3Y��v��T���TyL�V��[Fh���.m�	�ߑt_�%�͗�k9"Q�c�@f�T0m^��^��� f��,��HТ{Sϐ�/G�c/2�����X&����g���7_�_Ko�?}�\��Jf�[�F���2��*[�a*��o�n�:��_.���yW�@m	W�_��_��q�Ap�
'�!TG�:��(s�V�>=����Y�V* �H�{Dr�Cr5�(>xa�O:<�9��+T0z�;��df�u���F��!������,��TF{�lh�>f������}�U+s?���)�#5M�C:m��R�*Z�"�T"���*���w�HS�U2��z5I���ẒɎ�kR3E��&���b�?TH�)�7�;���;�ʄB���I���=��L8�^��Y6�J�Z����穏i�*�th�*f��Y.��?Ty�Ѥ�w��j<��\���*$��O���V�$��U22������| {�]��V�0���j�g��6pƕ�R��8㨪���P���˗�f+�$L��9F��\¬70�Y'L��/#H�r�]�Lq���i7p�Ѝ��^P�KL��A�'-�dX�eҨ%up�ï�#�"U��������7�`n���6�Gn�5i��9��(��Z������cd8�e����A?Ԩ�B��P-��LPo���7�D&ChW�����iKH1�F��d��������a%˃�.tp�%#+�iWUpq�o������t��8��6��F���J�ήHm6R���崾��K_Gl��u�ۡ_�'�7��CFs���u��x�[,�9[��
wyx�Ey��)[E%Va0�����[�H�����1�"▆�O��`��^ � ��ǃ����.�eD�<�r��J��3��7�'5�F\5��čL#��)��9�e�I0��fna������|Q�Oߨ��%O�΋����R�/�����Ȓ �?'5�0˭���>�`��� #/��d�Bz�F�h<���#/�r�� 1f�X��G�F��e��
���%8~Tb��Y��Ң偑WhY�%~w��Ly���R�A�!�z�37����p��Ԟ�Q*V�8A��Q.V�{9do�j��n���'H�y�*[�Vc���}�x����(^��WF^��5߁�5̀�O*ڿ�Od;"��0L��kZy�%�'��/���CV�g���@�7��Q�֭��f�}��"sj�}$�$��J�j��W2�@����Q��5P���l�!��Qo��f�i�L8P`�e++ױ����ˬv�zKW2�@��b4��l��
L�^���Ñ4E���\�F�D������
 c �J�(Ь�e�w��|�f"2�@�
��������۰����e����̐��(�8O�]߃����48�T���"�SV���2�*i��M���·U���+��Y��VQ��]^pЕ�÷��l���ķ��m��QŷU���a���0������D,�:�vK���[�KO��+"|[-c(�n�A·U�bL���G��V��C������
<K�g�P��J���V�{a���i����/�e�M��ò`�'2�04�e�����B�;>Z�X�J�q�>�hj���:c��D��:c(�s�	�L�Ѫ�c��N�d��Ơ��Ya�j$��qXg7s��dx�'�h�m���:c��;�p����1�uƩ�W�2ꌧ&2���� �,�8��ƌ2>�0��+A'4�6q��M;_'�*�vlQ)��ht!v!�2ʋS�eG2���s����@Q0�~u�+��^��t�&c�,�[���CwA�]#��dG&ü,����8��n�☾�0/�F���0/�V��2��d�F����=�󀙕ڲP�	W�,���Fv�9�sC#{؜��l��Y�h�Ұ����Ӣ�x��o�>#`F�1!�'�uM �g=�<H�3��"+}��U�呼�G�s����.[�l���vvϧ�,/|h�qv�jʞF�B'?, ��
�$�t�!c�̨�}Z~e�%J���$���!O��0X�=�d�%x�9_���?2 ���}�,�D��K�K��['f�7�N��V��/�t�r�<��I�W��f��n�g��of�Ԥ�n+qf�Ѳ�K d�vܧ��;�D��I�\�.�iEƤY���<��tv)C�,��&:��ѩZ�x���A�fh���B�����7 b���u=:���k%#Lp�I�hj�P˅
�:���jxk�.v��Mڤ��j/��K�$�?~$1J	4�s�/w��S2�j��\}�c!�NN^�=~�����>O'��KBK+B�0���X?ӗ�%l;��)��3�p|���R�`��Z�~�*5/B����B��x\��2%C)|��WT� 
������1��3}V%Y@!Y�R(�_F��ZchL'����G���^->�ҏ�S�d��=��q^��)aAPL�9�M+]A�lz �d,���JNhG�PJ��/�[�n��]=N��`j���$x�ԘHMIL%��>���EjI��%2�z|I��7�6�ܿ��w"���� y� }Z�u L 2��E��޳<<��}#Ξ4�P2�]Mii����3���`��X�,�S�w�^�K�6@i!����������̫Sެ�Wrj�t�:K�!�2�I#�yx[��B;TY�P��+Zb����`�*9�I
�)v���*9�I��FFh���e�+���2Wd�&P��S�CN�0�Ni����SJ�9��i����xx2�KϦf\���o��P�H�F�#S�>�wz�Yci��W*��@���[%]����
 ܭ�]b諤��~
�g鲴@j���c�� 3��f��~4KǤ������^S�}t��BZ\%���,\��D�{,�upFvB�*�rB��@c<���D�Π����3�ILWh3@��|���:����cĹ�-��!��������e�g|�6mG������i���S�z���&��iddE�F�^E�[���z�k�%d�'ʨ����7��:����tqP���*� 4Y�/�A���:K��.�~�a�@�T�6��2M��L�[�	���Xܝ���1R�ʶ%�|v�D���U�5�Dh�ʶ���R����A� �/��ISU�ݡ|	�mA(���!y�6��)�w/��RtV��v �{�$����+�-M����;Ĺd���m���64����3��ζ1��a�_O߿pH�Y冒4�!��b��� �Y�hq^�1��2J�4��&���|���׃°���3�b��9�ip�҃��!��gE%�$pY�A	�!x�L�m�b��1l:"��C�%�=RV�!w�l���M8�5:+z(Qӹ�ed��:��%lV�1V�d���]i~�Y��6�:Y�C���cP���:�97�
 %�(��+��RO~�=�k�o?�    5�p�����K0��b� nĻ�'<1��wh.ŧ� �y�턙<h�S|ޟ�|�'�Z�*f�/B�e4���Z���?�L�����!��2���.<�^��Ԅ&����� A'�߻�*�R���Co�Ѕ'u l���mwm�W�q�� ��	����ǫe	��q����rA�]��e����X�7D�Z�. (�o�[�rP�r��Oh}�+�Ū��J�v˕�hM�wT.W���~�8)(�,Y�\:ɳ�N��\dXfm����aY'01���Q�z�-*%�<�R�EO�ˊ?�8*���n?��
}D ��7�3Q~<p�UzYw���v`~�w�����2���$��_l[�y4?�a�|����*�)>�m�D��e�	��`g�{���.k���)=sY�����B�4˽۰���w��5�/l��YnN���꾞��R+�5�"�f�Yn"��M�-
~�+Ee��2,Cn������%�?*�ܰ������޺+�����.��]�$V�ۮ�@�F�\l_�9A���.w/�ٿ{�G̲"X��� ��
�3���v���h1��.k�uK�N�_�5�L�=B7��
?O�s�| ��nذ;I�b��zԣ������˚Q�}<���N/�����k=�EԲ���~`��S7�?]\���2��A��]r���e����0�-v��k~{�o�ܲ��$�G���Ɣ��_nc�Rj`u��������������-K�1w��g�,� �}��_(!�,!������4J�e�8nȾ�2m�,�9�4G�j���pጮ��X�);ʽ�����Yk�2" CZ�5�nD����<ڞ�{�7m:u
_8�|�&�*=~�|�&�~�1����AhS@/��4?.��Z�#�.��`z=���(^'�ƑG�i���q��ӑ��{�\�3%ͷ�3B����`����r�sGͷ�3<��~�H�`�8A���Z�ӊ�/�ٿ��(6(�>�s��Q��T�5d�V�w��оN�շ��?;�'��l8�8�k�4�bq�/}vc�j��j��T�u�.���*\����Gd�l�������p��"�l��f�0�P����z��Ȭg�?��s�]��vj7�ؒ�JË�j�޼ ��{���!���N���w�����6���b��G�8�&��u�ą+��2 �v+�F�xr)%���v��l n���㢏;�ڭ�����D��R[�9����n��ZbV�˪�������V��T��k�����|P��ҍ R2����lw[p��*����&� T堊���5Y��7�J���L�`���V
����Ǿb�q}L�M����ᅰm;-��r�d���{�E�������믻_�O#UX8:>L�8���"Mo���.���@1�h���3ǁŧ����na���R4WG-�q�t�hn
Hsy�,cx^�w:��x��h���oԭ7����LzX�����&��3H�D�N|7��QMAen��Y�^�*آZ��[�{2Y���\6yv!u]i4���7?�vՆ6�����F/n7Ѭ�h��z��%���2���#2Ǉ�l-�h�_L��Mo0%�
�I�o7=�y]%ƿ��IW�|<��G�&!'�8��6�T4}��T�ӛx���h	,��=���mRDr��`�A m����������\����Ih&c�/ߧٺx�}��"��n8��d���&���B{�~�7��lS����pz������������No��`�~���4h�)8Z,��h�ћz=���-j6i�����љM"�*�����l�������M
c6)���/��Ic�[8-wf���w�.7K��K�x��i6�A��8ڛM���Ko�I] u3�ݤ-v���m�����&����I[b���(��M�b���Ii ���oz�&��Ae��Kw�1�nx���_g7�<ƴ��@�i���p8}�7/�� s���K���՛�P8ע�u�z�� j�-r�&���s�j
��M�Sky=��MJS���KTM�ntt��=g��w���o4'�b+��o,'�~�����a�� ���"�������}���� ��^gݑj3��oI�)H�V�4|�@�x��ڀP����y�ݏr��� av�"�5K�2M��4���yK��	�Z��	�L�ǀQoJ�|�2���IR�7iT��� Q4�4*�������N��ʴ���_,�������F7��ʴ9�3�NW.��J�h`�=^w6:��7��+�F��48�������֚/��io�l�(�= 3��ֵљ��M�2��� �4���ho՘��N1�e�! e��$���Hc2�
�øN'P��4�1!K�_�ip0��opk�g��	�iZ#R y���!�0�l�fs�߻$�֑�{�=LN���~v
��t��-2#[39��w5~��H~��56#Z���d�،L-3@�[a���L籚h~t�:��u��Q�<��.d4�،�X�va0�E�\Q���'Ƣ|5�ʦ�D��56#g�LT)��'R��x��pK��lgd�gT~'����댪������Vg�&Z_Ekæ��M�\��Riw��v=ܣn��c\K����{Sg�^��t'Z^H����z��ߠ�w�pn�����F�ͺZ�����cs�����e������h�q�2Ҙ�w1RF^�����G��e��cݚV#�_��<�Qרn�����Ȗ�u����e$��]ع���q��ڱwߏ_�4�����̘؉v��������S�&�1��}˹Oz���؋n���5�fd��F��#��[���i��g�7L`�f�Z-��$0?B�oB�e��<�Z�A�抳x�V9h%�Z�YJ�`���P�[�gJ�:�yo��)����6�a���}�����:�a}H�O�r@j�v��R*רPp��`LGѳP�*װP�q�:�:��бU��Uj�ܯ��5.�Ïmr��K��?|߾��#	��摷[��E(��Mq���:Q�j/\�G�	;hЊ8����Σ�s]�0Rߏ��3��������	{B�����g�U�d��x*v:_�*��tĚ}Ș�M�� ^e�0{=ΏڒV{�v:��_:�iV{�j:����o���F��'�	��~&�q�ڛL��+�8<<b���L�t�t&��/���8��Ȍ��t���B~�N:W{���P7�;�`FD���Rv�,�)��7��kd���g�Xt� �i�-�$�Y~�����&���I�fdkYxt4{Bkd���+�"��q��d$����q���x�R��Ǖ�_�޻�H�V�կ�eDWO�s�4Y{V�������",����L+�7f��uv�s\F�����cc�h.Ք	��U2�G
���h�\�P+����J�E4��pq����OL-kc���L���Ĩ���J���Ѣ�~��TyS"4q��WNU�'$�]�{*U����87�d@����^�N�Ū$(
&����?X�d��0� �JF,�+i%aC!, ����� �$k(L���$o(��!�=D�GxIڪ�}��P"�xV|�� �M±it�����I��~]z��%)C�/�%Ca��c��%�B����<���z��(�#�����-J��B8Ӂk���r�����)��'(�G��AQ�q��{��i7MF28� ���e�+�c�'pG�,C�wp��/�/��8�C��B��p��;�A/t�q��������n�uLk(������)d�q�+�����'�v??;��%�'8�E+%Gѽ����)�@ޘ�hm��>a�[�
��B|�E���8
��VO���W9��Ǫ����ڽ�m
��t�R$tA���ܽ^z�!�I��H}Y�]?����+Dt��GI�UAҾ�YP�W�V����DBfAھ���0��k ���|'T+>!� e_�ep����}��g<���5
�e��v$���q�K@|AҾ��í�]���S 8�\�m�U�/�ӗ    -i�*ԗ��z���a
��h��	���*}�ރ �*�
��j���*��zβ�*�Z�9��Y��U� z]�Y�
:���v(�n�Oq�*��n'�Dg�s���曂*���p�������wÙ�S�/��r���_X����y��hw6=���|jbC�	������o":?NN�U-��<]�Ӗ����V���s�M��@��7V$x,ޫ)=��I�Qj��r�|\[z\�$�?�}�^�8�w8^s�2���R�UIàЌ�bG*�hnv�(Fen�����4r���@a��Q��<��[�d̵0&tp�2+f�C*
!�����U�9E���E�^qO��((!2�N�`I���Rz�/��Kʠ������M�W�������$��
!��p�ե��+?�4��Q�0���F��x����gb,&P������v+�-=���0SRj� ���B?Aa���`�؞Y=%��q�<SR�vΒ�����!FG��`3mj��J�?��ww�/�~D�:�	�h�u�X��:��~�{��ᵛ<�;�v����	�ҩc��i����_qg���wƫҽ�b��VM7/����g�x��(}�|g���wF����bnf�t�+��K�ؙ=2�\�'���P%��z�.14c(b��0Fe�aK����}L]�Ձ��k<��@q��ی��:e�������)�Є���s��8�*�$��@L~�]�R%�PQ-�9�?�ARI3T�����iP|U���k�_���ىAj��M)���rIє^hP��n���z~@�s�ǿb�8Ga�2�\a��=�:������Hρ�Ly��D7i6�s��}�4�9�NW4�����fc=��0����?��?�\k6�sR#n����mtj�ǚS}RBs�W܋j6�\� 6+H_��M�"q�)A�A^ �zE|i�5+�ʦ'��]��{��� ���ʴ��M*!���%!%�_}�]<+D_�o#6+B�y���`ht9]���*+I�����)}
i��Jԗ��᫃l'jO����ʽ�蘒�����S�[��bJ�r�(Κ��#1��%I1 x�Ǉ ��˻���ɣ�)�$l)��!m��$I�cr��'���<����@�>����'�R�EE�nm�����b��K�C�ͷ��R�&�ܰ	Š��Ɲ�o�S��]S�눮
t�V��,3�.<��@7PB�Y�M43��;�����aS���؅����д��acx�Տa��9\�sm��M�n�t��n�t�z�G䝮���XI���rP��vp�J\���ΰ�뼒d̈w��-Z�v�|$n�*�[�x�����j�y{�D���|�į��"��m�,v�V>�!��o��5��r*�r�j�*��ʫ�K��uP�А@GMQEuS�	!L�tZj��i'*���!4��]O~Q?�M�i�i\�[6n.��T��Bʲ�g΢��O�_N�����ߖm�ypIWֻ���t����KGV���"�/p	y-�c�86>�5yR�ƪ#�%a�5���u�_�Ӭq��I�ӮqZ:����Ϊ�Z���Or
�a��"�r�0V�i�R�����se�'���%�|(�jM�����|»m��5]�D������@�Skz�c�a7���l��éY|���2�Q��<���h��������Y�]"���z�j�k�X:z7��eVXf|V���.��Ⱥv_�_w$�+�z$ų��+�6ф��k֣XS����Yū���V+B���ϊt0��@_d�H��c��"5��(w�"5��^wL�%P�ڣ=E��qq�ش�LQ,�����+����Nb����t�q�a��1�6˄z�qlW�w���сc�W�q���4*;v���n�*���H���@~hq�� �ĦqjL��NQ��cCS��ѳ����c�R�]��Q�:���x����2����R�EGA�V8��1�8zk#���?G�1��!�Z��Sy?w�Gb�а��@���ѳ�Oo���uL7�(�N����a={ώ�֩�fvFְ^�@�x�P	u�<�(�O^�Â���%����Q��"IA����6��,��Qn��,Q,�-;��z^�m�2{���=x�&�%L�~����j�m�d���Ҭ�[�/�u���
����(Pl����!(rV�0vl��I��Z��B����J�Vd��
�t��(vP��D�U|�^�є-�b^SG񄊼*�{�8��j6�4zE�1�P2��@��;<�m���:<��F��)8=��J��1��|�YB4=� "�B	��/��F�0+�?e�0+0������EE�	s����)-���E�q�}��-���6����.WD�h������C���;�[6�e��e�D�����^���2=Ϯ���&�j�R.�h��c`�G����װ�%�]�A����:�O(56�)��.D�qK��k�l>�?P���t��m"��8x@o6�1��ln��������e��,��=�s�����&b���:�WJVm�1�_��j�&�� ��}S�6�_\�G��Vm�A5����GmR &h��G���m�6i��b4�}ƝO�6����^��v�G:�I��:7�׍	,w��M�Y\��/������Ĉ�j6)�V�����v�a����j4�h�&]�l�~�x9yڕ�z���K�1q$07i[r%1��oZ��z��iv%�NXKf�"��\�\"t?B�d�qj<��2˨�)3M9
%�6`�j��������3H~)H�A���
���I�@�k3�6)�	w�k�ٸ	謀���n	q9���ӯ�6o<�����9.x�.�'mƧs����i������V�J�e)���`����Ϛ}���e���_��^h�PbX��%(�Qk��L����z!�1L�(Cr�t�ܔ)�Qۤ7��1��,�]X��)���H���:��`���0�DOJpc��0�֘=���#+ո�#���x��icYi+��!|NV�aI�x"T��?ɣ��W������%��v��҇e�@�Z f���~|	c������@��YU��s�k�m��5V�y�!`d��(xj)j�Y��8�L�IY�H��#2�
Z�³�׎���A3��J_7����f�mr�4s�de�CB�}Gq�2��Ha����X%�V��J�(����i4���s�����;��Y#5+�#n;�L���A�8}܆o4�'"Ҳ
`j6 ���d5!�Z���;�� $��>�&v��*��������q�cG
öL����ϲY����6�ڬZ�lt�Aq�2H�Q`��`��ć�b�e�v�+Y���}�Pl�:Ҝ��fF���j�(���ˀ+��;�
B��Y�|
8�/hH_��F�R���9">+��-���3WuV�b��-�%jOQ�pB]��8h��%�5	��pj�zAkF�ׁ"Z͈�=˔������ V"��m��۸cqf�OæW?"_���j�i��Z@���(j�ɪ�^#`�F��c<VG!斠1(���A;B�`Bj"�f�G��n"~����Cy�J�@1Zi��`n榗;����>�YF7�=\%<�'������A-�4�]�w.���ө��	�]%��'��D��S��q��~����`=V������@�A�+«��xEx���I5��
�D<�0�_������}�Ns'�e�:=��G�^%�t�
�=/�Y6<
� �Y�4l^澀��/�*��L�8|��:�fVi�n2���6P��/'ji��[�ט�a��T3�ҍ�'���6��Xk���(�`��T��*qT��j3k�$��jc��)����6��'��;^c>�V�[7�e�ǯ6���:�<+@4�����3V[����B�fV�n���~�Y�8�yM��GC�Y�I]!�6	ɬ��gWeV�
���!@6��*+�R_���zު" ������x���+=���f4�
�w&^�x�Gp��O��/�7��;�"��dV�OsMq��V�U�")�!��F��U��    �Ұ�s��@�z�*,�<ǭrYX"�YE71���v�vů~���ߋ���XJ��!�Z�UӶ�2��F�yt���dͽd��=�%V�Av�c�"���/8�p_�evj���$�V�RG�ɂ�;�.�ڐ��z��ӫ�d"���;~V�����'�;����_���z w�d`=F��'C�'8�Ss��`�v�ٓ�,/��'�~=��1��20���_'Sj�7x3�p�͠Z�y����ǺJ�����? �UK��^��J����{�ݜ����/HR�!4'�ʤ]��Шh����!*zX���'�y��dU�����O�I i���A͈���j��P=A5Bmj'�Bh����B��Bݞ7@�E�b8�>����R��ir옣!^fq�Lv����������0q?L�Pxq;�t�+w�Lp1�:�W��2���;�pf73��]������AjB��`b�5aom�u3��Iad�.�)��;J�8��a:���p&�3�M~�(;��	|�o��-j��L�u���~�)�ۍL�&�o��M���S1w�L���8�\e���B�7����wG8�8}}X���R��l�A#�[w:���(��T8ax?}?��7�pvn�~|��D4c��1�=��&v��������1�SI�Χ�ht���&j��_?;�WBb���l����6�%�'�z�����0�q��|=Uhp:��>�y�b	=��}�}z>�>��	_��T��u
���P?Z��i���q�Y45�>�!,�����<��]�8v���5:H���6����i9�|�]�8	��~�&���+N.���}�t~:�Ty&��+N�S�����&G�Y�(�d�Y�����'�CT���}����(g�h~�q'\V��K�y���|�Bg�?�[Q�)�:�)x{�l>�rJ2�s��N��RN��S���R�ə�X������S��Ӧg4yB�e6]:>�rb���ՐSy9)�|*mi��DaG��{_q�P������\ZS8������r�:�%�*��.xyEP6�"���[�q)*�O*x�\͑�W|%]ip�S�������Ϭv'?��_bL��k|p�9��{���&wN]c��B����U`2Aw3-WC�Lu��M�k�*���kģa�¹Z��0���;�D���[�	�筰1=y���ƵZ�K��ު�O�jI\���lG���j,N���<������s��<:�XF`�"�2?�,y�&uiV�O�q��3L��O�j�և�u� ?���1��u�_E�>́��"�������=v����Q
+�w��~�9��V�u�#�i���s�\��|��aذ�<��Cx�j�N�g�H-_Igx��>�1m<6n��:CnС�܇�o������?.�	ϋ_ϟ��'�\��[Cˊ�+fYl ���ȭ�pk���v��ϼ��Yf̜��/~6D"_pe�
� ~"�s��.�Ǉ�%�g\G�&ܢpg�,|���N�"<�~���|c�e�na���dm^t��T+���9yD����&Xn��/���^Z*5�Y��z����L�h+�ƕ'i~���GW�g�x���W�6#���_yZ8�£��x2ڪ*���6�K��T9J�r>HQ�(�(Jq��;�����H2nH�*�r�"��Fl��;�`�6s�Uqg�<1��t�����=�
�
���$F}>O�����?ӻn�Kt8��V$�m�4k��!{�D)��� (�aT`��/�	9�m��F�N}�oCx�m�֥c�l���T��A�#z(�wT �ynME�m`��z�ަAz:��B��W�MwC*�C4���ĩ�5+�p��{`�Žu�D�M�|���h!�н�b71�>1��P�V��m���ٞFq����'S����s1�6�e�<b�u3vH�D�M�����'��+�^S z�lÎ=��ݦ� �����n��Ņ�{h��ۤh�0��Jqי��4 *�+���&D�"6 q[��1�^\8*��g��^��6v�z�(j5����aY$;!��K��3Iq��B������$����tmٱ2]}y�65�G��i誷)R��j�w�E�M ����N���}��Rm�:�u��z�í�K�moЌo _<��6m�ۙӌ�Ș[�n��k����m㒋Ǝ���I���K8��/�S i�V,����߰�r�T`�Ҝ�(�m�ؐ�:��mJ	�G��??.���6�XDn����R�������~ơ��lӋ&��q��Z�4ۤڨtP!s�H� ځ&�f�N ,w~ū]�ܦ �M�d������͛�oӪf�A���Ώ�T�6=i���=5��lS�&Fӻ`���ԣ�b�!��6l�ܱ������3�	o�'Ptk}������J���bB�B)ٴ�L!�ן�k�6-��y�ݦ�]���m�xR.Oݦ�-*���f��t`���|��]U��2�[=���j�\������Qq����#Om�)X�1�w�-!�jw������bK̸1�2�c���z�+�>[��.W�\�)�f[����*�}[b���m<�m����2�x��@�O��,(��.�����⺻_h�N�%nb!G��@ߨ�L	q|@���%.&�.�'�.񦩎E=T<�v�'&*���Dm��B�0On7�Y����%9�᩼��P����M� ��A��߻I���!p�,�C >�󊗨j��xv��㢇��)�K,��H�F�r��������� "z�Zj�v
àN֐�ި�1��x��w��m2��lTG�����6�(F��<s��x@dG�y	p�3?�܍��%�+��lԤp,l;=o�.��Yㅋ��l�'������e�l��L��ڨX�S~�X�j���܊Uٍ�����v�*�� ��7)���ј�����j7*i�U4�)Vv��Z3�쉶�8�ڍ�j�DBV� J=��B��5����P5��U<���$��78҄�z�WX��ZѬ"�z���<�|M�����_?Aь�"Y*���?���H����9T�!Rl ���Po�t���h�G��J�X��Z$�S"�:G�e�n��$>���9������"Y�M��i�,�s��{�DRsV��E�7�V��*�(�tL��w��kㄊ����YLvY[�TYJ5�wT�5�B��y��*!6�uY���n�����R�����eZĶYl�_Զ���PٶRIYK�,�+T���r�f�������u��IFq�y�3�͎,��r�y΋~�t��Js������=�N���X��HY�%��G҃8Y�)�P�����Yq&1ɻ��*=��&��r׉���ȊJF�嚮�B��
�%b�t��Ws�Mz����v���s�d�r?��_�G�{FLN�=+�8_善����U<��*4d2��:����I�=#�s:32���*�I�Wc	�k(���O7Y�4U��m�ZY5t��n*��\��e^� �i��2��xt�<y����<XE��'�g�CW�9&����y;5�\>}f�4��1�G,(P+Zu�LhUl�<�g����
+��*`���l��� 0��nc�x�7.�yl��8_���
<73��I���h��t��nc���IT�M����3D���]��M���2Zi�c����1Hb�������W^��}ּ�',/�a���x�;�p�0{2���t��=�5�6���p���V�f��w�Fo�,���Yo�-�ZSh$nS,��R�n�-�Y��1�T�aX��m*�"Fo�V�N�B�oSR�%��L�MG�$.iT�6e�<}�o���`�;=s���1�Mbo�6U�H�<�>�@�z�MmÁ�E���-���)��Ma�
	��l�/����ܦ^ �����T,��?w��f�^����M�x�xz�m��ɏ��k�)�^��k�:���M� vF���/hj�6}��=m�B���hbز��ʎ��⁣���
�n��ݦK1��dce�6mX�UO�mJD�3cU�M��t��<����k�Y��P����`��M�<s�z� �S�    O�6f��U(:�6��M21��UVlS��i�O'��_;K��M��1����۔��/pn��i^͆/bnS��]�ӱ�6-sKy1��M� ^a� {��9fI��r����tS_��ʁ`��cK�r7�_�!�|���Ö=\�a�a<Ń�I��`7�頧����Zy��n:e�S�/���$[ Y�~ߺK|B] C q�tG�+ �[��$�ˏ/��m��{)�&q'L:�v:��h�nU��Ynw��=���
�^�a- fbݮ��x�)pM��gU��1 Kw9��ɪBk����S��ݗ���CO�M��mO����G���v|VZ��Ƽݏc�_�+�����JM�.4�N���zHB�Y��V��)	PT��&�|B'�݀����_���F�+��̎|q�.�=��^G���xjMAΆ�H
Ͱ�vw�޶�0�A���']0]0j� �ì)(�M�.��e��.�CYS�9��N�&�҆��#"6ӥ���xy��5�f�,�T�IOm lҵS/VV�X[.��w��n�[��v�I�龲��AXjT$[����@ˇM[�e��~t��C*�s�.[�aM�|�t%��Y
a�#��Y�T$�����(�-(��x`�l]7�A�g2��uA���FNA�P6�����u�x���LƏkw~�p[D[3�N��c��:XP�n�j�HL�jZI���)i�W�q]'$-!�i`��/S(B��Ct���[R��
�ȌP���Y�)�S�p���r8qg��^
(1��^0�&H��Ф��8C�=����x�� ��'����Dp��!��N�I6"�c�2��` ���ċ&e����q]d�����/kd�,K-<�B��r��!<R&�.)��0�e6o^]����,�'%Za�>!<P&�������P���L͞�f)�DI¯)$�Q�~I����Wˢ��ʊJ��n�J=&��+�C���쐞����8�����I����	���).9�@hV�уc4:w*+���nJ̉�HgE�3�/�Y��tI�8 �|�]:��x5���Y���2�f��e�k�g���ɀ�Y��;�d���MU3�g
�=��<�i����ǅh���UH�<�i�e�����2|��T�'�ǆ8l�j��:�i�]��6!`Z����w���_���E��l^Hj6����QZF�s3��d��c�f��9(׶�S�,�@��(,(�6�I8��-�y��<ף�I�-x��<��~�߅�<X^�Ъy�<�1�7)����p%�&��C�z��H�[t��k�N�<.j���!.�<��j��4Tad�1LS�,߰�KS��T�p�)�,H��*Mі�
<0i
��iE�"�\sy���$w6�#���7�i��8���d;W9p5:�$U�v�f\���u
�j���FK��!�����XX�8�i�nO!����`f����F�ˑ��Er�#71�4��|���[�(���@yxyh�ۃ�*x�S�a�jӰ�Ý2;<��|�qtʹ���UC��X�1c}ݔ1�Q����)XƤ�*������QP�G���/�L`�)��H�y���#�*����=V�z��d��y<�9ˌ���l�eGV���<
������;c�2ퟃ[�����qy���*��he
�{���>�l�E�$�Y�h)s���)2)AbQ^����gH�%���L/��B�X���ޝ��ް.���Zj[��9d�5��97��j��V�)�
�x^�<�4�9c��h��<��^%�Xͣ`��l������4{�Ҍ1�I���ﷁ�l���Ƞ�X��y=�oY|��[�L��2��}���JL�\�؟W`�ģS2#���y]�H������i?�/�6.TI��J��g8��*ڟW�>�y��J�?X�����Z�.�Ǫ��2��_��_Tz4���A����d�	���X�?htţyc5��ӞC��X�?�t���{ܨ�V��Ϟs���#�����m #>����B�]Eu��Z{N�CA�.�a!�ԥ�"L��?
�^�\�'����厕��D�C��s�`U��j��
��k2�R�	1���N=�v�y=��h� U��RE+�s�o��/ոh��0PI�/���v:8�r��մ��*P��t]��a�:GA]��{�=�t��T�/z�B�����*�������9PǿhN��k����_�|P_W��C����8�`��	�CH
_�K;��=�`�o��ˡ?����&4�t7�����V!������0&gD�ˠ���X�$�)6�> ��@c��Ub5���@�9ⲭ\�F���}J��EJP����{��d�Jh�C��� 	&�23v����K����t��B��(����*���q
��k�-�3N��t�w?��(�Ą����x��7���pm����j��,#�$dc��4����(�\�Ԉ̶ �0�f@�e���Kw��H 8u�	Ӟ9O_�,�&��,���%s%�d�>	1����Kg������#�N�q��t�!��u	�g�Z-�FɘN�	lK`���ډu�PO��v%�[���d�H�w �-�i��2_�e B��^Np}�pU��J8hE|Qd�t�ei�=���t�n������*;��ر�(��$̠c�,�e�KY�}�o�ib 洰���w����&��-I��'X-�s: $�=��2.%��ǰ���~������+s�� BS&����[㭮'�%�$P*"\i��W�rG��=x�Y�q߇מ�B��z���؄��� �,��˟R�²M�r3S^�+ظ#�-b�Jt�E|��Z;�g%�
�0(>����D%(wcFB��t�fO��Y����܏�~.���!��Č�5p9���Jh;������e�B1���7H(��e�n�S��1���#�o�W2����h�5朄��ӛ�P���Gߠ�SnP6w~}�N�r{B1�e�xcH)��i����tp��垑8@��}Ė��3���f�b�.�qid�r�<E�+i[����g_�{?���j��r߰3�8���okq���4�+k99�>�QC�u�6{��o��+�_Y�81�򄺬��h�W������;�¸bĲ7���U/�C��zԈ5�@�1z �l)��
�n�X�
l3��G�F�_��^XG[���� ��L@}���;t�K�g��{��>=���%�
}������%X��ވ3F�s�'��XeJL_h��8/{��,�I#V��1z�>^�D%ڰ#s#V�]ǣ��� �2�/0�"�	�,��3�]OQE�E��E���:�Ϩ����Q!�(2<�8a���68.�n�z���8�Y�5�14��MY[�a����
�!n��f�$�����'�=�u�
�74G��}��H�HrO�;�|����.�躂;9HM�p+�휢�M�=p��|����	������[+v�s���o8s�Bs�(x�A�f!j
bu	��	[-TFLr�Pu�X���K�z�Y��RH��v�*ʡ$O�]��@g2
y��3�������e����Z�sF��G��_���;�eJ��N�9,�k1�(n?.)�7z���h�{����RR�8QU�E+��+��Ww$b��)��7��תܼx
YD��ԪܶxL�3���r�&����!��ٌ��~�9��k�Ǵ��X]�j��t���`'��@3�iD�t��Iq�O�K�AN,O��F
'� �̛8q�#i�,>�9'IS<M
t��$*�^������Ii��E�-���!z��")dt��'��"܍�h�RS$5rlvb���V�iN��^n*�ک��1�����Z��2����}�SE	Ɠ���ک� ��cH���|o�{0���U�SE�C�#��9U9�~t/]@TQ�*I�I�*
[���@׷N��c�t�x��C���l�NB$�Q�otQ1tra v��(�ƕ�_�a4����f@�Bo�vQ6n'�.J8�+�QYE�x�Тhyb�f߈Y�0�:΁�6b    d8a�ڈ��aM�ARÛFL��`�u#�8n9��.�����mb�h��	
B_�'<�n��¡1�Y�mĬ��f�2��
gX9v7bB��Q���A#��v�&��Ƕ!e$��*�&��	Rd�~�#�����F$��6V�*�htT�
"��Ƥ� 4��q���/�ʦn�
�Qn4��&o�uA.��N�]A��/���ភ��P�K[�����mEߟLx'R�Vt�9�NK�V,~��z� hEO����r�Yc���I?	?�)�����T��H��ח�;�ΰ������܀��>�	�[�C���	��S�t����j/-d9�����8��Qv	��?����_O�Wb�ļ���+��r��c��	�m	ےi������cGCa�)���ȕC1�jbK����D=��*�	
�.��kҢ�3�^_��pV�Q;	np���5��Ɨo�|G���t�%�<�%�q�o�$OJMQ��ۖ\@�v��Zr��c+3�%�a�Ɔ��-yl��y�ޒKF��G��3�o	]��/�k��zZ�(��и��)
��;��| 2��o��h�w��u�;�G�v��UI�K��Y��/d���2��*�&й�D���
��0�#�$}_�6�$w�V�/	�����)6�|]���ˀ[w�zJ:��BZq��A�O�ިJJ��T������K蟪$}_����i���5��B	����>x>����@����%O�Xǣ�CO�i��혮~�:R���T��.�G�x���1�xO^�x8z�͉�1P���;1y���@�!�8l�[�I(@�3������9��J���h��v9�]��]���d����1!�����.�J��ԯV`�3&��B�4�Խt�!6��?ŝ.���[�1)j��ҙӢf�z\���c��?#$Y�Zr�Ȁi��� ��ӧ��x��]��3(�~K.l��䁰����G�?�;��b+̕t�~C��]N|�J�xt 6Y� m9@��Ѯ��	l��$�rwy�n�p�Om] \��u�b�Ǻ`����= rX4"��Z�c)���N�Ja	T�㻂U`���}���������y�Aѥ9z/7�6/4%��[���}���/��rlM��hj祰�>��tyѡ�l�ǳ��|*r��Q����G�abK~�ާ���0�n��Pm^�1��8ȁ:��=tl=���6�S�yIB�d� ؼ0c��p���(�E��n��$�c2ų��>�����U���̖\Tr��.�	�䟒�ö3��%g��&3��L
���<���*�r"��4PdiX��ȹ������ TBQ��Zǻ��|�@�>��<� -G����]Z�u������%��,�E���Fq�[�j��1�/���_@i�$��˱?���U�������P��%�0n*�׶�A��%�jn�{�H	�s����r�`�1��S��~�t��\�<�$U �8Ɉ�����F�)p��#��dh��Z��%c�<��.�Z2�a��S�"�*�+t%���VE�/}?]��w#�)��`�r��C����R00�q �`h���=T�Bd�p�o�B��vUmxy�`�Hh���oݡ;"�-��i!�-#{"g@).aSKR�]R�����g#;�����#�UQxP��^#�(9(Eΰ�1ry&���	�,��ؠ4�Ѐ��0J�%.^�h'���k3A��aCX��2�X\����1~�\�	p5�]���J#����	r�&HӦ)(�\�	��J��k�t�����r?`�Y9lۧqc�Z� ��!�?ݳr��h�ܒ�q�`҆�dz�'�t�r���:����s�KVqVn�9�Y���<�礖�\�<�����Pmo�R ��FjIȕ�Ά�<�P]Z$[9'�d�q�����Ӣ�WS2��,�`��p�#e<B�rM+�3��'E�Ө OG�E�/=�v~tGp]ct����� ��8q����7i�hp����m�X�����E���)?GfJ.�5N{ݑV�(y95	|=�3�=��,tn�Z< �r�p_�K��_Q��O���T9�s*�����#�V���<��T� m�E ��l�;�N!��hP�8��R?V6��rށ���鹻<%y=N��#m�����m���j��K�Ih� �
`<�?���{��~3����$�Z��܄�S-��gq6�ox��W"�R�����˸:��py�i��:m��sl��d�����{�\ʥh�O��wǓ�T+�
~3�uZϰ{j'4��U�Ʌ�`-nF�\����){�,�u^M�������/��^M���L�����l֪݉!�.�D��ĨS뜝!|�L� ��*�4jq|��J�F ?v����,9m�����F�C�d+7GI��L�D�֭�%u�!�V�0y�NS���[��O���:�3�/�Z�t$95J��aYfW*��C}�J}"���@�z+�X	�~j��:�:z�q�{�EB��q��j0*���S�m���1y�	^E�B=��f1�l�)Ӿ��5 �|��ʴ�v jSK��� I���4�L��;�<�sj����`w�+�e#"pMbi� $�sD��k���������fD����D��v��f9N��=!��#����S�r`�����g��/������5b��YO1�Yb/h����!yž���wR���?
uҼi������I#$���8�:��41B�K�jGQ!u�Iޟs�G'�����}j���HQ%O���H�*���~1f��k��y�hiH�QH��xk���@ �u��{'!�k���	���9	��Tc�����!�me( ��ԕ���v ^�Rx��2�r�����!wZ��P���u�P;BM�&Gh�B�XgBN=�{|N�I�R���s��'��<)\�=?��N�)��2�S y�p�B~��S�<q��S}7S/-9��K�]�;9�zi���v��{�D��]���<.(�(�G��hp�[/յO����녊f }~v(����=��)-��>?U�DJ��z�'�99��uu�+��<{ܶ��k~g�)� �P��2��wH�\����[���}n{J,�R���S/�w���Wܕ m��&HkB�6O��.�=�]��s���]�� ����r�v\ʌ�SS���D��+��p�������q�V�O��|~�*��<5���7U$�����Vު��p�S�A@%U��� �PHm����L�k�UQ� � ��}>A!U�(v3 W�w>9�:D�=&�<��4�%^���%����^��2��+k�1I��̀|M�G�1\/�kׄ.I���J%VY����K�9wqUU��Ւ�j1�o���@��bV�*Z�����)3���Cz̛�����l���3�ץ���������kUE=�����T�r�BD��_�֫8P<D�i�8YUrO��I;�~���,��h����_��Y��\U�:	��z�z��iXN`���1��X��ir��(l=����^PB���F�Ν꼙X���:��h�w4 1_��Ӡ� V�}i,%�|u��稫���J��Jܡ6��a�(�}��Z�I� �m~t�>_`UQ��6�t�UQ��'�X�~����m�`\Eq�xWp��:ұ���j��x�ЂHh6"5�͠i�m��b�x� ��^�b` ��68�_o��H��͠w��Ӫ(�cn��yt���75��ӫȷ\E����|�U`�Ϸ�"�L�{�Ѕ3�m�������a�X=|������c���P��2���J��QDqH?��M�?'$��, 7�=o��ĵ�������~�A��L�XJ �\m�U������QT^�uXh����"k( ������Cj�pإ��l��j.�Bq��k�͜r^���Y���db���<���저�֜�,��<,���YӔ����_���Ŗ�JG&������?ओ������Fܿ_cSQE�Ȓ��6W�G���rL_�!U���`�	,8.�0    )T1��UB��a��9�JN ���:��G��	��3�NN�L�(���#�(T9� �9C�"{9�`���=�U�����B ؜OL xM�?0-QXiU�������QXi�x�(�4$��x�(�4��v��^���I�(�4��$cH�!�v��u��t��Q`i��v���ޱ(�4��w/�r��K���.Q�9�.
��*�/���"0�ĭ�(�R��vu]��\�!���zBE`
97~��GH�����Ľ.�( S��e�FQ~)h��/0�&�{�'E�_�����@kg ^�N�n?�!��d*��\�02BjMܿ��GF�AHl�z7@�:�aW _�DՄޣ(dT�S$-0h��#� ��R�"��I�p�##  ��^F�$T��՚\Ǡ��1�f�z{���r�.U�k�"��D
�#׾>m"M�Q� �j"=���@L�d�����=OP32E�6i�M����Gۭyp�Hk{�>��
!r��	�5�Q���`c���\�<���:O�T�ž���?���k�&��>�|��3`����§�dR�Dt��U)~��ô�����WM��}L~��Þm((�j��'w�nx�\E�q�Ǧ�N[vQ��O�9<����+�Omcy��#=�Sk�-�'�&<F����{�W�cA�H-5�O݇�"UB�Só?�%r
����c��ͧ�KS ]�A=W���#}������a&9����̧�"4��w�O#	wtk�SwΙ:o*���	�a��Pt�	�Ys�����q3���1h��IW��}���>�]�x4��2s7#l1}n��;S�=�����Y�_xv���T�oY�^Ӧ��Y�bC)>w��q�0:�BVf�����W&��Y#��
a�(w��T�UP��Lv�<_uS��t���%2ӂV�Ym�w��W����puM��ze��#���W��a����%�EM��pM-��	��(m�j��^�O�z��'hxn��&Z��S��&��3�(a" ����(��#]�$���y�������wK���]x,��
B�~n�y�/G�UQjA Qղ��KQvA��W]���!<��ё-��gc��]���1`<k�F�� Ѻ3��������2�0�?im����񶻍,�ri}NeB��\�J�Ʃ��P	 jIe�/�!�[�e,:?�G��,:�G��s�D�8�U�������vJ�K�z���\���)ab������J`ݎ:�Pu�%@��*�,�d��@9NW���l�+,+�?����O`X�Ւ�@����TX��bP�DT^���"X�qc
õ��������CK_�1�h�޳i��!ETєB~�	���pE���l�+_����%h�CA\^_�˻[^�)�6��E3c�VC}�U�z�T�
����8\�T[*(�g�q�h�1	}B�
�sd��T�&�EkYW�ƣ@��yR�en�z��*�#]���+y9A!B	�v����{g�<��/G�"i��qG�~u� jV�AE"g����������������|���*�F���r\)nL�.at�#��4�&1)o��E�8F�6��4��S�	Mg��׌�&3�"����6�c�<@Ѥ��]P.���IC� � �������M�R�JUO#�rU�p��e��(ħP�*��Y(]l,��.���#<��	Z(`�R����P�,����>yC��*9���,]��y���P��^	����Q+��e\����lO[+(_���
"�J%L�p�p0����M����	�6Uo��h�2&J�0A�)�? �u)�>���;˰�r�,�/J�Gx[
�BZ�:���$z�(c�K.���d�*�9�c��/��Ǟ����H�J���>'j��u���~���|�K\c�0�U���'�q_����qȳTR���ߟ~Y��Td��y�G����.K�?4`)�2-�2X4�z��H�JE��
Tp�K��t��Ι".@�\ z킁�ȡT���k�-m]*�Z��T=y0�R)ց��ȩT��&k���jי�8��RN�'�0���'a.�%( �Mi�Q:�t�ъ�'x�L��g���A�ݪ� 2w��%QT���1�c��m����tb���R��c$x,ekp��ڴ�>ݮ����'�r��-|A,|ߧ�0��B��T]ǟ}_�	7�o�IÛ=|����p����B�%�"�Xn������?���&օl�n��^�T>�9X}a����}#.?�O�u��㋤4��o0Qs�-1��R���-Չm{����I�0LB����_rA)��|�2��%�;0	���AF_�H����ezo����ߘA��W���	��v.����v�N�1�U�:3� �_���x`
`�K}��9�X�����!�w��%��*#��$�q���E���Y@&�5��#��#�x�s�����7��������&q��� =�(8'�s�s�Ad+�u6�<�u�ʂ�d!��qϑ".�^^�R��~�iV�:u��Z�>]��@.?3l���&a⮳�&����:N�=�g��C)vMS�բ�?b,���`��ko����P�]���Wi�EX����/ژJ�9,�W4�懢��,
��?�#���\�>���2�����N��*�׉=8�E]s	���x�7	m���c!H�P� ���� ����.����}�~�p"�?�ss�p�GL�&�a@�3� ���Nx���>,0*�Y%���b��Q'Ķ�Xl�n���`��aI��ķu��#��d��	{j�ڨ^��T�'�TT������>�OݖK���/u�k�x}�m��%��o~o�W����1�v�k������dĶ�Xz�?^�������7�'�h�㩹Ix�cX9���=yjg�	4vZ��Ug��7�ei��%6a�Šn��P�^�0�"$�jr�.�b�#��_W*��m����Ċқ��Ri^K�Q���b>���n������]@�ꄭ�,�u:c�_vv�	�,��*>=)(q��㰙�c��Í�&as�(�g9_��	+Fy��Dx�	�*��� ��P�_�������_�H�M�����}�nXX��`
�P�P^���e|�(c����F��cl��s]X��n�w@�����63dz_�*����	����@/ճ�|���z����h���Vs�
��G~e�� i���Y/�[�t7	���v��k6���$�y�Z�g�&a���62��!Dd���T��E����ݢΛ����6�n��Т������(�T����3��k���o�|���E��IX#}Mn��mp/��W h�r��/�$<}�ł3{kͿ1 VW�s9֧�g$�v>@��$�tB�ƵD�>�r�D�nو\�e�O���<^�)�����"�~���&a�v)D���S�^;���$\v����ӗ�-l�5��-]��b�IXc�1�!k�0��&�w��SB��7l�DfP hg��N�f�B���� ^g�-ãcco3��@��	׳�@�W1h��g�@��j-�8���;B�W�1��t�^����M��
`T����4�p�^
 �;���pg]�01|[�+�I�}���j�A �����z�W��ă��;$Xl}��c��;e4��o�^ �>sc��χ�,����+�Y_�M�����]Ӭ <���`�MW��o�u���X��̏f]g�c<�:yk֧
���qВ�.7x�a�T������4�
s��?�ē����q���?��B������f�v�HuK���.�ڛ5������P�����s)2�iL�����Jm�9F��Zh��/����Տ��^��s���E�*��>8"��֗=��?�f��*ܭ@g�c{����r�X_�mi��F���^'_Or�כu>f�.�v}���EH���x�`���/X/�$n���v�Sq������]�������׃g��[�.�#v$��q��u�������u���N�?�    e���w��x�`Bʻuq�Ǫ�
f6����k���~�huX��a�>��uq��^��|��t�r�Q�ޥ�U���~	��V�[�y�d�����G��Y�H~�u	藋Ok ݹY׵<���4��u���\��6A�0{�����
�J�pEs�KL��#��ݯ�Mh���j_�-z]��P)]���ݐ��x`%���y��|߭�� �{���8=�w+) k߲����x��?<�ۊ�b��t��&�g-g+�ץ+���KzX��p2�Mx�R8#��m��>�6�SKa��&.�S���`�T<q��@S�:�M<]�⹻O�n@S@̋=Z��tv�M8�b(9�� ���iN�$���i޿$�8w������콚-�'V;I>�+24,сm�C�7�(i�bD͊�%R�Cg�p8&?��a��h�$�.�;Ċ!ɠ?l�r»��弜���&b�����xT醠���*?�n?�.�b��&m«�72ЯM�6�Hw��Ԅ�3���U��w&��s�^]�j��-OA�������c����d3P����5��*n���2	�*Xd-�t��"m�"<�4�+�	Gn[ⲝ�%nǺ�ŮY�;�L8'�<b�$���=�,��G��GZ���DjJ�/]e��C¼
�P`�,.Ag���%NH`��g�W��J�K�.!�	o��Ė~�}� ���
%|��?����f=v��/�=��Z>x<GT�$����D/�W`�2��t�ZV��7^j�K`����X�5GN���K\���zAG'�.����fAC�# oȃZ�v�3�Ʉ�.��H&�"�K�9!��W/���M���,�QV� �H��]��.ѻ��b�k���=���'l�{��S��E����C�-�9"�Q�	�CiaV􉃓Gn���>qX��a�V����Q�}b_��iWY���&H<>DC�x��@A�e�C�FLǤX�>��}L���C�x���m⾬Oh� S�rY�}�z���4��'4��
��T����%4�ǽ'H�U|H�CtG�Xy��>x�&}Bwx�]%�j���
�q��5��p�	=f}B�@>vxQ9}¨�:�UAV���]��ժ�6����	"�(c�O�T���)��ޕ_�*���c�%!9'�:�
��~�G����!JԠ֦x�	c�%*�:$tX�h�:$�<1��(n�H	~@C�?#�bL�<��DE�b^�����@uS�m.^��Rs3�ǲ���n�'�7��.<�&X��3�5C�X�$@�rFDT�}��yp�F�����K����zz%�6����s��L��^x5�~?a����v�}�x���I��΄Ec���6L�ʡ�۸f6Z�{����,�T���M�g������8x�����DA�~x{���q�>��ގs������D~+�ǵ�$���������J)L	������MڬJ�M�k\`a��2V�i�*U�U�:OKW����s$�����e]��Ϭ�de�\-�~��p�C�l�%�|��k��\F�ۢbI�2��ɥ T���l��1�K��5ޭb��i[��6�� жAb��ۜG
���ee#X�H�숯��d#� �{���!���ad,��$X��Q����A�����=޾��aa͂��sYel#�'K���)��`QHT��8�I�+��&
�F�vy3�=:���n-�xul#N]����5��m�R���iQ%k�F��wD�\��ϣ�-j���	�H٢!"4E�����2vk��V�Ć�7�esE�-��Q�}�ڔ5(xTK�w�}���M�U|M����]�
vД1a��Bl�(Ե�F?=a_E��̟ND�Վ?��%6����}�ܻ-�A7�1�����&|�V<��#$���O��?=�݉?�:$7���&��K��Bl��[�#�����$��C��D���庖��
�2��	��`�e�s_��Բ�s_��T�)�
����P�s_�dT�� ���@g�����s��h�-k���9�9m>�I���9�u�@��l�ff��bP̮@\,M�mQy�5��f�+ty�N���[�u�����")�n��Kܢ_z��R���.b:���5��S���T_�N��x�\�ڱ$D)�O+X������wq�Q�p��\Z��_���p9�ߝp{E�����i�k�+h����V�쨈��R��^��@������ZU�4���r�o
$�hO���Ukt-�r�&r��OX�V�k�W��r)�_�$U�;�\ߓ���Y<iAЋE��Y��ET���2g�/�X�<��ȴ��H��=�	w ŶΕ�¢�`��/�r�G��(n�JU=Oq�$���T�*�)��C�-�]�ʔ������i���g��	�I��~[�j���x[jwt����&@=��&��r�	P�����B��QU~	�ÿ`��Iu��x��vI��Ԥ�����*UH<��Ú�U�~x
�O��oN��RU�S�����]Q��w
�t�iU��@~�T1��݈����T��Vˬ��~	�;M=p�P�*e�$>�D��.�JU�N���w��D�9*k�|������E�X{5����@ʂIp�*�	
�A���:��X�,�T��s.�lqEbT�㊧W�M�
�(U�DI��6�$�)�"��)UʖH �TFW�* _�I�c��J���}�&-�$���g?" �':��͂5�a�{gp|�`�{^(����.�����x����ρq��Z�Q @��Z�Z��K���}��`��޻I�|�	�Xr�]���W�n�G��>���.��x�_��3�`%���]Q���$\���4Ц�X
8Qѿ��mJLpsv��Wnʌe�~��Wn�M�]s��R���"Ta
�`7��C�梥�Ёr��m�`-c�ѩ�2	��2k�Ci��]��@ۦ�7�2�=��d?�*0�xLRv��ϕ%]��*�L!�i_Oc$��%4��Ցu����b���68�
!��Gq)*Z��D��ҝ)[_"��y��������4C� ��Rj����:<�j`a �x� g��Q/!s}���EnO����~��mϘ��˟�ω��:e�K����)[^bzז��M�S�� F�O&Lh�rt�c�t�Pƿ��N�$��c��|o��������>z[r0%	���9YNSG���:ub��$3J|p�ͅ����D�$��"�V;C�ڤ4�� �K����	���W%]&��C ����Q��T���F���إ�{�*/�28!�Dw	]�|/yo��3!�,ʌ���OB�m���~eB5���M�?]��k �[)�"zf��4X��&"����!A��z-�􇠇T���B��� kQ�@m�2 �Q�ץv���Yh�ԙs�(_�����|pU�73��<�i�^��µ��Tj��㝿wK�0;����מ��T��])zT�.C(�T/���yWujhsXgq�L��^�07�p�D,�� �ph��r<ݝo0U�{��l��gs�~�*���\G��y�)���p�A��]P�k���:e�|\����{]�*������]���|$3U���kp^k����Ti�5|�ۯ���k�&�eN�P�T��5&�u?w��R����*��*Yl�t)�m\�b�8G��
K�Ne�W����k�R�%L�u�R��%F�V�:�!��{�<��ϣ�B�sIŴ:U�ػ���q�hq�$��O֦&�IU"�PmnÝ�6���!1Pp��n^ �K� ��R%��ˀ$���[�Dd	�	I����Eo�*U�7A�^�oW��6@��a��x�{���#U8bU�Y�&B�2p�H����>�QjJ�fp�K�y�}���e���iJ�Kv�K�5�`j	����^��4)u/qK��N��b��uc��Ҟ`	�.a��O��%��\Hׂ��H��|��~h 	D؇oK������w�U����`%�M�D��3�x[��e����.�$�/%�0�F�	6�	j�RB��/U�9�w�Hn[�L��+R�٪vuL���D4k��;[    <���bѽm�����Ǵ��r�����Z"��uo�r�/~�]�n��Z
̻��ٍ��ߜ�[�n�T�DY
�	��P�]����L�B$P�"�*	Nh��?�4Z��d'�e[��E�b��t`.�r	\T������{I&���#�)+@�4q�����z��|]B��\K�`�y�2�)g@���M��Hn0jwY�d�v����R��}��(ګM��}y�D���^�i�=U�K"�\� D�_�)=�D!m��]�GZ���\�\��T���c�|'���t���D*�ߌ�Ϙ�9b���RL�Х���@|���F���T��D�V�?����TI3��h�G�v���ػ=�7���>�4	{�J�U�Z��#�T�TE����Z6�����Q2W�����n}�\��R���Oh��6�/�����U��YSs]*��R�}�՘
&���S�{��+�����2�@�\�KO��J��8,&~;��ƿ�@�&�e��d��1�R{���W��J��A��o�JU�!�-�U{T�܍~�ڝ"�v�8�̈ۛ��B�ڝ"�����W0���z9$.U&��w����$U-l&u5(���ȑ�}J_{�J�]S��| ���*��]h�R�B�E���x����3U�̧�����;����������B擷���y�Zbȩ���ܧ,t�^I_z���A��,�(B-Ur�:4!V �����Ŀ>�9x�~^�����4}Y��J��W�u~�(ޚ���#*U(*�zQt�P{q�T��QyN���6��<V-ǘ��mZ�!!�yx�	gTj�'��2��+�lq4�-�xe�̇B���2�^ΖW�l����2�e9�dI��q��jk+�$*	'U�Y�� �*��������\L�*����K�*��S�e�P�2H>�	t�J�?��=��ɣ��O�.�1�E8chݽC�?��T��98(܍�J���9��K|�Tф��=�?�4�	�8����*�ࣰ0�E���{ʝ�9�CIU��A U^��ڍ@�-lti�ٿ`�1&�4���(෨o�P Z25立�n��Ll��TY� _炋U�@ m��O�p;�F��;�i��T�����P�V��H�v����y�J�rsKe�Ef;?�ӑa�N������_������2����")V��ʋR�*������|�`9��R����˕09U Q���9���.���b.!��:C�}%��&Ci<�	�a�6�람�M�8V�H�f�[ѫ���pIb>W���L_��;MYA�?��P��@����*��;bu�ZBi��%��jV�#�Ęټ��'�y �:K_Cw�?/H��&K��PBn?Ww!H���'ߘS~{�����=}�D\Uv���x��`M��9�'����op! ;Mj���#h ��&xB��j�NՁ6�4��"`���4QMG��:;W��~y��V�X!�H�Tv�������o���b�y���f�]-M��h�xJ����zF�����I���N<��^�n�����X͆�, �ӥ�Ԛ�P�:;c.m!VbQ�Ez��L�;*�;���rq��{��|R�"�G�Ϛ��ɑ7\�yf��([ʛ�S����.G����8H��h��t%?,l�A�P�w�or�S��E��Ƿix�O��#n��e-���g��Y���`�����z��삖uH]g��i�t��-W�;��Y5�V�)�z�s�Rgg��)����eg�>����Tv�/9C����yJTv
���nIC���QV�~[3% �N�}B����GCeg�>y����Q�T.o��6;}�I\; !�I�O&�f�/�Ο}r�E�z��y&�N�}� H���N$�{M����B]a~��F���i2���+ez�e@v���d�����P2.��)
ɳ3ș?�uni�3�������O��.��;�{�޹n�`4aB
�0_���z&tg&�������t&%��%�Ԯx�ҺZ�[�-'�k�+��3�`'+m4�Z��aJX�������X�MT%4Z�:Y�݁Fpk<���hr,�]��-#M�찰7ZG`Ѥr(8�GXSkv�̦�n�E���QE�`���0�F,7C�؞W�]w>�����KI�k�1�Y�4;W�I�R�y���Q�
�Ylx�E�, �<��~ݟ�'QWyj�gzq�\���:�t/G�mz����m�Y%6�36�gdL~���]��~L��J������l����k���x��՗-7ޘ���U�A�3�k�P�G��(0��T*E�v~CY��):�9֑ʤ��/�;���wT��S�:��j���!�r^)K���B��b;s�v��h5�bs��.�����iZƧO�{k���o�=�(����2y�~_��_.��LYq�����	U�\�'6G�J%��3��j_	������?_�ϳ�E&ME=����k�g�cR���l��x'>��}��k���>d.�vfO`-�L;W~}���K��?c���2���Ӕ�+͟|��h��,��ʴ`�>*�Di�?Cn�����~�J�*����+�%'������$�3�ӈ�.���9.)ߑ#�3����ܙ4��g?g�?G�(-N�g:�}W2Gj��&��|���MBZ��F�rQ��ty.�@c봀y�Ż_���6OW��a�3^�p���Јݨҧ���#�0��M^�F�J>���n�F�O>B3R!g+'!��0f�s������69��7�A�6G��\So$�r����,݈�Ƨt��Q�3LOPq���� ~V;�v7�JU�>;��	:$H�qn
(�+��0iv+4��O���A���1��2j�y(�l���H��B�$QM1ٙ�OB�z#|wq���X����!�7�}�V�uف�8�.�L�F��<*=@ݢWٙTlA������P~-8�݆��d��W�k���y�O諾�����K�������'��N�>�[!*;��򢈐4;�
��rt#��:US	�%����%�pv�Ig�������1�\Qٹ�mr�|lv��y�nɳ�m���x�ؾhMv��|n�d"�3l���ii�Sl�"u�H��e��
&�|�m��[���i�/^�u��N�}� 1Dl�ߙ�f��H��]�K��%�g'�>�֣��Mv^�X�����ׇ����Z�%V���Z�op��T��J���uv�k�B5k�:o+�)E>^v�퓄꯳�_;M>�>�:Gꅜ�ա���r�o�cd�T�t6�}���ɅkU�p��6�Ħ/N��;HSH�B��$��>��z���Q�ћ+m-.�F��.���巪��ã�s>7*���!�&R�����^�ޛ���Y��U���>���$��+��D�"�;I�IR9��x��Zb�o����h��nuo;e�NL�r�j��[q\�iu|׊��On\	��D?�G5��N���m��'k�a<��]��m�����#�w9�=P�x��N�����|Z��L�8�ӱĦ�w�*?ys|œClZq@��8����4Sg��>�H[qF(�ٿ�m�1-�wu�`�/�d�j�M8�[qf -��p{�cg�鳓
a�~�����Ba+�l���'�`�x�E���ʴ����[|�ފ�\����N�Zq�0
򱏟����Ze'�>�Lq�V�YWF�r������b�S���廙6;���m�����yY؃1�ٶO���tBKvPP�6RB�m�W����	;CT�ܚ�
F#@g �]pa��b�����N�E�{��!H�f���%0�~Li'T��vm��*��3����k'ԟ?�୭N(ASy_vB��ݑ!�	�d�wB�������e/�Q&�2���Xv����ƪ���u��������w�?�r��	-飺p�d��s� j�`������_9�>�I*7��
D�C!:�}�b��:�'�s���Pa������z����d�� �Z�e�rr`H�GtB��6��b�;�I}t+���(�s"a������!1�9qч�D�JOB.99�".ds����	�    �=��;��٨�Tf�k�m�Nx�|pb���AN��rI��i�ݙ�ɠ��T�;�I��/�-����a�K��wW���xuN$u� �p�����0j'��y�}��ɟ�oٖ3��ߏMN��9����^ϧ)��dͨy�=�~�kh�8��O�׉ɉ�}�� %er�e���NDUF<LN���r����3r�e��o�v»�#zNy- \�&��&�	�����N�'�unz� 27SET�x����;���a�oyW������y]��}�-׹٨�%έf�owη������'�C�[��!@&^��x3/p��4n���M�����pryw�C���F'>��Fw�hr�Ә���HN��>`���b���l�69ٱ(����"mNf�`/@C���MNL��E�u��4�K��͘}���!1��j�����}�����>`�إ�Y���l÷��B��"���~��6� ���"l��\���FJ��8� ��ߗ=q�n�u���V�r��G��v�6���޽7T�]n�w	;2�bZ|Nn��)��.'@]�nד��.a9�!B���n��Kf���ރ��r��Ձ���s��5�b�5p�	Sג�7�浀�Ǣ��[�'�.'?�b�!#`z�4��T��%^+�m��}N���D��=�IS��\�����7xWDDXNxz�0����IL��F�a����>����C��9���O��u�����	�OX�}����W�����FT\c��;e��S"s�����'X��ɏW��z���s�5NuȺ�I�灪�nNx�cW��V����Ф���Yo�}2k&�:��9*��WY)<ɜk�@?*�����1���eՊ��[�ާ�ɳ2Rͮm�VL���i���t�X�^8����i�}�!�����Iu�TS�ȑ��/&6ibk�?�-�^x���ݴ|X��g��m����l�D-%��ݜ�q��ǾPv܀�5�i��_�p�j��sp��f���,��^8�=b�Q�����y4��4@���?$3F�C��"3��/�Y'\z�M��	.����^x�=Ҏ��}��̔ؿGN�腓ۓ����xz����)o���z���hU��G�I����jU�¥��I���`��̄)
�ӵK����#�H��p�x�E�2�e�����bud~�ЗiuX~�"��Q�M����(P�MH/�2zs�J{�)�qDor�s4���ݯ�s�:�� ��%�Ų�a��vW��v=]yV���&�.̧=`��v���4�\T��?����ϋq��"N���L��8��~4��.�r�&l8������+�u�R�Ƞ/c`���7#� Q~^��ƅ4�J�a1=&��R����jAJ���ҽ�~��?&8~b�JdR(PWD.��P68��mf 6��&(n�B���#�f���UuZn�S�D�=���	w�it�
Ŭ�E_@I�BQSAnzk��X�PTm��8b��~\�P~1c�u@�D[E��"��;��@���n��d�Џ�[Tď���b��j�UQE��^���_�ZE��bVV��<�[���s��j� P����v`	�w�c�_�	��xjp���*q1�O����4m���q�	�%
����VɺYV�^N_oT���>.��e�,����F���\a'�����<J�:A���� �u�x鯁dm�}�\����_����C��i?뵒��|�ڏ�d�(��Ş��pu_�娻��WV��n�&�y�
�H�W�C���dIr�6Bdљ!z��f�n���f��_�n��	���I�e-���}Y�d�M��j,1��A�&8o�_�&#V�K����|�Z�P=�o��d��Cd%�D���<|q]�Jc� :�ЭdM�`8���6+Y�%�����i{zi1��,��J�x�Ю�@�|`Lï�k\�[EuKjп{,
@;��-�}�kB�G���@І$8��!d]*ς��K����KK�l�i�G #S� �.�v�V ��0T�T��Ⱥ(̦6;w �.�PhF���ߍ�/k��unj}��0�p8ŀ*��F8�bz�J!����@_�2�^�u�PtH�������W��\��Gv��@�.�N�FR������8O������`u�ep�O#B%c�{�Y!v�a@�.ڭ~����9��� ���2��-��g�>������j����i�.��N�
��^�x�.����E0��@���p1ԁ�\�r]t�{�*BȺ8���Ŀ?��
�����;'�d��؃���b��@������
ͺ���h�^?�qϿѬK�}�eO����"6l��nO�O;8���ج�
T���a3���|��ui1M�� �%b��R��J�G \�9D���e�>>�ES��}<�ޟG'�� ��.X�ׅ��Yi�,{6�Y�~'����1�T�Z�Ra��J�C� z��Q��JVE�h�!O�$[�ZD]�>��;,��Y�4Q{���v�-��'2�d��f�,v��"�g4mV�\���e��W�]���Z��@�.�Z��91yө��Q;m�vuސZښ���j���gjf=�tu�5A����@Y{) V|B�<J��f#MV`
p�h++/�F~{�%��	F�]
���6�{9�A����6kC�ZV`
h�����땬�`z�ʽl0\��5��Y;kMV+�hɉ�����j�\�k���,o��g�)���H����k;=�z�
"�m�4:�f�����DݮP�sg�'�g�a�V �-��8�B�~���J

��=O/K�~"���؀J��N���~��*��d� e��5ޙ|ej�Y�R�b!y�
R�a�H�B�Y3��uB�6vnhe���z�e��c�2�~�=�W����X#�x�8���2&t���K�����od� �4��Fv	��P�A{�"ڈ�Y@�"����2e�<f�[��$F�P+�����aH@ٹھ�@ɨZ0� �l�-�j@�br�'w'`�3d���Z/̙�䉍4�e�������a�'l?O+/#=Z�-.$�_��/��I�a��Uk/�\���'S���>t��k��k���BŚ-@��!�H[�_��Pun�`��5���Lv8ȟ�g��f�pa�]��j��ڃ'�F��Oϩk�vk���~������א��s>h,�N��� ,��;iH^��1�;֤
�YBo����
BW�,�Q��Y	£���u'��Bg�~*�Cx���omR����ֶKz|^�nwr)tryɰ�`Z"�Yb�������K��B�<vry��'�2��@����ǣ�O^V��|�,�}��}�`e��,�,�˵u'�}!�\�+D!�����*,�ei���O�\UK���-(��x�`Hv����Nڍu��fϩ��<;C�j�����Edgʳ���N��
��%^-&;Ţ�7��c)Z��<��-yv��Il�u�6� |ų�;iz��t��D[Z�dIx�|��� �|2-7iz�z�h��Q��W��sO�5���4=���2�u���O�4�E�dg�,���Lvҍ_�y��a!�i7ˁ ݜt!D΋�d���љ��⭥=���;��qS{�$�:0;�����Lvֽ��ˠq� A�`��b���XLV̲���J�q��rPD�BK���z9.ŏ�����JA�H�P�uVj�*�����8Ծ8�zx���+��k�:+u*)���BQ��kp{��XXV ���W�zd���|ۢ��F3�`#����ƅ��K���G�ȇ>Xi?�D5Y�h�B�+��_��H��H`J6Y�h�@����IV`�E`~a���E�H��$��
<���^�TeE��q,"++��Eĥ�U]��x�p��l��~�wuA��<ۏ&<�̢<���߂�j\���*b'�nU2=/�D�>��Lxږi{Y��1j1�e_ۈ��ڗ��Qf�d�\f��9�ʗ�>y�����GB[,�M!��,�-�Z:T%�N�8�w����#���l�U�    �����,����?D�زL��z�^�O7�
9,�<j�k�J�8��Wd�<�e���g/��Y�Ɨ�i-�B�R��@�򴗅v�C�<��@7[Nov�`����\�A�^����#3)���A�#���/�@����T�<fYX�?��a���/�~�ݜ�dp��.�At,����y��
L�����GP#�z�2�Ph�NF���<��Tn/�vYtt����.Շ�b����,�]<X8��Y8�@����4�Ev��޾�g�,�w.����J���Y^���^ֲع������Z��ڨ���,T�m�-�pz���O|���K,-�:��iM��m�)t���H����0s�湫K>�uړ�ˣb�C�b���tAc:u��DHӱ��y|�*ryYUp�eR�!U��E����\9Hh2�f��Z�q�}�2q�!�����x����u�s-o��͐����6S�] e���(&�x��G�>C+j�-����GJɳ�U���͗���o"}n�8�c"�87w���V@�ܼ� �~�e��͜}j ��M`�"�oWh�0�1�Mc���>�>G��Dru}V�@�r�wO�̇atÅ��r�S�8��s�����"^L���V�47?��H���=�Ͽ��.Tnj8�`.]�Ĺy�0�j�S� K^eF���^��%P�,%\���Q���ۆ�����R���
H0m��mR��93M��ƃe��>y�����C��,�@�QJt.�G�D����izwA)�NAB�%T~r(��,��բ�;�Oo�u��Y�k�Ш)�������J�'>���r:�H��S|��W�J�(>e��`P1&������.��oX�B��<N�ع3}~&�߭$=�7"U~&+/� I�X��>�o��ў�����9��>�LJ�P���x�>�ӈH��j������vVR�5E-��IeP�g�2�{��+k��������Ne��K�GN�BR�%՞y���	��7���R��)��Rˉ[�i���pŤf�V+Ԋ:jߟ��{ r�B�̥� �
�>{M����ZNu@]s(�c��"�f��	b$��]�n��f �V����n0�7��~Ի�)x�c����������6��Nh�k�2��uؘ���oU+s
�v��لgo����ȥe�.x��Z��~���r�CK\���),I��f6`ap�~^�F
Z���I0�/!{��5FJQ��\�Ѝ���2A�&�7��<I <w���D&��R��Ch�)[�g���=/4���z�au��I����=~�w<��`�ǧZ*�Uʳ�j��W�Q&*20�L�;Bx]�����|Ή"����N�_�t	,�OX(Np��@���FK��ɫ��,�OX��o�zŲ�G�5:r�<G�6?�6���ّ���	��Ƒ��2`����4`����=���J#`��r5�������}RR���*m���P�ܮ��1�Z�Ws^	t�u
��2(P������w)1T/�6NO�4���ʨ��T6rZ .�������K-R�:��4}���v�i������*5��xfh�$h�f�)��V*B�޸��ρϭ����3��C��V��T����y+%( �n�ּ_��
�>�%���j�O7+3��^/t����tda=��������40�_���"�+����z�ܗSu'�G t�z �
0�I�s��U( 5ڣ'��:� u�uNXT�^�~�ᢧ��O�-��N�hU�A���h��h���^������ן�Qױ�KgI h��6�'a<C����yP�����	�P��zw_��q�.U�@q��j���oT\�7����k��+"����(.�%��R�f�d�^����E�О�?�R�SK�\!�ߚx��re�צ�D߮ѷL�%A�"-���ʅ
� y�F��p�t��_,���Ύ�^KB�J��^JBz����'�Zy�A�(�O�,���L��(&K\��C��Mn�=��م����+4�>g}]'2�O�0��D���rh&�����h�	Z���ս��n��y�iCj���?aS��u�@z����Ciq��<��	��_p3�LN���)q���˟��»�*���?�L\����u#�n�6y�P��ˢM�AT�.�O�4=i��+�.�2j�K���7������?��͞Z��0�����M6���-��Zk���"��?Zvz �!<å�O?��Џ�V��m�}�sg���O��4��?��Tʘ�aUG�Ts��m�������pUf��)�d�∓����Ol���8�$3��]U�^� �?�h���"�e)��m�^0PJC
�⏳S����SLĀnj��v�RK������������Ut�[�_����?��0(�ǟ��d��4}_)�~U$�,����˪��"K������M\�ppѳ��q����6���h_Eנ%�j�A��L|�VW�����4���޵����Y�\,���W����d����
8��_�J�n3D��8�������
�]���E _��{dSEE{XJ-��K%@�?H8���T$�E[.?DL�j�T���\2�D�!���=h�5���*���Iy��|�_uU�Ž a0�'M��d(�GO�fzavE���U�O��z�	w�"��Ri�>��?�V��>��k�D��]N� X� R�BԉX�(�@��g�(*@P��D��E� u�@��� �P�΍^EQ�\n8��SUt�?WCDS\$Uѝ�$���BN�b1�7�8ʞ���f?�cx�>�u���P%���$;i�mt�+"^KQ�Aff�G ���h)�� 8�������c��?h
ȹp1����=C1�=����q�^7�[�S�T汪���hv"�.���:��A�\*uӸrr��5Dws��5D�si��:�����yش���c@5�p?8���h��G��1���,"�=�65��_3�f�z�N.���
����� r�F:�[a�SIc:���G��\Bn8������v�\
��ѝ��G�}>H1F����z֌��%@:��~�:�@�X5R�@l��U�|Y#v�&�w ���s��I6����}�A�/�kG�s��P�c��t��F��j�/5�@�q>��\B�_�OsPX-��aa
��A<�}0ݬ- u �۳=��n�9s����#ҜGT qN�s�
����4]�rppԜoT��]�4�����sy��Hx�i���5����(��<���.���?�B�O��I�%s!˚��JPzG��$�IM%`�į9ߩ�G��%bƗIY����p�R���]���R���rSCMC4'� �� ����C��ErAo�=�Lv�#�j4;JІ�Dn�ٽ�.����s�z�2�p�-�%���R�b���u�VM!����p���6�TFs�Q	�n�q>3���@7]�4��K%X�V�qǏ����0B��;��
�调����SV�P�V>�'�Pv�N��P(��wI��V0���[:k��Ơ
E_a����M �"��{�к�ByG��/����m8�O�h��N�7����e ����W8?+0(~�/����y��/�}�a���t^�x��H+����ρS����sBS�_��~���-Ծ@��?W�>*�(Ԝ@���d�`I|`R8�@g�K�'kws)d�e_a�W�&92ET���O	�
�X��5Z1��	d��>d
uБ<*��L�2�Z{�r�@u�	th���������TZ]��ι��q��Vk��S��3�N4 u�� :TO�h]8@��Q�ߧo�T���.��1�3v@��+ܥ�n"W��-��p����M��p��T���n��O;Qf+��ݫn�O�����b���˿�M~�0����w�k�����?�������U�|�Ty�-.�?`�~���^��-0+�M���1�6��\{��s���b��Z�LC����q���l
Wvs����A�ȤlȀnn3�B% t�)�4}a� b�i����E��Q�o��2��Oԯ��@�
�<N��$����8    ����y�\��Pk���$F�k�	־vK?��i
�W���|��9��'< �˯i�Z7;�M��	t�p򼽆O^���	K�;/,r(|{?K�t�:M\-�e��3̙�m��l�)��	\V3��YTn[�h������׵-\�m��@�P���p*|-ut�Q� .\�@�1ܯl���F`�ݒ��!l�o��D�g񃴅+�W�U�����6cL
۴-\�\����s?��
�ضK��V����Ӭ���E�P	 ]�N=���eO�ȭ+T�!<�߭����
�yK�΍�P+t�V���{<y��+T]��`˟Y��;���h���=�(\�@!�p�tW���7p���+< t�*���JW�>�S��8лB-��e�\� �.�!���uHW�C�@�`/ď�������u�u��z���]�����9�NR��-0(_�i��˽����� ��V�:S�0~�ڝ/��� �n�5¥P���SyZ��O)q�(ik�S*48�nT��B�p�B(]�ۯ���91^��*~\��Or�*q�����.S �m"�}Lu:K(q��|, �u�+���d(�����5)6�Č>p�~��C��a���};
w�Y�� �	 냠jW9x:��I�*�s�0�p��?b�e�$�����@���у;܏n&�����H�66�5x]n��������c�M�����Qz}�5�\� ���2��u\�i ĺ����m~E����q4��a�Q@���Tb��fҩ�8t�F��S+�.?��4���Ҁ^ ��C�}���5��a]|L >w�1Rf]nL��v������ C\�E�{
=|�D�u1
�4�v�/l*�.S��W��d;h��j������}�Zz��g���4�i��FLT��m�N�c�W�ho��bG���X[|f^��ס��"(�o��E�ODFwZ�����zr,�f�~�\u7�~��V�s2.���B�z�]���"<5��[C��eE�!S���`�d�:�D���d�C�$V�OO�( }�Z�0�l��<��~�6�K���1N�tv`�?D{	.�og�h�!1����=q�����JFZo��vy�l�i�!h�s���_o�<ݱ�Po��ƒy��ٴ�fcq�swȖ�6R�[�������6�	��ան�y��-�P����?oc�9U{$�6K�D�Ǽ:��^,�=58UPoE��KG������R����,���o���+�l�⥿�'�͆�4�a)���C<�9�?����y�fcU7�5������nL�������֕�/4���#KM�>�7�He�l���r�1XSՙ��RhbǍw�͆�4����f��nLxx�`��#E�˅��8V�1;c�N�!<mp�K���Z|=���a-s{=�����;jB�i6¤�p
|�d�a;F�sMM*����0�Ah���Q8;ÚUX#C#Ch�����1��đ�G�x5�'���z����_�7p�c��6�G~���z���NW:�a����a�� h"w�����&i��׿]��7u�܋�3���sh�$?�QR����Jm��"(7����8Pt`��<^�-�f�z����@M�0�Z��ݪ�Y�<ѿh#��ŴO��E/�Y5ڪ�_zz=��|���c�<��8��r�ᬚM�
��g�l�W��ug�) �-����w����l�]۬���l"]E*�h8gf��6�y3��n�!���6���aGZ3���C������a�(�kl[< �[�@��=�9��C�"�
; N���)�b8�f����3�(��&=�����ǃPU"TU-�c��e��M*��p��mxJ�9/�z5�Hh�y� ���QV�N��&��Q�������za�RȞ+��g.��nO
�խh�e8;h�����_���Ц��Wy��2E��*��#^u/�[�"f ���D
GJ���b�+U�3���K4�ጝmT�Ң��&�7S�߇�5��$nϚ�10����+8-�?A	$P�F;=A����Y�����HްD��oU$V��fE��J-�V�ǝ��p��'�l3�XA�48؍7�KU$`@�L��?'���PE��ߋ��d8�e��"�?�OrW��QɜdJ�]��W������y�?љ2+,����H�f���N����6P&�`8�a\�RX�𩔕��E�yV�ΒB+w���M{�����L�\��erQ� pw=�w��5��i�}\`\��B")��R����p���	�-�Җ��&>r��t�c�s��ś��䇞[JX���r�a�_�1[_�.��HȂ1�x1�<��1rK��h�0�(>`��P����7�D��߀ w�<�l���`s7!v������U�}��m��=>@���,�0��a2x8=��N<Pb�W���4/VS �f�E�S�R�au��p(�<\u�ו��w�x}�����1b���\��N�V#� _��jz��y�ꂹ�ðf��x�_�
&h x�{_j
�h�>���h
&�q���m*�
f�Q�psԑ͠y]��:���3���`�-������^ό�ۨdQ-��[`3GL���BԻ_ÃW���G)�v�w� ��/����"ͨnՉpj��۸>����C���v�uvD_3�DV���#�PF6��@��N�%y��s�nYG�Md/:sOG�bU�����i��V�]$7ᖨ�f��r���鴯�����ˉ�A��$���g|^ᯑ
3�i	��J��ŏ<�>��eL >lz	����&�I0P�I1��v�Y��et; �/.,&rcH��D�k�{@G�$��H*�e���L-��Z��>1!��"ɴʏ(3UĭD����SxF"�E{�0�<=áK�0�,��q#�K$R-���Ba8j�,��<��%�D�qo�GG�$�N��D�̳�C�R��}[ʞ�lt4J�à]�����g8Nl�G`ZR�u�=16��Fǲ˲�K�0��C��g8#�D u�Q��Q%������7��$ag�םǭD5�1�@%b���.;^t�L�2�x�4�KD�,���.�ǔ�	�"K��K��,B��D��p������Mw��_����d�򶟞ol�G�$*�a�p��o��K�ą��˿{l�9X�E%r�{��?:c'��)>����S��c�c$x ����h�"چ�7٨Ҙ�����i<�J��R�+Y ����
�.S ��DhP�܁�ĢDf���{��~q����<</�!r$qIy��::֧������B&7Aڙ6.���$7q&n&n8Pr�]3�>ݮ��'q�5����)j ��`kK~'�¡�K胮�t[�C�:�[В_	D���I�A"��Vf[22@n�N���| ��kY�:1i_�$0O釉j 	��Mܕh�0A��w�k�m{}d:���N�7�+�+ �:AA�J>�%�s(Ϯ��+U��zβ�M��DԺ�.5�<��"w+��3����0�!��ROI&%_�T���/B Zp��e82���@4���}�NDޫ������Q�g@Y��Ѳ�8�/19��#�1S̠�3T-��}��������Q�)y}P���_{��y��KtB& ���K>��K���w�%��£F_��E3@��*�ە�d��q���Q��N�cT�!M�P ��_��P���mxP��<m��-4i�}��,I%���d����7w^g�a:����)я}X��n];2)���������o^� ���������'����6/N�Q�1���G��(���8L#�m�_wLU7���q=i^pC�¸(�g��� w���z��Ufh�M6�������Mn���tah�M^p��+�Ԯ�x�'�3��-P7��	lt�#�t}C���L]�$@��Xvu�g�k�Ȓ�>�~�{���8���
;Nـy3�����Mt]��w����J�]�AJ$�]G�e8V    ,�j�Q�e������6V�R9V�A���T5�{ǩt��[I�T�B��n���7HvQav�q� Yy�c>�@�V8 ��Z$r�'��A�ҙ�g��)�Ǿz9�8 ��pM��PC���ք<�nm"wxV����!�'E�{�i"�v�G��2�c;�V93�O7f $,R�h9g<���|�"��/��am�=��[S�猡��纥�ֺ�|n	|��l��iƃ���<�����門��:���|[��\�p3j�e?�>~>o����g*���)�G���`�fҳ	�-.m5�������M��ۖ�� �"ؓ��m��+}��f�-��Eh[t&t������I����R{?v[��*���ˠ�B�jg6�����2��J�Ѽ��$iu�@ڨ��n�GHZ�{�)���U�D��Q�F��Za�_�Y��0��RQ�Q@J��aֻ�J
N�2�t�\EC��7��f�ٽn�ߟ�9a�z""�ʒ��ȱ_����A�'FMt*����g-6�Q���ꪉ��f�0 6'��`�C���L�&:P��K�UF���l��kq�i���SK��@/V�� ���?/��|^�_ɽ\�$ �O/j?jI#y�H���|	�O��,�#��烚pO��ZÝ~rt �aJ��=k�&
���Q�d{��C�]td��a�^z��+�わ����@�� �"�ڏ�1:0�0��9�|b�0���t�#�nl�������^x�V�UGR���D�)�r+��+�e�����#��}'�S|*�ntzxe6 ��K;�*����,[]�[|P�XM���v�v7oSK�^k�A�߿���FSmM[x�·�F���Ut��6���,E{]�����N�ȧ��f>	��9��<~[�Z�hɬi#�p�IX\��%n�����P:N"�,��ϫB��F��&�N?'����M.����F^�MtMP���6]?�£!��& ��!��FF�&>S���82��7�xf{����-N���b�pb�S)QUx�k�#�&���(�z�����C(s��̱Mf&`/ܨ�L�M^�ڵ���"(�L%�����vYӎ�OeX�g����F�ٛ�z���F��}�Ɔ�T�C��m�*�Z���G*��~�<��!����62X7٫̩����MVґ�F��&�M�W�3�"�ہ�yz��	DY#>�n�"�ç4��"k�ձ��Ef�h�=��,�v��D?��J/�6�H�����$�(W* ,չ�H���|��ȩP���/����_��:.�`��i�*�pN�k��6
p������ �a��oN�{�K��	 ��N���h_�1��qr��}d����t�X��to�K��H��hlr
/��h]l2Т�21?db\�-_`���dQ����h]m�iv���a$˱�,�|���pp��\~ۼ�4
�._����67��_��C��mpS\�� S��8Qu�>2��X��Bt-�r������]m}��Yt��K����6�>��ciQ5Bwc2G߅� ���R���"�ĥ ���m3��E�,��aaC��&��������@Qݞ$��<��ɺg1��(<�B����Z��4��pd+T�+B=๿+��OI|ܢ�N�����`t4 Q��gt0 �T�7T��Q٧$V�|\`,������$��B#�!.&���U��9MB�K�7F�+�0f�,ݥ�Mu�+�%���Lv	b/��[��!UhPYh����f�"��Bô:�U�RH���Aq�$���j(85�W���j��5�ү�ΜǃuV�D��j��/�i�Ć�(J���D��Ft���W�(�i��(k{�#��u"p4��D��U�.�@���4�7�X���23���7��* �K%���J���s�T���,Jfp��ȟ��m��_ڵ�7�#�u�*��z�'�Rv�M|�8�����u�*U	t63�ż�$(I�R���q2�d���C�E�'�D�p7 u�ēJ���c�O�H����H�L�p��"�6rO�H�V�15�䉴���-S�͈��/�?%B�P���Q��P*1R�Ii=]�^:�'%���u^[W�eނ�<7W���,�*p�'!�����O�H�i��6��]�-t�^�����1��]!��.vF~�$	Y�(��������ђ"X��FG#ؽ����n��dF�2K�L�[e ��}H�Y��ĹK���QB f�#O?�P>��O@g�~W5*U�J�����ܑ��f+~�+n��J6ܟ�g�R��3��9���?#���
����5PD 7;'瀅0����h�MgkɌ����^���<#6fz9�~�o��NR��^�1D��˭K��K�X{��6B���m�@<.�f�~7�ǋ�F�D��0#'��u�h��76���v������/��D`#��T��|��} hFL�?Ǐ�$/���g7��B8�AIr��m2��V�N'������4m�,T�?�8D~҈f�t��n�?���bk�	�ZE�@���x7�t��=�ϖ��8*�5�G�M#�@R�B<q�G;�L(�vJ�f�vp���`�Ǡ�v?'I��IBgB3t&q3I���4QFt���Y�]d�r[`4~��v1�J�8�i8g�88x��n�C���=;N/ř&�0\f7a�)��$S��ඦ���'@�z�Yo�5�LR� h���Lf��mn���T�1&�Y�P~ �Ō_��X�W�ȬTH�r�Ȕ��g=�0I����t� 
-�� �
��k�4N���.�v��pϹ�j�լ������nq�i{�h~�����M)��>'��AkN��Ò���a��8<��ݞ�
�i��W=��YɌM������]��P��_�5xV^�	=�a��a:;�Q�&�MC0�s�������\y������7Z�ܳ�MUx2]�����j�@kq.I��|����
�Y������LP&~O�
d��\�n{�P�ːB�'�g%��t;+Jf^:�V�w�&�N2 ���M�c�'n
d�Y����'n�c����Ο���Qn�c��hD8g"��{�DW�2�̐:�t*/���rl��������ǎ�Pf@sP��C� ��Mfl�ny�$n�b^p�?M��/�8��9��4�x��%�ՋD��.z�7q�[����m��}�rU%� �KS������El!2� bZ��\�d��i��U����4b�>���ꒉ-��hD���ck�`,[�&�h�>>-%�nlU��r�
���C�_�]�dHF0�!fZL�g�I?fZP��@����lasu���j��f4ѳ}�;�q�&i�������;M�Y2�����&���n�)��)q�uZX�e��\��������!�4�L2�Tf0V*Aގ�(7m��5t��sP�X����٬���(�z����H��6ԟScv�TZ@�͏ɘ��W��ݢ}t��H���`�m��!���M�~�\l/�Ich!�#�7�����i0Sm�Y�l��?���6��<�&�ΐM���A��i���)S�m�3��$�����B��A�vW�l�͉?��-}�W�o�����@2:|��
�js��Tzw��ti��ʨ�\$<	!?6��՟���`@� �2Ӎ�}��1
�Z�o����86Đ��������ʨ�],EJ�84��G��@*��ʆ�7��_��U?T�RI�-���T�z�4�~0{�C?@�:�r����_J w/ ��C��-�@�e$S���݄f��&}A��YI!ˣ���'q� nf�!9� u��˦�!����&k�3*tK�$���*�J�xAh�YI1�?9�N���d���'ƒDv�n�:���|I�^���l_ �jH>���³$'uWu��8	���� �ه�P��g����4ca3`zAj��	D/���is��������{�y�gV����87x����+� ̦U���#]�P����߸�V��?@���L������+F5�bp�6�B|@|�d�Y�=���AgFd    �$��e��$��|=Q+�/��_�tgTe+o|�}ÝԟZ��M�Ͽ�	k���vj���*����m��\�x�:X�|�j)�A+�Ч�j�c0vЁFtI�78��v���Sk�b�!P��0^HͬRf7��xtv�X�|�i��k���Ю�#�B�g�6\ݟ3��I�{���3�e]RF��"i�d_��QU�H�"���AC��/敧%�pIr?_�KP�Qj��%!��i�'Q�DjiL�����wg�B=�y�<�����3��[������}��	D������t ip����J�ER�n?��Yh��10�_}*�z���#���:��$��I�Y��<O�����y�?z9���/�b���mY��&N�2:�1״4��'�*��h��Rj�ͻ��r�ZF�4�v�Ws��s\���Y�L�����CCKe?�$L�	��[,���6�Z��ݬS����E�f��R�Ο=n�y�l�;�bE��Yi���"!�OK�O��ʰϨ�[$w1�}��b�é�� ��a�
�Q>�6-����cްW���BY�-�[J���ȼx�hsӈ�U��8Mgm┪�V�`T.�sb���t.?�X&޷�r/�����$�¤w�n�vy�W��,~B��V���9�/�U���)�
:ᑔ�O0T��m�~�oab��b���BXeT�.������}s���Gs��%��F�/F2"��۝�*�<d��A��M��>���o�E-��m����Qe�l�v|��%P*�dB�
cD����8����#q;"�E��GO��A�XsQ�0�+Hh�y��H�8Y���2�c�}M��\��!��C���N2�	�xL��=��	�x�v���8Q���-�$.j�q2'�;�8N��c�7L�B�,修�C�,̼	y�r������%������й�c
8����b�������Ǭ����
�Wm�L�$�b�l��ؗ���&C�g��ܺ��C�Q�B<�B�q�ޙ��3�A�3��L	���G�A���F-(�4��gHC�\��i ��G����XH���O�]���	�l:˯���'>B:�9p�Nr2-���R�]��<��2J6���E	�0�*3RVs��8���堁1R�s[���!g�T����UB�HY�m;���V��<����*.'�᤽(t8sX�sb����B�ݐ�:M���Q�O��aə�#�ߊ��rw|�y�CG��Wy;�֖�'y���03��w�=p�
�hC$���/��?G�%���:�8��9π�d/{����y��5ܬ/4�9"E�"�@��Ay-"Wo�3�m>�����t��A�Hq/Ԃn�eT3)����S|��#r/,�?��)f¿t#!�&Կ�8#����s��@�l��Ì
>W�gu��#��U����4(�|�.������%V8g輻�s����'u�/"��P��-�e�|������Ug^F
f�|x
� A�T���3���X�IrL�n����27�ϯ赗���V�kq-���(�D)UfM�su;?�U��U*!�1AM���0X�}���R��)G*��h_2j��q��F
�Y9^߶d��+�o��^
0Y�90'^b������Q�p����L{>�V?�X�C����BF2�t�]��i��wW�J�ӊ��,d�`�[�`h�o��[�=���`n��Aλ��Q�]]p�)B�x�������ԗ�|��З�����E���k�N�A(j_���oh�����-�P���_�ӑt���/�ءXmjh�Eȟ�!�F�#�G/y�[�P��I��մ���@��(r��9�'���T&�O�f�9	9P*41�9E��A�V^l G~�4�*v0�����������j���%�ehJ�o}_Q��+��%{S�U��N0r���L]�V��ͥ;��'|m�&��W�e�)_eh��%�a/����_"9��)V�+R�1�rp��U�S�E�k:��Nޚ��oG��)CYi�����)�t3�n
��>v`nő��*��7ŵ�c�8��It8U��Fg@��q�m}y\�M��kDұ7�tR�.��dǯ��\�a��B�%����"�Lzpyzל���uF\��{p$�5��P�U{^���+U�~���7�tl��Xzl��㨷�7F3�5]ݎ����C�V�
�=�\����G�m�|�)Q��ϳ��DO��*xW�5��-�$�=�©��cT�kq3�Xk��׌ 	����{è<����6�w�מӀ��z��$�C �hg͋K�'�:�!5�8������\�k��]�o�^s��B�r\�pc�o��}��F�����Q���6#�g-�M�N�o�@�����)�9�ǵS��.�spB���f�N��h�t
�6���fԁކu�*�Y3�PV���z߼��Cd
o�k��[Q�Wdk���S(��ǭ�x�
`�Wh����ZX3�]`�:�Օ� ��F̐�)X2�"5�b$�G`$��7��R�\q�I#1��,��F#A��Jo�!(��40����Ki:�dk6����Ij�<7X�QU���3�?���k��Y�������6�[X��g�ۉ�BJ-�C>9�[X��g�����֌�M�!��l��Xc�PRk�PXc\x�{Ͷm�E������ͳ�Nﶦ
��l��44$ƃ����f`��Tn���5�x���N}@�6�f#7}����bU��5C��u�O�f+)C΂Q�f,X���9�5[x��*
[Y���Qhf+W~�>a�S3k��s��"W��bSkvs���jJ�_ͬ���ߚ1�9ė��^�����t�ĸQ���3w�|;�����xCΣq��s#ɨ�5#��F��c�i5�67��Y3��r��!��8fco����D���ƖnLh��߆ޯ��M]�C�9ݺk�n��n����� �V�5ۺ�i6�7{���ƚ�l��K_�mu�k���q\;������*�Y3������åB���̪�j�
�F5ì=�l栬�l���>�Cy��ښ��v\Ip��ͽ�֌"!��>��.f��K�����8������
���w6֝�\kށ Cz���1���{� ����st[lk�(���$&;BN�qd,]�ʊ��lw >_�����ǑX�����5�)H.,zN�v�>`�n�CN���L+tlk�% _ �ٴ�E�oAb"o!S��:x͎Ô�6rᚗ(�W�E��$Dqc>�01A��s �jH�[�B�>	����*Ś��Pہ23#���۪��b͡�F�^�ш����J�mM�2��k�5�H�XقoM's�+�?��B�V(�$��)G�Cu�G�5����@�?1nڦB!)q�*���Z,�l0 ������8F��o��X�� � ��:�k6�B�+4��xM_�J�<ΐv�V3��>��<��|_���8y���bC�FQ�C��Q��_q�D�����W�j��5'���4i+q(�)��4���8q�>T�Fe��y霍	�x�5kW���&�L�I/�,:e�F�1ݎ%ֶ5�s��Ԋ?0&��r��(k��w[)��5�e���6�B����C�o�\N؞���5�نG3��b3k�g3"�l�JU&Z����xx{��"�[ͤa$﫫��U�����Gh�B�6��Б�[_�dp�KT{�1���V��e.�[߆�:�(��п����o��Ű �k���̶�p�z�-^�ƚ\�P<�e&1�����6�1��t,6~C��f�3�ߩ�*��3l*Ҍ,aZ�aH
��H:�t5���6)1���7t��
����U"�u4Y�X1Cfƽ�C�?Y��N���j�T�,���J܅��n�-����l�>ĩɞǻP��Gj�مܻ'�PL��B6�;��X���$a����%&��w�\?�l����.��<Gi\��%l/�]�y�ۃI�s��쪻���\��ނ&l!��9�)E�2���D@��k�P���B����ԯw���T�v!��z��o��U/ �񀼁t�7���^(ʵb�ǜc���V�ۺ�F�fcis����   �>�Jo����Bn�slS����zU��Jc�>��È��R�/�>��5fd@ΐ����c��oFy��ʏ�J�),�#����PPE�Wi��y��A�/~Eӣ�*_�*}�=%��u����@%�
D�W���9wA�!�=BV��89s��8-T�ꦚX"��L�K���S�Z�q޻V$�ɂ9 0��|�W���SJF�jGw��L"õ/�ݵ%r���D1�U|�M,��I9�D`n'�v���X���$�X0ד���K�-�&���3��ZG6�`ڧ��^�&�����SFm��7�$��g���7]T��evc��?�RG�lK�jL�k%N�b*�"��.h��>W�B�=�[���4V�[��~R*kjA�8_f��lA���י-��*��%������/��:�*}T�u.���7��dgKz�l%���.]�$ߒ޲��n��X�[u����CA�����ͅ^r����l���K���='���&�ݒΖ����%���ͺ�)�M��8���wK���}�é�^�Kv�������i��d/9�y ,2�jUP�ڗ;i������|;݃n�,��˜�S�gs��uR]�%K�l�{�������钵y���r(N�/�K�ϡX�'ɥ�$\����b�J���iQK�,;C�ܶ�bxPA�s_fR� ��m6w���y�Kf�iGӬ�C1:ɝ-�Ŧ���3&T~�e~����ÿ��PNIܳ����@2��Qf��rL�{H�5濖��|������PfZI����I���-Yg�J���$�-Y���]�zP�w�d�̒�Ư?"�%h�;q�?�G[��)[� e���,UwdZ��x+JN�z����%*���b��\M�%��]���Fg"�y����-Y��U+��O�2ۉ�%��"��>�kO��E�[�!����}Č@���u�����`K3���ř_̙	�,vu�ǣq0_�^�V�$��_��vC�a���f����w�7�x;Ƚd5ɋ`��Z}�uNɗ�0f���N�e�|�"`F���Zl2�/��f�N~��� �ْ9φ���M'.�ѐ-�� j���u[2�̊"&ߒ���v,���^_�i�Z�<F �?��%���?���G(����K�'�>Ŏ���Ql�Lнn�_N�kɄc����
�CE��d�h�����u�~F�4��-��ɓ���N��X2��b&C�y�[2��dt:�Z,�TX�i.���S ��,x�u85_`��B�-�E�dF`��h�<K�E�|����f�D0˘X�K&�n��"��d�W "q^��iUX�׳�#��7�r�4�ĳ��5u�\2JC}��7?\��� ��uY�d�)3�H�C�r�(M��҉��Z.��Oa��L#��/�!���u����AzKfKYN�>_�}�~^Z�q��д����9UnC]W2�G�K:�uޕ�%c�Q:-#|&ٷK��:����c�L�Ӊ�"���%+�֓��@��K֓m6�^"��f�9%Z�;ɾ[��4���l���=��BluwB��H����4y�a!�n���Y�,�%K�NY�r�JT��A��n��SW�vT&.Y�d��ӒrgF+�=�I�O�nkvKV��sU �E�Sz����Έ�9<�\�`w����Ό�����Fn;��o_�����@����h��mP��tp�/�l}���]= k*�s۩�z^wM{�4����'�����K�_�-��!26E�������r�?�	��}Y��P^���ՠ�)t�6�D��m�#�at1J���d��-��C���Noi��l�pں��n��@��7��+�w�:E�@64T�D����(�AA��'����
 ��`CC�O�U�*O�icJ��܀�Mc��9��N֬(a}]�����M��}�k�ܾ����vZ�����}�n�n?	7-Tf��t����8��+t�|���%��˾8�9�j.\��m�opE�o�mxi�'�����ܾ��N@�U�t!vZ��+��|6��v�Q�w��
y�������mh��1�?#nZ��g�C=U�GߪD�h���6���Q��+S��k\�8!dZ���#�޻բ�W�L�|����nM��|���]BL
�ܴ������KO�%�}����é�Y���i������qCa�/�l��ٸ���1 ~��w�o�l������T���F8���!���z���A77�BXP��	tr�d�2gD�ʸL�i݌=f+ V��VOʄP2{�%ӸD�ħ/���Z�M���Ķs"N�eӨL�l���J���R���R�l�Xs8��ӨB�Η��+�a���a�i�V�8��S��M�v
���!����=ȉ8����ٻ����8��W�q�_-լ����b��6��H��7�����RLFs��W]%��?�>�)ͭ�����xEr17�Ҏfo�6�n�m3�ߘ�XD�̫
Dӫ�����h�5b��`3�3L��WEwz�Vl�lj�H�i"�Z�T�����'�M����o����?-�f��_U�ۊ�����r�$�ZGC��v�r�١#��աiA�+�D*]��U�'���o�~}Vw����|B>�/8���hZ4�h�w|�c�uk�d2��bb��1[m���O��m��/fZ����xzH��3-���0K[^d�<ٔ,6�KsG���J�Mj{u[U��B��,��/�P�N�t�v���b�i����;~����}tlH6�us�M�"?S�*=A�I	e�K�zR�5�n�Q�Y1�&����?�?�F�b7�wa2-M�5:)
�t�(��Y�W�T!/?y(e�c�'r|�8|= U�Paj���3�b&qi.�E�Kxϴ��Ǡ�7��7�]T��`�%-:4m+����yz�'Z��rV���
u\la)��2qÑ����-`��(#82y����H�q$�1�6�.n4*Hj���<b�c��(�4k<vC� ��^���c�ï���T��!�
�������u.��p3|�4N^M�(ݕ�����])0ŉ���ǨWRf�'}�7Y(?�5�HGQR2�yljN�Ի������S�B��skU�D!�8�e)��"U�i>�%����f:��҆��\���n6_�T�S:ǡE�����B<#���pU�s�+%^F���#BQ�K��.���	D�ܷ�x�Hs�j�'���A�;C�	6���#�C �@��H�[*<`��V�_=��������Np�e��R���4�P�y���W�0N���4��Z�	i� �����'��5�Yt6��&G�����4����)N@M���3UR.)�-���N�燫�"��Tӎ2���,NXM�x�P�����*t�w;����T��Ø���u/�!*�(Z�{�j+���%0��X~�}ź��G�l�����8Bp��}�#�p�y��[}���u�\F�?�/8�K�ޟ%`����e�����z=�E)�RX���|\�}+�V��gJ�Vҽ�,�ҁ����8ɒ�����E��H�fI�@6�l�����e�"Ӫ�y2,}�䴒��G`��W'��l��yg���r���z�|3�K�M���x	���/�y|���c����T�V#�o�yi�uQ�X��{^O�B-�aI�[QTf���M��7+��!����z%���e0��B�Q1b��壀廤�W7t��M�4��f<-3����L:�u�}�䭒�sD�vM<8�f�W�vs��H�4�^6C�9d4E�X�6��R7գ��������_��̘0      �   �  x����n�(����� x�����m����}�)�nvOfs����)��x��"�J����i�ܼv^�>�7��R�pU��k�(��:d�EDH�S�_OD��xo�r�=ʛC�Z�+pM��8��^~*/g=��q��R�}��|85���z�����e)(������"n��u�6)g�����&�M����"L��szRC�3����W\ 3���:ھd�gL^�4�M��Ҷ�3��g��7w3�Ѻ�~�8��e$zTr���
=/� �m�Ǿ��*��Bt1�ׇ�m�qd���}��u];�
f��:���R�jQ�� �L`Uz֒z��-��n�ʥ��c�X��&���>�\|�?����Ōj,B�p������F�d�����Y/��+](���<M�\���G�hH�L��z�2�Ң�X��-b7����c����b� ;��B9H�W=�g�ຊ�(Rs��K�3��j��'2ꎴОV�g3��c�KNdn��<Oу3��)��*l��#��P���V�{�PQ��q���N�ⷹ�i�El*��5�bE��0T&8�.;/xv�[��/��2���%�$��^SF��p���v����H�����CF���������b}Dy���P�R&�b��k���rMtR����q��%�eT�`��p�ǼXqo"o�x��Hڭ3�G�d�������<o�"|���!��~�:�ʼ��x��;��lK% ��ʗ��wg�aW,v^�O .D(�5�u��U|�^�G�^��b^0+o��E�s	�k�:H��V�xS<T�"�?�x�%���|�5{qW�E�M8v';��1~k )�уO�<�v�l�\N�Ӈ��v5�'���n�_��m             x������ � �         b  x�u�;nC1k�0ş�2H��q��} �m�	�;o߯�u�<����ԑ҅��n��#�d\���b��n�Yǝ1[:[:[:[:[:[:[[[[[[[[[[[&[&�$�$�NΝ�;9wr�����)�)�)�N�e�e�N�N�N�N�N�Ns�����i�i�Y��Xg��b���Yl�8��ܛsoν9��ܛs����"$yg����k����>�9��y���z�On�I'�����#�������N�Nm�D]�dS�p��)~�M\u��M�u���e7�S�vF��E'�)|EYs��\�5}�Eas��\U6��di����ަ��jn�e;��5��-���         �  x���An�0���)|P�$��J��QG��DF!������s�Yv�U����K:�fZ�~���#h(�ȸd8x��m�L�<LP�����X����h������7����k?>!��I�1[�����gW�R�e��U�_��Q,9.i��wk��<R���J#�!�Ez���hw�,��Y�Oe&9�7`{U>���=�A̒��1�cS���v@ͨ��%ga�FL�� ���&��/�s�q���`�t)uP�9�ѕ�=�g��Fքe�';�¼,����W65{Ȓ�z^p9^�"�$�%�;��Hh��Ox���%/����E=4���GLn�ْ���
QB{&�:h�� �!O�h�`B<�z�fz�%PE�Ҵ�<Sie��	b�;��X�D����q�2���U�n���&� ։�-��\*@����|�@arE�=�_����*9�|��8��x�~����Ӥ.�&�E��]Lq�Yxͣ��ϧ~�C�I��0��#���Y�)|��(�Q��Z~Y�Qڤ=�,kUG&�@Rs�z�!��ޭ-�bi��>�Z��[i��8P�c����g�,�d��9$U�j_�A�"e�B���諎8n�f@?�Qu�^`�����l_�I!��l%U|41|�4�I��̘V��-�nQ��gRaj��e���m�\K�{6r��	f���_Lgx6���߅�N��prV������1=�6i�C�0��	�            x������ � �            x������ � �         P   x�3�,��I���,.�,OM�4202�54�5�P04�2��2����2�(KM�ī��,�(5�$�B�ԜT�
c���� ��(�            x������ � �      	      x�3�4�2bc 6�=... c�      
   f   x�3�tL����,.)JL�/�,OM�4202�54�5�P04�2��2����2�t�K��LĢ�������1g@bQIfrfAb^Ij1�^+c+#clb\1z\\\ D�&�            x������ � �            x�}�K��ʶ���OQ�����1[TDA@PP�D�@^�<�-��G���X��{{�ݤʚ��7bEM����c���)>[aY�����/�W�o;�𜬜9�3X��K(�����K�������ਅ����Q���/��,OcHrw�r��Ln�p(��GYtRݪ�2t&���*�^��	��Px��-��N�$f�[��`��}Ůn����ɮ��Q��O�~C��0�%�O�'
���O��3�����l]���do�,��;�z��|?��l�0���G�칱
�+i˞�Ր��{m�97>λ��eu_�ռ=�Xx����|=���O�|Cȟ�Afx�_x�۽^l�y��3�����8{:5��'`��\�a.��u �ib� �0�9��]�����f���`���	�a�����.��yŌ������C�}�)�P3�w^�pPn\u�J�-��i��-��z� �axc�_��+���s�����Yb�AL�T���k���R�vb�0j�{)�މ8sv����n��j�7l�`ׁ8E��T�L��8�P�������q��9B�5Y۱˓�΂��\�.)lӫ�NX7g��1�ߕ�=����L�����7�.�\�`j�Y|s��QdI�
�������;����ҥ��q@Q��{ι����h�
�\.�L)�� �!���1�؟e���r�y�{�^ݝ{=�ɼ��(�8x�N�,R��rw	�;�cg���k(W'��=3���[�^�����n�ڶ;<���i�nR=q�jׂN'�,
���N�t`q0:?.�o�nAu�IpY>H��2K�4���8�R��t��r��L�,Pzk]p��z�q����As�E��#�=�w�]�q��JS� ���Af��c7{[��0.����,?�)� [�1߬jo�sn^(�[��t�˘S�o�[�fH9?M�H��}P 3��!pߴ� EU�oF�v|��x�(����'�U��%���(G��/n�v��!���:�E�
Wօ�#�]��R��-GMx2�z�:[���Ma��̵�������L�����!+��!�h_,[S(3n�:m3��R=81̀�N�?�l�e��(��7����f��^5WQ������<���q�� #[q��a���E��v��UO/�t߼#t��[p��e�C}��3=��a�.p����N����z�ᕿ�7��Q�~� ��%O��rS�+������\��-�S4�st�"�sc���ٓ�O�'�Z-��|Y��'X-n���i��(�Tt�8Ag~}�%��^�������H��/f�h���N�Y:�r���"�B0\X=�J��ed��^Q����zD�[��7������a�}��-s
�!<i2��� 5�3�z�C|��^fi	�}�x��z�	�V����%sY�YoqwN��/�2C��vW>��:��RL�L`�'��O}�)��~�wo
`S?`�RM��wX�z��,m2)d�1��"��)k8"՛�1�@��O �9_X/n��o�W$Y9,��%t�x�/�a'�؃�,��oN�~��E�\��"�ˆl�2��<��p��.2�?o���e���̠�l�qX�p��S�GX.aV��mǵ;5,l�Ԝc{I`�mnWM٩����h����,cR{F�c�Y��)�C+ Y��D��n�1�ѳ�� 9����q#a^�vN�����D�Ӊ{&¹��})�7�F8��8�h�OM����e��k���<��|Z&�&�.�Cq�U̲�
�{������q6!6b-�M%-�k��ɉ��}��k��Ò����e�'Y�Q׌u\ڃ?1��� �"�=����Q����X�U�92iKj�)��#��L���س+x�lg��Wx�,�[q����¹@
�cX<�ӥ�x�j��65_�����r�s���G��A:`@�VH�4@�aǾ�Mu�X�2r;Z(-�B�u�ZZƼ�1�8,����H���̫<�<�s���� ���ɥ�K��~�l���Z�B��j싉{��ov6tD��Y�4g[��@�=���H�&���	?ǇK�HA4�޸��P�`�~��˧ib���|�˒����m���VZ��<�}�Z���Ĉ�$č˝v��O]ǀP�����r$_�)�'P,�Y��l�;Ǟߍ��ȰO%3$��~�s�-.G+�.��6��<�W��,$|��d��彴Ɛ7��I�?qr�F�@`x��.o�N��Wj�(3���t���mr��D�4�q�����̢�S�K@�)VU��	��#���@��}����u����_UC�}�ޫ~@|'E�U/���nQ���571��U�qd��{�ǈ��,�`ߤs{��r�m ŀd�� ��ؿ���=Pȳ�Μ	�������+�r���cp���!��$�*�w?=�A-�u�w�dԯx�O��I��堅��1,?7+�����.A�l��$v��
�����t.����*�*��BM�r5v�z)oYC����!�O��Gm�ljǏC�p��1x2��S�$���V��U��V�N��P����N�����zq��=6���{���"��e�<>��#�{�6+#~�^2�$�W˨W�(Ay����w��aC�	�O��Dѡ@>�l[���{Ϣ��ž���?t!3���G���W������]-��^G�	&�tq�nz�c�$ή���3�n��o�ڨԧ��e  �Y9����q|�/���(��bq+c�
Q;s$Y9�甆����;��9�IFx��2Q����m�F� ?�糔y/4�YR6��`Lկ��������_(��W-�y��|��6�m��S<�����?|袽�0�9������b��ޢ0��׿��|�3|�kNxz'�8��2�C�^�]���)R{�s��c��z��<��d����PƟ���C}���/�� �gRؿ�5H���?��q:b˯��t�uPlv�Eȃ��o����ĳ��R�e��2WK-��#�:jt�ڇ�������:L~�'��,��D�&Z?:�[J�jE�ڀ�G�n5��s�׏5�¼�p�ǭLWDY3�
�:�JP�6�J������˪x�d|�*�cZ��O�w*��&,��#W�~���z.�M��K���N�����ਡ��8jv��T� O���P��g9�(1�`�VT[�lT�.��괛R�5~V{R��r��r��o�>�[�I��؄�6n��/a-ieP�.Gr��P�f�瑑*���h���KE­�>���M�7#R̬C��Y�t8>nt���rԲ�{�^j���<�[���j�G{t�="l_ˋ�j3g��9��#M�p���.�ڇm����;�p��W�Dr�^&�G��ZV�¿B�DARܸ�j��7zc��MB&�׻��!��GkF����� "��غ��o.��1�p��r�.��m�&zM�Y\�%O�aW�sr������w���}�&�Q8�8�}^Q�ۯ�n��u�xBc'iӟT-�U�$��5����#��=��p���K*-ͫ�o��]�����i|�L E�F�[�.�8���aD�qN=�	�R��j�����ܲ��rw�����=P7����8��D�N�p�c�/|�D��F>��ry�;���r����@`*������f?��Z��#Hlĸ��ua;᰽$׿���D�Й�U�%8�Y;�ݞ.����~�7�*�h.�;��Ak%�Y�혃 ����6�F~J��q�H��*5$=\�t��Qdi��J9�KS�O���h
��ܔ�t�F�� �M���~� 5�Bg�8߸bD! ��O��=�����<�%z�Zԕ�ޏ"W�D���� �ݻ֍&�x7�1 ���6�F�]�o.vQ�Hd"_@M-���hn׽U:]�7όwg�֧@!c�:���L�!C.�:z#l����`cfC�A�{`�ޤ�N_�-2�9�Kc��b�Ǚ:��y��c�4x�4���u/6�=sR    W��e�M�f趌ڨ�G�㋺��&�F�<��<gϊ:
*;B��^����)��bEN��T^�>$w}J6瑀*�o��ix k�ez[�/`S#�jx)9�	L����S��
��:$�b��5�/��^QF2.�}�O Cg������%�)�'R���G��l$Ae�@�B��	�����_T�;(���kҬ���P����6 ��^��^ʟ\�!.�ZA�|}�Ġ�;��	���7ll�W�L.��Ԁp��M ��e�J�i\��&A�������
�.���.��ATmX�4K+��]`99ɲ��5V���Q6�_x�~�"�1�/2�s�3ծ
�b�./^�&�DP��oQ�P��#�zQ@�b��7���q�«����m[�����b�k�}� 9�����/=�?�&�&>ڏ�-��Z7K�oN�@CZ�]�{�"�G�����e�Pv��I(�]x>m ��J�6��a;��_��7�L>I�@<d�x�z�*ZWk�9�0g�t�mkR���d�c��WE�@z�q����p��?�&�Cj�JK�#~ʃE����{�r�.�H�R2�l�Tr��Rۉ��P�d.����6��|�vnH`�V�U:�$nn�M�#HV&"��.���sm�r}`>�M\3�����*����S�m�6���\��}�N��1�Ơ:)�+J^��P���]�;���e2v��_�-��< �������ul�a9%���hlSYA��}�:�MR��"��#���9z�f�~y��C�\��M���Q��f[;L�)��&"��>{���H�x�{P���@I_/H���)ѽ�{�V���DAG�	����6�F��0v�Q��&"d`
���f����b#7�����^�kF!�Ԓ/�Ҟ+Ҳ�ʲ6EbD�c�pn����^=���c�*|�t�D�۪}��ٹ�\�
{�0S�n�yD*�Pv�@�knr~���A�\ŧ1g �ߦ�����
-s�Y��M���b^�S%���m��-�(�A_�w7%��V�kWA��/v�q����F}��O@�?�2����;0t��:z1o/5'#��v/�K%J36!7�t���ծV��d��΋�B�Q�^��H�p�{+��̌�n8�4���Y/(7:�_��]����4'Ou�x�
9��	^m���9BG�Fm�xkz�����a�hBj�$��T�B/��o��_��<�}}w�0Q�/�ӹ&KX�t���V!�n۝o娔��Ά�lMY+������0�6eZUbVS�����v�����$���ҳ��v^N�(�9]�TPZJRY�L��R�cS�\w/9��f�Ďג�u]��9��[���햍���:�T�p�E��kl�cV�_y�P�g9����~�a�zF��P���٨:��*�۩��S͏"k�*�ŗ�^O�V��������"q]��~[{F0��O��q��m��3�]���R�H��ս)�����H�3�pY'>��ʌ�����?��ca�K"��\3QB�_W��R_��~����>�ޤ�_���	�ǉI
Z�{uEc��Ե��/mҧ�Z3=��@�����Z��Chֳ)K���m�����Ve����e�aC�SPjq�C�*��3l�ށ�q�um�oK�ڀs���3
��Q�$8��,7Oɼn�l���d�#��d�pT���^m£IyR
��"!��T���̰���O��#�s����ıH=�C�&��hQ�~ �テ�˒tdm.�p��n)l�Fg�kNѥ(b��a�>:z �x�ph){����E9�f�x�b�w�h��d���y�U�"�˅{�-�BR6�,�	�F�nXs���+򖒭���v�\��k��S��D�]���'�:�|����_v	�5���Q?�w\������>!}�Y{�b��H�+O�&a]\o�-r/f�z,�Mˮ�tw���[���6~:�̀z-�ؠ��{��&�Fӧ
����N�_7BR�@0!e��=���>�C0��; �ua�6 �Ξ�]<'�׭�	;��LÜ-�Y���x%��P�Y²�xO-�ĺ�f�Y�H��/шÑ�O��>N,�^'����ipj�B�/�bxZ����*$��jՖ�@�|����&�w4D]�/�џ�7�@{?���67�V�3<Bi�<6{�b��}�m��:�Ȼ��>�8lyT�6�[b�ѱ���Q�#fz�l��E��S���g�[��N.��ja~F�Z�T}CHW�"���`��ڞ׫�a�4�[k�w���,r�rA�>�~P���M�#y{5���Y���.r%�ռ�� ���q��n���O�[
$�#��JO �g�� ��W�:��f��Ce��4��}�ͮ�pM�2.�
Ԝ�fEI��lݖ�iDV�#��T��?d��p"�O�	�:�V��R;^M���t�"�B��Ky���m�-�r�{_����r���{��F� ���N=��M��ۢN�cA�V��z+�E.��lq�ӏ[I��B9�����鳖�W��G
L 5�9��А�/n�'�m��_}t"���h�32O,g��f_v=�_��{W�>D�N�;V��t�<q�k��p�o�Q�XcM\b"�.SkO�K%m뻊���Xӛ,���Y���΃����p�~9Yq�}�iX�e7���n)1syK�7�݌� �C&cԵ�8�t/�{H?��֝綟�8��#{�����Q`#~_3y��KG���m����W!]�������4�!H�Ƌ���9o�P�����i2�O䵺|� ��^��a�U���D�[)����B�M5?��!;9u_S�!nO��lC�g[�)�\4Ro���_��ax��p}�c��hY�.���A�r�EY/��}�I��QUJ�i�I2&�Kxk0����Ud�5C0#Mp���|��`��r�3�����p�]��5!R�R�F",�]�NMގ�rt��|\��n~���Fub��D!�����6@�̤:,�|�l"K��D;�]�L�gx>���ڭ,T����ȍ��Pa��H�u�?�#���gԧ��A��Ȯ|3��N1�⾑��ך�t	H�l=z�/R�ɂL�e�;������t���9π(�z��p���?D9[uY}��^'�/�_h��/e~;�|�/(f�(�GWL��٨x�z�9i�V�ӬY(#���w����o@|�Y<\k�(ȉ8���,�ڞ�Mݎ[8�[����$1L�إ��|��s}}�u�7	Ǽ�C�m ���žq����o"�PoUQ�%VE�8���m��k9��:U�5�'���GI��b��[� ,@�}���>|�&�+�a/2N^�rB�v��l���Iւ�Nm�k�#ms
�"D����^"'�b^�-?��(�o�#fƯ��*+�
�~Ia�D�ع��V\ru�!4cn�k8��Q:����b�`���!,�c��n<�� ��>���g�~�;u���v"��q�?���"��k�7|�Gs�h�MV�����I���MKZ����oɂ�o}�d>!�Ϛpi7���y���r"�-0��������ԧ�1�ni*�BΊW�z���A�,Ċo�C���b��Q<n�~�σñ��M����'�D��N���>�\�"�o�zWo;�u⼌DD����r;O٦e�}=����m �?#t��Q��Ҭ''Br���釧��ƹ�=�72���r���%�%� $��9tD���5�Cʯ���3c8R)P_^݃��z�+��]�/Z0'�'�ڟ���J�>t�mHb�!�V~-[ynT#QP�ĸ�a�a����|?#EM�;h���V����^8s�=��}�����Y��}���#��^F#�x���os�ad��{W(�!i�z�m"�!e�\(�\�{��闷�a�_�1ǻuzz(���}�h�0�2�oې���{�����f�x�#AL0��.��L�\JnUp��I�Y/(�4��]x���.��[+#�6�c�c>�y\����<�v�����3Q=�bY����DY��C�dt �  *��[m�պ12N�وjax��\r�K@A1/6�z�e����બ�`(�.�=�W�hN��}�ܝ~ԑ&yT���!wGi�}V��O"L������0�ڼE�,xG������j��P�ʛ5��z���M���gt.�|#ry,�J�D��A���dI�E���j-B�\��i���4=,�tX���6A��_�'��Q�;�� e���	�_O�^��dRFlYoUz�s��f�̢$/�<�_���z�#��Ծ� &�yS�2�a�7�ߍ��u�?���ZUO�����uJ�J�^�p��s��^z1d��D@��1��#����F�� �[���/��R�6�`�أ�$|��,�@��4�jD�b<�s��{HzL�õ̓�#��2	�3W��w@��"��������
��Ft�G��^4��7G���+�M�e�n������f��E$8Qɣ`���;���4�Sj��6@5l�T!p��J�M���w�{�d��8�Z�̻d�z��~-.�;���v������44�Ι���{�P�p�e�m 
�=�7E����}���"�}w�V������օjj7��z�ҵm�n/�?Xt}ڵ�v�&����#<C���B�}�����@EW7U�ϡH���	Q�e`��<`����s��[g����Z\u�ۢn,�q�� 1��H�����k*N���	(C�[��6X�t�9%J��@�u��d�K����h��Y�4����P��n�~�(��u���@��Dh@�x��?�S������}L��R����b}D�j���kN�����"�>H8�G���C��ۋ��SUja�}(��%H��̱���D�Z�i�}��0o5P����d1P�+o~[#M�ؕd~����$�e8;_Z�v���D�_����t�@Ɏ4�i���ݫ�h��2&^@�ɭ�|ݤE��N�Q?��7������K�u5�G�;�;��8��F�p"	�݉�t��d�7:1O�q�);�;�U�嚪a�30|�m��ەs�\I�p;����R�*�0�W&,Z�n� 2����M~N��Z�{�Nޔ&�@�s�k�rB��_�I�=����$�_ނ*Qkݛ�2�
g��Ǔ
w"�:�#i����������<������9���}7�^�������A��C��T������c�	ឤZֽx�A��b��j��3κ
W;��m�h*����v�x��y}k8^F���I��h�HFG�F�^M(�6|7��V�6𱞇BU�go�W4��F�t�߿c�}�.sha�� F~�{����H?r�X[rɦ���J�;��ȕ�+�8����~�����;�/ý�!��N��jN(��p!�!�S7����T�Af^�ӹq9>N�昤�i��/u�o�s����=@���Y	<���lv��a1f]��3]I�'�wzuV*#Zx�/d[��V>�gOXK��.0�<���k`r�)��_Џ?��2I     