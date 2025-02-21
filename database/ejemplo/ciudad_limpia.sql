--
-- PostgreSQL database dump
--

-- Dumped from database version 17.0
-- Dumped by pg_dump version 17.0

-- Started on 2025-02-21 10:52:53

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 223 (class 1259 OID 34122)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- TOC entry 222 (class 1259 OID 34121)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5060 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 244 (class 1259 OID 34271)
-- Name: incidencias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.incidencias (
    id bigint NOT NULL,
    tipo character varying(255) NOT NULL,
    ubicacion character varying(255) NOT NULL,
    descripcion text NOT NULL,
    fecha timestamp(0) without time zone NOT NULL,
    estado character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    prioridad character varying(255) NOT NULL,
    latitud numeric(10,8),
    longitud numeric(11,8),
    infraestructura_id bigint NOT NULL,
    tecnico_id bigint,
    ciudadano_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.incidencias OWNER TO postgres;

--
-- TOC entry 243 (class 1259 OID 34270)
-- Name: incidencias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.incidencias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.incidencias_id_seq OWNER TO postgres;

--
-- TOC entry 5061 (class 0 OID 0)
-- Dependencies: 243
-- Name: incidencias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.incidencias_id_seq OWNED BY public.incidencias.id;


--
-- TOC entry 231 (class 1259 OID 34165)
-- Name: incidents; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.incidents (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    description text NOT NULL,
    latitude numeric(10,8) NOT NULL,
    longitude numeric(11,8) NOT NULL,
    status_id bigint NOT NULL,
    infrastructure_id bigint NOT NULL,
    assigned_to bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    priority character varying(255) DEFAULT 'Media'::character varying NOT NULL,
    priority_color character varying(255) GENERATED ALWAYS AS (
CASE
    WHEN ((priority)::text = 'Baja'::text) THEN 'success'::text
    WHEN ((priority)::text = 'Media'::text) THEN 'warning'::text
    WHEN ((priority)::text = 'Alta'::text) THEN 'danger'::text
    WHEN ((priority)::text = 'Crítica'::text) THEN 'dark'::text
    ELSE 'primary'::text
END) STORED,
    CONSTRAINT incidents_priority_check CHECK (((priority)::text = ANY ((ARRAY['Baja'::character varying, 'Media'::character varying, 'Alta'::character varying, 'Crítica'::character varying])::text[])))
);


ALTER TABLE public.incidents OWNER TO postgres;

--
-- TOC entry 230 (class 1259 OID 34164)
-- Name: incidents_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.incidents_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.incidents_id_seq OWNER TO postgres;

--
-- TOC entry 5062 (class 0 OID 0)
-- Dependencies: 230
-- Name: incidents_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.incidents_id_seq OWNED BY public.incidents.id;


--
-- TOC entry 242 (class 1259 OID 34261)
-- Name: infraestructuras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.infraestructuras (
    id bigint NOT NULL,
    tipo character varying(255) NOT NULL,
    ubicacion character varying(255) NOT NULL,
    descripcion text NOT NULL,
    estado character varying(255) DEFAULT 'operativo'::character varying NOT NULL,
    ultima_revision timestamp(0) without time zone,
    historial_mantenimiento json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.infraestructuras OWNER TO postgres;

--
-- TOC entry 241 (class 1259 OID 34260)
-- Name: infraestructuras_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.infraestructuras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.infraestructuras_id_seq OWNER TO postgres;

--
-- TOC entry 5063 (class 0 OID 0)
-- Dependencies: 241
-- Name: infraestructuras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.infraestructuras_id_seq OWNED BY public.infraestructuras.id;


--
-- TOC entry 227 (class 1259 OID 34146)
-- Name: infrastructures; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.infrastructures (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    address character varying(255) NOT NULL,
    latitude numeric(10,8) NOT NULL,
    longitude numeric(11,8) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.infrastructures OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 34145)
-- Name: infrastructures_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.infrastructures_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.infrastructures_id_seq OWNER TO postgres;

--
-- TOC entry 5064 (class 0 OID 0)
-- Dependencies: 226
-- Name: infrastructures_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.infrastructures_id_seq OWNED BY public.infrastructures.id;


--
-- TOC entry 259 (class 1259 OID 34400)
-- Name: jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO postgres;

--
-- TOC entry 258 (class 1259 OID 34399)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO postgres;

--
-- TOC entry 5065 (class 0 OID 0)
-- Dependencies: 258
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 252 (class 1259 OID 34346)
-- Name: mantenimiento_preventivo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.mantenimiento_preventivo (
    id bigint NOT NULL,
    infraestructura_id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text NOT NULL,
    frecuencia character varying(255) NOT NULL,
    dias_frecuencia integer NOT NULL,
    ultima_ejecucion date,
    proxima_ejecucion date NOT NULL,
    checklist json,
    costo_estimado numeric(10,2),
    duracion_estimada integer NOT NULL,
    materiales_requeridos json,
    personal_requerido json,
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT mantenimiento_preventivo_frecuencia_check CHECK (((frecuencia)::text = ANY ((ARRAY['diaria'::character varying, 'semanal'::character varying, 'mensual'::character varying, 'trimestral'::character varying, 'semestral'::character varying, 'anual'::character varying])::text[])))
);


ALTER TABLE public.mantenimiento_preventivo OWNER TO postgres;

--
-- TOC entry 5066 (class 0 OID 0)
-- Dependencies: 252
-- Name: COLUMN mantenimiento_preventivo.duracion_estimada; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.mantenimiento_preventivo.duracion_estimada IS 'en minutos';


--
-- TOC entry 251 (class 1259 OID 34345)
-- Name: mantenimiento_preventivo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.mantenimiento_preventivo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.mantenimiento_preventivo_id_seq OWNER TO postgres;

--
-- TOC entry 5067 (class 0 OID 0)
-- Dependencies: 251
-- Name: mantenimiento_preventivo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mantenimiento_preventivo_id_seq OWNED BY public.mantenimiento_preventivo.id;


--
-- TOC entry 248 (class 1259 OID 34311)
-- Name: materiales; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.materiales (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text NOT NULL,
    cantidad_disponible integer NOT NULL,
    costo_unitario numeric(10,2) NOT NULL,
    unidad_medida character varying(255) NOT NULL,
    stock_minimo integer NOT NULL,
    stock_maximo integer,
    ubicacion_almacen character varying(255),
    codigo_interno character varying(255) NOT NULL,
    proveedores json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.materiales OWNER TO postgres;

--
-- TOC entry 247 (class 1259 OID 34310)
-- Name: materiales_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.materiales_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.materiales_id_seq OWNER TO postgres;

--
-- TOC entry 5068 (class 0 OID 0)
-- Dependencies: 247
-- Name: materiales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.materiales_id_seq OWNED BY public.materiales.id;


--
-- TOC entry 218 (class 1259 OID 34097)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 34096)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- TOC entry 5069 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 238 (class 1259 OID 34225)
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO postgres;

--
-- TOC entry 239 (class 1259 OID 34235)
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO postgres;

--
-- TOC entry 257 (class 1259 OID 34391)
-- Name: notifications; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.notifications (
    id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id bigint NOT NULL,
    data text NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.notifications OWNER TO postgres;

--
-- TOC entry 250 (class 1259 OID 34322)
-- Name: ordenes_trabajo; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.ordenes_trabajo (
    id bigint NOT NULL,
    codigo character varying(255) NOT NULL,
    incidencia_id bigint,
    infraestructura_id bigint NOT NULL,
    tipo character varying(255) NOT NULL,
    estado character varying(255) NOT NULL,
    prioridad character varying(255) NOT NULL,
    descripcion text NOT NULL,
    fecha_programada timestamp(0) without time zone NOT NULL,
    fecha_inicio timestamp(0) without time zone,
    fecha_fin timestamp(0) without time zone,
    costo_estimado numeric(10,2),
    costo_real numeric(10,2),
    materiales_requeridos json,
    personal_asignado json,
    observaciones text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT ordenes_trabajo_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'en_proceso'::character varying, 'completada'::character varying, 'cancelada'::character varying])::text[]))),
    CONSTRAINT ordenes_trabajo_prioridad_check CHECK (((prioridad)::text = ANY ((ARRAY['baja'::character varying, 'media'::character varying, 'alta'::character varying, 'critica'::character varying])::text[]))),
    CONSTRAINT ordenes_trabajo_tipo_check CHECK (((tipo)::text = ANY ((ARRAY['correctivo'::character varying, 'preventivo'::character varying])::text[])))
);


