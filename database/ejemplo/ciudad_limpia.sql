--
-- PostgreSQL database dump
--

-- Dumped from database version 17.0
-- Dumped by pg_dump version 17.0

-- Started on 2025-02-18 02:17:30

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
-- TOC entry 223 (class 1259 OID 31296)
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
-- TOC entry 222 (class 1259 OID 31295)
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
-- TOC entry 4967 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 229 (class 1259 OID 31330)
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
-- TOC entry 228 (class 1259 OID 31329)
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
-- TOC entry 4968 (class 0 OID 0)
-- Dependencies: 228
-- Name: incidencias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.incidencias_id_seq OWNED BY public.incidencias.id;


--
-- TOC entry 227 (class 1259 OID 31320)
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
-- TOC entry 226 (class 1259 OID 31319)
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
-- TOC entry 4969 (class 0 OID 0)
-- Dependencies: 226
-- Name: infraestructuras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.infraestructuras_id_seq OWNED BY public.infraestructuras.id;


--
-- TOC entry 244 (class 1259 OID 31459)
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
-- TOC entry 243 (class 1259 OID 31458)
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
-- TOC entry 4970 (class 0 OID 0)
-- Dependencies: 243
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 237 (class 1259 OID 31405)
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
-- TOC entry 4971 (class 0 OID 0)
-- Dependencies: 237
-- Name: COLUMN mantenimiento_preventivo.duracion_estimada; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.mantenimiento_preventivo.duracion_estimada IS 'en minutos';


--
-- TOC entry 236 (class 1259 OID 31404)
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
-- TOC entry 4972 (class 0 OID 0)
-- Dependencies: 236
-- Name: mantenimiento_preventivo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mantenimiento_preventivo_id_seq OWNED BY public.mantenimiento_preventivo.id;


--
-- TOC entry 233 (class 1259 OID 31370)
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
-- TOC entry 232 (class 1259 OID 31369)
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
-- TOC entry 4973 (class 0 OID 0)
-- Dependencies: 232
-- Name: materiales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.materiales_id_seq OWNED BY public.materiales.id;


--
-- TOC entry 218 (class 1259 OID 31271)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 31270)
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
-- TOC entry 4974 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 242 (class 1259 OID 31450)
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
-- TOC entry 235 (class 1259 OID 31381)
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
-- TOC entry 234 (class 1259 OID 31380)
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
-- TOC entry 4975 (class 0 OID 0)
-- Dependencies: 234
-- Name: ordenes_trabajo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ordenes_trabajo_id_seq OWNED BY public.ordenes_trabajo.id;


--
-- TOC entry 221 (class 1259 OID 31288)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- TOC entry 231 (class 1259 OID 31355)
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
-- TOC entry 225 (class 1259 OID 31308)
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
-- TOC entry 224 (class 1259 OID 31307)
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
-- TOC entry 4976 (class 0 OID 0)
-- Dependencies: 224
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 230 (class 1259 OID 31354)
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
-- TOC entry 4977 (class 0 OID 0)
-- Dependencies: 230
-- Name: personal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_id_seq OWNED BY public.personal.id;


--
-- TOC entry 239 (class 1259 OID 31421)
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
-- TOC entry 238 (class 1259 OID 31420)
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
-- TOC entry 4978 (class 0 OID 0)
-- Dependencies: 238
-- Name: presupuestos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.presupuestos_id_seq OWNED BY public.presupuestos.id;


--
-- TOC entry 241 (class 1259 OID 31434)
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
-- TOC entry 4979 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN rutas.ordenes_trabajo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.ordenes_trabajo IS 'Array de IDs de órdenes de trabajo';


--
-- TOC entry 4980 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN rutas.puntos; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.puntos IS 'Array de coordenadas ordenadas';


--
-- TOC entry 4981 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN rutas.distancia_total; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.distancia_total IS 'en kilómetros';


--
-- TOC entry 4982 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN rutas.tiempo_estimado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.tiempo_estimado IS 'en minutos';


--
-- TOC entry 4983 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN rutas.metricas; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.metricas IS 'Tiempo real, distancia real, etc.';


