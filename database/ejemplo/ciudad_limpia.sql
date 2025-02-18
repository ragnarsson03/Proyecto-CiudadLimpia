--
-- PostgreSQL database dump
--

-- Dumped from database version 17.0
-- Dumped by pg_dump version 17.0

-- Started on 2025-02-17 22:16:03

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
-- TOC entry 223 (class 1259 OID 29991)
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
-- TOC entry 222 (class 1259 OID 29990)
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
-- TOC entry 4957 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 229 (class 1259 OID 30027)
-- Name: incidencias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.incidencias (
    id bigint NOT NULL,
    tipo character varying(255) NOT NULL,
    ubicacion character varying(255) NOT NULL,
    descripcion text NOT NULL,
    fecha timestamp(0) without time zone NOT NULL,
    estado character varying(255) NOT NULL,
    prioridad character varying(255) NOT NULL,
    latitud numeric(10,8),
    longitud numeric(11,8),
    infraestructura_id bigint NOT NULL,
    tecnico_id bigint,
    ciudadano_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT incidencias_estado_check CHECK (((estado)::text = ANY ((ARRAY['pendiente'::character varying, 'en_proceso'::character varying, 'resuelto'::character varying, 'cancelado'::character varying])::text[]))),
    CONSTRAINT incidencias_prioridad_check CHECK (((prioridad)::text = ANY ((ARRAY['baja'::character varying, 'media'::character varying, 'alta'::character varying, 'critica'::character varying])::text[])))
);


ALTER TABLE public.incidencias OWNER TO postgres;

--
-- TOC entry 228 (class 1259 OID 30026)
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
-- TOC entry 4958 (class 0 OID 0)
-- Dependencies: 228
-- Name: incidencias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.incidencias_id_seq OWNED BY public.incidencias.id;


--
-- TOC entry 227 (class 1259 OID 30017)
-- Name: infraestructuras; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.infraestructuras (
    id bigint NOT NULL,
    tipo character varying(255) NOT NULL,
    ubicacion character varying(255) NOT NULL,
    descripcion text NOT NULL,
    estado character varying(255) NOT NULL,
    ultima_revision timestamp(0) without time zone,
    historial_mantenimiento json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT infraestructuras_estado_check CHECK (((estado)::text = ANY ((ARRAY['operativo'::character varying, 'mantenimiento'::character varying, 'reparacion'::character varying, 'fuera_de_servicio'::character varying])::text[])))
);


ALTER TABLE public.infraestructuras OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 30016)
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
-- TOC entry 4959 (class 0 OID 0)
-- Dependencies: 226
-- Name: infraestructuras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.infraestructuras_id_seq OWNED BY public.infraestructuras.id;


--
-- TOC entry 238 (class 1259 OID 30112)
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
-- TOC entry 4960 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN mantenimiento_preventivo.duracion_estimada; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.mantenimiento_preventivo.duracion_estimada IS 'en minutos';


--
-- TOC entry 237 (class 1259 OID 30111)
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
-- TOC entry 4961 (class 0 OID 0)
-- Dependencies: 237
-- Name: mantenimiento_preventivo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.mantenimiento_preventivo_id_seq OWNED BY public.mantenimiento_preventivo.id;


--
-- TOC entry 234 (class 1259 OID 30077)
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
-- TOC entry 233 (class 1259 OID 30076)
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
-- TOC entry 4962 (class 0 OID 0)
-- Dependencies: 233
-- Name: materiales_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.materiales_id_seq OWNED BY public.materiales.id;


--
-- TOC entry 218 (class 1259 OID 29966)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 29965)
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
-- TOC entry 4963 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 230 (class 1259 OID 30052)
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
-- TOC entry 236 (class 1259 OID 30088)
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
-- TOC entry 235 (class 1259 OID 30087)
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
-- TOC entry 4964 (class 0 OID 0)
-- Dependencies: 235
-- Name: ordenes_trabajo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.ordenes_trabajo_id_seq OWNED BY public.ordenes_trabajo.id;