ALTER TABLE public.ordenes_trabajo OWNER TO postgres;

--
-- TOC entry 249 (class 1259 OID 34321)
-- Name: ordenes_trabajo_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.ordenes_trabajo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ordenes_trabajo_id_seq OWNER TO postgres;

--
-- TOC entry 5070 (class 0 OID 0)
-- Dependencies: 249
-- Name: ordenes_trabajo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ordenes_trabajo_id_seq OWNED BY public.ordenes_trabajo.id;


--
-- TOC entry 221 (class 1259 OID 34114)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 34208)
-- Name: permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO postgres;

--
-- TOC entry 234 (class 1259 OID 34207)
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permissions_id_seq OWNER TO postgres;

--
-- TOC entry 5071 (class 0 OID 0)
-- Dependencies: 234
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- TOC entry 246 (class 1259 OID 34296)
-- Name: personal; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    especialidad character varying(255) NOT NULL,
    disponibilidad character varying(255) NOT NULL,
    habilidades json,
    telefono character varying(255) NOT NULL,
    direccion character varying(255) NOT NULL,
    notas text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT personal_disponibilidad_check CHECK (((disponibilidad)::text = ANY ((ARRAY['disponible'::character varying, 'ocupado'::character varying, 'ausente'::character varying])::text[])))
);