--
-- TOC entry 240 (class 1259 OID 31433)
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
-- TOC entry 4984 (class 0 OID 0)
-- Dependencies: 240
-- Name: rutas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rutas_id_seq OWNED BY public.rutas.id;


--
-- TOC entry 220 (class 1259 OID 31278)
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
-- TOC entry 219 (class 1259 OID 31277)
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
-- TOC entry 4985 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4712 (class 2604 OID 31299)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4717 (class 2604 OID 31333)
-- Name: incidencias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias ALTER COLUMN id SET DEFAULT nextval('public.incidencias_id_seq'::regclass);


--
-- TOC entry 4715 (class 2604 OID 31323)
-- Name: infraestructuras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infraestructuras ALTER COLUMN id SET DEFAULT nextval('public.infraestructuras_id_seq'::regclass);


--
-- TOC entry 4728 (class 2604 OID 31462)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4722 (class 2604 OID 31408)
-- Name: mantenimiento_preventivo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo ALTER COLUMN id SET DEFAULT nextval('public.mantenimiento_preventivo_id_seq'::regclass);


--
-- TOC entry 4720 (class 2604 OID 31373)
-- Name: materiales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales ALTER COLUMN id SET DEFAULT nextval('public.materiales_id_seq'::regclass);


--
-- TOC entry 4709 (class 2604 OID 31274)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4721 (class 2604 OID 31384)
-- Name: ordenes_trabajo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo ALTER COLUMN id SET DEFAULT nextval('public.ordenes_trabajo_id_seq'::regclass);


--
-- TOC entry 4719 (class 2604 OID 31358)
-- Name: personal id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal ALTER COLUMN id SET DEFAULT nextval('public.personal_id_seq'::regclass);


--
-- TOC entry 4714 (class 2604 OID 31311)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 4724 (class 2604 OID 31424)
-- Name: presupuestos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos ALTER COLUMN id SET DEFAULT nextval('public.presupuestos_id_seq'::regclass);


--
-- TOC entry 4727 (class 2604 OID 31437)
-- Name: rutas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas ALTER COLUMN id SET DEFAULT nextval('public.rutas_id_seq'::regclass);


--
-- TOC entry 4710 (class 2604 OID 31281)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4940 (class 0 OID 31296)
-- Dependencies: 223
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 4946 (class 0 OID 31330)
-- Dependencies: 229
-- Data for Name: incidencias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.incidencias (id, tipo, ubicacion, descripcion, fecha, estado, prioridad, latitud, longitud, infraestructura_id, tecnico_id, ciudadano_id, created_at, updated_at, deleted_at) FROM stdin;
1	Contenedor Lleno	Av. Principal 123	El contenedor está desbordado	2025-02-18 04:24:01	pendiente	alta	-33.44890000	-70.66930000	1	2	3	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
2	Luz Dañada	Plaza Central	La luminaria no enciende	2025-02-18 04:24:01	en_proceso	media	-33.45000000	-70.67000000	2	2	3	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
3	Semáforo Intermitente	Intersección Principal	El semáforo está en amarillo intermitente	2025-02-18 04:24:01	pendiente	critica	-33.45110000	-70.67110000	3	\N	3	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
\.


