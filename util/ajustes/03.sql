ALTER TABLE TipoMovimiento
ADD color varchar(15) NULL;

UPDATE TipoMovimiento
SET color = 'green'
WHERE idTipoMovimiento = 1;

UPDATE TipoMovimiento
SET color = 'red'
WHERE idTipoMovimiento = 2;