ALTER TABLE public.personal OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 34134)
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 34133)
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- TOC entry 5072 (class 0 OID 0)
-- Dependencies: 224
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 245 (class 1259 OID 34295)
-- Name: personal_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_id_seq OWNER TO postgres;

--
-- TOC entry 5073 (class 0 OID 0)
-- Dependencies: 245
-- Name: personal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_id_seq OWNED BY public.personal.id;


--
-- TOC entry 254 (class 1259 OID 34362)
-- Name: presupuestos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.presupuestos (
    id bigint NOT NULL,
    "año" integer NOT NULL,
    mes integer NOT NULL,
    monto_asignado numeric(12,2) NOT NULL,
    monto_ejecutado numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    monto_comprometido numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    categoria character varying(255) NOT NULL,
    zona character varying(255),
    desglose json,
    notas text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.presupuestos OWNER TO postgres;

--
-- TOC entry 253 (class 1259 OID 34361)
-- Name: presupuestos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.presupuestos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.presupuestos_id_seq OWNER TO postgres;

--
-- TOC entry 5074 (class 0 OID 0)
-- Dependencies: 253
-- Name: presupuestos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.presupuestos_id_seq OWNED BY public.presupuestos.id;


--
-- TOC entry 240 (class 1259 OID 34245)
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 34217)
-- Name: roles; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    color character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO postgres;

--
-- TOC entry 236 (class 1259 OID 34216)
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO postgres;

--
-- TOC entry 5075 (class 0 OID 0)
-- Dependencies: 236
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- TOC entry 256 (class 1259 OID 34375)
-- Name: rutas; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.rutas (
    id bigint NOT NULL,
    personal_id bigint NOT NULL,
    fecha date NOT NULL,
    ordenes_trabajo json NOT NULL,
    puntos json NOT NULL,
    distancia_total numeric(8,2) NOT NULL,
    tiempo_estimado integer NOT NULL,
    estado character varying(255) NOT NULL,
    hora_inicio timestamp(0) without time zone,
    hora_fin timestamp(0) without time zone,
    metricas json,
    observaciones text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT rutas_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'en_proceso'::character varying, 'completada'::character varying, 'cancelada'::character varying])::text[])))
);


ALTER TABLE public.rutas OWNER TO postgres;

--
-- TOC entry 5076 (class 0 OID 0)
-- Dependencies: 256
-- Name: COLUMN rutas.ordenes_trabajo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.ordenes_trabajo IS 'Array de IDs de órdenes de trabajo';


--
-- TOC entry 5077 (class 0 OID 0)
-- Dependencies: 256
-- Name: COLUMN rutas.puntos; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.puntos IS 'Array de coordenadas ordenadas';