--
-- TOC entry 4944 (class 0 OID 31320)
-- Dependencies: 227
-- Data for Name: infraestructuras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.infraestructuras (id, tipo, ubicacion, descripcion, estado, ultima_revision, historial_mantenimiento, created_at, updated_at, deleted_at) FROM stdin;
1	Contenedor de Basura	Av. Principal 123	Contenedor principal del sector	operativo	\N	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
2	Luminaria	Plaza Central	Poste de luz LED	mantenimiento	\N	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
3	Semáforo	Intersección Principal	Semáforo de 4 vías	operativo	\N	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
4	Parque	Parque Central	Infraestructura tipo Parque en Parque Central	fuera_de_servicio	2025-01-21 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
5	Parque	Parque Norte	Infraestructura tipo Parque en Parque Norte	operativo	2025-01-20 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
6	Parque	Parque Sur	Infraestructura tipo Parque en Parque Sur	fuera_de_servicio	2025-01-22 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
7	Semáforo	Intersección Principal	Infraestructura tipo Semáforo en Intersección Principal	operativo	2025-02-11 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
8	Semáforo	Cruce Comercial	Infraestructura tipo Semáforo en Cruce Comercial	mantenimiento	2025-02-01 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
9	Semáforo	Avenida Central	Infraestructura tipo Semáforo en Avenida Central	fuera_de_servicio	2025-02-15 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
10	Contenedor	Zona Residencial	Infraestructura tipo Contenedor en Zona Residencial	operativo	2025-02-14 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
11	Contenedor	Mercado Central	Infraestructura tipo Contenedor en Mercado Central	fuera_de_servicio	2025-01-22 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
12	Contenedor	Centro Comercial	Infraestructura tipo Contenedor en Centro Comercial	fuera_de_servicio	2025-02-16 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
13	Luminaria	Calle Principal	Infraestructura tipo Luminaria en Calle Principal	fuera_de_servicio	2025-02-12 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
14	Luminaria	Plaza Central	Infraestructura tipo Luminaria en Plaza Central	operativo	2025-01-30 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
15	Luminaria	Avenida Comercial	Infraestructura tipo Luminaria en Avenida Comercial	fuera_de_servicio	2025-01-29 04:24:01	"[]"	2025-02-18 04:24:01	2025-02-18 04:24:01	\N
\.


