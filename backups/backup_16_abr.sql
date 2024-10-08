PGDMP                         |           siplan %   12.18 (Ubuntu 12.18-0ubuntu0.20.04.1) %   12.18 (Ubuntu 12.18-0ubuntu0.20.04.1) �   �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    30267    siplan    DATABASE     x   CREATE DATABASE siplan WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'en_US.UTF-8' LC_CTYPE = 'en_US.UTF-8';
    DROP DATABASE siplan;
                postgres    false                        2615    44824    estadistica    SCHEMA        CREATE SCHEMA estadistica;
    DROP SCHEMA estadistica;
                postgres    false                        2615    44825    planificacion    SCHEMA        CREATE SCHEMA planificacion;
    DROP SCHEMA planificacion;
                postgres    false                        2615    44826    proyecto    SCHEMA        CREATE SCHEMA proyecto;
    DROP SCHEMA proyecto;
                postgres    false                        3079    44827 	   uuid-ossp 	   EXTENSION     ?   CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;
    DROP EXTENSION "uuid-ossp";
                   false                        0    0    EXTENSION "uuid-ossp"    COMMENT     W   COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';
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
       estadistica          postgres    false    206    6                       0    0     formulario_clasificadores_id_seq    SEQUENCE OWNED BY     o   ALTER SEQUENCE estadistica.formulario_clasificadores_id_seq OWNED BY estadistica.formulario_clasificadores.id;
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
       estadistica          postgres    false    6    208                       0    0 *   formulario_formulario_has_variables_id_seq    SEQUENCE OWNED BY     �   ALTER SEQUENCE estadistica.formulario_formulario_has_variables_id_seq OWNED BY estadistica.formulario_formulario_has_variables.id;
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
       estadistica          postgres    false    210    6                       0    0    formulario_formularios_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE estadistica.formulario_formularios_id_seq OWNED BY estadistica.formulario_formularios.id;
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
       estadistica          postgres    false    212    6                       0    0    formulario_items_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE estadistica.formulario_items_id_seq OWNED BY estadistica.formulario_items.id;
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
       estadistica          postgres    false    214    6                       0    0    formulario_variables_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE estadistica.formulario_variables_id_seq OWNED BY estadistica.formulario_variables.id;
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
       planificacion          postgres    false    7    216                       0    0    foda_analisis_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE planificacion.foda_analisis_id_seq OWNED BY planificacion.foda_analisis.id;
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
       planificacion          postgres    false    7    219                       0    0    foda_cruce_ambientes_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE planificacion.foda_cruce_ambientes_id_seq OWNED BY planificacion.foda_cruce_ambientes.id;
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
       planificacion          postgres    false    7    225                       0    0    foda_groups_has_perfiles_id_seq    SEQUENCE OWNED BY     q   ALTER SEQUENCE planificacion.foda_groups_has_perfiles_id_seq OWNED BY planificacion.foda_groups_has_perfiles.id;
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
       planificacion          postgres    false    7    227            	           0    0    foda_models_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE planificacion.foda_models_id_seq OWNED BY planificacion.foda_models.id;
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
       planificacion          postgres    false    231    7            
           0    0 $   pei_profiles_has_dependencies_id_seq    SEQUENCE OWNED BY     {   ALTER SEQUENCE planificacion.pei_profiles_has_dependencies_id_seq OWNED BY planificacion.pei_profiles_has_dependencies.id;
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
       planificacion          postgres    false    233    7                       0    0 "   pei_profiles_has_strategies_id_seq    SEQUENCE OWNED BY     w   ALTER SEQUENCE planificacion.pei_profiles_has_strategies_id_seq OWNED BY planificacion.pei_profiles_has_strategies.id;
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
       planificacion          postgres    false    7    235                       0    0 !   peis_profiles_has_analysts_id_seq    SEQUENCE OWNED BY     u   ALTER SEQUENCE planificacion.peis_profiles_has_analysts_id_seq OWNED BY planificacion.peis_profiles_has_analysts.id;
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
       planificacion          postgres    false    7    237                       0    0 %   peis_profiles_has_responsibles_id_seq    SEQUENCE OWNED BY     }   ALTER SEQUENCE planificacion.peis_profiles_has_responsibles_id_seq OWNED BY planificacion.peis_profiles_has_responsibles.id;
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
       planificacion          postgres    false    240    7                       0    0    tasks_has_analysts_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE planificacion.tasks_has_analysts_id_seq OWNED BY planificacion.tasks_has_analysts.id;
          planificacion          postgres    false    241            �            1259    44956    tasks_has_type_tasks    TABLE       CREATE TABLE planificacion.tasks_has_type_tasks (
    id bigint NOT NULL,
    task_id integer NOT NULL,
    type_task_id integer NOT NULL,
    status integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE planificacion.tasks_has_type_tasks;
       planificacion         heap    postgres    false    7            �            1259    44960    tasks_has_type_tasks_id_seq    SEQUENCE     �   CREATE SEQUENCE planificacion.tasks_has_type_tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE planificacion.tasks_has_type_tasks_id_seq;
       planificacion          postgres    false    7    242                       0    0    tasks_has_type_tasks_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE planificacion.tasks_has_type_tasks_id_seq OWNED BY planificacion.tasks_has_type_tasks.id;
          planificacion          postgres    false    243            �            1259    44962    tasks_id_seq    SEQUENCE     |   CREATE SEQUENCE planificacion.tasks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE planificacion.tasks_id_seq;
       planificacion          postgres    false    239    7                       0    0    tasks_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE planificacion.tasks_id_seq OWNED BY planificacion.tasks.id;
          planificacion          postgres    false    244            �            1259    44964 	   typetasks    TABLE     #  CREATE TABLE planificacion.typetasks (
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
       planificacion          postgres    false    7    245                       0    0    typetasks_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE planificacion.typetasks_id_seq OWNED BY planificacion.typetasks.id;
          planificacion          postgres    false    246            �            1259    44969    e_p_c_equipamientos    TABLE       CREATE TABLE proyecto.e_p_c_equipamientos (
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
       proyecto          postgres    false    247    8                       0    0    e_p_c_equipamientos_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE proyecto.e_p_c_equipamientos_id_seq OWNED BY proyecto.e_p_c_equipamientos.id;
          proyecto          postgres    false    248            �            1259    44974    e_p_c_equipamientos_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_equipamientos_servicios (
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
       proyecto          postgres    false    250    8                       0    0    e_p_c_especialidads_id_seq    SEQUENCE OWNED BY     ]   ALTER SEQUENCE proyecto.e_p_c_especialidads_id_seq OWNED BY proyecto.e_p_c_especialidads.id;
          proyecto          postgres    false    251            �            1259    44985    e_p_c_estandars    TABLE       CREATE TABLE proyecto.e_p_c_estandars (
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
       proyecto          postgres    false    8    252                       0    0    e_p_c_estandars_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE proyecto.e_p_c_estandars_id_seq OWNED BY proyecto.e_p_c_estandars.id;
          proyecto          postgres    false    253            �            1259    44993    e_p_c_estandars_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_estandars_servicios (
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
       proyecto         heap    postgres    false    8                        1259    45002    e_p_c_horarios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_horarios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE proyecto.e_p_c_horarios_id_seq;
       proyecto          postgres    false    255    8                       0    0    e_p_c_horarios_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE proyecto.e_p_c_horarios_id_seq OWNED BY proyecto.e_p_c_horarios.id;
          proyecto          postgres    false    256                       1259    45004    e_p_c_infraestructura_servicio    TABLE     �   CREATE TABLE proyecto.e_p_c_infraestructura_servicio (
    servicio_id integer NOT NULL,
    infraestructura_id integer NOT NULL,
    cantidad integer NOT NULL
);
 4   DROP TABLE proyecto.e_p_c_infraestructura_servicio;
       proyecto         heap    postgres    false    8                       1259    45007    e_p_c_infraestructuras    TABLE     J  CREATE TABLE proyecto.e_p_c_infraestructuras (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    measurement double precision NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 ,   DROP TABLE proyecto.e_p_c_infraestructuras;
       proyecto         heap    postgres    false    8                       1259    45010    e_p_c_infraestructuras_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_infraestructuras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE proyecto.e_p_c_infraestructuras_id_seq;
       proyecto          postgres    false    258    8                       0    0    e_p_c_infraestructuras_id_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE proyecto.e_p_c_infraestructuras_id_seq OWNED BY proyecto.e_p_c_infraestructuras.id;
          proyecto          postgres    false    259                       1259    45012    e_p_c_medicamento_insumos    TABLE     "  CREATE TABLE proyecto.e_p_c_medicamento_insumos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 /   DROP TABLE proyecto.e_p_c_medicamento_insumos;
       proyecto         heap    postgres    false    8                       1259    45015     e_p_c_medicamento_insumos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 9   DROP SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq;
       proyecto          postgres    false    260    8                       0    0     e_p_c_medicamento_insumos_id_seq    SEQUENCE OWNED BY     i   ALTER SEQUENCE proyecto.e_p_c_medicamento_insumos_id_seq OWNED BY proyecto.e_p_c_medicamento_insumos.id;
          proyecto          postgres    false    261                       1259    45017    e_p_c_otro_servicios    TABLE       CREATE TABLE proyecto.e_p_c_otro_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 *   DROP TABLE proyecto.e_p_c_otro_servicios;
       proyecto         heap    postgres    false    8                       1259    45020    e_p_c_otro_servicios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_otro_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 4   DROP SEQUENCE proyecto.e_p_c_otro_servicios_id_seq;
       proyecto          postgres    false    262    8                       0    0    e_p_c_otro_servicios_id_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE proyecto.e_p_c_otro_servicios_id_seq OWNED BY proyecto.e_p_c_otro_servicios.id;
          proyecto          postgres    false    263                       1259    45022    e_p_c_prestaciones    TABLE     ;  CREATE TABLE proyecto.e_p_c_prestaciones (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    detail text NOT NULL,
    cost character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 (   DROP TABLE proyecto.e_p_c_prestaciones;
       proyecto         heap    postgres    false    8            	           1259    45028    e_p_c_prestaciones_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_prestaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE proyecto.e_p_c_prestaciones_id_seq;
       proyecto          postgres    false    8    264                       0    0    e_p_c_prestaciones_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE proyecto.e_p_c_prestaciones_id_seq OWNED BY proyecto.e_p_c_prestaciones.id;
          proyecto          postgres    false    265            
           1259    45030    e_p_c_servicios    TABLE       CREATE TABLE proyecto.e_p_c_servicios (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 %   DROP TABLE proyecto.e_p_c_servicios;
       proyecto         heap    postgres    false    8                       1259    45036    e_p_c_servicios_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE proyecto.e_p_c_servicios_id_seq;
       proyecto          postgres    false    266    8                       0    0    e_p_c_servicios_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE proyecto.e_p_c_servicios_id_seq OWNED BY proyecto.e_p_c_servicios.id;
          proyecto          postgres    false    267                       1259    45038    e_p_c_talento_humanos    TABLE     I  CREATE TABLE proyecto.e_p_c_talento_humanos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    hours character varying(191) NOT NULL,
    type character varying(191) NOT NULL,
    cost double precision NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 +   DROP TABLE proyecto.e_p_c_talento_humanos;
       proyecto         heap    postgres    false    8                       1259    45044    e_p_c_talento_humanos_id_seq    SEQUENCE     �   CREATE SEQUENCE proyecto.e_p_c_talento_humanos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE proyecto.e_p_c_talento_humanos_id_seq;
       proyecto          postgres    false    8    268                       0    0    e_p_c_talento_humanos_id_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE proyecto.e_p_c_talento_humanos_id_seq OWNED BY proyecto.e_p_c_talento_humanos.id;
          proyecto          postgres    false    269                       1259    45046    e_p_c_tthhs_servicios    TABLE     �   CREATE TABLE proyecto.e_p_c_tthhs_servicios (
    servicio_id integer NOT NULL,
    tthh_id integer NOT NULL,
    cantidad integer NOT NULL
);
 +   DROP TABLE proyecto.e_p_c_tthhs_servicios;
       proyecto         heap    postgres    false    8                       1259    45049    e_p_c_turnos    TABLE     �   CREATE TABLE proyecto.e_p_c_turnos (
    id bigint NOT NULL,
    item character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 "   DROP TABLE proyecto.e_p_c_turnos;
       proyecto         heap    postgres    false    8                       1259    45052    e_p_c_turnos_id_seq    SEQUENCE     ~   CREATE SEQUENCE proyecto.e_p_c_turnos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE proyecto.e_p_c_turnos_id_seq;
       proyecto          postgres    false    271    8                       0    0    e_p_c_turnos_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE proyecto.e_p_c_turnos_id_seq OWNED BY proyecto.e_p_c_turnos.id;
          proyecto          postgres    false    272                       1259    45054    otroservicio_has_servicios    TABLE     �   CREATE TABLE proyecto.otroservicio_has_servicios (
    servicio_id integer NOT NULL,
    otro_servicio_id integer NOT NULL,
    cantidad integer NOT NULL
);
 0   DROP TABLE proyecto.otroservicio_has_servicios;
       proyecto         heap    postgres    false    8                       1259    45057 
   categories    TABLE     �   CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(128) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.categories;
       public         heap    postgres    false                       1259    45060    categories_id_seq    SEQUENCE     z   CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.categories_id_seq;
       public          postgres    false    274                       0    0    categories_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;
          public          postgres    false    275                       1259    45062    groups    TABLE        CREATE TABLE public.groups (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    _lft integer DEFAULT 0 NOT NULL,
    _rgt integer DEFAULT 0 NOT NULL,
    parent_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.groups;
       public         heap    postgres    false                       1259    45067    groups_has_members    TABLE     �   CREATE TABLE public.groups_has_members (
    id bigint NOT NULL,
    group_id integer NOT NULL,
    user_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
 &   DROP TABLE public.groups_has_members;
       public         heap    postgres    false                       1259    45070    groups_has_members_id_seq    SEQUENCE     �   CREATE SEQUENCE public.groups_has_members_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.groups_has_members_id_seq;
       public          postgres    false    277                       0    0    groups_has_members_id_seq    SEQUENCE OWNED BY     W   ALTER SEQUENCE public.groups_has_members_id_seq OWNED BY public.groups_has_members.id;
          public          postgres    false    278                       1259    45072    groups_id_seq    SEQUENCE     v   CREATE SEQUENCE public.groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.groups_id_seq;
       public          postgres    false    276                       0    0    groups_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.groups_id_seq OWNED BY public.groups.id;
          public          postgres    false    279                       1259    45074 
   migrations    TABLE     �   CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(191) NOT NULL,
    batch integer NOT NULL
);
    DROP TABLE public.migrations;
       public         heap    postgres    false                       1259    45077    migrations_id_seq    SEQUENCE     �   CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.migrations_id_seq;
       public          postgres    false    280                        0    0    migrations_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;
          public          postgres    false    281                       1259    45079    model_has_permissions    TABLE     �   CREATE TABLE public.model_has_permissions (
    permission_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);
 )   DROP TABLE public.model_has_permissions;
       public         heap    postgres    false                       1259    45082    model_has_roles    TABLE     �   CREATE TABLE public.model_has_roles (
    role_id integer NOT NULL,
    model_type character varying(191) NOT NULL,
    model_id bigint NOT NULL
);
 #   DROP TABLE public.model_has_roles;
       public         heap    postgres    false                       1259    45085    organigramas    TABLE     �  CREATE TABLE public.organigramas (
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
       public         heap    postgres    false                       1259    45093    organigramas_id_seq    SEQUENCE     |   CREATE SEQUENCE public.organigramas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.organigramas_id_seq;
       public          postgres    false    284            !           0    0    organigramas_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.organigramas_id_seq OWNED BY public.organigramas.id;
          public          postgres    false    285                       1259    45095    password_resets    TABLE     �   CREATE TABLE public.password_resets (
    email character varying(191) NOT NULL,
    token character varying(191) NOT NULL,
    created_at timestamp(0) without time zone
);
 #   DROP TABLE public.password_resets;
       public         heap    postgres    false                       1259    45098    permissions    TABLE     �   CREATE TABLE public.permissions (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.permissions;
       public         heap    postgres    false                        1259    45101    permissions_id_seq    SEQUENCE     �   CREATE SEQUENCE public.permissions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.permissions_id_seq;
       public          postgres    false    287            "           0    0    permissions_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;
          public          postgres    false    288            !           1259    45103    risks    TABLE     �   CREATE TABLE public.risks (
    id bigint NOT NULL,
    name character varying(191) NOT NULL,
    detail character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.risks;
       public         heap    postgres    false            "           1259    45106    risks_id_seq    SEQUENCE     u   CREATE SEQUENCE public.risks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.risks_id_seq;
       public          postgres    false    289            #           0    0    risks_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.risks_id_seq OWNED BY public.risks.id;
          public          postgres    false    290            #           1259    45108    role_has_permissions    TABLE     o   CREATE TABLE public.role_has_permissions (
    permission_id integer NOT NULL,
    role_id integer NOT NULL
);
 (   DROP TABLE public.role_has_permissions;
       public         heap    postgres    false            $           1259    45111    roles    TABLE     �   CREATE TABLE public.roles (
    id integer NOT NULL,
    name character varying(191) NOT NULL,
    guard_name character varying(191) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
    DROP TABLE public.roles;
       public         heap    postgres    false            %           1259    45114    roles_id_seq    SEQUENCE     �   CREATE SEQUENCE public.roles_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.roles_id_seq;
       public          postgres    false    292            $           0    0    roles_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;
          public          postgres    false    293            &           1259    45116 	   servicios    TABLE     b  CREATE TABLE public.servicios (
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
       public         heap    postgres    false            '           1259    45121    servicios_id_seq    SEQUENCE     y   CREATE SEQUENCE public.servicios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.servicios_id_seq;
       public          postgres    false    294            %           0    0    servicios_id_seq    SEQUENCE OWNED BY     E   ALTER SEQUENCE public.servicios_id_seq OWNED BY public.servicios.id;
          public          postgres    false    295            (           1259    45123    users    TABLE     �  CREATE TABLE public.users (
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
       public         heap    postgres    false            )           1259    45129    users_id_seq    SEQUENCE     u   CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public          postgres    false    296            &           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public          postgres    false    297            G           2604    45131    formulario_clasificadores id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_clasificadores ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_clasificadores_id_seq'::regclass);
 P   ALTER TABLE estadistica.formulario_clasificadores ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    207    206            I           2604    45132 &   formulario_formulario_has_variables id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formulario_has_variables_id_seq'::regclass);
 Z   ALTER TABLE estadistica.formulario_formulario_has_variables ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    209    208            J           2604    45133    formulario_formularios id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_formularios ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_formularios_id_seq'::regclass);
 M   ALTER TABLE estadistica.formulario_formularios ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    211    210            K           2604    45134    formulario_items id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_items ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_items_id_seq'::regclass);
 G   ALTER TABLE estadistica.formulario_items ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    213    212            N           2604    45135    formulario_variables id    DEFAULT     �   ALTER TABLE ONLY estadistica.formulario_variables ALTER COLUMN id SET DEFAULT nextval('estadistica.formulario_variables_id_seq'::regclass);
 K   ALTER TABLE estadistica.formulario_variables ALTER COLUMN id DROP DEFAULT;
       estadistica          postgres    false    215    214            O           2604    45136    foda_analisis id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_analisis ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_analisis_id_seq'::regclass);
 F   ALTER TABLE planificacion.foda_analisis ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    217    216            Q           2604    45137    foda_cruce_ambientes id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_cruce_ambientes_id_seq'::regclass);
 M   ALTER TABLE planificacion.foda_cruce_ambientes ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    224    219            R           2604    45138    foda_groups_has_perfiles id    DEFAULT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_groups_has_perfiles_id_seq'::regclass);
 Q   ALTER TABLE planificacion.foda_groups_has_perfiles ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    226    225            U           2604    45139    foda_models id    DEFAULT     ~   ALTER TABLE ONLY planificacion.foda_models ALTER COLUMN id SET DEFAULT nextval('planificacion.foda_models_id_seq'::regclass);
 D   ALTER TABLE planificacion.foda_models ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    228    227            X           2604    45140     pei_profiles_has_dependencies id    DEFAULT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_dependencies_id_seq'::regclass);
 V   ALTER TABLE planificacion.pei_profiles_has_dependencies ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    232    231            Y           2604    45141    pei_profiles_has_strategies id    DEFAULT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies ALTER COLUMN id SET DEFAULT nextval('planificacion.pei_profiles_has_strategies_id_seq'::regclass);
 T   ALTER TABLE planificacion.pei_profiles_has_strategies ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    234    233            Z           2604    45142    peis_profiles_has_analysts id    DEFAULT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_analysts_id_seq'::regclass);
 S   ALTER TABLE planificacion.peis_profiles_has_analysts ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    236    235            [           2604    45143 !   peis_profiles_has_responsibles id    DEFAULT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles ALTER COLUMN id SET DEFAULT nextval('planificacion.peis_profiles_has_responsibles_id_seq'::regclass);
 W   ALTER TABLE planificacion.peis_profiles_has_responsibles ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    238    237            ]           2604    45144    tasks id    DEFAULT     r   ALTER TABLE ONLY planificacion.tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_id_seq'::regclass);
 >   ALTER TABLE planificacion.tasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    244    239            ^           2604    45145    tasks_has_analysts id    DEFAULT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_analysts_id_seq'::regclass);
 K   ALTER TABLE planificacion.tasks_has_analysts ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    241    240            `           2604    45146    tasks_has_type_tasks id    DEFAULT     �   ALTER TABLE ONLY planificacion.tasks_has_type_tasks ALTER COLUMN id SET DEFAULT nextval('planificacion.tasks_has_type_tasks_id_seq'::regclass);
 M   ALTER TABLE planificacion.tasks_has_type_tasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    243    242            a           2604    45147    typetasks id    DEFAULT     z   ALTER TABLE ONLY planificacion.typetasks ALTER COLUMN id SET DEFAULT nextval('planificacion.typetasks_id_seq'::regclass);
 B   ALTER TABLE planificacion.typetasks ALTER COLUMN id DROP DEFAULT;
       planificacion          postgres    false    246    245            b           2604    45148    e_p_c_equipamientos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_equipamientos_id_seq'::regclass);
 G   ALTER TABLE proyecto.e_p_c_equipamientos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    248    247            c           2604    45149    e_p_c_especialidads id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_especialidads ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_especialidads_id_seq'::regclass);
 G   ALTER TABLE proyecto.e_p_c_especialidads ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    251    250            d           2604    45150    e_p_c_estandars id    DEFAULT     |   ALTER TABLE ONLY proyecto.e_p_c_estandars ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_estandars_id_seq'::regclass);
 C   ALTER TABLE proyecto.e_p_c_estandars ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    253    252            e           2604    45151    e_p_c_horarios id    DEFAULT     z   ALTER TABLE ONLY proyecto.e_p_c_horarios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_horarios_id_seq'::regclass);
 B   ALTER TABLE proyecto.e_p_c_horarios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    256    255            f           2604    45152    e_p_c_infraestructuras id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_infraestructuras_id_seq'::regclass);
 J   ALTER TABLE proyecto.e_p_c_infraestructuras ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    259    258            g           2604    45153    e_p_c_medicamento_insumos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_medicamento_insumos_id_seq'::regclass);
 M   ALTER TABLE proyecto.e_p_c_medicamento_insumos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    261    260            h           2604    45154    e_p_c_otro_servicios id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_otro_servicios_id_seq'::regclass);
 H   ALTER TABLE proyecto.e_p_c_otro_servicios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    263    262            i           2604    45155    e_p_c_prestaciones id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_prestaciones ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_prestaciones_id_seq'::regclass);
 F   ALTER TABLE proyecto.e_p_c_prestaciones ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    265    264            j           2604    45156    e_p_c_servicios id    DEFAULT     |   ALTER TABLE ONLY proyecto.e_p_c_servicios ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_servicios_id_seq'::regclass);
 C   ALTER TABLE proyecto.e_p_c_servicios ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    267    266            k           2604    45157    e_p_c_talento_humanos id    DEFAULT     �   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_talento_humanos_id_seq'::regclass);
 I   ALTER TABLE proyecto.e_p_c_talento_humanos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    269    268            l           2604    45158    e_p_c_turnos id    DEFAULT     v   ALTER TABLE ONLY proyecto.e_p_c_turnos ALTER COLUMN id SET DEFAULT nextval('proyecto.e_p_c_turnos_id_seq'::regclass);
 @   ALTER TABLE proyecto.e_p_c_turnos ALTER COLUMN id DROP DEFAULT;
       proyecto          postgres    false    272    271            m           2604    45159    categories id    DEFAULT     n   ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);
 <   ALTER TABLE public.categories ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    275    274            p           2604    45160 	   groups id    DEFAULT     f   ALTER TABLE ONLY public.groups ALTER COLUMN id SET DEFAULT nextval('public.groups_id_seq'::regclass);
 8   ALTER TABLE public.groups ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    279    276            q           2604    45161    groups_has_members id    DEFAULT     ~   ALTER TABLE ONLY public.groups_has_members ALTER COLUMN id SET DEFAULT nextval('public.groups_has_members_id_seq'::regclass);
 D   ALTER TABLE public.groups_has_members ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    278    277            r           2604    45162    migrations id    DEFAULT     n   ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);
 <   ALTER TABLE public.migrations ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    281    280            u           2604    45163    organigramas id    DEFAULT     r   ALTER TABLE ONLY public.organigramas ALTER COLUMN id SET DEFAULT nextval('public.organigramas_id_seq'::regclass);
 >   ALTER TABLE public.organigramas ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    285    284            v           2604    45164    permissions id    DEFAULT     p   ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);
 =   ALTER TABLE public.permissions ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    288    287            w           2604    45165    risks id    DEFAULT     d   ALTER TABLE ONLY public.risks ALTER COLUMN id SET DEFAULT nextval('public.risks_id_seq'::regclass);
 7   ALTER TABLE public.risks ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    290    289            x           2604    45166    roles id    DEFAULT     d   ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);
 7   ALTER TABLE public.roles ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    293    292            {           2604    45167    servicios id    DEFAULT     l   ALTER TABLE ONLY public.servicios ALTER COLUMN id SET DEFAULT nextval('public.servicios_id_seq'::regclass);
 ;   ALTER TABLE public.servicios ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    295    294            |           2604    45168    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public          postgres    false    297    296            �          0    44838    formulario_clasificadores 
   TABLE DATA           |   COPY estadistica.formulario_clasificadores (id, clasificador, clasificador_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    206   �0      �          0    44843 #   formulario_formulario_has_variables 
   TABLE DATA           �   COPY estadistica.formulario_formulario_has_variables (id, formulario_id, variable_id, selected, selected_variable_id, value, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    208   �0      �          0    44849    formulario_formularios 
   TABLE DATA           �   COPY estadistica.formulario_formularios (id, formulario, status, dependencia_emisor_id, dependencia_receptor_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    210   1      �          0    44854    formulario_items 
   TABLE DATA           r   COPY estadistica.formulario_items (id, item, questions, variable_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    212   71      �          0    44862    formulario_variables 
   TABLE DATA           {   COPY estadistica.formulario_variables (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
    estadistica          postgres    false    214   T1      �          0    44869    foda_analisis 
   TABLE DATA           �   COPY planificacion.foda_analisis (id, user_id, perfil_id, aspecto_id, tipo, ocurrencia, impacto, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    216   q1      �          0    44875    foda_categorias_has_perfil 
   TABLE DATA           k   COPY planificacion.foda_categorias_has_perfil (perfil_id, category_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    218   �G      �          0    44878    foda_cruce_ambientes 
   TABLE DATA           w   COPY planificacion.foda_cruce_ambientes (id, user_id, perfil_id, estrategia, tipo, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    219   �K      �          0    44884 !   foda_cruce_ambientes_has_amenazas 
   TABLE DATA           X   COPY planificacion.foda_cruce_ambientes_has_amenazas (cruce_id, amenaza_id) FROM stdin;
    planificacion          postgres    false    220   0O      �          0    44887 $   foda_cruce_ambientes_has_debilidades 
   TABLE DATA           ]   COPY planificacion.foda_cruce_ambientes_has_debilidades (cruce_id, debilidad_id) FROM stdin;
    planificacion          postgres    false    221   �O      �          0    44890 #   foda_cruce_ambientes_has_fortalezas 
   TABLE DATA           \   COPY planificacion.foda_cruce_ambientes_has_fortalezas (cruce_id, fortaleza_id) FROM stdin;
    planificacion          postgres    false    222   �O      �          0    44893 &   foda_cruce_ambientes_has_oportunidades 
   TABLE DATA           a   COPY planificacion.foda_cruce_ambientes_has_oportunidades (cruce_id, oportunidad_id) FROM stdin;
    planificacion          postgres    false    223   P      �          0    44898    foda_groups_has_perfiles 
   TABLE DATA           j   COPY planificacion.foda_groups_has_perfiles (id, perfil_id, group_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    225   SP      �          0    44903    foda_models 
   TABLE DATA           �   COPY planificacion.foda_models (id, type, name, owner, environment, description, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    227   pP      �          0    44913    foda_perfiles 
   TABLE DATA           �   COPY planificacion.foda_perfiles (id, name, context, type, model_id, group_id, dependency_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    229   \d      �          0    44919    pei_profiles 
   TABLE DATA           <  COPY planificacion.pei_profiles (id, type, level, mision, vision, "values", period, numerator, operator, denominator, goal, progress, group_id, _lft, _rgt, deleted_at, created_at, updated_at, dependency_id, name, action, indicator, baseline, target, parent_id, year_start, year_end, user_id, order_item) FROM stdin;
    planificacion          postgres    false    230   uh      �          0    44927    pei_profiles_has_dependencies 
   TABLE DATA           y   COPY planificacion.pei_profiles_has_dependencies (id, pei_profile_id, dependency_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    231   X�      �          0    44932    pei_profiles_has_strategies 
   TABLE DATA           q   COPY planificacion.pei_profiles_has_strategies (id, profile_id, strategy_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    233   u�      �          0    44937    peis_profiles_has_analysts 
   TABLE DATA           s   COPY planificacion.peis_profiles_has_analysts (id, pei_profile_id, analyst_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    235   E�      �          0    44942    peis_profiles_has_responsibles 
   TABLE DATA           w   COPY planificacion.peis_profiles_has_responsibles (id, profile_id, responsible_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    237   ��      �          0    44947    tasks 
   TABLE DATA           ]   COPY planificacion.tasks (id, group_id, details, status, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    239   p�      �          0    44951    tasks_has_analysts 
   TABLE DATA           d   COPY planificacion.tasks_has_analysts (id, task_id, analyst_id, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    240   	�      �          0    44956    tasks_has_type_tasks 
   TABLE DATA           p   COPY planificacion.tasks_has_type_tasks (id, task_id, type_task_id, status, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    242   ��      �          0    44964 	   typetasks 
   TABLE DATA           p   COPY planificacion.typetasks (id, name, typetaskable_id, typetaskable_type, created_at, updated_at) FROM stdin;
    planificacion          postgres    false    245   ��      �          0    44969    e_p_c_equipamientos 
   TABLE DATA           ]   COPY proyecto.e_p_c_equipamientos (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    247   �      �          0    44974    e_p_c_equipamientos_servicios 
   TABLE DATA           a   COPY proyecto.e_p_c_equipamientos_servicios (servicio_id, equipamiento_id, cantidad) FROM stdin;
    proyecto          postgres    false    249   0�      �          0    44977    e_p_c_especialidads 
   TABLE DATA           e   COPY proyecto.e_p_c_especialidads (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    250   M�      �          0    44985    e_p_c_estandars 
   TABLE DATA           [   COPY proyecto.e_p_c_estandars (id, item, type, detail, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    252   j�      �          0    44993    e_p_c_estandars_servicios 
   TABLE DATA           Y   COPY proyecto.e_p_c_estandars_servicios (estandar_id, servicio_id, cantidad) FROM stdin;
    proyecto          postgres    false    254   ��      �          0    44996    e_p_c_horarios 
   TABLE DATA           b   COPY proyecto.e_p_c_horarios (id, item, start_time, end_time, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    255   ��      �          0    45004    e_p_c_infraestructura_servicio 
   TABLE DATA           e   COPY proyecto.e_p_c_infraestructura_servicio (servicio_id, infraestructura_id, cantidad) FROM stdin;
    proyecto          postgres    false    257   ��      �          0    45007    e_p_c_infraestructuras 
   TABLE DATA           m   COPY proyecto.e_p_c_infraestructuras (id, item, type, measurement, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    258   ��      �          0    45012    e_p_c_medicamento_insumos 
   TABLE DATA           c   COPY proyecto.e_p_c_medicamento_insumos (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    260   ��      �          0    45017    e_p_c_otro_servicios 
   TABLE DATA           ^   COPY proyecto.e_p_c_otro_servicios (id, item, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    262   �      �          0    45022    e_p_c_prestaciones 
   TABLE DATA           d   COPY proyecto.e_p_c_prestaciones (id, item, type, detail, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    264   5�      �          0    45030    e_p_c_servicios 
   TABLE DATA           `   COPY proyecto.e_p_c_servicios (id, item, type, description, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    266   R�      �          0    45038    e_p_c_talento_humanos 
   TABLE DATA           f   COPY proyecto.e_p_c_talento_humanos (id, item, hours, type, cost, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    268   o�      �          0    45046    e_p_c_tthhs_servicios 
   TABLE DATA           Q   COPY proyecto.e_p_c_tthhs_servicios (servicio_id, tthh_id, cantidad) FROM stdin;
    proyecto          postgres    false    270   ��      �          0    45049    e_p_c_turnos 
   TABLE DATA           J   COPY proyecto.e_p_c_turnos (id, item, created_at, updated_at) FROM stdin;
    proyecto          postgres    false    271   ��      �          0    45054    otroservicio_has_servicios 
   TABLE DATA           _   COPY proyecto.otroservicio_has_servicios (servicio_id, otro_servicio_id, cantidad) FROM stdin;
    proyecto          postgres    false    273   ��      �          0    45057 
   categories 
   TABLE DATA           F   COPY public.categories (id, name, created_at, updated_at) FROM stdin;
    public          postgres    false    274   ��      �          0    45062    groups 
   TABLE DATA           Y   COPY public.groups (id, name, _lft, _rgt, parent_id, created_at, updated_at) FROM stdin;
    public          postgres    false    276    �      �          0    45067    groups_has_members 
   TABLE DATA           [   COPY public.groups_has_members (id, group_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    277   ��      �          0    45074 
   migrations 
   TABLE DATA           :   COPY public.migrations (id, migration, batch) FROM stdin;
    public          postgres    false    280   ��      �          0    45079    model_has_permissions 
   TABLE DATA           T   COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
    public          postgres    false    282   K�      �          0    45082    model_has_roles 
   TABLE DATA           H   COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
    public          postgres    false    283   h�      �          0    45085    organigramas 
   TABLE DATA           �   COPY public.organigramas (id, dependency, _lft, _rgt, parent_id, manager, phone, email, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    284   ��      �          0    45095    password_resets 
   TABLE DATA           C   COPY public.password_resets (email, token, created_at) FROM stdin;
    public          postgres    false    286   /�      �          0    45098    permissions 
   TABLE DATA           S   COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    287   L�      �          0    45103    risks 
   TABLE DATA           I   COPY public.risks (id, name, detail, created_at, updated_at) FROM stdin;
    public          postgres    false    289   ��      �          0    45108    role_has_permissions 
   TABLE DATA           F   COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
    public          postgres    false    291   ��      �          0    45111    roles 
   TABLE DATA           M   COPY public.roles (id, name, guard_name, created_at, updated_at) FROM stdin;
    public          postgres    false    292   ��      �          0    45116 	   servicios 
   TABLE DATA           k   COPY public.servicios (id, name, type, _lft, _rgt, parent_id, user_id, created_at, updated_at) FROM stdin;
    public          postgres    false    294   g�      �          0    45123    users 
   TABLE DATA              COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, group_id) FROM stdin;
    public          postgres    false    296   ��      '           0    0     formulario_clasificadores_id_seq    SEQUENCE SET     T   SELECT pg_catalog.setval('estadistica.formulario_clasificadores_id_seq', 1, false);
          estadistica          postgres    false    207            (           0    0 *   formulario_formulario_has_variables_id_seq    SEQUENCE SET     ^   SELECT pg_catalog.setval('estadistica.formulario_formulario_has_variables_id_seq', 1, false);
          estadistica          postgres    false    209            )           0    0    formulario_formularios_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('estadistica.formulario_formularios_id_seq', 1, false);
          estadistica          postgres    false    211            *           0    0    formulario_items_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('estadistica.formulario_items_id_seq', 1, false);
          estadistica          postgres    false    213            +           0    0    formulario_variables_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('estadistica.formulario_variables_id_seq', 1, false);
          estadistica          postgres    false    215            ,           0    0    foda_analisis_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('planificacion.foda_analisis_id_seq', 510, true);
          planificacion          postgres    false    217            -           0    0    foda_cruce_ambientes_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('planificacion.foda_cruce_ambientes_id_seq', 24, true);
          planificacion          postgres    false    224            .           0    0    foda_groups_has_perfiles_id_seq    SEQUENCE SET     U   SELECT pg_catalog.setval('planificacion.foda_groups_has_perfiles_id_seq', 1, false);
          planificacion          postgres    false    226            /           0    0    foda_models_id_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('planificacion.foda_models_id_seq', 96, true);
          planificacion          postgres    false    228            0           0    0 $   pei_profiles_has_dependencies_id_seq    SEQUENCE SET     Z   SELECT pg_catalog.setval('planificacion.pei_profiles_has_dependencies_id_seq', 1, false);
          planificacion          postgres    false    232            1           0    0 "   pei_profiles_has_strategies_id_seq    SEQUENCE SET     X   SELECT pg_catalog.setval('planificacion.pei_profiles_has_strategies_id_seq', 10, true);
          planificacion          postgres    false    234            2           0    0 !   peis_profiles_has_analysts_id_seq    SEQUENCE SET     W   SELECT pg_catalog.setval('planificacion.peis_profiles_has_analysts_id_seq', 32, true);
          planificacion          postgres    false    236            3           0    0 %   peis_profiles_has_responsibles_id_seq    SEQUENCE SET     \   SELECT pg_catalog.setval('planificacion.peis_profiles_has_responsibles_id_seq', 115, true);
          planificacion          postgres    false    238            4           0    0    tasks_has_analysts_id_seq    SEQUENCE SET     O   SELECT pg_catalog.setval('planificacion.tasks_has_analysts_id_seq', 46, true);
          planificacion          postgres    false    241            5           0    0    tasks_has_type_tasks_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('planificacion.tasks_has_type_tasks_id_seq', 78, true);
          planificacion          postgres    false    243            6           0    0    tasks_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('planificacion.tasks_id_seq', 42, true);
          planificacion          postgres    false    244            7           0    0    typetasks_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('planificacion.typetasks_id_seq', 49, true);
          planificacion          postgres    false    246            8           0    0    e_p_c_equipamientos_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('proyecto.e_p_c_equipamientos_id_seq', 1, false);
          proyecto          postgres    false    248            9           0    0    e_p_c_especialidads_id_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('proyecto.e_p_c_especialidads_id_seq', 1, false);
          proyecto          postgres    false    251            :           0    0    e_p_c_estandars_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('proyecto.e_p_c_estandars_id_seq', 1, false);
          proyecto          postgres    false    253            ;           0    0    e_p_c_horarios_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('proyecto.e_p_c_horarios_id_seq', 1, false);
          proyecto          postgres    false    256            <           0    0    e_p_c_infraestructuras_id_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('proyecto.e_p_c_infraestructuras_id_seq', 1, false);
          proyecto          postgres    false    259            =           0    0     e_p_c_medicamento_insumos_id_seq    SEQUENCE SET     Q   SELECT pg_catalog.setval('proyecto.e_p_c_medicamento_insumos_id_seq', 1, false);
          proyecto          postgres    false    261            >           0    0    e_p_c_otro_servicios_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('proyecto.e_p_c_otro_servicios_id_seq', 1, false);
          proyecto          postgres    false    263            ?           0    0    e_p_c_prestaciones_id_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('proyecto.e_p_c_prestaciones_id_seq', 1, false);
          proyecto          postgres    false    265            @           0    0    e_p_c_servicios_id_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('proyecto.e_p_c_servicios_id_seq', 1, false);
          proyecto          postgres    false    267            A           0    0    e_p_c_talento_humanos_id_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('proyecto.e_p_c_talento_humanos_id_seq', 1, false);
          proyecto          postgres    false    269            B           0    0    e_p_c_turnos_id_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('proyecto.e_p_c_turnos_id_seq', 1, false);
          proyecto          postgres    false    272            C           0    0    categories_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.categories_id_seq', 1, false);
          public          postgres    false    275            D           0    0    groups_has_members_id_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('public.groups_has_members_id_seq', 251, true);
          public          postgres    false    278            E           0    0    groups_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.groups_id_seq', 33, true);
          public          postgres    false    279            F           0    0    migrations_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.migrations_id_seq', 49, true);
          public          postgres    false    281            G           0    0    organigramas_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.organigramas_id_seq', 14, true);
          public          postgres    false    285            H           0    0    permissions_id_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.permissions_id_seq', 4, true);
          public          postgres    false    288            I           0    0    risks_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.risks_id_seq', 1, false);
          public          postgres    false    290            J           0    0    roles_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.roles_id_seq', 3, true);
          public          postgres    false    293            K           0    0    servicios_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.servicios_id_seq', 1, false);
          public          postgres    false    295            L           0    0    users_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.users_id_seq', 104, true);
          public          postgres    false    297            ~           2606    45173 8   formulario_clasificadores formulario_clasificadores_pkey 
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
       planificacion            postgres    false    240            �           2606    45205 .   tasks_has_type_tasks tasks_has_type_tasks_pkey 
   CONSTRAINT     s   ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT tasks_has_type_tasks_pkey PRIMARY KEY (id);
 _   ALTER TABLE ONLY planificacion.tasks_has_type_tasks DROP CONSTRAINT tasks_has_type_tasks_pkey;
       planificacion            postgres    false    242            �           2606    45207    tasks tasks_pkey 
   CONSTRAINT     U   ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT tasks_pkey PRIMARY KEY (id);
 A   ALTER TABLE ONLY planificacion.tasks DROP CONSTRAINT tasks_pkey;
       planificacion            postgres    false    239            �           2606    45209    typetasks typetasks_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY planificacion.typetasks
    ADD CONSTRAINT typetasks_pkey PRIMARY KEY (id);
 I   ALTER TABLE ONLY planificacion.typetasks DROP CONSTRAINT typetasks_pkey;
       planificacion            postgres    false    245            �           2606    45211 ,   e_p_c_equipamientos e_p_c_equipamientos_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY proyecto.e_p_c_equipamientos
    ADD CONSTRAINT e_p_c_equipamientos_pkey PRIMARY KEY (id);
 X   ALTER TABLE ONLY proyecto.e_p_c_equipamientos DROP CONSTRAINT e_p_c_equipamientos_pkey;
       proyecto            postgres    false    247            �           2606    45213 ,   e_p_c_especialidads e_p_c_especialidads_pkey 
   CONSTRAINT     l   ALTER TABLE ONLY proyecto.e_p_c_especialidads
    ADD CONSTRAINT e_p_c_especialidads_pkey PRIMARY KEY (id);
 X   ALTER TABLE ONLY proyecto.e_p_c_especialidads DROP CONSTRAINT e_p_c_especialidads_pkey;
       proyecto            postgres    false    250            �           2606    45215 $   e_p_c_estandars e_p_c_estandars_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY proyecto.e_p_c_estandars
    ADD CONSTRAINT e_p_c_estandars_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY proyecto.e_p_c_estandars DROP CONSTRAINT e_p_c_estandars_pkey;
       proyecto            postgres    false    252            �           2606    45217 "   e_p_c_horarios e_p_c_horarios_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY proyecto.e_p_c_horarios
    ADD CONSTRAINT e_p_c_horarios_pkey PRIMARY KEY (id);
 N   ALTER TABLE ONLY proyecto.e_p_c_horarios DROP CONSTRAINT e_p_c_horarios_pkey;
       proyecto            postgres    false    255            �           2606    45219 2   e_p_c_infraestructuras e_p_c_infraestructuras_pkey 
   CONSTRAINT     r   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras
    ADD CONSTRAINT e_p_c_infraestructuras_pkey PRIMARY KEY (id);
 ^   ALTER TABLE ONLY proyecto.e_p_c_infraestructuras DROP CONSTRAINT e_p_c_infraestructuras_pkey;
       proyecto            postgres    false    258            �           2606    45221 8   e_p_c_medicamento_insumos e_p_c_medicamento_insumos_pkey 
   CONSTRAINT     x   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos
    ADD CONSTRAINT e_p_c_medicamento_insumos_pkey PRIMARY KEY (id);
 d   ALTER TABLE ONLY proyecto.e_p_c_medicamento_insumos DROP CONSTRAINT e_p_c_medicamento_insumos_pkey;
       proyecto            postgres    false    260            �           2606    45223 .   e_p_c_otro_servicios e_p_c_otro_servicios_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios
    ADD CONSTRAINT e_p_c_otro_servicios_pkey PRIMARY KEY (id);
 Z   ALTER TABLE ONLY proyecto.e_p_c_otro_servicios DROP CONSTRAINT e_p_c_otro_servicios_pkey;
       proyecto            postgres    false    262            �           2606    45225 *   e_p_c_prestaciones e_p_c_prestaciones_pkey 
   CONSTRAINT     j   ALTER TABLE ONLY proyecto.e_p_c_prestaciones
    ADD CONSTRAINT e_p_c_prestaciones_pkey PRIMARY KEY (id);
 V   ALTER TABLE ONLY proyecto.e_p_c_prestaciones DROP CONSTRAINT e_p_c_prestaciones_pkey;
       proyecto            postgres    false    264            �           2606    45227 $   e_p_c_servicios e_p_c_servicios_pkey 
   CONSTRAINT     d   ALTER TABLE ONLY proyecto.e_p_c_servicios
    ADD CONSTRAINT e_p_c_servicios_pkey PRIMARY KEY (id);
 P   ALTER TABLE ONLY proyecto.e_p_c_servicios DROP CONSTRAINT e_p_c_servicios_pkey;
       proyecto            postgres    false    266            �           2606    45229 0   e_p_c_talento_humanos e_p_c_talento_humanos_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos
    ADD CONSTRAINT e_p_c_talento_humanos_pkey PRIMARY KEY (id);
 \   ALTER TABLE ONLY proyecto.e_p_c_talento_humanos DROP CONSTRAINT e_p_c_talento_humanos_pkey;
       proyecto            postgres    false    268            �           2606    45231    e_p_c_turnos e_p_c_turnos_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY proyecto.e_p_c_turnos
    ADD CONSTRAINT e_p_c_turnos_pkey PRIMARY KEY (id);
 J   ALTER TABLE ONLY proyecto.e_p_c_turnos DROP CONSTRAINT e_p_c_turnos_pkey;
       proyecto            postgres    false    271            �           2606    45233    categories categories_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.categories DROP CONSTRAINT categories_pkey;
       public            postgres    false    274            �           2606    45235 *   groups_has_members groups_has_members_pkey 
   CONSTRAINT     h   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_pkey PRIMARY KEY (id);
 T   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_pkey;
       public            postgres    false    277            �           2606    45237    groups groups_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.groups
    ADD CONSTRAINT groups_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.groups DROP CONSTRAINT groups_pkey;
       public            postgres    false    276            �           2606    45239    migrations migrations_pkey 
   CONSTRAINT     X   ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);
 D   ALTER TABLE ONLY public.migrations DROP CONSTRAINT migrations_pkey;
       public            postgres    false    280            �           2606    45241 0   model_has_permissions model_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);
 Z   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_pkey;
       public            postgres    false    282    282    282            �           2606    45243 $   model_has_roles model_has_roles_pkey 
   CONSTRAINT     }   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);
 N   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_pkey;
       public            postgres    false    283    283    283            �           2606    45245 &   organigramas organigramas_email_unique 
   CONSTRAINT     b   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_email_unique UNIQUE (email);
 P   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_email_unique;
       public            postgres    false    284            �           2606    45247    organigramas organigramas_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_pkey PRIMARY KEY (id);
 H   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_pkey;
       public            postgres    false    284            �           2606    45249    permissions permissions_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);
 F   ALTER TABLE ONLY public.permissions DROP CONSTRAINT permissions_pkey;
       public            postgres    false    287            �           2606    45251    risks risks_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.risks
    ADD CONSTRAINT risks_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.risks DROP CONSTRAINT risks_pkey;
       public            postgres    false    289            �           2606    45253 .   role_has_permissions role_has_permissions_pkey 
   CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);
 X   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_pkey;
       public            postgres    false    291    291            �           2606    45255    roles roles_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.roles DROP CONSTRAINT roles_pkey;
       public            postgres    false    292            �           2606    45257    servicios servicios_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_pkey PRIMARY KEY (id);
 B   ALTER TABLE ONLY public.servicios DROP CONSTRAINT servicios_pkey;
       public            postgres    false    294            �           2606    45259    users users_email_unique 
   CONSTRAINT     T   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_unique;
       public            postgres    false    296            �           2606    45261    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    296            �           1259    45262 :   estadistica_formulario_variables__lft__rgt_parent_id_index    INDEX     �   CREATE INDEX estadistica_formulario_variables__lft__rgt_parent_id_index ON estadistica.formulario_variables USING btree (_lft, _rgt, parent_id);
 S   DROP INDEX estadistica.estadistica_formulario_variables__lft__rgt_parent_id_index;
       estadistica            postgres    false    214    214    214            �           1259    45263 3   planificacion_foda_models__lft__rgt_parent_id_index    INDEX     �   CREATE INDEX planificacion_foda_models__lft__rgt_parent_id_index ON planificacion.foda_models USING btree (_lft, _rgt, parent_id);
 N   DROP INDEX planificacion.planificacion_foda_models__lft__rgt_parent_id_index;
       planificacion            postgres    false    227    227    227            �           1259    45264     groups__lft__rgt_parent_id_index    INDEX     d   CREATE INDEX groups__lft__rgt_parent_id_index ON public.groups USING btree (_lft, _rgt, parent_id);
 4   DROP INDEX public.groups__lft__rgt_parent_id_index;
       public            postgres    false    276    276    276            �           1259    45265 /   model_has_permissions_model_id_model_type_index    INDEX     �   CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);
 C   DROP INDEX public.model_has_permissions_model_id_model_type_index;
       public            postgres    false    282    282            �           1259    45266 )   model_has_roles_model_id_model_type_index    INDEX     u   CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);
 =   DROP INDEX public.model_has_roles_model_id_model_type_index;
       public            postgres    false    283    283            �           1259    45267 &   organigramas__lft__rgt_parent_id_index    INDEX     p   CREATE INDEX organigramas__lft__rgt_parent_id_index ON public.organigramas USING btree (_lft, _rgt, parent_id);
 :   DROP INDEX public.organigramas__lft__rgt_parent_id_index;
       public            postgres    false    284    284    284            �           1259    45268    password_resets_email_index    INDEX     X   CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);
 /   DROP INDEX public.password_resets_email_index;
       public            postgres    false    286            �           1259    45269 #   servicios__lft__rgt_parent_id_index    INDEX     j   CREATE INDEX servicios__lft__rgt_parent_id_index ON public.servicios USING btree (_lft, _rgt, parent_id);
 7   DROP INDEX public.servicios__lft__rgt_parent_id_index;
       public            postgres    false    294    294    294            �           2606    45270 W   formulario_clasificadores estadistica_formulario_clasificadores_clasificador_id_foreign    FK CONSTRAINT        ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign FOREIGN KEY (clasificador_id) REFERENCES estadistica.formulario_clasificadores(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT estadistica_formulario_clasificadores_clasificador_id_foreign;
       estadistica          postgres    false    3198    206    206            �           2606    45275 O   formulario_clasificadores estadistica_formulario_clasificadores_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_clasificadores
    ADD CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 ~   ALTER TABLE ONLY estadistica.formulario_clasificadores DROP CONSTRAINT estadistica_formulario_clasificadores_user_id_foreign;
       estadistica          postgres    false    3294    296    206            �           2606    45280 c   formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_formulario_id_f    FK CONSTRAINT       ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f FOREIGN KEY (formulario_id) REFERENCES estadistica.formulario_formularios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT estadistica_formulario_formulario_has_variables_formulario_id_f;
       estadistica          postgres    false    208    3202    210            �           2606    45285 c   formulario_formulario_has_variables estadistica_formulario_formulario_has_variables_variable_id_for    FK CONSTRAINT       ALTER TABLE ONLY estadistica.formulario_formulario_has_variables
    ADD CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formulario_has_variables DROP CONSTRAINT estadistica_formulario_formulario_has_variables_variable_id_for;
       estadistica          postgres    false    214    208    3207            �           2606    45290 V   formulario_formularios estadistica_formulario_formularios_dependencia_emisor_id_foreig    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig FOREIGN KEY (dependencia_emisor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_dependencia_emisor_id_foreig;
       estadistica          postgres    false    210    3278    284            �           2606    45295 V   formulario_formularios estadistica_formulario_formularios_dependencia_receptor_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore FOREIGN KEY (dependencia_receptor_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_dependencia_receptor_id_fore;
       estadistica          postgres    false    284    210    3278            �           2606    45300 I   formulario_formularios estadistica_formulario_formularios_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_formularios
    ADD CONSTRAINT estadistica_formulario_formularios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY estadistica.formulario_formularios DROP CONSTRAINT estadistica_formulario_formularios_user_id_foreign;
       estadistica          postgres    false    3294    210    296            �           2606    45305 =   formulario_items estadistica_formulario_items_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 l   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT estadistica_formulario_items_user_id_foreign;
       estadistica          postgres    false    3294    296    212            �           2606    45310 A   formulario_items estadistica_formulario_items_variable_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_items
    ADD CONSTRAINT estadistica_formulario_items_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES estadistica.formulario_variables(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY estadistica.formulario_items DROP CONSTRAINT estadistica_formulario_items_variable_id_foreign;
       estadistica          postgres    false    212    3207    214            �           2606    45315 E   formulario_variables estadistica_formulario_variables_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY estadistica.formulario_variables
    ADD CONSTRAINT estadistica_formulario_variables_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY estadistica.formulario_variables DROP CONSTRAINT estadistica_formulario_variables_user_id_foreign;
       estadistica          postgres    false    3294    296    214            �           2606    45320    pei_profiles fk_dependency    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT fk_dependency FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 K   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT fk_dependency;
       planificacion          postgres    false    3278    284    230            �           2606    45325    pei_profiles fk_user_id    FK CONSTRAINT     }   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES public.users(id);
 H   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT fk_user_id;
       planificacion          postgres    false    230    296    3294            �           2606    45330 <   foda_analisis planificacion_foda_analisis_aspecto_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign FOREIGN KEY (aspecto_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 m   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_aspecto_id_foreign;
       planificacion          postgres    false    216    227    3215            �           2606    45335 ;   foda_analisis planificacion_foda_analisis_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 l   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_perfil_id_foreign;
       planificacion          postgres    false    229    216    3218            �           2606    45340 9   foda_analisis planificacion_foda_analisis_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_analisis
    ADD CONSTRAINT planificacion_foda_analisis_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 j   ALTER TABLE ONLY planificacion.foda_analisis DROP CONSTRAINT planificacion_foda_analisis_user_id_foreign;
       planificacion          postgres    false    296    3294    216            �           2606    45345 W   foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_category_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign FOREIGN KEY (category_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil DROP CONSTRAINT planificacion_foda_categorias_has_perfil_category_id_foreign;
       planificacion          postgres    false    3215    218    227            �           2606    45350 U   foda_categorias_has_perfil planificacion_foda_categorias_has_perfil_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil
    ADD CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_categorias_has_perfil DROP CONSTRAINT planificacion_foda_categorias_has_perfil_perfil_id_foreign;
       planificacion          postgres    false    218    3218    229            �           2606    45355 a   foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore FOREIGN KEY (amenaza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_amenaza_id_fore;
       planificacion          postgres    false    3215    227    220            �           2606    45360 a   foda_cruce_ambientes_has_amenazas planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_amenazas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_amenazas_cruce_id_foreig;
       planificacion          postgres    false    3211    219    220            �           2606    45365 d   foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_cruce_id_for;
       planificacion          postgres    false    219    3211    221            �           2606    45370 d   foda_cruce_ambientes_has_debilidades planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id    FK CONSTRAINT        ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id FOREIGN KEY (debilidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_debilidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_debilidades_debilidad_id;
       planificacion          postgres    false    3215    221    227            �           2606    45375 c   foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_cruce_id_fore;
       planificacion          postgres    false    3211    219    222            �           2606    45380 c   foda_cruce_ambientes_has_fortalezas planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_ FOREIGN KEY (fortaleza_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_fortalezas DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_fortalezas_fortaleza_id_;
       planificacion          postgres    false    227    3215    222            �           2606    45385 f   foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f FOREIGN KEY (cruce_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_cruce_id_f;
       planificacion          postgres    false    223    3211    219            �           2606    45390 f   foda_cruce_ambientes_has_oportunidades planificacion_foda_cruce_ambientes_has_oportunidades_oportunida    FK CONSTRAINT       ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida FOREIGN KEY (oportunidad_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes_has_oportunidades DROP CONSTRAINT planificacion_foda_cruce_ambientes_has_oportunidades_oportunida;
       planificacion          postgres    false    227    223    3215            �           2606    45395 I   foda_cruce_ambientes planificacion_foda_cruce_ambientes_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 z   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT planificacion_foda_cruce_ambientes_perfil_id_foreign;
       planificacion          postgres    false    229    219    3218            �           2606    45400 G   foda_cruce_ambientes planificacion_foda_cruce_ambientes_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_cruce_ambientes
    ADD CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY planificacion.foda_cruce_ambientes DROP CONSTRAINT planificacion_foda_cruce_ambientes_user_id_foreign;
       planificacion          postgres    false    3294    219    296            �           2606    45405 P   foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT planificacion_foda_groups_has_perfiles_group_id_foreign;
       planificacion          postgres    false    3263    225    276            �           2606    45410 Q   foda_groups_has_perfiles planificacion_foda_groups_has_perfiles_perfil_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles
    ADD CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign FOREIGN KEY (perfil_id) REFERENCES planificacion.foda_perfiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.foda_groups_has_perfiles DROP CONSTRAINT planificacion_foda_groups_has_perfiles_perfil_id_foreign;
       planificacion          postgres    false    225    3218    229            �           2606    45415 ?   foda_perfiles planificacion_foda_perfiles_dependency_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_dependency_id_foreign;
       planificacion          postgres    false    229    3278    284            �           2606    45420 :   foda_perfiles planificacion_foda_perfiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 k   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_group_id_foreign;
       planificacion          postgres    false    229    3263    276            �           2606    45425 :   foda_perfiles planificacion_foda_perfiles_model_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.foda_perfiles
    ADD CONSTRAINT planificacion_foda_perfiles_model_id_foreign FOREIGN KEY (model_id) REFERENCES planificacion.foda_models(id) ON UPDATE CASCADE ON DELETE CASCADE;
 k   ALTER TABLE ONLY planificacion.foda_perfiles DROP CONSTRAINT planificacion_foda_perfiles_model_id_foreign;
       planificacion          postgres    false    229    227    3215            �           2606    45430 8   pei_profiles planificacion_pei_profiles_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles
    ADD CONSTRAINT planificacion_pei_profiles_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 i   ALTER TABLE ONLY planificacion.pei_profiles DROP CONSTRAINT planificacion_pei_profiles_group_id_foreign;
       planificacion          postgres    false    230    3263    276                        2606    45435 ]   pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_dependency_id_forei    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei FOREIGN KEY (dependency_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT planificacion_pei_profiles_has_dependencies_dependency_id_forei;
       planificacion          postgres    false    231    284    3278                       2606    45440 ]   pei_profiles_has_dependencies planificacion_pei_profiles_has_dependencies_pei_profile_id_fore    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies
    ADD CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_dependencies DROP CONSTRAINT planificacion_pei_profiles_has_dependencies_pei_profile_id_fore;
       planificacion          postgres    false    3220    231    230                       2606    45445 X   pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT planificacion_pei_profiles_has_strategies_profile_id_foreign;
       planificacion          postgres    false    230    3220    233                       2606    45450 Y   pei_profiles_has_strategies planificacion_pei_profiles_has_strategies_strategy_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies
    ADD CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign FOREIGN KEY (strategy_id) REFERENCES planificacion.foda_cruce_ambientes(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.pei_profiles_has_strategies DROP CONSTRAINT planificacion_pei_profiles_has_strategies_strategy_id_foreign;
       planificacion          postgres    false    233    3211    219                       2606    45455 V   peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_analyst_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT planificacion_peis_profiles_has_analysts_analyst_id_foreign;
       planificacion          postgres    false    296    235    3294                       2606    45460 Z   peis_profiles_has_analysts planificacion_peis_profiles_has_analysts_pei_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts
    ADD CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign FOREIGN KEY (pei_profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_analysts DROP CONSTRAINT planificacion_peis_profiles_has_analysts_pei_profile_id_foreign;
       planificacion          postgres    false    235    3220    230                       2606    45465 ^   peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_profile_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES planificacion.pei_profiles(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT planificacion_peis_profiles_has_responsibles_profile_id_foreign;
       planificacion          postgres    false    237    3220    230                       2606    45470 ^   peis_profiles_has_responsibles planificacion_peis_profiles_has_responsibles_responsible_id_for    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles
    ADD CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for FOREIGN KEY (responsible_id) REFERENCES public.organigramas(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY planificacion.peis_profiles_has_responsibles DROP CONSTRAINT planificacion_peis_profiles_has_responsibles_responsible_id_for;
       planificacion          postgres    false    284    3278    237                       2606    45475 *   tasks planificacion_tasks_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks
    ADD CONSTRAINT planificacion_tasks_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 [   ALTER TABLE ONLY planificacion.tasks DROP CONSTRAINT planificacion_tasks_group_id_foreign;
       planificacion          postgres    false    3263    239    276            	           2606    45480 F   tasks_has_analysts planificacion_tasks_has_analysts_analyst_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign FOREIGN KEY (analyst_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 w   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT planificacion_tasks_has_analysts_analyst_id_foreign;
       planificacion          postgres    false    296    240    3294            
           2606    45485 C   tasks_has_analysts planificacion_tasks_has_analysts_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_analysts
    ADD CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY planificacion.tasks_has_analysts DROP CONSTRAINT planificacion_tasks_has_analysts_task_id_foreign;
       planificacion          postgres    false    3230    239    240                       2606    45490 G   tasks_has_type_tasks planificacion_tasks_has_type_tasks_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT planificacion_tasks_has_type_tasks_task_id_foreign FOREIGN KEY (task_id) REFERENCES planificacion.tasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 x   ALTER TABLE ONLY planificacion.tasks_has_type_tasks DROP CONSTRAINT planificacion_tasks_has_type_tasks_task_id_foreign;
       planificacion          postgres    false    242    3230    239                       2606    45495 L   tasks_has_type_tasks planificacion_tasks_has_type_tasks_type_task_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY planificacion.tasks_has_type_tasks
    ADD CONSTRAINT planificacion_tasks_has_type_tasks_type_task_id_foreign FOREIGN KEY (type_task_id) REFERENCES planificacion.typetasks(id) ON UPDATE CASCADE ON DELETE CASCADE;
 }   ALTER TABLE ONLY planificacion.tasks_has_type_tasks DROP CONSTRAINT planificacion_tasks_has_type_tasks_type_task_id_foreign;
       planificacion          postgres    false    3236    242    245                       2606    45500 \   e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign FOREIGN KEY (equipamiento_id) REFERENCES proyecto.e_p_c_equipamientos(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios DROP CONSTRAINT proyecto_e_p_c_equipamientos_servicios_equipamiento_id_foreign;
       proyecto          postgres    false    3238    247    249                       2606    45505 X   e_p_c_equipamientos_servicios proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios
    ADD CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_equipamientos_servicios DROP CONSTRAINT proyecto_e_p_c_equipamientos_servicios_servicio_id_foreign;
       proyecto          postgres    false    249    3254    266                       2606    45510 P   e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_estandar_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign FOREIGN KEY (estandar_id) REFERENCES proyecto.e_p_c_estandars(id) ON UPDATE CASCADE ON DELETE CASCADE;
 |   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios DROP CONSTRAINT proyecto_e_p_c_estandars_servicios_estandar_id_foreign;
       proyecto          postgres    false    254    252    3242                       2606    45515 P   e_p_c_estandars_servicios proyecto_e_p_c_estandars_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios
    ADD CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 |   ALTER TABLE ONLY proyecto.e_p_c_estandars_servicios DROP CONSTRAINT proyecto_e_p_c_estandars_servicios_servicio_id_foreign;
       proyecto          postgres    false    3254    254    266                       2606    45520 ^   e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore    FK CONSTRAINT       ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore FOREIGN KEY (infraestructura_id) REFERENCES proyecto.e_p_c_infraestructuras(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio DROP CONSTRAINT proyecto_e_p_c_infraestructura_servicio_infraestructura_id_fore;
       proyecto          postgres    false    257    258    3246                       2606    45525 Z   e_p_c_infraestructura_servicio proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio
    ADD CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.e_p_c_infraestructura_servicio DROP CONSTRAINT proyecto_e_p_c_infraestructura_servicio_servicio_id_foreign;
       proyecto          postgres    false    257    266    3254                       2606    45530 H   e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 t   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios DROP CONSTRAINT proyecto_e_p_c_tthhs_servicios_servicio_id_foreign;
       proyecto          postgres    false    270    3254    266                       2606    45535 D   e_p_c_tthhs_servicios proyecto_e_p_c_tthhs_servicios_tthh_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios
    ADD CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign FOREIGN KEY (tthh_id) REFERENCES proyecto.e_p_c_talento_humanos(id) ON UPDATE CASCADE ON DELETE CASCADE;
 p   ALTER TABLE ONLY proyecto.e_p_c_tthhs_servicios DROP CONSTRAINT proyecto_e_p_c_tthhs_servicios_tthh_id_foreign;
       proyecto          postgres    false    268    3256    270                       2606    45540 W   otroservicio_has_servicios proyecto_otroservicio_has_servicios_otro_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign FOREIGN KEY (otro_servicio_id) REFERENCES proyecto.e_p_c_otro_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios DROP CONSTRAINT proyecto_otroservicio_has_servicios_otro_servicio_id_foreign;
       proyecto          postgres    false    262    273    3250                       2606    45545 R   otroservicio_has_servicios proyecto_otroservicio_has_servicios_servicio_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY proyecto.otroservicio_has_servicios
    ADD CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign FOREIGN KEY (servicio_id) REFERENCES proyecto.e_p_c_servicios(id) ON UPDATE CASCADE ON DELETE CASCADE;
 ~   ALTER TABLE ONLY proyecto.otroservicio_has_servicios DROP CONSTRAINT proyecto_otroservicio_has_servicios_servicio_id_foreign;
       proyecto          postgres    false    266    3254    273                       2606    45597    users fk_group_id    FK CONSTRAINT     r   ALTER TABLE ONLY public.users
    ADD CONSTRAINT fk_group_id FOREIGN KEY (group_id) REFERENCES public.groups(id);
 ;   ALTER TABLE ONLY public.users DROP CONSTRAINT fk_group_id;
       public          postgres    false    296    276    3263                       2606    45550 6   groups_has_members groups_has_members_group_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_group_id_foreign FOREIGN KEY (group_id) REFERENCES public.groups(id) ON UPDATE CASCADE ON DELETE CASCADE;
 `   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_group_id_foreign;
       public          postgres    false    3263    277    276                       2606    45555 5   groups_has_members groups_has_members_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.groups_has_members
    ADD CONSTRAINT groups_has_members_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 _   ALTER TABLE ONLY public.groups_has_members DROP CONSTRAINT groups_has_members_user_id_foreign;
       public          postgres    false    296    3294    277                       2606    45560 A   model_has_permissions model_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 k   ALTER TABLE ONLY public.model_has_permissions DROP CONSTRAINT model_has_permissions_permission_id_foreign;
       public          postgres    false    282    3281    287                       2606    45565 /   model_has_roles model_has_roles_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 Y   ALTER TABLE ONLY public.model_has_roles DROP CONSTRAINT model_has_roles_role_id_foreign;
       public          postgres    false    292    3287    283                       2606    45570 )   organigramas organigramas_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.organigramas
    ADD CONSTRAINT organigramas_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 S   ALTER TABLE ONLY public.organigramas DROP CONSTRAINT organigramas_user_id_foreign;
       public          postgres    false    3294    284    296                       2606    45575 ?   role_has_permissions role_has_permissions_permission_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;
 i   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_permission_id_foreign;
       public          postgres    false    3281    291    287                       2606    45580 9   role_has_permissions role_has_permissions_role_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;
 c   ALTER TABLE ONLY public.role_has_permissions DROP CONSTRAINT role_has_permissions_role_id_foreign;
       public          postgres    false    291    3287    292                       2606    45585 #   servicios servicios_user_id_foreign    FK CONSTRAINT     �   ALTER TABLE ONLY public.servicios
    ADD CONSTRAINT servicios_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;
 M   ALTER TABLE ONLY public.servicios DROP CONSTRAINT servicios_user_id_foreign;
       public          postgres    false    294    296    3294            �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x���K��8r@�7W����Ȝ0<����d��
�.F{��;�*�ޕH�H
o ��\��`|��}��H�y�4��R�/g�ٿ�Ǧ��<�W�_��~����^�ח�K1�O6_�8?Y3��O6�M����f�����UN�~
������SN�LqNɄ��o��r�������oA����oڻ�w�[�ns�%N�'�ַ�5'��g�r_���G��U���Ӆ���szͶ��a6���� ���S���z*�@��d�\E<��0쁼���|�{ʏ��Bq���_�ә��*I���+�!I���o}�y�I��s��F
�4�����&���zh��8	�^��1)��\~U�K�o79�()�7�)>��.�%��~���MH��$�s����;7^߹���H|�ƋR>s�E��:"^�=�#"�{]R�:"�-�]��☸��|ꓸ�פ<��t@G\�F��pm��:������q�|J숱�K:`G\Ӱ�qM������?�������яv�I~�%����ǻۀ��-qeT��蓸2*HE�I\$}����O*4�B��ݹַ�l(�vA��&�RƠB6m�����o��Pt^4b���M�~
Bk�EB�R�(hF����6l�7,ѫ���:XT��2ްH����EZu�s
H��A3���?����cG-4)�n�[_n�<�kz}�<���rF�y�<�#{󶥠�������vˑ����%"�{�1���&(ܝ�Q� �FQ־��&'�D��A2tD������}���{~�*&M%�<���%����B�k0n���{�w�3�=��D92�'@N�o����x<a�����!�S+��%�|xD?S�F$����;�+Y�#�V��sFi�)�bp��DHN�Ƿ|7F��;;�Er�io�;�b֝��GwsY��D�ۛ;�Ȳg�$�_n�@"��&(�R%�k��f�A�E�K�w�$�E����< %���1y�r������I�0Y�}�����a�%�w��[Cd+5AI#w[�(�-?��1���V�����A�؟1�[�Y���_i!������Sx?>S16N����6v.ou��&k�s5��7!%J��>i�c�J���Ic_i�$VAXHeNS�ߟ)|�{zʿS���x�xXp`O�wW�&����'����W���3��MBz�����ɸ���>�NlJ���E)�*��������0)�15II��1�w���~ɜ}�S�N�l/ob��x���M�F�-I,J�2]'`%w�iٹb���}����e
��0=�K}��g~Ƙ��K�fL!�	!$GIyl�vH�|J�4N�tI�c��I���D�D"
���1ؖ���аq���� ����~ڌ�h��3�;�OD�[�'��/�R�{�䍂�L�x��b�XuH���ä���!��CI�X�uIu;�X ��7B\A!Ō�|�X�\�i9~��d�B�Aw��^�<<{e��:�'�'*��P�?;�R���C`,WR�I�A���g��t��Zq:�������r��$�HL#3cRdvK�ʭ��t�T��oH��d�,�{)�¼}Rm����:�(�������uj��K���*�w�D",>sWA�FؿGVǄeoU��%ż&Ɇ��y�#��o���5`yH~����E�b�ʈ�*��Y�HE�Zѯ���9�ѯb���sx߆��=$�T�K؝΍uj�1	I���Nʸ漕��v߰"(��߷nx�vH5�a��ؐF����XCD{V�� ��c�g%B<R�`=�O1��q|e?y,�!���E���~ʞ�zϙ�ixl�Fd��H�sE�=޹�.w���"�#:��������+[R�g�Ƕr�*o�jF�:,{��:�{Н��)���7ד��ٳ�I#p\��Y�N���O2��/���}��P{u��%��O�4��������Mw�6Y�;V0�x��`:gF�~F����_8�Y�rB��#>E�\��6֞�=6b�M�c���kX��ɯwSU�Gl����oˊ56*�?˓�P3Qc����y��������S�6N�w�S��K��e�I��&�캜�~���8�m�k� /�ae��-!iB-�d�<N��TI(�oB���q�z�T�D�d��ƥ�$�I㊜}�äqI�>�cҸ&g�0i\Y�G*<��H�ll+,o��eT�2�?��7�*N�-@�D��I3���EAH����uIQ\��U9�O7g�k��-��Tvƴ�aW, RsL���b�A���da�P�O��{����%qs��+H�ԿcF�\ݧ�+�ն�1U����삱M]�BR��5,d���W��Z�lp��)o�ӟ$��i��n�SV�Lt����ǳ�����^���r@�|g�����k��e�X*���I�Θ�8��0@E~���+Eg��^s���j8�ˉ6j&�¢a3�=��Z������Gtv�g*�i�a�;���g�x��D4�I\B���=2{Mٛ�:Y���x?uf/ߝ��%��>�����1�F�1������Sk���C����%��G�HH��9�j�k�O�5h6�#Y��O��n:W�\w��olGt��\�O[��t�X,^S��a�ײaCk�6߼b8�}BP趔4�Fg��ˏuyg�B=�.� �rg��vP8�����m�Iu�fLI}簯�*�;�Nr@��Z$6�YK��e,�Q�ͤ\��eLأ]!�1e-7r��|�H��d�p�K��vf/ke�s<��ʞ̨�,� Rޜ����yм���B���M�d���MH8���'� 6�Oh�����B�}�Z7
�p�"�Rh��O�ƍK�z?	I����������Blf��@|���Y���-�m$�|vL��g@4�C���ou�dL�SCH^g�G	ȩ!r���\J�ĎأF�Q�T����� ����qVhn�j��Q����)������S�^B�x/��F����bb�yԍ�M��wF�K9��sY��:��2��0��Բ�8���y�.)�-"8��k��Z�xq~�Y�
���<�6./ޞ�"��d�e�.���X�����?�(AgLQI��%��������(�fϛ�Q\� �Om�ͭ�&�CO�H�F����ծzq�Y,�1�u��'�H�7$BHA��G42���kJ�[f�{�$М=��Oo��mk���ꙛq�0�3]�ur������:����D��4%���G�K<{���*�2�XH��C�넣n��8�N��F�F����1�j�l�q��Kқ�"���������l���ؘ�jXoplYl����ΧT3][a���1���x]��}vtD��򂭰�<�֘j6��+Vg.�,�����F���M�X��[�����ˋ����u�{t����F{�v��$͠�6ꈴQC"��u�'ap'g/,�Q�Fmjh�mB)ǕȫiI���x G|���!��nG ���N�zj	k�@ba�uk����(vh�Q��sڥ��栨[k����y����,$��;�����JR?�ǍP�M
Af�{�$�ә=��,��Z-�����G��zjB
VƄ=5d-w���}��{`����y}�ʲ<D�S�a��{MR\$����z5J�1��i|�UgL�>(��N�X3�3����
c�SZ�<1�r�M��']'~��#����3����tƴ��f�m��ȫў�|�_(��m������݈�T#�/QO}�AŪ��S�$�x����#��L�>��̌?�I�f�T.�p�x&y��kf�3��$��I�'��p�L�>�눋�7c�I�'a�I�'a�I�'a�I�'a�I�'a�I�'qquL\G\�F3����}�O��툋�)sq�|�܎�h�enG\԰���Q�WCy��.K#�V����'�W�<���0�}I��r�N�°�UJKVkÙ����Wy�َ��q:s8�*ݚ37k��g,w�.��(��K��|��k����sT�ל9-����lZ���;#�Q8��0��S�R��=vN�v0�^�R����r|I���a|�V[B�1�\Y��)���&���;v���S/N��S%���F6�9��7� %  ;��ء$t8!܂��B��{��3r��8�W�SH��y<?�,@$?��e1
E��P�P�h������
|T���CE�">�*a
��f.�WG��XC����)�����o�������ot{�3ҡQFsjw9��=[��b���CP�|뼍���;^�K*�c
uL�go�V��%��x�h>Y�o������NF����>E��5+�=����eo^|Z�ɀj����U�>���H�����@��O'��[Rѻլ��_���;$�Z	�du-Ңa�3"�k�IrU�ְ��E��2�ƨR�I����{zL�Y$�{��9Q�v0=�xg���\߹��D��C�1C�Bgn���Б��碆z���7i�*�&����mDj�;$9s�)�����n�EW�r�%U�PܭFY�=w�QZu��j�W�Cq�%V�PܭF��=������=x˵J���mq12��.�+w@[\�A��QLw wQݺA�k���K��&IH��q�!��_cH�^�k��[�2�ÝO%�2�X�v��a�;7&}E8������<�=��y8�ɵ�s�g]I^���ΓM�@gT��w�7���{Od=ϲ�ь{�u�PQ��oj6{\���-��ڜ<���,�J��X����&$�,9�vH��+GL�=�K�X�{$��,���Hx9r���V"�מ��Vl'��:,�S޸(���{��q?*�pBի��dҳ����w���(�}�y��3*��l�U��i�ur��w�
���������g;#5��E.��y�x|G.���B��U�G>ۙ�(������kH��V�ƻ�ũw��_Hjt�m����W�&Ɉp^���n�/�J�	P`��0��(D��%�W�S_����N�N�&�p>��㞭�Z�2���q�_#�ޝ�D�����-�am޸��T�+�h�+j�~H�wsbW��	k<o�k%��l�E���v���g�Y��s����5S�z5a5T�O�v$B� �o�,���=d�@���*�x� �/J"aeT�2jO�����?
Gp�6��b��nS��� ٲ��΍��}N��͡���ﮍ}�E̡oܡ9��D���+���Y9�6s��3޲ed>t9ٝ��2V�MN}q:�X��pqԺ�OIͮ?�b�Q�?-6�";���Q���s�(�E�Vo&3V�?:2J���r�lӌm.;�$t8�ϗ�����P��6qƶ�ݨ�>��)������*��E[B�ms;��pk�8I���7g5��.�D���a�M�V��)?J\u9A5)f�-��h٪��v�$N�W�oB�M��X��l}n�����!����^��
��oN�P�gI�}�c�囦M.�D����q��+���6��*?�њ�]�P��������vm��k���jC�9ϣlj��,S+>X�ŀ��p�������?L���
6|�԰���(|Gֶ;u�FmN���%~����t���w]�ٴCo0���7��h��3eԚ��8�B8m`7���s^�[p��*a�%�E���)���˘,V�0��7E{�O
����_�r���T�X�      �     x���Kn1D��]�C��K���{�#DY��\�����	��В�z}B4��-;��udu���9�\������������sd�V�y(��j�F�J�Mզ`7c�U�i&��ʈKEm�g�Rg��'�� �;�h����+��w�J�Mզ`7c�*y�#�!HK�~[��8O�'�� �;�h����+��w�J�Mզ`7��WM������X$�1g����zG��;�h���#����T~�H+7U��݌5f�v�h�O���O�AK�;��{G�A�w�Tߕ��;i��jS��qO�����*��I��B����v���_� ���_� ��R��A�RqS�)����=�X�/���I��%m5]#[=�:�"81�w��D{G M�]���#�V*n�6���Rۼ��S���Wt�j9���Apb`���#���@��R�yG �T�Tm
v3v]�fhwwY����z�a�;�� 81�w��D{G M�]���#�V*n�6��N�!�{xYA��KB4��1�����+U����#�Ǉhc`�6l�
�
�*�탴�di������nQ+Ey�j'�qBo/G�A4{G��!���#�*l*��wҮ��*}�u����f����C�xo��}�@�w��At��4���F���@���H�NN���H���ݘڒ�B����k�A�w��At��4���F���@���H�N֖���3�Z����nݶ��8���_� ��_� :QC�A�PaS�P�_� ����;ӽ�hJ[zjMF�sܣ�F�@�9i
v�@:QC;Gp#T�T Ti��?;I�\��k�H*�m�z�7���7���Bb:1���ܔ.ALG�NT'F-S�}�L.v$�XE�2�	�'��Z�5�v�`���9i����#C'������>V�i��֩�
�������b�9��7pL:�����L:�Z):2t�:1jq��ϭ�<�Y������ݙZ)�Xke��r��HS�xG���Rtd�Dub��00���������2��l      �   `  x��V�n�0]��𲕚;�6옦U���j���c��G���:3�.��Oȏ�q�	u�
݄�</P@~P.�p.�0�d�X01�N�e"D�'r0U�ʪ�ߜ$��ڒg�W�gܒ���ҒB�"\]<�B�R��-S����r��}�|6RȮ	�1��k�Q��/�P%��aB��+�)y�����x���	��Վţ4 ��9H|_�l�T������8�[X�̣�/�Y���T�&XI>1�^7����4݂=ʽ��'0 \^��u�W�O̧�йZ8#�K �ؤ=�$�$�l[`.�\8)_o>,�yT��[���+HK��g2$O���]<��E��u��]3�}�>hS�~�v`��ƻ�޿U�˒oJg��D	\'�$�1��i��A|@t�S;���B܄{��k�Ѱw��y����.0�rN�љj��:-���.����0�&[�q�9�^�G��:�#v�5���W�ǡǊ�\�2̷~��x6r���5��w�LE0���c���ז�;Y��vm����2��#�.F��+����O��������ȋ.>4O�-/n��兛y���n�Ձ�Ҭ�9�Ji� u\�e��o�t�|PV��E���0���^"34����ޏމ��"=1~~8����CmZ�-i��t��i(]*�[8�J3��P�!������K�QS�����P���Gox|ɘY���ͧu+��R�H�@^���˞�ft�3BYİ&z��c'�'jw�I��IȢ�`yZ% �B�R����l6��� J(�K���9���{_�g�&�%����v���"W����CXKW$~�8b�m_�_��^�W�Q�V6���6����1�<���      �   M   x���� C�3����.��ڹ|��0<���M��
��;���u@48~�s6+�dZ3�zO��5N|�נ�      �   (   x�34�0�24�4�22�47�22�Xr��s��qqq _�!      �   8   x���  C�3�P�����
���&�J�[�HIMR�=�OGk�(���. >6	|      �   6   x�%ʱ 0�a��2��@�\�z�i�4%5��E�|O�c�9�ǳ \v�
A      �      x������ � �      �      x��[�nI�]g~Enf��l�Ir]�)�VA�IU�zL���䃕Mɫ�O�f /�hx�[��|ɜ����*�j����7#�=���s���(��mv��ZG�>�n�=�sq�%���zo�_��YYn����uQ�a��0>}�hU��e���.��U���D�������-�}TZ1nx(؞�V�|H"q�z�W7�緷?���ǿv��?�X�g��gr���`ekefмﺊ2�7�j�e�
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
'�Q�%n	Np�AJi^�S��������|` �]]rɱ�W0_]�?� �X��ỴV���� �����h��}�/��U���l��������S��      �   	  x����nG�k�)�t�	�3��d0��psf挰�x	�2e�<AJ���b>�U"��n�4�����snlͅ����6ș��YoU�1��f���Uuk���c��z��6a[�av����m���_��|�]ö�#�Zt�m���v����\*Fnv��=�r&fv��j��g�UR5J5B��3?��hd�NQ� �f�c��16Z8F�'L��6���6�)�L%��֜3'%}���"f�G��|���o�)i#��G6*�L#d�0L�#����K91���j�����k#��i�-�@t�:⬭��(D^�]��=<��wq�O�JX}��׫�rU=V���ru�?OM�Lb.i/&02��P��2��c�#��:����Č��]�uI�����&$��)(o�?h����8�y��c{�b�R�&�85d�օ���%mt�kˬ��h� ��t4�+ js�bbą
0����p�K�;��X@^�$8ő&m�~�5e�?L�oMԍ:�Ž6��:�N�hH�jH�����O&&�?��8o�j�-i#ZҖXh�km����V�eaC��F�O�n�˶K's���41N�̦1���̨5DZ�1#���hK�/h�p���9ZT%m���� ij�H5��ۘ���q���������)�[^��K�oBVI���34 ,�T+!�CމY�բ)i{6�#R}�\�R"�V�@���A���m��{<�s��0��:����zO���	낋�=���]�L�<�1:Т��K5M ���G?�o�����<ݷ]�gk�am��'�0ujA��������ό��{"�BΜ�� ���-L,�7,C��K���4x*�(^E�p��,���VPb1�}���� O+B���ޯڈs�N�t::���1�x�L��-�e���i
����F����̛a��Y�M:�u0g������⦺��üZ\�̮/�W������E��Uu~s�y~�����ꀊ�q�Z�	h@����BHS	*=����ٗ��ξÒ�      �      x����n�H�&��z
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
�߀�2�RAn���	��ls>�|mO����ϟ��JU      �   �  x���Kj�@���)|��z��1��Ȯ��Z��{��{�\���h��B`�@�g�_�Q�a}ܼ�qx��~8����4}�����H�O�0}퇏�?TB�+{�����o�wQQ)�xp�S�B���1��	j������Y�x[hC�:��1�C��T�e�]�Rv���EJ��z3CV��]i��5�J�B�]]�K-�>3����)��vC3���.�zm$�3¤�j������J�l��Ц"�%K���Ce0��Iu%qI�1T<>l*�eMCM�燥��d��up�T�YW��jw�[ڬ6\=���p������"[��0��uk�H|�=�6X���[?��Wo&�G��b��_M*�����=�I@�~|���a������m��      �   �   x�E��D!Ϧ�Ճ�_[��_��9Hs�@P�L����z`�>�L�,,���Y�A�� �- ��l"McE�j��&D�6�-ĝ��b���!v>W5nf�X�2Nh���T���cN�];��>�c�`v�;?es�ߧ����N5      �   �   x�U��q�0D�T1�K��T���-2��>C�gȱl����[�'+�N}o��ΛDj�2jE��V�ƨٚy3���|�����B5�(���J����F�l�=K|)ϡ���mK�����	4�b� F1+ӳ��10��`�q"�i�>���q�)��`,���8���3VɎ���-g��x�y��ZY��=����Uc.���l=Ig�qHN8��`�ǚ��X|���І�      �   T  x��XM�c�]�E��(d��v9�O #@HHl�9�O�����簜Ŭ�	�Ǩ��ss��SoZJ��=>�s��n~|�xؼI���>��|��_���&&�-R�TA6��b�!;�O7�7V[��č�[�dF1.�޼���.��	H��C����h]�7���+��(��?0�~O*�48���Q�s)ڄ؂Q�f�.1N1e��3���������PH��:�a�$gS�5���]zX��@y���2���=�S�y���F1e�
ꙓa��&Wc:p'�T4=e�^�8���(��Y	��\0�����%w�A��@���&%�F���2�虏�\vRjXru*2���|܀�ce���v�m��P���qJUC��6�Rn:��C6���j�c��挋3M��q���ؐJ+:Lią��F1e��������}]\#����Rz�X�tS��Fz���m�Ŕk�3V�p�ޡS�"�sX�/�Ev������B��w��F�6��5���-ր:�^�0�i�f�s�F4�g6@Y�;Y���S�0f�Ve=����n���eJ�/ș�'��D�b�R�)z�ؒ]֟���,�SL����k˹�.E�B25 c��2�:�-�lkG1��
{�&6ici 1`k�cn$mJg=�C=u}u}�b��ЋL5�h��]�%v;�%^$^��ە���O�i���m:��a�~�s+�������E
�Ai�.Jbn�Y3x��akfܘv hsLz��2ĳHT)��$�+t�\�u�%^1�U[�1e������UN�w�L禮��O��|�ۻ�\��EPRd�� ����+S�R�=���ե�1e��G:�Q�U.Ͷ1�&ۺ�n�|ń.�SV/��EJT� Z�b���&��(I�fW'��$��z�O%��##�\Ȩ&������-���9�,����L?��}]��ʐ��L�D^J�Z+w�s׉�X��������?c�5Y�U�)j+�KM�����.�:@7��Kc��]7�YB�7�"���\�}��K��*�9�P�;�����e���	׶Xl���U��1�O��t�r���೸ߥ2��v�����]��h�JYd򵶉s3C�X��K��s�� �b
��'zΐ��r��4$�F�MZJ�Gq�^)�S�8�|��{���E�,q^b�!���j����ϝ��n��Ώb
���W�ݴ�n��n�#�|=�|ӎKe��'�Q̝�hwl1C2Ic6$庾�y?�:3�)���<㠋t2��g�w���nR��R�q�b���ǘ"|��; ���\3��I�9N�Yn}=4��/��ǘ��`��aۈ��3�QL�PiE���o��P/|�)�^�=������Ϻ5��ڙg(َqS4��wv��1��SC��*V�>p�p��_�b�+�w�n	HA�(=/Q�d�b��D�Ѣ��[���Ö�(����%�t�Ok���'�h;�]dvU��Z^���t�So�<�z�K9%�����.�7�O%����T�o���~�����g��������?�%v���:�Pd7'9�G9(� ��ױk�"?h6���֏b�/�R5�s      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �      x������ � �      �   �  x����n�HE��_�K{���G�X	l��I�dӦ��4()��7��«|,�)E�f�2\�NUWݺe��O��ʶ\�e�f��oZ[��g��+v[ٚ�֛�n��W4�^o�f[���;�+��Ҳ�rek{J�&:%_����<�љ�'F
�E(F9'��aWM�)+W/v��ԅ���;[m��$%Q O�<@�P�"W�����K�#���2(?�\��{=B�˕�]�"�ݲg�����-ځB��I��D2�\��ܺ�ݗ���a�������c^�����+W�5)��$��OB1����>l-�x�[��@�	�7�	aT���F`�(;�ᾔ-�������,�ʕ��A{��C�
>OJx`���r�G�ܜS)��r�$;c�oo�v����9%:&/MK�(bӆg4�U�?����L@c��1���WU4+�<�y��m�T������ʿ��	�NKăw�b��E�6������S��P��8��@y;��^�=SDD6�FBY(F[F�c�<6�D�D&��H��@d�gE��_���C_AJ	HbB��J�P��*"�=R����t�H6��]�I�("i�Q�ǫ���Vh;�\O�)��樂�")|����L�p9F@v��d��k/�G�d�]��΍�&B9�HýW��_D$����Pʳ���?@���e�XBSM�W�"2�gm�ἄ�MS=���`�o���§�퓭�!\ƭp�R�g���e�e����E���ȴ7�`2���JF]>�1�x����F�C��r���m�{$����^��َt��wPQ�ȯ/=-%ދ�4-�J�4�/;��i�@{	��3���x�����	����������9P�x'_�H��q@)I�a��u�/.�s���:�C�&8����_��_�a�      �   J  x�M�ۑ�0���q��d������a�*����cȻ>�g�Ͽ�����/<������-x~a�]4D�(�8�����(:EQt�ʃJPզT��r�-�z�Z��Z�(3J�Ϭꔂ�z���NaȽܢr�0T.ޟY.���e��N��9k$(���r�0T.f�ϒ���l(-,jˇ���:x��%A}��*�����4���@@X��k`��*)+|�e������>���GX�Kai�#�/�y�>Z�e������>Bo�3�zFOX�wn��$��$z�Gۤ��WY�#,+|�e�����zs_��URO��������	�C���θ:Q�W}.:ju�����R�8fV�ߒ�:?�z��-���C荲G�[c�#{��3v���p��/���hg�9��#��%\~w;�#���b��8"۝|�����S�-��q��$���}���W�8V>���lw
���<��n�St0��^'<KH�������<*V����u������Jz���x�f�G=���}��?f5^9"����{�O�Ϯ��sd`���K��x��7��<�F��X�b����	7�<@g�/����d�
;O���W���M2��S�sk���q�rƎ+����-���ȋ��?��=�����H�������>~���+��1e�_���_�K��՞��ۙyx�Md(z��b����o�H��eg�=~O�L��'/3�>�9�ǟ�ѝ���l��o�_��J��e�����ү�c�����o��+�z���`���L������&����|��덙��~'�տ���`�u^��n��=T{~v���:�c���G��v�C����|]��<�G�s駘����wi�_�6ڊW�@���x�bI�9^�XY��)�,������}tb������8}��7�w�������'"����-"�|N�u�#؞�������O�{Ŀ����w>o����}lt�������Ċ�~���L?��C
�����������h�-��!�<O|E�3g���!���U04jo>�݂��=�,�9�Rg��:�ׯ���	���G|\駛���~~~�|�%      �   A  x���ю�*����y��F���x w���[h����g.&v�AQU o� W@PE�?�{��QK0>����n�B%�u?��7�Č��HEV�@���?���6u@9�䨥�shڗ�ûe���I)�O�h��r��^��z�^�:��S�4
�l_>f������$n^������:��oۥ7y :rE�h�WzRC�3r���+��L�pa���������4Ǧ��"h�c�§�`�׃�%�,�T<Xh�1��ӃV�̣��R�0�Vz�����לQ=~}8��V�A���UxQY�ةV0+�U V� ����6؂�2��ұ��������wk�X��'P|	���D�0�t���������h�Lv�C�$
'+�W�����ш���؛ͤ���҅2*��sǪٻ��(?)���m᳙3���hN�g�T?C,�T|tmPL`���[h�ze�lz�׌��8A��K�>E��eԓ+�n'�H������zL�.9��u��<E7�Nw�M�xTq�Ź0R��������i�"���̀g\wj���%M3-SQ��鋈lg(å2��Wٹp����t꓉���d���3e�����UNr�<�dm���z?dDd$帜���.�, �e@��'��q�G�뚊Far�X�;.�<����-��g%���3�pl���A����)�sq-"�n��������P�M�|(	Fs��T䃯u�V9��Q�2���D]�7Y�����=(�RIH�My9��8b;cia�x�b B�\����JO�T�^�7�����qY��\��Z9R���M�v������h��      �      x������ � �      �     x�u�1n!��<�Z �:�ЗY����(�A׌v��}<�������y������VKCژ��t2M���fZk	��;���t�o��rlNk��lV6+����fe�2�+�ʰ2�+�ʰ2�+��ݜnNw���ݽ�{w��=��a�0gx;��a�0g���9iN�����N���Ls�9Ӝi��v��ӽ�{/�^��{��r�<p|������u8�I��õ��w��C�n�u_��T]i~�}��������ޞw�<�z��q��S�r��R����      �   �  x���AN�0���)|��I�$�)I��R-��q�rcd��ݜaN��G���O�TA�M���{~�s:F�\2�<cj�#~�S4R�]�"��l9�>�I����|��Boz�O� �RG$�ď�(����Ri�~-��ϴ}�S����'��a���>_i�H���i����xQ��P�d)9�W`��~6��;8AΊ�h1t��0B�~Jݬ��!a�NLf�~��IN�g�rm+�Ȁ� �28���-?�t���d?�4��`%N��4��!O������� q;z�!E ���-�Ц~)e�%4r��G|�8,>R�;�`Bb?�]�Ks�����JB54~�]�/��S� ���D�3�`j��惛咃��?��B����g�R����V��(|7r�sq�#*���Jawv�C��g��.�!ޞ�+#�G\j@��e���\,x��Eh��E�6,ܭ5D�S�����F��N��KsB�Mg�l~3���_]�n�YV����e�'P.�76�kS|��R�c(��aJ#���ĔviN�]Z��ma����g����}�����>�BU�^�����Fs�l����Vrà'�d�"�s����c����<��69��Z(�B񷥯&�]�s�s����T      �      x������ � �      �   P   x�3�,��I���,.�,OM�4202�54�5�P04�2��2����2�(KM�ī��,�(5�$�B�ԜT�
c���� ��(�      �      x������ � �      �      x�3�4�2bc 6�=... c�      �   f   x�3�tL����,.)JL�/�,OM�4202�54�5�P04�2��2����2�t�K��LĢ�������1g@bQIfrfAb^Ij1�^+c+#clb\1z\\\ D�&�      �      x������ � �      �      x�}�ɖ�ܶ���UDcw7R�ڠR��2N�)�z�Rvs7������eDd~��'S����9�9ׂafl�oJ��n��{��­�b,f.���V���5tf������@�t��!�1W�ts�6/��8�R9!�`u��#��Y(^T�	���Ga�'�C��@�qrZ�~ 0<[�7��~�U�,)N��M�t�u�S�LW����>�¡�C���e2\V��4�ی�+��M Q��ЧvBf��`�� ���M(����`�?�j gx*"���4z&Eҹ�.#.-��Q��"t���U�qdi�[�ň��|���;AJ���v'�fZf�MOA05�����37�n��yS���U���}�(k��<�lL�6I��r\�y4&KBh��W���~��w����x�l�V?�r���v_~~Q�o�[En=����ÿ�̍S�+�ox(tmO�>��j�k鰮�4m�ۮ
\���AX�k��� ��g<yG�i�7�6����#��Y�������0�8z$�o���L�YUP�:qxp(��6��M�+%�Wv;����3g��fDJ6��rʓ%��y:�kO��m7Vz+m�f�Y��ΎծNI���or���w���4�?a�4�;���������Y���=KZ/�\�6����3�_��h�}#�ѳ�3Lbh�<1ۤ:���/���lBLWT���2w�v[��c[}����������mS�Q���Ͳ����}���F�P��K���Zt�֛#���t�T=�m-�����Rm2�󑉺��:�Q?�_�ӌ�.K3NS��eu��a�iFA?����z��~��ǅ�n!uՓC����钜t�g��vN�G0���Q�q��_����X��ϛ���8H߬ ���lA ���[5�_ݼ	�iR���]���M���!�ǀ��mm��`�<�d5�R�P��ġ�I�;�h��H��of[��߱�8t��X�+y�1�U�*i���2��B��]A<۞m^7E�rp����	:���I���G�[��螂�M������0
n偩��F=�h�hͪ��F��'�����ϕ�ܷ�
F�7n�:_u��+(�N�$*AMk ����p�z�d|���cz��up�ׇ.����ԸqQأ~�)�å~`�/cŊSp�;���8j�s�;�Z O��U�iZ�튪
�?A�i�
[Q}e�IS��6v���ՐN�QI:��)�j.�c2�n�[��	�ڴ@���"uߌ�C	0�#9M���RZ�eb��o�.�����RSpGؖ��v���b%�]�=zB9I����c@��/G�Ȃ�饦��aϣ�e<Z�!�F8��x�m�a�V]M_x���k��h2�S��'�@�{�O���¿�&���껓Ȯ����ӈ��(��F�gh�iH�[[o;�BKV��22K�݌D��p&�:�M�����R���t��N��	�Lí��u���ץ@��2��^*��¾j��
��%��w�:�m�g�I8�� �1�wS�������q�����N�4t��u�&�溶��D�"ۤ?���p��\k�#hҧ��ak��u��>l&U������"�~��ad��LRB<�6sE��i�=�w {$���:�v�/67�/�}�i���&8�w��&5��Oq>�H'"v�κV���A��(!K��aR� ����.��Ϗ���9%L�#HlĴ���P�H� �?���E�0����%y��8�Ö��,(�q��%}�4�;r��h�
�-�~j� ����4�F��/��E��Um ����:G���W+m//m>��!՜�-�7"JJ[]ӥ��&!��� 53b�^q�qՄC@^d�=p�:�!݆%z\;4ԗ1L���̜ۅ� �;�΅&�t3�1 ���4�2FP�nओ[U� y�/���ug��������%��%����D�id�;�Zi3f�%�4�`���^��6fv�bqt���7%u��t���ץ%����v���x�>`�����Ѥ���J:n��v3���N�'�
hPqNk��#��U�<�Y��nuf��ge�����Z��U�Zso�*S���R�1'��D@Г;�� �q]���W5�ث���	^�U	"3Fn��b ��<�F�F�ɂ4dSÿ�K]�j�D�E���0t�ʫ�7����"Uh��Ms����](J�W&
sWN�cx��a�U���Y'�M;1�(����4 ����7vp�G��!��ZA�R8��	���F�Bu�
��K��X���T:�ʸ� ӝ�����i��}i ��=?.2�EV�hq\VWY�,���ڧYZ3����AUa4X����ILX�?U�ϋ���Ȉ��6`q�t/�%P���Q���	�!�vQA�b�n�粖���Oⳁ����{����b�c��� 9S��.�{����D_$)ݻ���ح`�51vM�!#^/����-ߞZ�*�qW(	{N\O$�~6�_��>Ç��f�M���=�ދ�`�Y�X ��{��Y3*�@xCԔ�<�ަF�̨�p珎�K���{�t������-�/�C���Ȱ=~(�E�G����r�.�Dkr��\��<r[nr�K1A�&2?g�/�1�\;wO`�7���E����:o�t�8����;,pn,��ˍ�}û>���[l��r�z���5�Ϭ�c<��>�/�)��l��1��j󌒧�@�&��N�P��)����l&�����B�\��D� �c��� :R׏�W�	{�]���^��{��(b�!s_�@�]`�u�3��h��U�;�ݥ��U��c`�Mk ���8^�]�ED��� ���9�
���l��vA��ƨ7^�n->`����C �z<aN0�~4�_`#fj�����^D<��4Re��7���v�~���V�[F#�Ԓ��ڝkʲ�꺵eb¥c(n�5 vo��x\|4�����a/B�Z�U����L6�M��:�[. r9���^wQ��I;#��c��6��?M���~�
���E�>��vEO����ZzA͋�5�i;�F��W?'��֛��@�.6�uH���F=G�/�ѿʲH�
��0t��8z/b�V�Vj��V�Z�lFJ�r�쑑5�N$%*S5��na�(y��sr��8ϻ���[�b]꿬������,lM�D�xUr��������!��@�s�����2 '�@fx4)_�������[�����ՔIvC�2����^"D����<*3S��r��o��ؒ5l�X�ft���w�AI�jR+�QGK�ֶ*�͜-�|cr�ӻ:o�h)��e��;��c��[	��t��\�n
��8Q�K�%N�����k����a�A����Zs�N/1��od{�,��9��P��Ͳ��Qj�B�w{^�t!`��9�v8b��[2�pC�*����K6�Q?t�M<�m���Ouo~TE�6�,=]��Eo���q���y(�e�m�m`E��<<�W+ׅ�9C^5J��
G���omU��N'�[軓���z�2����cx;2膅.K���L��QhZe�?,-�~���
�]VKyV�^N����/&)�hM��Mѳ_Q�J,[d<�٘�aб�T��b���ym��lds�[3�9�v���Ǖj�Zbco�Pjq�Ô��T�#����q�qm��0=��͍�0���o3�dH�ӏ䠸}��|�L��1���dy�^�/µ��M;R���"#��T�����p'�>��#pW�:f�>���nOq�\)1-��Q��#�����˚�Tc.V"�q�].9��`��M�R���}�>;z ����U�f�0��^�?MS-V��V��|��_%P+S�R���r��M��J��Xb�ڗ�*{JuJN5z�;�˂~-)a���B޵��9{x�bH�A�R��I �<����٢��&��G�*�>NB<�Wyw�U�C>o8�T��#foB� ���իI�����ߺ��K��u��������X��<�h�x�0z���=Ѭ���"s�p٭/v�5�RYG=�#̓� r  ��Q?�w�2�X�/����"q��2�$+YX߬K��ju[�RϮ�|s*���Z��ǹ�-%�O^��QWU� ��G!(�ML�B�*8�a��t�9�ĸ��'-�:7c�@"@<���4 �ξ�J�ă<��^8�9.0st�d٫p
j���p�eM�^c�s�펋��#4Ӟ�	��#�G9_��fb}%��8��c>���+T]X��*Ǉ�!Y	�C�xmV}}��!��y�J	��!��M�}G��>5��q�»���������Nڒ���`U�䓅�H��^��6�n�̭��8a�p0��#ff��4�Vワ�_��b�Ůu�;�\�j���Z��M�P��IQ����ح;oW˱id;�ֈgW��,r��#�u@1D�`/ʅ��[����Q?��)s53��iX�(���~��~3�1�_�$w�#�+�/��33��Tq�<n�N��w�S��2��^���=�D<�+,�Kr]T5��MrY���b���/�K0ߺ^n��#�#>?7���^�Mak{|�qp���5�w�bD���mh��]��D�E�ϻ�K�~�0����b��~���E��,���W����j��\�r()�+}��$/TھL.�zN��?�?Q6���4���:�	r��慄��U��}xCZv���g��������
D-���q�;k3劵����@�o�Z��?!~j �U4՚&^��eb�A����C_���k�t�H%	�nDaA�����hc���Ċ#����/`a�Ӑ"_�1�wTZ2��b�`B#d3V�*��0���j����u��q��::q8����6,��#�Xq��OH��m�/������ւ�B;�6��$ۋ$��v���}�F��6=�&Z��<��_`#�>�҃*΃��(�x�V0խ��u�̏i�+^;�T�K�&.�Xٞ�j��-���>5t��k�h ���αr��n���qɽ舌+�X���RX<����XM��ё���`Ҳ����y��ev��h��;�4��`��r�wg{"��WM�Ro	�"���$�p�~�z-y����#��~q4���Eog5	)hw�6AH?7��4@�̔6�����EV�+�N���q��|y��#�Y9��ha�����8��g�����	�D���K�~P�G_u�߬��F���iu�Tc�"�>:���n��F�"�y��.1¹ü5������/��� (���Ӕ�m���g���E�%��T�./;����lMxt���&խ5���3�)�N`��xJ8e�o ~�"�WC/
�E6mQU7�H��馌+���5ad�'p�Y�Hlr�g+>Y��)�/�dᘏ�}ȴ�Y��~�����'����f�˪��:¦I�.km��Ym�{f���{ES��a��)O �۞�>5�����9�^�m�fE���𵒵B�c��u#�n�i3��=��B�Q\D`bO���}5����xA�������/�)�{��>�0�E����ii>�:�Z03�id��TsKyyX�y��Ǜ�7M��UG��5����ߟ�{��{�i��߹w~����L��џo񘟫���0�CD�^I��6�s�Qu=�2F��s������d~ՄK���7#�C�/��C���dw8�oQ{8�c��*)��|���ȩw�E�Bn�^��O�s��E������q���H�����"���`(��xQm]�.%�ݴ�@�8���b�-b�^�s��Yx�N "���o "��ϊ}7�h��G�Ї��Mb���-�i�G�@�KI�xX.7m�$�L�ls<ƞ��OY�~���z�� :��7\j`�����^D�v�U����'#�˃x�C{cn{к�yL/��K�<׽:����(pbZd��
���/	=���^�;H�Yܨ����Ƭ+�9�����^6����v���堠��x� �4�� �g܇CYߓ���^D;���W[:!�@��qy�!FO9�o��M�a�ݽc���v�W?0������o��=��H/���\c��N5�����`�l�iQ�&ޙ��T�Ɲ��q1֔��'���z	���- `�|���*ҋ�|a�ue�Y�H�Ց�E�|#�Cd�j���*tX<Xv'�lv<s*�q*} �<h�e�)
xy�3���&���)vga��m�㹺���'�{�[D�e��r�W�χf��G��0� ��|����ǖ4
l��8�ķS~�m��bS�4����ߧٰuy��=�sE�;���$`9_�fbzcwbHwGA"E�	��:�� �H�5�v�$>����ס�݃���/�'WD�_���[)��|1�[B�E&43k4O0��	[�k����nAU8���uW���5���̞11�J��0A
�����{����"(}��ϙC�V3�[�>U�8Rk{%�*�L��\� �,s��Ⱦܧ�;1�Xj�����e�;�Z�������/Z�!+��l�'(�������d��d��x�3B$�Z�P�-�p�>���5��V@ ��' $E$/-�H�]\Mm#5\q��β)�]�]�}D�3�܋.���q/1+���LE��	�k��~x��`+|TBo���^A�ˀ�"0ӽ����`_g3��h��8���0�����
�?Pyl��7������&��� :�Al���{�(�ʁ���+�֒��{�P�q�	h�(�X'���.ǝC��M�%�D�}�N��;`�x�����v����E:���{��H)4)`D����������-�[꯽���zu6��Eu돈�]d�L#uw$|�XSi~|zeto����٠s��|	���QgK\�$��,�Cso!�(g�PS<B����8����ɏ��=Q�(�._
#<7�̭m<Ĵ�\#͹��.�="7[T?�K���q��� ��%�A~��_�҈��ӹ�-AJg��ݿ����1��7����[�@�#��
���Ŧ&���a&i,�����˴�d�)	�n0�4��NL"���l``��e̠��&UL>�:P{{�x�� 3�Q?��kDA����:��m3�>�8���>���̖���y�$�~#�ʩ	�U����2Ǐ־��lVm_���d��30|��uk�땷k|E��\��4�)N���V"�Y����F3�x�w�q�y�<�C��E��g�n(r��w��&Qř �J&�UY��V�ǽ~���b��Si�qx�����mf�
I4�k�z�Y>��7���������?|a�bW�h֯��.y�"�,�u��N�1Vy��t�@�]�����H�����'�u�!���,���j�����/��'_j������F��]��R�c����M��xKR=럂x�C�~r�d%���s���-�E�Z+��Zm�>�^/n�M�M�\[;�WQ�fK9[#m��do��߫%ހ'C��]�?4�X_Wqu��ofPu��� �����Zɋ6�)<Z�c�!JQ�_nnq��2L�#�W�Z��孝:�oV��Jm�Ew��^{���߭�Ž/�� @����s���_��jNh���!�!��R����rt�Avv���:�oO�gy~X<�\��n��}�����Ǐ�a]�     