--
-- TOC entry 5078 (class 0 OID 0)
-- Dependencies: 256
-- Name: COLUMN rutas.distancia_total; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.distancia_total IS 'en kilómetros';


--
-- TOC entry 5079 (class 0 OID 0)
-- Dependencies: 256
-- Name: COLUMN rutas.tiempo_estimado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.tiempo_estimado IS 'en minutos';


--
-- TOC entry 5080 (class 0 OID 0)
-- Dependencies: 256
-- Name: COLUMN rutas.metricas; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.metricas IS 'Tiempo real, distancia real, etc.';


--
-- TOC entry 255 (class 1259 OID 34374)
-- Name: rutas_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.rutas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.rutas_id_seq OWNER TO postgres;

--
-- TOC entry 5081 (class 0 OID 0)
-- Dependencies: 255
-- Name: rutas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rutas_id_seq OWNED BY public.rutas.id;


--
-- TOC entry 233 (class 1259 OID 34189)
-- Name: status; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.status (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    color character varying(255) DEFAULT 'primary'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.status OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 34188)
-- Name: status_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.status_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.status_id_seq OWNER TO postgres;

--
-- TOC entry 5082 (class 0 OID 0)
-- Dependencies: 232
-- Name: status_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.status_id_seq OWNED BY public.status.id;


--
-- TOC entry 229 (class 1259 OID 34155)
-- Name: statuses; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.statuses (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    color character varying(255) DEFAULT '#6c757d'::character varying NOT NULL,
    description character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.statuses OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 34154)
-- Name: statuses_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.statuses_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.statuses_id_seq OWNER TO postgres;

--
-- TOC entry 5083 (class 0 OID 0)
-- Dependencies: 228
-- Name: statuses_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.statuses_id_seq OWNED BY public.statuses.id;


--
-- TOC entry 220 (class 1259 OID 34104)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'ciudadano'::character varying NOT NULL,
    CONSTRAINT users_role_check CHECK (((role)::text = ANY ((ARRAY['admin'::character varying, 'supervisor'::character varying, 'tecnico'::character varying, 'ciudadano'::character varying])::text[])))
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 219 (class 1259 OID 34103)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 5084 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4754 (class 2604 OID 34125)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4769 (class 2604 OID 34274)
-- Name: incidencias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias ALTER COLUMN id SET DEFAULT nextval('public.incidencias_id_seq'::regclass);


--
-- TOC entry 4760 (class 2604 OID 34168)
-- Name: incidents id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidents ALTER COLUMN id SET DEFAULT nextval('public.incidents_id_seq'::regclass);


--
-- TOC entry 4767 (class 2604 OID 34264)
-- Name: infraestructuras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infraestructuras ALTER COLUMN id SET DEFAULT nextval('public.infraestructuras_id_seq'::regclass);


--
-- TOC entry 4757 (class 2604 OID 34149)
-- Name: infrastructures id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infrastructures ALTER COLUMN id SET DEFAULT nextval('public.infrastructures_id_seq'::regclass);


--
-- TOC entry 4780 (class 2604 OID 34403)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4774 (class 2604 OID 34349)
-- Name: mantenimiento_preventivo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo ALTER COLUMN id SET DEFAULT nextval('public.mantenimiento_preventivo_id_seq'::regclass);


--
-- TOC entry 4772 (class 2604 OID 34314)
-- Name: materiales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales ALTER COLUMN id SET DEFAULT nextval('public.materiales_id_seq'::regclass);


--
-- TOC entry 4751 (class 2604 OID 34100)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4773 (class 2604 OID 34325)
-- Name: ordenes_trabajo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo ALTER COLUMN id SET DEFAULT nextval('public.ordenes_trabajo_id_seq'::regclass);


--
-- TOC entry 4765 (class 2604 OID 34211)
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- TOC entry 4771 (class 2604 OID 34299)
-- Name: personal id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal ALTER COLUMN id SET DEFAULT nextval('public.personal_id_seq'::regclass);


--
-- TOC entry 4756 (class 2604 OID 34137)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 4776 (class 2604 OID 34365)
-- Name: presupuestos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos ALTER COLUMN id SET DEFAULT nextval('public.presupuestos_id_seq'::regclass);


