<?php
    require_once('config/config.php');
    class Model {

        protected $db;
        //Crea la conexión a la DB
        protected function crearConexion () {   
            try {
                $this->db = new PDO("mysql:host=".MYSQL_HOST .";dbname=".MYSQL_DB.";charset=utf8", MYSQL_USER, MYSQL_PASS);
                $this->deploy();
            } catch (\Throwable $th) {
                die($th);
            }

            return $this->db;
        }

        private function deploy() {
            $query = $this->db->query('SHOW TABLES');
            $tables = $query->fetchAll();
            if(count($tables) == 0) {
                $sql =<<<END
                    SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
                    START TRANSACTION;
                    SET time_zone = "+00:00";

                    --
                    -- Base de datos: `libreria`
                    --

                    -- --------------------------------------------------------

                    --
                    -- Estructura de tabla para la tabla `autores`
                    --

                    CREATE TABLE `autores` (
                    `id` int(11) NOT NULL,
                    `nombre` varchar(64) NOT NULL,
                    `biografia` varchar(2000) NOT NULL,
                    `imagen` varchar(255) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

                    -- --------------------------------------------------------

                    --
                    -- Estructura de tabla para la tabla `libros`
                    --

                    CREATE TABLE `libros` (
                    `isbn` bigint(13) NOT NULL,
                    `titulo` varchar(128) NOT NULL,
                    `fecha_de_publicacion` date NOT NULL,
                    `editorial` varchar(32) NOT NULL,
                    `encuadernado` enum('Tapa dura','Tapa blanda') NOT NULL,
                    `sinopsis` varchar(2000) NOT NULL,
                    `autor` int(11) NOT NULL,
                    `nro_de_paginas` smallint(6) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

                    -- --------------------------------------------------------

                    --
                    -- Estructura de tabla para la tabla `roles`
                    --

                    CREATE TABLE `roles` (
                    `id` int(11) NOT NULL,
                    `nombre` varchar(100) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

                    --
                    -- Volcado de datos para la tabla `roles`
                    --

                    INSERT INTO `roles` (`id`, `nombre`) VALUES
                    (1, 'ADMIN');

                    -- --------------------------------------------------------

                    --
                    -- Estructura de tabla para la tabla `usuarios`
                    --

                    CREATE TABLE `usuarios` (
                    `id` int(11) NOT NULL,
                    `nombre` varchar(50) NOT NULL,
                    `password` varchar(255) NOT NULL,
                    `rol` int(11) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

                    --
                    -- Volcado de datos para la tabla `usuarios`
                    --

                    INSERT INTO `usuarios` (`id`, `nombre`, `password`, `rol`) VALUES
                    (1, 'webadmin', '$2y$10\$Sq5b5Cp.ezwSpSMqlGaeae3zGjCmUXZZdFRYVPKSju37pwS3m7CBO', 1);

                    --
                    -- Índices para tablas volcadas
                    --

                    --
                    -- Indices de la tabla `autores`
                    --
                    ALTER TABLE `autores`
                    ADD PRIMARY KEY (`id`);

                    --
                    -- Indices de la tabla `libros`
                    --
                    ALTER TABLE `libros`
                    ADD PRIMARY KEY (`isbn`),
                    ADD KEY `idioma` (`autor`);

                    --
                    -- Indices de la tabla `roles`
                    --
                    ALTER TABLE `roles`
                    ADD PRIMARY KEY (`id`);

                    --
                    -- Indices de la tabla `usuarios`
                    --
                    ALTER TABLE `usuarios`
                    ADD PRIMARY KEY (`id`),
                    ADD UNIQUE KEY `nombre` (`nombre`),
                    ADD KEY `fk_rol` (`rol`);

                    --
                    -- AUTO_INCREMENT de las tablas volcadas
                    --

                    --
                    -- AUTO_INCREMENT de la tabla `autores`
                    --
                    ALTER TABLE `autores`
                    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

                    --
                    -- AUTO_INCREMENT de la tabla `roles`
                    --
                    ALTER TABLE `roles`
                    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

                    --
                    -- AUTO_INCREMENT de la tabla `usuarios`
                    --
                    ALTER TABLE `usuarios`
                    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

                    --
                    -- Restricciones para tablas volcadas
                    --

                    --
                    -- Filtros para la tabla `libros`
                    --
                    ALTER TABLE `libros`
                    ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`autor`) REFERENCES `autores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

                    --
                    -- Filtros para la tabla `usuarios`
                    --
                    ALTER TABLE `usuarios`
                    ADD CONSTRAINT `fk_rol` FOREIGN KEY (`rol`) REFERENCES `roles` (`id`);
                    COMMIT;

                    END;
            $this->db->query($sql);
            }
        }
    


    }

?>
