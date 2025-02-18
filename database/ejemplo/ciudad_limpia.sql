--
-- PostgreSQL database dump
--

-- Dumped from database version 17.0
-- Dumped by pg_dump version 17.0

-- Started on 2025-02-17 21:03:17

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
-- TOC entry 223 (class 1259 OID 29895)
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
-- TOC entry 222 (class 1259 OID 29894)
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
-- TOC entry 4877 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 229 (class 1259 OID 29931)
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
-- TOC entry 228 (class 1259 OID 29930)
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
-- TOC entry 4878 (class 0 OID 0)
-- Dependencies: 228
-- Name: incidencias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.incidencias_id_seq OWNED BY public.incidencias.id;


--
-- TOC entry 227 (class 1259 OID 29921)
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
-- TOC entry 226 (class 1259 OID 29920)
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
-- TOC entry 4879 (class 0 OID 0)
-- Dependencies: 226
-- Name: infraestructuras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.infraestructuras_id_seq OWNED BY public.infraestructuras.id;


--
-- TOC entry 218 (class 1259 OID 29870)
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- TOC entry 217 (class 1259 OID 29869)
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
-- TOC entry 4880 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 230 (class 1259 OID 29956)
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
-- TOC entry 221 (class 1259 OID 29887)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO postgres;

--
-- TOC entry 225 (class 1259 OID 29907)
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
-- TOC entry 224 (class 1259 OID 29906)
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
-- TOC entry 4881 (class 0 OID 0)
-- Dependencies: 224
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- TOC entry 220 (class 1259 OID 29877)
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
-- TOC entry 219 (class 1259 OID 29876)
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
-- TOC entry 4882 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4677 (class 2604 OID 29898)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4681 (class 2604 OID 29934)
-- Name: incidencias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias ALTER COLUMN id SET DEFAULT nextval('public.incidencias_id_seq'::regclass);


--
-- TOC entry 4680 (class 2604 OID 29924)
-- Name: infraestructuras id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infraestructuras ALTER COLUMN id SET DEFAULT nextval('public.infraestructuras_id_seq'::regclass);


--
-- TOC entry 4674 (class 2604 OID 29873)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4679 (class 2604 OID 29910)
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- TOC entry 4675 (class 2604 OID 29880)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 4864 (class 0 OID 29895)
-- Dependencies: 223
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 4870 (class 0 OID 29931)
-- Dependencies: 229
-- Data for Name: incidencias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.incidencias (id, tipo, ubicacion, descripcion, fecha, estado, prioridad, latitud, longitud, infraestructura_id, tecnico_id, ciudadano_id, created_at, updated_at) FROM stdin;
1	Contenedor Lleno	Av. Principal 123	El contenedor está desbordado	2025-02-18 01:01:05	pendiente	alta	-33.44890000	-70.66930000	1	2	3	2025-02-18 01:01:05	2025-02-18 01:01:05
2	Luz Dañada	Plaza Central	La luminaria no enciende	2025-02-18 01:01:05	en_proceso	media	-33.45000000	-70.67000000	2	2	3	2025-02-18 01:01:05	2025-02-18 01:01:05
3	Semáforo Intermitente	Intersección Principal	El semáforo está en amarillo intermitente	2025-02-18 01:01:05	pendiente	critica	-33.45110000	-70.67110000	3	\N	3	2025-02-18 01:01:05	2025-02-18 01:01:05
\.


--
-- TOC entry 4868 (class 0 OID 29921)
-- Dependencies: 227
-- Data for Name: infraestructuras; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.infraestructuras (id, tipo, ubicacion, descripcion, estado, ultima_revision, historial_mantenimiento, created_at, updated_at) FROM stdin;
1	Contenedor de Basura	Av. Principal 123	Contenedor principal del sector	operativo	\N	\N	2025-02-18 01:01:05	2025-02-18 01:01:05
2	Luminaria	Plaza Central	Poste de luz LED	mantenimiento	\N	\N	2025-02-18 01:01:05	2025-02-18 01:01:05
3	Semáforo	Intersección Principal	Semáforo de 4 vías	operativo	\N	\N	2025-02-18 01:01:05	2025-02-18 01:01:05
\.


