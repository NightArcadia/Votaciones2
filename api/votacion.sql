CREATE DATABASE votacion;

CREATE TABLE `personas` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Correo` varchar(256) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Curp` varchar(18) NOT NULL,
  `Foto` longblob NOT NULL,
  `Fecha de registro` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;