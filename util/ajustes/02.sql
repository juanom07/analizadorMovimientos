INSERT INTO TipoMovimiento (descripcion)
VALUES ('Ingreso');

INSERT INTO TipoMovimiento (descripcion)
VALUES ('Egreso');

ALTER TABLE Movimiento
ALTER COLUMN fecha DATETIME NULL;