--
-- TOC entry 221 (class 1259 OID 29983)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- TOC entry 232 (class 1259 OID 30062)
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
-- TOC entry 225 (class 1259 OID 30003)
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
-- TOC entry 224 (class 1259 OID 30002)
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
-- TOC entry 4965 (class 0 OID 0)
-- Dependencies: 224
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 231 (class 1259 OID 30061)
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
-- TOC entry 4966 (class 0 OID 0)
-- Dependencies: 231
-- Name: personal_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_id_seq OWNED BY public.personal.id;


--
-- TOC entry 240 (class 1259 OID 30128)
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
-- TOC entry 239 (class 1259 OID 30127)
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
-- TOC entry 4967 (class 0 OID 0)
-- Dependencies: 239
-- Name: presupuestos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.presupuestos_id_seq OWNED BY public.presupuestos.id;


--
-- TOC entry 242 (class 1259 OID 30141)
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
-- TOC entry 4968 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN rutas.ordenes_trabajo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.ordenes_trabajo IS 'Array de IDs de órdenes de trabajo';


--
-- TOC entry 4969 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN rutas.puntos; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.puntos IS 'Array de coordenadas ordenadas';


--
-- TOC entry 4970 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN rutas.distancia_total; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.distancia_total IS 'en kilómetros';


--
-- TOC entry 4971 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN rutas.tiempo_estimado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.tiempo_estimado IS 'en minutos';


--
-- TOC entry 4972 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN rutas.metricas; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.rutas.metricas IS 'Tiempo real, distancia real, etc.';


--
-- TOC entry 241 (class 1259 OID 30140)
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
-- TOC entry 4973 (class 0 OID 0)
-- Dependencies: 241
-- Name: rutas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.rutas_id_seq OWNED BY public.rutas.id;


--
-- TOC entry 220 (class 1259 OID 29973)
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
-- TOC entry 219 (class 1259 OID 29972)
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
-- TOC entry 4974 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4707 (class 2604 OID 29994)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4711 (class 2604 OID 30030)
-- Name: incidencias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias ALTER COLUMN id SET DEFAULT nextval('public.incidencias_id_seq'::regclass);


--
-- TOC entry 4710 (class 2604 OID 30020)
-- Name: infraestructuras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infraestructuras ALTER COLUMN id SET DEFAULT nextval('public.infraestructuras_id_seq'::regclass);


--
-- TOC entry 4715 (class 2604 OID 30115)
-- Name: mantenimiento_preventivo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo ALTER COLUMN id SET DEFAULT nextval('public.mantenimiento_preventivo_id_seq'::regclass);


--
-- TOC entry 4713 (class 2604 OID 30080)
-- Name: materiales id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales ALTER COLUMN id SET DEFAULT nextval('public.materiales_id_seq'::regclass);


--
-- TOC entry 4704 (class 2604 OID 29969)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4714 (class 2604 OID 30091)
-- Name: ordenes_trabajo id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo ALTER COLUMN id SET DEFAULT nextval('public.ordenes_trabajo_id_seq'::regclass);


--
-- TOC entry 4712 (class 2604 OID 30065)
-- Name: personal id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal ALTER COLUMN id SET DEFAULT nextval('public.personal_id_seq'::regclass);


--
-- TOC entry 4709 (class 2604 OID 30006)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 4717 (class 2604 OID 30131)
-- Name: presupuestos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos ALTER COLUMN id SET DEFAULT nextval('public.presupuestos_id_seq'::regclass);


--
-- TOC entry 4720 (class 2604 OID 30144)
-- Name: rutas id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas ALTER COLUMN id SET DEFAULT nextval('public.rutas_id_seq'::regclass);


