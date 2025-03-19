CREATE TABLE casetas 
(
    id integer primary key auto_increment,
    nombre varchar(50) Null,
    num_cerdos integer NULL ,
    peso_promedio FLOAT NULL,
    edad_promedio INTEGER,
    fecha_llegada DATETIME NULL,
    etapa_alimentacion enum('Iniciador','Desarrollo','Crecimiento','Finalizador') NULL,
    creado_en timestamp
);

CREATE TABLE corrales (
    id integer primary key auto_increment,
    numero_corral integer,
    num_cerdos integer NOT NULL ,
    caseta_id integer,
    CONSTRAINT fk_corrales_casetas FOREIGN KEY  (caseta_id) REFERENCES casetas (id) ON UPDATE CASCADE ON DELETE no ACTION

    );