--
-- TOC entry 4859 (class 0 OID 29870)
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
\.


--
-- TOC entry 4871 (class 0 OID 29956)
-- Dependencies: 230
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.notifications (id, type, notifiable_type, notifiable_id, data, read_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4862 (class 0 OID 29887)
-- Dependencies: 221
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_resets (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 4866 (class 0 OID 29907)
-- Dependencies: 225
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4861 (class 0 OID 29877)
-- Dependencies: 220
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role) FROM stdin;
1	Administrador	admin@ciudadlimpia.com	2025-02-18 01:01:05	$2y$10$jMS0lWNEf9tAjctJKZV83e0DPMNIQD4oTsnWnWvKFnraWtl8Fmn5q	\N	2025-02-18 01:01:05	2025-02-18 01:01:05	admin
4	Supervisor	supervisor@ciudadlimpia.com	\N	$2y$10$BSNWDyYfbeZaX.K2hvOg/ezb583RWlljxU7OqYhg3GLB/XpPjPVIC	\N	2025-02-18 01:01:06	2025-02-18 01:01:06	supervisor
2	Técnico	tecnico@ciudadlimpia.com	2025-02-18 01:01:05	$2y$10$ozk0hN1HvrZtdIzNNAUtMu5ZEUcKhRKHwCF1QF/Gpc/ZZlAMZ0FhG	\N	2025-02-18 01:01:05	2025-02-18 01:01:06	tecnico
3	Ciudadano	ciudadano@ciudadlimpia.com	2025-02-18 01:01:05	$2y$10$13J9E18fsfxB92LiSEEVIeVUQ6vg25Mw01U6rxnqtjQCwGfCr/NLG	\N	2025-02-18 01:01:05	2025-02-18 01:01:06	ciudadano
\.


--
-- TOC entry 4883 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 4884 (class 0 OID 0)
-- Dependencies: 228
-- Name: incidencias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.incidencias_id_seq', 3, true);


--
-- TOC entry 4885 (class 0 OID 0)
-- Dependencies: 226
-- Name: infraestructuras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.infraestructuras_id_seq', 3, true);


--
-- TOC entry 4886 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 8, true);


--
-- TOC entry 4887 (class 0 OID 0)
-- Dependencies: 224
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- TOC entry 4888 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 4, true);


--
-- TOC entry 4695 (class 2606 OID 29903)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 4697 (class 2606 OID 29905)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 4706 (class 2606 OID 29940)
-- Name: incidencias incidencias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_pkey PRIMARY KEY (id);


--
-- TOC entry 4704 (class 2606 OID 29929)
-- Name: infraestructuras infraestructuras_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.infraestructuras
    ADD CONSTRAINT infraestructuras_pkey PRIMARY KEY (id);


--
-- TOC entry 4687 (class 2606 OID 29875)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 4709 (class 2606 OID 29963)
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- TOC entry 4693 (class 2606 OID 29893)
-- Name: password_resets password_resets_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_resets
    ADD CONSTRAINT password_resets_pkey PRIMARY KEY (email);


--
-- TOC entry 4699 (class 2606 OID 29914)
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- TOC entry 4701 (class 2606 OID 29917)
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- TOC entry 4689 (class 2606 OID 29886)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4691 (class 2606 OID 29884)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 4707 (class 1259 OID 29961)
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- TOC entry 4702 (class 1259 OID 29915)
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- TOC entry 4710 (class 2606 OID 29951)
-- Name: incidencias incidencias_ciudadano_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_ciudadano_id_foreign FOREIGN KEY (ciudadano_id) REFERENCES public.users(id);


--
-- TOC entry 4711 (class 2606 OID 29941)
-- Name: incidencias incidencias_infraestructura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_infraestructura_id_foreign FOREIGN KEY (infraestructura_id) REFERENCES public.infraestructuras(id) ON DELETE CASCADE;


--
-- TOC entry 4712 (class 2606 OID 29946)
-- Name: incidencias incidencias_tecnico_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.incidencias
    ADD CONSTRAINT incidencias_tecnico_id_foreign FOREIGN KEY (tecnico_id) REFERENCES public.users(id);


-- Completed on 2025-02-17 21:03:17

--
-- PostgreSQL database dump complete
--