--
-- TOC entry 4705 (class 2604 OID 29976)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4932 (class 0 OID 29991)
-- Dependencies: 223
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 4938 (class 0 OID 30027)
-- Dependencies: 229
-- Data for Name: incidencias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.incidencias (id, tipo, ubicacion, descripcion, fecha, estado, prioridad, latitud, longitud, infraestructura_id, tecnico_id, ciudadano_id, created_at, updated_at) FROM stdin;
1	Contenedor Lleno	Av. Principal 123	El contenedor está desbordado	2025-02-18 01:05:11	pendiente	alta	-33.44890000	-70.66930000	1	2	3	2025-02-18 01:05:11	2025-02-18 01:05:11
2	Luz Dañada	Plaza Central	La luminaria no enciende	2025-02-18 01:05:11	en_proceso	media	-33.45000000	-70.67000000	2	2	3	2025-02-18 01:05:11	2025-02-18 01:05:11
3	Semáforo Intermitente	Intersección Principal	El semáforo está en amarillo intermitente	2025-02-18 01:05:11	pendiente	critica	-33.45110000	-70.67110000	3	\N	3	2025-02-18 01:05:11	2025-02-18 01:05:11
\.


--
-- TOC entry 4936 (class 0 OID 30017)
-- Dependencies: 227
-- Data for Name: infraestructuras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.infraestructuras (id, tipo, ubicacion, descripcion, estado, ultima_revision, historial_mantenimiento, created_at, updated_at) FROM stdin;
1	Contenedor de Basura	Av. Principal 123	Contenedor principal del sector	operativo	\N	\N	2025-02-18 01:05:11	2025-02-18 01:05:11
2	Luminaria	Plaza Central	Poste de luz LED	mantenimiento	\N	\N	2025-02-18 01:05:11	2025-02-18 01:05:11
3	Semáforo	Intersección Principal	Semáforo de 4 vías	operativo	\N	\N	2025-02-18 01:05:11	2025-02-18 01:05:11
\.


