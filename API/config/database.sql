CREATE DATABASE cafeteriakipling;

CREATE TABLE ciclo_escolar 
(
    id_ciclo_escolar serial,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    CONSTRAINT ciclo_escolar_pkey PRIMARY KEY (id_ciclo_escolar)
);

CREATE TABLE ciclo_escolar_actual
(
    id_ciclo_escolar_actual serial,
    id_ciclo_escolar integer NOT NULL,
    CONSTRAINT ciclo_escolar_actual_pkey PRIMARY KEY (id_ciclo_escolar_actual)
);

CREATE TYPE inscrio AS ENUM ('Inscrito', 'No inscrito');
CREATE TABLE alumno 
(
    id_alumno serial,
    nombre character varying(30) NOT NULL,
    apellido_paterno character varying(30) NOT NULL,
    apellido_materno character varying(30) NOT NULL,
    id_ciclo_escolar integer NOT NULL,
    salon character varying(2) NOT NULL,
    status inscrio,
    CONSTRAINT alumno_pkey PRIMARY KEY (id_alumno)
);

CREATE TABLE usuario
(
    id_usuario serial,
    usuario character varying(30) NOT NULL,
    password character varying(200) NOT NULL,
    nombre_completo character varying(100) NOT NULL,
    CONSTRAINT usuario_pkey PRIMARY KEY (id_usuario)
);

CREATE TABLE usuario_alumno
(
    id_usuario_alumno serial,
    id_alumno integer,
    id_usuario integer,
    CONSTRAINT usuario_alumno_pkey PRIMARY KEY (id_usuario_alumno)
)

CREATE TABLE menu
(
    id_menu serial,
    comida character varying(100) NOT NULL,
    agua character varying(100) NOT NULL,
    postre character varying(100) NOT NULL,
    precio float NOT NULL,
    fecha date NOT NULL,
    CONSTRAINT menu_pkey PRIMARY KEY (id_menu)
);

CREATE TABLE pedido 
(
    id_pedido serial NOT NULL,
    id_usuario integer NOT NULL,
    total float NOT NULL,
    pagado boolean NOT NULL,
    fecha date NOT NULL,
    CONSTRAINT pedido_pkey PRIMARY KEY (id_pedido)
);

CREATE TABLE detalle_pedido 
(
    id_detalle_pedido serial,
    id_pedido integer NOT NULL,
    id_menu integer NOT NULL,
    cantidad integer NOT NULL,
    CONSTRAINT detalle_pedido_pkey PRIMARY KEY (id_detalle_pedido)
);

CREATE TABLE menu 
(
    id_menu serial,
    comida character varying(100) NOT NULL,
    agua character varying(100) NOT NULL,
    postre character varying(100) NOT NULL,
    fecha date NOT NULL,
    precio float NOT NULL,
    CONSTRAINT menu_pkey PRIMARY KEY (id_menu)
);

CREATE TABLE fecha_semana 
(
    id_fecha_semana serial,
    fecha_inicio date NOT NULL,
    fecha_fin date NOT NULL,
    CONSTRAINT fecha_semana_pkey PRIMARY KEY (id_fecha_semana)
);

INSERT INTO usuario (id_alumno, usuario, password, nombreCompleto) VALUES(1, 'kipling', 'kipling123', 'Centro Educativo Kipling');



// Se excluye esta tabla