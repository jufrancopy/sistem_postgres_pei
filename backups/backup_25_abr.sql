PGDMP     ,    1                |           siplan %   12.18 (Ubuntu 12.18-0ubuntu0.20.04.1) %   12.18 (Ubuntu 12.18-0ubuntu0.20.04.1) �              0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false                       0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false                       0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false                       1262    30267    siplan    DATABASE     x   CREATE DATABASE siplan WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8';
    DROP DATABASE siplan;
                postgres    false            	            2615    47283    estadistica    SCHEMA        CREATE SCHEMA estadistica;
    DROP SCHEMA estadistica;
                postgres    false                        2615    47282    planificacion    SCHEMA        CREATE SCHEMA planificacion;
    DROP SCHEMA planificacion;
                postgres    false                        2615    47284    proyecto    SCHEMA        CREATE SCHEMA proyecto;
    DROP SCHEMA proyecto;
                postgres    false            �            1259    47515    formulario_clasificadores    TABLE       CREATE TABLE estadistica.formulario_clasificadores (
    id bigint NOT NULL,
    clasificador character varying(191) NOT NULL,
    clasificador_id integer,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 2   DROP TABLE estadistica.formulario_clasificadores;
       estadistica         heap    postgres    false    9            �            1259    47513     formulario_clasificadores_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_clasificadores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 <   DROP SEQUENCE estadistica.formulario_clasificadores_id_seq;
       estadistica          postgres    false    9    234                       0    0     formulario_clasificadores_id_seq    SEQUENCE OWNED BY     o   ALTER SEQUENCE estadistica.formulario_clasificadores_id_seq OWNED BY estadistica.formulario_clasificadores.id;
          estadistica          postgres    false    233            �            1259    47496 #   formulario_formulario_has_variables    TABLE     _  CREATE TABLE estadistica.formulario_formulario_has_variables (
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
       estadistica         heap    postgres    false    9            �            1259    47494 *   formulario_formulario_has_variables_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_formulario_has_variables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 F   DROP SEQUENCE estadistica.formulario_formulario_has_variables_id_seq;
       estadistica          postgres    false    232    9                       0    0 *   formulario_formulario_has_variables_id_seq    SEQUENCE OWNED BY     �   ALTER SEQUENCE estadistica.formulario_formulario_has_variables_id_seq OWNED BY estadistica.formulario_formulario_has_variables.id;
          estadistica          postgres    false    231            �            1259    47473    formulario_formularios    TABLE     Q  CREATE TABLE estadistica.formulario_formularios (
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
       estadistica         heap    postgres    false    9            �            1259    47471    formulario_formularios_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_formularios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE estadistica.formulario_formularios_id_seq;
       estadistica          postgres    false    230    9                       0    0    formulario_formularios_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE estadistica.formulario_formularios_id_seq OWNED BY estadistica.formulario_formularios.id;
          estadistica          postgres    false    229            �            1259    47452    formulario_items    TABLE       CREATE TABLE estadistica.formulario_items (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    questions text NOT NULL,
    variable_id integer,
    user_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE estadistica.formulario_items;
       estadistica         heap    postgres    false    9            �            1259    47450    formulario_items_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_items_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE estadistica.formulario_items_id_seq;
       estadistica          postgres    false    228    9                       0    0    formulario_items_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE estadistica.formulario_items_id_seq OWNED BY estadistica.formulario_items.id;
          estadistica          postgres    false    227            �            1259    47436    formulario_variables    TABLE     r  CREATE TABLE estadistica.formulario_variables (
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
       estadistica         heap    postgres    false    9            �            1259    47434    formulario_variables_id_seq    SEQUENCE     �   CREATE SEQUENCE estadistica.formulario_variables_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 7   DROP SEQUENCE estadistica.formulario_variables_id_seq;
       estadistica          postgres    false    9    226                       0    0    formulario_variables_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE estadistica.formulario_variables_id_seq OWNED BY estadistica.formulario_variables.id;
          estadistica          postgres    false    225            �            1259    47601    foda_analisis    TABLE     O  CREATE TABLE planificacion.foda_analisis (
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
       planificacion         heap    postgres    false    7            �            1259    47599    foda_analisis_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_analisis_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE planificacion.foda_analisis_id_seq;
       planificacion          postgres    false    242    7                       0    0    foda_analisis_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE planificacion.foda_analisis_id_seq OWNED BY planificacion.foda_analisis.id;
          planificacion          postgres    false    241            �            1259    47568    foda_categorias_has_perfil    TABLE     �   CREATE TABLE planificacion.foda_categorias_has_perfil (
    perfil_id uuid NOT NULL,
    category_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 5   DROP TABLE planificacion.foda_categorias_has_perfil;
       planificacion         heap    postgres    false    7            �            1259    47625    foda_cruce_ambientes    TABLE     -  CREATE TABLE planificacion.foda_cruce_ambientes (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    perfil_id uuid NOT NULL,
    estrategia text NOT NULL,
    tipo character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE planificacion.foda_cruce_ambientes;
       planificacion         heap    postgres    false    7            �            1259    47683 !   foda_cruce_ambientes_has_amenazas    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_amenazas (
    cruce_id integer NOT NULL,
    amenaza_id integer NOT NULL
);
 <   DROP TABLE planificacion.foda_cruce_ambientes_has_amenazas;
       planificacion         heap    postgres    false    7            �            1259    47670 $   foda_cruce_ambientes_has_debilidades    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_debilidades (
    cruce_id integer NOT NULL,
    debilidad_id integer NOT NULL
);
 ?   DROP TABLE planificacion.foda_cruce_ambientes_has_debilidades;
       planificacion         heap    postgres    false    7            �            1259    47644 #   foda_cruce_ambientes_has_fortalezas    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_fortalezas (
    cruce_id integer NOT NULL,
    fortaleza_id integer NOT NULL
);
 >   DROP TABLE planificacion.foda_cruce_ambientes_has_fortalezas;
       planificacion         heap    postgres    false    7            �            1259    47657 &   foda_cruce_ambientes_has_oportunidades    TABLE     �   CREATE TABLE planificacion.foda_cruce_ambientes_has_oportunidades (
    cruce_id integer NOT NULL,
    oportunidad_id integer NOT NULL
);
 A   DROP TABLE planificacion.foda_cruce_ambientes_has_oportunidades;
       planificacion         heap    postgres    false    7            �            1259    47623    foda_cruce_ambientes_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_cruce_ambientes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE planificacion.foda_cruce_ambientes_id_seq;
       planificacion          postgres    false    244    7                       0    0    foda_cruce_ambientes_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE planificacion.foda_cruce_ambientes_id_seq OWNED BY planificacion.foda_cruce_ambientes.id;
          planificacion          postgres    false    243            �            1259    47583    foda_groups_has_perfiles    TABLE     �   CREATE TABLE planificacion.foda_groups_has_perfiles (
    id bigint NOT NULL,
    perfil_id uuid NOT NULL,
    group_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 3   DROP TABLE planificacion.foda_groups_has_perfiles;
       planificacion         heap    postgres    false    7            �            1259    47581    foda_groups_has_perfiles_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_groups_has_perfiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 =   DROP SEQUENCE planificacion.foda_groups_has_perfiles_id_seq;
       planificacion          postgres    false    7    240                       0    0    foda_groups_has_perfiles_id_seq    SEQUENCE OWNED BY     q   ALTER SEQUENCE planificacion.foda_groups_has_perfiles_id_seq OWNED BY planificacion.foda_groups_has_perfiles.id;
          planificacion          postgres    false    239            �            1259    47533    foda_models    TABLE     �  CREATE TABLE planificacion.foda_models (
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
       planificacion         heap    postgres    false    7            �            1259    47531    foda_models_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.foda_models_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE planificacion.foda_models_id_seq;
       planificacion          postgres    false    7    236                       0    0    foda_models_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE planificacion.foda_models_id_seq OWNED BY planificacion.foda_models.id;
          planificacion          postgres    false    235            �            1259    47545    foda_perfiles    TABLE     r  CREATE TABLE planificacion.foda_perfiles (
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
       planificacion         heap    postgres    false    7            �            1259    47696    pei_profiles    TABLE     n  CREATE TABLE planificacion.pei_profiles (
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
    parent_id uuid
);
 '   DROP TABLE planificacion.pei_profiles;
       planificacion         heap    postgres    false    7            �            1259    47724    pei_profiles_has_dependencies    TABLE     �   CREATE TABLE planificacion.pei_profiles_has_dependencies (
    id bigint NOT NULL,
    pei_profile_id uuid NOT NULL,
    dependency_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 8   DROP TABLE planificacion.pei_profiles_has_dependencies;
       planificacion         heap    postgres    false    7            �            1259    47722 $   pei_profiles_has_dependencies_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 B   DROP SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq;
       planificacion          postgres    false    251    7                        0    0 $   pei_profiles_has_dependencies_id_seq    SEQUENCE OWNED BY     {   ALTER SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq OWNED BY planificacion.pei_profiles_has_dependencies.id;
          planificacion          postgres    false    250            &           1259    48001    pei_profiles_has_strategies    TABLE     �   CREATE TABLE planificacion.pei_profiles_has_strategies (
    id bigint NOT NULL,
    profile_id uuid NOT NULL,
    strategy_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 6   DROP TABLE planificacion.pei_profiles_has_strategies;
       planificacion         heap    postgres    false    7            %           1259    47999 "   pei_profiles_has_strategies_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.pei_profiles_has_strategies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 @   DROP SEQUENCE planificacion.pei_profiles_has_strategies_id_seq;
       planificacion          postgres    false    294    7            !           0    0 "   pei_profiles_has_strategies_id_seq    SEQUENCE OWNED BY     w   ALTER SEQUENCE planificacion.pei_profiles_has_strategies_id_seq OWNED BY planificacion.pei_profiles_has_strategies.id;
          planificacion          postgres    false    293            �            1259    47742    peis_profiles_has_analysts    TABLE     �   CREATE TABLE planificacion.peis_profiles_has_analysts (
    id bigint NOT NULL,
    pei_profile_id uuid NOT NULL,
    analyst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 5   DROP TABLE planificacion.peis_profiles_has_analysts;
       planificacion         heap    postgres    false    7            �            1259    47740 !   peis_profiles_has_analysts_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.peis_profiles_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ?   DROP SEQUENCE planificacion.peis_profiles_has_analysts_id_seq;
       planificacion          postgres    false    253    7            "           0    0 !   peis_profiles_has_analysts_id_seq    SEQUENCE OWNED BY     u   ALTER SEQUENCE planificacion.peis_profiles_has_analysts_id_seq OWNED BY planificacion.peis_profiles_has_analysts.id;
          planificacion          postgres    false    252            (           1259    48019    peis_profiles_has_responsibles    TABLE     �   CREATE TABLE planificacion.peis_profiles_has_responsibles (
    id bigint NOT NULL,
    profile_id uuid NOT NULL,
    responsible_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 9   DROP TABLE planificacion.peis_profiles_has_responsibles;
       planificacion         heap    postgres    false    7            '           1259    48017 %   peis_profiles_has_responsibles_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 C   DROP SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq;
       planificacion          postgres    false    296    7            #           0    0 %   peis_profiles_has_responsibles_id_seq    SEQUENCE OWNED BY     }   ALTER SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq OWNED BY planificacion.peis_profiles_has_responsibles.id;
          planificacion          postgres    false    295                        1259    47955    tasks    TABLE     �   CREATE TABLE planificacion.tasks (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    details character varying(191),
    status integer DEFAULT 0,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
     DROP TABLE planificacion.tasks;
       planificacion         heap    postgres    false    7            "           1259    47969    tasks_has_analysts    TABLE     �   CREATE TABLE planificacion.tasks_has_analysts (
    id bigint NOT NULL,
    task_id integer NOT NULL,
    analyst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 -   DROP TABLE planificacion.tasks_has_analysts;
       planificacion         heap    postgres    false    7            !           1259    47967    tasks_has_analysts_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.tasks_has_analysts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 7   DROP SEQUENCE planificacion.tasks_has_analysts_id_seq;
       planificacion          postgres    false    290    7            $           0    0    tasks_has_analysts_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE planificacion.tasks_has_analysts_id_seq OWNED BY planificacion.tasks_has_analysts.id;
          planificacion          postgres    false    289            $           1259    47987    tasks_has_type_tasks    TABLE     .  CREATE TABLE planificacion.tasks_has_type_tasks (
    id bigint NOT NULL,
    task_id integer NOT NULL,
    typetaskable_id uuid,
    typetaskable_type character varying(191),
    status integer DEFAULT 0,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE planificacion.tasks_has_type_tasks;
       planificacion         heap    postgres    false    7            #           1259    47985    tasks_has_type_tasks_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.tasks_has_type_tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE planificacion.tasks_has_type_tasks_id_seq;
       planificacion          postgres    false    292    7            %           0    0    tasks_has_type_tasks_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE planificacion.tasks_has_type_tasks_id_seq OWNED BY planificacion.tasks_has_type_tasks.id;
          planificacion          postgres    false    291                       1259    47953    tasks_id_seq    SEQUENCE     |   CREATE SEQUENCE planificacion.tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE planificacion.tasks_id_seq;
       planificacion          postgres    false    7    288            &           0    0    tasks_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE planificacion.tasks_id_seq OWNED BY planificacion.tasks.id;
          planificacion          postgres    false    287                       1259    47947 	   typetasks    TABLE     #  CREATE TABLE planificacion.typetasks (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    typetaskable_id uuid NOT NULL,
    typetaskable_type character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 $   DROP TABLE planificacion.typetasks;
       planificacion         heap    postgres    false    7                       1259    47945    typetasks_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.typetasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE planificacion.typetasks_id_seq;
       planificacion          postgres    false    7    286            '           0    0    typetasks_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE planificacion.typetasks_id_seq OWNED BY planificacion.typetasks.id;
          planificacion          postgres    false    285                       1259    47782    e_p_c_equipamientos    TABLE       CREATE TABLE proyecto.e_p_c_equipamientos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE proyecto.e_p_c_equipamientos;
       proyecto         heap    postgres    false    8                       1259    47780    e_p_c_equipamientos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_equipamientos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE proyecto.e_p_c_equipamientos_id_seq;
       proyecto          postgres    false    8    259            (           0    0    e_p_c_equipamientos_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE proyecto.e_p_c_equipamientos_id_seq OWNED BY proyecto.e_p_c_equipamientos.id;
          proyecto          postgres    false    258                       1259    47823    e_p_c_equipamientos_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_equipamientos_servicios (
    servicio_id integer NOT NULL,
    equipamiento_id integer NOT NULL,
    cantidad integer NOT NULL
);
 3   DROP TABLE proyecto.e_p_c_equipamientos_servicios;
       proyecto         heap    postgres    false    8            �            1259    47760    e_p_c_especialidads    TABLE     6  CREATE TABLE proyecto.e_p_c_especialidads (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 )   DROP TABLE proyecto.e_p_c_especialidads;
       proyecto         heap    postgres    false    8            �            1259    47758    e_p_c_especialidads_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_especialidads_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 3   DROP SEQUENCE proyecto.e_p_c_especialidads_id_seq;
       proyecto          postgres    false    8    255            )           0    0    e_p_c_especialidads_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE proyecto.e_p_c_especialidads_id_seq OWNED BY proyecto.e_p_c_especialidads.id;
          proyecto          postgres    false    254                       1259    47907    e_p_c_estandars    TABLE       CREATE TABLE proyecto.e_p_c_estandars (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 %   DROP TABLE proyecto.e_p_c_estandars;
       proyecto         heap    postgres    false    8                       1259    47905    e_p_c_estandars_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_estandars_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE proyecto.e_p_c_estandars_id_seq;
       proyecto          postgres    false    279    8            *           0    0    e_p_c_estandars_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE proyecto.e_p_c_estandars_id_seq OWNED BY proyecto.e_p_c_estandars.id;
          proyecto          postgres    false    278                       1259    47916    e_p_c_estandars_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_estandars_servicios (
    estandar_id integer NOT NULL,
    servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);
 /   DROP TABLE proyecto.e_p_c_estandars_servicios;
       proyecto         heap    postgres    false    8                       1259    47864    e_p_c_horarios    TABLE     '  CREATE TABLE proyecto.e_p_c_horarios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    start_time character varying(191) NOT NULL,
    end_time character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 $   DROP TABLE proyecto.e_p_c_horarios;
       proyecto         heap    postgres    false    8                       1259    47862    e_p_c_horarios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_horarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE proyecto.e_p_c_horarios_id_seq;
       proyecto          postgres    false    272    8            +           0    0    e_p_c_horarios_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE proyecto.e_p_c_horarios_id_seq OWNED BY proyecto.e_p_c_horarios.id;
          proyecto          postgres    false    271                       1259    47849    e_p_c_infraestructura_servicio    TABLE     �   CREATE TABLE proyecto.e_p_c_infraestructura_servicio (
    servicio_id integer NOT NULL,
    infraestructura_id integer NOT NULL,
    cantidad integer NOT NULL
);
 4   DROP TABLE proyecto.e_p_c_infraestructura_servicio;
       proyecto         heap    postgres    false    8                       1259    47790    e_p_c_infraestructuras    TABLE     J  CREATE TABLE proyecto.e_p_c_infraestructuras (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    measurement double precision NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 ,   DROP TABLE proyecto.e_p_c_infraestructuras;
       proyecto         heap    postgres    false    8                       1259    47788    e_p_c_infraestructuras_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_infraestructuras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE proyecto.e_p_c_infraestructuras_id_seq;
       proyecto          postgres    false    261    8            ,           0    0    e_p_c_infraestructuras_id_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE proyecto.e_p_c_infraestructuras_id_seq OWNED BY proyecto.e_p_c_infraestructuras.id;
          proyecto          postgres    false    260            	           1259    47806    e_p_c_medicamento_insumos    TABLE     "  CREATE TABLE proyecto.e_p_c_medicamento_insumos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE proyecto.e_p_c_medicamento_insumos;
       proyecto         heap    postgres    false    8                       1259    47804     e_p_c_medicamento_insumos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq;
       proyecto          postgres    false    8    265            -           0    0     e_p_c_medicamento_insumos_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq OWNED BY proyecto.e_p_c_medicamento_insumos.id;
          proyecto          postgres    false    264                       1259    47798    e_p_c_otro_servicios    TABLE       CREATE TABLE proyecto.e_p_c_otro_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 *   DROP TABLE proyecto.e_p_c_otro_servicios;
       proyecto         heap    postgres    false    8                       1259    47796    e_p_c_otro_servicios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_otro_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 4   DROP SEQUENCE proyecto.e_p_c_otro_servicios_id_seq;
       proyecto          postgres    false    263    8            .           0    0    e_p_c_otro_servicios_id_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE proyecto.e_p_c_otro_servicios_id_seq OWNED BY proyecto.e_p_c_otro_servicios.id;
          proyecto          postgres    false    262                       1259    47883    e_p_c_prestaciones    TABLE     ;  CREATE TABLE proyecto.e_p_c_prestaciones (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 (   DROP TABLE proyecto.e_p_c_prestaciones;
       proyecto         heap    postgres    false    8                       1259    47881    e_p_c_prestaciones_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_prestaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE proyecto.e_p_c_prestaciones_id_seq;
       proyecto          postgres    false    8    276            /           0    0    e_p_c_prestaciones_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE proyecto.e_p_c_prestaciones_id_seq OWNED BY proyecto.e_p_c_prestaciones.id;
          proyecto          postgres    false    275                       1259    47814    e_p_c_servicios    TABLE       CREATE TABLE proyecto.e_p_c_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 %   DROP TABLE proyecto.e_p_c_servicios;
       proyecto         heap    postgres    false    8            
           1259    47812    e_p_c_servicios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE proyecto.e_p_c_servicios_id_seq;
       proyecto          postgres    false    267    8            0           0    0    e_p_c_servicios_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE proyecto.e_p_c_servicios_id_seq OWNED BY proyecto.e_p_c_servicios.id;
          proyecto          postgres    false    266                       1259    47771    e_p_c_talento_humanos    TABLE     I  CREATE TABLE proyecto.e_p_c_talento_humanos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    hours character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 +   DROP TABLE proyecto.e_p_c_talento_humanos;
       proyecto         heap    postgres    false    8                        1259    47769    e_p_c_talento_humanos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_talento_humanos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE proyecto.e_p_c_talento_humanos_id_seq;
       proyecto          postgres    false    8    257            1           0    0    e_p_c_talento_humanos_id_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE proyecto.e_p_c_talento_humanos_id_seq OWNED BY proyecto.e_p_c_talento_humanos.id;
          proyecto          postgres    false    256                       1259    47836    e_p_c_tthhs_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_tthhs_servicios (
    servicio_id integer NOT NULL,
    tthh_id integer NOT NULL,
    cantidad integer NOT NULL
);
 +   DROP TABLE proyecto.e_p_c_tthhs_servicios;
       proyecto         heap    postgres    false    8                       1259    47875    e_p_c_turnos    TABLE     �   CREATE TABLE proyecto.e_p_c_turnos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 "   DROP TABLE proyecto.e_p_c_turnos;
       proyecto         heap    postgres    false    8                       1259    47873    e_p_c_turnos_id_seq    SEQUENCE     ~   CREATE SEQUENCE proyecto.e_p_c_turnos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE proyecto.e_p_c_turnos_id_seq;
       proyecto          postgres    false    8    274            2           0    0    e_p_c_turnos_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE proyecto.e_p_c_turnos_id_seq OWNED BY proyecto.e_p_c_turnos.id;
          proyecto          postgres    false    273                       1259    47892    otroservicio_has_servicios    TABLE     �   CREATE TABLE proyecto.otroservicio_has_servicios (
    servicio_id integer NOT NULL,
    otro_servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);
 0   DROP TABLE proyecto.otroservicio_has_servicios;
       proyecto         heap    postgres    false    8            .           1259    48059 
   activities    TABLE     �   CREATE TABLE public.activities (
    id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.activities;
       public         heap    postgres    false            -           1259    48057    activities_id_seq    SEQUENCE     z   CREATE SEQUENCE public.activities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.activities_id_seq;
       public          postgres    false    302            3           0    0    activities_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.activities_id_seq OWNED BY public.activities.id;
          public          postgres    false    301                       1259    47939 
   categories    TABLE     �   CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(128) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.categories;
       public         heap    postgres    false                       1259    47937    categories_id_seq    SEQUENCE     z   CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.categories_id_seq;
       public          postgres    false    284            4           0    0    categories_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;
          public          postgres    false    283            �            1259    47295    groups    TABLE        CREATE TABLE public.groups (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.groups;
       public         heap    postgres    false            �            1259    47381    groups_has_members    TABLE     �   CREATE TABLE public.groups_has_members (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 &   DROP TABLE public.groups_has_members;
       public         heap    postgres    false            �            1259    47379    groups_has_members_id_seq    SEQUENCE     �   CREATE SEQUENCE public.groups_has_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.groups_has_members_id_seq;
       public          postgres    false    220            5           0    0    groups_has_members_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE public.groups_has_members_id_seq OWNED BY public.groups_has_members.id;
          public          postgres    false    219            �            1259    47293    groups_id_seq    SEQUENCE     v   CREATE SEQUENCE public.groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.groups_id_seq;
       public          postgres    false    208            6           0    0    groups_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.groups_id_seq OWNED BY public.groups.id;
          public          postgres    false    207            *           1259    48037 
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
       public         heap    postgres    false            )           1259    48035    localities_id_seq    SEQUENCE     z   CREATE SEQUENCE public.localities_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.localities_id_seq;
       public          postgres    false    298            7           0    0    localities_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.localities_id_seq OWNED BY public.localities.id;
          public          postgres    false    297            �            1259    47287 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(191) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap    postgres    false            �            1259    47285    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public          postgres    false    206            8           0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public          postgres    false    205            �            1259    47342    model_has_permissions    TABLE     �   CREATE TABLE public.model_has_permissions (
    permission_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);
 )   DROP TABLE public.model_has_permissions;
       public         heap    postgres    false            �            1259    47353    model_has_roles    TABLE     �   CREATE TABLE public.model_has_roles (
    role_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);
 #   DROP TABLE public.model_has_roles;
       public         heap    postgres    false            �            1259    47399    organigramas    TABLE     �  CREATE TABLE public.organigramas (
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
       public         heap    postgres    false            �            1259    47397    organigramas_id_seq    SEQUENCE     |   CREATE SEQUENCE public.organigramas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.organigramas_id_seq;
       public          postgres    false    222            9           0    0    organigramas_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.organigramas_id_seq OWNED BY public.organigramas.id;
          public          postgres    false    221            �            1259    47322    password_resets    TABLE     �   CREATE TABLE public.password_resets (
    email character varying(191) NOT NULL,
    token character varying(191) NOT NULL,
    created_at timestamp(0) without time zone
);
 #   DROP TABLE public.password_resets;
       public         heap    postgres    false            ,           1259    48048    patrimonies    TABLE     �  CREATE TABLE public.patrimonies (
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
       public         heap    postgres    false            +           1259    48046    patrimonies_id_seq    SEQUENCE     {   CREATE SEQUENCE public.patrimonies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.patrimonies_id_seq;
       public          postgres    false    300            :           0    0    patrimonies_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.patrimonies_id_seq OWNED BY public.patrimonies.id;
          public          postgres    false    299            �            1259    47328    permissions    TABLE     �   CREATE TABLE public.permissions (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.permissions;
       public         heap    postgres    false            �            1259    47326    permissions_id_seq    SEQUENCE     �   CREATE SEQUENCE public.permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.permissions_id_seq;
       public          postgres    false    213            ;           0    0    permissions_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;
          public          postgres    false    212                       1259    47931    risks    TABLE     �   CREATE TABLE public.risks (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    detail character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.risks;
       public         heap    postgres    false                       1259    47929    risks_id_seq    SEQUENCE     u   CREATE SEQUENCE public.risks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.risks_id_seq;
       public          postgres    false    282            <           0    0    risks_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.risks_id_seq OWNED BY public.risks.id;
          public          postgres    false    281            �            1259    47364    role_has_permissions    TABLE     o   CREATE TABLE public.role_has_permissions (
    permission_id integer NOT NULL,
    role_id integer NOT NULL
);
 (   DROP TABLE public.role_has_permissions;
       public         heap    postgres    false            �            1259    47336    roles    TABLE     �   CREATE TABLE public.roles (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.roles;
       public         heap    postgres    false            �            1259    47334    roles_id_seq    SEQUENCE     �   CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.roles_id_seq;
       public          postgres    false    215            =           0    0    roles_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;
          public          postgres    false    214            �            1259    47420 	   servicios    TABLE     b  CREATE TABLE public.servicios (
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
       public         heap    postgres    false            �            1259    47418    servicios_id_seq    SEQUENCE     y   CREATE SEQUENCE public.servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.servicios_id_seq;
       public          postgres    false    224            >           0    0    servicios_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.servicios_id_seq OWNED BY public.servicios.id;
          public          postgres    false    223            �            1259    47306    users    TABLE     �  CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    email character varying(191) NOT NULL,
    group_id integer NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(191) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.users;
       public         heap    postgres    false            �            1259    47304    users_id_seq    SEQUENCE     u   CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public          postgres    false    210            ?           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public          postgres    false    209            e           2604    47518    formulario_clasificadores id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_clasificadores ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_clasificadores_id_seq'::regclass);
 P   ALTER TABLE estadistica.formulario_clasificadores ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    233    234    234            c           2604    47499 &   formulario_formulario_has_variables id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formulario_has_variables_id_seq'::regclass);
 Z   ALTER TABLE estadistica.formulario_formulario_has_variables ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    231    232    232            b           2604    47476    formulario_formularios id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_formularios ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formularios_id_seq'::regclass);
 M   ALTER TABLE estadistica.formulario_formularios ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    230    229    230            a           2604    47455    formulario_items id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_items ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_items_id_seq'::regclass);
 G   ALTER TABLE estadistica.formulario_items ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    228    227    228            ^           2604    47439    formulario_variables id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_variables_id_seq'::regclass);
 K   ALTER TABLE estadistica.formulario_variables ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    225    226    226            j           2604    47604    foda_analisis id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_analisis ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_analisis_id_seq'::regclass);
 F   ALTER TABLE planificacion.foda_analisis ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    242    241    242            l           2604    47628    foda_cruce_ambientes id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_cruce_ambientes_id_seq'::regclass);
 M   ALTER TABLE planificacion.foda_cruce_ambientes ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    243    244    244            i           2604    47586    foda_groups_has_perfiles id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_groups_has_perfiles_id_seq'::regclass);
 Q   ALTER TABLE planificacion.foda_groups_has_perfiles ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    239    240    240            f           2604    47536    foda_models id    DEFAULT     ~   ALTER TABLE ONLY planificacion.foda_models ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_models_id_seq'::regclass);
 D   ALTER TABLE planificacion.foda_models ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    235    236    236            o           2604    47727     pei_profiles_has_dependencies id    DEFAULT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_dependencies_id_seq'::regclass);
 V   ALTER TABLE planificacion.pei_profiles_has_dependencies ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    251    250    251            �           2604    48004    pei_profiles_has_strategies id    DEFAULT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_strategies_id_seq'::regclass);
 T   ALTER TABLE planificacion.pei_profiles_has_strategies ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    293    294    294            p           2604    47745    peis_profiles_has_analysts id    DEFAULT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_analysts_id_seq'::regclass);
 S   ALTER TABLE planificacion.peis_profiles_has_analysts ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    252    253    253            �           2604    48022 !   peis_profiles_has_responsibles id    DEFAULT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_responsibles_id_seq'::regclass);
 W   ALTER TABLE planificacion.peis_profiles_has_responsibles ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    295    296    296                       2604    47958    tasks id    DEFAULT     r   ALTER TABLE ONLY planificacion.tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_id_seq'::regclass);
 >   ALTER TABLE planificacion.tasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    287    288    288            �           2604    47972    tasks_has_analysts id    DEFAULT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_analysts_id_seq'::regclass);
 K   ALTER TABLE planificacion.tasks_has_analysts ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    290    289    290            �           2604    47990    tasks_has_type_tasks id    DEFAULT     �   ALTER TABLE ONLY planificacion.tasks_has_type_tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_type_tasks_id_seq'::regclass);
 M   ALTER TABLE planificacion.tasks_has_type_tasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    292    291    292            ~           2604    47950    typetasks id    DEFAULT     z   ALTER TABLE ONLY planificacion.typetasks ALTER COLUMN id SET DEFAULT nextval('planificacion.typetasks_id_seq'::regclass);
 B   ALTER TABLE planificacion.typetasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    286    285    286            s           2604    47785    e_p_c_equipamientos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_equipamientos_id_seq'::regclass);
 G   ALTER TABLE proyecto.e_p_c_equipamientos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    258    259    259            q           2604    47763    e_p_c_especialidads id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_especialidads ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_especialidads_id_seq'::regclass);
 G   ALTER TABLE proyecto.e_p_c_especialidads ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    254    255    255            {           2604    47910    e_p_c_estandars id    DEFAULT     |   ALTER TABLE ONLY proyecto.e_p_c_estandars ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_estandars_id_seq'::regclass);
 C   ALTER TABLE proyecto.e_p_c_estandars ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    279    278    279            x           2604    47867    e_p_c_horarios id    DEFAULT     z   ALTER TABLE ONLY proyecto.e_p_c_horarios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_horarios_id_seq'::regclass);
 B   ALTER TABLE proyecto.e_p_c_horarios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    272    271    272            t           2604    47793    e_p_c_infraestructuras id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_infraestructuras_id_seq'::regclass);
 J   ALTER TABLE proyecto.e_p_c_infraestructuras ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    261    260    261            v           2604    47809    e_p_c_medicamento_insumos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_medicamento_insumos_id_seq'::regclass);
 M   ALTER TABLE proyecto.e_p_c_medicamento_insumos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    265    264    265            u           2604    47801    e_p_c_otro_servicios id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_otro_servicios_id_seq'::regclass);
 H   ALTER TABLE proyecto.e_p_c_otro_servicios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    262    263    263            z           2604    47886    e_p_c_prestaciones id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_prestaciones ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_prestaciones_id_seq'::regclass);
 F   ALTER TABLE proyecto.e_p_c_prestaciones ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    276    275    276            w           2604    47817    e_p_c_servicios id    DEFAULT     |   ALTER TABLE ONLY proyecto.e_p_c_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_servicios_id_seq'::regclass);
 C   ALTER TABLE proyecto.e_p_c_servicios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    266    267    267            r           2604    47774    e_p_c_talento_humanos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_talento_humanos_id_seq'::regclass);
 I   ALTER TABLE proyecto.e_p_c_talento_humanos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    256    257    257            y           2604    47878    e_p_c_turnos id    DEFAULT     v   ALTER TABLE ONLY proyecto.e_p_c_turnos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_turnos_id_seq'::regclass);
 @   ALTER TABLE proyecto.e_p_c_turnos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    273    274    274            �           2604    48062    activities id    DEFAULT     n   ALTER TABLE ONLY public.activities ALTER COLUMN id SET DEFAULT nextval('public.activities_id_seq'::regclass);
 <   ALTER TABLE public.activities ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    301    302    302            }           2604    47942    categories id    DEFAULT     n   ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);
 <   ALTER TABLE public.categories ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    284    283    284            Q           2604    47298 	   groups id    DEFAULT     f   ALTER TABLE ONLY public.groups ALTER COLUMN id SET DEFAULT nextval('public.groups_id_seq'::regclass);
 8   ALTER TABLE public.groups ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    207    208    208            W           2604    47384    groups_has_members id    DEFAULT     ~   ALTER TABLE ONLY public.groups_has_members ALTER COLUMN id SET DEFAULT nextval('public.groups_has_members_id_seq'::regclass);
 D   ALTER TABLE public.groups_has_members ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    220    219    220            �           2604    48040    localities id    DEFAULT     n   ALTER TABLE ONLY public.localities ALTER COLUMN id SET DEFAULT nextval('public.localities_id_seq'::regclass);
 <   ALTER TABLE public.localities ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    298    297    298            P           2604    47290    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    206    205    206            X           2604    47402    organigramas id    DEFAULT     r   ALTER TABLE ONLY public.organigramas ALTER COLUMN id SET DEFAULT nextval('public.organigramas_id_seq'::regclass);
 >   ALTER TABLE public.organigramas ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    221    222    222            �           2604    48051    patrimonies id    DEFAULT     p   ALTER TABLE ONLY public.patrimonies ALTER COLUMN id SET DEFAULT nextval('public.patrimonies_id_seq'::regclass);
 =   ALTER TABLE public.patrimonies ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    300    299    300            U           2604    47331    permissions id    DEFAULT     p   ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);
 =   ALTER TABLE public.permissions ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    212    213    213            |           2604    47934    risks id    DEFAULT     d   ALTER TABLE ONLY public.risks ALTER COLUMN id SET DEFAULT nextval('public.risks_id_seq'::regclass);
 7   ALTER TABLE public.risks ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    282    281    282            V           2604    47339    roles id    DEFAULT     d   ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);
 7   ALTER TABLE public.roles ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    215    214    215            [           2604    47423    servicios id    DEFAULT     l   ALTER TABLE ONLY public.servicios ALTER COLUMN id SET DEFAULT nextval('public.servicios_id_seq'::regclass);
 ;   ALTER TABLE public.servicios ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    224    223    224            T           2604    47309    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    210    209    210            �          0    47515    formulario_clasificadores 
   TABLE DATA           |   COPY estadistica.formulario_clasificadores (id, clasificador, clasificador_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    234   P      �          0    47496 #   formulario_formulario_has_variables 
   TABLE DATA           �   COPY estadistica.formulario_formulario_has_variables (id, formulario_id, variable_id, selected, selected_variable_id, value, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    232   -P      �          0    47473    formulario_formularios 
   TABLE DATA           �   COPY estadistica.formulario_formularios (id, formulario, status, dependencia_emisor_id, dependencia_receptor_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    230   JP      �          0    47452    formulario_items 
   TABLE DATA           r   COPY estadistica.formulario_items (id, item, questions, variable_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    228   gP      �          0    47436    formulario_variables 
   TABLE DATA           {   COPY estadistica.formulario_variables (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    226   �P      �          0    47601    foda_analisis 
   TABLE DATA           �   COPY planificacion.foda_analisis (id, user_id, perfil_id, aspecto_id, tipo, ocurrencia, impacto, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    242   �P      �          0    47568    foda_categorias_has_perfil 
   TABLE DATA           k   COPY planificacion.foda_categorias_has_perfil (perfil_id, category_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    238   }      �          0    47625    foda_cruce_ambientes 
   TABLE DATA           w   COPY planificacion.foda_cruce_ambientes (id, user_id, perfil_id, estrategia, tipo, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    244   3�      �          0    47683 !   foda_cruce_ambientes_has_amenazas 
   TABLE DATA           X   COPY planificacion.foda_cruce_ambientes_has_amenazas (cruce_id, amenaza_id) FROM stdin;
    planificacion          postgres    false    248   ��      �          0    47670 $   foda_cruce_ambientes_has_debilidades 
   TABLE DATA           ]   COPY planificacion.foda_cruce_ambientes_has_debilidades (cruce_id, debilidad_id) FROM stdin;
    planificacion          postgres    false    247    �      �          0    47644 #   foda_cruce_ambientes_has_fortalezas 
   TABLE DATA           \   COPY planificacion.foda_cruce_ambientes_has_fortalezas (cruce_id, fortaleza_id) FROM stdin;
    planificacion          postgres    false    245   8�      �          0    47657 &   foda_cruce_ambientes_has_oportunidades 
   TABLE DATA           a   COPY planificacion.foda_cruce_ambientes_has_oportunidades (cruce_id, oportunidad_id) FROM stdin;
    planificacion          postgres    false    246   ��      �          0    47583    foda_groups_has_perfiles 
   TABLE DATA           j   COPY planificacion.foda_groups_has_perfiles (id, perfil_id, group_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    240   ƅ      �          0    47533    foda_models 
   TABLE DATA           �   COPY planificacion.foda_models (id, type, name, owner, environment, description, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    236   �      �          0    47545    foda_perfiles 
   TABLE DATA           �   COPY planificacion.foda_perfiles (id, name, context, type, model_id, group_id, dependency_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    237   l�      �          0    47696    pei_profiles 
   TABLE DATA           X  COPY planificacion.pei_profiles (id, name, year_start, year_end, type, level, mision, vision, "values", period, numerator, operator, denominator, goal, progress, order_item, "year_start ", "year_end ", action, indicator, baseline, target, group_id, dependency_id, user_id, _lft, _rgt, deleted_at, created_at, updated_at, parent_id) FROM stdin;
    planificacion          postgres    false    249   ��      �          0    47724    pei_profiles_has_dependencies 
   TABLE DATA           y   COPY planificacion.pei_profiles_has_dependencies (id, pei_profile_id, dependency_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    251   �.                0    48001    pei_profiles_has_strategies 
   TABLE DATA           q   COPY planificacion.pei_profiles_has_strategies (id, profile_id, strategy_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    294   /      �          0    47742    peis_profiles_has_analysts 
   TABLE DATA           s   COPY planificacion.peis_profiles_has_analysts (id, pei_profile_id, analyst_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    253   �/      
          0    48019    peis_profiles_has_responsibles 
   TABLE DATA           w   COPY planificacion.peis_profiles_has_responsibles (id, profile_id, responsible_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    296   {1                0    47955    tasks 
   TABLE DATA           ]   COPY planificacion.tasks (id, group_id, details, status, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    288   ;                0    47969    tasks_has_analysts 
   TABLE DATA           d   COPY planificacion.tasks_has_analysts (id, task_id, analyst_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    290   �<                0    47987    tasks_has_type_tasks 
   TABLE DATA           �   COPY planificacion.tasks_has_type_tasks (id, task_id, typetaskable_id, typetaskable_type, status, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    292   N=                 0    47947 	   typetasks 
   TABLE DATA           p   COPY planificacion.typetasks (id, name, typetaskable_id, typetaskable_type, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    286   �A      �          0    47782    e_p_c_equipamientos 
   TABLE DATA           ]   COPY proyecto.e_p_c_equipamientos (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    259   pH      �          0    47823    e_p_c_equipamientos_servicios 
   TABLE DATA           a   COPY proyecto.e_p_c_equipamientos_servicios (servicio_id, equipamiento_id, cantidad) FROM stdin;
    proyecto          postgres    false    268   �H      �          0    47760    e_p_c_especialidads 
   TABLE DATA           e   COPY proyecto.e_p_c_especialidads (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    255   �H      �          0    47907    e_p_c_estandars 
   TABLE DATA           [   COPY proyecto.e_p_c_estandars (id, item, type, detail, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    279   �H      �          0    47916    e_p_c_estandars_servicios 
   TABLE DATA           Y   COPY proyecto.e_p_c_estandars_servicios (estandar_id, servicio_id, cantidad) FROM stdin;
    proyecto          postgres    false    280   �H      �          0    47864    e_p_c_horarios 
   TABLE DATA           b   COPY proyecto.e_p_c_horarios (id, item, start_time, end_time, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    272   I      �          0    47849    e_p_c_infraestructura_servicio 
   TABLE DATA           e   COPY proyecto.e_p_c_infraestructura_servicio (servicio_id, infraestructura_id, cantidad) FROM stdin;
    proyecto          postgres    false    270   I      �          0    47790    e_p_c_infraestructuras 
   TABLE DATA           m   COPY proyecto.e_p_c_infraestructuras (id, item, type, measurement, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    261   ;I      �          0    47806    e_p_c_medicamento_insumos 
   TABLE DATA           c   COPY proyecto.e_p_c_medicamento_insumos (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    265   XI      �          0    47798    e_p_c_otro_servicios 
   TABLE DATA           ^   COPY proyecto.e_p_c_otro_servicios (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    263   uI      �          0    47883    e_p_c_prestaciones 
   TABLE DATA           d   COPY proyecto.e_p_c_prestaciones (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    276   �I      �          0    47814    e_p_c_servicios 
   TABLE DATA           `   COPY proyecto.e_p_c_servicios (id, item, type, description, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    267   �I      �          0    47771    e_p_c_talento_humanos 
   TABLE DATA           f   COPY proyecto.e_p_c_talento_humanos (id, item, hours, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    257   �I      �          0    47836    e_p_c_tthhs_servicios 
   TABLE DATA           Q   COPY proyecto.e_p_c_tthhs_servicios (servicio_id, tthh_id, cantidad) FROM stdin;
    proyecto          postgres    false    269   �I      �          0    47875    e_p_c_turnos 
   TABLE DATA           J   COPY proyecto.e_p_c_turnos (id, item, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    274   J      �          0    47892    otroservicio_has_servicios 
   TABLE DATA           _   COPY proyecto.otroservicio_has_servicios (servicio_id, otro_servicio_id, cantidad) FROM stdin;
    proyecto          postgres    false    277   #J                0    48059 
   activities 
   TABLE DATA           @   COPY public.activities (id, created_at, updated_at) FROM stdin;
    public          postgres    false    302   @J      �          0    47939 
   categories 
   TABLE DATA           F   COPY public.categories (id, name, created_at, updated_at) FROM stdin;
    public          postgres    false    284   ]J      �          0    47295    groups 
   TABLE DATA           Y   COPY public.groups (id, name, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
    public          postgres    false    208   zJ      �          0    47381    groups_has_members 
   TABLE DATA           [   COPY public.groups_has_members (id, group_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    220   �N                0    48037 
   localities 
   TABLE DATA           y   COPY public.localities (id, cod_dpto, desc_dpto, cod_dist, desc_dist, area, cod_barrio_loc, desc_barrio_loc) FROM stdin;
    public          postgres    false    298   dS      �          0    47287 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public          postgres    false    206   ��      �          0    47342    model_has_permissions 
   TABLE DATA           T   COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
    public          postgres    false    216   l�      �          0    47353    model_has_roles 
   TABLE DATA           H   COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
    public          postgres    false    217   ��      �          0    47399    organigramas 
   TABLE DATA           �   COPY public.organigramas (id, dependency, _lft, _rgt, parent_id, manager, phone, email, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    222   ��      �          0    47322    password_resets 
   TABLE DATA           C   COPY public.password_resets (email, token, created_at) FROM stdin;
    public          postgres    false    211   ��                0    48048    patrimonies 
   TABLE DATA           �  COPY public.patrimonies (id, type, quantity_account_current, detail_location, estate_quantity, department, city, locality, latitude, longitude, location_address, infrastructure_type, description, registry_number, cadastral_current_account, estate_status, committed_investment, transfer, balance_for_transfer, tenant, rent_amount, rent_amount_period, contract_resolution, contract_number, current_period_start, current_period_end, status_documentation, land_area_mt2, land_area_hectares, land_sub_area, built_area_m2, built_value_gs, property_value_gs, total_value_gs, possession_rent_without_title, main_photo_file, main_photo_file_path, evidence_file, evidence_file_path, created_at, updated_at) FROM stdin;
    public          postgres    false    300   �      �          0    47328    permissions 
   TABLE DATA           S   COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    213   %�      �          0    47931    risks 
   TABLE DATA           I   COPY public.risks (id, name, detail, created_at, updated_at) FROM stdin;
    public          postgres    false    282   ��      �          0    47364    role_has_permissions 
   TABLE DATA           F   COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
    public          postgres    false    218   ��      �          0    47336    roles 
   TABLE DATA           M   COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    215   ��      �          0    47420 	   servicios 
   TABLE DATA           k   COPY public.servicios (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    224   @�      �          0    47306    users 
   TABLE DATA              COPY public.users (id, name, email, group_id, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
    public          postgres    false    210   ]�      @           0    0     formulario_clasificadores_id_seq    SEQUENCE SET     T   SELECT pg_catalog.setval('estadistica.formulario_clasificadores_id_seq', 1, false);
          estadistica          postgres    false    233            A           0    0 *   formulario_formulario_has_variables_id_seq    SEQUENCE SET     ^   SELECT pg_catalog.setval('estadistica.formulario_formulario_has_variables_id_seq', 1, false);
          estadistica          postgres    false    231            B           0    0    formulario_formularios_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('estadistica.formulario_formularios_id_seq', 1, false);
          estadistica          postgres    false    229            C           0    0    formulario_items_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('estadistica.formulario_items_id_seq', 1, false);
          estadistica          postgres    false    227            D           0    0    formulario_variables_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('estadistica.formulario_variables_id_seq', 1, false);
          estadistica          postgres    false    225            E           0    0    foda_analisis_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('planificacion.foda_analisis_id_seq', 3208, true);
          planificacion          postgres    false    241            F           0    0    foda_cruce_ambientes_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('planificacion.foda_cruce_ambientes_id_seq', 25, true);
          planificacion          postgres    false    243            G           0    0    foda_groups_has_perfiles_id_seq    SEQUENCE SET     U   SELECT pg_catalog.setval('planificacion.foda_groups_has_perfiles_id_seq', 1, false);
          planificacion          postgres    false    239            H           0    0    foda_models_id_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('planificacion.foda_models_id_seq', 216, true);
          planificacion          postgres    false    235            I           0    0 $   pei_profiles_has_dependencies_id_seq    SEQUENCE SET     Z   SELECT pg_catalog.setval('planificacion.pei_profiles_has_dependencies_id_seq', 1, false);
          planificacion          postgres    false    250            J           0    0 "   pei_profiles_has_strategies_id_seq    SEQUENCE SET     X   SELECT pg_catalog.setval('planificacion.pei_profiles_has_strategies_id_seq', 10, true);
          planificacion          postgres    false    293            K           0    0 !   peis_profiles_has_analysts_id_seq    SEQUENCE SET     W   SELECT pg_catalog.setval('planificacion.peis_profiles_has_analysts_id_seq', 32, true);
          planificacion          postgres    false    252            L           0    0 %   peis_profiles_has_responsibles_id_seq    SEQUENCE SET     \   SELECT pg_catalog.setval('planificacion.peis_profiles_has_responsibles_id_seq', 115, true);
          planificacion          postgres    false    295            M           0    0    tasks_has_analysts_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('planificacion.tasks_has_analysts_id_seq', 47, true);
          planificacion          postgres    false    289            N           0    0    tasks_has_type_tasks_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('planificacion.tasks_has_type_tasks_id_seq', 1, false);
          planificacion          postgres    false    291            O           0    0    tasks_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('planificacion.tasks_id_seq', 43, true);
          planificacion          postgres    false    287            P           0    0    typetasks_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('planificacion.typetasks_id_seq', 50, true);
          planificacion          postgres    false    285            Q           0    0    e_p_c_equipamientos_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('proyecto.e_p_c_equipamientos_id_seq', 1, false);
          proyecto          postgres    false    258            R           0    0    e_p_c_especialidads_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('proyecto.e_p_c_especialidads_id_seq', 1, false);
          proyecto          postgres    false    254            S           0    0    e_p_c_estandars_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('proyecto.e_p_c_estandars_id_seq', 1, false);
          proyecto          postgres    false    278            T           0    0    e_p_c_horarios_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('proyecto.e_p_c_horarios_id_seq', 1, false);
          proyecto          postgres    false    271            U           0    0    e_p_c_infraestructuras_id_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('proyecto.e_p_c_infraestructuras_id_seq', 1, false);
          proyecto          postgres    false    260            V           0    0     e_p_c_medicamento_insumos_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('proyecto.e_p_c_medicamento_insumos_id_seq', 1, false);
          proyecto          postgres    false    264            W           0    0    e_p_c_otro_servicios_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('proyecto.e_p_c_otro_servicios_id_seq', 1, false);
          proyecto          postgres    false    262            X           0    0    e_p_c_prestaciones_id_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('proyecto.e_p_c_prestaciones_id_seq', 1, false);
          proyecto          postgres    false    275            Y           0    0    e_p_c_servicios_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('proyecto.e_p_c_servicios_id_seq', 1, false);
          proyecto          postgres    false    266            Z           0    0    e_p_c_talento_humanos_id_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('proyecto.e_p_c_talento_humanos_id_seq', 1, false);
          proyecto          postgres    false    256            [           0    0    e_p_c_turnos_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('proyecto.e_p_c_turnos_id_seq', 1, false);
          proyecto          postgres    false    273            \           0    0    activities_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.activities_id_seq', 1, false);
          public          postgres    false    301            ]           0    0    categories_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.categories_id_seq', 1, false);
          public          postgres    false    283            ^           0    0    groups_has_members_id_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('public.groups_has_members_id_seq', 259, true);
          public          postgres    false    219            _           0    0    groups_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.groups_id_seq', 42, true);
          public          postgres    false    207            `           0    0    localities_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.localities_id_seq', 1, false);
          public          postgres    false    297            a           0    0    migrations_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.migrations_id_seq', 52, true);
          public          postgres    false    205            b           0    0    organigramas_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.organigramas_id_seq', 15, true);
          public          postgres    false    221            c           0    0    patrimonies_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.patrimonies_id_seq', 1, false);
          public          postgres    false    299            d           0    0    permissions_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.permissions_id_seq', 4, true);
          public          postgres    false    212            e           0    0    risks_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.risks_id_seq', 1, false);
          public          postgres    false    281            f           0    0    roles_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.roles_id_seq', 3, true);
          public          postgres    false    214            g           0    0    servicios_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.servicios_id_seq', 1, false);
          public          postgres    false    223            h           0    0    users_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.users_id_seq', 227, true);
          public          postgres    false    209            �           2606    47520 8   formulario_clasificadores formulario_clasificadores_pkey 
   CONSTRAINT     {   ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT formulario_clasificadores_pkey PRIMARY KEY (id);
 g   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT formulario_clasificadores_pkey;
       estadistica            postgres    false    234            �           2606    47502 L   formulario_formulario_has_variables formulario_formulario_has_variables_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT formulario_formulario_has_variables_pkey PRIMARY KEY (id);
 {   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT formulario_formulario_has_variables_pkey;
       estadistica            postgres    false    232            �           2606    47478 2   formulario_formularios formulario_formularios_pkey 
   CONSTRAINT     u   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT formulario_formularios_pkey PRIMARY KEY (id);
 a   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT formulario_formularios_pkey;
       estadistica            postgres    false    230            �           2606    47460 &   formulario_items formulario_items_pkey 
   CONSTRAINT     i   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT formulario_items_pkey PRIMARY KEY (id);
 U   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT formulario_items_pkey;
       estadistica            postgres    false    228            �           2606    47443 .   formulario_variables formulario_variables_pkey 
   CONSTRAINT     q   ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT formulario_variables_pkey PRIMARY KEY (id);
 ]   ALTER TABLE ONLY estadistica.formulario_variables DROP CONSTRAINT formulario_variables_pkey;
       estadistica            postgres    false    226            �           2606    47607     foda_analisis foda_analisis_pkey 
   CONSTRAINT     e   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT foda_analisis_pkey PRIMARY KEY (id);
 Q   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT foda_analisis_pkey;
       planificacion            postgres    false    242            �           2606    47633 .   foda_cruce_ambientes foda_cruce_ambientes_pkey 
   CONSTRAINT     s   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT foda_cruce_ambientes_pkey PRIMARY KEY (id);
 _   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT foda_cruce_ambientes_pkey;
       planificacion            postgres    false    244            �           2606    47588 6   foda_groups_has_perfiles foda_groups_has_perfiles_pkey 
   CONSTRAINT     {   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT foda_groups_has_perfiles_pkey PRIMARY KEY (id);
 g   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT foda_groups_has_perfiles_pkey;
       planificacion            postgres    false    240            �           2606    47543    foda_models foda_models_pkey 
   CONSTRAINT     a   ALTER TABLE ONLY planificacion.foda_models
    ADD CONSTRAINT foda_models_pkey PRIMARY KEY (id);
 M   ALTER TABLE ONLY planificacion.foda_models DROP CONSTRAINT foda_models_pkey;
       planificacion            postgres    false    236            �           2606    47567     foda_perfiles foda_perfiles_pkey 
   CONSTRAINT     e   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT foda_perfiles_pkey PRIMARY KEY (id);
 Q   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT foda_perfiles_pkey;
       planificacion            postgres    false    237            �           2606    47729 @   pei_profiles_has_dependencies pei_profiles_has_dependencies_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT pei_profiles_has_dependencies_pkey PRIMARY KEY (id);
 q   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT pei_profiles_has_dependencies_pkey;
       planificacion            postgres    false    251            �           2606    48006 <   pei_profiles_has_strategies pei_profiles_has_strategies_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT pei_profiles_has_strategies_pkey PRIMARY KEY (id);
 m   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT pei_profiles_has_strategies_pkey;
       planificacion            postgres    false    294            �           2606    47721    pei_profiles pei_profiles_pkey 
   CONSTRAINT     c   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT pei_profiles_pkey PRIMARY KEY (id);
 O   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT pei_profiles_pkey;
       planificacion            postgres    false    249            �           2606    47747 :   peis_profiles_has_analysts peis_profiles_has_analysts_pkey 
   CONSTRAINT        ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT peis_profiles_has_analysts_pkey PRIMARY KEY (id);
 k   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT peis_profiles_has_analysts_pkey;
       planificacion            postgres    false    253            �           2606    48024 B   peis_profiles_has_responsibles peis_profiles_has_responsibles_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT peis_profiles_has_responsibles_pkey PRIMARY KEY (id);
 s   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT peis_profiles_has_responsibles_pkey;
       planificacion            postgres    false    296            �           2606    47974 *   tasks_has_analysts tasks_has_analysts_pkey 
   CONSTRAINT     o   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT tasks_has_analysts_pkey PRIMARY KEY (id);
 [   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT tasks_has_analysts_pkey;
       planificacion            postgres    false    290            �           2606    47993 .   tasks_has_type_tasks tasks_has_type_tasks_pkey 
   CONSTRAINT     s   ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT tasks_has_type_tasks_pkey PRIMARY KEY (id);
 _   ALTER TABLE ONLY planificacion.tasks_has_type_tasks DROP CONSTRAINT tasks_has_type_tasks_pkey;
       planificacion            postgres    false    292            �           2606    47961    tasks tasks_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT tasks_pkey PRIMARY KEY (id);
 A   ALTER TABLE ONLY planificacion.tasks DROP CONSTRAINT tasks_pkey;
       planificacion            postgres    false    288            �           2606    47952    typetasks typetasks_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY planificacion.typetasks
    ADD CONSTRAINT typetasks_pkey PRIMARY KEY (id);
 I   ALTER TABLE ONLY planificacion.typetasks DROP CONSTRAINT typetasks_pkey;
       planificacion            postgres    false    286            �           2606    47787 ,   e_p_c_equipamientos e_p_c_equipamientos_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY proyecto.e_p_c_equipamientos
    ADD CONSTRAINT e_p_c_equipamientos_pkey PRIMARY KEY (id);
 X   ALTER TABLE ONLY proyecto.e_p_c_equipamientos DROP CONSTRAINT e_p_c_equipamientos_pkey;
       proyecto            postgres    false    259            �           2606    47768 ,   e_p_c_especialidads e_p_c_especialidads_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY proyecto.e_p_c_especialidads
    ADD CONSTRAINT e_p_c_especialidads_pkey PRIMARY KEY (id);
 X   ALTER TABLE ONLY proyecto.e_p_c_especialidads DROP CONSTRAINT e_p_c_especialidads_pkey;
       proyecto            postgres    false    255            �           2606    47915 $   e_p_c_estandars e_p_c_estandars_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY proyecto.e_p_c_estandars
    ADD CONSTRAINT e_p_c_estandars_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY proyecto.e_p_c_estandars DROP CONSTRAINT e_p_c_estandars_pkey;
       proyecto            postgres    false    279            �           2606    47872 "   e_p_c_horarios e_p_c_horarios_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY proyecto.e_p_c_horarios
    ADD CONSTRAINT e_p_c_horarios_pkey PRIMARY KEY (id);
 N   ALTER TABLE ONLY proyecto.e_p_c_horarios DROP CONSTRAINT e_p_c_horarios_pkey;
       proyecto            postgres    false    272            �           2606    47795 2   e_p_c_infraestructuras e_p_c_infraestructuras_pkey 
   CONSTRAINT     r   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras
    ADD CONSTRAINT e_p_c_infraestructuras_pkey PRIMARY KEY (id);
 ^   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras DROP CONSTRAINT e_p_c_infraestructuras_pkey;
       proyecto            postgres    false    261            �           2606    47811 8   e_p_c_medicamento_insumos e_p_c_medicamento_insumos_pkey 
   CONSTRAINT     x   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos
    ADD CONSTRAINT e_p_c_medicamento_insumos_pkey PRIMARY KEY (id);
 d   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos DROP CONSTRAINT e_p_c_medicamento_insumos_pkey;
       proyecto            postgres    false    265            �           2606    47803 .   e_p_c_otro_servicios e_p_c_otro_servicios_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios
    ADD CONSTRAINT e_p_c_otro_servicios_pkey PRIMARY KEY (id);
 Z   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios DROP CONSTRAINT e_p_c_otro_servicios_pkey;
       proyecto            postgres    false    263            �           2606    47891 *   e_p_c_prestaciones e_p_c_prestaciones_pkey 
   CONSTRAINT     j   ALTER TABLE ONLY proyecto.e_p_c_prestaciones
    ADD CONSTRAINT e_p_c_prestaciones_pkey PRIMARY KEY (id);
 V   ALTER TABLE ONLY proyecto.e_p_c_prestaciones DROP CONSTRAINT e_p_c_prestaciones_pkey;
       proyecto            postgres    false    276            �           2606    47822 $   e_p_c_servicios e_p_c_servicios_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY proyecto.e_p_c_servicios
    ADD CONSTRAINT e_p_c_servicios_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY proyecto.e_p_c_servicios DROP CONSTRAINT e_p_c_servicios_pkey;
       proyecto            postgres    false    267            �           2606    47779 0   e_p_c_talento_humanos e_p_c_talento_humanos_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos
    ADD CONSTRAINT e_p_c_talento_humanos_pkey PRIMARY KEY (id);
 \   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos DROP CONSTRAINT e_p_c_talento_humanos_pkey;
       proyecto            postgres    false    257            �           2606    47880    e_p_c_turnos e_p_c_turnos_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY proyecto.e_p_c_turnos
    ADD CONSTRAINT e_p_c_turnos_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY proyecto.e_p_c_turnos DROP CONSTRAINT e_p_c_turnos_pkey;
       proyecto            postgres    false    274            �           2606    48064    activities activities_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.activities
    ADD CONSTRAINT activities_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.activities DROP CONSTRAINT activities_pkey;
       public            postgres    false    302            �           2606    47944    categories categories_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.categories DROP CONSTRAINT categories_pkey;
       public            postgres    false    284            �           2606    47386 *   groups_has_members groups_has_members_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_pkey PRIMARY KEY (id);
 T   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_pkey;
       public            postgres    false    220            �           2606    47302    groups groups_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.groups DROP CONSTRAINT groups_pkey;
       public            postgres    false    208            �           2606    48045    localities localities_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.localities
    ADD CONSTRAINT localities_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.localities DROP CONSTRAINT localities_pkey;
       public            postgres    false    298            �           2606    47292    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public            postgres    false    206            �           2606    47352 0   model_has_permissions model_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);
 Z   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_pkey;
       public            postgres    false    216    216    216            �           2606    47363 $   model_has_roles model_has_roles_pkey 
   CONSTRAINT     }   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);
 N   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_pkey;
       public            postgres    false    217    217    217            �           2606    47417 &   organigramas organigramas_email_unique 
   CONSTRAINT     b   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_email_unique UNIQUE (email);
 P   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_email_unique;
       public            postgres    false    222            �           2606    47409    organigramas organigramas_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_pkey;
       public            postgres    false    222            �           2606    48056    patrimonies patrimonies_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.patrimonies
    ADD CONSTRAINT patrimonies_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.patrimonies DROP CONSTRAINT patrimonies_pkey;
       public            postgres    false    300            �           2606    47333    permissions permissions_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_pkey;
       public            postgres    false    213            �           2606    47936    risks risks_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.risks
    ADD CONSTRAINT risks_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.risks DROP CONSTRAINT risks_pkey;
       public            postgres    false    282            �           2606    47378 .   role_has_permissions role_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);
 X   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_pkey;
       public            postgres    false    218    218            �           2606    47341    roles roles_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
       public            postgres    false    215            �           2606    47427    servicios servicios_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.servicios DROP CONSTRAINT servicios_pkey;
       public            postgres    false    224            �           2606    47321    users users_email_unique 
   CONSTRAINT     T   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
       public            postgres    false    210            �           2606    47314    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    210            �           1259    47444 :   estadistica_formulario_variables__lft__rgt_parent_id_index    INDEX     �   CREATE INDEX estadistica_formulario_variables__lft__rgt_parent_id_index ON estadistica.formulario_variables USING btree (_lft, _rgt, parent_id);
 S   DROP INDEX estadistica.estadistica_formulario_variables__lft__rgt_parent_id_index;
       estadistica            postgres    false    226    226    226            �           1259    47544 3   planificacion_foda_models__lft__rgt_parent_id_index    INDEX     �   CREATE INDEX planificacion_foda_models__lft__rgt_parent_id_index ON planificacion.foda_models USING btree (_lft, _rgt, parent_id);
 N   DROP INDEX planificacion.planificacion_foda_models__lft__rgt_parent_id_index;
       planificacion            postgres    false    236    236    236            �           1259    47303     groups__lft__rgt_parent_id_index    INDEX     d   CREATE INDEX groups__lft__rgt_parent_id_index ON public.groups USING btree (_lft, _rgt, parent_id);
 4   DROP INDEX public.groups__lft__rgt_parent_id_index;
       public            postgres    false    208    208    208            �           1259    47345 /   model_has_permissions_model_id_model_type_index    INDEX     �   CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);
 C   DROP INDEX public.model_has_permissions_model_id_model_type_index;
       public            postgres    false    216    216            �           1259    47356 )   model_has_roles_model_id_model_type_index    INDEX     u   CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);
 =   DROP INDEX public.model_has_roles_model_id_model_type_index;
       public            postgres    false    217    217            �           1259    47410 &   organigramas__lft__rgt_parent_id_index    INDEX     p   CREATE INDEX organigramas__lft__rgt_parent_id_index ON public.organigramas USING btree (_lft, _rgt, parent_id);
 :   DROP INDEX public.organigramas__lft__rgt_parent_id_index;
       public            postgres    false    222    222    222            �           1259    47325    password_resets_email_index    INDEX     X   CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);
 /   DROP INDEX public.password_resets_email_index;
       public            postgres    false    211            �           1259    47428 #   servicios__lft__rgt_parent_id_index    INDEX     j   CREATE INDEX servicios__lft__rgt_parent_id_index ON public.servicios USING btree (_lft, _rgt, parent_id);
 7   DROP INDEX public.servicios__lft__rgt_parent_id_index;
       public            postgres    false    224    224    224                       2606    47521 W   formulario_clasificadores estadistica_formulario_clasificadores_clasificador_id_foreign    FK CONSTRAINT        ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign FOREIGN KEY (clasificador_id) REFERENCES estadistica.formulario_clasificadores(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign;
       estadistica          postgres    false    3251    234    234                       2606    47526 O   formulario_clasificadores estadistica_formulario_clasificadores_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 ~   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign;
       estadistica          postgres    false    210    3217    234                        2606    47503 c   formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_formulario_id_f    FK CONSTRAINT       ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f FOREIGN KEY (formulario_id) REFERENCES estadistica.formulario_formularios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f;
       estadistica          postgres    false    230    232    3247                       2606    47508 c   formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_variable_id_for    FK CONSTRAINT       ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for;
       estadistica          postgres    false    226    232    3243            �           2606    47479 V   formulario_formularios estadistica_formulario_formularios_dependencia_emisor_id_foreig    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig FOREIGN KEY (dependencia_emisor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig;
       estadistica          postgres    false    222    3237    230            �           2606    47484 V   formulario_formularios estadistica_formulario_formularios_dependencia_receptor_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore FOREIGN KEY (dependencia_receptor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore;
       estadistica          postgres    false    222    230    3237            �           2606    47489 I   formulario_formularios estadistica_formulario_formularios_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_user_id_foreign;
       estadistica          postgres    false    230    210    3217            �           2606    47466 =   formulario_items estadistica_formulario_items_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 l   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT estadistica_formulario_items_user_id_foreign;
       estadistica          postgres    false    3217    228    210            �           2606    47461 A   formulario_items estadistica_formulario_items_variable_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT estadistica_formulario_items_variable_id_foreign;
       estadistica          postgres    false    228    226    3243            �           2606    47445 E   formulario_variables estadistica_formulario_variables_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT estadistica_formulario_variables_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY estadistica.formulario_variables DROP CONSTRAINT estadistica_formulario_variables_user_id_foreign;
       estadistica          postgres    false    3217    226    210                       2606    47618 <   foda_analisis planificacion_foda_analisis_aspecto_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign FOREIGN KEY (aspecto_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 m   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign;
       planificacion          postgres    false    3253    242    236                       2606    47613 ;   foda_analisis planificacion_foda_analisis_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 l   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_perfil_id_foreign;
       planificacion          postgres    false    3256    237    242                       2606    47608 9   foda_analisis planificacion_foda_analisis_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 j   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_user_id_foreign;
       planificacion          postgres    false    3217    242    210                       2606    47576 W   foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_category_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign FOREIGN KEY (category_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil DROP CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign;
       planificacion          postgres    false    238    3253    236                       2606    47571 U   foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil DROP CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign;
       planificacion          postgres    false    237    238    3256                       2606    47691 a   foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore FOREIGN KEY (amenaza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore;
       planificacion          postgres    false    248    3253    236                       2606    47686 a   foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig;
       planificacion          postgres    false    3262    248    244                       2606    47673 d   foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for;
       planificacion          postgres    false    247    3262    244                       2606    47678 d   foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id    FK CONSTRAINT        ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id FOREIGN KEY (debilidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id;
       planificacion          postgres    false    3253    247    236                       2606    47647 c   foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore;
       planificacion          postgres    false    245    3262    244                       2606    47652 c   foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_ FOREIGN KEY (fortaleza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_;
       planificacion          postgres    false    3253    236    245                       2606    47660 f   foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f;
       planificacion          postgres    false    246    244    3262                       2606    47665 f   foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_oportunida    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida FOREIGN KEY (oportunidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida;
       planificacion          postgres    false    246    236    3253                       2606    47639 I   foda_cruce_ambientes planificacion_foda_cruce_ambientes_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 z   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign;
       planificacion          postgres    false    237    3256    244                       2606    47634 G   foda_cruce_ambientes planificacion_foda_cruce_ambientes_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign;
       planificacion          postgres    false    244    210    3217            
           2606    47594 P   foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign;
       planificacion          postgres    false    208    240    3213            	           2606    47589 Q   foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign;
       planificacion          postgres    false    237    3256    240                       2606    47561 ?   foda_perfiles planificacion_foda_perfiles_dependency_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign;
       planificacion          postgres    false    222    237    3237                       2606    47556 :   foda_perfiles planificacion_foda_perfiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 k   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_group_id_foreign;
       planificacion          postgres    false    3213    237    208                       2606    47551 :   foda_perfiles planificacion_foda_perfiles_model_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_model_id_foreign FOREIGN KEY (model_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 k   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_model_id_foreign;
       planificacion          postgres    false    237    236    3253                       2606    47709 =   pei_profiles planificacion_pei_profiles_dependency_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_dependency_id_foreign FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 n   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT planificacion_pei_profiles_dependency_id_foreign;
       planificacion          postgres    false    249    3237    222                       2606    47704 8   pei_profiles planificacion_pei_profiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 i   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT planificacion_pei_profiles_group_id_foreign;
       planificacion          postgres    false    208    249    3213                       2606    47735 ]   pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_dependency_id_forei    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei;
       planificacion          postgres    false    251    3237    222                       2606    47730 ]   pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_pei_profile_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore;
       planificacion          postgres    false    251    3264    249            -           2606    48007 X   pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign;
       planificacion          postgres    false    294    3264    249            .           2606    48012 Y   pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_strategy_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign FOREIGN KEY (strategy_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign;
       planificacion          postgres    false    294    3262    244                       2606    47714 7   pei_profiles planificacion_pei_profiles_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 h   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT planificacion_pei_profiles_user_id_foreign;
       planificacion          postgres    false    3217    249    210                       2606    47753 V   peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_analyst_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign;
       planificacion          postgres    false    3217    210    253                       2606    47748 Z   peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_pei_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign;
       planificacion          postgres    false    3264    253    249            /           2606    48025 ^   peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign;
       planificacion          postgres    false    249    3264    296            0           2606    48030 ^   peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_responsible_id_for    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for FOREIGN KEY (responsible_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for;
       planificacion          postgres    false    3237    296    222            )           2606    47962 *   tasks planificacion_tasks_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT planificacion_tasks_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 [   ALTER TABLE ONLY planificacion.tasks DROP CONSTRAINT planificacion_tasks_group_id_foreign;
       planificacion          postgres    false    288    3213    208            +           2606    47980 F   tasks_has_analysts planificacion_tasks_has_analysts_analyst_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 w   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign;
       planificacion          postgres    false    3217    210    290            *           2606    47975 C   tasks_has_analysts planificacion_tasks_has_analysts_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign;
       planificacion          postgres    false    3298    288    290            ,           2606    47994 G   tasks_has_type_tasks planificacion_tasks_has_type_tasks_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT planificacion_tasks_has_type_tasks_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY planificacion.tasks_has_type_tasks DROP CONSTRAINT planificacion_tasks_has_type_tasks_task_id_foreign;
       planificacion          postgres    false    292    288    3298                        2606    47831 \   e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign FOREIGN KEY (equipamiento_id) REFERENCES proyecto.e_p_c_equipamientos(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios DROP CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign;
       proyecto          postgres    false    259    268    3274                       2606    47826 X   e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios DROP CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign;
       proyecto          postgres    false    3282    268    267            '           2606    47919 P   e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_estandar_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign FOREIGN KEY (estandar_id) REFERENCES proyecto.e_p_c_estandars(id) ON UPDATE CASCADE ON DELETE CASCADE;
 |   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios DROP CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign;
       proyecto          postgres    false    279    280    3290            (           2606    47924 P   e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 |   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios DROP CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign;
       proyecto          postgres    false    280    3282    267            $           2606    47857 ^   e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore    FK CONSTRAINT       ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore FOREIGN KEY (infraestructura_id) REFERENCES proyecto.e_p_c_infraestructuras(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio DROP CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore;
       proyecto          postgres    false    270    261    3276            #           2606    47852 Z   e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio DROP CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign;
       proyecto          postgres    false    270    267    3282            !           2606    47839 H   e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios DROP CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign;
       proyecto          postgres    false    3282    267    269            "           2606    47844 D   e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_tthh_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign FOREIGN KEY (tthh_id) REFERENCES proyecto.e_p_c_talento_humanos(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios DROP CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign;
       proyecto          postgres    false    269    3272    257            &           2606    47900 W   otroservicio_has_servicios proyecto_otroservicio_has_servicios_otro_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign FOREIGN KEY (otro_servicio_id) REFERENCES proyecto.e_p_c_otro_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios DROP CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign;
       proyecto          postgres    false    3278    277    263            %           2606    47895 R   otroservicio_has_servicios proyecto_otroservicio_has_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 ~   ALTER TABLE ONLY proyecto.otroservicio_has_servicios DROP CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign;
       proyecto          postgres    false    267    3282    277            �           2606    47387 6   groups_has_members groups_has_members_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 `   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_group_id_foreign;
       public          postgres    false    3213    220    208            �           2606    47392 5   groups_has_members groups_has_members_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 _   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_user_id_foreign;
       public          postgres    false    220    3217    210            �           2606    47346 A   model_has_permissions model_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 k   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_permission_id_foreign;
       public          postgres    false    3220    213    216            �           2606    47357 /   model_has_roles model_has_roles_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 Y   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_role_id_foreign;
       public          postgres    false    3222    215    217            �           2606    47411 )   organigramas organigramas_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_user_id_foreign;
       public          postgres    false    3217    222    210            �           2606    47367 ?   role_has_permissions role_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 i   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_permission_id_foreign;
       public          postgres    false    218    3220    213            �           2606    47372 9   role_has_permissions role_has_permissions_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 c   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_role_id_foreign;
       public          postgres    false    215    218    3222            �           2606    47429 #   servicios servicios_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 M   ALTER TABLE ONLY public.servicios DROP CONSTRAINT servicios_user_id_foreign;
       public          postgres    false    224    3217    210            �           2606    47315    users users_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 F   ALTER TABLE ONLY public.users DROP CONSTRAINT users_group_id_foreign;
       public          postgres    false    210    3213    208            �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x���O��:�_Ǚ�����̙�öP���H���a�'^�IE�ߗ!^�ø����<J��%��t���V?�Ǽ��+n1�}~{����|��?�[.o��~��������m��������>ln�\�/.~��#ԧ�������>8م-����ϟc+��[:r�c
�������������?������ן�}�������9�ĩ��<����/�r���o����۾�o���V���}��ps���JQA�#���G����A���F�	�p���0����g�o���X��v��������]s����D�Լ��p��<'y)pR��"&���2�����tMI���ՓH�����_m���o�m�����)�M���$>r3O2��܌z�L�#7����&>r�-5���M�^~p���qG$���H�{")qG$����#��M��O2�;"�zyZp��M���(qGD��M����i��0��G�槼PG苧)i���6/�6���W�����?���o���)�v�I�x��F������3�QE��I\F)B&qU�!d�ur�T�>�"h���~����S�"��!��\0��A;���ۺ��b�z������J��%�)�@�[����x���y��������:���:�3$Ut`3o�������haHq�x/��?(��{�����-�9b?����,a��Z��n��ᣭ ���DAH�P� ��	(`Q�?|�G��=J�ޏ~{o��t�ʠvh��"S��<��m����P��V�/�^���������;ݟ��z�v�����s�z�s2崖_8r��p{~w��'�Y�B����S�:��{�-�}D�ТV����S_�-J����sA��#R�b���LH�ݔ_���}��~a{�:uD�mо{��le݋m�ɿ������65��w�p��^mS����/d��w9l�z�n���ހ�ʖ���|��G�H�������z/o�!� Q)ϴ�P�/�x5�*}�+Vt�=��\��4�sWqŊ����{ �o]:���#��x:z�����x߷~�-�?����������z�Kx�d�}^�~�[#eJ+�9��mҗJsR�$}�4%�� >H��[._?[�I�����ů�kp���upPO�~�����Rz')����.7�m�n??����ۏ���VS���T������]j���:)cRm�r������1n���w�f?��~�v۾Þ�k7��R��Iao�ϕ�*J��O�J��c�J�x��W�>J���W�n�?�-���?q���)�VB4����׮�ݾN
��������k�S���zD_���#*��48�m��&�<��/R����9�.m�=�����U��士�ւ��׊�����rl|>�9A)���a�.#��ϭ��c�^$�p.����]{"�N���;D[
6R*����N�&�'�c"���W|�G���F�8�UR����_���'�������j��)�o�b�$�ߤs'�����{r�{�֊�m*�M�W�N�{Ԧf�V�&%f�+)t���<Mr��Bj�]=������+���"���f�@Jm�o$����!ܧ1�\b�=m�-�b�ϹO���m��J��&����=")�gR����x<;�o��8ݯ���.�bD*��m-�wi����T[]���_�yP�k+`��<��^��n?{^���2�O���ҦF�~��G��x?��$<��AAI�}��֫3�@:3\Ľ<z#�s��ɽ��,��)��#ڊ�U����_�m���S��<&6��H}<\���}�a���\i\�&T��Hm�^�ȍ����s�(�*";u3B ���.�7�z�J��p�#��G��D�y�����)�>r�}��վw�3��WϽN�{��=�*�_����xj����u���������'VxT[���_f�s��{�GxN���?�,!�:�a����s����&����W�ʉg��,�<���m�T{�|��%��ð�1Vz�6���GS����WZ��ߕ�νѶ����HhxP�z����\~�_?��|�c�Υ��^�VR�I�����#����=Ǚ��i��'�M�c��\���"Z���
>�=�#��$��o��s��9��6�1!��MۤG#�$�Iz"gN�Gr椀Iz&gN���'Kf��7��'W�������h2�TF �s�����r5Q�#R?y��ZY�OzfO$���(\��|zx�BOi���% (��Q��X@�a��y̕*�t,�<$�O��=H�ex�z%���@�X�����)#R9�O�'���Ц��y^��`��}�z��r��}�<$�Y�g�@�|t��"��>�ԣ�Tza�������{iŧ����z�������#�Qx�H�a=���qZ
m�=H�w� �|��9��(z�H�^��Xjx�rb��P|�ે�7�z�1r=�z8B��8�=��6���|�����Ԗ��6ʸ7�{�y�܎���'����p� G����sZ�����6�s;���>�6����������$<��@��=��<k0'�5x�`Fr�?�o�8�͹~��>�����xr�Z��P��~��=�ި����t�-� ��xM�n�����x�W���u�W/��ǑP-W/�'(�����ջܧ�K�r_kx��Oὅ��&��ڄWj#k�ѣ���K$�Y/����&��O�m*=n�^k��I����]=��K�c���+=��=�cI���vE��g H/^��8��!�㺛C��Iو�ܬ6<�j��Hx�NhN��}Bs��/���S���Bs~j�RhJ
xM��!����Hm<�V3Q��H��}<������W��5a�-�ڨ��6��ɟuD��`7G �����QG�4�5)���w	Ȭ���ӓ��U�#"^Q��hH:C>�z�� ��)�+��O���Y͵6��c~*x<er�F��g	����o���&��ﺑ�ưG���������?�O{��
��Q>R$�ʲ�ݜ������"�� "�ސt�!������5hP�{��35=^.��|{�Ox���j�G��y/��B�{����mJ���O��q>���%���������8� ΟƆ����vu謑ȾQx/��T�Ox�U,�6�s׭�5aB.�F�}<����	���{�\=��&g ë���g�qq}tl@�ι�F��K�O��\ˎ�����Ԧ�%�o�d�e�#¹�QpŒ^�s����/�b!;T�Ox�m�@�O�*���ˇm�g[qm��sB��߄���/O#G��t����уV�M�6�xo��F��'���y�UqњpԦ|�'��c��#�_^qъzԦ�4��W:<͹��|�q(8�7�dv���ixŕeDs��ꕇ#x���}���i$>�Ͻާ1����Ǩ�ѠG��\QW<F��Ջ��Q�u<��Fn�!�^��hU3�却���;��ZH��U^G�U��>�s���a#�ާV-�v��.v<Fͮ��O�����]��������.vz�Zn$�gw^��v菉���F�`L��]=��&�9��뵑�+��#��kB�C��J���km�+5T-mJ���O5���;���#�*�%R�5<��P�7$�G���{��j:����O�뮄6���Ϲ�e��D���r�Val~ʏ����rhL��?���g�W���t���0��/B��{���sѪ�?�U�]�/	Ϗ�/=����M8��H����/+�t�<�*�L��33$�1#���L��33���s�̐��33�3�s�̀�$�I��p��9	?>�<'qG�ށ>�<'aG��$�I��3�sv�L�>�<'aG��$�I��6qGmt`G��$�I��ٽ�:�8�
w�q~*��0Va��F�^Gm����e��w
~[��ʋ�N>�r�?J��t}.��u�v��ôW)=N�.��\��ʫ�̹�g�����O�t^���Ԅ�����K���Ʃ�%T�x�Sνހ�?Gɽ��q��"��0��    ��vYI-
�q�k��_}�S�Ut�+v_��q�U�UJ��q"'����Q�ָ'����d�w/٧�7�W�;���9_�� ��	�]z¥=�����GnOujO81�����|߷#��M�9m�;�-�{�>��y��bD�Ϧ�o�0
��P���x�H9CE�*R��P���f�����f��wvk�
���Q��2�zur����O���<^3������t����B��<?�}5����f��A	��|�����^��)�m������Jz�ax�-/��7{���f��NF:��?�^���y*~�+����ǚ?ɀ2�CR����������!!�u:�C_I��[�u^ע��@꩕�ׁ,w1"=�:#�wmI��{��!F�pV��	c��ң���r���"�R|�����O�� ��H�4�����=d}ϐ�H�9wL�y�������w�?�A�P
lH��xJ�6"Y|���ܸW^�W�naYm\�9��F��3_V�S��/�ѱ�ŗ��\u���jt�:C�e5:Y�����V-,��+x�m�W'(�`�Θ_؄3�+�`�Ǝ_؄3�b��M8�n��&�ML����^	�vs:Z���O�!�g����Ͽ�Uf��ɧ�N�XG*�] �򯵩�q�5�n��U~1�gO~�<,p��,R��k+�Zo�ɓK~@h���;�o�_=��I������O�c�O��7*����o�l8���[V�����=^Ŝ����+�?6������&$��+OJ�	�%O��	�$Gj�	�#Of��%R|���h[�D<$�z����@D#�������+a����W#~���g��Uz~ީ���%<n/��Z��f���֪���婓V�jc���S�K����[#^}2�����/�����x�_�'~�����ITj����S9 	�������|7��I���.i���;�7��Z��u����c\'&�z%�PP��0g�(&|�Kʯ���X��G�D�jbo>���q��x=Oe��I�o�5a3�*'�����������2W1��J�����Ӹ�c�]g�:fl�������^K�.��vZ�W��{�g����'5ݫk�>�V�}��7�?���!�T}~��x�H��$2�Q2_��%ſ��+8��q�����������q@�m��G켾������\��w�����Vx���I��\Dz�smr�\��$x�![��A���݃�)�X���������\���JJ-��y/��^<�O�iU��I}>�֜��R;t��7��k��uP㋜Ԇi�5��N�c�Wp���iH�����Z�]�����LS�7׏O�>|�i���ms�o��I��T��l��W�Z׮8"��G���������\���M�73��G��{��+���~z5����C��X��r�[#�l��&$<ߡ���_���z�曄*��ȁ\���H��֏M+�k��������J��MV��?�wk�刕�WV{�����L�_{H�i�o���PN��SԐs�M���ڿp�h,�}�_%��v���QJ}
V��Q��;��O�^�ј�z{�%~����1������_.�C_0��s}�!���3U{4U��>�𱁻t�+�?���-���J����].�k)�_�����h׿���<S�x~~��z��ۗ�ڲ;J��է�߶Ps����s�J΀���O��q�����9���(��o�)��:n^�$�9���y{��s��ゅS(���S�e���]��sv�aίd�2���-�-�'cT��`w΂L��6p���a�_9�e΂,�9t`�豠�v��ݢ���`��W�*�X���
9�v��`j��n)�g�A�>p�qZ��e�)����>0U�ea�`�[�>p�������}�M�������A�x�bxӼP�K�����*��7]7�o�[�>���}L�g�>��sua�`���z�p�η��~`h���6z��|_�ڳ��h?��h?0�s�k���a�;��F�c����6�I�og���t���q��0o����|��%7q|`��>(�~�|`��޲>=�,A9���qX��wJP�ţ~a�`��=��e��|�m���~��~���e�|��X<�y}�-��>Ȧ~�}pX���w�����b�N��A�ܟ����ׁ���������q���⃰���Y��ţ���X��}pX�O�>(�~���D^X�K��p}`�o����>(�y!rS��>(�y!-��2/�X�u�>�����]�xt!�X-~[H'V�:��s�*�"��������}�Q�@��O��[>�c�_��V�B��^�g�v�IdО0�o���?�
�ne�����9��xT�D|H�A�$�!�b2&��FR�2����#s
�ԭ̩��]��� �ne��M�1�dwT�,���l���9�(u+st`jς,=t`���u@v�EN�: �F�ëSR|`�B
�I�����9���T�p��W�p�SM��}@R�2����"���}`�F��j���}@N�e�B}`�[�> �[��}@R�2����ne�J�ʜ����߸H�V⤝���ne�I������9�$u+s��<������>H��z�t�x}@R�2����ne�I݊���h?����ne�J������9�$u+s�H�V�,����}@R�2g���Or�$u+r��,���$u+s|`�� �ne���e>�����H�V�,�,��>��'�}�R�"',��ү�J����~|
�> �[������9�$u+s�H�V�����ne�I��^�ԭȉ|���ne�H�V������9�$u+s�H�V�,��2~�,��$u+s�H�V�p�ԭ�I�$u+sx}`9/Ii�>����}@R�2����ne�I������9>��i��~�}@R�"��Q�V�����ne�H�V�p�ԭ��> �[���`��'�ԭ�������߷X�}�<~n���_��s�_?o u���z���:�PH��8s����G<Qm��������fO9����e�S�3N�}{g�I���ng�̯���� �ng�}7q��6�wG'��`K{
�H��8� u;�,��ԞX,Zt`�N�:�w�g�}�8����T��X���}��f�=u;�p觧3��^��8�����}��ng�}i��>0U�ua�`�[�>�O�eN������}��ng�=u;�p��� �ng�����߸����}��ng�=u;�p��	�q��g�>0�s�-��Av��u������q�����}��ng���D���>�S�3�H�N8��@O��8�z�v��>�S�3�B}`�?�z�v�Y��a��=�����q|@��G�u�����q|@���	� u;�,�.��T�p����}��ng�����E��'9� u;�,��ԯ�@�v��>@��'��@O��8�>�S�3�����q������=u;�p����z�v���z�v�������q������p�����q���팳���I>�x4q����@O��8�z�v��>�S�3��y��Y�,�-s����@O��8�z�v��>�S�3΂,�B^���_g�=u;�p����z�v�������p҉z�v��>�S�3�����;<�R�3�_>�~KG*[,��V��������g��3&���i�[�$�A�[��1��ne���<*s
�?$� s*�m1�G<Q�l$u+s����1��neN���Z�`�ԭ��: ��2�ۀ�ʜ�ڃm�R�2� �nEN]Ё�=uA��X�S�Ȯ���: �F�ëSR|`�B*�I�����9���T�;��ze�9Ք9�$u+s��.���>�T�Ǿ�Z0��ع�)��Y�~;v����9�$u+r�I������9����9����9�$u+s�H�V�p�ԭ��> �[��PX� c  9��^��~a�`�n��$u+s�H�V�p�ԭ�Y�M���}@R�2� �ne�I������9�$u+r�B}`�?����ne΂,��}@R�2g������9>0�����9���4pX���}@R�2ga�`��#��e����(u+s|`��� �ne��e?���> �[������9�$u+r�I��^�ԭ��> �[������9|���ne�H�V������9�$u+s�H�V�,��2~�,��$u+s�H�V�p�ԭ��> �[����yI^�L�����ne�I�����9�I�ʜX�c��~}p�ԭ��> �[������9�> �[��}@R�2����ne�����;<��R�2�/|}o�]�|�햿�V?S������r u{|�5����R�3N�=u;�D�գ3N��G�9�8�r�m����MO��8s���b����p��'��M����u R�3ց��8�p軣3΂L��6 ��� �ng��ڳ��E��);ׁ��<�p���W�*��>0T!e�>��3�����q������@�zg��Ts��>�S���>�w�f�K5Z��j��7�}����8���o��@O��8�z�v��>�S�3�H��8����y�=u;�p����@O��8�z�v��>�S�3�B}`����z�R�������@O��8�z�v�	�z�v�Y�M����}��ng����q�����}��ng�=u;�,����}��ng�X�?��@O��8>�x'r��g��~�@�v�Y8]�̧���r�P"�����q��y;rX��K�> ��g��~��@�v��>��Ǘ��=u;���@O��8�z�v��>�S�3�����}��n'���=u;���=u;���@O��8|�@O��8�z�v��>�S�3΂,�'/�����}��ng�=u;��z�v��>�S�3�L�%�B}`�o�����q�����}��ng�=u;�,��2/>����}��ng�=u;���@O��8�>�S�3�����q���팳�`��'����~n�E�U����^��|}��ԭ���:�RJ݊�?���ne����9��s�9�rȶ������ԭ�I�C�wdN����9�n@�2� �ne��M�0u�6 ��2g���`�ԭ��6@�[���S{t`�h�t`�NݹȮ���: �F�ëKR݂UHu�$} s�H�V�p��S��}@�^��}@N5e�I�����$s�,�hu���<�9E�9���o����ne�I������9�(u+s�S�> �[��}@R�2����nEN�> �[��}@R�2g�>��saa�`���z�t�x}@R�2����ne�I�ʜ��D���> �[��P�V�p�ԭ��> �[��}@R�2g�>0�����9>��"�I�ʜX��H�V�X�A�>@�[��p�`�O���x�&����9�˼��L��	� �ne΂L�� �nEN�>0��g^�ԭ���I������9�$u+sx}@R�2����ne�H�V������9�> �[�s�����9�$u+s�H�V�,��2~�X<zp�ԭ��> �[��}@R�2����ne�L�%e�>����}@R�2����ne�I������9>��e��~�}@R�2����ne�H�V�,�I�6��:����ne�I�ʜ�����}�R�2�?}���v������;m���n��|<���'��o��)�ne΁9$u+s
o�̩������	�_�D�C��d�×��ne����#s�ԭ̉��]�����9Xd7Q�p��Q��`S{�P�V�8l���9:0�gA��X��Ȯ���: �F�ëC�8>x�
i��>�9�$u+r<�9=�9���9��TS�p�ԭ��> �H2���T���Ղ�o�����˜����7�}@R�"'p�ԭ��> �[��}�R�2g�>����}@R�2����ne�I������9�$u+s��<��� .�,�-����ne�I������9�����H�V�`�ԭ��> �[��}@R�"'q�ԭ�Y�,�'q�ԭ�Y��e���H�V�,����}@R�2g��~�}�R�2g�t�2�f���B�> �[���^��ۙ���O��P�V�,��ү3�J����~|��I��^�ԭ�9�H�V�p�ԭ���I������9�> �[���H�V�����ne�? �[��}@R�2����nENY��e��X<Z�H�V�p�ԭ��> �[��}@R�2����P���I�����9����ne�I�ʜX慺�K���$u+s�H�V�����ne�H�V�p�ԭ��> �[��x:�ne�J�ʜ�|ݖ��s�����v���S��֧A�|�K����9�r@�vƉ���ng��ۣzt�����9��}[l�)�����b���3��O�8z�v�q�����q�@�v��:�wgn}wt�Y���=� u;�`��팳�S{t`��_ЁE;��@�U�q��U�ëS�|`�B<���>�q�����}����8�z�;�p觚N�>�S�3����4�p��Ѱ�Z��-p��3�B}`�[�>�S�3�����q���팃} R�N\�,�-r����@O��8�z�v��>�S�3�����q��<��� .�L׍�z�v�I�z�v��>�S�3��n�e�$�=u;�`����}��ng�=u;�p��g�>0��=u;��X�?��@O��8>�x's��g��~�} R�3���e>�����}��ng����e�>�L���H��8>���� �ng��~���=u;���@O��8�z�v��>�S�3����S������=u;���=u;���@O��8|�@O��8�z�v��>�S�3΂L�g���=u;�T�=u;�p����@O��8�>0��ԅ����*�����q�����}��ng�=u;�,��0/�}��~�w�=u;�p����z�v�������q�����}��ng����w<�'�ԭ��ۿ����VƆ�      �     x���Kn1D��]�C��K���{�#DY��\�����	��В�z}B4��-;��udu���9�\������������sd�V�y(��j�F�J�Mզ`7c�U�i&��ʈKEm�g�Rg��'�� �;�h����+��w�J�Mզ`7c�*y�#�!HK�~[��8O�'�� �;�h����+��w�J�Mզ`7��WM������X$�1g����zG��;�h���#����T~�H+7U��݌5f�v�h�O���O�AK�;��{G�A�w�Tߕ��;i��jS��qO�����*��I��B����v���_� ���_� ��R��A�RqS�)����=�X�/���I��%m5]#[=�:�"81�w��D{G M�]���#�V*n�6���Rۼ��S���Wt�j9���Apb`���#���@��R�yG �T�Tm
v3v]�fhwwY����z�a�;�� 81�w��D{G M�]���#�V*n�6��N�!�{xYA��KB4��1�����+U����#�Ǉhc`�6l�
�
�*�탴�di������nQ+Ey�j'�qBo/G�A4{G��!���#�*l*��wҮ��*}�u����f����C�xo��}�@�w��At��4���F���@���H�NN���H���ݘڒ�B����k�A�w��At��4���F���@���H�N֖���3�Z����nݶ��8���_� ��_� :QC�A�PaS�P�_� ����;ӽ�hJ[zjMF�sܣ�F�@�9i
v�@:QC;Gp#T�T Ti��?;I�\��k�H*�m�z�7���7���Bb:1���ܔ.ALG�NT'F-S�}�L.v$�XE�2�	�'��Z�5�v�`���9i����#C'������>V�i��֩�
�������b�9��7pL:�����L:�Z):2t�:1jq��ϭ�<�Y������ݙZ)�Xke��r��HS�xG���Rtd�Dub��00���������2��l      �   `  x��V�n�0]��𲕚;�6옦U���j���c��G���:3�.��Oȏ�q�	u�
݄�</P@~P.�p.�0�d�X01�N�e"D�'r0U�ʪ�ߜ$��ڒg�W�gܒ���ҒB�"\]<�B�R��-S����r��}�|6RȮ	�1��k�Q��/�P%��aB��+�)y�����x���	��Վţ4 ��9H|_�l�T������8�[X�̣�/�Y���T�&XI>1�^7����4݂=ʽ��'0 \^��u�W�O̧�йZ8#�K �ؤ=�$�$�l[`.�\8)_o>,�yT��[���+HK��g2$O���]<��E��u��]3�}�>hS�~�v`��ƻ�޿U�˒oJg��D	\'�$�1��i��A|@t�S;���B܄{��k�Ѱw��y����.0�rN�љj��:-���.����0�&[�q�9�^�G��:�#v�5���W�ǡǊ�\�2̷~��x6r���5��w�LE0���c���ז�;Y��vm����2��#�.F��+����O��������ȋ.>4O�-/n��兛y���n�Ձ�Ҭ�9�Ji� u\�e��o�t�|PV��E���0���^"34����ޏމ��"=1~~8����CmZ�-i��t��i(]*�[8�J3��P�!������K�QS�����P���Gox|ɘY���ͧu+��R�H�@^���˞�ft�3BYİ&z��c'�'jw�I��IȢ�`yZ% �B�R����l6��� J(�K���9���{_�g�&�%����v���"W����CXKW$~�8b�m_�_��^�W�Q�V6���6����1�<���      �   M   x���� C�3����.��ڹ|��0<���M��
��;���u@48~�s6+�dZ3�zO��5N|�נ�      �   (   x�34�0�24�4�22�47�22�Xr��s��qqq _�!      �   8   x���  C�3�P�����
���&�J�[�HIMR�=�OGk�(���. >6	|      �   6   x�%ʱ 0�a��2��@�\�z�i�4%5��E�|O�c�9�ǳ \v�
A      �      x������ � �      �      x��}Mo�V�޺�+�H�Դ�d}*��jy�A�[h��$�UE��/��!���z��ox����Av�'�%9_������Y���-yy��<�{1��]���77��wU�����
�����zY�=�Q6�����jp��Vݦ���O�ݶ^�rY?������������2�v���O8Ԥ����]������v���J��T���^_����������� ��3̧�|��Fȧi6;)f'Yt,���=�V�wI������'��xYu���@/��U����˾�������J�eS�J���;��b1&z&�<�&'����%y��wVn�p?ڈ�?��%<���jxP�-y��?�>���4ջ�+a�=��l�wUS}j{��lP�s{V��d���f��d�&z�U=�,�WEs)�)���M�,���k�pe�����vO���ʍaJ���dR��d�'������n�i��mZ�'���-l:,X�u�)a/��EZ�-k�⼇,ӊ�f��R/��[˓n�,��l6�r�"iV�L��lKfs��U�)�5<��s���߶>�Ưz�^mw@4�W�9P�n�
N�w]��~��\��9Na��|0s�>?���
o����d��Gj��ۦ\��a%���Smһ�{��]�e�������_�_\��U��������aV������g}���'�ļn�U�g'$�SzڴLǃb��,?)��X�N��o����\�!bE�|�?��8��e�;HpK�T�����w��5ۺ��|N~�e|u�:�_.�����ß�=Ƴ,��?�S ��X2��y��������?�@R����m�&�I�I�U|��7�u Uzex�����v���%�ݮ�{O|�k�?Q�Mܨ���~�цn��+�9����z�����������$?Eǒ�Ac	<�?vp;�Ց�p�v0ٳ�i�pB�0���w�^f	���>mi�$Ko*>to��>�������'ٌ%E6X�ܶ�~p�.o��.5�M��E>
��	�=n˲ޭ����t��H�4��t���?$�P��X2լ�m�,7u�VK�6�B`GQ�l���p���Ve �_ta�>�q�4�y��Kc�B3��䲰��պ��FX�ni�U�~[n��WL��l0�>;� ���e�"W3��B�Ik�����>��r���d	֣Ge�l� �K��F���-�B��up7��������NK���/�;�Ku�T�#� ���'��$�[ ����t`f��g����^a�6�����b����%�|~W�<Z�t�G�`�t0I���y8I�ɪ�$כ�Zq���n��l
�@i ���7�ȼy�͎@*a^F���� �Nr_��XR-��뚨�I�]Z�r
�Y��.ywWo����n`Ն�=��>�_O�_���D�vk����b ���I搥K����עi���t�l��7( ���� ����	&�Ѱ�[ ������
��Vo `�e�H|].����\�w��^b����<{z�Occ�ܰ�C��+���b���xv2�i�ƒ�a]�����Z�3a��������O�-��d:6D�[�yz]⢡^�Ձ r��}�����}&D�"����R����%�8GxY���$[�ƒYn��0�ovph�~�σe�����AQxKf�^56���l�3���F�Vg����WJ��L�~������WW������@ �A΍ �@�����Lߟ������{��]�����{?L�.�{}����w������w��������ү��\�7�`��s�0 Ӛ����X�y����lv= n�O~A�K5E�4��i<N�3u #�V $�G��|,V�B٪ \خme����wt����@w��No��"ہe���h�j�.��8O��К�q���_`�Ġ��7,���Uj��W����j�[�r�����Cv֒�{�����O;\?$�W�뾅_����a(&K>�4�10\����y�ak( P������.©ߢ�N/͸b��u�,�x�� �ED��۲#� 7H�V��@.�\� �+�-��F+I��X�Q���7bL;�2:��G�2�̌��#���<M�8O>�<����Y!#���=��J:C���  u�9�7>�Zu � �''*o��]Skd�&�]Q�f`�� .����d�d�A�7e����C`���U���f�U�"HD��\���-(�U�+�=j�k8'w��!�����ɠ8D0~S�����}���/xX��uـ�����������4-���z	��A�?�K\�6.�M� �3�1Ӊ�N�Y(<i,)���v�v�ؐ�i���z�G�<��%DzV�o�$U�Z��C�/��n���z�}h���"���N�Ydl�Lƶ׳�֓��:a!y�r���@ �b8t�(�f,k.H^(�=��g���?p�����=���ʆ�q��*=ە��M�~qˀj6̦���9�U3�L�Ხ�[x�;E�!�ωpzC���^�㋯Q/ي�����ťpA��u�E1XDl �G>ऱd���U{�����^��!�� +�~�'.��]�*UW���{��ʾ)W�
�\�����n��x��(�A��	)�ѱ�� '��e^�����F������B�n�<��L���V�� xgh�-N�Ql,��Q���52-��51������ �z���%�?Z[ S�V��l������X2��m�-��MuK��#��9Шu�=��V?,"�
u�<6��Bz��y�+-��{��N�B�d,%��k�������v��5���(0L|���?a2�LgN��j��>q�`���̦#u>z��T��Y�� H�Ԍ;��� !Vi1�` ��o��c�M�v��oaM�*����~�F��F��}����Y(����?�(���	�b�+K
�_�7;�l��Om�_@j0�1�?� �I������ո��s��A�f,�]�n�������^�Ҥ��-�̀#6L�Mq�u4�yK,�e�<��E�k`(ן����7���l���L+[�N�+����k�N�uՋY��<Ͽ�@��$:��zN o�����	����&�ot2��4�%�"�&�x�I��
EzL�����1�&�CРߠެwյ��- ��"$`Փ�� �_�x��,����D�2���=�ݲ�D��s���FMS���vl`=�}s�%������~\�h�/m&��c�+8x�("&��Mǒq����(�GLc{x*:�����x^z#����<F�7��d�9���w�,�Wu���� �� I�[<r���!9��QD�����y �o7��8�� ��PHs�����6���[�p���EIK��X�o��.Ѣ���w�~�( ��)ź���L��꓈�˫��yz~u���7��d�3�Nշ���#!��]}j�L��עF���Y�͋�<�e�Uǒ���5��������+P�NQ�2��$}N?(Z/�gd��lɪƪec�6Bv����::�kWi�Yid��$�9�ߌ%�>��������}�����S&��X0��0 ܊�ui��5j���b�N'Z�2���.y�J12��%s'pkw���6�w�Dc�����������띹�c����T8 ��ݸ���u���[`�C��z�Q*���ưY��az��}�uN�v1�*�a|���g\2�S�Ê��G��N�n�n�!�d�-���ʭk;4O��A�f z�q����!K
-���U�A�_4L�4�����`:�F/��)�%p��z��~�6�~!O���h�*~&��E���|�ƒ������X�-y</BW���j��$3�;�V�� [�OXA
�C��vK�/�Nl��Ӷ�E�}��ȍc�f�o�=<ESx�~�-��&�,~���1h���%�� �B:�a;#,�h�d��Nyfl5cP.�k��bCvh-dmv����`���ü'!ۼj�[��k�
�=%B���N����vb���j,����fW5��Y�D    �kHF�����u�:8W�j�@3��/6��L"S� f��%�92&�,��loH�"q^��8BGu>qb)�X��L+��`���p�!xBÊ%E� ��I�R������;�O� !А�>���G���oΒ�Fn{�dx]}T�s�B�%J}����F�;�Ȕ�nP=��k}�x��,��j�!"0ra�T�u(��׉��`�
��% �5ř�iQ�'"��@_E���Q�X2�D< �����r|���x�$F1��!��p,k*7����	��5�Zu� o�wq�*�};���X>�cn�����X2����aX9ҍ^E� ��Zepĳb6"y���L�%3M�:�RӼ�w)���~Q�Y`YE����}C��%�ܵy2�3�-���0�U~>���,b�/"4Fc�DC�gY^H��'�\�[������ Ӈ��fW�~��"�a�G�h��d�+�4��L��
�
�j搬�K N��lp�J兴�'�^�/�.�=�����ɳ ̀O�Hz�[�e,O�Y���َ��P�+�F���u�51�{�;$i,�hs��6X9+�^���t���a��}���6�9�LO�b�o���X26�De��L�G��ha0���ƨ�M���#�*�-�۫����aىx��%����SiOJ!g`�#6�U�D��-���n��s�X2ռ�uu�o���%A��!���{�wd���e1k��$��4�L�)40H�OE2����_th	N`˜{��j,)�1A�"k��/�n�IA�hq��R�f��d6���0#;�!is��\��_v�������^�؅��;�1�ﱯ�R`D�	�ԡ~nD�����,�2�05n��]>��K����BX�s�r5D1�-�mnD
���&q��7�]Sn��;���r0�U��7F���o)Y;-@� �֠<�6�hSM��n*��h�⃥\M��&C3ieb��8oŜ�B�E����sd'psGE5oݢ���1>�|� N�w=�ܙ ߂�ťN�@���A�7j����u����+����XF_qb����eE���8�Ҙ��R�̗�5��Ë���f�0_^qe/�ߪ�"�J���ޠ��K�*s Zzі�^f�ݵ=iQ&����Š�!8!�3��h��|��(��3���$3& e2��q-�#��Ex#n9�T���_�ƣ���UZ��Q�p��Y�h���J��
"j��I�H�[��3�V�v?���ɕI�Z�Deҩ؏�������K�׷:��=۴)�/rO?�fWuDb\���g7;u�$i��xh\�7;qD�Bb�1*�) G�;��A�v;����NX4�cI6{:��ڷ�	nāLGd���M���������E������#8i���t�����T{��3��o{-fd��ƒ��r9�`%�"��c��ǥ֢U��J�k��^����^ŠV�%�[�Ӭ�<|�Y�BD�U��c��d@�J���	�����z9p��po%6��V��r,��W�s����$���`��q ʈ�;)��g�n���Xe��N��6r���PH�E��\�L�X�gƧG�Y���q����n/��n)��#!�`��yG��\���CB�Wju�PS G𡘏c� kKv~�M�q�Yü�58+up�}�`َl2\������2*@V�����6�>ax�����X�����:1�S	���r̫�a8��Ǒ�����&�E"��Y����Ցn����o�5�eh~�h����Os2�t���i,��cy`���v�>!3�7�mx�i�5��a�we���O?�J�mwM��, `��V���ޒ����)I���1���My������݀��{J䶹#�Sv�p`k�@{J�N�\b��{~��zMW�Ɵ�{
��`	�V��� aN<�E5�O����Z0lE�-�BC�8 b�[����S�Lg&���9�*�C����5�pP.�kq`�M^B̯�p9�-���O��w���H�z����m��\���*�Ē9(���g},N.,�L��JWL�/�DC�S_V&)ַ[V�z�qxI���J�'��Nk�7%�-u�	W�^Q�����6��_� �E�Q?��v����0-�7�\�-�p�gS��26���%)��/"�,�&�=���2]]����%��r������$!)�r��V"�����n�R���-P�c�IE+���z����d��i�Nj�����Cq����� z�TʂB���X�YFP�S\��]n9��8�S� JІVA�ZY�%?hZ黷���y J��=��~\r� r4F|^�!a�KE.��6�Xo�{<�9��ֽe~��M{􁩛�Zc^s��j�aRR~�KY��\�t�X�ݶv�R7;�����4�$���ٜ\���.NFyl,YXFJ?��ٻ�G��[j<DzߪXxW�݋Yk	�@��_ʭ��	�.f>?�%���&	�yY����%����2j���q�����z�����'̹�D0�(�p��r�Q����[c����I�p\�Yҹ"ٵ@)��8���'���BSTxi[�L�Ԋc_)��_� SuX'�r���F�U�*b\���v�
n��m��x���>�4��%W��mȰ�����G>m �� ?.��z��e�z���Q�]�ЂT�,E���n�Kd*��,��+�`�K�%AGT��4��\~�a�����X6M�������E�Q�<�F̤����)���H+i�^�^��&��ǒ��y'^�K�X��_e��&e*��˚b����W"'POQ�0�2 2ݎ`f�gG�^��5�|a��qF{W7�s�:�z����\O�����B�1� �1$�k1��S�m�RLg���{�8�+ѥl��`��.�%���sl��3$��}�ahv��">z�v��ʿ�_�u���&��NM���RX������}�RܓM"�0�<V��D"Gߘ�o��;I\e9��+%�Oo�5+_���0���{1kj=�R*Vx�J�;�=��}ۥn��[���լ,�V�J䩐z-IId��ґCF�T�C�|㦾ƌV^��eL�4e�!�~��E����"+Xj�}ek�t;��v�>�C6n=��,�k� O*��^���~��8��,�L~�[Ĥ�Z�)��8�)CEyӎ8P�ʕ�Xyc�}�V`�͢����me�E;L�R���ؔ;>��]Y�Ќ>�Z�T�=,�Lȑ�̃�M*�t����Fٚ%V_��_��գlC���|VlH��{oU�R>lќ0�y{�ܧK0����Ӹ�=S��x�@��Ҕ0�ѐ�	�q�]�6��ܔ�r���3)����b`������;1y+Kٹb��,�1,�F:��I9�+44}��y9���'+�^�6V���d����\1t��й��_�x�o���nڔ�`����b1n��C[A��S�c�%�B�6�'��k��7��>Z���h�\لˏRR2=K�񹀟�����}�y���^�22��$3�I�MP�qy���R���0������ˌ�KE�2yA*	�؟H�XQhs��vM㖋9Y��-��C��لh^c3��!7bQ�F��S�tt�pR����L��`��<�Wǁ���U�y��[ߙ��G�!8Ru��O�p��b���UewNC�.;�6�+ջS��;���% ]����,�g�h��,��^WTD�41Ft�2����Ȝ��V
Wh��`M�S"@W.�$T�W�u�Y�RAfG����v�ߠ��[L��ú�l3�Ș�{`NKUp�����e�]1���g��
��(�]6Un�ju].�U�hX'7�"`6*��������0T|��49̝l�K:�U�}�ϳ���s�ls�}�&c	��{�ElQ>��껍�-��}�stl��6�%�m�#�b䁛�
 �fY����(�os�%�	�С��'��jc�
�r�J��*L7;�&�Tϸ݅X����&��,���f$Nn�IXy4����g��5    ��� =�I����1]*��'z�Q�����r�S��Z��d 
V���t�NT���r:k�%1H���U(q��Ọ8�_,�LX!��%�>N1���N�c���h���Z�+H�������������l�g�*}C��/o~�腏��j�/���_*�#�c�m�x���1��`�Y۱h��⡲=��)����W�e+��.�V���m��yB�O�Q����#D��2�`ŧ�ly
�P��0p�^��2�#F/U�r�Z�D��4cB�$]��Bi��Ge�2��(��R����:�}�<���[TM�v��½F�d�R�S�X�ֶc�1�����.���0��&�Bk�­�4A�*�Ay ���G���Ě=BT�Xq��=�6딩��|�S��l���k���Y$����'�-���) z{k��Qr#��ړE����H�p@�h�aS�Z��(�Z�6��?����E��<�̒oK�qE��h���Y`���'E#��ڒ륚8`��e{�3"��#;�+�-��Ό�q$�@��#b���b�Ո�p��ގ�Z�k��7�bD�a������ �P���f��8`I�F@�_���k50�<)��(��Q�������������p#�^b��:�����ǣ��7��X��^�a�N���{n����s"�i�I�^[M*�┨��zK�9_1SD���&e!�fK�L�Gޜ��s���j����#^�)e�h9MF?��/16�k����A����#�c����I���{#���*�1��]�7��+�Q��������t|�s�>Ag۔��"ð��N�����쳉��T��]̉f��E�ն>��g׊�|��-ƾ*�*��ȝ�a�j'}s1���	��v�ƒ̔[�ɝ��L'-m���r���aX/������U�*����t�3&;.����R�V��� �k}o�AG1�a�I�s��R�X������.����:������I��:�P��	l��cZ7�����ƕ�A˘��Uz!��\�;_wܞF�\{�
�g_��mHņW�jm���6�+1=�wm��%cFK���yɎ�U0>�ZX�����	����B�G����9xׇ�W�z5�/LAҖEc��c�*nT�y��	�;���a鱨nA(]z�z�3."��\��y&�&Z����P!� �;N�c	Z�u �vZ�c�mϡwr���@U�+j��/9[����6w�0P�ٱ�{}E��҉-�2�D_����c�u��m$F:0h��T�s��Ɇ	�y^�����I�p�X���
?͸���D��_������Ψ������Ҫ���,z�� �[��bl���o��G4RH"�@:,������|��;�-r�Xك�t���;���S��b�*#l�M����dFѵb-��{M�,L̻�8ay'��J�V�p��F������Z鞕���w���e	�����
�&3|�eBx#���1�g�w�����p��ⓨN�y�@�����%�`Q�q@�A�X%��+�B�fDB�a�0�%�郡cxL�?,����/�6U��lu��:�/~sj.�P�����qA&Z��	{��+)s�x�ͤ����X���F��E�
*�gʃ���*4fzu/A���`�31�銓{0�XYvҤ���P�6Z�Mƥcy���q-,��ay�<wgn�\�>{:�Wa�Ue����m�����y��T�k�����7W�T`؛�a�U贲߃��D�r���iz��颪t&�߆��y�[uL�?r��Q��*��C�mG~{���X�J��%a<���B�↥!{ ��(����?U�0!!4�%��+�q�x�Ux����	�e#��nT��^(`{�����h]��o�M�&���ͺ5zTv'":Q�)����F�T����-�����(�.E9|�]��_��h���QY&�{n���R�T}d��%%��0!��=�`=�/}���*1�E��(*��DS&'�o֦��L�v�52,ǰ"U=�`��6)���G������ʎG�V*ErKH���,�,8T���!�<�6ͩP/oJ+���T��n���vwfl�M4{��}�oٯ��O;8�j�A͢�4TM9,�Lӗ 5���s�r�ů���y{ޮ�L7���9�6Z�|{g`��ŷ�������K��M�ʘӌ����=�%�8D,M��`���%�����T��,�9�Ŕ�bj���?lkWj�d��1�7bd��T༅�w?�*'.�nTU�&kT�Ɓ�*� �["��l�:���$��Z�@���_z�Ct|���~l���]d�:&3b�~��tY:?v7_
�)�5�;�+��*���:_��E�,ah�Um-�̛��v�6�Us�.�X��fg@�pϱg�v�{E�pE���n������ʃZr:�N�G��K�|"ad�*5W��)R	l��l%�",� r=��K+L|�ƒ̔I7}���[l��"����,l�MX����հ$�#,}᜝~x8��>��s��ZgY�7*nn�Q�[U!q�d���e�h��'�*�8D��$|1������%� ����ܡX6Wr�^�WZ�2ۥ�[�"�hp]�ak�p��F}�ΩG+�W��֠��%���z_!�0��)��c�6�$9��'���@���߷J����9:[�<I�R��ٱ����J�zܣ�mѩ�|Aչ2-������U��*b��ڤ��C)f�䱂f�I1��%���-��K���5;,l6�5f̪�����Ϫ�I�^e)��b��y���b9L������xDS �੪S�>)l`��HR�m�a$��n�ā�����>�(�̩�ƒ�t�W����o��V�߁�nN�ޚ[|�����aZ*�I-�xdi���t�� L���08XH�w��S���S�������:��A^R�ȕ����HY�#�qVH�J9f�3ׅ��V� v7��!$3$�E ���]�����"��� SOUpR?Z���$a��؛TM�/�I�����%��s�,'���Fk{�E9:C���`��>���N:��z�����^��%xmπ��V��R#�8�g�z��O�ZdwO�]R���$���$or_�Y�F�B��hnK����:��x{F��:��˰���<���^�0�>�&/~Pj�~���%أZw����oA� m)���Zxcq���# R�Hp�s}��-��ef4v�?�)'ǀ�aiO�j(䚣�M��OY��s`ӟ���S�dQ%ybPk,Am!ʡ�+V��_�{o���ݑ����/	H?b���2 V��?�Z�Q�n�n'�Վ(qP�ٱ4���Q�,��x;�MCao�h�s�7NtI<�VX�mi.�T4�Q�����X���L��/Ee�������Ԝ��3���vVm�'�����nk�Ͱ1g�P�p���1X��O��@W���F�X+��EL��l����/-��:�*��� ~ �B���Ʋ��ra�1j mr��%���o��m����qĪ������۝����۵�:wX� 4�k���Ң2�P��f�-Ģ3{?Gʤ[�ɤK�T�?��<�p6�N�=[U����oJ�2a@y��e�*% ����r[8��f��cXD6��O��0pIV���1���1<���x��{�V3��:��}���&U�}��FMn����T�^b$(�oT����I�N�����2��)�,����&���aăkhd%w��x�HSGc�t�-�e��D,�O�é��R)Z��ɖ jT���8�
��ٰ�P��*���S����TR��R-��J����>�*O��;�&ik�$��� )������^3�� V:������ε^ٕ�g�#?�	��o" c u�U1���w�ڽpUD��#U�,5�<�Z�Fu󮱴W/%Ċ`ұ���8ƞ���+NF���X��=��WV��b��V;�k�*�Oc[%�Hzt�%�/C�eХo��4�s��آ�D��I5���2�;ByQ��`ݦ�j��	���$7)�l&    ۿ�f�K���״(�N��X�B<	?T���L���M����{V.���l��Ï�KT1��~& W�Y��In7��@p�]<M�v��u Y��m����]�~\��-?�����T0�}'� ylAB��{Mc���8�|9�%��������r���K�+��ڗY�z�S��%iu�=���q��2�,��rA��zf��)@�'�D����	�L�mr, c�Yi3���3�>Rej:��C�؎��m�U�+�:=�(xHR��q7�-�ך�!���S5!�f���x�p���q��C���������psD�����i����4�����v;8���w�O�?��/.���ڇ�}ˊ(/}�T)H�tK�QSO�ѥ�tJn�<D���6箎$}Qg�A��q>n�Ld��X�MF^�->��>��c�˕ah���n�ow��.q��5�{K,r�Z�!J�6��!,�%5�4 ět0Y�$��N�	cӕv8͋��T�^�|�d-���m��5��7�����8�cx����S�u�oX���%���0z�Wa��s���D+>v9{Ϙ��}qJ���1����i[�O�����X,��e�0l*:��4���誨v�è-e��CW��h+�\m��P��&��
d�����[
�ZE���y|_�����-�	X#�/u���܀�(r��kt쐃S�p�*	uW��$2�)=fG�;�ʕ�Sk��5K<g����4V7.ǆ
�Q|�Ǒ5��*��H�72�����Ϗ�����3Q�5jW˺[�i�`�[	�օ��^�ێa�X���r��aRR�D���I�Jcԋ�Y�ǉ9Wm�����`=�W� ��Jj�a��F��PGm����� '���A�x��2��c�N�~���%�)�Ad�E����ܠ���=�8Z�9�ݲ'C+�hC�p�m��

3>W�Y�x �gQ0^؝/��[@�[�A�<��UJ.lR��$��K�q�.���V�Es���!���맃���- Dlkt;�	�%�ɿ8sh�_��9U=r� 9H�f2xd��V��-�����Ze]�h�Z�Id靧���k�	�*�k��b�S1��Pb�;GD#6G�o�h�WT�M:��%���	[4��(wr2
�u�X�����)�r�J�_=��~�Ʃ��8��e1�^2irCN�E;N�� �X])�Nq������Zvf�5��Vf	Y���N�:��0����쵻�AVHj}�����Ŀ���/�sݮ��O?�?���K��o�ۗt{~D��O�_���*�u��IG��Y��#��u�R��K^�?���h�&9��+�J���:��C�&��xC9)��[i��v�5�,�T|� e|?�V!�<O܁�|��9�ε���1#Vά�\wUSZV@�g��b�#���'�A1�<_H�aC�qK�j����o�׃&�kW�{.�V�+���ץ�w�P���fI9�ˏ%��������p�.����b�x�5.WcIn� ^�NY: _�dRX�4���}(�D���W�m��`bNjUs�#IJ���Ѝ�8�ϻλ��e�j��T*o�z[�a���\�Tq��t[j���g���J紩�V�Ŭ���f�NT�~eG��~�b
�J��OS�ۊK���WZT�NN� ����O�1���B���"�l���Ci���Z���Z�T\�|J+[��hîj��:�3�������Yg_Q1+�g�3���K�TcInG]x�Q�.�.ŝt)���1�8>�ʑ�-h�	����I�����U��@��1��:�W�0��j��n��&˭���Ĥ���Cj�#"�Չd�N�s�� pǏ�~+��)l�TNo�X�/!i,�j|ꔑ^���2M�%[�d���������p~ٞ��QR�%Ta��>DV�}�������V�E���#�� xQUdF�g��G[uX%4D��v�
��i�y
x��Uw� 9�L�9�$��p,�oht E9���3IN�P�����A&���cv\�W��I�)7��o�a�,���b�������U�0�����[JL�Bm#6USN1�	�Lc� ;ɢc	^��n����s7ЮI������^3�QO�B���d"���,�Y,<?/_�H���_���.�Ew�u긳��s�K��Ic9������k�.@K���ۆC9�u��F��[ )$��f��k���a&z\x����v�-5��+�ެ���D�[�8�v�v*�rq����]�;��]D2i@l��0�u���v �k�T8��� r,X ۿK=�x�I��UJ �cE�Т;;�D�`I�9��,h��d�q١� �u�������nl�7H�J�U���p�3��k�� uz͹R�m`��4n��i4�dlp�-g�X5�/���GCغ,Q���rD5�bE�rS�6�q]�^��rEK�0��i����Uñ$�k����
��0�D/t�@��V�z;W\�H݄6ת�j�J9���
��?�^G�s���"�c�bI{�"��E
Gc@N�ט:���8���yu����HL�<�F�
��F�Gj�ŽM�����c��,ZF'"�l��UJ�R�j�Rc��ފ=[��]���A/F1����2j�a�����=�`l�jr�������hGۇ�]�ު� ����q!�w*{��ʭ+�0�(�*�Ӻ �[���
b2h�kn��B�o���#�^�݌e!۝I�������4�(:�BD�E4q�J��q|�&�1�1X%-��Z+i3��t�GW����˥��q/�ہ�z^�s�ڰ�)a%�lV��Z�ҝ���F4|Ь��v�ȃETa��0�b�t��;q9vBfU� �w��cu�� ;*�#q����wiGl aI��Jڱ�Q��6!7����:��[�8��(��u��E� Bc��Ehl=ץ_|z�NG֫�x��{RcY2kc�έ��-AᘵO��~��h�q�L�k�L�:�>�vlS�*~��ꆤ�dEnb%�C�j,��a��H�<� ���:�$a��i#��h��b�x�ų����N�%D���a,HU����QmKaU9)Y�ʹ��@�2�a,��ۛd]F눲 ���шR-�~��1��"�T,�<����[�U��⪿
��H�@?�>��k<0-�����o\��Jk���0KB�3��'k��Lj,�v]����u���N�����2*�-�{n��II���ڡ�a-����Fw��J&�j�ju5�m�,Z]���(�5�</L,�:"�nY�H-S�7ց�Zw�Å�+Sʡ5,N�O��HX/�7e����(�_D�܂���/I4�A�Ͱ���>���JײY٥T��*Z�ue�.�A1�μ~��V�l=�����d���h,�M�`H�-�m�g�%9�B���*�͹�Ҿk����B�q�Wb�IA��{K4"(��>L�=U�LK���<{3��fZc	^L/���YL�%�H_n\<bw�o�S��E�?C;ij����^f��X��b����j3[�����x���uZ�ϖ��hjT�N�˧�����[�,&	��T`g��4r� '�1� �,��=���M�V:�/X�Wa�EG�4}B�:�WĬe��c?E��`E���`�?��aT|6��X�I]]�)�����U;6��R�[{�%���`��x⽈�wX� ��`��Y��)~Ԥ_X�}u�)L7n����e%N�g�"�?���&�Kʨ�����\Z� �&F�t���j� $1�0�"G/�gj	8��݆���y[��n���U���H#�K{�/�G.o��+���z�
���� #�(��B�����k
M�������UK� �uLi�7�)w
�$'��۴����y��m��R�����8L�n���v�p�`�b�ȉ]F�K����}�D��#
5�װ����^�`-���>����KC�T7�����3��_`�tw�������?71��!#Pⶈ�۠A���Jk����ݷ���FGSX��:"��E�?��]��(�U+ə����\w���[�d�#u�в&�������X�l���c�q��� e  ���3��a2�K��O�őR Kв����
6Uj�s�^[^�vi��Q��VƉ-���1X��������c��Vx����US�#��R�qK�Z3u�ʽ���=���vZO�~6~���W+r��Õ��~4�%xm(�DP�o�:p�����{ʥE�vb	���:������YB��$�ؗT��N2�5k�u���2���G�d�h����9�.<-��:7 �!<V�J#�Ip�'Tm|+��*��B��o�[���]N��ɶ�����Z=&���#a�9��gZV�Fl�@�<��f#p����m�>?6s`�ׅc$t�;lQ�k#!����G�L���V�X�WI��T�]�      �   C  x����j[W���Oq��`�}���H���8"6�B&�jN���K��;�t�AFy�X���$���#�/���}m絡�9��JD�L���EǠbj��>/ۙ_���h_�Vyݧ���������/}���}Y�����ov���z�o��_.�����ܭ���a�n>\7{�Q�l�E'D�XM�p*���b�)JX�D�\LPJGퟢ��T�Nw�IE�D�D$Q����9�Y&Y,��t���G��T(]'TM)%��D1Df_��LQB�����Fyf�*ɕ�����k#�f�H[4�tV[p��T"g���qy���7y��`�r�*��j��ϗ�C{��b9_��>�][qB����ȑd����8�-�.������83ƕ�K�QY�F<k�@�"��TH�&+SN�����Fx�z�c���f���`�s��{�8����^8����VT&�DF��`�3��xqf�+�T�hM�L$EjQ�A���!%po͘�e��5���)��}���q/�щ(#t|#}�N0>�	��4=r�L���w-��5mDKR��_J�bN�#�-L�B���.�<��hN���6�NN+̪S����YJ�sƊ�%5F[T9�A�1��g��j��Wh&����#j�&8�19� �﷏�O?>��?�e����xS&Fq�g�
La ��ԇ�KVaC-���gc4f�'��<ϭ%A�0��X�!����pw��8y�^��b����s#,+^<�cߵ������#��.�d0D`���%���}~��$���~����1�^��o���&�]`��X��.�
���(��P
%8 Ca>s,��.�Y$�
�u
��j��f�w(-^���y���B()|m�b@{����_	��"�������6"��i����3��*U��V%�#_��1U�ᠩi�4�eĩa�)�H�N��eP.7��ݟWӛ�M��ݫI;��4����������t��u{ys�~r������q����c@�F5�!�jJ7vU�px<�fwA�מ�@ptYɢ�Y�涿[��%�lV�8F��tzsӒ��kT�i�װHB�ޒnPjk�����ً�>
d��;��᮹��ŉ�mո���΄I��\7�$�u��S�ciޭ�'�vۺ���l;�g��1��N������f��^�s�(�L���v �X�A2 $�U-����LCbZ53�9�{]1n���v0^QA�(:��P Vr<Id�x���:�|�}I����i��4�.���}�lk�"
�=�L'�gU��Y��`a�#@j���#h6�?G��p����u�qZ�.>�|qq�/����      �      x���Ko�H�&���Du�+*i��u�T(#���Z�L��1�F.��]�J�jfy��r3@-�A�j��'�K�{�f$]rI�u/:�����=���w����aa%�8�y���8c4Jh�8������j�7��ؔ��,�J.�k�Z�K����M�jV�R�r�T���@^�ե\������������]]�+q��ʙ(o�vi>�����~��ܯ�G�fY�Ūi��-��e%�nG%odi�7����}��C.��f%��F�ٱX��oy^��z�]�
����4�lv�ru�Q�XI�{�����yK?���A?Y��+�?�V!!aD����D�{I@�Q��|���%y*BZ�"�I]�0�y�QV��k����q�x(�<y��M$!��52�p����_ͺ1��OO/.T7N�.��»��F^���g3�8`*�_8��Q,d�\50��z-���٫������n+~�x���X���9����}8k%q����9�QA_�
>�"Z�媹�jQ��W^?$�f�6XÇ-4��o���u)qy�X������ɲ_���Y7�����Ͷˆ�^�pٰ��16\6��\6D��k�㞗",☆�ʔ����eC�X��F�MZ�PHY���yYE�
��	L���i��e{k�	[p�.���)Ep��7�l'`��k��N�� W���_͵��j��z��[��O�Cf�������hEG���(d$�0Z�і�`p�\�e<��ǲ0��D�q�ꈤ��xY]V�`.�if%�V����:��J��~�`~�n�kXTE�^���j�v/�ӕh��b�.$l�ٻ��Rk6|S6fe����> �gI�E�/9B���+�cn^�\p�aVGUX�I��9��$S�3e��Ρ��E-I��LJ�ʪLb�?�i����]���č��e{u'�n�I&W���+��g���;���z��%�Jx@3Df�$�#��0���d�^t��S����`�e\�x/¼�!�Ie4ͪ*ejts����s����aAs楐U������Ay�Q�^��\�0��������+xg-/���t$��n*yմ���E���A)�&s����j����Y'����_���̳`����f4��so�ң8:�t�S��l�:�&���10��\�4��
B�Et-@&�us�J'T���˼d"%Ws	c��h�̒�����t��;���,F*�0�+�M`(�p��2���5�����g��^ÿ�`Wg�Z�nA�)1q#����,�^V�?D�R�ѩ3,��#�w5�:��|�:�2V�U���8L7gU���WRdj%<z�Qg�����j�W�(����Z�P��Us٠����֨��e�J����q�1��"��G	=��K��!N-�L͡Ǥ���Ȋ��X�p�p=,�.�D	�2�a{&LhQGy.XR�a9�~/6�J��-�ՏXVlc��D��91z�@�Z�U'J%��Z)��n�$ꭏ�?���x8##:>�y���\��A&p2�9ț����*Q� ��X��� \���g.�HmN,A���ev������
�P���٦� �z#��׫�*��wJ�^����C`�l�=[|z��q�@{ģ��J=�찣Vmo�����j�G�r����M��*V߿�6�w���೸K�n˪���9��bc�����m�;�)�2H�~�aWB��L�u[��?��i��)4���p�)CLݝ�2���>e���ZY�5�ʈ2D�;���H��s�.��.ai��ݩ�������Q,�^���,���>�x�fq�h�������h6�6�j �p:����p�x2����l�㘋������&��*O˂R^��.,��HT�+�0�4Z��ACBI6ܴ�ӕ_�͏_��� +����ȁ�ӻ6r�(g��K��R����M �(Y��r���ە4��ｫ��<XYW���=.�82�����ғ�#��PlG =c�������0cER��d`)傤���	,�
�*�]�����:ˊ2�"�������68k`6��6x�V���o�Z�@�.p]R����U�6#v�j7�3! x��J*���aD!��g��F������W�}�;Q$�,IE�f,��"�qNM����܈�}��X=���7��N����&�����Z�����KeT,Ji�}������v�Q@gæ�:J��e`�L^���QZ
V%(	 ��P�B��Y.����Wx{_ju��Kh>#,*7�����c�N^�w�8)@ၽf)�NY\V<�R��Q
1��*Ⱥ�����엛�|Ƣ���+e�=��hL5��L�![P�Z�B���r/���/p����p�����fI�	V��Gpփ&Y�Iɜ�=�N
�h�T�G��Q�f3�'Mz;��6���
R�.A���OӒ�Bʪ2��_��qb[��X6�Z���{���$�U�����
�W�e��Z��;����WG��=��7`)�'r��O����8�)GO+�z�u���*���ɔq���0	���Y"��=�1�6�V���8�qh�asu�ȟ�V����h���|���=��M�����-���e5)�|���t��u[��j�	�a����6�s�$�X���aJEߌbT�%��5A�ʘ�He\���C����X5�@i{�ws�*#n��%�q�]�@��&'����7��9÷Xvk��?$���u�l
��8��z0Ҹ�G�GG9�cTviP�B���ʴ"N�b�eJx$R�8�=;��Z�Я�a��-Ǘs����~7sN=u67#7ށ0.�q��>��+�E}���'xZ�PfE��#Y��T�1r"]W�������t�RA�W�˕6hЃ�Y�VB-r��5@�W��݆j���v�:�¶�>����a=�ǿ�h����I$��I>�_J�jn0�X`�h��yW��yU����(@K���4��I[�?�SQ�����_��h�;�g����t��J-T�΍�qp-�X�}������O{�:�,��6�A�$
�;W}�]u�;�0��u�W{�p܇?&���h3y�-1,ب1��n�V�V/%ÄE�"����"M�-Ǎݢ��Z6������2v욛+���*t�>v�XW�ԉ����s0��p�0��v��Y�A3�V4�CQ�F<��8f4 �-z��%�z8<^�7 
��r�j�KW}rUl૰�|X H�/�9����;j�x�n�Y�c�1����I�9�H�+�Mĸ�'x���Qy�8~�$�`�i$�%
 ��L�^��0>4��P%������vĉ )t݅%��m%QuV�XT�Z^���黃Z�U[b����°��o|��3X�����p��F^]�����/�vx���6������V�	�V����2��[�@?Zw��Z�c���+8Lae�m,�u��b�X�%�?�F'*!q�Gn����=�E�h�X�h�s��1�`]2N�*V���Z�I7��k�T�T��es��T�:���˰��W|v>��,���Gd�O��\��)#YH+�S���,)�eפq���.��=�)�����c�L�*�jq7�z%0>�)�`����U4��;�`u-�yׁ��#$�ɩ5%�s^��������6���$a�E~0������r�v	�h�RD4>���v]�V_�2�&��`�`��H;��ad��O?Vhv?{e&��[��`��NQZ��3�H�Ҭu��z��
\T}�������.MOF
�0i�e�%#�*��z�rw6F��vƫ�(ɪ�`<��J�"%,L�Y�#�W��8�6��5粂�R�X%p���s�\ ��@G\�u��m�8�i m�NE���(�[w�<d}l�<x$���3o{lA%f��G%>
�ԖQ�V��Ee�+�%�vYd$�u��p��G�ڴ�ߝh�M���	�s2�����~5F�-�c�{uW�AP�h|G,� D������ڌ��;2�ݘ�����r    ��J;���_N��w�}D�6�k
g98�|x\�jg�5�������Y�H�:��]֢�h-�:�N�����G�Y%̷�{��-ۅѻ���Ω��/��J����;[��Ԩ>x���K'0wi��X5&0�ΈT����x�GV��Wv�1N���|��8g���j&�32�~�S��2eS����{�K��VGX,&�|tD��b&G4�Ǖ�}DR��Z�̊��`u��R��]��J�Z\��D�
�`3�d��+��5Z�F�$|x�WZ�Y����,�������K����Ϻ�:pa;�쒩���}u�Ζ�pz�a{�y���6N�h<\�Q��A+/�o)"�T�5��I� T�*"�ì"�X�Y�@f%<�e��Tǒ��J0�3\�^��I��RV���#�ш��\oZ��[��F��#�̪����3g"�]���6���++��`�[xΣ�W�`�BO�n�,�x��{��V���X٧�cJN6Bä
�H�k��l7�lF�4��:�W_,A��,���(�"�G����$��'-�M�t��~�f������z"_��.���1����S�&0uc�i�g!mR��Y�S�8+�燠����qYɔ�	5��H\��*�4����i���M9tA@_@4���ZZk� X\�+�����(�������T>�f}����/���<��H�!t)�[�K�)�dJ�Yb��`���#���Y��H$2N��(���B�L>����O�K�.u��Ń��/���$�a4���=��$7{��pЄ�"�q��lU��"�E%2A����P
�H�_od�yWp8ޛt�W&�r�,k�mMŕ�Gڞ���rZ�!S�����Z��{S��b���,���e'�+}$���XR�a��c�4��Z��wC}�C�v����>ˮ��4@PX�Q�+��uB�Il�E{��X�,,�(�3��aQ��^�e͓8'I�;X7�:/���ۻ���k�Ih:��0��qQJ�]�lm���K+su)�U�e���*P&�KL�0����?n@N�k��T^�������U�o�.�,�D��}ƨ�kA]Y�.F\���9�a1Tr�4 �S�M�"F���Ԍ�`W�C�O�z�ܔ�T����ӻ@��{�W���v�0����7���.���E��o��jk������3�C�(����Je��.a<����(.���!�����_A��������=�-B�>K=oh�k�;.U���l�~�KOk{r��3j0Q-���+���b�\��2~��i�1����	�po.�84�7��Bb��p=\�8~�B���F���fu��*�|`� �Fv��m~Z�>��r���3`I��¬b�����N���}�c��w}���1=��K�[w���n�K��Jo��Qj�,�s���M�J���j�m�Yp���
O��?�{�#�O�?���"8��t>�t���==�xqd�s�1^t�e���Ѵ<�0ǟ�_����h�q{�������S�̞��&��KQ3!�΋1���@���k<�=�+����	s�?66{UE7p�5h��X�u���)|=�j���F�׍Zh��F��9j�M⺽��^���c:z��7�m�}�����r�8��#Li*rn �ؐ�:+�d�b�ѹ��)[�b�b�㐨�)e��L\IP an�f�]���G6F�z��Pkd�Z�馷�~��_��PN�E?x��oa��x�j�k�'�g���Q��ޮ'$�T�J�=�ܕں����7`d�>�F�?*�4*�����ȲV�h`X[\z7�Y
��h��r�e!=��,Z�R�noY-=�YZ��p�r����b{`n�O���~1��v��e;�}ta΁~~�����]�f����Af]��N��F�ˏn^�Y.¤���y
�aB�`���[A�c���_��J�ᕬa�H3D��[l��X?���k{E�!�N��O�hFE�2L�i�E����0㎑J�;2[����O4���\^�4�<�1=�Z�"��������o��X��Q\��������L�
��芕�ny̝�a�D�-����x���GQ��Q�!.%�G�,�:Oi%�R�J:g�ztj�.1�c�� Q7K�Y6`�)ρ2k� ���Ɇ��G�O^�3R��"y�	Ȁ$H�EZ�^�ɓ�<������W<i�J�㹠B�A��+Ufѫ���05mEՈ���7��kfr=l(9���#pLCgs���5OK��2����A���vASL(q�j�M�����T�D^���a�R�|��w:K؄_��C���u��̥��\	���2+C��D5++��C#F&��w�9>��ՏD���ZÞ��$;e���Q���$������i��oB�Q;��A�8m��pcS/�5�'f�k�*��`������G	^���z=�o�:�4r���8��߇M����+u��b�V�T���y�5 #����Y�G���,��΀���Y�愁���PDuf4���J&���2��ɵ�
t7��@�Ć)���BX�|����"����K_�#�������FrGR�a��t2�|.? 2P��Vh)*�Ͷ@��Э/�*��t��x�;�4����n,j���'�.�n��3�^��p�d{������u
.1� ,���q8�C8���u�&���3����L�}kc�ެԦVP��Q�񀡒ŭ�8�]����xˍc���:ܠ��mne6�(ׂ?h����;D�h��� n�;���W�m;DKZ:e��g�����r�$w(�6���q;�C��U�z �]+��������fjE��s�s�n��^@'g�Y��ce�`�j���J�]�hɦ�*%�8�s�ϔ��((+D��`4k���dS�t�s8� x�Ri���J�TáA<ʻ�����,`�T��n�ڣ�V��Y�r�#�� X���a ^^�4X
\����[���3;X�����Y�{%~���3>���Z����%�]�]�z%6ըS��;�L"��H�w:+��%Y���F֧��wSJw�tQ���"z�`N�������D5#�Ca�8�G�_m+t�z�Zt��m#7$ŝruo͗0��{� ��h��o��Y~p�n�QG�a�ʀ�}�tp7����ؐ�$0��m���;��S����nz�ze��{�k�5mfj��LZ@������=O��78i�5�B~,䥲-�+[Q���J������
��z|IX��Y����9���j�@�mDl����:P��۴��V����.�Jn!&�ht�K�:GP>p�WͲR
��3W�N8���q��!o���$}//������㬑?��s\mЁr�E7�0)w^eRVl[����E(&�.M7
��`�T���f}:�t�i� v�Z0�={�g<0�.�Sy�:��ó�� ϱ��Z�v�Sճ?���z�Zɵ͏tu�Q�������}D2~�P���/�4:����D��ព7��K�d�c��ݱ>�eL1��,7�N��M(�i�/�B5֔e��F�t8`���}�ώ(Q�tpU�>&����.��Ǫ*��p����m"6���e
�ηEe�U9�
k�:r�N	6/a;i��#�A9(� F�5,��Q��c�gh��Z������6*If>;�\�x�6�ّ�%���Y�iFi���HH��I�	�yJ
>�MD��m�e����iϸOᚣG��}�
���)E$��gH��EX�Q�SV	�����i��`�tƳq��n�{��*�2�A���S)�</eX��&�&�S���� �Iw�Q�#.�s�y*��S��*
��Q�tN؁��86[Uݩ6�T%D��M��j��U ���L�����~!3� ��\6�C�dLn�N<X��x���g��~o��Y<����|0�`��3l�k���q9�80����<�i�0t������
t&�	�<�>o~SO\��#n¡�ѤJ>ۥ;��;Ć�� ���I1{�N{j���O�@m<������Nn���Z3��    �D�O(�S����O�w'ǧ�OO>|��|�̓�'�N?~ޜ�_�'o���Nޞ��������(Md�%ɓ�G��-Oqv �P�5:])G�Nf�;:�s,��	[�IJ�9h�	�ә�6ݰ����ŧ��_��;}3������7�����������"�Spv��B��ߟ�ClͅI��>���f�����ڣ��Or��;�����0:d��<o�W�3�3�`����������!��y��T�sO�U��+��0��^�L�[���x~�N�����O��] D���7ݠ�M�ץCb�=o��pM5ږ��i��=yG>o��8� ��=,r�?����B����y�����c��E����s�!d��;�m܇M�G������i����������4��?�5���ra.��h뙀�ܨ�I\=�ޒp���1�[&u^N��}؛�@]��CҨrQK��];\ zW�랧�Q�7,�oN?�?������{���Q�*xS;Z����)o��.-W��?�u_��5c!��n��,%�\ZIG
:���΄�r-67��	rN�̌J-1��k���\�p��R�*��XC���С��]���Z*8|�r\�b��}���s�t}���s�I�x����m���/s#>�E<x��|ܽx8\Ļ��<E:����������,5~�'a\�Hy�
� ˽�3$Y��r�=`���q�.&k�q�}\k����������L��'�?���SvQ}�Js�%���#�M1m�h�|�,���1h�vhlE�<�:z�-4e�*��xA��I�Yw�٧��7�.߂�W��q���]J}H\��%�J�+3?���}���a�p�QQ��K��t(�	�B�mS)1�r��yu��Ug~oq�<ۋr�:Q�+��Պv�"��~�J9�ƢKo��9`� �i;���$����9�������'��	�� ڣi��������w'�}:@�N�㎲Ji�r�Yu���w���!�s�ې���i�6M䡾96�(�Z�"W-γ:��G2X#��[W��[��<2�<D%�.W�:��L�ms…�����~�?��Phn;0��L�;�YtuՅ��xߕ���	NC���	nz�Þ�����z�����c);���!o�\��z���Ӄylʘ_#�S ό��K��Fл�[a�a�_j�Fɻ�o�ܓ���8�}Wݾ+ �Ɍ���Tm0����P�G�@�4�C�c�+�>	E�Wa$J.��R�rM4�s�[����;����g,�א4���8�aL©�۝��Fu(sVb��q�i�wVј2��>������9X�,!�|+2��b^֌s��%qY�y�4�����9.D�����tz.������o+�K:/��V��,x��𓖔�"�%(ʎ����G�J-Z����{�r$L^#��-�U����H��9awzލ:�]��0W��aN'�t@>��I�ӹXM2Ve.C�Y��0-+FjQ��_�v���Cd&#W��;��FF���*5t�AY�z��L�R(�[7�dw[W� 0P=�{v�i�f���PLn��1T뻿�uP�����'��yJ�c���cyQbΏ^s��R�܍��<��*�R��ob��t��@*�*p�����S��=ܿe����������2��7hʩ1L�\n��*��2�+��2�v����
������l�`]���Ӄ��z��	f�7'�����}�ݎ&��ʓ�NA�Wa�㹎�͏�O.N�~��} �x9�q6?��E]|3?>���E����o�\�8��������7/�i���h�{<lc:��N�?|� h�62���t���pm?N���y ��p��LP���ٻ���m}#����������#t�KY؆F�-wrl�7?;?����B��oN>>i~s�I�������v��OO.ގ��q�w��ߨ��
%�?3'����w�s�������v|��91�߿?=�O.�>~����?�hi�8 �}�F-˳�sX/'�������ՙ��_w�ࣿ��L��"t0E�W�����a�13�j���%�f�%K��PY��Ns��O��>%���=��	;�c�Fș2)'i��aW�=B���N[!�Ё]EUe[�2'c�dq����4I�4���g�S=V��qm�I)��r`���c8EˊM��=R�?�8D)�`�G�Z�Ϯ��0jqۮ�.�?�M�b,X���N+US]"w��ñ�^��1޶�f���{g�o4�A��[큹c�9L�����;��T����\�i�!�ף������=W��Ο�	0�{ʤ}������w�1��ݔ��?�VC�rW�g��0q����a۠�;e��������hQ.+��T�w����\�w���IjS�v���x��}��>�ᘰ0x/V�����x��V��xt��w�m�O9�a@`CU���?��"���6�öW�����ٶ������<��>����(ʽ��c������8�jBq�*��:/X��){�?oek-�J�zgO��󥴓���u�(��k2�x�h<y�ε�)$L2Y������8������T�{4ʳ#��T�K�Y:�-�6.���!��i�Y��aR�a�
���G2�2C�X�2*0��*%iX"xq*E��n!��*�x����6�E��L�]�CU�G+ij�,o5���]R���k��̇��$�A��̒�E�5�^WQϜ�E��.�H9y{1�Q쑣hD��`���)�D]�aĉEV����*d"K9�e-t��in����{Z(��~�U�_$ȳ̴7I	�������-��!14��'��vf
�	@/{2���hT
4=�����C�BI	\�&`IDV^V��eVs1eI�YV��fJ|T��8	�C|>�#�Pq�I��UŃP���$1y.�g�0�S��q��\v���zqZ�S�,/L�yJ�#Y9�eތ nݰ��������قL[<�L�{���Z�wܮ��H�Io�0��!���M�x	'��fzJ�m�K�5:�WCd��w�Db���q� �0|8
�y�tG��`~����
�+�LA������u��6��UU֣?�����m����(RN��6z�N��Z3X2�H�Z�$u���%K�("��ƭz��5P7NMG��T��9�y�b��:�߫ؕ��,�׼YW�y�
�ӌc4{o�Lso�\o���eg�/����G��`;��R,��uQ`�*�R Wd�gEY���䎵���5ǥ��f�5�r��jPaR�es-:*b�X����K?n�יٸ��b�P��d]�g'K�GeմA�'�ka���dH%t�W���T��c���.��H�E���ϛ kDw/��E�I�q�$����Q!� #B>������K椹餶�M[��7�:D�"�y�z2���<I���a!��+�8�K^澐T�4�$�ڰ��,ڱ�|	��_�͏�͏���"��򭣦X����>}����.�n&�����axg�:~Ŕ����Y�kX��n6D�������I�����`R�!�ED�����ئY�}DIU��$j?T`��w���Yņ\�7�`�;��p��-�c�t߻D�V;���|&���a�)�U-ü,ҐS8���Q%��� zT!�3�3�*3�
e�W�^u���������l�x�sՈ���N-�Z�gԔ���U�p1��	d�Z�]�*�̭��\�3_w�Q(��$�TDq�T�"�o���x�R��������Us�R՟9+ل~ћX��X5�-W�ɨ��Vg Ia�j���Q����ۀ��{�(-Â��:�ä$y�JYQQ�$Ÿ���p�;��^�:��V~����,lJ�\��E����tb�GOC�۱c���U����N�fȏ4Q�&BMRT��k�02���y^�:��g�2�k��!𺧁����y�����@z:��r�}���lF��@�ê�_s�Lҏr
��?U��Մy�J~������eԟ���ܘK2��v2��J���Yxá    v5�;W�$�È�$:-��UT� D+�j<q� � �^��׶��Z`F!tKɫB(���Z�ص)�.UN�M�?=��1s���ݝ�G0�t�vz=��s[� �����-�s�n�v�Q<�BJ��&2+I��ъ2���Z��E�@w1�}�Gн��0Xn�Ce`)5:y�^�y�&�+�b"����G,5��kQ����"2P�#��J�	g�}�����q�3�O}�����B�r����Rq�Z?�fBA��ۭ;���'�U��lʰ���;�s��ڻ���,rJ�>V{�2�7�r������W$�ƅo��զ�C5���3����b����T1{�Dg�L�3�+D�YV�A{�!Kho_������)��_%���'�������������vU}�������>CB��4:�bsi�-�ŏ��n<&~�|Ǹ�w��񷦡�O�8�;=$& �H�Rb<;�E���r�t��#�4���n�X��В��덗����6��A��VT�U ϶�������X���@g�݀&�QxU�V�V��A��K5 z5��x����{7/ݧ0tV��N��x��&M|�CAx�o���f:�]o��t��^���6%�����{z<�ȱ�_6��L_fI�O���qp8N�c!�Z�]������O�?��?݇�7�.�l�Y.r���[�]�
9��Hg���)�2̪l�*�+�ef�����=�}�؝�_6lD����1 퓔k�@~���u��5KD��y������
N�e��w;��P�g����ر4�.K��A�П5u��aeX&)	y��OX��EI΢�%�`_�X"}8�в8.����_�Q�e��B�=�8�E4"uVg2�b=�l��h��9`M�=��dV��Q�4�	g!��Jʌ������[���*p��,�}��~m�tõ�RY@m��xܧ��X�v�4�=¶N��4�ToV������o3��h��H��C�a����i����B6�y�.ʏ�Q 6�1	�z����C&Y�BfaN�"�RX�e^Gym$��Z�T���2L1Ie:���<�K��jC2@ϩ���qp�tIlw>�Ǿ��������N� ���i�*v�Ҕ�.�wD���z_}0b��F��з5t�;tu�b#.k�h�$;U�~U���0�m��6^�I#��zMϻ��{h��=�]�2���E��Д<���.+���ZCp;[i�퍾C����R
�m��
��CI�A�t�}�G��=:�1���ꁊ�r��Ț%��Hi��J�����DZYa��nր��,nͤ�L�vg�0ou:��^��К*�i;�;S	�y������V�yF�#�c�#��S�1�U#�^Qp��`���@j��Y��k`>!�L�A/���;z�����h�n	���X9n���_��bs��6
Tѩ_�eƨp��B�,a��4\ڛ���Y���}���mH�s�R���W�mI)��>��awЬ�fs�P��j�V���"Ã�T^�
�)˅`�d�O�by�׽h�6�c��1��:}�#~�FVLc,
/Dr�aנ9��8��Fg=�Lu���:�ސ}������Ҧd����;�rw�٧�+����iOP?b��vO�8��F��njuD�Px���O�a���`��EiXT19�@�$Y����2�rPUh��b�2�Sa@`�����Ƅ��_.�r?�s�,H�͢at��xŎ���u8
,���+Щ3DkqV�1)�8H�LC|��`a,b
;>�D��tiA��`ר���M�r�Z�<��`A���(�>��O6SI��hZ�s8����{��,l.� �M.Y��B���o��������chޡ;T��=�ٴ�V�o��g8�6B��+n�+�����K;m�G�&��M"Zؓ���ѝ�c���wY��eS!QA=���ܙ�"[r~���!�o�1�Abd��T�l8�p�D�WÕ�R���!p�$aV�L�`u�5�c�'p�dT�p���")ü�d*sA�T�i췤�s��!1��ѺC��c�!��0I�f02QK%E�$�iAe^���Q �� ����ԋI����߱T�6uߍ>	��Ӣ����Aga��Y�U`������u=����m&,f��>�Ehx0���q�^;x

��l.��^oW�k4�� ��-, fg��J�a�c'��G�Q�.��.�a v"���Qp��o�j���O���Lv�?Q�]�7?��x(δ�œ�W������s��H�����(�]OF46���aqjƻ���K�K��g�}��������������=����ǳ�O.>��߶�u����"������*w�y:(�%5�=R�Ι�v2<�ZU�&,A6�z"�� sF��Z�T8�Z��p���?# d�1a&84�̲^��,3��[C'$+Y�f��$���1aYDKQKNƈoF?pPɼ�B��	�����`�-��V�u��t��%֎R�,�K�"��{���>9������ɜH�ֵ8V7�#]�#Ftw�x^�B���B���pU	hی�U���^]cmT;9��c�FW���������.:y�޵NE�\��w��x����.o���u��c�Z�C��Q�C�+UF�[k��P�۠��}��"L��_}��M9z�,-�+ؐ�o�7�g)��c
_{vo~��}��r]��뛶���H���Ӹ͖�d�?�kp�/���}���?� ���8�*��.4B1\GG�d�Rw�P3���5]�D�ԃf��NY��YVQ"%�N��cMƔn���8���I�A��^V#�=\;
	��8��ng��l��H��6�(�L�d�"�r��	Fy��h���Rp��-���w�w�mJ���ӽ����l�m��t¤��x�L��`���x%�6�����~'�`�)�>��CO�S���ADV����H��(!�	�2)D$*c��`��I_%�TI1�<�7�Xi)�?L�F���J(�v��H-J��$-KC&��%�Z?-�� ���H�)����Fջ$[������vΌ�5a�r������,�`��+�U�����O��!��Y���l��l���G/®�V~�M��'SF���]z�s7.�6`H��gd�{��C�
3N�������� �gG��sK<���2c��WL�p3�n��2�$q(�.��D�`!��0�`��8�»�,���[ett��Y[����9�Sny|��^�:�eʘ���E�+�Ds�t{�jJś���l�����6��4�m�7�Y��Y���@U��[<���<a�S���Rd�#X�eYf�NdUj>ߴ�$�`;�C�.���k���iu^(�q�7�@����J���,/��L�y$bӭ3T�ZV85�B���t#��R������x��[0b�ْ&aND�L
F�$n���X��|6i�C�!Dк�v7*Z���Z�(+�Gy�
fm�)`���=(@�s��VX~���lM.|����c��?O>�f��������Ϻ���(�G�"�[�8^`vِzD_�,�T &gW08)J��	I�����BcM3��J��KȢ��%�H��z(�!e,v�XU+MXY��{a��hT�'H����Fi�65u��IC�9��?�tz<�������Φ�`��H|�R�e�^���=���!|�dX�!�㣄<�F&e���0�W�@�0�q�����8R*S��i�յOnQK=����3"|Qb���������>�T��)>י�=�K=e���|�cj`��U݁�P�S�%x�D�J�QGM��\T�P�X�����V�4N"�dX�F��X�1Eu����+��5���J���o��@�&��m�b�hKS�Xe��M.�ezݷ��X�!2��y���~���k��J�wl�6d�!)��8�1�瓌~���aznI�զXu�X��cz����6o��`���8_U�g������7��s�F�b��Tq���{U\�aVss��:�Y�"�'DfE6s�u���YnS�\�x9�0����+9����$���    z06 !��
@�����:�����(��X�0U'�Z.?on���=�;?�\L`	Oə�+?4R��	�rR �M2K�,n��i��I�G��+��q�3-D�	��{��^(���8�v{棁��0�N$sP��)�H��8S&~;X�+PrE	��#;:�esZr�>3V����ݾ`���ɑ�Ώ�Ϟ�^G7���û�Ȥ�ʤi�1�p�΋�.0;�$UXH�1e!��g�֔�
.-�Y4�F�/�16�gn����C�^B���U~�?)����9�'h�@���Fd��=:s9��vĊ����Ѧ�A�F�"ú�(�#=��T�E]�LI�#w0Ǔ��e�rʲ����^�w|���*Gy�h�R��}=�c�6��5c�R��_d1�1�|ss�>�꫿��/�����=��^���|��@a��|6�xr��mk��M3�T�.r��&��|]S�^�N>��B<��x�д�#	F1�ww~jt�U3O�:�X�UR�̦R�½�oa�V�P���S��h-whX_�g��������˓��T5��/`3y��!9�&���l��0>b���JP�`:�H�Q���BQ��+Z�Y6��;�`���GIi���������+�վ����ʅ:��Vz��v ���O�3n{Xȶ���A��b1U������6�\�5_�Х�0B8H��򜾸�B� �$���f�����K1�z�4�|���p���?[��^#�~z�^,S��y�3Q(�3��KY	����X�\Te�&���RiK)����rjv�X�t[Q!T���٘��U B�v��`a/�lc��8�n��鈅=���)���1b�u��"���E���֝���w�Yܧ4LI����?���v�C����N�=��'3Ć���pװ~8eJZ�"��_����X���P�65�V���1�űWI$��ǂ�F��Bt����6��i$��H���{����sK��w��6e��=��>t�����KG�ߪp����嗆�kD���1�i3��6���uZ6,	H<�HA,
0�c�$u�dy��/YHYR����P�yo9��:*��z�?κ����չu��o����'�k̔�U�z>v'1���PĬDr4 ZU�M^�s�"�Yge%a�dP��0�$cYI�:W��s,dJ�9|�����
�k�����i�\��+�?T��ϭJܖ����+��"�X?_s �&�ѕG�\{Ύ��]q@������-�����8^t�
Q@�����5���ǐ1�A�U��Y$ywݩ���x��\���C7,)�^s#��hZ�m� �S)�g*���ť�d7*ndO<8�Qv,=�8���1A��d�ʎ]?�CQ�a��<�hQU��	��?@v��}4u4q7s�O�-;�<A5(�*,��P��a{�Q�9N��(�By���	��5���K0�{<������JPe}�������]�Q����3��[X'����k{���V l�8W�Ec~a��}x~\kS�؃�[��(AZ�m��{�YI��������p���{��{u�-��𛠣ҊT�1P�
���&|�v-�;<�ה+�)�gG�g#
Q�Ґӂ�.�kL�MiB�d�z@W�tFgm�*c�t�N�._�dna��0 �%��7lF�W�T�R���4`m�׽��Y�?���G>�Dk3|�f�(�x�ue=�6��:�*�R����2,�8�)�@��')znDW5r�·�҄&UY��ѾG����7)���5]Lk��bO�?+���G�M�EE��0.�T�U�T�O #T6�)$��������Q�I5֯�_�.l��ՠ|�*oD����_֍.e������Y_�R��#�iN�P?{��u/'z��{����y�)hA�H�V�s�ź]HU�;x�Y������ꋌ�םΜ�Ŀ���"9�(
�=�{�JJ��A�@�Z��y�'���fqǄ�^陕�a�Z�t5�R�D:u�T:G��TRA^��{� 0�͠~�?l���ƭ�U3�!�&l���C���.����Cy>c�(�*t Jpb�evC�<�aL#�΀Z-�:	K��@��K�ڌ�G^\b��w@k9l˼�AIr�e��4Q�>J�5HE����'�Q�ɭ�jV����/s5�������[y��&q�l*E�L�H*���Ä�H�n��甄u�Y��-�E[l��a��-��K�lШ���,_Bp�Z���=��7�2��!PiJK��s%�e�"�_5 s=%m?wtw����"��Tbi�o�����&�KU�D(n�v�"���U�8l�ql1U���u �]�X�XX�9)�0ˊ4,k����uY�btn<PjK�P}#.�VG���n��t
'��m���r�2��7 �!·������<^�$�O��XU����s��	�M1�a�g���������kE���;Z�������7�>
�S8'T��2�t�@��薘�.=���K6���U�9ؕp���H�&�(��8'����iL'D{���
-���n� K��	�qu�S�,��FŶP�o����[w'H�wao�b8%w}�Y�gi<�=ic�#C0�`��#v I!k$��I���a<i�\I��&�L��Y�V�PA�3M�$��zU~�E���\!dR;�,Y������:��'�RY��ՍqsƯ�1M��ҏ&�˲-��L��&���g-|�p�*/������G��5�>�Gd�b&\�cqެ��n8�5����
�-yE�X�����О� �-\�޻ ��e��;���s�z�V~V�~M���ɿ�mw)n��O<ɽ��1�5��ʵP%`ܑ�@�ܪԼap2�i�)A���=���v�����^)�B�y?6kE��A�Ɋy�ܲ��<��D)�8��z�<�w�\fa�d`�m@�aT���2Q&"���Ս�k�q�El^4�^����9��]õ�>�7�� �zh�n��Y�Vr!1�\��8�E�;h���Mn褲�c?!8�#M5S���Pҹv��2���x�)ةE$NSY��@�䶘� |Q���=P;A�L��OB�_�<u�Z�v���K��ٛq��ҋ^��V�]�px����Y�Gh����ݑ���/@����\��1ϧ�;P�΃���JCz���K磯axz8c��_��!
M=҉~�v�>!�WJF�1G�p� ��Z�Y�ז,�� r�������d�f %�hh�FCE��G����:�U�>��B�eXW�����<b{�����4KO�}u�I��(\ԯ�NNͶQ�(���u'�e�z�S�-�f5Z�����A�p����Å��Nl���p��{l�|�=��9l�*�$�B�	My�T��z)^v���b֞����,J���>�rQ6x�E}'��]9S�<��^9��̲Nص3���#:]�[�`T9¾��[�P�!���	��3F'xyh�WEp�5�=�V�	J��E�L&<��J�M)�[��wqe1kMO��F�K;P;�d<9�&�W�����O���z�cfs���gR�Ī�׍�DEU�v���#�{y�V�e�뾏�us&�Tƈ��K�<RF\i�k���4�����������mQ�]���
���ÂrX�QUU�D�&���z�~�I�hG$g��1Ȁο����>�..�J����v�H>��14$աwe��毃W&R"V6�U�-��6U��ԗ�4�E�ol��'%��)��Vr����MP��W��vǬ�8s�����;>�8�1��6�!l�Mw9dp�0�9���C���?���*��ށ�B����-7���q�?_jN�<my���O�T.]l�J�0\(w�h<jBmw*vљ�4BM`俋UZC6��	i^<+���@�N>Va��EEZ�:��QjtJoek:8G.�
�zphݡ{�T�U^�Ep|~2���R:M��_�5Ŧ��B��~/���~}��
D5:��b���\���g
-�i/e'=��.	y���f�~����)�,!���Љ�B:DbC�k/V'u�4�ݵWmv�՗É�-�Ń�j��    9d"�˙�R��w!��!*/J�(����*������Kb��JS��L��Ş�\�;�Ͱ���"=慌�4'EJ���aH�������x�̭.��|������>l=	ް�$]�1�?7�es2���&w�-ŉd��؍�ᘂ~Ͳd��RL26#,x�~�e�i���<	�nĢ4�2��O�},w3�)j�Ȝ�(	�dU�N��j����R��C�=�~&����}vb�*h���OF��;'���� {��@���0;�cü�� ]$ݺ�f٨T���(�!���úz!�}"?���+���1�
�|��O^�;�,/жd)��aQ	�WY�UEJV�eѮ*\Ȯ��#�.��4Z���dX��K���E[WH�ւ�[��y���#�F�FJ)U��1{ԣT��>ꜟ!nIvwT��o���g����3����S�T��V�BW�0՟)�t�qQ�۪<D�f�����U}VQ�Q��A�g��]_�j��F�icUh�n�0(=,f�Ip����ʹ��%�mv귪3D�cz�7�����x��v	61V�6��1I�F��e�/Z̬R�7��$�ָ.[E0��0��|� �S�$tJ�H��B�[�`�!���TXK7��B��V�6���(5cݬn[3땼��eT��$2_�$���t�je��&K�,��M��|��s}�7���9�:�jTn��Z m����J1��Xl.b��I�����]ςX���$:ِ��=���*KD9#-�@�&X�#�a�Vy��J�����E���$�w՚��y��U�w7bF��UT��{=Ք�Z�-�e�$�J;{q��a���9��<̓�9'p�(�L���E]V���q~����Y{8�R�@~�A��[.�q|<q�xg}�q�E�I��,:�C��X���9lT7��;1�1X�e?$��'2�+�(gTV�q�����c��X��Y�$���`�bt6��Y��bt��K�fJè�|���[M*��5R�ar/�Y�/Av���xI�PLWf2���8��$�ӊź>��gH̯���i�Z����?�Hq���}�k��{� ޴����?ܽ5h݌��9gg���7�����G'c��}�8QB�^���M�ܘ���S]��Ӌ�=��`��S���(&�E�ID֚I���<,�4S��sB���()�s@_൙�g��a���E3� }ɝ8�\�zv�U������MǓH5#�����"�d_3���+��
�����[�<��R[5��!q�Z�5�M^.`����؈h�i: ,�4�3<�x�"J��y�Ҫ�#��76Ơ��hP���ʧƶ�B�ʆ�jQ�rw��u��Q��ܱ\��_Nt<]�pn�Q��R�Z+�'�h��.h�ٳ�0���~=��f��K=s��V1�ۧ�PZ,�����9S����¼ǻq�Xi��Y6db��I2u�<�O+X�%��e�Y�BVP���(���ɾr�s$i�|eL���Kӎ�v���֧��>�����o���_Y�Ӎ��B���7��)�|����l�u^`�8�daUH��L5ƞ�{.la[�/}�^����0 �Et�}�|�����;
&g�Q��臾��?lǭTW>T��)X=8�T٧hh�(�͞��+��u��,IS�uJ���a�k��l�|�_ʋ�ϝ#�$cg2��lg#�y
ڜ����r�A�.��L�i/2��3E�>AJA!ek��f4O��v,%5�]4a �C�����v��	FR���W���,P�Y[���ѹ����S�T��h�~t�3��B�����k�}'cdvf�e�R���]i��f�v
_���Z)Y�qm���	n��_V�ٳ�y2i��#2�� f��jK@gT��^fER�y�������k�,�2䳁#1̩(B��Jj�ב�%���s�r�r�`ǆ�.闃�8�a�e�sO�r~����_��e�+y���"VѸ*zA@�5b�S��ф�A�Q�M��#��*C^g�������q�����./��\b�:�u���&�`:�噖�ڳߠ)�b�3���A�x�֛��=��륨�@��H��|B@z���_SSw.����k�^����p��i 1=6��{���7&���v��X��f]�Rx�Nv�����:�SХ�q^�P��HDdR�Z�Ť�-{qg΁�{�0�X�zј�����³���o"�����d�g�T4,��X�i��ws,�A�D�/~�&#�QX^Q�c���p�����&��M�n��y�A$����ӳP��� ��}j螵�1���``�L/�?z�� 8��>]���Jj��1�u18�촟�+0
�)_@x��=��4鿄!=��U:iH9@G}��k��y������9ȷ^��{!=��^���O�r c<��LvW� U�(h2��4�L�,&E��^��wU�O�����U+�9w��M�TW�d~UlB�^���!*đ�D�`ޝs�7�z���j7��"�7S�4$G_5���r�݌��*!�wkh�����x�j�	�޻ZYRZ!f+��x�H��ť�[� ⼟��*�x�1dY\�k��QA�:+���ғ�B�Nʊrmi8����^p�oa�쫗J�xdڦ�fw���f�`�;ExՖz��0@�:_�c�)w���_�m\�����>���8�FN/���Q��J�;�zIWy�Qrt�
j@&�����2Q����[LW��0� ז�� �f�=�ؔm��	u?���	�,k�!�����C.^AB�Bڎ���b	���j?��뽨V�@�},��R��v��VҔ�nn�|7���4҃�q��� �N�j#-,�D�Zأ-��&�����m_<4��nj@@_X4ʹ}�@sgCv��:�f�Uv�lp��-�9�7l�JWA���	fҨb="����48]�y�2��`R�X1��p��K�1+H�&`.ņu���@ׂqd�L}�m�n���vy�#�A�zB��`с�l�]&���e繣��t����6pN(g=e;�2�yQ�q$`kİ�E�a�,!	X�'��X¾k`�¡;��K�Kp��!��Ӄ{�)*�մ
���EpX�ET�2�>(�7;���V�&��S8���k�t�C�˕�&���e�����[UW$�Ҋ���x����dQ7&���FO4(�*����n�:�	����$��S�ǹM3є^d�"��^�u�2���hl���Z%b��b�����Ջ��8b4���s�H�M�٨J.�l,�:,����<�:F���8��$������Q�h~j������R]���/��9q�Wz{Y#SǍ������I�k�W�ߊ��ǵ?1���[�hm�~�3L�fx���w��?	��NZ��s��`
&L��j�N�#YT�U����LK@,�EF����Q>�4��>p�c�5��	 ��1�q6�L����pr�Si���GyW�d���c�;ԏ�@Z�*Wk�iQ'X������זA��+�,�N�6��`���ö���!�%�$G�:�x���9(�Ǒygf�,K�˘5���EA�PJ��לSUG���Y"��/-.��^zB:JwZ��)����x�@Z��R��o��U=0l��J��M��1b�����㇪��b �+�����b��G���/k��$#�,��G\��+`�}¨`))\7�Ԑ>�ݵ� CiK� �2����F��([�Y?�l0����Ց��O��b���l���o��F�"�\�8����W�gy�	�U��Xb1T�	M[�p����,�0�1��ఇ�@�@��vZ��z�ޚ�C`�ѡ�غ���)_vz�'*j�,�d{ɣṪAOȉOk0"JRa�3���N�뤁�A
!�	��)��GӁ|XͫY��J���h�M��>�ƐQk�hm#�@K�L��)N����wҌq0Ί�2.@
�jN�4.D��&I{,���\�e,4��VT�jL�� �t��3[1Oh�Ķ���M`�&��� 	  	��.���`h�@��6L�".+��:��e��t��dF�Ȑ$q��i�
Ͷ���/�`��/�$OS��ґ�ˇ\���H���v/�VMH)��@�S���D0U��TU�~=x���i�*	��WՐ���FU�|�`l��X6kr�D5@�C����q_�b*���|7kq}���2������:,��z�}@eSȥ�KExuĈ�Z/�ޠN�,ٚ�}��z�*�9A�q����͐�l�A���g��vTg���QN�F�>J�m{|1`*�qX�G�|�v3�\52gh�v��y��sx֏H)�ʄ�"�+�]G���֡�뼏���X!�ѻ���ge�8�����D�a.��c�R���p�
j�p��Ki/8��K�ʘ�5*r<���VLVYQօ��[I��C�{/�q�C(�{��mx����������d�$�z{����KyƆ���������-�P[��|§�٦:���+��(ՉE��T�|�O��O�@EF#      �      x������ � �         �   x���A��0�s�Fƴ�|b_0l��?a3����H�[o���$�#ȃ�XH�uG)8�Z���u{}�,/_�I�q�D�a�I2ğ"6�~,?/:�'y��NA�&�1���>��E�`!d��j�l=ep|�^z,o�ˤlY����x��M����׫Ci�P���&I9�\�̍y�+�,�+�~����e�      �   �  x���˭�0Eך^P��d��m$�꿄hf�$K^	�⚇���+}��t�h	Ǆ�>���Q�����+��~�x�L�����,�,&�R��W�S`Mm }$��`p�)�洊�>�0(:bo���Q}iP��󗯜���Bz[07\���t�.��0�.�;[w�L�����k��c�x���z^���{����{�-�@�/dU�l�W0?���bRJ{+� ��$K���M�7 �h[Ɋ�x]uɘd���(}����n�.�*��HX�/����?F]��-��[�V;������0ٗp��.��A<;����R��{�;���RL/�6˹!�g�Y�3���3�e|Ʒ�ׇ󞁙�M��z�^ ;��      
   �	  x���I�d7D��w��Y�%|o4��~��ʯ�l1%2�`�/��j�Ō6��U�iҝIu?|���/���_����q\����M�-��u�Vz����~9{·�|�݄>�z+;2�v� ݧ��˅s>>���&�:�]FC�&���jy�/����}>��LԖ8�{c�m)�Г����9�������yM�h-�W�i|�_��6��M,��;'&���T-��y��Tq��Hay�7�U��])3�߯���*�B1�I7m�`\�}�C�=χ��ۻ�J�k�P/�瘻���_ğ��(��1um��0F�xW�*@�)��X�G����	�FκI��%���'�Zq�{gMO�ڈRWC�c���lS�Y��!�j3w�S�c�����[�Հ�4g�VsF���s��ڗ�?���ѕh� �҇�-����J���2�B冔ň�L{+���Ϯ���3Y�A�>5��K��+	/���2�s�A�F��r�ڍШf��f��gLo�� -3����V�S��ZN���8� ��1\�{�Omm���D��R�*��/c݂��?�yjE���9��j���M�lV�9���~�� ��of�A���N�_mI�ʪ��I:���n���j5Z��n��Q���Cў��~
���@��������+�� ��(&W�:�h�8NI�����K� ]��tn��i.�ړ��u�A��`�����Flf�9�����W��tw�����!����|���+� ��
�I��Y)K�
��W��t��GR��ԩQ�|@�Q�^
��ԠH![�$�@�^���J���� ��R9�)9�\�����ҹ�b�nmm�1�56�H)�]O��Y�+;��5�BfwꫵjKx{)�[�[��(�E���(k5�9.��p<�ٰVa6�s|i[t�|�y|�.V9�ͺ �>�P_$���5�c:Xoh"Rh��;d�n#���{��s񕔳M[3(j ���LQR�Q�P8��lEL�Yl�h��ƈl#a�V^�,R��0c��4$� }^�?;����oA��)���(#\tK����I�2�gS�ʦs)�[��{�?���V�����v,؝�	�Q��pQ7֌یkr�j��,-w��"�Ԝ�� j�'����;��O �*H$]�twf�X�_���m�>��JG���aTDx���'"|W��[�]�𸤈p�wY�h+Χ_���\�l�qr����=�bJ�V�����}të�b����Z��{C~�[�vY�w�jʞ�i��]����1����Q%�����?t������ҽ����8_�aZ��?�	@2vs����S�`U���]I�8��X`��ȞR%Ǵm2{��7��@�A|�F��6,e*wU�7p�N�כ�p��=4T��l�}I��'?�Id�lK嶮F��`z�f�z����L/m���b Q�E<+�����4o�ꨏek�ê��kٮ��rp+���]O�0I�t�L��߶��2��#и�6�Y	4�8�kJƲ�x���� -��h���2s:�I\ѻ��4��0r��"���Ek�b�����43YM�{F� ����F�v�o�	8HW�5��mFp���s��aO��r�����t�䌓45Y��^�o�r�FM	�>BI���~!���^$�((3�n��P��ӡ<�7� ���Q�=MJ躟��KeaT�� ��(�oh߻.��W�K�U��J� ��O,'3����J��RK�W@:���������C̱$�.c/�PG�-\�L�!��UU��v<@�B)�Fh��v�?�m�D�\��n-{m��:���������=L��$=02�*��`���ު���F�ǝ�l�nF�۵���*���2�7�����Mۥ�Fw�ҕ�ۤ<wk@��Bњs#?�D߀������&�Vi����J����[5� �ۣ�M� �w����6t�_��>H�hՔ�k±#T[܂����|�	��	Ěp��[�{�� ������]�.%7lc���5� �4v�ȸ��Y�� �c�}�~?I\t1��M�3X������ru��q�>P�)%#,yh+-el�Ї{>��?��~́�����f�Q�2�R�F���-��+�,����G�]r���;ވmt���{�%���*S�E��ܿ�<p�=�W�vc�i�=0~X����^��M'���l��Aӎݞ�^��_��<�7�ͤ��6�k�SBٳ`��+����y���S]�F�o�������	��RM������{�
_x+�� �zV��rSa������1�V��G0Ժ�i���p��dp7|??J�aY����>\ǨHK�~�q�����~)�oD��
�߀�2�RAn���	��ls>�|mO����ϟ��JU         �  x���Kj�@���)|��z��1��Ȯ��Z��{��{�\���h��B`�@�g�_�Q�a}ܼ�qx��~8����4}�����H�O�0}퇏�?TB�+{�����o�wQQ)�xp�S�B���1��	j������Y�x[hC�:��1�C��T�e�]�Rv���EJ��z3CV��]i��5�J�B�]]�K-�>3����)��vC3���.�zm$�3¤�j������J�l��Ц"�%K���Ce0��Iu%qI�1T<>l*�eMCM�燥��d��up�T�YW��jw�[ڬ6\=���p������"[��0��uk�H|�=�6X���[?��Wo&�G��b��_M*�����=�I@�~|���a������m��         �   x�E��D!Ϧ�Ճ�_[��_��9Hs�@P�L����z`�>�L�,,���Y�A�� �- ��l"McE�j��&D�6�-ĝ��b���!v>W5nf�X�2Nh���T���cN�];��>�c�`v�;?es�ߧ����N5         b  x��W;n%9��w�B)�'�p��D_���x0������<�h�%�2YU,�C�ZB�Ju���.�������k=~���x����z<^�_o��������z}_����������+�A-y%	��i�T�C��){��u�}|^��z��������@�W!�u����W�=�����Gy��M^�L-�A�z'O$�t)u�1�F�����N��s�A�����Z���M�\躢%�'�����U/[�����gH&\�����'5|IzV�Xk,yk0�nx])�-C�<��ȋ�t�4��;�S>���]:�I���A!��6�s[��� �#yl���Ff�P*���s�6	�$�G��)B��1�5ɻ�]Q��{���/x�7�n&��2S�ܻ�0����|�;֘Tr��f��!�>
��V�#/7<�#tW�H� ͚���\�k岥��Mt�3�h^$Y]��r��^�:[�z�4Ze�q2v�ø�=�+���y
�G$�YJ�٦�Q�/�6
�N�x���Uo�`v�Ɋ��F�,U�p�|�VH���s���8������Ԣ$�q�x �68������6`(�� ��{�#����``�f%�>qN抲��-�/SA�.�U��i?�����N3a��G^nx�C)v£yPk�n bR�o�/G����--OD���"����푷�i�ذX�����x5��3�u?8��
�p��:&og�ڇ+�������d���G�5=T��SG�oE����*D�D�Ii"�ͨ)�A�Ծ�N_gM���6��b�Q�*�5�A8I�Qx�3�#]eR�u*~6��<g�I	�̖�=�x��BBÑ��>��(h5ܢ`�{�L?�s�E��3)[��V <yڊu&t�=���^X"�>��������V�[غ�
�|����1��m��Y)re��{QY�'|>�dv��dv /KҢ�����!_��z�KA�g᳉�g�Q�`����nTI��(�8y�qE"fu�Q���n�G��k���b^KG��+Y�_�����o[ӗ��!�y�zHx:J�?]�����y�~_-����x?�����RM#���$����W�H�          �  x��X�n#G<�����!��z�&��-<��2�K=\pH�����8�9��cM��f�]1���ʌȤ�����~�!�Ǉ�y���~���������8J=D�Z���L[��ZW\����RR�T\I�V~�j��û�#��T�:&b)��`�9j��<e\�8���'���iF��$k�'n�S�ʒ��|lލ�h���ǘP�䕆і�Օ��B��&־�O��:��5�3�	e�ҵٖ���y?c�Z���t�I���=.���YKń��WN*h�%6\��:;��S6��'�Va�v���E�ȅґ;�(�'q�.����#6��P�蕏��l�(5S�j;U.��t�[3�c|l��~n��fl
<��9UIѧJREN��hd��^T���`N�XEQe�,�L!$OڧҊ�Ť4��k����OE�~��o�ͮ��1�TŸ��(�n��Ш�Se\pv��(&�_b��
�J��>v�1d�)�٢v�]���S!��+��ˌQ���ո��,�jQp���L���x��R5�9a��9(��NZ��d��)?fce=�/���6���&}3#�b$�,��B�b���:�d[���V�q�	��a_����R�Ȝ%U=����:�-H�֣��
{��iLhc4 ��������U�A�PD]E]^�|�XL$ӌ�L�@QK���lJ�2vK�H���������+4���.��v�������~�����,N�ǁ�-�����I��55nL=�)&��D'
�HT�yt��̕z�� �+�a,�j�	��t��4�9�ݷ���i���D�O���ߔ�J���Q ��D�����p��Y\�*��H'9j0!Wqi�%��4xl늻j��:o�cLh9s�7e(q�$�,d�b�G��F�%��j�$N��x���T���<�(��	�f�����YL7SLh^���n�����Q1�H���١t�V�M]��e
�A�QLh��៰���V�
g�R�6b�I���kh���z��_0�f	xo����\�R�C��fQSL���'����% �0���&\�b�=f͋&�b�<���&���y>��}*���ݭޕ���O(��Y.�DWbM��u�s�@ٙ��.^g=�E��0�	�z��a���R1�25�Fs���p�B駘ু�_�?�__Q`e����SS������-Q�ڄ�u��0q��B��~��6w��t��-A�����seB�3�s��-f���ɊQ��K����U��`=?�+����Lj��,)�.	�o�*5��j������b�͛h��+���iN��fCg��rh<��O1�����ö�����(&x��P��?v���_��N�+�3�]�	m곮�I�X6Kg����QL�Ph��܃�t.�i~j:[a�^;�|h���|���sL�`��~����%�%*4,Q�f*Q����.����~�n.�$�ţ5b�Q�b���k��!�*�e��[y6>�ǫ�w�އR�I�m���M�ջcI?~��)����?Lb��+z���S�~����v��ؾ�'Ș|�n�8�38(C�!{�vӥ�u��V*���ڍb�ʫk�g[�ݭ&���[��ӡO�M�onno��o>ܾ�-׬P��/2X�cĸV�ֶf������I2i���g��M�yL���B���A�      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �            x������ � �      �      x������ � �      �   Y  x��V�n�F<�|�����Ǎ���$o�{K\�E����}�i?A?�5�,�� IN�������j��mY�M�	�y0*�S��E��Q��2���
F�mc���U���q���ݢ�+[�|���6��k[�KR�tL��I�B^��J�G�)��f��:���m^ղ.� �jQX��ܖ��%Ŕ�:�8����c�jA�x�����C� R�H�c\ o�����2[���hKT�e��ন,��ʁDpp�����W�i�O���}�ZW{�k�����u��)�z@�G�3G���΂�A˖	h�p��&D��g,	8O������y�,;)gOv��źȫm��L���Z8�����a�e�G9nΙ�4�2�
�̾N���b�p�pIڐ�M���b��"�1��E���ۻ��a�$�WZc�G��E���=�^��4uY��?拪.��ݜа[�t�}�EN�g���?����<m�S��bL�,�����m�b��Dgr� �J!�Ř���q������!�=m�5�"�:+&Dw���m
�H1�؃$H&U�\R��8�
�9��Y��l�:)J�$HI�V��?��߻e��zxO�1�v&�tqs��#�q2�P)�}�6�}�$��T VO�U��<U<�N��p����> 9 ��(�I�k�?@��J���46�f"��LE�Sg���~	7��0�٠na�!���O5�[�!\�Xc)�V��Ϩ�Uy`�fIǲ0E<冩a:����%�\Q��'.a�뜖m9�'�lH�6r�=��h�/P��t��_��l���:����ѴLU�G�n�C-M��~�F��V@HΤX�g@�Av�mr�A���P����I��?8NPJ20t�F���f���-'�����l8��My,VMN&$Ý$�
�7�)4?~@�1���4�n8�늌 #���L<�aS�Qd���6�s����������1d"�a�!�aL��1��{Pp�������fa�5E!E���;H�c�y����)I�Q婮k����6V�b�<\gA^�`��>���l<�����tN:��'�슼sų�ޘ��x�HQ��Yjo�}��1�g�؍      �   q  x�M�۱� D����Ā$�������>���h���^�_�����%�L��Ex�	��&��FZI�;�_�4H�����@Y�@Y��\}��J�� �ZW�J��gu�e����� ����A�����R�t�7)���F�
���A� tAa�HT&�JS�����Bu��(OL	���=ibj@�� ���bj@�h�-́�WIZ���V�!�|�ibj����!�|��b~|�<ߴ�WkZ���V�!�|��DC�=C������LI�SzĔ��IZ���
>Ĵ�1��CL+����̕RS;�v�$z]C���
:@�P货:����:U��]=�����-�&�����mm�G}�Do���n������v�=�=�a�4��>ОDO�^��w7����teg#;O�jw��]���-�����$�V��b��i�����V�S�@���=�Nцy��U�c	��=���ծ�b������9�/�jw���ݎ� ��3�G==��<b�f5��l����&y/r���x�#&�߾HU(�u�u��Ƕ!�?,X��h�;���ګ}�G����>�+�y��q�|{䷋;7I����VC����ѦrƶGG�[�هL�ȓ뜿����ߋ�b}�1jq��������a�)>ڵ�����z�k�V;f��&Ҙ5~�d�c%�k��Gl�/v6��7�3�^�.3�>0sd�?r�w�cg��~K~5K~u�Z����W����}���{ɯ�������[P��nr�o�0�]_B�z��ƹ����ߏ����B��}��=�D��l���:��l��y�{�m��{ ��q�
�q�+��c>x�?��y�0���e����r����"�{;�Hd��Hd���܏��3x���F����߸�U���s��'N�����!?�Ӂ�ڏ�lO��_r���v��w9���y�wų,��Q�|8�;<F��]~��D���A��:��Z]Y��d�b�<�!�f���A��^��а�x�݂
�{B�X{�KU��:��W���?����x���/j�#�:���bp�����2�>^.���9;;UL�?�o���ߟ���"���            x����v븮.�N�½����EQ���h&J�Ǘ���y�R"��k�kU��(Y A ��e��^n�}?����.��p��W�Hc��i�W�^�E������潽����<����[��y�6�-��vk_�k�9����}-I��C����|l�O��"i�rm������Z��^>o_����^$����ڽ���k�H׼�w�h6�å�to�����l����5�.3a�2���y���kw|����|���{ߞ��,[�f/�aם�������]��k��K:���ކ��|{o���l��0��?��ֽf�܃���ws�?��oq����o��G���=���l��0t��/��2a觿�������-��@&�;��2;���������%@v�� �z5���}{k�6�[��k��0th/����޺˫I,D�k���Ĺ���e���'�,3��A�.{���H/�as-���p�~_�2a�?�7�5~�|�P
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
�������u.��p3|�4N^M�(ݕ�����])0ŉ���ǨWRf�'}�7Y(?�5�HGQR2�yljN�Ի������S�B��skU�D!�8�e)��"U�i>�%����f:��҆��\���n6_�T�S:ǡE�����B<#���pU�s�+%^F���#BQ�K��.���	D�ܷ�x�Hs�j�'���A�;C�	6���#�C �@��H�[*<`��V�_=��������Np�e��R���4�P�y���W�0N���4��Z�	i� �����'��5�Yt6��&G�����4����)N@M���3UR.)�-���N�燫�"��Tӎ2���,NXM�x�P�����*t�w;����T��Ø���u/�!*�(Z�{�j+���%0��X~�}ź��G�l�����8Bp��}�#�p�y��[}���u�\F�?�/8�K�ޟ%`����e�����z=�E)�RX���|\�}+�V��gJ�Vҽ�,�ҁ����8ɒ�����E��H�fI�@6�l�����e�"Ӫ�y2,}�䴒��G`��W'��l��yg���r���z�|3�K�M���x	���/�y|���c����T�V#�o�yi�uQ�X��{^O�B-�aI�[QTf���M��7+��!����z%���e0��B�Q1b��壀廤�W7t��M�4��f<-3����L:�u�}�䭒�sD�vM<8�f�W�vs��H�4�^6C�9d4E�X�6��R7գ��������_��̘0      �   r  x����n�&���<L��ƼK%DlN�jp���w��p���FQ�H�of��a&1J��ᮗ��*h���y����7R�D���R�^�"��7�My��u�t��P!Q��(|��㽱�� �9h	9�槠?���^���5�(�>�j>�ZT��K�ILqOsPp�/3[��Eܽ��}V�X�_�'b@w�	z)^0j!�(�����`\�AB`���L���ˑ�$�!P����g��Ìj����&�}��O�'%;�ҳZ���o�����]�#|}X8������|h𪳮�c�@VP/1�п�V���71�2����>j��w��P��ƀ�>A��ߪ�	��~k�n`h_ͤ�*$�/��oMM���gC�d�����E��9��Je��9�V��3С
��8��:�u4���b��D솟��$6I
��й8����~ӣQ)c���Q�Ų�fz_�Z�+2d2.j�zܟ<���M-1�5'
��(+[����pJ� �
���BQ�rֳj��7���XGD.z��������KM�B��T��[��#��*}���X�[��\:me���-�e��TSJ��p�QL��I�U��H�&>�K�CAxAb�����wwuѡ "�<q>��?(�k������w�#qm�|�8-��g'ū�QL�y\�Z'U���Fد����H�u��Q>�3��JI����))~^7P����&9���ze����1�u�>�΅=)�ZI0Fef��"b��u�������#D��y��W�I{�]����ż��*b��{	���u�B�mv��y�t�;!���VưLg;����gEZ(�*�ZA�-v��ԑ��&��]N���J����?������7      �      x������ � �      �   b  x�u�;nC1k�0ş�2H��q��} �m�	�;o߯�u�<����ԑ҅��n��#�d\���b��n�Yǝ1[:[:[:[:[:[:[[[[[[[[[[[&[&�$�$�NΝ�;9wr�����)�)�)�N�e�e�N�N�N�N�N�Ns�����i�i�Y��Xg��b���Yl�8��ܛsoν9��ܛs����"$yg����k����>�9��y���z�On�I'�����#�������N�Nm�D]�dS�p��)~�M\u��M�u���e7�S�vF��E'�)|EYs��\�5}�Eas��\U6��di����ަ��jn�e;��5��-���      �   �  x���An�0���)|P�$��J��QG��DF!������s�Yv�U����K:�fZ�~���#h(�ȸd8x��m�L�<LP�����X����h������7����k?>!��I�1[�����gW�R�e��U�_��Q,9.i��wk��<R���J#�!�Ez���hw�,��Y�Oe&9�7`{U>���=�A̒��1�cS���v@ͨ��%ga�FL�� ���&��/�s�q���`�t)uP�9�ѕ�=�g��Fքe�';�¼,����W65{Ȓ�z^p9^�"�$�%�;��Hh��Ox���%/����E=4���GLn�ْ���
QB{&�:h�� �!O�h�`B<�z�fz�%PE�Ҵ�<Sie��	b�;��X�D����q�2���U�n���&� ։�-��\*@����|�@arE�=�_����*9�|��8��x�~����Ӥ.�&�E��]Lq�Yxͣ��ϧ~�C�I��0��#���Y�)|��(�Q��Z~Y�Qڤ=�,kUG&�@Rs�z�!��ޭ-�bi��>�Z��[i��8P�c����g�,�d��9$U�j_�A�"e�B���諎8n�f@?�Qu�^`�����l_�I!��l%U|41|�4�I��̘V��-�nQ��gRaj��e���m�\K�{6r��	f���_Lgx6���߅�N��prV������1=�6i�C�0��	�      �      x������ � �            x������ � �      �   P   x�3�,��I���,.�,OM�4202�54�5�P04�2��2����2�(KM�ī��,�(5�$�B�ԜT�
c���� ��(�      �      x������ � �      �      x�3�4�2bc 6�=... c�      �   f   x�3�tL����,.)JL�/�,OM�4202�54�5�P04�2��2����2�t�K��LĢ�������1g@bQIfrfAb^Ij1�^+c+#clb\1z\\\ D�&�      �      x������ � �      �      x�}|˲�ڶmY�bNu#�G�6������č���y�[��O��]؅�zk�cw`�\���b&�k����[�ٲ��:ʜ��s)����9�����-��gt��0o�{?C���Qf��������QKC�m���+��_LwU��ΐ��~U[���R
�����yu-��L�����:ޭ\������Y_�ڇi¢�|��\Їƚ]�	���_�
��?����`�J���
��7��sFfBS�N�y6|�W���A�{�,tB :����h��0�a%b5p���}o,���OKC��R��y�«n������~�A����`��Ag|�{^�a��3�?̂�i��,�ө�!�+��$Պ���`\iB	�I[j�G��]�����X�#lf8��X��(g�<�R9e:>OR�u	3�Eh'<����s��.�]MG��<�C����L+YFd�
��%xS?I�`>�������c�^��)�l�:N� h�Uɭ��������Ԥ��Eηw"��]l�e|vZ{��-�?X!��Xa8`E�t'�A2�U��J'|>LRp�-Q �YW��:��lHh�vEaBB�[:{`1ܞ�z�8A_9�ș�7��W�S�y�j������=&��Ȋ0"����?:�w�W��G�ģB� ��-Y���b�
1�5S+@�z�+O�Y^�ϗ���ޛ�����K�rF���7�f��R�K�$�a��kt�@G�&��A��r�l����OB�q�8��8��L#w��S�x6t:yd�"Pd��m���`tq\>.�m�I�� d��g�eL��*O�,�?�$�3��tI�j���$(�H�!�t��|Ӽ�+��!�9C�j�����:��.�8H[gw\Od�2���N��+'�E��l�'��$e'�6d.6��_���\XJ��k8[
�V��[�ֽ�r�/"K!҃5�� d8��9`Q�·%��\��/K?*��Y�tZ_B�8HJL.���Q,��n�"kb\R�hm_� ������X�r�D&^�蠳U�yڔN��<'��|���K�����
_-"�����媳�*���۵0C�/����$�����`3=�����F��U�[��8\e���Nto��� U͛�*%����;�khM��.6��L�z�d��������D.>��ό(iǉ�� }_��_�����z�u�M6��q+<֐�ಯ�Y�)���{E9}�n���c�)6�8:Č�����8����߽?���z�5*-iw|�b��5�:>d���t[����y��+z;ޟa�T���2��N�����A�_�Q��
A��3����C0{UK��;K��l��޼�]˧C���%�˷�5,����I���A�����q>9ȡjV�a�gH��!�߰�Qʣu�?�VŜ$V�"�_��Ӂ��K��Q��:�]<�I�	ƿ�,"�_nJ.a�y�����Ôf0P�����W}u�i� �uDTERNy���f�Շڄ}��b@�x��|�~���4�K����?����r��O*�����]6�U�lbu�ȡm�-����Me�2���Ƹ:[Ƙ]`8�̠�l�IT����ȣ�K�W�b;I��Ͱ�Ep��Jw���U��]uu��K
^�u	�Y>&����ĳ$7S\�P ��H��ݝ$5z�>��	L�č�����*�#
u�K�o�\���.Q.o�K3�q�3xi"h���C���=�2.=�Z�:ςO�>�iBEWڡ��Œ*Cf�KF��C�@���M���B�bS˫�Z��Dq"�a�8��X�@E2Ɛ�U.;#�ˍ&0�qY���hǇ�����6ZW��;`-Vg�آm��(����3���̮�rܿy�5�!��V���*y�t�!p�BK�{��U/cX˂ơ��^�	QMd.� B�3A�`����~P�����K[06����+�|��F��+�\�4��ݺ;:	#z���uU&��#�_%�`�K�TN}��l��dP�GTl�ֹX��O�f�@G��]w��!��	TĻ㠫ށ�;qbz�o�8>�2j�pQ���/A�����Ohz�ܫr���W��q�ಌ�z�"4�;����L��,&�ǝv�D���c��E��!G@�A��K'��%/�~�:�dS��{���L�"�^.�Ǣ�n��r���h�ٖց��f)����d; ?�<[k�@�o�'�as�g�������)A��`&�IB0��}�� �d�l�m{�}<����$��R�7�.��>崒~�oQ ��a�2{RT�q�|G�7� ���zl�/���L÷L�da7��St:T�ŕH��M�)w�cY�ڞ��1��7K>ܷ��yÔ5��b�����0����:���m��g�����@>��q%-`UW-�j�s�ww6"2���e����"�!4E��f�J�������\�^�mc�yy	ܼS�NԳ}>�3L�(y���P��\m8f9Q�.�`M�]�i����X�ɨ�eu���JA��b��fh���F��L���0���:$�ݵ��^����p�*��
�q��7wJb��~X��k��ň���e1:ol����o]Yڛ�U�ɣ���٦Y���f���)�k�P�y��'������c��̶���H�YܸA�؟��������GìQԈ����n}#g����	&�ly�oF`������3�i���|�ͩ��k�L�j���qr�/���(h�ry�	݊6Ѹ$]����겣��i���=��E�xl�r�����lN����V��h���j��Ęz��Y����bX��R�����b��m΋��3<Į��J�?�J!�3?g�X}�7h&.�%
�O�����s������ǵ�j��`��!�.�.�4�k�Ϲ��5��#�p���[(��+����Ί�����as����a4�����?ȡ��-����߅��첋X��o�ȃ�;k�g���.�:/�]�Z�A���u�;l��ճꭓh Ms�!D��g`?۴?�b�T��j��$�ހ�G�n�jy0�������ǭ��D�0�����*A��栅[�π\:U]�L2>I���c:��O����6*���P�a�[�v.�M�+���n;܆�ߑ���_ȍ���'��.�����$��N�A��$Zbk�+-6�+��G�u:mi�����Ԡ���)r���i3��n��A{��Ag�;�(O�%ieؼ�#9��P��f]������lۦ�+U�ma_(�ަ���V�#��
�[v8���J�m�p����^j���yt��S�7���(ک{(BءQ�}���n�ss@0��D��7���}����3�v~���Z1p�=�Xz����x�Z�*����Қ����[nR2����D �J5�}3t�F�a�e�k�p���B�'9f��z�޷��q[Ġ��k��
�)FA�qM�Qo㶠����#�O�t����À���h�Aa���߭���O{��"o����f[$Y߷�ؠ�dw'�^`!���{�A���u��>m�wOl�V4�?<�/+�̓���^�A&yqB���b���t����a��V�P���J���z9����5����7P��`�����t9�U潴�Px��z��C��LeMy�u~q�,��Q»q��x��A�+����KJ���p��ӰZ��B7<�'���U���3,��F[g=ͅF���"�̹��{� �5�J���V��2L��t��u�#���gt�"+�/��QZY"|:�}��GKDD���Qޘ�o�_�w������;��|���A4'n��ggCl�
=om���Qhpj,��e�<�F|�{S1 �U����u웋S�/� ��P�H�}8Z[a�+�o����[��p
U2��c�δ1䊎����{o�6G��XŢp��>�9q��r�LW������׌�8SGg8y�3�M��i[���P�rs�3'�    �s�^_�*`À��6��?��/����MW��}T�u�{��tv�r���kܛ[b[Վ��-���H里l�ot@_���������[�/���b$[}�Ĳ�{�B':�1P}*aCG��[��~���ݒ��٫ꛊ�b_+��c�l��0�?��4�ӥB6�Ѱ0IQW]>Х,G�qg� s��8��jy�����S7o&ſ:�'6�0`��k����'��Ґ�y��[!�D႘�q'ߡ*�d��!uȕJ_�-��"jC�p��R������o
r4z2t�*���<�ʻ$A&QwQ��,��N~����(�0��3�Q7LP�!�}�"�4��sYͩK�����xa7]d5���b�������7�▫d�ٷ�D��c]u�^��V���4�e����怴�'U�����'��°I��c��V#VE�IiH��Ko��D�����ڒ��5J®Uo
J5�Ol�Q?�CM���:n}����MW�O�'�U�@t޽��^µ �Q
s�?m;�3�|' ���:�'6��ُ�$ ���7h��6]2_�z��S.[-l���/VKt�j��9�P���b�9n�	b��ra�������ӵsc+����z��"q��mV�P�s	1�M9��o?X��Z�B끷]�������p?�ś��yz����i �n�5�����t��[�,`j�ʸ���Г�u�ޗ�k8�(��c�Ɓ��,�F� �U��GgB�xQ5e��i5v���;��\�&��,<	�����c�A���fX���W"�x<������:Q�O�.6��7��&�6"�a��v˅ҡY�,�1��[�{��Y�:�C�K��1'�~5�Ol�3%J<�m�bӊ��Jj�������(�~<��d���Q�-��K�r��j��j,�x��147�9>.�5��?���WO�MK�V�u�.��J��C��*��'2)��D��)�N7=t�s���%��2�#6a�&�Z���5O�j�b�wT�M�&7Ըm�S��Z�`>����eD�>�Z}�k�у��7O	�.ڨ�j���8�SPVy����ށ�s��ћּ�ܞ�Đ��ح�8�ٔܨ�ewDV���&V��>/K�Aɻv]�o�@�k�R�lef,к��T�N#,)/>X�B�
Ѻ�t-�SӶ>�A���"���=C1�O�a*ëIybsdk��o�b'��m�i�)���$
��s̹���~����H��!�a��_��sCV�!a�ޟѝJ�7�qz9�*q%#z��M��Z��4s5���`Nk������D��W	vy]qF^ۏOl�sw�s�9����Q��!.��v����[�i�BǶ��^q�A+0�����G�*��٦��P]�ݪu�a��&����R�6fg��o�ewVBlŸ����7��uX�w�Ag�_GH;����q��[��g�����k�D�G�|�/���
n�m|3\�d��^;�/�R¢�9R�fwmo)Z{�q:V�\;\�4`�[�q�&;��ǙAw,lsi,u�g�j�B��k�%�`���߰9п�m�&��r\�)�h|���(�Up��h�^����b� åK��tˇ�-��Fg��S�[#�f��t [ؖ�����hMQa>*�@U�6r{��7�;ɮ�=ö��w^ckܖ~��q�s	�S��2�q�"8��l�N颓^�n�ŉd�#�:d��xԴ�p�7�Ѣ�)G�z����R��桙qs'G��#��>~���.xp��՞�9��0��C�����:�*�U�X�h�a�z�e������Ɨb���^�C��QPD�x<����xꢚ�}Q�r��V˵ѧ���%�E�}t��1�HԮ��:lKY���T!�aF~�a��X��,:J�NѻM{6,	ڽ���B�$����f�e����!K/;���؜���u���Y���i������� Q��"�Ⱥ$l�-�.V�~,�MǮ�lw)�.�z��_0�@f��Vl�De��^ME��O5��!b�ؠi�d�`b��w$��}aD`(C�1@��*�����sf���ɢ�u�h��.p�i��M��ޅ�_q�ź-�*���
K�e�\�׏�����c8�u���	�ǉ�1� ��	ŧ-�`��֭Qei&˻�֧x�ǬIý^w�%��������Q��M��7����
�8n䦗��Qڷ�͞���|_�[��O@|�� @R���Z�7��qK�*>o�>`}��	bf��x��^�7���o�[��N��qqF�F�5cC�W�"+��`��Y4�i[4�k�W����]{�Ym?h�_�M�+�{-��Y���!q�7����0���q�^�3N�W�$�xG���+=�9��~P��ȯ_`ڳ��ˋ�R�M9�IV�C���"��`��ZAn��\��o����V�o\���o�CN�'��$7mY�~PZ�k�!�-�/?��\t�:d���B����i��K�}ӎ���|bs�����㩇j�r{[6�{,I�ξ�l��eP�/�w�q�H^(�c���>�yw%]�M�	��xbs����g�m����k�No@K�y?#��v�8`�U?@{��Dÿ2�� aM*߱����K�ݻ�5��8bs���".1-��h�I��Oն�k�����7-O6�}�	Y���Ýɯo"����=�99^��$�X-%�5��z�#���@Bd1f�Ȯ�0i��J��������y�����f���?���$��5��iu^VԈim;���º���l.I.�|w��.A���2~l���yc]�+O���$�ǛEf����v�OlN����4��2�~�4 �n��>S>j
ٶ��ć��6CCU��;a⪋���x�2
�4YH�i��@�o؜�f����j��U�r�k��˗U�*e\&�wf]�]x��6͙������|~�$W���`�,��7�˴�؜����@N$~���o�K�!$�T�2�Ȅ�tK�m��W�.��˳�/n��¨Al�怨o�_���؜dfrU�|��ش��D{�[J̰gx����ڭmT�՞�ɍ��PQ��HчM�?�oA��OlN��Ⱦ�0�ו*bZ��Q�!�۫|	I�l?F��BRȒ�Uͻ�Ə�������eq�*�z���S������m¦����R��_���LY\��v���XR̞Q5�G�L$�l\>#��,E��mʉ/%�I�ee��SO�'�Ɖ����a�e�dcN�8���BFx��i��.c=���S����_,�<�����o�w���}��~�Z�?"��$ŐYa�8�ַ�:Jd/�&�\�	R��裬*K�\3t�N .��}�7bs
��������/M9�{�^Jj.d�}R��"�����I�ZDb��� .C0����ɮ\��z=����)bf~��[����:/%��.;4ުG�/8��̍��"9��8>^�lָ��a#ăE�]�Q�b����_���{<�:�I���̽~��i�3�z��e4d�rh�$��nۼ�֛%�-��8U/ێ�/)#_�3���J�y�d~��+�����_�Ӣ��P�3��*>�N�G؜���e�B�+]��5��C��!��j��ї֋�G�;lN���o��f���/z]F{�ċJ�Ht���]��9�*�U��2��"cێ�����k;��i������<oD���b=9-}ȉ�>�>��QZ��^x�5��(���ծ�Vd�*�����ȕl�K�)�Z�OlN�3s<R+�\^Ӄ�V�f����&=\��x�}�`en�жeiHn��+��V��0�7�X��;lNc�a����|=#EM���Y\/Oف��ڨJ�9>K��>��vׇ���Ӿ�Ց�%?��7z<~E9�9�ϸ�CَE��/Ԧ����R픆���W�Ͱ�E����'�5��nNA~��m��ڲ�lC>i|͇��ݕНQ��V$�i�Ƣ��ө��[���p���$)i��a(VI������2�;���>b<.O�:/���x]ڙ��i�����lE�*��!�H2>�~ʭ��Zh�\�œi �  ��V����3��ޕ࠘�F�LQ�.��:
�F�9����j��;De!ww��M!�C��{D��Q^�aK�բ��S��������(��&~���o��~���p|=���M���gt!+|+q.�,���TL�A��dˈMd��=F���<�?��/lN��4��3`6ơ��"������/J�������=��"�9�L�����F/�$��mJv�-���z�`�_ib���/lN�?o��c7,����������m-��@�A�ڊ�r��tr��9>���W~��*�cqL6ΛA�@���9��e]��l�����أ���k/d�%B��y�b4�9_����#үa��m� �6���؞���w���l�as�\ZB8nDǠ~�y�����@���n��M��~�L�����a��Cd8�ȣ��6��;������"����93n��H�g'�!��;пԩ��d!t's�>Yw���)Z� -/�?��~�4B��TYl�."�v��WR�x�e�as�}~o��dϯ}�ݜ2�?�&h'{X��G�B�Y�'���}k��6ݜv]��$�s��q}���W����ߎ������i�s��P�Ս�Ȉ
�B��!{̴}S��ۺ��6��jX_�j�������1��H���O��1����i���ӝ���K{��m�о�U���pc�I�h�.%�L�z�WR�(��o������u��\@ �B5-(��o�x��{��Zx����Ji�_
GD���v�ݟ��/������\O>����U�Q��pn+PR�����~���r��a��np����)��kq��[�*����a���*��/�D{v�]b�0��sIcs:�q��G��u�z4��7�>���I�C�Jg�^7iA2�9�<p����:�����3��a�h�5tI/��g����(�C$�v;	�/u�����f/~6�夳rg{��:78��(�6:�]��ړ���+E8�J�����*e�R$w���` ���;lN�?'��Z�v'j������:?~9mq"g�/Ѵ�m�������*�+^EX�n;�x�`�a�'�M�A_��q��iΐ�4>����F������ZZ��j԰>�8�U0�k^?L��$ձ�ŏ�j��כq�U�:y�sMSi��������A>D����y�xU�&c+���#��M(�1~7����߰9�y(Ts.I�a�e;&n����v���=�ܥ�V��5����6'��=c]I(���֮���dVG�P�\�ƙ�{�߰9�W��������e�2����4CMZ/ۛD2��$��9�C��2��*j![�����n�i���_�Z�[_�6�6QyPD�!�@&�тW��W�:њ���a��t-c�����M\S���ѥ��]�g;�T�}Q��w����َ߾� &ǜ�?�|>���I     