--
-- TOC entry 4947 (class 0 OID 30112)
-- Dependencies: 238
-- Data for Name: mantenimiento_preventivo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.mantenimiento_preventivo (id, infraestructura_id, nombre, descripcion, frecuencia, dias_frecuencia, ultima_ejecucion, proxima_ejecucion, checklist, costo_estimado, duracion_estimada, materiales_requeridos, personal_requerido, activo, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4943 (class 0 OID 30077)
-- Dependencies: 234
-- Data for Name: materiales; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.materiales (id, nombre, descripcion, cantidad_disponible, costo_unitario, unidad_medida, stock_minimo, stock_maximo, ubicacion_almacen, codigo_interno, proveedores, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4927 (class 0 OID 29966)
-- Dependencies: 218
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_resets_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
4	2019_12_14_000001_create_personal_access_tokens_table	1
5	2025_02_17_215639_add_role_to_users_table	1
6	2025_02_17_215653_create_infraestructuras_table	1
7	2025_02_17_215724_create_incidencias_table	1
8	2025_02_17_234342_create_notifications_table	1
9	2025_02_17_212542_create_personal_table	2
10	2025_02_17_212543_create_materiales_table	2
11	2025_02_17_212544_create_ordenes_trabajo_table	2
12	2025_02_17_212545_create_mantenimiento_preventivo_table	2
13	2025_02_17_212546_create_presupuestos_table	2
14	2025_02_17_212547_create_rutas_table	2
\.


--
-- TOC entry 4939 (class 0 OID 30052)
-- Dependencies: 230
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notifications (id, type, notifiable_type, notifiable_id, data, read_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4945 (class 0 OID 30088)
-- Dependencies: 236
-- Data for Name: ordenes_trabajo; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.ordenes_trabajo (id, codigo, incidencia_id, infraestructura_id, tipo, estado, prioridad, descripcion, fecha_programada, fecha_inicio, fecha_fin, costo_estimado, costo_real, materiales_requeridos, personal_asignado, observaciones, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4930 (class 0 OID 29983)
-- Dependencies: 221
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 4941 (class 0 OID 30062)
-- Dependencies: 232
-- Data for Name: personal; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal (id, user_id, especialidad, disponibilidad, habilidades, telefono, direccion, notas, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4934 (class 0 OID 30003)
-- Dependencies: 225
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4949 (class 0 OID 30128)
-- Dependencies: 240
-- Data for Name: presupuestos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.presupuestos (id, "año", mes, monto_asignado, monto_ejecutado, monto_comprometido, categoria, zona, desglose, notas, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4951 (class 0 OID 30141)
-- Dependencies: 242
-- Data for Name: rutas; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.rutas (id, personal_id, fecha, ordenes_trabajo, puntos, distancia_total, tiempo_estimado, estado, hora_inicio, hora_fin, metricas, observaciones, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- TOC entry 4929 (class 0 OID 29973)
-- Dependencies: 220
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role) FROM stdin;
1	Administrador	admin@ciudadlimpia.com	2025-02-18 01:05:10	$2y$10$PL3C75rV0ljPp636ipV9WeLGiKoD9Z2QG71/d6Nt9q7CUJo/QWyiW	\N	2025-02-18 01:05:10	2025-02-18 01:05:10	admin
2	Técnico	tecnico@ciudadlimpia.com	2025-02-18 01:05:10	$2y$10$tMDIzR64Dg.4o7/vWWxWj.4uLMX45irYtXWmdmzcB0clsNW8aeCT2	\N	2025-02-18 01:05:10	2025-02-18 01:05:10	tecnico
3	Ciudadano	ciudadano@ciudadlimpia.com	2025-02-18 01:05:11	$2y$10$2Mqh3tabP/wXysm06nQDnOiXf7lj4juetMM5T82Mxtp.TQ4kaizpu	\N	2025-02-18 01:05:11	2025-02-18 01:05:11	ciudadano
4	Administrador	admin@test.com	\N	$2y$10$vPPBn90YuPJP.qE.nGQmyO1fX5McLE1OzDTvDhSveArzoikFSXc1S	\N	2025-02-18 01:05:11	2025-02-18 01:05:11	admin
5	Supervisor	supervisor@test.com	\N	$2y$10$eArrrqsqyVxFunPX92RrLe5GSNGKrhwTL8s.WYu2FDv8Vegpsdbli	\N	2025-02-18 01:05:11	2025-02-18 01:05:11	supervisor
6	Técnico	tecnico@test.com	\N	$2y$10$2oDmcLRI3RDMyhI//xtL6eC2ykz5U/VroOIf6Txxh8tcTrFMf82EW	\N	2025-02-18 01:05:11	2025-02-18 01:05:11	tecnico
7	Ciudadano	ciudadano@test.com	\N	$2y$10$DjWX6qeGwaMimA094QZBT.C/8yWFjVvUKG2IL7hKK70Mv3wfWGqze	\N	2025-02-18 01:05:11	2025-02-18 01:05:11	ciudadano
\.


--
-- TOC entry 4975 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 4976 (class 0 OID 0)
-- Dependencies: 228
-- Name: incidencias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.incidencias_id_seq', 3, true);


--
-- TOC entry 4977 (class 0 OID 0)
-- Dependencies: 226
-- Name: infraestructuras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.infraestructuras_id_seq', 3, true);


--
-- TOC entry 4978 (class 0 OID 0)
-- Dependencies: 237
-- Name: mantenimiento_preventivo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.mantenimiento_preventivo_id_seq', 1, false);


--
-- TOC entry 4979 (class 0 OID 0)
-- Dependencies: 233
-- Name: materiales_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.materiales_id_seq', 1, false);


--
-- TOC entry 4980 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 14, true);


--
-- TOC entry 4981 (class 0 OID 0)
-- Dependencies: 235
-- Name: ordenes_trabajo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.ordenes_trabajo_id_seq', 1, false);


--
-- TOC entry 4982 (class 0 OID 0)
-- Dependencies: 224
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- TOC entry 4983 (class 0 OID 0)
-- Dependencies: 231
-- Name: personal_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_id_seq', 1, false);


--
-- TOC entry 4984 (class 0 OID 0)
-- Dependencies: 239
-- Name: presupuestos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.presupuestos_id_seq', 1, false);


--
-- TOC entry 4985 (class 0 OID 0)
-- Dependencies: 241
-- Name: rutas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.rutas_id_seq', 1, false);


--
-- TOC entry 4986 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 7, true);


--
-- TOC entry 4740 (class 2606 OID 29999)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4742 (class 2606 OID 30001)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4751 (class 2606 OID 30036)
-- Name: incidencias incidencias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_pkey PRIMARY KEY (id);


--
-- TOC entry 4749 (class 2606 OID 30025)
-- Name: infraestructuras infraestructuras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infraestructuras
    ADD CONSTRAINT infraestructuras_pkey PRIMARY KEY (id);


--
-- TOC entry 4766 (class 2606 OID 30121)
-- Name: mantenimiento_preventivo mantenimiento_preventivo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo
    ADD CONSTRAINT mantenimiento_preventivo_pkey PRIMARY KEY (id);


--
-- TOC entry 4758 (class 2606 OID 30086)
-- Name: materiales materiales_codigo_interno_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales
    ADD CONSTRAINT materiales_codigo_interno_unique UNIQUE (codigo_interno);


--
-- TOC entry 4760 (class 2606 OID 30084)
-- Name: materiales materiales_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.materiales
    ADD CONSTRAINT materiales_pkey PRIMARY KEY (id);


--
-- TOC entry 4732 (class 2606 OID 29971)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4754 (class 2606 OID 30059)
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- TOC entry 4762 (class 2606 OID 30110)
-- Name: ordenes_trabajo ordenes_trabajo_codigo_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_codigo_unique UNIQUE (codigo);


--
-- TOC entry 4764 (class 2606 OID 30098)
-- Name: ordenes_trabajo ordenes_trabajo_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_pkey PRIMARY KEY (id);


--
-- TOC entry 4738 (class 2606 OID 29989)
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (email);


--
-- TOC entry 4744 (class 2606 OID 30010)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4746 (class 2606 OID 30013)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4756 (class 2606 OID 30070)
-- Name: personal personal_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal
    ADD CONSTRAINT personal_pkey PRIMARY KEY (id);


--
-- TOC entry 4768 (class 2606 OID 30139)
-- Name: presupuestos presupuestos_año_mes_categoria_zona_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT "presupuestos_año_mes_categoria_zona_unique" UNIQUE ("año", mes, categoria, zona);


--
-- TOC entry 4770 (class 2606 OID 30137)
-- Name: presupuestos presupuestos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT presupuestos_pkey PRIMARY KEY (id);


--
-- TOC entry 4772 (class 2606 OID 30149)
-- Name: rutas rutas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas
    ADD CONSTRAINT rutas_pkey PRIMARY KEY (id);


--
-- TOC entry 4734 (class 2606 OID 29982)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4736 (class 2606 OID 29980)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4752 (class 1259 OID 30057)
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- TOC entry 4747 (class 1259 OID 30011)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4773 (class 2606 OID 30047)
-- Name: incidencias incidencias_ciudadano_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_ciudadano_id_foreign FOREIGN KEY (ciudadano_id) REFERENCES public.users(id);


--
-- TOC entry 4774 (class 2606 OID 30037)
-- Name: incidencias incidencias_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id) ON DELETE CASCADE;


--
-- TOC entry 4775 (class 2606 OID 30042)
-- Name: incidencias incidencias_tecnico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_tecnico_id_foreign FOREIGN KEY (tecnico_id) REFERENCES public.users(id);


--
-- TOC entry 4779 (class 2606 OID 30122)
-- Name: mantenimiento_preventivo mantenimiento_preventivo_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.mantenimiento_preventivo
    ADD CONSTRAINT mantenimiento_preventivo_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id);


--
-- TOC entry 4777 (class 2606 OID 30099)
-- Name: ordenes_trabajo ordenes_trabajo_incidencia_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_incidencia_id_foreign FOREIGN KEY (incidencia_id) REFERENCES public.incidencias(id);


--
-- TOC entry 4778 (class 2606 OID 30104)
-- Name: ordenes_trabajo ordenes_trabajo_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.ordenes_trabajo
    ADD CONSTRAINT ordenes_trabajo_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id);


--
-- TOC entry 4776 (class 2606 OID 30071)
-- Name: personal personal_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal
    ADD CONSTRAINT personal_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 4780 (class 2606 OID 30150)
-- Name: rutas rutas_personal_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.rutas
    ADD CONSTRAINT rutas_personal_id_foreign FOREIGN KEY (personal_id) REFERENCES public.personal(id);


-- Completed on 2025-02-17 22:16:04

--
-- PostgreSQL database dump complete
--

