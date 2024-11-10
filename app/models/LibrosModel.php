<?php
require_once "app/models/Model.php";
class LibrosModel extends Model{
    public function all(){
        try{
            $connection = $this->crearConexion();
            $connection->beginTransaction();
                $query = $connection->prepare("SELECT libros.*, autores.nombre AS autor_nombre, autores.biografia AS autor_biografia, autores.imagen AS autor_imagen FROM libros JOIN autores ON libros.autor = autores.id");
                $query->execute();
                $libros = $query->fetchAll(PDO::FETCH_OBJ);
            $connection->commit();
            return $libros;
        }catch(Exception $e){
            $connection->rollBack();
            error_log($e->getMessage());
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