--
-- TOC entry 4961 (class 0 OID 31459)
-- Dependencies: 244
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 4954 (class 0 OID 31405)
-- Dependencies: 237
-- Data for Name: mantenimiento_preventivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mantenimiento_preventivo (id, infraestructura_id, nombre, descripcion, frecuencia, dias_frecuencia, ultima_ejecucion, proxima_ejecucion, checklist, costo_estimado, duracion_estimada, materiales_requeridos, personal_requerido, activo, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4950 (class 0 OID 31370)
-- Dependencies: 233
-- Data for Name: materiales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.materiales (id, nombre, descripcion, cantidad_disponible, costo_unitario, unidad_medida, stock_minimo, stock_maximo, ubicacion_almacen, codigo_interno, proveedores, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4935 (class 0 OID 31271)
-- Dependencies: 218
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
4	2019_12_14_000001_create_personal_access_tokens_table	1
5	2025_02_17_212540_create_infraestructuras_table	1
6	2025_02_17_212541_create_incidencias_table	1
7	2025_02_17_212542_create_personal_table	1
8	2025_02_17_212543_create_materiales_table	1
9	2025_02_17_212544_create_ordenes_trabajo_table	1
10	2025_02_17_212545_create_mantenimiento_preventivo_table	1
11	2025_02_17_212546_create_presupuestos_table	1
12	2025_02_17_212547_create_rutas_table	1
13	2025_02_17_215639_add_role_to_users_table	1
14	2025_02_17_234342_create_notifications_table	1
15	2025_02_18_033247_create_jobs_table	1
\.


--
-- TOC entry 4959 (class 0 OID 31450)
-- Dependencies: 242
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notifications (id, type, notifiable_type, notifiable_id, data, read_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4952 (class 0 OID 31381)
-- Dependencies: 235
-- Data for Name: ordenes_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ordenes_trabajo (id, codigo, incidencia_id, infraestructura_id, tipo, estado, prioridad, descripcion, fecha_programada, fecha_inicio, fecha_fin, costo_estimado, costo_real, materiales_requeridos, personal_asignado, observaciones, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4938 (class 0 OID 31288)
-- Dependencies: 221
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 4948 (class 0 OID 31355)
-- Dependencies: 231
-- Data for Name: personal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal (id, user_id, especialidad, disponibilidad, habilidades, telefono, direccion, notas, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4942 (class 0 OID 31308)
-- Dependencies: 225
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4956 (class 0 OID 31421)
-- Dependencies: 239
-- Data for Name: presupuestos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.presupuestos (id, "año", mes, monto_asignado, monto_ejecutado, monto_comprometido, categoria, zona, desglose, notas, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4958 (class 0 OID 31434)
-- Dependencies: 241
-- Data for Name: rutas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rutas (id, personal_id, fecha, ordenes_trabajo, puntos, distancia_total, tiempo_estimado, estado, hora_inicio, hora_fin, metricas, observaciones, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4937 (class 0 OID 31278)
-- Dependencies: 220
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role) FROM stdin;
1	Administrador	admin@ciudadlimpia.com	2025-02-18 04:24:01	$2y$10$m6p3JC8vT4irSCLNAJR1Lua/BSKzNsaM9t2G5f6fIOZdMNchhJoK2	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	admin
2	Técnico	tecnico@ciudadlimpia.com	2025-02-18 04:24:01	$2y$10$k1MBGhKijGBB/mnMyZ3sL.Sd0iHdYVnW3ApY8XgRgc7DvWtRqvrtu	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	tecnico
3	Ciudadano	ciudadano@ciudadlimpia.com	2025-02-18 04:24:01	$2y$10$tOmTvUWQMJQdZjhaSzDNl.5ykyrLc8BcqqvEFOAU24tITsAxlwP76	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	ciudadano
4	Administrador	admin@test.com	\N	$2y$10$iL0fawM1RMuezNUDqxnqM.0Tmf2538U8rD3DFmMHzG/TRbRL2qNJ2	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	admin
5	Supervisor	supervisor@test.com	\N	$2y$10$ghAZyUPY./HQkM6qGSuydOui.2vY/HHyQ2nD0gmaDj7kn5qNhbq96	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	supervisor
6	Técnico	tecnico@test.com	\N	$2y$10$1H6iRpFbxLmQXkr0l3BK5eV8y5HmF25nqtjJTZ8zEAIbZZN.Es0we	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	tecnico
7	Ciudadano	ciudadano@test.com	\N	$2y$10$0lFGPiVJ.VO9lvrb3b.M9.1abEAlOyyDZsgm7Olyybwyym.WrgrY6	\N	2025-02-18 04:24:01	2025-02-18 04:24:01	ciudadano
\.


--
-- TOC entry 4986 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 4987 (class 0 OID 0)
-- Dependencies: 228
-- Name: incidencias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.incidencias_id_seq', 3, true);


--
-- TOC entry 4988 (class 0 OID 0)
-- Dependencies: 226
-- Name: infraestructuras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.infraestructuras_id_seq', 15, true);


--
-- TOC entry 4989 (class 0 OID 0)
-- Dependencies: 243
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 4990 (class 0 OID 0)
-- Dependencies: 236
-- Name: mantenimiento_preventivo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mantenimiento_preventivo_id_seq', 1, false);


--
-- TOC entry 4991 (class 0 OID 0)
-- Dependencies: 232
-- Name: materiales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.materiales_id_seq', 1, false);


--
-- TOC entry 4992 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 15, true);


--
-- TOC entry 4993 (class 0 OID 0)
-- Dependencies: 234
-- Name: ordenes_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ordenes_trabajo_id_seq', 1, false);


--
-- TOC entry 4994 (class 0 OID 0)
-- Dependencies: 224
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- TOC entry 4995 (class 0 OID 0)
-- Dependencies: 230
-- Name: personal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_id_seq', 1, false);


--
-- TOC entry 4996 (class 0 OID 0)
-- Dependencies: 238
-- Name: presupuestos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.presupuestos_id_seq', 1, false);


--
-- TOC entry 4997 (class 0 OID 0)
-- Dependencies: 240
-- Name: rutas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rutas_id_seq', 1, false);


--
-- TOC entry 4998 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 7, true);


--
-- TOC entry 4745 (class 2606 OID 31304)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4747 (class 2606 OID 31306)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4756 (class 2606 OID 31338)
-- Name: incidencias incidencias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_pkey PRIMARY KEY (id);


--
-- TOC entry 4754 (class 2606 OID 31328)
-- Name: infraestructuras infraestructuras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infraestructuras
    ADD CONSTRAINT infraestructuras_pkey PRIMARY KEY (id);


--
-- TOC entry 4779 (class 2606 OID 31466)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4768 (class 2606 OID 31414)
-- Name: mantenimiento_preventivo mantenimiento_preventivo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo
    ADD CONSTRAINT mantenimiento_preventivo_pkey PRIMARY KEY (id);


--
-- TOC entry 4760 (class 2606 OID 31379)
-- Name: materiales materiales_codigo_interno_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales
    ADD CONSTRAINT materiales_codigo_interno_unique UNIQUE (codigo_interno);


--
-- TOC entry 4762 (class 2606 OID 31377)
-- Name: materiales materiales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales
    ADD CONSTRAINT materiales_pkey PRIMARY KEY (id);


--
-- TOC entry 4737 (class 2606 OID 31276)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4777 (class 2606 OID 31457)
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- TOC entry 4764 (class 2606 OID 31403)
-- Name: ordenes_trabajo ordenes_trabajo_codigo_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_codigo_unique UNIQUE (codigo);


--
-- TOC entry 4766 (class 2606 OID 31391)
-- Name: ordenes_trabajo ordenes_trabajo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_pkey PRIMARY KEY (id);


--
-- TOC entry 4743 (class 2606 OID 31294)
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (email);


--
-- TOC entry 4749 (class 2606 OID 31315)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4751 (class 2606 OID 31318)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4758 (class 2606 OID 31363)
-- Name: personal personal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal
    ADD CONSTRAINT personal_pkey PRIMARY KEY (id);


--
-- TOC entry 4770 (class 2606 OID 31432)
-- Name: presupuestos presupuestos_año_mes_categoria_zona_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT "presupuestos_año_mes_categoria_zona_unique" UNIQUE ("año", mes, categoria, zona);


--
-- TOC entry 4772 (class 2606 OID 31430)
-- Name: presupuestos presupuestos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT presupuestos_pkey PRIMARY KEY (id);


--
-- TOC entry 4774 (class 2606 OID 31442)
-- Name: rutas rutas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas
    ADD CONSTRAINT rutas_pkey PRIMARY KEY (id);


--
-- TOC entry 4739 (class 2606 OID 31287)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4741 (class 2606 OID 31285)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4780 (class 1259 OID 31467)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 4775 (class 1259 OID 31455)
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- TOC entry 4752 (class 1259 OID 31316)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4781 (class 2606 OID 31349)
-- Name: incidencias incidencias_ciudadano_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_ciudadano_id_foreign FOREIGN KEY (ciudadano_id) REFERENCES public.users(id);


--
-- TOC entry 4782 (class 2606 OID 31339)
-- Name: incidencias incidencias_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id);


--
-- TOC entry 4783 (class 2606 OID 31344)
-- Name: incidencias incidencias_tecnico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_tecnico_id_foreign FOREIGN KEY (tecnico_id) REFERENCES public.users(id);


--
-- TOC entry 4787 (class 2606 OID 31415)
-- Name: mantenimiento_preventivo mantenimiento_preventivo_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo
    ADD CONSTRAINT mantenimiento_preventivo_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id);


--
-- TOC entry 4785 (class 2606 OID 31392)
-- Name: ordenes_trabajo ordenes_trabajo_incidencia_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_incidencia_id_foreign FOREIGN KEY (incidencia_id) REFERENCES public.incidencias(id);


--
-- TOC entry 4786 (class 2606 OID 31397)
-- Name: ordenes_trabajo ordenes_trabajo_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id);


--
-- TOC entry 4784 (class 2606 OID 31364)
-- Name: personal personal_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal
    ADD CONSTRAINT personal_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 4788 (class 2606 OID 31443)
-- Name: rutas rutas_personal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas
    ADD CONSTRAINT rutas_personal_id_foreign FOREIGN KEY (personal_id) REFERENCES public.personal(id);


-- Completed on 2025-02-18 02:17:30

--
-- PostgreSQL database dump complete
--

