ALTER TABLE TipoMovimiento
ADD color varchar(15) NULL;

UPDATE TipoMovimiento
SET color = 'green'
WHERE idTipoMovimiento = 1;

UPDATE TipoMovimiento
SET color = 'red'
WHERE idTipoMovimiento = 2;

INSERT INTO CategoriaMovimiento (descripcion, idTipoMovimiento)
VALUES ('Recurrente', 1);

INSERT INTO CategoriaMovimiento (descripcion, idTipoMovimiento)
VALUES ('Extraordinario', 1);

INSERT INTO CategoriaMovimiento (descripcion, idTipoMovimiento)
VALUES ('Obligatorio', 2);

INSERT INTO CategoriaMovimiento (descripcion, idTipoMovimiento)
VALUES ('Necesario', 2);

INSERT INTO CategoriaMovimiento (descripcion, idTipoMovimiento)
VALUES ('Ocasional', 2);

INSERT INTO CategoriaMovimiento (descripcion, idTipoMovimiento)
VALUES ('Ahorro', 2);