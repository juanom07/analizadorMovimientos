CREATE TABLE TipoMovimiento(
	idTipoMovimiento INT IDENTITY(1,1),
	descripcion varchar(50) NOT NULL,

	CONSTRAINT PK_TipoMovimiento PRIMARY KEY (idTipoMovimiento)
);

CREATE TABLE CategoriaMovimiento(
	idCategoriaMovimiento INT IDENTITY(1,1),
	descripcion varchar(50) NOT NULL,
	idTipoMovimiento INT NULL,
	
	CONSTRAINT PK_CategoriaMovimiento PRIMARY KEY (idCategoriaMovimiento),
	CONSTRAINT FK_CategoriaMovimiento_TipoMovimiento FOREIGN KEY (idTipoMovimiento) REFERENCES TipoMovimiento (idTipoMovimiento)
);

CREATE TABLE MotivoMovimiento(
	idMotivoMovimiento INT IDENTITY(1,1),
	descripcion varchar(50) NOT NULL,
	idCategoriaMovimiento INT NULL,
	
	CONSTRAINT PK_MotivoMovimiento PRIMARY KEY (idMotivoMovimiento),
	CONSTRAINT FK_MotivoMovimiento_CategoriaMovimiento FOREIGN KEY (idCategoriaMovimiento) REFERENCES CategoriaMovimiento (idCategoriaMovimiento)
);

CREATE TABLE Movimiento(
	idMovimiento INT IDENTITY(1,1),
	descripcion varchar(250) NULL,
	fecha DATE NULL,
	valor FLOAT NULL,
	idMotivoMovimiento INT NOT NULL,
	
	CONSTRAINT PK_Movimiento PRIMARY KEY (idMovimiento),
	CONSTRAINT FK_Movimiento_MotivoMovimiento FOREIGN KEY (idMotivoMovimiento) REFERENCES MotivoMovimiento (idMotivoMovimiento)
);