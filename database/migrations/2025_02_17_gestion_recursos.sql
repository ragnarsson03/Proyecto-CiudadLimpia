-- Gestión de Personal
CREATE TABLE public.personal (
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
    user_id bigint NOT NULL,
    especialidad character varying(255) NOT NULL,
    disponibilidad character varying(50) NOT NULL,
    horario_laboral jsonb,
    habilidades text[],
    certificaciones jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT personal_disponibilidad_check CHECK (disponibilidad IN ('disponible', 'ocupado', 'vacaciones', 'baja')),
    CONSTRAINT personal_pkey PRIMARY KEY (id),
    CONSTRAINT personal_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id)
);

-- Gestión de Materiales
CREATE TABLE public.materiales (
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
    codigo character varying(50) NOT NULL UNIQUE,
    nombre character varying(255) NOT NULL,
    descripcion text,
    unidad_medida character varying(50) NOT NULL,
    cantidad_disponible numeric(10,2) NOT NULL,
    cantidad_minima numeric(10,2) NOT NULL,
    costo_unitario numeric(10,2) NOT NULL,
    ubicacion_almacen character varying(255),
    proveedor_principal character varying(255),
    categoria character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT materiales_pkey PRIMARY KEY (id)
);

-- Asignación de Recursos a Órdenes de Trabajo
CREATE TABLE public.recursos_orden_trabajo (
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
    orden_trabajo_id bigint NOT NULL,
    personal_id bigint,
    material_id bigint,
    cantidad_material numeric(10,2),
    horas_asignadas integer,
    costo_total numeric(10,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT recursos_orden_trabajo_pkey PRIMARY KEY (id),
    CONSTRAINT recursos_orden_trabajo_orden_trabajo_id_foreign FOREIGN KEY (orden_trabajo_id) REFERENCES public.ordenes_trabajo(id),
    CONSTRAINT recursos_orden_trabajo_personal_id_foreign FOREIGN KEY (personal_id) REFERENCES public.personal(id),
    CONSTRAINT recursos_orden_trabajo_material_id_foreign FOREIGN KEY (material_id) REFERENCES public.materiales(id)
);

-- Control de Presupuesto
CREATE TABLE public.presupuesto_anual (
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
    año integer NOT NULL,
    monto_total numeric(15,2) NOT NULL,
    monto_ejecutado numeric(15,2) DEFAULT 0,
    monto_comprometido numeric(15,2) DEFAULT 0,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT presupuesto_anual_pkey PRIMARY KEY (id),
    CONSTRAINT presupuesto_anual_año_unique UNIQUE (año)
);

CREATE TABLE public.partidas_presupuestarias (
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
    presupuesto_id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    descripcion text,
    monto_asignado numeric(15,2) NOT NULL,
    monto_ejecutado numeric(15,2) DEFAULT 0,
    tipo_infraestructura character varying(100),
    zona_geografica character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT partidas_presupuestarias_pkey PRIMARY KEY (id),
    CONSTRAINT partidas_presupuestarias_presupuesto_id_foreign FOREIGN KEY (presupuesto_id) REFERENCES public.presupuesto_anual(id)
);

-- Registro de Gastos
CREATE TABLE public.gastos (
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY,
    partida_id bigint NOT NULL,
    orden_trabajo_id bigint,
    concepto character varying(255) NOT NULL,
    monto numeric(10,2) NOT NULL,
    fecha date NOT NULL,
    tipo_gasto character varying(50) NOT NULL,
    comprobante_url character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT gastos_pkey PRIMARY KEY (id),
    CONSTRAINT gastos_partida_id_foreign FOREIGN KEY (partida_id) REFERENCES public.partidas_presupuestarias(id),
    CONSTRAINT gastos_orden_trabajo_id_foreign FOREIGN KEY (orden_trabajo_id) REFERENCES public.ordenes_trabajo(id)
);

-- Índices para mejorar el rendimiento
CREATE INDEX idx_personal_disponibilidad ON public.personal(disponibilidad);
CREATE INDEX idx_materiales_cantidad ON public.materiales(cantidad_disponible);
CREATE INDEX idx_gastos_fecha ON public.gastos(fecha);
CREATE INDEX idx_recursos_orden_trabajo ON public.recursos_orden_trabajo(orden_trabajo_id);
