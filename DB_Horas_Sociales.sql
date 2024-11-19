CREATE TABLE tipos_usuario (
    id_tipo_usuario SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
);


CREATE TABLE usuarios (
    id_usuario SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    id_tipo_usuario INTEGER REFERENCES tipos_usuario(id_tipo_usuario),
    estado_usuario BOOLEAN DEFAULT TRUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    token_recuperacion VARCHAR(255),
    token_expiracion TIMESTAMP
);

CREATE TABLE departamento (
    id_departamento SERIAL PRIMARY KEY,
    nombre_departamento VARCHAR(255) NOT NULL
);

CREATE TABLE carreras (
    id_carrera SERIAL PRIMARY KEY,
    id_departamento INTEGER REFERENCES departamento(id_departamento),
    codigo_carrera VARCHAR(10) UNIQUE NOT NULL,
    nombre_carrera VARCHAR(100) NOT NULL
);

CREATE TABLE estudiantes (
    id_estudiante SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES usuarios(id_usuario),
    carnet VARCHAR(10) UNIQUE NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    id_carrera INTEGER REFERENCES carreras(id_carrera),
    año_estudio INTEGER,
    telefono VARCHAR(20),
    direccion TEXT,
    curriculum_url VARCHAR(255)
);

CREATE TABLE sectores_industria (
    id_sector SERIAL PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT
);

CREATE TABLE empresas (
    id_empresa SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES usuarios(id_usuario),
    nombre VARCHAR(200) NOT NULL,
    id_sector INTEGER REFERENCES sectores_industria(id_sector),
    direccion TEXT,
    telefono VARCHAR(20),
    sitio_web VARCHAR(255),
    descripcion TEXT,
    logo_url VARCHAR(255),
    verificada BOOLEAN DEFAULT false
);

CREATE TABLE coordinadores (
    id_coordinador SERIAL PRIMARY KEY,
    id_usuario INTEGER REFERENCES usuarios(id_usuario),
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    id_departamento INTEGER REFERENCES departamento(id_departamento),
    telefono VARCHAR(20)
);

CREATE TABLE modalidades_trabajo (
    id_modalidad SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
);

CREATE TABLE tipos_proyecto (
    id_tipo_proyecto SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    numero_horas INTEGER NOT NULL
);

-- Tabla para colocar abierto, proceso, cerrada
CREATE TABLE estados_oferta (
    id_estado_oferta SERIAL PRIMARY KEY,
    nombre_estado VARCHAR(50) UNIQUE NOT NULL
);


-- La oferta seria mostrar la información de esta tabla
CREATE TABLE proyectos (
    id_proyecto SERIAL PRIMARY KEY,
    id_empresa INTEGER REFERENCES empresas(id_empresa),
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT NOT NULL,
    requisitos TEXT,
    id_estado_oferta INTEGER REFERENCES estados_oferta(id_estado_oferta),
    id_modalidad INTEGER REFERENCES modalidades_trabajo(id_modalidad),
    fecha_publicacion DATE DEFAULT CURRENT_DATE,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    fecha_limite_aplicacion DATE,
    estado_proyecto BOOLEAN DEFAULT true,
    cupos_disponibles INTEGER DEFAULT 1,
    id_tipo_proyecto INTEGER REFERENCES tipos_proyecto(id_tipo_proyecto),
    ubicacion TEXT,
    id_carrera INTEGER REFERENCES carreras(id_carrera)
);

CREATE TABLE estados_aplicacion (
    id_estado_aplicacion SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

CREATE TABLE aplicaciones (
    id_aplicacion SERIAL PRIMARY KEY,
    id_estudiante INTEGER REFERENCES estudiantes(id_estudiante),
    id_proyecto INTEGER REFERENCES proyectos(id_proyecto),
    fecha_aplicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_estado_aplicacion INTEGER REFERENCES estados_aplicacion(id_estado_aplicacion),
    comentarios_empresa TEXT,
    comentarios_estudiante TEXT,
    fecha_inicio DATE,
    fecha_fin DATE,
    horas_completadas INTEGER DEFAULT 0
);


CREATE TABLE estados_reporte (
    id_estado_reporte SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
);

CREATE TABLE reportes_progreso (
    id_reporte SERIAL PRIMARY KEY,
    id_aplicacion INTEGER REFERENCES aplicaciones(id_aplicacion),
    fecha_reporte DATE NOT NULL,
    horas_reportadas INTEGER NOT NULL,
    descripcion_actividades TEXT NOT NULL,
    id_estado_reporte INTEGER REFERENCES estados_reporte(id_estado_reporte),
    comentarios_supervisor TEXT
);

CREATE TABLE evidencias_reporte (
    id_evidencia SERIAL PRIMARY KEY,
    id_reporte INTEGER REFERENCES reportes_progreso(id_reporte),
    url_evidencia TEXT NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tipos_documento (
    id_tipo_documento SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
);

CREATE TABLE estados_documento (
    id_estado_documento SERIAL PRIMARY KEY,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
);

CREATE TABLE documentos (
    id_documento SERIAL PRIMARY KEY,
    id_aplicacion INTEGER REFERENCES aplicaciones(id_aplicacion),
    id_tipo_documento INTEGER REFERENCES tipos_documento(id_tipo_documento),
    url_documento VARCHAR(255) NOT NULL,
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_estado_documento INTEGER REFERENCES estados_documento(id_estado_documento)
);

CREATE INDEX idx_proyectos_estado ON proyectos(id_proyecto);
CREATE INDEX idx_aplicaciones_estado ON aplicaciones(id_estado_aplicacion);
CREATE INDEX idx_usuarios_email ON usuarios(email);
CREATE INDEX idx_estudiantes_carnet ON estudiantes(carnet);
CREATE INDEX idx_proyectos_empresa ON proyectos(id_empresa);
CREATE INDEX idx_proyectos_carrera ON proyectos(id_carrera);
CREATE INDEX idx_aplicaciones_estudiante ON aplicaciones(id_estudiante);
CREATE INDEX idx_documentos_aplicacion ON documentos(id_aplicacion);