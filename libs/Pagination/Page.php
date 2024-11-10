<?php
class Page implements JsonSerializable{
    //Número de página
    private $number;
    //Tamaño de la página
    private $size;
    //Tipo de ordenamiento de la página
    private $sort;
    //Filtros aplicados a la pagina (para el query)
    private $filter;
    //Contenido de la página: datos obtenidos de la base de datos
    private $contents;
    public function __construct() {
    }

    public function getNumber() {
        return $this->number;
    }

    public function setNumber($number) {
        $this->number = $number;
    }

    public function getSize() {
        return $this->size;
    }

    public function setSize($size) {
        $this->size = $size;
    }

    public function getSort() {
        return $this->sort;
    }

    public function setSort($sort) {
        $this->sort = $sort;
    }

    public function getFilter() {
        return $this->filter;
    }

    public function setFilter($filter) {
        $this->filter = $filter;
    }

    public function getContents() {
        return $this->contents;
    }

    public function setContents($contents) {
        $this->contents = $contents;
    }
    // Esta función es necesaria para que se pueda convertir el objeto en json, debido a que los campos son privados
    public function jsonSerialize():mixed  {
        return [
            'number' => $this->number,
            'size' => $this->size,
            'sort' => $this->sort,
            'filter' => $this->filter,
            'contents' => $this->contents,
        ];
    }
} 