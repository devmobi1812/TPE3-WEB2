<?php
require_once "app/models/Model.php";
class LibrosModel extends Model{

    //Obtiene todos los registros y los filtra y ordena de ser necesario
    public function all($sort, $filter){
        try{
            $connection = $this->crearConexion();
            $connection->beginTransaction();
            $queryString = "
            SELECT libros.*, autores.nombre AS autor_nombre, autores.biografia AS autor_biografia, autores.imagen AS autor_imagen
            FROM libros
            JOIN autores ON libros.autor = autores.id";

            $allowedColumns = [ "isbn", "titulo", "fecha_de_publicacion", "editorial", "encuadernado", "sinopsis", "autor", "nro_de_paginas", "autor_id", "autor_biografia", "autor_imagen", "auto.id", "autor.biografia", "autor.imagen"];
            $allowedOrders = ["ASC", "DESC"];
            
            if($filter && in_array($filter->getField(), $allowedColumns)){
                $queryString .= " WHERE {$filter->getField()} LIKE :filter";
            }

            if($sort && in_array($sort->getSortedField(), $allowedColumns) && in_array($sort->getOrder(), $allowedOrders)){
                $queryString .= " ORDER BY " . $sort->getSortedField()." ".$sort->getOrder();
            }

            $query = $connection->prepare($queryString);

            if ($filter && in_array($filter->getField(), $allowedColumns)) {
                $query->bindValue(':filter', '%' . $filter->getFilter() . '%', type: PDO::PARAM_STR);
            }
            $query->execute();
            $libros = $query->fetchAll(PDO::FETCH_OBJ);
            $connection->commit();
            return $libros;
        }catch(Exception $e){
            $connection->rollBack();
            error_log($e->getMessage());
        }
    }
        //Obtiene un número limitado de registros definido en el objeto page y los ordena y filtra de ser necesario
    public function allPaginated($page){
        try{
            $connection = $this->crearConexion();
            $connection->beginTransaction();

            $queryString = "
            SELECT libros.*, autores.nombre AS autor_nombre, autores.biografia AS autor_biografia, autores.imagen AS autor_imagen
            FROM libros
            JOIN autores ON libros.autor = autores.id";

            $allowedColumns = [ "isbn", "titulo", "fecha_de_publicacion", "editorial", "encuadernado", "sinopsis", "autor", "nro_de_paginas", "autor_id", "autor_biografia", "autor_imagen", "auto.id", "autor.biografia", "autor.imagen"];
            $allowedOrders = ["ASC", "DESC"];

            if($page->getFilter() && in_array($page->getFilter()->getField(), $allowedColumns)){
                $queryString .= " WHERE {$page->getFilter()->getField()} LIKE :filter";
            }

            if($page->getSort() && in_array($page->getSort()->getSortedField(), $allowedColumns) && in_array($page->getSort()->getOrder(), $allowedOrders)){
                $queryString .= " ORDER BY " . $page->getSort()->getSortedField()." ".$page->getSort()->getOrder();
            }

            $queryString .= " LIMIT :page_size OFFSET :offset";

            $query = $connection->prepare($queryString);

            if($page->getFilter() && in_array($page->getFilter()->getField(), $allowedColumns)){
                $query->bindValue(':filter', '%' . $page->getFilter()->getFilter() . '%', PDO::PARAM_STR);
            }
            $query->bindValue(':page_size', $page->getSize(), PDO::PARAM_INT);
            $query->bindValue(':offset', (($page->getNumber() - 1) * $page->getSize()), PDO::PARAM_INT);

            $query->execute();
            $connection->commit();
            $page->setContents($query->fetchAll(PDO::FETCH_OBJ));
            
            return $page;
        }catch(Exception $e){
            $connection->rollBack();
            error_log(message: $e->getMessage());
        }
    }
    public function find($isbn){
        try{
            $connection = $this->crearConexion();
            $connection->beginTransaction();
            $query = $connection->prepare("SELECT libros.*, autores.id AS autor_id, autores.nombre AS autor_nombre, autores.biografia AS autor_biografia, autores.imagen AS autor_imagen FROM libros JOIN autores ON libros.autor = autores.id WHERE libros.isbn=?");
            $query->execute([$isbn]);
            $libro = $query->fetch(PDO::FETCH_OBJ);
            $connection->commit();
            return $libro;
        }catch(Exception $e){
            $connection->rollBack();
            error_log($e->getMessage());
        }
    }
    public function create($libro){
        try{
            $connection = $this->crearConexion();
            $connection->beginTransaction();
                $query = $connection->prepare("INSERT INTO libros(isbn, titulo, fecha_de_publicacion, editorial, encuadernado, sinopsis, autor, nro_de_paginas) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
                $query->execute([
                    $libro->isbn,
                    $libro->titulo,
                    $libro->fecha_de_publicacion,
                    $libro->editorial,
                    $libro->encuadernado,
                    $libro->sinopsis,
                    $libro->autor,
                    $libro->nro_de_paginas,
                ]);
            $connection->commit();
        }catch(Exception $e){
            $connection->rollBack();
            error_log($e->getMessage());
        }
    }
    public function update($libro){
        try{
            $connection = $this->crearConexion();
            $connection->beginTransaction();
                $query = $connection->prepare("UPDATE libros SET isbn=?, titulo=?, fecha_de_publicacion=?, editorial=?, encuadernado=?, sinopsis=?, autor=?, nro_de_paginas=? WHERE libros.isbn = ?");
                $query->execute([
                    $libro->isbn,
                    $libro->titulo,
                    $libro->fecha_de_publicacion,
                    $libro->editorial,
                    $libro->encuadernado,
                    $libro->sinopsis,
                    $libro->autor,
                    $libro->nro_de_paginas,
                    $libro->old_isbn
                ]);
            $connection->commit();
        }catch(Exception $e){
            $connection->rollBack();
            error_log($e->getMessage());
        }
    }

    public function delete($isbn){
        try{
            $connection = $this->crearConexion();
            $connection->beginTransaction();
                $query = $connection->prepare("DELETE FROM libros WHERE isbn = ?");
                $query->execute([$isbn]);
            $connection->commit();
        }catch(Exception $e){
            $connection->rollBack();
            error_log($e->getMessage());
        }
    }
}
?>