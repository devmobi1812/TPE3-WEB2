<?php
    require_once('Model.php');
    class AutoresModel extends Model{

        public function create($autor){
            try{
                $conexion = $this->crearConexion();
                $conexion->beginTransaction();
                    $query=$conexion->prepare('INSERT INTO `autores`(`id`, `nombre`, `biografia`, `imagen`) VALUES (NULL, ?,?,?)');
                    $query->execute([$autor->nombre, $autor->biografia, $autor->imagen]);
                $conexion->commit();
            }catch(PDOException $e){
                $conexion->rollback();
                error_log($e->getMessage());
            }
        }
        public function all(){
            try{
                $conexion = $this->crearConexion();
                $conexion->beginTransaction();
                    $query = $conexion -> prepare('SELECT id, nombre, biografia, imagen FROM autores ORDER BY nombre ASC');
                    $query -> execute();
                    $autores = $query -> fetchAll(PDO::FETCH_OBJ);

                $conexion->commit();

                return $autores;
            }catch(PDOException $e){
                $conexion->rollback();
                error_log($e->getMessage());
            }
        }

        public function find($id){
            try{
                $conexion = $this->crearConexion();
                $conexion->beginTransaction();
                    $query = $conexion->prepare('SELECT * FROM `autores` WHERE id=?');
                    $query->execute([$id]);
                    $autor=$query->fetch(PDO::FETCH_ASSOC);
                $conexion->commit();
                return $autor;
            }catch(PDOException $e){
                $conexion->rollback();
                error_log($e->getMessage());
            }
        }

        public function getLibrosAutor($id){
            try{
                $conexion = $this->crearConexion();
                $conexion->beginTransaction();
                    $query = $conexion->prepare('SELECT libros.isbn, libros.titulo, libros.fecha_de_publicacion, libros.editorial, libros.encuadernado, libros.sinopsis, autores.nombre AS autor_nombre, libros.nro_de_paginas 
                                                        FROM libros 
                                                        INNER JOIN autores ON libros.autor=autores.id
                                                        WHERE autores.id=?');
                    $query->execute([$id]);
                    $libros=$query->fetchAll((PDO::FETCH_OBJ));
                $conexion->commit();
                return $libros;
            }catch(PDOException $e){
                $conexion->rollback();
                error_log($e->getMessage());
            }
        }

        public function delete($id){
            try{
                $conexion = $this->crearConexion();
                $conexion->beginTransaction();
                    $query=$conexion->prepare('DELETE FROM autores WHERE id=?');
                    $query->execute([$id]);
                $conexion->commit();
            }catch(PDOException $e){
                $conexion->rollback();
                error_log($e->getMessage());
            }
        }

        public function update($autor){
            try{
                $conexion = $this->crearConexion();
                $conexion->beginTransaction();
                    $query=$conexion->prepare('UPDATE `autores` SET `nombre`=?,`biografia`=?,`imagen`=? WHERE id=?');
                    $query->execute([$autor->nombre, $autor->biografia, $autor->imagen, $autor->id]);
                $conexion->commit();
            }catch(PDOException $e){
                $conexion->rollback();
                error_log($e->getMessage());
            }
        }

    }
?>