--
-- TOC entry 4766 (class 2604 OID 34220)
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- TOC entry 4779 (class 2604 OID 34378)
-- Name: rutas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas ALTER COLUMN id SET DEFAULT nextval('public.rutas_id_seq'::regclass);


--
-- TOC entry 4763 (class 2604 OID 34192)
-- Name: status id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status ALTER COLUMN id SET DEFAULT nextval('public.status_id_seq'::regclass);


--
-- TOC entry 4758 (class 2604 OID 34158)
-- Name: statuses id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.statuses ALTER COLUMN id SET DEFAULT nextval('public.statuses_id_seq'::regclass);


--
-- TOC entry 4752 (class 2604 OID 34107)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 5018 (class 0 OID 34122)
-- Dependencies: 223
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 5039 (class 0 OID 34271)
-- Dependencies: 244
-- Data for Name: incidencias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.incidencias (id, tipo, ubicacion, descripcion, fecha, estado, prioridad, latitud, longitud, infraestructura_id, tecnico_id, ciudadano_id, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5026 (class 0 OID 34165)
-- Dependencies: 231
-- Data for Name: incidents; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.incidents (id, title, description, latitude, longitude, status_id, infrastructure_id, assigned_to, created_at, updated_at, deleted_at, priority) FROM stdin;
\.


--
-- TOC entry 5037 (class 0 OID 34261)
-- Dependencies: 242
-- Data for Name: infraestructuras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.infraestructuras (id, tipo, ubicacion, descripcion, estado, ultima_revision, historial_mantenimiento, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5022 (class 0 OID 34146)
-- Dependencies: 227
-- Data for Name: infrastructures; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.infrastructures (id, name, description, address, latitude, longitude, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5054 (class 0 OID 34400)
-- Dependencies: 259
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 5047 (class 0 OID 34346)
-- Dependencies: 252
-- Data for Name: mantenimiento_preventivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mantenimiento_preventivo (id, infraestructura_id, nombre, descripcion, frecuencia, dias_frecuencia, ultima_ejecucion, proxima_ejecucion, checklist, costo_estimado, duracion_estimada, materiales_requeridos, personal_requerido, activo, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5043 (class 0 OID 34311)
-- Dependencies: 248
-- Data for Name: materiales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.materiales (id, nombre, descripcion, cantidad_disponible, costo_unitario, unidad_medida, stock_minimo, stock_maximo, ubicacion_almacen, codigo_interno, proveedores, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5013 (class 0 OID 34097)
-- Dependencies: 218
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
4	2019_12_14_000001_create_personal_access_tokens_table	1
5	2024_01_01_000000_create_infrastructures_table	1
6	2024_01_01_000000_create_statuses_table	1
7	2024_01_01_000001_create_incidents_table	1
8	2024_01_01_000001_create_status_table	1
9	2024_02_21_000000_add_priority_to_incidents_table	1
10	2024_02_21_000002_add_color_to_roles_table	1
11	2025_02_17_212540_create_infraestructuras_table	1
12	2025_02_17_212541_create_incidencias_table	1
13	2025_02_17_212542_create_personal_table	1
14	2025_02_17_212543_create_materiales_table	1
15	2025_02_17_212544_create_ordenes_trabajo_table	1
16	2025_02_17_212545_create_mantenimiento_preventivo_table	1
17	2025_02_17_212546_create_presupuestos_table	1
18	2025_02_17_212547_create_rutas_table	1
19	2025_02_17_215639_add_role_to_users_table	1
20	2025_02_17_234342_create_notifications_table	1
21	2025_02_18_033247_create_jobs_table	1
22	2025_02_21_065559_add_soft_deletes_to_incidents_table	1
23	2025_02_21_075548_remove_role_column_from_users_table	1
24	2025_02_21_080231_add_color_to_roles_table	1
\.


--
-- TOC entry 5033 (class 0 OID 34225)
-- Dependencies: 238
-- Data for Name: model_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_permissions (permission_id, model_type, model_id) FROM stdin;
\.


--
-- TOC entry 5034 (class 0 OID 34235)
-- Dependencies: 239
-- Data for Name: model_has_roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.model_has_roles (role_id, model_type, model_id) FROM stdin;
1	App\\Models\\User	1
2	App\\Models\\User	2
3	App\\Models\\User	3
\.


--
-- TOC entry 5052 (class 0 OID 34391)
-- Dependencies: 257
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notifications (id, type, notifiable_type, notifiable_id, data, read_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5045 (class 0 OID 34322)
-- Dependencies: 250
-- Data for Name: ordenes_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ordenes_trabajo (id, codigo, incidencia_id, infraestructura_id, tipo, estado, prioridad, descripcion, fecha_programada, fecha_inicio, fecha_fin, costo_estimado, costo_real, materiales_requeridos, personal_asignado, observaciones, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5016 (class 0 OID 34114)
-- Dependencies: 221
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5030 (class 0 OID 34208)
-- Dependencies: 235
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.permissions (id, name, guard_name, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5041 (class 0 OID 34296)
-- Dependencies: 246
-- Data for Name: personal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal (id, user_id, especialidad, disponibilidad, habilidades, telefono, direccion, notas, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5020 (class 0 OID 34134)
-- Dependencies: 225
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5049 (class 0 OID 34362)
-- Dependencies: 254
-- Data for Name: presupuestos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.presupuestos (id, "año", mes, monto_asignado, monto_ejecutado, monto_comprometido, categoria, zona, desglose, notas, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5035 (class 0 OID 34245)
-- Dependencies: 240
-- Data for Name: role_has_permissions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.role_has_permissions (permission_id, role_id) FROM stdin;
\.


--
-- TOC entry 5032 (class 0 OID 34217)
-- Dependencies: 237
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.roles (id, name, guard_name, color, created_at, updated_at) FROM stdin;
1	Administrador	web	#FF4444	2025-02-21 08:06:17	2025-02-21 08:06:17
2	Técnico	web	#33B679	2025-02-21 08:06:17	2025-02-21 08:06:17
3	Ciudadano	web	#3366CC	2025-02-21 08:06:17	2025-02-21 08:06:17
\.


--
-- TOC entry 5051 (class 0 OID 34375)
-- Dependencies: 256
-- Data for Name: rutas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rutas (id, personal_id, fecha, ordenes_trabajo, puntos, distancia_total, tiempo_estimado, estado, hora_inicio, hora_fin, metricas, observaciones, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 5028 (class 0 OID 34189)
-- Dependencies: 233
-- Data for Name: status; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.status (id, name, color, created_at, updated_at) FROM stdin;
1	Pendiente	warning	2025-02-21 08:06:17	2025-02-21 08:06:17
2	En Proceso	info	2025-02-21 08:06:17	2025-02-21 08:06:17
3	Resuelto	success	2025-02-21 08:06:17	2025-02-21 08:06:17
\.


--
-- TOC entry 5024 (class 0 OID 34155)
-- Dependencies: 229
-- Data for Name: statuses; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.statuses (id, name, color, description, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5015 (class 0 OID 34104)
-- Dependencies: 220
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role) FROM stdin;
1	Administrador	admin@ciudadlimpia.com	\N	$2y$10$Y.gKOr6Tkgg1zQEQ.JFaqOm0W26IyTOD6TtWXUbv7ydmSXVW.74Zi	\N	2025-02-21 08:06:17	2025-02-21 08:06:17	ciudadano
2	Técnico	tecnico@ciudadlimpia.com	\N	$2y$10$BQe1Xkt0Vc0HIxu6WgKx9Or/0Xxlq7w61NOFgSBSquy1gPqtQmZMu	\N	2025-02-21 08:06:17	2025-02-21 08:06:17	ciudadano
3	Ciudadano	ciudadano@ciudadlimpia.com	\N	$2y$10$3Zjt8srUiwSmVRI1FLaWvu.PR2wRvMhpMJybYnd.5FsZo5U9.ZPbK	\N	2025-02-21 08:06:17	2025-02-21 08:06:17	ciudadano
\.


--
-- TOC entry 5085 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5086 (class 0 OID 0)
-- Dependencies: 243
-- Name: incidencias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.incidencias_id_seq', 1, false);


--
-- TOC entry 5087 (class 0 OID 0)
-- Dependencies: 230
-- Name: incidents_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.incidents_id_seq', 1, false);


--
-- TOC entry 5088 (class 0 OID 0)
-- Dependencies: 241
-- Name: infraestructuras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.infraestructuras_id_seq', 1, false);


--
-- TOC entry 5089 (class 0 OID 0)
-- Dependencies: 226
-- Name: infrastructures_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.infrastructures_id_seq', 1, false);


--
-- TOC entry 5090 (class 0 OID 0)
-- Dependencies: 258
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5091 (class 0 OID 0)
-- Dependencies: 251
-- Name: mantenimiento_preventivo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mantenimiento_preventivo_id_seq', 1, false);


--
-- TOC entry 5092 (class 0 OID 0)
-- Dependencies: 247
-- Name: materiales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.materiales_id_seq', 1, false);


--
-- TOC entry 5093 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 24, true);


--
-- TOC entry 5094 (class 0 OID 0)
-- Dependencies: 249
-- Name: ordenes_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ordenes_trabajo_id_seq', 1, false);


--
-- TOC entry 5095 (class 0 OID 0)
-- Dependencies: 234
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.permissions_id_seq', 1, false);


--
-- TOC entry 5096 (class 0 OID 0)
-- Dependencies: 224
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- TOC entry 5097 (class 0 OID 0)
-- Dependencies: 245
-- Name: personal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_id_seq', 1, false);


--
-- TOC entry 5098 (class 0 OID 0)
-- Dependencies: 253
-- Name: presupuestos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.presupuestos_id_seq', 1, false);


--
-- TOC entry 5099 (class 0 OID 0)
-- Dependencies: 236
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.roles_id_seq', 3, true);


--
-- TOC entry 5100 (class 0 OID 0)
-- Dependencies: 255
-- Name: rutas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rutas_id_seq', 1, false);


--
-- TOC entry 5101 (class 0 OID 0)
-- Dependencies: 232
-- Name: status_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.status_id_seq', 3, true);


--
-- TOC entry 5102 (class 0 OID 0)
-- Dependencies: 228
-- Name: statuses_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.statuses_id_seq', 1, false);


--
-- TOC entry 5103 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 3, true);


--
-- TOC entry 4798 (class 2606 OID 34130)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4800 (class 2606 OID 34132)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4827 (class 2606 OID 34279)
-- Name: incidencias incidencias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_pkey PRIMARY KEY (id);


--
-- TOC entry 4811 (class 2606 OID 34172)
-- Name: incidents incidents_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidents
    ADD CONSTRAINT incidents_pkey PRIMARY KEY (id);


--
-- TOC entry 4825 (class 2606 OID 34269)
-- Name: infraestructuras infraestructuras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infraestructuras
    ADD CONSTRAINT infraestructuras_pkey PRIMARY KEY (id);


--
-- TOC entry 4807 (class 2606 OID 34153)
-- Name: infrastructures infrastructures_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infrastructures
    ADD CONSTRAINT infrastructures_pkey PRIMARY KEY (id);


--
-- TOC entry 4850 (class 2606 OID 34407)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4839 (class 2606 OID 34355)
-- Name: mantenimiento_preventivo mantenimiento_preventivo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo
    ADD CONSTRAINT mantenimiento_preventivo_pkey PRIMARY KEY (id);


--
-- TOC entry 4831 (class 2606 OID 34320)
-- Name: materiales materiales_codigo_interno_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales
    ADD CONSTRAINT materiales_codigo_interno_unique UNIQUE (codigo_interno);


--
-- TOC entry 4833 (class 2606 OID 34318)
-- Name: materiales materiales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales
    ADD CONSTRAINT materiales_pkey PRIMARY KEY (id);


--
-- TOC entry 4790 (class 2606 OID 34102)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4819 (class 2606 OID 34229)
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- TOC entry 4821 (class 2606 OID 34239)
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- TOC entry 4848 (class 2606 OID 34398)
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- TOC entry 4835 (class 2606 OID 34344)
-- Name: ordenes_trabajo ordenes_trabajo_codigo_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_codigo_unique UNIQUE (codigo);


--
-- TOC entry 4837 (class 2606 OID 34332)
-- Name: ordenes_trabajo ordenes_trabajo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_pkey PRIMARY KEY (id);


--
-- TOC entry 4796 (class 2606 OID 34120)
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (email);


--
-- TOC entry 4815 (class 2606 OID 34215)
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- TOC entry 4802 (class 2606 OID 34141)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4804 (class 2606 OID 34144)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4829 (class 2606 OID 34304)
-- Name: personal personal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal
    ADD CONSTRAINT personal_pkey PRIMARY KEY (id);


--
-- TOC entry 4841 (class 2606 OID 34373)
-- Name: presupuestos presupuestos_año_mes_categoria_zona_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT "presupuestos_año_mes_categoria_zona_unique" UNIQUE ("año", mes, categoria, zona);


--
-- TOC entry 4843 (class 2606 OID 34371)
-- Name: presupuestos presupuestos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT presupuestos_pkey PRIMARY KEY (id);


--
-- TOC entry 4823 (class 2606 OID 34249)
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- TOC entry 4817 (class 2606 OID 34224)
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- TOC entry 4845 (class 2606 OID 34383)
-- Name: rutas rutas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas
    ADD CONSTRAINT rutas_pkey PRIMARY KEY (id);


--
-- TOC entry 4813 (class 2606 OID 34197)
-- Name: status status_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.status
    ADD CONSTRAINT status_pkey PRIMARY KEY (id);


--
-- TOC entry 4809 (class 2606 OID 34163)
-- Name: statuses statuses_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.statuses
    ADD CONSTRAINT statuses_pkey PRIMARY KEY (id);


--
-- TOC entry 4792 (class 2606 OID 34113)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4794 (class 2606 OID 34111)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4851 (class 1259 OID 34408)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 4846 (class 1259 OID 34396)
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- TOC entry 4805 (class 1259 OID 34142)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4859 (class 2606 OID 34290)
-- Name: incidencias incidencias_ciudadano_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_ciudadano_id_foreign FOREIGN KEY (ciudadano_id) REFERENCES public.users(id);


--
-- TOC entry 4860 (class 2606 OID 34280)
-- Name: incidencias incidencias_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id);


--
-- TOC entry 4861 (class 2606 OID 34285)
-- Name: incidencias incidencias_tecnico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_tecnico_id_foreign FOREIGN KEY (tecnico_id) REFERENCES public.users(id);


--
-- TOC entry 4852 (class 2606 OID 34183)
-- Name: incidents incidents_assigned_to_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidents
    ADD CONSTRAINT incidents_assigned_to_foreign FOREIGN KEY (assigned_to) REFERENCES public.users(id);


--
-- TOC entry 4853 (class 2606 OID 34178)
-- Name: incidents incidents_infrastructure_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidents
    ADD CONSTRAINT incidents_infrastructure_id_foreign FOREIGN KEY (infrastructure_id) REFERENCES public.infrastructures(id);


--
-- TOC entry 4854 (class 2606 OID 34173)
-- Name: incidents incidents_status_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidents
    ADD CONSTRAINT incidents_status_id_foreign FOREIGN KEY (status_id) REFERENCES public.statuses(id);


--
-- TOC entry 4865 (class 2606 OID 34356)
-- Name: mantenimiento_preventivo mantenimiento_preventivo_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo
    ADD CONSTRAINT mantenimiento_preventivo_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id);


--
-- TOC entry 4855 (class 2606 OID 34230)
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- TOC entry 4856 (class 2606 OID 34240)
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- TOC entry 4863 (class 2606 OID 34333)
-- Name: ordenes_trabajo ordenes_trabajo_incidencia_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_incidencia_id_foreign FOREIGN KEY (incidencia_id) REFERENCES public.incidencias(id);


--
-- TOC entry 4864 (class 2606 OID 34338)
-- Name: ordenes_trabajo ordenes_trabajo_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id);


--
-- TOC entry 4862 (class 2606 OID 34305)
-- Name: personal personal_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal
    ADD CONSTRAINT personal_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 4857 (class 2606 OID 34250)
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- TOC entry 4858 (class 2606 OID 34255)
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- TOC entry 4866 (class 2606 OID 34384)
-- Name: rutas rutas_personal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas
    ADD CONSTRAINT rutas_personal_id_foreign FOREIGN KEY (personal_id) REFERENCES public.personal(id);


-- Completed on 2025-02-21 10:53:01

--
-- PostgreSQL database dump complete
--

