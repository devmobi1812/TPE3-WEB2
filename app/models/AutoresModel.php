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
        public function getAll($sort, $filter){
            try{
                $conexion = $this->crearConexion();
                $conexion->beginTransaction();
                    $queryString="SELECT id, nombre, biografia, imagen FROM autores";
                    
                    $columnasPermitidas = ["id","nombre", "biografia", "imagen"];
                    $ordenPermitidos = ["ASC", "DESC"];
                    if($filter && in_array($filter->getField(), $columnasPermitidas)){
                        $queryString .= " WHERE {$filter->getField()} LIKE :filter";
                    }
        
                    if($sort && in_array($sort->getSortedField(), $columnasPermitidas) && in_array($sort->getOrder(), $ordenPermitidos)){
                        $queryString .= " ORDER BY " . $sort->getSortedField()." ".$sort->getOrder();
                    }

                    $query = $conexion -> prepare($queryString);

                    if ($filter && in_array($filter->getField(), $columnasPermitidas)) {
                        $query->bindValue(':filter', '%' . $filter->getFilter() . '%', PDO::PARAM_STR);
                    }

                    $query -> execute();
                    $autores = $query -> fetchAll(PDO::FETCH_OBJ);

                $conexion->commit();

                return $autores;
            }catch(PDOException $e){
                $conexion->rollback();
                error_log($e->getMessage());
            }
        }
                //Obtiene un número limitado de registros definido en el objeto page y los ordena y filtra de ser necesario
    public function allPaginated($page){
        try{
            $conexion = $this->crearConexion();
            $conexion->beginTransaction();

            $queryString = "SELECT id, nombre, biografia, imagen FROM autores";

            $columnasPermitidas = ["id","nombre", "biografia", "imagen"];
            $ordenPermitidos = ["ASC", "DESC"];
            if($page->getFilter() && in_array($page->getFilter()->getField(), $columnasPermitidas)){
                $queryString .= " WHERE {$page->getFilter()->getField()} LIKE :filter";
            }

            if($page->getSort() && in_array($page->getSort()->getSortedField(), $columnasPermitidas) && in_array($page->getSort()->getOrder(), $ordenPermitidos)){
                $queryString .= " ORDER BY " . $page->getSort()->getSortedField()." ".$page->getSort()->getOrder();
            }

            $queryString .= " LIMIT :page_size OFFSET :offset";

            $query = $conexion->prepare($queryString);

            if ($page->getFilter() && in_array($page->getFilter()->getField(), $columnasPermitidas)) {
                $query->bindValue(':filter', '%' . $page->getFilter()->getFilter() . '%', PDO::PARAM_STR);
            }
            $query->bindValue(':page_size', $page->getSize(), PDO::PARAM_INT);
            $query->bindValue(':offset', (($page->getNumber() - 1) * $page->getSize()), PDO::PARAM_INT);

            $query->execute();
            $conexion->commit();
            $page->setContents($query->fetchAll(PDO::FETCH_OBJ));
            
            return $page;
        }catch(Exception $e){
            $conexion->rollBack();
            error_log(message: $e->getMessage());
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

        // METODO NO IMPLEMENTADO NI MODIFICADO PARA ESTE TPE (EL 3)
        /*
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
        */

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