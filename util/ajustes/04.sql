ALTER TABLE movimientos.dbo.MotivoMovimiento 
ALTER COLUMN descripcion varchar(200) COLLATE Modern_Spanish_CI_AS NOT NULL;

INSERT INTO movimientos.dbo.MotivoMovimiento
(descripcion, idCategoriaMovimiento)
VALUES('TRANSFERENCIA POR ONLINE BANKING', 1);